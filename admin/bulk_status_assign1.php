<?php
session_start();
date_default_timezone_set("Asia/Karachi");
require 'includes/conn.php';
$message = '';



if(isset($_POST['order_ids']) && !empty($_POST['order_ids']) && !empty($_POST['order_status']) and !isset($_POST['is_for'])){
    
  
                    
                    
 	$date=date('Y-m-d H:i:s');
	$sent = '';
	$order_id_data = explode(',', $_POST['order_ids']);
	$active_status = $_POST['order_status'];
	$deliver_driver_id = $_POST['active_courier'];
	$error = 0;
	$current_branch_id = 0;
	if(isset($_SESSION['branch_id']) AND !empty($_SESSION['branch_id']))
	{
		$current_branch_id = $_SESSION['branch_id'];
	}
	///validate all data first
	foreach($order_id_data as $order_id)
	{
		if(!empty($order_id))
		{

            //   $order_id = $_POST['order_ids'] ;
                    $ch = curl_init();
                    $fields = "account_id=11749&api_token=wqnVPz7cexehNLkO9QHABgJNqLVzo3PMigvgeEAyqii1p7n3MexUKyBUd5EH&order_id=".$order_id ;
                    curl_setopt($ch, CURLOPT_URL,"https://forrun.co/api/v1/getOrderStatus");
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
                    
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                      
                       
                    $result1 = curl_exec ($ch);
                    curl_close($ch);
                     echo "<pre>";
                    print_r($result1);
                    
                        die();
                    $data =  json_decode($result1);      
                    foreach ( $data as $key => $value) {
                
                
                      if ($key == 'code' && $value == 404) {
                          echo $value;
                          
                       
                    $record = mysqli_query($con,"SELECT * FROM orders WHERE track_no =".$order_id." ");
                    
                 while($row=mysqli_fetch_array($record)){ 
											 echo $row['sphone'].'<br>'; 
											 echo $row['semail'].'<br>';   
									          echo $row['scnic'].'<br>';
				// 	$payment_method = $row['payment_method']; 
				// 	$sname = $row['sname']; 
				// 	$sphone = $row['sphone']; 
				// 	$sender_address = $row['sender_address']; 
				// 	$sender_city = 'Lahore'; 
				// 	$payment_method = $row['payment_method']; 
				// 	$rname = $row['rname']; 
				// 	$rphone = $row['rphone'];
				// 	$delivery_city = 'Karachi'; 
				// 	$amount = $row['net_amount']; 
				// 	$delivery_address = $row['receiver_address']; 
				// 	$remail = $row['remail'];
				// 	$reference_number = null;
				// 	$no_of_pieces = $row['quantity']; 
				// 	$ensured_declared = null;
				// 	$dimension_l = null;
				// 	$dimension_w = null;
				// 	$dimension_h = null;
				// 	$weight = $row['weight'];
				// 	$item_detail = $row['product_desc'];
				// 	$item_type = null;
				// 	$instructions = null; 
				// 	$no_of_flyers = $row['flyer_qty']; 
					 
										  
                    
                    //      $ch = curl_init();
                    // $fields = "account_id=11749&api_token=wqnVPz7cexehNLkO9QHABgJNqLVzo3PMigvgeEAyqii1p7n3MexUKyBUd5EH&service_type=".$payment_method."&pickup_name=".$sname."&pickup_phone=".$sphone."&pickup_address=".$sender_address."&pickup_city=".$sender_city."&delivery_name=".$rname."&delivery_phone=".$rphone."&delivery_city=".$delivery_city."&amount=".$amount."&delivery_address=".$delivery_address."&delivery_email=".$remail."&reference_number=".$reference_number."&no_of_pieces=".$no_of_pieces."&ensured_declared=".$ensured_declared."&dimension_l=".$dimension_l."&dimension_w=".$dimension_w."&dimension_h=".$dimension_h."&weight=".$weight."&item_detail=".$item_detail."&item_type=".$item_type."&instructions=".$instructions."&no_of_flyers=".$no_of_flyers."" ;
                    // curl_setopt($ch, CURLOPT_URL,"https://forrun.co/api/v1/addnewOrder");
                    // curl_setopt($ch, CURLOPT_POST, 1);
                    // curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                    // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
                    
                    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                      
                       
                    // $result = curl_exec ($ch);
                    // curl_close($ch);
                    //  echo "<pre>";
                    // print_r($result);
                      }
                      
                      die();
                    }
                    
                    
			$query = mysqli_query($con,"SELECT orders.status,allowed_status FROM orders LEFT JOIN order_status ON orders.status=order_status.status WHERE  orders.track_no =".$order_id."   ");
			$record = mysqli_fetch_array($query);


			$allowed_status = explode(',', $record['allowed_status']);


			$check_status  = mysqli_query($con,"SELECT sts_id FROM order_status WHERE status ='".$active_status."'   ");
			$status_record = mysqli_fetch_array($check_status);

			$id_check = $status_record['sts_id'];


			if (!in_array($id_check, $allowed_status))
			{
			 	$message .= "<p> Order ".$order_id." can't be assigned as ".$active_status." </p>";
			 	$error = 1;
		 	}
		}

  	}

  	if ($error == 0)
  	{
		foreach($order_id_data as $order_id)
		{
			if(!empty($order_id))
			{
			    
			    
                    
				$record = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM orders WHERE track_no =".$order_id." "));
                    
                     
                    
                        
                        
                    

				// $check_status  = mysqli_query($con,"SELECT * FROM order_status WHERE status ='".$active_status."'   ");
				// $status_record = mysqli_fetch_array($check_status);
                    
                    
				if (isset($_SESSION['brnach_id']) && !empty($_SESSION['branch_id'])) {
					$q = mysqli_query($con, "UPDATE orders SET current_branch = ".$_SESSION['brnach_id']." , status ='".$active_status."' WHERE track_no = $order_id");
				}else{
					$q = mysqli_query($con, "UPDATE orders SET status ='".$active_status."' WHERE track_no = $order_id");
				}


				$active_status = $_POST['order_status'];

				if (isset($_POST['reason_enable']) and !empty($_POST['reason_enable']))
				{
					$active_status .= ' ( '.$_POST['reason_enable'].' ) ';

					$reason_enable = $_POST['reason_enable'];
					mysqli_query($con, "UPDATE orders SET status_reason ='".$reason_enable."' WHERE track_no = '".$order_id."' ");
				}

				$status_received_by = $active_status;
				if (isset($_POST['received_by']) and !empty($_POST['received_by']) and $_POST['order_status'] =='Delivered')
				{
					$received_by = $_POST['received_by'];
					$status_received_by .= ' ( Received By  '.$received_by.' )';
					mysqli_query($con, "UPDATE orders SET received_by ='".$received_by."' WHERE track_no = '".$order_id."' ");
				}
				if (isset($_POST['assign_branch']) and !empty($_POST['assign_branch']))
				{
					$assign_branch = $_POST['assign_branch'];
					mysqli_query($con, "UPDATE orders SET current_branch =".$assign_branch." WHERE track_no = '".$order_id."' ");
					$branch_id = $_SESSION['branch_id'];
					$status= $_POST['order_status'];
					if($branch_id == '')
					{
						$branch_id = 0;
					}

					$checkId = mysqli_query($con, "SELECT * from branch_assignment where order_num =".$order_id);
					$prevId = mysqli_fetch_assoc($checkId);
					$previousID = $prevId['order_num'];
					if(isset($previousID) && !empty($previousID)){
						$query3 = "UPDATE `branch_assignment` SET `branch_completion_status`=1, `assign_data_time`='".$date."',`status_update_time`='".$date."' WHERE order_num=".$previousID;
						mysqli_query($con, $query3);

						$query4 = "INSERT INTO `branch_assignment`(`branch_id`, `assign_branch`, `order_num`, `assign_data_time`, `status_update_time`, `status_submitted`, `created_on`) VALUES ( ".$branch_id." ,".$assign_branch.",".$order_id.",'".$date."','".$date."','".$status."','".$date."')";
						mysqli_query($con, $query4);
					}else{

						$query4 = "INSERT INTO `branch_assignment`(`branch_id`, `assign_branch`, `order_num`, `assign_data_time`, `status_update_time`, `status_submitted`, `created_on`) VALUES ( ".$branch_id." ,".$assign_branch.",".$order_id.",'".$date."','".$date."','".$status."','".$date."')";
						mysqli_query($con, $query4);
					}
					// echo $query4;
					// die();
					mysqli_query($con,"INSERT INTO order_logs(`order_no`,`branch_id`,`assign_branch`,`order_status`,`location`,`created_on`) VALUES (".$order_id.", '".$branch_id."', '".$assign_branch."', '".$status."','','".$date."') ");
				}

				// out for the delivery



				if($q == true)
				{
					$sent = true;


					/**
					* check if mark done enable
					*/
					$check_mark_done = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM order_status WHERE status ='".$_POST['order_status']."' "));
					if ($check_mark_done['marked_done'] == 1)
					{
						$rider_status_done = " rider_status_done_no = '1', ";
						mysqli_query($con, "UPDATE assignment_record SET $rider_status_done status_update_time ='".$date."' WHERE order_num = $order_id  ");
					}
					if($check_mark_done['branch_completion_status'] == 1)
					{
						$branch_status_done = " branch_completion_status = '1', ";
						mysqli_query($con, "UPDATE branch_assignment SET $branch_status_done status_update_time ='".$date."' WHERE order_num = $order_id  ");
					}

					if($active_status == 'Parcel Received at Destination')
					{
						if(isset($_SESSION['branch_id']) AND !empty($_SESSION['branch_id']))
						{
							$upQ = mysqli_query($con,"UPDATE orders set current_branch = ".$_SESSION['branch_id']);
						}else{
							$upQ = mysqli_query($con,"UPDATE orders set current_branch = 1");
						}
					}

					/**
					* if parcel at office send sms
					*/

					if($active_status == 'Parcel Received at office')
					{
						$rphone = $record['rphone'];
						$rphone  = preg_replace('/[^0-9]/s','',$rphone);
						$pos0 = substr($rphone, 0,1);
						if($pos0 == '3'){
							$alterno=substr($rphone,1);
							$alterno = '0'.$rphone;
							$sphone = $alterno;
						}
						$pos = substr($rphone, 0,2);
						if($pos == '03'){
							$alterno=substr($rphone,1);
							$alterno = '92'.$alterno;
							$rphone = $alterno;
						}

						$sms_data = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM sms_settings WHERE id=1 "));
						//////////////SMS///////////////
						$sms = "";
					    $sms .= "Dear Customer, \r\n";
						$sms .= "Your shipment from ".$record['sbname']." with tracking number ".$record['track_no']." has been picked by ".$sms_data['thanku_company'].". Track at ".$sms_data['track_from_url']." ";

						$http_query = http_build_query([
							'action'  => 'send-sms',
							'api_key' => $sms_data['api_key'],
							'from'    => $sms_data['mask_from'],//sender ID
							'to'      => trim($rphone),
							'sms'     => $sms,
						]);

						$url = 'https://login.brandedsms.me/sms/api?'.$http_query;
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $url);
						curl_setopt($ch, CURLOPT_HEADER, 0);
						curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
						ob_start();
						$response = curl_exec($ch);
						ob_end_clean();
						curl_close($ch);
						//////////////SMS///////////////
							if(isset($_SESSION['branch_id']) AND !empty($_SESSION['branch_id']))
							{
								$upQ = mysqli_query($con,"UPDATE orders set current_branch = ".$_SESSION['branch_id']);
							}else{
								$upQ = mysqli_query($con,"UPDATE orders set current_branch = 1");
							}
					}


					if($active_status == 'Out for Delivery')
					{

						$check_vendor = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM users WHERE id =".$deliver_driver_id." AND user_role_id != 3  "));

						if (!empty($check_vendor) )
						{
							$sphone = $record['sphone'];
							$sphone  = preg_replace('/[^0-9]/s','',$sphone);
							$pos0 = substr($sphone, 0,1);
							if($pos0 == '3')
							{
								$alterno=substr($sphone,1);
								$alterno = '0'.$sphone;
								$sphone = $alterno;
							}
							$pos = substr($sphone, 0,2);
							if($pos == '03'){
								$alterno=substr($sphone,1);
								$alterno = '92'.$alterno;
								$sphone = $alterno;
							}
							$sent = true;

							$sms_data = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM sms_settings WHERE id=1 "));


							$sms = "";
						    $sms .= "Your shipment from ".$record['sbname']." ";
						    $sms .= "with tracking number ".$record['track_no']." is Out for Delivery. Rider will reach at your given address within 1 working day. Please keep Cash amount Rs. ".$record['collection_amount']." ready. Thank you! ";
							$http_query = http_build_query([
								'action'  => 'send-sms',
								'api_key' => $sms_data['api_key'],
								'from'    => $sms_data['mask_from'],//sender ID
								'to' 	  => trim($record['rphone'].','.$sphone),
								'sms'     => $sms,
							]);
							$url = 'https://login.brandedsms.me/sms/api?'.$http_query;
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL, $url);
							curl_setopt($ch, CURLOPT_HEADER, 0);
							curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
							ob_start();
							$response = curl_exec($ch);
							ob_end_clean();
							curl_close($ch);
						}else{
							$status_log = 'Out of Destination City';

							mysqli_query($con, "UPDATE orders SET status='".$status_log."' WHERE track_no = $order_id");
						}
						mysqli_query($con,"INSERT INTO order_logs(`order_no`,`order_status`,`location`,`created_on`) VALUES ('".$record['track_no']."', '".$status_log."','','".$date."') ");
					 //SMS


				 	$record_assignment = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM assignment_record WHERE order_num ='".$record['track_no']."' AND assignment_type= 2 "));
					if (empty($record_assignment)) {


						mysqli_query($con,"INSERT INTO assign_order_record(`order_num`,`user_id`,`assign_data_time`,`status_submitted`,`assignment_status`,`assignment_type`,`assign_branch`,`branch_id`) VALUES ('".$record['track_no']."', '".$deliver_driver_id."', '".$date."', 6, 0 , 2 ,".$current_branch_id.",".$current_branch_id." )");

						mysqli_query($con,"INSERT INTO assignment_record(`order_num`,`user_id`,`assign_data_time`,`status_submitted`,`assignment_status`,`assignment_type`,`assign_branch`,`branch_id`) VALUES ('".$record['track_no']."', '".$deliver_driver_id."', '".$date."', 6, 0 , 2 ,".$current_branch_id.",".$current_branch_id." )");

					}



					}


				}


				mysqli_query($con,"INSERT INTO order_logs(`order_no`,`order_status`,`location`,`created_on`) VALUES ('".$record['track_no']."', '".$status_received_by."','$current_branch_city','".$date."') ");
			}
	  	}

	  	if($q == true){
		  	$_SESSION['succ_msg'] = 'Status Updated';
	  	}
  	}else{
  		$_SESSION['error_msg'] = $message;
  		$message ='';
  	}

	if (isset($_SESSION['error_msg']) and !empty($_SESSION['error_msg']))
	{
		$_SESSION['old_orders_list'] = $_POST['order_ids'];
	}

 	header('Location: order_processing.php?message='.$message);
}elseif(!isset($_POST['is_for'])){
 	header('Location: order_processing.php?message='.$message);
}

