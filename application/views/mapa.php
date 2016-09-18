<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>

  <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet" />
  <link rel="stylesheet" href="<?php echo base_url('assets/css/ol.css');?>" type="text/css" />
  <link rel="stylesheet" href="<?php echo base_url('assets/css/range.css');?>" type="text/css" />
  <link href="<?php echo base_url('assets/css/map.css'); ?>" rel="stylesheet" />  
  
<!-- openlayers and maps -->
  <script src="<?php echo base_url('assets/js/ol.js');?>" type="text/javascript"></script>
  <script src="<?php echo base_url('assets/js/jquery-2.2.2.min.js');?>" type="text/javascript"></script>
  <script type="text/javascript"
 src="http://maps.googleapis.com/maps/api/js?key=<?php echo $this->config->item( 'gmaps_key' ); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/js/ol-source-gmaps-tms.min.js');?>"></script>  
  <script type="text/javascript">
    var zoom, lat, lon, center;
    if (window.location.hash == '') {
      zoom = <?php echo $_SESSION[ 'zoom' ]; ?>;
      lat  = <?php echo $_SESSION[ 'lat' ]; ?>; 
      lon  = <?php echo $_SESSION[ 'lon' ]; ?>; 
      center = [ lon, lat ]; 
      // console.log( 'zoom set in view map to ' + zoom + ' and center set to [' + lon + ',' + lat + ']' );
    }
    var ts_msbas_num  = 0;
    var ts_seism_num  = 0;
    var ts_gnss_num   = 0;
    var ts_seism_data = [];
    var ts_gnss_data  = [];
    var ts_msbas_data = [];
    var ts_msbas_lon  = [];
    var ts_msbas_lat  = [];
    <?php
      if( $_SESSION[ 'ts_msbas_num' ] != 0 )
      {
        echo "ts_msbas_data = " . $_SESSION[ 'ts_msbas' ]    . ";\n";
        echo "ts_msbas_lon  = " . $_SESSION[ 'ts_lon' ]      . ";\n";
        echo "ts_msbas_lat  = " . $_SESSION[ 'ts_lat' ]      . ";\n";
        echo "ts_msbas_num  = " . $_SESSION[ 'ts_msbas_num' ]. ";\n";
      }
      if( $_SESSION[ 'ts_histo_num' ] != 0 )
      {
        echo "ts_seism_data = " . $_SESSION[ 'ts_histo' ]    . ";\n";
        echo "ts_seism_num  = " . $_SESSION[ 'ts_histo_num' ]. ";\n";
      }
      if( $_SESSION[ 'ts_gnss_num' ] != 0 )
      {
        echo "ts_gnss_data  = " . $_SESSION[ 'ts_gnss' ]     . ";\n";
        echo "ts_gnss_num   = " . $_SESSION[ 'ts_gnss_num' ] . ";\n";
      }
    ?>
    // console.log( 'ini msbas ts (' + ts_msbas_num + '): ' + ts_msbas_data );
    // console.log( 'ini histo ts (' + ts_seism_num + '): ' + ts_seism_data );
    //console.log( 'ini gnss ts  (' + ts_gnss_num  + '): ' + ts_gnss_data  );
  </script>
  <script type="text/javascript" src="<?php echo base_url('assets/js/permalink.js');?>"></script>  

<!-- timeseries -->  
  <script src="<?php echo base_url('assets/js/highcharts.js');?>" type="text/javascript"></script>
  <script src="<?php echo base_url('assets/js/data.js');?>" type="text/javascript"></script>
  <script src="<?php echo base_url('assets/js/exporting.js');?>" type="text/javascript"></script>
  <script src="<?php echo base_url( 'assets/data/events.js');?>" type="text/javascript"></script>
  <script src="<?php echo base_url('assets/js/ts-empty.js');?>" type="text/javascript"></script>
  
  <title>Home | wisdom-volkano</title>
