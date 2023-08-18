<style>
        .form-control,
        .input-group-addon,
        .bootstrap-select .btn {
            background-color: #ffffff;
            border-color: #ccc;
            border-radius: 3px;
            box-shadow: none;
            color: #000;
            font-size: 14px;
            height: 34px;
            padding: 0 20px;
            font-weight: 300;
        }

        label {
            font-weight: normal;
            margin: 0;
            color: #000;
            margin-bottom: 7px;
            font-weight: bold;
        }

        .modal-header {
            padding: 6px 11px;
            border-bottom: 1px solid #e5e5e5;
            margin-top: 0;
        }

        .profile-page-title,
        .col-lg-4 {
            padding: 0 15px;
        }

        .modal-title {
            text-align: center;
        }

        .register_page {
            max-width: 660px;
        }

        .form-group input,
        input.emaill {
            background-color: #f8fbff7d !important;
        }

        .wizard {
            width: 538px;
            margin-left: 0 !important;
            margin: 0px auto 0px;
            padding: 21px 21px;
            background: #fff;
            border: 1px solid #cccccc78;
            text-align: center;
            box-shadow: unset;
            border-radius: 6px;
            max-width: unset;
        }

        .wizard .nav-tabs>li {
            width: 50%;
            margin: 0 auto;
            text-align: center;
        }

        .wizard .nav-tabs {
            margin: 0;

        }

        .connecting-line {

            width: 60%;

        }

        .progressbar {
            margin: 0 0 16px;
        }

        section .dashboard .dashboard {
            padding: 20px 0 0 32px;
        }

        .bg {
            padding: 15px 0 0 !important;
        }

        label {
            margin: 6px 0;
            font-weight: 500;
            font-size: 14px;
        }

        .term_label {
            color: #0a68bb;
        }


        @media (max-width: 1250px) {
            .container {
                width: 100%;
            }


        }

        @media (max-width: 1024px) {
            .container {
                width: 100%;
            }


        }

        @media (max-width: 767px) {
            .container {
                width: auto;
            }

            .register_title {
                margin-top: 0;
            }
        }
    </style>

