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
      <div class="row Info">
          <div class="map col-xs-12" id="infoMessage"><?php echo $message;?></div>
      </div>
      <div class="row Main">
          <div class="menu col-xs-12">
            
<?php echo form_open(uri_string(), "class='form-horizontal'");?>

      <h2>Edit user</h2>

      <div class="form-group">
          <label for="first_name" class="col-xs-2 control-label">First name</label>
          <div class="col-xs-10">
          <?php echo form_input($first_name, '', 'class="form-control" placeholder="enter First name"');?></div>
      </div>
      <div class="form-group">
          <label for="last_name" class="col-xs-2 control-label">Last name</label>
          <div class="col-xs-10">
          <?php echo form_input($last_name, '', 'class="form-control" placeholder="enter Last name"');?></div>
      </div>
      <div class="form-group">
          <label for="company" class="col-xs-2 control-label">Institution</label>
          <div class="col-xs-10">
          <?php echo form_input($company, '', 'class="form-control" placeholder="enter Institution"');?></div>
      </div>
      <div class="form-group">
          <label for="phone" class="col-xs-2 control-label">Phone</label>
          <div class="col-xs-10">
          <?php echo form_input($phone, '', 'class="form-control" placeholder="enter Phone"');?></div>
      </div>
      <div class="form-group">
          <label for="password" class="col-xs-2 control-label">Password</label>
          <div class="col-xs-10">
          <?php echo form_input($password, '', 'class="form-control" placeholder="enter Password"');?></div>
      </div>
      <div class="form-group">
          <label for="password_confirm" class="col-xs-2 control-label">Password confirm</label>
          <div class="col-xs-10">
          <?php echo form_input($password_confirm, '', 'class="form-control" placeholder="confirm Password"');?></div>
      </div>

      <?php if ($this->ion_auth->is_admin()): ?>

          <h3>Groups this user is in</h3>
          <div class="form-group">
          <?php foreach ($groups as $group):?>
              <div class="col-xs-offset-2 col-xs-10">
              <?php
                  $gID=$group['id'];
                  $checked = null;
                  $item = null;
                  foreach($currentGroups as $grp) {
                      if ($gID == $grp->id) {
                          $checked= ' checked="checked"';
                      break;
                      }
                  }
              ?>
              <input type="checkbox" name="groups[]" value="<?php echo $group['id'];?>"<?php echo $checked;?>>
              <?php echo htmlspecialchars($group['name'],ENT_QUOTES,'UTF-8');?>
              </div>
          <?php endforeach?>
          </div>

      <?php endif ?>

      <?php echo form_hidden('id', $user->id);?>
      <?php echo form_hidden($csrf); ?>

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
    <script src="<?php echo base_url('assets/js/jquery-2.2.2.min.js');?>"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo base_url('assets/js/bootstrap.min.js');?>"></script>

</body>
</html>
