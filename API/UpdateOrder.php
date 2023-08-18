<?php
date_default_timezone_set("Asia/Karachi");
include_once "../includes/conn.php";
include_once '../admin/includes/weight_calculations.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$content = (array)json_decode(file_get_contents('php://input'), TRUE);
// $decoded = json_decode($content, true);
$decoded = $content;
// echo '<pre>', print_r($decoded), '</pre>';
// exit;
$track_no = isset($decoded['track_no']) ? $decoded['track_no'] :'';
if(!isset($track_no) || empty($track_no)){ 
	$error_array = array(
		'response' => 0, 
		'message' => "Order No is required!"
	);
	echo json_encode($error_array);
	exit();
}
$track_query = mysqli_query($con,"SELECT id, status,product_type_id,order_type,origin from orders where track_no = '$track_no'");
if(mysqli_num_rows($track_query)==0){
	$error_array = array(
		'response' => 0, 
		'message' => "No record found with order no# ".$track_no
	);
	echo json_encode($error_array);
	exit(); 
}
$track_row = mysqli_fetch_assoc($track_query); 
$status = isset($track_row['status']) ? $track_row['status'] :'';
if($status !='New Booked'){ 
	$error_array = array(
		'response' => 0, 
		'message' => "Only New Booked order can be updated!"
	);
	echo json_encode($error_array);
	exit();
}
$order_tracking_id = isset($track_row['id']) ? $track_row['id'] :'';
if(!isset($order_tracking_id) || empty($order_tracking_id) || $order_tracking_id==0){ 
	$error_array = array(
		'response' => 0, 
		'message' => "No record found with order no# ".$track_no
	);
	echo json_encode($error_array);
	exit();
}
$auth_key = $decoded['auth_key'];
$client_code = $decoded['client_code'];
$auth_query = mysqli_query($con, "SELECT * FROM customers WHERE auth_key ='" . $auth_key . "' AND client_code = '" . $client_code . "' AND api_status = 1 ");
$count = mysqli_num_rows($auth_query);
if ($count == 0) {
	$error_array = array(
		'response' => 0, 
		'message' => "Invalid Authentication Key Or Client Code!"
	);
	echo json_encode($error_array);
	exit(); 
} else {
	$customer_data = mysqli_fetch_array($auth_query);
}
// print_r($customer_data);
// function getBarCodeImage($text = '', $code = null, $index)
// {
// 	require_once('../includes/BarCode.php');
// 	$barcode = new BarCode();
// 	$path = '../assets/barcodes/imagetemp' . $index . '.png';
// 	$barcode->barcode($path, $text);
// 	$folder_path = 'assets/barcodes/imagetemp' . $index . '.png';
// 	return $folder_path;
// }
// echo "string";
// die;
// $product = isset($decoded['product']) ? $decoded['product'] : '';
// $product_sql = mysqli_query($con, "SELECT * FROM products Where name='" . $product . "'");
// $proRes = mysqli_fetch_assoc($product_sql);
$product_type_id = isset($track_row['product_type_id']) ? $track_row['product_type_id'] : '';
// echo $product_type_id;
// die;
$origin = isset($track_row['origin']) ? strtoupper($track_row['origin']) : '';
$origin = trim($origin);
$destination = isset($decoded['destination']) ? strtoupper($decoded['destination']) : '';
$destination = trim($destination);
$receiver_name = $decoded['receiver_name'];
$receiver_phone = $decoded['receiver_phone'];
$original_no = $receiver_phone;

$receiver_phone = $original_no;
$receiver_email = $decoded['receiver_email'];
$receiver_address = $decoded['receiver_address'];
$pieces = trim($decoded['pieces']);
$weight = trim($decoded['weight']);
$collection_amount = isset($decoded['collection_amount']) ? trim($decoded['collection_amount']) : 0;
$product_description = $decoded['product_description'];
$special_instruction = $decoded['special_instruction'];
$order_type = $decoded['service_type'];
$order_type = isset($track_row['order_type']) ? $track_row['order_type'] : '';
// $service_type_q = mysqli_query($con, "SELECT id FROM services WHERE service_type='" . $order_type . "' ");
// if (mysqli_num_rows($service_type_q) > 0) {
// 	$order_type_res = mysqli_fetch_array($service_type_q);
// 	$order_type = $order_type_res['id'];
// }
$date = date('Y-m-d H:i:s');
//validation
$validation_error = "";

