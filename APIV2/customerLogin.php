<?php
require_once('../admin/includes/conn.php');
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$data_post = (array)json_decode(file_get_contents("php://input"));

$email = mysqli_real_escape_string($con, $data_post['email']);
$email = strtolower($email);
$password = mysqli_real_escape_string($con, md5($data_post['password']));
$query = mysqli_query($con, "SELECT * from customers where LOWER(email)='$email' AND password = '" . $password . "' AND status = 1 ");
$count = mysqli_affected_rows($con);
if ($count > 0) {
    $fetch = mysqli_fetch_array($query);
    $customer_id = $fetch['id'];
    mysqli_query($con, "UPDATE customers SET is_online = 1 WHERE id = " . $fetch['id']);
    http_response_code(201);
    echo json_encode(array("response" => 1, 'customer_id' => $customer_id, "message" => "Login Successfull"));
    exit();
} else {
    http_response_code(200);
    echo json_encode(array("response" => 0, "message" => "Login Failed"));
    exit();
}