<?php
require_once('../../admin/includes/conn.php');
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$data_post = (array)json_decode(file_get_contents("php://input"));
$rider_id = $data_post['rider_id'];
$sql = "SELECT balance FROM rider_wallet_ballance WHERE rider_id=" . $rider_id;
$response = mysqli_query($con, $sql);
$result = mysqli_fetch_assoc($response);
$rider_balance = isset($result['balance']) ? $result['balance'] : 0;
if ($rider_id) {
    http_response_code(201);
    echo json_encode(array("response" => 1, "walletBalance" => $rider_balance));
    exit();
} else {
    http_response_code(200);
    echo json_encode(array("response" => 0, "message" => "Error Occured !"));
    exit();
}

exit();