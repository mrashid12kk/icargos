<?php
require_once('../../admin/includes/conn.php');
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$data_post = (array)json_decode(file_get_contents("php://input"));
$rider_id = $data_post['rider_id'];
$searchQ = "SELECT * FROM users Where id = $rider_id";
$query = mysqli_query($con, $searchQ);
$data = mysqli_fetch_assoc($query);
http_response_code(201);
echo json_encode(array("response" => 1, 'data' => $data, "base_url" => BASE_URL . 'admin/'));
exit();

exit();