</head>
<body>
  <div class="container-fluid">
      <div class="row Navigation">
          <div class="menu col-lg-12">
            <?php echo menu('home'); ?>
          </div>
      </div>
      <div class="row Main">
          <div class="layers col-lg-3" id="layers">

            <?php echo form_open( 'layer/load', 'class="form-horizontal form-inline" id="f"'); ?>
              
              <ul class="list-group">
              
              <a href="#myModal" class="list-group-item  btn-primary btn-block active" data-toggle="modal">
              Manage layers</a>
              
                  <!-- Button HTML (to Trigger Modal) -->
                  <!-- Modal HTML -->
                  <div id="myModal" class="modal fade">
                      <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&#x274E;</button>
                                  <h4 class="modal-title">Please sort and select layers to load:</h4>
                              </div>
                              <div class="modal-body">
                              <?php 
                                if( is_array( $layers ) )
                                {
                                  echo "<ol class='sortable'>\n";
                                  for( $i = 0; $i < count( $layers ); ++$i )
                                  {
                                    echo "<li>";
                                    $loaded = ( $layers[ $i ]->config_loaded == 1 );
                                    echo form_checkbox( 'grant[]', $layers[$i]->layer, $loaded ) . $layers[$i]->layer;
                                    echo "</li>\n";
                                  }
                                  echo "</ol>\n";
                                  echo "<p class='text-warning'><small>You may drag and drop layers to sort them. Please maintain layers within the same workspace grouped or some may not be shown. If you don't save, your changes will be lost.</small></p>";
                                  
                                  echo "</div><div class='modal-footer'>";
                                  echo '<button type="submit" class="btn btn-primary">Save changes</button>';
                                }
                                else // not an array: it is an error
                                  echo $layers;
                              ?>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                              </div>
                          </div>
                      </div>
                  </div>
              
              <li class="list-group-item">
                <a href="#bg" data-toggle="collapse">backgrounds >></a>

                <div id="bg" class="collapse">
                  <fieldset id="layer0">
                    <input id="visible0" class="visible" type="checkbox" <?php if( $layvis[0]->bg1_visible == 1 ) echo 'checked="checked"'; ?>/>&nbsp;bg:GoogleMaps
                    <input id="opac0" class="opacity" type="range" min="0" max="1" step="0.01" value="<?php echo ($layvis[0]->bg1_opacity / 100); ?>"/>
                  </fieldset>
                  <fieldset id="layer1">
                    <input id="visible1" class="visible" type="checkbox" <?php if( $layvis[0]->bg2_visible == 1 ) echo 'checked="checked"'; ?>/>&nbsp;bg:OpenStreetMaps
                    <input id="opac1" class="opacity" type="range" min="0" max="1" step="0.01" value="<?php echo ($layvis[0]->bg2_opacity / 100); ?>"/>
                  </fieldset>
<?php 
    $i = 2; // counting from backgrounds on
    $ws_current = ""; // one collapse panel per workspace
    if( is_array( $layers ) ) // substr( $layers, 0, 5 ) != "Error" )
    {
      foreach( $layers as $layer )
      {
        $ws = substr( $layer->layer, 0, strpos( $layer->layer, ":" ) );
        if( $ws != $ws_current ) 
        {
          echo "  </div>\n";
          echo "</li>\n";
          echo "<li class='list-group-item'>\n";
          echo "  <a href='#" . $ws . "' data-toggle='collapse'>" . $ws . " >></a>\n";
          echo "  <div id='" . $ws . "' class='collapse'>\n";
        }
        
        // print_r( $layer );
        if( $layer->config_loaded == 1 )
        {
          echo "    <fieldset id='layer$i'>\n";
          echo "      <input id='visible[]' class='visible' type='checkbox' />&nbsp;" . $layer->layer . "<input id='opac[]' class='opacity' type='range' min='0' max='1' step='0.05'/>\n";
             echo "    </fieldset>\n";
          $i ++;
        }
        $ws_current = $ws;
      }                
    }
    echo "  </div>\n";
    echo "</li>\n";
    echo "<li class='list-group-item'>\n";
    echo "  <a href='#stations' data-toggle='collapse'>stations >></a>\n";
    echo "  <div id='stations' class='collapse'>\n";
    
    echo '<fieldset id="layer'.$i.'">';
    echo '  <input id="visible'.$i.'" class="visible" type="checkbox"';
    if( $layvis[0]->bg3_visible == 1 ) echo 'checked="checked"'; 
    echo '/>&nbsp;stations:Seismo';
    echo '  <input id="opac'.$i.'" class="opacity" type="range" min="0" max="1" step="0.01"';
    echo '     value="' . ($layvis[0]->bg3_opacity / 100) . '"/>';
    echo '</fieldset>';
    $i ++;
    echo '<fieldset id="layer'.$i.'">';
    echo '  <input id="visible'.$i.'" class="visible" type="checkbox"';
    if( $layvis[0]->bg4_visible == 1 ) echo 'checked="checked"'; 
    echo '/>&nbsp;stations:GNSS';
    echo '  <input id="opac'.$i.'" class="opacity" type="range" min="0" max="1" step="0.01"';
    echo '     value="' . ($layvis[0]->bg4_opacity / 100) . '"/>';
    echo '</fieldset>';
    
