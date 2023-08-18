<?php
session_start();
require 'includes/conn.php';
require 'includes/API/post_on_api.php';
$message = '';
$date = date('Y-m-d H:i:s');
$user_id = isset($_SESSION['users_id']) ? $_SESSION['users_id'] : '';
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
include "includes/sms_helper.php";
function getBarCodeImage($text = '', $code = null, $index)
{
	require_once('../includes/BarCode.php');
	$barcode = new BarCode();
	$path = 'assets/barcodes/imagetemp' . $index . '.png';
	$barcode->barcode($path, $text);
	$folder_path = 'assets/barcodes/imagetemp' . $index . '.png';
	return $folder_path;
}
$api_err_msg = '';
$api_succ_msg = '';
if (isset($_POST['order_ids']) && !empty($_POST['order_ids']) && !empty($_POST['order_status']) and !isset($_POST['is_for'])) {

	$date = date('Y-m-d H:i:s');
	$sent = '';
	$order_id_data = explode(',', $_POST['order_ids']);
	$active_status = $_POST['order_status'];
	$select_api = isset($_POST['select_api']) ? $_POST['select_api'] : '';
	$api_service = isset($_POST['api_service']) ? $_POST['api_service'] : '';
	
	$deliver_driver_id = isset($_POST['active_courier']) ? $_POST['active_courier'] : '';
	$error = 0;
	$current_branch_id = 1;
	if (isset($_SESSION['branch_id']) and !empty($_SESSION['branch_id'])) {
		$current_branch_id = $_SESSION['branch_id'];
	}
	///validate all data first
	foreach ($order_id_data as $order_id) {
		if (!empty($order_id)) {

			$query = mysqli_query($con, "SELECT orders.status,allowed_status FROM orders LEFT JOIN order_status ON orders.status=order_status.status WHERE  orders.track_no ='$order_id'");
			$record = mysqli_fetch_array($query);
			$allowed_status = explode(',', $record['allowed_status']);
			$check_status  = mysqli_query($con, "SELECT sts_id FROM order_status WHERE status ='" . $active_status . "'   ");
			$status_record = mysqli_fetch_array($check_status);
			$id_check = $status_record['sts_id'];
			if (!in_array($id_check, $allowed_status)) {
				$message .= "<p> Order " . $order_id . " can't be assigned as " . $active_status . " </p>";
				$error = 1;
			}
		}
	}

	if ($error == 0) {
		
		$date = date('Y-m-d H:i:s');
		$order_records = $_POST['order_ids'];
		$track_records = rtrim($order_records, ',');
		$track_no_array = explode(',', $track_records);
		$order_records = '';
		foreach ($track_no_array as $value) {
			$track_id = "'" . $value . "'";
			$order_records .= $track_id . ',';
		}
		$order_records = rtrim($order_records, ',');
		$check_query = "SELECT * FROM orders where track_no IN (" . $order_records . ") ";

		$checkSql = mysqli_query($con, $check_query);
		$not_Assigned_Trackno = '';
		$unAssignedTrackArray = array();
		while ($checkRes = mysqli_fetch_assoc($checkSql)) {

			$check_track_no = isset($checkRes['track_no']) ? $checkRes['track_no'] : '';

			$check_pickup_rider = isset($checkRes['pickup_rider']) ? $checkRes['pickup_rider'] : '';
			$check_assignment_no = isset($checkRes['assignment_no']) ? $checkRes['assignment_no'] : '';
			if (empty($check_assignment_no) && empty($check_pickup_rider)) {
				array_push($unAssignedTrackArray, $check_track_no);
				$track_id = "'" . $check_track_no . "'";
				$not_Assigned_Trackno .= $track_id . ',';
			}
		}
		$not_Assigned_Trackno = rtrim($not_Assigned_Trackno, ',');


		$pickup_driver_id = isset($_POST['active_courier']) ? $_POST['active_courier'] : '';
		$assignment_no = str_pad(rand(0, 999999), 5, "0", STR_PAD_LEFT);
		if (isset($not_Assigned_Trackno) && !empty($not_Assigned_Trackno) && !empty($unAssignedTrackArray)) {
			$created_by = isset($_SESSION['users_id']) ? $_SESSION['users_id'] : '';
			$check_query = mysqli_query($con, "SELECT assignment_no from assignments where assignment_no=" . $assignment_no);
			$assign_result = mysqli_fetch_array($check_query);
			$assign_check = isset($assign_result['assignment_no'])  ? $assign_result['assignment_no'] : '';
			if (isset($assign_check) && !empty($assign_check)) {
				$assignment_no = str_pad(rand(0, 999999999), 5, "0", STR_PAD_LEFT);
			}
			$business_sq = mysqli_query($con, "SELECT GROUP_CONCAT(DISTINCT customer_id SEPARATOR ',') as business_ids FROM orders WHERE track_no IN(" . $not_Assigned_Trackno . ") ");

			$business_ids_q = mysqli_fetch_array($business_sq);
			$business_ids = $business_ids_q['business_ids'];

			$barcode_image = getBarCodeImage($assignment_no, null, $assignment_no);
			$branch_id = 1;
			if (isset($_SESSION['branch_id']) && !empty($_SESSION['branch_id'])) {
				$branch_id = $_SESSION['branch_id'];
			}
			mysqli_query($con, "INSERT INTO assignments(`assignment_no`,`assignment_type`,`business_ids`,`rider_id`,`assign_branch`,`branch_id`,`barcode_image`,`created_by`, `created_on`) VALUES('" . $assignment_no . "','Pickup','" . $business_ids . "','" . $pickup_driver_id . "','" . $branch_id . "'," . $branch_id . ", '$barcode_image', $created_by,'" . $date . "') ");
		}
		
		foreach ($order_id_data as $order_id) {
			if (!empty($order_id)) {
				if( isset($select_api) && !empty($select_api) && getAPIConfig('booking_on')=='order_processing'){
					$response = book_on_api($select_api,$order_id,$api_service);
					if($response['status']=='error'){
						$api_err_msg .=$response['message'];
					}
					if($response['status']=='success'){
						$api_succ_msg .=$response['message'];
					}
				}
				
				$recordsql = "SELECT * FROM orders WHERE track_no ='" . $order_id . "'";

				$record = mysqli_fetch_array(mysqli_query($con, $recordsql));
				$q = mysqli_query($con, "UPDATE orders SET current_branch = '" . $current_branch_id . "' , status ='" . $active_status . "',action_date='" . $date . "' WHERE track_no = '" . $order_id . "'");
				$active_status = $_POST['order_status'];
				if (isset($_POST['reason_enable']) and !empty($_POST['reason_enable'])) {
					$active_status .= ' ( ' . $_POST['reason_enable'] . ' ) ';
					$reason_enable = $_POST['reason_enable'];
					mysqli_query($con, "UPDATE orders SET status_reason ='" . $reason_enable . "' WHERE track_no = '" . $order_id . "' ");
				}
				$status_received_by = $active_status;
				if (isset($_POST['received_by']) and !empty($_POST['received_by']) and $_POST['order_status'] == 'Delivered') {
					$received_by = $_POST['received_by'];
					$status_received_by .= ' ( Received By  ' . $received_by . ' )';
					mysqli_query($con, "UPDATE orders SET received_by ='" . $received_by . "',action_date='" . $date . "' WHERE track_no = '" . $order_id . "' ");
					if (isset($record['booking_type']) && $record['booking_type'] == 3) {
						mysqli_query($con, "UPDATE orders SET payment_status = 'Paid',action_date='" . $date . "' WHERE track_no = '" . $order_id . "'");
					}
					$rider_id = isset($record['delivery_rider']) ? $record['delivery_rider'] : '';
					updateRiderWalletBalance($order_id, $rider_id);
					$sms=addToSmsLog($order_id, 'Delivered');

					mysqli_query($con, "UPDATE assignment_record SET rider_status_done_no = '1', status_update_time ='" . date('Y-m-d H:i:s') . "' WHERE order_num = '" . $order_id . "' AND  assignment_type = 2");
				}
				if (isset($_POST['return_received_by']) and !empty($_POST['return_received_by']) and $_POST['order_status'] == 'Returned to Shipper') {
					$received_by = $_POST['return_received_by'];
					$status_received_by .= ' ( Received By  ' . $received_by . ' )';
					mysqli_query($con, "UPDATE orders SET return_received_by ='" . $received_by . "',action_date='" . $date . "' WHERE track_no = '" . $order_id . "' ");
				}
				if (isset($_POST['assign_branch']) and !empty($_POST['assign_branch'])) {
					$assign_branch = $_POST['assign_branch'];
					mysqli_query($con, "UPDATE orders SET current_branch =" . $assign_branch . ",action_date='" . $date . "' WHERE track_no = '" . $order_id . "' ");
					$branch_id = 1;
					$status = $_POST['order_status'];
					if (isset($_SESSION['branch_id']) && !empty($_SESSION['branch_id'])) {
						$branch_id = $_SESSION['branch_id'];
					}

					mysqli_query($con, "INSERT INTO order_logs(`order_no`,`branch_id`,`assign_branch`,`order_status`,`location`,`created_on`,`user_id`) VALUES ('" . $order_id . "', '" . $branch_id . "', '" . $assign_branch . "', '" . $status . "','','" . $date . "','" . $user_id . "') ");
					mysqli_query($con, "UPDATE orders set action_date='" . $date . "' WHERE track_no='" . $order_id . "'");
				}

				// out for the delivery

				if ($q == true) {
					$sent = true;
					$check_mark_done = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM order_status WHERE status ='" . $_POST['order_status'] . "' "));

					if ($check_mark_done['marked_done'] == 1) {

						$rider_status_done = " rider_status_done_no = '1', ";
						mysqli_query($con, "UPDATE assignment_record SET $rider_status_done status_update_time ='" . $date . "' WHERE order_num ='" . $order_id . "'");
					}
					if ($check_mark_done['branch_completion_status'] == 1) {
						$branch_status_done = " branch_completion_status = '1', ";
						mysqli_query($con, "UPDATE branch_assignment SET $branch_status_done status_update_time ='" . $date . "' WHERE order_num = $order_id  ");
					}
					if ($active_status == 'Parcel Received at Destination') {
						if (isset($_SESSION['branch_id']) and !empty($_SESSION['branch_id'])) {
							$upQ = mysqli_query($con, "UPDATE orders set current_branch = " . $_SESSION['branch_id'] . " WHERE track_no = '" . $order_id . "'");
						} else {
							$upQ = mysqli_query($con, "UPDATE orders set current_branch = 1 WHERE track_no = '" . $order_id . "'");
						}
					}
					if ($active_status == 'Parcel Received at office') {

						if (in_array($order_id, $unAssignedTrackArray)) {
							mysqli_query($con, "UPDATE orders SET status = 'Parcel Received at office' , pickup_rider =" . $pickup_driver_id . ",assignment_no='" . $assignment_no . "' WHERE track_no = " . $order_id);
							mysqli_query($con, "INSERT INTO assignment_record(`order_num`,`user_id`,`assign_data_time`,`status_submitted`,`assignment_status`,`assignment_type`,`created_on`) VALUES ('" . $order_id . "', '" . $pickup_driver_id . "', '" . $date . "', 2, 1 , 1,'" . $date . "') ");
						}

						if (isset($_SESSION['branch_id']) && !empty($_SESSION['branch_id'])) {
							$upQ = mysqli_query($con, "UPDATE orders set current_branch = " . $_SESSION['branch_id'] . " WHERE track_no = '" . $order_id . "'");
						} else {
							$upQ = mysqli_query($con, "UPDATE orders set current_branch = 1 WHERE track_no = '" . $order_id . "'");
						}
					}
					if ($active_status == 'Picked up') {
						if (in_array($order_id, $unAssignedTrackArray)) {
							mysqli_query($con, "UPDATE orders SET status = 'Picked up' , pickup_rider =" . $pickup_driver_id . ",assignment_no='" . $assignment_no . "' WHERE track_no = " . $order_id);
							mysqli_query($con, "INSERT INTO assignment_record(`order_num`,`user_id`,`assign_data_time`,`status_submitted`,`assignment_status`,`assignment_type`,`created_on`) VALUES ('" . $order_id . "', '" . $pickup_driver_id . "', '" . $date . "', 2, 1 , 1,'" . $date . "') ");
						}
					}


					if ($active_status == 'Out for Delivery') {

						$check_vendor = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM users WHERE id =" . $deliver_driver_id . " AND user_role_id != 3  "));

						if (!empty($check_vendor)) {
						} else {
							$status_log = 'Out of Destination City';

							mysqli_query($con, "UPDATE orders SET status='" . $status_log . "',action_date='" . $date . "' WHERE track_no = '" . $order_id . "'");
						}
						$sms = addToSmsLog($order_id, 'Status Update');
						// mysqli_query($con, "INSERT INTO order_logs(`order_no`,`order_status`,`location`,`created_on`,`user_id`) VALUES ('" . $record['track_no'] . "', '" . $status_log . "','','" . $date . "','" . $user_id . "') ");
						mysqli_query($con, "UPDATE orders set action_date='" . $date . "' WHERE track_no='" . $record['track_no'] . "'");
						$record_assignment = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM assignment_record WHERE order_num ='" . $record['track_no'] . "' AND assignment_type= 2 "));
						if (empty($record_assignment)) {

							mysqli_query($con, "INSERT INTO assignment_record(`order_num`,`user_id`,`assign_data_time`,`status_submitted`,`assignment_status`,`assignment_type`,`assign_branch`,`branch_id`) VALUES ('" . $record['track_no'] . "', '" . $deliver_driver_id . "', '" . $date . "', 6, 0 , 2 ," . $current_branch_id . "," . $current_branch_id . " )");
						}
					}
				}
				
				// include_once "email/sendEmail/status_update.php";
				// email_status_update($order_id);
				$orderLogSql = "INSERT INTO order_logs(`order_no`,`order_status`,`location`,`created_on`,`user_id`) VALUES ('" . $order_id . "', '" . $status_received_by . "','$current_branch_city','" . $date . "','" . $user_id . "') ";

				mysqli_query($con, $orderLogSql);
				mysqli_query($con, "UPDATE orders set action_date='" . $date . "' WHERE track_no='" . $record['track_no'] . "'");

				if($active_status=='Delivered'){
					$sms=addToSmsLog($order_id, 'Delivered');
				}else{
					$sms = addToSmsLog($order_id, 'Status Update');
				}
			}
		}

		if ($q == true) {
			$_SESSION['succ_msg'] = 'Status Updated';
		}
	} else {
		$_SESSION['error_msg'] = $message;
		$message = '';
	}
	$_SESSION['succ_msg_for_api'] = $api_succ_msg;
	$_SESSION['err_msg_for_api'] = $api_err_msg;
	if (isset($_SESSION['error_msg']) and !empty($_SESSION['error_msg'])) {
		$_SESSION['old_orders_list'] = $_POST['order_ids'];
	}

	header('Location: order_processing.php?message=' . $message);
} elseif (!isset($_POST['is_for'])) {
	header('Location: order_processing.php?message=' . $message);
}

