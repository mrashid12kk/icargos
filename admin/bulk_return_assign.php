<?php
session_start();
date_default_timezone_set("Asia/Karachi");
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
$message = '';
// if(isset($_POST['order_ids']) && !empty($_POST['order_ids']) && !empty($_POST['active_courier'])){
if (isset($_POST['order_ids']) && !empty($_POST['order_ids'])) {

	$date = date('Y-m-d H:i:s');
	$sent = '';
	$order_records = $_POST['order_ids'];
	$return_title = isset($_POST['return_title']) ? $_POST['return_title'] : '';
	$order_status = isset($_POST['order_status']) ? $_POST['order_status'] : '';
	$check_d = mysqli_query($con, "SELECT COUNT(id) as total FROM orders WHERE track_no IN(" . $order_records . ") ");

	// if(mysqli_num_rows($check_d) >0){
	$order_id_data = explode(',', $_POST['order_ids']);
	$deliver_driver_id = isset($_POST['active_courier']) ? $_POST['active_courier'] : 0;
	//////////////////
	$assignment_no = str_pad(rand(0, 999999), 5, "0", STR_PAD_LEFT);
	$barcode_image = getBarCodeImage($assignment_no, null, $assignment_no);
	$branch_id = 1;
	if (isset($_SESSION['branch_id']) && !empty($_SESSION['branch_id'])) {
		$branch_id = $_SESSION['branch_id'];
	} else {
		$branch_id = 1;
	}
	$created_by = isset($_SESSION['users_id']) ? $_SESSION['users_id'] : '';

	mysqli_query($con, "INSERT INTO assignments(`assignment_no`,`assignment_type`,`assignment_title`,`business_ids`,`rider_id`,`branch_id`,`created_by`,`barcode_image`) VALUES('" . $assignment_no . "','Return','" . $return_title . "','','" . $deliver_driver_id . "','" . $branch_id . "','" . $created_by . "','$barcode_image') ");

	/////////////////
	foreach ($order_id_data as $order_id) {
		if (!empty($order_id)) {
			$query = mysqli_query($con, "SELECT * FROM orders WHERE track_no ='" . $order_id . "'");
			$record = mysqli_fetch_array($query);

			$deliver_driver_id = isset($_POST['active_courier']) ? $_POST['active_courier'] : 0;
			$q = mysqli_query($con, "UPDATE orders SET  return_rider ='" . $deliver_driver_id . "',return_assignment_no='" . $assignment_no . "',status='" . $order_status . "' WHERE track_no = '" . $order_id . "'");


			if ($q == true) {
				$sent = true;
			}
			mysqli_query($con, "INSERT INTO order_logs(`order_no`,`order_status`,`location`,`user_id`,`created_on`) VALUES ('" . $record['track_no'] . "', '" . $order_status . "','','" . $created_by . "','" . $date . "') ");
			mysqli_query($con, "UPDATE orders set action_date='" . $date . "' WHERE track_no='" . $record['track_no'] . "'");
		}
	}
	$url = 'return_assignment_sheet.php?assignment_no=' . $assignment_no;
	echo "<script type='text/javascript'>window.open('" . $url . "');</script>";
	echo "<script type='text/javascript'>location.replace('order_processing.php?message=');</script>";
	//header("Location:order_processing.php?message=");
	// header('Location: delivery_run_sheet.php?message='.$message);
	// }else{
	//    	 // header('Location: return_run_sheet.php?message='.$message);
	// }
} else {
	// header('Location: return_run_sheet.php?message='.$message);
}
?>
<script type='text/javascript'></script>