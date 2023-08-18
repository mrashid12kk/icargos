<?php
date_default_timezone_set("Asia/Karachi");
include_once "../includes/conn.php";
include '../price_calculation.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$content = (array)json_decode(file_get_contents("php://input"));
$tracking_no = trim($content['tracking_no']);  
$tracking_sql = "SELECT * FROM orders WHERE track_no ='".$tracking_no."' ";

$track_query = mysqli_query($con, $tracking_sql);
$count = mysqli_num_rows($track_query);
$track_data = mysqli_fetch_assoc($track_query);
if ($count == 0) {
	$error_msg = "Tracking not found";
	echo json_encode($error_msg);
	exit();
} else { 
	echo json_encode($track_data);
	exit(); 
}
