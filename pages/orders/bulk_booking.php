<?php
session_start();
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
function encrypt($string)
{
	$key = "usmannnn";
	$result = '';
	for ($i = 0; $i < strlen($string); $i++) {
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key)) - 1, 1);
		$char = chr(ord($char) + ord($keychar));
		$result .= $char;
	}
	return base64_encode($result);
}
require '../../includes/conn.php';
include_once '../../price_calculation.php';
// 
function getBarCodeImage($text = '', $code = null, $index)
{
	require_once('../../includes/BarCode.php');
	$barcode = new BarCode();
	$path = '../../assets/barcodes/imagetemp' . $index . '.png';
	$barcode->barcode($path, $text);
	$folder_path = 'assets/barcodes/imagetemp' . $index . '.png';
	return $folder_path;
}
function getGst($origin, $customer_id)
{
	global $con;
	$origin = $origin;
	$q = mysqli_query($con, "SELECT * FROM cities WHERE city_name ='" . $origin . "' ");
	$res = mysqli_fetch_array($q);
	$state_id = isset($res['state_id']) ? $res['state_id'] : '';
	$gst_percentage = 0;
	if (isset($state_id) && !empty($state_id)) {
		$stateQ = mysqli_query($con, "SELECT tax FROM state WHERE id =" . $state_id);
		$stateResult = mysqli_fetch_array($stateQ);
		$gst_percentage = isset($stateResult['tax']) ? $stateResult['tax'] : '';
	}
	return $gst_percentage;
}
if (isset($_POST['bulk_booking']) && !empty($_POST['bulk_booking'])) {
	// echo "<pre>";
	// print_r($_POST);
	// die;


	$customer_id = $_SESSION['customers'];
	$customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $customer_id . " ");
	$customer_data = mysqli_fetch_array($customer_query);
	// echo "<pre>";
	// print_r($customer_data);
	// die
	// ;
	$gst_query = mysqli_query($con, "SELECT value FROM config WHERE `name`='gst' ");
	$total_gst = mysqli_fetch_array($gst_query);
	$gst = isset($total_gst['value']) ? $total_gst['value'] : '0';
	$fsc_query = mysqli_query($con, "SELECT value FROM config WHERE `name`='fsc' ");
	$total_fsc = mysqli_fetch_array($fsc_query);
	$fsc = isset($total_fsc['value']) ? $total_fsc['value'] : '0';
	$customer_id = $_SESSION['customers'];
	$bulk_data = $_POST['bulk_data'];
	$order_ids = array();
	//check if all data is correct
	foreach ($bulk_data as $row) {
		if (isset($customer_data['is_order_manual']) and $customer_data['is_order_manual'] == 1) :
			if (isset($row[2]) and !empty($row[2])) {
				$track_no = $row[2];
				unset($row[2]);
				$row = array_values($row);
				$check_track_no_exist = mysqli_query($con, "SELECT id FROM orders WHERE track_no= '" . $track_no . "' ");
				if (mysqli_num_rows($check_track_no_exist) > 0) {
					$err_response = array();
					$err_response['error'] = 1;
					$err_response['alert_msg'] = $track_no . " Order no already exist.";
					echo json_encode($err_response);
					exit();
				}
			} else {
				$err_response = array();
				$err_response['error'] = 1;
				$err_response['alert_msg'] =  " Order no is required.";
				echo json_encode($err_response);
				exit();
			}
		endif;
		// echo "<pre>";
		// print_r($_POST);
		// die;
		$origin = ucwords($row[2]);
		$destination = ucwords($row[3]);
		$order_type_str = $row[1];
		$service_type_q = mysqli_query($con, "SELECT id FROM services WHERE service_type LIKE '" . $order_type_str . "' ");
		$order_type_res = mysqli_fetch_array($service_type_q);
		$order_type = $order_type_res['id'];
		$product_type_str = $row[0];
		$product_type_q = mysqli_query($con, "SELECT id FROM products WHERE name LIKE '" . $product_type_str . "' ");
		$product_type_res = mysqli_fetch_array($product_type_q);
		$product_type_id = $product_type_res['id'];
		$weight = $row[13];

		$delivery = delivery_calculation($origin, $destination, $weight, $customer_id, $order_type, $product_type_id);
		// echo $delivery;die();
		if ($delivery <= 0) {
			$err_response = array();
			$err_response['error'] = 1;
			$err_response['alert_msg'] =  "No zone found for Origin " . $origin . " and Distination " . $destination;
			echo json_encode($err_response);
			exit();
		}
	}
	foreach ($bulk_data as $row) {
		$insert_id = '';
		$trackNo = '';
		if (isset($customer_data['is_order_manual']) and $customer_data['is_order_manual'] == 1) :
			$track_no = $row[2];
			unset($row[2]);
			$row = array_values($row);
		endif;
		$product_type_str = $row[0];
		$product_type_q = mysqli_query($con, "SELECT id FROM products WHERE name LIKE '" . $product_type_str . "' ");
		$product_type_res = mysqli_fetch_array($product_type_q);
		$product_type_id = $product_type_res['id'];
		$order_type_str = $row[1];
		$service_type_q = mysqli_query($con, "SELECT id FROM services WHERE service_type LIKE '" . $order_type_str . "' ");
		$order_type_res = mysqli_fetch_array($service_type_q);
		$order_type = $order_type_res['id'];
		$origin = ucwords($row[2]);
		$destination = ucwords($row[3]);
		$sender_name = $row[5];
		$sender_phone = $row[6];
		// $sender_email = $row[5];
		$sender_address = $row[7];
		$receiver_name = $row[8];
		// $receiver_email = $row[8];
		$receiver_phone = $row[9];
		$original_no = $receiver_phone;
		$original_no  = preg_replace('/[^0-9]/s', '', $original_no);
		$pos0 = substr($original_no, 0, 1);
		if ($pos0 == '3') {
			$alterno = substr($original_no, 1);
			$alterno = '0' . $original_no;
			$original_no = $alterno;
		}
		$pos = substr($original_no, 0, 2);
		if ($pos == '03') {
			$alterno = substr($original_no, 1);
			$alterno = '92' . $alterno;
			$original_no = $alterno;
		}
		$receiver_phone = $original_no;
		$receiver_address = $row[10];
		$ref_no = $row[11];
		$order_id = $row[12];
		$pieces = $row[13];
		if (empty($pieces)) {
			$pieces = 0;
		}
		$weight = $row[14];
		$cod_amount = $row[15];
		$product_description = $row[16];
		// $special_instruction = $row[17];
		// $order_type = $row[17];

		$price = 0;
		$delivery = delivery_calculation($origin, $destination, $weight, $customer_id, $order_type, $product_type_id);
		// echo $delivery;
		// die;
		// if($delivery <=0){
		// 	$err_response = array();
		// 	$err_response['error'] = 1;
		// 	$err_response['alert_msg'] =  " No zone found for Origin ".$origin. " and Distination ".$destination;
		// 	echo json_encode($err_response); exit();
		// 	continue;
		// }
		$price = $delivery;
		$gst_amount = 0;
		$fsc_amount = 0;
		$total_charges = 0;
		$net_amount = 0;
		$gst_amount = ($delivery / 100) * $gst;
		$fsc_amount = ($delivery / 100) * $fsc;
		$total_charges = $delivery + $pft_amount + $fsc_amount;
		if (isset($_SESSION['customers'])) {
			$customer_id = $_SESSION['customers'];
			$total_charges = $delivery;
			$net_amount = $delivery + $gst_amount;
			$date = date('Y-m-d H:i:s');
			$customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $customer_id . " ");
			$customer_data = mysqli_fetch_array($customer_query);
			$insert_qry = "INSERT INTO `orders`(`sname`,`sbname`, `sphone`, `sender_address`, `rname`, `rphone`, `receiver_address`,`pickup_date`,`price`,`collection_amount`,`order_date`,`action_date`,`payment_method`,`customer_id`,`origin`,`destination`,`weight`,`product_id`,`product_desc`,`quantity`,`ref_no`,`order_type`,`pft_amount`,`grand_total_charges`,`net_amount`,`order_type_booking`,`booking_type`,`product_type_id`) VALUES ('" . $sender_name . "','" . $customer_data['bname'] . "', '" . $sender_phone . "','" . $sender_address . "','" . $receiver_name . "','" . $receiver_phone . "','" . $receiver_address . "','" . $date . "','" . $price . "','" . $cod_amount . "','" . $date . "','" . $date . "','CASH','" . $customer_id . "','" . $origin . "','" . $destination . "','" . $weight . "','" . $order_id . "','" . $product_description . "' ,'" . $pieces . "','" . $ref_no . "','" . $order_type . "','" . $gst_amount . "','" . $total_charges . "','" . $net_amount . "','3',1,'" . $product_type_id . "') ";
			// echo $insert_qry;
			// die();
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
			$query = mysqli_query($con, $insert_qry);
			$insert_id = mysqli_insert_id($con);
			// echo $insert_id;
			// 	die;
			if ($insert_id > 0) {

				// $cus_sql = mysqli_query($con, "SELECT * FROM customres WHERE id=".$customer_id);
				// $cus_data = mysqli_fetch_assoc($cus_sql);
				$customer_branch = 1;
				mysqli_query($con, "UPDATE orders set booking_branch=" . $customer_branch . ", current_branch = 1 WHERE id = " . $insert_id);

				// include_once '../../admin/includes/weight_calculations.php';
				// $weight_calculations_detail = backendCalculations($delivery, $customer_id, $insert_id);

				// Start Backend Calcualtion
				$gst_percent = 0;
				$fuelsurcharge_percent = 0;
				$special_charges = 0;
				$total_charges = 0;
				$net_amount = 0;
				$order_query = mysqli_query($con, "SELECT * FROM orders WHERE id=" . $insert_id . " ");

				$order_data = mysqli_fetch_array($order_query);
				$origin = isset($order_data['origin']) ? $order_data['origin'] : '';
				$customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $customer_id . " ");
				$customer_data = mysqli_fetch_array($customer_query);
				$admin_other_charges   = getconfig('admin_other_charges');
				if (isset($customer_data['is_fuelsurcharge']) && $customer_data['is_fuelsurcharge'] == 1) {
					$fuelsurcharge_percent   = getconfig('fuel_surcharge');
					$customer_fuel_charge = getconfig('customer_fuel_charge');
					if (isset($customer_fuel_charge) && $customer_fuel_charge == 1) {
						$customer_wise_charges_query = mysqli_query($con, "SELECT * FROM customer_wise_charges WHERE charge_name = 'fuel_surcharge' AND customer_id=" . $customer_id . " ");
						$customer_wise_charges_data = mysqli_fetch_array($customer_wise_charges_query);
						$fuelsurcharge_percent = isset($customer_wise_charges_data['charge_value']) ? $customer_wise_charges_data['charge_value'] : '';
					}
				}
				$gst_percent   = getGst($origin, $customer_id);

				if ($admin_other_charges == 1) {
					$charges_q = mysqli_query($con, "SELECT * FROM charges");
					while ($row = mysqli_fetch_array($charges_q)) {
						if (isset($row['charge_value']) && $row['charge_value']) {
							$special_charges += $row['charge_value'];
							$charges_id = isset($row['id']) ? $row['id'] : '';
							$charges_type = isset($row['charge_type']) ? $row['charge_type'] : '';
							$charges_amount = isset($row['charge_value']) ? $row['charge_value'] : '';
							$last_inserted_id = mysqli_query($con, "INSERT INTO  `order_charges`(`charges_id`,`charges_type`,`charges_amount`,`order_id`) VALUES('" . $charges_id . "','" . $charges_type . "','" . $charges_amount . "','" . $insert_id . "')");
						}
					}
				}
				$total_charges = ($delivery + $special_charges);
				$fuel_surcharge = ($total_charges / 100 * $fuelsurcharge_percent);
				$net_amount = ($total_charges + $fuel_surcharge);
				$pft_amount = ($net_amount / 100 * $gst_percent);
				$net_amount = ($net_amount + $pft_amount);
				mysqli_query($con, "UPDATE orders SET net_amount = '" . $net_amount . "',grand_total_charges = '" . $total_charges . "',special_charges = '" . $special_charges . "', pft_amount = '" . $pft_amount . "', fuel_surcharge = " . $fuel_surcharge . " WHERE id = $insert_id ");

				// End Backend Calculation
				if ($next_number > 0) {
					$trackNo = $next_number;
				} elseif (isset($customer_data['is_order_manual']) and $customer_data['is_order_manual'] == 1) {
					$trackNo = $_POST['track_no'];
				} else {
					$trackNo = $insert_id + 6000000;
				}
				$barcode = rand(1000000, 9999999);
				$barcode = substr($barcode, 0, strlen($barcode) - strlen($insert_id));
				$barcode .= $insert_id;
				$barcode_image = getBarCodeImage($trackNo, null, $trackNo);

				mysqli_query($con, "UPDATE orders SET barcode = '" . $trackNo . "', barcode_image = '" . $barcode_image . "', track_no = '" . $trackNo . "' WHERE id = $insert_id");
				mysqli_query($con, "INSERT INTO order_logs(`order_no`,`order_status`,`location`,`created_on`) VALUES ('" . $trackNo . "', 'Order is Booked', '" . $origin . "','" . $date . "') ");
				///////////send Email 
				// include_once "sendEmail/customer_booking.php";
				// email_customer_booking($customer_id,$insert_id);
				sendSmsMobileGateWay($trackNo, 'Customer Booking');
			}
			$order_ids[] = $insert_id;
		}
	}
	$err_response = array();
	$err_response['data_ids'] = implode(",", $order_ids);
	echo json_encode($err_response);
	exit();
}