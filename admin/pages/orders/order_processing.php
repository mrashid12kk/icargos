<style type="text/css">
.zones_main {
    margin-bottom: 20px;
}

.panel-default>.panel-heading {
    color: #333 !important;
    background-color: #f5f5f5 !important;
    border-color: #ddd !important;

}

.panel-default>.panel-heading a {
    font-weight: bold !important;
}
</style>
<?php
$msg = '';
// if(isset($_POST['updateweight'])){
//      $track_no=$_POST['track_no'];
//      $weight=$_POST['weight'];
//      $delivery_charges=$_POST['delivery_charges'];
//      $pft_amount=$_POST['pft_amount'];
//      $inc_amount=$_POST['inc_amount'];
//      $query=mysqli_query($con,"update orders set weight='".$weight."',price='".$delivery_charges."',pft_amount='".$pft_amount."',inc_amount='".$inc_amount."' where track_no='".$track_no."'")or die(mysqli_error($con));
//      if(mysqli_affected_rows($con)>0){
//          $msg="<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>X</button><strong>Well done!</strong> Weight of This track_no '".$track_no."' Is Updated Successfully </div>";
//      }
//      else{
//          $msg="<div class='alert alert-danger'><button type='button' class='close' data-dismiss='alert'>X</button><strong>!</strong> Weight of This Track_no '".$track_no."' Is Not Updated Successfully </div>";
//      }
//  }
$current_branch = $_SESSION['branch_id'];
if (!isset($_SESSION['branch_id'])) {
    $current_branch = 1;
}
$branch_query = mysqli_query($con, "Select * from branches where id !=" . $current_branch);
$courier_query = mysqli_query($con, "Select * from users where type='driver'");
$customer_fetch_q = mysqli_query($con, "SELECT  cus.id as customer_id,cus.fname as business FROM orders o INNER JOIN customers cus ON (o.customer_id = cus.id) WHERE o.status='New Booked' GROUP BY cus.id ");
$status_query = mysqli_query($con, "Select * from order_status where active='1' and hide_from_listing = '0' order by sort_num");

