<?php
require_once('../../admin/includes/conn.php');
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$data_post = (array)json_decode(file_get_contents("php://input"));
$rider_id = $data_post['rider_id'];
$pickQ = "SELECT assignment_record.order_num as track_no, orders.collection_amount,orders.pickup_latitude,orders.pickup_longitude,orders.map_latitude,orders.map_longitude, orders.status, orders.order_type, orders.sname as sender_name, orders.sbname as business_name,   orders.sender_address, orders.sphone as sender_phone from assignment_record join orders on assignment_record.order_num = orders.track_no WHERE assignment_record.rider_status_done_no = '0' AND assignment_record.assignment_type=1  AND  assignment_record.user_id =" . $rider_id . "   order by assignment_record.id desc ";

$pickupresponse = mysqli_query($con, $pickQ);
$optimizeOrders = [];
$nonOptimizeOrders = [];
while ($row = mysqli_fetch_assoc($pickupresponse)) {
    $service_icon = isset($row['order_type']) ? $row['order_type'] : '';
    if (isset($service_icon) && $service_icon != '') {
        $service_typeQuery = mysqli_query($con, "SELECT * from services where id = " . $service_icon);
        $service_type_a = mysqli_fetch_assoc($service_typeQuery);
        $service_icon = '';
        if (isset($service_type_a['icon']) && !empty($service_type_a['icon'])) {
            $service_icon = 'admin/' . $service_type_a['icon'];
        }
    }
    $row['service_icon'] = $service_icon;
    if (isset($row['pickup_latitude']) && !empty($row['pickup_latitude']) && !empty($row['pickup_longitude']) && $row['pickup_longitude'] != "" && $row['pickup_latitude'] != "") {
        array_push($optimizeOrders, $row);
    } else {
        array_push($nonOptimizeOrders, $row);
    }
}

http_response_code(201);
echo json_encode(array("response" => 1, 'optimizeOrders' => $optimizeOrders, 'nonOptimizeOrders' => $nonOptimizeOrders));
exit();

exit();