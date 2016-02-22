<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>wisdom-volkano Login</title>

  <meta charset="utf-8" /> 
  <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet" />
  <link href="<?php echo base_url('assets/css/login.css'); ?>" rel="stylesheet" />


</head>
<body>

<div class="container">

<h1>wisdom-volkano</h1>
<p>Web Interface for Sharing Data On Monitoring Volkano<br/>(swahili for volcano, from portuguese volcão)</p>


<?php echo form_open("auth/login",'class="form-login"'); ?><br/>
    <h2>Please log in</h2>
    <div id="infoMessage"><?php echo $message;?></div>

    <div class="form-group">
      <label for="identity" class="sr-only control-label">Identity</label>
      <?php echo form_input( $identity, '', "class='form-control' placeholder='Identify' required autofocus" );?>
    </div>

    <div class="form-group">
      <label for="password" class="sr-only control-label">Password</label>
      <?php echo form_input( $password, '', 'class="form-control" placeholder="Password" required ' );?>
    </div>
<!--
    <div class="form-group">
      <?php echo form_checkbox( 'remember', '1', FALSE, 'id="remember"' );?>
      <label for="remember" class="control-label">Remember me</label>
    </div>
-->
    <div class="form-group">
      <button class="btn btn-lg btn-primary btn-block" type="submit">Log in</button>
    </div>

  <?php echo form_close(); ?>
  
<!-- <p><a href="forgot_password">Did you forget your password?</a></p> -->

</div>


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?php echo base_url('assets/js/jquery-2.2.0.min.js');?>"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo base_url('assets/js/bootstrap.min.js');?>"></script>

</body>
</html>
