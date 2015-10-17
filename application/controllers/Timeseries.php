<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Timeseries extends CI_Controller {
	var $ts_name = "";
  var $ts_types = "msbas,histogram"; // models available; events is fixed as background
  var $ts_file_ts = ""; // ex VVP_ML_1_Pixel_FullSerie_238_370test_EW_Detrended.dat
  // the following are only needed for msbas
  var $ts_file_ts_ini_coord = 0; // ex 26 regexp above to NNN_NNN
  var $ts_file_raster = ""; // ex 20030116e.bin.hdr
  var $ts_file_raster_ini_date = 0; // ex 0 regexp above to YYMMDDDD
  var $ts_coord_lat_top = -1.1; // negative is South, positive is North
  var $ts_coord_lon_left = 29.0; // negative is west Greenwich, positive is east
  var $ts_coord_lat_inc = 0.0008333333; // positive increment in degrees per pixel
  var $ts_coord_lon_inc = 0.0008333333; // positive increment in degrees per pixel
  // and this only for seism-counting
  var $ts_seism_station = ""; // ex KBB
  
	function __construct()
	{
		parent::__construct();
		$this->load->database();
 		$this->load->library( array( 'ion_auth', 'form_validation' ) );
    $this->load->helper( array( 'menu', 'form' ) );
  }
	
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/descarga
	 *	- or -
	 * 		http://example.com/index.php/descarga/index
	 * 
	 * Controller to be used internally, for handling layers.
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/layer/<method_name>
	 * @see http://codeigniter.com/user_guide/layer/urls.html
	 */
	public function index()
	{
		if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		elseif (!$this->ion_auth->is_admin()) //remove this elseif if you want to enable this for non-admins
		{
			//redirect them to the home page because they must be an administrator to view this
			return show_error('You must be an administrator to view this page.');
		}
		else
		{
      $this->load->model( 'timeseries_model', 'ts' );
      $this->load->model( 'userts_model', 'userts' );
      
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
      
			//list the ts
			$this->data[ 'ts' ] = $this->ts->get_all_timeseries();
      // and for each ts, list the granted users
			foreach( $this->data[ 'ts' ] as $k => $ts )
			{
				$this->data[ 'ts' ][ $k ]->users = $this->userts->get_ts_users( $ts->ts_name );
			}   
			$this->load->view( 'auth/timeseries_list', $this->data );
		}
	}
  /* end of function index */

  
  /**
   * Create a timeseries in the system
   *
   * @access	public
   * @param   none
   * @return	calls the create timeseries view
  */  
  public function create_ts()
  {
		if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		elseif (!$this->ion_auth->is_admin()) //remove this elseif if you want to enable this for non-admins
		{
			//redirect them to the home page because they must be an administrator to view this
			return show_error('You must be an administrator to view this page.');
		}
		else
		{   
      //rules to validate form input
      $this->form_validation->set_rules('ts_name', 'Timeseries name', 'required'); 
      // |min_length[5]|max_length[250]|alpha_numeric');
      // http://www.codeigniter.com/userguide3/libraries/form_validation.html#rule-reference
      $this->form_validation->set_rules('ts_type', 'Timeseries type', 'required|in_list[' . $this->ts_types . ']' );
      // ts_description is optional
      $this->form_validation->set_rules('ts_file_raster_ini_date', 'Raster file date initial position', 'required|is_natural', array( 'is_natural' => 'The %s should be a natural number.'
        ) );
      $this->form_validation->set_rules('ts_coord_lat_top', 'Raster file top (latitude) coordinate', 'required|callback_decimal_numeric', array( 'callback_decimal_numeric' => 'The %s should be a number.' ) ); 
      $this->form_validation->set_rules('ts_coord_lon_left', 'Raster file left (longitude) coordinate', 'required|callback_decimal_numeric', array( 'callback_decimal_numeric' => 'The %s should be a number.' ) ); 
      $this->form_validation->set_rules('ts_coord_lat_inc', 'Raster file latitude coordinate increment', 'required|callback_decimal_numeric', array( 'callback_decimal_numeric' => 'The %s should be a number.' ) ); 
      $this->form_validation->set_rules('ts_coord_lon_inc', 'Raster file longitude coordinate increment', 'required|callback_decimal_numeric', array( 'callback_decimal_numeric' => 'The %s should be a number.' ) );           
      $this->form_validation->set_rules('ts_file_ts_ini_coord', 'Timeseries file coords initial position', 'required|is_natural', array( 'callback_decimal_numeric' => 'The %s should be a natural number.' ) );
      $this->form_validation->set_rules('ts_seism_station', 'Seism station', 'max_length[20]', array( 'max_length[20]' => 'The %s should be maximum 20 chars long.' ) );

      $this->load->model( 'timeseries_model', 'ts' );
      $this->load->model( 'userts_model', 'userts' );
      // Validate the form
      if( $this->form_validation->run() == true )
      {
        $data[ 'ts_name' ] = $this->input->post( 'ts_name' );
        $data[ 'ts_type' ] = $this->input->post( 'ts_type' );
        $data[ 'ts_file' ] = trim( $this->input->post( 'ts_file' ) ); // folder for msbas, file for histogram 
        $data[ 'ts_description' ] = $this->input->post( 'ts_description' );
        $data[ 'ts_file_raster' ] = $this->input->post( 'ts_file_raster' );
        $data[ 'ts_file_raster_ini_date' ] = $this->input->post( 'ts_file_raster_ini_date' );
        $data[ 'ts_file_ts' ] = $this->input->post( 'ts_file_ts' );
        $data[ 'ts_file_ts_ini_coord' ] = $this->input->post( 'ts_file_ts_ini_coord' );
        $data[ 'ts_coord_lat_top' ] = $this->input->post( 'ts_coord_lat_top' );
        $data[ 'ts_coord_lon_left' ] = $this->input->post( 'ts_coord_lon_left' );
        $data[ 'ts_coord_lat_inc' ] = $this->input->post( 'ts_coord_lat_inc' );
        $data[ 'ts_coord_lon_inc' ] = $this->input->post( 'ts_coord_lon_inc' );
        $data[ 'ts_seism_station' ] = $this->input->post( 'ts_seism_station' );
        
        $this->ts->set_timeseries_new( $data );
        
        // get all users to grant the ts
        if( $this->input->post( 'grant' ) ) 
          foreach( $this->input->post( 'grant' ) as $user_email )
            $this->userts->set_userts_new( $user_email, $data['ts_name'] );
        // by default, the admin grants herself the ts 
        $this->userts->set_userts_new( $this->session->userdata( 'email' ), $data['ts_name'] );
        
   			redirect( "timeseries", 'refresh' );
      }
      else // Show the form
      {
        // First time, we show by default the msbas form
        $ts_type = "msbas";
      
        // 1. load folders 
        $this->load->model( 'Ts_folder_model', 'ts_folder' );
        $ar_result = $this->ts_folder->get_folder_ts( $ts_type );
        if( ! $ar_result )
        {
          $err = 'app/controller/Timeseries/E-029 Error: No timeseries available from the folder ' . $this->config->item( 'folder_' . $ts_type );
          log_message( 'error', $err );
          return false;
        }
        else
        {
          foreach( $ar_result as $folder )
          {
            // remove the trailing "/" in folders
            $ts = substr( $folder, 0, strlen( $folder ) - 1 );
            $this->data[ 'ts' ][] = $ts;
          }
          
          // 2. load files in first msbas folder 
          $ts_file = $this->data[ 'ts' ][ 0 ];
          
          // 3.1 read first file raster header 
          $raster = $this->ts_folder->get_filename_msbas( $ts_file, 'ras', 0 );
          $this->data[ 'ts_file_raster' ] = $raster;
          $regexp = '[0-9]{8}';
          if( ( $pos = $this->ts_folder->preg_pos( $raster, $regexp ) ) >= 0 )
          {
            $this->data[ 'ts_file_raster_ini_date' ] = $pos;
            $this->data[ 'ts_file_raster_ex_date' ] = substr( $raster, $pos, 8 );
          }
          else
          {
            $this->data[ 'ts_file_raster_ini_date' ] = 'Error: regular expression ' . $regexp . ' not found in ' . $raster;
          }  
          
          // 3.2 load coords 
          $file = array( 0 => ( $this->config->item( 'folder_msbas' ) . $ts_file . $this->config->item( 'folder_msbas_ras' ) . $raster ) );
       		$this->load->library( 'EnviHeader', $file );          
          $enviheader = new EnviHeader( $file );
          $enviheader->read_header();
          $this->data[ 'ts_coord_lat_top' ]  = $enviheader->get_value( 'mapLat' ); // -1.1;
          $this->data[ 'ts_coord_lon_left' ] = $enviheader->get_value( 'mapLon' ); // 29.0;
          $this->data[ 'ts_coord_lat_inc' ]  = $enviheader->get_value( 'mapLatInc' ); // 0.0008333333;
          $this->data[ 'ts_coord_lon_inc' ]  = $enviheader->get_value( 'mapLonInc' ); // 0.0008333333;
          //var_dump( $this->data );
          
          // 3.3 read first file timeseries and load coord position in filename
          $tsfile = $this->ts_folder->get_filename_msbas( $ts_file, 'ts', 0 );
          $this->data[ 'ts_file_ts' ] = $tsfile;
          $regexp = '[0-9]{3}_[0-9]{3}';
          if( ( $pos = $this->ts_folder->preg_pos( $tsfile, $regexp ) ) >= 0 )
          {
            $this->data[ 'ts_file_ts_ini_coord' ] = $pos;
            $this->data[ 'ts_file_ts_ex_coord' ] = substr( $tsfile, $pos, 7 );
          }
          else
          {
            $this->data[ 'ts_file_ts_ini_coord' ] = 'Error: regular expression ' . $regexp . ' not found in ' . $ts_file;
          }
          
          // and get all enabled users that can be granted the timeseries
          $this->data[ 'users' ] = $this->userts->get_all_users();
          
          //set the flash data error message if there is one
          $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
          $this->data['ts_types'] = $this->ts_types;
        
          $this->load->view( 'auth/create_timeseries', $this->data );
        }  
      }
    }
  }
  /* end of function create_ts */
  

  /**
   * Delete a timeseries from the system 
   *
   * @access	public
   * @param   name of the timeseries
   * @return	deletes the timeseries, and in the db in cascade, all grants in user_timeseries are removed as well
  */  
  public function del_ts( $ts_id )
  {
		if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		elseif (!$this->ion_auth->is_admin()) //remove this elseif if you want to enable this for non-admins
		{
			//redirect them to the home page because they must be an administrator to view this
			return show_error('You must be an administrator to view this page.');
		}
		else
		{
      $this->load->model( 'timeseries_model', 'ts' );
      $this->ts->del_timeseries( $ts_id );
      redirect( site_url() . '/timeseries', 'refresh' );
    }
  }
  /* end of function del_layer */
  
  
  
  /**
   * Function to validate the coordinates (type float/double). The decimal rule in codeigniter rejects 29, only accepts 29.0
   *
   * @access	public // private does not work
   * @param   the string with the number
   * @return	whether it is a numeric float/double/integer or not
  */  
  public function decimal_numeric($str)
  {
      if( ! is_numeric( $str ) )
      {
          $this->form_validation->set_message('decimal_numeric', 'The ' . $str . ' field must be a number.');
          return FALSE;
      }
      else
      {
          return TRUE;
      }
  }  
  /* end of function decimal_numeric */
  
  /**
   * Function called via ajax: loads the file names from a given timeseries-type
   *
   * @access	public//ajax
   * @param   ts_type 
   * @return	[not return but echo] reads the folder from the model and ECHOES its contents
  */  
  public function load_folder( $ts_type = "msbas" )
  {
    $this->load->model( 'Ts_folder_model', 'ts_folder' );
    $result = $this->ts_folder->get_folder_ts( $ts_type );
    if( ! $result )
    {
      $err = 'app/controller/Timeseries/E-030 Error: No timeseries available from the folder (via ajax) - type : ' . $ts_type;
      log_message( 'error', $err );
      return false;
    }
    else
    {
      $str = ""; $i = 0;
      foreach( $result as $tss )
      {
          if( $ts_type == "msbas" ) // removing the trailing \
            $ts = substr( $tss, 0, strlen( $tss ) - 1 );
          else
            $ts = $tss;
          $ar->data[ $i++ ] = $ts;
      }
      echo json_encode( $ar ); // ajax not to be used as php return function
    }
  }
  /* end of function load_folder */

  /**
   * Function called via ajax: loads the raster and ts from msbas folder
   *
   * @access	public//ajax
   * @param   name of msbas folder 
   * @return	[not return but echo] reads the two files and ECHOES its needed contents in json TBD!!!
  */  
  public function load_msbas_files( $folder )
  {
    $this->load->model( 'Ts_folder_model', 'ts_folder' );
    $resRaster = $this->ts_folder->get_filename_msbas( $folder, 'ras' );
    $resTs = $this->ts_folder->get_filename_msbas( $folder, 'ts' );
    
    if( ! $resRaster ) 
    {
      $err = 'app/controller/Timeseries/E-037 Error: No raster available from the folder (via ajax): ' . $folder;
      log_message( 'error', $err );
      return $err;
    }
    else
    {
      $regexp = '[0-9]{8}';
      if( ( $pos = $this->ts_folder->preg_pos( $resRaster, $regexp ) ) >= 0 )
      {
        $ar->data[ 'ts_file_raster' ] = $resRaster;
        $ar->data[ 'ts_file_raster_ini_date' ] = $pos;
        $ar->data[ 'ts_file_raster_ex_date' ] = substr( $resRaster, $pos, 8 );
        
        $str = $this->config->item( 'folder_msbas' ) . $folder . $this->config->item( 'folder_msbas_ras' ) . $resRaster;
        $file = array( 0 => ( $str ) );
        $this->load->library( 'EnviHeader', $file );          
        $enviheader = new EnviHeader( $file );
        $enviheader->read_header();
      
        $ar->data[ 'ts_coord_lat_top' ]  = $enviheader->get_value( 'mapLat' ); // -1.1;
        $ar->data[ 'ts_coord_lon_left' ] = $enviheader->get_value( 'mapLon' ); // 29.0;
        $ar->data[ 'ts_coord_lat_inc' ]  = $enviheader->get_value( 'mapLatInc' ); // 0.0008333333;
        $ar->data[ 'ts_coord_lon_inc' ]  = $enviheader->get_value( 'mapLonInc' ); // 0.0008333333;
        // var_dump( $ar->data );
      }
      else
      {
        $ar->data[ 'ts_file_raster_ini_date' ] = 'Error: regular expression ' . $regexp . ' not found in ' . $resRaster;
      }        
    }
    if( ! $resTs ) 
    {
      $err = 'app/controller/Timeseries/E-038 Error: No timeseries available from the folder (via ajax): ' . $folder;
      log_message( 'error', $err );
      return $err;
    }
    else
    {
      $regexp = '[0-9]{3}_[0-9]{3}';
      if( ( $pos = $this->ts_folder->preg_pos( $resTs, $regexp ) ) >= 0 )
      {
        $ar->data[ 'ts_file_ts' ] = $resTs;
        $ar->data[ 'ts_file_ts_ini_coord' ] = $pos;
        $ar->data[ 'ts_file_ts_ex_coord' ] = substr( $resTs, $pos, 7 );              
      }
      else
      {
        $ar->data[ 'ts_file_ts_ini_coord' ] = 'Error: regular expression ' . $regexp . ' not found in ' . $resTs;
      }
    }        
    echo json_encode( $ar ); // ajax not to be used as php return function
  }
  /* end of function load_msbas_files */


  /**
   * Loads timeseries (and their order?) into the user canvas 
   *   from the user ts pool of enabled timeseries
   * TBD: is this used?
   * @access	public
   * @param   sorted timeseries to be loaded
   * job     	load (set 1) the userts rows 
   *          in the correct order
   * @return	nothing - reload the page
   */  
  public function load()
  {
		if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		else
		{
      $ts = $this->input->post( 'grant' );
      $this->load->model( 'userts_model', 'userts' );
      // TBD: all ts unloaded; only the checked below are loaded
      $this->userts->unload_ts( $this->session->userdata( 'email' ) );
      if( is_array( $ts ) )
      {
        $i = 0;
        foreach( $ts as $tss )
        {
          $this->userts->load_ts_order( $tss, $this->session->userdata( 'email' ), $i );
          $i ++;
        }
      }
      else
      {
        if( $ts > 0 )
          $this->userts->load_ts_order( $tss, $this->session->userdata( 'email' ), 1 );
      }
      
      redirect( site_url(), 'refresh' );
    }
  }
  /* end of function load */
  
  
}

/* End of file Timeseries.php */
/* Location: ./application/controllers/Timeseries.php */