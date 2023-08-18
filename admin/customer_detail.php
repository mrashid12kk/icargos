<?php
$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
session_start();
require 'includes/conn.php';
function addQuote($value)
{
    return (!is_string($value) == true) ? $value : "'" . $value . "'";
}

require 'includes/role_helper.php';
$user_role_id = $_SESSION['user_role_id'];

$fuel_sur_value = mysqli_fetch_array(mysqli_query($con, "SELECT charge_value FROM customer_wise_charges WHERE customer_id = " . $_GET['customer_id'] . " AND charge_name = 'fuel_surcharge' "));
$fuel_charge_value = isset($fuel_sur_value['charge_value']) ? $fuel_sur_value['charge_value'] : 0;
$checkAllowed = checkRolePermission($user_role_id, 2, 'view_only', 'Business Module with add enabled');
if (isset($_SESSION['users_id']) && isset($_GET['customer_id']) && $checkAllowed) {
    $customer_id = $_GET['customer_id'];
    $api_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $customer_id . " ");
    $api_record = mysqli_fetch_array($api_query);
    if (isset($_POST['update_customer'])) {
        $customer_update = $_POST;
        if (isset($_POST['fuel_charge_val']) && !empty($_POST['fuel_charge_val'])) {
            $check_prev_val = mysqli_fetch_array(mysqli_query($con, "SELECT charge_value FROM customer_wise_charges WHERE customer_id = " . $_GET['customer_id'] . " AND charge_name = 'fuel_surcharge' "));
            $ch_q = '';
            if (isset($check_prev_val['charge_value']) && !empty($check_prev_val['charge_value'])) {
                $ch_q = "UPDATE customer_wise_charges SET charge_value='" . $_POST['fuel_charge_val'] . "' WHERE customer_id = " . $_GET['customer_id'] . " AND charge_name = 'fuel_surcharge' ";
                mysqli_query($con, $ch_q);
            } else {
                $ch_q = "INSERT INTO `customer_wise_charges`(`customer_id`, `charge_name`, `charge_type`, `charge_value`) VALUES ('" . $_GET['customer_id'] . "','fuel_surcharge','','" . $_POST['fuel_charge_val'] . "')";
                mysqli_query($con, $ch_q);
            }

            unset($customer_update['fuel_charge_val']);
        }



        $api_key_status = $customer_update['api_status'];

        if (isset($customer_update['is_order_manual']) && $customer_update['is_order_manual'] == 1) {
            $customer_update['is_order_manual'] = 1;
        } else {
            $customer_update['is_order_manual'] = 0;
        }
        if (isset($customer_update['is_booking_manual']) && $customer_update['is_booking_manual'] == 1) {
            $customer_update['is_booking_manual'] = 1;
        } else {
            $customer_update['is_booking_manual'] = 0;
        }
        if (isset($customer_update['is_fuelsurcharge']) && $customer_update['is_fuelsurcharge'] == 1) {
            $customer_update['is_fuelsurcharge'] = 1;
        } else {
            $customer_update['is_fuelsurcharge'] = 0;
        }

        if (isset($customer_update['is_merchant']) && $customer_update['is_merchant'] == 1) {
            $customer_update['is_merchant'] = 1;
        } else {
            $customer_update['is_merchant'] = 0;
        }

        if (isset($customer_update['is_saletax']) && $customer_update['is_saletax'] == 1) {
            $customer_update['is_saletax'] = 1;
        } else {
            $customer_update['is_saletax'] = 0;
        }
        if (isset($customer_update['wave_off_return_delivery_fee']) && $customer_update['wave_off_return_delivery_fee'] == 1) {
            $customer_update['wave_off_return_delivery_fee'] = 1;
        } else {
            $customer_update['wave_off_return_delivery_fee'] = 0;
        }
        if (isset($customer_update['is_return_fee_per_parcel']) && $customer_update['is_return_fee_per_parcel'] == 1) {
            $customer_update['is_return_fee_per_parcel'] = 1;
        } else {
            $customer_update['is_return_fee_per_parcel'] = 0;
        }
        if (isset($_FILES["image"]["name"]) && !empty($_FILES["image"]["name"])) {
            $target_dir = "../users/";
            $target_file = $target_dir . uniqid() . basename($_FILES["image"]["name"]);
            // $db_dir = "users/";
            // $db_file = $db_dir .uniqid(). basename($_FILES["image"]["name"]);
            $extension = pathinfo($target_file, PATHINFO_EXTENSION);
            if ($extension == 'jpg' || $extension == 'png' || $extension == 'JPG' || $extension == 'PNG' || $extension == 'gif' || $extension == 'GIF' || $extension == 'jpeg' || $extension == 'JPEG') {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    // echo $target_file;

                    $customer_update['image'] = trim($target_file, '../');
                }
            } else {
                header('Location: customer_detail.php?customer_id=' . $customer_id . '&edit=1&message=Your Logo Image Type In Wrong');
                exit();
            }
        }
        if (isset($_FILES["cnic_copy"]["name"]) and !empty($_FILES["cnic_copy"]["name"])) {
            $target_dir = "../cnic_copy/";
            $target_file = $target_dir . uniqid() . basename($_FILES["cnic_copy"]["name"]);
            // $db_dir = "users/";
            // $db_file = $db_dir .uniqid(). basename($_FILES["cnic_copy"]["name"]);
            $extension = pathinfo($target_file, PATHINFO_EXTENSION);
            if ($extension == 'jpg' || $extension == 'png' || $extension == 'JPG' || $extension == 'PNG' || $extension == 'jpeg ' || $extension == 'JPEG') {
                if (move_uploaded_file($_FILES["cnic_copy"]["tmp_name"], $target_file)) {
                    // echo $target_file;

                    $customer_update['cnic_copy'] = trim($target_file, '../');
                    // echo $db_file;
                    // die;
                }
            } else {
                header('Location: customer_detail.php?customer_id=' . $customer_id . '&edit=1&message=Your CNIS Copy Type In Wrong');
                exit();
            }
        }
        unset($customer_update['update_customer']);
        if (isset($customer_update['password']) && $customer_update['password'] == '') {
            unset($customer_update['password']);
        }


        if (isset($customer_update['password']) && ($customer_update['password'] != $customer_update['repassword'])) {
            unset($customer_update['password']);
        } else {
            if (trim($customer_update['password']) == '') {
                unset($customer_update['password']);
            } else {
                $customer_update['password'] = md5($customer_update['password']);
            }
        }
        unset($customer_update['repassword']);
        unset($customer_update['fuel_charge_val']);
        $index = 0;
        $is_booking_manual = $customer_update['is_booking_manual'];
        $is_fuelsurcharge = $customer_update['is_fuelsurcharge'];
        $is_saletax = $customer_update['is_saletax'];
        $is_merchant = $customer_update['is_merchant'];
        $is_order_manual = $customer_update['is_order_manual'];
        $wave_off_return_delivery_fee = $customer_update['wave_off_return_delivery_fee'];
        $is_return_fee_per_parcel = $customer_update['is_return_fee_per_parcel'];
        $customer_update['multi_user'] = isset($customer_update['multi_user']) && $customer_update['multi_user'] != '' ? $customer_update['multi_user'] : '0';

        foreach ($customer_update as $key => &$value) {
            if (trim($value) == '') {
                array_splice($customer_update, $index, 1);
                $index--;
            }
            $index++;
        }

        $sql = "UPDATE customers SET ";
        $customer_update['client_code'] = 1000 + $customer_id;
        foreach ($customer_update as $k => &$value) {

            $value = addQuote($value);
            $sql .=  $k . " = " . $value . ",";
        }
        $sql = rtrim($sql, ',') . " WHERE id = " . $customer_id;
        mysqli_query($con, "UPDATE customers SET is_booking_manual=" . $is_booking_manual . ",is_fuelsurcharge=" . $is_fuelsurcharge . ",is_saletax=" . $is_saletax . ",is_merchant = '" . $is_merchant . "',is_order_manual=" . $is_order_manual . ",wave_off_return_delivery_fee=" . $wave_off_return_delivery_fee . ",is_return_fee_per_parcel=" . $is_return_fee_per_parcel . " WHERE id = " . $customer_id);
        if (isset($is_merchant) &&  $is_merchant == 1) {
            $check_key = mysqli_query($con, "SELECT merchant_key FROM customers where id=" . $customer_id);
            $mer_key_res = mysqli_fetch_assoc($check_key);
            $mer_key = isset($mer_key_res['merchant_key']) ? $mer_key_res['merchant_key'] : '';
            if (empty($mer_key)) {
                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $generated_key = substr(str_shuffle($permitted_chars), 0, 15);
                mysqli_query($con, "UPDATE customers set merchant_key ='" . $generated_key . "' where id =" . $customer_id);
            }
        }
        if (mysqli_query($con, $sql)) {
            header('Location: customer_detail.php?customer_id=' . $customer_id);
            exit();
        }
    }





    include "includes/header.php";

    $query = mysqli_query($con, "SELECT * FROM customers WHERE id =" . $customer_id . " ");
    $record = mysqli_fetch_array($query);
    // echo "<pre>";
    // print_r($record);
    // die();
    $cities_from = mysqli_query($con, "SELECT * FROM cities WHERE 1 ORDER BY city_name");
    $cities_to = mysqli_query($con, "SELECT * FROM cities WHERE 1  order by city_name  ");
    $branch_query = mysqli_query($con, "Select * from branches where 1");
    $customer_pricing = mysqli_query($con, "SELECT * FROM customer_pricing WHERE customer_id='" . $customer_id . "' order by id DESC ");

    function getBranchNameById($id)
    {
        global $con;
        $branchQ = mysqli_query($con, "SELECT name from branches where id = $id");

        $res = mysqli_fetch_array($branchQ);

        if ($id == null) {
            return "Admin Branch";
        } else {

            return $res['name'];
        }
    }
    function getaccountNameById($id)
    {
        global $con;
        $accountQ = mysqli_query($con, "SELECT account_type from account_types where id = $id");

        $res = mysqli_fetch_array($accountQ);

        if ($id == null) {
            return "";
        } else {

            return $res['account_type'];
        }
    }


    $is_edit = (isset($_GET['edit'])) ? true : false;

