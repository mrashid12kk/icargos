<?php
session_start();
date_default_timezone_set("Asia/Karachi");
require 'includes/conn.php';
$date=date('Y-m-d H:i:s');
$message = '';
	if(isset($_POST['order_ids']) && !empty(json_decode($_POST['order_ids']))){
		$message = '';
			$order_id_data = json_decode($_POST['order_ids']); 
	
	foreach($order_id_data as $order_id){
		$query = mysqli_query($con,"SELECT * FROM orders WHERE id =".$order_id." ");
		$record = mysqli_fetch_array($query);
			
				if($record['destination'] != 'Karachi'){
					$msg = "Shipment Dispatch to ".$record['destination'];
		        mysqli_query($con,"INSERT INTO order_logs(`order_no`,`order_status`,`created_on`) VALUES ('".$record['track_no']."', '".$msg."','".$date."') ");
		        mysqli_query($con,"UPDATE orders SET is_shipped =1, status='dispatch' WHERE id=".$order_id." ");
		         
				}
			
		
	   
		
		
	  }
	  $message = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Order Dispatched Successfully</div>';
	  header('Location: order_received_outstation.php?message='.$message);
	   
	}else{
		header('Location: order_received_outstation.php?message'.$message);
	}	 
?>