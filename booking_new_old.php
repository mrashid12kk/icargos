<?php
session_start();
include_once "includes/conn.php";
include "admin/includes/sms_helper.php";
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
function getBarCodeImage($text = '', $code = null, $index)
{
    require_once('includes/BarCode.php');
    $barcode = new BarCode();
    $path = 'assets/barcodes/imagetemp' . $index . '.png';
    $barcode->barcode($path, $text);
    $folder_path = 'assets/barcodes/imagetemp' . $index . '.png';
    return $folder_path;
}

$customer_id = $_SESSION['customers'];
$date = date('Y-m-d H:i:s');
if (isset($_POST['settle']) && isset($_SESSION['customers'])) {
    $product_type_id = isset($_POST['product_type_id']) ? $_POST['product_type_id'] : '';
    $weight = (float)$_POST['weight'];
    $order_type = $_POST['order_type'];
    $customer_id = $_SESSION['customers'];
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    include 'price_calculation.php';
    // $delivery = delivery_calculation($origin, $destination, $weight, $customer_id, $order_type);
    $delivery = delivery_calculation($origin, $destination, $weight, $customer_id, $order_type, $product_type_id);
    echo $delivery;
    exit();
}
$customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $customer_id . " ");
$customer_data = mysqli_fetch_array($customer_query);
$customer_type = $customer_data['customer_type'];
if (isset($_POST['submit_order']) || isset($_POST['save_order'])) {
    $customer_id = 0;
    $plocation = '';
    $dlocation = '';
    if (isset($_SESSION['customers'])) {
        require_once "includes/role_helper.php";
        if (!checkRolePermission(1, 'add_only', '')) {

            header("location:access_denied.php");
        }
        //if manually order enable check validation for order no
        if (isset($customer_data['is_order_manual']) and $customer_data['is_order_manual'] == 1) :
            if (isset($_POST['track_no']) and !empty($_POST['track_no'])) {
                $track_no = $_POST['track_no'];
                $check_track_no_exist = mysqli_query($con, "SELECT id FROM orders WHERE track_no= '" . $track_no . "' ");

                if (mysqli_num_rows($check_track_no_exist) > 0) {
                    $err_response = array();
                    $err_response['error'] = 1;
                    $err_response['alert_msg'] = "Order no already exist.";
                    echo json_encode($err_response);
                    exit();
                }
            } else {
                $err_response = array();
                $err_response['error'] = 1;
                $err_response['alert_msg'] = "Order no is required.";
                echo json_encode($err_response);
                exit();
            }
        endif;

        $last_id_q = mysqli_query($con, "SELECT id FROM orders WHERE 1 ORDER BY id DESC LIMIT 1");
        if (mysqli_num_rows($last_id_q) > 0) {
            $last_id_res = mysqli_fetch_array($last_id_q);
            $last_id = $last_id_res['id'] + 1;
        } else {
            $last_id = 1;
        }
        if ((int)$_POST['delivery_charges'] <= 0) {
            $err_response = array();
            $err_response['error'] = 1;
            $err_response['alert_msg'] = "Invalid charges calculation. please check pickup city,delivery city and service type";
            echo json_encode($err_response);
            exit();
        }
        $customer_id = $_SESSION['customers'];
        $_POST['receiver_address'] = strip_tags(trim($_POST['receiver_address']));
        $_POST['receiver_address'] = htmlentities($_POST['receiver_address'], ENT_NOQUOTES);
        $_POST['receiver_address'] = str_replace("'", '"', $_POST['receiver_address']);

        $_POST['pickup_address'] = strip_tags(trim($_POST['pickup_address']));
        $_POST['pickup_address'] = htmlentities($_POST['pickup_address'], ENT_NOQUOTES);
        $_POST['pickup_address'] = str_replace("'", '"', $_POST['pickup_address']);

        $_POST['product_desc'] = strip_tags(trim($_POST['product_desc']));
        $_POST['product_desc'] = htmlentities($_POST['product_desc'], ENT_NOQUOTES);
        $_POST['product_desc'] = str_replace("'", '"', $_POST['product_desc']);

        $_POST['special_instruction'] = strip_tags(trim($_POST['special_instruction']));
        $_POST['special_instruction'] = htmlentities($_POST['special_instruction'], ENT_NOQUOTES);
        $order_date = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['order_date'])));

        $_POST['special_instruction'] = str_replace("'", '"', $_POST['special_instruction']);

        $net_amount = isset($_POST['net_amount']) ? $_POST['net_amount'] : 0;
        $special_charges = isset($_POST['special_charges']) ? $_POST['special_charges'] : 0;
        $total_charges = isset($_POST['total_charges']) ? $_POST['total_charges'] : 0;
        $fuel_surcharge = isset($_POST['fuel_surcharge']) ? $_POST['fuel_surcharge'] : 0;
        $fuel_surcharge_percentage = isset($_POST['fuel_surcharge_percentage']) ? $_POST['fuel_surcharge_percentage'] : 0;
        $flyer_qty = isset($_POST['flyer_qty']) ? $_POST['flyer_qty'] : 0;
        $ref_no = isset($_POST['ref_no']) ? $_POST['ref_no'] : '';
        $remail = isset($_POST['receiver_email']) ? $_POST['receiver_email'] : '';
        $insurance_type = isset($_POST['insurance_type']) ? $_POST['insurance_type'] : '';
        $gst_per = isset($_POST['total_gst']) ? $_POST['total_gst'] : 0;


        $zone_id = '';
        $origin = $_POST['origin'];
        $destination = $_POST['destination'];
        $zone_q = mysqli_query($con, "SELECT zone FROM zone_cities WHERE origin='" . $origin . "' AND  ( destination='" . $destination . "' or destination ='other' or destination ='others')  ");
        if (mysqli_num_rows($zone_q) > 0) {
            $zone_r = mysqli_fetch_array($zone_q);
            $zone_id = $zone_r['zone'];
        }
        $original_no = $_POST['receiver_phone'];
        $original_no  = preg_replace('/[^0-9]/s', '', $original_no);
        $pos0 = substr($original_no, 0, 1);
        if ($pos0 == '3') {
            $alterno = substr($original_no, 1);
            $alterno = '0' . $original_no;
            $original_no = $alterno;
        }
        $pos = substr($original_no, 0, 2);
        if ($pos == '03') {
            $alterno = substr($original_no, 1);
            $alterno = '92' . $alterno;
            $original_no = $alterno;
        }
        $insurance_type    = isset($_POST['insurance_type']) ? $_POST['insurance_type'] : '';
        $pickup_address_new    = isset($_POST['pickup_address_new']) ? $_POST['pickup_address_new'] : '';
        $insured_premium = isset($_POST['insured_premium']) ? $_POST['insured_premium'] : 0;
        $extra_charges = isset($_POST['extra_charges']) ? $_POST['extra_charges'] : 0;
        $excl_amount = isset($_POST['excl_amount']) ? $_POST['excl_amount'] : 0;
        $pft_amount = isset($_POST['pft_amount']) ? $_POST['pft_amount'] : 0;
        $ref_no = isset($_POST['ref_no']) ? $_POST['ref_no'] : 0;
        $inc_amount = isset($_POST['inc_amount']) ? $_POST['inc_amount'] : 0;
        $special_instruction = isset($_POST['special_instruction']) ? $_POST['special_instruction'] : '';
        $tracking_no = isset($_POST['tracking_no']) ? $_POST['tracking_no'] : '';
        $product_desc = isset($_POST['product_desc']) ? $_POST['product_desc'] : '';
        $scnic = isset($_POST['scnic']) ? $_POST['scnic'] : '';
        $insured_item_value = isset($_POST['insured_item_value']) ? $_POST['insured_item_value'] : 0;
        $product_type_id = isset($_POST['product_type_id']) ? $_POST['product_type_id'] : '';
        $map_latitude  = $_POST['latitude'];
        $map_longitude = $_POST['longitude'];
        $pickup_latitude = $_POST['pickup_latitude'];
        $pickup_longitude = $_POST['pickup_longitude'];

        $insert_qry = 'INSERT INTO `orders`(`id`,`sname`,`sbname`,`sphone`, `semail`, `sender_address`, `sstate`, `scity`, `szip`, `sfax`, `shipper_reference`, `rname`, `remail`, `rphone`, `receiver_address`,`rstate`, `rcity`, `rzip`, `rfax`, `eori_number`, `iec_number`,`google_address`,`pickup_date`,`price`,`collection_amount`,`order_date`,`order_time`,`payment_method`,`customer_id`,`origin`,`destination`,`tracking_no`,`weight`,`product_desc`,`special_instruction`,`quantity`,`product_id`, `order_type`,`ref_no`,`flyer_qty`,`excl_amount`,`pft_amount`,`inc_amount`,`order_type_booking`,`Pick_location`,`net_amount`, `grand_total_charges`,`special_charges`, `insured_item_value`, `scnic` ,`insured_premium`,`extra_charges`,`fuel_surcharge`,`fuel_surcharge_percentage`,`gst_per`,`booking_type`,`product_type_id`,`map_latitude`,`map_longitude`,`pickup_latitude`,`pickup_longitude`,`description`,`customer_currency`,`ref_1`,`ref_2`,`length`,`width`,`height`,`collection_date`,`collection_ready_time`,`vehicle_Type`,`order_booking_type`) VALUES ("' . $last_id . '","' . $_POST['fname'] . '","' . $_POST['bname'] . '","' . $_POST['mobile_no'] . '","' . $_POST['email'] . '","' . $_POST['pickup_address'] . '","' . $_POST['sstate'] . '","' . $_POST['scity'] . '","' . $_POST['szip'] . '","' . $_POST['sfax'] . '","' . $_POST['shipper_reference'] . '","' . $_POST['receiver_name'] . '","' . $remail . '","' . $original_no . '","' . $_POST['receiver_address'] . '","' . $_POST['rstate'] . '","' . $_POST['rcity'] . '","' . $_POST['rzip'] . '","' . $_POST['rfax'] . '","' . $_POST['eori_number'] . '","' . $_POST['iec_number'] . '","' . $_POST['google_address'] . '", "' . $date . '","' . $_POST['delivery_charges'] . '","' . $_POST['collection_amount'] . '","' . $order_date . '","' . $date . '","CASH","' . $customer_id . '","' . $_POST['origin'] . '","' . $_POST['destination'] . '","' . $tracking_no . '","' . $_POST['weight'] . '","' . $product_desc . '","' . $special_instruction . '" ,"' . $_POST['quantity'] . '","' . $_POST['product_id'] . '", "' . $_POST['order_type'] . '","' . $ref_no . '","' . $flyer_qty . '","' . $excl_amount . '","' . $pft_amount . '","' . $inc_amount . '",4,"' . $pickup_address_new . '","' . $net_amount . '","' . $total_charges . '","' . $special_charges . '","' . $insured_item_value . '","' . $scnic . '" ,"' . $insured_premium . '","' . $extra_charges . '","' . $fuel_surcharge . '","' . $fuel_surcharge_percentage . '","' . $gst_per . '",1,"' . $product_type_id . '","' . $map_latitude . '","' . $map_longitude . '","' . $pickup_latitude . '","' . $pickup_longitude . '","' . $_POST['description'] . '","' . $_POST['customer_currency'] . '","' . $_POST['ref_1'] . '","' . $_POST['ref_2'] . '","' . $_POST['length'] . '","' . $_POST['width'] . '","' . $_POST['height'] . '","' . $_POST['collection_date'] . '","' . $_POST['collection_ready_time'] . '","' . $_POST['vehicle_Type'] . '",1)';


        $next_number = 0;
        $query = mysqli_query($con, $insert_qry);
        $insert_id = mysqli_insert_id($con);
        if ($insert_id == 0) {
            $err_response = array();
            $err_response['error'] = 1;
            $err_response['alert_msg'] = 'Error please try again!..';
            echo json_encode($err_response);
            exit();
        }
        if ($insert_id > 0) {
            if (isset($_POST['c_i_discription']) && !empty($_POST['c_i_discription'])) {
                $c_i_discription = $_POST['c_i_discription'];
                $c_i_pieces = $_POST['c_i_pieces'];
                $c_i_price = $_POST['c_i_price'];
                $c_i_coo = $_POST['c_i_coo'];
                $c_i_hs_code = $_POST['c_i_hs_code'];
                $c_i_hs_total = $_POST['c_i_hs_total'];
                foreach ($c_i_discription as $key => $value) {
                    if ($value != '' or $c_i_pieces[$key] or $c_i_price[$key] or $c_i_coo[$key] or $c_i_hs_code[$key] or $c_i_hs_total[$key]) {
                        $insert_comercial_invoice = "INSERT INTO `order_commercial_invoice`(`order_id`,`c_i_discription`,`c_i_pieces`,`c_i_price`, `c_i_coo`, `c_i_hs_code`, `c_i_hs_total`) VALUES ('" . $insert_id . "','" . $value . "','" . $c_i_pieces[$key] . "','" . $c_i_price[$key] . "','" . $c_i_coo[$key] . "','" . $c_i_hs_code[$key] . "','" . $c_i_hs_total[$key] . "') ";
                        mysqli_query($con, $insert_comercial_invoice);
                    }
                }
            }
            if (isset($_POST['origin_area_id']) && !empty($_POST['origin_area_id'])) {
                mysqli_query($con, "UPDATE orders set origin_area_id=" . $_POST['origin_area_id'] . "  WHERE id = " . $insert_id);
            }
            $cus_sql = mysqli_query($con, "SELECT * FROM customres WHERE id=" . $customer_id);
            $cus_data = mysqli_fetch_assoc($cus_sql);
            $customer_branch = isset($cus_data['branch_id']) ? $cus_data['branch_id'] : 1;
            mysqli_query($con, "UPDATE orders set booking_branch=" . $customer_branch . ", current_branch = 1 WHERE id = " . $insert_id);

            $listcharges = mysqli_query($con, "SELECT * FROM charges");
            if (isset($listcharges) && !empty($listcharges)) {
                while ($row_charge = mysqli_fetch_array($listcharges)) {
                    $row_id = $row_charge['id'];
                    $dynamic_var_c_id = 'charge_id' . $row_id;
                    if (isset($_POST[$dynamic_var_c_id])) {
                        $dynamic_var_c_type = 'charge_type' . $row_id;
                        $dynamic_var_c_amnt = 'charge_amount' . $row_id;
                        $charge_type   = $_POST[$dynamic_var_c_type];
                        $charge_amount = $_POST[$dynamic_var_c_amnt];
                        $charge_id     = $_POST[$dynamic_var_c_id];
                        if ($charge_amount > 0) {
                            mysqli_query($con, "INSERT INTO order_charges(`charges_id`,`charges_type`,`charges_amount`,`order_id`,`created_on`) VALUES ('" . $charge_id . "', '" . $charge_type . "', '" . $charge_amount . "', '" . $insert_id . "','" . $date . "') ");
                        }
                    }
                }
            }
            $next_number = 0;
            $custom_track_numbers = getConfig('custom_track_numbers');
            if (isset($custom_track_numbers) && $custom_track_numbers == 1) {
                $next_number = custom_track_numbers($customer_id);
            }
            if (isset($customer_data['is_order_manual']) and $customer_data['is_order_manual'] == 1) {
                $track_no = $_POST['track_no'];
            } elseif ($next_number > 0) {
                $track_no = $next_number;
            } else {
                $track_no = $insert_id + 6000000;
            }

            $barcode = rand(1000000, 9999999);
            $barcode = substr($barcode, 0, strlen($barcode) - strlen($insert_id));
            $barcode .= $insert_id;
            $barcode_image = getBarCodeImage($track_no, null, $track_no);


            /**
             * Walk in Customer
             * Change status to Parcel Received at Office
             * else Order is Booked
             *
             */

            // if(isset($customer_id) and $customer_id == 1)
            // {
            // 	$status_update = 'Parcel Received at office';
            // 	mysqli_query($con, "UPDATE orders SET status = '".$status_update."'  WHERE id = $last_id");
            // }else{
            // }
            $status_update = 'New Booked';


            mysqli_query($con, "UPDATE orders SET barcode = '" . $track_no . "', barcode_image = '" . $barcode_image . "', track_no = '" . $track_no . "' WHERE id = $insert_id ");
            mysqli_query($con, "INSERT INTO order_logs(`order_no`,`order_status`,`location`,`created_on`) VALUES ('" . $track_no . "', '" . $status_update . "', '" . $_POST['origin'] . "','" . $date . "') ");

            $iddd = encrypt($insert_id . "-usUSMAN767###");
            ///////////send Email 
            include_once "admin/email/sendEmail/customer_booking.php";
            email_customer_booking($customer_id, $insert_id);
            ///////////Send SMS if Walk in Customer
            // 
            ///////////////////////SMS////////////////////////

            sendSmsMobileGateWay($track_no, 'Customer Booking');


            if (isset($_POST['submit_order']) && $_POST['submit_order'] == '1') {
                ob_clean();
                echo json_encode(['id' => $insert_id, 'print' => 1, 'track_no' => $track_no]);
                exit();
            } else {
                ob_clean();
                echo json_encode(['id' => $insert_id, 'track_no' => $track_no]);
                exit();
            }
        }
    }
    exit();
}





