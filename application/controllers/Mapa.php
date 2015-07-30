<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mapa extends CI_Controller {

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
		if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
    
    $this->load->model( 'Userlayer_model', 'Userlayer' );
    $result = $this->Userlayer->get_all_layers( $this->session->userdata( 'email' ) );
    
    if( ! $result )
    {
      $err = 'Error: No layers available. If Geoserver is running, please ask the admin to grant you some.';
      log_message( 'error', 'app/controller/Mapa/E-001 ' . $err ); 
      $data[ 'layers' ] = $err;
    }
    else
    { 
      $data[ 'layers' ] = $result;
    }
    
    $this->load->helper( array( 'menu', 'form' ) );
    $this->load->view( 'mapa', $data ); 
    // $this->load->view( 'footer' );
    
	}
}
