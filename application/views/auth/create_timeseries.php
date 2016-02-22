<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Create time series | wisdom-volkano</title>

  <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet">


</head>
<body>

<div class="container">
      <div class="row Navigation">
          <div class="menu col-xs-12">
            <?php echo menu('admin'); ?>
          </div>
      </div>
      <div class="row Info">
          <div class="map col-xs-12" id="infoMessage"><?php echo $message;?></div>
      </div>
      <div class="row Main">
          <div class="menu col-xs-12">

<?php echo form_open("timeseries/create_ts", "class='form-horizontal'");?>

      <h2>Create time series</h2>

      <div class="form-group">
          <label for="ts_type" class="col-xs-3 control-label">Type</label>
          <div class="col-xs-9">
          <?php 
            $options  = array();
            $ar_types = explode( ',', $ts_types );
            foreach( $ar_types as $op ) $options[ $op ] = $op; 
            echo form_dropdown('ts_type', $options, 'msbas', 'class="form-control" id="ts_type" '); 
           ?></div>
      </div>
      <div class="form-group">
          <label for="ts_name" class="col-xs-3 control-label">Time series name</label>
          <div class="col-xs-9">
          <?php 
            echo form_input('ts_name', '', 'class="form-control" required="true" id="ts_name" '); 
          ?></div>
      </div>
      <div class="form-group">
          <label for="ts_file" class="col-xs-3 control-label">Time series file or group folder</label>
          <div class="col-xs-9">
          <?php 
            $option   = array();
            foreach( $ts as $op ) 
              $option[ $op ] = $op; 
            echo form_dropdown('ts_file', $option, '', 'class="form-control" id="ts_file" '); // folder for msbas; file for histogram
          ?></div>
      </div>      
      <div id="groupmsbas">
        <div class="form-group">
          <label for="ts_file_raster" class="col-xs-3 control-label">Raster file found</label>
          <div class="col-xs-9">
            <?php 
            echo form_input( "ts_file_raster", $ts_file_raster, 'class="form-control" id="ts_file_raster" readonly="readonly"' );
            ?>
          </div>
        </div>
        <div class="form-group">
          <label for="ts_file_raster_ini_date" class="col-xs-3 control-label">Raster filename - date starts at</label>
          <div class="col-xs-9">
            <?php 
            echo form_input('ts_file_raster_ini_date', $ts_file_raster_ini_date, 'class="form-control" id="ts_file_raster_ini_date" '); 
            ?>
          </div>
        </div>
        <div class="form-group">
          <label for="ts_file_raster_ex_date" class="col-xs-3 control-label">Example date</label>
          <div class="col-xs-9">
            <div id="ts_file_raster_ex_date"><?php echo $ts_file_raster_ex_date; ?></div>
          </div>
        </div>
            
        <div class="form-group">
          <label for="ts_coord_lat_top" class="col-xs-3 control-label">Raster coords - lat top</label>
          <div class="col-xs-9">
            <?php echo form_input('ts_coord_lat_top', $ts_coord_lat_top, 'class="form-control" id="ts_coord_lat_top" '); // -1.1 ?>
          </div>
        </div>
            
        <div class="form-group">
          <label for="ts_coord_lon_left" class="col-xs-3 control-label">Raster coords - lon left</label>
          <div class="col-xs-9">
            <?php echo form_input('ts_coord_lon_left', $ts_coord_lon_left, 'class="form-control" id="ts_coord_lon_left" '); // 29.0 ?>
          </div>
        </div>
            
        <div class="form-group">
          <label for="ts_coord_lat_inc" class="col-xs-3 control-label">Raster coords - lat increment (degrees per pixel)</label>
          <div class="col-xs-9">
            <?php echo form_input('ts_coord_lat_inc', $ts_coord_lat_inc, 'class="form-control" id="ts_coord_lat_inc" '); // 0.0008333333 ?>
          </div>
        </div>
            
        <div class="form-group">
          <label for="ts_coord_lon_inc" class="col-xs-3 control-label">Raster coords - lon increment (degrees per pixel)</label>
          <div class="col-xs-9">
            <?php echo form_input('ts_coord_lon_inc', $ts_coord_lon_inc, 'class="form-control" id="ts_coord_lon_inc" '); // 0.0008333333 ?>
          </div>
        </div>
            
        <div class="form-group">
          <label for="ts_file_raster" class="col-xs-3 control-label">Ts file found</label>
          <div class="col-xs-9">
            <?php 
            echo form_input( "ts_file_ts", $ts_file_ts, 'class="form-control" id="ts_file_ts" readonly="readonly"' );
            ?>
          </div>
        </div>
        
        <div class="form-group">
          <label for="ts_file_ts_ini_coord" class="col-xs-3 control-label">Ts file - coords (NNN_NNN) starts at</label>
          <div class="col-xs-9">
            <?php 
            echo form_input('ts_file_ts_ini_coord', $ts_file_ts_ini_coord, 'class="form-control" id="ts_file_ts_ini_coord" '); 
            ?>
          </div>
        </div>
        <div class="form-group">
          <label for="ts_file_ts_ex_coord" class="col-xs-3 control-label">Example coords</label>
          <div class="col-xs-9">
            <div id="ts_file_ts_ex_coords"><?php echo $ts_file_ts_ex_coord; ?></div>
          </div>
        </div>
        
      </div>      
      
      <div id="grouphistogram">
        <div class="form-group">
            <label for="ts_seism_station" class="col-xs-3 control-label">Seism or GNSS station</label>
            <div class="col-xs-9">
            <?php 
              echo form_input('ts_seism_station', '', 'class="form-control" id="ts_seism_station" '); 
            ?>
            (name must be the same as in KML/KMZ station file)
            </div>
        </div>  
      </div>

      <!-- common for all types -->
      <div class="form-group">
          <label for="ts_description" class="col-xs-3 control-label">Description</label>
          <div class="col-xs-9">
          <textarea class="form-control" id="ts_description" name="ts_description" placeholder="enter time series description"></textarea></div>
      </div>
      <h3>Grant to these users <small>(admin is always granted)</small></h3>
      <div class="form-group">
          <?php 
            $i = 0;
            foreach( $users as $user )
            {
              echo '<div class="col-xs-offset-2 col-xs-10">';
              $checked = ( $user->email == $this->session->userdata( 'email' ) );
              $disabled = ( ! $checked ? "" : "disabled" );
              echo form_checkbox( 'grant[]', $user->email, $checked, $disabled ) . "&nbsp;" . $user->email;
              echo "</div>\n";
              $i ++;
            }
          ?>
      </div>
      
  <div class="form-group">
    <div class="col-xs-offset-3 col-xs-9">      
      <button type="submit" class="btn btn-large btn-primary">Submit</button>
    </div>
  </div>
      
