<?php
require_once('../../admin/includes/conn.php');
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$data_post = (array)json_decode(file_get_contents("php://input"));
$rider_id = isset($data_post['rider_id']) ? $data_post['rider_id'] : '';
$lat = isset($data_post['lat']) ? $data_post['lat'] : '';
$long = isset($data_post['long']) ? $data_post['long'] : '';
$deliverQ = "SELECT assignment_record.order_num as track_no, 
orders.collection_amount, orders.status,  
orders.rname as receiver_name,   
orders.receiver_address, 
map_latitude,
map_longitude,
orders.rphone as receiver_phone ,
( 3959 * acos( cos( radians($lat) ) * cos( radians( orders.map_latitude ) ) * 
cos( radians( orders.map_longitude ) - radians($long) ) + sin( radians($lat) ) * 
sin( radians( orders.map_latitude ) ) ) ) AS distance
from assignment_record join orders on assignment_record.order_num = orders.track_no WHERE assignment_record.rider_status_done_no = '0' AND assignment_record.assignment_type=2  AND  assignment_record.user_id =" . $rider_id . "   order by distance asc ";
// echo $deliverQ;
// die;
$deliverResponse = mysqli_query($con, $deliverQ);
$data = [];
while ($row = mysqli_fetch_assoc($deliverResponse)) {
    if (isset($row['map_latitude']) && !empty($row['map_latitude']) && !empty($row['map_longitude']) && $row['map_longitude'] != "" && $row['map_latitude'] != "") {
        array_push($data, $row);
    }
}

http_response_code(201);
echo json_encode(array("response" => 1, 'data' => $data));
exit();

exit();