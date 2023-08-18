<?php
// session_start();
require 'includes/conn.php';
function getBarCodeImage($text = '', $code = null, $index)
{
	require_once('../includes/BarCode.php');
	$barcode = new BarCode();
	$path = 'assets/barcodes/imagetemp' . $index . '.png';
	$barcode->barcode($path, $text);
	$folder_path = 'assets/barcodes/imagetemp' . $index . '.png';
	return $folder_path;
}
$current_branch_id = isset($_SESSION['branch_id']) ? $_SESSION['branch_id'] : 1;
$message = '';
$user_id = isset($_SESSION['users_id']) ? $_SESSION['users_id'] : '';

if (isset($_POST['order_ids']) && !empty($_POST['order_ids']) && !empty($_POST['active_courier']) ) {
	$route_code = isset($_POST['delivery_zone_number']) ? $_POST['delivery_zone_number'] : '';

	$validZoneQuery = mysqli_query($con, "SELECT * FROM delivery_zone WHERE route_code = '" . $route_code . "' ");
	$routeCodeRes = mysqli_fetch_assoc($validZoneQuery);

	$deliver_zone_id = isset($routeCodeRes['route_code']) ? $routeCodeRes['route_code'] : '';

	$validZone = isset($routeCodeRes['route_code']) && !empty($routeCodeRes['route_code']) ? true : false;
	// if ($validZone) {

		$created_by = isset($_SESSION['users_id']) ? $_SESSION['users_id'] : '';
		$date = date('Y-m-d H:i:s');
		$sent = '';

		$order_records = $_POST['order_ids'];
		$track_records = rtrim($order_records, ',');
		$track_no_array = explode(',', $track_records);
		$order_records = '';
		foreach ($track_no_array as $value) {
			$track_id = "'" . $value . "'";
			$order_records .= $track_id . ',';
		}
		$order_records = rtrim($order_records, ',');

		$check_d = mysqli_query($con, "SELECT COUNT(id) as total FROM orders WHERE track_no IN(" . $order_records . ") ");

		if (mysqli_num_rows($check_d) > 0) {
			$order_id_data = explode(',', $order_records);
			$deliver_driver_id = $_POST['active_courier'];
			$assign_branch = isset($_POST['assign_branch']) ? $_POST['assign_branch'] : 0;
			//////////////////
			$assignment_no = str_pad(rand(0, 999999), 5, "0", STR_PAD_LEFT);
			$check_query = mysqli_query($con, "SELECT assignment_no from assignments where assignment_no=" . $assignment_no);
			$assign_result = mysqli_fetch_array($check_query);
			$assign_check = isset($assign_result['assignment_no'])  ? $assign_result['assignment_no'] : '';
			if (isset($assign_check) && !empty($assign_check)) {
				$assignment_no = str_pad(rand(0, 999999999), 5, "0", STR_PAD_LEFT);
			}
			$status = 'Parcel in Transit to Destination';

			$barcode_image = getBarCodeImage($assignment_no, null, $assignment_no);
			$branch_id = 1;
			if (isset($_SESSION['branch_id']) && !empty($_SESSION['branch_id'])) {
				$branch_id = $_SESSION['branch_id'];
			}

			mysqli_query($con, "INSERT INTO assignments(`assignment_no`,`assignment_type`,`business_ids`,`rider_id`,`assign_branch`,`branch_id`,`barcode_image`,`destination`,`created_by`,`created_on`) VALUES('" . $assignment_no . "','Delivery','','" . $deliver_driver_id . "','" . $branch_id . "'," . $branch_id . ", '$barcode_image','" . $_POST['filter_destination'] . "', $created_by,'" . $date . "')");

			mysqli_query($con, "INSERT INTO assign_orders(`assignment_no`,`assignment_type`,`business_ids`,`rider_id`,`assign_branch`,`branch_id`) VALUES('" . $assignment_no . "','Delivery','','" . $deliver_driver_id . "','" . $assign_branch . "'," . $branch_id . ") ");

			/////////////////
			foreach ($order_id_data as $order_id) {

				if (!empty($order_id)) {

					$query = mysqli_query($con, "SELECT * FROM orders WHERE track_no =" . $order_id . "");
					$record = mysqli_fetch_array($query);

					$original_no = trim($record['rphone']);
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
					$deliver_driver_id = $_POST['active_courier'];
					// echo "UPDATE orders SET  delivery_rider =" . $deliver_driver_id . ",delivery_assignment_no='" . $assignment_no . "', delivery_zone_id= '" . $deliver_zone_id . "', status='Out for Delivery' WHERE track_no = $order_id ";
					// die;
					$q = mysqli_query($con, "UPDATE orders SET  delivery_rider =" . $deliver_driver_id . ",delivery_assignment_no='" . $assignment_no . "', delivery_zone_id= '" . $deliver_zone_id . "', status='Out for Delivery' WHERE track_no = $order_id ");


					$status_log = 'Out for Delivery';
					if ($q == true) {
						$sent = true;

						/**
						 * check if vendor, vendor role id is 3
						 * if not then send sms
						 *
						 */

						$check_vendor = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM users WHERE id =" . $deliver_driver_id . " AND user_role_id != 3  "));

						if (!empty($check_vendor)) {
							$sphone = $record['sphone'];
							$sphone  = preg_replace('/[^0-9]/s', '', $sphone);
							$pos0 = substr($sphone, 0, 1);
							if ($pos0 == '3') {
								$alterno = substr($sphone, 1);
								$alterno = '0' . $sphone;
								$sphone = $alterno;
							}
							$pos = substr($sphone, 0, 2);
							if ($pos == '03') {
								$alterno = substr($sphone, 1);
								$alterno = '92' . $alterno;
								$sphone = $alterno;
							}
							$sent = true;

							// include "includes/sms_helper.php";
							// $sendSms = sendSmsMobileGateWay($order_id, 'Delivered');
							
						} else {
							$status_log = 'Out of Destination City';

							mysqli_query($con, "UPDATE orders SET status='" . $status_log . "' WHERE track_no = " . $order_id . "");
						}
					}

					mysqli_query($con, "INSERT INTO order_logs(`order_no`, `order_status`,`location`,`created_on`,`user_id`) VALUES ('" . $record['track_no'] . "','" . $status_log . "','','" . $date . "','" . $user_id . "') ");
					//SMS

					mysqli_query($con, "UPDATE orders set action_date='" . $date . "' WHERE track_no='" . $record['track_no'] . "'");

					mysqli_query($con, "INSERT INTO assignment_record(`order_num`,`user_id`,`assign_data_time`,`status_submitted`,`assignment_status`,`assignment_type`,`branch_id`,`created_on`) VALUES ('" . $record['track_no'] . "', '" . $deliver_driver_id . "', '" . $date . "', 6, 0 , 2 ," . $current_branch_id . ",'" . $date . "' )");
					// $record_assignment = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM assignment_record WHERE order_num ='" . $record['track_no'] . "' AND assignment_type= 2 "));

					// if (empty($record_assignment)) {

					// 	mysqli_query($con, "INSERT INTO assign_order_record(`order_num`,`user_id`,`assign_data_time`,`status_submitted`,`assignment_status`,`assignment_type`,`branch_id`) VALUES ('" . $record['track_no'] . "', '" . $deliver_driver_id . "', '" . $date . "', 6, 0 , 2 ," . $current_branch_id . " )");



					// 	mysqli_query($con, "INSERT INTO assignment_record(`order_num`,`user_id`,`assign_data_time`,`status_submitted`,`assignment_status`,`assignment_type`,`branch_id`,`created_on`) VALUES ('" . $record['track_no'] . "', '" . $deliver_driver_id . "', '" . $date . "', 6, 0 , 2 ," . $current_branch_id . ",'" . $date . "' )");
					// } else {
					// 	mysqli_query($con, "UPDATE assignment_record SET user_id = '" . $deliver_driver_id . "', status_update_time ='" . $date . "'  WHERE id = '" . $record_assignment['id'] . "'   ");
					// }
				}
			}

			if (isset($_POST['return_to']) && !empty($_POST['return_to'])) {
				$url = 'delivery_assignment_sheet.php?assignment_no=' . $assignment_no;
				echo "<script type='text/javascript'>window.open('" . $url . "');</script>";
				echo "<script type='text/javascript'>location.replace('" . $_POST['return_to'] . "?message=');</script>";
			} else {
				$url = 'delivery_assignment_sheet.php?assignment_no=' . $assignment_no;
				$_SESSION['print_url'] = $url;
				header("Location:delivery_run_sheet.php");
			}
			// header('Location: delivery_run_sheet.php?message='.$message);
		} else {
			header('Location: delivery_run_sheet.php?message=' . $message);
		}
	// } else {

	// 	$_SESSION['error_msg'] = "Invalid Delivery Zone Number";
	// 	if (isset($_POST['return_to']) && !empty($_POST['return_to'])) {
	// 		$url = 'delivery_assignment_sheet.php?assignment_no=' . $assignment_no;
	// 		// echo "<script type='text/javascript'>window.open('" . $url . "');</script>";
	// 		echo "<script type='text/javascript'>location.replace('" . $_POST['return_to'] . "?message=');</script>";
	// 	} else {
	// 		$url = 'delivery_assignment_sheet.php?assignment_no=' . $assignment_no;
	// 		$_SESSION['print_url'] = $url;
	// 		header("Location:delivery_run_sheet.php");
	// 	}
	// }
}
// Starting condition from here
elseif (isset($_POST['order_ids']) && !empty($_POST['order_ids']) && !empty($_POST['assign_branch'])) {


	$date = date('Y-m-d H:i:s');
	$sent = '';

	$order_records = $_POST['order_ids'];
	$track_records = rtrim($order_records, ',');
	$track_no_array = explode(',', $track_records);
	$order_records = '';
	foreach ($track_no_array as $value) {
		$track_id = "'" . $value . "'";
		$order_records .= $track_id . ',';
	}
	$order_records = rtrim($order_records, ',');

	$check_d = mysqli_query($con, "SELECT COUNT(id) as total FROM orders WHERE track_no IN(" . $order_records . ") ");

	if (mysqli_num_rows($check_d) > 0) {
		$branch_id = 1;
		$status = 'Parcel in Transit to Destination';
		if (isset($_SESSION['branch_id']) && !empty($_SESSION['branch_id'])) {
			$branch_id = $_SESSION['branch_id'];
		}
		$order_id_data = explode(',', $order_records);
		$assign_branch = $_POST['assign_branch'];
		//////////////////
		$assignment_no = str_pad(rand(0, 999999), 5, "0", STR_PAD_LEFT);

		mysqli_query($con, "INSERT INTO assignments(`assignment_no`,`assignment_type`,`business_ids`,`rider_id`,`branch_id`,`created_on`) VALUES('" . $assignment_no . "','Delivery','','" . $deliver_driver_id . "'," . $branch_id . ",'" . $date . "') ");

		mysqli_query($con, "INSERT INTO assign_orders(`assignment_no`,`assignment_type`,`business_ids`,`rider_id`,`assign_branch`,`branch_id`) VALUES('" . $assignment_no . "','Delivery','','" . $deliver_driver_id . "','" . $_POST['assign_branch'] . "'," . $branch_id . ") ");

		// echo "INSERT INTO assignments(`assignment_no`,`assignment_type`,`business_ids`,`rider_id`) VALUES('".$assignment_no."','Delivery','','".$deliver_driver_id."') ";
		// die();
		/////////////////
		foreach ($order_id_data as $order_id) {
			if (!empty($order_id)) {
				$query = mysqli_query($con, "SELECT * FROM orders WHERE track_no ='" . $order_id . "'");
				$record = mysqli_fetch_array($query);

				$assign_branch = $_POST['assign_branch'];
				mysqli_query($con, "UPDATE orders SET current_branch =" . $assign_branch . " WHERE track_no = '" . $order_id . "' ");

				$status = 'Parcel in Transit to Destination';


				$checkId = mysqli_query($con, "SELECT * from branch_assignment where order_num ='" . $order_id . "'");
				$prevId = mysqli_fetch_assoc($checkId);
				$previousID = $prevId['order_num'];
				if (isset($previousID) && !empty($previousID)) {
					$query3 = "UPDATE `branch_assignment` SET `branch_completion_status`=1, `assign_data_time`='" . $date . "',`status_update_time`='" . $date . "' WHERE order_num='" . $previousID . "'";
					mysqli_query($con, $query3);

					$query4 = "INSERT INTO `branch_assignment`(`branch_id`, `assign_branch`, `order_num`, `assign_data_time`, `status_update_time`, `status_submitted`, `created_on`) VALUES ( " . $branch_id . " ," . $assign_branch . ",'" . $order_id . "','" . $date . "','" . $date . "','" . $status . "','" . $date . "')";
					mysqli_query($con, $query4);
				} else {

					$query4 = "INSERT INTO `branch_assignment`(`branch_id`, `assign_branch`, `order_num`, `assign_data_time`, `status_update_time`, `status_submitted`, `created_on`) VALUES ( " . $branch_id . " ," . $assign_branch . ",'" . $order_id . "','" . $date . "','" . $date . "','" . $status . "','" . $date . "')";
					mysqli_query($con, $query4);
				}


				$q = mysqli_query($con, "UPDATE orders SET  current_branch =" . $assign_branch . ", status='Parcel in Transit to Destination' WHERE track_no = '" . $order_id . "'");

				$status_log = 'Parcel in Transit to Destination';


				mysqli_query($con, "INSERT INTO order_logs(`order_no`,`branch_id`,`assign_branch`,`order_status`,`location`,`created_on`,`user_id`) VALUES ('" . $record['track_no'] . "', '" . $branch_id . "', '" . $assign_branch . "', '" . $status . "','','" . $date . "','" . $user_id . "') ");
				//SMS

				mysqli_query($con, "UPDATE orders set action_date='" . $date . "' WHERE track_no='" . $record['track_no'] . "'");
			}
		}
		$url = 'delivery_assignment_sheet.php?assignment_no=' . $assignment_no;
		// $_SESSION['print_url'] = $url;
		header("Location:delivery_run_sheet.php");
		// header('Location: delivery_run_sheet.php?message='.$message);
	} else {
		header('Location: delivery_run_sheet.php?message=' . $message);
	}


	// Ending Condition here

} else {
	header('Location: delivery_run_sheet.php?message=' . $message);
}