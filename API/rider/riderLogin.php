<?php
require_once('../../admin/includes/conn.php');
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$data_post = (array)json_decode(file_get_contents("php://input"));
$user_name = $data_post['user_name'];
$query = mysqli_query($con, "SELECT * from users where user_name='$user_name' or email='$user_name'") or die(mysqli_error($con));
$fetch = mysqli_fetch_assoc($query);
$password = mysqli_real_escape_string($con, $data_post['password']);
$hash = $fetch['password'];
if (password_verify($password, $hash)) {
    http_response_code(201);
    echo json_encode(array("response" => 1, 'data' => $fetch, "message" => "Login successfull.", "base_url" => BASE_URL . 'admin/'));
    exit();
} else {
    http_response_code(200);
    echo json_encode(array("response" => 0, "message" => "Login Failed"));
    exit();
}

exit();