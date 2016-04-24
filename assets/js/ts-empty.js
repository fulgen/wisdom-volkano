$(function () {

  $('#chart0').highcharts({
    chart: { type: 'column', zoomType: 'x' },
    title: { text: 'No data yet' },
    xAxis: { type: 'datetime' },
    series: [{
        name: 'Select a point in the map in order to show the timeseries',
        lineWidth: 0, 
        color: '#fff', 
        showInLegend: false,  
        formatter: function() { return false; }, 
        enableMouseTracking: false, 
        dataLabels: { 
          enabled: true, 
          rotation: -90, 
          color: '#000', // 877 
          align: 'left', 
          formatter: function() { return ar[ this.x ]; }, 
          y: -2, // pixels from the origin 
          style: { 
            fontSize: '9px', 
            fontFamily: 'Arial Narrow, Arial, Helvetica Condensed, Helvetica, sans-serif' 
          } 
        }, 
        data: dataH 
    }],
    exporting: { buttons: { contextButton: { align: 'left' } } }
  });
  
  // Ts load in first execution
  var newdiv;
  for( var i = 1; i <= ( ts_seism_num + ts_gnss_num ); i++ )
  {
    newdiv = '<div class="chart" id="chart' + i + '">';
    // console.log( 'newdiv: chart' + i + ' created ' );
    $( newdiv ).appendTo( $('#container') );
  }
  call_ts_ajax();
});


/** 
 * Main loader of ts data. Charts available: msbas (data, lon, lat), seismo (station), gnss (station)
 */
function load_async( ts_type, ts_name, lon, lat )
{
  switch( ts_type )
  {
    case 'msbas':
      ts_msbas_data.push( ts_name );
      ts_msbas_lon.push( lon );
      ts_msbas_lat.push( lat );
      ts_msbas_num ++;
      // console.log( 'type:' + ts_type + ' - data:' + ts_msbas_data + ' - lon:' + ts_msbas_lon + ' - lat:' + ts_msbas_lat );      
      break;
    case 'histogram': 
      if( ts_seism_data.indexOf( ts_name ) < 0 ) // not added yet
      { 
        // ts_seism_data.push( ts_name );
        ts_seism_data[ ts_seism_num ] = ts_name;
        ts_seism_num ++;
        var newdiv = '<div class="chart" id="chart' + ts_seism_num + '">';
        // console.log( 'newdiv: ' + newdiv + ' created ' );
        $( newdiv ).appendTo( $('#container') );
      }
      else
      {
        console.log( 'already added, skipping ' );      
      }
      // console.log( 'type:' + ts_type + ' - data:' + ts_seism_data );      
      break;
    case 'gnss':
      if( ts_gnss_data.indexOf( ts_name ) < 0 ) // not added yet
      { 
        // ts_gnss_data.push( ts_name );
        ts_gnss_data[ ts_gnss_num ] = ts_name;
        ts_gnss_num ++;
        var next = ts_seism_num + ts_gnss_num;
        var newdiv = '<div class="chart" id="chart' + next + '">';
        console.log( 'newdiv: ' + newdiv + ' created ' );
        $( newdiv ).appendTo( $('#container') );
      }
      else
      {
        console.log( 'already added, skipping ' );      
      }
      // console.log( 'type:' + ts_type + ' - data:' + ts_gnss_data );      
      // gnss always after histogram
      break;
  }
  call_ts_ajax();
}


function call_ts_ajax()
{
  $.ajax({type: "POST", 
     url: "/index.php/mapa/load_ts_async/",
     // json to be decoded in php with json_decode
     data: { ts_msbas: array2json( ts_msbas_data ), 
             ts_histo: array2json( ts_seism_data ),
             ts_gnss: array2json(  ts_gnss_data  ),
             lon:  array2json( ts_msbas_lon ),
             lat:  array2json( ts_msbas_lat ) },
     success: function(result){ 
        // console.log( result ); // uncomment to debug the ts code
        try {
            eval(result); 
        } catch (e) {
            if (e instanceof SyntaxError) {
                console.log( 'load_ts_async error: ' + e.message );
            }
        }
        s = form_modal_list_ts();
        $("#modalTSbody").html( s ); 
        $("ol.sortable").sortable();
        
        // and save the list to config
        $.ajax({type: "POST", 
           url: "/index.php/status/ajaxTSConfig/",
           data: { ts_msbas: array2json( ts_msbas_data ), 
                   ts_histo: array2json( ts_seism_data ),
                   ts_gnss: array2json(  ts_gnss_data  ),
                   lon:  array2json( ts_msbas_lon ),
                   lat:  array2json( ts_msbas_lat ) },
           success: function(result){ 
             //console.log( 'session timeseries saved ' + result );
           },
           error: function( jqXHR, textStatus, errorThrown ) { 
              console.log(JSON.stringify(jqXHR));
              console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
           }
        }); 
        
     },
     error: function( jqXHR, textStatus, errorThrown ) { 
        console.log(JSON.stringify(jqXHR));
        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
     }
  }); 
}


