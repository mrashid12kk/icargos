<?php

require_once("inc/conn.php");
require_once("inc/constants.php");
require_once("inc/functions.php");

$shop = isset($_SERVER['X-Shopify-Shop-Domain']) ? $_SERVER['X-Shopify-Shop-Domain'] : $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'];

$hmac_header = isset($_SERVER['X-Shopify-Hmac-SHA256']) ? $_SERVER['X-Shopify-Hmac-SHA256'] : $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];

$order_detail_json = file_get_contents('php://input');

function verify_webhook($data, $hmac_header) {
    $calculated_hmac = base64_encode(hash_hmac('sha256', $data, SHOPIFY_SHARED_SECRET, true));
    return hash_equals($hmac_header, $calculated_hmac);
}

$verified = verify_webhook($order_detail_json, $hmac_header);
if ($verified) {
    $order_detail_arr = json_decode($order_detail_json, TRUE);
    $order_id = $order_detail_arr['id'];
    $order_no = $order_detail_arr['name'];
    $shipping_detail = $order_detail_arr['shipping_address'];
    $line_items = $order_detail_arr['line_items'];
    $destination = isset($shipping_detail['city']) ? $shipping_detail['city'] : '';
    $receiver_name = $shipping_detail['first_name'] . ' ' . $shipping_detail['last_name'];
    $receiver_phone = isset($shipping_detail['phone']) ?  $shipping_detail['phone'] : '';
    $receiver_email = $order_detail_arr['email'];
    $receiver_address = isset($shipping_detail['address1']) ? $shipping_detail['address1'] : '';
    $customer_lat = isset($shipping_detail['latitude']) ? $shipping_detail['latitude'] : '';
    $customer_long = isset($shipping_detail['longitude']) ? $shipping_detail['longitude'] : '';
    $collection_amount = $order_detail_arr['total_price'];
    $track_no = '';
    if(!empty($order_detail_arr['fulfillments'])){
        $track_no = $order_detail_arr['fulfillments'][0]['tracking_number'];
    }
    $quantity = 0;
    $item_title = '';
    $product_id = '';
    if (!empty($line_items)) {
            foreach ($line_items as $key => $row) {
                    $quantity += $row['quantity'];
                    if ($key == 0) {
                            $item_title = $row['title'];
                            $product_id = $row['product_id'];
                    } else {
                            $item_title .= ' ,' . $row['title'];
                            $product_id .= ' ,' . $row['product_id'];
                    }
            }
    }
    $total_weight = ($order_detail_arr['total_weight'] != '') ? $order_detail_arr['total_weight'] / 1000 : 0.5;
    
    $get_pref = mysqli_query($con, "SELECT * FROM  preferences WHERE `shop_url`='" . $shop . "' ");
    $pref_res = mysqli_fetch_array($get_pref);

    $user_auth = $pref_res['auth_key'];
    $client_code = $pref_res['client_code'];

    $url = COURIER_URL . 'API/UpdateOrder.php';
    $order_data = array(
        'auth_key' => $user_auth,
        'client_code' => $client_code,
        'destination' => $destination,
        'receiver_name' => $receiver_name,
        'receiver_phone' => $receiver_phone,
        'receiver_email' => $receiver_email,
        'receiver_address' => $receiver_address,
        'pieces' => $quantity,
        'weight' => $total_weight,
        'collection_amount' => $collection_amount,
        'product_description' => $item_title,
        'special_instruction' => 'shopify',
        'track_no' => $track_no,
        'order_id' => $order_no
    );
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($order_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    //    $ch = curl_init($url);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($order_data));
    // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    // $result = curl_exec($ch);
    // curl_close($ch);
    
    $response = json_decode($result);
        
    $get_order = mysqli_query($con, "SELECT * FROM  shopify_order_update_request WHERE `order_id`='" . $order_id . "' ");
    $date = date('Y-m-d H:i:s');
    if (mysqli_num_rows($get_order) == 0) {
        $query = "INSERT INTO `shopify_order_update_request`(order_id, order_no, track_no, product_info, weight, update_request_date, customer_name, customer_phone, customer_email, customer_address, customer_lat, customer_long, api_response, created_at) VALUES('" . $order_id . "', '" . $order_no . "', '" . $track_no . "', '" . $item_title . "', '" . $total_weight . "', '" . $date . "', '" . $receiver_name . "', '" . $receiver_phone . "', '" . $receiver_email . "', '" . $receiver_address . "', '" . $customer_lat . "', '" . $customer_long . "', '" . $result . "', '" . $date . "') ";
        mysqli_query($con, "INSERT INTO `shopify_order_update_request`(order_id, order_no, track_no, product_info, weight, update_request_date, customer_name, customer_phone, customer_email, customer_address, customer_lat, customer_long, api_response, created_at) VALUES('" . $order_id . "', '" . $order_no . "', '" . $track_no . "', '" . $item_title . "', '" . $total_weight . "', '" . $date . "', '" . $receiver_name . "', '" . $receiver_phone . "', '" . $receiver_email . "', '" . $receiver_address . "', '" . $customer_lat . "', '" . $customer_long . "', '" . $result . "', '" . $date . "') ");
    }else{
        $query = "UPDATE `shopify_order_update_request` SET track_no = '" . $track_no . "', product_info = '" . $item_title . "', weight = '" . $total_weight . "',update_request_date = '" . $date . "',customer_name = '" . $receiver_name . "',customer_phone = '" . $receiver_phone . "',customer_email = '" . $receiver_email . "',customer_address = '" . $receiver_address . "',customer_lat = '" . $customer_lat . "',customer_long = '" . $customer_long . "',api_response = '" . $result . "' WHERE order_id='" . $order_id . "' ";
        mysqli_query($con, "UPDATE `shopify_order_update_request` SET track_no = '" . $track_no . "', product_info = '" . $item_title . "', weight = '" . $total_weight . "',update_request_date = '" . $date . "',customer_name = '" . $receiver_name . "',customer_phone = '" . $receiver_phone . "',customer_email = '" . $receiver_email . "',customer_address = '" . $receiver_address . "',customer_lat = '" . $customer_lat . "',customer_long = '" . $customer_long . "',api_response = '" . $result . "' WHERE order_id='" . $order_id . "' ");
    }
    http_response_code(200);
} else {
  http_response_code(401);
}
?>