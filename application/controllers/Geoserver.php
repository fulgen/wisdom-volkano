<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Geoserver Controller
 *
 * @package		CodeIgniter
 * @subpackage	Controllers
 * @version	  1.0
 * @author		Fulgencio Sanmartín
 * @link		email@fulgenciosanmartin.com
*/
class Geoserver extends CI_Controller {

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
	 * ajaxGetFeatInfo Gets the URL info via cURL from Geoserver
   *    Called from view map 
	 *
	 * Maps to
	 * 		http://server/index.php/geoserver/ajaxGetFeatInfo/
	 *
   * @access	public
   * @param   zoom and position
   * @return	nothing, it saves the data into the Session (and the db) 
	 */
  public function ajaxGetFeatInfo()
  {
    $urlGPSSta   = $this->input->post('urlGPSSta');
    $urlSeismSta = $this->input->post('urlSeismSta');
    $urlSeismLoc = $this->input->post('urlSeismLoc');
    $this->load->model( 'geoserver_model', 'geoserver' );
    
    $result = $this->geoserver->ajaxGetFeatInfo( $urlGPSSta, $urlSeismSta, $urlSeismLoc );
    echo $result; // ajax needs echo, not return
	}
  /* end of function ajaxGetFeatInfo */
  
  
}

/* End of file Geoserver.php */
/* Location: ./application/controllers/Geoserver.php */