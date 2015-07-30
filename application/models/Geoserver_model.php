<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Geoserver Model
 *
 * @package		CodeIgniter
 * @subpackage	Models
 * @version	  1.0
 * @author		Fulgencio Sanmartín
 * @link		email@fulgenciosanmartin.com
*/
class Geoserver_model extends CI_Model
{
  private $debug = ''; // 2>&1'; 
  
  /**
   * Calculates environment. Does not work as __construct
   *
   * @access	private
   * @return	the curl executable in win or linux
  */  
	private function get_curl_env()
	{
    $curl = '';
    switch( ENVIRONMENT )
    {
      case 'development':
        $curl = '"c:\\Users\\Rufus T. Firefly\\AppData\\Local\\Apps\\cURL\\bin\\curl"';      
        break;
      case 'testing':
      case 'production':
      default:
        $curl = "curl";
        break;
    }  
    return $curl;
	}
  
  /**
   * Reads Geoserver workspaces and returns an array with them
   *
   * @access	public
   * @param   none
   * @return	array with workspaces available
  */
 	function get_workspaces()
  {
    $curl_app = $this->get_curl_env();
    $curl_call = $curl_app . ' -u ' . $this->config->item( 'geoserver_userpwd' ) . ' -v -XGET -H "Accept: text/xml" ' . $this->config->item( 'geoserver_rest' ) . '/workspaces/ ' . $debug; 
    // print_r( $curl_call );
    $out = shell_exec( $curl_call ); 
    // print_r( $out );
    $xml = simplexml_load_string( $out );
    // print_r( $xml );
    
    return $xml;
  }
  
  /**
   * Reads Geoserver layers of a workspace, returns an array with them; if no workspace given, returns all layers
   *
   * @access	public
   * @param   [optional] workspace name
   * @return	array with layers 
  */
 	function get_layers( $param_workspace = "" )
  {
    $curl_app = $this->get_curl_env();
    // Filter through existing workspaces
    $workspaces = get_workspaces();
    $found = false;
    foreach( $workspaces as $ws ) 
    {
      if( $ws == $param_workspace ) 
      {
        $curl_call = $curl_app . ' -u ' . $this->config->item( 'geoserver_userpwd' ) . '-v -XGET -H "Accept: text/xml" ' . $this->config->item( 'geoserver_rest' ) . $ws . '/coveragestores/' . $debug; 
        $found = true;
      }      
    }    
    if( ! $found )  // return all layers
    {
      return get_all_layers();
    }
    else
    {
      //print_r( $curl_call );
      $out = shell_exec( $curl_call ); 
      //print_r( $out );
      $xml = simplexml_load_string( $out );
      //print_r( $xml );
      return $xml;
    }
  }

  
  /**
   * Reads all Geoserver layers of all workspace, returns an array with them
   *
   * @access	public
   * @return	array with layers 
  */
 	function get_all_layers()
  {
    $curl_app = $this->get_curl_env();
    $ar_return = array();
    $rest_all = 'layers/';
    $rest_ws_lay ='/coveragestores/';
    $debug = ''; // 2>&1'; 
    
    $curl_call = $curl_app . ' -u ' . $this->config->item( 'geoserver_userpwd' ) . ' -v -XGET -H "Accept: text/xml" ' . $this->config->item( 'geoserver_rest' ) . $debug; 

    // print_r( "<pre>" . $curl_call . "</pre>" );
    $out = shell_exec( $curl_call ); 
    // print_r( "<pre>" . $out . "</pre>" );
    $xml = simplexml_load_string( $out );
    // print_r( $xml );
    if( !$xml instanceof SimpleXMLElement || empty( $xml ) )
    {
      log_message( 'error', 'app/model/geoserver/E-050 Error Cannot find any layer from GeoServer. Is it running?' );
      return;
    }
    
    $i = 0;
    foreach( $xml as $element ) 
    {
      //echo " workspace #$i: $element->name -- layers: <br/>";
      $curl_call = $curl_app . ' -u ' . $this->config->item( 'geoserver_userpwd' ) . ' -v -XGET -H "Accept: text/xml" ' . $this->config->item( 'geoserver_rest' )  . $element->name . $rest_ws_lay . $debug; 

      // print_r( "<pre>" . $curl_call . "</pre>" );
      
      $out2 = shell_exec( $curl_call ); 
      // print_r( "<pre>" . $out . "</pre>" );
      $xml2 = simplexml_load_string( $out2 );
      
      // now I have two separates arrays, I need to mix them in one
      foreach( $xml2 as $element2 ) 
      {
        // echo " layer #$i: $element->name:$element2->name <br/>";
        $ar_return[ $i ] = $element->name . ":" . $element2->name;
        $i = $i + 1;
      }
    }
    return $ar_return;
  }


  
  /*
	function create_workspace( )
  {
  }
  
  
  function create_geotiff( )
  {
    // parameter:
    var $layername = 'curltest.geotiff'; 

    // step 1: crear el store sin nada dentro
    //   nota: en windows no funciona sin dobles comillas
    var $curl_call = 'curl -u progci:blMKZjdMBYmo7ka30yNVGZXSyoeoRCZM3 -v -XPOST -H "Content-type:application/xml" -d "<coverageStore><name>' . $layername . '</name><workspace>cint</workspace><enabled>true</enabled></coverageStore>" http://127.0.0.1:8080/geoserver/rest/workspaces/cint/coveragestores';
    
    // step 2: subir? el fichero, lo que crea tb el layer 
    var $curl_call = 'curl -u admin:geoserver -XPUT -H "Content-type: application/zip" --data-binary  @ filename.tiff http://localhost:8080/geoserver/rest/workspaces/cint/coveragestores/' . $layername . '/file.geotiff';
  }
  */
}

/* End of file getlayer_helper.php */
/* Location: ./application/helpers/getlayer_helper.php */