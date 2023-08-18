<?php
require_once('../../admin/includes/conn.php');
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$data_post = (array)json_decode(file_get_contents("php://input"));
$rider_id = $data_post['rider_id'];
$pickQ = "SELECT 
assignment_record.order_num as track_no,
orders.delivery_assignment_no as assignment_no,
orders.delivery_zone_id as route_code,
delivery_zone.route_name
from assignment_record 
join orders 
on assignment_record.order_num = orders.track_no 
join delivery_zone
on delivery_zone.route_code = orders.delivery_zone_id
WHERE assignment_record.rider_status_done_no = '0' 
AND assignment_record.assignment_type=2  
AND  assignment_record.user_id =" . $rider_id . "   
Group By orders.delivery_zone_id";
// echo $pickQ;
// die;
$pickupresponse = mysqli_query($con, $pickQ);
$data = [];
$count = 0;
while ($row = mysqli_fetch_assoc($pickupresponse)) {
	array_push($data, $row);
	$count++;
}

http_response_code(201);
echo json_encode(array("response" => 1, 'data' => $data, 'count' => $count));
exit();

exit();