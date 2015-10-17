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
  <script src="<?php echo base_url('assets/js/jquery-2.1.4.min.js');?>" type="text/javascript"></script>
  <script type="text/javascript"
 src="http://maps.googleapis.com/maps/api/js?key=<?php echo $this->config->item( 'gmaps_key' ); ?>&sensor=TRUE"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/js/ol-source-gmaps-tms.min.js');?>"></script>  

<!-- timeseries -->  
  <script src="<?php echo base_url('assets/js/highcharts.js');?>" type="text/javascript"></script>
  <script src="<?php echo base_url('assets/js/data.js');?>" type="text/javascript"></script>
  <script src="<?php echo base_url('assets/js/exporting.js');?>" type="text/javascript"></script>
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
                    <input id="visible0" class="visible" type="checkbox" />&nbsp;bg:GoogleMaps<input id="opac0" class="opacity" type="range" min="0" max="1" step="0.01"/>
                  </fieldset>
                  <fieldset id="layer1">
                    <input id="visible1" class="visible" type="checkbox" />&nbsp;bg:OpenStreetMaps<input id="opac1" class="opacity" type="range" min="0" max="1" step="0.01"/>
                  </fieldset>
                  <fieldset id="layer2">
                    <input id="visible2" class="visible" type="checkbox" />&nbsp;bg:Seismo stations<input id="opac2" class="opacity" type="range" min="0" max="1" step="0.01"/>
                  </fieldset>
                  <fieldset id="layer3">
                    <input id="visible3" class="visible" type="checkbox" />&nbsp;bg:GPS stations<input id="opac3" class="opacity" type="range" min="0" max="1" step="0.01"/>
                  </fieldset>
<?php 
    $i = 4; // counting from backgrounds on
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

?>
                </div>
              </li>
            </ul>
          </form>
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Config panel</h3>
              </div>
              <div class="panel-body">
                To be completed in iteration 3.
              </div>
            </div>          
          
          </div>
          
          <div class="map col-lg-9" id="map">
            <div id="popup" class="ol-popup">
                <a href="#" id="popup-closer" class="ol-popup-closer"></a>
                <div id="popup-content"></div>
            </div>          
          </div>
          
      </div>
      <div class="row Timeseries">
          <div class="graph col-lg-12">
              <div class="panel panel-default">
                <div class="panel-body">
                  <div id="container" style="height: 250px; max-height: 250px;"></div>
                </div>
              </div>            
          </div>

                  <!-- Modal HTML -->
                  <div id="modalTS" class="modal fade">
                      <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&#x274E;</button>
                                  <h4 class="modal-title">Please select timeseries to load:</h4>
                              </div>
                              <div class="modal-body" id="modalTSbody"></div>
                              <div class="modal-body">
                                <p class="text-warning"><small>You may drag and drop timeseries to sort them, but only the first enabled type will be shown. If you don&quot;t save, your changes will be lost.</small></p>          
                              </div>
                              <div class="modal-footer">      
                                <button type="button" id="buttonts" class="btn btn-primary">Reload chart</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>                              
                              </div>
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
          }), 
          new ol.layer.Vector({
            source: new ol.source.Vector({
              url: "/assets/data/stations/Seismos.kml",
              format: new ol.format.KML()
            })
          }), 
          new ol.layer.Vector({
            source: new ol.source.Vector({
              url: "/assets/data/stations/GPS.kml",
              format: new ol.format.KML()
            })
          }) 
