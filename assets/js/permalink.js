/**  
 * From http://openlayers.org/en/master/examples/permalink.html
 */

// default values zoom, lat/lon set up in views/mapa

$(function  () {

  if (window.location.hash !== '') {
    // try to restore center, zoom-level from the URL
    var hash = window.location.hash.replace('#map=', '');
    var parts = hash.split('/');
    if (parts.length === 3) { // 4 with rotation
      zoom = parseInt(parts[0], 10);
      lon = parseFloat(parts[1]);
      lat = parseFloat(parts[2]);
      center = [ lon, lat ];
      // and update the view with them 
      map.getView().setCenter( center );
      map.getView().setZoom( zoom );
      ajaxSavePermalink();
    }
  }

  var shouldUpdate = true;
  var view = map.getView();
  var updatePermalink = function() {
    if (!shouldUpdate) {
      // do not update the URL when the view was changed in the 'popstate' handler
      shouldUpdate = true;
      return;
    }

    var center = view.getCenter();
    zoom = view.getZoom();
    lon  = Math.round(center[0] * 100) / 100;
    lat  = Math.round(center[1] * 100) / 100;
    var hash = '#map=' + zoom + '/' + lon + '/' + lat;
    var state = {
      zoom: view.getZoom(),
      center: view.getCenter()
    };
    window.history.pushState(state, 'map', hash);
    ajaxSavePermalink();
  };

  map.on('moveend', updatePermalink);

  // restore the view state when navigating through the history, see
  // https://developer.mozilla.org/en-US/docs/Web/API/WindowEventHandlers/onpopstate
  window.addEventListener('popstate', function(event) {
    if (event.state === null) {
      return;
    }
    map.getView().setCenter(event.state.center);
    map.getView().setZoom(event.state.zoom);
    shouldUpdate = false;
  });
  
});

function ajaxSavePermalink()
{
  $.ajax({type: "POST", 
     url: "/index.php/status/ajaxPermaURL/",
     data: { ps_zoom: zoom, 
             ps_lat:  lat,
             ps_lon:  lon 
           },
     success: function(result){ 
     },
     error: function( jqXHR, textStatus, errorThrown ) { 
        console.log(JSON.stringify(jqXHR));
        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
     }
  }); 
}
