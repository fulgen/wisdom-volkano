<?php
/**
 * Userlayer Model
 *
 * @package		CodeIgniter
 * @subpackage	Models
 * @version	  1.0
 * @author		Fulgencio Sanmartín
 * @link		email@fulgenciosanmartin.com
*/
class Userlayer_model extends CI_Model {
  var $user_email     = "";
	var $layer          = "";
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
   * Update a user's all layers config, usually when loggin out
   *
   * @access	public
   * @param   the user and an array with the layers
   * @return	nothing
  */
	function set_userlayers_config( $user_email, $ar_layers )
	{
		$this->load->database();
    $i = 0;
    $count = count( $ar_layers );
    for( $i = 0; $i < $count; $i++ )
    {
      $sql = "UPDATE user_layers SET layer = ?, config_visible = ?, config_opacity = ?, config_order = ? WHERE user_email = ?";
      $this->db->query( $sql, array( $ar_layers[ $i ][ "name" ], $ar_layers[ $i ][ "visible" ], $ar_layers[ $i ][ "opacity" ], $i, $user_email ) );
    
      if( $this->db->affected_rows() == 0 )
      {
        log_message( 'error', 'app/model/userlayer/E-002 Error cannot update the config for ' . $this->layer . '.' );
      }
    }
    return;
  }
  /* end of function set_userlayers_config */

    
  /**
   * Loads a single user layer from the pool and sets its order
   *
   * @access	public
   * @param   the user and the layer, and ther order
   * @return	nothing
  */
	function load_layer_order( $layer, $user_email, $order )
  {
		//$this->load->database();
    $sql = "UPDATE user_layers " .
           "   SET config_loaded = 1, config_order = ? " .
           " WHERE user_email = ? AND layer = ?";
    $this->db->query( $sql, array( $order, $user_email, $layer ) );

    if( $this->db->affected_rows() == 0 )
    {
      log_message( 'error', 'app/model/userlayer/E-013 Error cannot load the layer ' . $layer . ' for user ' . $user . '.' );
    }
    return;
  }
  /* end of function load_layer_order */

  /**
   * Unloads all user layers back to the pool. To be used right before the above load_layer
   *
   * @access	public
   * @param   the user 
   * @return	nothing
  */
	function unload_layers( $user_email )
  {
    $sql = "UPDATE user_layers SET config_loaded = 0 WHERE user_email = ?";
    $this->db->query( $sql, array( $user_email ) );

    if( $this->db->affected_rows() == 0 )
    {
      log_message( 'error', 'app/model/userlayer/E-014 Error cannot unload the layers for user ' . $user_email . '.' );
    }
    return;
  }
  /* end of function unload_layers */
  
  
  /**
   * Grant a layer for a user
   *
   * @access	public
   * @param   the user to grant access to, layer name, and the current user
   * @return	nothing
  */
	function set_userlayer_new( $user_email, $layer )
	{
    $this->user_email   = $user_email; 
    $this->granted_by   = $this->session->userdata( 'email' );
    $this->granted_when = date( 'Y/m/d' );
    $this->layer        = $layer; 
    $this->db->insert( 'user_layers', $this );      

    if( $this->db->affected_rows() == 0 )
    {
      log_message( 'error', 'app/model/userlayer/E-003 Error cannot create the grant for layer ' . $this->layer . ' for user ' . $this->granted_by . '.' );
    }
    return;
  }
  
  /**
   * Delete a layer from a user
   *
   * @access	public
   * @param   the user to revoke access to what layer 
   * @return	nothing
   * Note: no versioning/history? Log!
  */
	function del_userlayer( $user_email, $layer )
	{
		$this->load->database();
    $this->db->where( 'user_email', $user_email );
    $this->db->where( 'layer', $layer );
    $this->db->delete( 'user_layers' );      

    if( $this->db->affected_rows() == 0 )
    {
      log_message( 'error', 'app/model/userlayer/E-004 Error cannot revoke layer ' . $this->layer . ' from user ' . $user_email . '.' );
    }
    return;
  }
  /* end of function del_userlayer */
  
  /**
   * Delete all user grants from a layer
   *
   * @access	public
   * @param   the layer to remove all grants - usually right before adding grants again
   * @return	nothing
   * Note: no versioning/history? Log!
  */
	function del_userlayer_all( $layer )
	{
		$this->load->database();
    $this->db->where( 'layer', $layer );
    $this->db->delete( 'user_layers' );      

    if( $this->db->affected_rows() == 0 )
    {
      log_message( 'error', 'app/model/userlayer/E-016 Error cannot revoke grants on layer ' . $this->layer . '.' );
    }
    return;
  }
  /* end of function del_userlayer_all */
  
      
  /**
   * Get all layers and their config of a user (usually on login or after reload)
   *
   * @access	public
   * @param   the user 
   * @return	the result of layers + config
  */
  function get_all_layers( $user_email )
  {
		$this->load->database();
		$this->db->where( 'user_email', $user_email );
    $this->db->order_by( 'config_order', 'ASC' );
		$this->db->select( 'layer, config_visible, config_opacity, config_order, config_loaded' );
		$query = $this->db->get( 'user_layers' );
    if( $query->num_rows() == 0 )
    {
        log_message( 'error', 'app/model/userlayer/E-005 Error This user ' . $user_email . ' is not granted yet any layers.' );
    }
		return $query->result();
	}

  /**
   * Get all enabled users who can be granted a layer
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
        log_message( 'error', 'app/model/userlayer/E-012 Error No enabled users found.' );
    }
		return $query->result();
	}
  /* end of function get_all_users */
  
  /**
   * Get all users granted for a layer 
   *
   * @access	public
   * @param   the layer
   * @return	the users who are granted that layer
  */
  function get_layer_users( $layer )
  {
		$this->load->database();
		$this->db->where( 'layer', $layer );
		$this->db->select( 'user_email' );
		$query = $this->db->get( 'user_layers' );
    if( $query->num_rows() == 0 )
    {
      // Not an error: layer yet not granted to anyone
    }
		return $query->result();
	}
}

/* End of file userlayer_model.php */
/* Location: ./application/models/userlayer_model.php */