<?php
// echo var_dump( $this->session->userdata );

defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>

  <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet" />
  
  <link rel="stylesheet" href="<?php echo base_url('assets/css/ol.css');?>" type="text/css" />
  <link rel="stylesheet" href="<?php echo base_url('assets/css/range.css');?>" type="text/css" />
  <script src="<?php echo base_url('assets/js/ol.js');?>" type="text/javascript"></script>
  <script src="<?php echo base_url('assets/js/jquery-2.1.4.min.js');?>" type="text/javascript"></script>
    <style type="text/css">
      .ol-popup {
        position: absolute;
        background-color: white;
        -webkit-filter: drop-shadow(0 1px 4px rgba(0,0,0,0.2));
        filter: drop-shadow(0 1px 4px rgba(0,0,0,0.2));
        padding: 15px;
        border-radius: 10px;
        border: 1px solid #cccccc;
        bottom: 12px;
        left: -50px;
        width: 250px;
      }
      .ol-popup:after, .ol-popup:before {
        top: 100%;
        border: solid transparent;
        content: " ";
        height: 0;
        width: 0;
        position: absolute;
        pointer-events: none;
      }
      .ol-popup:after {
        border-top-color: white;
        border-width: 10px;
        left: 48px;
        margin-left: -10px;
      }
      .ol-popup:before {
        border-top-color: #cccccc;
        border-width: 11px;
        left: 48px;
        margin-left: -11px;
      }
      .ol-popup-closer {
        text-decoration: none;
        position: absolute;
        top: 2px;
        right: 8px;
      }
      .ol-popup-closer:after {
        content: "✖";
      }
      body.dragging, body.dragging * {
        cursor: move !important;
      }
    </style>
  
  <title>Home | wisdom-volkano</title>
</head>
<body>
  <div class="container">
      <div class="row Navigation">
          <div class="menu col-xs-12">
            <?php echo menu('home'); ?>
          </div>
      </div>
      <div class="row Main">
          <div class="layers col-md-3" id="layers">

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
                                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
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
                                    // TBD: paginado? search?
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
                    <input id="visible0" class="visible" type="checkbox" />&nbsp;bg:OpenStreetMaps<input id="opac0" class="opacity" type="range" min="0" max="1" step="0.01"/>
                  </fieldset>
                  <fieldset id="layer1">
                    <input id="visible1" class="visible" type="checkbox" />&nbsp;bg:MapBox<input id="opac1" class="opacity" type="range" min="0" max="1" step="0.01"/>
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
                To be completed in iteration 2.
              </div>
            </div>          
          
          </div>
          
          <div class="map col-md-9" id="map">
            <div id="popup" class="ol-popup">
                <a href="#" id="popup-closer" class="ol-popup-closer"></a>
                <div id="popup-content"></div>
            </div>          
          </div>
          
      </div>
      <div class="row Timeseries">
          <div class="graph col-md-6">
              <div class="panel panel-default">
                <div class="panel-body">
                  Timeseries graph. To be completed in iteration 2.
                </div>
              </div>            
          </div>
          
          <div class="data col-md-6">
              <div class="panel panel-default">
                <div class="panel-body" style="min-height: 100px; max-height: 100px; height: 100px; overflow-y: scroll;">
                  <table class="table table-condensed table-hovered table-bordered">
                    <tr class="table-info"><th>date</th><th>p1</th><th>p2</th><th>p3</th></tr>
                    <tr>
                      <td>23.10.2011</td>
                      <td>value X</td>
                      <td>value X</td>
                      <td>value X</td>
                    </tr>
                    <tr>
                      <td>09.11.2011</td>
                      <td>value X</td>
                      <td>value X</td>
                      <td>value X</td>
                    </tr>
                    <tr>
                      <td>18.11.2011</td>
                      <td>value X</td>
                      <td>value X</td>
                      <td>value X</td>
                    </tr>
                    <tr>
                      <td>01.12.2011</td>
                      <td>value X</td>
                      <td>value X</td>
                      <td>value X</td>
                    </tr>
                  </table>
                  Timeseries data sheet. To be completed in iteration 2.
                </div>
              </div>            
          </div>
      </div>
  </div>
  
  
    <script type="text/javascript">
      var listLayers = [ 
          new ol.layer.Tile({
            title: "bg-osm",
            source: new ol.source.OSM()
          }),
          new ol.layer.Tile({
            title: "bg-mapbox",
            source: new ol.source.TileJSON({
              url: 'http://api.tiles.mapbox.com/v3/' +
                  'mapbox.natural-earth-hypso-bathy.jsonp',
              crossOrigin: 'anonymous'
            })
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
          echo "        params: {LAYERS: '" . $layer->layer . "'} \n";
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
        var coordinate = evt.coordinate;
        var hdms = ol.coordinate.toStringHDMS(ol.proj.transform(
            coordinate, 'EPSG:3857', 'EPSG:4326'));

        content.innerHTML = '<p>You clicked here:</p><code>' + hdms +
            '</code>';
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