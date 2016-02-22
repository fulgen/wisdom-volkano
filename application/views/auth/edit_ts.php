<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Edit time series | wisdom-volkano</title>

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

<?php echo form_open("timeseries/edit_ts/" . $current_ts, "class='form-horizontal'");?>

      <h2>Edit time series <small><?php echo $current_ts; ?></small></h2>

      <div class="form-group">
          <label for="ts_type" class="col-xs-2 control-label">Type</label>
          <div class="col-xs-10">
          <?php 
            $options  = array();
            $ar_types = explode( ',', $ts_types );
            foreach( $ar_types as $op ) $options[ $op ] = $op; 
            echo form_dropdown('ts_type', $options, 'msbas', 'class="form-control" id="ts_type" '); 
           ?></div>
      </div>
      <div class="form-group">
          <label for="ts_name" class="col-xs-2 control-label">Time series name</label>
          <div class="col-xs-10">
          <?php 
            $option   = array();
            $option[ $current_ts ] = $current_ts;
            foreach( $tss as $op ) $option[ $op ] = $op; 
            echo form_dropdown('ts_name', $option, '', 'class="form-control" id="ts_name" '); 
          ?></div>
      </div>
      <div class="form-group">
          <label for="ts_description" class="col-xs-2 control-label">Description</label>
          <div class="col-xs-10">
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
    <div class="col-xs-offset-2 col-xs-10">      
      <button type="submit" class="btn btn-default">Submit</button>
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
           $("#ts_type").change(function(){  
           $.ajax({  
              url:"<?php echo base_url();?>index.php/timeseries/load_folder/" + $(this).val(),  
              // data: {id: $(this).val()},  
              type: "POST",  
              success:function(data){ 
                $("#ts_name").html(data);  
              }  
          });  
          });  
      });       
    </script>
</body>
</html>
