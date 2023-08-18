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

    <div class="panel-heading">Delivery Scan Sheet </div>

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
                        echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> ' . $msg1 . '</div>';
                        unset($_SESSION['succ_msg_for_api']);
                    }

                    if (isset($_SESSION['err_msg_for_api']) && !empty($_SESSION['err_msg_for_api'])) {
                        $msg1 = $_SESSION['err_msg_for_api'];
                        echo '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">X</button><strong>Error !</strong> ' . $msg1 . '</div>';
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
                            <form method="POST" action="bulk_delivery_assign.php" id="bulk_status_assign"
                                style="clear: both;">

                                <div class="col-sm-6 left_right_none rider_assign">
                                    <div class="form-group">
                                        <label><?php echo getLange('rider') . ' ' . getLange('vender'); ?> </label>
                                        <select class="form-control courier_list js-example-basic-single active_courier"
                                            name="active_courier">
                                            <option selected disabled>Select delivery (Rider/Vendor)</option>
                                            <?php mysqli_data_seek($courier_query, 0);
                                            while ($row = mysqli_fetch_array($courier_query)) { ?>
                                            <option value="<?php echo $row['id']; ?>"><?php echo $row['Name']; ?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 left_right_none rider_assign">
                                    <div class="form-group">
                                        <label>Delivery Zone Number </label>

                                        <select class="form-control courier_list js-example-basic-single"
                                            name="delivery_zone_number">
                                            <option selected value="">
                                                <?php echo getLange('select') . ' ' . getLange('deliveryzone'); ?>
                                            </option>
                                            <?php while ($row = mysqli_fetch_array($delivery_zone_q)) { ?>
                                            <option <?php if ($row['route_code'] == $delivery_zone_number) {
														echo "selected";
													} ?> value="<?php echo $row['route_code']; ?>"><?php echo $row['route_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>


                                <input type="hidden" name="order_ids" id="print_data">
                                <input type="hidden" name="return_to" value="delivery_scan_sheet.php">

                                <textarea class="form-control status_update_run" rows="8"
                                    placeholder="Please enter order ids"><?php if (isset($_SESSION['old_orders_list']) and !empty($_SESSION['old_orders_list'])) {
                                                                                                                                    echo $_SESSION['old_orders_list'];
                                                                                                                                } ?></textarea>

                                <div class="col-sm-3 left_right_none upate_Btn">
                                    <a href="#" class="update_status btn btn-success"
                                        style="margin-top: 7px;"><?php echo getLange('update'); ?></a>
                                </div>
                            </form>

                            <div class="help-info orders-count" style="float: right;font-size: 12px;color: #999;">Orders
                                Count: 0</div>

                            <?php
                            //   print_r($_SESSION['old_orders_list']);
                            if (isset($_SESSION['old_orders_list']) and !empty($_SESSION['old_orders_list'])) {
                                unset($_SESSION['old_orders_list']);
                            }
                            ?>



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
    <script src="assets/js/app/weight_calculation.js" type="text/javascript"></script>