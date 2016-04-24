<!DOCTYPE html>
<html>
  <head>
    <title>test ts</title>

<script src="http://127.0.0.1:8085/assets/js/jquery-2.2.2.min.js" type="text/javascript"></script>    
<script src="http://127.0.0.1:8085/assets/js/highcharts.js" type="text/javascript"></script>
<script src="http://127.0.0.1:8085/assets/js/data.js" type="text/javascript"></script>
<script src="http://127.0.0.1:8085/assets/js/exporting.js" type="text/javascript"></script>
<script src="http://127.0.0.1:8085/assets/data/events.js" type="text/javascript"></script>
   
    
<script>    
var fecha, lines, items, val;                            
var dataEW = []; // [ dates, values ] 
var dataNS = []; // [ dates, values ] 
var dataUP = []; // [ dates, values ] 
$(function () {                                          
  var numCharts = Highcharts.charts.length; 
  var tsv0 = $.get( 'http://127.0.0.1:8085/assets/data/msbas/UP/Time_Series/VVP_ML_1_Pixel_FullSerie_264_680test_UP.dat' );        
  var tsv1 = $.get( 'http://127.0.0.1:8085/assets/data/msbas/EW/Time_Series/VVP_ML_1_Pixel_FullSerie_530_781test_EW.dat' );        
  var tsv2 = $.get( 'http://127.0.0.1:8085/assets/data/msbas/EW/Time_Series/VVP_ML_1_Pixel_FullSerie_284_690test_EW.dat' );        
  var tsv3 = $.get( 'http://127.0.0.1:8085/assets/data/msbas/EW/Time_Series/VVP_ML_1_Pixel_FullSerie_271_680test_EW.dat' );        
  tsv0.done( function( csv0 ) {        
     if( csv0 == 'false' ) return 'Data not found'; // TBD 
     else {                                               
  tsv1.done( function( csv1 ) {        
     if( csv1 == 'false' ) return 'Data not found'; // TBD 
     else {                                               
  tsv2.done( function( csv2 ) {        
     if( csv2 == 'false' ) return 'Data not found'; // TBD 
     else {                                               
  tsv3.done( function( csv3 ) {        
     if( csv3 == 'false' ) return 'Data not found'; // TBD 
     else {                                               
  var data0 = []; // [ dates, values ]          
  var data1 = []; // [ dates, values ]          
  var data2 = []; // [ dates, values ]          
  var data3 = []; // [ dates, values ]          
  lines = csv0.split( '\n' );                  
  $.each( lines, function( lineNo, line ) {               
    items = line.split( '\t' );                          
    fecha = tick2Date( items[ 0 ] );                      
    data0.push( [ fecha, parseFloat( items[1] ) ] );
  });                                                     
  lines = csv1.split( '\n' );                  
  $.each( lines, function( lineNo, line ) {               
    items = line.split( '\t' );                          
    fecha = tick2Date( items[ 0 ] );                      
    data1.push( [ fecha, parseFloat( items[1] ) ] );
  });           
console.log( data1 );  
  lines = csv2.split( '\n' );                  
  $.each( lines, function( lineNo, line ) {               
    items = line.split( '\t' );                          
    fecha = tick2Date( items[ 0 ] );                      
    data2.push( [ fecha, parseFloat( items[1] ) ] );
  });                                                     
console.log( data2 );  
  lines = csv3.split( '\n' );                  
  $.each( lines, function( lineNo, line ) {               
    items = line.split( '\t' );                          
    fecha = tick2Date( items[ 0 ] );                      
    data3.push( [ fecha, parseFloat( items[1] ) ] );
  }); 
console.log( data3 );  
  
  $('#chart0').highcharts({                           
    chart: { type: 'line', zoomType: 'x' },                 
    title: { text: 'Timeseries of MSBAS' },                 
    xAxis: {                                                
      type: 'datetime',                                     
      gridLineWidth: 1,                                     
      crosshair: true, 
       events: { 
      } 
    },                                                      
    yAxis: {                                                
      title: { text: 'displacement in cm' },                
      alternateGridColor: '#f5f5f5'                         
    },                                                      
    series: [{                                             
         name: 'events', 
         lineWidth: 0, 
         color: '#fff', 
         showInLegend: false,  
         enableMouseTracking: false, 
         dataLabels: { 
             enabled: true, 
             rotation: -90, 
             color: '#000', // 877 
             align: 'left', 
             formatter: function() { 
               return ar[ this.x ]; 
             }, 
             y: -2, // pixels from the origin 
             style: { 
                 fontSize: '9px', 
                 fontFamily: 'Arial Narrow, Arial, Helvetica Condensed, Helvetica, sans-serif' 
             } 
         }, 
         data: dataH 
     }, { 
      name:  ' up           lat: -1.667 lon: 29.22', 
         showInLegend: true,  
         enableMouseTracking: true, 
      data: data0                               
    }    /*                                                 
 , {                            
      name:  ' ew           lat: -1.751 lon: 29.442', 
         showInLegend: true,  
         enableMouseTracking: true, 
      data: data1                               
    }        */                                           
 , {                            
      name:  ' ew           lat: -1.675 lon: 29.237', 
         showInLegend: true,  
         enableMouseTracking: true, 
      data: data2                               
    }                                                    
 , {                            
      name:  ' ew           lat: -1.667 lon: 29.226', 
         showInLegend: true,  
         enableMouseTracking: true, 
      data: data3                               
    }                                                  
    ]
  });                                                  
  } });                                                  
  } });                                                  
  } });                                                  
  } });                                                  
}); 




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

function round_number(num, dec) {
    return Math.round(num * Math.pow(10, dec)) / Math.pow(10, dec);
}

</script>    
    
  </head>
  <body>
    <div id="container">
      <div id="chart0" class="chart panel panel-default"></div>
      <div id="chart1" class="chart panel panel-default"></div>
      <div id="chart2" class="chart panel panel-default"></div>
      <div id="chart3" class="chart panel panel-default"></div>
      <div id="chart4" class="chart panel panel-default"></div>
    </div>
  </body>
</html>