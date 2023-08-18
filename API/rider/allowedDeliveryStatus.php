<?php
require_once('../../admin/includes/conn.php');
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$data_post = (array)json_decode(file_get_contents("php://input"));

$query = mysqli_query($con, "SELECT status, color_code,reason_id as reason_enable, signature_enable from order_status Where delivery_rider = 1") or die(mysqli_error($con));
$data = [];

while ($row = mysqli_fetch_assoc($query)) {
    array_push($data, $row);
}

if (isset($data) && !empty($data)) {
    echo json_encode(array("response" => 1, "data" => $data));
} else {
    echo json_encode(array("response" => 0, "message" => "Error Occured!"));
}

exit();