<?php
/**
 * Layer Model
 *
 * @package		CodeIgniter
 * @subpackage	Models
 * @version	  1.0
 * @author		Fulgencio Sanmartín
 * @link		email@fulgenciosanmartin.com
*/
class Layer_model extends CI_Model {
  var $creator           = "";
	var $layer_name_ws     = "";
  var $layer_type        = "";
  var $layer_description = "";
	
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
	function index()
	{
	}
  
  /**
   * Create a layer in the system
   *
   * @access	public
   * @param   the layer name, and optionally which type it is
   * @return	nothing
  */
	function set_layer_new( $layer, $type = "raster", $description = "" )
	{
		$this->load->database();
    $this->creator           = $this->session->userdata( 'email' );
    $this->layer_type        = $type; 
    $this->layer_name_ws     = $layer;
    $this->layer_description = $description;
    $this->db->insert( 'layer', $this );      

    if( $this->db->affected_rows() == 0 )
    {
      log_message( 'error', 'app/model/layer/E-006 Error Cannot create the layer ' . $this->layer . '.' );
    }
    return;
  }
  /* end of function set_layer_new */
  
  /**
   * Edits a layer in the system
   *
   * @access	public
   * @param   the layer name, and optionally which type it is
   * @return	nothing
  */
	function set_layer_edit( $layer, $type = "raster", $description = "" )
	{
    $sql = "UPDATE layer SET layer_name_ws = ?, layer_type = ?, layer_description = ? WHERE layer_name_ws = ?";
    $this->db->query( $sql, array( $layer, $type, $description, $layer ) );
    
    if( $this->db->affected_rows() == 0 )
    {
      log_message( 'error', 'app/model/layer/E-016 Error Cannot edit the layer ' . $layer . '.' );
    }
    return;
  }
  /* end of function set_layer_edit */
  
  /**
   * Delete a layer from the system
   *
   * @access	public
   * @param   the layer 
   * @return	nothing
   * Note: CASCADE delete: it will remove the layer for all users
   * TBD: Log!
  */
	function del_layer( $layer )
	{
		$this->load->database();
    $this->db->where( 'layer_name_ws', $layer );
    $this->db->delete( 'layer' );      

    $nrows = $this->db->affected_rows();
    if( $nrows == 0 )
    {
      log_message( 'error', 'app/model/layer/E-007 Error Cannot remove layer ' . $layer . '.' );
    }
    return $nrows;
  }

  /**
   * Get a layer 
   *
   * @access	public
   * @param   the layer name 
   * @return	the record
  */
  function get_layer( $layer )
  {
		$this->load->database();
		$this->db->where( 'layer_name_ws', $layer );
		$this->db->select( 'layer_id, creator, layer_type, layer_description' );
		$query = $this->db->get( 'layer' );
    if( $query->num_rows() == 0 )
    {
        log_message( 'error', 'app/model/layer/E-008 Error This layer ' . $layer . ' does not exist.' );
        return 0;
    }
    else 
    {
        return $query->row();
    }
	}  
  /* end function get_layer */
  
  /**
   * Get if a layer exists
   *
   * @access	public
   * @param   the layer name 
   * @return	TRUE or FALSE
  */
  function get_layer_exist( $layer )
  {
		$this->load->database();
		$this->db->where( 'layer_name_ws', $layer );
		$this->db->select( 'layer_id' );
		$query = $this->db->get( 'layer' );
    $existe = ( $query->num_rows() > 0 );
    return $existe;
	}  
  /* end function get_layer_exist */
  
  
  /**
   * Get all layers, optionally of a given type
   *
   * @access	public
   * @param   optional type
   * @return	the result of layers 
  */
  function get_all_layers( $type = "" )
  {
		$this->load->database();
    if( $type != "" ) $this->db->where( 'layer_type', $type );
		$this->db->select( 'layer_id, creator, layer_name_ws, layer_type, layer_description' );
		$query = $this->db->get( 'layer' );
    if( $query->num_rows() == 0 )
    {
        log_message( 'error', 'app/model/layer/E-009 Error Cannot find any layers ' . $type  );
    }
		return $query->result();
	}
		
}

/* End of file layer_model.php */
/* Location: ./application/models/layer_model.php */