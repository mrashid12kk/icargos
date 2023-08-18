<div class="panel panel-default" style="margin-top:0;">
   <?php if (!isset($_GET['print'])) { ?>
   <div class="panel-heading">
      <b style="    padding-top: 6px;
         display: inline-block;"><?php echo getLange('shipmentreport'); ?> </b>
      <?php
         $active_id = "";
         if (isset($_GET['active_customer'])) {
             $active_id = $_GET['active_customer'];
         }
         ?>
      <div class="col-sm-2 all_customer_gapp left_right_none" style="float: right;margin-top: 0;">
         <div class="form-group all_business">
            <select class="form-control active_customer_detail js-example-basic-single"
               onchange="window.location.href='shipment_report.php?active_customer='+this.value;">
               <option value="">All Business Accounts</option>
               <?php foreach ($customers as $customer) { ?>
               <option <?php if ($customer['id'] == $active_id) {
                  echo "selected";
                  } ?> value="<?php echo $customer['id']; ?>">
                  <?php echo $customer['fname'] . (($customer['bname'] != '') ? ' (' . $customer['bname'] . ')' : ''); ?>
               </option>
               <?php } ?>
            </select>
         </div>
      </div>
   </div>
   <div class="panel-heading shipment_report order_box shipment_report_box"></div>
   <?php } ?>
   <div class="panel-body" id="same_form_layout">
      <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
         <div class="row">
            <div class="col-sm-12 table-responsive gap-none bordernone" style="padding:0;">
               <?php
                  if (!isset($_GET['print'])) {
                  ?>
               <form method="POST" action="">
                  <div class="row" style="margin:0;">
                     <div class="col-sm-2 left_right_none">
                        <div class="form-group">
                           <label><?php echo getLange('pickupcity'); ?> </label>
                           <select class="form-control origin js-example-basic-single" name="origin"
                              id="origin">
                              <option value="" <?php if ($active_origin == '') {
                                 echo "selected";
                                 } ?>>All</option>
                              <?php while ($row = mysqli_fetch_array($cities1)) { ?>
                              <option 
                                 <?php if ($active_origin == $row['city_name']) {
                                    echo "selected";
                                    } ?>>
                                 <?php echo $row['city_name']; ?>
                              </option>
                              <?php } ?>
                           </select>
                        </div>
                     </div>
                     <div class="col-sm-2 left_right_none">
                        <div class="form-group">
                           <label><?php echo getLange('destination'); ?></label>
                           <select class="form-control destination js-example-basic-single" name="destination"
                              id="destination">
                              <option value="" <?php if ($active_destination == '') {
                                 echo "selected";
                                 } ?>>All</option>
                              <?php while ($row = mysqli_fetch_array($cities2)) { ?>
                              <option <?php if ($active_destination == $row['city_name']) {
                                 echo "selected";
                                 } ?> ><?php echo $row['city_name']; ?></option>
                              <?php } ?>
                           </select>
                        </div>
                     </div>
                     <div class="col-sm-2 left_right_none">
                        <div class="form-group">
                           <label><?php echo getLange('rider'); ?></label>
                           <select class="form-control courier js-example-basic-single" name="courier"
                              id="courier">
                              <option value="" <?php if ($active_courier == '') {
                                 echo "selected";
                                 } ?>>All</option>
                              <?php while ($row = mysqli_fetch_array($drivers)) { ?>
                              <option value="<?php echo $row['id']; ?>" <?php if ($active_courier == $row['id']) {
                                 echo "selected";
                                 } ?>>
                                 <?php echo $row['Name']; ?>
                              </option>
                              <?php } ?>
                           </select>
                        </div>
                     </div>
                     <div class="col-sm-2 left_right_none">
                        <div class="form-group">
                           <label><?php echo getLange('status'); ?></label>
                           <select class="form-control status js-example-basic-single" name="status"
                              id="status">
                              <option value="" <?php if ($active_status == '') {
                                 echo "selected";
                                 } ?>>All</option>
                              <?php while ($row = mysqli_fetch_array($status_query)) { ?>
                              <option value="<?php echo $row['status']; ?>" <?php if ($active_status == $row['status']) {
                                 echo "selected";
                                 } ?>>
                                 <?php echo $row['status']; ?>
                              </option>
                              <?php } ?>
                           </select>
                        </div>
                     </div>
                     <div class="col-sm-1 left_right_none">
                        <div class="form-group">
                           <label><?php echo getLange('from'); ?></label>
                           <input type="text" value="<?php echo $from; ?>" class="form-control datetimepicker4"
                              name="from" id="from">
                        </div>
                     </div>
                     <div class="col-sm-1 left_right_none">
                        <div class="form-group">
                           <label><?php echo getLange('to'); ?></label>
                           <input type="text" value="<?php echo $to; ?>" class="form-control datetimepicker4"
                              name="to" id="to">
                        </div>
                     </div>
                     <div class="col-sm-1 sidegapp-submit left_right_none">
                        <input type="button" id="submit_shipment" name="submit"
                           class="shipment_btn btn btn-info" value="<?php echo getLange('submit'); ?>">
                     </div>
                  </div>
               </form>
               <?php } ?>
               <?php if (isset($_GET['print'])) { ?>
               <table cellpadding="0" cellspacing="0" border="0"
                  class="table table-striped table-bordered no-footer" role="grid">
               <?php } else { ?>
               <table cellpadding="0" cellspacing="0" border="0"
                  class="table shipment_reportt table-striped table-bordered no-footer orders_tbl" id="basic-datatable "
                  role="grid" aria-describedby="basic-datatable_info">
                  <?php } ?>
                  <div class="fake_loader" id="image" style="text-align: center;">
                     <img src="images/fake-loader-img.gif" alt="logo" style="width:130px;">
                  </div>
                  <thead>
                     <tr role="row">
                        <th><input type="checkbox" name="" class="main_select"></th>
                        <th><?php echo getLange('trackingno'); ?> </th>
                        <th><?php echo getLange('status'); ?> </th>
                        <th><?php echo getLange('Update Date'); ?> </th>
                        <th><?php echo getLange('servicetype'); ?> </th>
                        <th><?php echo getLange('pickupname'); ?> </th>
                        <th><?php echo getLange('pickupaddress'); ?> </th>
                        <th><?php echo getLange('deliveryname'); ?> </th>
                        <th><?php echo getLange('deliveryaddress'); ?> </th>
                        <th><?php echo getLange('deliveryphone'); ?> </th>
                        <th><?php echo getLange('weightkg'); ?></th>
                        <th><?php echo getLange('codamount'); ?> </th>
                        <th><?php echo getLange('rider'); ?> </th>
                        <th><?php echo getLange('receivedby'); ?> </th>
                        <th><?php echo getLange('signature'); ?> </th>
                        <th>Receiver CNIC</th>
                        <th>Receiver CNIC Image</th>
                        <th><?php echo getLange('action'); ?> </th>
                     </tr>
                  </thead>
                  <tfoot>
                     <tr>
                        <td colspan="8" style="background-color: #F5F5F5;"> <?php echo getLange('total'); ?>
                        </td>
                        <td class="parcelweight" style="background-color: #b6dde8;"></td>
                        <td class="codamount" style="background-color: #c2d69a;"></td>
                        <td colspan="4" style="background-color: #F5F5F5;"></td>
                     </tr>
                  </tfoot>
               </table>
               <form method="GET" id="bulk_submit" action="shipment_report_print.php; ?>" target="_blank">
                  <input type="hidden" name="order_id" id="print_data" >
                  <input type="hidden" name="print">
               </form>
            </div>
         </div>
      </div>
   </div>
</div>