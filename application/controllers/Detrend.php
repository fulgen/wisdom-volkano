<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Detrend Controller
 *
 * @package		CodeIgniter
 * @subpackage	Controllers
 * @version	  1.0
 * @author		Fulgencio Sanmartín
 * @link		email@fulgenciosanmartin.com
*/
class Detrend extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function Index()
	{
    $this->list_ts();
	}
  /* end of function index */

  /**
   * Detrends the given timeseries, and returns to the main page
   * @access	public
   * @param   the timeseries to be detrended, the min and max dates to calculate the trend
   * @return	nothing - reload the page
   */  
  public function list_ts()
  {
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}
    
    $this->load->model( 'detrend_model', 'detrend' );
    $this->data[ 'ts_list' ] = $this->detrend->get_all_detrended();
    
    $this->load->view( 'detrend_list', $this->data );
  }
  /* end of function list_ts */

  
  /**
   * Deletes the given detrended timeseries and returns to the list
   * @access	public
   * @param   the timeseries to be removed, from the table and the db
   * @return	nothing - reload the page
   */  
  public function delete( $id )
  {
    $this->load->model( 'detrend_model', 'detrend' );
    $this->detrend->delete( $id );

    redirect('detrend', 'refresh');
  }
  /* end of function delete */
  

  /**
   * Detrends the given timeseries, and returns to the main page
   * @access	public
   * @param   the timeseries to be detrended, the min and max dates to calculate the trend
   * @return	nothing - reload the page
   */  
  public function calculate()
  {
    $type = $this->input->post('detrendtype');  
    $name = $this->input->post('radiots'); 
    $minx = $this->input->post('minx'); 
    $maxx = $this->input->post('maxx'); 
    
    if( $type == 'msbas' )
    {
      // msbas format is: 'name[lat,lon]'
      $ts    = substr( $name, 0, strpos( $name, '[' ) );
      $coord = substr( $name, strpos( $name, '[' ) );
      $lat   = substr( $coord, 1, strpos( $coord, ',' ) - 1 );
      $lon   = substr( $coord, strpos( $coord, ',' ) + 1 );
      $lon   = substr( $lon, 0, strlen( $lon ) - 1 );
      $sub   = "";
    }
    else if( $type == 'gnss' )
    {
      // gnss format is:  'name[EW]'
      $ts  = substr( $name, 0, strpos( $name, '[' ) );
      $sub = substr( $name, strpos( $name, '[' ) + 1, 2 );
      $lat = $lon = 0;
    }
    else  // nothing to do, return
    {
      log_message( 'error', 'app/controller/detrend/E-080 Type of timeseries incorrect, not msbas or gnss: ' . $type );
      redirect( site_url(), 'refresh' );
    }
    
    // 0- check that this ts is not already detrended (if it is, return!)
    $this->load->model( 'Detrend_model', 'detrend_model' );
    if( $this->detrend_model->detrend_exists( $type, $ts, $sub, $lat, $lon ) )
    {
      log_message( 'error', 'app/controller/detrend/E-079 Detrend series already exists, skipping.' );
      redirect( site_url(), 'refresh' );
    }
      
    // 1- get the original file name
    $filepath = $this->detrend_model->get_detrend_filepath( $type, $ts, $sub, $lat, $lon, 'orig' );

    // 2- open original file and load values into array
    $ar_file = file( trim( $filepath ) );
    $xorig = array(); 
    $yorig = array();
    foreach( $ar_file as $line )
    {
      // msbas: 2003.26849	-0.49725160002708
      // gnss: 2009.34109589	1.6 2.3 -7.7 0.234611 0.181749 3.24396
      $element = explode( "\t", $line );
      $xorig[] = $element[ 0 ]; // date.tick for both!
      if( $type == 'msbas' )
        $yorig[] = $element[ 1 ];
      else
      {
        $val = explode( " ", $element[ 1 ] );
        switch( $sub )
        {
          case 'EW': $yorig[] = $val[ 0 ]; break;
          case 'NS': $yorig[] = $val[ 1 ]; break;
          case 'UP': $yorig[] = $val[ 2 ]; break;
        }
      }  
    }
      
    // 3- extract zoom from the data
    $xzoom = array(); 
    $yzoom = array();
    $i = 0; $ini = $end = 0;
    // echo "<br>looking for minx: " . $minx;
    while( $i < count( $xorig ) && $ini == 0 )
    {
      if( $this->tick2Date( $xorig[$i] ) > $minx )
      {
        // echo "<br>encontrado ini(" . $i . "): " . $xorig[$i];
        $ini = $i;
        break;
      }
      $i ++;
    }
    if( $i > 0 ) $i --;
    // echo "<br>looking for maxx: " . $maxx;
    while( $i < count( $xorig ) && $end == 0 )
    {
      $xzoom[] = $xorig[ $i ];
      $yzoom[] = $yorig[ $i ];
      if( $this->tick2Date( $xorig[$i] ) > $maxx )
      {
        // echo "<br>encontrado fin(" . $i . "): " . $xorig[$i];
        $end = $i;
        break;
      }  
      $i ++;
    }
    // echo "<br>type: " . $type . " --- sub: " . $sub . " --- size x: " . count( $xorig ) . " --- size y: " . count( $yorig ) . " <br/>x[0]: " . $xorig[0] . " -- y[0]: " . $yorig[1];
    // echo "<br>ini: " . $ini . " --- fin: " . $end . " --- size xzoom: " . count( $xzoom ) . " --- size yzoom: " . count( $yzoom ) . " <br/>xzoom[0]: " . $xzoom[0] . " -- yzoom[0]: " . $yzoom[1];
    // exit();
    
    // 4- detrend
    $ar_param  = $this->linear_regression( $xzoom, $yzoom );
    $yexpected = $this->trend_values( $xorig, $ar_param['m'], $ar_param['b'] );
    $ydetrend  = $this->to_detrend( $yorig, $yexpected );
    // echo "<pre>"; print_r( $ydetrend ); echo "</pre>"; exit(); 

    // 5- name for detrended file 
    $new_filename = $this->detrend_model->get_detrend_filepath( $type, $ts, $sub, $lat, $lon, 'detrend' );
    
    // 6- save the values to a file
    $this->detrend_model->create_file_ts( $new_filename, $type, $xorig, $ydetrend, $sub );
    
    // 7- save the new detrended timeseries to the table
	  $this->detrend_model->set_detrend_new( $ts, $type, $sub, $lat, $lon );
    
    // 8- return to main page
    redirect( site_url(), 'refresh' );
  }
  /* end of function calculate */


  /**
   * linear regression function
   *   Example Usage: 
   *   var_dump( linear_regression(array(1, 3, 4, 7), array(1.5, 1.6, 2.1, 3.0)) );
   * https://richardathome.wordpress.com/2006/01/25/a-php-linear-regression-function/  
   * @param $x array x-coords
   * @param $y array y-coords
   * @returns array() m=>slope, b=>intercept
   */
  private function linear_regression($x, $y) 
  {
    // calculate number points
    $n = count($x);

    if( $n == 0 || count($y) == 0 ) 
    {
      log_message( 'error', "app/helper/trend/E-076 Error: empty array." );
      return false;
    }
    
    // ensure both arrays of points are the same size
    if ($n != count($y)) 
    {
      log_message( 'error', "app/helper/trend/E-073 Error: Number of values in both arrays do not match." );
      return false;
    }

    // calculate sums
    $x_sum = array_sum($x);
    $y_sum = array_sum($y);

    $xx_sum = 0;
    $xy_sum = 0;
    
    for($i = 0; $i < $n; $i++) 
    {
      $xy_sum+=($x[$i]*$y[$i]);
      $xx_sum+=($x[$i]*$x[$i]);
    }
    
    // calculate slope
    if( (($n * $xx_sum) - ($x_sum * $x_sum)) == 0 )
    {
      log_message( 'error', "app/helper/trend/E-075 Error: zero division." );
      return;
    }
    else   
      $m = (($n * $xy_sum) - ($x_sum * $y_sum)) / (($n * $xx_sum) - ($x_sum * $x_sum));
    
    // calculate intercept
    $b = ($y_sum - ($m * $x_sum)) / $n;
      
    // return result
    return array("m"=>$m, "b"=>$b);
  } // end function linear_regrestion


  /**
   * trend_values function, calculates trended y values based on linear trend and x 
   *   Example Usage: var_dump( trend_values(array(1, 3, 4, 7), 0.2, 4 );
   * @param $x array x-coords
   * @param m=slope, b=intercept
   * @returns $y array y-coords
   */
  private function trend_values( $x, $m, $b )
  {
    if( count($x) == 0 ) 
    {
      log_message( 'error', "app/helper/trend/E-077 Error: empty array." );
      return false;
    }
    
    for( $i = 0; $i < count( $x ); $i++ )
      $ytrend[$i] = ( $x[$i] * $m ) + $b;
      
    return $ytrend;
  } // end function trend_values


  /**
   * detrend function, removes trend from y values  
   *   Example Usage: 
   *   var_dump( detrend(array(1.5,1.6,2.1,3.0), trend_values(array(1,3,4,7),0.2,4));
   * @param $yreal array, y-coords initial, offset
   * @param $ytrend array y-coords trend
   * @returns $ydetrend array y-coords detrended
   */
  private function to_detrend( $yreal, $ytrend, $offset = 0 )
  {
    // ensure both arrays of points are the same size
    if( count($yreal) != count($ytrend) ) 
    {
      log_message( 'error', "app/helper/trend/E-074 Error: Number of values in both arrays do not match." );
    }
    
    // calculate the detrend: real value minus the theoretical trend, plus the initial
    for( $i = 0; $i < count( $yreal ); $i++ )
      $ydetrend[$i] = $yreal[$i] - $ytrend[$i] + $offset;
      
    return $ydetrend;
  } // end function detrend
  
  
  // format: 2005.40301
  // Convert date: 0.0000 = 1st Jan, 00:00:00; 0.9999 = 31st Dec, 23:59:59
  private function tick2Date( $yeartick ) 
  {
    $year = substr( $yeartick,  0, strpos( $yeartick, "." ) );
    $tick = $yeartick - $year; 

    $dayofyear = round( $tick * 365 ); 
    if( $dayofyear <= 31 ) { // Jan
      $month = '1'; $day = $dayofyear;
    } else if( $dayofyear <= 59 ) { // Feb
      $month = '2'; $day = $dayofyear - 31;
    } else if( $dayofyear <= 90 ) { // Mar
      $month = '3'; $day = $dayofyear - 59;
    } else if( $dayofyear <= 120 ) { // Apr
      $month = '4'; $day = $dayofyear - 90;
    } else if( $dayofyear <= 151 ) { // May
      $month = '5'; $day = $dayofyear - 120;
    } else if( $dayofyear <= 181 ) { // Jun
      $month = '6'; $day = $dayofyear - 151;
    } else if( $dayofyear <= 212 ) { // Jul
      $month = '7'; $day = $dayofyear - 181;
    } else if( $dayofyear <= 243 ) { // Aug
      $month = '8'; $day = $dayofyear - 212;
    } else if( $dayofyear <= 273 ) { // Sep
      $month = '9'; $day = $dayofyear - 243;
    } else if( $dayofyear <= 304 ) { // Oct
      $month = '10'; $day = $dayofyear - 273;
    } else if( $dayofyear <= 334 ) { // Nov
      $month = '11'; $day = $dayofyear - 304;
    } else { // Dec
      $month = '12'; $day = $dayofyear - 334; 
    }
    $dat = date( "Y-m-d", mktime( 0, 0, 0, $month, $day, $year ) ); 
    return $dat;
  }
  
  
}

/* End of file Detrend.php */
/* Location: ./application/controllers/Detrend.php */