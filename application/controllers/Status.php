<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Status Controller
 *
 * @package		CodeIgniter
 * @subpackage	Controllers
 * @version	  1.0
 * @author		Fulgencio Sanmartín
 * @link		email@fulgenciosanmartin.com
*/
class Status extends CI_Controller {

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
    //this controller does not show anything by default,
    //redirect them to the login page
    redirect('auth/login', 'refresh');
	}
  /* end of function index */
  
  
	/**
	 * ajaxPermaURL Saves asynchronously (ajax) the URL 
   *              with map zoom and position
   *    Called from permalink.js   
	 *
	 * Maps to
	 * 		http://server/index.php/status/ajaxPermaURL/
	 *
   * @access	public
   * @param   zoom and position
   * @return	nothing, it saves the data into the Session (and the db) 
	 */
  public function ajaxPermaURL()
  {
    $_SESSION[ 'zoom' ] = $this->input->post('ps_zoom');
    $_SESSION[ 'lat'  ] = $this->input->post('ps_lat');
    $_SESSION[ 'lon'  ] = $this->input->post('ps_lon');
    
    $this->load->model( 'status_model', 'status' );
		$this->status->set_permaurl( $_SESSION[ 'zoom' ], $_SESSION[ 'lat' ], $_SESSION[ 'lon' ] );
	}
  /* end of function ajaxPermaURL */
  
	/**
	 * ajaxTsConfig Saves asynchronously (ajax) the timeseries list
   *    Called from ts-empty.js
	 *
	 * Maps to
	 * 		http://server/index.php/status/ajaxTsConfig/
	 *
   * @access	public
   * @param   list of timeseries
   * @return	nothing, it saves the data into the Session (and the db) 
	 */
  public function ajaxTSConfig()
  {
    $_SESSION[ 'ts_msbas' ] = $this->input->post('ts_msbas');
    $_SESSION[ 'ts_lat'  ]  = $this->input->post('lat');
    $_SESSION[ 'ts_lon'  ]  = $this->input->post('lon');
    $_SESSION[ 'ts_histo' ] = $this->input->post('ts_histo');
    $_SESSION[ 'ts_gnss' ]  = $this->input->post('ts_gnss');
    if( strlen( $_SESSION['ts_msbas'] ) > 2 ) 
      $_SESSION['ts_msbas_num']=substr_count($_SESSION['ts_msbas'], ",") + 1;
    else
      $_SESSION['ts_msbas_num']=0;
      
    if( strlen( $_SESSION['ts_histo'] ) > 2 ) 
      $_SESSION['ts_histo_num']=substr_count($_SESSION['ts_histo'], ",") + 1;
    else
      $_SESSION['ts_histo_num']=0;
      
    if( strlen( $_SESSION['ts_gnss'] ) > 2 ) 
      $_SESSION['ts_gnss_num']=substr_count($_SESSION['ts_gnss'], ",") + 1;
    else
      $_SESSION['ts_gnss_num']=0;
      
    $this->load->model( 'status_model', 'status' );
		$this->status->set_ts_config( 
      $_SESSION[ 'ts_msbas' ], $_SESSION[ 'ts_lat' ], $_SESSION[ 'ts_lon' ],
      $_SESSION[ 'ts_histo' ], $_SESSION[ 'ts_gnss' ] );
	}
  /* end of function ajaxTsConfig */
  
	/**
	 * ajaxLayerVisib Saves asynchronously (ajax) the layer visib/opac list
   *    Called from ts-empty.js
	 *
	 * Maps to
	 * 		http://server/index.php/status/ajaxLayerVisib/
	 *
   * @access	public
   * @param   list of timeseries
   * @return	nothing, it saves the data into the Session (and the db) 
	 */
  public function ajaxLayerVisib()
  {
    $id  = $this->input->post('id');
    $val = $this->input->post('val');
    $numBG = 4;
    if( $id < $numBG ) // base layer
    {
      $table = "user_config";
      if( is_numeric( $val ) ) 
      {
        $field = "bg" . ($id+1) . "_opacity";
        $val *= 100; // 0..100
      }
      else 
      { 
        $field = "bg" . ($id+1) . "_visible";
        if( $val == 'true' ) $val = 1; else $val = 0;
      }
    }
    else // regular layer
    {
      $table = "user_layers";
      $id -= $numBG; // order, without the base layers
      if( is_numeric( $val ) )
      { 
        $field = "config_opacity";
        $val *= 100; // 0..100
      }
      else 
      {
        $field = "config_visible";
        if( $val == 'true' ) $val = 1; else $val = 0;
      }
    }
    $this->load->model( 'status_model', 'status' );
		$this->status->set_layer_visib( $table, $field, $id, $val );
	}
  /* end of function ajaxLayerVisib */

  
	/**
	 * ajaxReset Reset the timeseries loaded, also in the status table
   *    Called from ts-empty.js
	 *
	 * Maps to
	 * 		http://server/index.php/status/ajaxReset/
	 *
   * @access	public
   * @param   list of timeseries
   * @return	nothing, it resets the data into the Session (and the db) 
	 */
  public function ajaxReset()
  {
    $_SESSION[ 'ts_msbas' ] = [];
    $_SESSION[ 'ts_lat'  ]  = [];
    $_SESSION[ 'ts_lon'  ]  = [];
    $_SESSION[ 'ts_histo' ] = [];
    $_SESSION[ 'ts_gnss' ]  = [];
    $_SESSION['ts_msbas_num'] = 0;
    $_SESSION['ts_histo_num'] = 0;
    $_SESSION['ts_gnss_num']  = 0;
      
    $this->load->model( 'status_model', 'status' );
		$this->status->reset_ts_config();
  }
  /* end of function ajaxReset */
  
}

/* End of file Status.php */
/* Location: ./application/controllers/Status.php */