?>

                </div>
              </li>
            </ul>
          </form>
          <!-- 
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Config panel</h3>
              </div>
              <div class="panel-body">
                To be completed in iteration 3.
              </div>
            </div>
          -->
          
          </div>
          
          <div class="map col-lg-9" id="map">
            <div style="clear:both"></div>
            <form class="form-inline" id="searchcoord">
              <div class="form-group form-group-sm">
                <label for="longitude">longitude</label>
                <input class="form-control" id="longitude" type="text" value="29.1"/>
              </div>
              <div class="form-group form-group-sm">
                <label for="latitude">latitude</label>
                <input class="form-control" id="latitude" type="text" value="-1.5"/>
              </div>
              <input id="btn_addmarker" type="button" class="btn btn-default btn-sm" value="Go >>" />
            </form>
            <div style="clear:both"></div>
            <div id="popup" class="ol-popup">
                <a href="#" id="popup-closer" class="ol-popup-closer"></a>
                <div id="popup-content"></div>
            </div>          
          </div>
          
      </div>
      <div class="row">
        <div class="col-lg-12">
          <input id="btn_ts_reset" type="button" class="btn btn-default btn-sm" value="Reset all time series"/>
        </div>
      </div>
      <div class="row Timeseries">
          <div class="graph col-lg-12">
            <div id="container">
              <div id="chart0" class="chart panel panel-default"></div>
            </div>
          </div>

                  <!-- Modal HTML Manage TS -->
                  <div id="modalTS" class="modal fade">
                      <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&#x274E;</button>
                                  <h4 class="modal-title">Please select timeseries to load:</h4>
                              </div>
                              <div class="modal-body" id="modalTSbody"></div>
                              <div class="modal-body">
                                <p class="text-warning"><small>You may drag and drop timeseries to sort them<!--, but only the first enabled type will be shown-->. If you don&quot;t save, your changes will be lost.</small></p>          
                              </div>
                              <div class="modal-footer">      
                                <button type="button" id="btfav" class="btn btn-success">Save as msbas-favorites</button>
                                <button type="button" id="buttonts" class="btn btn-primary">Reload chart</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>                              
                              </div>
                          </div>
                      </div>
                  </div>
                  <!-- Modal HTML Detrend TS -->
                  <div id="detrendTS" class="modal fade">
                      <div class="modal-dialog">
                          <div class="modal-content">
                          <form action="<?php echo site_url('detrend/calculate');?>" id="formdetrend" method="post">
                                <input type="hidden" id="detrendtype" name="detrendtype" value="msbas" />
                                <input type="hidden" id="minx" name="minx" value="0" />
                                <input type="hidden" id="maxx" name="maxx" value="0" />
                              <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&#x274E;</button>
                                  <h4 class="modal-title">Please select timeseries to detrend:</h4>
                              </div>
                              <div class="modal-body" id="detrendTSbody"></div>
                              <div class="modal-body">
                                <p class="text-warning"><small><!-- If you don&quot;t save, your changes will be lost.--></small></p>          
                              </div>
                              <div class="modal-footer">     
                              
                                <button type="submit" id="btdetrend" class="btn btn-success">Detrend selected timeseries</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>                              
                              </div>
                          </form>
                          </div>
                      </div>
                  </div>

      </div>
  </div>
  
  
    <script type="text/javascript">
      var listLayers = [ 
          new ol.layer.Tile({
            title: "gmaps",
            source: ol.source.GMapsTMS({layer: 'satellite'})  
          }), 
          new ol.layer.Tile({
            title: "bg-osm",
            source: new ol.source.OSM()
          })
 
<?php           
  $i = 2; // Counting from background
  if( is_array( $layers ) ) // substr( $layers, 0, 5 ) != "Error" )
  {
    foreach( $layers as $layer )
    {
      // print_r( $layer );
      if( $layer->config_loaded == 1 )
      {
        echo "  ,\n";
        echo "      new ol.layer.Tile({ \n";
        echo "      title: '" . $layer->layer . "', \n";
        echo "      source: new ol.source.TileWMS({ \n";
        echo "        url: '" . $this->config->item( 'geoserver_url' ); // http://127.0.0.1:8080/geoserver/";
        $ws = substr( $layer->layer, 0, strpos( $layer->layer, ":" ) );
        echo $ws . "/wms', \n";
        echo "        params: {                                     \n";
        echo "                   'LAYERS': '" . $layer->layer . "'  \n";
        echo "                 , 'TILED': true                      \n";
        echo "                }                                     \n";
        echo "      }) \n";  
        echo "  }) \n";
        $i ++;
      }
    }
  }
?>          

          , new ol.layer.Image({
            title: "gnss",
            source: new ol.source.ImageWMS({ 
              url: '<?php echo $this->config->item( 'geoserver_url' ); ?>geom/wms',
              params: {
                       'LAYERS' : 'geom:GNSS_station'
                      }
            })
          }), 
          new ol.layer.Image({ 
            title: "seismo",
            source: new ol.source.ImageWMS({ 
              url: '<?php echo $this->config->item( 'geoserver_url' ); ?>geom/wms',
              params: { 
                       'LAYERS' : 'geom:Seismo_station'
                      }
            })
          }) 


      ];
      var totLayers = listLayers.length;

      /**
       * Elements that make up the popup.
       */
      var container = document.getElementById('popup');
      var content = document.getElementById('popup-content');
      var closer = document.getElementById('popup-closer');


      /**
       * Add a click handler to hide the popup.
       * @return {boolean} Don't follow the href.
       */
      closer.onclick = function() {
        overlay.setPosition(undefined);
        closer.blur();
        return false;
      };


      /**
       * Create an overlay to anchor the popup to the map.
       */
      var overlay = new ol.Overlay(/** @type {olx.OverlayOptions} */ ({
        element: container,
        autoPan: true,
        autoPanAnimation: {
          duration: 250
        }
      }));

      /**
        * Permalink, see permalink.js
        */
      var scaleLineControl = new ol.control.ScaleLine();      

      var map = new ol.Map({
        target: 'map',
        layers: listLayers,
        overlays: [overlay],
        view: new ol.View({
          center: center,
          zoom: zoom
          // , rotation: rotation
        }),
        controls: ol.control.defaults({
          attributionOptions: /* @type {olx.control.AttributionOptions} */ ({
            collapsible: false
          })
        }).extend([
          scaleLineControl
        ])
      });
      console.log( 'projection: ' + map.getView().getProjection().getCode() );
      
      <?php 
      /**
        * Related to example 
        * http://openlayers.org/en/master/examples/layer-group.html 
        */ ?> 
      function bindInputs(layerid, layer) {
        var id = parseInt( layerid.substring( '#layer'.length ) );
        var visibilityInput = $(layerid + ' input.visible');
        visibilityInput.on('change', function() { 
          layer.setVisible(this.checked); // true/false
          // console.log( 'modifying layer... ' + id + ' visibility:' + this.checked );
          call_layer_visib_ajax( id, this.checked );
        });
        // opposite than the example: we get to the map the input value
        // but it hides the point layers
        // layer.setVisible( visibilityInput.prop( 'checked' ) );
        visibilityInput.prop('checked', layer.getVisible());        

        var opacityInput = $(layerid + ' input.opacity');
        opacityInput.on('ready mouseup', function() {
          layer.setOpacity( parseFloat(this.value) ); // 0..1, 2 decimals
          // console.log( 'modifying layer... ' + id + ' opacity:' + this.value );
          call_layer_visib_ajax( id, this.value );
        });
        // opposite than the example: we get to the map the input value
        // but it hides the point layers
        // layer.setOpacity( parseFloat( opacityInput.val ) );
        opacityInput.val(String(layer.getOpacity()));
      }
      map.getLayers().forEach(function(layer, i) {
        bindInputs('#layer' + i, layer);
        if (layer instanceof ol.layer.Group) {
          layer.getLayers().forEach(function(sublayer, j) {
            bindInputs('#layer' + i + j, sublayer);
          });
        }        
      });

      $(function  () {
        $("ol.sortable").sortable();
      });
      
      /* Add a click handler to the map to render the popup. */
      map.on('singleclick', function(evt) {
        var pixel = evt.pixel;
        // var coordinate = evt.coordinate; 
        // var coord4326 = ol.proj.transform( coordinate, 'EPSG:3857', 'EPSG:4326' );
        select_point( evt.coordinate ); 
      }); 
      
      /* function to get coords manually set via input */
      $("#btn_addmarker").on("click", function () {
        var lat = parseFloat( $("#latitude").val() );
        var lon = parseFloat( $("#longitude").val() );
        // lon,lat are already in EPSG:4326 // ol.Coordinate{Array.<number>}
        var coord3857 = ol.proj.transform( [lon, lat], 'EPSG:4326', 'EPSG:3857' );
        select_point( coord3857 ); 
      });

      /* function from a click or a manual input, opens pop-up on screen */
      function select_point( coord3857 )
      { 
        var coord4326 = ol.proj.transform( coord3857, 'EPSG:3857', 'EPSG:4326' );
        var view = map.getView();
        var viewResolution = view.getResolution();
        var sourceSeismSta = listLayers[ totLayers-1 ].getSource(); 
        var sourceGpsSta   = listLayers[ totLayers-2 ].getSource(); 
        var sourceSeismLoc = listLayers[ totLayers-3 ].getSource(); 
        // var coord3857 = ol.proj.transform( coord4326, 'EPSG:4326', 'EPSG:3857' );
        var urlGPSSta   = sourceGpsSta.getGetFeatureInfoUrl(
          coord3857, viewResolution, view.getProjection(),
          {'INFO_FORMAT': 'application/json', 'FEATURE_COUNT': 50});
        var urlSeismSta = sourceSeismSta.getGetFeatureInfoUrl(
          coord3857, viewResolution, view.getProjection(),
          {'INFO_FORMAT': 'application/json', 'FEATURE_COUNT': 50});
        var urlSeismLoc = sourceSeismLoc.getGetFeatureInfoUrl(
          coord3857, viewResolution, view.getProjection(),
          {'INFO_FORMAT': 'application/json', 'FEATURE_COUNT': 50});

        var hdms = ol.coordinate.toStringXY( coord4326, 3 ); // with 3 decimal
        var html = '<code>' + hdms + '</code><p>Loading...</p>';
        // console.log( 'point (' + coord4326 + ') ' + hdms );
        content.innerHTML =  html;
        overlay.setPosition(coord3857);
        
        // console.log( 'arrTs: <?php echo json_encode( $ts ); ?>' );
        var arrTs = JSON.parse( '<?php echo json_encode( $ts ); ?>' );
        var html = '<code>' + hdms + '</code>';
        var htmlGNSS = ''; var htmlSeism = ''; var htmlMSBAS = '';
        var numButSeism = 0; var numButGNSS = 0; var numButMSBAS = 0;
        $.ajax({type: "POST", 
          // url: p_url, // Error Cross-Origin Request Blocked
          url: "/index.php/geoserver/ajaxGetFeatInfo/",
          data: { urlGPSSta:   urlGPSSta,  
                  urlSeismSta: urlSeismSta,
                  urlSeismLoc: urlSeismLoc
                },
          success: function(result){ 
            console.log( 'arrClick: ' + result );
            var arrClick = JSON.parse( result ); 
             
            var station = '';
            for( var i = 0; i < arrTs.length; i++ )
            {
              station = arrTs[ i ][ 'ts_seism_station' ].trim(); 
              ts_name = arrTs[ i ][ 'ts_name' ];
              type = arrTs[ i ][ 'ts_type' ];
              // console.log( i + '- type: ' + type + ' -- ts_name:' + ts_name + ' -- station:' + station );

              // 1. Seism station
              if( type == 'histogram' && arrClick['SeismSta'] == station ) 
              {
                htmlSeism = htmlSeism + '<button id="but' + numButSeism 
                    + '" type="button" ' 
                    + ' onclick="load_async( \'histogram\', \'' 
                    + ts_name + '\', ' 
                    + coord4326[0] + ', ' + coord4326[1] 
                    + ' );" class="btn btn-primary btn-xs">' 
                    + arrClick['SeismSta'] + '</button>&nbsp;'; 
                numButSeism ++;
              }
              
              // 2. GNSS station
              if( type == 'gnss' && arrClick['GPSSta'] == station )
              {
                htmlGNSS = htmlGNSS + '<button id="but' + numButGNSS 
                    + '" type="button" ' 
                    + ' onclick="load_async( \'gnss\', \'' 
                    + ts_name + '\', ' 
                    + coord4326[0] + ', ' + coord4326[1] 
                    + ' );" class="btn btn-primary btn-xs">' 
                    + arrClick['GPSSta'] + '</button>&nbsp;'; 
                numButGNSS ++;
              }

              // 3. MSBAS
              /* Original code */
             if( (type == 'msbas')
             && ( coord4326[0] >= <?php echo $left; ?> )
             && ( coord4326[0] <= <?php echo $right; ?> )
             && ( coord4326[1] <= <?php echo $top; ?> )
             && ( coord4326[1] >= <?php echo $down; ?>)  )  // coord4326[1] is negative! */
            
              /* code only for Safari 
              if( (type == 'msbas')
              && ( coord4326[0] >= $left  )
              && ( coord4326[0] <= $right )
              && ( coord4326[1] <= $top   )
              && ( coord4326[1] >= $down  )  )  // coord4326[1] is negative!  */
              {
                htmlMSBAS = htmlMSBAS + '<button id="but' + numButMSBAS 
                    + '" type="button" ' 
                    + ' onclick="load_async( \'msbas\', \'' 
                    + ts_name + '\', ' 
                    + coord4326[0] + ', ' + coord4326[1] 
                    + ' );" class="btn btn-primary btn-xs">'
                    + ts_name + '</button>&nbsp;';
                numButMSBAS ++;
              }
            } // loop for timeseries

            // 4. Seism location
            if( arrClick['SeismLoc'] )
            {
              html = html + '<p>Seism location:<br/>' + arrClick['SeismLoc'] + '</p>';
            }
            
            if( arrClick['SeismSta'] )
            {
              if( numButSeism == 0 )
                html = html + '<p>No histogram found at ' +  arrClick['SeismSta'] + '. You may add it with timeseries create.</p>'; 
              else
                html = html + '<p>Load the histogram:<br/>' + htmlSeism + '</p>';
            }
              
            if( arrClick['GPSSta'] )
            {
              if( numButGNSS == 0 )
                html = html + '<p>No GNSS timeseries found at ' + arrClick['GPSSta'] + '. You may add it with timeseries create.</p>'; 
              else
                html = html + '<p>Load the GNSS ts:<br/>' + htmlGNSS + '</p>';
            }
              
            if( numButMSBAS == 0 )
              html = html + '<p>Out of boundaries of the timeseries configured.</p>';
            else
              html = html + '<p>Load the MSBAS ts:<br/>' + htmlMSBAS + '</p>';      

            content.innerHTML = html;
            overlay.setPosition(coord3857);

          } // success ajax
        }); // ajax
      }      
    </script>
    
    <!-- jQuery is necessary for Bootstrap but already loaded -->
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo base_url('assets/js/bootstrap.min.js');?>"></script>
    <script src="<?php echo base_url('assets/js/jquery-sortable.js');?>"></script>
    
  </body>
</html>