//order process////////////////
$destination_cities_list = "<option  value='' >Select Destination</option>";
$origin_q = mysqli_query($con, " SELECT * FROM cities order by city_name asc");
$city_q = mysqli_query($con, "SELECT * FROM cities order by city_name asc");
while ($city_q_r = mysqli_fetch_array($city_q)) {
    $city = $city_q_r['city_name'];
    $destination_cities_list .= "<option  value='" . $city . "' >" . $city . "</option>";
}if (!function_exists('getProducts')) {
    function getProducts()
    {
        global $con;
        global $customer_type;
        $cus_pro_ids = '';
        $customerPaySql = "SELECT * FROM pay_mode WHERE account_type = '" . $customer_type . "'";

        $c_pay_mode_q = mysqli_query($con, $customerPaySql);
        $paymodeRes = mysqli_fetch_assoc($c_pay_mode_q);
        $customerPayMode = isset($paymodeRes['pay_mode']) ? $paymodeRes['pay_mode'] : '';
        $customerPayModeId = isset($paymodeRes['id']) ? $paymodeRes['id'] : '';
        $tariffSql =  "SELECT product_id FROM `tariff` Where pay_mode=" . $customerPayModeId;
        $tariffResult = mysqli_query($con, $tariffSql);
        while ($t_row = mysqli_fetch_array($tariffResult)) {
            $cus_pro_ids .= $t_row['product_id'] . ',';
        }
        $cus_pro_ids = rtrim($cus_pro_ids, ',');
        $all_products = [];
        $product_sql =  "SELECT * FROM `products` Where id IN(" . $cus_pro_ids . ") AND product_type='international' ORDER BY id DESC";
        //    echo $product_sql;
        //    die;
        $product_result = mysqli_query($con, $product_sql);
        while ($p_row = mysqli_fetch_array($product_result)) {
            if (isset($p_row['id'])) {
                $p_row = (object)$p_row;
                array_push($all_products, $p_row);
            }
        }
        return $all_products;
    }
}
$getProducts = getProducts();
$orders_sql = mysqli_query($con, "SELECT track_no FROM orders WHERE 1 ORDER BY id DESC LIMIT 1");
// $gst_query = mysqli_query($con, "SELECT value FROM config WHERE `name`='gst' ");
// $total_gst = mysqli_fetch_array($gst_query);
// $gst_percentage = 0;
// if (isset($customer_data['is_saletax']) && $customer_data['is_saletax'] == 1) {
//     $gst_percentage = isset($total_gst['value']) ? $total_gst['value'] : 0;
// }
$order_record = mysqli_fetch_array($orders_sql);
function encrypt($string)
{
    $key = "usmannnn";
    $result = '';
    for ($i = 0; $i < strlen($string); $i++) {
        $char = substr($string, $i, 1);
        $keychar = substr($key, ($i % strlen($key)) - 1, 1);
        $char = chr(ord($char) + ord($keychar));
        $result .= $char;
    }

    return base64_encode($result);
}
if (isset($_SESSION['customers'])) {
    include "includes/header.php";
    $page_title = 'Dashboard';
    $is_profile_page = true;
    $other_charges = mysqli_query($con, "SELECT * FROM charges");
    $insurance = mysqli_query($con, "SELECT * FROM insurance_type");
    $order_status = mysqli_query($con, "SELECT * FROM order_status");
    $admin_other_charges   = getconfig('customer_other_charges');
    $admin_extra_charges   = getconfig('customer_extra_charges');
    $admin_insured_premium = getconfig('customer_insured_premium');
    $profile_query = mysqli_query($con, "SELECT * FROM profiling WHERE customer_id=" . $customer_id . " ");
    ?>



    <style>
        a {
            text-decoration: none !important;
        }


        input::-webkit-input-placeholder,
        textarea::-webkit-input-placeholder {
            color: #b8b8b8 !important;
        }

        input:-moz-placeholder,
        textarea:-moz-placeholder {
            color: #b8b8b8 !important;
        }

        input::-moz-placeholder,
        textarea::-moz-placeholder {
            color: #b8b8b8 !important;
        }

        input:-ms-input-placeholder,
        textarea:-ms-input-placeholder {
            color: #b8b8b8 !important;
        }

        label {
            font-weight: bold;
        }

        .hide_city {
            display: none;
        }

        .btn-purple:hover,
        .btn-purple:focus {
            color: #fff !important;
        }
    </style>
    <section class="bg padding30">
        <div class="container-fluid dashboard">
            <div class="col-lg-2 col-md-3 col-sm-4 profile" style="margin-left:0">
                <!--sidebar come here!-->
                <?php
                include "includes/sidebar.php";
                ?>
            </div>
            <div class="col-lg-10 col-md-9 col-sm-8 dashboard">

                <div class=" shipper_box" style="padding: 10px 0;">

                    <form role="form" action="booking.php" method="POST" id="booking_form_new">

                        <input type="hidden" name="" class="total_gst" value="0">
                        <input type="hidden" name="active_customer_id" id="active_customer_id"
                        value="<?php echo $customer_id; ?>">

                        <div class="row services_type">
                            <div class="col-sm-4 sidegap">
                                <div class="form-group">
                                    <label><?php echo getLange('producttype'); ?> </label>
                                    <select class="form-control product_type_id" required="true" name="product_type_id">
                                        <!-- <option value="">--select--</option> -->
                                        <?php if (!empty($getProducts)) {
                                            foreach ($getProducts as $key1 => $p_single) { ?>
                                                <option value="<?php echo isset($p_single->id) ? $p_single->id : ''; ?>"
                                                    data-id="<?php echo isset($p_single->id) ? $p_single->id : ''; ?>">
                                                    <?php echo isset($p_single->name) ? $p_single->name : ''; ?>
                                                </option>
                                            <?php  }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 sidegap">
                                <div class="form-group">
                                    <label><?php echo getLange('servicetype'); ?> </label>
                                    <select class="form-control order_type" name="order_type" required="true">
                                        <option value="">--select--</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 left_right_none">
                                <div class="form-group">
                                    <label><?php echo getLange('orderdate'); ?> </label>
                                    <input type="text" value="<?php echo date('d/m/Y'); ?>" class="form-control datepicker"
                                    name="order_date">
                                </div>
                            </div>

                            <?php if (isset($customer_data['is_order_manual']) and $customer_data['is_order_manual'] == 1) : ?>
                                <div class="col-sm-2 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('orderno'); ?> </label>
                                        <input type="text" name="track_no" value="" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 pickup_details_padd">
                                <div class="panel panel-default">
                                    <div class="panel-heading"><?php echo getLange('pickupdetail'); ?> <span
                                        style="float: right;" class="right_order"></span></div>
                                        <div class="panel-body pickup_details">
                                            <div class="col-sm-4 padd_left">
                                               <div class="form-group">
                                                <label><span
                                                    style="color: red;">*</span>Country
                                                </label>
                                                <input type="hidden" name="origin_branch" class="origin_branch_id"
                                                value="0">
                                                <select
                                                class="form-control origin origin_cal origin_cha js-example-basic-single"
                                                name="origin">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 padd_left">
                                        <div class="form-group">
                                            <label><span
                                                style="color: red;">*</span>State
                                            </label> 
                                            <select
                                            class="form-control origin_state js-example-basic-single"
                                            name="sstate">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 padd_left">
                                    <div class="form-group">
                                        <label><span
                                            style="color: red;">*</span>City
                                        </label> 
                                        <select
                                        class="form-control origin_city js-example-basic-single"
                                        name="scity" required>
                                    </select>
                                </div>
                            </div>
                                    <!-- <div class="col-sm-8 padd_left padd_right booking_box"
                                        style="padding-bottom: 15px;">
                                        <label> Select Profile</label>
                                        <select class="form-control select_profile js-example-basic-single">
                                            <option selected disabled>Select Profile</option>
                                            <?php while ($rec = mysqli_fetch_array($profile_query)) { ?>
                                            <option value="<?php echo $rec['id']; ?>">
                                                <?php echo $rec['shipper_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div> -->
                                    <div class="col-sm-6 padd_left">
                                        <div class="form-group">
                                            <label class="control-label"><span style="color: red;">*</span>
                                                <?php echo getLange('name'); ?> </label>
                                                <input type="hidden" name="bname"
                                                value="<?php echo isset($customer_data['bname']) ? $customer_data['bname'] : '';  ?>">
                                                <input type="text" class="form-control bname"
                                                value="<?php echo isset($customer_data['fname']) ? $customer_data['fname'] : '';  ?>"
                                                name="fname"
                                                placeholder="<?php echo getLange('shipper') . ' ' . getLange('name'); ?>"
                                                required="true"
                                                <?php echo isset($customer_id) && $customer_id != 1 ? 'readonly' : ''; ?>>
                                            </div>

                                        </div>
                                        <div class="col-sm-6 padd_left">
                                            <div class="form-group">
                                                <label class="control-label"><span style="color: red;">*</span>
                                                    <?php echo getLange('phone'); ?> </label>
                                                    <input type="text" class="form-control shipper_phone"
                                                    value="<?php echo isset($customer_data['mobile_no']) ? $customer_data['mobile_no'] : '';  ?>"
                                                    name="mobile_no"
                                                    placeholder="<?php echo getLange('shipper') . ' ' . getLange('phone'); ?>"
                                                    required="true"
                                                    <?php echo isset($customer_id) && $customer_id != 1 ? 'readonly' : ''; ?>>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 padd_left" >
                                                <div class="form-group">
                                                    <label class="control-label"><span
                                                        style="color: red;">*</span><?php echo getLange('email'); ?>
                                                    </label>
                                                    <input type="email"
                                                    value="<?php echo isset($customer_data['email']) ? $customer_data['email'] : '';  ?>"
                                                    class="form-control shipper_email" name="email"
                                                    placeholder="<?php echo getLange('shipper') . ' ' . getLange('email'); ?>"
                                                    required="true"
                                                    <?php echo isset($customer_id) && $customer_id != 1 ? 'readonly' : ''; ?>>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-lg-6 padd_left" >
                                                <div class="form-group">
                                                    <label class="control-label">Shipper's Reference</label>
                                                    <input autocomplete="false" type="text" class="form-control"
                                                    name="shipper_reference" placeholder="Shipper Reference">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6 col-lg-6 padd_left" >
                                                    <div class="form-group">
                                                        <label class="control-label"> Zip</label>
                                                        <input type="text" class="form-control"
                                                        value="<?php echo isset($customer_data['zip']) ? $customer_data['zip'] : '';  ?>"
                                                        name="szip" placeholder="Zip">
                                                    </div>
                                                </div>

                                                <div class="col-sm-12 col-lg-6 padd_left" >
                                                    <div class="form-group">
                                                        <label class="control-label">Fax</label>
                                                        <input autocomplete="false" type="text" class="form-control"
                                                        name="sfax" placeholder="Fax">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 padd_left" >
                                                    <div class="form-group">
                                                        <label class="control-label"><span style="color: red;">*</span>
                                                            <?php echo getLange('address'); ?></label>
                                                            <textarea class="form-control pickup_address" name="pickup_address"
                                                            placeholder="<?php echo getLange('shipper') . ' ' . getLange('address'); ?>"
                                                            required="true"
                                                            <?php echo isset($customer_id) && $customer_id != 1 ? 'readonly' : ''; ?>><?php echo isset($customer_data['address']) ? $customer_data['address'] : '';  ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12 padd_left" >
                                                        <div class="form-group">
                                                            <label class="control-label"><?php echo getLange('pickupaddress'); ?>
                                                        </label>
                                                        <textarea autocomplete="false" class="form-control pickup_address_new"
                                                        name="pickup_address_new"
                                                        placeholder="<?php echo getLange('pickupaddress'); ?>"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="panel panel-default">
                                        <div class="panel-heading"><?php echo getLange('deliverydetail'); ?> <span
                                            style="float: right;" class="right_order"></span></div>
                                            <div class="panel-body">

                                                <div class="row">
                                                    <div class="col-sm-3 padd_left ">
                                                        <div class="form-group">
                                                            <label class="control-label"><span style="color: red;">*</span>
                                                            Country</label>
                                                            <select
                                                            class="form-control destination js-example-basic-single"
                                                            name="destination">
                                                       <!--  <?php while ($row = mysqli_fetch_array($destination_country_q)) { ?>
                                                            <option value="<?php echo $row['id']; ?>"
                                                                <?php if ($row['id'] == $customer_country) {
                                                                    echo "selected";
                                                                } ?>>
                                                                <?php echo $row['country_name']; ?></option>
                                                                <?php } ?>> -->
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 padd_left ">
                                                        <div class="form-group">
                                                            <label class="control-label"><span style="color: red;">*</span>
                                                            State</label>
                                                            <select
                                                            class="form-control destination_state js-example-basic-single"
                                                            name="rstate">
                                                            <!-- <?php while ($row = mysqli_fetch_array($destination_state_q)) { ?>
                                                                <option value=" <?php echo $row['id']; ?></option>"
                                                                <?php if ($row['id'] == $customer_country) {
                                                                    echo "selected";
                                                                } ?>>
                                                                <?php echo $row['state_name']; ?></option>
                                                                <?php } ?>> -->
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 padd_left padd_right">
                                                        <div class="form-group">
                                                            <label class="control-label"><span style="color: red;">*</span>
                                                                City<a href="#" data-toggle="modal"
                                                                data-target="#exampleModal"> <!-- <i
                                                                class="fa fa-plus"></i> --></a></label>
                                                                <select
                                                                class="form-control destination_city  js-example-basic-single"
                                                                name="rcity" required>
                                                                <!-- <option value="0"><?php echo getLange('select'); ?></option>
                                                                    <?php echo $destination_cities_list; ?> -->
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <?php if (getConfig('manual_area') == 1) { ?>
                                                            <div class="col-sm-4 padd_left">
                                                                <div class="form-group">
                                                                    <label class="control-label"><span style="color: red;">*</span>
                                                                        <?php echo getLange('areas'); ?></label>
                                                                        <select class="form-control area origin_select js-example-basic-single"
                                                                        name="origin_area_id">
                                                                        <option value=""><?php echo getLange('select'); ?></option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                        <div class="col-sm-6 padd_left">
                                                            <div class="form-group">
                                                                <label class="control-label"><span
                                                                    style="color: red;">*</span><?php echo getLange('name'); ?>
                                                                </label>
                                                                <input type="text" class="form-control" name="receiver_name"
                                                                placeholder="<?php echo getLange('consignee') . ' ' . getLange('name'); ?>"
                                                                required="true">
                                                            </div>
                                                        </div>

                                        <!-- <div class="col-sm-4 padd_left" >
                                            <div class="form-group">
                                                <label class="control-label"><span
                                                        style="color: red;">*</span><?php echo getLange('phone'); ?>
                                                </label>
                                                <div class="phone_search"
                                                    style="position: absolute;right: 22px;top: 34px;">
                                                    <i class="fa fa-search search_phone"></i>
                                                </div>
                                                <input type="text" class="form-control" name="receiver_phone"
                                                    placeholder="<?php echo getLange('consignee') . ' ' . getLange('phone'); ?>"
                                                    required="true">
                                                <div class="r_phone_msg"></div>
                                            </div>
                                        </div> -->

                                        <div class="col-sm-4 padd_left">
                                            <div class="form-group">
                                                <label class="control-label"><span
                                                    style="color: red;"></span><?php echo getLange('email'); ?>
                                                </label>
                                                <input type="email" class="form-control" name="receiver_email"
                                                placeholder="<?php echo getLange('consignee') . ' ' . getLange('email'); ?>">
                                            </div>
                                        </div>
                                        <!-- <div class="col-sm-8 " id="phone_code">
                                            <label><span
                                                style="color: red;">*</span><?php echo getLange('phone'); ?></label>
                                                <input id="phone" type="tel">
                                                <span class="default_number">+92</span>
                                                <img class="flag_default" src="img/pkr-flag.jpg">
                                                <span id="valid-msg" class="hide">Valid</span>
                                                <span id="error-msg" class="hide">Invalid number</span>
                                                <div class="phone_search" style="position: absolute;right: 22px;top: 34px;">
                                                    <i class="fa fa-search search_phone"></i>
                                                </div>
                                                <input type="text" id="phoneno" name="receiver_phone"
                                                placeholder="<?php echo getLange('consignee') . ' ' . getLange('phone'); ?>"
                                                required="true">
                                                <div class="r_phone_msg"></div>
                                            </div> -->
                                            <div class="col-sm-4 padd_left">
                                                <div class="form-group">
                                                    <label class="control-label"><span
                                                        style="color: red;"></span><?php echo getLange('phone'); ?>
                                                    </label>
                                                    <input type="text" class="form-control" name="receiver_phone"
                                                    placeholder="Phone Number">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-lg-4 padd_left" >
                                                <div class="form-group">
                                                    <label class="control-label"> Zip</label>
                                                    <input type="text" class="form-control" name="rzip"
                                                    placeholder="Zip">
                                                </div>
                                            </div>

                                            <div class="col-sm-6 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Fax</label>
                                                    <input autocomplete="false" type="text" class="form-control"
                                                    name="rfax" placeholder="Fax">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                           <div class="col-sm-6 col-lg-6 padd_left" >
                                            <div class="form-group">
                                                <label class="control-label">EORI Number</label>
                                                <input autocomplete="false" type="text" class="form-control"
                                                name="eori_number" placeholder="EORI Number">
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-lg-6">
                                            <div class="form-group">
                                                <label class="control-label">IEC Number</label>
                                                <input autocomplete="false" type="text" class="form-control"
                                                name="iec_number" placeholder="IEC Number">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 padd_left">
                                            <div class="form-group">
                                                <label> <span style="color: red;">*</span>
                                                    <?php echo getLange('receiver'); ?>
                                                    <?php echo getLange('address'); ?> </label>
                                                    <textarea placeholder="Receiver Address" class="form-control"
                                                    name="receiver_address" id="receiver_address"
                                                    required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 padd_left padd_right">
                                                <div class="form-group">
                                                    <label><span style="color: red;">*</span>
                                                        <?php echo getLange('consignee') . ' ' . getLange('address'); ?>
                                                    </label>
                                                    <textarea
                                                    placeholder="<?php echo getLange('consignee') . ' ' . getLange('address'); ?>"
                                                    class="form-control" id="google_address" name="receiver_address"
                                                    required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 padd_left padd_right">
                                                <div class="form-group">
                                                    <label class="control-label">
                                                        <?php echo getLange('googleaddress'); ?></label>
                                                        <input autocomplete="false" name="google_address"
                                                        class="address form-control" type="text" value="" id="property_add"
                                                        placeholder="<?php echo getLange('googleaddress'); ?>">
                                                        <i aria-hidden="true" className="dot circle outline link icon"
                                                        id="location-button"></i>
                                                        <input type="hidden" class="form-control" id="latitude" name="latitude">
                                                        <input type="hidden" class="form-control" id="longitude"
                                                        name="longitude">
                                                        <input type="hidden" class="form-control pickup_latitude"
                                                        name="pickup_latitude"
                                                        value="<?php echo $customer_data['customer_longitude']; ?>">
                                                        <input type="hidden" class="form-control pickup_longitude"
                                                        name="pickup_longitude"
                                                        value="<?php echo $customer_data['customer_latitude']; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">

                                                <div class="col-sm-12 padd_left padd_right">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="mapping" id="mapping"
                                                                style="width: 100%; height: 143px;margin-bottom: 10px;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 sidegap" style="padding: 0;">
                                <div class="row">
                                    <div class="col-sm-6 ful_width_box" style="padding-right: 0;">
                                        <div class="panel panel-default" style="padding-bottom: 0;">
                                            <div class="panel-heading"><?php echo getLange('shipmentdetail'); ?> <span
                                                style="float: right;" class="right_order"></span></div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label><span style="color: red;"></span>Customer
                                                                Currency</label>
                                                                <input class="form-control" name="customer_currency">
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3 width_33">
                                                            <div class="form-group">
                                                                <label> <?php echo getLange('refernceno'); ?> .</label>
                                                                <input type="text" name="ref_no" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3 ">
                                                            <div class="form-group">
                                                                <label> Ref 1.</label>
                                                                <input type="text" name="ref_1" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3 ">
                                                            <div class="form-group">
                                                                <label>Ref 2.</label>
                                                                <input type="text" name="ref_2" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3 padd_right">
                                                            <div class="form-group">
                                                                <label> <?php echo getLange('orderid'); ?> .</label>
                                                                <input type="text" name="product_id" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3 ">
                                                            <div class="form-group">
                                                                <label class="calculation_label"><span style="color: red;">*</span><?php echo getLange('noofpiece'); ?></label>
                                                                <input type="myNumber" name="quantity" class="form-control pieces" required="true" value="1">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3 padd_left padd_right">
                                                            <div class="form-group">
                                                                <label class="calculation_label"><span style="color: red;">*</span>   <?php echo getLange('weightkg'); ?></label>
                                                                <input type="myNumber" name="weight" class="form-control weight"  required="true" value="0.5">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3 padd_left">
                                                            <div class="form-group"> <label><span
                                                                style="color: red;">*</span><?php echo getLange('codamount'); ?></label>
                                                                <input type="text" name="collection_amount"
                                                                class="form-control allownumericwithdecimal" required="true"
                                                                value="0">
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-sm-12 padd_left">
                                                                <div class="form-group">
                                                                    <div class="col-sm-3">
                                                                        <label class="calculation_label"><span
                                                                            style="color: red;">*</span>Cubic(L * W *
                                                                        H)cm</label>
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <label class="calculation_label">Length</label>
                                                                        <input type="number" name="length"
                                                                        class="form-control length" required="true"
                                                                        value="0">
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <label class="calculation_label">Width</label>
                                                                        <input type="number" name="width"
                                                                        class="form-control width" required="true"
                                                                        value="0">
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <label class="calculation_label">Height</label>
                                                                        <input type="number" name="height"
                                                                        class="form-control height" required="true"
                                                                        value="0">
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            <div class="col-sm-12 col-lg-6">
                                                                <div class="form-group">
                                                                    <label><span
                                                                        style="color: red;"></span>Description</label>
                                                                        <textarea class="form-control"
                                                                        name="description"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6 padd_left">
                                                                    <div class="form-group">
                                                                        <label><span
                                                                            style="color: red;">*</span><?php echo getLange('itemdetail'); ?>
                                                                        </label>
                                                                        <textarea class="form-control" name="product_desc"
                                                                        required="true"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12 col-lg-12">
                                                                    <div class="form-group">
                                                                        <label><span style="color: red;"></span>
                                                                            <?php echo getLange('specialinstruction'); ?> </label>
                                                                            <textarea class="form-control"
                                                                            name="special_instruction"></textarea>
                                                                        </div>
                                                                    </div>  
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <div class="panel panel-default">
                                                                            <div class="panel-heading">Pickup Request</div>
                                                                            <!-- <div class="panel-body booking_row"> -->
                                                                                <div class="panel-body  ">
                                                                                    <div class="row">
                                                                                        <div class="col-sm-4 padd_left">
                                                                                            <div class="form-group">
                                                                                                <label
                                                                                                class="calculation_label">Collection
                                                                                            Date</label>
                                                                                            <input type="text"
                                                                                            class="form-control datetimepicker4"
                                                                                            name="collection_date"
                                                                                            value="<?php echo date('Y-m-d') ?>">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-4 padd_left">
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                            class="calculation_label">Collection
                                                                                        Ready Time</label>
                                                                                        <input type="time"
                                                                                        name="collection_ready_time"
                                                                                        class="form-control"
                                                                                        value="<?php echo date('H:i'); ?>">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-4 padd_left">
                                                                                    <div class="form-group">
                                                                                        <label class="calculation_label">Vehicle
                                                                                        Type</label>
                                                                                        <input type="text" name="vehicle_Type"
                                                                                        class="form-control">
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <?php if ($admin_insured_premium == 1) { ?>
                                                                <div class="col-sm-6 padd_left">
                                                                    <div class="form-group">
                                                                        <span style="color: red;">*</span>
                                                                        <lab el class="calculation_label">
                                                                            <?php echo getLange('fragile'); ?>
                                                                        </label>
                                                                        <select
                                                                        class="form-control insurance_rate is_fragile js-example-basic-single"
                                                                        name="insurance_type">
                                                                        <option value="" selected disabled="true">Select
                                                                            Insurance
                                                                        Type</option>
                                                                        <?php while ($insuranceval = mysqli_fetch_array($insurance)) { ?>
                                                                            <option value="<?php echo $insuranceval['id'] ?>"
                                                                                id="insurancedata<?php echo $insuranceval['id'] ?>"
                                                                                data-attr="<?php echo $insuranceval['rate'] ?>">
                                                                                <?php echo $insuranceval['name']; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6 padd_left padd_right">
                                                                    <div class="form-group">
                                                                        <label class="calculation_label"><span
                                                                            style="color: red;">*</span><?php echo getLange('insureditemdeclare'); ?>
                                                                        </label>
                                                                        <input type="number" name="insured_item_value"
                                                                        class="form-control insurance_rate insured_item_value"
                                                                        required="true" value="0">
                                                                    </div>
                                                                </div>
                                                            <?php } ?>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if ($admin_other_charges == '1') : ?>
                                                <?php if ($admin_other_charges == '1') : ?>
                                                    <div class="col-sm-6" id="charges_table" style="padding: 0 15px;">
                                                        <div class="panel panel-default" style="padding-bottom: 62px;">
                                                            <div class="panel-heading"><?php echo getLange('charges'); ?> <button
                                                                class="button-main">Apply
                                                            Tariff</button>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-sm-12 " style="padding: 0;">
                                                                    <div class="form-group">
                                                                        <div class="auto_scroll">
                                                                            <table class="table_box">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <th><?php echo getLange('charges'); ?></th>
                                                                                        <th style="width: 42px;"></th>
                                                                                        <th><?php echo getLange('amount'); ?></th>
                                                                                    </tr>
                                                                                    <?php
                                                                                    if (isset($other_charges) && !empty($other_charges)) { // $total_amount_calc = 0;
                                                                                        while ($row = mysqli_fetch_array($other_charges)) {
                                                                                            $c_id = $row['id']; //
                                                                                            if (isset($row['customer_id']) && $row['customer_id'] == $customer_id) {
                                                                                                $row['charge_value'] = 0;
                                                                                            }
                                                                                            $total_amount_calc += $row['charge_value'];
                                                                                            ?>
                                                                                            <tr>
                                                                                                <td>
                                                                                                    <input type="hidden"
                                                                                                    value="<?php echo $c_id; ?>"
                                                                                                    name="charge_id<?php echo $c_id; ?>">
                                                                                                    <input type="hidden"
                                                                                                    value="<?php echo $row['charge_type']; ?>"
                                                                                                    name="charge_type<?php echo $c_id; ?>">
                                                                                                    <span>
                                                                                                        <?php echo isset($row['charge_name']) ? $row['charge_name'] : ''; ?>
                                                                                                    </span>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <input type="checkbox" checked="true"
                                                                                                    disabled="true"
                                                                                                    class="change_charges"
                                                                                                    value="<?php echo $c_id; ?>"
                                                                                                    data-charges="<?php echo $row['charge_value'] ?>">
                                                                                                </td>
                                                                                                <td>
                                                                                                    <div class="input-group">
                                                                                                        <input disabled
                                                                                                        data-type="<?php echo $row['charge_type'] ?>"
                                                                                                        type="text"
                                                                                                        class="form-control other_charges <?php echo $c_id; ?>"
                                                                                                        value="<?php echo $row['charge_value']; ?>"
                                                                                                        name="charge_amount<?php echo $c_id; ?>">
                                                                                                        <span style=" padding: 2px;"
                                                                                                        class="input-group-addon">
                                                                                                    </span>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    <?php }
                                                                                } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-lg-12 padding-all">
                                                                            <div class="input_label_box main-box-x">
                                                                                <label><?php echo getLange('specialcharges'); ?></label>
                                                                                <input type="text"
                                                                                class="form-control special_charges"
                                                                                name="special_charges" value="0" disabled>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <div class="col-sm-6 width-30">

                                            <?php else : ?>
                                                <div class="col-sm-6">
                                                <?php endif; ?>
                                                <div class="panel panel-default" style="padding-bottom: 21px;">
                                                    <div class="panel-heading"><?php echo getLange('priceinformation'); ?>
                                                    <span style="float: right;" class="right_order"></span>
                                                </div>
                                                <div class="panel-body">
                                                <!-- <div class="row"> <div class="col-sm-12 side_gap">
                               <div class="form-group"> <label> <input style="    width: auto !important;"
                               type="checkbox" name="manual_rate" class="manual_rates" value="0"> Manual
                           Rates </label> </div> </div> </div> -->
                           <div class="row">
                            <div class="col-sm-4 padd_left
                            ">
                            <div class="form-group"> <label class="calculation_label"><span
                                style="color: red;">*</span>
                                <?php echo getLange('deliveycharges'); ?></label> <input
                                type="text" name="delivery_charges" class="form-control total_amount
                                allownumericwithdecimal" disabled> </div>
                            </div>
                            <?php if ($admin_other_charges == '1') : ?>
                                <div class="col-sm-4 padd_left
                                ">
                                <div class="form-group">
                                    <label><?php echo getLange('specialcharges'); ?></label>
                                    <input type="text" class="form-control special_charges"
                                    name="special_charges" value="0" disabled>
                                </div>
                            </div>
                            <?php else : ?>
                                <input type="hidden" class="form-control" name="special_charges"
                                value="0" disabled>
                            <?php endif; ?>
                            <?php if ($admin_extra_charges == '1') : ?>
                                                    <!--  <div class="col-sm-12 side_gap">
                                   <div class="form-group">
                                     <label class="calculation_label"><span style="color: red;">*</span><?php echo getLange('extracharges'); ?> </label>
                                     <input type="number" name="extra_charges" class="form-control extra_charges" required="true" value="0" disabled>
                                  </div>
                              </div> -->
                              <?php else : ?>
                                <!-- <input type="hidden" name="extra_charges" class="form-control extra_charges" required="true" value="0"disabled > -->
                            <?php endif; ?>
                            <?php if ($admin_insured_premium == '1') : ?>
                                <div class="col-sm-4 padd_left">
                                    <div class="form-group">
                                        <label class="calculation_label"><span
                                            style="color: red;">*</span><?php echo getLange('insurancepremium'); ?>
                                        </label>
                                        <input type="number" name="insured_premium"
                                        class="form-control insurance_value" required="true"
                                        value="0" disabled>
                                    </div>
                                </div>
                                <?php else : ?>
                                    <input type="hidden" name="insured_premium"
                                    class="form-control insurance_value" required="true" value="0"
                                    disabled>
                                <?php endif; ?>
                                <div class="col-sm-4 padd_left">
                                    <div class="form-group">
                                        <label>
                                            <?php echo getLange('totalcharges'); ?>
                                        </label>
                                        <input type="text" name="total_charges" value="0"
                                        readonly="true" class="form-control allownumericwithdecimal
                                        total_charges" required="true">
                                    </div>
                                </div>
                                <div class="col-sm-4 padd_left">
                                    <div class="form-group">
                                        <?php

                                        $fuelsurcharge_percent = getFuelValue($customer_id);
                                                                // if(isset($customer_data['is_fuelsurcharge']) && $customer_data['is_fuelsurcharge'] == 0)
                                                                // {
                                                                // 	$fuelsurcharge_percent = 0;
                                                                // }
                                        $fuelsurcharge_percent = isset($fuelsurcharge_percent) ? $fuelsurcharge_percent : 0;
                                        ?>
                                        <input type="hidden" name="fuel_surcharge_percentage"
                                        class="fuel_surcharge_percentage"
                                        value="<?php echo $fuelsurcharge_percent; ?>">
                                        <label>
                                            <?php echo getLange('fuelsurcharge'); ?>
                                            (<?php echo $fuelsurcharge_percent; ?>%)
                                        </label>
                                        <input type="text" name="fuel_surcharge" value="0"
                                        readonly="true" class="form-control allownumericwithdecimal
                                        fuel_surcharge" required="true">
                                    </div>
                                </div>
                                <div class="col-sm-4 padd_left">
                                    <div class="form-group">
                                        <label>
                                            <span style="color: red;">*</span>
                                            <?php echo getLange('salestax'); ?>
                                            </label> <?php if ($checkGstExempt == 1) {
                                                ?> <input type="text" name="pft_amount"
                                                value="0" readonly="true" class="form-control">
                                                <?php   } else {   ?> <input type="text" name="pft_amount"
                                                value="0" readonly="true" class="form-control allownumericwithdecimal
                                                pft_amount" required="true"> <?php } ?>
                                            </div>
                                        </div>
                                        <div
                                        class="col-sm-4 padd_left input_label_box main-box-x total_charged">
                                        <label><?php echo getLange('netamount'); ?></label>
                                        <input type="text" class="form-control" readonly="true"
                                        name="net_amount" value="0">
                                    </div>
                                    <div class="col-sm-12 side_gap" style="display:none">
                                        <div class="form-group"> <label><span style="color:
                                        red;">*</span><?php echo getLange('totalservicescharges'); ?></label> <input
                                        type="text" value="0" name="inc_amount" readonly="true"
                                        class="form-control allownumericwithdecimal
                                        inc_amount" required="true"> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">Commercial Invoice</div>
                                <!-- <div class="panel-body booking_row"> -->
                                    <div class="panel-body  ">
                                        <div class="row">
                                            <input type="hidden" class="count_array_commercial_invoice"
                                            value="0">
                                            <div class="col-sm-12 padd_left">
                                                <div class="form-group">
                                                    <label class="calculation_label">Discription</label>
                                                    <input type="text" class="form-control"
                                                    name="c_i_discription[0]">
                                                </div>
                                            </div>
                                            <div class="col-sm-2 padd_left">
                                                <div class="form-group">
                                                    <label class="calculation_label">Pieces</label>
                                                    <input type="text" name="c_i_pieces[0]"
                                                    class="form-control c_i_pieces" value="0">
                                                </div>
                                            </div>
                                            <div class="col-sm-2 padd_left">
                                                <div class="form-group">
                                                    <label class="calculation_label">Price</label>
                                                    <input type="text" name="c_i_price[0]"
                                                    class="form-control c_i_price" value="0">
                                                </div>
                                            </div>
                                            <div class="col-sm-2 padd_left">
                                                <div class="form-group">
                                                    <label class="calculation_label">COO</label>
                                                    <input type="text" name="c_i_coo[0]"
                                                    class="form-control" value="PK">
                                                </div>
                                            </div>
                                            <div class="col-sm-2 padd_left">
                                                <div class="form-group">
                                                    <label class="calculation_label">HS
                                                    Code</label>
                                                    <input type="text" name="c_i_hs_code[0]" value=""
                                                    placeholder="0000.0000"
                                                    class="form-control c_i_hs_code">
                                                </div>
                                            </div>
                                            <div class="col-sm-2 padd_left">
                                                <div class="form-group">
                                                    <label class="calculation_label">Total</label>
                                                    <input type="text" name="c_i_hs_total[0]" value="0"
                                                    class="form-control c_i_hs_total">
                                                </div>
                                            </div>
                                            <div class="col-sm-2 padd_left">
                                                <div class="form-group">
                                                    <a href="#" class="btn btn-info btn_commercial_invoice"
                                                    style="margin-top: 24px;margin-left: 36px;">+</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="plus_commercial_invoice"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='msgs'></div>
                </div>
                <input type="hidden" class="print_template" value="invoicehtml_new.php">
                <div class="save_print_btn">
                    <input type="submit" name="save_order" class="btn btn-purple submit_btns"
                    value="<?php echo getLange('save'); ?>">
                    <a href="#" class="submit_order_new btn btn-purple submit_btns btn-purple"><?php echo getLange('saveprint'); ?></a>
                </div>
                <!-- <input type="submit" name="submit_order" class="btn btn-purple submit_btns" value="Save & Print" > -->
            </div>
        </div>
    </form>
</div>

<style>
    .whitee {
        color: white !important;
    }

    .whitee:hover {
        color: white !important;
    }
</style>
</section>
</div>
<?php
} else {
    header("location:index.php");
}
?>
<script type="text/javascript">
    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',

    });

    function isNumberKey(txt, evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode == 46) {
        //Check if the text already contains the . character
        if (txt.value.indexOf('.') === -1) {
            return true;
        } else {
            return false;
        }
    } else {
        if (charCode > 31 &&
            (charCode < 48 || charCode > 57))
            return false;
    }
    return true;
}
</script>

<?php include 'includes/footer.php'; ?>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        $('body').on('keyup', 'input[type=text]', function(e) {
            $('input[type=text]').val(function() {
                return this.value.toUpperCase();
            })
        })
        $('body').on('keyup', 'input[type=email]', function(e) {
            $('input[type=email]').val(function() {
                return this.value.toUpperCase();
            })
        })
        $('body').on('keyup', 'input[type=myNumber]', function(e) {
            $('input[type=myNumber]').val(function() {
                return this.value.toUpperCase();
            })
        })
        $('body').on('keyup', 'textarea', function(e) {
            $('textarea').val(function() {
                return this.value.toUpperCase();
            })
        })
        $(document).ready(function() {
            var product_type_id = $('.product_type_id').find(':selected').val();
            var customer_id = $('#active_customer_id').val();
            getServiceTypes(product_type_id);
            $('body').on('change', '.product_type_id', function(event) {
                event.preventDefault();
                var product_type_id = $(this).find(':selected').val();
                getServiceTypes(product_type_id)
            });

            function getServiceTypes(product_type_id) {

                if (product_type_id) {
                    $.ajax({
                        url: 'getServiceType.php',
                        type: 'POST',
                        data: {
                            is_product: 1,
                            product_type_id: product_type_id,
                            customer_id: customer_id
                        },
                        success: function(response) {
                            var result = jQuery.parseJSON(response);
                            var service_types_options =
                            '<option value="">Select Service Type </option>';
                            var service_types = result.service;
                            for (var i in service_types) {
                                service_types_options += '<option value="' + service_types[i]
                                .id +
                                '">';
                                service_types_options += service_types[i].service_type;
                                service_types_options += '</option>';
                            }
                            $('body').find('.order_type').html(service_types_options);
                        }
                    });
                }
            }
        })
    });


    const api_key = '<?php echo getConfig("api_key") ?>';
    var placeSearch, autocomplete;
    var componentForm = {
    // street_number: 'short_name',
    // route: 'long_name',
    // locality: 'long_name',
    // administrative_area_level_1: 'short_name',
    // country: 'long_name',
    // postal_code: 'short_name'
};
// starting Navigator

// navigator.geolocation.getCurrentPosition(function(position) {
//         getUserAddressBy(position.coords.latitude, position.coords.longitude);
//         latitude = position.coords.latitude;
//         longitude = position.coords.longitude;
//         // console.log("ere latitude is" + latitude)
//         // console.log(" er e longitude is" + longitude)
//         initialize();
//     },
//     function(error) {
//         console.log("The Locator was denied :(")
//     })
// var locatorSection = document.getElementById("location-input-section")

// function init() {
//     var locatorButton = document.getElementById("location-button");
//     locatorButton.addEventListener("click", locatorButtonPressed)
// }

// function locatorButtonPressed() {
//     locatorSection.classList.add("loading")

//     navigator.geolocation.getCurrentPosition(function(position) {
//             getUserAddressBy(position.coords.latitude, position.coords.longitude)
//             document.getElementById('latitude').value = position.coords.latitude;
//             document.getElementById('longitude').value = position.coords.longitude;
//         },
//         function(error) {
//             locatorSection.classList.remove("loading")
//             alert("The Locator was denied :( Please add your address manually")
//         })
// }

function getUserAddressBy(lat, long) {

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var address = JSON.parse(this.responseText)
            // document.getElementById('property_add').value = address.results[0].formatted_address;
            // document.getElementById('google_address').value = address.results[0].formatted_address;
            // filladdress(address.results[0]);
            // document.getElementById('latitude').value = lat;
            // document.getElementById('longitude').value = long;

        }
    };
    xhttp.open("GET", "https://maps.googleapis.com/maps/api/geocode/json?latlng=" + lat + "," + long +
        "&key=" + api_key + "", true);
    xhttp.send();

}
// Ending Navigator

