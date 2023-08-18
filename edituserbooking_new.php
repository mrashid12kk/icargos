<?php
session_start();
date_default_timezone_set("Asia/Karachi");
include_once "includes/conn.php";
$id = $_GET['id'];
include "admin/includes/functions.php";
$edit_flag = validateEditOrder($id);
$query = mysqli_query($con, "select * from orders where id='$id'") or die(mysqli_error($con));
$row2 = mysqli_fetch_array($query);


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
if (isset($_POST['submit_order']) || isset($_POST['save_order'])) {
   
    $customer_id = 0;
    $plocation = '';
    $dlocation = '';
    if (isset($_SESSION['customers'])) {

        //if manually order enable check validation for order no
        if (isset($customer_data['is_order_manual']) and $customer_data['is_order_manual'] == 1) :
            if (isset($_POST['track_no']) and !empty($_POST['track_no'])) {
                $track_no = $_POST['track_no'];
                $check_track_no_exist = mysqli_query($con, "SELECT id FROM orders WHERE track_no= '" . $track_no . "' ");

                if (mysqli_num_rows($check_track_no_exist) > 0) {
                    // $err_response = array();
                    // $err_response['error'] = 1;
                    // $err_response['alert_msg'] = "Order no already exist.";
                    // echo json_encode($err_response); exit();
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
        //  if((int)$_POST['price']){
        //  $err_response = array();
        //  $err_response['error'] = 1;
        //  $err_response['alert_msg'] = "Invalid charges calculation. please check pickup city,delivery city and service type";
        //  echo json_encode($err_response); exit();
        // }
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

        $zone_id = '';
        $origin = $_POST['origin'];
        $destination = $_POST['destination'];
        $zone_q = mysqli_query($con, "SELECT zone FROM zone_cities WHERE origin='" . $origin . "' AND  ( destination='" . $destination . "' or destination ='other' or destination ='others')  ");
        if (mysqli_num_rows($zone_q)) {
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
        $id = $_POST['id'];
        $edit_flag = validateEditOrder($id);
        if ($edit_flag == 1) {
            $err_response = array();
            $err_response['error'] = 1;
            $err_response['alert_msg'] = getLange('alert_message_edit_validate');
            echo json_encode($err_response);
            exit();
        }
        $flyer_qty = isset($_POST['flyer_qty']) ? $_POST['flyer_qty'] : 0;
        $ref_no = isset($_POST['ref_no']) ? $_POST['ref_no'] : '';
        $remail = isset($_POST['receiver_email']) ? $_POST['receiver_email'] : '';
        $net_amount = isset($_POST['net_amount']) ? $_POST['net_amount'] : 0;
        $special_charges = isset($_POST['special_charges']) ? $_POST['special_charges'] : 0;
        $total_charges = isset($_POST['total_charges']) ? $_POST['total_charges'] : 0;
        $fuel_surcharge = isset($_POST['fuel_surcharge']) ? $_POST['fuel_surcharge'] : 0;
        $fuel_surcharge_percentage = isset($_POST['fuel_surcharge_percentage']) ? $_POST['fuel_surcharge_percentage'] : 0;
        $extra_charges = isset($_POST['extra_charges']) ? $_POST['extra_charges'] : 0;
        $excl_amount = isset($_POST['excl_amount']) ? $_POST['excl_amount'] : 0;
        $pft_amount = isset($_POST['pft_amount']) ? $_POST['pft_amount'] : 0;
        $inc_amount = isset($_POST['inc_amount']) ? $_POST['inc_amount'] : 0;
        $insured_premium = isset($_POST['insured_premium']) ? $_POST['insured_premium'] : 0;
        $insured_item_value = isset($_POST['insured_item_value']) ? $_POST['insured_item_value'] : '';
        $insurance_type = isset($_POST['insurance_type']) ? $_POST['insurance_type'] : '';
        $pickup_address_new = isset($_POST['pickup_address_new']) ? $_POST['pickup_address_new'] : '';
        $product_type_id = isset($_POST['product_type_id']) ? $_POST['product_type_id'] : '';
        $insert_qry = "UPDATE `orders` SET sname='" . $_POST['fname'] . "',sbname='" . $_POST['bname'] . "',sphone='" . $_POST['mobile_no'] . "', semail='" . $_POST['email'] . "', sender_address='" . $_POST['pickup_address'] . "', `sstate`='" . $_POST['sstate'] . "', `scity`='" . $_POST['scity'] . "', `szip`='" . $_POST['szip'] . "', `sfax`='" . $_POST['sfax'] . "', `shipper_reference`='" . $_POST['shipper_reference'] . "', remail='" . $_POST['receiver_email'] . "', rname='" . $_POST['receiver_name'] . "', rphone='" . $original_no . "', receiver_address='" . $_POST['receiver_address'] . "',`rstate`='" . $_POST['rstate'] . "', `rcity`='" . $_POST['rcity'] . "', `rzip`='" . $_POST['rzip'] . "', `rfax`='" . $_POST['rfax'] . "', `eori_number`='" . $_POST['eori_number'] . "', `iec_number`='" . $_POST['iec_number'] . "',google_address='" . $_POST['google_address'] . "',pickup_date='" . $date . "',price='" . $_POST['delivery_charges'] . "',collection_amount='" . $_POST['collection_amount'] . "',order_date='" . $order_date . "',payment_method='CASH',customer_id='" . $customer_id . "',origin='" . $_POST['origin'] . "',destination='" . $_POST['destination'] . "',weight='" . $_POST['weight'] . "',product_desc='" . $_POST['product_desc'] . "',special_instruction='" . $_POST['special_instruction'] . "' ,quantity='" . $_POST['quantity'] . "',product_id='" . $_POST['product_id'] . "', order_type='" . $_POST['order_type'] . "', ref_no='" . $ref_no . "',flyer_qty='" . $flyer_qty . "',excl_amount='" . $excl_amount . "',pft_amount='" . $pft_amount . "',inc_amount='" . $inc_amount . "',order_type_booking='4',Pick_location='" . $pickup_address_new . "',insured_item_value='" . $insured_item_value . "',insured_premium='" . $insured_premium . "',special_charges='" . $special_charges . "',extra_charges='" . $extra_charges . "',extra_charges='" . $extra_charges . "',fuel_surcharge_percentage='" . $fuel_surcharge_percentage . "',fuel_surcharge='" . $fuel_surcharge . "',grand_total_charges='" . $total_charges . "',net_amount='" . $net_amount . "',product_type_id='" . $product_type_id . "',`description`='" . $_POST['description'] . "',`customer_currency`='" . $_POST['customer_currency'] . "',`ref_1`='" . $_POST['ref_1'] . "',`ref_2`='" . $_POST['ref_2'] . "',`length`='" . $_POST['length'] . "',`width`='" . $_POST['width'] . "',`height`='" . $_POST['height'] . "',`collection_date`='" . $_POST['collection_date'] . "',`collection_ready_time`='" . $_POST['collection_ready_time'] . "',`vehicle_Type`='" . $_POST['vehicle_Type'] . "'  WHERE id='$id'";

        $query = mysqli_query($con, $insert_qry);
        // $insert_id=mysqli_insert_id($con);
        $insert_id = $id;
        if (isset($_POST['origin_area_id']) && !empty($_POST['origin_area_id'])) {
            mysqli_query($con, "UPDATE orders set origin_area_id=" . $_POST['origin_area_id'] . " Where id = $id");
        }
        if ($insert_id > 0) {
         if (isset($_POST['c_i_discription']) && !empty($_POST['c_i_discription'])) {
            $c_i_discription = $_POST['c_i_discription'];
            $c_i_pieces = $_POST['c_i_pieces'];
            $c_i_price = $_POST['c_i_price'];
            $c_i_coo = $_POST['c_i_coo'];
            $c_i_hs_code = $_POST['c_i_hs_code'];
            $c_i_hs_total = $_POST['c_i_hs_total'];
            mysqli_query($con, "DELETE FROM order_commercial_invoice WHERE order_id='" . $insert_id . "'");
            foreach ($c_i_discription as $key => $value) {
                if ($value != '' or $c_i_pieces[$key] or $c_i_price[$key] or $c_i_coo[$key] or $c_i_hs_code[$key] or $c_i_hs_total[$key]) {
                    $insert_comercial_invoice = "INSERT INTO `order_commercial_invoice`(`order_id`,`c_i_discription`,`c_i_pieces`,`c_i_price`, `c_i_coo`, `c_i_hs_code`, `c_i_hs_total`) VALUES ('" . $insert_id . "','" . $value . "','" . $c_i_pieces[$key] . "','" . $c_i_price[$key] . "','" . $c_i_coo[$key] . "','" . $c_i_hs_code[$key] . "','" . $c_i_hs_total[$key] . "') ";
                    mysqli_query($con, $insert_comercial_invoice);
                }
            }
        }
        $listcharges = mysqli_query($con, "SELECT * FROM charges");
        if (isset($listcharges) && !empty($listcharges)) {
            while ($row_charge = mysqli_fetch_array($listcharges)) {
                $row_id = $row_charge['id'];
                $dynamic_var_c_id = 'charge_id' . $row_id;
                if (isset($dynamic_var_c_id)) {
                    $dynamic_var_c_type = 'charge_type' . $row_id;
                    $dynamic_var_c_amnt = 'charge_amount' . $row_id;
                    $charge_type   = $_POST[$dynamic_var_c_type];
                    $charge_amount = $_POST[$dynamic_var_c_amnt];
                    $charge_id     = $_POST[$dynamic_var_c_id];
                    mysqli_query($con, "DELETE FROM order_charges WHERE charges_id='" . $charge_id . "' AND order_id='" . $insert_id . "'");
                    if (isset($charge_amount) && $charge_amount > 0) {
                        mysqli_query($con, "INSERT INTO order_charges(`charges_id`,`charges_type`,`charges_amount`,`order_id`,`created_on`) VALUES ('" . $charge_id . "', '" . $charge_type . "', '" . $charge_amount . "', '" . $insert_id . "','" . $date . "') ");
                    }
                }
            }
        }
    }
    $track_no = $_POST['track_no'];
    if (!$insert_id) {
        $err_response = array();
        $err_response['error'] = 1;
        $err_response['alert_msg'] = $insert_qry;
        echo json_encode($err_response);
        exit();
    }
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
exit();
}





//order process////////////////
$customer_origin_zone_q = mysqli_query($con, " SELECT GROUP_CONCAT(DISTINCT zone_id SEPARATOR ',') as zone_ids
    FROM customer_pricing WHERE customer_id='" . $customer_id . "'  ");
if (mysqli_num_rows($customer_origin_zone_q) > 0) {
    // $origin_zone_res = mysqli_fetch_array($customer_origin_zone_q);
    // $zone_ids = $origin_zone_res['zone_ids'];
    // $origin_q = mysqli_query($con," SELECT DISTINCT origin FROM zone_cities WHERE zone IN(".$zone_ids.") ORDER BY origin ");
    // $destination_q = mysqli_query($con," SELECT DISTINCT destination FROM zone_cities WHERE zone IN(".$zone_ids.") ORDER BY destination ");
    //service types queries

    // $origin_zone_res = mysqli_fetch_array($customer_origin_zone_q);
    // $zone_ids = $origin_zone_res['zone_ids'];
    // $origin_q = mysqli_query($con," SELECT DISTINCT origin FROM zone_cities WHERE zone IN(".$zone_ids.") ORDER BY origin ");
    // $destination_q = mysqli_query($con," SELECT * FROM cities ");
    // $destination_cities_list = '';
    // while($destination_r = mysqli_fetch_array($destination_q))
    // {
    //  $city = $destination_r['city_name'];
    //  if($city == 'Other' or $city == 'Others')
    //  {
    //      $city_q = mysqli_query($con,"SELECT DISTINCT city_name FROM cities WHERE city_name !='Other' AND city_name !='Others' AND city_name !='LAHORE' ");
    //      while($city_q_r = mysqli_fetch_array($city_q))
    //      {
    //          $city = $city_q_r['city_name'];
    //          $destination_cities_list .= "<option ".($row2['destination']==$city ? "Selected" : "")." value='".$city."' >".$city."</option>";
    //      }
    //  }else{
    //      $destination_cities_list .= "<option ".($row2['destination']==$city ? "Selected" : "")." value='".$city."'  >".$city."</option>";
    //  }
    // }
    //service types queries
    $service_type_q = mysqli_query($con, " SELECT GROUP_CONCAT(DISTINCT service_type SEPARATOR ',') as service_types FROM zone WHERE id IN (" . $zone_ids . ") ");
    if (mysqli_num_rows($service_type_q) > 0) {
        $service_type_id_res = mysqli_fetch_array($service_type_q);
        $service_types = $service_type_id_res['service_types'];
        $get_service_types = mysqli_query($con, " SELECT DISTINCT id,service_type FROM services WHERE id IN(" . $service_types . ") ");
    }
}
$get_service_types = mysqli_query($con, " SELECT DISTINCT id,service_type FROM services");
$orders_sql = mysqli_query($con, "SELECT track_no FROM orders WHERE 1 ORDER BY id DESC LIMIT 1");
// $gst_query = mysqli_query($con,"SELECT value FROM config WHERE `name`='gst' ");
// $total_gst = mysqli_fetch_array($gst_query);
// $gst_percentage = 0;
// if(isset($customer_data['is_saletax']) && $customer_data['is_saletax'] == 1)
// {
//  $gst_percentage = isset($total_gst['value']) ? $total_gst['value']:0;
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

$query = mysqli_query($con, "SELECT * FROM orders WHERE id='$id'") or die(mysqli_error($con));
$row2 = mysqli_fetch_array($query,MYSQLI_ASSOC);

// echo "<pre>";
// print_r($row2);
// exit;

$other_charges = mysqli_query($con, "SELECT * FROM charges");
$resultother_charges = mysqli_fetch_assoc($other_charges);
$origin_q = mysqli_query($con, " SELECT * FROM  cities ");
$destination_q = mysqli_query($con, " SELECT * from cities ");
$destination_cities_list = '';
while ($destination_r = mysqli_fetch_array($destination_q)) {
    $city = $destination_r['city_name'];
    if ($city == 'Other' or $city == 'Others') {
        $city_q = mysqli_query($con, "SELECT DISTINCT city_name FROM cities WHERE city_name !='Other' AND city_name !='Others' AND city_name !='LAHORE' ");
        while ($city_q_r = mysqli_fetch_array($city_q)) {
            $city = $city_q_r['city_name'];
            $destination_cities_list .= "<option " . ($row2['destination'] == $city ? "Selected" : "") . " value='" . $city . "' >" . $city . "</option>";
        }
    } else {
        $destination_cities_list .= "<option " . ($row2['destination'] == $city ? "Selected" : "") . " value='" . $city . "'  >" . $city . "</option>";
    }
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
                    <?php if ($edit_flag == 1) { ?>
                        <div class="alert alert-danger"><?php echo getLange('alert_message_edit_validate'); ?></div>
                    <?php } ?>
                    <div class='msgs'></div>
                    <form role="form" action="edituserbooking.php?id=<?php echo $row2['id']; ?>" method="POST"
                        id="editbooking_form_new">

                        <input type="hidden" name="active_customer_id" id="active_customer_id" class="active_customer"
                        value="<?php echo $customer_id; ?>">

                        <input type="hidden" name="" class="total_gst" value="0">
                        <input type="hidden" name="id" class="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '0'; ?>">
                        <div class="row">
                            <div class="col-sm-2 sidegap">
                                <div class="form-group">
                                    <label><?php echo getLange('producttype'); ?> </label>
                                    <select class="form-control product_type_id" required="true" name="product_type_id">
                                        <option value="">--select</option>
                                        <?php if (!empty($getProducts)) {
                                            foreach ($getProducts as $key1 => $p_single) { ?>
                                                <option value="<?php echo isset($p_single->id) ? $p_single->id : ''; ?>"
                                                    <?php echo (isset($row2['product_type_id']) && $row2['product_type_id'] == $p_single->id) ? 'selected' : ''; ?>
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
                                    <label><?php echo getLange('servicetype'); ?> </label>
                                    <select class="form-control order_type" name="order_type">

                                        <?php if (isset($get_service_types) && !empty($get_service_types)) {
                                            while ($row = mysqli_fetch_array($get_service_types)) {
                                                ?>
                                                <option value="<?php echo $row['id']; ?>"
                                                    <?php echo (isset($row2['order_type']) && $row2['order_type'] == $row['id']) ? 'selected' : ''; ?>
                                                    data-id="<?php echo $row['id']; ?>">
                                                    <?php echo isset($row['service_type']) ? $row['service_type'] : ''; ?>
                                                </option>
                                            <?php  }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 left_right_none">
                                <div class="form-group">
                                    <label><?php echo getLange('orderdate'); ?></label>
                                    <input type="text" value="<?php if (isset($row2['order_date']) && !empty($row2['order_date'])) {
                                        echo date('d/m/Y', strtotime($row2['order_date']));
                                        } else {
                                            echo date('d/m/Y');
                                        } ?>" class="form-control datepicker"
                                        name="order_date">
                                    </div>
                                </div>

                                <?php if (isset($customer_data['is_order_manual']) and $customer_data['is_order_manual'] == 1) : ?>
                                    <div class="col-sm-2 left_right_none">
                                        <div class="form-group">
                                            <label><?php echo getLange('orderno'); ?></label>
                                            <input type="text" name="track_no" readonly="true"
                                            value="<?php echo isset($row2['track_no']) ? $row2['track_no'] : ''; ?>"
                                            class="form-control">
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
                                                            <label><span style="color: red;">*</span>Country</label>
                                                            <input type="hidden" name="origin_branch" class="origin_branch_id" value="0">
                                                            <select class="form-control origin origin_cal origin_cha js-example-basic-single" name="origin">
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
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4 padd_left">
                                                    <div class="form-group">
                                                        <label><span style="color: red;">*</span>City</label> 
                                                        <select class="form-control origin_city js-example-basic-single" name="scity" required>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 padd_left">
                                                    <div class="form-group">
                                                        <label class="control-label"><span style="color: red;">*</span>
                                                            <?php echo getLange('name'); ?></label>
                                                            <input type="hidden" name="bname"
                                                            value="<?php echo isset($customer_data['bname']) ? $customer_data['bname'] : '';  ?>">
                                                            <input type="text" class="form-control"
                                                            value="<?php echo isset($customer_data['fname']) ? $customer_data['fname'] : $row2['sname']; ?>"
                                                            name="fname" placeholder="Shipper name" required="true"
                                                            <?php echo isset($customer_id) && $customer_id != 1 ? 'readonly' : ''; ?>>
                                                        </div>

                                                    </div>
                                                    <div class="col-sm-6 padd_left padd_right">
                                                        <div class="form-group">
                                                            <label class="control-label"><span style="color: red;">*</span>
                                                                <?php echo getLange('phone') ?></label>
                                                                <input type="text" class="form-control"
                                                                value="<?php echo isset($customer_data['mobile_no']) ? $customer_data['mobile_no'] : $row2['sphone']; ?>"
                                                                name="mobile_no" placeholder="Shipper Phone" required="true"
                                                                <?php echo isset($customer_id) && $customer_id != 1 ? 'readonly' : ''; ?>>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-6 padd_left">
                                                            <div class="form-group">
                                                                <label class="control-label"><span style="color: red;">*</span>
                                                                    <?php echo getLange('email'); ?></label>
                                                                    <input type="email"
                                                                    value="<?php echo isset($customer_data['email']) ? $customer_data['email'] : $row2['semail']; ?>"
                                                                    class="form-control" name="email" placeholder="Shipper Email"
                                                                    required="true"
                                                                    <?php echo isset($customer_id) && $customer_id != 1 ? 'readonly' : ''; ?>>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 padd_left" style="padding-right:0;">
                                                            <div class="form-group">
                                                                <label class="control-label">Shipper's Reference</label>
                                                                <input autocomplete="false" type="text" class="form-control"
                                                                name="shipper_reference" placeholder="Shipper Reference" value="<?php echo $row2['shipper_reference'] ?>">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-6 col-lg-6 padd_left" style="padding-right:0;">
                                                                <div class="form-group">
                                                                    <label class="control-label"> Zip</label>
                                                                    <input type="text" class="form-control"
                                                                    value="<?php echo isset($customer_data['zip']) ? $customer_data['zip'] : $row2['szip'];  ?>"
                                                                    name="szip" placeholder="Zip">
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-6 col-lg-6 padd_left" style="padding-right:0;">
                                                                <div class="form-group">
                                                                    <label class="control-label">Fax</label>
                                                                    <input autocomplete="false" type="text" class="form-control"
                                                                    name="sfax" placeholder="Fax" value="<?php echo $row2['sfax'] ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12 padd_left" style="padding-right:0;">
                                                                <div class="form-group">
                                                                    <label class="control-label"><span style="color: red;">*</span>
                                                                        <?php echo getLange('address'); ?></label>
                                                                        <textarea class="form-control" name="pickup_address"
                                                                        placeholder="Shipper Address" required="true"
                                                                        <?php echo isset($customer_id) && $customer_id != 1 ? 'readonly' : ''; ?>><?php echo isset($customer_data['address']) ? $customer_data['address'] : $row2['sender_address']; ?></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12 padd_left" style="padding-right:0;">
                                                                    <div class="form-group">
                                                                        <label
                                                                        class="control-label"><?php echo getLange('pickupaddress'); ?></label>
                                                                        <textarea autocomplete="false" class="form-control shipper_address"
                                                                        name="pickup_address_new"
                                                                        placeholder="Pickup Address"><?php echo $row2['Pick_location']; ?></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading"><?php echo getLange('deliverydeatils'); ?> <span
                                                            style="float: right;" class="right_order"></span></div>
                                                            <div class="panel-body ">
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
                                                        <div class="col-sm-6 padd_left">
                                                            <div class="form-group">
                                                                <label class="control-label"><span style="color: red;">*</span>
                                                                    <?php echo getLange('name'); ?></label>
                                                                    <input type="text" class="form-control" name="receiver_name"
                                                                    value="<?php echo $row2['rname'] ?>" placeholder="Consignee name"
                                                                    required="true">
                                                                </div>
                                                            </div>
                                        <!-- <div class="col-sm-4 padd_left">
                                            <div class="form-group">
                                                <label class="control-label"><span style="color: red;">*</span>
                                                    <?php echo getLange('phone'); ?></label>
                                                <div class="phone_search"
                                                    style="position: absolute;right: 19px;top: 34px;    ">
                                                    <i class="fa fa-search search_phone"></i>
                                                </div>
                                                <input type="text" class="form-control" name="receiver_phone"
                                                    value="<?php echo $row2['rphone']; ?>" placeholder="Consignee Phone"
                                                    required="true">
                                                <div class="r_phone_msg"></div>
                                            </div>
                                        </div> -->

                                        <div class="col-sm-6 padd_left">
                                            <div class="form-group">
                                                <label class="control-label"><span style="color: red;"></span>
                                                    <?php echo getLange('email'); ?></label>
                                                    <input type="email" class="form-control" name="receiver_email"
                                                    value="<?php echo $row2['remail']; ?>"
                                                    placeholder="Consignee Email">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 padd_left" style="padding-right:0;">
                                            <div class="form-group">
                                                <label class="control-label"><span
                                                    style="color: red;"></span><?php echo getLange('phone'); ?>
                                                </label>
                                                <input type="text" class="form-control" name="receiver_phone"
                                                placeholder="Phone Number"  value="<?php echo $row2['rphone'] ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4 padd_left" style="padding-right:0;">
                                            <div class="form-group">
                                                <label class="control-label"> Zip</label>
                                                <input type="text" class="form-control" name="rzip"
                                                placeholder="Zip"  value="<?php echo $row2['rzip'] ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4">
                                            <div class="form-group">
                                                <label class="control-label">Fax</label>
                                                <input autocomplete="false" type="text" class="form-control"
                                                name="rfax" placeholder="Fax" value="<?php echo $row2['rfax'] ?>">
                                            </div>
                                        </div>
                                        <div class="row">
                                         <div class="col-sm-6 col-lg-6 padd_left" style="padding-right:0;">
                                            <div class="form-group">
                                                <label class="control-label">EORI Number</label>
                                                <input autocomplete="false" type="text" class="form-control"
                                                name="eori_number" placeholder="EORI Number" value="<?php echo $row2['eori_number'] ?>">
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-lg-6">
                                            <div class="form-group">
                                                <label class="control-label">IEC Number</label>
                                                <input autocomplete="false" type="text" class="form-control"
                                                name="iec_number" placeholder="IEC Number" value="<?php echo $row2['iec_number'] ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 padd_left">
                                            <div class="form-group">
                                                <label> <span style="color: red;">*</span>
                                                    <?php echo getLange('receiver'); ?>
                                                    <?php echo getLange('address'); ?> </label>
                                                    <textarea placeholder="Receiver Address" class="form-control"
                                                    name="receiver_address" id="receiver_address"
                                                    required><?php echo $row2['receiver_address'] ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 padd_left">

                                                <div class="form-group">
                                                    <label class="control-label"><span style="color: red;">*</span>
                                                        <?php echo getLange('address'); ?></label>
                                                        <input required="true" name="receiver_address"
                                                        class="address form-control" type="text"
                                                        value="<?php echo $row2['receiver_address'] ?>"
                                                        placeholder="Consignee Address">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 padd_left" style="padding-right:0;">
                                                    <div class="form-group">

                                                        <label><?php echo getLange('googleaddress'); ?> </label>
                                                        <textarea placeholder="Paste your google address here"
                                                        class="form-control"
                                                        value="<?php echo $row2['google_address'] ?>" id="property_add"
                                                        name="google_address"></textarea>
                                                        <input type="hidden" class="form-control"
                                                        value="<?php echo $row2['map_latitude'] ?>" id="latitude"
                                                        name="latitude">
                                                        <input type="hidden" class="form-control"
                                                        value="<?php echo $row2['map_longitude'] ?>" id="longitude"
                                                        name="longitude">
                                                    </div>
                                                </div>

                                                <div class="col-sm-12 padd_right padd_left">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="mapping" id="mapping"
                                                                style="width: 100%; height:94px;"></div>
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
                                                            <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <label><span style="color: red;"></span>Customer
                                                                    Currency</label>
                                                                    <input class="form-control" name="customer_currency" value="<?php echo $row2['customer_currency'] ?>">
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-3 width_33">
                                                                <div class="form-group">
                                                                    <label> <?php echo getLange('refernceno'); ?> .</label>
                                                                    <input type="text" name="ref_no" class="form-control" value="<?php echo isset($row2['ref_no']) ? $row2['ref_no'] : ''; ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3 ">
                                                                <div class="form-group">
                                                                    <label> Ref 1.</label>
                                                                    <input type="text" name="ref_1" class="form-control" value="<?php echo $row2['ref_1'] ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3 ">
                                                                <div class="form-group">
                                                                    <label>Ref 2.</label>
                                                                    <input type="text" name="ref_2" class="form-control" value="<?php echo $row2['ref_2'] ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3 padd_right">
                                                                <div class="form-group">
                                                                    <label> <?php echo getLange('orderid'); ?> .</label>
                                                                    <input type="text" name="product_id" class="form-control" value="<?php echo isset($row2['product_id']) ? $row2['product_id'] : ''; ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3 ">
                                                                <div class="form-group">
                                                                    <label class="calculation_label"><span style="color: red;">*</span><?php echo getLange('noofpiece'); ?></label>
                                                                    <input type="myNumber" name="quantity" class="form-control pieces" required="true"   value="<?php echo isset($row2['quantity']) ? $row2['quantity'] : ''; ?>">
                                                                </div>
                                                            </div>
                                                             <div class="col-sm-3 padd_left padd_right">
                                                                <div class="form-group">
                                                                    <label class="calculation_label"><span style="color: red;">*</span> Chargable weight</label>
                                                                    <input type="myNumber" name="cweight" id="charweight" class="form-control"  required="true" value="<?php echo isset($row2['cweight']) ? $row2['cweight'] : '1'; ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3 padd_left padd_right">
                                                                <div class="form-group">
                                                                    <label class="calculation_label"><span style="color: red;">*</span> Scale weight</label>
                                                                    <input type="myNumber" name="sweight" class="form-control"  required="true" value="<?php echo isset($row2['scale_weight']) ? $row2['scale_weight'] : '1'; ?>">
                                                                </div>
                                                            </div>
                                                           <div class="col-sm-4 padd_left padd_right">
                                                                <div class="form-group">
                                                                    <label class="calculation_label"><span style="color: red;">*</span> Dimensional weight</label>
                                                                    <input type="myNumber" name="weight" class="form-control weight"  required="true" value="<?php echo isset($row2['weight']) ? $row2['weight'] : ''; ?>">
                                                                </div>
                                                            </div>
                                                            <!-- <div class="col-sm-3 padd_left padd_right">
                                                                <div class="form-group">
                                                                    <label class="calculation_label"><span style="color: red;">*</span>   <?php// echo getLange('weightkg'); ?></label>
                                                                    <input type="myNumber" name="weight" class="form-control weight"  required="true" value="">
                                                                </div>
                                                            </div> -->
                                                            <div class="col-sm-3 padd_left">
                                                                <div class="form-group"> <label><span
                                                                    style="color: red;">*</span><?php echo getLange('codamount'); ?></label>
                                                                    <input type="text" name="collection_amount"
                                                                    class="form-control allownumericwithdecimal" required="true"
                                                                    value="<?php echo isset($row2['collection_amount']) ? $row2['collection_amount'] : 0; ?>">
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
                                                                            class="form-control length" required="true" value="<?php echo isset($row2['length']) ? $row2['length'] : '0'; ?>">
                                                                        </div>
                                                                        <div class="col-sm-3">
                                                                            <label class="calculation_label">Width</label>
                                                                            <input type="number" name="width"
                                                                            class="form-control width" required="true"
                                                                            value="<?php echo isset($row2['width']) ? $row2['width'] : '0'; ?>">
                                                                        </div>
                                                                        <div class="col-sm-3">
                                                                            <label class="calculation_label">Height</label>
                                                                            <input type="number" name="height"
                                                                            class="form-control height" required="true"
                                                                            value="<?php echo isset($row2['height']) ? $row2['height'] : '0'; ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <div class="col-sm-12 col-lg-6">
                                                                    <div class="form-group">
                                                                        <label><span
                                                                            style="color: red;"></span>Description</label>
                                                                            <textarea class="form-control"
                                                                            name="description"><?php echo isset($row2['description']) ? $row2['description'] : ''; ?></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6 padd_left">
                                                                        <div class="form-group">
                                                                            <label><span
                                                                                style="color: red;">*</span><?php echo getLange('itemdetail'); ?>
                                                                            </label>
                                                                            <textarea class="form-control" name="product_desc"
                                                                            required="true"><?php echo isset($row2['product_desc']) ? $row2['product_desc'] : ''; ?></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-12 col-lg-12">
                                                                        <div class="form-group">
                                                                            <label><span style="color: red;"></span>
                                                                                <?php echo getLange('specialinstruction'); ?> </label>
                                                                                <textarea class="form-control"
                                                                                name="special_instruction"><?php echo isset($row2['special_instruction']) ? $row2['special_instruction'] : ''; ?></textarea>
                                                                            </div>
                                                                        </div>  
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <div class="panel panel-default">
                                                                                <div class="panel-heading">weightRequest</div>
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
                                                                                                value="<?php echo isset($row2['collection_date']) ? $row2['collection_date'] : date('Y-m-d'); ?>">
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
                                                                                            value="<?php echo isset($row2['collection_ready_time']) ? $row2['collection_ready_time'] : date('H:i'); ?>">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-4 padd_left">
                                                                                        <div class="form-group">
                                                                                            <label class="calculation_label">Vehicle
                                                                                            Type</label>
                                                                                            <input type="text" name="vehicle_Type"
                                                                                            class="form-control" value="<?php echo isset($row2['vehicle_Type']) ? $row2['vehicle_Type'] : ''; ?>">
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
                                                                            <label
                                                                            class="calculation_label"><?php echo getLange('fragile'); ?>
                                                                        </label>
                                                                        <select
                                                                        class="form-control insurance_rate is_fragile js-example-basic-single"
                                                                        name="insurance_type">
                                                                        <option value="" selected disabled="true">Select Insurance
                                                                        Type</option>
                                                                        <?php while ($insuranceval = mysqli_fetch_array($insurance)) { ?>
                                                                            <option
                                                                            <?php echo (isset($row2['insurance_type']) && $insuranceval['id'] == $row2['insurance_type']) ? 'selected' : ''; ?>
                                                                            value="<?php echo $insuranceval['id'] ?>"
                                                                            id="insurancedata<?php echo $insuranceval['id'] ?>"
                                                                            data-attr="<?php echo $insuranceval['rate'] ?>">
                                                                            <?php echo $insuranceval['name']; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6 padd_right">
                                                                <div class="form-group">
                                                                    <label class="calculation_label"><span
                                                                        style="color: red;">*</span><?php echo getLange('insureditemdeclare'); ?>
                                                                    </label>
                                                                    <input type="number" name="insured_item_value"
                                                                    class="form-control insurance_rate insured_item_value"
                                                                    required="true"
                                                                    value="<?php echo isset($row2['insured_item_value']) ? $row2['insured_item_value'] : 0; ?>">
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
                                                                                    <th><?php echo getLange('charges'); ?></th>
                                                                                    <th style="width: 42px;"></th>
                                                                                    <th><?php echo getLange('amount'); ?></th>
                                                                                </tr>
                                                                                <?php
                                                                                $other_charges_array = [];
                                                                                $other_query = mysqli_query($con, "SELECT * from `order_charges` WHERE order_id='" . $_GET['id'] . "'");
                                                                                while ($othercharges = mysqli_fetch_array($other_query)) {
                                                                                    if (isset($othercharges['charges_id']) && $othercharges['charges_id']) {
                                                                                        $other_charges_array[$othercharges['charges_id']] = $othercharges;
                                                                                    }
                                                                                }
                                                                                if (isset($other_charges) && !empty($other_charges)) {
                                                                                    while ($row = mysqli_fetch_array($other_charges)) {
                                                                                        $c_id = $row['id'];
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
                                                                                                <input type="checkbox" disabled="true"
                                                                                                class="change_charges"
                                                                                                <?php echo (isset($other_charges_array[$c_id]['charges_id']) && $c_id == $other_charges_array[$c_id]['charges_id'] && $other_charges_array[$c_id]['charges_amount'] > 0) ? "checked='true'" : ''; ?>
                                                                                                value="<?php echo $c_id; ?>"
                                                                                                data-charges="<?php echo $row['charge_value'] ?>">
                                                                                            </td>
                                                                                            <td>
                                                                                                <div class="input-group">
                                                                                                    <input disabled
                                                                                                    data-type="<?php echo $row['charge_type'] ?>"
                                                                                                    type="text"
                                                                                                    class="form-control other_charges <?php echo $c_id; ?>"
                                                                                                    value="<?php echo (isset($other_charges_array[$c_id]['charges_id']) && $c_id == $other_charges_array[$c_id]['charges_id']) ? $other_charges_array[$c_id]['charges_amount'] : '0'; ?>"
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
                                                                            name="special_charges"
                                                                            value="<?php echo isset($row2['special_charges']) ? $row2['special_charges'] : 0; ?>"
                                                                            readonly>
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
                                <div class="col-sm-4 
                                padd_left">
                                <div class="form-group"> <label class="calculation_label"><span
                                    style="color: red;">*</span>
                                    <?php echo getLange('deliveycharges'); ?></label> <input
                                    type="text" name="delivery_charges" readonly="true"
                                    value="<?php echo isset($row2['price']) ? $row2['price'] : 0; ?>"
                                    class="form-control total_amount
                                    allownumericwithdecimal"> </div>
                                </div>
                                <?php if ($admin_other_charges == '1') : ?>
                                    <div class="col-sm-4 padd_left">
                                        <div class="form-group">
                                            <label><?php echo getLange('specialcharges'); ?></label>
                                            <input type="text" class="form-control special_charges"
                                            name="special_charges"
                                            value="<?php echo isset($row2['special_charges']) ? $row2['special_charges'] : 0; ?>"
                                            readonly>
                                        </div>
                                    </div>
                                    <?php else : ?>
                                        <input type="hidden" class="form-control" name="special_charges"
                                        value="<?php echo isset($row2['special_charges']) ? $row2['special_charges'] : 0; ?>"
                                        readonly>
                                    <?php endif; ?>
                                    <?php if ($admin_extra_charges == '1') : ?>
                                        <div class="col-sm-4 padd_left">
                                            <div class="form-group">
                                                <label class="calculation_label"><span
                                                    style="color: red;">*</span><?php echo getLange('extracharges'); ?>
                                                </label>
                                                <input type="number" name="extra_charges"
                                                class="form-control extra_charges" required="true"
                                                value="<?php echo isset($row2['extra_charges']) ? $row2['extra_charges'] : 0; ?>">
                                            </div>
                                        </div>
                                        <?php else : ?>
                                            <input type="hidden" name="extra_charges"
                                            class="form-control extra_charges" required="true"
                                            value="<?php echo isset($row2['extra_charges']) ? $row2['extra_charges'] : 0; ?>">
                                        <?php endif; ?>
                                        <?php if ($admin_insured_premium == '1') : ?>
                                            <div class="col-sm-4 padd_left">
                                                <div class="form-group">
                                                    <label class="calculation_label"><span
                                                        style="color: red;">*</span><?php echo getLange('insurancepremium'); ?>
                                                    </label>
                                                    <input type="number" name="insured_premium"
                                                    class="form-control insurance_value" required="true"
                                                    value="<?php echo isset($row2['insured_premium']) ? $row2['insured_premium'] : 0; ?>"
                                                    readonly>
                                                </div>
                                            </div>
                                            <?php else : ?>
                                                <input type="hidden" name="insured_premium"
                                                class="form-control insurance_value" required="true"
                                                value="<?php echo isset($row2['insured_premium']) ? $row2['insured_premium'] : 0; ?>"
                                                readonly>
                                            <?php endif; ?>
                                            <div class="col-sm-4 padd_left">
                                                <div class="form-group">
                                                    <label>
                                                        <?php echo getLange('totalcharges'); ?>
                                                    </label>
                                                    <input type="text" name="total_charges"
                                                    value="<?php echo isset($row2['grand_total_charges']) ? $row2['grand_total_charges'] : 0; ?>"
                                                    readonly="true" class="form-control allownumericwithdecimal
                                                    total_charges" required="true">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 padd_left">
                                                <div class="form-group">
                                                    <?php

                                                                // $fuelsurcharge_percent = getConfig('fuel_surcharge');

                                                                //  if(isset($customer_data['is_fuelsurcharge']) && $customer_data['is_fuelsurcharge'] == 0)
                                                                //  {
                                                                //      $fuelsurcharge_percent = 0;
                                                                //  }

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
                                                    <input type="text" name="fuel_surcharge"
                                                    value="<?php echo isset($row2['fuel_surcharge']) ? $row2['fuel_surcharge'] : 0; ?>"
                                                    readonly="true" class="form-control allownumericwithdecimal
                                                    fuel_surcharge" required="true">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 padd_left">
                                                <div class="form-group"> <label><span
                                                    style="color: red;">*</span><?php echo getLange('salestax'); ?></label>
                                                    <?php if ($checkGstExempt == 1) {
                                                        ?> <input type="text" name="pft_amount"
                                                        value="<?php echo isset($row2['pft_amount']) ? $row2['pft_amount'] : 0; ?>"
                                                        readonly="true" class="form-control">
                                                        <?php   } else {   ?> <input type="text" name="pft_amount"
                                                        value="<?php echo isset($row2['pft_amount']) ? $row2['pft_amount'] : 0; ?>"
                                                        readonly="true" class="form-control allownumericwithdecimal
                                                        pft_amount" required="true"> <?php } ?> </div>
                                                    </div>
                                                    <div
                                                    class="col-sm-4 padd_left input_label_box main-box-x total_charged">
                                                    <label><?php echo getLange('netamount'); ?></label>
                                                    <input type="text" class="form-control" readonly="true"
                                                    name="net_amount"
                                                    value="<?php echo isset($row2['net_amount']) ? $row2['net_amount'] : 0; ?>">
                                                </div>
                                                <div class="col-sm-4 padd_left" style="display:none">
                                                    <div class="form-group"> <label><span style="color:
                                                    red;">*</span><?php echo getLange('totalservicescharges'); ?></label> <input
                                                    type="text"
                                                    value="<?php echo isset($row2['inc_amount']) ? $row2['inc_amount'] : 0; ?>"
                                                    name="inc_amount" readonly="true" class="form-control allownumericwithdecimal
                                                    inc_amount" required="true"> </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="row">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">Commercial Invoice</div>
                                                <!-- <div class="panel-body booking_row"> -->
                                                    <div class="panel-body  ">
                                                        <div class="row">
                                                          <?php $commercial_invoice_q=mysqli_query($con,"SELECT * FROM order_commercial_invoice WHERE order_id='" . $row2['id'] . "'");
                                                          if (mysqli_affected_rows($con) > 0) {
                                                            foreach ($commercial_invoice_q as $key => $row_oic) {
                                                                ?>
                                                                <div class="col-sm-12 padd_left">
                                                                    <div class="form-group">
                                                                        <label class="calculation_label">Discription</label>
                                                                        <input type="text" class="form-control" value="<?php echo isset($row_oic['c_i_discription']) ? $row_oic['c_i_discription'] : ''; ?>"
                                                                        name="c_i_discription[<?php echo $key; ?>]">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-2 padd_left">
                                                                    <div class="form-group">
                                                                        <label class="calculation_label">Pieces</label>
                                                                        <input type="text" name="c_i_pieces[<?php echo $key; ?>]"
                                                                        class="form-control c_i_pieces" value="<?php echo isset($row_oic['c_i_pieces']) ? $row_oic['c_i_pieces'] : '0'; ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-2 padd_left">
                                                                    <div class="form-group">
                                                                        <label class="calculation_label">Price</label>
                                                                        <input type="text" name="c_i_price[<?php echo $key; ?>]"
                                                                        class="form-control c_i_price" value="<?php echo isset($row_oic['c_i_price']) ? $row_oic['c_i_price'] : '0'; ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-2 padd_left">
                                                                    <div class="form-group">
                                                                        <label class="calculation_label">COO</label>
                                                                        <input type="text" name="c_i_coo[<?php echo $key; ?>]"
                                                                        class="form-control" value="<?php echo isset($row_oic['c_i_coo']) ? $row_oic['c_i_coo'] : 'PK'; ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-2 padd_left">
                                                                    <div class="form-group">
                                                                        <label class="calculation_label">HS
                                                                        Code</label>
                                                                        <input type="text" name="c_i_hs_code[<?php echo $key; ?>]" value="<?php echo isset($row_oic['c_i_hs_code']) ? $row_oic['c_i_hs_code'] : ''; ?>" placeholder="0000.0000"
                                                                        class="form-control c_i_hs_code">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-2 padd_left">
                                                                    <div class="form-group">
                                                                        <label class="calculation_label">Total</label>
                                                                        <input type="text" name="c_i_hs_total[<?php echo $key; ?>]" value="<?php echo isset($row_oic['c_i_hs_total']) ? $row_oic['c_i_hs_total'] : '0'; ?>"
                                                                        class="form-control c_i_hs_total">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-2 padd_left">
                                                                    <div class="form-group">
                                                                        <a href="" class="btn btn-<?php echo isset($key) && $key==0 ? 'info' : 'danger';?> <?php echo isset($key) && $key==0 ? 'btn_commercial_invoice' : 'btn_commercial_invoice_romove'; ?>"
                                                                            style="margin-top: 24px;margin-left: 36px;"><?php echo isset($key) && $key==0 ? '+' : '-';?></a>
                                                                        </div>
                                                                    </div>
                                                                    <?php 
                                                                }
                                                            }else{
                                                                ?>
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
                                                            <?php } ?>

                                                            <input type="hidden" class="count_array_commercial_invoice"
                                                            value="<?php echo isset($key) && !empty($key) ? $key : '0'; ?>">
                                                            <div class="plus_commercial_invoice"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class='msgs'></div>
                            </div>
                            <input type="hidden" class="print_template"
                            value="<?php echo getConfig('print_template'); ?>">
                            <div class="save_print_btn">
                                <?php if ($edit_flag == 0) { ?>
                                    <input type="submit" name="save_order" class="btn btn-purple submit_btns"
                                    value="<?php echo getLange('save'); ?>">
                                <?php } ?>
                                <!-- <a href="#" class="submit_order_edit btn btn-purple submit_btns btn-purple"><?php echo getLange('saveprint'); ?></a> -->
                            </div>
                            <!-- <input type="submit" name="submit_order" class="btn btn-purple submit_btns" value="Save & Print" > -->
                        </div>
                    </div>
                </form>
                <?php if ($edit_flag == 1) { ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="alert alert-danger"><?php echo getLange('alert_message_edit_validate'); ?></div>
                        </div>
                    </div>
                <?php } ?>
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
    document.addEventListener('DOMContentLoaded', function() {
        // $('body').on('keyup', 'input[type=text]', function(e) {
        //     $('input[type=text]').val(function() {
        //         return this.value.toUpperCase();
        //     })
        // })
        // $('body').on('keyup', 'input[type=email]', function(e) {
        //     $('input[type=email]').val(function() {
        //         return this.value.toUpperCase();
        //     })
        // })
        // $('body').on('keyup', 'input[type=myNumber]', function(e) {
        //     $('input[type=myNumber]').val(function() {
        //         return this.value.toUpperCase();
        //     })
        // })
        // $('body').on('keyup', 'textarea', function(e) {
        //     $('textarea').val(function() {
        //         return this.value.toUpperCase();
        //     })
        // })
        // $('.select2').select2();
        $('body').on('change', '.product_type_id', function(event) {
            event.preventDefault();
            var product_type_id = $(this).find(':selected').val();
            if (product_type_id) {
                $.ajax({
                    url: '<?php echo BASE_URL ?>getServiceType.php',
                    type: 'POST',
                    data: {
                        is_product: 1,
                        product_type_id: product_type_id
                    },
                    success: function(response) {
                        var result = jQuery.parseJSON(response);
                        var service_types_options = '<option value="">--select--</option>';
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
        });
    }, false);

    
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
// document.addEventListener('DOMContentLoaded',function(){
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
//     getUserAddressBy(position.coords.latitude, position.coords.longitude);
//     latitude = position.coords.latitude;
//     longitude = position.coords.longitude;
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
//         getUserAddressBy(position.coords.latitude, position.coords.longitude)
//         document.getElementById('latitude').value = position.coords.latitude;
//         document.getElementById('longitude').value = position.coords.longitude;
//     },
//     function(error) {
//         locatorSection.classList.remove("loading")
//         alert("The Locator was denied :( Please add your address manually")
//     })
// }

// function getUserAddressBy(lat, long) {

//     var xhttp = new XMLHttpRequest();
//     xhttp.onreadystatechange = function() {
//         if (this.readyState == 4 && this.status == 200) {
//             var address = JSON.parse(this.responseText)
//             document.getElementById('property_add').value = address.results[0].formatted_address;
//             document.getElementById('google_address').value = address.results[0].formatted_address;
//             // filladdress(address.results[0]);

//         }
//     };
//     xhttp.open("GET", "https://maps.googleapis.com/maps/api/geocode/json?latlng=" + lat + "," + long +
//         "&key=" + api_key + "", true);
//     xhttp.send();

// }
// Ending Navigator

var latitude = document.getElementById('latitude').value;
var longitude = document.getElementById('longitude').value;

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
    document.getElementById('google_address').value = address;
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;
}
</script>

<script type="text/javascript">
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
</script>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
    // var country_code=$('.default_number').html();
    // country_code=country_code.replace('+', '');
    // $('#phoneno').val('00'+country_code);
    $('body').on('click keyup', '.country', function(e) {
        var country_code = $(this).attr('data-dial-code');
        $('#phoneno').val('00' + country_code);
    });
    // $(document).on('change','#phone',function(){
    //        var country_code=$(this).attr('data-dial-code');
    //         $('#phoneno').val('00'+country_code);
    // })

}, false);</script>
<script type="text/javascript" src="js/booking_new.js"></script>