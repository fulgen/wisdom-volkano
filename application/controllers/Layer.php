<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Layer extends CI_Controller {
	var $layer_name = "";
  var $layer_types = "raster,dem,feat-point,feat-line,feat-poly"; // to be used for populating and validation 
  
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
      $this->load->model( 'layer_model', 'layer' );
      $this->load->model( 'userlayer_model', 'userlayer' );
      
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
      
			//list the layers
			$this->data[ 'layers' ] = $this->layer->get_all_layers();
      // and for each layer, list the granted users
			foreach( $this->data[ 'layers' ] as $k => $layer )
			{
				$this->data[ 'layers' ][ $k ]->users = $this->userlayer->get_layer_users( $layer->layer_name_ws );
			}   
			$this->load->view( 'auth/layer_list', $this->data );
		}
	}
  /* end of function index */
  
  /**
   * Create a layer in the system
   *
   * @access	public
   * @param   none
   * @return	calls the create layer view
  */  
  public function create_layer()
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
      $this->form_validation->set_rules('layer_name', 'Layer name', 'required'); 
      // |min_length[5]|max_length[250]|alpha_numeric');
      // http://www.codeigniter.com/userguide3/libraries/form_validation.html#rule-reference
      $this->form_validation->set_rules('layer_type', 'Layer type', 'required|in_list[' . $this->layer_types . ']' );
      // layer_description is optional

      $this->load->model( 'layer_model', 'layer' );
      $this->load->model( 'userlayer_model', 'userlayer' );
      // Validate the form
      if( $this->form_validation->run() == true )
      {
        $layer_name = $this->input->post( 'layer_name' );
        $layer_type = $this->input->post( 'layer_type' );
        $layer_description = $this->input->post( 'layer_description' );
        
        $this->layer->set_layer_new( $layer_name, $layer_type, $layer_description );
        
        // get all users to grant the layer
        //var_dump( $this->input );
        if( $this->input->post( 'grant' ) ) 
          foreach( $this->input->post( 'grant' ) as $user_email )
            $this->userlayer->set_userlayer_new( $user_email, $layer_name );
        // by default, the admin grants herself the layer 
        $this->userlayer->set_userlayer_new( $this->session->userdata( 'email' ), $layer_name );
        
   			redirect( "layer", 'refresh' );
      }
      else // Show the form
      {
        $this->load->model( 'geoserver_model', 'Geoserver' );
        $result = $this->Geoserver->get_all_layers();
        if( ! $result )
        {
          $err = 'app/controller/Layer/E-011 Error: No layers available from Geoserver. Is it running?';
          log_message( 'error', $err );
          $this->data['message'] = $err;
        }
        else
        { 
          /* except the already created ones */
          $this->data[ 'layers' ] = array();
          foreach( $result as $layer )
          {
            if( $this->layer->get_layer_exist( $layer ) == FALSE )
            {
              $this->data[ 'layers' ][] = $layer;
              // print_r( $this->data['layers'] );
            }
          } 
          //$this->data[ 'layers' ] = $result;
  
          // all enabled users that can be granted the layer
          $this->data[ 'users' ] = $this->userlayer->get_all_users();
          
          //set the flash data error message if there is one
          $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
          $this->data['layer_types'] = $this->layer_types;
          
          $this->load->view( 'auth/create_layer', $this->data );
        }
      }
    }
  }
  /* end of function create_layer */
  

  /**
   * Edit a layer in the system
   *
   * @access	public
   * @param   the layer name
   * @return	calls the create layer view
  */  
  public function edit_layer( $layer )
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
      $this->form_validation->set_rules('layer_name', 'Layer name', 'required'); 
      // |min_length[5]|max_length[250]|alpha_numeric');
      // http://www.codeigniter.com/userguide3/libraries/form_validation.html#rule-reference
      $this->form_validation->set_rules('layer_type', 'Layer type', 'required|in_list[' . $this->layer_types . ']' );
      // layer_description is optional

      $this->load->model( 'layer_model', 'layer' );
      $this->load->model( 'userlayer_model', 'userlayer' );
      // Validate the form
      if( $this->form_validation->run() == true )
      {
        $layer_name = $this->input->post( 'layer_name' );
        $layer_type = $this->input->post( 'layer_type' );
        $layer_description = $this->input->post( 'layer_description' );
        
        $this->layer->set_layer_edit( $layer_name, $layer_type, $layer_description );

        // remove all grants for that layer
        $this->userlayer->del_userlayer_all( $layer_name );
        
        // get all users to grant the layer
        if( $this->input->post( 'grant' ) ) 
          foreach( $this->input->post( 'grant' ) as $user_email )
            $this->userlayer->set_userlayer_new( $user_email, $layer_name );
        // by default, the admin grants herself the layer 
        $this->userlayer->set_userlayer_new( $this->session->userdata( 'email' ), $layer_name );
        
   			redirect( "layer", 'refresh' );
      }
      else // Show the form
      {
        // Get all the layer info
        $this->load->model( 'Layer_model', 'Layer' );
        $result = $this->Layer->get_layer( $layer );
        if( ! $result )
        {
          $err = 'app/controller/Layer/E-015 Error: Layer ' . $layer . ' not found.';
          log_message( 'error', $err );
          $this->data['message'] = $err;
        }
        else
        { 
        
          // get the info from the current layer
          $this->data[ 'current_layer' ] = $layer;
          $this->data[ 'current_type'  ] = $result->layer_type;
          $this->data[ 'current_desc'  ] = $result->layer_description;
          
          // and get all layers available
          $this->load->model( 'geoserver_model', 'Geoserver' );
          $result = $this->Geoserver->get_all_layers();
          $this->data[ 'layers' ] = array();
          if( $result ) 
          {
            if( is_array( $result ) )
            {
              foreach( $result as $lay )
              {
                if( $this->layer->get_layer_exist( $lay ) == FALSE )
                {
                  $this->data[ 'layers' ][] = $lay;
                  // print_r( $this->data['layers'] );
                }
              }
            }
            else // there's only one
            {
              $this->data[ 'layers' ][] = $lay;
            }
          }
          
          // get the users currently granted
          $this->data[ 'current_users' ] = $this->userlayer->get_layer_users( $layer );
          
          // and get all enabled users that can be granted the layer
          $this->data[ 'users' ] = $this->userlayer->get_all_users();
          
          //set the flash data error message if there is one
          $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
          $this->data['layer_types'] = $this->layer_types;
          
          $this->load->view( 'auth/edit_layer', $this->data );
        }
      }
    }
  }
  /* end of function edit_layer */
  
  
  /**
   * Delete a layer from the system
   *
   * @access	public
   * @param   name of the layer
   * @return	deletes the layer, and in the db in cascade, all grants in user_layers are removed as well
  */  
  public function del_layer( $layer )
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
      $this->load->model( 'layer_model', 'layer' );
      $this->layer->del_layer( $layer );
      redirect( site_url() . '/layer', 'refresh' );
    }
  }
  /* end of function del_layer */
  

  /**
   * Loads layers and their order into the user canvas 
   *   from the userlayer pool of enabled layers
   *
   * @access	public
   * @param   sorted layers to be loaded
   * job     	load (set 1) the userlayer rows 
   *          in the correct order
   * @return	nothing - reload the panel
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
      $layers = $this->input->post( 'grant' );
      $this->load->model( 'userlayer_model', 'userlayer' );
      // TBD: all layers unloaded; only the checked below are loaded
      $this->userlayer->unload_layers( $this->session->userdata( 'email' ) );
      if( is_array( $layers ) )
      {
        $i = 0;
        foreach( $layers as $layer )
        {
          $this->userlayer->load_layer_order( $layer, $this->session->userdata( 'email' ), $i );
          $i ++;
        }
      }
      else
      {
        if( $layers > 0 )
          $this->userlayer->load_layer_order( $layers, $this->session->userdata( 'email' ), 1 );
      }
      
      redirect( site_url(), 'refresh' );
    }
  }
  /* end of function load */
  
  /**
   * Saves user config (layers visibility, opacity...)
   *   right before moving from the map canvas
   *
   * @access	public
   * @param   [POST] layers array, plus the new page to go to 
   * job     	save to userlayer rows 
   * @return	nothing - redirects to new page
   */  
  public function save_config( $href )
  {
		if( !$this->ion_auth->logged_in() )
		{
			//nothing to be done, out
			redirect('auth/login', 'refresh');
		}
    // only if we are coming from the map (home) and there is at least 1 layer
		else
    {
      $count = count( $this->input->post( 'visible' ) ); // TBD: ensure that returns 1 if it is not an array, just one
      if( $count > 0 )
      {
        $visible = $this->input->post( 'visible' );
        $opac    = $this->input->post( 'opac' );
        print_r( $visible );
        print_r( $opac );

      }
      // in any case, we go to the expected page
      redirect( $href, 'refresh' );
    }
	}
}

/* End of file Layer.php */
/* Location: ./application/controllers/Layer.php */