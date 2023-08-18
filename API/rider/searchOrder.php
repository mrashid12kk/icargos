<?php
require_once('../../admin/includes/conn.php');
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$data_post = (array)json_decode(file_get_contents("php://input"));
$search = $data_post['search'];
$searchQ = "SELECT sname as sender_name, track_no, sender_address, sphone as sender_phone, rname as receiver_name, rphone as receiver_phone, collection_amount, status as order_status, receiver_address,map_latitude,map_longitude FROM orders WHERE sname LIKE  '%$search%' OR sphone LIKE  '%$search%' OR rname LIKE  '%$search%' OR rphone LIKE  '%$search%' OR destination LIKE  '%$search%' OR semail LIKE  '%$search%' OR track_no LIKE  '%$search%' OR origin LIKE  '%$search%'";

$query = mysqli_query($con, $searchQ);
$data = [];
$optimizeOrders = [];
$nonOptimizeOrders = [];
while ($row = mysqli_fetch_assoc($query)) {
    if (isset($row['map_latitude']) && !empty($row['map_latitude']) && !empty($row['map_longitude']) && $row['map_longitude'] != "" && $row['map_latitude'] != "") {
        array_push($optimizeOrders, $row);
    } else {
        array_push($nonOptimizeOrders, $row);
    }
}

http_response_code(201);
echo json_encode(array("response" => 1, 'optimizeOrders' => $optimizeOrders, 'nonOptimizeOrders' => $nonOptimizeOrders));
exit();

exit();