<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Favorite Model 
 *
 * @package		CodeIgniter
 * @subpackage	Models
 * @version	  1.0
 * @author		Fulgencio Sanmartín
 * @link		email@fulgenciosanmartin.com
*/
class Favorite_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
  /**
   * Saves list of Ts points to user favorites
   * Called from ts_empty.js and controller Favorite (ajax)
   *
   * @access	public
   * @param   ts_msbas, lat, lon (all arrays of same length)
   * @return	nothing
  */
 	function set_ts_favorite( $p_msbas, $p_lat, $p_lon )
  {
		$this->load->database();
    $email = $this->session->userdata( 'email' );
    
    for( $i = 0; $i < count( $p_msbas ); $i++ )
    {
      $this->db->where( 'user_email', $email );    
      $this->db->where('round( cast( lat as numeric ), 3 ) = ',
                        round( $p_lat[ $i ], 3 ) );    
      $this->db->where('round( cast( lon as numeric ), 3 ) = ',
                        round( $p_lon[ $i ], 3 ) );    
      $this->db->where( 'ts_name', $p_msbas[$i] );    
      $query = $this->db->get( 'user_favorite' );
      if( $query->num_rows() == 0 ) // if exists we do nothing
      {
        $sql = "INSERT INTO user_favorite (user_email, ts_name, lat, lon) " 
             . "VALUES ( ?, ?, ?, ? )";
        if(!$this->db->query( $sql, array( $email, $p_msbas[$i], $p_lat[$i], $p_lon[$i] ))) 
        {
          $error = $this->db->error();    
          log_message( 'error', 'app/model/favorite/E-061 Error: Cannot save favorite points of the user ' . $email );
        }
      }
    }
    return;
  }
  /* end of set_ts_favorite */

  
  /**
   * Get all favorites of a user
   *
   * @access	public
   * @param   nothing (the current user)
   * @return	the result of favs 
  */
  function get_all_favorites()
  {
		$this->load->database();
    $email = $this->session->userdata( 'email' );
    
    $this->db->where( 'user_email', $email );
    $this->db->order_by( 'description', 'lon', 'lat' );
		$query = $this->db->get( 'user_favorite' );
    if( $query->num_rows() == 0 )
    {
        log_message( 'error', 'app/model/favorite/E-062 Error Cannot find any favorites of user ' . $email  );
    }
		return $query->result(); // object
	}
  /* end function get_all_favorites  */
  
  
  /**
   * Delete a favorite
   *
   * @access	public
   * @param   the id of the favorite
   * @return	nothing
  */
	function del_fav( $id )
	{
		$this->load->database();
    $this->db->where( 'id', $id );
    $this->db->delete( 'user_favorite' );      

    $nrows = $this->db->affected_rows();
    if( $nrows == 0 )
    {
      log_message( 'error', 'app/model/favorite/E-063 Error Cannot remove favorite' . $id . '.' );
    }
    return $nrows;
  }
  /* end function del_fav */
  

  /**
   * Get favorite data from id
   *
   * @access	public
   * @param   the id
   * @return	the data for that id
  */
  function get_favorite( $id )
  {
		$this->load->database();
    
    $this->db->where( 'id', $id );
		$query = $this->db->get( 'user_favorite' );
    if( $query->num_rows() == 0 )
    {
        log_message( 'error', 'app/model/favorite/E-064 Error Cannot find the ' . $id . ' favorite.'  );
    }
		return $query->row(); // object
	}
  /* end function get_favorite  */
  
  /**
   * Set (a single) favorite
   *
   * @access	public
   * @param   the data of the favorite, including the id (or 0 if creating)
   * @return	nothing
  */
  function set_favorite( $id, $ts_name, $lon, $lat, $description )
  {
		$this->load->database();
    $this->db->set( 'ts_name', $ts_name );
    $this->db->set( 'lon',     $lon );
    $this->db->set( 'lat',     $lat );
    $this->db->set( 'description', $description );
    
    if( $id === 0 ) // create
    {
      $this->db->set( 'user_email', $this->session->userdata( 'email' ) );
      $this->db->insert( 'user_favorite' );
      $error = 'app/model/favorite/E-066 Error Cannot create the new favorite.';
    }
    else // update
    {
      $this->db->where( 'id', $id );
      $this->db->update( 'user_favorite' );
      $error = 'app/model/favorite/E-067 Error Cannot update the ' . $id . ' favorite.';
    }
    if( $this->db->affected_rows() == 0 )
    {
      log_message( 'error', $error );
    }
    return;
	}
  /* end function set_favorite  */

  
  
}

/* End of file Favorite_model.php */
/* Location: ./application/models/Favorite_model.php */