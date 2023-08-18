<style type="text/css">
    .city_to option.hide {
        /*display: none;*/
    }

    .form-group {
        margin-bottom: 0px !important;
    }
</style>
<?php
if (isset($_POST['submit'])) {
    $return = $_POST['return_fee'];
    $cash_handling = $_POST['cash_handling'];
    $gst = $_POST['gst'];
    $fuelsurcharge = isset($_POST['fuel_surcharge']) ? $_POST['fuel_surcharge'] : 0;
    $auto_assign = $_POST['rider_vendor_auto_assign'];
    $auto_processing = $_POST['auto_processing'];
    $print = $_POST['print'];
    $bulk_valid = $_POST['bulk_status_validation'];
    $print_template = $_POST['print_template'];
    $number_format = $_POST['number_format'];
    $manual_area = $_POST['manual_area'];
    $cancel_attempts_value = $_POST['cancel_attempts'];
    $status_after_refused_value = $_POST['status_after_refused'];
    $customer_language_priority = $_POST['customer_language_priority'];
    $tariff_type = $_POST['tariff_type'];

    $c_fuel_charge = isset($_POST['customer_fuel_charge']) ? 1 : 0;
    $status_for_cancel_values = '';
    foreach ($_POST['status_for_cancel'] as $key => $value) {
        $status_for_cancel_values .= $value . ',';
    }
    $trim_status_value = trim($status_for_cancel_values, ',');
    // echo "UPDATE config SET value='".$trim_status_value."' WHERE `name`='status_for_cancel' ";
    // die;
    mysqli_query($con, "UPDATE config SET value='" . $trim_status_value . "' WHERE `name`='status_for_cancel' ");
    mysqli_query($con, "UPDATE config SET value='" . $gst . "' WHERE `name`='gst' ");
    mysqli_query($con, "UPDATE config SET value='" . $return . "' WHERE `name`='return_fee' ");
    mysqli_query($con, "UPDATE config SET value='" . $cash_handling . "' WHERE `name`='cash_handling' ");
    mysqli_query($con, "UPDATE config SET value='" . $print . "' WHERE `name`='print' ");
    mysqli_query($con, "UPDATE config SET value='" . $auto_assign . "' WHERE `name`='rider_vendor_auto_assign' ");
    mysqli_query($con, "UPDATE config SET value='" . $auto_processing . "' WHERE `name`='auto_processing' ");
    mysqli_query($con, "UPDATE config SET value='" . $print_template . "' WHERE `name`='print_template' ");
    mysqli_query($con, "UPDATE config SET value='" . $number_format . "' WHERE `name`='number_format' ");
    mysqli_query($con, "UPDATE config SET value='" . $manual_area . "' WHERE `name`='manual_area' ");
    mysqli_query($con, "UPDATE config SET value='" . $fuelsurcharge . "' WHERE `name`='fuel_surcharge' ");
    mysqli_query($con, "UPDATE config SET value='" . $bulk_valid . "' WHERE `name`='bulk_status_validation' ");
    mysqli_query($con, "UPDATE config SET value='" . $c_fuel_charge . "' WHERE `name`='customer_fuel_charge' ");
    mysqli_query($con, "UPDATE config SET value='" . $cancel_attempts_value . "' WHERE `name`='cancel_attempts' ");
    mysqli_query($con, "UPDATE config SET value='" . $_POST['order_delayed_time'] . "' WHERE `name`='order_delayed_time' ");
    mysqli_query($con, "UPDATE config SET value='" . $status_after_refused_value . "' WHERE `name`='status_after_refused' ");
    mysqli_query($con, "UPDATE config SET value='" . $customer_language_priority . "' WHERE `name`='customer_language_priority' ");
    mysqli_query($con, "UPDATE config SET value='" . $tariff_type . "' WHERE `name`='tariff_type' ");


    if (isset($_FILES["logo"]["name"]) && !empty($_FILES["logo"]["name"])) {
        $target_dir = "img/";
        $target_file = $target_dir . uniqid() . basename($_FILES["logo"]["name"]);
        $extension = pathinfo($target_file, PATHINFO_EXTENSION);
        if ($extension == 'jpg' || $extension == 'png' || $extension == 'jpeg') {
            if (move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file)) {
                mysqli_query($con, "UPDATE config SET value='" . $target_file . "' WHERE `name`='logo' ");
            }
        }
    }
    echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Successfull!</strong> Settings saved successfuly.</div>';
}
$return_query = mysqli_query($con, "SELECT value FROM config WHERE `name`='return_fee'  ");
$total_return = mysqli_fetch_array($return_query);
$cash_handling_query = mysqli_query($con, "SELECT value FROM config WHERE `name`='cash_handling'  ");
$total_cash_handling = mysqli_fetch_array($cash_handling_query);
$gst_query = mysqli_query($con, "SELECT value FROM config WHERE `name`='gst' ");
$total_gst = mysqli_fetch_array($gst_query);
$logo_img = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='logo' "));
$refused_status = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='status_after_refused' "));
$auto_assign_rider = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='rider_vendor_auto_assign' "));
$auto_processing   = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='auto_processing' "));
$print  = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='print' "));
$print_template  = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='print_template' "));
$manual_area  = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='manual_area' "));
$bulk_validation  = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='bulk_status_validation' "));
$cancelAtmpts  = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='cancel_attempts' "));
$fuelsurcharge  = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='fuel_surcharge' "));
$customer_fuel_charge  = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='customer_fuel_charge' "));
$language_priority = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='customer_language_priority' "));
$tariff_type = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='tariff_type' "));



