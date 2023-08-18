<?php
date_default_timezone_set("Asia/Karachi");
include_once "../includes/conn.php";
$content = $_GET;
$auth_key = trim($content['auth_key']);
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
$total_orders_query = mysqli_query($con, "SELECT COUNT(*) as total_orders  FROM orders WHERE  status !='cancelled' AND customer_id =" . $customer_id . " ");
$count = mysqli_num_rows($total_orders_query);

$total_booked_orders_query = mysqli_query($con, "SELECT COUNT(*) as booked_orders  FROM orders WHERE  customer_id =" . $customer_id . " AND status='New Booked' ");
$count2 = mysqli_num_rows($total_booked_orders_query);
$orders_data = array();
if (!empty($count) && $count > 0) {
	$total_o = mysqli_fetch_array($total_orders_query);
	$total_orders = $total_o['total_orders'];
	$orders_data['total_orders'] = $total_orders;
}
if (!empty($count2) && $count2 > 0) {
	$total_o = mysqli_fetch_array($total_booked_orders_query);
	$total_orders = $total_o['booked_orders'];
	$orders_data['total_booked_orders'] = $total_orders;
}

if (!empty($orders_data)) {
	echo json_encode($orders_data);
	exit();
}