<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Edit layer | wisdom-volkano</title>

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

<?php echo form_open("layer/edit_layer/" . $current_layer, "class='form-horizontal'");?>

      <h2>Edit layer <small><?php echo $current_layer; ?></small></h2>

      <div class="form-group">
          <label for="layer_name" class="col-xs-2 control-label">Layer workspace and name</label>
          <div class="col-xs-10">
          <?php 
            $option   = array();
            foreach( $layers as $op ) $option[ $op ] = $op; 
            $option[ $current_layer ] = $current_layer;
            echo form_dropdown('layer_name', $option, $current_layer, 'class="form-control" id="layer_name" '); 
          ?></div>
      </div>
      <div class="form-group">
          <label for="layer_type" class="col-xs-2 control-label">Layer type</label>
          <div class="col-xs-10">
          <?php 
            $options  = array();
            $ar_types = explode( ',', $layer_types );
            foreach( $ar_types as $op ) $options[ $op ] = $op; 
            echo form_dropdown('layer_type', $options, $current_type, 'class="form-control" id="layer_type" '); 
           ?></div>
      </div>
      <div class="form-group">
          <label for="institution" class="col-xs-2 control-label">Layer description</label>
          <div class="col-xs-10">
          <textarea class="form-control" id="layer_description" name="layer_description"><?php echo $current_desc; ?></textarea></div>
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
              foreach( $current_users as $cu ) 
                if( $user->email == $cu->user_email ) $checked = TRUE;
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
    <script src="<?php echo base_url('assets/js/jquery-2.1.4.min.js');?>"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo base_url('assets/js/bootstrap.min.js');?>"></script>

</body>
</html>
