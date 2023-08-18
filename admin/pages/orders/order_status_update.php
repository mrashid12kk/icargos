<?php
// mysqli_query($con,"TRUNCATE table cod_collection");
$date = date('Y-m-d H:i:s');
$status_query = mysqli_query($con, "SELECT * FROM order_status ");
$status_querys = mysqli_query($con, "SELECT * FROM order_status ");
$city = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='city' "));
$country = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='country' "));
?>
<style type="text/css">
    .display_none {
        display: none;
    }

    .fa-check:before {
        content: "\f00c";
        font-size: 17px;
        margin-left: 10px;
        background: green;
        padding: 2px;
        border-radius: 5px;
        color: #fff;
        font-weight: 200;
        cursor: pointer;
    }
    .select2-container--default{
    z-index: 9999;
    }
</style>


<!-- Code starts from here -->
<div class="warper container-fluid padd_none">


    <form method="POST">
        <div class="page-header">
            <h1><?php echo getLange('order_status_update'); ?></h1>
        </div>

        <div class="row" style="padding: 15px 0 0;">
            <div class="col-sm-2 colums_gapp padd_none colums_content">
                <input type="text" placeholder="<?php echo  getLange('enter') . ' ' . getLange('cnno') ?>." class="enter_cn enter_cn_no">
            </div>
            <div class="col-sm-1 colums_gapp padd_none">
                <button style="background: #23294c;" type="button" class="append_cn_nos submit_cn"><?php echo getLange('submit'); ?></button>
            </div>
        </div>
        <div class="alert alert-danger display_none order_condition"><button type="button" class="close" data-dismiss="alert">X</button>Paid order cannot be updated.</div>
        <div class="row cn_table">
            <div class="col-sm-12 right_contents">
                <div class="inner_contents table-responsive">
                    <div class="panel panel-default">
                        <div class="panel-heading"><?php echo getLange('order_current_status'); ?></div>
                    </div>
                    <table class="table_box">
                        <thead>

                            <tr>
                                <th><?php echo getLange('cn'); ?>#</th>
                                <th><?php echo getLange('consigner'); ?></th>
                                <th><?php echo getLange('origin'); ?></th>
                                <th><?php echo getLange('consignee'); ?></th>
                                <th><?php echo getLange('destination'); ?></th>
                                <th><?php echo getLange('status'); ?></th>
                                <th><?php echo getLange('pcs'); ?></th>
                                <th><?php echo getLange('weight'); ?></th>
                                <th><?php echo getLange('payment') . ' ' . getLange('status'); ?></th>
                                <th><?php echo getLange('action'); ?></th>
                            </tr>
                        </thead>
                        <tbody class="response_table_body">
                            <tr>
                                <td colspan="10"><?php echo getLange('please_scan_order_with_track_to_proceed'); ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="cn_loader" style="background: #fff; text-align: center; display: none;">
            <img src="<?php echo BASE_URL . 'admin/images/fake-loader-img.gif' ?>">
        </div>


        <div class="row history_table" style="display: none;">
            <div class="col-sm-12 right_contents">
                <div class="inner_contents table-responsive">
                    <table class="table_box">
                        <thead>
                            <tr>
                                <th><?php echo getLange('cn'); ?>#</th>
                                <th><?php echo getLange('status'); ?></th>
                                <th><?php echo getLange('location'); ?></th>
                                <th><?php echo getLange('remarks'); ?></th>
                                <th><?php echo getLange('date'); ?></th>
                                <th><?php echo getLange('action'); ?></th>
                            </tr>
                        </thead>
                        <tbody class="order_history_body">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </form>
</div>
<div class="overlay_popup_fixed"></div>
<div class="overly_popup">
    <div class="close_btn">
        <i class="fa fa-close"></i>
    </div>
    <div class="authincation section-padding" id="order_status_update_log">
        <div class="row">
            <div class="col-sm-12 form_box_date">
                <label><?php echo getLange('orderstatus'); ?></label>
                <select class='add_order_status_log'>
                    <?php while ($row = mysqli_fetch_assoc($status_query)) { ?>
                        <option value="<?php echo $row['status']; ?>"><?php echo getKeyWord($row['status']); ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 form_box_date">
                <label><?php echo getLange('orderdate'); ?></label>
                <input type="date" class="order_date add_order_log_date" value="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="col-sm-6 form_box_date">
                <label><?php echo getLange('ordertime'); ?></label>
                <input type="time" class="order_time add_order_log_time" value="<?php echo date('H:i:s'); ?>">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 left_right_none">
                <div class="form-group">
                    <label><?php echo getLange('Country'); ?> </label>
                    <!-- add this class in blow line::  js-example-basic-single  -->
                   <select class="form-control country js-example-basic-single" name="country" id="country">
                        <!-- <option selected value="">
                                                <?php echo getLange('select') . ' ' . getLange('country'); ?></option> -->
                        <?php
                        $country_query = mysqli_query($con, 'SELECT * FROM country ORDER BY country_name ASC');
                        while ($row = mysqli_fetch_array($country_query)) { ?>
                            <option <?php echo isset($country['value']) && $country['value']==$row['country_name'] ? 'selected' :'' ?> value="<?php echo $row['country_name']; ?>">
                                <?php echo getKeyWord($row['country_name']); ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-sm-6 left_right_none">
                <div class="form-group">
                    <label><?php echo getLange('city'); ?> </label>
                    <select class="form-control select_dynmic_city js-example-basic-single" name="city" id="city">
                        <option selected value="">
                            <?php echo getLange('select') . ' ' . getLange('city'); ?></option>
                        <?php
                        $country_id = 'Pakistan';
                        $country_res = mysqli_fetch_assoc(mysqli_query($con, "SELECT id from country where country_name='$country_id'"));
                        $countryid = isset($country_res['id']) ? $country_res['id'] : '';
                        $city_query = mysqli_query($con, "SELECT * FROM cities where country_id=$countryid ORDER BY city_name ASC");
                        while ($row = mysqli_fetch_array($city_query)) { ?>
                          <option <?php echo isset($city['value']) && $city['value']==$row['city_name'] ? 'selected' :'' ?> value="<?php echo $row['country_name']; ?>">
                                <?php echo getKeyWord($row['city_name']); ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <!-- <div class="col-sm-4 left_right_none">
                <div class="form-group">
                    <label><?php echo getLange('Date Time'); ?> </label>
                    <input type="text" class="form-control datetimepicker created_on_date" name="created_on" value="<?php echo date('Y-m-d H:i:s'); ?>">
                </div>
            </div> -->

        </div>
        <div class="row">
            <div class="col-sm-12 left_right_none">
                <div class="form-group">
                    <label><?php echo getLange('Tracking Remarks'); ?> </label>
                    <input type="text" name="tracking_remarks" class="form-control tracking_remarks">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 form_box_date">
                <button class="add_to_log_btn"><?php echo getLange('add_to_log'); ?></button>
            </div>
        </div>

    </div>
</div>
<script src="assets/js/app/weight_calculation.js" type="text/javascript"></script>