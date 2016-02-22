<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Status Model 
 *
 * @package		CodeIgniter
 * @subpackage	Models
 * @version	  1.0
 * @author		Fulgencio Sanmartín
 * @link		email@fulgenciosanmartin.com
*/
class Status_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
  /**
   * Saves PermaURL to user config
   * Called from permalink.js (ajax)
   *
   * @access	public
   * @param   zoom, lat, lon
   * @return	nothing
  */
 	function set_permaurl( $p_zoom, $p_lat, $p_lon )
  {
		$this->load->database();
    $email = $this->session->userdata( 'email' );

    if( $email )
    {
      $this->db->where( 'user_email', $email );    
      $query = $this->db->get( 'user_config' );
      if( $query->num_rows() > 0 ) // update
      {
        $sql = "UPDATE user_config "
             . "   SET zoom = ?, lat = ?, lon = ? "
             . " WHERE user_email = ? ";
      }
      else // insert -- order must be the same as in update for bindings
      {
        $sql = "INSERT INTO user_config ( zoom, lat, lon, user_email ) " 
             . "VALUES ( ?, ?, ?, ? )";
      }
      if(!$this->db->query( $sql, array( $p_zoom, $p_lat, $p_lon, $email))) 
      {
        $error = $this->db->error();    
        log_message( 'error', 'app/model/status/E-051 Error: Cannot update the user ' . $email . ' config.' );
      }
    }
    return;
  }
  /* end of set_permaurl */

  
  /**
   * Saves Timeseries config to user_timeseries
   *
   * @access	public
   * @param   arrays of msbas (plus lat lon), histograms and gnss, all json'd
   * @return	nothing
  */
 	function set_ts_config( $pa_msbas, $pa_lat, $pa_lon, $pa_histo, $pa_gnss )
  {
		$this->load->database();
    $email = $this->session->userdata( 'email' );
    
    // 1. msbas
    // 1.1 all config msbas to be removed
    $sql = "UPDATE user_timeseries AS ut "
         . "   SET config_msbas = NULL, "
         . "       config_visible = 0 "
         . "  FROM timeseries AS t "
         . " WHERE t.ts_name = ut.ts_name "
         . "   AND ut.user_email = '" . $email . "' "
         . "   AND t.ts_type = 'msbas' "; 
    if( ! $this->db->query( $sql ) ) // , $a_bind ) )
    {
      $error = $this->db->error();    
      log_message( 'error', 'app/model/status/E-057 Error: Config ts-msbas for user ' . $email . ' could not be removed: ' . $error );
    }

    // 1.2 save config msbas
    if( $pa_msbas != "[]" AND $pa_msbas != "{}" )
    {
      // replacing [ ] by ( ), and " by '
      $list = substr( str_replace( "\"", "'", $pa_msbas ), 1, -1 ); 
      $sql = "UPDATE user_timeseries AS ut "
           . "   SET config_msbas = '" 
           . json_encode( [ $pa_msbas, $pa_lat, $pa_lon ] ) . "', "
           . "       config_visible = 1 "
           . "  FROM timeseries AS t "
           . " WHERE t.ts_name = ut.ts_name "
           . "   AND ut.user_email = '" . $email . "' "
           . "   AND t.ts_type = 'msbas' "
           . "   AND ut.ts_name in (" . $list . ")"; 
      if( ! $this->db->query( $sql ) ) // , $a_bind ) )
      {
        $error = $this->db->error();    
        log_message( 'error', 'app/model/status/E-053 Error: Config ts-msbas for user ' . $email . ' could not be saved: ' . $error );
      }
    }
    
    // 2. histo
    // 2.1 all config histo to be removed
    $sql = "UPDATE user_timeseries AS ut "
         . "   SET config_order = 0, "
         . "       config_visible = 0 "
         . "  FROM timeseries AS t "
         . " WHERE t.ts_name = ut.ts_name "
         . "   AND ut.user_email = '" . $email . "' "
         . "   AND t.ts_type = 'histogram' ";
    if( ! $this->db->query( $sql ) )
    {
      $error = $this->db->error();    
      log_message( 'error', 'app/model/status/E-056 Error: Config ts-histogram for user ' . $email . ' could not be removed: ' . $error );
    }
    
    // 2.2 save histo config
    if( $pa_histo != "[]" AND $pa_histo != "{}" )
    {
      // replacing [ ] by ( ), and " by '
      $list = substr( str_replace( "\"", "'", $pa_histo ), 1, -1 ); 
      // string to array
      $ar_histo = explode( ",", $list );
      $totHisto = count( $ar_histo );
      for( $i = 0; $i < $totHisto; $i ++ )
      {
        $sql = "UPDATE user_timeseries AS ut "
             . "   SET config_order = $i, "
             . "       config_visible = 1 "
             . "  FROM timeseries AS t "
             . " WHERE t.ts_name = ut.ts_name "
             . "   AND ut.user_email = '" . $email . "' "
             . "   AND t.ts_type = 'histogram' "
             . "   AND ut.ts_name = " . $ar_histo[ $i ] . " "; // has quotes
        if( ! $this->db->query( $sql ) )
        {
          $error = $this->db->error();    
          log_message( 'error', 'app/model/status/E-054 Error: Config ts-histogram ' . $ar_histo[ $i ] . ' for user ' . $email . ' could not be saved: ' . $error );
        }
      }
    }
    
    // 3. gnss
    // 3.1 all config gnss to be removed
    $sql = "UPDATE user_timeseries AS ut "
         . "   SET config_order = 0, "
         . "       config_visible = 0 "
         . "  FROM timeseries AS t "
         . " WHERE t.ts_name = ut.ts_name "
         . "   AND ut.user_email = '" . $email . "' "
         . "   AND t.ts_type = 'gnss' ";
    if( ! $this->db->query( $sql ) )
    {
      $error = $this->db->error();    
      log_message( 'error', 'app/model/status/E-071 Error: Config ts-gnss for user ' . $email . ' could not be removed: ' . $error );
    }
    
    // 3.2 save gnss config
    if( $pa_gnss != "[]" AND $pa_gnss != "{}" )
    {
      // replacing [ ] by ( ), and " by '
      $list = substr( str_replace( "\"", "'", $pa_gnss ), 1, -1 ); 
      // string to array
      $ar_gnss = explode( ",", $list );
      $totGnss = count( $ar_gnss );
      for( $i = 0; $i < $totGnss; $i ++ )
      {
        $sql = "UPDATE user_timeseries AS ut "
             . "   SET config_order = $i, "
             . "       config_visible = 1 "
             . "  FROM timeseries AS t "
             . " WHERE t.ts_name = ut.ts_name "
             . "   AND ut.user_email = '" . $email . "' "
             . "   AND t.ts_type = 'gnss' "
             . "   AND ut.ts_name = " . $ar_gnss[ $i ] . " "; // has quotes
        if( ! $this->db->query( $sql ) )
        {
          $error = $this->db->error();    
          log_message( 'error', 'app/model/status/E-072 Error: Config ts-gnss ' . $ar_histo[ $i ] . ' for user ' . $email . ' could not be saved: ' . $error );
        }
      }
    }
    
    return;
  }
  /* end of set_ts_config */

  
  /**
   * Resets Timeseries config in user_timeseries
   *
   * @access	public
   * @param   none
   * @return	nothing
  */
 	function reset_ts_config()
  {
		$this->load->database();
    $email = $this->session->userdata( 'email' );
    
    $sql = "UPDATE user_timeseries AS ut "
         . "   SET config_msbas = NULL, "
         . "       config_visible = 0, "
         . "       config_order = 0 "
         . "  FROM timeseries AS t "
         . " WHERE t.ts_name = ut.ts_name "
         . "   AND ut.user_email = '" . $email . "' "; 
    if( ! $this->db->query( $sql ) ) // , $a_bind ) )
    {
      $error = $this->db->error();    
      log_message( 'error', 'app/model/status/E-077 Error: Config ts for user ' . $email . ' could not be removed: ' . $error );
    }
    
    return;
  }
  /* end of reset_ts_config */
  
  

  /**
   * Saves Layer visibility or opacity to the corresponding field/table
   *
   * @access	public
   * @param   table and field to be update with layer id and value
   * @return	nothing
  */
  function set_layer_visib( $table, $field, $id, $val )
  {
		$this->load->database();
    $email = $this->session->userdata( 'email' );
    
    $sql = "UPDATE " . $table 
         . "   SET " . $field . " = " . $val // only num
         . " WHERE user_email = '" . $email . "' ";
    if( $table == 'user_layers' ) // needs additional filtering
      $sql .="   AND config_loaded = 1 " 
           . "   AND config_order = " . $id;
    if( ! $this->db->query( $sql ) )
    {
      $error = $this->db->error();    
      log_message( 'error', 'app/model/status/E-059 Error: Config layer ' . $id . ' visib/opac ' . $val . ' for user ' . $email . ' could not be saved: ' . $error );
    }
  }
  /* end of set_layer_visib */
  

  
  /**
   * Get perma config - called from login in controller Mapa
   *
   * @access	public
   * @param   nothing (the session user email)
   * @return	object with perma config data for the current user
  */
 	function get_config_perma()
  {
		$this->load->database();
    $email = $this->session->userdata( 'email' );
    
    $this->db->where( 'user_email', $email );    
		$query = $this->db->get( 'user_config' );
    if( $query->num_rows() == 0 ) 
    {
      log_message( 'error', 'app/model/status/E-052 Error: Config for user ' . $email . ' not found.' );
      return FALSE;
    }
    $result = $query->result();
    return $result[0];
  }
  /* end of get_config_perma */

  /**
   * Get ts config - called from login in controller Mapa
   *
   * @access	public
   * @param   nothing (the session user email)
   * @return	object with ts config data for the current user
  */
 	function get_ts_config()
  {
		$this->load->database();
    $email = $this->session->userdata( 'email' );
    
    $this->db->select('*');
    $this->db->from('user_timeseries');
    $this->db->join('timeseries', 'timeseries.ts_name = user_timeseries.ts_name');
    $this->db->where( 'user_email', $email );    
    $this->db->where( 'config_visible', 1 );    
    // 1.msbas 2.histogram 3.gnss
    $this->db->order_by( 'ts_type DESC, config_order ASC' );  
		$query = $this->db->get(); 
    
    if( $query->num_rows() == 0 ) 
    {
      log_message( 'error', 'app/model/status/E-058 Error: Ts config for user ' . $email . ' not found.' );
      return FALSE;
    }
    $result = $query->result();
    return $result; 
  }
  /* end of get_ts_config */


  /**
   * Get layer visib - called from controller Mapa
   *
   * @access	public
   * @param   nothing (the session user email)
   * @return	object with layer visib data (two tables)
  */
 	function get_layer_visib()
  {
		$this->load->database();
    $email = $this->session->userdata( 'email' );
    
    $this->db->select('*');
    $this->db->from('user_layers');
    // 1 row in user_config, its fields will be repeated for all user_layers
    $this->db->join('user_config', 'user_layers.user_email = user_config.user_email', 'right');
    $this->db->where( 'user_layers.user_email', $email );    
    $this->db->where( 'config_loaded', 1 );    
    $this->db->order_by( 'user_layers.config_order ASC' );  
		$query = $this->db->get(); 
    
    if( $query->num_rows() == 0 ) 
    {
      log_message( 'error', 'app/model/status/E-060 Error: Layer config for user ' . $email . ' not found.' );
      return FALSE;
    }
    $result = $query->result();
    return $result; 
  }
  /* end of get_layer_visib */
  
}

/* End of file Status_model.php */
/* Location: ./application/models/Status_model.php */