// if (empty($order_type_id) && !empty($order_type_id)) {
// 	$validation_error = "Invalid service type";
// 	echo json_encode($validation_error);
// 	exit();
// }
if (empty($receiver_name)) {
	$validation_error = "Receiver Name is required";
}
// if (empty($receiver_phone)) {
// 	$validation_error = "Phone Number is required";
// }
// if (empty($receiver_email) || !filter_var($receiver_email, FILTER_VALIDATE_EMAIL)) {
// 	$validation_error = "Invalid email";
// }
if (empty($receiver_address)) {
	$validation_error = "Receiver address is required";
}
if (empty($pieces) || !is_numeric($pieces)) {
	$validation_error = "Invalid piece type";
}
// if (empty($collection_amount) || !is_numeric($collection_amount)) {
// 	$validation_error = "Invalid Collection Amount";
// }
if (empty($product_description)) {
	$validation_error = "Product Description is required";
}
if (!empty($validation_error)) {
	$error_array = array(
		'response' => 0, 
		'message' => $validation_error
	);
	echo json_encode($error_array);
	exit(); 
}
$customer_id = isset($customer_data['id']) ? $customer_data['id'] :'';
// echo "origin".$origin."<br>";
// echo "destination".$destination."<br>";
// echo "weight".$weight."<br>";
// echo "order_type".$order_type."<br>";
// echo "product_type_id".$product_type_id."<br>";
// echo "customer_id".$customer_id."<br>";
$delivery = delivery_calculation($origin, $destination, $weight, $customer_id, $order_type, $product_type_id);


if ($delivery == "" || $delivery == 0) { 
	$error_array = array(
		'response' => 0, 
		'message' => "Invalid charges calculation. please try with valid origin,destination,service type"
	);
	echo json_encode($error_array);
	exit();
}

$pick_time =  date('H:i:s');
if (isset($decoded['Pick_time']) and !empty($decoded['Pick_time'])) {
	$pick_time = date('H:i:s', strtotime($decoded['Pick_time']));
}
function remvoeQuotes($string = null)
{
	if ($string != null) {
		return str_replace('"', '', $string);
	}
}
// $is_fragile = isset()
//insert query 
$address = isset($customer_data['address']) ? remvoeQuotes($customer_data['address']) : '';
$product_description = isset($decoded['product_description']) ? remvoeQuotes($decoded['product_description']) : '';
$special_instruction = isset($decoded['special_instruction']) ? remvoeQuotes($decoded['special_instruction']) : '';
$pieces = isset($decoded['pieces']) ? $decoded['pieces'] : 1;
$Cust_ref = isset($decoded['Cust_ref']) ? $decoded['Cust_ref'] : '';
$is_fragile = isset($decoded['is_fragile']) ? $decoded['is_fragile'] : 0;
$pickup_location = isset($decoded['pickup_location']) ? remvoeQuotes($decoded['pickup_location']) : '';

$gst_query = mysqli_query($con, "SELECT value FROM config WHERE `name`='gst' ");
$total_gst = mysqli_fetch_array($gst_query);
$gst = isset($total_gst['value']) ? $total_gst['value'] : '0';

$fsc = getFuelValue($customer_id);
$gst_amount = 0;
$fsc_amount = 0;
$total_charges = 0;
$net_amount = 0;


$price = $delivery;

$fsc_amount = ($delivery / 100) * $fsc;
$delivery = $delivery + $fsc_amount;
$gst_amount = ($delivery / 100) * $gst;
$pft_amount = $gst_amount;
$total_charges = $delivery + $pft_amount + $fsc_amount;
$total_charges = $price;
$net_amount = $price + $gst_amount + $fsc_amount;

/// profile data
$customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $customer_id . " ");
$customer_data = mysqli_fetch_array($customer_query);

$fname = remvoeQuotes($customer_data['fname']);
$bname = remvoeQuotes($customer_data['bname']);
$mobile_no = $customer_data['mobile_no'];
$email = $customer_data['email'];
$customer_longitude = $customer_data['customer_longitude'];
$customer_latitude = $customer_data['customer_latitude'];

