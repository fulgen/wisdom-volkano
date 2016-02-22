<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>MSBAS favorite list | wisdom-volkano</title>

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
          <div class="map col-xs-12" id="infoMessage">
            <?php if( isset( $_SESSION[ 'message' ] ) )
                  {
                    echo $_SESSION[ 'message' ];
                    $_SESSION[ 'message' ] = "";
                  }
            ?>
          </div>
      </div>
      <div class="row Main">
          <div class="menu col-xs-12">

            <h2>List of favorite MSBAS time series points</h2>
              <table class="table table-stripped table-hover">
                <tr>
                  <th>MSBAS ts</th>
                  <th>longitude</th>
                  <th>latitude</th>
                  <th>description</th>
                  <th>action</th>
                </tr>
                <?php
                    if( empty( $favs ) )
                    {
                      echo "<tr><td colspan='5'>No favorites found.</td></tr>\n";
                    }
                    else foreach( $favs as $fav ):?>
                  <tr>
                          <td><?php echo htmlspecialchars($fav->ts_name,ENT_QUOTES,'UTF-8');?></td>
                          <td><?php echo $fav->lon;?></td>
                          <td><?php echo $fav->lat;?></td>
                          <td><?php echo htmlspecialchars($fav->description,ENT_QUOTES,'UTF-8');?></td>
                    <td>
                    <a href="<?php echo site_url(); ?>/favorite/edit_fav/<?php echo $fav->id; ?>" class="btn btn-default" role="button">edit</a>
                    <button class="btn btn-default" data-href="<?php echo site_url(); ?>/favorite/del_fav/<?php echo $fav->id; ?>" data-toggle="modal" data-target="#confirm-delete">delete</button>
                    <a href="<?php echo site_url(); ?>/favorite/load_fav/<?php echo $fav->id; ?>" class="btn btn-default" role="button">load into current session</a>
                    </td>
                  </tr>
                <?php endforeach;?>
              </table>
              <p>
              <a href="<?php echo site_url(); ?>/favorite/create_fav/" role="button" class="btn btn-large btn-primary">Create favorite point</a>
              <a href="<?php echo site_url(); ?>/favorite/load_all/" role="button" class="btn btn-large btn-primary">Load all points into current session</a>              
              </p>
               
        </div> <!-- col main -->
      </div> <!-- row main -->
</div> <!-- container -->

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Confirm favorite delete
            </div>
            <div class="modal-body">
                The favorite will be removed from wisdom-volkano. Please note that this action cannot be undone. 
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
