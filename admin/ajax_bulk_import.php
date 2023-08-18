<?php

require 'includes/conn.php';
include "admin/includes/sms_helper.php";
//  ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// if (isset($_POST['remane'])) {


//     $remanefile = 'excel/'.$_POST['remanefile'];
//     $oldfile = 'excel/'.$_POST['oldfile'];

//     if($remanefile != $oldfile){
//     	echo "string";
//     	die;
// 	    if (!file_exists("excel/".$_POST['remanefile'])) {
// 		    if (rename($oldfile , $remanefile)) {
// 		    	echo 'Updated Successfully';
// 		    }
// 		    else{
// 		    	echo 'Updated UnSuccessfully';
// 		    }
// 		}
// 		else{
// 			echo '19';
// 		}
// 	}
// }
// if (isset($_FILES) && !empty($_FILES)) {

//  	 if ( $_FILES['file']['error'] > 0 ){
//         echo 'Error: ' . $_FILES['file']['error'] . '<br>';
// 	}
// 	else {

// 		   if(move_uploaded_file( $source, $destination ))
// 		    {
// 		        echo "100";
// 		    }
// 		}
// 		else{
// 			return '0';
// 		}
// 	}
// }
if (isset($_POST['delete'])) {
	$file = $_POST['remanefile'];
	if (file_exists('excel/' . $file)) {
		unlink('excel/' . $file);
		echo 'Successfully';
	} else {
		echo 'UnSuccessfully';
	}
}
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

