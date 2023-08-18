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
$trackNoExplode = explode(',', $tracking_no);
$trackNoArray = '';
foreach ($trackNoExplode as $key => $single) {
	$trackNoArray .= "'" . $single . "',";
}
$trackNoArray = rtrim($trackNoArray, ',');
$tracking_sql = "SELECT * FROM order_logs WHERE order_no IN(" . $trackNoArray . ") order by order_no ";

$track_query = mysqli_query($con, $tracking_sql);
$count = mysqli_num_rows($track_query);
$track_data = array();
if ($count == 0) {
	$error_msg = "Tracking not found";
	echo json_encode($error_msg);
	exit();
} else {
	while ($row = mysqli_fetch_array($track_query)) {
		$data = array(
			'tracking_no' => $row['order_no'],
			'status' => $row['order_status'],
			'created' => $row['created_on']
		);
		array_push($track_data, $data);
	}
}
if (!empty($track_data)) {
	echo json_encode($track_data);
	exit();
}