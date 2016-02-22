  var dataH = []; var z = 0;
  var ar = [];

  ar[ Date.UTC(2009, 5,  15) ] = "Some event";
  ar[ Date.UTC(2015, 8,  7)  ] = "Some later event";
  
  var baseY = -2.5; // base Y-axis msbas value for the labels, can be negative
  for( var i in ar )
  { dataH[ z++ ] = [ parseInt( i ), baseY ]; }
