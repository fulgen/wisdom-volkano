<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Edit group | wisdom-volkano</title>

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

          
<?php echo form_open(uri_string(), "class='form-horizontal'");?>

      <h2>Edit group</h2>

      <div class="form-group">
          <label for="group_name" class="col-xs-2 control-label">Group name</label>
          <div class="col-xs-10">
          <?php echo form_input($group_name, '', 'class="form-control" placeholder="enter Group name"');?></div>
      </div>
      <div class="form-group">
          <label for="group_description" class="col-xs-2 control-label">Group description</label>
          <div class="col-xs-10">
          <?php echo form_input($group_description, '', 'class="form-control" placeholder="enter Group description"');?></div>
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

</body>
</html>
