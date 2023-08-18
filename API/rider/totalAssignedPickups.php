<?php
require_once('../../admin/includes/conn.php');
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$data_post = (array)json_decode(file_get_contents("php://input"));
$rider_id = $data_post['rider_id'];
$sql = "SELECT * FROM assignment_record WHERE rider_status_done_no = '0' AND user_id =" . $rider_id . " AND assignment_type=1  order by id desc ";

$query1 = mysqli_query($con, $sql);
$orderspickupcount = mysqli_affected_rows($con);
if ($rider_id) {
    http_response_code(201);
    echo json_encode(array("response" => 1, "TotalPickups" => $orderspickupcount));
    exit();
} else {
    http_response_code(200);
    echo json_encode(array("response" => 0, "message" => "Error Occured !"));
    exit();
}

exit();