$all_satuses_query  =  mysqli_query($con, "SELECT * FROM order_status WHERE delivery_rider = 1");


?>
<!-- <div class="page-header"><h3><?php echo getLange('setting'); ?> </h3></div> -->
<div class="warper container-fluid">
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="panel panel-primary">
            <div class="panel-heading"><?php echo getLange('setting'); ?></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4 setting_padd">
                        <div class="form-group">
                            <label><?php echo getLange('gst'); ?> (%)</label>
                            <input type="text" name="gst" value="<?php echo $total_gst['value']; ?>" class="form-control" required="true">
                        </div>
                    </div>
                    <div class="col-md-4 setting_padd">
                        <div class="form-group">
                            <label><?php echo getLange('returnedfee'); ?> (<?php echo getConfig('currency'); ?>)</label>
                            <input type="text" name="return_fee" value="<?php echo $total_return['value']; ?>" class="form-control" required="true">
                        </div>
                    </div>
                    <div class="col-md-4 setting_padd">
                        <div class="form-group">
                            <label><?php echo getLange('cashhandling'); ?> (%)</label>
                            <input type="text" name="cash_handling" value="<?php echo $total_cash_handling['value']; ?>" class="form-control" required="true">
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-4 setting_padd">
                        <div class="form-group">
                            <label><?php echo getLange('ridervenderassign'); ?></label>
                            <select name="rider_vendor_auto_assign" class="form-control">
                                <option <?php if ($auto_assign_rider['value'] == 'yes') : echo "selected";
                                endif ?> value="yes">Active</option>
                                <option <?php if ($auto_assign_rider['value'] == 'no') : echo "selected";
                                endif ?> value="no">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 setting_padd">
                        <div class="form-group">
                            <label><?php echo getLange('autoprocessing'); ?></label>
                            <select name="auto_processing" class="form-control">
                                <option <?php if ($auto_processing['value'] == 'yes') : echo "selected";
                                endif ?> value="no">Inactive</option>
                                <option <?php if ($auto_processing['value'] == 'yes') : echo "selected";
                                endif ?> value="yes">Active</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 setting_padd">
                        <div class="form-group">
                            <label><?php echo getLange('manualarea'); ?> </label>
                            <select name="manual_area" class="form-control">
                                <option value="1" <?php if ($manual_area['value'] == '1') : echo "selected";
                                endif ?>>Yes</option>
                                <option value="0" <?php if ($manual_area['value'] == '0') : echo "selected";
                                endif ?>>No</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 setting_padd">
                        <div class="form-group">
                            <label><?php echo getLange('fuelsurcharge'); ?> (%)</label> <span class="customer_fuel_charge">
                                <input style="width: auto;border: none;vertical-align: middle;box-shadow: none;" type="checkbox" name="customer_fuel_charge" <?php if ($customer_fuel_charge['value'] == 1) {
                                    echo 'checked';
                                } ?>>Enable Customer Wise
                            </span>
                            <input type="text" name="fuel_surcharge" value="<?php echo isset($fuelsurcharge['value']) ? $fuelsurcharge['value'] : ''; ?>" class="form-control" required="true">
                        </div>
                    </div>
                    <div class="col-md-4 setting_padd">
                        <div class="form-group">
                            <label><?php echo getLange('printform'); ?> </label>
                            <select name="print" class="form-control">
                                <option <?php if ($print['value'] == '1') : echo "selected";
                                endif ?>>1</option>
                                <option <?php if ($print['value'] == '2') : echo "selected";
                                endif ?>>2</option>
                                <option <?php if ($print['value'] == '3') : echo "selected";
                                endif ?>>3</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 setting_padd">
                        <div class="form-group">
                            <label><?php echo getLange('printtemplate'); ?> </label>
                            <select name="print_template" class="form-control">
                                <option <?php if ($print_template['value'] == 'invoicehtml.php') : echo "selected";
                                endif ?> value="invoicehtml.php"><?php echo getLange('oldinvoice'); ?></option>
                                <option <?php if ($print_template['value'] == 'receipt_booking_invoice.php') : echo "selected";
                                endif ?> value="receipt_booking_invoice.php"><?php echo getLange('newinvoice'); ?></option>

                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 setting_padd">
                        <div class="form-group">
                            <label><?php echo getLange('change_status_on_bulk_status'); ?> </label>
                            <select name="bulk_status_validation" class="form-control">
                                <option value="1" <?php if ($bulk_validation['value'] == '1') : echo "selected";
                                endif ?>>Yes</option>
                                <option value="0" <?php if ($bulk_validation['value'] == '0') : echo "selected";
                                endif ?>>No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 setting_padd">
                        <div class="form-group">
                            <label><?php echo getLange(''); ?>Delayed Order Time(Hours)</label>
                            <input type="text" name="order_delayed_time" value="<?php echo getConfig('order_delayed_time'); ?>" class="form-control" required="true">
                        </div>
                    </div>
                    <div class="col-md-4 setting_padd">
                        <div class="form-group">
                            <label>Canceling Statuses After Failure Attempts</label>
                            <select name="status_after_refused" class="form-control js-example-basic-single">
                                <?php

                                $sta_query = mysqli_query($con, "SELECT * from order_status");
                                while ($rowsts = mysqli_fetch_assoc($sta_query)) { ?>
                                    <option <?php if ($refused_status['value'] == $rowsts['status']) {
                                        echo "selected";
                                    } ?> value="<?php echo $rowsts['status'] ?>"><?php echo getKeyWord($rowsts['status']) ?></option>
                                <?php }
                                ?>
                            </select>

                        </div>
                    </div>
                    <div class="col-md-4 setting_padd">
                        <div class="form-group">
                            <label>Attempts before cancel </label>
                            <select name="cancel_attempts" class="form-control">
                                <option value="1" <?php if ($cancelAtmpts['value'] == '1') : echo "selected";
                                endif ?>>1</option>
                                <option value="2" <?php if ($cancelAtmpts['value'] == '2') : echo "selected";
                                endif ?>>2</option>
                                <option value="3" <?php if ($cancelAtmpts['value'] == '3') : echo "selected";
                                endif ?>>3</option>
                                <option value="4" <?php if ($cancelAtmpts['value'] == '4') : echo "selected";
                                endif ?>>4</option>
                                <option value="5" <?php if ($cancelAtmpts['value'] == '5') : echo "selected";
                                endif ?>>5</option>
                                <option value="6" <?php if ($cancelAtmpts['value'] == '6') : echo "selected";
                                endif ?>>6</option>
                                <option value="7" <?php if ($cancelAtmpts['value'] == '7') : echo "selected";
                                endif ?>>7</option>
                                <option value="8" <?php if ($cancelAtmpts['value'] == '8') : echo "selected";
                                endif ?>>8</option>
                                <option value="9" <?php if ($cancelAtmpts['value'] == '9') : echo "selected";
                                endif ?>>9</option>
                                <option value="10" <?php if ($cancelAtmpts['value'] == '10') : echo "selected";
                                endif ?>>10</option>

                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 setting_padd">
                        <div class="form-group">
                            <label>Tariff Type</label> 
                            <select name="tariff_type" class="form-control ">
                                <option <?php echo isset($tariff_type['value']) && $tariff_type['value']==1 ? 'selected' :'' ?> value="1">Default Tariff</option>
                                <option <?php echo isset($tariff_type['value']) && $tariff_type['value']==2 ? 'selected' :'' ?> value="2">Customer Wise Tariff</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 setting_padd">
                        <div class="form-group">
                            <label>Customer Language Priority </label>
                            <select name="customer_language_priority" class="form-control js-example-basic-single">
                                <?php

                                $sql_portal_lang = mysqli_query($con, "SELECT * FROM portal_language WHERE is_active = 1");
                                while ($rowsls = mysqli_fetch_assoc($sql_portal_lang)) { ?>
                                    <?php $name = isset($rowsls['language']) ? $rowsls['language'] : ''; ?><?php $id = isset($rowsls['id']) ? $rowsls['id'] : ''; ?>
                                    <option <?php if ($id == $language_priority['value']) {
                                        echo "selected";
                                    } ?> value="<?php echo $id; ?>"><?php echo ucfirst($name); ?></option>
                                <?php }
                                ?>
                            </select>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 setting_padd">
                            <div class="form-group">
                                <label>Canceling Statuses</label>
                                <select name="status_for_cancel[]" class="form-control js-example-basic-single" multiple>
                                    <?php
                                    $cancel_save_statuses  = mysqli_fetch_object(mysqli_query($con, "SELECT value FROM config WHERE `name`='status_for_cancel' "))->value;
                                    $prev_array = explode(',', $cancel_save_statuses);
                                    while ($fetch_status = mysqli_fetch_object($all_satuses_query)) {
                                        $selected = '';
                                        if (in_array($fetch_status->status, $prev_array)) {
                                            $selected = 'selected';
                                        }

                                        ?>
                                        <option <?php echo $selected ?> value="<?php echo $fetch_status->status; ?>"><?php echo getKeyWord($fetch_status->status); ?></option>
                                    <?php } ?>


                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 setting_padd">
                        <div class="form-group">
                            <label><?php echo getLange(''); ?>Number Formate</label>
                            <input type="number" name="number_format" value="<?php echo getConfig('number_format'); ?>" class="form-control" required="true">
                        </div>
                    </div>
                    <div class="col-md-12 setting_padd">
                        <div class="form-group">
                            <label><?php echo getLange('logo'); ?></label>
                            <br>
                            <img src="<?php echo $logo_img['value'] ?>" alt="Logo Image" style="width: 100px;">
                            <br>
                            <input type="file" name="logo" accept="image/jpg, image/jpeg, image/png">
                        </div>
                    </div>
                </div>




                <div class="row">
                    <div class="col-md-4 setting_padd rtl_full">
                        <input type="submit" name="submit" value="<?php echo getLange('save'); ?>" class="btn btn-info">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>