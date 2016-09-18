<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mapa extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function Index()
	{
		if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
    
    // 0. is geoserver running? 
    $this->load->model( 'geoserver_model', 'geoserver' );
    $result = $this->geoserver->get_workspaces();
    if( $result == false )
    {
      $err = 'Error: GeoServer is not available.';
      log_message( 'error', 'app/controller/Mapa/E-093 ' . $err ); 
      show_error( $err );
      exit( -1 );
    }
    
    // 1. Load granted / available layers
    $this->load->model( 'Userlayer_model', 'Userlayer' );
    $result = $this->Userlayer->get_all_layers( $this->session->userdata( 'email' ) );
    
    if( ! $result )
    {
      $err = 'Error: No layers available. If Geoserver is running, please ask the admin to grant you some.';
      log_message( 'error', 'app/controller/Mapa/E-001 ' . $err ); 
      $data[ 'layers' ] = $err;
    }
    else
    { 
      // 1.1. are layers available in GeoServer? 
      foreach( $result as $layer )
      {
        $ping = $this->geoserver->ping_layer( $layer->layer_name_ws, $layer->layer_type ); 
        if( $ping === false )
          redirect( 'layer' );
      }
      
      $data[ 'layers' ] = $result;
    }

    // 2. Load granted / available timeseries
    $this->load->model( 'Userts_model', 'userts' );
    $result = $this->userts->get_all_timeseries( $this->session->userdata( 'email' ) );
    if( ! $result )
    {
      $err = 'Error: No timeseries available. Please ask the admin to grant you some.';
      log_message( 'error', 'app/controller/Mapa/E-041 ' . $err ); 
      $data[ 'ts' ] = $err;
    }
    else
    { 
      $data[ 'ts' ] = $result;
      $data[ 'left'  ] = 0;
      $data[ 'right' ] = 0;
      $data[ 'top'   ] = 0;
      $data[ 'down'  ] = 0;
   
      $this->load->model( 'timeseries_model', 'ts_model' );
      // only for msbas ts
      foreach( $result as $tss )
      {
        $ts = $this->ts_model->get_timeseries( $tss->ts_name );
        if( $ts->ts_type == "msbas" )
        {
          $file = array( 0 => ( $this->config->item( 'folder_msbas' ) . trim( $ts->ts_file ) . $this->config->item( 'folder_msbas_ras' ) . trim( $ts->ts_file_raster ) ) );

          if( file_exists( $file[0] ))
          {
            $this->load->library( 'EnviHeader', $file );
            $enviheader = new EnviHeader( $file );
            $enviheader->read_header();
        
            $data[ 'left'  ] = $ts->ts_coord_lon_left;
            $data[ 'right' ] = $ts->ts_coord_lon_left 
                + $ts->ts_coord_lon_inc * $enviheader->get_value( "nCol" );
            // TBD: negative!
            $data[ 'top'   ] = $ts->ts_coord_lat_top;
            $data[ 'down'  ] = $ts->ts_coord_lat_top 
                - $ts->ts_coord_lat_inc * $enviheader->get_value( "nRow" );
                
          }
          else // default
          {
            $data[ 'left'  ] = -1.1;
            $data[ 'right' ] = -1.9;
            $data[ 'top'   ] = 29.1;
            $data[ 'down'  ] = 29.8;
            
          }
          break; // one set of boundaries is enough
        }
      }
    }
    
    // 3.1 Load permalink config
    $this->load->model( 'status_model', 'status' );
    if( ! isset( $_SESSION[ 'zoom' ] ) )
    {
      $config = $this->status->get_config_perma();
      if( $config !== FALSE ) // loading previous config from table
      {
        $_SESSION[ 'zoom' ] = intval( $config->zoom );
        $_SESSION[ 'lat' ]  = floatval( $config->lat );
        $_SESSION[ 'lon' ]  = floatval( $config->lon ); 
      }
      else // no config found, default
      {
        $_SESSION[ 'zoom' ] = $this->config->item( 'default_zoom' );
        $_SESSION[ 'lat' ] = $this->config->item( 'default_lat' );
        $_SESSION[ 'lon' ] = $this->config->item( 'default_lon' ); 
      }
    }
    // 3.2 Load ts config 
    if( ! isset( $_SESSION[ 'ts_msbas' ] ) )
    {
      $config = $this->status->get_ts_config();
      $_SESSION[ 'ts_msbas_num' ] = 0;
      $_SESSION[ 'ts_histo_num' ] = 0;
      $_SESSION[ 'ts_gnss_num' ]  = 0;
      $_SESSION[ 'ts_msbas' ]     = [];  
      $_SESSION[ 'ts_lat' ]       = [];
      $_SESSION[ 'ts_lon' ]       = []; 
      $_SESSION[ 'ts_histo' ]     = [];  
      $_SESSION[ 'ts_gnss' ]      = [];  
      if( $config !== FALSE ) // loading previous config from table
      {
        // only need one row per type: msbas, histo, gnss
        $a_histo = array(); $a_gnss = array();
        for( $i = 0; $i < count( $config ); $i ++ )
        {
          switch( $config[$i]->ts_type )
          {
            case 'msbas': 
              $deco = json_decode( $config[$i]->config_msbas );
              $_SESSION[ 'ts_msbas' ] = $deco[ 0 ];  
              $_SESSION[ 'ts_lat' ]   = $deco[ 1 ];
              $_SESSION[ 'ts_lon' ]   = $deco[ 2 ]; 
              $msbas_count = substr_count($deco[0], ",") + 1;
              $_SESSION[ 'ts_msbas_num' ] = $msbas_count;
              break;
            case 'histogram':
              array_push( $a_histo, $config[$i]->ts_name );
              break;
            case 'gnss':
              array_push( $a_gnss,  $config[$i]->ts_name );
              break;
          }
        } // for
        $_SESSION[ 'ts_histo_num' ] = count( $a_histo );
        if( count( $a_histo ) > 0 ) 
          $_SESSION[ 'ts_histo' ] = '["' . implode( '","', $a_histo ) . '"]';
        
        $_SESSION[ 'ts_gnss_num' ]  = count( $a_gnss );
        if( count( $a_gnss ) > 0 ) 
          $_SESSION[ 'ts_gnss' ] = '["' . implode( '","', $a_gnss ) . '"]';
      }
    }
    
    // 3.3 Load layer config
    $res = $this->status->get_layer_visib();
    if( $res )
    {
      $data[ 'layvis' ] = $res;
    }
    else
    { 
      $data[ 'layvis' ][0] = (object) array(
        'bg1_visible' => 1,
        'bg1_opacity' => 1,
        'bg2_visible' => 1,
        'bg2_opacity' => 1,
        'bg3_visible' => 1,
        'bg3_opacity' => 1,
        'bg4_visible' => 1,
        'bg4_opacity' => 1,
        'config_visible' => 1,
        'config_opacity' => 1
      );
    }

    // 3.3.1. internet connection to load background layers? TBD use
    $ping = @fsockopen( 'maps.google.com' );
    if( $ping )
    {
      $data[ 'internet' ] = 'yes';
      @fclose( $ping );
    }
    else
    {
      $data[ 'internet' ] = 'no';
    }
    
    // 3.3.2. api key defined to load google maps layer? TBD use
    if( $this->config->item( 'gmaps_key' ) == '' )
      $data[ 'gmap' ] = 'no';
    else
      $data[ 'gmap' ] = 'yes';
      
    
    $this->load->helper( array( 'menu', 'form' ) );
    $this->load->view( 'mapa', $data ); 
    // $this->load->view( 'footer' );
	}
  /* end of function index */
  
  
	/**
	 * load_ts_async Loads asynchronously (ajax) all timeseries
	 *
	 * Maps to
	 * 		http://server/index.php/Mapa/load_ts_async/
	 *
   * @access	public
   * @param   the complete set of timeseries 
   * @return	filters data and calls the adequate function 
	 */
  public function load_ts_async()
  {
    $this->load->model( 'Userts_model', 'userts' );
    $this->load->model( 'Timeseries_model', 'ts' );
    $ps_msbas = json_decode( $this->input->post('ts_msbas'), true );
    $ps_histo = json_decode( $this->input->post('ts_histo'), true );
    $ps_gnss  = json_decode( $this->input->post('ts_gnss'), true );
    
    $a_lon    = array();
    $a_lat    = array();
    $ts_msbas = array(); $ts_histo = array(); $ts_gnss = array();
    $ps_lon   = json_decode( $this->input->post('lon'), true ); 
    $ps_lat   = json_decode( $this->input->post('lat'), true ); 
  
    // filter all msbas should exist and be granted to the user
    $totMsbas = count( $ps_msbas );
    for( $i = 0; $i < $totMsbas; $i ++ )
    {
      if( is_null( $this->userts->get_ts_user( $ps_msbas[ $i ], $this->session->userdata( 'email' ) ) ) )
      {
        log_message( 'error', 'app/controller/Mapa/E-042 Error: Not found ts ' . $ps_msbas[ $i ] . ' or not granted ts to user.' );
        // echo 'alert( "Error: Not found timeseries ' . $ps_msbas[ $i ] . ' or not granted to user.")';
        return;
      }
      else
      { 
        // filter all lat and lon be numbers for coords
        if( ! is_numeric( $ps_lon[ $i ] ) || ! is_numeric( $ps_lat[ $i ] ) )
        {
          log_message( 'error', 'app/controller/Mapa/E-043 Error: Coordinates lat ' . $ps_lat[ $i ] . ' and lon ' . $ps_lon[ $i ] . ' are not numeric.' );
          // echo 'alert( "Error: Coordinates lat ' . $ps_lat[ $i ] . ' and lon ' . $ps_lon[ $i ] . ' are not numeric." )';
          return;
        }
      }
      $a_lat[ $i ] = round( floatval( $ps_lat[ $i ] ), 3 ); // 3 decimals
      $a_lon[ $i ] = round( floatval( $ps_lon[ $i ] ), 3 );

      $ts_msbas[ $i ] = $this->ts->get_timeseries( $ps_msbas[ $i ] );
      if( is_null( $ts_msbas[ $i ] ) )
      {
        log_message( 'error', 'app/controller/Mapa/E-044 Error: Cannot find ts_msbas ' . $ts_msbas[ $i ] . '.' );
        //echo 'alert( "Error: Cannot find the timeseries ' . $ts_msbas[ $i ] . '." )';
        return;
      }
    }

    // filter all histo should exist and be granted to the user
    $totHisto = count( $ps_histo );
    for( $i = 0; $i < $totHisto; $i ++ )
    {
      if( is_null( $this->userts->get_ts_user( $ps_histo[ $i ], $this->session->userdata( 'email' ) ) ) )
      {
        log_message( 'error', 'app/controller/Mapa/E-048 Error: Not found ts ' . $ps_histo[ $i ] . ' or not granted ts to user ' . $this->session->userdata( 'email' ) . '.' );
        // echo 'alert( "Error: Not found timeseries ' . $ps_histo[ $i ] . ' or not granted to user ' . $this->session->userdata( 'email' ) . '")';
        return;
      }
      $ts_histo[ $i ] = $this->ts->get_timeseries( $ps_histo[ $i ] );
      if( is_null( $ts_histo[ $i ] ) )
      {
        log_message( 'error', 'app/controller/Mapa/E-049 Error: Cannot find ts_histogram ' . $ts_histo[ $i ] . '.' );
        // echo 'alert( "Error: Cannot find the timeseries ' . $ts_histo[ $i ] . '." )';
        return;
      }
    }

    // filter all histo should exist and be granted to the user
    $totGnss = count( $ps_gnss );
    for( $i = 0; $i < $totGnss; $i ++ )
    {
      if( is_null( $this->userts->get_ts_user( $ps_gnss[ $i ], $this->session->userdata( 'email' ) ) ) )
      {
        log_message( 'error', 'app/controller/Mapa/E-069 Error: Not found ts ' . $ps_gnss[$i] . ' or not granted ts to user.' );
        // echo 'alert( "Error: Not found timeseries ' . $ps_gnss[ $i ] . ' or not granted to user.")';
        return;
      }
      $ts_gnss[ $i ] = $this->ts->get_timeseries( $ps_gnss[ $i ] );
      if( is_null( $ts_gnss[ $i ] ) )
      {
        log_message( 'error', 'app/controller/Mapa/E-070 Error: Cannot find ts_gnss ' . $ts_gnss[ $i ] . '.' );
        // echo 'alert( "Error: Cannot find the timeseries ' . $ts_gnss[ $i ] . '." )';
        return;
      }
    }
    
    // for the latest msbas, look for the file with the point $lat $lon, 
    //   and if it doesn't exist, calculate and then echo 
    if( $totMsbas > 0 )
    {
      $this->msbas_point_file_generate( $ts_msbas[$totMsbas-1], $a_lon[$totMsbas-1], $a_lat[$totMsbas-1] );
    }
    
    // main call: echo (ajax) the whole list of charts
    $this->echo_async( $totMsbas, $ts_msbas, $a_lon, $a_lat, $totHisto, $ts_histo, $totGnss, $ts_gnss );
	}
  /* end of function load_ts_async */
  
  
  
	/**
	 * msbas_point_file_generate Checks if the coordinate point file exists, and if it doesn't, creates it
	 *
   * @access	private
   * @param   the timeseries and the point coords arrays
   * @return	void
	 */
  private function msbas_point_file_generate( $msbas, $lon, $lat )  
  {
    $this->load->helper( 'coord' );
    
    // 0. convert coords to row,col
    $xcol = coord2pix( $lon, $msbas->ts_coord_lon_left, $msbas->ts_coord_lon_inc );
    $yrow = coord2pix( $lat, $msbas->ts_coord_lat_top, $msbas->ts_coord_lat_inc );
    
    // 1. get file name
    $this->load->model( 'ts_folder_model', 'tsfolder' );
    $file = $this->tsfolder->get_ts_file_name( $msbas, "disk", $xcol, $yrow );
    
    // 2. if the file exists, return (nothing to do)
    if( file_exists( $file ) )
      return;
    // 3. if not, calculate and create
    else
    {
      $raster = $this->config->item( 'folder_msbas' ) 
                . trim( $msbas->ts_file ) 
                . $this->config->item( 'folder_msbas_ras' ) 
                . trim( $msbas->ts_file_raster );
      $a_file = array( 0 => $raster );
      $this->load->library( 'EnviHeader', $a_file );          
      $enviheader = new EnviHeader( $a_file );
      $enviheader->read_header();
      
      $new_ts = $enviheader->calc_pixel_stack( $msbas->ts_file_raster_ini_date, $xcol, $yrow );
      
      $this->load->model( 'Ts_folder_model', 'ts_folder' );
      $this->ts_folder->create_file_ts( $file, $new_ts );
    }
  }
  /* end of function msbas_point_file_generate */
    
  
  
	/**
	 * echo_async Echoes (ajax call) the js msbas series of a point
	 *
   * @access	private
   * @param   the number of timeseries, the ts, and the point coords arrays
   * @return	echoes (ajax!) the highcharts.com javascript code for the timeseries 
	 */
  private function echo_async( $totMsbas, $ts_msbas, $lon, $lat, $totHisto, $ts_histo, $totGnss, $ts_gnss )
  {
    echo "var fecha, lines, items, val;                            \n";
    echo "var dataEW = []; // [ dates, values ] \n";
    echo "var dataNS = []; // [ dates, values ] \n";
    echo "var dataUP = []; // [ dates, values ] \n";
    
    echo "$(function () {                                          \n";
    echo "  var numCharts = Highcharts.charts.length; \n"; 

/*    
    echo 
"  $('#chart0').bind('mousemove touchmove', function (e) { \n".
"      var chart, point, i; \n".
"      for( i = 1; i <= numCharts; i++ ) { \n".
"          if( typeof Highcharts.charts[i] != 'undefined') \n" .
"          { \n".
//"             console.log( '' + i + '.- ' + Highcharts.charts[i] ); \n".
"             chart = Highcharts.charts[i]; \n".
"             e = chart.pointer.normalize(e);  \n".
"             point = chart.series[0].searchPoint(e, true);  \n".
"             if (point) { \n".
"               point.onMouseOver(); \n".
"               chart.tooltip.refresh(point); \n".
"               chart.xAxis[0].drawCrosshair(e, point); \n".
"             } \n".
"          }\n".
"      } \n".
"  }); \n";
    echo 
"  Highcharts.Pointer.prototype.reset = function () { \n".
"      return undefined; \n".
"  };   \n"; 
*/

    if( $totMsbas == 0 ) // back to empty chart, copy of ts-empty.js
    {
      echo 
       "  $('#chart0').highcharts({ \n" .
       "    chart: { type: 'column', zoomType: 'x' }, \n" .
       "    title: { text: 'No data yet' }, \n" .
       "    xAxis: { type: 'datetime' }, \n" .
       "    series: [{  \n" .
       "      name: 'Select a point in the map in order to show the timeseries', \n" .
       "      lineWidth: 0, \n".
       "      color: '#fff', \n".
       "      enableMouseTracking: false, \n".
       "      formatter: function() { return false; }, \n".
       "      dataLabels: { \n".
       "        enabled: true, \n".
       "        rotation: -90, \n".
       "        color: '#000', // 877 \n".
       "        align: 'left', \n" .
       "        formatter: function() { return ar[ this.x ]; }, \n".
       "        y: -2, // pixels from the origin \n" .
       "        style: { \n".
       "          fontSize: '9px', \n".
       "          fontFamily: 'Arial Narrow, Arial, Helvetica Condensed, Helvetica, sans-serif' \n". 
       "        } \n".
       "      }, \n".
       "      data: dataH  \n" .
       "    }], \n" .
       "    exporting: { buttons: { contextButton: { align: 'left' } } } \n".
       " });  \n";
    }    
    else // at least one msbas
    {
      $this->load->helper( 'coord' );
    
      $detrend = array();
      for( $i = 0; $i < $totMsbas; $i ++ )
      {
        $xcol = coord2pix( $lon[$i], $ts_msbas[$i]->ts_coord_lon_left, $ts_msbas[$i]->ts_coord_lon_inc );
        $yrow = coord2pix( $lat[$i], $ts_msbas[$i]->ts_coord_lat_top, $ts_msbas[$i]->ts_coord_lat_inc );

        $this->load->model( 'ts_folder_model', 'tsfolder' );
        $file = $this->tsfolder->get_ts_file_name( $ts_msbas[$i], "uri", $xcol, $yrow );

        // if detrend exists for that point, get it instead
        $detrend[ $i ] = false;
        $this->load->model( 'detrend_model', 'detrend' );
        if( $this->detrend->detrend_exists( "msbas", $ts_msbas[$i]->ts_name, "", $lat[$i], $lon[$i] ) )
        {
          $detrend[ $i ] = true;
          $file = $this->detrend->get_detrend_filepath( "msbas", $ts_msbas[$i]->ts_name, "", $lat[$i], $lon[$i], 'detrend', 'uri' );
        }
        // log_message( 'error', $i . ' - file ' . $file );
        
        echo "  var tsv" . $i . " = $.get( '" . $file . "' );        \n";
        // echo "  console.log( 'finished loading $file ' ); ";
      }
      
      // wait for async calls to be finished
      for( $i = 0; $i < $totMsbas; $i ++ )
      {
        echo "  tsv" . $i . ".done( function( csv" . $i . " ) {        \n" .
            "     if( csv" . $i . " == 'false' ) return 'Data not found'; // TBD \n" .
            "     else {                                               \n";
      }
      
      for( $i = 0; $i < $totMsbas; $i ++ )
      {    
        echo "  var data" . $i ." = []; // [ dates, values ]          \n";
      }

      for( $i = 0; $i < $totMsbas; $i ++ )
      {
        echo "  lines = csv" . $i . ".split( '\\n' );                  \n" .
            "  $.each( lines, function( lineNo, line ) {               \n" .
            "    items = line.split( '\\t' );                          \n" .
            "    fecha = tick2Date( items[ 0 ] );                      \n" .
            "    data".$i.".push( [ fecha, parseFloat( items[1] ) ] );\n" .
            "  });                                                     \n";
      }

      // load every msbas in the same chart 
      echo "  $('#chart0').highcharts({                           \n" .
          "    chart: { type: 'line', zoomType: 'x' },                 \n" .
          "    title: { text: 'Timeseries of MSBAS' },                 \n" .
          "    xAxis: {                                                \n" .
          "      type: 'datetime',                                     \n" .
          "      gridLineWidth: 1,                                     \n" .
          "      crosshair: true, \n ". 
          "      events: { \n".
//          "          setExtremes: syncExtremes \n".
          "      } \n".
          "    },                                                      \n" .
          "    yAxis: {                                                \n" .
          "      title: { text: 'displacement in cm' },                \n" .
          "      alternateGridColor: '#f5f5f5'                         \n" .
          "    },                                                      \n";
          
      echo "    series: [{                                             \n";
      
      // events
      echo "         name: 'events', \n" .
           "         lineWidth: 0, \n" .
           "         color: '#fff', \n" .
           "         showInLegend: false,  \n" .
           "         enableMouseTracking: false, \n" .
           //"         formatter: function() { return this.y; }, \n".
           "         dataLabels: { \n" .
           "             enabled: true, \n" .
           "             rotation: -90, \n" .
           "             color: '#000', // 877 \n" .
           "             align: 'left', \n" .
           "             formatter: function() { \n" .
           "               return ar[ this.x ]; \n" .
           "             }, \n" .
           "             y: -2, // pixels from the origin \n" .
           "             style: { \n" .
           "                 fontSize: '9px', \n" .
           "                 fontFamily: 'Arial Narrow, Arial, Helvetica Condensed, Helvetica, sans-serif' \n" .
           "             } \n" .
           "         }, \n" .
           "         data: dataH \n" .
           "     }, { \n";
      
      for( $i = 0; $i < $totMsbas; $i ++ )
      {
        $det = ( $detrend[ $i ] ) ? "detrended" : "";

        echo "      name:  '" . $det . " " . trim( $ts_msbas[$i]->ts_name ) .
             "           lat: " . $lat[$i] . " lon: " . $lon[$i] . "', \n" .
             "         showInLegend: true,  \n" .
             "         enableMouseTracking: true, \n" .
             "      data: data" . $i . "                               \n" .
             "    }                                                    \n";
        if( $i + 1 < $totMsbas ) echo " , {                            \n";
      }
      
      echo "    ],                                                     \n" .
          "    legend: {                                               \n" .
          "      align: 'right',                                       \n" .
          "      x: -30,                                               \n" .
          "      verticalAlign: 'top',                                 \n" .
          "      y: 25,                                                \n" .
          "      floating: true,                                       \n" .
          "      backgroundColor: (Highcharts.theme &&                 \n" .
          "            Highcharts.theme.background2) || 'white',       \n" .
          "      borderColor: '#CCC',                                  \n" .
          "      borderWidth: 1,                                       \n" .
          "      shadow: false                                         \n" . 
          "    },                                                      \n" .
          "    tooltip: {                                              \n" .
          "      useHTML: true,                                        \n" .
          "      formatter: function () {                              \n" .
          "        if (this.series.name != 'events') { \n".
          "var d = new Date( this.x );                                 \n" .
          "var day = d.getDate() + 1;  if( day < 10 ) day = '0' + day; \n" .
          "var mon = d.getMonth() + 1; if( mon < 10 ) mon = '0' + mon; \n" .
          "var fecha = d.getFullYear() + '-' + mon + '-' + day;        \n" .
          "var total = this.point.stackTotal;                          \n" .
          "var str = '<b>' + fecha + '</b><br/>' +                     \n" .
          "          this.series.name + ': ' + this.y + '<br/>';       \n" .
          "if( typeof total != 'undefined' )                           \n" .
          "  str = str + 'Total: ' + total;                            \n" .
          "return str;                                                 \n" .
          "        } // if not events \n".
          "      }                                                     \n" .
          "    },                                                      \n" .
          "    plotOptions: {                                          \n" .
          "        column: { stacking: 'normal' }                      \n" .  
          "    },                                                      \n" .
          "    exporting: {                                            \n" .
          "        enabled: true,                                      \n" .
          "        buttons: {                                          \n" .
          "          contextButton: {                                  \n" .
          "            align: 'left',                                  \n" .
          "            x: 10,                                          \n" .
          "            menuItems: [{                                   \n";
           
      echo "      text: 'manage timeseries',                           \n" .
           "              onclick: function() {                        \n" .
           "                $('#modalTS').modal('show');               \n" .
           "              }                                            \n" .
           "            }, {                                           \n"; 

      echo "      text: 'detrend timeseries',                          \n" .
           "              onclick: function() {                        \n" .
           "                call_modal_detrend_ts('msbas', 0);\n" .
           "              }                                            \n" .
           "            }, {                                           \n"; 
           
      echo "         text: 'export to PNG',                            \n" .
          "              onclick: function() {                         \n" .
          "                this.exportChart();                         \n" .
          "              }                                             \n" .
          "            }]                                              \n" .
          "          }                                                 \n" .
          "        }                                                   \n" .
          "      }                                                     \n";        
          echo "  });                                                  \n";

      for( $i = 0; $i < $totMsbas; $i ++ )
      {
        echo "  } });                                                  \n";
      }
    } // end msbas

    
    /** end msbas, start histogram **/
    if( $totHisto > 0 )
    {
      for( $nth = 1; $nth <= $totHisto; $nth ++ )
      {
        // $nth = $totHisto - 1;
        $obj = $ts_histo[$nth - 1];
        $seism_station = trim( $obj->ts_seism_station );
        $seism_file    = trim( $obj->ts_file );
        
        echo "  $.get('" . $this->config->item('uri_histogram') . $seism_file . "'," .
           "        function(csv) {                                \n";
        // echo "  console.log( 'finished reading $seism_file ' ); ";
        // charts created in ts-empty.js
        echo "$('#chart" . $nth . "').highcharts({\n";
        echo
           "      chart: {                                              \n" .
           "        type: 'column', zoomType: 'x'                       \n" .
           "      },                                                    \n" .
           "      title: {                                              \n" .
           "        text: '" . $seism_station . " seismic station'     \n" .
           "      },                                                    \n" .
           "      data: {                                               \n" .
           "        csv: csv,                                           \n" .
           "        itemDelimiter: '\\t',                               \n" .
           "        lineDelimiter: '\\n'                                \n" .
           "      },                                                    \n" . 
           "      series: [{                                            \n" .  
           "        type:  'column',                                    \n" .
           "        name:  'LP',                                        \n" .
           "        yAxis: 0,                                           \n" .
           "        allowPointSelect: true,                             \n" .  
           "        color: '#66d',                                      \n" .
           "      }, {                                                  \n" .
           "        type:  'column',                                    \n" .
           "        name:  'SP',                                        \n" .
           "        yAxis: 0,                                           \n" . 
           "        allowPointSelect: true,                             \n" .
           "        color: '#d66',                                      \n" .
           "      }, {                                                  \n" .
           "        type:  'line',                                      \n" .
           "        name:  'LP-accumulated',                            \n" .
           "        yAxis: 1,                                           \n" .
           "        allowPointSelect: true,                             \n" .
           "        color: 'blue'                                       \n" .
           "      }, {                                                  \n" .
           "        type:  'line',                                      \n" .
           "        name:  'SP-accumulated',                            \n" .
           "        yAxis: 2,                                           \n" . 
           "        allowPointSelect: true,                             \n" .
           "        color: 'red'                                        \n" .
           "      }],                                                   \n" .
           "      xAxis: { \n" .
           "        type: 'datetime', \n".
           "        crosshair: true,  \n". 
           "        events: {  \n".
//           "          setExtremes: syncExtremes  \n".
           "        }  \n".
           "      },  \n".
           "      yAxis: [{                                             \n" .
           "        min: 0,                                             \n" .
           "        title: { text: 'number of events' }                 \n" .
           "      }, { // secondary yAxis for LP-accumulated            \n" .  
           "        opposite: true,                                     \n" .
           "        min: 0,                                             \n" .
           "        title: { text: 'LP-accumulated' }                   \n" .
           "      }, { // secondary yAxis for SP-accumulated            \n" .
           "        opposite: true,                                     \n" . 
           "        min: 0,                                             \n" .
           "        title: { text: 'SP-accumulated' }                   \n" .
           "      }],                                                   \n" .
           "      legend: {                                             \n" .
           "        align: 'right',                                     \n" .
           "        x: -30,                                             \n" .
           "        verticalAlign: 'top',                               \n" .
           "        y: 25,                                              \n" .
           "        floating: true,                                     \n" .
           "        backgroundColor: (Highcharts.theme &&               \n" .
           "                  Highcharts.theme.background2) || 'white', \n" .
           "        borderColor: '#CCC',                                \n" .
           "        borderWidth: 1,                                     \n" .
           "        shadow: false                                       \n" .
           "      },                                                    \n" .
           "      tooltip: {                                            \n" .
           "        formatter: function () {                            \n" .
           "var d = new Date( this.x );                             \n" .
           "var day = d.getDate();  if( day < 10 ) day = '0' + day; \n" .
           "var mon = d.getMonth(); if( mon < 10 ) mon = '0' + mon; \n" .
           "var fecha = d.getFullYear() + '-' + mon + '-' + day;    \n" .
           "var total = this.point.stackTotal;                      \n" .
           "var str = '<b>' + fecha + '</b><br/>' +                 \n" .
           "         this.series.name + ': ' + this.y + '<br/>';    \n" .
           "if( typeof total != 'undefined' )                       \n" .  
           "  str = str + 'Total: ' + total;                        \n" .
           "return str;                                             \n" .
           "        }                                               \n" .
           "      },                                                    \n" .
           "      plotOptions: {                                        \n" .
           "        column: { stacking: 'normal' }                      \n" .  
           "      },                                                    \n" .
           "      exporting: {                                          \n" .
           "        enabled: true,                                      \n" .
           "        buttons: {                                          \n" .
           "          contextButton: {                                  \n" .
           "            align: 'left',                                  \n" .
           "            x: 10,                                          \n" .
           "            menuItems: [{                                   \n";
           
           echo "      text: 'remove timeseries',                       \n" .
           "              onclick: function() {                         \n" .
     " var i=ts_seism_data.indexOf('".$seism_station."');" .
     " ts_seism_data.splice( i, 1 ); " .
     " $('#chart" . $nth . "').remove(); " .
     " ts_seism_num --; " .
     " this.destroy(); " .
     " $.ajax({type: 'POST', ".
     "      url: '/index.php/status/ajaxTSConfig/', " .
     "      data: { ts_msbas: array2json( ts_msbas_data ), " .
     "              ts_histo: array2json( ts_seism_data ), " . 
     "              ts_gnss: array2json(  ts_gnss_data  ), " . 
     "              lon:  array2json( ts_msbas_lon ), " . 
     "              lat:  array2json( ts_msbas_lat ) }, " . 
     "      success: function(result){ " .
//     "        console.log( 'session timeseries saved ' + result ); " .
     "      }, " .
     "      error: function( jqXHR, textStatus, errorThrown ) { " .
     "         console.log(JSON.stringify(jqXHR)); ".
     "         console.log('AJAX error: ' + textStatus + ' : ' + errorThrown); ".
     "      } ".
     "   });  ".
     " return false; " .
           "              }                                             \n" .
           "            }, {                                            \n"; 

           echo "         text: 'export to PNG',                        \n" .
           "              onclick: function() {                         \n" .
           "                this.exportChart();                         \n" .
           "              }                                             \n" .
           "            }]                                              \n" .
           "          } // contextButton                                \n" .
           "        } // buttons                                        \n" .
           "      } // exporting                                       \n" .
           "    }); // highcharts - histogram                           \n" .
           "  }); // get csv histogram                                  \n";
      } // for histograms     
    } // if any histograms
    
    /** end histogram, start gnss **/
    $s = "";
    if( $totGnss > 0 )
    {
      for( $nth = 1; $nth <= $totGnss; $nth ++ )
      {
        $obj = $ts_gnss[$nth - 1];
        $gnss_station = trim( $obj->ts_seism_station ); // field name is same as ts_seim_station
        $gnss_file    = trim( $obj->ts_file );
        
        // if detrend exists for that point, get it instead
        $this->load->model( 'detrend_model', 'detrend' );
        if( $this->detrend->detrend_exists( "gnss", $obj->ts_name, 'EW', 0, 0 ) ) 
          { $detrendEW = true;  $nameEW = "EW detrended"; }
        else
          { $detrendEW = false; $nameEW = "EW"; }
        if( $this->detrend->detrend_exists( "gnss", $obj->ts_name, 'NS', 0, 0 ) ) 
          { $detrendNS = true;  $nameNS = "NS detrended"; }
        else
          { $detrendNS = false; $nameNS = "NS"; }
        if( $this->detrend->detrend_exists( "gnss", $obj->ts_name, 'UP', 0, 0 ) ) 
          { $detrendUP = true;  $nameUP = "UP detrended"; }
        else
          { $detrendUP = false; $nameUP = "UP"; }
        
        if( $detrendEW or $detrendNS or $detrendUP )
        {
          $file = $this->detrend->get_detrend_filepath( "gnss", $obj->ts_name, "", 0, 0, 'detrend', 'uri' );
          // log_message( 'error', $i . ' - file ' . $file );
          $s .= "  var gnss" . $nth . " = $.get('" . $file . "' ); \n";
          // $s .="   console.log( 'starting reading " . $file . "' ); \n";
          
        }
        else
        {
          $s .= "  var gnss" . $nth . " = $.get('" . $this->config->item('uri_gnss') 
                                             . $gnss_file . "' ); \n";
        }
        $s .="gnss" . $nth . ".done( function( csv ) { \n".
             "  if( csv == 'false') return 'Data not found'; \n".
             "  else { \n" .
             "    var prev = 0; \n ".
             "    lines = csv.split( '\\n' ); \n" .
             "    $.each(lines, function(lineNo, line) { \n" .
             "      if( line.length > 0 ) { \n";
             // ATT: date is separated with \\t from *the espaced values* 
        $s .="        items = line.split( '\\t' );  \n" .
             "        fecha = tick2Date( items[0] ); \n".
             "        if( fecha <= prev ) console.log( 'err: ' + fecha + ' < ' + prev ); \n".
             "        prev = fecha; \n ".
             "        val = items[1].split( ' ' );\n" .
             "        dataEW.push([fecha,round_number(parseFloat(val[0]),2)/10]);\n". //mm > cm
             "        dataNS.push([fecha,round_number(parseFloat(val[1]),2)/10]);\n".
             "        dataUP.push([fecha,round_number(parseFloat(val[2]),2)/10]);\n".
             "      } //if \n" .
             "    }); //each \n";
       
       /* debug
        $s .="    console.log( 'finished reading " . $file . "' ); \n";
        $s .="    console.log( 'dataEW: ' + dataEW ); \n";
        $s .="    console.log( 'dataNS: ' + dataNS ); \n";
        $s .="    console.log( 'dataUP: ' + dataUP ); \n";
      */
        
        $s .="    $('#chart" . ( $nth + $totHisto ) . "').highcharts({\n" .
             "      chart: { type: 'line', zoomType: 'x' }, \n".
             "      title: {                                              \n" .
             "        text: '" . $gnss_station . " GNSS station'     \n" .
             "      },                                                    \n" .
             "      legend: {                                             \n" .
             "        align: 'right',                                     \n" .
             "        x: -30,                                             \n" .
             "        verticalAlign: 'top',                               \n" .
             "        y: 25,                                              \n" .
             "        floating: true,                                     \n" .
             "        backgroundColor: (Highcharts.theme &&               \n" .
             "                  Highcharts.theme.background2) || 'white', \n" .
             "        borderColor: '#CCC',                                \n" .
             "        borderWidth: 1,                                     \n" .
             "        shadow: false                                       \n" .
             "      },                                                    \n" .
             "      xAxis: { type: 'datetime' }, \n".
             "      yAxis: {                                              \n" .
             "        title: { text: 'displacement in cm' },              \n" .
             "        alternateGridColor: '#f5f5f5'                       \n" .
             "      },                                                    \n" .
             "      series: [{ name: '$nameEW', data: dataEW, color:'#aa0000' \n".
             "             },{ name: '$nameNS', data: dataNS, color:'#0000aa' \n".
             "             },{ name: '$nameUP', data: dataUP, color:'#00aa00'}],\n"; 
          
        $s.="     tooltip: {                                        \n" .
           "        formatter: function () {                        \n" .
           "var d = new Date( this.x );                             \n" .
           "var day = d.getDate();  if( day < 10 ) day = '0' + day; \n" .
           "var mon = d.getMonth(); if( mon < 10 ) mon = '0' + mon; \n" .
           "var fecha = d.getFullYear() + '-' + mon + '-' + day;    \n" .
           "var total = this.point.stackTotal;                      \n" .
           "var str = '<b>' + fecha + '</b><br/>' +                 \n" .
           "         this.series.name + ': ' + this.y + '<br/>';    \n" .
           "if( typeof total != 'undefined' )                       \n" .  
           "  str = str + 'Total: ' + total;                        \n" .
           "return str;                                             \n" .
           "        }                                               \n" .
           "      },                                                \n";
             
        $s.= "      exporting: {                                        \n" .
             "        enabled: true,                                    \n" .
             "        buttons: {                                        \n" .
             "        contextButton: {                                  \n" .
             "          align: 'left',                                  \n" .
             "          x: 10,                                          \n" .
             "          menuItems: [{                                   \n";
        $s.= "            text: 'remove timeseries',                    \n" .
             "            onclick: function() {                         \n" .
     " var i=ts_gnss_data.indexOf('".$gnss_station."'); \n" .
     " ts_gnss_data.splice( i, 1 ); \n" .
     " $('#chart" . ( $nth + $totHisto ) . "').remove(); \n" .
     " ts_gnss_num --; \n" .
     " this.destroy(); \n" .
     " $.ajax({type: 'POST', \n".
     "      url: '/index.php/status/ajaxTSConfig/', \n" .
     "      data: { ts_msbas: array2json( ts_msbas_data ), \n" .
     "              ts_histo: array2json( ts_seism_data ), \n" . 
     "              ts_gnss: array2json(  ts_gnss_data  ), \n" . 
     "              lon:  array2json( ts_msbas_lon ), \n" . 
     "              lat:  array2json( ts_msbas_lat ) }, \n" . 
     "      success: function(result){ \n" .
     // "        console.log( 'session timeseries saved ' + result ); \n" .
     "      }, \n" .
     "      error: function( jqXHR, textStatus, errorThrown ) { \n" .
     "         console.log(JSON.stringify(jqXHR)); \n".
     "         console.log('AJAX error: ' + textStatus + ' : ' + errorThrown); \n".
     "      } \n".
     "   });  \n".
     " return false; \n" .
           "              }                                             \n" .
           "            }, {                                            \n"; 
           
      $s.= "      text: 'detrend timeseries',                          \n" .
           "              onclick: function() {                        \n" .
           "                call_modal_detrend_ts('gnss', " . ($nth) . ");\n" .
           "              }                                            \n" .
           "            }, {                                           \n"; 
           
      $s.= "         text: 'export to PNG',                        \n" .
           "              onclick: function() {                         \n" .
           "                this.exportChart();                         \n" .
           "              }                                             \n" .
           "            }]                                              \n" .
           "          } // contextButton                                \n" .
           "        } // buttons                                        \n" .
           "      } // exporting                                       \n";
           
        $s .="    });  // chartnth \n";  
        $s .="  } // else data found \n" .
             "}); //done \n ";
      } // for nth
    } // end gnss 
    // log_message( 'error', $s );
    echo $s . "}); \n"; // $.ready
  
  }
  /* end of function echo_async */ 
  
}

/* End of file Mapa.php */
/* Location: ./application/controllers/Mapa.php */