<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Detrended timeseries list | wisdom-volkano</title>

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

            <h2>List of detrended time series</h2>
              <table class="table table-stripped table-hover">
                <tr>
                  <th>type</th>
                  <th>name</th>
                  <th>MSBAS lon/lat</th>
                  <th>GNSS axis</th>
                  <th>action</th>
                </tr>
                <?php
                    if( empty( $ts_list ) )
                    {
                      echo "<tr><td colspan='5'>No detrended found.</td></tr>\n";
                    }
                    else { foreach( $ts_list as $ts ):?>
                  <tr>
                          <td><?php echo htmlspecialchars($ts->ts_type,ENT_QUOTES,'UTF-8');?></td>
                          <td><?php echo htmlspecialchars($ts->ts_name,ENT_QUOTES,'UTF-8');?></td>
                          <td><?php if( $ts->lon != 0 and $ts->lat != 0 ) 
                                      echo "[" . $ts->lon . "," . $ts->lat . "]";?></td>
                          <td><?php echo htmlspecialchars($ts->gnss_sub,ENT_QUOTES,'UTF-8');?></td>
                    <td>
                    <button class="btn btn-default" data-href="<?php echo site_url(); ?>/detrend/delete/<?php echo $ts->id; ?>" data-toggle="modal" data-target="#confirm-delete">delete</button>
                    </td>
                  </tr>
                <?php endforeach; }?>
              </table> 
               
        </div> <!-- col main -->
      </div> <!-- row main -->
</div> <!-- container -->

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Confirm detrend delete
            </div>
            <div class="modal-body">
                <p>The detrended timeseries will be removed from wisdom-volkano. The default point (not detrended) will be shown again. Please note that this action cannot be undone. </p>
                <p>In case of deleting a detrended axis GNSS timeseries for which other axis still exist, this delete will take a few seconds.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?php echo base_url('assets/js/jquery-2.2.2.min.js');?>"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo base_url('assets/js/bootstrap.min.js');?>"></script>

    <script type="text/javascript">
      $('#confirm-delete').on('show.bs.modal', function(e) {
        $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
      });
    </script>  
</body>
</html>