<?php echo form_close();?>

        </div> <!-- col main -->
      </div> <!-- row main -->
</div> <!-- container -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?php echo base_url('assets/js/jquery-2.2.0.min.js');?>"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo base_url('assets/js/bootstrap.min.js');?>"></script>

    <!-- jQuery UI needed for ajax-load available ts -->
    <script src="<?php echo base_url('assets/js/jquery-ui.min.js');?>"></script>
    
    <!-- ajax to load available ts -->
    <script>
      $(document).ready(function() {  
        $("#grouphistogram").hide(); // by default we show only msbas in the 1st load
        $("#ts_type").change(function(){  
           var tipo = $("#ts_type").val();
           if( tipo == 'msbas' )
           {
              $("#grouphistogram").hide();
              $("#groupmsbas").show();
           } 
           else // 'histogram'
           {
              $("#grouphistogram").show();
              $("#groupmsbas").hide();
           } 
           $.ajax({  
              url:"<?php echo base_url();?>index.php/timeseries/load_folder/" + $(this).val(),  
              // data: {id: $(this).val()},  
              type: "POST",  
              success:function(data){ 
                var obj = $.parseJSON( data );
                var str = "";
                for( var k in obj.data ) {
                  str = str + "<option value='" + obj.data[ k ] + "'>" + obj.data[ k ] + "</option>";
                }
                $("#ts_file").html(str);  
              }  
           });  
        });
        
        $("#ts_file").change(function(){  
           var tipo = $("#ts_type").val();
           if( tipo == 'msbas' ) // only in this case
           {
             $.ajax({  
                url:"<?php echo base_url();?>index.php/timeseries/load_msbas_files/" + $(this).val(),  
                // data: {id: $(this).val()},  
                type: "POST",  
                success:function(data){
                  var obj = $.parseJSON( data );
                  $("#ts_file_raster").val( obj.data.ts_file_raster );
                  $("#ts_file_raster_ini_date").val( obj.data.ts_file_raster_ini_date );  
                  $("#ts_file_raster_ex_date").html( obj.data.ts_file_raster_ex_date );
                  $("#ts_file_ts").val( obj.data.ts_file_ts );  
                  $("#ts_file_ts_ini_coord").val( obj.data.ts_file_ts_ini_coord );
                  $("#ts_coord_lat_top").val( obj.data.ts_coord_lat_top );
                  $("#ts_coord_lon_left").val( obj.data.ts_coord_lon_left );
                  $("#ts_coord_lat_inc").val( obj.data.ts_coord_lat_inc );
                  $("#ts_coord_lon_inc").val( obj.data.ts_coord_lon_inc );
                }  
             });  
           }
        });
      });       
    </script>
</body>
</html>
