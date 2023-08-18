<?php
date_default_timezone_set("Asia/Karachi");
include_once "../includes/conn.php";
$date=date('Y-m-d H:i:s');
$content = $_GET;
$tracking_no = trim($content['tracking_no']);
$auth_key = trim($content['auth_key']);
$auth_query = mysqli_query($con,"SELECT * FROM customers WHERE auth_key ='".$auth_key."' AND api_status=1 ");
$count = mysqli_num_rows($auth_query);

if($count == 0){
	$error_msg = "Invalid Authentication Key";
	echo json_encode($error_msg); exit();
}else{
	$customer_data = mysqli_fetch_array($auth_query);
}
$track_query = mysqli_query($con,"SELECT * FROM orders WHERE track_no ='".$tracking_no."' AND customer_id=".$customer_data['id']." ");
$track_record = mysqli_fetch_array($track_query);
$count2 = mysqli_num_rows($track_query);
if($count2 == 0){
	$error_msg = "Invalid Tracking Number";
	echo json_encode($error_msg); exit();
}else{
	if($track_record['is_received'] ==1){ 
		$error_msg = "Order is already processed. You're not allowed to cancel this order. Please Contact Administration to solve this problem.";
		echo json_encode($error_msg); exit();
	}else{
		if($track_record['status'] == 'cancelled'){
			$error_msg = "Already cancelled this orders";
		    echo json_encode($error_msg); exit();
		}else{
			mysqli_query($con,"UPDATE orders SET status='cancelled',is_received=2 WHERE track_no='".$tracking_no."' ");
			$log_msg = 'Order is Cancelled';
			mysqli_query($con,"INSERT INTO order_logs(`order_no`,`order_status`,`created_on`) VALUES ('".$tracking_no."', '".$log_msg."','".$date."') ");
			$cancel_msg = array(
				'tracking_no' => $tracking_no,
				'message' => 'Order '.$tracking_no.' cancelled successfully',
			);
			echo json_encode($cancel_msg); exit();
		}
		
	}
}	
?>