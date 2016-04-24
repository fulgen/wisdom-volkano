<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Ts_folder Model (timeseries folder)
 *
 * @package		CodeIgniter
 * @subpackage	Models
 * @version	  1.0
 * @author		Fulgencio Sanmartín
 * @link		email@fulgenciosanmartin.com
*/
class Ts_folder_model extends CI_Model
{
  /**
   * Reads Timeseries folder ($config['folder_TYPE_ts']) and returns a list of the files
   *
   * @access	public
   * @param   type timeseries
   * @return	array with timeseries (files) available
  */
 	function get_folder_ts( $type )
  {
    $this->load->helper('directory');  
    $depth = 1;
    $dir = $this->config->item( 'folder_' . $type );
    // echo ' is dir? ' . $dir;
    if( is_dir( $dir ) )
    {
      $ar_map = directory_map( $this->config->item( 'folder_' . $type ), $depth ); 
    }
    else
    {
      $err = 'app/model/ts_folder/E-047 Error: No timeseries folders available from ' . $dir;
      log_message( 'error', $err );
      show_error( $err );
      return false;
    }
    return $ar_map;
  }
  /* end of get_folder_ts */

  /**
   * Reads a folder and returns the n-th file name in it 
   *
   * @access	public
   * @param   folder, raster/ts, and n-th file to get (by default, the first)
   * @return	file name
  */
 	function get_filename_msbas( $folder, $type = 'ras', $ord = 0 )
  {
    $this->load->helper('directory');  
    $depth = 1;
    $where = $this->config->item( 'folder_msbas' ) . $folder . $this->config->item( 'folder_msbas_' . $type );
    $ar_map = directory_map( $where, $depth ); 
    if( is_dir( $where . $ar_map[ $ord ] ) ) // it is the detrend folder, skip to next
      return $ar_map[ $ord + 1 ];
    else 
      return $ar_map[ $ord ];
  }
  /* end of get_filename_msbas */

  /**
   * finds the position of a regexp; found in
   *   http://forums.devshed.com/php-development-5/regex-version-strpos-711256.html
   *
   * @access	public
   * @param   the string to search in, and the regexp
   * @return	the position, or -1 if not found
  */
  public function preg_pos( $subject, $regexp )
  {
      if( preg_match( '/^(.*?)'.$regexp.'/', $subject, $matches ) ) 
        return strlen( $matches[ 1 ] );
      else 
        return -1;
  }    
  /* end of preg_pos */
  

  /** 
    * create_file_ts  Function to create a file msbas timeseries, 
    * 
    * @param:  the file name already constructed, and the data to write
    * @return: void
    */
  public function create_file_ts( $file, $new_ts )  
  {
    // 1. open file to write
    $f = fopen( $file, "w" );
    // 2. loop to write the data (length array) 
    $first = 0;
    foreach( $new_ts as $key => $value )
    {
      if( $first == 0 )
        $first ++ ;
      else 
        fwrite( $f, "\n" );
      //    2.1. convert date to ticks
      $tick = $this->date2tick( $key );
      //    2.2. line sample: 2003.04252	-8.14710397571605
      $line = $tick . "\t" . $value;
      // echo " line $line ";
      fwrite( $f, $line );
    }    
    // 3. close file
    fclose( $f );
  }
  /* end of function create_file_ts */
  
  
  /** 
    * date2tick  Function to write msbas timeseries, 
    *            inverse of js/ts_empty.js/tick2Date 
    * 
    * @param: YYYYMMDD 0.0000 = 1st Jan, 00:00:00; 0.9999 = 31st Dec, 23:59:59
    * @return: 2005.40301
    */
  private function date2tick( $date ) 
  {
    $year  = substr( $date, 0, 4 );
    $month = substr( $date, 4, 2 );
    $day   = substr( $date, 6, 2 );
    $d1    = $year . "-" . $month . "-" . $day;
    $d2    = strtotime( $d1 );
    $dayoftheyear = date( 'z', $d2 );
    $tick = round( $dayoftheyear / 365, 5 ); 
    // echo " date: $d1 $d2  día del año: $dayoftheyear > $tick \n";

    if( $tick >= 1.0 ) $tick = 0.99999; 
    $tick = substr( $tick, 1 ); // remove the first zero
    $tick = str_pad( $tick, 6, "0" ); // complete with zeroes
    return $year . $tick; 
  }
  /* end of function date2tick */
  
	/**
	 * get_ts_file_name Constructs the file name with the ts default + col,row
	 *
   * @access	public
   * @param   the coordinates and the ts, and if it shall return uri or disk link
   * @return	the file name
	 */
  public function get_ts_file_name( $msbas, $type, $xcol, $yrow )
  {
    if( $type == "uri" )
      $file = base_url( "assets/data/msbas/" ) . "/" 
            . trim( $msbas->ts_file ) 
            . $this->config->item( 'uri_msbas_ts' ) 
            . substr( trim( $msbas->ts_file_ts ), 0, $msbas->ts_file_ts_ini_coord );    
    else // "disk" 
      $file = $this->config->item( 'folder_msbas' )
            . trim( $msbas->ts_file ) 
            . $this->config->item( 'folder_msbas_ts' ) 
            . substr( trim( $msbas->ts_file_ts ), 0, $msbas->ts_file_ts_ini_coord );

    // assuming pixels with 3 numbers, range 001..999, format XXX_YYY
    // TBD: use ts_folder->preg_pos regexp
    $file = $file . $xcol . "_" . $yrow;
    $file = $file . substr( trim( $msbas->ts_file_ts ), $msbas->ts_file_ts_ini_coord + 7 ); // until the end
    // echo "looking for file $file \n";
    return $file;
  }
  
}

/* End of file Ts_folder_model.php */
/* Location: ./application/models/Ts_folder_model.php */