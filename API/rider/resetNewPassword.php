<?php
require_once('../../admin/includes/conn.php');
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$data_post = (array)json_decode(file_get_contents("php://input"));
$rider_id = $data_post['rider_id'];
$new_password = $data_post['new_password'];
$setPass = mysqli_real_escape_string($con, password_hash($new_password, PASSWORD_DEFAULT));
mysqli_query($con, "UPDATE users set password = '" . $setPass . "' WHERE id= $rider_id");
http_response_code(201);
echo json_encode(array("response" => 1, "message" => "Password Updated Successflly."));
exit();

exit();