// var latitude = document.getElementById('latitude').value;
// var longitude = document.getElementById('longitude').value;

// console.log("latitude is" + latitude)
// console.log("longitude is" + longitude)

function initialize() {

    var latlng = new google.maps.LatLng(latitude, longitude);
    var map = new google.maps.Map(document.getElementById('mapping'), {
        center: latlng,
        zoom: 14
    });
    var marker = new google.maps.Marker({
        map: map,
        position: latlng,
        draggable: true,
        anchorPoint: new google.maps.Point(0, -29)
    });
    var input = document.getElementById('property_add');
    var geocoder = new google.maps.Geocoder();
    var autocomplete = new google.maps.places.Autocomplete(input);

    autocomplete.bindTo('bounds', map);
    var infowindow = new google.maps.InfoWindow();

    autocomplete.addListener('place_changed', function() {
        infowindow.close();
        marker.setVisible(false);
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
            return;
        }
        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);
        }
        marker.setPosition(place.geometry.location);
        marker.setVisible(true);

        bindDataToForm(place.formatted_address, place.geometry.location.lat(), place.geometry
            .location.lng());
        infowindow.setContent(place.formatted_address);
        infowindow.open(map, marker);
    });
    // this function will work on marker move event into map
    google.maps.event.addListener(marker, 'dragend', function() {
        geocoder.geocode({
            'latLng': marker.getPosition()
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    bindDataToForm(results[0].formatted_address, marker.getPosition().lat(),
                        marker
                        .getPosition().lng());
                    infowindow.setContent(results[0].formatted_address);
                    infowindow.open(map, marker);
                }
            }
        });
    });
}
// }, false);

