<?php
/**
 * Timeseries Model
 *
 * @package		CodeIgniter
 * @subpackage	Models
 * @version	  1.0
 * @author		Fulgencio Sanmartín
 * @link		email@fulgenciosanmartin.com
*/
class Timeseries_model extends CI_Model {
  var $creator        = "";
	var $ts_name        = "";
  var $ts_type        = "";
  var $ts_description = "";
	
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
	function index()
	{
	}
  
  /**
   * Create a timeseries in the system
   *
   * @access	public
   * @param   array with data
   * @return	nothing
  */
	function set_timeseries_new( $data )
	{
		$this->load->database();
    $data[ 'creator' ] = $this->session->userdata( 'email' );
    $this->db->insert( 'timeseries', $data );      

    if( $this->db->affected_rows() == 0 )
    {
      log_message( 'error', 'app/model/timeseries/E-017 Error Cannot create the timeseries ' . $tdata[ 'ts_name' ] . '.' );
    }
    return;
  }
  /* end of function set_timeseries_new */
  
  /**
   * Edits a timeseries in the system
   *
   * @access	public
   * @param   the timeseries name, and optionally which type it is
   * @return	nothing
  */
	function set_timeseries_edit( $ts, $type = "msbas", $description = "" )
	{
    $sql = "UPDATE timeseries SET ts_name = ?, ts_type = ?, ts_description = ? WHERE ts_name = ?";
    $this->db->query( $sql, array( $ts, $type, $description, $ts ) );
    
    if( $this->db->affected_rows() == 0 )
    {
      log_message( 'error', 'app/model/timeseries/E-018 Error Cannot edit the timeseries ' . $ts . '.' );
    }
    return;
  }
  /* end of function set_timeseries_edit */
  
  /**
   * Delete a timeseries from the system
   *
   * @access	public
   * @param   the timeseries id
   * @return	nothing
   * Note: CASCADE delete: it will remove the timeseries for all users
   * TBD: Log!
  */
	function del_timeseries( $ts_id )
	{
		$this->load->database();
    $this->db->where( 'ts_id', $ts_id );
    $this->db->delete( 'timeseries' );      

    $nrows = $this->db->affected_rows();
    if( $nrows == 0 )
    {
      log_message( 'error', 'app/model/timeseries/E-019 Error Cannot remove timeseries ' . $ts_id . '.' );
    }
    return $nrows;
  }
  /* end of function del_timeseries */

  /**
   * Get a timeseries
   *
   * @access	public
   * @param   the timeseries name 
   * @return	the record
  */
  function get_timeseries( $ts )
  {
		$this->load->database();
		$this->db->where( 'ts_name', $ts );
		// $this->db->select( 'ts_id, creator, ts_type, ts_description' ); // *
		$query = $this->db->get( 'timeseries' );
    if( $query->num_rows() == 0 )
    {
        log_message( 'error', 'app/model/timeseries/E-020 Error This timeseries ' . $ts . ' does not exist.' );
        return null;
    }
    else 
    {
        return $query->row();
    }
	}  
  /* end function get_timeseries */
  
  /**
   * Get if a timeseries exists
   *
   * @access	public
   * @param   the timeseries name 
   * @return	TRUE or FALSE
  */
  function get_timeseries_exist( $ts )
  {
		$this->load->database();
		$this->db->where( 'ts_name', $ts );
		$this->db->select( 'ts_id' );
		$query = $this->db->get( 'timeseries' );
    $existe = ( $query->num_rows() > 0 );
    return $existe;
	}  
  /* end function get_timeseries_exist */
  
  
  /**
   * Get all timeseries, optionally of a given type
   *
   * @access	public
   * @param   optional type
   * @return	the result of timeseries
  */
  function get_all_timeseries( $type = "" )
  {
		$this->load->database();
    if( $type != "" ) $this->db->where( 'ts_type', $type );
		// $this->db->select( 'ts_id, creator, ts_name, ts_type, ts_description' ); // select * 
		$query = $this->db->get( 'timeseries' );
    if( $query->num_rows() == 0 )
    {
        log_message( 'error', 'app/model/timeseries/E-021 Error Cannot find any timeseries ' . $type  );
    }
		return $query->result();
	}
  /* end function get_all_timeseries */
		
}

/* End of file timeseries_model.php */
/* Location: ./application/models/timeseries_model.php */