function form_modal_list_ts()
{
  var s = '<ol class="sortable">                               \n';
  
  for( var i = 0; i < ts_msbas_num; i++ )
  {
    s += '    <li>                                         \n'
      +  '       <input type="checkbox" name="checkts[]"     '
      +  '              value="' + i + '" checked="checked">&nbsp;' 
      +          ts_msbas_data[ i ] 
      +  '       [' + round_number( ts_msbas_lon[i], 3 ) + ',' 
      +               round_number( ts_msbas_lat[i], 3 ) + ']&nbsp;'
      +  '    </li>                                        \n';
  }

  s += '</ol>                                              \n';
   
  return s;
}



function  call_modal_detrend_ts( type, idx )
{
  var idChart = 0;
  if( idx > 0 ) // gnss
    idChart = ts_seism_num + idx;
  var chart = $('#chart' + idChart).highcharts();
  var xextremes = chart.xAxis[0].getExtremes();
  var yextremes = chart.yAxis[0].getExtremes();
  var maxx = Highcharts.dateFormat('%Y-%m-%d', xextremes.max );
  var minx = Highcharts.dateFormat('%Y-%m-%d', xextremes.min ); 
  $("#detrendtype").val( type );
  $("#minx").val( minx );
  $("#maxx").val( maxx );
  
  var s = '<p>Detrend ' + $("#detrendtype").val() + ' from date ' + $("#minx").val() + ' to ' + $("#maxx").val() + ' which timeseries?</p>\n';

  s += '<ol> \n';
  if( type == 'msbas' )
  {
    for( var i = 0; i < ts_msbas_num; i++ )
    {
      var msbas = ts_msbas_data[ i ] 
        +  '[' + round_number( ts_msbas_lat[i], 3 ) + ',' 
        +        round_number( ts_msbas_lon[i], 3 ) + ']';

      s += '    <li>                                         \n'
        +  '       <input type="radio" name="radiots"     '
        +  '              value="' + msbas + '" />&nbsp;' + msbas  
        +  '    </li>                                        \n';
    }
  }
  else // gnss
  {
    // ts_gnss_data is an object here, back to an array
    var arGnss = $.map(ts_gnss_data, function(value, index) { return [value]; });
    s += '    <li><input type="radio" name="radiots" '
      +  '        value="' + arGnss[idx-1] + '[EW]" />&nbsp;' + arGnss[idx-1] + '&nbsp;EW</li>\n';
    s += '    <li><input type="radio" name="radiots" '
      +  '        value="' + arGnss[idx-1] + '[NS]" />&nbsp;' + arGnss[idx-1]  + '&nbsp;NS</li>\n';
    s += '    <li><input type="radio" name="radiots" '
      +  '        value="' + arGnss[idx-1] + '[UP]" />&nbsp;' + arGnss[idx-1]  + '&nbsp;UP</li>\n';
  }

  s += '</ol> \n';
  
  $("#detrendTSbody").html( s ); 
  $("#detrendTS").modal('show');
}



$(function  () {

  /* Detrend TS: at least one radio selected */
  $('#formdetrend').submit(function()
  {
    if( ! $("input[type=radio]:checked").val() ) 
    {
      alert( 'Please select at least one timeseries.' );
      return false; 
    }  
  });

/* Manage Ts: Rearranging the content of the msbas list */
  $("#buttonts").on('click', function() {
    var new_ts  = [];
    var new_lon = [];
    var new_lat = [];
    var i = 0;
    // 0. if none checked
    if( $("input[name='checkts[]']:checked").size() == 0 )
    {
      ts_msbas_data = []; ts_msbas_data.length = 0;
      ts_msbas_lon  = []; ts_msbas_lon.length = 0;
      ts_msbas_lat  = []; ts_msbas_lat.length = 0;
    }
    else
    {
      // 1. call to rearrange the content in the form in the 3 arrays
      $.each( $("input[name='checkts[]']:checked"), function() {
        i = $(this).val();
        // console.log( 'reordering ' + i );
        new_ts.push( ts_msbas_data[ i ] );
        new_lon.push( ts_msbas_lon[ i ] );
        new_lat.push( ts_msbas_lat[ i ] );
      });
      ts_msbas_data = []; ts_msbas_data.length = 0;
      ts_msbas_lon  = []; ts_msbas_lon.length = 0;
      ts_msbas_lat  = []; ts_msbas_lat.length = 0;
      ts_msbas_data = new_ts.slice();
      ts_msbas_lon  = new_lon.slice();
      ts_msbas_lat  = new_lat.slice();
      ts_msbas_num  = new_ts.length;
    }
    
    // 2. call ajax to reload the charts 
    call_ts_ajax();
    // console.log( 'type:msbas - data:' + ts_msbas_data + ' - lon:' + ts_msbas_lon + ' - lat:' + ts_msbas_lat );      
    
    // 3. close modal window
    $('#modalTS').modal('hide');
  });
  
   
  
  /* button to store timeseries points as favorites */
  $("#btfav").on('click', function() {
    $.ajax({type: "POST", 
       url: "/index.php/favorite/ajaxTSSave/",
       data: { ts_msbas: array2json( ts_msbas_data ), 
               lon:  array2json( ts_msbas_lon ),
               lat:  array2json( ts_msbas_lat ) },
       success: function(result){ 
         // console.log( 'favorites saved ' + result );
       },
       error: function( jqXHR, textStatus, errorThrown ) { 
          console.log(JSON.stringify(jqXHR));
          console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
       }
    }); 
    // console.log( 'favorites saved ts:' + ts_msbas_data + ' - lon:' + ts_msbas_lon + ' - lat:' + ts_msbas_lat  );      
    $('#modalTS').modal('hide');
  });
  
  
  /* button to reset timeseries */
  $("#btn_ts_reset").on('click', function() {
    if( confirm( 'Are you sure to reset the timeseries?' ) )
    {
      ts_msbas_data = []; ts_msbas_data.length = 0;
      ts_msbas_lon  = []; ts_msbas_lon.length = 0;
      ts_msbas_lat  = []; ts_msbas_lat.length = 0;
      ts_seism_data = []; ts_seism_data.length = 0;
      ts_gnss_data  = []; ts_gnss_data.length = 0;
      
      /* Loop to remove div charts except the first one */
      for( var i = 1; i <= ( ts_seism_num + ts_gnss_num ); i ++ )
      {
        $('#chart' + i).remove(); 
        // chart.destroy();
      }
      ts_msbas_num = ts_seism_num = ts_gnss_num = 0;
    
      $.ajax({type: "POST", 
         url: "/index.php/status/ajaxReset/",
         data: { },
         success: function(result){ 
           console.log( 'status reset ' + result );
           call_ts_ajax();
           // And reload the page?
         },
         error: function( jqXHR, textStatus, errorThrown ) { 
            console.log(JSON.stringify(jqXHR));
            console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
         }
      }); 
    }
  });
});


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


