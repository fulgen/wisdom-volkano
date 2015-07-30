<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>User list | wisdom-volkano</title>

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

            <h2>User list</h2>
              <table class="table table-stripped table-hover">
                <tr>
                  <th>first name</th>
                  <th>last name</th>
                  <th>email</th>
                  <th>groups</th>
                  <th>status</th>
                  <th>action</th>
                </tr>
                <?php foreach ($users as $user):?>
                  <tr>
                          <td><?php echo htmlspecialchars($user->first_name,ENT_QUOTES,'UTF-8');?></td>
                          <td><?php echo htmlspecialchars($user->last_name,ENT_QUOTES,'UTF-8');?></td>
                          <td><?php echo htmlspecialchars($user->email,ENT_QUOTES,'UTF-8');?></td>
                    <td>
                      <?php foreach ($user->groups as $group):?>
                        <?php echo anchor("auth/edit_group/".$group->id, htmlspecialchars($group->name,ENT_QUOTES,'UTF-8')) ;?><br />
                              <?php endforeach?>
                    </td>
                    <td><?php echo ($user->active) ? anchor("auth/deactivate/".$user->id, 'activated' ) : anchor("auth/activate/". $user->id, 'disactivated' );?></td>
                    <td><a href="<?php echo site_url(); ?>/auth/edit_user/<?php echo $user->id; ?>" class="btn btn-default" role="button">edit user</a></td>
                  </tr>
                <?php endforeach;?>
              </table>
              <p><a href="auth/create_user/" role="button" class="btn btn-large btn-primary">Create user</a></p>
 
              
        </div> <!-- col main -->
      </div> <!-- row main -->
</div> <!-- container -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?php echo base_url('assets/js/jquery-2.1.4.min.js');?>"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo base_url('assets/js/bootstrap.min.js');?>"></script>

</body>
</html>
