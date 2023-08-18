<?php

require 'includes/conn.php';
include "admin/includes/sms_helper.php";
//  ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// if (isset($_POST['delete'])) {
// 	$file = $_POST['remanefile'];
// 	if (file_exists('excel/' . $file)) {
// 		unlink('excel/' . $file);
// 		echo 'Successfully';
// 	} else {
// 		echo 'UnSuccessfully';
// 	}
// }
if (isset($_FILES) && !empty($_FILES)) {
	if ($_FILES['file']['error'] > 0) {
		echo 'Error: ' . $_FILES['file']['error'] . '<br>';
	} else {
		$filename   = $_POST['remanefile'];
		if ($filename != $_FILES["file"]["name"]) {
			$extension  = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
			$basename   = $filename;
			$source       = $_FILES["file"]["tmp_name"];
			$destination  = "excel/" . $basename;
			if (move_uploaded_file($source, $destination)) {
				$data['percentage'] = "100";
				$data['filename'] = $target_file;
				echo json_encode($data);
			} else {
				$data['percentage'] = '0';
				echo json_encode($data);
			}
		} else {
			$extension  = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
			$target_file = uniqid() . basename($_FILES["file"]["name"]);
			$basename   = $target_file;
			$source       = $_FILES["file"]["tmp_name"];
			$destination  = "excel/" . $basename;
			if (move_uploaded_file($source, $destination)) {
				$data['percentage'] = "100";
				$data['filename'] = $target_file;
				echo json_encode($data);
			} else {
				$data['percentage'] = '0';
				echo json_encode($data);
			}
		}
	}
}
if (!function_exists("getcountryid")){
	function getcountryid($id)
	{
		global $con;
		$result = mysqli_fetch_assoc(mysqli_query($con, "SELECT id FROM country WHERE country_name ='".$id."'"));
		$output = '';
		if (isset($result['id']))
		{
			$output = $result['id'];
		}
		return $output;
	}
}
function getBarCodeImage($text = '', $code = null, $index)
{
	require_once('includes/BarCode.php');
	$barcode = new BarCode();
	$path = 'assets/barcodes/imagetemp' . $index . '.png';
	$barcode->barcode($path, $text);
	$folder_path = 'assets/barcodes/imagetemp' . $index . '.png';
	return $folder_path;
}
if (isset($_POST['bulk_booking']) && !empty($_POST['bulk_booking'])) {
	// include_once '../../price_calculation.php';
	include_once 'admin/includes/weight_calculations.php';
	require_once('PHPExcel/Classes/PHPExcel/IOFactory.php');
	$_SESSION['customers'] = $_POST['customer_id'];
	$customer_id = $_SESSION['customers'];
	$customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $customer_id . " ");
	$customer_data = mysqli_fetch_array($customer_query);
	
	$gst_query = mysqli_query($con, "SELECT value FROM config WHERE `name`='gst' ");
	$total_gst = mysqli_fetch_array($gst_query);
	$gst = isset($total_gst['value']) ? $total_gst['value'] : '0';
	$fsc_query = mysqli_query($con, "SELECT value FROM config WHERE `name`='fsc' ");
	$total_fsc = mysqli_fetch_array($fsc_query);
	$fsc = isset($total_fsc['value']) ? $total_fsc['value'] : '0';
	$customer_id = $_SESSION['customers'];
	// include_once 'admin/includes/weight_calculations.php';
	$change_file_name = $_POST['change_file_name'];

	$objPHPExcel = PHPExcel_IOFactory::load('excel/' . $change_file_name);
	$getsheet = $objPHPExcel->getActiveSheet()->toArray(null);
	$order_ids = array();
	
	//check if all data is correct
	unset($getsheet[0]);
	foreach ($getsheet as $row) {
		if (isset($row[0]) && !empty($row[0])) {
			if (isset($customer_data['is_order_manual']) and $customer_data['is_order_manual'] == 1) :
				if (isset($row[0]) and !empty($row[0])) {
					$track_no = $row[0];
					unset($row[0]);
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
			$origin = $row[21];
			$destination = ucwords($row[2]);
			$order_type_str = $row[14];
			$service_type_q = mysqli_query($con, "SELECT id FROM services WHERE service_type LIKE '" . $order_type_str . "' ");
			$order_type_res = mysqli_fetch_array($service_type_q);
			$order_type = $order_type_res['id'];
			$product_type_str = $row[10];
			$product_type_q = mysqli_query($con, "SELECT id FROM products WHERE name LIKE '" . $product_type_str . "' ");
			$product_type_res = mysqli_fetch_array($product_type_q);
			$product_type_id = isset($product_type_res['id']) ? $product_type_res['id'] :2;
			// $product_type_id = 2;
			$weight = $row[13];
			//check destination city
			$country_id = getcountryid($destination);
			$destination_city_name = ucwords($row[3]);
			$destination_city = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM cities where country_id='$country_id' AND city_name='$destination_city_name'"));
			if (!isset($destination_city['id']) && $destination_city['id']=='') {
				$err_response = array();
				$err_response['error'] = 1;
				$err_response['alert_msg'] =  $destination_city_name." Is not Avialable In ".$destination." In Consignee Info";
				echo json_encode($err_response);
				exit();
			}
			$country_id = getcountryid($origin);
			$origin_city_name = ucwords($row[22]);
			$origin_city = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM cities where country_id='$country_id' AND city_name='$origin_city_name'"));
			if (!isset($origin_city['id']) && $origin_city['id']=='') {
				$err_response = array();
				$err_response['error'] = 1;
				$err_response['alert_msg'] =  $origin_city_name." Is not Avialable In ".$destination." In Shipper Info";
				echo json_encode($err_response);
				exit();
			}
			$delivery = delivery_calculation($origin, $destination, $weight, $customer_id, $order_type, $product_type_id,'international');
			// $delivery = 123;
			// echo $delivery;die();
			if ($delivery <= 0) {
				$err_response = array();
				$err_response['error'] = 1;
				$err_response['alert_msg'] =  "No zone found for Origin " . $origin . " and Distination " . $destination;
				echo json_encode($err_response);
				exit();
			}
			// echo '1';
		} else {
			$success = '100';
			echo json_encode($success);
			exit();
		}
	}
	$success = '100';
	echo json_encode($success);
	exit();
}

