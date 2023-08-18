<?php
session_start();
require 'includes/conn.php';
require 'includes/functions.php';

$message = '';
$date=date('Y-m-d H:i:s');
$delivery_location = '';
$order_id = null;
if(isset($_GET['stat']) && !empty($_GET['order'])){
	$order_id = $_GET['order'];
	if($_GET['stat'] == 'received'){
	$log_msg = 'Arrived at DM Courier Fulfillment Facility';
   } else if($_GET['stat'] == 'booked') {
	$log_msg = 'Order Restored';
   }else{
   	$log_msg = 'Item Not Received';
   }
	$query = mysqli_query($con,"SELECT * FROM orders WHERE id =".$order_id." ");
		$record = mysqli_fetch_array($query);
		$user_id = isset($_SESSION['users_id']) ? $_SESSION['users_id'] : '';
		mysqli_query($con,"INSERT INTO order_logs(`order_no`,`order_status`,`created_on`,`user_id`) VALUES ('".$record['track_no']."', '".$log_msg."','".$date."','".$user_id."') ");
		if($_GET['stat'] == 'received'){
		mysqli_query($con,"UPDATE orders SET status='received',is_received =1 WHERE id=".$order_id." ");
		
	
	    }else if($_GET['stat'] == 'booked') {
	    	mysqli_query($con,"UPDATE orders SET status='booked', is_shipped=0, is_received=0 WHERE id=".$order_id." ");
	    }else{
	    	mysqli_query($con,"UPDATE orders SET status='cancelled', is_received =2 WHERE id=".$order_id." ");
	    }
	    $message = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> '.$log_msg.'</div>';
		
}

if(isset($_POST['update_sts']) )
			{
				if($_POST['update_status']== 'Delivered'){
					$date=date('Y-m-d H:i:s');
				$order_id= $_POST['order_id'];
				$query = mysqli_query($con,"SELECT track_no FROM orders WHERE id =".$order_id." ");
			$record = mysqli_fetch_array($query);
			$sts = $_POST['update_status'];
			$received_by = $_POST['received_by'];
			$track_no = $record['track_no'];
			// echo "UPDATE orders SET status ='".$sts."' WHERE id='".$order_id."'  "; exit();				
			mysqli_query($con,"UPDATE orders SET status ='".$sts."',received_by ='".$received_by."' WHERE id='".$order_id."'  ");
					//// Send Email
    				include_once 'email/sendEmail/status_update.php';
                         email_delivered($track_no);
                         
			if($sts == 'Parcel Received at office'){
				$original_no = trim($record['rphone']);
						$original_no  = preg_replace('/[^0-9]/s','',$original_no);
						$pos0 = substr($original_no, 0,1);
						if($pos0 == '3'){
							$alterno=substr($original_no,1);
							$alterno = '0'.$original_no;
							$original_no = $alterno;
						}
						$pos = substr($original_no, 0,2);
						if($pos == '03'){
							$alterno=substr($original_no,1);
							$alterno = '92'.$alterno;
							$original_no = $alterno;
						}
						
					}
					if (isset($_POST['status_reason']) and !empty($_POST['status_reason'])) 
				{
					$active_status .= ' ( '.$_POST['status_reason'].' ) ';
					$status_reason = $_POST['status_reason'];
					
					$sts .= ' ( '.$_POST['status_reason'].' ) ';
					mysqli_query($con, "UPDATE orders SET status_reason ='".$status_reason."' WHERE id = '".$order_id."' ");
				}
				$user_id = isset($_SESSION['users_id']) ? $_SESSION['users_id'] : '';
			mysqli_query($con,"INSERT INTO order_logs(order_no,order_status,created_on,user_id) VALUES('".$track_no."','".$sts."','".$date."','".$user_id."') ");
			header("Location:".$_SERVER['HTTP_REFERER']); 
			exit();
			
				}
				else{
				$date=date('Y-m-d H:i:s');
				$order_id= $_POST['order_id'];
				$query = mysqli_query($con,"SELECT track_no FROM orders WHERE id =".$order_id." ");
			$record = mysqli_fetch_array($query);
			$sts = $_POST['update_status'];
			$track_no = $record['track_no'];
				mysqli_query($con,"UPDATE orders SET status ='".$sts."' WHERE id='".$order_id."'  ");
				////send email
					    include_once 'email/sendEmail/status_update.php';
                         email_status_update($track_no);
                         
			if($sts == 'Parcel Received at office'){
				$original_no = trim($record['rphone']);
						$original_no  = preg_replace('/[^0-9]/s','',$original_no);
						$pos0 = substr($original_no, 0,1);
						if($pos0 == '3'){
							$alterno=substr($original_no,1);
							$alterno = '0'.$original_no;
							$original_no = $alterno;
						}
						$pos = substr($original_no, 0,2);
						if($pos == '03'){
							$alterno=substr($original_no,1);
							$alterno = '92'.$alterno;
							$original_no = $alterno;
						}
						
					}
				if (isset($_POST['status_reason']) and !empty($_POST['status_reason'])) 
				{
					$active_status .= ' ( '.$_POST['status_reason'].' ) ';
					$status_reason = $_POST['status_reason'];
					
					$sts .= ' ( '.$_POST['status_reason'].' ) ';
					mysqli_query($con, "UPDATE orders SET status_reason ='".$status_reason."' WHERE id = '".$order_id."' ");
				}
				$user_id = isset($_SESSION['users_id']) ? $_SESSION['users_id'] : '';
			mysqli_query($con,"INSERT INTO order_logs(order_no,order_status,created_on,user_id) VALUES('".$track_no."','".$sts."','".$date."','".$user_id."') ");
			header("Location:".$_SERVER['HTTP_REFERER']); 
			exit();
		}
		}

		if(isset($_POST['addcomment']) )
			{
				$date=date('Y-m-d H:i:s');
				$order_id= $_POST['order_id'];
				$query = mysqli_query($con,"SELECT track_no,customer_id FROM orders WHERE id =".$order_id." ");
		$record = mysqli_fetch_array($query);
			$comment = $_POST['comment'];
			$track_no = $record['track_no'];
			$customer_id = $record['customer_id'];
			
			mysqli_query($con,"INSERT INTO order_comments(order_id,track_no,customer_id,subject,order_comment,comment_by,created_on) VALUES('".$order_id."','".$track_no."','".$customer_id."','','".$comment."','Admin','".$date."') ");
			header("Location:".$_SERVER['HTTP_REFERER']); 
			exit();

		}
