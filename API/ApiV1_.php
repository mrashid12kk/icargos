<?php
date_default_timezone_set("Asia/Karachi");
include_once "../includes/conn.php";
include '../price_calculation.php';
if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') != 0){
    throw new Exception('Request method must be POST!');
}
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
if(strcasecmp($contentType, 'application/json') != 0){
}
$content = trim(file_get_contents("php://input"));

$decoded = json_decode($content, true);
if(!is_array($decoded)){
    throw new Exception('Received content contained invalid JSON!');
}
$auth_key = $decoded['auth_key'];
$auth_query = mysqli_query($con,"SELECT * FROM customers WHERE auth_key ='".$auth_key."' ");
$count = mysqli_num_rows($auth_query);
if($count == 0){
	echo "Invalid Authentication Key"; exit();
}else{
	$customer_data = mysqli_fetch_array($auth_query);
}
function getBarCodeImage($text = '', $code = null, $index) {
  require_once('../includes/BarCode.php');
  $barcode = new BarCode();
  $path = '../assets/barcodes/imagetemp'.$index.'.png';
  $barcode->barcode($path, $text);
  $folder_path='../assets/barcodes/imagetemp'.$index.'.png';
  return $folder_path;
}

// insertorder();
// getorders();
trackorder();
function insertorder(){
	global $decoded;
	global $customer_data;
	global $con;
	$origin = $decoded['origin'];
	$destination = $decoded['destination'];
	$weight = $decoded['weight'];
	$customer_id = $customer_data['id'];
	$order_type = $decoded['order_type'];
	$delivery = delivery_calculation(null,$destination,$weight,$customer_id,$order_type);
	 $date=date('Y-m-d H:i:s');
	//insert query 
	$insert_qry="INSERT INTO `orders`(`sname`,`sbname`,`sphone`, `semail`, `sender_address`, `rname`, `rphone`,`remail`, `receiver_address`,`price`,`collection_amount`,`order_date`,`customer_id`,`origin`,`destination`,`tracking_no`,`weight`,`product_desc`,`special_instruction`,`quantity`,`product_id`, `order_type`) VALUES ('".$customer_data['fname']."','".$customer_data['bname']."','".$customer_data['mobile_no']."','".$customer_data['email']."','".$customer_data['address']."','".$decoded['receiver_name']."','".$decoded['receiver_phone']."','".$decoded['receiver_email']."','".$decoded['receiver_address']."','".(int)$delivery."','".$decoded['collection_amount']."','".$date."','".$customer_id."','".$origin."','".$destination."','".$decoded['tracking_no']."','".$weight."','".$decoded['product_description']."','".$decoded['special_instruction']."','".$decoded['pieces']."','','".$order_type."') ";
	$query=mysqli_query($con,$insert_qry);
	$insert_id=mysqli_insert_id($con);
	if($insert_id > 0) {
			$track_no = $insert_id+20000000;
			$barcode_image = getBarCodeImage($track_no, null, $insert_id);
			mysqli_query($con, "UPDATE orders SET barcode = '".$track_no."', barcode_image = '".$barcode_image."', track_no = '".$track_no."' WHERE id = $insert_id");
			mysqli_query($con,"INSERT INTO order_logs(`order_no`,`order_status`,`location`,`created_on`) VALUES ('".$track_no."', 'Order is Booked', '".$_POST['origin']."','".$date."') ");
		}
}
//get customer orders list
function getorders(){
	global $decoded;
	global $customer_data;
	global $con;
	$customer_id = $customer_data['id'];
	$orders_query = mysqli_query($con,"SELECT * FROM orders WHERE customer_id =".$customer_id." ");
	$count = mysqli_num_rows($orders_query);
	$orders_data = array();
	if($count == 0){
		echo "No Record found for this customer"; exit();
	}else{
		while($row = mysqli_fetch_array($orders_query)){
			array_push($orders_data, $row);
		}
	}
	if(!empty($orders_data)){
		echo json_encode($orders_data); exit();
	}
}
function trackorder(){
	global $decoded;
	global $customer_data;
	global $con;
	$tracking_no = $decoded['tracking_no'];
	$track_query = mysqli_query($con,"SELECT * FROM order_logs WHERE order_no='".$tracking_no."' order by id ");
	$count = mysqli_num_rows($track_query);
	$track_data = array();
	if($count == 0){
		echo "Tracking not found"; exit();
	}else{
		while($row = mysqli_fetch_array($track_query)){
			array_push($track_data, $row);
		}
	}
	if(!empty($track_data)){
		echo json_encode($track_data); exit();
	}
}	

?>