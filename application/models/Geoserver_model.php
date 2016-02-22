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
    //log_message('error', $curl_call );
    $out = shell_exec( $curl_call ); 
    //log_message('error', $out );
    $xml = simplexml_load_string( $out );
    //log_message('error', $xml );
    
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
   *  http://docs.geoserver.org/latest/en/user/rest/examples/curl.html
   *
   * @access	public
   * @return	array with layers 
  */
 	function get_all_layers()
  {
    $curl_app = $this->get_curl_env();
    $ar_return = array();
    $rest_all = 'layers/';
    $rest_ws_ras = '/coveragestores/'; 
    $rest_ws_fea = '/datastores/'; 
    $debug = ''; // '2>&1'; // '';
    
    $curl_call = $curl_app . ' -u ' . $this->config->item( 'geoserver_userpwd' ) . ' -v -XGET -H "Accept: text/xml" ' . $this->config->item( 'geoserver_rest' ) . $debug; 

    // log_message('error', "curl: " . $curl_call . " " );
    $out = shell_exec( $curl_call ); 
    // log_message('error', "curl out: " . $out . "" );
    $xml = simplexml_load_string( $out );
    // log_message('error', print_r( $xml ) );
    if( ( ! ( $xml instanceof SimpleXMLElement ) ) || empty( $xml ) )
    {
      log_message( 'error', 'app/model/geoserver/E-050 Error Cannot find any layer from GeoServer. Is it running?' );
      return;
    }
    
    $i = 0;
    foreach( $xml as $element ) 
    {
      // echo "<br> -------------- workspace #$i: $element->name -- rasters: <br/>";
      $curl_call = $curl_app . ' -u ' . $this->config->item( 'geoserver_userpwd' ) . ' -v -XGET -H "Accept: text/xml" ' . $this->config->item( 'geoserver_rest' )  . $element->name . $rest_ws_ras . $debug;  // first, the rasters

      //log_message('error', "raster: " . $curl_call . "" );
      $out2 = shell_exec( $curl_call ); 
      // print_r( $out2 );
      $xml2 = simplexml_load_string( $out2 );
      // print_r( $xml2 );
      
      if( $xml2 instanceof SimpleXMLElement ) 
      {
        // now I have two separates arrays, I need to mix them in one
        foreach( $xml2 as $element2 ) 
        {
          // echo "<br/> found raster #$i: $element->name:$element2->name ";
          $ar_return[ $i ] = $element->name . ":" . $element2->name;
          $i = $i + 1;
        }
      }

      // echo "<br> -------------- workspace #$i: $element->name -- features: <br/>";
      $curl_call = $curl_app . ' -u ' . $this->config->item( 'geoserver_userpwd' ) . ' -v -XGET -H "Accept: text/xml" ' . $this->config->item( 'geoserver_rest' )  . $element->name . $rest_ws_fea . $debug;  // second, the features

      // log_message('error', "feat: " . $curl_call . "" );
      $out2 = shell_exec( $curl_call ); 
      // print_r( $out2 );
      $xml2 = simplexml_load_string( $out2 );
      // print_r( $xml2 );
      
      if( $xml2 instanceof SimpleXMLElement ) 
      {
        // now I have two separates arrays, I need to mix them in one
        foreach( $xml2 as $element2 ) 
        {
            // echo "<br/> found feature #$i: $element->name:$element2->name ";
            $ar_return[ $i ] = $element->name . ":" . $element2->name;
            $i = $i + 1;
        }
      }
    }
    return $ar_return;
  }

  /**
   * Gets feature info from Geoserver and discards the unknown
   * Called from Geoserver controller 
   *
   * @access	public
   * @return	array with info
  */
  function ajaxGetFeatInfo( $urlGPSSta, $urlSeismSta, $urlSeismLoc )
  {
    $ret = [];
    $curl_app = $this->get_curl_env();
    // Filter through existing workspaces
    // $curl_call = $curl_app . ' -u ' . $this->config->item( 'geoserver_userpwd' ) . ' -v -XGET -H "Accept: text/xml" ' . $p_url; 

    $curl_call = $curl_app . ' "' . $urlGPSSta . '" '; 
    // log_message( 'error', $curl_call );
    $out = shell_exec( $curl_call ); 
    // log_message( 'error', $out );
    $json = json_decode( $out, true ); 
    if( count( $json['features'] ) ) // data available
      $ret['GPSSta'] = $json['features'][0]['properties']['Name']; 
    else
      $ret['GPSSta'] = "";

    $curl_call = $curl_app . ' "' . $urlSeismSta . '" '; 
    // log_message( 'error', $curl_call );
    $out = shell_exec( $curl_call ); 
    // log_message( 'error', $out );
    $json = json_decode( $out, true ); 
    if( count( $json['features'] ) ) // data available
      $ret['SeismSta'] = $json['features'][0]['properties']['Name']; 
    else
      $ret['SeismSta'] = "";
      
    $curl_call = $curl_app . ' "' . $urlSeismLoc . '" '; 
    // log_message( 'error', $curl_call );
    $out = shell_exec( $curl_call ); 
    // log_message( 'error', $out );
    $json = json_decode( $out, true ); 
    if( count( $json['features'] ) ) // data available
      $ret['SeismLoc'] = $json['features'][0]['properties']['date'] . "\n" 
           . $json['features'][0]['properties']['mb'] . " magnitude - " 
           . $json['features'][0]['properties']['Depth (km)'] . " km deep."; 
    else
      $ret['SeismLoc'] = "";
      
    return json_encode( $ret );
  }
  
 
}

/* End of file geoserver_model.php */
/* Location: ./application/helpers/geoserver_model.php */