function bindDataToForm(address, lat, lng) {
    document.getElementById('property_add').value = address;
    // document.getElementById('google_address').value = address;
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;
}
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('title').text($('title').text() + ' Booking');
    // $('body').find('.other_charges').val('0');
}, false);
</script>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        $('input[name=receiver_phone]').on('keyup', function(event) {
            event.preventDefault();
            if (event.key == 'Enter' || event.keyCode == 13) {
                event.preventDefault();
                getPhoneDetails();
            }
        });


        $(document).on('click', '.search_phone', function() {
            getPhoneDetails();
        })

        function getPhoneDetails() {
            var mobile_search = $('input[name=receiver_phone]').val();
            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                data: {
                    rphone_no: mobile_search
                },
                dataType: 'JSON',
                success: function(data) {
                    if (data.status === 1) {
                        $('input[name=receiver_name]').val(data.response.rname);
                        $('input[name=receiver_email]').val(data.response.remail);
                        $('input[name=receiver_address]').val(data.response.receiver_address);
                        $('.r_phone_msg').html('');
                    } else {
                        $('input[name=receiver_name]').val("");
                        $('input[name=receiver_email]').val("");
                        $('input[name=receiver_address]').val("");
                        $('.r_phone_msg').html(data.response);
                    }
                }
            });
        }
        $('body').on('change', '.select_profile', function(e) {
            e.preventDefault();
            id = $(this).val();
            $.ajax({
                url: 'getprofile.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    settle: 1,
                    profile_id: id
                },
                success: function(data) {
                    $('.bname').val(data.shipper_name);
                    $('.shipper_phone').val(data.shipper_phone);
                    $('.shipper_email').val(data.shipper_email);
                    $('.pickup_address_new').val(data.shipper_address);
                // $('.pickup_latitude').val(data.shipper_latitude);
                // $('.pickup_longitude').val(data.shipper_longitude);
            }
        });
        })
    }, false);
</script>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        var country_code = $('.default_number').html();
        country_code = country_code.replace('+', '');
    // $('#phoneno').val('00' + country_code);
    $('body').on('click keyup', '.country', function(e) {
        var country_code = $(this).attr('data-dial-code');
        $('#phoneno').val('00' + country_code);
    });
    // $(document).on('change','#phone',function(){
    //        var country_code=$(this).attr('data-dial-code');
    //         $('#phoneno').val('00'+country_code);
    // })

}, false);
</script>
<script type="text/javascript" src="js/booking_new.js"></script>