$profile_id = isset($decoded['profile_id']) ? $decoded['profile_id'] : '';
$profile_query = mysqli_query($con, "SELECT * FROM profiling WHERE customer_id=" . $customer_id . " AND profile_id= " . $profile_id);
$profile_Data = mysqli_fetch_array($profile_query);
if (isset($profile_Data['id']) && !empty($profile_Data['id'])) {
	$fname = remvoeQuotes($profile_Data['shipper_name']);
	$mobile_no = $profile_Data['shipper_phone'];
	$email = $profile_Data['shipper_email'];
	$customer_longitude = $profile_Data['shipper_latitude'];
	$customer_latitude = $profile_Data['shipper_longitude'];
	$address = $profile_Data['shipper_address'];
}

// echo $net_amount;
// die;
$update_query = 'UPDATE orders set `sname`="' . $fname . '",`sbname`="' . $bname . '",`sphone`="' . $mobile_no . '", `semail`="' . $email . '", `sender_address`="' . $address . '", `rname`="' . $receiver_name . '", `rphone`="' . $receiver_phone . '",`remail`="' . $receiver_email . '", `receiver_address`="' . $receiver_address . '",`price`="' . (int)$price . '",`collection_amount`="' . $collection_amount . '",`order_date`="' . $date . '",`customer_id`="' . $customer_id . '",`origin`="' . $origin . '",`destination`="' . $destination . '",`weight`="' . $weight . '",`quantity`="' . $pieces . '",`product_id`="' . $orderRandomId . '", `order_type`="' . $order_type . '",`pft_amount`="' . $pft_amount . '",`inc_amount`="' . $incl_amount . '",`Pick_location`="' . $pickup_location . '",`pickup_time`="' . $pick_time . '",`pickup_latitude`="' . $customer_latitude . '",`pickup_longitude`="' . $customer_longitude . '",`Cust_ref`="' . $Cust_ref . '",`is_fragile`="' . $is_fragile . '",`order_type_booking`=1 ,`net_amount`="' . $net_amount . '",`grand_total_charges`="' . $total_charges . '",`current_branch`=1,`booking_branch`=1,`product_type_id`="' . $product_type_id . '",`fuel_surcharge`="' . $fsc_amount . '",`fuel_surcharge_percentage`= "' . $fsc . '" WHERE id = '.$order_tracking_id.'';
// echo $update_query;
// die();
$query = mysqli_query($con, $update_query);
// $next_number = 0;
// $enableCNAllocation = getConfig('enable_cn_allocation');
// if (isset($enableCNAllocation) && $enableCNAllocation == 1) {

// 	$isNumberAvailableQuery = mysqli_query($con, "SELECT * from cn_allocation_master WHERE customer_id=" . $customer_id . " AND is_used=0 ORDER BY id ASC");
// 	$cnAvailResult = mysqli_fetch_assoc($isNumberAvailableQuery);
// 	$nextAvailNumber = isset($cnAvailResult['cn']) ? $cnAvailResult['cn'] : '';
// 	if (isset($nextAvailNumber) && !empty($nextAvailNumber)) {
// 		$next_number = $nextAvailNumber;
// 		mysqli_query($con, "UPDATE cn_allocation_master set is_used = 1 WHERE cn ='" . $next_number . "'");
// 	} else {
// 		$err_response = array();
// 		$err_response['error'] = 1;
// 		$err_response['alert_msg'] = "All Assigned CN for this account are used. Please contact administration for new CN Allocation.";
// 		echo json_encode($err_response);
// 		exit();
// 	}
// }


if (mysqli_affected_rows($con) >0 ) {
	// if ($next_number > 0) {
	// 	$track_no = $next_number;
	// } elseif (isset($customer_data['is_order_manual']) and $customer_data['is_order_manual'] == 1) {
	// 	$track_no = $_POST['track_no'];
	// } else {
	// 	$track_no = $insert_id + 6000000;
	// }
	// $barcode_image = getBarCodeImage($track_no, null, $insert_id);
	// mysqli_query($con, "UPDATE orders SET barcode = '" . $track_no . "', barcode_image = '" . $barcode_image . "', track_no = '" . $track_no . "' WHERE id = $insert_id");
	// mysqli_query($con, "INSERT INTO order_logs(`order_no`,`order_status`,`location`,`created_on`) VALUES ('" . $track_no . "', 'Order is Booked', '" . $origin . "','" . $date . "') ");
	$succ_array = array(
		"response"=>1,
		'tracking_no' => $track_no, 
		'message' => "Order " . $track_no . " updated successfully"
	);
	echo json_encode($succ_array);
	exit();
} else {
	$error_array = array(
		"response"=>0,  
		'message' => "Error Occured Try Again Later!"
	);
	echo json_encode($error_array);
	exit(); 
}