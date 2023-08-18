<?php
date_default_timezone_set("Asia/Karachi");
include_once "../includes/conn.php";
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$content = (array)json_decode(file_get_contents("php://input"), TRUE);
$decoded = $content;
// echo '<pre>', print_r($decoded), '</pre>';
// exit;
$auth_key = $decoded['auth_key'];
$auth_query = mysqli_query($con, "SELECT * FROM customers WHERE auth_key ='" . $auth_key . "' AND api_status=1 ");
$count = mysqli_num_rows($auth_query);
if ($count == 0) {
	$error_msg = "Invalid Authentication Key";
	echo json_encode($error_msg);
	exit();
} else {
	$customer_data = mysqli_fetch_array($auth_query);
}
$customer_id = $customer_data['id'];
$orders_query = mysqli_query($con, "SELECT * FROM orders WHERE customer_id =" . $customer_id . " ORDER BY id DESC ");
$count = mysqli_num_rows($orders_query);
$orders_data = array();
if ($count == 0) {
	$error_msg = "No Record found for this customer";
	echo json_encode($error_msg);
	exit();
} else {
	while ($row = mysqli_fetch_array($orders_query)) {
		$data = array(
			'tracking_no' => $row['track_no'],
			'origin' => $row['origin'],
			'order_id' => $row['product_id'],
			'payment_status' => $row['payment_status'],
			'destination' => $row['destination'],
			'sender_name' => $row['sname'],
			'sender_company' => $row['sbname'],
			'sender_phone' => $row['sphone'],
			'sender_email' => $row['semail'],
			'sender_address' => $row['sender_address'],
			'receiver_name' => $row['rname'],
			'receiver_phone' => $row['rphone'],
			'receiver_address' => $row['receiver_address'],
			'collection_amount' => $row['collection_amount'],
			'delivery_charges' => $row['price'],
			'order_date' => $row['order_date'],
			'barcode_image' => $row['barcode_image'],
			'quantity' => $row['quantity'],
			'weight' => $row['weight'],
			'product_descriptiption' => $row['product_desc'],
			'special_instruction' => $row['special_instruction'],
			'status_date' => $row['action_date'],
			'status' => $row['status'],
		);
		array_push($orders_data, $data);
	}
}
if (!empty($orders_data)) {
	echo json_encode($orders_data);
	exit();
}