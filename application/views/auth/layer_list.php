<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Layer list | wisdom-volkano</title>

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

            <h2>List of layers</h2>
              <table class="table table-stripped table-hover">
                <tr>
                  <th>layer id</th>
                  <th>creator</th>
                  <th>layer name</th>
                  <th>layer type</th>
                  <th>grants</th>
                  <th>action</th>
                </tr>
                <?php
                    if( empty( $layers ) )
                    {
                      echo "<tr><td colspan='7'>No layers found.</td></tr>\n";
                    }
                    else foreach( $layers as $layer ):?>
                  <tr>
                          <td><?php echo htmlspecialchars($layer->layer_id,ENT_QUOTES,'UTF-8');?></td>
                          <td><?php echo htmlspecialchars($layer->creator,ENT_QUOTES,'UTF-8');?></td>
                          <td><?php echo htmlspecialchars($layer->layer_name_ws,ENT_QUOTES,'UTF-8');?></td>
                          <td><?php echo htmlspecialchars($layer->layer_type,ENT_QUOTES,'UTF-8');?></td>
                    <td>
                      <?php foreach( $layer->users as $user):?>
                        <?php echo anchor("auth/edit_user/".$user->user_email, htmlspecialchars($user->user_email,ENT_QUOTES,'UTF-8')) ;?><br />
                      <?php endforeach?>
                    </td>
                    <td>
                    <a href="<?php echo site_url(); ?>/layer/edit_layer/<?php echo $layer->layer_name_ws; ?>" class="btn btn-default" role="button">edit layer</a>
                    <button class="btn btn-default" data-href="<?php echo site_url(); ?>/layer/del_layer/<?php echo $layer->layer_name_ws; ?>" data-toggle="modal" data-target="#confirm-delete">delete layer </button>
                    </td>
                  </tr>
                <?php endforeach;?>
              </table>
              <p><a href="<?php echo site_url(); ?>/layer/create_layer/" role="button" class="btn btn-large btn-primary">Create layer</a></p>
               
        </div> <!-- col main -->
      </div> <!-- row main -->
</div> <!-- container -->

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Confirm layer delete
            </div>
            <div class="modal-body">
                The layer will be removed from wisdom-volkano (not from Geoserver). Please note that this action will remove any grants to users, and cannot be undone. 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?php echo base_url('assets/js/jquery-2.2.0.min.js');?>"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo base_url('assets/js/bootstrap.min.js');?>"></script>

    <script type="text/javascript">
      $('#confirm-delete').on('show.bs.modal', function(e) {
        $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
      });
    </script>  
</body>
</html>
