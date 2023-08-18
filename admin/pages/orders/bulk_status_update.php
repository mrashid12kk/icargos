<?php
 // var_dump($_POST);
$date = date('Y-m-d H:i:s');
$records_query = "SELECT * FROM orders WHERE origin IN($all_allowed_origins)";
$records_q = mysqli_query($con, $records_query);
$brnach_query = mysqli_query($con, "SELECT * FROM branches ");
if (isset($_SESSION['branch_id']) && !empty($_SESSION['branch_id']) && $_SESSION['branch_id'] != 1) {
    $brnach_querys = mysqli_query($con, "SELECT * FROM branches WHERE id !=" . $_SESSION['branch_id']);
} else {
    $brnach_querys = mysqli_query($con, "SELECT * FROM branches ");
}
$type_query = mysqli_query($con, "SELECT * FROM types ");
$mode_query = mysqli_query($con, "SELECT * FROM modes ");
$transport_q = mysqli_query($con, "SELECT * FROM transport_company WHERE mode_id = 1 ");
$service_by_q = mysqli_query($con, "SELECT * FROM manifest_services ");
$cities_q = mysqli_query($con, "SELECT * FROM cities ");
$city_q = mysqli_query($con, "SELECT * FROM cities ");
$status_query = mysqli_query($con, "SELECT * FROM order_status ");
$status_querys = mysqli_query($con, "SELECT * FROM order_status ");
$active_query_result = '';
if (isset($_SESSION['branch_id']) and !empty($_SESSION['branch_id']) && $_SESSION['branch_id'] != 1) {
    $active_query_result = mysqli_query($con, "SELECT * FROM users WHERE type = 'driver'  and  branch_id=" . $_SESSION['branch_id']);
} else {
    $active_query_result = mysqli_query($con, "SELECT * FROM users WHERE type = 'driver' ");
}
$branch_selection_query = '';
if (isset($_SESSION['branch_id']) and !empty($_SESSION['branch_id']) && $_SESSION['branch_id'] != 1) {
    $branch_selection_query = mysqli_query($con, "SELECT * FROM branches WHERE id !=" . $_SESSION['branch_id']);
} else {
    $branch_selection_query = mysqli_query($con, "SELECT * FROM branches WHERE id != 1 ");
}
$next_no = mysqli_query($con, "SELECT * FROM manifest_master ORDER BY id DESC limit 1");
$number = mysqli_fetch_assoc($next_no);
if (isset($_POST['collect']) && !empty($_POST['order_ids'])) {
    $order_id_array = explode(',', $_POST['order_ids']);
    foreach ($order_id_array as $value) {
        $order_id = $value;
        $sts_query = mysqli_fetch_assoc(mysqli_query($con, "SELECT status from orders where track_no= '" . $value . "'"));
        $status = $sts_query['status'];
        if (isset($status) && $status != 'Delivered') {
            echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Error! </strong> Order ' . $value . ' is not delivered yet.</div>';
        } else {
            $sql = mysqli_query($con, "SELECT orders.rider_collection, orders.admin_collection, orders.collection_amount as collection_amount, orders.delivery_rider, orders.track_no, orders.assignment_no, orders.status as order_status,  users.Name as rider_name  FROM orders Join users on orders.delivery_rider = users.id WHERE orders.status = 'Delivered'  AND orders.track_no = '" . $order_id . "'");
            $order_Query = mysqli_fetch_assoc($sql);
            if ($order_Query['rider_collection'] == 1 && $order_Query['admin_collection'] == 0) {
                $rider_id = $order_Query['delivery_rider'];
                $rider_name = $order_Query['rider_name'];
                $collection_amount = $order_Query['collection_amount'];
                $riders_collections = $collection_amount;
                $all_cn_no = $order_id;
                $assignment_no = $order_Query['assignment_no'];
                $debitamount = 0;
                $rider_b = "SELECT * FROM rider_wallet_ballance WHERE rider_id=" . $rider_id;
                $rider_res = mysqli_query($con, $rider_b);
                $rider_prev_balance_q = mysqli_fetch_array($rider_res);
                $rider_prev_balance = $rider_prev_balance_q['balance'];
                $newBalance = $rider_prev_balance - $riders_collections;
                $check_q = "SELECT * FROM rider_wallet_ballance WHERE rider_id =" . $rider_id;
                $check_res = mysqli_query($con, $check_q);
                $check_rider_exists  = mysqli_fetch_array($check_res);
                $master_id = 0;
                if (isset($check_rider_exists['rider_id']) && !empty($check_rider_exists['rider_id'])) {
                    $cod_q = mysqli_query($con, "UPDATE  rider_wallet_ballance SET balance = " . $newBalance . ", update_date = '" . date('Y-m-d H:i:s') . "' WHERE rider_id =  " . $rider_id);
                    $master_id = $rider_prev_balance_q['id'];
                } else {
                    $cod_q = mysqli_query($con, "INSERT INTO `rider_wallet_ballance`(`rider_id`, `rider_name`, `balance`, `update_date`) VALUES (" . $rider_id . " , '" . $rider_name . "' , " . $newBalance . " , '" . date('Y-m-d H:i:s') . "'  )");
                    $master_id = mysqli_insert_id($con);
                }
                $log_q = mysqli_query($con, "INSERT INTO `rider_wallet_ballance_log`(`order_id`, `order_no`, `rider_id`, `rider_name`, `debit`, `credit`, `date`)VALUES (" . $master_id . " , '" . $all_cn_no . "'  , " . $rider_id . " , '" . $rider_name . "' , '$debitamount' , '" . $riders_collections . "' , '" . date('Y-m-d H:i:s') . "') ");
                $query = mysqli_query($con, "UPDATE orders set admin_collection = 1 where track_no = '" . $order_id . "'");
                echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Successfull!</strong> Amount of ' . $value . ' is collected.</div>';
            }
            if (isset($order_Query['admin_collection']) && $order_Query['admin_collection'] == 1) {
                echo '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">X</button><strong>Error!</strong> Order ' . $value . ' is already  collected.</div>';
            }
        }
    }
}
if (isset($_POST['submit'])) {
    $status_validation = getConfig('bulk_status_validation');
    $status_received_by = isset($_POST['status']) ? $_POST['status'] : '';
    $country = isset($_POST['country']) ? $_POST['country'] : '';
    $tracking_remarks = isset($_POST['tracking_remarks']) ? $_POST['tracking_remarks'] : '';
    $city = isset($_POST['city']) ? $_POST['city'] : '';
    $post_status = isset($_POST['status']) ? $_POST['status'] : '';
    $active_courier = isset($_POST['active_courier']) ? $_POST['active_courier'] : '';
    $selected_branch = isset($_POST['branch_selection']) ? $_POST['branch_selection'] : '';
    $status_date = isset($_POST['status_date']) ? date('Y-m-d H:i:s', strtotime($_POST['status_date'])) : date('Y-m-d H:i:s');
    $user_id = isset($_SESSION['users_id']) ? $_SESSION['users_id'] : '';
    $order_id_array = [];
    $returnAway = array();
    $date = $status_date;
    foreach ($_POST['all_cn_no'] as $key => $value) {
        $order_id = $value;
        if (isset($post_status) && !empty($post_status)) {
            $query = mysqli_query($con, "SELECT orders.status,order_status.allowed_status FROM orders LEFT JOIN order_status ON orders.status=order_status.status WHERE  orders.track_no ='" . $value . "'");
            $record = mysqli_fetch_array($query);
            $fetch = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM orders WHERE track_no =" . $value . ""));
            $allowed_status = isset($record['allowed_status']) ? explode(',', $record['allowed_status']) : '';
            $check_status  = mysqli_query($con, "SELECT sts_id FROM order_status WHERE status ='" . $post_status . "'   ");
            $status_record = mysqli_fetch_array($check_status);
            $id_check = isset($status_record['sts_id']) ? $status_record['sts_id'] : '';
            if ((!in_array($id_check, $allowed_status)) && $status_validation == 1) {
                echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful! </strong> Order ' . $value . ' cannot be assigned as ' . $post_status . '.</div>';
            } else {
                $check_mark_done = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM order_status WHERE status ='" . $post_status . "' "));

                if ($check_mark_done['marked_done'] == 1) {
                    $rider_status_done = " rider_status_done_no = '1', ";
                    mysqli_query($con, "UPDATE assignment_record SET $rider_status_done status_update_time ='" . $date . "' WHERE order_num = '" . $order_id . "'");
                }
                if ($post_status == 'Pick up in progress') {
                    mysqli_query($con, "UPDATE orders SET status = 'Pick up in progress', status_reason ='', pickup_rider =" . $active_courier . "  WHERE track_no = '" . $order_id . "'");
                    if (isset($_SESSION['branch_id']) and !empty($_SESSION['branch_id'])) {
                        mysqli_query($con, "UPDATE orders SET current_branch = '" . $_SESSION['branch_id'] . "' WHERE track_no = '" . $order_id . "'");
                    } else {
                        mysqli_query($con, "UPDATE orders SET current_branch = '1' WHERE track_no = '" . $order_id . "'");
                    }
                }
                if ($post_status == 'Delivered') {
                    // $received_by = $_POST['received_by'];
                    $status_received_by .= ' ( Received By  Self )';
                    mysqli_query($con, "UPDATE orders SET received_by ='" . $received_by . "' WHERE track_no = '" . $order_id . "' ");
                    if (isset($fetch['booking_type']) && $fetch['booking_type'] == 3) {
                        mysqli_query($con, "UPDATE orders SET payment_status = 'Paid' WHERE track_no = '" . $order_id . "'");
                    }
                    $rider_id = isset($fetch['delivery_rider']) ? $fetch['delivery_rider'] : '';

                    mysqli_query($con, "UPDATE assignment_record SET rider_status_done_no = '1', status_update_time ='" . date('Y-m-d H:i:s') . "' WHERE order_num = '" . $order_id . "' AND  assignment_type = 2");
                }
                if ($post_status == 'Out for Delivery') {
                    mysqli_query($con, "UPDATE orders SET  delivery_rider =" . $active_courier . " , status='Out for Delivery' WHERE track_no = '" . $order_id . "'");
                }
                if ($post_status == 'Parcel in Transit to Destination') {
                    if (isset($_POST['branch_selection']) && !empty($_POST['branch_selection'])) {
                        $selected_branch = isset($_POST['branch_selection']) ? $_POST['branch_selection'] : '';
                    } else {
                        $selected_branch = 1;
                    }
                    mysqli_query($con, "UPDATE orders SET  current_branch =" . $selected_branch . " , status='Parcel in Transit to Destination' WHERE track_no = '" . $order_id . "'");
                }
                if ($post_status == 'Parcel Received at Destination') {
                    $branch_id = 1;
                    if (isset($_SESSION['branch_id']) && !empty($_SESSION['branch_id'])) {
                        $branch_id = $_SESSION['branch_id'];
                    }
                    mysqli_query($con, "UPDATE orders SET  current_branch =" . $branch_id . " , status='Parcel Received at Destination' WHERE track_no = '" . $order_id . "'");
                }
                $updateQuery = "UPDATE orders set status='" . $post_status . "', user_id = $user_id, current_branch = '" . $_SESSION['branch_id'] . "' Where track_no = '" . $value . "'";
                $updatestatus = mysqli_query($con, $updateQuery);
                if ($post_status == 'Delivered') {
                    updateRiderWalletBalance($order_id, $rider_id);
                    $sendSms = addToSmsLog($order_id, 'Delivered');
                } else {
                    $sendSms = addToSmsLog($order_id, 'Status Update');
                }
                $location = isset($_SESSION['branch_id']) ? getBranchCity($_SESSION['branch_id']) : '';
                $branch_id = isset($_SESSION['branch_id']) ? $_SESSION['branch_id'] : '0';
                $order_logs_q = "INSERT INTO `order_logs`(`branch_id`, `assign_branch`, `order_no`, `order_status`,`location`,`user_id`,`created_on`,`country`,`tracking_remarks`,`city`) VALUES (" . $branch_id . "," . $branch_id . ",'" . $value . "','" . $status_received_by . "','" . $location . "','" . $user_id . "','" . $date . "','" . $country . "','" . $tracking_remarks . "','" . $city . "')";
                    // var_dump($order_logs_q);
                $order_log = mysqli_query($con, $order_logs_q);
                mysqli_query($con, "UPDATE orders SET action_date='" . $date . "' WHERE track_no='" . $value . "'");
                echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Successfull!</strong>Order ' . $value . ' status changed as ' . $post_status . '.</div>';
            }
        }
    }
}
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
</style>
<div class="warper container-fluid padd_none">
    <div class="myTextMessage"></div>
    <div class="alert alert-danger display_none"><button type="button" class="close"
            data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not Added a Manifest Details .</div>
    <form method="POST">
        <div class="page-header">
            <h1>Bulk Status Update</h1>
        </div>
        <div class="manifest_box">
            <div class="row">
                <div class="col-sm-6 colums_gapp gray-bg">
                    <div class="row">
                        <div class="col-sm-6 colums_gapp french_lang_section">
                            <div class="colums_content">
                                <div class="row">
                                    <div class="col-sm-8 colums_gapp padd_none">
                                        <input data-name="sheet_no" type="text"
                                            placeholder="<?php echo getLange('pickuprunsheet') . ' ' . getLange('no') ?>."
                                            class="custom_field">
                                    </div>
                                    <div class="col-sm-4 colums_gapp padd_none">
                                        <button data-name="sheet_no" type="button"
                                            class="Pick_cn pick_cn_number"><?php echo getLange('pickcn'); ?></button>
                                    </div>
                                </div>
                            </div>
                            <div class="colums_content">
                                <div class="row">
                                    <div class="col-sm-8 colums_gapp padd_none">
                                        <input data-name="run_sheet_no" type="text"
                                            placeholder="<?php echo getLange('customerrunsheetno'); ?>."
                                            class="custom_field">
                                    </div>
                                    <div class="col-sm-4 colums_gapp padd_none">
                                        <button data-name="run_sheet_no" type="button"
                                            class="Pick_cn pick_cn_number"><?php echo getLange('pickcn'); ?></button>
                                    </div>
                                </div>
                            </div>
                            <div class="colums_content">
                                <div class="row">
                                    <div class="col-sm-8 colums_gapp padd_none">
                                        <input data-name="delivery_sheet_no" type="text"
                                            placeholder="<?php echo getLange('deliveryrunsheet') . ' ' . getLange('no'); ?>."
                                            class="custom_field">
                                    </div>
                                    <div class="col-sm-4 colums_gapp padd_none">
                                        <button data-name="delivery_sheet_no" type="button"
                                            class="Pick_cn pick_cn_number"><?php echo getLange('pickcn'); ?></button>
                                    </div>
                                </div>
                            </div>
                            <div class="colums_content">
                                <div class="row">
                                    <div class="col-sm-8 colums_gapp padd_none">
                                        <input data-name="return_sheet_no" type="text"
                                            placeholder="<?php echo getLange('returnsheet') . ' ' . getLange('no'); ?>."
                                            class="custom_field">
                                    </div>
                                    <div class="col-sm-4 colums_gapp padd_none">
                                        <button data-name="return_sheet_no" type="button"
                                            class="Pick_cn pick_cn_number"><?php echo getLange('pickcn'); ?></button>
                                    </div>
                                </div>
                            </div>
                            <div class="colums_content">
                                <div class="row">
                                    <div class="col-sm-8 colums_gapp padd_none">
                                        <input data-name="manifest_no" type="text"
                                            placeholder="<?php echo getLange('manifestno'); ?>." class="custom_field">
                                    </div>
                                    <div class="col-sm-4 colums_gapp padd_none">
                                        <button data-name="manifest_no" type="button"
                                            class="Pick_cn pick_cn_number"><?php echo getLange('pickcn'); ?></button>
                                    </div>
                                </div>
                            </div>
                            <div class="colums_content">
                                <div class="row">
                                    <div class="col-sm-8 colums_gapp padd_none">
                                        <input data-name="demanifest_no" type="text"
                                            placeholder="<?php echo getLange('demanifest') . ' ' . getLange('no'); ?>."
                                            class="custom_field">
                                    </div>
                                    <div class="col-sm-4 colums_gapp padd_none">
                                        <button data-name="demanifest_no" type="button"
                                            class="Pick_cn pick_cn_number"><?php echo getLange('pickcn'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2"></div>
                <div class="col-sm-4  gray-bg colums_gapp" id="bulk_status_box">
                    <div class="row">
                        <div class="col-sm-12 colums_gapp">
                            <div class="colums_content">
                                <div class="row colums_content">
                                  <div class="col-sm-6 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('origin'); ?> (*)</label>
                                        <select type="text" class="form-control js-example-basic-single country origin"
                                            name="country1" id="country1">
                                     <option selected value=""><?php echo getLange('select') . ' ' . getLange('country'); ?></option>
                                            <?php 
                                            $country_query=mysqli_query($con,'SELECT * FROM country ORDER BY country_name ASC');
                                            while ($row = mysqli_fetch_array($country_query)) {
                                            // var_dump($row);
                                             ?>
                                            <option value="<?php echo $row['country_name']; ?>">
                                                <?php echo getKeyWord($row['country_name']); ?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('city'); ?> </label>
                                        <select class="form-control js-example-basic-single city_origin"
                                            name="city1" id="city1">
                                            <option selected value="">
                                                <?php echo getLange('select') . ' ' . getLange('city'); ?></option>
                                                <option>Select City</option>
                                        </select>
                                    </div>
                                </div>
                                        <div class="col-sm-6 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('destination'); ?> (*)</label>
                                        <select type="text" class="form-control js-example-basic-single country destination"
                                            name="country2" id="country2">
                                             <option selected value="">
                                                <?php echo getLange('select') . ' ' . getLange('country'); ?></option> 
                                            <?php 
                                            $country_query=mysqli_query($con,'SELECT * FROM country ORDER BY country_name ASC');
                                            while ($row = mysqli_fetch_array($country_query)) {
                                            // var_dump($row);
                                             ?>
                                            <option <?php echo isset($row['country_name']) && $row['country_name'] == 'USA' ? 'selected' : ''; ?> value="<?php echo $row['country_name']; ?>">
                                                <?php echo getKeyWord($row['country_name']); ?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('city'); ?> </label>
                                        <select class="form-control js-example-basic-single city_destination"
                                            name="city2" id="city2">
                                            <option selected value="">
                                                <?php echo getLange('select') . ' ' . getLange('city'); ?></option>
                                                <option>Select City</option>
                                        </select>
                                    </div>
                                </div>
                                </div>
                                <div class="row colums_content">
                                </div>
                                <div class="row colums_content">
                                    <div class="col-sm-12 colums_gapp padd_none">
                                        <select class="origin js-example-basic-single area" name="area">
                                            <option selected="" value="">Area</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row colums_content" style="    padding-top: 4px;">
                                    <div class="col-sm-12 colums_gapp padd_none">
                                        <label><?php echo getLange('selectstatuses'); ?> (*)</label>
                                        <select name="status" class="js-example-basic-single allowed_statuses" multiple>
                                            <?php while ($row = mysqli_fetch_assoc($status_query)) { ?>
                                            <option value="<?php echo $row['status'] ?>"><?php echo $row['status']; ?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row colums_content">
                                    <div class="col-sm-12 colums_gapp padd_none">
                                        <div class="col-sm-6 colums_gapp padd_none">
                                        </div>
                                        <div class="col-sm-6 colums_gapp padd_none">
                                            <input type="hidden" name="change_status" value="1">
                                            <button style="background: #23294c; margin-top: 10px;" type="button"
                                                name="submit"
                                                class="submit_cn filter_right"><?php echo getLange('submit'); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="padding: 15px 0 0;">
            <div class="col-sm-2 colums_gapp padd_none colums_content">
                <input type="text" placeholder="Enter CN No." class="enter_cn enter_cn_no">
            </div>
            <div class="col-sm-1 colums_gapp padd_none">
                <button style="background: #23294c;" type="button"
                    class="append_cn_nos submit_cn"><?php echo getLange('submit'); ?></button>
            </div>
            <div class="col-sm-6 colums_gapp padd_none colums_content"></div>
            <div class="col-sm-2 colums_gapp padd_none colums_content">
                <input type="text" placeholder="Enter weight" id="weight_bulk_update_val" class="enter_cns">
            </div>
            <div class="col-sm-1 colums_gapp padd_none">
                <button style="background: #23294c;" type="button" id="weight_bulk_update" class="submit_cn">
                    <?php echo getLange('update'); ?></button>
            </div>
        </div>
        <div class="row cn_table">
            <div class="col-sm-12 right_contents">
                <div class="inner_contents table-responsive">
                    <table class="table_box">
                        <thead>
                            <tr>
                                <th><?php echo getLange('cn'); ?>#</th>
                                <th><?php echo getLange('servicetype'); ?></th>
                                <th><?php echo getLange('shipper'); ?></th>
                                <th><?php echo getLange('origin'); ?></th>
                                <th><?php echo getLange('consignee'); ?></th>
                                <th><?php echo getLange('destination'); ?></th>
                                <th><?php echo getLange('status'); ?></th>
                                <th><?php echo getLange('pcs'); ?></th>
                                <th><?php echo getLange('weight'); ?></th>
                                <th><?php echo getLange('action'); ?></th>
                            </tr>
                        </thead>
                        <tbody class="response_table_body">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="manifest_box">
            <div class="row">
                <div class="col-sm-9 colums_gapp">
                    <div class="colums_content">
                        <label><?php echo getLange('remarks'); ?></label>
                        <textarea type="text" class="remarks" name="remarks"
                            style="margin: 0px 53.3281px 0px 0px;height: 50px;width: 100%;display: block;"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-sm-2 colums_gapp">
                            <div class="colums_content">
                                <label><?php echo getLange('status') . ' ' . getLange('date'); ?></label>
                                <input type="date" name="status_date" placeholder=""
                                    value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row save_print_btn">
                        <div class="col-sm-2 colums_gapp padd_none">
                            <button data-val="1" style="background: #23294c;" name="submit" type="submit"
                                class="save_print_manifest save_manifest"><?php echo getLange('update') . ' ' . getLange('status'); ?></button>
                        </div>
                        <div class="col-sm-6 colums_gapp padd_none">
                            <button data-val="1" style="background: #286fad;" type="button" id="collect_cash"
                                class="save_manifest"><?php echo getLange('collectcash'); ?></button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="row">
                        <div class="col-sm-6 colums_gapp">
                            <div class="colums_content">
                                <label><?php echo getLange('pcs'); ?></label>
                                <input type="text" placeholder="00" name="pieces" class="total_pieces" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6 colums_gapp">
                            <div class="colums_content">
                                <label><?php echo getLange('weight'); ?></label>
                                <input type="text" placeholder="00" name="weight" class="total_weight" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 colums_gapp" required>
                            <div class="colums_content skip-bag">
                                <label><?php echo getLange('orderupdatestatus'); ?> (*)</label>
                                <select name="status" class="status this_status js-example-basic-single" required="">
                                    <option value="">None</option>
                                    <?php while ($row = mysqli_fetch_assoc($status_querys)) { ?>
                                    <option value="<?php echo $row['status'] ?>"><?php echo $row['status']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                           <div class="col-sm-6 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('Country'); ?> </label>
                                        <select class="form-control js-example-basic-single country"
                                            name="country" id="country">
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
                                <div class="col-sm-6 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('city'); ?> </label>
                                        <select class="form-control js-example-basic-single city"
                                            name="city" id="city">
                                            <option selected value="">
                                                <?php echo getLange('select') . ' ' . getLange('city'); ?></option>
                                            <?php /*
                                            $city_query=mysqli_query($con,'SELECT * FROM cities ORDER BY city_name ASC');

                                            while ($row = mysqli_fetch_array($city_query)) { 
                                                ?>
                                            <option value="<?php echo $row['city_name']; ?>">
                                                <?php echo getKeyWord($row['city_name']); ?></option>
                                            <?php }*/ ?>
                                        </select>
                                    </div>
                                </div>
                                 <div class="col-sm-6 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('Date Time'); ?> </label>
                                        <input type="text" class="form-control datetimepicker" name="created_on" value="<?php echo date('Y-m-d H:i:s');?>"> 
                                    </div>
                                </div>
                                <div class="col-sm-6 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('Tracking Remarks'); ?> </label>
                                        <input type="text" name="tracking_remarks" class="form-control tracking_remarks"> 
                                    </div>
                                </div>
                        <div class="col-sm-12 colums_gapp display_none active_courier_div">
                            <div class="colums_content skip-bag">
                                <label>Select Rider</label>
                                <select name="active_courier" class="active_courier">
                                    <!-- <option value="">None</option> -->
                                    <?php while ($row = mysqli_fetch_assoc($active_query_result)) { ?>
                                    <option value="<?php echo $row['id'] ?>"><?php echo $row['Name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 colums_gapp display_none branch_selection">
                            <div class="colums_content skip-bag">
                                <label>Select Branch</label>
                                <select name="branch_selection" class="branch_selection">
                                    <!-- <option value="">None</option> -->
                                    <?php while ($row = mysqli_fetch_assoc($branch_selection_query)) { ?>
                                    <option value="<?php echo $row['id'] ?>"><?php echo $row['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row save_print_btn">
                            <div class="col-sm-12 colums_gapp padd_none">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="myTextMessage"></div>
        </div>
    </form>
</div>
<div class="overlay_popup_fixed"></div>
<div class="overly_popup">
    <div class="close_btn">
        <i class="fa fa-close"></i>
    </div>
    <form method="POST" action="#">
        <div class="authincation section-padding" id="rider_balance_report">
        </div>
    </form>
</div>
<script src="assets/js/app/weight_calculation.js" type="text/javascript"></script>