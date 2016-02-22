<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  EnviHeader
*
* Version: 1.0.0
*
* Author: Fulgencio Sanmartín
*         email@fulgenciosanmartin.com
*
* Location: http://github.com/fulgen/wisdom-volkano/application/libraries/EnviHeader
*
* Created:  2015.09.02
*
* Description:  Class to read values from an Envi Header raster file.
*
* Requirements: PHP5 or above
*
*/ 
class EnviHeader {

  private $nCol, $nRow, $nBand, $dataType, $headerOffset, $byteOrder, $interleave;
  private $headerFile, $binaryFile;
  private $mapLon, $mapLat, $mapLonInc, $mapLatInc;

  /** 
   * __construct
   *
   * @param  $fileName string with file name and needed complete path. It shall have .hdr extension; 
   *                   the binary is placed in the same folder and called equal, but with .bin
   * @return void
   **/
  public function __construct( $arFile ) {
    $fileName = $arFile[ 0 ];
    $this->nCol = $this->nRow = $this->nBand = $this->dataType = $this->headerOffset = $this->byteOrder = 0;
    $this->interleave = "bsq"; 
    $this->mapLon = $this->mapLat = $this->mapLonInc = $this->mapLatInc = 0;
    $this->bin = null;
    if( ! is_file( $fileName ) ) 
    {
      $err = 'Error: the Envi file ' . $fileName . ' could not be found.';
      log_message( 'error', 'app/library/Enviheader/E-032 ' . $err );    
      show_error( $err );
      exit( -1 );
    }
    $this->headerFile = $fileName;
    if( substr( $fileName, -4 ) != '.hdr' ) 
    {
      $err = 'Error: the Envi file found has not extension .hdr.';
      log_message( 'error', 'app/library/Enviheader/E-031 ' . $err );    
      show_error( $err );
    }
    $this->binaryFile = substr( $fileName, 0, strlen( $fileName ) - 4 ) . '.nvi';
  }
  /* end of __construct */

  
  /** 
   * read_header
   *
   * @return void
   **/
    /* Example file.hdr:
                    ENVI
                    description = {
                      File Imported into ENVI.}
                    samples = 601
                    lines   = 781
                    bands   = 1
                    header offset = 0
                    file type = unknown
                    data type = 4
                    interleave = bsq
                    sensor type = Unknown
                    byte order = 1
                    map info = {Geographic Lat/Lon, 1.0000, 1.0000,  29.00 ,  -1.1 ,  0.000833333333333 ,  0.000833333333333 ,  WGS-84, units=Degrees}
                    wavelength units = Unknown     
    */
  public function read_header() {
    $no = "";
    // convert all lines to an array
    $lines = file( $this->headerFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
    foreach( $lines as $line )
    {
      $ar = explode( "=", $line );
      if( ! empty( $ar[1] ) ) // first line is ENVI alone!
      {
        $ar[ 0 ] = strtolower( trim( $ar[ 0 ] ) ); // key
        $ar[ 1 ] = strtolower( trim( $ar[ 1 ] ) ); // value
        switch( $ar[ 0 ] )
        {
          case 'lines':         $this->nRow         = intval( $ar[ 1 ] ); break;
          case 'samples':       $this->nCol         = intval( $ar[ 1 ] ); break;
          case 'bands':         $this->nBand        = intval( $ar[ 1 ] ); break;
          case 'data type':     $this->dataType     = intval( $ar[ 1 ] ); break;
          case 'interleave':    $this->interleave   = $ar[ 1 ]; break;
          case 'header offset': $this->headerOffset = intval( $ar[ 1 ] ); break;
          case 'byte order':    $this->byteOrder    = intval( $ar[ 1 ] ); break;
          case 'map info':      
            list( $no, $no, $no, $this->mapLon, $this->mapLat, $this->mapLonInc, $this->mapLatInc ) = explode( ",", $ar[ 1 ] );
            $this->mapLon = trim( $this->mapLon ) + 0.0;
            $this->mapLat = trim( $this->mapLat ) + 0.0;
            $this->mapLonInc = trim( $this->mapLonInc ) + 0.0;
            $this->mapLatInc = trim( $this->mapLatInc ) + 0.0; 
            break;
          default: break; //ignore
        }
      }
    }
    $err = false;
    if( $this->nCol <= 0 || $this->nRow <= 0 || $this->nBand <= 0 )
      $err = 'app/library/Enviheader/E-033 Error: the Envi file ' . $this->headerFile . ' has incorrect values: ' . $this->nRow . ', ' . $this->nCol . ', ' . $this->nBand;
    if( ! in_array( $this->dataType, array( 1,2,3,4,5,9,12 ) ) )
      $err = 'app/library/Enviheader/E-034 Error: the Envi file ' . $this->headerFile . ' has an incorrect datatype: ' . $this->dataType;
    if( ( ! is_numeric( $this->mapLon ) ) or
        ( ! is_numeric( $this->mapLat ) ) or    
        ( ! is_numeric( $this->mapLonInc ) ) or    
        ( ! is_numeric( $this->mapLatInc ) ) )
    {
      $err = 'app/library/Enviheader/E-039 Error: the Envi file ' . $this->headerFile . ' has incorrect coordinates (lat,lon): (' . $this->mapLat . ',' . $this->mapLon . '), or incorrect steps (incLat, incLon): (' . $this->mapLatInc . ',' . $this->mapLonInc . ').';
    }
    if( $err )
    {
      log_message( 'error', $err ); show_error( $err ); exit( -1 );
    }
    
  }
  /* end of function read_header */

  /** 
   * get_value
   *
   * @param  the variable to return its value
   * @return the value
   **/
  public function get_value( $val )
  {
    $ret = 0;
    switch( $val )
    {
      case "nCol" : $ret = $this->nCol; break;
      case "nRow" : $ret = $this->nRow;  break;
      case "nBand" : $ret = $this->nBand; break; 
      case "dataType": $ret = $this->dataType; break;
      case "headerOffset": $ret = $this->headerOffset; break;
      case "byteOrder": $ret = $this->byteOrder; break;
      case "interleave": $ret = $this->interleave; break;
      case "headerFile": $ret = $this->headerFile; break;
      case "binaryFile": $ret = $this->binaryFile; break;
      case "mapLon": $ret = $this->mapLon; break;
      case "mapLat": $ret = $this->mapLat; break;
      case "mapLonInc": $ret = $this->mapLonInc; break;
      case "mapLatInc": $ret = $this->mapLatInc; break;
      case "bin": $ret = $this->bin; break;
      default: 
        $err = 'app/library/Enviheader/E-039 Error: value not found ' . $val;
        log_message( 'error', $err );
        break;
    }
    return $ret;
  }
  /* end of function read_header */

  
  /** 
   * calc_pixel_stack, reads the whole raster stack (this->binaryFile is the first) for a coordinate and returns an array with date (filename) and value
   *
   * @param  the coordinates and the date start in the raster name (YYYYMMDD)
   * @return value
   **/
  public function calc_pixel_stack( $datestart, $xcol, $yrow )
  {
    $this->ci =& get_instance();
    $new_ts = array();
    $ultbar = strrpos( $this->binaryFile, $this->ci->config->item('bar_slash') );
    $folder = substr( $this->binaryFile, 0, $ultbar );
    // echo " escaneando el directorio... $this->binaryFile - $folder";
    $ar_map = scandir( $folder ); 
    foreach( $ar_map as $file )
    {
      if( is_file( $folder . $this->ci->config->item('bar_slash') . $file )
       && substr( $file, -3 ) == 'nvi' )
      {
        $date = substr( $file, $datestart, 8 ); // format YYYYMMDD
        $link = $folder . $this->ci->config->item('bar_slash') . $file;
        // echo " looking for date $date and file $link coord [$xcol,$yrow] ";
        $val  = $this->get_pixel( $link, $xcol, $yrow );
        $new_ts[ $date ] = $val;
      }
    }
    return $new_ts;
  }
  /* end of function calc_pixel_stack */
  
  
  /** 
   * get_pixel, reads a coordinate, converts it to pixel, reads the binary data and returns the value
   *
   * @param  the file name and the coordinates
   * @return value
   **/
  public function get_pixel( $binfile, $col, $row )  
  {
    if( !is_file( $binfile ) ) 
    { 
      $err = 'app/library/Enviheader/E-040 Error: cannot find file' . $binfile;
      log_message( 'error', $err );
    }
    else
    {
      $desplaz = $this->headerOffset 
               + $row * $this->nCol * $this->dataType 
               + $col * $this->dataType;
    
      $file = fopen( $binfile, "rb" );
      fseek( $file, $desplaz );
      $bytes = fread( $file, $this->dataType ); 
      fclose( $file );

      if( $this->is_little_endian() == "little" ) 
        $bytes = $this->byteswap( $bytes );
      
      $val = unpack( "f*", $bytes ); 
    }
    return $val[1];
  }
  /* end of function get_pixel */


  /** 
   * byteswap, used for reversing the binary data when endian is little
   *
   * @return the value reversed in bytes
   **/
  function byteswap( $val ) 
  {
    return strrev( $val );
  }  
  /* end of function byteswap */
    
  
  /** 
   * is_little_endian, used for reading the binary data
   *
   * @return void
   **/
  private function is_little_endian() 
  {
    $v = unpack('S',"\x01\x00");
    $ieee   = ( $v[1] === 1 ) ? 0 : 1;
    $endian = ( $ieee == $this->byteOrder || $this->byteOrder < 0 ) ? "big" : "little"; 
    return $endian;    
  }
  /* end of function is_little_endian */
} 

