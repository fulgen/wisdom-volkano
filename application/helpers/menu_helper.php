<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Menu Helper
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Fulgencio Sanmartín
 * @link		fulgenciosanmartin.com
*/

// ------------------------------------------------------------------------

/**
 * Menu options, it takes the parameter. 
 * By default, home 
 *
 * @access	public
 * @param	current page
 * @return	string with all the menu
*/
if ( ! function_exists('menu'))
{
	
	function menu($pagina_actual = 'home')
	{
		$ret = "<ul class='nav nav-tabs'>\n";
	
		if( $pagina_actual == 'home' )
			$ret .= "<li role='presentation' class='active'>";
		else 
			$ret .= "<li role='presentation'>";
		$ret .= "<a href='" . site_url() . "'>Home</a></li>\n";
		// $ret .= "<a href='#' onclick='this.form_menu.action=\"" . site_url() . "\"'>Home</a></li>\n";

    $CI =& get_instance();

    if( $CI->ion_auth->is_admin() ) 
    {
      if( $pagina_actual == 'admin' )
        $ret .= "<li role='presentation' class='active dropdown'>";
      else 
        $ret .= "<li role='presentation' class='dropdown'>";
      $ret .= "<a class='dropdown-toggle' data-toggle='dropdown' href='#' role='button' aria-expanded='false' title='Admin'>Admin <span class='caret'></span></a>\n";
      $ret .= "<ul class='dropdown-menu' role='menu'>\n";
      $ret .= "  <li role='presentation'><a role='menuitem' tabindex='-1' href='" . site_url( '/auth' ) . "'>User list</a></li>\n";
      $ret .= "  <li role='presentation'><a role='menuitem' tabindex='-1' href='" . site_url( '/auth/create_user' ) . "'>Create user</a></li>\n";
      $ret .= "  <li role='presentation'><a role='menuitem' tabindex='-1' href='" . site_url( '/auth/create_group' ) . "'>Create group</a></li>\n";
      
      $ret .= "<li role='presentation' class='divider'></li>\n";
      
      $ret .= "  <li role='presentation'><a role='menuitem' tabindex='-1' href='" . site_url( '/layer' ) . "'>Layer list</a></li>\n";
      $ret .= "  <li role='presentation'><a role='menuitem' tabindex='-1' href='" . site_url( '/layer/create_layer' ) . "'>Create layer</a></li>\n";
      
      $ret .= "</ul></li>\n";
    }

		if( $pagina_actual == 'Help' )
      $ret .= '<li role="presentation" class="active"><a href="' . site_url( 'help' ) . '" title="Help">Help</a></li>' . "\n";
    else
      $ret .= '<li role="presentation"><a href="' . site_url( 'help' ) . '" title="Help">Help</a></li>' . "\n";
    
		$ret .= '<li role="presentation"><a href="' . site_url( 'auth/logout' ) . '" title="Logout">Logout</a></li>' . "\n" .
            '</ul>' . "\n";
		
    // $form = form_open( "layer/save_config", "id='form_menu'" );
    // $form .= form_hidden( "href", "" );
    
		return $ret;
	}
}

/* End of file menu_helper.php */
/* Location: ./application/helpers/menu_helper.php */