<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
include_once "../../../includes/conn.php";
// include "../../includes/sms_helper.php";
function getBarCodeImage($text = '', $code = null, $index)
{
    require_once('../../../includes/BarCode.php');
    $barcode = new BarCode();
    $path = '../../assets/barcodes/imagetemp' . $index . '.png';
    $barcode->barcode($path, $text);
    $folder_path = 'admin/assets/barcodes/imagetemp' . $index . '.png';
    return $folder_path;
}
if (isset($_GET['branch_id'])) {
    $_GET['branch_id'] = $_GET['branch_id'];
} else {
    $_GET['branch_id'] = 1;
}
function getProductsbyID($id){
    global $con;
    $product = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM products where id = '".$id."'"));
    return $product['name'];
}
if (!function_exists('getChargeSingleChargeDetail')) {
    function getChargeSingleChargeDetail($charge_id = null)
    {
        global $con;
        $charges_detail = [];
        if ($charge_id != null) {
            $charges_sql =  "SELECT * FROM `charges` WHERE id = $charge_id";
            $charges_result = mysqli_query($con, $charges_sql);
            while ($c_row = mysqli_fetch_array($charges_result)) {
                if (isset($c_row['id'])) {
                    $charges_detail['charge_name'] = isset($c_row['charge_name']) ? $c_row['charge_name'] : '';
                    $charges_detail['id'] = isset($c_row['id']) ? $c_row['id'] : '';
                    $charges_detail['charge_value'] = isset($c_row['charge_value']) ? $c_row['charge_value'] : 0;
                    $charges_detail['charge_type'] = isset($c_row['charge_type']) ? $c_row['charge_type'] : '';
                }
            }
        }
        return $charges_detail;
    }
}
if (!function_exists('getCustomerWiseCharges')) {
    function getCustomerWiseCharges($customer_id = null)
    {
        global $con;
        $customerWiseCharges = [];
        if ($customer_id != null) {
            $customerWiseCharges_sql =  "SELECT * FROM `charges_customer_wise` WHERE customer_id = $customer_id ORDER BY id DESC";
            $customerWiseCharges_result = mysqli_query($con, $customerWiseCharges_sql);
            $i = 0;
            while ($w_row = mysqli_fetch_array($customerWiseCharges_result)) {
                if (isset($w_row['id'])) {
                    $customerWiseCharges[$i]['id'] = isset($w_row['id']) ? $w_row['id'] : '';
                    $customerWiseCharges[$i]['charge_id'] = isset($w_row['charge_id']) ? $w_row['charge_id'] : '';
                    $customerWiseCharges[$i]['charge_value'] = isset($w_row['charge_value']) ? $w_row['charge_value'] : '';
                }
                $i++;
            }
        }
        return $customerWiseCharges;
    }
}

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
$customer_id = '';
$customer_wise_charge = '';
if (isset($_GET['customer_id'])) {
    $customer_id = $_GET['customer_id'];
    $customer_wise_charge = getCustomerWiseCharges($customer_id);
    $customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $customer_id . " ");
    $customer_data = mysqli_fetch_array($customer_query);
    $customer_city = $customer_data['city'];
    $customer_type = $customer_data['customer_type'];
    $tariff_type = $customer_data['tariff_type'];
}
if (!function_exists('getProducts')) {
    function getProducts()
    {
        global $con;
        global $customer_type;
        global $tariff_type;
        if($tariff_type=='custom'){
            $cus_pro_ids = '';
            $tariffSql =  "SELECT product_id FROM custom_tariff_pricing";
            $tariffResult = mysqli_query($con, $tariffSql);
            while ($t_row = mysqli_fetch_array($tariffResult)) {
                $cus_pro_ids .= $t_row['product_id'] . ',';
            }
            $cus_pro_ids = rtrim($cus_pro_ids, ',');
            $all_products = [];
            $product_sql =  "SELECT * FROM `products` Where id IN(" . $cus_pro_ids . ") ORDER BY id DESC";
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
        }else{
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
            $product_sql =  "SELECT * FROM `products` Where id IN(" . $cus_pro_ids . ") ORDER BY id DESC";
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
}
$getProducts = getProducts();
if (isset($_POST['settle'])) {
    $product_type_id = isset($_POST['product_type_id']) ? $_POST['product_type_id'] : '';
    $customer_id = $_POST['customer_id'];
    $weight = $_POST['weight'];
    $order_type = isset($_POST['order_type']) ? $_POST['order_type'] : '';
    $origin = isset($_POST['origin']) ? $_POST['origin'] : '';
    $destination = isset($_POST['destination']) ? $_POST['destination'] : '';
    include '../../../price_calculation.php';
    $delivery = delivery_calculation($origin, $destination, $weight, $customer_id, $order_type, $product_type_id);
    echo $delivery;
    exit();
}
if (isset($_POST['submit_order']) || isset($_POST['save_order'])) {
    $customer_id = $_POST['active_customer_id'];
    $customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $customer_id . " ");
    $customer_data = mysqli_fetch_array($customer_query);
    //if manually order enable check validation for order no
    if (isset($customer_data['is_order_manual']) and $customer_data['is_order_manual'] == 1) {
        if (isset($_POST['track_no']) and !empty($_POST['track_no'])) {
            $track_no = $_POST['track_no'];
            $check_track_no_exist = mysqli_query($con, "SELECT * FROM orders WHERE track_no='" . $track_no . "' ");
            $countrow = mysqli_num_rows($check_track_no_exist);
            if ($countrow > 0) {
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
    }
    if (empty($_POST['delivery_charges'])) {
        $err_response = array();
        $err_response['error'] = 1;
        $err_response['alert_msg'] = "Invalid charges calculation. please enter valid origin,destination and service type";
        echo json_encode($err_response);
        exit();
    }
    $customer_id = $_POST['active_customer_id'];
    $customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $customer_id . " ");
    $customer_data = mysqli_fetch_array($customer_query);
    $date = date('Y-m-d H:i:s');
    $plocation = '';
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
    $original_no = $_POST['receiver_phone'];
    //Validation for  Phone no to remove 92
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
    if (isset($_POST['order_time']) and empty($_POST['order_time'])) {
        $_POST['order_time'] = date('H:i:s');
    }
    $map_latitude  = $_POST['latitude'];
    $map_longitude = $_POST['longitude'];
    $pickup_latitude = isset($customer_data['customer_latitude']) ? $customer_data['customer_latitude'] : '';
    $pickup_longitude = isset($customer_data['customer_longitude']) ? $customer_data['customer_longitude'] : '';
    $is_fragile    = isset($_POST['is_fragile']) ? $_POST['is_fragile'] : 0;
    $payment_status = 'Pending';
    $booking_type = isset($_POST['booking_type']) ? $_POST['booking_type'] : '';
    if ($booking_type && $booking_type == 2) {
        $payment_status = 'Paid';
    }
    $insurance_type    = isset($_POST['insurance_type']) ? $_POST['insurance_type'] : '';
    $pickup_address_new    = $_POST['pickup_address_new'];
    $insured_premium = isset($_POST['insured_premium']) ? $_POST['insured_premium'] : 0;
    $extra_charges = isset($_POST['extra_charges']) ? $_POST['extra_charges'] : 0;
    $excl_amount = isset($_POST['excl_amount']) ? $_POST['excl_amount'] : 0;
    $pft_amount = isset($_POST['pft_amount']) ? $_POST['pft_amount'] : 0;
    $ref_no = isset($_POST['ref_no']) ? $_POST['ref_no'] : 0;
    $inc_amount = isset($_POST['inc_amount']) ? $_POST['inc_amount'] : 0;
    $special_instruction = isset($_POST['special_instruction']) ? $_POST['special_instruction'] : '';
    $tracking_no = isset($_POST['tracking_no']) ? $_POST['tracking_no'] : '';
    $product_desc = isset($_POST['product_desc']) ? $_POST['product_desc'] : '';
    $insured_item_value = isset($_POST['insured_item_value']) ? $_POST['insured_item_value'] : 0;
    $order_time = date("Y-m-d H:i:s");
    $product_type_id = isset($_POST['product_type_id']) ? $_POST['product_type_id'] : '';
    $user_id = isset($_SESSION['users_id']) ? $_SESSION['users_id'] : '';
    $insert_qry = "INSERT INTO `orders`(`sname`,`sbname`,`sphone`, `semail`, `sender_address`, `rname`,`remail`, `rphone`, `receiver_address`,`google_address`,`pickup_date`,`pickup_time`,`price`,`collection_amount`,`order_date`,`payment_method`,`customer_id`,`origin`,`destination`,`tracking_no`,`weight`,`product_desc`,`special_instruction`,`quantity`,`product_id`, `order_type`,`ref_no`,`excl_amount`,`pft_amount`,`inc_amount`,`is_ondesk`,`map_latitude`,`map_longitude`,`is_fragile`,`order_type_booking`,`Pick_location`,`current_branch`,`net_amount`, `grand_total_charges`,`special_charges`, `insured_item_value`, `scnic`,`insurance_type`,`insured_premium`,`extra_charges`,`fuel_surcharge`,`fuel_surcharge_percentage`,`booking_type`,`payment_status`,`order_time`,`origin_area_id`,`user_id`,`product_type_id`,`pickup_latitude`,`pickup_longitude`) VALUES ('" . $_POST['fname'] . "','" . $_POST['bname'] . "','" . $_POST['mobile_no'] . "','" . $_POST['email'] . "','" . $_POST['pickup_address'] . "','" . $_POST['receiver_name'] . "','" . $_POST['receiver_email'] . "','" . $original_no . "','" . $_POST['receiver_address'] . "','" . $_POST['google_address'] . "','" . $date . "','" . $_POST['order_time'] . "','" . $_POST['delivery_charges'] . "','" . $_POST['collection_amount'] . "','" . $order_date . "','CASH','" . $customer_id . "','" . $_POST['origin'] . "','" . $_POST['destination'] . "','" . $tracking_no . "','" . $_POST['weight'] . "','" . $product_desc . "','" . $special_instruction . "' ,'" . $_POST['quantity'] . "','" . $_POST['product_id'] . "', '" . $_POST['order_type'] . "','" . $ref_no . "','" . $excl_amount . "','" . $pft_amount . "','" . $inc_amount . "','1','" . $map_latitude . "','" . $map_longitude . "','" . $is_fragile . "', 2,'" . $pickup_address_new . "','" . $_POST['branch_id'] . "','" . $net_amount . "','" . $total_charges . "','" . $special_charges . "','" . $insured_item_value . "','" . $_POST['scnic'] . "','" . $insurance_type . "','" . $insured_premium . "','" . $extra_charges . "','" . $fuel_surcharge . "','" . $fuel_surcharge_percentage . "', " . $booking_type . ",'" . $payment_status . "','" . $order_time . "','" . $_POST['origin_area_id'] . "','" . $user_id . "','" . $product_type_id . "','" . $pickup_latitude . "','" . $pickup_longitude . "') ";
    // echo $map_latitude . " is map_latitude <br>";
    // echo $map_longitude . " is map_longitude <br>";
    // echo $pickup_latitude . " is pickup_latitude <br>";
    // echo $pickup_longitude . " is pickup_longitude <br>";
    // echo $insert_qry;
    // die();
    $next_number = 0;
    $start_date = date('Y-m-01');
    $end_date = date('Y-m-30');
    $count_query = mysqli_query($con, "SELECT count(id) as total_count FROM orders WHERE DATE_FORMAT(order_date, '%Y-%m-%d') >= '" . $start_date . "' AND  DATE_FORMAT(order_date, '%Y-%m-%d') <= '" . $end_date . "'");
    $result_query = mysqli_fetch_array($count_query);
    $count = isset($result_query['total_count']) ? $result_query['total_count'] : 0;
    
    $query = mysqli_query($con, $insert_qry);
    $insert_id = mysqli_insert_id($con);
    //   $limit_enable = getConfig('enable_orders_limit');
    //   $orders_limit = getConfig('orders_limit');
    //   $limit_message = getConfig('limit_message');
    //    if (isset($limit_enable) && $limit_enable==1 && ($count >= $orders_limit)) {
    //       $err_response = array();
    //       $err_response['error'] = 1;
    //       $err_response['alert_msg'] = '<h4>Your monthly number of orders limit has been reached '.$count.' out of '.$orders_limit.'</h4><p>You can upgrade your package by visiting your <a target="_blank" href="https://billing.icargos.com/">client area</a></p>';
    //       echo json_encode($err_response); exit();
    //    }else{
    //       $query=mysqli_query($con,$insert_qry);
    //       $insert_id=mysqli_insert_id($con);
    //    }
    if (isset($_SESSION['branch_id']) and !empty($_SESSION['branch_id'])) {
        $upquery = "UPDATE orders SET branch_id = '" . $_SESSION['branch_id'] . "', assign_branch = '" . $_SESSION['branch_id'] . "' ,booking_branch = '" . $_SESSION['branch_id'] . "' WHERE id = " . $insert_id;
        $query = mysqli_query($con, $upquery);
    }
    // fclose($file);
    if ($insert_id > 0) {
        if (isset($_POST['s_save_sender_to_address_book']) && $_POST['s_save_sender_to_address_book'] == '1') {
            $oder_id_q = mysqli_query($con, "SELECT order_id FROM address_book WHERE order_id='" . $insert_id . "'");
            $rowcount = mysqli_affected_rows($con);
            if ($rowcount > 0) {
                mysqli_query($con, "UPDATE address_book SET sname = '" . $_POST['fname'] . "',sbname = '" . $_POST['bname'] . "',sphone = '" . $_POST['mobile_no'] . "',semail = '" . $_POST['email'] . "',sender_address = '" . $_POST['pickup_address'] . "' WHERE order_id = '" . $insert_id . "'");
            } else {
                $insert_qry_address_book = "INSERT INTO `address_book`(`order_id`,`sname`,`sbname`,`sphone`, `semail`, `sender_address`) VALUES ('" . $insert_id . "','" . $_POST['fname'] . "','" . $_POST['bname'] . "','" . $_POST['mobile_no'] . "','" . $_POST['email'] . "','" . $_POST['pickup_address'] . "') ";
                mysqli_query($con, $insert_qry_address_book);
            }
        }
        if (isset($_POST['r_save_sender_to_address_book']) && $_POST['r_save_sender_to_address_book'] == '1') {
            $oder_id_q = mysqli_query($con, "SELECT order_id FROM address_book WHERE order_id='" . $insert_id . "'");
            $rowcount = mysqli_affected_rows($con);
            if ($rowcount > 0) {
                mysqli_query($con, "UPDATE address_book SET rname = '" . $_POST['receiver_name'] . "',remail = '" . $_POST['receiver_email'] . "',rphone = '" . $_POST['original_no'] . "',receiver_address = '" . $_POST['receiver_address'] . "' WHERE order_id = '" . $insert_id . "'");
            } else {
                $insert_r_qry_address_book = "INSERT INTO `address_book`(`order_id`,`rname`,`remail`, `rphone`, `receiver_address`) VALUES ('" . $insert_id . "','" . $_POST['receiver_name'] . "','" . $_POST['receiver_email'] . "','" . $original_no . "','" . $_POST['receiver_address'] . "') ";
                mysqli_query($con, $insert_r_qry_address_book);
            }
        }
        $listcharges = mysqli_query($con, "SELECT * FROM charges");
        if (isset($listcharges) && !empty($listcharges)) {
            // echo '<pre>',print_r($_POST),'</pre>';
            while ($row_charge = mysqli_fetch_array($listcharges)) {
                $row_id = $row_charge['id'];
                $dynamic_var_c_id = 'charge_id' . $row_id;
                if (isset($_POST[$dynamic_var_c_id])) {
                    $dynamic_var_c_type = 'charge_type' . $row_id;
                    $dynamic_var_c_amnt = 'charge_amount' . $row_id;
                    $charge_type   = $_POST[$dynamic_var_c_type];
                    $charge_amount = (isset($_POST[$dynamic_var_c_amnt]) && $_POST[$dynamic_var_c_amnt] > 0) ? $_POST[$dynamic_var_c_amnt] : 0;
                    $charge_id     = $_POST[$dynamic_var_c_id];
                    if (isset($charge_amount) && (float)($charge_amount > 0)) {
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
        mysqli_query($con, "UPDATE orders SET barcode = '" . $track_no . "', barcode_image = '" . $barcode_image . "', track_no = '" . $track_no . "' WHERE id = $insert_id");
        mysqli_query($con, "INSERT INTO order_logs(`order_no`,`order_status`,`location`,`created_on`) VALUES ('" . $track_no . "', '" . $_POST['status'] . "', '" . $_POST['origin'] . "','" . $date . "') ");
        mysqli_query($con, "UPDATE orders SET status = '" . $_POST['status'] . "' WHERE id = $insert_id");
        $iddd = encrypt($insert_id . "-TRS767###");
        ///////////send Email 
        include_once "../../email/sendEmail/admin_booking.php";
        email_admin_booking($customer_id, $insert_id);
        ///////////Send SMS if Walk in Customer

        ///////////////////////SMS////////////////////////
        // sendSmsMobileGateWay($track_no, 'Admin Booking');
        // die('Ok');
        $api_msg_status ='' ;
        $api_message = '';
        $api_service = '';
        if(isset($_POST['select_api']) && !empty($_POST['select_api'])){
            require '../../includes/API/post_on_api.php';
            $api_response = book_on_api($_POST['select_api'],$track_no,$api_service);
            // echo "<pre>";
            // print_r($api_response);
            // die;
            $api_msg_status =$api_response['status'] ;
            $api_message = $api_response['message'];
            if($api_msg_status=='success'){
                $_SESSION['succ_msg_for_api'] = $api_response['message'];
            }
            if($api_msg_status=='error'){
                $_SESSION['err_msg_for_api'] = $api_response['message'];
            }
            
        }
        
        
        if (isset($_POST['submit_order']) && $_POST['submit_order'] == '1') {
            ob_clean();
            echo json_encode(['id' => $insert_id, 'print' => 1, 'track_no' => $track_no, 'api_msg_status'=>$api_msg_status,'api_msg'=>$api_message]);
            exit();
        } else {
            ob_clean();
            echo json_encode(['id' => $insert_id, 'track_no' => $track_no, 'api_msg_status'=>$api_msg_status,'api_msg'=>$api_message]);
            exit();
        }
    } else {
        $err_response = array();
        $err_response['error'] = 1;
        $err_response['alert_msg'] = $insert_qry;
        $err_response['alert_msg'] = "Error please try again!.";
        echo json_encode($err_response);
        exit();
    }
    exit();
}
//order process////////////////
$customer_origin_zone_q = mysqli_query($con, " SELECT GROUP_CONCAT(DISTINCT zone_id SEPARATOR ',') as zone_ids
   FROM customer_pricing WHERE customer_id='" . $customer_id . "'  ");
$this_current_branch = '';
$allowed_origins_ids = "";
if (isset($_GET['branch_id']) && !empty($_GET['branch_id'])) {
    $this_current_branch = $_GET['branch_id'];
} elseif (isset($_SESSION['branch_id']) && !empty($_SESSION['branch_id'])) {
    $this_current_branch = $_SESSION['branch_id'];
}
if (isset($this_current_branch) && !empty($this_current_branch)) {
    $allowed_origin_q = mysqli_query($con, "SELECT * FROM branches WHERE id=" . $this_current_branch);
    $allowed_origins_ids_result = mysqli_fetch_assoc($allowed_origin_q);
    $allowed_origins_ids = $allowed_origins_ids_result['branch_origin'];
}
$allowe_ids_arry = explode(',', $allowed_origins_ids);
$city_names = "";
foreach ($allowe_ids_arry as $key => $value) {
    $single_city_query = mysqli_query($con, "SELECT * FROM cities WHERE id=" . $value);
    $single_city = mysqli_fetch_assoc($single_city_query);
    $city_names .= '"' . $single_city['city_name'] . '"' . ',';
}
$city_name_trim = rtrim($city_names, ',');
// echo $city_names;
// die();
if (mysqli_num_rows($customer_origin_zone_q) > 0) {
    $origin_zone_res = mysqli_fetch_array($customer_origin_zone_q);
    $zone_ids = $origin_zone_res['zone_ids'];
    $origin_q = mysqli_query($con, " SELECT * FROM cities");
    // $origin_q = mysqli_query($con, " SELECT DISTINCT origin FROM zone_cities WHERE zone IN(" . $zone_ids . ") ORDER BY origin ");
    $destination_q = mysqli_query($con, " SELECT DISTINCT destination FROM zone_cities WHERE zone IN(" . $zone_ids . ") ORDER BY destination ");
    $destination_cities_list = '';
    while ($destination_r = mysqli_fetch_array($destination_q)) {
        //          echo "<pre>ss";
        // print_r(" SELECT DISTINCT destination FROM zone_cities WHERE zone IN(".$zone_ids.") ORDER BY destination ");
        // print_r($destination_r);
        //          die();
        $city = $destination_r['destination'];
        if ($city == 'Other' or $city == 'Others') {
            $city_q = mysqli_query($con, "SELECT DISTINCT city_name FROM cities WHERE city_name !='Other' AND city_name !='Others'  ");
            while ($city_q_r = mysqli_fetch_array($city_q)) {
                $city = $city_q_r['city_name'];
                $destination_cities_list .= "<option  value='" . $city . "' >" . $city . "</option>";
            }
        } else {
            $destination_cities_list .= "<option value='" . $city . "'  >" . $city . "</option>";
        }
    }
    $destination_cities_list = '';
    $city_q = mysqli_query($con, "SELECT * FROM cities order by city_name asc");
    while ($city_q_r = mysqli_fetch_array($city_q)) {
        $city = $city_q_r['city_name'];
        $destination_cities_list .= "<option  value='" . $city . "' >" . $city . "</option>";
    }

    //service types queries
    // echo "<pre>";
    // echo $destination_cities_list;
    // // echo " SELECT DISTINCT destination FROM zone_cities WHERE zone IN(".$zone_ids.") ORDER BY destination ";
    // die();
    $service_type_q = mysqli_query($con, " SELECT GROUP_CONCAT(DISTINCT service_type SEPARATOR ',') as service_types FROM zone WHERE id IN (" . $zone_ids . ") ");
    if (mysqli_num_rows($service_type_q) > 0) {
        $service_type_id_res = mysqli_fetch_array($service_type_q);
        $service_types = $service_type_id_res['service_types'];
    }
}
$branchQuery = '1';
if (isset($_SESSION['branch_id']) and !empty($_SESSION['branch_id'])) {
    $branchQuery = " branch_id= " . $_SESSION['branch_id'];
}
if (isset($_SESSION['branch_id']) && !empty($_SESSION['branch_id'])) {
    $customers = mysqli_query($con, "SELECT * FROM customers WHERE status=1 OR id=1");
} else {
    $customers = mysqli_query($con, "SELECT * FROM customers WHERE status=1  OR id=1");
}
$branches = mysqli_query($con, "SELECT * FROM branches");
// $gst_query = mysqli_query($con, "SELECT value FROM config WHERE `name`='gst' ");
// $total_gst = mysqli_fetch_array($gst_query);
// $gst_percentage = 0;
// // if (isset($customer_data['is_saletax']) && $customer_data['is_saletax'] == 1) {
// //    $gst_percentage = isset($total_gst['value']) ? $total_gst['value'] : 0;
// // }
$customer_id = isset($_GET['customer_id']) ? $_GET['customer_id'] :  '';
$get_service_types = mysqli_query($con, " SELECT DISTINCT id,service_type FROM services WHERE 1 ");
//order process////////////////
$customer_origin_zone_q = mysqli_query($con, " SELECT GROUP_CONCAT(DISTINCT zone_id SEPARATOR ',') as zone_ids FROM customer_pricing WHERE customer_id='" . $customer_id . "'  ");
if (mysqli_num_rows($customer_origin_zone_q) > 0) {
    $origin_zone_res = mysqli_fetch_array($customer_origin_zone_q);
    $zone_ids = $origin_zone_res['zone_ids'];
    if ($_GET['branch_id'] == '1') {
        // $origin_q      = mysqli_query($con, " SELECT DISTINCT origin FROM zone_cities WHERE zone IN(" . $zone_ids . ")   ");
        $origin_q      = mysqli_query($con, " SELECT * FROM cities");
    } else {
        $origin_q      = mysqli_query($con, " SELECT * FROM cities");
        // $origin_q      = mysqli_query($con, " SELECT DISTINCT origin FROM zone_cities WHERE zone IN(" . $zone_ids . ") AND origin IN (" . $city_name_trim . ")  ");
    }
    $destination_q = mysqli_query($con, " SELECT DISTINCT destination FROM zone_cities WHERE zone IN(" . $zone_ids . ") ");
    //service types queries
    $service_type_q = mysqli_query($con, " SELECT GROUP_CONCAT(DISTINCT service_type SEPARATOR ',') as service_types FROM zone WHERE id IN (" . $zone_ids . ") ");
    if (mysqli_num_rows($service_type_q) > 0) {
        $service_type_id_res = mysqli_fetch_array($service_type_q);
        $service_types         = $service_type_id_res['service_types'];
        $get_service_types   = mysqli_query($con, " SELECT DISTINCT id,service_type FROM services WHERE id IN(" . $service_types . ") ");
    }
}
$other_charges = mysqli_query($con, "SELECT * FROM charges");
$insurance = mysqli_query($con, "SELECT * FROM insurance_type");
$order_status = mysqli_query($con, "SELECT * FROM order_status");
$admin_other_charges   = getconfig('admin_other_charges');
$admin_extra_charges   = getconfig('admin_extra_charges');
$admin_insured_premium = getconfig('admin_insured_premium');
$services = mysqli_query($con, "SELECT * FROM services order by service_type asc ");

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

        section .dashboard .white {
            padding: 11px 7px 11px 12px !important;
        }

        .go_Dashboard {
            background: #f5f5f5;
            padding: 12px 11px;
            margin: 0;
        }
        .table_upload{
            width: 33%;
        }
        .file-upload{
            padding-right: 20px;
        }
        .upload_excel_file .change_file_name {

    background: unset;
}
        .file-upload table tr th, .file-upload table tr td {
    font-size: 11px !important;
    font-weight: 600;
    color: #000;
}
        .table_upload td {
            padding: 8px;
            border: 1px solid #ccc;
        }

        table.jexcel>thead>tr>td {
            font-size: 13px;
        }

        .progressbar h3 {
            margin: 0;
            padding: 8px 0 0;
            font-weight: 600;
            font-size: 20px;
        }

        .complete_profile {
            text-align: right;
            padding: 0 5px;
        }

        .complete_profile a {
            background-color: #4cade0;
            border: #00a5b3;
            color: #fff;
            font-size: 13px;
            padding: 9px 18px;
            margin-left: 10px;
            border-radius: 3px;
            display: inline-block;
        }

        table.jexcel>thead>tr>td {
            font-size: 11px;
        }

        /*.bulk-bg {
    background: #f5f5f5;
    padding: 13px 7px 13px 0;
    border-top: 1px solid #cccccc85;
}*/
        .go_to:hover {
            color: #fff !important;
        }

        .go_to:focus {
            color: #fff !important;
        }

        .sample_sheet {
            background-color: #449d44 !important;
            border-color: #398439 !important;
        }

        /*.jexcel_content table tr >td:first-child{
	display: none;
}*/
        @media(max-width: 1250px) {
            .container {
                width: 100%;
            }

            .complete_profile a {
                padding: 9px 7px;
            }
        }

        @media(max-width: 1024px) {
            .container {
                width: 100%;
            }

            .complete_profile a {
                margin-left: 3px;
                font-size: 11px;
                padding: 9px 5px;
            }
        }

        @media(max-width: 767px) {
            .container {
                width: auto;
            }

            #spreadsheet .jexcel_content {
                min-height: .01%;
                overflow-x: auto;
            }

            .progressbar h3,
            .complete_profile {
                text-align: center;
            }

            .complete_profile a {
                margin-left: 3px;
                font-size: 11px;
                padding: 9px 15px;
            }

            .site-logo img {
                top: -2px !important;
                left: 10px !important;
            }
        }

        @media(max-width: 1250px) {
            .container {
                width: 100%;
            }
        }

        @media(max-width: 1024px) {
            .container {
                width: 100%;
            }

            #header_wrap .theme-menu>li:nth-child(5),
            #header_wrap .theme-menu>li:nth-child(6) {
                padding-top: 8px !important;
            }

            .navbar-nav .active:last-child a {
                padding: 5px 0 1px;
            }

            .site-logo img {
                top: 7px !important;
            }

            section .dashboard .white {
                padding: 20px 10px !important;
            }
        }

        @media(max-width: 767px) {
            .container {
                width: auto;
            }

            .menu_icon i {
                top: -42px;
            }

            #header_wrap .menu-bar {
                padding: 17px 0px 0 !important;
            }

            .site-logo img {
                top: -7px !important;
            }
        }

        .jdropdown-container {
            min-width: 101px;
            bottom: auto !important;
            z-index: 999999;
            background: #f5f5f5;
            position: absolute;
        }

        .jdropdown-content {
            line-height: 1.6;
            z-index: 999999;
        }

        .jdropdown-close {
            display: none !important;
        }

        .alert {
            padding: 6px !important;
            margin-bottom: 6px !important;
        }


        .filelabel {

            border: 2px dashed grey;
            border-radius: 5px;
            display: block;
            padding: 5px;
            transition: border 300ms ease;
            cursor: pointer;
            text-align: center;
            margin: 0;
        }

        .filelabel i {
            display: block;
            font-size: 30px;
            padding-bottom: 5px;
        }

        .filelabel i,
        .filelabel .title {
            color: grey;
            transition: 200ms color;
        }

        .filelabel:hover {
            border: 2px solid #1665c4;
        }

        .filelabel:hover i,
        .filelabel:hover .title {
            color: #1665c4;
        }

        #FileInput {
            display: none;
        }

        /*tabs*/
        /*custom font*/
        @import url(https://fonts.googleapis.com/css?family=Montserrat);

        /*basic reset*/
        * {
            margin: 0;
            padding: 0;
        }

        html {
            height: 100%;
            /*Image only BG fallback*/

            /*background = gradient + image pattern combo*/
            
        }

        body {
            font-family: montserrat, arial, verdana;
        }

        /*form styles*/
        #msform {
            width: 400px;
            margin: 50px auto;
            text-align: center;
            position: relative;
        }

        #msform fieldset {
            background: white;
            border: 0 none;
            border-radius: 3px;
            box-shadow: 0 0 15px 1px rgba(0, 0, 0, 0.4);
            padding: 20px 30px;
            box-sizing: border-box;
            width: 80%;
            margin: 0 10%;

            /*stacking fieldsets above each other*/
            position: relative;
        }

        /*Hide all except first fieldset*/
        #msform fieldset:not(:first-of-type) {
            display: none;
        }

        /*inputs*/
        #msform input,
        #msform textarea {
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
            margin-bottom: 10px;
            width: 100%;
            box-sizing: border-box;
            font-family: montserrat;
            color: #2C3E50;
            font-size: 13px;
        }

        /*buttons*/
        #msform .action-button {
            width: 100px;
            background: #27AE60;
            font-weight: bold;
            color: white;
            border: 0 none;
            border-radius: 1px;
            cursor: pointer;
            padding: 10px 5px;
            margin: 10px 5px;
        }

        #msform .action-button:hover,
        #msform .action-button:focus {
            box-shadow: 0 0 0 2px white, 0 0 0 3px #27AE60;
        }

        /*headings*/
        .fs-title {
            font-size: 15px;
            text-transform: uppercase;
            color: #2C3E50;
            margin-bottom: 10px;
        }

        .fs-subtitle {
            font-weight: normal;
            font-size: 13px;
            color: #666;
            margin-bottom: 20px;
        }

        /*progressbar*/
        #progressbar {
            margin-bottom: 30px;
            overflow: hidden;
            /*CSS counters to number the steps*/
            counter-reset: step;
        }

        #progressbar li {
            list-style-type: none;
            color: white;
            text-transform: uppercase;
            font-size: 9px;
            width: 33.33%;
            float: left;
            position: relative;
        }

        #progressbar li:before {
            content: counter(step);
            counter-increment: step;
            width: 20px;
            line-height: 20px;
            display: block;
            font-size: 10px;
            color: #333;
            background: white;
            border-radius: 3px;
            margin: 0 auto 5px auto;
        }

        /*progressbar connectors*/
        #progressbar li:after {
            content: '';
            width: 100%;
            height: 2px;
            background: white;
            position: absolute;
            left: -50%;
            top: 9px;
            z-index: -1;
            /*put it behind the numbers*/
        }

        #progressbar li:first-child:after {
            /*connector not needed before the first step*/
            content: none;
        }

        /*marking active/completed steps green*/
        /*The number of the step and the connector before it = green*/
        #progressbar li.active:before,
        #progressbar li.active:after {
            background: #27AE60;
            color: white;
        }

        .m_zero {
            margin: 0;
            padding-left: 0;
        }

        .main_box_hide {
            display: none;
        }
        .form-control,
        .input-group-addon,
        .bootstrap-select .btn {
            background-color: #ffffff;
            border-color: #ccc;
            border-radius: 3px;
            box-shadow: none;
            color: #000;
            font-size: 14px;
            height: 34px;
            padding: 0 20px;
            font-weight: 300;
        }

        label {
            font-weight: normal;
            margin: 0;
            color: #000;
            margin-bottom: 7px;
            font-weight: bold;
        }

        .modal-header {
            padding: 6px 11px;
            border-bottom: 1px solid #e5e5e5;
            margin-top: 0;
        }

        .profile-page-title,
        .col-lg-4 {
            padding: 0 15px;
        }

        .modal-title {
            text-align: center;
        }

        .register_page {
            max-width: 660px;
        }

        .form-group input,
        input.emaill {
            background-color: #f8fbff7d !important;
        }

        .wizard {
            width: 538px;
            margin-left: 0 !important;
            margin: 0px auto 0px;
            padding: 21px 21px;
            background: #fff;
            border: 1px solid #cccccc78;
            text-align: center;
            box-shadow: unset;
            border-radius: 6px;
            max-width: unset;
        }

        .wizard .nav-tabs>li {
            width: 50%;
            margin: 0 auto;
            text-align: center;
        }

        .wizard .nav-tabs {
            margin: 0;

        }

        .connecting-line {

            width: 60%;

        }

        .progressbar {
            margin: 0 0 16px;
        }

        section .dashboard .dashboard {
            padding: 20px 0 0 32px;
        }

        .bg {
            padding: 15px 0 0 !important;
        }

        label {
            margin: 6px 0;
            font-weight: 500;
            font-size: 14px;
        }

        .term_label {
            color: #0a68bb;
        }


        @media (max-width: 1250px) {
            .container {
                width: 100%;
            }


        }

        @media (max-width: 1024px) {
            .container {
                width: 100%;
            }


        }

        @media (max-width: 767px) {
            .container {
                width: auto;
            }

            .register_title {
                margin-top: 0;
            }
        }
        section .dashboard .shipper_box {
            display: unset;
            padding: 0px !important;
        }

        .whitee {
            color: white !important;
        }

        .whitee:hover {
            color: white !important;
        }

        .jexcel_container,
        .jexcel_content,
        .jexcel {
            width: 100% !important;
        }
    </style>