$reasons_list = mysqli_query($con, "Select * from order_reason where active='1' ");
$courier_query = mysqli_query($con, "Select * from users where  user_role_id = 3 or user_role_id = 4  AND $check_branch  ");
$gst_query = mysqli_query($con, "SELECT value FROM config WHERE `name`='gst' ");
$total_gst = mysqli_fetch_array($gst_query);
$delivery_zone_q = mysqli_query($con, " SELECT * FROM delivery_zone WHERE 1 ");
?>
<div class="panel panel-default">

    <div class="panel-heading"><?php echo getLange('orderprocessing'); ?> </div>

    <div class="panel-body" id="same_form_layout">

        <div class="col-sm-12">
            <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
                <div class="row">
                    <div id="msg"></div>
                    <?php
                    echo $msg;
                    if (isset($_SESSION['succ_msg']) && !empty($_SESSION['succ_msg'])) {
                        $msg = $_SESSION['succ_msg'];
                        echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> ' . $msg . '</div>';
                        unset($_SESSION['succ_msg']);
                    }
                    echo $msg1;
                    if (isset($_SESSION['succ_msg_for_api']) && !empty($_SESSION['succ_msg_for_api'])) {
                        $msg1 = $_SESSION['succ_msg_for_api'];
                        echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>API Booking Status: Well done!</strong> ' . $msg1 . '</div>';
                        unset($_SESSION['succ_msg_for_api']);
                    }

                    if (isset($_SESSION['err_msg_for_api']) && !empty($_SESSION['err_msg_for_api'])) {
                        $msg1 = $_SESSION['err_msg_for_api'];
                        echo '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">X</button><strong>API Booking Status: Error !</strong> ' . $msg1 . '</div>';
                        unset($_SESSION['err_msg_for_api']);
                    }
                    ?>


                    <?php
                    if (isset($_SESSION['error_msg']) && !empty($_SESSION['error_msg'])) {
                        $msg = $_SESSION['error_msg'];
                        echo '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">X</button><strong>Error !</strong> ' . $msg . '</div>';
                        unset($_SESSION['error_msg']);
                    }
                    ?>
                    <!-- Modal -->
                    <div class="modal fade modal" id="exampleModal" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">
                                        <center><?php echo getLange('updatedate'); ?> </center>
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="POST">
                                    <input type="hidden" name="" class="total_gst"
                                        value="<?php echo isset($total_gst['value']) ? $total_gst['value'] : '0'; ?>">
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label><?php echo getLange('weight'); ?></label>
                                                <input type="text" name="weight" class="edituserweight form-control"
                                                    autocomplete="off">
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Dimensional weight</label>
                                                <input type="text" name="dimensional_weight"
                                                    class="dimensional_weight form-control" autocomplete="off">
                                            </div>
                                        </div>
                                        <input type="hidden" name="track_no" class="track_no editusertrackno" value="">
                                        <input type="hidden" name="status" class="status" value="">
                                        <input type="hidden" name="" class="total_gst"
                                            value="<?php echo isset($total_gst['value']) ? $total_gst['value'] : '0'; ?>">
                                        <div class="list hidden"></div>
                                        <div class="viewcharges hidden">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label><?php echo getLange('deliveycharges'); ?> </label>
                                                    <input type="text" name="delivery_charges"
                                                        class="total_amount delivery_charges calculate_delivery_charges form-control"
                                                        autocomplete="off">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label><?php echo getLange('specialcharges'); ?></label>
                                                    <input type="text" class="form-control special_charges"
                                                        name="special_charges" value="0" disabled>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <!-- <div class="col-sm-6">
                                                <label><?php echo getLange('extracharges'); ?> </label>
                                                <input type="number" name="extra_charges" class="form-control extra_charges" readonly="true" required="true" value="0" >
                                            </div> -->
                                                <div class="col-sm-6">
                                                    <label
                                                        class="calculation_label"><?php echo getLange('insurancepremium'); ?></label>
                                                    <input type="number" name="insured_premium"
                                                        class="form-control insurance_value" required="true" value="0"
                                                        disabled>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label> <?php echo getLange('totalcharges'); ?> </label>
                                                    <input type="text" name="total_charges" value="0" readonly="true"
                                                        class="form-control allownumericwithdecimal total_charges"
                                                        required="true">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label><?php echo getLange('fuelsurcharge'); ?>
                                                        (<?php echo $fuelsurcharge_percent; ?>%)</label>
                                                    <input type="text" name="fuel_surcharge" value="0" readonly="true"
                                                        class="form-control allownumericwithdecimal fuel_surcharge"
                                                        required="true">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label><?php echo getLange('salestax'); ?></label>
                                                    <input type="text" name="pft_amount" class="pft_amount form-control"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label><?php echo getLange('net_amount'); ?></label>
                                                    <input type="text" name="net_amount" class="net_amount form-control"
                                                        readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <a href="#"
                                            class="btn btn-primary update_new_value"><?php echo getLange('submit'); ?></a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <div class="row">

                        <div class="col-sm-6 table-responsive gap-none">
                            <?php


                            // $ch = curl_init();
                            // $fields = "account_id=11749&api_token=wqnVPz7cexehNLkO9QHABgJNqLVzo3PMigvgeEAyqii1p7n3MexUKyBUd5EH&order_id=2010125634" ;
                            // curl_setopt($ch, CURLOPT_URL,"https://forrun.co/api/v1/getOrderStatus");
                            // curl_setopt($ch, CURLOPT_POST, 1);
                            // curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                            // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

                            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                            // $result = curl_exec ($ch);
                            // curl_close($ch);

                            // echo "<pre>";
                            // print_r($result);
                            // die();

                            ?>
                            <textarea autofocus="true" class="form-control status_update_run" rows="8"
                                placeholder="Please enter order ids"><?php if (isset($_SESSION['old_orders_list']) and !empty($_SESSION['old_orders_list'])) {
                                                                                                                                                echo $_SESSION['old_orders_list'];
                                                                                                                                            } ?></textarea>

                            <div class="help-info orders-count" style="float: right;font-size: 12px;color: #999;">Orders
                                Count: 0</div>

                            <?php
                            //   print_r($_SESSION['old_orders_list']);
                            if (isset($_SESSION['old_orders_list']) and !empty($_SESSION['old_orders_list'])) {
                                unset($_SESSION['old_orders_list']);
                            }
                            ?>
                            <form method="POST" action="bulk_status_assign.php" id="bulk_status_assign"
                                style="clear: both;">

                                <div class="col-sm-4 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('orderstatus'); ?> </label>
                                        <select class="form-control status_list js-example-basic-single"
                                            name="order_status">
                                            <option selected value="">
                                                <?php echo getLange('select') . ' ' . getLange('status'); ?></option>
                                            <?php while ($row = mysqli_fetch_array($status_query)) { ?>
                                            <option data-reasonenable="<?php echo $row['reason_id']; ?>"
                                                value="<?php echo $row['status']; ?>">
                                                <?php echo getKeyWord($row['status']); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 receive hidden">
                                    <label><?php echo getLange('received'); ?> </label>
                                    <input type="text" name="received_by" class="form-control "
                                        placeholder="Received By" value="Self">
                                </div>
                                <div class="col-md-3 return_receive hidden">
                                    <label><?php echo getLange('received'); ?> </label>
                                    <input type="text" name="return_received_by" class="form-control "
                                        placeholder="Received By" value="Self">
                                </div>
                                <div class="col-md-3 branch_to hidden">
                                    <label><?php echo getLange('assignbranch'); ?> </label>
                                    <select class="form-control js-example-basic-single" name="assign_branch">
                                        <option selected disabled>
                                            <?php echo getLange('select') . ' ' . getLange('branch') ?></option>
                                        <?php while ($row = mysqli_fetch_array($branch_query)) { ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-sm-3 left_right_none rider_assign hidden">
                                    <div class="form-group">
                                        <label><?php echo getLange('rider') . ' ' . getLange('vender'); ?> </label>
                                        <select class="form-control courier_list js-example-basic-single"
                                            name="active_courier">
                                            <?php mysqli_data_seek($courier_query, 0);
                                            while ($row = mysqli_fetch_array($courier_query)) { ?>
                                            <option value="<?php echo $row['id']; ?>"><?php echo $row['Name']; ?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('Country'); ?> </label>
                                        <select class="form-control js-example-basic-single country country_selection"
                                            name="country">
                                            <!-- <option selected value="">
                                                <?php echo getLange('select') . ' ' . getLange('country'); ?></option> -->
                                            <?php 
                                            $country_query=mysqli_query($con,'SELECT * FROM country ORDER BY country_name ASC');
                                            while ($row = mysqli_fetch_array($country_query)) { ?>
                                            <option <?php echo isset($row['country_name']) && $row['country_name'] == 'Pakistan' ? 'selected' : ''; ?> value="<?php echo $row['country_name']; ?>">
                                                <?php echo getKeyWord($row['country_name']); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('city'); ?> </label>
                                        <select class="form-control js-example-basic-single select_dynmic_city"
                                            name="city">
                                            <option selected value="">
                                                <?php echo getLange('select') . ' ' . getLange('city'); ?></option>
                                                <?php 
                                            $country_id = 'Pakistan';
                                            $country_res = mysqli_fetch_assoc(mysqli_query($con,"SELECT id from country where country_name='$country_id'"));
                                            $countryid = isset($country_res['id']) ? $country_res['id'] : '';
                                            $city_query=mysqli_query($con,"SELECT * FROM cities where country_id=$countryid ORDER BY city_name ASC");
                                                while ($row = mysqli_fetch_array($city_query)) { ?>
                                                <option value="<?php echo $row['city_name']; ?>">
                                                    <?php echo getKeyWord($row['city_name']); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('Date Time'); ?> </label>
                                        <input type="text" class="form-control datetimepicker" name="created_on" value="<?php echo date('Y-m-d H:i:s');?>"> 
                                    </div>
                                </div>
                                <div class="col-sm-6 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('Tracking Remarks'); ?> </label>
                                        <input type="text" name="tracking_remarks" class="form-control"> 
                                    </div>
                                </div>
                                <?php if(getAPIConfig('booking_on')=='order_processing'):?>
                                    <div class="col-sm-3" style="display: block;">
                                    <div class="form-group">
                                        <label><?php echo getLange('select') . ' ' . getLange('api'); ?></label>

                                        <select class="form-control select_api js-example-basic-single" name="select_api">
                                            <option value=''>Select API</option>
                                            <?php
                                            $record = mysqli_query($con, "SELECT * FROM  third_party_apis where status=1");

                                            while ($row = mysqli_fetch_array($record)) {
                                            ?>

                                            <option value="<?php echo $row['title'] ?>"><?php echo $row['title'] ?>
                                            </option>

                                            <?php }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <?php endif;?>
                                <div class="col-sm-3 left_right_none api_service_box hidden" >
                                    <div class="form-group">
                                        <label>Select API Service</label>

                                        <select class="form-control api_service_select js-example-basic-single" name="api_service">
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 left_right_none enable_reason" style="display: none;">
                                    <div class="form-group">
                                        <label><?php echo getLange('reason'); ?></label>
                                        <input type="hidden" name="reason_enable" class="reason_enable" value="">
                                        <select class="form-control reason_list js-example-basic-single"
                                            name="reason_list">
                                            <option selected value="">
                                                Select Reason</option>
                                            <?php while ($row = mysqli_fetch_array($reasons_list)) { ?>
                                            <option value="<?php echo $row['id']; ?>"
                                                data-valuestat="<?php echo $row['reason_desc']; ?>">
                                                <?php echo getKeyWord($row['reason_desc']); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>


                                <input type="hidden" name="order_ids" id="print_data">
                                <input type="hidden" name="return_to" value="order_processing.php">
                                <div class="col-sm-2 left_right_none upate_Btn">
                                    <a href="#" class="update_status btn btn-success"
                                        style="margin-top: 7px;"><?php echo getLange('update'); ?></a>
                                </div>
                            </form>


                        </div>
                        <div class="col-md-6">
                            <div class="order_logs" style="border: 1px solid #e3e3e3; min-height: 355px; ">
                                <ul id="order_sts_lg">
                                </ul>
                            </div>
                        </div>


                    </div>

                </div>

            </div>
        </div>

    </div>

    <script type="text/javascript">
        $(document).ready(function(){
    $("select.country").change(function(){
        var selectedCountry = $(".country option:selected").val();
        $.ajax({
            type: "POST",
            url: "order_processing.php",
            data: { country : selectedCountry } 
        }).done(function(data){
            $("#response").html(data);
        });
    });
});
    document.addEventListener('DOMContentLoaded', function() {
        $(function () {
            $('.datetimepicker').datetimepicker({
                format: 'YYYY-MM-DD H:mm:s',
            });
        });

        $(document).on('change', '.status_list', function() {
            if ($(this).find(':selected').attr('data-reasonenable') == 1) {
                $('.enable_reason').show();
                $('.receive').removeClass('hidden');
            } else {
                $('.enable_reason').hide();
                $('.receive').addClass('hidden');
            }
        })
    }, false);
    document.addEventListener('DOMContentLoaded', function() {
        $(document).on('change', '.status_list', function() {
            var id = $(this).val();
            if (id == 'Delivered') {
                $('body').find('#bulk_status_assign').prop('action', 'bulk_status_assign.php');
                $('.return_receive').addClass('hidden');
                $('.receive').removeClass('hidden');
                $('.branch_to').addClass('hidden');
                $('.rider_assign').addClass('hidden');
                // $('.deliveryZoneNo').addClass('hidden');
                // $('.receive').show();
            } else if (id === 'Returned to Shipper') {
                $('body').find('#bulk_status_assign').prop('action', 'bulk_status_assign.php');
                $('.return_receive').removeClass('hidden');
                $('.receive').addClass('hidden');
                $('.branch_to').addClass('hidden');
                $('.rider_assign').addClass('hidden');
                // $('.deliveryZoneNo').addClass('hidden');
                // $('.receive').show();
            } else if (id === 'Parcel in Transit to Destination') {
                $('body').find('#bulk_status_assign').prop('action', 'bulk_status_assign.php');
                $('.branch_to').removeClass('hidden');
                $('.rider_assign').addClass('hidden');
                $('.receive').addClass('hidden');
                $('.return_receive').addClass('hidden');
                // $('.deliveryZoneNo').addClass('hidden');
            } else if (id === 'Returned to origin city') {
                $('body').find('#bulk_status_assign').prop('action', 'bulk_status_assign.php');
                $('.branch_to').removeClass('hidden');
                $('.rider_assign').addClass('hidden');
                $('.receive').addClass('hidden');
                $('.return_receive').addClass('hidden');
                // $('.deliveryZoneNo').addClass('hidden');
            } else if (id === 'Out for Delivery') {
                $('body').find('#bulk_status_assign').prop('action', 'bulk_delivery_assign.php');
                $('.rider_assign').removeClass('hidden');
                // $('.deliveryZoneNo').removeClass('hidden');
                $('.branch_to').addClass('hidden');
                $('.receive').addClass('hidden');
                $('.return_receive').addClass('hidden');

            } else if (id === 'Pick up in progress') {
                $('body').find('#bulk_status_assign').prop('action', 'bulk_pickup_assign.php');
                $('.rider_assign').removeClass('hidden');
                $('.branch_to').addClass('hidden');
                $('.receive').addClass('hidden');
                $('.return_receive').addClass('hidden');
                // $('.deliveryZoneNo').addClass('hidden');
            } else if (id === 'Parcel Received at office' || id === 'Picked up') {
                $('body').find('#bulk_status_assign').prop('action', 'bulk_status_assign.php');
                $('.rider_assign').removeClass('hidden');
                $('.branch_to').addClass('hidden');
                $('.receive').addClass('hidden');
                $('.return_receive').addClass('hidden');
                // $('.deliveryZoneNo').addClass('hidden');
            } else if (id === 'Return In Process') {
                $('body').find('#bulk_status_assign').prop('action', 'bulk_return_assign.php');
                $('.rider_assign').removeClass('hidden');
                $('.branch_to').addClass('hidden');
                $('.receive').addClass('hidden');
                $('.return_receive').addClass('hidden');
                // $('.deliveryZoneNo').addClass('hidden');
            } else {
                $('body').find('#bulk_status_assign').prop('action', 'bulk_status_assign.php');
                $('.receive').addClass('hidden');
                $('.return_receive').addClass('hidden');
                $('.branch_to').addClass('hidden');
                $('.rider_assign').addClass('hidden');
                // $('.deliveryZoneNo').addClass('hidden');
            }
        })
    }, false);
    </script>
    <script src="assets/js/app/weight_calculation.js" type="text/javascript"></script>