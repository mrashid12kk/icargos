<?php
session_start();
include_once "includes/conn.php";

$status_message = "";
if(isset($_REQUEST['submit']) && isset($_REQUEST['track_code'])){
	$track_no = $_REQUEST['track_code'];
	$order_query = mysqli_query($con,"SELECT * FROM orders WHERE track_no ='".$track_no."' ");
	$order_query_result = mysqli_fetch_array($order_query);
	$order_id = $order_query_result['id'];
	$subject = $_REQUEST['subject'];
	$comment = $_REQUEST['message'];
	$customer_id = $_SESSION['customers'];
	$date = date('Y-m-d H:i'); 
	mysqli_query($con,"INSERT INTO order_comments(`order_id`,`track_no`,`customer_id`,`subject`,`order_comment`) VALUES('".$order_id."','".$track_no."','".$customer_id."','".$subject."','".$comment."') ");
	$insert_id = mysqli_insert_id($con);
	require_once "admin/includes/functions.php";
	//send email to admin
	$path = BASE_URL.'admin/order.php?id='.$order_id;
	$message['subject'] = 'Comment Received';
	$message['body'] = "<p>New Comment Added</p>";
	$message['body'] .= "<p><b>Subject:</b> $subject </p>";
	$message['body'] .= "<p><b>Message:</b> $comment </p>"; 
	$message['body'] .= "<p>Click below link to view Order.</p>";
	$message['body'] .= "<a href='$path'>$path</a>";
	$data = array();
	sendEmailToAdmin($data, $message);
	$response = array();
	if($insert_id > 0){
		$response = array(
		'msg' => 'success'
		);
	}else{
		$response = array(
		'msg' => 'error'
	);
	}
	echo json_encode($response);
	exit();
	
}
 ?>