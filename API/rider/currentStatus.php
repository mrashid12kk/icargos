<?php
require_once('../../admin/includes/conn.php');
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$data_post = (array)json_decode(file_get_contents("php://input"));
$track_no = $data_post['track_no'];
$statusQ = mysqli_query($con, "SELECT status from orders where track_no=  '" . $track_no . "'");

$statusResult = mysqli_fetch_assoc($statusQ);

$currentStatus = isset($statusResult['status']) ? $statusResult['status'] : '';

if ($currentStatus && !empty($currentStatus)) {
    http_response_code(201);
    echo json_encode(array("response" => 1, "status" => $currentStatus));
    exit();
} else {
    http_response_code(200);
    echo json_encode(array("response" => 0, "message" => "Error Occured!"));
    exit();
}

exit();