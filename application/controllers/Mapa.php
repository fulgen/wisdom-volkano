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
      $data[ 'layers' ] = $result;
    }

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
    
      $this->load->model( 'timeseries_model', 'ts_model' );
      // only for msbas ts
      foreach( $result as $tss )
      {
        $ts = $this->ts_model->get_timeseries( $tss->ts_name );
        if( $ts->ts_type == "msbas" )
        {
          $file = array( 0 => ( $this->config->item( 'folder_msbas' ) . trim( $ts->ts_file ) . $this->config->item( 'folder_msbas_ras' ) . trim( $ts->ts_file_raster ) ) );
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
          break; // one set of boundaries is enough
        }
      }
    }

    $this->load->helper( array( 'menu', 'form' ) );
    $this->load->view( 'mapa', $data ); 
    // $this->load->view( 'footer' );
    
	}
  /* end of function index */
  
  
	/**
	 * load_ts_async Loads asynchronously (ajax) the timeseries for a coord
	 *
	 * Maps to
	 * 		http://server/index.php/Mapa/load_ts_async/
	 *
   * @access	public
   * @param   the timeseries to load the point from, and the point coords 
   * @return	filters data and calls the adequate function 
	 */
  public function load_ts_async()
  {
    $this->load->model( 'Userts_model', 'userts' );
    $this->load->model( 'Timeseries_model', 'ts' );
    $ts_type = $this->input->post('ts_type');
    $ts_name = json_decode( $this->input->post('ts_name') );
    
    if( $ts_type == 'histogram' ) 
    {
      if( is_null( $this->userts->get_ts_user( $ts_name[0], $this->session->userdata( 'email' ) ) ) )
      {
        log_message( 'error', 'app/controller/Mapa/E-045 Error: Not found ts ' . $ts_name . ' or not granted ts to user.' );
        echo 'alert( "Error: Not found timeseries ' . $ts_name . ' or not granted to user.")';
        return;
      }
      $ts_data = $this->ts->get_timeseries( $ts_name[0] );
      if( is_null( $ts_data ) )
      {
        log_message( 'error', 'app/controller/Mapa/E-046 Error: Cannot find ts_name ' . $ts_name . '.' );
        echo 'alert( "Error: Cannot find the timeseries ' . $ts_name . '." )';
        return;
      }
      
    }
    else
    {
      $a_lon   = array();
      $a_lat   = array();
      $ts_data = array();
      $ar_lon  = json_decode( $this->input->post('lon') ); 
      $ar_lat  = json_decode( $this->input->post('lat') ); 
      $tot     = count( $ts_name );
    
      // filter all ts_name should exist and be granted to the user
      for( $i = 0; $i < $tot; $i ++ )
      {
        if( is_null( $this->userts->get_ts_user( $ts_name[ $i ], $this->session->userdata( 'email' ) ) ) )
        {
          log_message( 'error', 'app/controller/Mapa/E-042 Error: Not found ts ' . $ts_name[ $i ] . ' or not granted ts to user.' );
          echo 'alert( "Error: Not found timeseries ' . $ts_name[ $i ] . ' or not granted to user.")';
          return;
        }
        else
        { 
          // filter all lat and lon be numbers for coords
          if( ! is_numeric( $ar_lon[ $i ] ) || ! is_numeric( $ar_lat[ $i ] ) )
          {
            log_message( 'error', 'app/controller/Mapa/E-043 Error: Coordinates lat ' . $ar_lat[ $i ] . ' and lon ' . $ar_lon[ $i ] . ' are not numeric.' );
            echo 'alert( "Error: Coordinates lat ' . $ar_lat[ $i ] . ' and lon ' . $ar_lon[ $i ] . ' are not numeric." )';
            return;
          }
        }
        $a_lat[ $i ] = round( floatval( $ar_lat[ $i ] ), 3 ); // 3 decimals
        $a_lon[ $i ] = round( floatval( $ar_lon[ $i ] ), 3 );

        $ts_data[ $i ] = $this->ts->get_timeseries( $ts_name[ $i ] );
        if( is_null( $ts_data[ $i ] ) )
        {
          log_message( 'error', 'app/controller/Mapa/E-044 Error: Cannot find ts_name ' . $ts_name[ $i ] . '.' );
          echo 'alert( "Error: Cannot find the timeseries ' . $ts_name[ $i ] . '." )';
          return;
        }
      }
    }
    
    switch( $ts_type )
    { 
      case "msbas": 
        // echo 'alert( "msbas, tsname: ' . $ts_name . ', lat: ' . $lat . ', lon: ' . $lon . '" );';
        // look for the file with the point $lat $lon, 
        //   and if it doesn't exist, calculate and then echo 
        //   ONLY the last one, not all!
        $this->msbas_point_file_generate( $ts_data[$tot-1], $a_lon[$tot-1], $a_lat[$tot-1] );
        $this->echo_async_msbas( $tot, $ts_data, $a_lon, $a_lat );
        break;
      case "histogram":
        // echo 'alert( "histogram, station: ' . $ts_name . ', lat: ' . $lat . ', lon: ' . $lon . '" );';
        // TBD: look for the file with the $station, and if it doesn't exist echo so
        $this->echo_async_histogram( $ts_data );
        break;
      default: break; 
    }
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
    // 0. convert coords to row,col
    $xcol = $this->coord2pix( $lon, $msbas->ts_coord_lon_left, $msbas->ts_coord_lon_inc );
    $yrow = $this->coord2pix( $lat, $msbas->ts_coord_lat_top, $msbas->ts_coord_lat_inc );
    
    // 1. get file name
    $file = $this->get_ts_file_name( $msbas, "disk", $xcol, $yrow );
    
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
	 * get_ts_file_name Constructs the file name with the ts default + col,row
	 *
   * @access	private
   * @param   the coordinates and the ts, and if it shall return uri or disk link
   * @return	the file name
	 */
  private function get_ts_file_name( $msbas, $type, $xcol, $yrow )
  {
    if( $type == "uri" )
      $file = base_url( "assets/data/msbas/" ) . "/" 
            . trim( $msbas->ts_file ) 
            . $this->config->item( 'uri_msbas_ts' ) 
            . substr( trim( $msbas->ts_file_ts ), 0, $msbas->ts_file_ts_ini_coord );    
    else // "disk" 
      $file = $this->config->item( 'folder_msbas' )
            . trim( $msbas->ts_file ) 
            . $this->config->item( 'folder_msbas_ts' ) 
            . substr( trim( $msbas->ts_file_ts ), 0, $msbas->ts_file_ts_ini_coord );

    // assuming pixels with 3 numbers, range 001..999, format XXX_YYY
    // TBD: use ts_folder->preg_pos regexp
    $file = $file . $xcol . "_" . $yrow;
    $file = $file . substr( trim( $msbas->ts_file_ts ), $msbas->ts_file_ts_ini_coord + 7 ); // until the end
    // echo "looking for file $file \n";
    return $file;
  }
  
  
	/**
	 * coord2pix Converts a coordinate into a pixel, given an origin and increment
   *        ex: given long 29.215, origin 29.0 and incr 0.0008333
   *            29.215 - 29 = 0.215 / 0.0008333 = 258
	 *
   * @access	private
   * @param   the coordinate, the origin of the image file and the increment per pixel
   * @return	the number for the pixel
	 */
  private function coord2pix( $coord, $origin, $incr )
  {
    $pix = round( abs( $coord - $origin ) / $incr );
    $pix = sprintf( "%'.03d", $pix );
    return $pix;
    
  }
  /* end of function coord2pix */
  
  
	/**
	 * echo_async_msbas Echoes (ajax call) the js msbas series of a point
	 *
   * @access	private
   * @param   the number of timeseries, the ts, and the point coords arrays
   * @return	echoes (ajax!) the highcharts.com javascript code for the timeseries 
	 */
  private function echo_async_msbas( $tot, $ts_data, $lon, $lat )
  {
    echo "var fecha, lines, items;                                 \n";
    echo "$(function () {                                          \n";
    for( $i = 0; $i < $tot; $i ++ )
    {
      $xcol = $this->coord2pix( $lon[$i], $ts_data[$i]->ts_coord_lon_left, $ts_data[$i]->ts_coord_lon_inc );
      $yrow = $this->coord2pix( $lat[$i], $ts_data[$i]->ts_coord_lat_top, $ts_data[$i]->ts_coord_lat_inc );
      
      $file = $this->get_ts_file_name( $ts_data[$i], "uri", $xcol, $yrow );
      /* $file = base_url( "assets/data/msbas/" 
            . trim( $ts_data[$i]->ts_file ) 
            . "/Time_Series/" 
            . trim( $ts_data[$i]->ts_file_ts ) ); */
      echo "  var tsv" . $i . " = $.get( '" . $file . "' );        \n";
      // 2nd ts
      /*
      echo "  var tsv2 = $.get( '" .
           base_url('assets/data/msbas/UP/Time_Series/VVP_ML_1_Pixel_FullSerie_238_370test_UP._Detrended.dat') . "' ); \n";
      */   
    }
    
    // wait for async calls to be finished
    for( $i = 0; $i < $tot; $i ++ )
    {
      echo "  tsv" . $i . ".done( function( csv" . $i . " ) {        \n" .
          "     if( csv" . $i . " == 'false' ) return 'Data not found'; // TBD \n" .
          "     else {                                               \n";
      // 2nd ts
      /*
      echo "  tsv2.done( function( csv2 ) {                            \n" .
          "    if( csv2 == 'false' ) return 'Data not found'; // TBD   \n" .
          "    else {                                                  \n";
      */    
    }
    
    for( $i = 0; $i < $tot; $i ++ )
    {    
      echo "  var data" . $i ." = []; // [ dates, values ]          \n";
      // 2nd ts
      /*
      echo "  var data2 = []; // [ dates, values ]                    \n";
      */
    }

    for( $i = 0; $i < $tot; $i ++ )
    {
      echo "  lines = csv" . $i . ".split( '\\n' );                    \n" .
          "  $.each( lines, function( lineNo, line ) {                 \n" .
          "    items = line.split( '\\t' );                            \n" .
          "    fecha = tick2Date( items[ 0 ] );                        \n" .
          "    data" . $i . ".push( [ fecha, parseFloat( items[1] ) ] );\n" .
          "  });                                                       \n";
      // 2nd ts
      /*
      echo "  lines = csv2.split( '\\n' );                             \n" .
          "  $.each( lines, function( lineNo, line ) {                 \n" .
          "    items = line.split( '\\t' );                            \n" .
          "    fecha = tick2Date( items[ 0 ] );                        \n" .
          "    data2.push( [ fecha, parseFloat( items[1] ) ] );       \n" .
          "  });                                                       \n";
      */
    }
    
    echo "  $('#container').highcharts({                             \n" .
        "    chart: { type: 'line', zoomType: 'x' },                 \n" .
        "    title: { text: 'Timeseries of MSBAS' },                 \n" .
        "    xAxis: {                                                \n" .
        "      type: 'datetime',                                     \n" .
        "      gridLineWidth: 1                                      \n" .
        "    },                                                      \n" .
        "    yAxis: {                                                \n" .
        "      title: { text: 'displacement in cm' },                \n" .
        "      alternateGridColor: '#f5f5f5'                         \n" .
        "    },                                                      \n";
        
    echo "    series: [{                                             \n";
    for( $i = 0; $i < $tot; $i ++ )
    {
      echo "      name:  '" . trim( $ts_data[$i]->ts_name ) .
           "           lat: " . $lat[$i] . " lon: " . $lon[$i] . "', \n" .
           "      data: data" . $i . "                               \n" .
           "    }                                                    \n";
      if( $i + 1 < $tot ) echo " , {                                 \n";
      // 2nd ts
      /*
      echo "    , {                                                    \n" .
          "      name:  'name...',                                     \n" .
          "      color: '#6d6',                                        \n" .
          "      data: data2                                           \n" .
          "    }                                                       \n";
      */
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

    echo "         text: 'export to PNG',                            \n" .
        "              onclick: function() {                         \n" .
        "                this.exportChart();                         \n" .
        "              }                                             \n" .
        "            }]                                              \n" .
        "          }                                                 \n" .
        "        }                                                   \n" .
        "      }                                                     \n";        
        echo "  });                                                  \n";

    for( $i = 0; $i < $tot; $i ++ )
    {
      // 2nd ts
      /*
      echo "  } });                                                  \n";
      */
      echo "  } });                                                  \n";
    }
    echo "});                                                        \n";
    
  }
  /* end of function echo_async_msbas */ 

  
 /**
	 * echo_async_histogram Echoes (ajax call) the js histogram of a point
	 *
   * @access	private
   * @param   the station timeseries
   * @return	echoes (ajax!) the highcharts.com javascript code for the timeseries 
	 */
  private function echo_async_histogram( $ts_data )
  {
    echo "$(function () {                                             \n" .  
         "  $.get('/assets/data/seism-count/" .$ts_data->ts_file. "', \n" .
         "        function(csv) {                                     \n" .
         "    $('#container').highcharts({                            \n" .
         "      chart: {                                              \n" .
         "        type: 'column', zoomType: 'x'                       \n" .
         "      },                                                    \n" .
         "      title: {                                              \n" .
         "        text: '" . 
            $ts_data->ts_seism_station . " seismic station'           \n" .
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
         
         /* echo "      text: 'manage timeseries',                    \n" .
         "              onclick: function() {                         \n" .
         "                $('#modalTS').modal('show');                \n" .
         "              }                                             \n" .
         "            }, {                                            \n"; */

         echo "         text: 'export to PNG',                        \n" .
         "              onclick: function() {                         \n" .
         "                this.exportChart();                         \n" .
         "              }                                             \n" .
         "            }]                                              \n" .
         "          }                                                 \n" .
         "        }                                                   \n" .
         "      }                                                     \n" .        
         "    });                                                     \n" .
         "  });                                                       \n" .
         "});                                                         \n";
  
  }
  /* end of function echo_async_histogram */ 

}

/* End of file Mapa.php */
/* Location: ./application/controllers/Mapa.php */