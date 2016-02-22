<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Help Controller
 *
 * @package		CodeIgniter
 * @subpackage	Controllers
 * @version	  1.0
 * @author		Fulgencio Sanmartín
 * @link		email@fulgenciosanmartin.com
*/
class Help extends CI_Controller {

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
    
    $this->load->helper( 'menu' );
    $this->load->view( 'help' );
    // $this->load->view( 'footer' );
    
	}
}
/* End of file Help.php */
/* Location: ./application/controllers/Help.php */