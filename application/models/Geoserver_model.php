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
   * @return	the curl executable in win or linux. 
   *          NOTE: in windows, ___full___ path to curl exe is needed
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
        $curl = '"D:\\wisdomvolkano\\cURL\\bin\\curl.exe"';      
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
    // $curl_call = $curl_app . ' -u ' . $this->config->item( 'geoserver_userpwd' ) . ' -v -XGET -H "Accept: text/xml" ' . $this->config->item( 'geoserver_rest' ) . '/workspaces/ ' . $debug; 
    $curl_call = $curl_app . ' -u ' . $this->config->item( 'geoserver_userpwd' ) . ' -H "Accept: application/xml" ' . $this->config->item( 'geoserver_rest' ) . $this->debug; // . '/workspaces/ ';  -v -XGET 
    // log_message('error', $curl_call );
    $out = shell_exec( $curl_call );
    // log_message('error', $out . "----" . $err );
    $xml = simplexml_load_string( $out );
    // log_message('error', $xml );
    if( ( ! ( $xml instanceof SimpleXMLElement ) ) || empty( $xml ) )
    {
      log_message( 'error', 'app/model/geoserver/E-092 Error Cannot connect to GeoServer. Is it running?' );
      return false;
    }
    
    return $xml;
  }

  /**
   * Pings a Geoserver layer to see if it has data. 
   *   Function called from controller/Layer and from controller/Mapa
   *
   * @access	public
   * @param   layer name and type
   * @return	boolean
  */
 	function ping_layer( $layer_name, $layer_type )
  {
    if( $layer_name == "" or $layer_type == "" ) 
    {
      // log_message('error', 'ping empty call ' );
      return false;
    }
    $FeatOrRaster = "";
    $FeatOrRaster2 = "";
    switch( substr( $layer_type, 0, 4 ) ) // possible values: see $this->layer_types in controller Layer
    {
      case "feat": $FeatOrRaster  = $this->config->item('feat_v_rast_1'); 
                   $FeatOrRaster2 = $this->config->item('feat_v_rast_2'); 
                   break; 
        
      case "rast": // both rasters and DEM are coverages
      case "dem" : $FeatOrRaster  = $this->config->item('rast_v_feat_1'); 
                   $FeatOrRaster2 = $this->config->item('rast_v_feat_2'); 
                   break;
    }
    $url = $this->config->item( 'geoserver_url' ) . 'rest/workspaces/' 
         . substr( $layer_name, 0, strpos( $layer_name, ":" ) ) . "/" 
         . $FeatOrRaster . "/"
         . substr( $layer_name, strpos( $layer_name, ":" ) + 1 ) . "/"
         . $FeatOrRaster2 . "/"
         . substr( $layer_name, strpos( $layer_name, ":" ) + 1 ) . ".xml"; // ".html"
    
    $curl_app = $this->get_curl_env();
    // $curl_call = $curl_app . ' -u ' . $this->config->item( 'geoserver_userpwd' ) . ' -v -XGET -H "Accept: text/xml" ' . $url . ' ' . $debug; 
    $curl_call = $curl_app . ' -u ' . $this->config->item( 'geoserver_userpwd' ) . ' -H "Accept: application/xml" ' . $url . ' ' . $this->debug; 
    // log_message('error', 'CURL CALL >> ' . $curl_call );
    $out = shell_exec( $curl_call ); 
    // log_message('error', 'CURL OUTPUT >> ' . $out );
    $xml = simplexml_load_string( $out );
    // log_message('error', 'OUT2XML >> ' . var_dump( $xml ) );
    
    // if( ( ! ( $xml instanceof SimpleXMLElement ) ) || empty( $xml ) )
    if( strpos( $xml, 'No such coverage' ) === true ) 
    {
      $err = "Error GeoServer layer $url is not enabled, please check it in GeoServer.";
      log_message( 'error', "app/model/geoserver/E-094 " . $err );
      return false;
    }
    else return $xml;
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
        // $curl_call = $curl_app . ' -u ' . $this->config->item( 'geoserver_userpwd' ) . '-v -XGET -H "Accept: text/xml" ' . $this->config->item( 'geoserver_rest' ) . $ws . '/coveragestores/' . $debug; 
        $curl_call = $curl_app . ' -u ' . $this->config->item( 'geoserver_userpwd' ) . '-v -XGET -H "Accept: application/xml" ' . $this->config->item( 'geoserver_rest' ) . $ws . '/coveragestores/' . $debug; 
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
    
    // $curl_call = $curl_app . ' -u ' . $this->config->item( 'geoserver_userpwd' ) . ' -v -XGET -H "Accept: text/xml" ' . $this->config->item( 'geoserver_rest' ) . $debug; 
    $curl_call = $curl_app . ' -u ' . $this->config->item( 'geoserver_userpwd' ) . ' -v -XGET -H "Accept: application/xml" ' . $this->config->item( 'geoserver_rest' ) . $debug; 

    //echo( "" . $curl_call . " " );
    $out = shell_exec( $curl_call ); 
    //echo( "" . $out . "" );
    // TBD: if there's no connection to Geoserver, the next line fails
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
      // $curl_call = $curl_app . ' -u ' . $this->config->item( 'geoserver_userpwd' ) . ' -v -XGET -H "Accept: text/xml" ' . $this->config->item( 'geoserver_rest' )  . $element->name . $rest_ws_ras . $debug;  // first, the rasters
      $curl_call = $curl_app . ' -u ' . $this->config->item( 'geoserver_userpwd' ) . ' -v -XGET -H "Accept: application/xml" ' . $this->config->item( 'geoserver_rest' )  . $element->name . $rest_ws_ras . $debug;  // first, the rasters

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
      // $curl_call = $curl_app . ' -u ' . $this->config->item( 'geoserver_userpwd' ) . ' -v -XGET -H "Accept: text/xml" ' . $this->config->item( 'geoserver_rest' )  . $element->name . $rest_ws_fea . $debug;  // second, the features
      $curl_call = $curl_app . ' -u ' . $this->config->item( 'geoserver_userpwd' ) . ' -v -XGET -H "Accept: application/xml" ' . $this->config->item( 'geoserver_rest' )  . $element->name . $rest_ws_fea . $debug;  // second, the features

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
    // $curl_call = $curl_app . ' -u ' . $this->config->item( 'geoserver_userpwd' ) . ' -v -XGET -H "Accept: application/xml" ' . $p_url; 

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