if(isset($_GET['shipped']) && !empty($_GET['order'])){
	$order_id = $_GET['order'];
$date=date('Y-m-d H:i:s');
	$query = mysqli_query($con,"SELECT * FROM orders WHERE id =".$order_id." ");
		$record = mysqli_fetch_array($query);
		$msg = "Shipment Dispatch to ".$record['destination'];
		$user_id = isset($_SESSION['users_id']) ? $_SESSION['users_id'] : '';
		mysqli_query($con,"INSERT INTO order_logs(`order_no`,`order_status`,`created_on`,`user_id`) VALUES ('".$record['track_no']."', '".$msg."','".$date."','".$user_id."') ");
		mysqli_query($con,"UPDATE orders SET is_shipped =1, status='dispatch' WHERE id=".$order_id." ");
		$message = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Order Dispatched Successfully</div>';
		
}
if(isset($_POST['update_status'])){
	echo "<pre>";
	print_r ($_POST);
	echo "</pre>";
	die;
	$order_id = $_POST['order_id'];
	$reason = "";
	$status_log = '';
	$order_status = $_POST['order_status'];
	if($order_status == 'New Booked'){
		if(empty($_POST['other_reason'])){
		$reason = $_POST['pending_reason'];
	     }else{
	     	$reason = $_POST['other_reason'];
	     }
		$status_log = 'New Booked due to ('.$reason.')';
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
		if(!empty($_POST['received_by']) ){
			$reason = $_POST['received_by'];
			$reason_status = "Received BY (".$_POST['received_by'].")";
		}
		$status_log = "Delivered Successfully ".$reason_status;

	}
$current_date = date('Y-m-d');
	$message = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Order '.$status_log.'</div>';
	$query = mysqli_query($con,"SELECT * FROM orders WHERE id =".$order_id." ");
		$record = mysqli_fetch_array($query);

		if($order_status == 'delivered'){
			mysqli_query($con,"UPDATE orders SET status ='".$order_status."', status_reason ='".$reason."', received_by ='".$reason."', `action_date` ='".$current_date."' WHERE id=".$order_id." ");
		}else{
			mysqli_query($con,"UPDATE orders SET status ='".$order_status."', status_reason ='".$reason."', `action_date` ='".$current_date."' WHERE id=".$order_id." ");
		}
	
	if($order_status == 'New Booked'){
		mysqli_query($con,"UPDATE orders SET is_pending =1 WHERE id=".$order_id." ");
	}
	if($order_status == 'delivered'){
		
		mysqli_query($con,"INSERT INTO ledger(`order_no`,`delivery_charges`,`collected_amount`,`customer_id`,`location`,`ledger_type`) VALUES ('".$record['track_no']."','".$record['price']."', '".$record['collection_amount']."','".$record['customer_id']."','".$delivery_location."','Order') ");
	
	}elseif($order_status == 'returned'){
		mysqli_query($con,"INSERT INTO ledger(`order_no`,`delivery_charges`,`customer_id`,`ledger_type`) VALUES('".$record['track_no']."','".$record['price']."','".$record['customer_id']."','Order') "); 
	}
	if($order_status == 'delivered'){
			$delivery_location = $record['destination'];
		}
		$date=date('Y-m-d H:i:s');
		$user_id = isset($_SESSION['users_id']) ? $_SESSION['users_id'] : '';
	mysqli_query($con,"INSERT INTO order_logs(`order_no`,`order_status`,`location`,`created_on`,`user_id`) VALUES ('".$record['track_no']."', '".$status_log."', '".$delivery_location."','".$date."','".$user_id."') ");
}
if(isset($_POST['action']) && $_POST['action'] == 'getOrderByBarCode' && isset($_POST['barcode'])) {
	$barcode = $_POST['barcode'];
	$order = mysqli_query($con, "SELECT * FROM orders WHERE barcode = '".$barcode."'");
	$order =($order) ? mysqli_fetch_object($order) : false;
	if($order) {
		echo json_encode($order);
	}
	exit();
}
if(isset($_POST['amount_collected_submit'])) {
	$order_id = $_POST['id'];
	$is_amount_collected = isset($_REQUEST['is_amount_collected']) ? '1' : '0';
	$flag = mysqli_query($con, "UPDATE orders SET is_amount_collected = $is_amount_collected WHERE id = ".$order_id);
	if($flag) {
		$message = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button>Order Updated successfully!</div>';
	} else {
		$message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button>Unable to perform actin, please try later</div>';
	}
}
if(isset($_REQUEST['id']) && isset($_REQUEST['status'])) {
	$order_id = (int)$_REQUEST['id'];
	$status = $_REQUEST['status'];
	if(isset($_REQUEST['driver'])) {
		$driver_id = (int)$_REQUEST['driver'];
		if($status == 'accepted') {
			$flag = mysqli_query($con, "UPDATE deliver SET status = 'assigned' WHERE order_id = $order_id AND driver_id = $driver_id");
			$flag = mysqli_query($con, "UPDATE orders SET status = 'assigned' WHERE id = $order_id") or die(mysqli_error($con));
		} else {
			if(isset($_REQUEST['deliver'])){
				if($status == 'postponed'){
					$postponed_date=$_REQUEST['date'];
					$flag = mysqli_query($con, "update users,orders,deliver set orders.status= '$status',orders.delivery_date='$postponed_date',users.status='complete',deliver.status= '$status' where orders.id=$order_id and users.id=$driver_id and deliver.order_id=$order_id and deliver.deliver_driver_id=$driver_id") or die(mysqli_error($con));	
				}
				else{
					$driver_signature = isset($_REQUEST['driver_signature']) ? $_REQUEST['driver_signature'] : '';
					$receiver_signature = isset($_REQUEST['receiver_signature']) ? $_REQUEST['receiver_signature'] : '';
					$is_amount_collected = isset($_REQUEST['is_amount_collected']) ? '1' : '0';
					$is_charges_received = isset($_REQUEST['is_charges_received']) ? '1' : '0';
					$reason = isset($_REQUEST['reason']) ? $_REQUEST['reason'] : '';
					$is_returned = ($status == 'return') ? '1' : '0';
					if($status == 'return') {
						$flag = mysqli_query($con, "update users,orders,deliver set orders.status= 'returned',users.status='complete',deliver.status= 'returned', orders.driver_signature = '$driver_signature', orders.receiver_signature = '$receiver_signature', orders.is_amount_collected = $is_amount_collected, orders.is_charges_received = $is_charges_received, orders.reason = '$reason', orders.is_returned = $is_returned where orders.id=$order_id and users.id=$driver_id and deliver.order_id=$order_id and deliver.deliver_driver_id=$driver_id") or die(mysqli_error($con));
					} else {
						$flag = mysqli_query($con, "update users,orders,deliver set orders.status= '$status',users.status='complete',deliver.status= '$status', orders.driver_signature = '$driver_signature', orders.receiver_signature = '$receiver_signature', orders.is_amount_collected = $is_amount_collected, orders.is_charges_received = $is_charges_received, orders.reason = '$reason', orders.is_returned = $is_returned where orders.id=$order_id and users.id=$driver_id and deliver.order_id=$order_id and deliver.deliver_driver_id=$driver_id") or die(mysqli_error($con));	
					}
					if($flag && $status == 'delivered') {
						$order = mysqli_query($con, "SELECT * FROM orders WHERE id = ".$order_id);
						$order = ($order) ? mysqli_fetch_object($order) : null;
						if(isset($order->sphone)) {
							require_once '../includes/sms_helper.php';
							$message = 'Dear '.$order->sname.', Your order no. '.$order->track_no.' already delivered to '.$order->rname.' Thank you for using Snap Courier Services';
							$message .= '. www.snapcourierservices.com';
							send_sms($order->sphone, $message);
						}
						if(isset($order->semail) && $order->semail != '') {
							sendEmail(array('email' => $order->semail), array(
								'subject' => 'Package is Delivered',
								'body' => $message
							));
						}
					} else if($flag && $status == 'delayed') {
						$sender_reason = $_REQUEST['sender_delayed_reason'];
						$receiver_reason = $_REQUEST['receiver_delayed_reason'];
						$order = mysqli_query($con, "SELECT * FROM orders WHERE id = ".$order_id);
						$order = ($order) ? mysqli_fetch_object($order) : null;
						if(isset($order->sphone)) {
							// require_once '../includes/sms_helper.php';
							// $message = 'Dear '.$order->sname.', Your package have been delayed';
							// if($sender_reason != '')
							// 	$message .= ' due to following reason: '.$sender_reason;
							// $message .= '. Tracking Number is '.$order->track_no;
							// $message .= '. www.snapcourierservices.com';
							// send_sms($order->sphone, $message);
						}
						if(isset($order->rphone)) {
							// require_once '../includes/sms_helper.php';
							// $message = 'Dear '.$order->rname.', Your package have been delayed';
							// if($receiver_reason != '')
							// 	$message .= ' due to following reason: '.$receiver_reason;
							// $message .= '. Tracking Number is '.$order->track_no;
							// $message .= '. www.snapcourierservices.com';
							// send_sms($order->rphone, $message);
						}
						mysqli_query($con, "UPDATE orders SET sender_delayed_reason = '$sender_reason', receiver_delayed_reason = '$receiver_reason' WHERE id =$order_id");
					}
				}
			}
			else{
				$reason = isset($_REQUEST['reason']) ? $_REQUEST['reason'] : '';
				$flag = mysqli_query($con, "update users,orders,deliver set orders.status= '$status',users.status='complete',deliver.status= '$status', orders.reason = '$reason' where orders.id=$order_id and users.id=$driver_id and deliver.order_id=$order_id and deliver.driver_id=$driver_id") or die(mysqli_error($con));	
				if($flag && $status == 'in process') {
					$order = mysqli_query($con, "SELECT * FROM orders WHERE id = ".$order_id);
					$order = ($order) ? mysqli_fetch_object($order) : null;
					if(isset($order->sphone)) {
						// require_once '../includes/sms_helper.php';
						// $message = 'Dear '.$order->sname.', Your package Pickup has been processed and is on the way. Tracking Number is '.$order->track_no;
						// $message .= '. www.snapcourierservices.com';
						// send_sms($order->sphone, $message);
					}
					if(isset($order->rphone)) {
						require_once '../includes/sms_helper.php';
						$message = 'Dear '.$order->rname.', We have received your shipment with tracking no '.$order->track_no.' from '.$order->sname.' and we will deliver as soon as possible';
						$message .= '. www.snapcourierservices.com';
						send_sms($order->rphone, $message);
					}
					if(isset($order->remail) && $order->remail != '') {
						sendEmail(array('email' => $order->remail), array(
							'subject' => 'Package is on the Way',
							'body' => $message
						));
					}
				}
			}
		}
		if($driver_id==""){
			$flag = mysqli_query($con, "update orders set orders.status= '$status' where orders.id=$order_id ") or die(mysqli_error($con));	
		}
		if($status != 'canceled' && isset($_REQUEST['amount'])) {
			$amount = (float)$_REQUEST['amount'];
			$flag = mysqli_query($con, "UPDATE orders SET payment_amount = payment_amount+$amount WHERE id = $order_id");
		}
		if($flag) {
			$message = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you have successfully mark order as '.$status.' .</div>';
		} else {
			$message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not mark the order as '.$status.'.</div>';
		}
	}
	if(isset($_GET['removeAssignment'])) {
		$flag = mysqli_query($con, "UPDATE orders SET status = '$status' WHERE id = $order_id");
		$flag = mysqli_query($con, "DELETE FROM deliver WHERE order_id = $order_id");
		if($flag) {
			$message = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you have successfully remove assigned driver.</div>';
		} else {
			$message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not remove assigned driver.</div>';
		}
		if(isset($_GET['driver_message'])) {
			$driverMessage = $_GET['driver_message'];
			$flag = mysqli_query($con, "UPDATE orders SET driver_message = '$driverMessage' WHERE id = $order_id");
			if($flag) {
				$message = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you have successfully reject the order.</div>';
			} else {
				$message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not reject the order.</div>';
			}
		}
	}
} else if(isset($_GET['id']) && isset($_GET['invoice_status'])) {
	$order_id = (int)$_GET['id'];
	$status = $_GET['invoice_status'];
	if(mysqli_query($con, "UPDATE orders SET invoice_status = '".$status."' WHERE id = ".$order_id)) {
		$message = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong>Invoice successfully paid.</div>';
	} else {
		$message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong>Unable to update the status.</div>';
	}
} else if(isset($_POST['verify_order'])) {
	$order_id=mysqli_real_escape_string($con,$_POST['id']);
	$deliver_id=mysqli_real_escape_string($con,$_POST['deliver_id']);
	$status=mysqli_real_escape_string($con,$_POST['status']);
	$sendData = array();
	if(isset($_FILES['receipt'])) {
		$file = $_FILES['receipt'];
		$uploadPath = 'assets/uploads/'.time().'_'.$file['name'];
		if(move_uploaded_file($file['tmp_name'], $uploadPath))
			$sendData['attachment'] = $uploadPath;
	}
	if($status=='delivered'){
	$flag = mysqli_query($con,"update orders,deliver set orders.status='completed',deliver.status='completed' where orders.id=$order_id and deliver.id=$deliver_id") or die(mysqli_error($con));
	}
	else{
		$flag = mysqli_query($con,"update orders set orders.status='$status(verified)' where orders.id=$order_id") or die(mysqli_error($con));
	}
	if($flag){
		// Send a notification to client
		$query = mysqli_query($con, "SELECT * FROM orders WHERE id = $order_id");
		$data = mysqli_fetch_array($query);
		$data['email'] = $data['semail'];
		$data['phone'] = $data['sphone'];
		$data['name'] = $data['sname'];
		if($data['email'] == '' && $data['customer_id'] > 0) {
			$customerID = $data['customer_id'];
			$query1 = mysqli_query($con, "SELECT * FROM customers WHERE id = $customerID");
			$customer = mysqli_fetch_array($query1);
			$data['email'] = $customer['email'];
			$data['name'] = $customer['bname'];
		}
		$sendData['subject'] = 'Order Delivery';
		$sendData['body'] = '<p>Order has been delivered successfully at '.$data['receiver_address'].'</p>';
		$sendData['alt_body'] = 'Order has been delivered successfully at '.$data['receiver_address'];
		sendEmail($data, $sendData);
		$message = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> Order has been Verified and notification sent!.</div>';
	}
	else{
		$message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> Something wrong happened, please try later.</div>';
	}
} else if(isset($_POST['assign'])){
	$driver='';
	$order='';
	if(isset($_POST['assign_driver']) && $_POST['assign_driver']!='')
	{
		$data = explode('_', $_POST['assign_driver']);
		$driver = (int)$data[0];
		$order = (int)$data[1];
	}
	else
	{
		$order = isset($_POST['assigndriver']) ? $_POST['assigndriver'] :'';
	}
	$deliver_driver_id = $_POST['dassign_driver'];
	// echo $deliver_driver_id;die();
	// $order_detail = mysqli_query($con, "SELECT * FROM orders WHERE id = '".$order."' ");
	// if(mysqli_num_rows($order_detail) > 0)
	// {
	// 	$user_one=mysqli_fetch_object($order_detail);
	// 	if(isset($user_one->pickup_type) && $user_one->pickup_type == 'Pickup order'){
	// 		$today = date('Y-m-d');
	// 		$tasks_query = mysqli_query($con, "SELECT * FROM deliver WHERE driver_id = $driver AND DATE_FORMAT('%Y-%m-%d', created_on) = '$today'");
	// 		$tasks = ($tasks_query) ? mysqli_num_rows($tasks_query) : 0;
	// 		$tasks += 1;
	// 		$tasks = date('Y').date('m').date('d').sprintf('%02d', $tasks);
	// 		$flag = mysqli_query($con, "INSERT INTO deliver (driver_id, order_id, status, deliver_driver_id, task_no) VALUES($driver, $order, 'accepted', $deliver_driver_id, $tasks)");
	// 	}
	// }
	if(isset($_POST['from_return'])) {
		$flag = mysqli_query($con, "UPDATE deliver SET deliver_driver_id = $deliver_driver_id, status = 'in process' WHERE order_id = $order");
		$flag = mysqli_query($con, "UPDATE orders SET status = 'assigned', status_reason='', assign_driver =".$deliver_driver_id." WHERE id = $order");
		$flag = mysqli_query($con, "UPDATE users SET status = 'assigned' WHERE id = $deliver_driver_id");
	} else {
		
 		// echo $order;die();
		$delivery_date=date('Y-m-d');
		$flag1 = mysqli_query($con, "UPDATE orders SET status = 'assigned' ,status_reason='', assign_driver =".$deliver_driver_id." WHERE id = $order");
		$flag = mysqli_query($con, "UPDATE orders SET delivery_date = '".$delivery_date."' WHERE id = '".$order."' ");

		$query = mysqli_query($con,"SELECT * FROM orders WHERE id=".$order." ");
		$record = mysqli_fetch_array($query);



		if($driver)
		{
			$flag = mysqli_query($con, "UPDATE users SET status = 'assigned' WHERE id = $driver");

		}
		$flag = mysqli_query($con, "UPDATE users SET status = 'assigned' WHERE id = $deliver_driver_id");
		$today = date('Y-m-d');
		if($driver)
		{
		$tasks_query = mysqli_query($con, "SELECT * FROM deliver WHERE driver_id = $driver AND DATE_FORMAT('%Y-%m-%d', created_on) = '$today'");
		$tasks = ($tasks_query) ? mysqli_num_rows($tasks_query) : 0;

		$tasks += 1;
		$tasks = date('Y').date('m').date('d').sprintf('%02d', $tasks);
		$flag = mysqli_query($con, "INSERT INTO deliver (driver_id, order_id, status, deliver_driver_id, task_no) VALUES($driver, $order, 'assigned', $deliver_driver_id, $tasks)");
		}
		else
		{
			$tasks += 1;
			$tasks = date('Y').date('m').date('d').sprintf('%02d', $tasks);
			$flag = mysqli_query($con, "INSERT INTO deliver (order_id, status, deliver_driver_id, task_no) VALUES($order, 'in process', $deliver_driver_id, $tasks)");
		}
	}
	if($flag1){
		$message = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong>Orders is assigned sucessfully!</div>';
	} else {
		$message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> Something wrong happened</div>';
	}


	//insert log
	$order_query = mysqli_query($con,"SELECT * FROM orders WHERE id =".$order." ");
	$order_data = mysqli_fetch_array($order_query);
$date=date('Y-m-d H:i:s');
$user_id = isset($_SESSION['users_id']) ? $_SESSION['users_id'] : '';
	mysqli_query($con,"INSERT INTO order_logs(`order_no`,`order_status`,`location`,`created_on`,`user_id`) VALUES ('".$order_data['track_no']."', 'Assigned to Rider.', '','".$date."','".$user_id."') ");
}
if($order_id != null)
	header('Location: order.php?id='.$order_id.'&message='.$message);
else
	header('Location: '.$_SERVER['HTTP_REFERER'].'&message='.$message);
exit();
?>