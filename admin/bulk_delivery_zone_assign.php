<?php
session_start();
date_default_timezone_set("Asia/Karachi");
require 'includes/conn.php';
$message = '';
// echo "<pre>";
// print_r($_POST);
// die();
  

if(isset($_POST['order_ids']) && !empty($_POST['order_ids']) && !empty($_POST['order_status']) and !isset($_POST['is_for']))
{
 	$date=date('Y-m-d H:i:s');
	$sent = '';
	$order_id_data = explode(',', $_POST['order_ids']); 
	$delivery_zone_id = $_POST['order_status'];

	$error = 0;
	///validate all data first 
	foreach($order_id_data as $order_id)
	{
		if(!empty($order_id))
		{
			$query = mysqli_query($con,"SELECT * FROM orders WHERE track_no =".$order_id." ");

			$record = mysqli_fetch_array($query);

			if (empty($record)) 
			{ 
				$message .= "<p> ".$order_id." ".getLange('no_such_order_found').". </p>";
			 	$error = 1; 
			} else{
				$status_check = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM order_status WHERE sts_id=3 "));
				if ($record['status'] != $status_check['status']) 
				{
					// echo $record['status'];
					// echo "<br>";
					// echo $status_check['status'];
				 	$message .= "<p> ".$order_id." ".getLange('order_not_received_at_office').".</p>";
			 		$error   = 1; 
				}
			}
		}
		
  	} 
  
  	if ($error == 0) 
  	{ 
		foreach($order_id_data as $order_id)
		{
			if(!empty($order_id))
			{
				// $query = mysqli_query($con,"SELECT * FROM orders WHERE track_no =".$order_id." ");
				// $record = mysqli_fetch_array($query);

				// $check_status  = mysqli_query($con,"SELECT * FROM order_status WHERE status ='".$active_status."'   ");
				// // $status_record = mysqli_fetch_array($check_status);		

				$auto_assign_rider = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='rider_vendor_auto_assign' "));

				if ($auto_assign_rider['value'] == 'yes') 
				{
					$rider = mysqli_fetch_array(mysqli_query($con,"SELECT rider FROM delivery_zone WHERE `id`='".$delivery_zone_id."' "));

					if (isset($rider['rider'])) 
					{
						$rider_id = $rider['rider'];
						mysqli_query($con, "UPDATE orders SET delivery_rider ='".$rider_id."' WHERE track_no = $order_id");
					}
					 
				}

				// $check_status  = mysqli_query($con,"SELECT * FROM order_status WHERE status ='".$active_status."'   ");

				$q = mysqli_query($con, "UPDATE orders SET delivery_zone_id ='".$delivery_zone_id."' WHERE track_no = $order_id");
				if($q == true)
				{
					$sent = true; 
				}
				// mysqli_query($con,"INSERT INTO order_logs(`order_no`,`order_status`,`location`,`created_on`) VALUES ('".$record['track_no']."', '".$active_status."','','".$date."') ");
			} 
	  	} 

	  	if($q == true){
		  	$_SESSION['succ_msg'] = getLange('delivery_zone_updated');
	  	}
  	}else{
  		$_SESSION['error_msg'] = $message;
  		$message ='';
  	}
  	 
 	header('Location: assign_delivery_zone.php?message='.$message);
}elseif(!isset($_POST['is_for'])){
 	header('Location: assign_delivery_zone.php?message='.$message);
}	 

 


?>