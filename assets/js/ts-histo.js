$(function () {
  $.get('/assets/data/seism-count/kbb.tsv', function(csv) {
    $('#container').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Stacked column chart'
        },
        data: {
            csv: csv,
            itemDelimiter: '\t',
            lineDelimiter: '\n'
        },        
        series: [{
            type:  'column',
            name:  'LP',
            yAxis: 0,
            allowPointSelect: true,
            color: '#66d',
        }, {
            type:  'column',
            name:  'SP',
            yAxis: 0,
            allowPointSelect: true,
            color: '#d66',
        }, {
            type:  'line',
            name:  'LP-accumulated',
            yAxis: 1,
            allowPointSelect: true,
            color: 'blue'
        }, {
            type:  'line',
            name:  'SP-accumulated',
            yAxis: 2,
            allowPointSelect: true,
            color: 'red'
        }],        
        yAxis: [{
            min: 0,
            title: {
                text: 'number of events'
            } 
        }, { // secondary yAxis for LP-accumulated
            opposite: true,
            min: 0,
            title: {
                text: 'LP-accumulated'
            }
        }, { // secondary yAxis for SP-accumulated
            opposite: true,
            min: 0,
            title: {
                text: 'SP-accumulated'
            }
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
                var day = d.getDate();  if( day < 10 ) day = '0' + day;
                var mon = d.getMonth(); if( mon < 10 ) mon = '0' + mon;
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
        plotOptions: {
            column: {
                stacking: 'normal' 
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
  });
});
