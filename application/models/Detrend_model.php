<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Detrend Model 
 *
 * @package		CodeIgniter
 * @subpackage	Models
 * @version	  1.0
 * @author		Fulgencio Sanmartín
 * @link		email@fulgenciosanmartin.com
*/
class Detrend_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
  /**
   * Check if a ts detrended already exists in db for the station+sub (gnss) or the point (msbas)
   *
   * @access	public
   * @param   type, ts, sub, lat, lon
   * @return	boolean
  */
  function detrend_exists( $type, $ts, $sub, $lat, $lon )
  {
    $debug = false;
    $sql = "SELECT * FROM ts_detrend WHERE ts_type = ? AND ts_name = ?";
    if( $type == 'msbas' )
    {
      // cast and round to compare reals: http://www.peterbe.com/plog/comparing-real-values
      $sql .= " AND round(lat::numeric, 3) = " . $lat 
           .  " AND round(lon::numeric, 3) = " . $lon ;
      $array = array( $type, $ts ); //, $lat, $lon );
    }
    else if( $type == 'gnss' )
    {
      $sql .= " AND gnss_sub = ? ";
      $array = array( $type, $ts, $sub );
    }
    else
    {
      return false;
    }
    if( $debug ) log_message( 'error', "$sql <br>" );
		$query = $this->db->query( $sql, $array );
    if( $debug ) log_message( 'error', "1- detrend exists?? type: $type ts: $ts sub: $sub lat: $lat lon: $lon <br><pre>" );
    if( $debug ) log_message( 'error', $query->result() );
    if( $query->result() )
    {
      if( $debug ) log_message( 'error', '</pre><br>2- detrend exists in db' );
      $in_db = true;
    }
    else
    {
      if( $debug ) log_message( 'error', '<br>3- detrend does not exist in db' );
      $in_db = false;
    }
    //if( $debug ) exit();
    return $in_db;
	}
  /* end function detrend_exists  */
  
  /**
   * Check if a ts detrended file already exists for the station (gnss) or the point (msbas)
   *
   * @access	public
   * @param   type, ts, sub, lat, lon
   * @return	boolean
  */
  function detrend_file_exists( $type, $ts, $sub, $lat, $lon )
  {  
    $filepath = $this->get_detrend_filepath( $type, $ts, $sub, $lat, $lon, 'detrend' );
    //echo "<br>6. filepath: $filepath ";
    //exit();
    if( file_exists( trim( $filepath ) ) )
      return true;
    else
      return false;
	}
  /* end function detrend_file_exists  */
  

  /**
   * Constructs file name depending on type and params
   *
   * @access	public
   * @param   type, ts, sub, lat, lon
   * @return	string with real file path in disk
  */
  function get_detrend_filepath( $type, $ts, $sub, $lat, $lon, $orig_or_detrend = 'detrend', $disk_or_uri = 'disk' )
  {
    $filepath = '';
    $this->load->model( 'Timeseries_model', 'ts_model' );
    $row = $this->ts_model->get_timeseries( $ts );

    if( $type == 'msbas' )
    {    
      $this->load->model( 'Ts_folder_model', 'ts_folder_model' );
      $this->load->helper( 'coord' );
      $xcol = coord2pix( $lon, $row->ts_coord_lon_left, $row->ts_coord_lon_inc );
      $yrow = coord2pix( $lat, $row->ts_coord_lat_top,  $row->ts_coord_lat_inc );
      $filepath = $this->ts_folder_model->get_ts_file_name( $row, $disk_or_uri, $xcol, $yrow );

      $slash = ( $disk_or_uri == 'disk' ) ? $this->config->item('bar_slash') : $this->config->item('uri_slash');
      $pos_file = strrpos( $filepath, $slash );
      $bar_size = strlen( $slash );
      $path = substr( $filepath, 0, $pos_file + $bar_size ); // to include the bar
      $filename = substr( $filepath, $pos_file + 1 ); // not to include the bar
    }
    else if( $type == 'gnss' )
    {
      $filename = trim( $row->ts_file );
      if( $disk_or_uri == 'disk' )
        $path     = $this->config->item( 'folder_gnss' );
      else // uri
        $path     = $this->config->item( 'uri_gnss' );
        
      $filepath = $path . $filename;
    }
    else return null;
    
    if( $disk_or_uri == 'disk' )
      $new_filename = $path . $this->config->item( 'folder_detrend' ) . $filename;
    else // uri
      $new_filename = $path . $this->config->item( 'uri_detrend' ) . $filename;    
    
    if( $orig_or_detrend == 'detrend' )
      return $new_filename;
    else
      return $filepath;
      
  }
  /* end function get_detrend_filepath  */

  /** 
    * create_file_detrend  Function to create a dentrended file timeseries, 
    * 
    * @param:  the file name with path, the type and the arrays to write, plus the ts type if gnss
    * @return: void
    */
  public function create_file_ts( $file, $type, $x, $y, $gnss_sub )  
  {
    $debug = false;
    if( $type == "msbas" )
    {
      if( file_exists( $file ) )
      {
        log_message( 'error', "app/model/detrend/E-086 Error Trying to create an msbas file $file that already exists." );
        return false;
      }
      $nf = fopen( $file, "w" );
      if( !$nf )
      {
        log_message( 'error', 'app/model/detrend/E-081 Error Cannot open the file to save the detrend.' );
        return false;
      }
      else 
      {
        for( $i = 0; $i < count( $x ); $i ++ )
        {
          $line = $x[ $i ] . "\t" . $y[ $i ];
          if( ( $i + 1 ) < count( $x ) ) $line .= "\n"; // the last should not be an empty line
          if( $debug ) log_message( 'error', "lin $i (total " . count($x) . "): $line" );
          fwrite( $nf, $line );
        }
        fclose( $nf );
        
        // To be able to remove it later
        chmod( $nf, 0777 );
      }
    }
    else // $type == "gnss"
    {
      $file_orig = false;
      if( ! file_exists( $file ) )
        $file_orig = str_replace( $this->config->item('folder_detrend'), "", $file );
      
      switch( $gnss_sub )
      {
        case 'EW': $idx = 1; break; // idx = 0 is tickdate
        case 'NS': $idx = 2; break;
        case 'UP': $idx = 3; break;
      }
      $nf = fopen( $file . ".tmp", "w" );
      if( !$nf ) 
      {
        log_message( 'error', "app/model/detrend/E-087 Error Cannot open a $file.tmp to save the detrend." );
        return false;
      }
      else
      {
        if( $file_orig )
          $of = fopen( $file_orig, "r" );
        else
          $of = fopen( $file, "r" );
        if( !$of ) 
        {
          log_message( 'error', "app/model/detrend/E-088 Error Cannot open the file $file_orig to read the timeseries for the detrend." );
          return false;
        }
        
        $i = 0;
        while( ( ! feof( $of ) ) && ( $i < count( $x ) ) )
        {
          $line = fgets( $of );
          $temp = explode( "\t", $line ); // this separates date at 0 from the rest
          $ar_v = explode( " ", $temp[1] ); // this separates the values
          if( $idx == 1 ) $newl = $x[$i] . "\t" . $y[$i]   . " " . $ar_v[1] . " " . $ar_v[2];
          if( $idx == 2 ) $newl = $x[$i] . "\t" . $ar_v[0] . " " . $y[$i]   . " " . $ar_v[2];
          if( $idx == 3 ) $newl = $x[$i] . "\t" . $ar_v[0] . " " . $ar_v[1] . " " . $y[$i];
          if( $debug ) log_message( 'error', "lin $i: $line - datetick: $temp[0] - valEW: $ar_v[0] - valNS: $ar_v[1] - valUP: $ar_v[2] - idx: $idx - newl: $newl<br><br>" );
          if( strlen( trim( $newl ) ) > 0 )
            fputs( $nf, str_replace( "\n", "", $newl ) . "\n" );
          $i ++;
        }
        fclose( $nf );
        fclose( $of );
        // To be able to remove it later
        chmod( $nf, 0777 );
        
        
        if( ! $file_orig ) // detrend file already existed, swap
        {
          if( $debug ) rename( $file, $file . ".old" );
          unlink( $file );
        }
        
        rename( $file . ".tmp", $file );
      }
    }
  }
  /* end of function create_file_detrend */
  
  
  /**
   * Creates a detrended ts in the db
   *
   * @access	public
   * @param   the ts name, type and either the sub (gnss) or lat,lon (msbas)
   * @return	nothing
  */
	function set_detrend_new( $ts, $type = "msbas", $sub, $lat, $lon )
	{
    $sql = "INSERT INTO ts_detrend ( ts_type, ts_name, gnss_sub, lat, lon ) " 
         . " VALUES ( ?, ?, ?, ?, ? ) ";
    $this->db->query( $sql, array( $type, $ts, $sub, $lat, $lon ) );
    
    if( $this->db->affected_rows() == 0 )
    {
      log_message( 'error', "app/model/detrend/E-078 Error Cannot save the detrend in db: ts_name $ts_name, type $type, sub $sub, lat $lat, lon $lon." );
    }
    return;
  }
  /* end of function set_detrend_new */


  /**
   * Delete a detrended timeseries from the db, and ...
   *  msbas file is unique to the point and therefore to be removed
   *  gnss file is common to three axis (EW,NS,UP): 
   *      if there is still another axis in db: remove column, copy from original
   *      if not, remove the file
   *
   * @access	public
   * @param   the detrend_id
   * @return	nothing
  */
	function delete( $id )
	{
    $debug = false;
    $this->db->where( 'id', $id );
    $query = $this->db->get( 'ts_detrend' );
    if( ! $query->result() )
    {
      log_message( 'error', "app/model/detrend/E-085 Error Cannot find the $id in detrend table to be deleted." );
      return;
    }  
    else
    {      
      $row = $query->row();
      $filepath = $this->get_detrend_filepath( $row->ts_type, $row->ts_name, $row->gnss_sub, $row->lat, $row->lon, 'detrend', 'disk' );

      // remove the row
      $this->db->where( 'id', $id );
      $this->db->delete( 'ts_detrend' );      

      $nrows = $this->db->affected_rows();
      if( $nrows == 0 )
      {
        log_message( 'error', 'app/model/detrend/E-083 Error Cannot remove detrended timeseries from the db: ' . $id . '.' );
      }
      else
      {
        if( $row->ts_type == 'gnss' )
        {
          switch( $row->gnss_sub )
          {
            case "EW": $search1 = "NS"; $search2 = "UP"; $idx = 1; break;
            case "NS": $search1 = "EW"; $search2 = "UP"; $idx = 2; break;
            case "UP": $search1 = "NS"; $search2 = "EW"; $idx = 3; break;
            default: $search1 = $search2 = ""; break;
          }
          $exists_another_gnss = false;
          if( $this->detrend_exists( "gnss", $row->ts_name, $search1, 0, 0 ) 
           or $this->detrend_exists( "gnss", $row->ts_name, $search2, 0, 0 ) )
            $exists_another_gnss = true;
        }
      
        if(   $row->ts_type == 'msbas' 
         or ( $row->ts_type == 'gnss' and ! $exists_another_gnss ) )
        { 
          // remove the msbas file
          if( ! unlink( $filepath ) )
            log_message( 'error', 'app/model/detrend/E-084 Error Cannot remove file of detrended timeseries: ' . $id . '.' );
        }
        else // there is another gnss, must be overwritten with the original ts
        {
          // 1. construct name and open r the detrended file df
          $detrended_file = $this->get_detrend_filepath( 'gnss', $row->ts_name, '', 0, 0, 'detrend', 'disk' ); 
          $df = fopen( $detrended_file, "r" );
          if( ! $df ) log_message( 'error', "app/model/detrend/E-089 Error Cannot open detrended file $detrended_file" );
          
          // 2. construct name and open r the original file of
          $original_file = $this->get_detrend_filepath( 'gnss', $row->ts_name, '', 0, 0, 'orig', 'disk' ); 
          $of = fopen( $original_file, "r" );
          if( ! $of ) log_message( 'error', "app/model/detrend/E-090 Error Cannot open original file $original_file" );

          // 3. find out which column is to be removed idx
          // $idx - done above 
          
          // 4. construct name and open w the detrended file.tmp nf
          $new_file = $detrended_file . ".tmp"; 
          $nf = fopen( $new_file, "w" );
          if( ! $of ) log_message( 'error', "app/model/detrend/E-091 Error Cannot open new file $new_file to write the detrend. " );
          
          // 5. loop until eof original file
          $i = 0;
          while( ( ! feof( $of ) ) and ( ! feof( $df ) ) )
          {
            //    5.1. read line from df and of
            $of_line = fgets( $of );
            $df_line = fgets( $df );

            //    5.2. decompose in items df and of lines
            $of_temp = explode( "\t", $of_line ); // this separates date at 0 from the rest
            $df_temp = explode( "\t", $df_line ); // this separates date at 0 from the rest
            if( array_key_exists( 1, $of_temp ) and array_key_exists( 1, $df_temp ) )
            {
              $orval = explode( " ",  $of_temp[1] ); // this separates the values
              $dtval = explode( " ",  $df_temp[1] ); // this separates the values
              
              //    5.3. construct newline with of[idx] and df[others]
              if( $idx == 1 ) 
                $newl = $df_temp[0] . "\t" . $orval[0] . " " . $dtval[1] . " " . $dtval[2];
              if( $idx == 2 ) 
                $newl = $df_temp[0] . "\t" . $dtval[0] . " " . $orval[1] . " " . $dtval[2];
              if( $idx == 3 ) 
                $newl = $df_temp[0] . "\t" . $dtval[0] . " " . $dtval[1] . " " . $orval[2];
              if( $debug ) log_message( 'error', "newl $i: $newl" );
              
              //    5.4. write newline 
              fputs( $nf, str_replace( "\n", "", $newl ) . "\n" );
            }
              
            $i ++;
          }
          
          // 6. close df, of and nf
          fclose( $nf ); fclose( $of ); fclose( $df );
          
          // To be able to remove it later
          chmod( $nf, 0777 );
          
          // 7. remove df
          unlink( $detrended_file );
            
          // 8. rename nf.temp to df
          rename( $new_file, $detrended_file );
        }
      }        
    }
    return;
  }
  /* end function delete */
  
 
  /**
   * Returns list of all detrended ts (granted to the user)
   *
   * @access	public
   * @param   the user authentified
   * @return	object with all detrended points whose timeseries are authorized to the user
  */
  function get_all_detrended()
  {
    $email = $this->session->userdata( 'email' );
    $sql = " select d.id, d.ts_type, d.ts_name, d.gnss_sub, d.lat, d.lon " 
         . "   from ts_detrend d, user_timeseries u " 
         . "  where d.ts_name = u.ts_name " 
         . "    and u.user_email = ? "
         . "  order by 2, 3 "; 
    $query = $this->db->query( $sql, array( $email ) );
    
    if( $query->num_rows() == 0 )
    {
        log_message( 'error', 'app/model/detrend/E-082 Error, no detrend found - none created yet for the user ' . $email . '?' );
        return null;
    }
    else 
    {
        return $query->result();
    }
  }
  /* end function get_all_detrended */
  
}

/* End of file Detrend_model.php */
/* Location: ./application/models/Detrend_model.php */