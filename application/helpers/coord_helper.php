<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Coord Helper
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
if ( ! function_exists('coord2pix'))
{

	/**
	 * coord2pix Converts a coordinate into a pixel, given an origin and increment
   *        ex: given long 29.215, origin 29.0 and incr 0.0008333
   *            29.215 - 29 = 0.215 / 0.0008333 = 258
	 *
   * @access	private
   * @param   the coordinate, the origin of the image file and the increment per pixel
   * @return	the number for the pixel
	 */
  function coord2pix( $coord, $origin, $incr )
  {
    $pix = round( abs( $coord - $origin ) / $incr );
    $pix = sprintf( "%'.03d", $pix );
    return $pix;
    
  }
  /* end of function coord2pix */

}

/* End of file coord_helper.php */
/* Location: ./application/helpers/coord_helper.php */