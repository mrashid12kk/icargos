<?php

session_start();
include_once "includes/conn.php";

if (isset($_POST['order_ids'])) {


	$order_ids = $_POST['order_ids'];
	$order_id_array = explode(',', $order_ids);
	$date  = date('Y-m-d H:i:s');
	$pickupData = mysqli_fetch_object(mysqli_query($con, "SELECT * FROM order_status WHERE sts_id = '1000' "));
	$ready_status = $pickupData->status;
	$error = 0;
	$message = '';
	$return_message = '';
	foreach ($order_id_array as $order_id) {
		if (!empty($order_id)) {

			// record query
			$record = mysqli_fetch_array(mysqli_query($con, "SELECT orders.status,allowed_status FROM orders LEFT JOIN order_status ON orders.status=order_status.status WHERE  orders.id =" . $order_id . "   "));
			$allowed_status = explode(',', $record['allowed_status']);
			$track_no_query = mysqli_query($con, "SELECT track_no from orders WHERE id = " . $order_id);
			$tracking_no = mysqli_fetch_array($track_no_query);
			$track = $tracking_no['track_no']; //Get Tracking no

			$status_record  = mysqli_fetch_array(mysqli_query($con, "SELECT sts_id FROM order_status WHERE status ='" . $ready_status . "'   "));
			$id_check      = $status_record['sts_id'];
			if (!in_array($id_check, $allowed_status)) {
				$return_message .= '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> Order ' . $track . ' can not be assigned as ' . $ready_status . '</div>';
			} else {
				$q = mysqli_query($con, "UPDATE orders SET status ='" . $ready_status . "' WHERE id = $order_id");

				$track_no_query = mysqli_query($con, "SELECT track_no from orders WHERE id = " . $order_id);
				$tracking_no = mysqli_fetch_array($track_no_query);
				$track = $tracking_no['track_no'];

				if (mysqli_affected_rows($con) > 0) {
					$add_to_log = mysqli_query($con, "INSERT INTO order_logs(`order_no`,`order_status`,`location`,`created_on`) VALUES ('" . $track . "', '" . $ready_status . "','','" . $date . "') ");
					mysqli_query($con, "UPDATE orders set action_date='" . $date . "' WHERE track_no='" . $track . "'");
					// $_SESSION['up_message'] = 'Order ' . $track . ' updated successfully.';
				}
				$return_message .= '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Successful!</strong> Order ' . $track . ' updated successfully</div>';
			}
		}
	}
	$_SESSION['return_msg'] = $return_message;
	echo json_encode($return_message);
}