if (isset($_POST['status_value'])) {
	$_POST['order_status'] = $_POST['status_value'];
}


if (isset($_POST['order_ids']) && !empty($_POST['order_ids']) && !empty($_POST['order_status']) and isset($_POST['is_for'])) {

	$date = date('Y-m-d H:i:s');
	$sent = '';
	$order_id_data = explode(',', $_POST['order_ids']);
	$active_status = $_POST['order_status'];
	$rider_id = $_SESSION['users_id'];
	$rider_name_q =  mysqli_fetch_array(mysqli_query($con, "SELECT Name FROM users WHERE id ='" . $rider_id . "' "));
	$rider_name = $rider_name_q['Name'];
	$status_record  = mysqli_fetch_array(mysqli_query($con, "SELECT sts_id,marked_done FROM order_status WHERE status ='" . $active_status . "'"));
	$id_check   = $status_record['sts_id'];
	$error = 0;
	foreach ($order_id_data as $order_id) {
		if (!empty($order_id)) {

			$order_pickup = mysqli_fetch_array(mysqli_query($con, "SELECT assignment_record.*,order_status.status as status_name FROM assignment_record LEFT JOIN order_status ON assignment_record.assignment_status=order_status.status WHERE  assignment_record.order_num ='" . $order_id . "'"));
			if (!empty($order_pickup)) {
				$query = mysqli_query($con, "SELECT orders.status,allowed_status FROM orders LEFT JOIN order_status ON orders.status=order_status.status WHERE  orders.track_no ='" . $order_id . "'");
				$record = mysqli_fetch_array($query);
				if (!empty($record)) {
					$allowed_status = explode(',', $record['allowed_status']);

					if (!in_array($id_check, $allowed_status)) {
						$message .= "<p> Order " . $order_id . " can't be assigned as " . $active_status . " </p>";
						$error = 1;
					}
				} else {
					$message .= "<p> " . $order_id . " no such order found. </p>";
					$error = 1;
				}
			}
		}
	}

	$can_be_marked_done = 1;
	if ($error == 0) {

		if ($status_record['marked_done'] == 1) {
			$can_be_marked_done = 2;
		}
		foreach ($order_id_data as $order_id) {
			if (!empty($order_id)) {

				$query = '';
				if (isset($_POST['is_for']) and $_POST['is_for'] == 'pickup_rid') {
					$query = mysqli_query($con, "SELECT * FROM orders WHERE track_no ='" . $order_id . "' and pickup_rider=" . $rider_id);
				} else if (isset($_POST['is_for']) and $_POST['is_for'] == 'delivery_rid') {
					$query = mysqli_query($con, "SELECT * FROM orders WHERE track_no ='" . $order_id . "' and delivery_rider=" . $rider_id);
				}
				$record = mysqli_fetch_array($query);
				if (!empty($record)) {

					$user_id = $_SESSION['users_id'];
					if ($_POST['is_for'] == 'delivery_rid') {
						$assignment_no = $record['delivery_assignment_no'];
					}
					if ($_POST['is_for'] == 'pickup_rid') {
						$assignment_no = $record['assignment_no'];
					}
					$check_rider = mysqli_query($con, "SELECT * FROM assignments WHERE rider_id = " . $user_id . "  and assignment_no='" . $assignment_no . "'");

					if ($check_rider->num_rows > 0) {
						$q = mysqli_query($con, "UPDATE orders SET status ='" . $active_status . "'  WHERE track_no = '" . $order_id . "'");
						$check_for = '';
						///// send Email
						
						
						$sms = addToSmsLog($order_id, 'Status Update');
						if (isset($_POST['is_for']) and $_POST['is_for'] == 'delivery_rid') {
							$check_for = ' AND  assignment_type = 2 ';
						} else if (isset($_POST['is_for']) and $_POST['is_for'] == 'pickup_rid') {
							$check_for = ' AND  assignment_type = 1 ';
						}
						$rider_status_done = '';
						if ($can_be_marked_done == 2) {
							$rider_status_done = " rider_status_done_no = '1', ";
						}
						mysqli_query($con, "UPDATE assignment_record SET $rider_status_done status_update_time ='" . $date . "' WHERE order_num = '" . $order_id . "' $check_for");

						$active_status = $_POST['order_status'];
						if (isset($_POST['reason_enable']) and !empty($_POST['reason_enable'])) {
							$active_status .= ' ( ' . $_POST['reason_enable'] . ' ) ';
							$reason_enable = $_POST['reason_enable'];
							mysqli_query($con, "UPDATE orders SET status_reason ='" . $reason_enable . "' WHERE track_no = '" . $order_id . "' ");
						}
						if (isset($_POST['assign_branch']) and !empty($_POST['assign_branch'])) {
							$status_name = $_POST['order_status'];
							mysqli_query($con, "UPDATE orders SET current_branch = " . $_POST['assign_branch'] . ", status='" . $status_name . "' WHERE track_no = '" . $order_id . "' ");
						}
						$status_received_by = $active_status;

						if (isset($_POST['received_by']) and !empty($_POST['received_by'])) {
							$status_received_by .= ' ( Received By ' . $_POST['received_by'] . ' ) ';
							$reason_enable = $_POST['received_by'];
							mysqli_query($con, "UPDATE orders SET received_by ='" . $reason_enable . "' WHERE track_no = '" . $order_id . "' ");
						}
						if (isset($_FILES["order_signature"]["name"]) and !empty($_FILES["order_signature"]["name"])) {

							if (!file_exists("images/order_signature/" . $order_id . "/")) {
								mkdir("images/order_signature/" . $order_id . "/");
							}

							$target_dir = "images/order_signature/$order_id/";

							$target_file = $target_dir . uniqid() . basename($_FILES["order_signature"]["name"]);

							$extension = pathinfo($target_file, PATHINFO_EXTENSION);

							if ($extension == 'jpg' || $extension == 'png' || $extension == 'jpeg' || $extension == 'JPG' || $extension == 'PNG' || $extension == 'JOEG') {

								if (move_uploaded_file($_FILES["order_signature"]["tmp_name"], $target_file)) {

									mysqli_query($con, "UPDATE orders SET order_signature='" . $target_file . "' WHERE `track_no`='" . $order_id . "' ");
								}
							}
						}
						if ($q == true) {
							$sent = true;
							if ($active_status == 'Parcel Received at office') {
								$sms = addToSmsLog($order_id, 'Status Update');
							}
							if ($active_status == "Delivered") {

								updateRiderWalletBalance($order_id, $rider_id);
								$sms = addToSmsLog($order_id, 'Delivered');

								if (isset($record['booking_type']) && $record['booking_type'] == 3) {

									mysqli_query($con, "UPDATE orders SET payment_status = 'Paid' WHERE track_no = '" . $order_id . "'");
								}
							}
						}
					}
				}
				if($active_status=='Delivered'){
					$sms=addToSmsLog($order_id, 'Delivered');
				}else{
					$sms = addToSmsLog($order_id, 'Status Update');
				}
				mysqli_query($con, "INSERT INTO order_logs(`order_no`,`order_status`,`created_on`) VALUES ('" .$order_id . "', '" . $status_received_by . "','" . $date . "') ");
				mysqli_query($con, "UPDATE orders set action_date='" . $date . "' WHERE track_no='" .$order_id . "'");
			}
		}

		if ($q == true) {
			$_SESSION['succ_msg'] = 'Status Updated';
		}
	} else {

		if (empty($message)) {
			$message = "No order found for update.";
		}

		$_SESSION['error_msg'] = $message;
		$message = '';
	}
	if (isset($_SESSION['error_msg']) and !empty($_SESSION['error_msg'])) {
		$_SESSION['old_orders_list'] = $_POST['order_ids'];
	}



	if (isset($_POST['is_for']) and $_POST['is_for'] == 'pickup_rid') {
		header('Location: pickups_order_processing.php?message=' . $message);
	} else if (isset($_POST['is_for']) and $_POST['is_for'] == 'delivery_rid') {
		header('Location: deliveries_order_processing.php?message=' . $message);
	}
}