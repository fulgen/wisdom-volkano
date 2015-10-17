
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

$(function () {
  var tsv1 = $.get( "/assets/data/msbas/EW/Time_Series/VVP_ML_1_Pixel_FullSerie_238_370test_EW_Detrended.dat" );
  var tsv2 = $.get( "/assets/data/msbas/UP/Time_Series/VVP_ML_1_Pixel_FullSerie_238_370test_UP._Detrended.dat" );
  
  // wait for both async calls to be finished
  tsv1.done( function( csv ) {
    if( csv == 'false' ) return 'Data not found'; // TBD
    else {
    
  tsv2.done( function( csv2 ) {
    if( csv2 == 'false' ) return 'Data not found'; // TBD
    else {
  
    var fecha, lines, items;
    var dataEW = []; // [ dates, values ]
    var dataUP = []; // [ dates, values ]
  
    lines = csv.split( '\n' );
    $.each( lines, function( lineNo, line ) {
      items = line.split( '\t' );
      fecha = tick2Date( items[ 0 ] );
      dataEW.push( [ fecha, parseFloat( items[1] ) ] );
      // console.log( lineNo + ' fecha: ' + fecha + ' value: ' + items[ 1 ] );
    });

    lines = csv2.split( '\n' ); 
    $.each( lines, function( lineNo, line ) {
      items = line.split( '\t' );
      fecha = tick2Date( items[ 0 ] );
      dataUP.push( [ fecha, parseFloat( items[1] ) ] ); 
      // console.log( lineNo + ' fecha: ' + fecha + ' value: ' + items[ 1 ] );
    });
    //console.log( dataEW );
    
    $('#container').highcharts({
        chart: {
          type: 'line',
          spacingBottom: 0,
          spacingTop: 0,
          spacingLeft: 0,
          spacingRight: 0          
        },
        title: {
          text: 'Timeseries of MSBAS'
        },
        xAxis: {
            type: 'datetime',
            gridLineWidth: 1            
        }, 
        yAxis: {
            title: {
                text: 'displacement in cm'
            },
            alternateGridColor: '#f5f5f5'            
        },
        series: [{
            name:  'detrend-EW',
            color: '#d66',
            data: dataEW
        }, {
            name:  'detrend-UP',
            color: '#6d6',
            data: dataUP
        }], 
        legend: {
            align: 'right',
            x: -30,
            verticalAlign: 'top',
            y: 25,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
            formatter: function () {
                var d = new Date( this.x );
                var day = d.getDate() + 1;  if( day < 10 ) day = '0' + day;
                var mon = d.getMonth() + 1; if( mon < 10 ) mon = '0' + mon;
                var fecha = d.getFullYear() + '-' + mon + '-' + day;
                var total = this.point.stackTotal;
                var str = '<b>' + fecha + '</b><br/>' +
                    this.series.name + ': ' + this.y + '<br/>';
                if( typeof total != 'undefined' ) 
                {
                  str = str + 'Total: ' + total;
                }
                return str;
            }
        },
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
  } });
  } });
});
