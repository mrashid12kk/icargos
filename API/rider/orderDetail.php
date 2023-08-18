<?php
require_once('../../admin/includes/conn.php');
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$data_post = (array)json_decode(file_get_contents("php://input"));
$track_no = $data_post['track_no'];
$orderQuery = " SELECT track_no,
sname as sender_name,
sbname as business_name,
sender_address,
sphone as sender_phone,
rname as receiver_name,
rphone as reciever_phone,
receiver_address,
origin,
destination,
collection_amount,
collection_amount,
current_branch,
weight,
pickup_latitude,
pickup_longitude,
map_latitude as delivery_latitude,
map_longitude as delivery_longitude,
status as current_status,
product_desc as item_info,
special_instruction as description ,
product_type_id,
order_type
 FROM orders WHERE track_no= '" . $track_no . "'";

$result = mysqli_query($con, $orderQuery);
$row = mysqli_fetch_assoc($result);
$currentBranchId = isset($row['current_branch']) ? $row['current_branch'] : '';
$sender_name = isset($row['sender_name']) ? $row['sender_name'] : '';
$business_name = isset($row['business_name']) ? $row['business_name'] : '';

$comQuery = mysqli_query($con, "SELECT subject,order_comment,comment_by from order_comments Where  track_no = '" . $track_no . "' ");
$comments = array();
while ($comRow = mysqli_fetch_assoc($comQuery)) {
    array_push($comments, $comRow);
}
$showNumberOf = getConfig('number_on_delivery');
$reciever_phone = isset($row['reciever_phone']) ? $row['reciever_phone'] : '';
if (isset($showNumberOf) && $showNumberOf == 'branch') {
    $reciever_phoneQuery = mysqli_query($con, "SELECT * from branches where id = " . $currentBranchId);
    $currentBranchRes = mysqli_fetch_assoc($reciever_phoneQuery);
    $reciever_phone = isset($currentBranchRes['phone']) ? $currentBranchRes['phone'] : '';
}
$service_type = isset($row['order_type']) ? $row['order_type'] : '';
if (isset($service_type) && $service_type != '') {
    $service_typeQuery = mysqli_query($con, "SELECT * from services where id = " . $service_type);
    $service_type_a = mysqli_fetch_assoc($service_typeQuery);
    $service_type = isset($service_type_a['service_type']) ? $service_type_a['service_type'] : '';
}
$product = isset($row['product_type_id']) ? $row['product_type_id'] : '';
if (isset($product) && $product != '') {
    $productQuery = mysqli_query($con, "SELECT * from products where id = " . $product);
    $product_a = mysqli_fetch_assoc($productQuery);
    $product = isset($product_a['name']) ? $product_a['name'] : '';
}
$service_icon = isset($row['order_type']) ? $row['order_type'] : '';
if (isset($service_icon) && $service_icon != '') {
    $service_typeQuery = mysqli_query($con, "SELECT * from services where id = " . $service_icon);
    $service_type_a = mysqli_fetch_assoc($service_typeQuery);
    $service_icon = '';
    if (isset($service_type_a['icon']) && !empty($service_type_a['icon'])) {
        $service_icon = 'admin/' . $service_type_a['icon'];
    }
}
$product_or_courier = "Courier";
if (isset($row['product_type_id']) && !empty($row['product_type_id'])) {
    $product_or_courier = $product;
}
$row['receiver_phone'] = $reciever_phone;
$row['service'] = $service_type;
$row['product'] = $product;
$row['service_icon'] = $service_icon;
$row['sender_name'] = isset($sender_name) ? $sender_name : $business_name;
$row['product_or_courier'] = $product_or_courier;
define("BASE_URL", "https://cods.com.pk/");
http_response_code(201);
echo json_encode(array("response" => 1, 'data' => $row, "comments" => $comments, "show_number" => $showNumberOf, "base_url" => "https://cods.com.pk/", "reciever_phone" => $reciever_phone));
exit();

exit();