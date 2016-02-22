<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Favorite Controller
 *
 * @package		CodeIgniter
 * @subpackage	Controllers
 * @version	  1.0
 * @author		Fulgencio Sanmartín
 * @link		email@fulgenciosanmartin.com
*/
class Favorite extends CI_Controller {

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
	 * 		http://example.com/index.php/favorite
	 *	- or -
	 * 		http://example.com/index.php/favorite/index
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
    // no need to be an admin to run see the user favorites' points
    else
    {
      $this->load->model( 'favorite_model', 'fav' );
      
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
      
			//list the favorites
			$this->data[ 'favs' ] = $this->fav->get_all_favorites();
			$this->load->view( 'favorite_list', $this->data );
    }    
  } /* end of index */ 
  
  
	/**
	 * ajaxTSSave Saves asynchronously (ajax) the timeseries list 
   *            as favorite points
   *    Called from ts-empty.js
	 *
	 * Maps to
	 * 		http://server/index.php/favorite/ajaxTSSave/
	 *
   * @access	public
   * @param   list of timeseries
   * @return	nothing, it saves the data into the db
	 */
  public function ajaxTSSave()
  {
    $ar_msbas = json_decode( $this->input->post('ts_msbas'), true ); 
    $ar_lat   = json_decode( $this->input->post('lat'), true );
    $ar_lon   = json_decode( $this->input->post('lon'), true );

    $this->load->model( 'favorite_model', 'fav' );
		$this->fav->set_ts_favorite( $ar_msbas, $ar_lat, $ar_lon );
	}
  /* end of function ajaxTSSave */
  
  /**
   * Delete a favorite
   *
   * @access	public
   * @param   favorite id
   * @return	deletes the favorite
  */  
  public function del_fav( $id )
  {
		if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		else
		{
      if( ! is_numeric( $id ) ) 
      {
        $err = 'app/controller/Favorite/E-063 Error: Favorite ' . $id . ' not found.';
        log_message( 'error', $err );
        $this->data['message'] = $err;
      }
      else
      {
        $this->load->model( 'favorite_model', 'fav' );
        $this->fav->del_fav( $id );
        redirect( site_url() . '/favorite', 'refresh' );
      }
    }
  }
  /* end of function del_fav */

  
  /**
   * Load a favorite into the current session
   *
   * @access	public
   * @param   favorite id
   * @return	loads the point into the current session
  */  
  public function load_fav( $id )
  {
		if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		else
		{
      if( ! is_numeric( $id ) ) 
      {
        $err = 'app/controller/Favorite/E-068 Error: Favorite ' . $id . ' not found.';
        log_message( 'error', $err );
        $this->data['message'] = $err;
      }
      else
      {
        $this->load->model( 'favorite_model', 'fav' );
        $result = $this->fav->get_favorite( $id );

        $comma = ( $_SESSION[ 'ts_msbas' ] == "[]" ) ? "" : ",";
        
        $_SESSION[ 'ts_msbas' ] = str_replace( "]", $comma . '"' . $result->ts_name . '"]', $_SESSION[ 'ts_msbas' ] );  
        $_SESSION[ 'ts_lat' ] = str_replace( "]", $comma . '"' . $result->lat . '"]', $_SESSION[ 'ts_lat' ] );  
        $_SESSION[ 'ts_lon' ] = str_replace( "]", $comma . '"' . $result->lon . '"]', $_SESSION[ 'ts_lon' ] );  
        $_SESSION[ 'ts_msbas_num' ] ++;
        $_SESSION[ 'message' ] = 'Point added to the current session.';
        
        redirect( site_url() . '/favorite', 'refresh' );
      }
    }
  }
  /* end of function load_fav */
  
  /**
   * Load all favorites into the current session
   *
   * @access	public
   * @param   none
   * @return	loads all the points into the current session
  */  
  public function load_all()
  {
		if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		else
		{
      $this->load->model( 'favorite_model', 'fav' );
      $result = $this->fav->get_all_favorites();

      if( empty( $result ) ) // if empty, ignoring the command
      {
        $_SESSION[ 'message' ] = 'No points to add.';
      }
      else
      {
        foreach( $result as $fav ) { $arr1[] = $fav->ts_name; }
        $comma = ( $_SESSION[ 'ts_msbas' ] == "[]" ) ? "" : ",";
        $str = implode( '","', $arr1 );
        $_SESSION[ 'ts_msbas' ] = str_replace( "]", $comma . '"' . $str . '"]', $_SESSION[ 'ts_msbas' ] );  
        
        $_SESSION[ 'ts_msbas_num' ] += count( $arr1 );

        foreach( $result as $fav ) { $arr2[] = $fav->lat; }
        $comma = ( $_SESSION[ 'ts_lat' ] == "[]" ) ? "" : ",";
        $str = implode( ",", $arr2 );
        $_SESSION[ 'ts_lat' ] = str_replace( "]", $comma . '' . $str . ']', $_SESSION[ 'ts_lat' ] );  
        
        foreach( $result as $fav ) { $arr3[] = $fav->lon; }
        $comma = ( $_SESSION[ 'ts_lon' ] == "[]" ) ? "" : ",";
        $str = implode( ",", $arr3 );
        $_SESSION[ 'ts_lon' ] = str_replace( "]", $comma . '' . $str . ']', $_SESSION[ 'ts_lon' ] );  
        
        $_SESSION[ 'message' ] = 'All points added to the current session.';
      }
      redirect( site_url() . '/favorite', 'refresh' );
    }
  }
  /* end of function load_all */

  
  /**
   * Edit a favorite
   *
   * @access	public
   * @param   the favorite id
   * @return	calls the create favorite view
  */  
  public function edit_fav( $id )
  {
		if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		else
		{   
      //rules to validate form input
      $this->load->model( 'timeseries_model', 'ts' );
      $this->form_validation->set_rules('ts_name', 'Time series name', 
        array( 'required', 
               array( 'ts_name_error_msg',
                      array( $this->ts, 'get_timeseries_exist' ) 
                    )
             ) 
      ); 
      $this->form_validation->set_message('ts_name_error_msg', 'Time series name not found');             
      $this->form_validation->set_rules('lat', 'Latitude', 'required|decimal'); 
      $this->form_validation->set_rules('lon', 'Longitude', 'required|decimal'); 
      // http://www.codeigniter.com/userguide3/libraries/form_validation.html#rule-reference
      // description is optional

      $this->load->model( 'favorite_model', 'fav' );
      // Validate the form
      if( $this->form_validation->run() == true )
      {
        $ts_name = $this->input->post( 'ts_name' );
        $lon = $this->input->post( 'lon' );
        $lat = $this->input->post( 'lat' );
        $description = $this->input->post( 'description' );
        
        $this->fav->set_favorite( $id, $ts_name, $lon, $lat, $description );

   			redirect( "favorite", 'refresh' );
      }
      else // Show the form
      {
        // Get all the favorite info
        $result = $this->fav->get_favorite( $id );
        if( ! $result )
        {
          $err = 'app/controller/favorite/E-065 Error Cannot find the ' . $id . ' favorite.';
          log_message( 'error', $err );
          $this->data['message'] = $err;
        }
        else
        { 
          // get the info from the current layer
          $this->data[ 'ts_name' ] = $result->ts_name;
          $this->data[ 'lon'  ] = $result->lon;
          $this->data[ 'lat'  ] = $result->lat;
          $this->data[ 'description' ] = $result->description;
          $this->data[ 'title' ] = "Edit favorite MSBAS point";
          
          //set the flash data error message if there is one
          $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
          
          $this->load->view( 'favorite_edit', $this->data );
        }
      }
    }
  }
  /* end of function edit_fav */

  
  /**
   * Create manually a favorite
   *
   * @access	public
   * @param   nothing
   * @return	calls the edit favorite view
  */  
  public function create_fav()
  {
		if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		else
		{   
      //rules to validate form input
      $this->load->model( 'timeseries_model', 'ts' );
      $this->form_validation->set_rules('ts_name', 'Time series name', 
        array( 'required', 
               array( 'ts_name_error_msg',
                      array( $this->ts, 'get_timeseries_exist' ) 
                    )
             ) 
      ); 
      $this->form_validation->set_message('ts_name_error_msg', 'Time series name not found');             
      $this->form_validation->set_rules('lat', 'Latitude', 'required|decimal'); 
      $this->form_validation->set_rules('lon', 'Longitude', 'required|decimal'); 
      // http://www.codeigniter.com/userguide3/libraries/form_validation.html#rule-reference
      // description is optional

      $this->load->model( 'favorite_model', 'fav' );
      // Validate the form
      if( $this->form_validation->run() == true )
      {
        $ts_name = $this->input->post( 'ts_name' );
        $lon = $this->input->post( 'lon' );
        $lat = $this->input->post( 'lat' );
        $description = $this->input->post( 'description' );
        
        $this->fav->set_favorite( 0, $ts_name, $lon, $lat, $description );

        $this->create_point( $ts_name, $lon, $lat ); 
        
   			redirect( "favorite", 'refresh' );
      }
      else // Show the form
      {
        // default values
        $this->data[ 'ts_name' ] = "";
        $this->data[ 'lon'  ] = 29.1;
        $this->data[ 'lat'  ] = -1.5;
        $this->data[ 'description' ] = "";
        $this->data[ 'title' ] = "Create favorite point";
          
        //set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        
        $this->load->view( 'favorite_edit', $this->data );
      }
    }
  }
  /* end of function create_fav */

  /**
   * If the newly created point is not yet calculated, do it
   *
   * @access	private
   * @param   the timeseries name, lon and lat of the point
   * @return	creates the file with the calculated data
  */  
  private function create_point( $ts_name, $lon, $lat )
  {
    $this->load->model( 'timeseries_model', 'ts' );
    $msbas = $this->ts->get_timeseries( $ts_name );
    $this->load->helper( 'coord' );
    
    $xcol = coord2pix( $lon, $msbas->ts_coord_lon_left, $msbas->ts_coord_lon_inc );
    $yrow = coord2pix( $lat, $msbas->ts_coord_lat_top, $msbas->ts_coord_lat_inc );
    
    $this->load->model( 'ts_folder_model', 'tsfolder' );
    $file = $this->tsfolder->get_ts_file_name( $msbas, "disk", $xcol, $yrow );
  
    $raster = $this->config->item( 'folder_msbas' ) 
              . trim( $msbas->ts_file ) 
              . $this->config->item( 'folder_msbas_ras' ) 
              . trim( $msbas->ts_file_raster );
    $a_file = array( 0 => $raster );
    $this->load->library( 'EnviHeader', $a_file );          
    $enviheader = new EnviHeader( $a_file );
    $enviheader->read_header();
    
    $new_ts = $enviheader->calc_pixel_stack( $msbas->ts_file_raster_ini_date, $xcol, $yrow );
    
    $this->load->model( 'Ts_folder_model', 'ts_folder' );
    $this->ts_folder->create_file_ts( $file, $new_ts );
  }
  /* end function create_point */
    
}

/* End of file Favorite.php */
/* Location: ./application/controllers/Favorite.php */