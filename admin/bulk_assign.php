<?php
session_start();
date_default_timezone_set("Asia/Karachi");
require 'includes/conn.php';
$message = '';
if(isset($_POST['order_ids']) && !empty(json_decode($_POST['order_ids'])) && !empty($_POST['active_courier'])){
 $date=date('Y-m-d H:i:s');
		
			$order_id_data = json_decode($_POST['order_ids']); 
	$deliver_driver_id = $_POST['active_courier'];
	foreach($order_id_data as $order_id){
		$query = mysqli_query($con,"SELECT * FROM orders WHERE id =".$order_id." ");
		$record = mysqli_fetch_array($query);
			
				$deliver_driver_id = $_POST['active_courier'];
				mysqli_query($con, "UPDATE orders SET status = 'assigned', status_reason ='', assign_driver =".$deliver_driver_id." WHERE id = $order_id");
				mysqli_query($con,"INSERT INTO order_logs(`order_no`,`order_status`,`location`,`created_on`) VALUES ('".$record['track_no']."', 'Assigned to Rider.','','".$date."') ");
				
		// 		$sms = "";
		// $sms .= "Your shipment, \r\n";
		// $sms .= $record['track_no']." is out for delivery. Courier will reach at your given address soon. Please keep Cash Amount Rs. ".$record['collection_amount']." ready. Thank you! ";
		
		// $http_query = http_build_query([
		//  'username' => 'Genious',
		//  'password' => 'Global1122',
		//  'sender' => 'IT-VISION',
		//  'phone' => $record['rphone'],
		//  'message' => $sms,
		// ]);
		// $url = 'http://login.brandedsms.me/sendsms.php?'.$http_query;
		// // create a new cURL resource
		// $ch = curl_init();
		// // set URL and other appropriate options
		// curl_setopt($ch, CURLOPT_URL, $url);
		// curl_setopt($ch, CURLOPT_HEADER, 0);
		// ob_start();
		// // grab URL and pass it to the browser
		// $response = curl_exec($ch);
		// ob_end_clean();
		// // close cURL resource, and free up system resources
		// curl_close($ch);
		
	  }
	  $message = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>'.getLange('Well_done').'!</strong>'.getLange('orders_is_assigned_successfully').'!</div>';
	  
	     header('Location: dispatch_orders.php?message='.$message);
	}else{
		 header('Location: dispatch_orders.php?message='.$message);
	}	 
?>