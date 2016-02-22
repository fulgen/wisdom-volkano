<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Edit user | wisdom-volkano</title>

  <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet">


</head>
<body>

<div class="container">
  
      <div class="row Navigation">
          <div class="menu col-xs-12">
            <?php echo menu('admin'); ?>
          </div>
      </div>
      <div class="row Main">
          <div class="menu col-xs-12">
            
          
<?php echo form_open(uri_string(), "class='form-horizontal'");?>

      <h2>Deactivate user <small><?php echo $user->username; ?></small></h2>

<?php echo form_open("auth/deactivate/".$user->id);?>

      <div class="form-group">
          <label for="confirm" class="col-xs-2 control-label">Confirm</label>
          <div class="col-xs-10">
            <input type="radio" name="confirm" value="yes" checked="checked" /> Yes &nbsp; 
            <input type="radio" name="confirm" value="no" /> No
          </div>
      </div>


  <?php echo form_hidden($csrf); ?>
  <?php echo form_hidden(array('id'=>$user->id)); ?>


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

</body>
</html>