// format: 2005.40301
// Convert date: 0.0000 = 1st Jan, 00:00:00; 0.9999 = 31st Dec, 23:59:59
function tick2Date( yeartick ) {
  var year = yeartick.substring( 0, yeartick.indexOf( "." ) );
  var daysinyear = 365;
  if(((year%4==0) && (year%100!=0)) || year%400==0) {
    daysinyear = 366;
  }
  
  var dayofyear, day, month;
  var tick = yeartick - year; 

  dayofyear = Math.floor( tick * daysinyear ); // days in Javascript start at 0
  // console.log( 'yeartick: ' + yeartick + ' -- dayofyear: ' + dayofyear );
  if( dayofyear < 31 ) { // Jan
    month = '0'; // months in Javascript start at 0
    day = dayofyear;
  }
  else if( dayofyear < 59 ) { // Feb
    month = '1';
    day = dayofyear - 31;
  }
  else if( dayofyear < 90 ) { // Mar
    month = '2';
    day = dayofyear - 59;
  }
  else if( dayofyear < 120 ) { // Apr
    month = '3';
    day = dayofyear - 90;
  }
  else if( dayofyear < 151 ) { // May
    month = '4';
    day = dayofyear - 120;
  }
  else if( dayofyear < 181 ) { // Jun
    month = '5';
    day = dayofyear - 151;
  }
  else if( dayofyear < 212 ) { // Jul
    month = '6';
    day = dayofyear - 181;
  }
  else if( dayofyear < 243 ) { // Aug
    month = '7';
    day = dayofyear - 212;
  }
  else if( dayofyear < 273 ) { // Sep
    month = '8';
    day = dayofyear - 243;
  }
  else if( dayofyear < 304 ) { // Oct
    month = '9';
    day = dayofyear - 273;
  }
  else if( dayofyear < 334 ) { // Nov
    month = '10';
    day = dayofyear - 304;
  }
  else { // Dec
    month = '11';
    day = dayofyear - 334; 
  }
  var timeofyear = ( tick * daysinyear ) - Math.floor( tick * daysinyear ); 
  var hour = Math.floor( timeofyear * 24 );
  timeofyear = ( timeofyear * 24 ) - hour;
  var minute = Math.floor( timeofyear * 60 ); 
  timeofyear = ( timeofyear * 60 ) - minute;
  var second = Math.floor( timeofyear * 60 );
  var millisec = ( timeofyear * 60 ) - second;

  var date = Date.UTC( year, month, day, hour, minute, second, millisec ); // must follow the example http://www.highcharts.com/demo/spline-irregular-time 
  //console.log( date );
  return date;
}

function round_number(num, dec) {
    return Math.round(num * Math.pow(10, dec)) / Math.pow(10, dec);
}


function call_layer_visib_ajax( layer_id, value )
{
  $.ajax({type: "POST", 
     url: "/index.php/status/ajaxLayerVisib/",
     data: { id: layer_id, 
             val: value
           },
     success: function(result){ 
       // console.log( 'layers visibility/opacity saved ' + result );
     },
     error: function( jqXHR, textStatus, errorThrown ) { 
        console.log(JSON.stringify(jqXHR));
        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
     }
  }); 
}



