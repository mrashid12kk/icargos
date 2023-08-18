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
$pickQ = "SELECT assignment_record.order_num as track_no,
 orders.collection_amount, 
 orders.status,  
 orders.sname as sender_name, 
 orders.sbname as business_name, 
 pickup_latitude,
 pickup_longitude,  
 orders.sender_address, 
 orders.sphone as sender_phone ,
 ( 3959 * acos( cos( radians($lat) ) * cos( radians( orders.pickup_latitude ) ) * 
cos( radians( orders.pickup_longitude ) - radians($long) ) + sin( radians($lat) ) * 
sin( radians( orders.pickup_latitude ) ) ) ) AS distance
from assignment_record join orders on assignment_record.order_num = orders.track_no WHERE assignment_record.rider_status_done_no = '0' AND assignment_record.assignment_type=1  AND  assignment_record.user_id =$rider_id  HAVING
distance < 25  ORDER BY distance ASC LIMIT 0 , 25";

$pickupresponse = mysqli_query($con, $pickQ);
$data = [];
while ($row = mysqli_fetch_assoc($pickupresponse)) {
    if (isset($row['pickup_latitude']) && !empty($row['pickup_latitude']) && !empty($row['pickup_longitude']) && $row['pickup_longitude'] != "" && $row['pickup_latitude'] != "") {
        array_push($data, $row);
    }
}

http_response_code(201);
echo json_encode(array("response" => 1, 'data' => $data));
exit();

exit();