<div class="panel-heading order_box"><?php echo getLange('Upload Excel File'); ?> </div>
<div class="panel-body" id="same_form_layout">
        <div class="row ">
                    <div class="col-sm-12 complete_profile" style="text-align: left !important;">
                        <?php if (isset($customer_data['is_order_manual']) and $customer_data['is_order_manual'] == 1) { ?>
                            <a class="go_to sample_sheet" href="<?php echo BASE_URL ?>sample/bulk_booking_manual.xlsx" download><?php echo getLange('downloadsamplesheet'); ?></a>
                        <?php } else { ?>
                            <a class="go_to sample_sheet" href="sample/bulk_booking.xlsx" download><?php echo getLange('downloadsamplesheet'); ?></a>
                        <?php } ?>

                    </div>
                    <br>
                    <br>
                    <br>
                    <div class="col-sm-12 progressbar">
                        <h3>Import Excel Sheet</h3>
                    </div>
				<?php if (isset($_SESSION['bulk_message']) && !empty($_SESSION['bulk_message'])) {
                ?><div class="alert alert-success"><?php echo $_SESSION['bulk_message']; ?></div>
                <?php
                    unset($_SESSION['bulk_message']);
                } ?>
                <input type="hidden" id="customer_id" value="<?php echo $_SESSION['customers']; ?>">
                 <div class="row">
                                <div class="col-sm-2 side_gapp">
                                    <div class="form-group">
                                        <!-- <label class="control-label"><span style="color: red">*</span>Date</label> -->
                                        <input type="date" class="form-control" id ="date" name="date" required="true" value="">
                                    </div>
                                </div>
                            </div>
                <div class="row">
                    <div class="col-lg-3 m_zero">
                        <label class="filelabel">
                            <i class="fa fa-paperclip">
                            </i>
                            <span class="title">
                                Import File
                            </span>
                            <input class="FileUpload1" id="FileInput" name="booking_attachment" type="file" />

                        </label>
                        <div id="msg"></div>


                        <div class="buttons">
                            <input type="hidden" class="oldfile form-control" value="">
                            <label>Rename File</label>
                            <input type="text" name="" class="change_file_name" value="">
                            <div class="msg"></div>
                            <button id="submit" class="submit btn btn-info hidden">Submit</button>
                            <div class="rename_msg"></div>
                            <button class="remanefile hidden btn btn-info">Rename File</button>
                        </div>
                    </div>
                    <img src="<?php echo BASE_URL; ?>images/loader_se.gif" style="width: 150px;display: none;" id="image1">
                    <div class="col-lg-9 main_box_hide">

                        <!-- multistep form -->
                        <section>
                            <div class="wizard hidden">

                                <div class="wizard-inner">
                                    <div class="connecting-line"></div>
                                    <ul class="nav nav-tabs" role="tablist">

                                        <li role="presentation" class="active" id="first_tab">
                                            <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" title="Step 1">
                                                <span class="round-tab">1</span>
                                            </a>
                                            <b>processing </b>
                                        </li>

                                        <li role="presentation" class="disabled" id="next_tab">
                                            <a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" title="Step 2">
                                                <span class="round-tab">2</span>
                                            </a>
                                            <b> submit </b>
                                        </li>





                                    </ul>
                                </div>

                                <form autocomplete="off" class="validateform" id="contactForm" action="" method="post" class="City:" role="form" enctype="multipart/form-data">
                                    <div class="tab-content">
                                        <div class="bulk_msg alert alert-danger" style="display: none;"></div>
                                        <div class="tab-pane active" role="tabpanel" id="step1">
                                            <img src="<?php echo BASE_URL ?>admin/img/excel.png" alt="">

                                            <p>Upload</p>
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                                    <span>
                                                        <p style="display: inline;" class="upload_processing">0 </p>%
                                                        Complete
                                                    </span>
                                                </div>
                                            </div>
                                            <button style="display: inline;" class="validation btn btn-info">Check
                                                Validation</button>
                                            <div>Validation</div>
                                            <div class="progress" id="progressbar">

                                                <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 0%" id="progressbar_validation">
                                                    <span>
                                                        <p class="validation_process" style="display: inline;">0 </p>%
                                                        Complete
                                                    </span>
                                                </div>
                                            </div>

                                            <ul class="list-inline pull-right">
                                                <li><button type="button" class="btn btn-primary next-step" id="submit_step_data1" disabled="true"><?php echo getLange('next'); ?></button></li>
                                            </ul>
                                        </div>
                                        <div class="tab-pane" role="tabpanel" id="step2">
                                            <img src="<?php echo BASE_URL; ?>images/loader_se.gif" style="width: 62px;display: none;" id="image">
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="100" aria-valuemax="100" style="width: 0%" id="progressbar_submit">
                                                    <span>
                                                        <p style="display: inline;" class="submit_process">0 </p>% Complete
                                                    </span>
                                                </div>
                                            </div>

                                            <ul class="list-inline pull-right">
                                                <li><button type="button" class="btn btn-default prev-step"><?php echo getLange('previous'); ?></button>
                                                </li>
                                                <li><button type="button" class="btn btn-primary" onclick="Loading.show()" id="submit_step_data"><?php echo getLange('submit'); ?></button>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="clearfix"></div>
                                    </div>
                                </form>
                            </div>
                    </div>

                    


                </div>
                  <div class="row file-upload"> 
            <table class="table_upload">
                <thead>
                    <tr>
                        <td>Sr No.</td>
                        <td>Product Name</td>
                        <td>Service Code</td>
                    </tr>
                </thead>
                <tbody>
                    <?php $srno=1;
                     while ($res = mysqli_fetch_assoc($services)) {  
                        $product_id = $res['product_id'];
                        ?>
                        <tr> 
                        <td> <?php echo $srno++;?> </td>
                        <td> <?php echo getProductsbyID($product_id); ?> </td>
                        <td> <?php echo $res['service_code']; ?> </td>
                    </tr>
                    <?php } ?>

                </tbody>
            </table>

            <div style="margin-top: 20px;">
                <div class="white shipper_box" >
        <div class="alert alert-info">
          <strong>Info!</strong> Download sample sheet &amp; update according to your bookings  .
        </div>
        <div class="alert alert-info">
          <strong>Info!</strong>Copy records from excel sheet &amp; paste here .
        </div>
        <div class="alert alert-info">
          <strong>Info!</strong>Use valid Service type,Origin &amp; Destination.
        </div>
        
        
            </div>
            </div>
        </div>
                </div>
  
