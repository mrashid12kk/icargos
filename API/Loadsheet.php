<?php
date_default_timezone_set("Asia/Karachi");
include_once "../includes/conn.php";
include_once '../admin/includes/weight_calculations.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$content = $_GET;
$auth_key = trim($_GET['auth_key']);
$sql = "SELECT id,auth_key,bname,client_code,mobile_no,address FROM customers WHERE auth_key ='" . $auth_key . "' AND api_status=1 ";

$auth_query = mysqli_query($con, $sql);
$count = mysqli_num_rows($auth_query);
if ($count == 0) {
	$error_msg = "Invalid Authentication Key";
	echo json_encode($error_msg);
	exit();
} else {
	$customer_data = mysqli_fetch_array($auth_query);
}
if (!function_exists("getOrderTypeByName")){
    function getOrderTypeByName($id)
    {
        global $con;
        $result = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM services WHERE id =".$id));
        
        return $result['service_type'];
    }
}
$order_ids = $_GET['search'];
$code = $_GET['search'];
$explode_orders = explode(',', $code);
$implode = "'" . implode("', '", $explode_orders) . "'";
$customer_id = $customer_data['id'];
$orders_query = mysqli_query($con, "SELECT * FROM orders WHERE customer_id =" . $customer_id . " AND track_no IN (" . $implode . ") ");
$count = mysqli_num_rows($orders_query);
$orders_data = array();
$logo = getConfig('logo');
$second_new_footer = getConfig('second_new_footer');
if ($count == 0) {
	$error_msg = "No Record found for this customer";
	echo json_encode($error_msg);
	exit();
} else {
	$customer_arr['customer'] = array();
	$customer_arr['customer'] = $customer_data;
	array_push($orders_data, $customer_arr);
	while ($row = mysqli_fetch_array($orders_query)) {
		$data = array(
			'tracking_no' => $row['track_no'],
			'order_id' => $row['product_id'],
			'pickup_date' => $row['pickup_date'],
			'order_type' => getOrderTypeByName($row['order_type']),
			'customer_id' => $row['customer_id'],
			'origin' => $row['origin'],
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
			'logo' => BASE_URL . 'admin/' . $logo,
			'second_new_footer' => $second_new_footer,
			'quantity' => $row['quantity'],
			'weight' => $row['weight'],
			'product_descriptiption' => $row['product_desc'],
			'special_instruction' => $row['special_instruction'],
			'status_date' => $row['action_date'],
			'status' => $row['status'],
			'ref_no' => isset($row['ref_no']) ? $row['ref_no'] : '',
			'payment_status' => $row['payment_status'],
		);
		array_push($orders_data, $data);
	}
}
if (!empty($orders_data)) {
	echo json_encode($orders_data);
	exit();
}