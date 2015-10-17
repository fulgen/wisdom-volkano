/* http://jsfiddle.net/gh/get/jquery/1.7.2/highslide-software/highcharts.com/tree/master/samples/highcharts/no-data-to-display/no-data-pie/ */

$(function () {

  $('#container').highcharts({
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false
    },
    title: {
        text: 'No data yet'
    },
    series: [{
        type: 'line',
        name: 'Select a point in the map in order to show the timeseries',
        data: []
    }],
    credits: {
      enabled: false
    },
    exporting: {
      enabled: true,
      buttons: {
        contextButton: {
          align: 'left',
          x: 10, 
          menuItems: [{
            text: 'manage timeseries',
            onclick: function() {
               $('#modalTS').modal('show'); 
            }
          }, {
            text: 'export to PNG',
            onclick: function() {
              this.exportChart();
            }              
          }]
        }
      }
    }
    
  });
});

var prev_type = "";
var ts_data = [];
var ts_lon  = [];
var ts_lat  = [];
var ts_num  = 0;
function load_async( ts_type, ts_name, lon, lat )
{
  if( ts_type != prev_type || ts_type == 'histogram' ) 
  {
    ts_data = [];
    ts_lon = [];
    ts_lat = [];
  }
  ts_data.push( ts_name );
  ts_lon.push( lon );
  ts_lat.push( lat );
  ts_num ++;
  prev_type = ts_type;
  
  console.log( 'data:' + ts_data + ' - type:' + ts_type + ' - lon:' + ts_lon + ' - lat:' + ts_lat );

  call_ajax( ts_type );
}


function call_ajax( ts_type )
{
  $.ajax({type: "POST", 
     url: "/index.php/mapa/load_ts_async/",
     // json to be decoded in php with json_decode
     data: { ts_name: array2json( ts_data ), 
             ts_type: ts_type, 
             lon:  array2json( ts_lon ),
             lat:  array2json( ts_lat ) },
     success: function(result){ 
       eval(result); 
       s = form_modal_list_ts();
       $("#modalTSbody").html( s ); 
       $("ol.sortable").sortable();
     },
     error: function( jqXHR, textStatus, errorThrown ) { 
        console.log(JSON.stringify(jqXHR));
        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
     }
  }); 
}

function form_modal_list_ts()
{
  s = '<ol class="sortable">                               \n';
  
  for( var i = 0; i < ts_num; i++ )
  {
    s += '    <li>                                         \n'
      +  '       <input type="checkbox" name="checkts[]"     '
      +  '              value="' + i + '" checked="checked">&nbsp;' 
      +          ts_data[ i ] 
      +  '       [' + round_number( ts_lon[i], 3 ) + ',' 
      +               round_number( ts_lat[i], 3 ) + ']    \n'
      +  '    </li>                                        \n';
  }

  s += '</ol>                                              \n';
   
  return s;
}


$(function  () {
  $("#buttonts").on('click', function() {
    var new_ts  = [];
    var new_lon = [];
    var new_lat = [];
    var i = 0;
    // 1. call to rearrange the content in the form in the 3 arrays
    $.each( $("input[name='checkts[]']:checked"), function() {
      i = $(this).val();
      // console.log( 'reordering ' + i );
      new_ts.push( ts_data[ i ] );
      new_lon.push( ts_lon[ i ] );
      new_lat.push( ts_lat[ i ] );
    });
    ts_data = []; ts_data.length = 0;
    ts_lon  = []; ts_lon.length = 0;
    ts_lat  = []; ts_lat.length = 0;
    ts_data = new_ts.slice();
    ts_lon = new_lon.slice();
    ts_lat = new_lat.slice();
    ts_num = new_ts.length;
    
    // 2. and call ajax above
    call_ajax( "msbas" );
    
    // 3. close modal window
    $('#modalTS').modal('hide');
  });
});


function round_number(num, dec) {
    return Math.round(num * Math.pow(10, dec)) / Math.pow(10, dec);
}


/**
 * Converts the given data structure to a JSON string.
 * Argument: arr - The data structure that must be converted to JSON
 * Example: var json_string = array2json(['e', {pluribus: 'unum'}]);
 * 			var json = array2json({"success":"Sweet","failure":false,"empty_array":[],"numbers":[1,2,3],"info":{"name":"Binny","site":"http:\/\/www.openjs.com\/"}});
 * http://www.openjs.com/scripts/data/json_encode.php
 */
function array2json(arr) {
    var parts = [];
    var is_list = (Object.prototype.toString.apply(arr) === '[object Array]');

    for(var key in arr) {
    	var value = arr[key];
        if(typeof value == "object") { //Custom handling for arrays
            if(is_list) parts.push(array2json(value)); /* :RECURSION: */
            else parts.push('"' + key + '":' + array2json(value)); /* :RECURSION: */
            //else parts[key] = array2json(value); /* :RECURSION: */
            
        } else {
            var str = "";
            if(!is_list) str = '"' + key + '":';

            //Custom handling for multiple data types
            if(typeof value == "number") str += value; //Numbers
            else if(value === false) str += 'false'; //The booleans
            else if(value === true) str += 'true';
            else str += '"' + value + '"'; //All other things
            // :TODO: Is there any more datatype we should be in the lookout for? (Functions?)

            parts.push(str);
        }
    }
    var json = parts.join(",");
    
    if(is_list) return '[' + json + ']';//Return numerical JSON
    return '{' + json + '}';//Return associative JSON
}


/** Function to be used with msbas timeseries **/
// format: 2005.40301
// Convert date: 0.0000 = 1st Jan, 00:00:00; 0.9999 = 31st Dec, 23:59:59
function tick2Date( yeartick ) {
  var year = yeartick.substring( 0, yeartick.indexOf( "." ) );

  var dayofyear, day, month;
  var tick = yeartick - year; 

  dayofyear = Math.round( tick * 365 ); // days in Javascript start at 0
  // console.log( 'yeartick: ' + yeartick + ' -- dayofyear: ' + dayofyear );
  if( dayofyear <= 31 ) { // Jan
    month = '0'; // months in Javascript start at 0
    day = dayofyear;
  }
  else if( dayofyear <= 59 ) { // Feb
    month = '1';
    day = dayofyear - 31;
  }
  else if( dayofyear <= 90 ) { // Mar
    month = '2';
    day = dayofyear - 59;
  }
  else if( dayofyear <= 120 ) { // Apr
    month = '3';
    day = dayofyear - 90;
  }
  else if( dayofyear <= 151 ) { // May
    month = '4';
    day = dayofyear - 120;
  }
  else if( dayofyear <= 181 ) { // Jun
    month = '5';
    day = dayofyear - 151;
  }
  else if( dayofyear <= 212 ) { // Jul
    month = '6';
    day = dayofyear - 181;
  }
  else if( dayofyear <= 243 ) { // Aug
    month = '7';
    day = dayofyear - 212;
  }
  else if( dayofyear <= 273 ) { // Sep
    month = '8';
    day = dayofyear - 243;
  }
  else if( dayofyear <= 304 ) { // Oct
    month = '9';
    day = dayofyear - 273;
  }
  else if( dayofyear <= 334 ) { // Nov
    month = '10';
    day = dayofyear - 304;
  }
  else { // Dec
    month = '11';
    day = dayofyear - 334; 
  }
  var date = Date.UTC( year, month, day ); // must follow the example http://www.highcharts.com/demo/spline-irregular-time 
  //console.log( date );
  return date;
}