<?php           
  $i = 4; // Counting from background
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
      ];


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

      var scaleLineControl = new ol.control.ScaleLine();      
      var map = new ol.Map({
        target: 'map',
        layers: listLayers,
        overlays: [overlay],
        view: new ol.View({
          center: ol.proj.transform([29.1, -1.4], 'EPSG:4326', 'EPSG:3857'),
          zoom: 8
        }),
        controls: ol.control.defaults({
          attributionOptions: /* @type {olx.control.AttributionOptions} */ ({
            collapsible: false
          })
        }).extend([
          scaleLineControl
        ])
      });
      
      <?php /* Related to example http://openlayers.org/en/v3.7.0/examples/layer-group.html */ ?> 
      function bindInputs(layerid, layer) {
        var visibilityInput = $(layerid + ' input.visible');
        visibilityInput.on('change', function() {
          layer.setVisible(this.checked);
        });
        visibilityInput.prop('checked', layer.getVisible());

        $.each(['opacity'], // , 'hue', 'saturation', 'contrast', 'brightness'
            function(i, v) {
              var input = $(layerid + ' input.' + v);
              input.on('input change', function() {
                layer.set(v, parseFloat(this.value));
              });
              input.val(String(layer.get(v)));
            }
        );
      }
      map.getLayers().forEach(function(layer, i) {
        bindInputs('#layer' + i, layer);
        if (layer instanceof ol.layer.Group) {
          layer.getLayers().forEach(function(sublayer, j) {
            bindInputs('#layer' + i + j, sublayer);
          });
        }
      });
      
      /**
       * Add a click handler to the map to render the popup.
       */
      map.on('singleclick', function(evt) {
        var pixel = evt.pixel;
        var featSis = map.forEachFeatureAtPixel(pixel, function(featSis, layer) {
          return featSis;
        }, null, function( layer ) {
          return layer === listLayers[ 2 ]; // seism station
        });        
        var featGPS = map.forEachFeatureAtPixel(pixel, function(featGPS, layer) {
          return featGPS;
        }, null, function( layer ) {
          return layer === listLayers[ 3 ]; // GPS station
        });        
        var coordinate = evt.coordinate;
        var coord = ol.proj.transform( coordinate, 'EPSG:3857', 'EPSG:4326' );
        // var hdms = ol.coordinate.toStringHDMS( coord ); // º, ' and "
        var hdms = ol.coordinate.toStringXY( coord, 3 ); // º with 3 decimal
        // console.log( coord[0] + ' ' + coord[1] );
        
        var ts_type = ''; var src = '';
        if( featSis ) // on a Sismo station
        {
          ts_type = 'histogram';
          src = '<p>Seismic station: ' + featSis.get('name');
        <?php
          $but = 0;
          if( is_array( $ts ) ) 
          {
            foreach( $ts as $tss ) 
            {
              if( $tss->ts_type == 'histogram' )
              {
        ?>
        if( featSis.get('name') == '<?php echo $tss->ts_name; ?>' )
        {
          src = src + '<br/>Load the histogram:</p>';
          src = src + '<button id="but<?php echo $but ++; ?>" type="button" ' +
                    ' onclick="load_async( \'<?php echo $tss->ts_type; ?>\', \'<?php echo $tss->ts_name; ?>\',' + coord[0] + ', ' + coord[1] + 
                    '  );" class="btn btn-primary btn-xs"><?php echo $tss->ts_name; ?></button> '; 
        }
        else
        {
          src = src + '<br/>No histogram found. You may add it with timeseries create.</p>'
        }
        <?php
              }
            }
          }
        ?>
        }
        else if( featGPS )
        {
          ts_type = 'GPS';
          src = '<p>GPS station: ' + featGPS.get('name') + '</p>';
        }
        else // msbas
        {
          ts_type = 'msbas';
        <?php
          $but = 0;
          if( is_array( $ts ) ) 
          {
            foreach( $ts as $tss )
            {
              if( $tss->ts_type == 'msbas' )
              {
        ?>
        // console.log( 'check boundaries: lon ' + coord[0] + ' within left <?php echo $left; ?>, right <?php echo $right; ?>' );
        // console.log( 'check boundaries: lat ' + coord[1] + ' within top <?php echo $top; ?>, down <?php echo $down; ?> ' ); 
        // within the ts boundaries
        if( coord[0] >= <?php echo $left; ?> 
         && coord[0] <= <?php echo $right; ?> 
         && coord[1] <= <?php echo $top; ?> 
         && coord[1] >= <?php echo $down; ?> )  // coord[1] is negative!
        {
          // console.log( 'ok, within boundaries' );
          src = src + '<button id="but<?php echo $but ++; ?>" type="button" ' +
                      ' onclick="load_async( \'<?php echo $tss->ts_type; ?>\', \'<?php echo $tss->ts_name; ?>\', ' + coord[0] + ', ' + coord[1] + 
                      '  );" class="btn btn-primary btn-xs"><?php echo $tss->ts_name ?></button> '; 
        }
        else
        {
          // console.log( 'out of boundaries' );
          src = '<p>Out of boundaries of the timeseries configured.</p>';
        }

        <?php
              }
            }
          }
          if( $but == 0 )
          {
            ?>
            src = '<p>No msbas timeseries configured for this point, please ask the admin to create or grant you some.</p>';
            <?php
          }
          else
          {
            ?>
            src = '<code>' + hdms + '</code><br/><p>Load the timeseries-msbas: </p>' + src;
            <?php  
          }
        ?>
        }
        content.innerHTML = src;
        overlay.setPosition(coordinate);
      });

      $(function  () {
        $("ol.sortable").sortable();
      });
      
    </script>
    
    <!-- jQuery is necessary for Bootstrap but already loaded -->
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo base_url('assets/js/bootstrap.min.js');?>"></script>
    <script src="<?php echo base_url('assets/js/jquery-sortable.js');?>"></script>
    
  </body>
</html>