<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// TBD
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Time series list | wisdom-volkano</title>

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

            <h2>List of time series</h2>
              <table class="table table-stripped table-hover">
                <tr>
                  <th>creator</th>
                  <th>name</th>
                  <th>type</th>
                  <th>file/s</th>
                  <th>coords or station</th>
                  <th>grants</th>
                  <th>action</th>
                </tr> 
                <?php
                    if( empty( $ts ) )
                    {
                      echo "<tr><td colspan='7'>No time series found.</td></tr>\n";
                    }
                    else foreach( $ts as $timeseries ):?>
                  <tr>
                          <td><?php echo htmlspecialchars($timeseries->creator,ENT_QUOTES,'UTF-8');?></td>
                          <td><?php echo htmlspecialchars($timeseries->ts_name,ENT_QUOTES,'UTF-8');?></td>
                          <td><?php echo htmlspecialchars($timeseries->ts_type,ENT_QUOTES,'UTF-8');?></td>
                          <td><?php 
                            switch( $timeseries->ts_type )
                            {
                              case "msbas":
                                $folder = trim( $timeseries->ts_file ) . $this->config->item( 'bar_slash' );
                                echo 'Ts:&nbsp;<small><code>' . htmlspecialchars( $folder . $this->config->item( 'folder_msbas_ts' ) . $timeseries->ts_file_ts, ENT_QUOTES, 'UTF-8' ) . "</code></small><br/>\n";
                                echo 'Raster:&nbsp;<small><code>' . htmlspecialchars( $folder . $this->config->item( 'folder_msbas_ras' ) . $timeseries->ts_file_raster, ENT_QUOTES, 'UTF-8' ) . "</code></small><br/>\n";
                                break;
                              case "histogram": 
                              case "gnss": 
                                echo 'Ts:&nbsp;<small><code>' . htmlspecialchars( $timeseries->ts_file, ENT_QUOTES, 'UTF-8' ) . "</code></small><br/>\n";
                                break;
                              default: 
                                echo "Error: ts type not correct"; 
                                break;
                            }
                          ?></td>
                          <td><?php 
                            switch( $timeseries->ts_type )
                            {
                              case "msbas":
                                echo 'Top left (lat,lon): (<small><code>' . $timeseries->ts_coord_lat_top . '</code></small>,<code><small>' . $timeseries->ts_coord_lon_left . "</code></small>)<br/>\n";
                                echo  'Increment degrees per pixel (lat,long): (<small><code>' . $timeseries->ts_coord_lat_inc . '</code></small>,<code><small>' . $timeseries->ts_coord_lon_inc . "</code></small>)<br/>\n";
                                break;
                              case "histogram":
                              case "gnss":
                                echo $timeseries->ts_seism_station;
                                break;
                              default: 
                                echo "Error: ts type not correct";
                                break;
                            }
                          ?></td>
                    <td>
                      <?php if( ! is_null( $timeseries->users ) ) 
                            foreach( $timeseries->users as $user):?>
                        <?php echo anchor("auth/edit_user/".$user->user_email, htmlspecialchars($user->user_email,ENT_QUOTES,'UTF-8')) ;?><br />
                      <?php endforeach?>
                    </td>
                    <td>
                    <!-- <a href="<?php echo site_url(); ?>/timeseries/edit_ts/<?php echo $timeseries->ts_id; ?>" class="btn btn-default" role="button">edit time series</a> -->
                    <button class="btn btn-default" data-href="<?php echo site_url(); ?>/timeseries/del_ts/<?php echo $timeseries->ts_id; ?>" data-toggle="modal" data-target="#confirm-delete">delete time series</button>
                    </td>
                  </tr>
                <?php endforeach;?>
              </table>
              <p><a href="<?php echo site_url(); ?>/timeseries/create_ts/" role="button" class="btn btn-large btn-primary">Create time series</a></p>
               
        </div> <!-- col main -->
      </div> <!-- row main -->
</div> <!-- container -->

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Confirm time series delete
            </div>
            <div class="modal-body">
                The time series will be removed from wisdom-volkano (not the file from the disk). Please note that this action will remove any grants to users, and cannot be undone. <br/>
                Also, if this timeseries was shown in the current session, the timeseries type will be reset.
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