?>

<body data-ng-app>
    <style type="text/css">
    input[disabled],
    textarea[disabled] {
        border: none;
        box-shadow: none;
        background: none;
        resize: none;
        width: 100%;
    }

    #same_form_layout table tr th:last-child {
        width: auto !important;
    }

    #customers_details_form tr th,
    #customers_details_form tr td {
        font-size: 12px !important;
        background: #fff;
        padding: 4px 10px;
        vertical-align: middle;
        font-weight: 500;
    }

    .business_tbl tr td {
        color: #1e2c59;

    }
    </style>

    <?php

        include "includes/sidebar.php";

        ?>
    <!-- Aside Ends-->

    <section class="content">

        <?php
            include "includes/header2.php";
            ?>
        <?php if (isset($_GET['message']) && !empty($_GET['message'])) {
                $msg = $_GET['message'];
                echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> ' . $msg . '</div>';
            } ?>
        <!-- Header Ends -->


        <div class="warper container-fluid">

            <div class="page-header">
                <h1><?php echo getLange('bussinessaccountdetail'); ?></h1>
            </div>
            <table class="table table-bordered business_tbl" id="customers_details_form">
                <form id="update_customer_from" action="" method="POST" enctype="multipart/form-data">

                    <tr>
                        <th><?php echo getLange('accountid'); ?>:</th>
                        <td><?php echo $record['client_code']; ?></td>
                        <th><?php echo getLange('accountname'); ?>:</th>
                        <td><input type="text" <?= (!$is_edit) ? 'disabled' : 'class="form-control"'; ?> name="fname"
                                value="<?= $record['fname']; ?>" /></td>
                    </tr>
                    <tr>
                        <th><?php echo getLange('businessname'); ?>:</th>
                        <td><input type="text" <?= (!$is_edit) ? 'disabled' : 'class="form-control"'; ?> name="bname"
                                value="<?= $record['bname']; ?>" /></td>
                        <th><?php echo getLange('city'); ?>:</th>
                        <?php if (!isset($_GET['edit'])) { ?>
                        <td><?= $record['city']; ?></td>
                        <?php } else { ?>
                        <td>
                            <select class="form-control" name="city">
                                <?php foreach ($cities_to as $city) { ?>
                                <option <?php if ($city['city_name'] == $record['city']) {
                                                        echo "selected";
                                                    } ?> value="<?php echo $city['city_name']; ?>">
                                    <?php echo $city['city_name']; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <th><?php echo getLange('busonessdevelopermanager'); ?>:</th>
                        <td><input type="text" <?= (!$is_edit) ? 'disabled' : 'class="form-control"'; ?>
                                name="business_manager" value="<?= $record['business_manager']; ?>" /></td>
                        <th><?php echo getLange('businessaddress'); ?>:</th>
                        <td colspan="3"><textarea rows="1" <?= (!$is_edit) ? 'disabled' : 'class="form-control"'; ?>
                                name="address"><?= $record['address']; ?></textarea></td>
                    </tr>
                    <tr>
                        <th><?php echo getLange('contactname') ?>:</th>
                        <td><input type="text" <?= (!$is_edit) ? 'disabled' : 'class="form-control"'; ?>
                                name="contact_name" value="<?= $record['contact_name']; ?>" /></td>
                        <th><?php echo getLange('contactemail'); ?>:</th>
                        <td><input type="text" <?= (!$is_edit) ? 'disabled' : 'class="form-control"'; ?> name="email"
                                value="<?= $record['email']; ?>" /></td>
                    </tr>
                    <tr>

                        <th><?php echo getLange('contactphone'); ?>:</th>
                        <td><input type="text" <?= (!$is_edit) ? 'disabled' : 'class="form-control"'; ?>
                                name="mobile_no" value="<?= $record['mobile_no']; ?>" /></td>
                        <th><?php echo getLange('contactcnic'); ?>:</th>
                        <td><input type="text" <?= (!$is_edit) ? 'disabled' : 'class="form-control"'; ?> name="cnic"
                                value="<?= $record['cnic']; ?>" /></td>
                    </tr>

                    <tr>

                        <th><?php echo getLange('bankname'); ?>:</th>
                        <td><input type="text" <?= (!$is_edit) ? 'disabled' : 'class="form-control"'; ?>
                        <?php $bank_name=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM bank_detail WHERE id=".$record['bank_name'])); ?>
                                name="bank_name" value="<?= $bank_name['bank_name']; ?>" /></td>
                        <th><?php echo getLange('accountitle'); ?>:</th>
                        <td><input type="text" <?= (!$is_edit) ? 'disabled' : 'class="form-control"'; ?>
                                name="acc_title" value="<?= $record['acc_title']; ?>" /></td>
                    </tr>
                    <tr>
                        <th><?php echo getLange('accountno'); ?>:</th>
                        <td><input type="text" <?= (!$is_edit) ? 'disabled' : 'class="form-control"'; ?>
                                name="bank_ac_no" value="<?= $record['bank_ac_no']; ?>" /></td>

                        <th><?php echo getLange('manuallyorder'); ?>:</th>
                        <td><input type="checkbox"
                                <?= (!$is_edit) ? 'disabled' : 'class="form-control checkbox_disabled"'; ?>
                                name="is_order_manual"
                                <?php if ($record['is_order_manual'] == 1) : echo "checked";
                                                                                                                                                        endif ?>
                                value=1 /></td>
                    </tr>
                    <tr>
                        <th><?php echo getLange('branchname') ?>:</th>
                        <td>
                            <?php if (!isset($_GET['edit'])) : ?>
                            <input type="text" class="form-control"
                                value="<?php echo getBranchNameById($record['branch_id']) ?>" readonly>
                            <?php else : ?>
                            <select class="form-control" name="branch_id">
                                <?php while ($row = mysqli_fetch_assoc($branch_query)) { ?>
                                <option value="<?php echo $row['id']; ?>" <?php if ($record['branch_id'] == $row['id']) {
                                                                                            echo "selected";
                                                                                        } ?>>
                                    <?php echo $row['name']; ?></option>
                                <?php } ?>
                            </select>
                            <?php endif; ?>

                        </td>
                        <th><?php echo getLange('branchcode'); ?>:</th>
                        <td><input type="text" <?= (!$is_edit) ? 'disabled' : 'class="form-control"'; ?>
                                name="branch_code" value="<?= $record['branch_code']; ?>" /></td>
                    </tr>
                    <tr>
                        <th><?php echo getLange('swiftcode'); ?>:</th>
                        <td><input type="text" <?= (!$is_edit) ? 'disabled' : 'class="form-control"'; ?>
                                name="swift_code" value="<?= $record['swift_code']; ?>" /></td>
                        <th><?php echo getLange('iban'); ?>:</th>
                        <td><input type="text" <?= (!$is_edit) ? 'disabled' : 'class="form-control"'; ?> name="iban_no"
                                value="<?= $record['iban_no']; ?>" /></td>
                    </tr>
                    <?php if ($is_edit) : ?>
                    <tr>
                        <th><?php echo getLange('password'); ?>: </th>
                        <td><input type="password" id="passwordVal"
                                <?= (!$is_edit) ? 'disabled' : 'class="form-control"'; ?> name="password" value="" />
                        </td>
                        <th><?php echo getLange('comfirmpassword'); ?>:</th>
                        <td><input type="password" id="rePasswordVal"
                                <?= (!$is_edit) ? 'disabled' : 'class="form-control"'; ?> name="repassword" value="" />
                        </td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <th><?php echo getLange('customer') . ' ' . getLange('status'); ?></th>
                        <?php if (!isset($_GET['edit'])) { ?>
                        <?php if ($record['status'] == 0) { ?>
                        <td>Inactive</td>
                        <?php } else { ?>
                        <td>Active</td>
                        <?php } ?>
                        <?php } else { ?>
                        <td>
                            <select class="form-control" name="status">
                                <option value="1" <?php if ($record['status'] == 1) {
                                                                echo "selected";
                                                            } ?>>Active</option>
                                <option value="0" <?php if ($record['status'] == 2) {
                                                                echo "selected";
                                                            } ?>>Inactive</option>
                            </select>
                        </td>
                        <?php } ?>
                        <th><?php echo getLange('customertype'); ?></th>
                        <?php if (!isset($_GET['edit'])) { ?>
                        <td><input type="text" class="form-control"
                                value="<?php echo getaccountNameById($record['customer_type']) ?>" readonly></td>
                        <?php } else { ?>
                        <td>
                            <select class="form-control" name="customer_type">
                                <option value="0" <?php if ($record['customer_type'] == 0) {
                                                                echo "selected";
                                                            } ?>>COD</option>
                                <option value="1" <?php if ($record['customer_type'] == 1) {
                                                                echo "selected";
                                                            } ?>>NON COD</option>
                                <option value="2" <?php if ($record['customer_type'] == 2) {
                                                                echo "selected";
                                                            } ?>>Corporate</option>
                            </select>
                        </td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <th><?php echo getLange('api') . ' ' . getLange('status'); ?></th>
                        <?php if (!isset($_GET['edit'])) { ?>
                        <?php if ($api_record['api_status'] == 0) { ?>
                        <td>Disabled</td>
                        <?php } else { ?>
                        <td>Enabled</td>
                        <?php } ?>
                        <?php } else { ?>
                        <td>
                            <select class="form-control" name="api_status">
                                <option value="1" <?php if ($api_record['api_status'] == 1) {
                                                                echo "selected";
                                                            } ?>>Enable</option>
                                <option value="0" <?php if ($api_record['api_status'] == 0) {
                                                                echo "selected";
                                                            } ?>>Disable</option>
                            </select>
                        </td>
                        <?php } ?>
                        <th><?php echo getLange('logo'); ?></th>
                        <?php if (!isset($_GET['edit'])) { ?>

                        <td><img src="<?php echo BASE_URL . '' . $record['image']; ?>" style="width: 53px;"></td>


                        <?php } else { ?>
                        <td>
                            <img src="<?php echo BASE_URL . '' . $record['image']; ?>" style="    width: 53px;">
                            <input type="file" name="image" id="logo">
                            <div id="msg"></div>
                        </td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <th><?php echo getLange('enablebookingform') ?></th>
                        <td>
                            <!-- <input type="checkbox" <?php echo (isset($record['is_booking_manual']) && $record['is_booking_manual'] == 1) ? 'checked' : ''; ?> name="is_booking_manual" value="1"> -->
                            <input type="checkbox"
                                <?php echo (!$is_edit) ? 'disabled' : 'class="form-control checkbox_disabled"'; ?>
                                <?php echo (isset($record['is_booking_manual']) && $record['is_booking_manual'] == 1) ? 'checked' : ''; ?>
                                name="is_booking_manual" value="1">
                        </td>
                        <th><?php echo getLange('cnic') . ' ' . getLange('copy'); ?></th>
                        <?php if (!isset($_GET['edit'])) { ?>

                        <td><?php if (!empty($record['cnic_copy'])) {
                                        echo "<img src='" . BASE_URL . '' . $record['cnic_copy'] . "'  style='width: 53px;'>";
                                    } ?></td>


                        <?php } else { ?>
                        <td>
                            <?php if (!empty($record['cnic_copy'])) {
                                        echo "<img src='" . BASE_URL . '' . $record['cnic_copy'] . "'  style='width: 53px;'>";
                                    } ?>
                            <input type="file" name="cnic_copy" id="image">
                            <div class="msg"></div>
                        </td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <th><?php echo getLange('websiteurl'); ?>:</th>
                        <td><input type="text" <?= (!$is_edit) ? 'disabled' : 'class="form-control"'; ?>
                                name="website_url" value="<?php echo $record['website_url']; ?>" /></td>
                        <th><?php echo getLange('producttype'); ?></th>
                        <?php if (!isset($_GET['edit'])) { ?>
                        <td>
                            <input type="text" disabled value="<?php echo $record['product_type']; ?>" />
                        </td>
                        <?php } else { ?>
                        <td>
                            <select class="form-control bname_check2 js-example-basic-single product_type"
                                name="product_type">
                                <option <?php if ($record['product_type'] == 'Apparel') {
                                                    echo "selected";
                                                } ?> value="Apparel">Apparel</option>
                                <option <?php if ($record['product_type'] == 'Automotive Pants') {
                                                    echo "selected";
                                                } ?> value="Automotive Pants">Automotive Pants</option>
                                <option <?php if ($record['product_type'] == 'Accessories') {
                                                    echo "selected";
                                                } ?> value="Accessories">Accessories</option>
                                <option <?php if ($record['product_type'] == 'Gadgets') {
                                                    echo "selected";
                                                } ?> value="Gadgets">Gadgets</option>
                                <option <?php if ($record['product_type'] == 'Cosmetics') {
                                                    echo "selected";
                                                } ?> value="Cosmetics">Cosmetics</option>
                                <option <?php if ($record['product_type'] == 'Jewellry') {
                                                    echo "selected";
                                                } ?> value="Jewellry">Jewellry</option>
                                <option <?php if ($record['product_type'] == 'Stationary') {
                                                    echo "selected";
                                                } ?> value="Stationary">Stationary</option>
                                <option <?php if ($record['product_type'] == 'Handicrafts') {
                                                    echo "selected";
                                                } ?> value="Handicrafts">Handicrafts</option>
                                <option <?php if ($record['product_type'] == 'Footwear') {
                                                    echo "selected";
                                                } ?> value="Footwear">Footwear</option>
                                <option <?php if ($record['product_type'] == 'Organic &amp; Health Products') {
                                                    echo "selected";
                                                } ?> value="Organic &amp; Health Products">Organic &amp; Health
                                    Products</option>
                                <option <?php if ($record['product_type'] == 'Appliances or Electronics') {
                                                    echo "selected";
                                                } ?> value="Appliances or Electronics">Appliances or Electronics
                                </option>
                                <option <?php if ($record['product_type'] == 'Home Decor or Interior items') {
                                                    echo "selected";
                                                } ?> value="Home Decor or Interior items">Home Decor or Interior items
                                </option>
                                <option <?php if ($record['product_type'] == 'Toys') {
                                                    echo "selected";
                                                } ?> value="Toys">Toys</option>
                                <option <?php if ($record['product_type'] == 'Fitness items') {
                                                    echo "selected";
                                                } ?> value="Fitness items">Fitness items</option>
                                <option <?php if ($record['product_type'] == 'MarketPlace') {
                                                    echo "selected";
                                                } ?> value="MarketPlace">MarketPlace</option>
                                <option <?php if ($record['product_type'] == 'Document &amp; Letters') {
                                                    echo "selected";
                                                } ?> value="Document &amp; Letters">Document &amp; Letters</option>
                                <option <?php if ($record['product_type'] == 'Others') {
                                                    echo "selected";
                                                } ?> value="Others">Others</option>
                            </select>
                            <?php } ?>
                    </tr>
                    <tr>
                        <th><?php echo getLange('expectedaverageshipmentmonth'); ?>:</th>
                        <td>
                            <input type="text" <?php echo (!$is_edit) ? 'disabled' : 'class="form-control"'; ?>
                                name="expected_shipment" value="<?php echo $record['expected_shipment']; ?>" />
                        </td>
                        <th>Language Priority:</th>
                        <td>
                            <select <?= (!$is_edit) ? 'disabled' : 'class="form-control js-example-basic-single "'; ?>
                                class="form-control" name="language_priority">
                                <?php
                                    $sql_portal_lang = mysqli_query($con, "SELECT * FROM portal_language WHERE is_active = 1");
                                    while ($rowsls = mysqli_fetch_assoc($sql_portal_lang)) { ?>
                                <?php $name = isset($rowsls['language']) ? $rowsls['language'] : ''; ?><?php $id = isset($rowsls['id']) ? $rowsls['id'] : ''; ?>
                                <option <?php if ($id == $record['language_priority']) {
                                                    echo "selected";
                                                } ?> value="<?php echo $id; ?>"><?php echo ucfirst($name); ?></option>
                                <?php } ?>
                            </select>
                    </tr>
                    <tr>
                        <th><?php echo getLange('fuelsurcharge'); ?>:</th>
                        <td>
                            <input type="checkbox"
                                <?php echo (!$is_edit) ? 'disabled' : 'class="form-control checkbox_disabled"'; ?>
                                <?php echo (isset($record['is_fuelsurcharge']) && $record['is_fuelsurcharge'] == 1) ? 'checked' : ''; ?>
                                name="is_fuelsurcharge" value="1" />
                            <input type="text" name="fuel_charge_val"
                                value="<?php echo isset($fuel_charge_value) ? $fuel_charge_value : 0; ?>"
                                <?php echo (!$is_edit) ? 'disabled' : 'class="form-control"'; ?>>
                        </td>
                        <th><?php echo getLange('salestax'); ?>:</th>
                        <td>
                            <input type="checkbox"
                                <?php echo (!$is_edit) ? 'disabled' : 'class="form-control checkbox_disabled"'; ?>
                                <?php echo (isset($record['is_saletax']) && $record['is_saletax'] == 1) ? 'checked' : ''; ?>
                                name="is_saletax" value="1" />
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo getLange('wave_off_return_delivery_fee'); ?>:</th>
                        <td>
                            <input type="checkbox"
                                <?php echo (!$is_edit) ? 'disabled' : 'class="form-control checkbox_disabled wave_off_return_delivery_fee"'; ?>
                                <?php echo (isset($record['wave_off_return_delivery_fee']) && $record['wave_off_return_delivery_fee'] == 1) ? 'checked' : ''; ?>
                                name="wave_off_return_delivery_fee" value="1" />
                        </td>
                        <th>Multi User:</th>
                        <td>
                            <input type="checkbox"
                                <?php echo (!$is_edit) ? 'disabled' : 'class="form-control checkbox_disabled"'; ?>
                                <?php echo (isset($record['multi_user']) && $record['multi_user'] == 1) ? 'checked' : ''; ?>
                                name="multi_user" value="1" />
                        </td>
                    </tr>

                    <tr>
                        <th><?php echo getLange('return_fee_per_parcel'); ?>:</th>
                        <td>
                            <input type="checkbox"
                                <?php echo (!$is_edit) ? 'disabled' : 'class="form-control checkbox_disabled is_return_fee_per_parcel"'; ?>
                                <?php echo (isset($record['is_return_fee_per_parcel']) && $record['is_return_fee_per_parcel'] == 1) ? 'checked' : ''; ?>
                                name="is_return_fee_per_parcel" value="1" />
                            <?php if ($is_edit) { ?>
                            <input type="text" name="return_fee_per_parcel"
                                value="<?php echo isset($record['return_fee_per_parcel']) ? $record['return_fee_per_parcel'] : 0; ?>"
                                <?php echo (isset($record['is_return_fee_per_parcel']) && $record['is_return_fee_per_parcel'] == 1) ? '' : 'readonly'; ?>
                                class="form-control">
                            <?php } else { ?>
                            <input type="text" name="return_fee_per_parcel"
                                value="<?php echo isset($record['return_fee_per_parcel']) ? $record['return_fee_per_parcel'] : 0; ?>"
                                <?php echo (!$is_edit) ? 'disabled' : 'class="form-control"'; ?>>
                            <?php } ?>
                        </td>
                        <th>Pickup Address</th>
                        <td><?php echo isset($record['billing_address']) ? $record['billing_address'] : ''; ?></td>
                    </tr>
                    <tr>
                        <th>Enable Merchant:</th>
                        <td>
                            <input type="checkbox"
                                <?php echo (!$is_edit) ? 'disabled' : 'class="form-control checkbox_disabled"'; ?>
                                <?php echo (isset($record['is_merchant']) && $record['is_merchant'] == 1) ? 'checked' : ''; ?>
                                name="is_merchant" value="1" />
                        </td>
                        <?php if (!$edit && $record['is_merchant'] == 1) : ?>
                        <th>Merchant Key:</th>
                        <td>
                            <input type="text" disabled
                                value="<?php echo isset($record['merchant_key']) ? $record['merchant_key'] : ''  ?>" />
                        </td>
                        <?php endif; ?>
                        <th>Devision</th>
                        <td><?php if (isset($record['state_id'])) {
                            $statename=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM state WHERE id=".$record['state_id']));
                            echo isset($statename['state_name']) ? $statename['state_name'] : '';
                        } ?></td>
                    </tr>
                    <tr>
                        <th>AREA:</th>
                        <td>
                            <?php if (isset($record['city'])) {
                            $area_code=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM cities WHERE city_name='".$record['city']."'"));
                            echo isset($area_code['stn_code']) ? $area_code['stn_code'] : '';
                        } ?>
                        </td>
                        <th>Parent Code</th>
                        <td><?php echo  isset($record['parent_code']) ? $record['parent_code'] : ''; ?></td>
                    </tr>
                    <tr>
                        <th>Contact Person:</th>
                        <td><?php echo  isset($record['contact_person']) ? $record['contact_person'] : ''; ?></td>
                        <th>Designation</th>
                        <td><?php echo  isset($record['designation']) ? $record['designation'] : ''; ?></td>
                    </tr>
                    <tr>
                        <th>Inductry Code:</th>
                        <td><?php echo  isset($record['industry_code']) ? $record['industry_code'] : ''; ?></td>
                        <th>GST:</th>
                        <td><?php echo  isset($record['gst']) ? $record['gst'] : ''; ?></td>
                    </tr>
                    <tr>
                        <th>Fax:</th>
                        <td><?php echo  isset($record['fax']) ? $record['fax'] : ''; ?></td>
                        <th>Validaty:</th>
                        <td><?php echo  isset($record['validity']) ? date('d-m-Y' ,strtotime($record['validity'])) : ''; ?></td>
                    </tr>
                    <tr>
                        <th>Billing Instruction:</th>
                        <td><?php echo  isset($record['billing_instruction']) ? $record['billing_instruction'] : ''; ?></td>
                        <th>Handling Charges:</th>
                        <td><?php echo  isset($record['handling_charges']) ? $record['handling_charges'] : ''; ?></td>
                    </tr>
                    <tr>
                        <th>Monthly Revenue:</th>
                        <td><?php echo  isset($record['monthly_revenue']) ? $record['monthly_revenue'] : ''; ?></td>
                        <th>Fuel Formula:</th>
                        <td><?php echo  isset($record['fuel_formula']) ? $record['fuel_formula'] : ''; ?></td>
                    </tr>
                    <tr>
                        <th>Billing Tariff Increase:</th>
                        <td><?php echo  isset($record['tariff_increase']) ? $record['tariff_increase'] : ''; ?></td>
                        <th>Settlement Period:</th>
                        <td><?php echo  isset($record['payment_within']) ? $record['payment_within'] : ''; ?></td>
                    </tr>
                    <tr>
                        <th>Other Charges:</th>
                        <td><?php echo  isset($record['other_charges']) ? $record['other_charges'] : ''; ?></td>
                        <th>Special Instruction:</th>
                        <td><?php echo  isset($record['special_instruction']) ? $record['special_instruction'] : ''; ?></td>
                    </tr>
                    <tr>
                        <th>Frequent Destinations:</th>
                        <td><?php echo  isset($record['frequent_destination']) ? $record['frequent_destination'] : ''; ?></td>
                        <th>Zone:</th>
                        <td><?php if (isset($record['zone_type'])) {
                            $zone_type=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM zone_type WHERE id=".$record['zone_type']));
                            echo isset($zone_type['zone_name']) ? $zone_type['zone_name'] : '';
                        } ?></td>
                    </tr>
                    <tr>
                        <th>BDM/KAM:</th>
                        <td><?php echo  isset($record['bdm_kam']) ? $record['bdm_kam'] : ''; ?></td>
                        <th>Territory Code:</th>
                        <td><?php echo  isset($record['territory_code']) ? $record['territory_code'] : ''; ?></td>
                    </tr>
                    <tr>
                        <th>Collector:</th>
                        <td><?php echo  isset($record['collector']) ? $record['collector'] : ''; ?></td>
                        <th>Collection id:</th>
                        <td><?php echo  isset($record['collection_id']) ? $record['collection_id'] : ''; ?></td>
                    </tr>
                    <tr>
                        <th>CHS/ABH:</th>
                        <td><?php echo  isset($record['chs_abh']) ? $record['chs_abh'] : ''; ?></td>
                        <th>MR/AMR:</th>
                        <td><?php echo  isset($record['mr_amr']) ? $record['mr_amr'] : ''; ?></td>
                    </tr>
                    <tr>
                        <th>SM:</th>
                        <td><?php echo  isset($record['sm']) ? $record['sm'] : ''; ?></td>
                        <th>NTN:</th>
                        <td><?php echo  isset($record['ntn_no']) ? $record['ntn_no'] : ''; ?></td>
                    </tr>
                    <tr>
                        <th>STN:</th>
                        <td><?php echo  isset($record['stn_no']) ? $record['stn_no'] : ''; ?></td>
                        <th>Logitics:</th>
                        <td><?php echo  isset($record['logistics']) ? $record['logistics'] : ''; ?></td>
                    </tr>
                    <tr>
                        <th>Express:</th>
                        <td><?php echo  isset($record['express']) ? $record['express'] : ''; ?></td>
                    </tr>
                    <?php if ($is_edit) : ?>

                    <tr>
                        <th colspan="4">
                            <span class="passError"></span>
                            <a href="customer_detail.php?customer_id=<?php echo $record['id'] ?>"
                                class="cancel_details btn btn-default"><?php echo getLange('cancel'); ?>
                            </a>
                            <input type="submit" name="update_customer" value="Update"
                                class="submit_details btn btn-success">
                        </th>
                    </tr>
                    <?php endif; ?>

                </form>
                <?php if (!$is_edit) : ?>
                <tr>
                    <th colspan="4">
                        <?php if ($record['status'] == 0) : ?>
                        <a href="approve_customer.php?id=<?php echo $record['id'] ?>"
                            class="btn btn-success">Approve</a>&nbsp;
                        <?php endif; ?>
                        <div class="row">
                            <?php require_once "includes/role_helper.php";
                                    if (checkRolePermission($_SESSION['user_role_id'], 2, 'edit_only', $comment = null)) {
                                    ?>
                            <div class="col-sm-1 sidegapp-submit  ">
                                <a style="    width: 100%;"
                                    href="editbusiness.php?customer_id=<?php echo $record['id'] ?>&edit=1"
                                    class="btn btn-info"><?php echo getLange('edit'); ?></a>
                            </div>
                            <?Php
                                    } ?>

                            <div class="col-md-1">
                                <form target="_blank" action="login_customer.php" method="POST">
                                    <input type="hidden" name="customer_id" value="<?= $record['id']; ?>">
                                    <input type="submit" name="submit"
                                        value="<?php echo getLange('loginasbussinessaccount'); ?>"
                                        class="btn btn-danger" />
                                </form>
                            </div>
                            <div class="col-md-1 " style="left: 112px; width: 107px;">
                                <a style="width: 100%;" href="bussiness_account_sheet.php?account_no=<?php echo $record['id'] ?>&edit=1"
                                    class="btn btn-primary"  target='_blank'><?php echo getLange('print'); ?></a>
                            </div>
                        </div>
                    </th>
                </tr>
                <?php endif; ?>
            </table>


            <div class="page-header">
                <h1><a class="btn btn-info"
                        href="assigntariff.php?customer_id=<?php echo $customer_id; ?>">Assign Tariff</a>
                </h1>

                 <!-- <a class="btn btn-info pull-right auto_assign_btn" -->
                    <!-- onclick="return confirm('Are you sure you want to assign all tariff?');" -->
                    <!-- href="autoassigntariff.php?customer_id=<?php echo $customer_id; ?>">Auto Assign All Tariff</a>  -->
            </div>

            <div class="row">
                <div class="col-sm-12" style="padding: 0;">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><?php echo getLange('pricing'); ?></div>
                        <div class="panel-body" id="same_form_layout" style="padding: 14px;">
                            <form method="POST" action="customer_cod_pricing.php">
                                <input type="hidden" name="customer_id" value="<?php echo $customer_id ?>">

                                <div class="table-responsive border-up">
                                    <table class="table table-bordered pricing_tbl">
                                        <thead>
                                            <tr>
                                                <th>Tariff Name</th>
                                                <th>Pay Mode</th>
                                                <th>Product</th>
                                                <th>Tariff Mapping</th>
                                                <th>Service Type</th>
                                                <th><?php echo getLange('action'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $tariff_id_q=mysqli_query($con,"SELECT * FROM customer_tariff_detail WHERE customer_id=".$_GET['customer_id']." GROUP BY tariff_id ");
                                                while ($tariff_id=mysqli_fetch_array($tariff_id_q)) {
                                                    
                                                $customer_pricing=mysqli_query($con,"SELECT tariff.tariff_name,tariff.id as tariff_id,pay_mode.pay_mode,products.name,tarrif_mapping.name as tarrif_mapping_name,services.service_type from tariff LEFT JOIN pay_mode on tariff.pay_mode=pay_mode.id LEFT JOIN products on tariff.product_id=products.id LEFT JOIN tarrif_mapping on tariff.tariff_mapping_id=tarrif_mapping.id LEFT JOIN services on tariff.service_type=services.id WHERE tariff.id=".$tariff_id['tariff_id']);
                                            while ($row = mysqli_fetch_array($customer_pricing)) {
                                               
                                                ?>
                                            <tr>

                                                <td>
                                                    <?php echo isset($row['tariff_name']) ? $row['tariff_name'] : ''; ?>
                                                </td>
                                                <td>
                                                    <?php echo isset($row['pay_mode']) ? $row['pay_mode'] : ''; ?>
                                                </td>
                                                <td>
                                                    <?php echo isset($row['name']) ? $row['name'] : ''; ?>
                                                </td>
                                                <td>
                                                    <?php echo isset($row['tarrif_mapping_name']) ? $row['tarrif_mapping_name'] : ''; ?>
                                                </td>
                                                <td>
                                                    <?php echo isset($row['service_type']) ? $row['service_type'] : ''; ?>
                                                </td>
                                                <td>
                                                    <a
                                                        href="editcustomerpricing.php?tariff_id=<?php echo $row['tariff_id']?>&customer_id=<?php echo $customer_id; ?>"><i
                                                            class="fa fa-edit"></i></a>
                                                    <a href="deletecustomertariff.php?tariff_id=<?php echo $row['tariff_id']?>&customer_id=<?php echo $customer_id; ?>"
                                                        onclick="return confirm('Are you sure you want to delete this Tariff?');">
                                                        <span class="glyphicon glyphicon-trash"></span>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php }  
                                        }?>


                                        </tbody>
                                    </table>
                                </div>
                                <input type="submit" name="submit_cod" class="btn btn-info" value="Save">
                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </div>


        <!-- Warper Ends Here (working area) -->


        <?php

        include "includes/footer.php";
    } else {
        header("location:index.php");
    }
        ?>

        <script type="text/javascript">
        $(document).ready(function() {

            $('body').on('click', '.is_return_fee_per_parcel', function(event) {
                if ($(this).prop('checked') == true) {
                    $('body').find('[name="return_fee_per_parcel"]').attr('readonly', false);
                } else {
                    $('body').find('[name="return_fee_per_parcel"]').attr('readonly', true);
                }
            });


            var counter = 1;
            var selected_to_array = [];


            var updateSelectedCites = function(element = null) {
                let rows = $('#price_table > tbody > tr');
                if (element) {
                    rows = element.siblings();
                }
                selected_to_array = [];
                if (element)
                    selected_to_array.push(element.find('.city_to').val());
                rows.each(function(i) {
                    var selected_to = $(this).find('.city_to :selected').val();
                    console.log(selected_to);
                    if ($.inArray(selected_to, selected_to_array) == -1) {
                        selected_to_array.push(selected_to);
                    } else {
                        let available_options = 0;
                        $(this).find('.city_to option').each(function(i) {
                            var value = $(this).text();
                            if ($.inArray(value, selected_to_array) > -1) {
                                $(this).addClass('hide_city');
                            } else {
                                $(this).removeClass('hide_city');
                                available_options++;
                            }
                        });
                        if (available_options == 0)
                            $(this).remove();
                        else {
                            $(this).find('.city_to option:not(.hide_city)').first().prop('selected',
                                true);
                            selected_to = $(this).find('.city_to').val();
                            if ($.inArray(selected_to, selected_to_array) == -1) {
                                selected_to_array.push(selected_to);
                            }
                        }
                    }

                });
                console.log(selected_to_array);

            }
            updateSelectedCites();
            $('body').on('change', '#price_table .city_to', function(e) {
                updateSelectedCites($(this).closest('tr'));
            })

            $('body').on('click', '.add_row', function(e) {
                e.preventDefault();
                var counter = $('#price_table > tbody tr').length;
                var row = $('#price_table > tbody tr').first().clone();
                row.find('input,select').each(function() {
                    var name = $(this).attr('name').split('[0]');
                    $(this).attr('name', name[0] + '[' + counter + ']' + name[1]);
                })

                row.find('.add_row').addClass('remove_row');
                row.find('.add_row').addClass('btn btn-danger');
                row.find('.fa-plus').addClass('fa-trash');
                row.find('.fa-plus').removeClass('fa-plus');
                row.find('.add_row').removeClass('add_row');
                $('#price_table').append(row);
                updateSelectedCites();
            })
            $('body').on('click', '.remove_row', function(e) {
                e.preventDefault();
                $(this).closest('tr').remove();
                updateSelectedCites();
            })
        })
        </script>
        <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            $(document).ready(function() {
                $("#logo").change(function() {
                    var validExtensions = ["jpg", "jpeg", "gif", "png", "JPG", "JPEG", "GIF",
                        "PNG"
                    ]
                    var file = $(this).val().split('.').pop();

                    if (validExtensions.indexOf(file) == -1) {
                        var msg = ("Only formats are allowed : " + validExtensions.join(', '));
                        $('#msg').html('');
                        $('#msg').html(msg);
                        $(this).val("");
                    } else {
                        $('#msg').html('');
                    }
                });
                $("#image").change(function() {
                    var validExtensions = ["jpg", "jpeg", "png", "JPG", "JPEG", "PNG"]
                    var file = $(this).val().split('.').pop();
                    if (validExtensions.indexOf(file) == -1) {
                        var msg = ("Only formats are allowed : " + validExtensions.join(', '));
                        $('.msg').html('');
                        $('.msg').html(msg);
                        $(this).val("");
                    } else {
                        $('.msg').html('');
                    }

                });
            });
            $(document).on('submit', '#update_customer_from', function(e) {
                const passwordVal = $("#passwordVal").val();
                const rePasswordVal = $("#rePasswordVal").val();
                if (passwordVal != '') {
                    if (passwordVal !== rePasswordVal) {
                        $('.passError').html("Password does not match");
                        e.preventDefault();
                    }
                }
            });
        }, false);
        </script>