</div>
</div>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $("#submit").click(function() {
            var remanefile = $('.change_file_name').val();
            var oldfile = $('.oldfile').val();
            var date = $('#date').val();
            // alert(date);
            // alert('1');
            var fd = new FormData();
            fd.append('file', $('#FileInput')[0].files[0]);
            fd.append('remanefile', remanefile);
            fd.append('oldfile', oldfile);
            fd.append('date', date);
            if(date != ''){
            $.ajax({
                url: 'ajax_bulk_import.php',
                dataType: 'Json',
                beforeSend: function() {
                    $('#image1').show();
                },
                complete: function() {
                    $('#image1').hide();
                },
                cache: false,
                contentType: false,
                processData: false,
                data: fd,
                type: 'post',
                success: function(output) {
                    // alert(output);
                    if (output.percentage == 0) {
                        $('#msg').html("This File is always available Choose other file");
                        $('.FileUpload1').val("");
                        $('.wizard').addClass('hidden');
                    } else {
                        $('.upload_processing').html(output.percentage);
                        $('.change_file_name').val(output.filename);
                        $('.upload_msg').html('succcess');
                        $('#msg').html('');
                        $('.tab-content').show();
                        $('.wizard').removeClass('hidden');
                        $('#submit').addClass('hidden');
                        $('.FileUpload1').prop('disabled', true);
                        $('.change_file_name').prop('disabled', true);
                        $('.remanefile').addClass('hidden');
                    }
                }
            })
        }else{
            alert('Select Date First');
        }
        });  

        $('body').on('click', '.remanefile', function(e) {
            e.preventDefault();
            var remanefile = $('.change_file_name').val();
            var oldfile = $('.oldfile').val();
             var date = $('#date').val();
            // alert(date);
            // alert('2');
            if (remanefile != "") {
                $.ajax({
                    type: 'POST',
                    data: {
                        remane: 1,
                        remanefile: remanefile,
                        oldfile: oldfile
                    },
                    url: 'ajax_bulk_import.php',
                    success: function(fetch) {
                        if (fetch == 19) {
                            var fatch_msg =
                                'This File Is Alredy Exist Please Chose Another File Name';
                            $('.rename_msg').html(fatch_msg);
                            $('.wizard').addClass('hidden');
                        } else {
                            $('.rename_msg').html(fetch);
                            $('.remanefile').addClass('hidden');
                            $('.wizard').removeClass('hidden');
                            $('.FileUpload1').prop('disabled', true);
                            $('.change_file_name').prop('disabled', true);
                        }
                    }
                });
            }
        });
        //          $('body').on('click', '#delete', function (e) {
        // e.preventDefault();
        //  var remanefile=$('.change_file_name').val();
        // if(remanefile!=""){
        //     $.ajax({
        //         type:'POST',
        //         data:{delete:1,remanefile:remanefile},
        //         url:'ajax_bulk_import.php',
        //         success:function(fetch){
        //                  $('.rename_msg').html(fetch);
        //             }
        //         });
        //     }
        // });
        $('body').on('change', '.change_file_name', function(e) {
            e.preventDefault();
            var validExtensions = ["xlsx", "xlsm"]
            var file = $(this).val().split('.').pop();
            if (validExtensions.indexOf(file) == -1) {
                var msg = ("Only formats are allowed : " + validExtensions.join(', '));
                $('.msg').html('');
                $('.msg').html(msg);
                $('#submit').addClass('hidden');
            } else {
                $('.msg').html('');
                $('#submit').removeClass('hidden');
            }
        });
        $(".FileUpload1").change(function() {
            var validExtensions = ["xlsx", "xlsm"]
            var file = $(this).val().split('.').pop();
            if (validExtensions.indexOf(file) == -1) {
                var msg = ("Only formats are allowed : " + validExtensions.join(', '));
                $('#msg').html('');
                $('#msg').html(msg);
                $(this).val("");
            } else {
                $('#msg').html('');
                $('#submit').removeClass('hidden');
            }
        });

        $('body').on('click', '.validation', function(e) {
            e.preventDefault();
            var change_file_name = $('.change_file_name').val();
            var customer_id = $('#customer_id').val();
            var date = $('#date').val();
            $.ajax({
                url: 'ajax_bulk_import.php',
                // beforeSend: function(){
                //         $('#image1').show();
                //         $('#process').css('display','block');
                //         },
                // complete: function(){
                //     $('#image1').hide();
                // },
                type: 'POST',
                data: {
                    bulk_booking: 1,
                    change_file_name: change_file_name,
                    customer_id: customer_id,
                    date:date
                },
                dataType: 'json',
                success: function(response) {
                    if (response.error) {
                        $('.bulk_msg').css({'display':'block'});
                        $('.bulk_msg').html(response.alert_msg);
                        $('.bulk_save').removeAttr('disabled');
                        $('.bulk_save').removeClass('disabled');
                        $('#submit_step_data1').prop('disabled', true);
                        $('#submit').addClass('hidden');
                        $('.FileUpload1').prop('disabled', false);
                        $('.change_file_name').prop('disabled', false);
                        var remanefile = $('.change_file_name').val();
                         var date = $('#date').val();
                        // alert(date);
                        // alert("3");
                        $.ajax({
                            type: 'POST',
                            data: {
                                delete: 1,
                                remanefile: remanefile
                            },
                            url: 'ajax_bulk_import.php',
                            success: function(fetch) {}
                        });
                        return;
                    } else {
                        var percentage = 0;
                        var timer = setInterval(function() {
                            percentage = percentage + 20;
                            progress_bar_process(percentage, timer);
                        }, 1000);
                        // $('.validation_process').html(response);
                        // $('#submit_step_data1').prop('disabled', false);
                    }
                }
            });
        });

        function progress_bar_process(percentage, timer) {
            if (percentage < 101) {
                $('#progressbar_validation').css('width', percentage + '%');
                $('.validation_process').html(percentage);
            }
            if (percentage > 100) {
                clearInterval(timer);
                $('.valiation_success').html('Success');
                $('#submit_step_data1').prop('disabled', false);
            }
        }
        $('body').on('click', '#submit_step_data', function(e) {
            e.preventDefault();
            var change_file_name = $('.change_file_name').val();
            var customer_id = $('#customer_id').val();
              var date = $('#date').val();
        
            $.ajax({
                url: 'ajax_bulk_import.php',
                beforeSend: function() {
                    $('#image').show();
                },
                complete: function() {
                    $('#image').hide();
                },
                type: 'POST',
                data: {
                    save_booking: 1,
                    change_file_name: change_file_name,
                    customer_id: customer_id,
                    date:date
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    if (response.error) {
                        $('.bulk_msg').css({'display':'block'});
                        $('.bulk_msg').html(response.alert_msg);
                        return;
                    } else {
                        var percentage = 0;
                        var timer = setInterval(function() {
                            percentage = percentage + 10;
                            progress_bar_process_submit(percentage, timer);
                        }, 1000);
                        // $('.submit_process').html(response.process)
                    }
                }
            });
        });

        function progress_bar_process_submit(percentage, timer) {
            if (percentage < 101) {
                $('#progressbar_submit').css('width', percentage + '%');
                $('.submit_process').html(percentage);
            }
            if (percentage > 100) {
                clearInterval(timer);
                $('.valiation_success').html('Success');
                $('#submit_step_data1').prop('disabled', false);
                var remanefile = $('.change_file_name').val();
                  var date = $('#date').val();
            // alert(date);
            // alert("5");
                $.ajax({
                    type: 'POST',
                    data: {
                        delete: 1,
                        remanefile: remanefile
                    },
                    url: 'ajax_bulk_import.php',
                    success: function(fetch) {
                        location.reload();
                    }
                });
            }
        }
    });
    document.addEventListener('DOMContentLoaded', function() {
        $('title').text($('title').text() + ' Bulk Booking')
    }, false);

    // select file




    // input file 


    $("#FileInput").on('change', function(e) {
        var labelVal = $(".title").text();
        var oldfileName = $(this).val();
        fileName = e.target.value.split('\\').pop();

        if (oldfileName == fileName) {
            return false;
        }
        var extension = fileName.split('.').pop();

        if ($.inArray(extension, ['jpg', 'jpeg', 'png']) >= 0) {
            $(".filelabel i").removeClass().addClass('fa fa-file-image-o');
            $(".filelabel i, .filelabel .title").css({
                'color': '#208440'
            });
            $(".filelabel").css({
                'border': ' 2px solid #208440'
            });
        } else if (extension == 'pdf') {
            $(".filelabel i").removeClass().addClass('fa fa-file-pdf-o');
            $(".filelabel i, .filelabel .title").css({
                'color': 'red'
            });
            $(".filelabel").css({
                'border': ' 2px solid red'
            });

        } else if (extension == 'doc' || extension == 'docx') {
            $(".filelabel i").removeClass().addClass('fa fa-file-word-o');
            $(".filelabel i, .filelabel .title").css({
                'color': '#2388df'
            });
            $(".filelabel").css({
                'border': ' 2px solid #2388df'
            });
        } else {
            $(".filelabel i").removeClass().addClass('fa fa-file-o');
            $(".filelabel i, .filelabel .title").css({
                'color': 'black'
            });
            $(".filelabel").css({
                'border': ' 2px solid black'
            });
        }

        if (fileName) {
            if (fileName.length > 10) {
                $(".filelabel .title").text(fileName.slice(0, 4) + '...' + extension);
            } else {
                $(".filelabel .title").text(fileName);
            }
        } else {
            $(".filelabel .title").text(labelVal);
        }
    });

    // tabs
    $('.nav-tabs > li a[title]').tooltip();
    $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {

        var $target = $(e.target);

        if ($target.parent().hasClass('disabled')) {
            return false;
        }
    });

    $(".next-step").click(function() {
        $('#first_tab').removeClass('active');
        $('#step1').removeClass('active');
        $('#next_tab').removeClass('disabled');
        $('#next_tab').addClass('active');
        $('#step2').addClass('active');
    });
    $(".prev-step").click(function(e) {

        $('#next_tab').removeClass('active');
        $('#step2').removeClass('active');
        $('#first_tab').removeClass('disabled');
        $('#first_tab').addClass('active');
        $('#step1').addClass('active');

    });

    function nextTab(elem) {
        $(elem).next().find('a[data-toggle="tab"]').click();
    }

    function prevTab(elem) {
        $(elem).prev().find('a[data-toggle="tab"]').click();
    }



    $("#FileInput").change(function(e) {
        //submit the form here
        $(".main_box_hide").css({
            "display": "block"
        });
        var fileName = e.target.files[0].name;
        $(document).find('.change_file_name').val('');
        $(document).find('.change_file_name').val(fileName);
        $(document).find('.oldfile').val('');
        $(document).find('.oldfile').val(fileName);
        $('.bulk_msg').html('');
        $('#msg').html('');
        $('.upload_msg').html('');
        $('.rename_msg').html('');
        $('.msg').html('');

    });
    // rename text
</script>