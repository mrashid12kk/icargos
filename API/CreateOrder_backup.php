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
$auth_key = $decoded['auth_key'];
$client_code = $decoded['client_code'];
$auth_query = mysqli_query($con, "SELECT * FROM customers WHERE auth_key ='" . $auth_key . "' AND client_code = '" . $client_code . "' AND api_status = 1 ");
$count = mysqli_num_rows($auth_query);
if ($count == 0) {
	$error_msg = "Invalid Authentication Key Or Client Code!";
	echo json_encode($error_msg);
	exit();
} else {
	$customer_data = mysqli_fetch_array($auth_query);
}
// print_r($customer_data);
function getBarCodeImage($text = '', $code = null, $index)
{
	require_once('../includes/BarCode.php');
	$barcode = new BarCode();
	$path = '../assets/barcodes/imagetemp' . $index . '.png';
	$barcode->barcode($path, $text);
	$folder_path = '../assets/barcodes/imagetemp' . $index . '.png';
	return $folder_path;
}
$product = isset($decoded['product']) ? $decoded['product'] : '';
$product_sql = mysqli_query($con, "SELECT * FROM products Where name='" . $product . "'");
$proRes = mysqli_fetch_assoc($product_sql);
$product_type_id = isset($proRes['id']) ? $proRes['id'] : '';
// echo $product_type_id;
// die;
$origin = $decoded['origin'];
$origin = trim($origin);
$destination = $decoded['destination'];
$destination = trim($destination);
$receiver_name = $decoded['receiver_name'];
$receiver_phone = $decoded['receiver_phone'];
$original_no = $receiver_phone;

$receiver_phone = $original_no;
$receiver_email = $decoded['receiver_email'];
$receiver_address = $decoded['receiver_address'];
$pieces = trim($decoded['pieces']);
$weight = trim($decoded['weight']);
$collection_amount = trim($decoded['collection_amount']);
$product_description = $decoded['product_description'];
$special_instruction = $decoded['special_instruction'];
$order_type = $decoded['service_type'];
$order_type_id = '';
$service_type_q = mysqli_query($con, "SELECT id FROM services WHERE service_type='" . $order_type . "' ");
if (mysqli_num_rows($service_type_q) > 0) {
	$order_type_res = mysqli_fetch_array($service_type_q);
	$order_type = $order_type_res['id'];
}
$date = isset($decoded['order_date']) ? date('Y-m-d H:i:s', strtotime($decoded['order_date'])) :  date('Y-m-d H:i:s');
//validation
$validation_error = "";

if (empty($order_type_id) && !empty($order_type_id)) {
	$validation_error = "Invalid service type";
	echo json_encode($validation_error);
	exit();
}
if (empty($receiver_name)) {
	$validation_error = "Receiver Name is required";
}
if (empty($receiver_phone)) {
	$validation_error = "Phone Number is required";
}
if (empty($receiver_email) || !filter_var($receiver_email, FILTER_VALIDATE_EMAIL)) {
	$validation_error = "Invalid email";
}
if (empty($receiver_address)) {
	$validation_error = "Receiver address is required";
}
if (empty($pieces) || !is_numeric($pieces)) {
	$validation_error = "Invalid piece type";
}
if (empty($collection_amount) || !is_numeric($collection_amount)) {
	$validation_error = "Invalid Collection Amount";
}
if (empty($product_description)) {
	$validation_error = "Product Description is required";
}
if (!empty($validation_error)) {
	echo json_encode($validation_error);
	exit();
}
$customer_id = $customer_data['id'];

$delivery = delivery_calculation($origin, $destination, $weight, $customer_id, $order_type, $product_type_id);


if ($delivery == "" || $delivery == 0) {
	$validation_error = "Invalid charges calculation. please try with valid origin,destination,service type";
	echo json_encode($validation_error);
	exit();
}
 
$pick_time =  isset($decoded['pickup_time']) ? date("H:i:s", strtotime($decoded['pickup_time'])) : date('H:i:s');
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