if (isset($_POST['status_value']))
{
	$_POST['order_status'] = $_POST['status_value'];
}

if(isset($_POST['order_ids']) && !empty($_POST['order_ids']) && !empty($_POST['order_status']) and isset($_POST['is_for']))
{
 	$date = date('Y-m-d H:i:s');
	$sent = '';
	$order_id_data = explode(',', $_POST['order_ids']);
	$active_status = $_POST['order_status'];


	$rider_id = $_SESSION['users_id'];

	$rider_name_q =  mysqli_fetch_array(mysqli_query($con,"SELECT Name FROM users WHERE id ='".$rider_id."' "));
	$rider_name = $rider_name_q['Name'];

	$status_record  = mysqli_fetch_array(mysqli_query($con,"SELECT sts_id,marked_done FROM order_status WHERE status ='".$active_status."'   "));
	$id_check   = $status_record['sts_id'];

	$error = 0;
	///validate all data first
	foreach($order_id_data as $order_id)
	{
		if(!empty($order_id))
		{
			$order_pickup = mysqli_fetch_array(mysqli_query($con,"SELECT assignment_record.*,order_status.status as status_name FROM assignment_record LEFT JOIN order_status ON assignment_record.assignment_status=order_status.status WHERE  assignment_record.order_num ='".$order_id."'   "));
			if (!empty($order_pickup))
			{
				$query = mysqli_query($con,"SELECT orders.status,allowed_status FROM orders LEFT JOIN order_status ON orders.status=order_status.status WHERE  orders.track_no =".$order_id."   ");
				$record = mysqli_fetch_array($query);
				if (!empty($record))
				{
					$allowed_status = explode(',', $record['allowed_status']);

					if (!in_array($id_check, $allowed_status))
					{
					 	$message .= "<p> Order ".$order_id." can't be assigned as ".$active_status." </p>";
					 	$error = 1;
				 	}


			 	}else{
			 		$message .= "<p> ".$order_id." no such order found. </p>";
				 	$error = 1;
			 	}
			}

			if ($active_status == "Delivered") {
				$credit = 0;
				$cod_q = "SELECT collection_amount from orders where track_no ='".$order_id."' ";

				$cod_result = mysqli_query($con,$cod_q);

				$check_cod=mysqli_fetch_array($cod_result);

				$rider_b = "SELECT * from rider_wallet_ballance where rider_id=".$rider_id;

				$rider_res= mysqli_query($con,$rider_b);
				$rider_prev_balance_q = mysqli_fetch_array($rider_res);

				$rider_prev_balance = $rider_prev_balance_q['balance'];

				$newBalance = $rider_prev_balance + $check_cod['collection_amount'];

				// echo "<pre>";
				// print_r($check_cod);




					// Enter the cod in to the rider wallet

				$check_q = "SELECT * from rider_wallet_ballance where rider_id =".$rider_id;

				$check_res = mysqli_query($con,$check_q);
				$check_rider_exists  = mysqli_fetch_array($check_res);

				$master_id = '';

				if (isset($check_rider_exists['rider_id']) && !empty($check_rider_exists['rider_id'])) {
					$query = "UPDATE  rider_wallet_ballance set balance = ".$newBalance.", update_date = '".date('Y-m-d H:i:s')."' WHERE rider_id =  ".$rider_id;

					$cod_q = mysqli_query($con, $query);

					$master_id = $rider_prev_balance_q['id'];

				}else{

					$query2 = "INSERT INTO `rider_wallet_ballance`(`rider_id`, `rider_name`, `balance`, `update_date`) VALUES (".$rider_id." , '".$rider_name."' , ".$newBalance." , '".date('Y-m-d H:i:s')."'  )";

					$cod_q = mysqli_query($con, $query2);

					$master_id = mysqli_insert_id($con);

				}

					$querys = "INSERT INTO `rider_wallet_ballance_log`(`order_id`, `order_no`, `rider_id`, `rider_name`, `debit`, `credit`, `date`)VALUES (".$master_id." , ".$order_id."  , ".$rider_id." , '".$rider_name."' , '".$check_cod['collection_amount']."' , '$credit' , '".date('Y-m-d H:i:s')."') ";



					$log_q = mysqli_query($con, $querys);
			}

		}



  	}




 	$can_be_marked_done = 1;
  	if ($error == 0)
  	{


		if ( $status_record['marked_done'] == 1)
		{
			$can_be_marked_done = 2;
		}


		foreach($order_id_data as $order_id)
		{
			if(!empty($order_id))
			{

				if (isset($_POST['is_for']) and $_POST['is_for'] == 'pickup_rid')
				{
					$query = mysqli_query($con,"SELECT * FROM orders WHERE track_no =".$order_id."  and pickup_rider=".$rider_id."  ");
				}else if (isset($_POST['is_for']) and $_POST['is_for'] == 'delivery_rid')
				{
					$query = mysqli_query($con,"SELECT * FROM orders WHERE track_no =".$order_id."  and delivery_rider=".$rider_id."  ");
				}



				$record = mysqli_fetch_array($query);

				if (!empty($record))
				{
					$user_id       = $_SESSION['users_id'];

					if ($_POST['is_for'] == 'delivery_rid')
					{
					 	$assignment_no = $record['delivery_assignment_no'];

					}

					if ($_POST['is_for'] == 'pickup_rid') {
						$assignment_no = $record['assignment_no'];
					}


					$check_rider = mysqli_query($con,"SELECT * FROM assignments WHERE rider_id = ".$user_id."  and assignment_no='".$assignment_no."' ");




					if ($check_rider->num_rows > 0)
					{
						$q = mysqli_query($con, "UPDATE orders SET status ='".$active_status."'  WHERE track_no = $order_id");
						$check_for = '';
						if (isset($_POST['is_for']) and $_POST['is_for'] == 'delivery_rid' )
						{
						 	$check_for = ' AND  assignment_type = 2 ';
						}else if (isset($_POST['is_for']) and $_POST['is_for'] == 'pickup_rid' )
						{
							$check_for = ' AND  assignment_type = 1 ';
						}

						$rider_status_done = '';
						if ($can_be_marked_done == 2)
						{
							$rider_status_done = " rider_status_done_no = '1', ";
						}

						mysqli_query($con, "UPDATE assignment_record SET $rider_status_done status_update_time ='".$date."' WHERE order_num = $order_id   $check_for   ");

						// echo "UPDATE assignment_record SET $rider_status_done status_update_time ='".$date."' WHERE order_num = $order_id   $check_for   ";



						$active_status = $_POST['order_status'];

						if (isset($_POST['reason_enable']) and !empty($_POST['reason_enable']))
						{
							$active_status .= ' ( '.$_POST['reason_enable'].' ) ';

							$reason_enable = $_POST['reason_enable'];
							mysqli_query($con, "UPDATE orders SET status_reason ='".$reason_enable."' WHERE track_no = '".$order_id."' ");
						}
						if (isset($_POST['assign_branch']) and !empty($_POST['assign_branch']))
						{
							$status_name= $_POST['order_status'];

							mysqli_query($con, "UPDATE orders SET current_branch = ".$_POST['assign_branch'].", status='".$status_name."' WHERE track_no = '".$order_id."' ");
						}

						$status_received_by = $active_status;
						if (isset($_POST['received_by']) and !empty($_POST['received_by']))
						{
							$status_received_by .= ' ( Received By '.$_POST['received_by'].' ) ';

							$reason_enable = $_POST['received_by'];
							mysqli_query($con, "UPDATE orders SET received_by ='".$received_by."' WHERE track_no = '".$order_id."' ");
						}
					 	if (isset($_FILES["order_signature"]["name"]) and !empty($_FILES["order_signature"]["name"]))
					    {
					    	if (!file_exists("images/order_signature/".$order_id."/")) {
								    mkdir("images/order_signature/".$order_id."/");
								}
					        $target_dir = "images/order_signature/$order_id/";

					        $target_file = $target_dir .uniqid(). basename($_FILES["order_signature"]["name"]);

					        $extension = pathinfo($target_file,PATHINFO_EXTENSION);
					        if($extension=='jpg'||$extension=='png'||$extension=='jpeg') {
					            if (move_uploaded_file($_FILES["order_signature"]["tmp_name"], $target_file))
					            {
					            // echo $target_file;
					                mysqli_query($con,"UPDATE order SET order_signature='".$target_file."' WHERE `track_no`='".$order_id."' ");
					            }
					        }
					    }

						if($q == true)
						{
							$sent = true;

							if($active_status == 'Parcel Received at office')
							{
								$rphone = $record['rphone'];
								$rphone  = preg_replace('/[^0-9]/s','',$rphone);
								$pos0 = substr($rphone, 0,1);
								if($pos0 == '3'){
									$alterno=substr($rphone,1);
									$alterno = '0'.$rphone;
									$sphone = $alterno;
								}
								$pos = substr($rphone, 0,2);
								if($pos == '03'){
									$alterno=substr($rphone,1);
									$alterno = '92'.$alterno;
									$rphone = $alterno;
								}

								$sms_data = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM sms_settings WHERE id=1 "));
								//////////////SMS///////////////
								$sms = "";
							    $sms .= "Dear Customer, \r\n";
								$sms .= "Your shipment from ".$record['sbname']." with tracking number ".$record['track_no']." has been picked by ".$sms_data['thanku_company'].". Track at ".$sms_data['track_from_url']." ";

								$http_query = http_build_query([
									'action'  => 'send-sms',
									'api_key' => $sms_data['api_key'],
									'from'    => $sms_data['mask_from'],//sender ID
									'to'      => trim($rphone),
									'sms'     => $sms,
								]);

								$url = 'https://login.brandedsms.me/sms/api?'.$http_query;
								$ch = curl_init();
								curl_setopt($ch, CURLOPT_URL, $url);
								curl_setopt($ch, CURLOPT_HEADER, 0);
								curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
								ob_start();
								$response = curl_exec($ch);
								ob_end_clean();
								curl_close($ch);
								//////////////SMS///////////////


							}

						}


						mysqli_query($con,"INSERT INTO order_logs(`order_no`,`order_status`,`location`,`created_on`) VALUES ('".$record['track_no']."', '".$status_received_by."','','".$date."') ");
					}
				}
			}
	  	}
	  	if($q == true){
		  	$_SESSION['succ_msg'] = 'Status Updated';
	  	}

	}else{

		// echo "<pre>";
		// print_r($check_rider);
		// die();


		if (empty($message) )
		{
			$message = "No order found for update.";
		}

  		$_SESSION['error_msg'] = $message;
  		$message = '';
  	}

  	if (isset($_SESSION['error_msg']) and !empty($_SESSION['error_msg']))
  	{
  		$_SESSION['old_orders_list'] = $_POST['order_ids'];
  	}

  	// echo "<pre>";
  	// print_r($_POST);
  	// print_r($_SESSION);
  	// die();


	if (isset($_POST['is_for']) and $_POST['is_for'] == 'pickup_rid')
	{
		header('Location: pickups_order_processing.php?message='.$message);
	}else if (isset($_POST['is_for']) and $_POST['is_for'] == 'delivery_rid')
	{
		header('Location: deliveries_order_processing.php?message='.$message);
	}


}

elseif(isset($_POST['is_for'])){


 	header('Location: pickups_order_processing.php?message='.$message);
}



?>
