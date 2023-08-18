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
    $customer_country = $customer_data['country'];
    $customer_type = $customer_data['customer_type'];
}
$origin_country_q = mysqli_query($con,"SELECT * from country");
$destination_country_q = mysqli_query($con,"SELECT * from country");
$origin_state_q = mysqli_query($con,"SELECT * from state");
$destination_state_q = mysqli_query($con,"SELECT * from state");
if (!function_exists('getProducts')) {
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
if (isset($_POST['settle'])) {
    $product_type_id = isset($_POST['product_type_id']) ? $_POST['product_type_id'] : '';
    $customer_id = $_POST['customer_id'];
    $weight = $_POST['weight'];
    $order_type = isset($_POST['order_type']) ? $_POST['order_type'] : '';
    $origin = isset($_POST['origin']) ? $_POST['origin'] : '';
    $destination = isset($_POST['destination']) ? $_POST['destination'] : ''; 
    // echo "<pre>";
    // print_r($_POST);
    // die;
    include '../../../price_calculation.php';
    $delivery = delivery_calculation($origin, $destination, $weight, $customer_id, $order_type, $product_type_id, 'international');
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
    // if (empty($_POST['delivery_charges'])) {
    //     $err_response = array();
    //     $err_response['error'] = 1;
    //     $err_response['alert_msg'] = "Invalid charges calculation. please enter valid origin,destination and service type";
    //     echo json_encode($err_response);
    //     exit();
    // }
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
    // $original_no  = preg_replace('/[^0-9]/s', '', $original_no);
    // $pos0 = substr($original_no, 0, 1);
    // if ($pos0 == '3') {
    //     $alterno = substr($original_no, 1);
    //     $alterno = '0' . $original_no;
    //     $original_no = $alterno;
    // }
    // $pos = substr($original_no, 0, 2);
    // if ($pos == '03') {
    //     $alterno = substr($original_no, 1);
    //     $alterno = '92' . $alterno;
    //     $original_no = $alterno;
    // }
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
    $insert_qry = "INSERT INTO `orders`(`sname`,`sbname`,`sphone`, `semail`, `sender_address`, `sstate`, `scity`, `szip`, `sfax`, `shipper_reference`, `rname`,`remail`, `rphone`, `receiver_address`,`rstate`, `rcity`, `rzip`, `rfax`, `eori_number`, `iec_number`,`google_address`,`pickup_date`,`pickup_time`,`price`,`collection_amount`,`order_date`,`payment_method`,`customer_id`,`origin`,`destination`,`tracking_no`,`weight`,`product_desc`,`special_instruction`,`quantity`,`product_id`, `order_type`,`ref_no`,`excl_amount`,`pft_amount`,`inc_amount`,`is_ondesk`,`map_latitude`,`map_longitude`,`is_fragile`,`order_type_booking`,`Pick_location`,`current_branch`,`net_amount`, `grand_total_charges`,`special_charges`, `insured_item_value`, `scnic`,`insurance_type`,`insured_premium`,`extra_charges`,`fuel_surcharge`,`fuel_surcharge_percentage`,`booking_type`,`payment_status`,`order_time`,`origin_area_id`,`user_id`,`product_type_id`,`pickup_latitude`,`pickup_longitude`,`description`,`customer_currency`,`ref_1`,`ref_2`,`length`,`width`,`height`,`collection_date`,`collection_ready_time`,`vehicle_Type`,`order_booking_type`) VALUES ('" . $_POST['fname'] . "','" . $_POST['bname'] . "','" . $_POST['mobile_no'] . "','" . $_POST['email'] . "','" . $_POST['pickup_address'] . "','" . $_POST['sstate'] . "','" . $_POST['scity'] . "','" . $_POST['szip'] . "','" . $_POST['sfax'] . "','" . $_POST['shipper_reference'] . "','" . $_POST['receiver_name'] . "','" . $_POST['receiver_email'] . "','" . $original_no . "','" . $_POST['receiver_address'] . "','" . $_POST['rstate'] . "','" . $_POST['rcity'] . "','" . $_POST['rzip'] . "','" . $_POST['rfax'] . "','" . $_POST['eori_number'] . "','" . $_POST['iec_number'] . "','" . $_POST['google_address'] . "','" . $date . "','" . $_POST['order_time'] . "','" . $_POST['delivery_charges'] . "','" . $_POST['collection_amount'] . "','" . $order_date . "','CASH','" . $customer_id . "','" . $_POST['origin'] . "','" . $_POST['destination'] . "','" . $tracking_no . "','" . $_POST['weight'] . "','" . $product_desc . "','" . $special_instruction . "' ,'" . $_POST['quantity'] . "','" . $_POST['product_id'] . "', '" . $_POST['order_type'] . "','" . $ref_no . "','" . $excl_amount . "','" . $pft_amount . "','" . $inc_amount . "','1','" . $map_latitude . "','" . $map_longitude . "','" . $is_fragile . "', 2,'" . $pickup_address_new . "','" . $_POST['branch_id'] . "','" . $net_amount . "','" . $total_charges . "','" . $special_charges . "','" . $insured_item_value . "','" . $_POST['scnic'] . "','" . $insurance_type . "','" . $insured_premium . "','" . $extra_charges . "','" . $fuel_surcharge . "','" . $fuel_surcharge_percentage . "', " . $booking_type . ",'" . $payment_status . "','" . $order_time . "','" . $_POST['origin_area_id'] . "','" . $user_id . "','" . $product_type_id . "','" . $pickup_latitude . "','" . $pickup_longitude . "','" . $_POST['description'] . "','" . $_POST['customer_currency'] . "','" . $_POST['ref_1'] . "','" . $_POST['ref_2'] . "','" . $_POST['length'] . "','" . $_POST['width'] . "','" . $_POST['height'] . "','" . $_POST['collection_date'] . "','" . $_POST['collection_ready_time'] . "','" . $_POST['vehicle_Type'] . "',1) ";
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
        // $next_number = 0;
        // $client_code = isset($customer_data['client_code']) ? $customer_data['client_code'] : '';
        // $customer_city = isset($customer_data['city']) ? $customer_data['city'] : '';
        // $cityQ = mysqli_fetch_assoc(mysqli_query($con, "SELECT area_code from cities where city_name='$customer_city'"));
        // $area_code = isset($cityQ['area_code']) ?  $cityQ['area_code'] : '';
        // $nextNoSql = "SELECT COUNT(id) as max_no from orders where customer_id=$customer_id";
        // $nextNoQ = mysqli_query($con, $nextNoSql);
        // $nextNoRes = mysqli_fetch_assoc($nextNoQ);
        // $nextNo = isset($nextNoRes['max_no']) ? $nextNoRes['max_no'] : '';
        // if (isset($nextNo) && !empty($nextNo) && $nextNo > 0) {
        //     $nextNo = $nextNo + 1;
        // } else {
        //     $nextNo = 1;
        // }
        // $get_number = $client_code * 10000000;
        // $next_number = $get_number + $nextNo;
        // $next_number = $area_code . $next_number;
        if (isset($customer_data['is_order_manual']) and $customer_data['is_order_manual'] == 1) {
            $track_no = $_POST['track_no'];
        } 
        // elseif ($next_number > 0) {
        //     $track_no = $next_number;
        // } 
        else {
            $track_no = $insert_id + 11200001000;
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
        if (isset($_POST['submit_order']) && $_POST['submit_order'] == '1') {
            ob_clean();
            echo json_encode(['id' => $insert_id, 'print' => 1, 'track_no' => $track_no]);
            exit();
        } else {
            ob_clean();
            echo json_encode(['id' => $insert_id, 'track_no' => $track_no]);
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
?>
<style type="text/css">
    .calculation_label {
        font-size: 14px !important;
    }

    .form-horizontal .control-label {
        padding-top: 0;
    }

    label,
    .calculation_label {
        font-weight: 500;
    }

    .booking_row {
        padding-bottom: 94px;
    }
</style>
<div class="panel-heading order_box">International Booking </div>
<div class="panel-body" id="same_form_layout">
    <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
        <div class="row">
            <div class="col-sm-12 dashboard" id="booking_form" style="padding: 0;">
                <div class="white shipper_box" style="    padding: 10px 0;">
                    <div id='msg'></div>
                    <form role="form" action="booking.php" method="POST" id="booking_form">
                        <input type="hidden" name="active_customer_id" id="active_customer_id"  class="active_customer"
                        value="<?php echo $customer_id; ?>">
                        <input type="hidden" name="" class="total_gst" value="0">
                        <div class="row">
                            <div class="col-sm-2 padd_none">
                                <div class="form-group">
                                    <?php $branch_id = '';
                                    if (isset($_GET['branch_id'])) {
                                        $branch_id = $_GET['branch_id'];
                                    } else {
                                        $branch_id = $_SESSION['branch_id'];
                                    }
                                    ?>
                                    <label><span style="color: red;">*</span><?php echo getLange('selectcustomer'); ?>
                                </label>
                                <select class="form-control active_customer_detail js-example-basic-single"
                                onchange="window.location.href='booking_form_new.php?customer_id='+this.value+'&branch_id=<?php echo $branch_id; ?>'">
                                <option selected disabled><?php echo getLange('selectcustomer'); ?> </option>
                                <?php foreach ($customers as $customer) { ?>
                                    <option <?php if (isset($_GET['customer_id']) && $_GET['customer_id'] == $customer['id']) {
                                        echo "Selected";
                                    } ?> value="<?php echo $customer['id']; ?>">
                                    <?php echo $customer['fname'] . (($customer['bname'] != '') ? ' (' . $customer['bname'] . ')' : ''); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-2 ">
                    <div class="form-group">
                        <label><?php echo getLange('branch'); ?></label>
                        <?php if (isset($_SESSION['branch_id']) && $_SESSION['branch_id'] != 1) : ?>
                            <input class="form-control" type="text" value="<?php echo $current_branch; ?>"
                            readonly>
                            <input class="form-control" type="hidden" name="branch_id"
                            value="<?php echo $_SESSION['branch_id']; ?>">
                            <?php else : ?>
                                <?php $customer_id = '';
                                if (isset($_GET['customer_id'])) {
                                    $customer_id = $_GET['customer_id'];
                                }
                                ?>
                                <select class="form-control active_branch_detail js-example-basic-single"
                                name="branch_id">
                                <option selected disabled><?php echo 'Select Branch'; ?> </option>
                                <?php foreach ($branches as $branch) { ?>
                                    <option <?php if (isset($_GET['branch_id']) && $_GET['branch_id'] == $branch['id']) {
                                        echo "Selected";
                                    } ?> value="<?php echo $branch['id']; ?>">
                                    <?php echo $branch['name']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-sm-2 sidegap">
                <div class="form-group">
                    <label><span style="color: red;">*</span><?php echo getLange('producttype'); ?>
                </label>
                <select class="form-control product_type_id" required="true" name="product_type_id">
                    <!-- <option value="">--select</option> -->
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
        <div class="col-sm-2 sidegap">
            <div class="form-group">
                <label><span style="color: red;">*</span><?php echo getLange('servicetype'); ?>
            </label>
            <select class="form-control order_type" name="order_type" required="true">
                <!-- <option value="">--select--</option> --> -->
                                        <!--  < <?php if (isset($get_service_types) && !empty($get_service_types)) {
                                                    while ($row = mysqli_fetch_array($get_service_types)) {
                                                ?>
                                    <option value="<?php echo $row['id']; ?>" data-id="<?php echo $row['id']; ?>"><?php echo isset($row['service_type']) ? $row['service_type'] : ''; ?></option>
                              <?php  }
                          } ?> -->
                      </select>
                  </div>
              </div>
              <div class="col-sm-2 padd_none">
                <div class="form-group">
                    <label><?php echo getLange('orderdate'); ?> </label>
                    <input type="text" value="<?php echo date('d/m/Y'); ?>"
                    class="form-control datepicker" name="order_date">
                </div>
            </div>
            <div class="col-sm-2 ">
                <div class="form-group">
                    <label><?php echo getLange('ordertime'); ?> </label>
                    <input type="time" value="<?php echo date('H:i:s'); ?>"
                    class="form-control timepicker" name="order_time">
                </div>
            </div>
            <div class="col-sm-2 padd_none">
                <div class="form-group">
                    <label style="cursor: pointer;"><?php echo getLange('booking_type'); ?> <i
                        class="fa fa-info info_icon"></i>
                        <div class="info_box_details">
                            <ul>
                                <li><b><i class="lnr lnr-file-empty"></i>
                                    <?php echo getLange('Invoice'); ?>:</b><?php echo getLange('paymentsettlementwouldbeviainvoice') ?>.
                                </li>
                                <li><b><i class="lnr lnr-briefcase"></i>
                                    <?php echo getLange('cash'); ?>:</b>
                                    <?php echo getLange('senderinpayingthedeliveryfeeontimeoforder'); ?>.
                                </li>
                                <li><b><i class="fa fa-credit-card"></i>
                                    <?php echo getLange('topay'); ?>:</b>
                                    <?php echo getLange('receiverwillpaythedeliveryfeeontimeofdelivery') ?>.
                                </li>
                            </ul>
                        </div>
                    </label>
                    <select class="form-control booking_type" name="booking_type">
                        <option value="1">Invoice</option>
                        <option value="2">Cash</option>
                        <option value="3">To Pay</option>
                    </select>
                </div>
            </div>
            <?php if (isset($customer_data['is_order_manual']) and $customer_data['is_order_manual'] == 1) : ?>
                <div class="col-sm-2 left_right_none">
                    <div class="form-group">
                        <label><span style="color: red;">*</span><?php echo getLange('orderno'); ?> </label>
                        <input type="text" name="track_no" value="" class="form-control">
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading"><?php echo getLange('pickupdetail'); ?><span
                        style="float: right;" class="right_order"></span></div>
                        <div class="panel-body booking_row">

                            <div class="row">
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
                                       <!--  <?php while ($row = mysqli_fetch_array($origin_country_q)) { ?>
                                            <option value=" <?php echo $row['id']; ?>"
                                             <?php if ($row['id'] == $customer_country) {
                                                echo "selected";
                                            } ?>>
                                            <?php echo $row['country_name']; ?></option>
                                            <?php } ?>> -->
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
                                       <!--  <?php while ($row = mysqli_fetch_array($origin_state_q)) { ?>
                                            <option <?php if ($row['state_name'] == $customer_city) {
                                                echo "selected";
                                            } ?>>
                                            <?php echo $row['state_name']; ?></option>
                                            <?php } ?>> -->
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
                                        name="scity">
                                        <!-- <?php while ($row = mysqli_fetch_array($origin_q)) { ?>
                                            <option <?php if ($row['city_name'] == $customer_city) {
                                                echo "selected";
                                            } ?>>
                                            <?php echo $row['city_name']; ?></option>
                                            <?php } ?>> -->
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 padd_left">
                                    <div class="form-group">
                                        <label class="control-label"><span
                                            style="color: red;">*</span><?php echo getLange('name'); ?>
                                        </label>
                                        <input type="hidden" name="bname" value="" class="shipper_bname">
                                        <input type="text" class="form-control shipper_fname"
                                        value="<?php echo isset($customer_data['fname']) ? $customer_data['fname'] : '';  ?>"
                                        name="fname"
                                        placeholder="<?php echo getLange('shipper') . ' ' . getLange('name'); ?>"
                                        required="true"
                                        <?php echo isset($customer_id) && $customer_id != 1 ? 'readonly' : ''; ?>>
                                    </div>
                                </div>
                                <div class="col-sm-4 padd_left" style="padding-right:0;">

                                    <div class="form-group">
                                        <label class="control-label"><span style="color: red;">*</span>
                                            <?php echo getLange('phone'); ?></label>
                                            <input type="text" class="form-control shipper_mob"
                                            value="<?php echo isset($customer_data['mobile_no']) ? $customer_data['mobile_no'] : '';  ?>"
                                            name="mobile_no"
                                            placeholder="<?php echo getLange('shipper') . ' ' . getLange('phone'); ?>"
                                            required="true"
                                            <?php echo isset($customer_id) && $customer_id != 1 ? 'readonly' : ''; ?>>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label"><span
                                                style="color: red;"></span><?php echo getLange('email'); ?>
                                            </label>
                                            <input type="email"
                                            value="<?php echo isset($customer_data['email']) ? $customer_data['email'] : '';  ?>"
                                            class="form-control shipper_email" name="email"
                                            placeholder="<?php echo getLange('shipper') . ' ' . getLange('email'); ?>"
                                            <?php echo isset($customer_id) && $customer_id != 1 ? 'readonly' : ''; ?>>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 padd_left" style="padding-right:0;">
                                        <div class="form-group">
                                            <label class="control-label">
                                                <?php echo getLange('cnic'); ?></label>
                                                <input type="text" class="form-control"
                                                value="<?php echo isset($customer_data['cnic']) ? $customer_data['cnic'] : '';  ?>"
                                                name="scnic" placeholder="<?php echo getLange('cnic'); ?>"
                                                <?php echo isset($customer_id) && $customer_id != 1 ? 'readonly' : ''; ?>>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-lg-4 padd_left" style="padding-right:0;">
                                            <div class="form-group">
                                                <label class="control-label">Shipper's Reference</label>
                                                <input autocomplete="false" type="text" class="form-control"
                                                name="shipper_reference" placeholder="Shipper Reference">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- <div class="col-sm-6  col-lg-4">
                                            <div class="form-group">
                                                <label class="control-label"><span
                                                    style="color: red;"></span>State</label>
                                                    <input type="text"
                                                    value="<?php echo isset($customer_data['state']) ? $customer_data['state'] : '';  ?>"
                                                    class="form-control" name="sstate" placeholder="State">
                                                </div>
                                            </div> -->
                                            <div class="col-sm-6 col-lg-6 padd_left" style="padding-right:0;">
                                                <div class="form-group">
                                                    <label class="control-label"> Zip</label>
                                                    <input type="text" class="form-control"
                                                    value="<?php echo isset($customer_data['zip']) ? $customer_data['zip'] : '';  ?>"
                                                    name="szip" placeholder="Zip">
                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-lg-6 padd_left" style="padding-right:0;">
                                                <div class="form-group">
                                                    <label class="control-label">Fax</label>
                                                    <input autocomplete="false" type="text" class="form-control"
                                                    name="sfax" placeholder="Fax">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 padd_left" style="padding-right:0;">
                                                <div class="form-group">
                                                    <label class="control-label"><span style="color: red;">*</span>
                                                        <?php echo getLange('address'); ?></label>
                                                    <!-- <textarea style="height: 101px;" autocomplete="false"
                                                        class="form-control shipper_address" name="pickup_address"
                                                        placeholder="<?php echo getLange('sender') . ' ' . getLange('address'); ?>"
                                                        required="true"
                                                        <?php echo isset($customer_id) && $customer_id != 1 ? 'readonly' : ''; ?>>
                                                        <?php echo isset($customer_data['address']) ? $customer_data['address'] : '';  ?></textarea> -->

                                                        <textarea style="height: 101px;" placeholder="Sender Address"
                                                        class="form-control shipper_address" name="pickup_address"
                                                        required
                                                        <?php echo isset($customer_id) && $customer_id != 1 ? 'readonly' : ''; ?>><?php echo isset($customer_data['address']) ? $customer_data['address'] : '';  ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 padd_left" style="padding-right:0;">
                                                    <div class="form-group">
                                                        <label
                                                        class="control-label"><?php echo getLange('pickupaddress'); ?>
                                                    </label>
                                                    <textarea autocomplete="false" class="form-control shipper_address"
                                                    name="pickup_address_new"
                                                    placeholder="<?php echo getLange('pickupaddress'); ?>"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="row">
                                            <div class="col-sm-12 padd_left" style="padding-right:0;">
                                                <div class="form-group">
                                                    <input autocomplete="false" type="checkbox"
                                                        name="s_save_sender_to_address_book" value="1">
                                                    <label class="control-label">Save Sender to Address Book</label>
                                                </div>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading"><?php echo getLange('deliverydetail'); ?> <span
                                        style="float: right;" class="right_order"></span></div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-sm-3 padd_left padd_right">
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
                                                    <div class="col-sm-3 padd_left padd_right">
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
                                                                data-target="#exampleModal"> <i
                                                                class="fa fa-plus"></i></a></label>
                                                                <select
                                                                class="form-control destination_city  js-example-basic-single"
                                                                name="rcity">
                                                                <!-- <option value="0"><?php echo getLange('select'); ?></option>
                                                                    <?php echo $destination_cities_list; ?> -->
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <?php if (getConfig('manual_area') == 1) { ?>
                                                            <div class="col-sm-3 padd_left">
                                                                <div class="form-group">
                                                                    <label class="control-label"><span style="color: red;">*</span>
                                                                    City</label>
                                                                    <select
                                                                    class="form-control area origin_select js-example-basic-single"
                                                                    name="origin_area_id">
                                                                    <option value=""><?php echo getLange('select'); ?></option>
                                                                    <?php while ($row = mysqli_fetch_assoc($destinationViseArea)) { ?>
                                                                        <option value="<?php echo $row['id']; ?>">
                                                                            <?php echo $row['area_name']; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                        <div class="col-sm-3 padd_left">
                                                            <div class="form-group">
                                                                <label class="control-label"><span style="color: red;">*</span>
                                                                    <?php echo getLange('name'); ?></label>
                                                                    <input type="text" class="form-control" name="receiver_name"
                                                                    placeholder="<?php echo getLange('consignee') . ' ' . getLange('name'); ?>"
                                                                    required="true">
                                                                </div>
                                                            </div>
                                            <!-- <div class="col-sm-3 padd_right">

                                                <div class="form-group">
                                                    <label class="control-label"><span style="color: red;">*</span>
                                                        <?php echo getLange('phone'); ?></label>
                                                    <div class="phone_search"
                                                        style="position: absolute;right: 9px;top: 31px;">
                                                        <i class="fa fa-search search_phone"></i>
                                                    </div>
                                                    <input type="text" class="form-control" name="receiver_phone"
                                                        placeholder="<?php echo getLange('consignee') . ' ' . getLange('phone'); ?>"
                                                        required="true">
                                                    <div class="r_phone_msg"></div>
                                                </div>
                                            </div> -->
                                            <div class="col-sm-3 " >
                                                <div class="form-group">
                                                    <label><?php echo getLange('phone'); ?></label>
                                                    <input type="text" class="form-control" placeholder="Phone Number"
                                                    name="receiver_phone" required="true">
                                                </div> 
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="row">

                                                <div class="col-sm-6 col-lg-4 padd_left" style="padding-right:0;">
                                                    <div class="form-group">
                                                        <label class="control-label"><span style="color: red;"></span>
                                                            <?php echo getLange('email'); ?></label>
                                                            <input type="email" class="form-control" name="receiver_email"
                                                            placeholder="<?php echo getLange('consignee') . ' ' . getLange('email'); ?>">
                                                        </div>
                                                    </div>

                                                    <!-- <div class="col-sm-6 col-lg-2">
                                                        <div class="form-group">
                                                            <label class="control-label"><span
                                                                style="color: red;"></span>State</label>
                                                                <input type="text" class="form-control" name="rstate"
                                                                placeholder="State">
                                                            </div>
                                                        </div> -->
                                                        <div class="col-sm-6 col-lg-4 padd_left" style="padding-right:0;">
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

                                                </div>

                                                <div class="row">
                                                 <div class="col-sm-6 col-lg-6 padd_left" style="padding-right:0;">
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
                                                    <div class="col-sm-12 padd_left" style="padding-right:0;">
                                                        <div class="form-group">
                                                            <label class="control-label"></span>
                                                                <?php echo getLange('address'); ?></label>
                                                                <!-- <textarea class="form-control" name="receiver_address"  placeholder="Consignee Address" required="true"></textarea> -->
                                                                <input autocomplete="false" name="google_address"
                                                                class="address form-control" type="text" value=""
                                                                id="property_add"
                                                                placeholder="<?php echo getLange('consignee') . ' ' . getLange('address'); ?>">
                                                                <i aria-hidden="true" className="dot circle outline link icon"
                                                                id="location-button"></i>
                                                                <input type="hidden" class="form-control" id="latitude"
                                                                name="latitude">
                                                                <input type="hidden" class="form-control" id="longitude"
                                                                name="longitude">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="mapping" id="mapping"
                                                                        style="width: 100%; height: 130px;margin-bottom: 10px;">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                        <!-- <div class="row">
                                            <div class="col-sm-12 padd_left" style="padding-right:0;">
                                                <div class="form-group">
                                                    <input autocomplete="false" type="checkbox"
                                                        name="r_save_sender_to_address_book" value="1">
                                                    <label class="control-label">Save Sender to Address Book</label>
                                                </div>
                                            </div>
                                        </div>  -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 sidegap" style="padding: 0;">
                                <div class="row">
                                    <div class="col-sm-6" style="padding-right: 0;">
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
                                                        <div class="col-sm-3 width_33">
                                                            <div class="form-group">
                                                                <label> <?php echo getLange('orderid'); ?> .</label>
                                                                <input type="text" name="product_id" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3 ">
                                                            <div class="form-group">
                                                                <label class="calculation_label"><span
                                                                    style="color: red;">*</span>
                                                                    <?php echo getLange('noofpiece'); ?></label>
                                                                    <input type="myNumber" name="quantity"
                                                                    class="form-control pieces" required="true" value="1">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3 ">
                                                                <div class="form-group">
                                                                    <label class="calculation_label"><span
                                                                        style="color: red;">*</span>
                                                                        <?php echo getLange('weightkg'); ?></label>
                                                                        <input type="myNumber" name="weight"
                                                                        class="form-control weight" required="true" value="0.5">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3   ">
                                                                    <div class="form-group"> <label><span
                                                                        style="color: red;">*</span><?php echo getLange('codamount'); ?></label>
                                                                        <input type="text" name="collection_amount"
                                                                        class="form-control allownumericwithdecimal"
                                                                        required="true" value="0">
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

                                                                        <div class="col-sm-12 col-lg-6">
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
                                                                        <div class="col-sm-6 width_35 padd_left">
                                                                            <div class="form-group">
                                                                                <label
                                                                                class="calculation_label"><?php echo getLange('fragile'); ?>
                                                                            </label>
                                                                            <select
                                                                            class="form-control insurance_rate is_fragile js-example-basic-single"
                                                                            name="insurance_type" required>
                                                                            <option value="" selected disabled="true">Select
                                                                            Insurance Type</option>
                                                                            <?php while ($insuranceval = mysqli_fetch_array($insurance)) { ?>
                                                                                <option
                                                                                <?php echo (isset($insuranceval['id']) && $insuranceval['id'] == 1) ? 'selected' : ''; ?>
                                                                                value="<?php echo $insuranceval['id'] ?>"
                                                                                id="insurancedata<?php echo $insuranceval['id'] ?>"
                                                                                data-attr="<?php echo $insuranceval['rate'] ?>">
                                                                                <?php echo $insuranceval['name']; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6 padd_left">
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
                                                    <div class="col-sm-6" id="charges_table">
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
                                                                                        <th><?php echo getLange('charges'); ?>
                                                                                    </th>
                                                                                    <th style="width: 42px;"></th>
                                                                                    <th><?php echo getLange('amount'); ?>
                                                                                </th>
                                                                            </tr>
                                                                            <?php
                                                                            if (!empty($customer_wise_charge)) {
                                                                                foreach ($customer_wise_charge as $k_s => $row) {
                                                                                    $c_id = $row['charge_id'];
                                                                                    $getSingleChargeDetail = getChargeSingleChargeDetail($c_id);
                                                                                    $row['charge_type'] = isset($getSingleChargeDetail['charge_type']) ? $getSingleChargeDetail['charge_type'] : '';
                                                                                    if (isset($row['customer_id']) && $row['customer_id'] == $customer_id) {
                                                                                        $row['charge_value'] = 0;
                                                                                    }
                                                                                    $total_amount_calc += $row['charge_value'];
                                                                                    $row['charge_name'] = isset($getSingleChargeDetail['charge_name']) ? $getSingleChargeDetail['charge_name'] : '';
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
                                                                                            <input type="checkbox"
                                                                                            class="change_charges"
                                                                                            value="<?php echo $c_id; ?>"
                                                                                            data-charges="<?php echo $row['charge_value'] ?>">
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="input-group">
                                                                                                <input
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
                                                                        } else { ?>
                                                                            <?php
                                                                            if (isset($other_charges) && !empty($other_charges)) {
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
                                                                                                        <input type="checkbox"
                                                                                                        class="change_charges"
                                                                                                        value="<?php echo $c_id; ?>"
                                                                                                        data-charges="<?php echo $row['charge_value'] ?>">
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <div class="input-group">
                                                                                                            <input
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
                                                                                    }
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
                                                                                name="special_charges" value="0"
                                                                                disabled>
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
                                        <div class="col-sm-6">
                                            <?php else : ?>
                                                <div class="col-sm-6">
                                                <?php endif; ?>
                                                <div class="panel panel-default" style="padding-bottom: 103px;">
                                                    <div class="panel-heading"><?php echo getLange('priceinformation'); ?>
                                                    <span style="float: right;" class="right_order"></span>
                                                </div>
                                                <div class="panel-body">
                                                    <!-- <div class="row"> <div class="col-sm-12 side_gap">
                                       <div class="form-group"> <label> <input style="    width: auto !important;"
                                       type="checkbox" name="manual_rate" class="manual_rates" value="0"> Manual
                                   Rates </label> </div> </div> </div> -->
                                   <div class="row">
                                    <div class="col-sm-3 side_gap
                                    padd_right">
                                    <div class="form-group"> <label
                                        class="calculation_label"><span
                                        style="color: red;">*</span>
                                        <?php echo getLange('deliveycharges'); ?></label>
                                        <input type="text" name="delivery_charges" class="form-control total_amount
                                        allownumericwithdecimal">
                                    </div>
                                </div>
                                <?php if ($admin_other_charges == '1') : ?>
                                    <div class="col-sm-3 side_gap
                                    padd_right">
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
                                    <div class="col-sm-3 side_gap">
                                        <div class="form-group">
                                            <label class="calculation_label"><span
                                                style="color: red;">*</span><?php echo getLange('extracharges'); ?>
                                            </label>
                                            <input type="number" name="extra_charges"
                                            class="form-control extra_charges" required="true"
                                            value="0">
                                        </div>
                                    </div>
                                    <?php else : ?>
                                        <input type="hidden" name="extra_charges"
                                        class="form-control extra_charges" required="true"
                                        value="0">
                                    <?php endif; ?>
                                    <?php if ($admin_insured_premium == '1') : ?>
                                        <div class="col-sm-3 side_gap">
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
                                            class="form-control insurance_value" required="true"
                                            value="0" disabled>
                                        <?php endif; ?>
                                        <div class="col-sm-3 side_gap">
                                            <div class="form-group">
                                                <label>
                                                    <?php echo getLange('totalcharges'); ?>
                                                </label>
                                                <input type="text" name="total_charges" value="0"
                                                readonly="true" class="form-control allownumericwithdecimal
                                                total_charges" required="true">
                                            </div>
                                        </div>
                                        <div class="col-sm-3 side_gap">
                                            <div class="form-group">
                                                <?php
                                                $fuelsurcharge_percent = getFuelValue($customer_id);
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
                                                class="form-control allownumericwithdecimal
                                                fuel_surcharge" required="true">
                                            </div>
                                        </div>
                                        <div class="col-sm-3 side_gap">
                                            <div class="form-group"> <label><span
                                                style="color: red;">*</span><?php echo getLange('salestax'); ?></label>
                                                <?php if ($checkGstExempt == 1) { ?> <input type="text"
                                                name="pft_amount" value="0" readonly="true"
                                                class="form-control"> <?php   } else {   ?> <input
                                                type="text" name="pft_amount" value="0"
                                                class="form-control allownumericwithdecimal
                                                pft_amount" required="true"> <?php } ?> </div>
                                            </div>
                                            <div style="clear: both;"
                                            class="col-sm-3  input_label_box main-box-x total_charged">
                                            <label><?php echo getLange('netamount'); ?></label>
                                            <input type="text" class="form-control" readonly="true"
                                            name="net_amount" value="0">
                                        </div>
                                        <div class="col-sm-4 side_gap" style="display:none">
                                            <div class="form-group"> <label><span style="color:
                                            red;">*</span><?php echo getLange('totalservicescharges'); ?></label>
                                            <input type="text" value="0" name="inc_amount"
                                            readonly="true" class="form-control allownumericwithdecimal
                                            inc_amount" required="true">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Commercial Invoive -->
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
                                                    <a href="" class="btn btn-info btn_commercial_invoice"
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

                    <!-- comm 1-->
                    <div class="row">
                        <div class="col-sm-4 parcel_box sidegap">
                            <div class="form-group">
                                <label><?php echo getLange('status'); ?> </label>
                                <select class="form-control js-example-basic-single" name="status">
                                    <?php if (isset($order_status) && !empty($order_status)) {
                                        while ($row = mysqli_fetch_array($order_status)) {
                                            ?>
                                            <option <?php if ($row['status'] == 'Parcel Received at office') {
                                                echo 'Selected';
                                            } ?>
                                            value="<?php echo isset($row['status']) ? $row['status'] : ''; ?>">
                                            <?php echo isset($row['status']) ? getKeyWord($row['status']) : ''; ?>
                                        </option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class='msgs'></div>
            </div>
            <input type="hidden" class="print_template"
            value="invoicehtml_new.php">
            <div class="save_print_btn">
                <input type="submit" name="save_order" class="btn btn-purple submit_btns"
                value="<?php echo getLange('save'); ?>">
                <a href="#"
                class="submit_order btn btn-purple submit_btns btn-purple"><?php echo getLange('saveprint'); ?></a>
            </div>
            <!-- <input type="submit" name="submit_order" class="btn btn-purple submit_btns" value="Save & Print" > -->
        </div>
    </div>
</form>
</div>
</div>
</div>
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">City</h5>

        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="control-label"><span style="color: red;"></span>
                City</label>
                <input type="text" class="form-control add_areas_booking" placeholder="Areas">
            </div>
            <div class="add_areas_booking_msg"></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary close_modal_booking" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary save_areas_booking">Save changes</button>
        </div>
    </div>
</div>
</div>

<script type="text/javascript">
    var telInput = $("#phone"),
    errorMsg = $("#error-msg"),
    validMsg = $("#valid-msg");

// initialise plugin
telInput.intlTelInput({

    allowExtensions: true,
    formatOnDisplay: true,
    autoFormat: true,
    autoHideDialCode: true,
    autoPlaceholder: true,
    defaultCountry: "auto",
    ipinfoToken: "yolo",

    nationalMode: false,
    numberType: "MOBILE",
    //onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
    preferredCountries: ['sa', 'ae', 'qa', 'om', 'bh', 'kw', 'ma'],
    preventInvalidNumbers: true,
    separateDialCode: true,
    initialCountry: "auto",
    geoIpLookup: function(callback) {
        $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
            var countryCode = (resp && resp.country) ? resp.country : "";
            callback(countryCode);
        });
    },
    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/utils.js"
});

var reset = function() {
    telInput.removeClass("error");
    errorMsg.addClass("hide");
    validMsg.addClass("hide");
};

// on blur: validate
telInput.blur(function() {
    reset();
    if ($.trim(telInput.val())) {
        if (telInput.intlTelInput("isValidNumber")) {
            validMsg.removeClass("hide");
        } else {
            telInput.addClass("error");
            errorMsg.removeClass("hide");
        }
    }
});

// on keyup / change flag: reset
telInput.on("keyup change", reset);
var telInput = $("#phone"),
errorMsg = $("#error-msg"),
validMsg = $("#valid-msg");

// initialise plugin
telInput.intlTelInput({

    allowExtensions: true,
    formatOnDisplay: true,
    autoFormat: true,
    autoHideDialCode: true,
    autoPlaceholder: true,
    defaultCountry: "auto",
    ipinfoToken: "yolo",

    nationalMode: false,
    numberType: "MOBILE",
    //onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
    preferredCountries: ['sa', 'ae', 'qa', 'om', 'bh', 'kw', 'ma'],
    preventInvalidNumbers: true,
    separateDialCode: true,
    initialCountry: "auto",
    geoIpLookup: function(callback) {
        $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
            var countryCode = (resp && resp.country) ? resp.country : "";
            callback(countryCode);

        });
    },
    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/utils.js"
});

var reset = function() {
    telInput.removeClass("error");
    errorMsg.addClass("hide");
    validMsg.addClass("hide");
};

// on blur: validate
telInput.blur(function() {
    reset();
    if ($.trim(telInput.val())) {
        if (telInput.intlTelInput("isValidNumber")) {
            validMsg.removeClass("hide");
        } else {
            telInput.addClass("error");
            errorMsg.removeClass("hide");
        }
    }
});

// on keyup / change flag: reset
telInput.on("keyup change", reset);
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
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        getServiceType();

        function getServiceType() {

            var product_type_id = $('.product_type_id').find(':selected').val();
            var customer_id = $('#active_customer_id').val(); 
            if (product_type_id) {
                $.ajax({
                    url: '<?php echo BASE_URL ?>getServiceType.php',
                    type: 'POST',
                    data: {
                        is_product: 1,
                        product_type_id: product_type_id,
                        customer_id: customer_id
                    },
                    success: function(response) {
                        var result = jQuery.parseJSON(response);
                        var service_types_options = '';
                        var service_types = result.service;
                        for (var i in service_types) {
                            service_types_options += '<option value="' + service_types[i].id +
                            '">';
                            service_types_options += service_types[i].service_type;
                            service_types_options += '</option>';
                        }
                        $('body').find('.order_type').html(service_types_options);
                    }
                })
            }
        }
        $('body').on('change', '.product_type_id', function(event) {
            event.preventDefault();
            getServiceType();
        });
    }, false);
// document.addEventListener('DOMContentLoaded', function() {
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
            // document.getElementById('receiver_address').value = address.results[0].formatted_address;
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
                        marker.getPosition().lng());
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
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;
}
</script>