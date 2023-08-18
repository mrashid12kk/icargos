<?php
	session_start();
	date_default_timezone_set("Asia/Karachi");
	require 'includes/conn.php';
$message = "";
	if(isset($_POST['order_ids']) && !empty(json_decode($_POST['order_ids']))){
		
		$reason = "";
	$status_log = '';
	$order_status = $_POST['order_status'];
	$delivery_location = "";
	if($order_status == 'pending'){
		if(empty($_POST['other_reason'])){
		$reason = $_POST['pending_reason'];
	     }else{
	     	$reason = $_POST['other_reason'];
	     }
		$status_log = 'Pending due to ('.$reason.')';
	}elseif($order_status == 'returned'){
		if(empty($_POST['other_reason'])){
		$reason = $_POST['returned_reason'];
		}else{
		$reason = $_POST['other_reason'];
	    }
		$status_log = 'Returned due to ('.$reason.')';
	}else{
		$reason_status = "";
		$reason = "";
		if(!empty($_POST['received_by'])){
			$reason = $_POST['received_by'];
			$reason_status = "Received BY (".$_POST['received_by'].")";
		}
		// echo "<pre>"; print_r($_POST); exit();
		$status_log = "Delivered Successfully ".$reason_status;

	}
	$order_id_data = json_decode($_POST['order_ids']); 

	foreach($order_id_data as $order_id){
			$message = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Orders '.$status_log.'</div>';
	$query = mysqli_query($con,"SELECT * FROM orders WHERE id =".$order_id." ");
		$record = mysqli_fetch_array($query);
if($order_status == 'delivered'){
			$delivery_location = $record['destination'];
		}
		
	mysqli_query($con,"UPDATE orders SET status ='".$order_status."', status_reason ='".$reason."', received_by ='".$reason."' WHERE id=".$order_id." ");
	if($order_status == 'delivered'){
		
		mysqli_query($con,"INSERT INTO ledger(`order_no`,`delivery_charges`,`collected_amount`,`customer_id`,`location`,`ledger_type`) VALUES ('".$record['track_no']."','".$record['price']."','".$record['collection_amount']."','".$record['customer_id']."','".$delivery_location."','Order') ");
	}elseif($order_status == 'returned'){
		mysqli_query($con,"INSERT INTO ledger(`order_no`,`delivery_charges`,`customer_id`,`ledger_type`) VALUES('".$record['track_no']."','".$record['price']."','".$record['customer_id']."','Order') "); 
	}
	if($order_status == 'delivered'){
			$delivery_location = $record['destination'];
		}
		 $date=date('Y-m-d H:i:s');
	mysqli_query($con,"INSERT INTO order_logs(`order_no`,`order_status`,`location`,`created_on`) VALUES ('".$record['track_no']."', '".$status_log."', '".$delivery_location."','".$date."') ");
	  }

	  header("Location:".$_SERVER[HTTP_REFERER].'?message='.$message);

	}else{
		
		$message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong>Please select orders.</div>';
		$src = $_SERVER['HTTP_REFERER'].'?message='.$message;

		 header("Location:".$src);
	}
?>