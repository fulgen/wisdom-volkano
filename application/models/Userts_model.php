<?php
/**
 * Userts Model
 *
 * @package		CodeIgniter
 * @subpackage	Models
 * @version	  1.0
 * @author		Fulgencio Sanmartín
 * @link		email@fulgenciosanmartin.com
*/
class Userts_model extends CI_Model {
  var $user_email     = "";
	var $ts_name        = "";
  var $config_visible = 0;
  var $config_opacity = 100;
  var $config_order   = 1;
  var $granted_by     = "";
  var $granted_when;
	
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
	function index()
	{
	}
  
  /**
   * Update a user's all timeseries config, usually when loggin out
   *
   * @access	public
   * @param   the user and an array with the timeseries
   * @return	nothing
  */
	function set_userts_config( $user_email, $ar_ts )
	{
		$this->load->database();
    $i = 0;
    $count = count( $ar_ts );
    for( $i = 0; $i < $count; $i++ )
    {
      $sql = "UPDATE user_timeseries SET ts_name = ?, config_visible = ?, config_opacity = ?, config_order = ? WHERE user_email = ?";
      $this->db->query( $sql, array( $ar_ts[ $i ][ "name" ], $ar_ts[ $i ][ "visible" ], $ar_ts[ $i ][ "opacity" ], $i, $user_email ) );
    
      if( $this->db->affected_rows() == 0 )
      {
        log_message( 'error', 'app/model/userts/E-021 Error cannot update the config for ' . $this->ts . '.' );
      }
    }
    return;
  }
  /* end of function set_userts_config */

    
  /**
   * Loads a single user ts from the pool and sets its order
   *
   * @access	public
   * @param   the user and the ts, and their order
   * @return	nothing
  */
	function load_ts_order( $ts, $user_email, $order )
  {
		//$this->load->database();
    $sql = "UPDATE user_timeseries " .
           "   SET config_loaded = 1, config_order = ? " .
           " WHERE user_email = ? AND ts_name = ?";
    $this->db->query( $sql, array( $order, $user_email, $ts ) );

    if( $this->db->affected_rows() == 0 )
    {
      log_message( 'error', 'app/model/userts/E-022 Error cannot load the timeseries ' . $ts . ' for user ' . $user . '.' );
    }
    return;
  }
  /* end of function load_ts_order */

  
  /**
   * Unloads all user ts back to the pool. To be used right before the above load_ts
   *
   * @access	public
   * @param   the user 
   * @return	nothing
  */
	function unload_ts( $user_email )
  {
    $sql = "UPDATE user_timeseries SET config_loaded = 0 WHERE user_email = ?";
    $this->db->query( $sql, array( $user_email ) );

    if( $this->db->affected_rows() == 0 )
    {
      log_message( 'error', 'app/model/userts/E-023 Error cannot unload the timeseries for user ' . $user_email . '.' );
    }
    return;
  }
  /* end of function unload_ts */
  
  
  /**
   * Grant a timeseries for a user
   *
   * @access	public
   * @param   the user to grant access to, ts name, and the current user
   * @return	nothing
  */
	function set_userts_new( $user_email, $ts )
	{
    $this->user_email   = $user_email; 
    $this->granted_by   = $this->session->userdata( 'email' );
    $this->granted_when = date( 'Y/m/d' );
    $this->ts_name      = $ts; 
    $this->db->insert( 'user_timeseries', $this );      

    if( $this->db->affected_rows() == 0 )
    {
      log_message( 'error', 'app/model/userts/E-024 Error cannot create the grant for timeseries ' . $this->ts_name . ' for user ' . $this->granted_by . '.' );
    }
    return;
  }
  /* end of function set_userts_new */

  
  /**
   * Delete a ts from a user
   *
   * @access	public
   * @param   the user to revoke access to what ts 
   * @return	nothing
   * Note: no versioning/history? Log!
  */
	function del_userts( $user_email, $ts )
	{
		$this->load->database();
    $this->db->where( 'user_email', $user_email );
    $this->db->where( 'ts_name', $ts );
    $this->db->delete( 'user_timeseries' );      

    if( $this->db->affected_rows() == 0 )
    {
      log_message( 'error', 'app/model/userts/E-025 Error cannot revoke timeseries ' . $ts . ' from user ' . $user_email . '.' );
    }
    return;
  }
  /* end of function del_userts */
  
  
  /**
   * Delete all user grants from a timeseries
   *
   * @access	public
   * @param   the timeseries to remove all grants - usually right before adding grants again
   * @return	nothing
   * Note: no versioning/history? Log!
  */
	function del_userts_all( $ts )
	{
		$this->load->database();
    $this->db->where( 'ts_name', $ts );
    $this->db->delete( 'user_timeseries' );      

    if( $this->db->affected_rows() == 0 )
    {
      log_message( 'error', 'app/model/userts/E-026 Error cannot revoke grants on timeseries ' . $ts . '.' );
    }
    return;
  }
  /* end of function del_userts_all */
  
      
  /**
   * Get all timeseries and their config of a user (usually on login or after reload)
   *
   * @access	public
   * @param   the user 
   * @return	the result of ts + config
  */
  function get_all_timeseries( $user_email )
  {
		$this->load->database();
		$this->db->where( 'user_email', $user_email );
    $this->db->order_by( 'config_order', 'ASC' );
		$this->db->select( 'timeseries.ts_name, config_visible, config_opacity, config_order, config_loaded, ts_type, ts_file_ts, ts_file, timeseries.ts_seism_station' );
    $this->db->from( 'user_timeseries' );
    $this->db->join( 'timeseries', 'timeseries.ts_name = user_timeseries.ts_name' );
		$query = $this->db->get();
    if( $query->num_rows() == 0 )
    {
        log_message( 'error', 'app/model/userts/E-027 Error This user ' . $user_email . ' is not granted yet any timeseries.' );
    }
		return $query->result();
	}
  /* end of function get_all_timeseries */

  
  /**
   * Get all enabled users who can be granted a timeseries
   *
   * @access	public
   * @param   none
   * @return	the list of enabled users
  */
  function get_all_users()
  {
		$this->load->database();
		$this->db->where( 'active', 1 );
		$this->db->select( 'email' );
		$query = $this->db->get( 'users' );
    if( $query->num_rows() == 0 )
    {
        log_message( 'error', 'app/model/userts/E-028 Error No enabled users found.' );
    }
		return $query->result();
	}
  /* end of function get_all_users */
  
  /**
   * Get all users granted for a timeseries
   *
   * @access	public
   * @param   the ts
   * @return	the users who are granted that timeseries
  */
  function get_ts_users( $ts )
  {
		$this->load->database();
		$this->db->where( 'ts_name', $ts );
		$this->db->select( 'user_email' );
		$query = $this->db->get( 'user_timeseries' );
    if( $query->num_rows() == 0 )
    {
      // Not an error: timeseries yet not granted to anyone
      return null;
    }
		return $query->result();
	}
  /* end of function get_ts_users */
  
  /**
   * Is a user granted a ts? Return all data related (folder...)
   *
   * @access	public
   * @param   the ts and the current user
   * @return	related data to the ts
  */
  function get_ts_user( $ts, $user_email )
  {
		$this->load->database();
		$this->db->where( 'ts_name', $ts );
		$this->db->where( 'user_email', $user_email );
		//$this->db->select( 'user_email' );
		$query = $this->db->get( 'user_timeseries' );
    if( $query->num_rows() == 0 )
    {
      // Not an error: timeseries yet not granted to anyone
      return null;
    }
		return $query->result();
	}
  /* end of function get_ts_user */
  
}

/* End of file userts_model.php */
/* Location: ./application/models/userts_model.php */