if (isset($_POST['save_booking']) && !empty($_POST['save_booking'])) {
	// include_once '../../price_calculation.php';
	include_once 'admin/includes/weight_calculations.php';
	require_once('PHPExcel/Classes/PHPExcel/IOFactory.php');
	$_SESSION['customers'] = $_POST['customer_id'];
	$customer_id = $_SESSION['customers'];
	$customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $customer_id . " ");
	$customer_data = mysqli_fetch_array($customer_query);
	$gst_query = mysqli_query($con, "SELECT value FROM config WHERE `name`='gst' ");
	$total_gst = mysqli_fetch_array($gst_query);
	$gst = isset($total_gst['value']) ? $total_gst['value'] : '0';
	$fsc_query = mysqli_query($con, "SELECT value FROM config WHERE `name`='fsc' ");
	$total_fsc = mysqli_fetch_array($fsc_query);
	$fsc = isset($total_fsc['value']) ? $total_fsc['value'] : '0';
	$customer_id = $_SESSION['customers'];
	$change_file_name = $_POST['change_file_name'];

	$objPHPExcel = PHPExcel_IOFactory::load('excel/' . $change_file_name);
	$getsheet = $objPHPExcel->getActiveSheet()->toArray(null);
	$order_ids = array();
	
	//check if all data is correct
	unset($getsheet[0]);
	// echo "<pre>";
	// print_r ($getsheet);
	// echo "</pre>";
	$countrow = 0;
	foreach ($getsheet as $row2) {
		if (isset($row2[0]) && !empty($row2[0])) {
			$countrow++;
		}
	}
	$countrowloop = $countrow / 10;
	foreach ($getsheet as $row) {
		if (isset($row[0]) && !empty($row[0])) {
			$insert_id = '';
			$trackNo = '';
			if (isset($customer_data['is_order_manual']) and $customer_data['is_order_manual'] == 1) :
				$trackNo = $row[0];
				unset($row[0]);
				$row = array_values($row);
			endif;
			// $product_type_str = $row[0];
			// $product_type_q = mysqli_query($con, "SELECT id FROM products WHERE name LIKE '" . $product_type_str . "' ");
			// $product_type_res = mysqli_fetch_array($product_type_q);
			$product_type_id = 1;
			$order_type_str = $row[14];
			$service_type_q = mysqli_query($con, "SELECT id FROM services WHERE service_code LIKE '" . $order_type_str . "' ");
			$order_type_res = mysqli_fetch_array($service_type_q);
			$order_type = $order_type_res['id'];
			$origin = $row[21];
			$destination = ucwords($row[2]);
			$sender_name = $row[20];
			$sender_phone = $row[24];
			$sender_phone2 = $row[25];
			// $sender_email = $row[5];
			$sender_address = $row[23];
			$receiver_name = $row[1];
			// $receiver_email = $row[8];
			$receiver_phone = $row[7];
			$receiver_phone_2 = $row[8];
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
			$receiver_address = $row[4];
			$order_id = '';
			$pieces = $row[12];
			if (empty($pieces)) {
				$pieces = 0;
			}
			$weight = $row[13];
			$cod_amount = isset($row[9]) && !empty($row[9]) ? $row[9] : 0;
			$product_description = $row[11];
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
			$sstate=$row[29];
			$scity=$row[22];
			$google_address=$row[6];
			$scnic=$row[32];
			$map_latitude=$row[17];
			$map_longitude=$row[28];
			$customer_currency=$row[16];
			$rstate=$row[27];
			$rcity=$row[3];
			$product_type_str = $row[10];
			$product_type_q = mysqli_query($con, "SELECT id FROM products WHERE name LIKE '" . $product_type_str . "' ");
			$product_type_res = mysqli_fetch_array($product_type_q);
			$product_type_id = isset($product_type_res['id']) ? $product_type_res['id'] :2;
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
				$insert_qry = 'INSERT INTO `orders`(`sname`,`sbname`,`sphone`,`sender_address`,`rname`,`rphone`,`receiver_address`,`google_address`,`rstate`,`rcity`,`sstate`,`scity`,`pickup_date`,`price`,`collection_amount`,`order_date`,`action_date`,`order_time`,`payment_method`,`customer_id`,`origin`,`destination`,`weight`,`product_desc`,`quantity`,`order_type`,`pft_amount`,`order_type_booking`,`net_amount`,`grand_total_charges`,`scnic`,`booking_type`,`product_type_id`,`pickup_latitude`,`pickup_longitude`,`map_latitude`,`map_longitude`,`customer_currency`,`order_booking_type`) VALUES ("' . $sender_name . '","' . $customer_data['bname'] . '","' . $sender_phone . '","' . $sender_address . '","' . $receiver_name . '","' . $receiver_phone . '","' . $receiver_address . '","' . $google_address . '","'.$rstate.'","'.$rcity.'","'.$sstate.'","'.$scity.'","' . $date . '","' . $price . '","' . $cod_amount . '","' . $date . '","' . $date . '","' . $date . '","CASH","' . $customer_id . '","' . $origin . '","' . $destination . '","' . $weight . '","' . $product_description . '" ,"' . $pieces . '","' . $order_type . '","' . $gst_amount . '",4,"' . $net_amount . '","' . $total_charges . '","' . $scnic . '" ,1,"' . $product_type_id . '","' . $customer_data['customer_longitude'] . '","' . $customer_data['customer_latitude'] . '","' . $map_latitude . '","' . $map_longitude . '","' . $customer_currency . '",1)';
				$next_number = 0;
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
					// $next_number = 0;
					// $client_code = isset($customer_data['client_code']) ? $customer_data['client_code'] : '';
					// $customer_city = isset($customer_data['city']) ? $customer_data['city'] : '';
					// $cityQ = mysqli_fetch_assoc(mysqli_query($con, "SELECT area_code from cities where city_name='$customer_city'"));
					// $area_code = isset($cityQ['area_code']) ?  $cityQ['area_code'] : '';
					// $nextNoSql = "SELECT COUNT(id) as max_no from orders where customer_id=$customer_id";
					// $nextNoQ = mysqli_query($con, $nextNoSql);
					// $nextNoRes = mysqli_fetch_assoc($nextNoQ);
					// $nextNo = isset($nextNoRes['max_no']) ? $nextNoRes['max_no'] : '';
					// if (isset($nextNo) && !empty($nextNo) && $nextNo > 0) {
					// 	$nextNo = $nextNo + 1;
					// } else {
					// 	$nextNo = 1;
					// }
					// $get_number = $client_code * 10000000;
					// $next_number = $get_number + $nextNo;
					// $next_number = $area_code . $next_number;
					// if ($next_number > 0) {
					// 	$trackNo = $next_number;
					// } else
					if (isset($customer_data['is_order_manual']) and $customer_data['is_order_manual'] == 1) {
						$trackNo = $trackNo;
					} else {
						$trackNo = $insert_id + 11200001000;
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
					// sendSmsMobileGateWay($trackNo, 'Customer Booking');
				}
				$order_ids[] = $insert_id;
			}
		}
	}
	// die;
	$err_response = array();
	$err_response['process'] = '100';
	$err_response['data_ids'] = implode(",", $order_ids);
	$_SESSION['bulk_message'] = 'File In Uploaded Successfully';
	echo json_encode($err_response);
	exit();
}