function getBarCodeImage($text = '', $code = null, $index)
{
	require_once('../includes/BarCode.php');
	$barcode = new BarCode();
	$path = 'assets/barcodes/imagetemp' . $index . '.png';
	$barcode->barcode($path, $text);
	$folder_path = 'assets/barcodes/imagetemp' . $index . '.png';
	return $folder_path;
}
if (isset($_POST['bulk_booking']) && !empty($_POST['bulk_booking'])) {

	// include_once '../../price_calculation.php';
	include_once 'includes/weight_calculations.php';
	require_once('../PHPExcel/Classes/PHPExcel/IOFactory.php');
	// $_SESSION['customers'] = $_POST['customer_id'];
	// $customer_id = $_SESSION['customers'];
	// $customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $customer_id . " ");
	// $customer_data = mysqli_fetch_array($customer_query);
	$gst_query = mysqli_query($con, "SELECT value FROM config WHERE `name`='gst' ");
	$total_gst = mysqli_fetch_array($gst_query);
	$gst = isset($total_gst['value']) ? $total_gst['value'] : '0';
	$fsc_query = mysqli_query($con, "SELECT value FROM config WHERE `name`='fsc' ");
	$total_fsc = mysqli_fetch_array($fsc_query);
	$fsc = isset($total_fsc['value']) ? $total_fsc['value'] : '0';
	$customer_id = $_SESSION['customers'];
	// include_once 'admin/includes/weight_calculations.php'; 
	$change_file_name = $_POST['change_file_name'];

	$objPHPExcel = PHPExcel_IOFactory::load(getcwd().'/excel/' . $change_file_name);
	$getsheet = $objPHPExcel->getActiveSheet()->toArray(null);
	$order_ids = array();

	//check if all data is correct
	unset($getsheet[0]);
	foreach ($getsheet as $rows) {

			foreach ($rows as $key => &$value) {
				$value = trim($value);
			}
			$row = $rows;
			$customer_id = $row[17];
			$customer_query = mysqli_query($con, "SELECT * FROM customers WHERE client_code=" . $customer_id . " ");
			$customer_data = mysqli_fetch_array($customer_query);
		if (isset($row[0]) && !empty($row[0])) {
			if (isset($customer_data['is_order_manual']) and $customer_data['is_order_manual'] == 1) :
				if (isset($row[0]) and !empty($row[0])) {
					$track_no = $row[0];
					// unset($row[11]);
					// $row = array_values($row);
					$check_track_no_exist = mysqli_query($con, "SELECT id FROM orders WHERE track_no= '" . $track_no . "' ");
					if (mysqli_num_rows($check_track_no_exist) > 0) {
						$err_response = array();
						$err_response['error'] = 1;
						$err_response['alert_msg'] = $track_no . " Order no already exist.";
						echo json_encode($err_response);
						exit();
					}
				} 
				else {
					$err_response = array();
					$err_response['error'] = 1;
					$err_response['alert_msg'] =  " Order no is required.";
					echo json_encode($err_response);
					exit();
				}
			endif;
			$origin = ucwords($row[3]);
			$destination = ucwords($row[4]);
			$order_type_str = $row[2];
			$ser = "SELECT id FROM services WHERE service_code LIKE '%" . $order_type_str . "%' " ;
			$service_type_q = mysqli_query($con, $ser);
			// var_dump($ser);
			$order_type_res = mysqli_fetch_array($service_type_q);
			$order_type = $order_type_res['id'];
			$product_type_str =$row[1];
			$product_type_q = mysqli_query($con, "SELECT id FROM products WHERE name LIKE '%" . $product_type_str . "%' ");
			$product_type_res = mysqli_fetch_array($product_type_q);
			$product_type_id = $product_type_res['id'];
			$weight = $row[13];
			$customers = $row[17];
			// client_code
			$customer = mysqli_query($con, "SELECT * FROM customers WHERE client_code = '" . $customers . "' ");
			$customer_ = mysqli_fetch_array($customer);
			$customer_id = $customer_['id'];
			// var_dump($origin);
			// var_dump($destination);
			// var_dump($customer_id);
			// var_dump($order_type);
			// var_dump($product_type_id);
			$delivery = delivery_calculation($origin, $destination, $weight, $customer_id, $order_type, $product_type_id);
						// var_dump($delivery);

			if ($delivery <= 0) {
				$err_response = array();
				$err_response['error'] = 1;
				$err_response['alert_msg'] =  "No zone found for Product ".$product_type_str." and Service ".$order_type_str." Origin " . $origin . " and Distination " . $destination;
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
include_once 'includes/weight_calculations.php';
require_once('../PHPExcel/Classes/PHPExcel/IOFactory.php');
// $_SESSION['customers'] = $_POST['customer_id'];
// $customer_id = $_SESSION['customers'];
// $customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $customer_id . " ");
// $customer_data = mysqli_fetch_array($customer_query);





$fsc_query = mysqli_query($con, "SELECT value FROM config WHERE `name`='fsc' ");
$total_fsc = mysqli_fetch_array($fsc_query);
$fsc = isset($total_fsc['value']) ? $total_fsc['value'] : '0';
// $customer_id = $_SESSION['customers'];
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
// echo "<pre>";
// print_r($getsheet);
// die;
foreach ($getsheet as $rows) {

	foreach ($rows as $key => &$value) {
		$value = trim($value);
		}
		$row = $rows;
	if (isset($row[0]) && !empty($row[0])) {

		$insert_id = '';
		$trackNo = '';
		$customer_id = $row[17];
		$customer_query = mysqli_query($con, "SELECT * FROM customers WHERE client_code=" . $customer_id . " ");

		$customer_data = mysqli_fetch_array($customer_query);

		if (isset($customer_data['is_order_manual']) and $customer_data['is_order_manual'] == 1) :
			$track_no = $row[0];
			// unset($row[11]);
			// $row = array_values($row);
		endif;
		$product_type_str = $row[1];
		$product_type_q = mysqli_query($con, "SELECT id FROM products WHERE name LIKE '" . $product_type_str . "' ");
		$product_type_res = mysqli_fetch_array($product_type_q);
		$product_type_id = $product_type_res['id'];
		$order_type_str = $row[2];
		$service_type_q = mysqli_query($con, "SELECT id FROM services WHERE service_code LIKE '" . $order_type_str . "' ");
	
		$order_type_res = mysqli_fetch_array($service_type_q);
		$order_type = $order_type_res['id'];
		$origin = $row[3];
		$destination = $row[4];
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
		if($cod_amount == null){
			$cod_amount = '0';
		}
		$product_description = $row[16];
		$customer_ids = $customer_data['id'];
		$price = 0;
		$delivery = delivery_calculation($origin, $destination, $weight, $customer_ids, $order_type, $product_type_id);
		$q = mysqli_query($con, "SELECT * FROM cities WHERE city_name ='" . $origin . "' ");
		$res = mysqli_fetch_array($q);
		$state_id = isset($res['state_id']) ? $res['state_id'] : '';
		$gst = 0;
		if (isset($state_id) && !empty($state_id)) {
			$stateQ = mysqli_query($con, "SELECT tax FROM state WHERE id =" . $state_id);
			$stateResult = mysqli_fetch_array($stateQ);
			$gst = isset($stateResult['tax']) ? $stateResult['tax'] : '';
		}

		$price = $delivery;
		$gst_amount = 0;
		$fsc_amount = 0;
		$total_charges = 0;
		$net_amount = 0;
		$gst_amount = ($delivery / 100) * $gst;
		$fsc_amount = ($delivery / 100) * $fsc;
		$total_charges = $delivery + $pft_amount + $fsc_amount;
		// var_dump($_SESSION);
		if (isset($_SESSION)) {
			$total_charges = $delivery;
			$net_amount = $delivery + $gst_amount;
			$date = $_REQUEST['date'] .' '.date('H:i:s');
			// var_dump($date);
			// die();
			// $date = date('Y-m-d H:i:s');
			$branch_id = $_SESSION['branch_id'];
			$customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $customer_ids . " ");
			$customer_data = mysqli_fetch_array($customer_query);
			$insert_qry = 'INSERT INTO `orders`(`sname`,`sbname`, `sphone`, `sender_address`, `rname`, `rphone`, `receiver_address`,`pickup_date`,`price`,`collection_amount`,`order_date`,`action_date`,`payment_method`,`customer_id`,`origin`,`destination`,`weight`,`product_id`,`product_desc`,`quantity`,`ref_no`,`order_type`,`pft_amount`,`grand_total_charges`,`net_amount`,`order_type_booking`,`booking_type`,`product_type_id`,`branch_id`,`status`) VALUES ("' . $sender_name . '","' . $customer_data['bname'] . '", "' . $sender_phone . '","' . $sender_address . '","' . $receiver_name . '","' . $receiver_phone . '","' . $receiver_address . '","' . $date . '","' . $price . '","' . $cod_amount . '","' . $date . '","' . $date . '","CASH","' . $customer_ids . '","' . $origin . '","' . $destination . '","' . $weight . '","' . $order_id . '","' . $product_description . '" ,"' . $pieces . '","' . $ref_no . '","' . $order_type . '","' . $gst_amount . '","' . $total_charges . '","' . $net_amount . '","2",1,"' . $product_type_id . '","' . $branch_id . '","Parcel Received at office") ';
		
				$next_number = 0;
				$query = mysqli_query($con, $insert_qry);
				if(!$query){
					echo "string" .mysqli_error($con);
				}
				$insert_id = mysqli_insert_id($con);
				echo $insert_id;
					die;
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
					$customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $customer_ids . " ");
					$customer_data = mysqli_fetch_array($customer_query);
					$admin_other_charges   = getconfig('admin_other_charges');
					if (isset($customer_data['is_fuelsurcharge']) && $customer_data['is_fuelsurcharge'] == 1) {
						$fuelsurcharge_percent   = getconfig('fuel_surcharge');
						$customer_fuel_charge = getconfig('customer_fuel_charge');
						if (isset($customer_fuel_charge) && $customer_fuel_charge == 1) {
							$customer_wise_charges_query = mysqli_query($con, "SELECT * FROM customer_wise_charges WHERE charge_name = 'fuel_surcharge' AND customer_id=" . $customer_ids . " ");
							$customer_wise_charges_data = mysqli_fetch_array($customer_wise_charges_query);
							$fuelsurcharge_percent = isset($customer_wise_charges_data['charge_value']) ? $customer_wise_charges_data['charge_value'] : '';
						}
					}
					if (isset($customer_data['is_saletax']) && $customer_data['is_saletax'] == 1) {
						$gst_percent   = getGst($origin, $customer_ids);
					}
					$row_new = $row;
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
					$pft_amount = 0;
					// $pft_amount = ($net_amount / 100 * $gst_percent);
					$net_amount = ($net_amount + $pft_amount);
					mysqli_query($con, "UPDATE orders SET net_amount = '" . $net_amount . "',grand_total_charges = '" . $total_charges . "',special_charges = '" . $special_charges . "', pft_amount = '" . $pft_amount . "', fuel_surcharge = " . $fuel_surcharge . " WHERE id = $insert_id ");

					// End Backend Calculation
					$next_number = 0;
					$custom_track_numbers = getConfig('custom_track_numbers');
					if (isset($custom_track_numbers) && $custom_track_numbers == 1) {
						$next_number = custom_track_numbers($customer_ids);
					
					}
					if (isset($customer_data['is_order_manual']) and $customer_data['is_order_manual'] == 1) {
						$trackNo = $row_new[0];
						// $trackNo = $_POST['track_no'];
					} elseif ($next_number > 0) {
						$trackNo = $next_number;
					} else {
						$trackNo = $insert_id + 6000000;
					}
					$barcode = rand(1000000, 9999999);
					$barcode = substr($barcode, 0, strlen($barcode) - strlen($insert_id));
					$barcode .= $insert_id;
					$barcode_image = getBarCodeImage($trackNo, null, $trackNo);

					mysqli_query($con, "UPDATE orders SET barcode = '" . $trackNo . "', barcode_image = '" . $barcode_image . "', track_no = '" . $trackNo . "' WHERE id = $insert_id");
					mysqli_query($con, "INSERT INTO order_logs(`order_no`,`order_status`,`location`,`created_on`) VALUES ('" . $trackNo . "', 'Parcel Received at office', '" . $origin . "','" . $date . "') ");
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
	// var_dump($);
		$err_response = array();
	if($insert_id == 0){
	$err_response['error'] = 1;
	$err_response['alert_msg'] = 'File Upload Failed';
	}else{
	$err_response['process'] = '100';
	$err_response['data_ids'] = implode(",", $order_ids);
	$_SESSION['bulk_message'] = 'File In Uploaded Successfully';
}
	echo json_encode($err_response);
	exit();
}