$profile_id = isset($decoded['profile_id']) ? $decoded['profile_id'] :'';
$profile_query = mysqli_query($con, "SELECT * FROM profiling WHERE customer_id=" . $customer_id . " AND profile_id= ".$profile_id);
$profile_Data = mysqli_fetch_array($profile_query);
if(isset($profile_Data['id']) && !empty($profile_Data['id'])){
	$fname = remvoeQuotes($profile_Data['shipper_name']); 
	$mobile_no = $profile_Data['shipper_phone'];
	$email = $profile_Data['shipper_email'];
	$customer_longitude = $profile_Data['shipper_latitude'];
	$customer_latitude = $profile_Data['shipper_longitude'];
	$address = $profile_Data['shipper_address'];
}
$insert_qry = 'INSERT INTO `orders`(`sname`,`sbname`,`sphone`, `semail`, `sender_address`, `rname`, `rphone`,`remail`, `receiver_address`,`price`,`collection_amount`,`order_date`,`customer_id`,`origin`,`destination`,`weight`,`product_desc`,`special_instruction`,`quantity`,`product_id`, `order_type`,`pft_amount`,`inc_amount`,`Pick_location`,`pickup_time`,`pickup_latitude`,`pickup_longitude`,`Cust_ref`,`is_fragile`,`order_type_booking`,`net_amount`,`grand_total_charges`,`current_branch`,`booking_branch`,`product_type_id`,`fuel_surcharge`,`fuel_surcharge_percentage`) VALUES ("' . $fname . '","' . $bname . '","' . $mobile_no . '","' . $email . '","' . $address . '","' . $receiver_name . '","' . $receiver_phone . '","' . $receiver_email . '","' . $receiver_address . '","' . (int)$price . '","' . $decoded['collection_amount'] . '","' . $date . '","' . $customer_id . '","' . $origin . '","' . $destination . '","' . $weight . '","' . $product_description . '","' . $special_instruction . '","' . $pieces . '","' . $orderRandomId . '","' . $order_type . '","' . $pft_amount . '","' . $incl_amount . '","' . $pickup_location . '","' . $pick_time . '","' . $customer_latitude . '","' . $customer_longitude . '","' . $Cust_ref . '","' . $is_fragile . '",1 ,"' . $net_amount . '","' . $total_charges . '",1,1,"' . $product_type_id . '","' . $fsc_amount . '","' . $fsc . '")';
// echo $insert_qry;
// die();
$query = mysqli_query($con, $insert_qry);
$insert_id = mysqli_insert_id($con);
$next_number = 0;
$enableCNAllocation = getConfig('enable_cn_allocation');
if (isset($enableCNAllocation) && $enableCNAllocation == 1) {

	$isNumberAvailableQuery = mysqli_query($con, "SELECT * from cn_allocation_master WHERE customer_id=" . $customer_id . " AND is_used=0 ORDER BY id ASC");
	$cnAvailResult = mysqli_fetch_assoc($isNumberAvailableQuery);
	$nextAvailNumber = isset($cnAvailResult['cn']) ? $cnAvailResult['cn'] : '';
	if (isset($nextAvailNumber) && !empty($nextAvailNumber)) {
		$next_number = $nextAvailNumber;
		mysqli_query($con, "UPDATE cn_allocation_master set is_used = 1 WHERE cn ='" . $next_number . "'");
	} else {
		$err_response = array();
		$err_response['error'] = 1;
		$err_response['alert_msg'] = "All Assigned CN for this account are used. Please contact administration for new CN Allocation.";
		echo json_encode($err_response);
		exit();
	}
}


if ($insert_id > 0) {
	if ($next_number > 0) {
		$track_no = $next_number;
	} elseif (isset($customer_data['is_order_manual']) and $customer_data['is_order_manual'] == 1) {
		$track_no = $_POST['track_no'];
	} else {
		$track_no = $insert_id + 6000000;
	} 
	$barcode_image = getBarCodeImage($track_no, null, $insert_id);
	mysqli_query($con, "UPDATE orders SET barcode = '" . $track_no . "', barcode_image = '" . $barcode_image . "', track_no = '" . $track_no . "' WHERE id = $insert_id");
	mysqli_query($con, "INSERT INTO order_logs(`order_no`,`order_status`,`location`,`created_on`) VALUES ('" . $track_no . "', 'Order is Booked', '" . $_POST['origin'] . "','" . $date . "') ");
	$succ_array = array(
		'tracking_no' => $track_no,
		'id' => $insert_id,
		'message' => "Order " . $track_no . " created successfully"
	);
	echo json_encode($succ_array);
	exit();
} else {

	echo json_encode("Invalid parameters");
	exit();
}