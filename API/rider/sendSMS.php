<?php
require_once('../../admin/includes/conn.php');
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$data_post = (array)json_decode(file_get_contents("php://input"));
$track_no = $data_post['track_no'];
$rider_id = $data_post['rider_id'];
$type = $data_post['type'];
$lat = isset($data_post['lat']) ? $data_post['lat'] : '';
$long = isset($data_post['long']) ? $data_post['long'] : '';
$location = isset($data_post['location']) ? $data_post['location'] : '';
mysqli_query($con, "UPDATE users set location = '' WHERE id = $rider_id");
$updateSql = "UPDATE `users` SET `location` = 'http://maps.google.com/?q=" . $lat . "," . $long . "' WHERE `users`.`id` = $rider_id";
// echo $updateSql;
// die;
mysqli_query($con,  $updateSql);
$template = '';
if ($type == 'pickup') {
    $template = 'Pickup SMS';
} else {
    $template = 'Delivery SMS'; //'Customer Booking'
}

include "../admin/includes/sms_helper.php";
$sms = sendSmsMobileGateWay($track_no, $template);

if ($sms) {
    http_response_code(201);
    echo json_encode(array("response" => 1, 'message' => "SMS Sent!"));
    exit();
} else {
    http_response_code(200);
    echo json_encode(array("response" => 0, "message" => "Error Occured Try Again!"));
    exit();
}
exit();