<?php

// function getRiderNameById($id)
// {
//   global $con;
//   $sql = "SELECT * from users WHERE id=".$id;
// // echo $sql; die;
//   $query =mysqli_query($con, $sql);
//   $result = mysqli_fetch_assoc($query);
//   return $result['Name'];
// }
?>
<div class="panel panel-default">
  <div class="panel-heading"><?php echo getLange('riderdata'); ?>
  </div>
    <div class="panel-body" id="same_form_layout" style="padding: 11px;">
      <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
        <div class="row">
          <div class="col-sm-12 table-responsive">

            <table id="ridder_datatable" cellpadding="0" cellspacing="0" border="0" class="table table-striped orders_tbl  table-bordered no-footer dtr-inline collapsed" role="grid" aria-describedby="basic-datatable_info">
                <div class="fake_loader" id="image" style="text-align: center;">
                   <img src="images/fake-loader-img.gif" alt="logo" style="width:130px;"> 
                </div>
            <thead>
                <tr role="row">
                  <th class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 35px;"><?php echo getLange('srno'); ?></th>
                   <th class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php echo getLange('ridername'); ?> </th>
                   <th class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php echo getLange('walletbalance'); ?></th>
                   <th class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php echo getLange('action'); ?></th>
                </tr>
            </thead>
            <tfoot>
              <tr>
                  <td colspan="2" style="background-color: #DEDEDE;"> <?php echo getLange('total'); ?></td>
                  <td colspan="2" class="walletbalance" style="background-color: #b6dde8;"></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
