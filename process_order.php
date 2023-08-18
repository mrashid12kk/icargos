<?php
$step=1;
function addQuote($value){
	return(!is_string($value)==true) ? $value : "'".$value."'";
}
function getBarCodeImage($text = '', $code = null, $index) {
  require_once('includes/BarCode.php');
  $barcode = new BarCode();
  $path = 'assets/barcodes/imagetemp'.$index.'.png';
  $barcode->barcode($path, $text);
  return $path;
}
$settings = mysqli_query($con, "SELECT * FROM settings");
$settings = ($settings) ? mysqli_fetch_object($settings) : null;
$possible_orders = isset($settings->per_day_packages) ? $settings->per_day_packages : 50;
$error = '';
	if(isset($_POST['step1'])){
// echo '<pre>', print_r($_POST), '</pre>';
// 		exit();	
		 		
		$_SESSION['step1']['plocation'] = mysqli_real_escape_string($con, $_POST['plocation']);
		$_SESSION['step1']['daddress'] = mysqli_real_escape_string($con, $_POST['daddress']);
		$_SESSION['step1']['weight'] = (int)mysqli_real_escape_string($con, $_POST['weight']);
		$_SESSION['step1']['package_type'] = mysqli_real_escape_string($con, $_POST['package_type']);
		$_SESSION['step1']['price'] = mysqli_real_escape_string($con, $_POST['price']);
		$_SESSION['step1']['collection_amount'] = mysqli_real_escape_string($con, $_POST['collection_amount']);
		
		$_SESSION['step1']['payment_type'] = mysqli_real_escape_string($con, $_POST['payment_type']);
		// $_SESSION['step1']['status']=$_SESSION['step1']['payment_type']=="PAYPAL"||$_SESSION['step1']['payment_type']=="VISA"?'null':"pending";
		$_SESSION['step1']['status']='pending';
		$_SESSION['step1']['cash_by'] = mysqli_real_escape_string($con, $_POST['cash_by']);
		$_SESSION['step1']['pickup_date'] = mysqli_real_escape_string($con, $_POST['pickup_date']);
		$_SESSION['step1']['distance'] = mysqli_real_escape_string($con, $_POST['distance']);
		if(isset($_SESSION['customers']) && isset($_POST['pickup_date'])) {
			$order_check = mysqli_query($con, "SELECT * FROM orders WHERE pickup_date = '".date('m/d/Y', strtotime($_POST['pickup_date']))."' AND customer_id = '".$_SESSION['customers']."'");
			if($order_check && mysqli_num_rows($order_check) >= $possible_orders) {
				$error = '<div class="alert alert-danger">You can create more order for this date</div>';
			}
		}
		
		
		// $_SESSION['step1']['plocation']=mysqli_real_escape_string($con,$_POST['plocation']);
		// $_SESSION['step1']['pickup_address']=mysqli_real_escape_string($con,$_POST['pickup_address']);
		// $_SESSION['step1']['package_type']=mysqli_real_escape_string($con,$_POST['package_type']);
		// $_SESSION['step1']['pickup_city']=mysqli_real_escape_string($con,$_POST['pickup_city']);
		
		// $_SESSION['step1']['sname']=mysqli_real_escape_string($con,$_POST['sname']);
		// $_SESSION['step1']['sphone']=mysqli_real_escape_string($con,$_POST['sphone']);
		// $_SESSION['step1']['semail']=mysqli_real_escape_string($con,$_POST['semail']);
		// $_SESSION['step1']['sender_address']=mysqli_real_escape_string($con,$_POST['sender_address']);
		if($error == '')
			$step=2;
		
	}
	else if(isset($_POST['back'])){
		$step=1;
	}
	else if(isset($_POST['step2'])){
		$_SESSION['step2']['rname']=mysqli_real_escape_string($con,$_POST['rname']);
		$_SESSION['step2']['rphone']=mysqli_real_escape_string($con,$_POST['rphone']);
		$_SESSION['step2']['remail']=mysqli_real_escape_string($con,$_POST['remail']);
		
		if(isset($_POST['sender'])) {
			$sender = $_POST['sender'];
			$_SESSION['step2']['sname'] = $sender['fname'];
			$_SESSION['step2']['sphone'] = $sender['mobile_no'];
			$_SESSION['step2']['semail'] = $sender['email'];
			if(isset($_SESSION['customers'])) {
				$customer_id = $_SESSION['customers'];
			} else {
				if(isset($sender['email'])) {
					$cust_query = mysqli_query($con, "SELECT * FROM customers WHERE email = '".$sender['email']."'");
					if(mysqli_num_rows($cust_query) > 0) {
						$row = mysqli_fetch_object($cust_query);
						if(isset($row->id))
							$customer_id = $row->id;
					}
				}
			}
			array_walk($sender, function(&$value, &$key) {
				if($value == '')
					unset($sender[$key]);
				else
					$value =addQuote($value);
			});
			if(isset($customer_id)) {
				array_walk($sender, function(&$val, &$k) {
					$val = "$k = ".$val;
				});
				mysqli_query($con, "UPDATE customers SET ".implode(',', $sender)." WHERE id = ".$customer_id);
			} else {
				$keys = implode(", ", array_keys($sender));
				$values = implode(",",$sender);
				if(mysqli_query($con, "INSERT INTO customers ($keys) VALUES($values)")) {
					$customer_id = mysqli_insert_id($con);
				}
			}
		}
		
		
		if (isset($customer_id)) {
				if(isset($_SESSION['step1']['pickup_date'])) {
					$order_check = mysqli_query($con, "SELECT * FROM orders WHERE pickup_date = '".date('m/d/Y', strtotime($_SESSION['step1']['pickup_date']))."' AND customer_id = '".$customer_id."'");
					if($order_check && mysqli_num_rows($order_check) >= $possible_orders) {
						$error = '<div class="alert alert-danger">You can create more order for this date</div>';
					}
				}
				if($error == '') {
					$query=mysqli_query($con,"INSERT INTO `orders`(`plocation`, `daddress`, `customer_id`, `weight_id`, `package_type`, `price`, `pickup_date`, `cash_by`, `payment_method`, `distance`, `rname`, `rphone`, `remail`, `status`, `collection_amount`, `sname`, `sphone`, `semail`) VALUES ('".$_SESSION['step1']['plocation']."','".$_SESSION['step1']['daddress']."','".$customer_id."','".$_SESSION['step1']['weight']."','".$_SESSION['step1']['package_type']."','".$_SESSION['step1']['price']."','".$_SESSION['step1']['pickup_date']."','".$_SESSION['step1']['cash_by']."','".$_SESSION['step1']['payment_type']."','".$_SESSION['step1']['distance']."','".$_SESSION['step2']['rname']."','".$_SESSION['step2']['rphone']."','".$_SESSION['step2']['remail']."','".$_SESSION['step1']['status']."', '".$_SESSION['step1']['collection_amount']."', '".$_SESSION['step2']['sname']."', '".$_SESSION['step2']['sphone']."', '".$_SESSION['step2']['semail']."')") or die(mysqli_error($con));
					$insert_id=mysqli_insert_id($con);
					if($insert_id > 0) {
						$barcode = rand(1000000, 9999999);
						$barcode = substr($barcode, 0, strlen($barcode)-strlen($insert_id));
						$barcode .= $insert_id;
						$barcode_image = getBarCodeImage($barcode, null, $insert_id);
						$track_no = sprintf('%06d', $insert_id);
						mysqli_query($con, "UPDATE orders SET barcode = '".$barcode."', barcode_image = '".$barcode_image."', track_no = '".$track_no."' WHERE id = $insert_id");
						if(isset($_POST['sender']['mobile_no'])) {
							// require_once 'includes/sms_helper.php';
							// $message = 'Dear '.$_POST['sender']['fname'].', Your order has been received and will be processed very soon. Tracking Number is '.$track_no;
							// $message .= '. www.cms.com.pk';
							// send_sms($_POST['sender']['mobile_no'], $message);
						}
					}
					unset($_SESSION['step1']);
					unset($_SESSION['step2']);
					$iddd=encrypt($insert_id."-usUSMAN767###");
					$src="invoicehtml.php?id=$iddd";
					if(isset($_SESSION['customers']))
					 echo "<script>window.location.href='$src'</script>";
					else {
			 			$step = 3;
					}
				}
		 }else {
			 // echo "<script>window.location.href='login.php?redirect=sendpackage.php'</script>";
			 echo "<script>window.location.href='login.php?redirect=sendpackage.php'</script>";
		 }
			 
			
		// $step=3;
	}
	else if(isset($_POST['send_package'])){
			$step=4;
		$date=date("Y-m-d");
		$_SESSION['step3']['distance']=mysqli_real_escape_string($con,$_POST['distance']);
		$_SESSION['step3']['package_type']=mysqli_real_escape_string($con,$_POST['package_type']);
		$_SESSION['step3']['pickup_date']=mysqli_real_escape_string($con,$_POST['pickup_date']);
		$_SESSION['step3']['pickup_time']=mysqli_real_escape_string($con,$_POST['pickup_time']);
		$_SESSION['step3']['delivery_date']=mysqli_real_escape_string($con,$_POST['delivery_date']);
		$_SESSION['step3']['delivery_time']=mysqli_real_escape_string($con,$_POST['delivery_time']);
		$_SESSION['step3']['delivery_by']=mysqli_real_escape_string($con,$_POST['delivery_by']);
		$_SESSION['step3']['collection_amount']=mysqli_real_escape_string($con,$_POST['collection_amount']);
		if(isset($_SESSION['customers'])){
			// FOr Registered USers
			if($_SESSION['step2']['delivery_city']==$_SESSION['step1']['pickup_city']){
				if($_SESSION['step3']['package_type']=='Food'){
					$queryy1=mysqli_query($con,"Select * from prices where user_type='registered' and package_type='Food'") or die(mysqli_error($con));
					$fetchh1=mysqli_fetch_array($queryy1);
					$price=$fetchh1['city_to_city'];
				}
				else{
					$queryy1=mysqli_query($con,"Select * from prices where user_type='registered' and package_type='Product'") or die(mysqli_error($con));
					$fetchh1=mysqli_fetch_array($queryy1);
					$price=$fetchh1['city_to_city'];
					// $price=30;
				}
			}
			else{
				if($_SESSION['step2']['delivery_city']=='Fujairah  الفجيرة'){
					if($_SESSION['step3']['package_type']=='Food'){
							$queryy1=mysqli_query($con,"Select * from prices where user_type='registered' and package_type='Food'") or die(mysqli_error($con));
							$fetchh1=mysqli_fetch_array($queryy1);
							$price=$fetchh1['city_to_fuj'];
				
						// $price=60;
					}
					else{
						$queryy1=mysqli_query($con,"Select * from prices where user_type='registered' and package_type='Product'") or die(mysqli_error($con));
						$fetchh1=mysqli_fetch_array($queryy1);
						$price=$fetchh1['city_to_fuj'];
						
						// $price=40;
					}
				}
				else{
					if($_SESSION['step3']['package_type']=='Food'){
						$queryy1=mysqli_query($con,"Select * from prices where user_type='registered' and package_type='Food'") or die(mysqli_error($con));
						$fetchh1=mysqli_fetch_array($queryy1);
						$price=$fetchh1['city_to_ano'];
						
						// $price=50;
					}
					else{
						$queryy1=mysqli_query($con,"Select * from prices where user_type='registered' and package_type='Product'") or die(mysqli_error($con));
						$fetchh1=mysqli_fetch_array($queryy1);
						$price=$fetchh1['city_to_ano'];
						
						// $price=30;
					}
				}
			}
		}
		else{
			// FOr Guest USers
			
			if($_SESSION['step2']['delivery_city']==$_SESSION['step1']['pickup_city']){
				if($_SESSION['step3']['package_type']=='Food'){
					$queryy1=mysqli_query($con,"Select * from prices where user_type='Guest' and package_type='Food'") or die(mysqli_error($con));
						$fetchh1=mysqli_fetch_array($queryy1);
						$price=$fetchh1['city_to_city'];
						
					// $price=50;
				}
				else{
						$queryy1=mysqli_query($con,"Select * from prices where user_type='Guest' and package_type='Product'") or die(mysqli_error($con));
						$fetchh1=mysqli_fetch_array($queryy1);
						$price=$fetchh1['city_to_city'];
						
					// $price=40;
				
				}
			}
			else{
				if($_SESSION['step2']['delivery_city']=='Fujairah  الفجيرة'){
					if($_SESSION['step3']['package_type']=='Food'){
							$queryy1=mysqli_query($con,"Select * from prices where user_type='Guest' and package_type='Food'") or die(mysqli_error($con));
						$fetchh1=mysqli_fetch_array($queryy1);
						$price=$fetchh1['city_to_fuj'];
					
						// $price=60;
					}
					else{
							$queryy1=mysqli_query($con,"Select * from prices where user_type='Guest' and package_type='Product'") or die(mysqli_error($con));
						$fetchh1=mysqli_fetch_array($queryy1);
						$price=$fetchh1['city_to_fuj'];
					
						// $price=40;
					}
				}
				else{
					if($_SESSION['step3']['package_type']=='Food'){
							$queryy1=mysqli_query($con,"Select * from prices where user_type='Guest' and package_type='Food'") or die(mysqli_error($con));
						$fetchh1=mysqli_fetch_array($queryy1);
						$price=$fetchh1['city_to_ano'];
					
						// $price=60;
					
					}
					else{
							$queryy1=mysqli_query($con,"Select * from prices where user_type='Guest' and package_type='Product'") or die(mysqli_error($con));
						$fetchh1=mysqli_fetch_array($queryy1);
						$price=$fetchh1['city_to_ano'];
					
						// $price=50;
					}
				}
			}
		}
		if($_SESSION['step3']['pickup_date']==$_SESSION['step3']['delivery_date']){
				$queryy1=mysqli_query($con,"Select * from prices where user_type='Guest' and package_type='Product'") or die(mysqli_error($con));
				$fetchh1=mysqli_fetch_array($queryy1);
					
			$price=$price+(int)$fetchh1['same_date_inc'];
		}
		$_SESSION['step3']['price']=$price;
			if(isset($_SESSION['customers'])){
				$_SESSION['step3']['customers_id']=mysqli_real_escape_string($con,$_POST['customers_id']);
				$query=mysqli_query($con,"INSERT INTO `orders`(`plocation`, `sname`, `sphone`, `semail`, `sender_address`, `daddress`, `rname`, `rphone`, `remail`, `receiver_address`, `package_type`, `pickup_date`, `delivery_by`,`customer_id`,`collection_amount`,`type`,`distance`,`delivery_date`,`price`,`pickup_address`,`pickup_time`,`delivery_time`,`pickup_city`,`order_date`) VALUES ('".$_SESSION['step1']['plocation']."','".$_SESSION['step1']['sname']."','".$_SESSION['step1']['sphone']."','".$_SESSION['step1']['semail']."','".$_SESSION['step1']['sender_address']."','".$_SESSION['step1']['daddress']."','".$_SESSION['step2']['rname']."','".$_SESSION['step2']['rphone']."','".$_SESSION['step2']['remail']."','".$_SESSION['step2']['receiver_address']."','".$_SESSION['step3']['package_type']."','".$_SESSION['step3']['pickup_date']."','".$_SESSION['step3']['delivery_by']."','".$_SESSION['step3']['customers_id']."','".$_SESSION['step3']['collection_amount']."','Delivery Order','".$_SESSION['step3']['distance']."','".$_SESSION['step3']['delivery_date']."','".$_SESSION['step3']['price']."','".$_SESSION['step1']['pickup_address']."','".$_SESSION['step3']['pickup_time']."','".$_SESSION['step3']['delivery_time']."','".$_SESSION['step1']['pickup_city']."','$date')") or die(mysqli_error($con));
			}
			
			else{
				$query=mysqli_query($con,"INSERT INTO `orders`(`plocation`, `sname`, `sphone`, `semail`, `sender_address`, `daddress`, `rname`, `rphone`, `remail`, `receiver_address`, `package_type`, `pickup_date`, `delivery_by`, `collection_amount`, `type`, `distance`, `delivery_date`, `price`, `pickup_address`, `pickup_time`, `delivery_time`, `pickup_city`, `order_date`) VALUES ('".$_SESSION['step1']['plocation']."','".$_SESSION['step1']['sname']."','".$_SESSION['step1']['sphone']."','".$_SESSION['step1']['semail']."','".$_SESSION['step1']['sender_address']."','".$_SESSION['step1']['daddress']."','".$_SESSION['step2']['rname']."','".$_SESSION['step2']['rphone']."','".$_SESSION['step2']['remail']."','".$_SESSION['step2']['receiver_address']."','".$_SESSION['step3']['package_type']."','".$_SESSION['step3']['pickup_date']."','".$_SESSION['step3']['delivery_by']."','".$_SESSION['step3']['collection_amount']."','Delivery Order','".$_SESSION['step3']['distance']."','".$_SESSION['step3']['delivery_date']."','".$_SESSION['step3']['price']."','".$_SESSION['step1']['pickup_address']."','".$_SESSION['step3']['pickup_time']."','".$_SESSION['step3']['delivery_time']."','".$_SESSION['step1']['pickup_city']."','$date')") or die(mysqli_error($con));
			}
			
		$count=mysqli_affected_rows($con);
		if($count>0){
			$insert_id=mysqli_insert_id($con);
				
				$msg='<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> your order has been successfully submitted,we have sent an invoice link to your email and your tracking code is '.encrypt($insert_id."-usUSMAN767###").' your estimate distance between delivery location and pickup location is '.$_SESSION['step3']['distance'].' and delivery charges are '.$price.' AED.</div>' ;
				$email=$_SESSION['step1']['semail'];
				$text="Your package has been submitted successfully,the invoice link is \n ".$_SERVER['HTTP_HOST']."/delivery/invoicehtml.php?id=".encrypt($insert_id."-usUSMAN767###")." \n and your tracking code is ".encrypt($insert_id."-usUSMAN767###");
				$headers="From:happinessdelivery@happinessdelivery.com";
				if($email!==""){
					mail($email,'Package Submitted',$text,$headers);
				}
				unset($_SESSION['step1']);
				unset($_SESSION['step2']);
				unset($_SESSION['step3']);
				// $salt="-usUSMAN767###";
				$iddd=encrypt($insert_id."-usUSMAN767###");
				$src="invoicehtml.php?id=$iddd";
				// echo "<script>window.location.href='$src';</script>";
			}
		else{
			$msg='<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> your order has not been  submitted,please try again later.</div>';
		}
			
	}
	
	else if(isset($_POST['step_1'])){
		$step=1;
	}
	else if(isset($_POST['step_2'])){
		$step=3;
		// die("hello");
	}
	else if(isset($_POST['step_3'])){
		$step=3;
	}
	else{
		// unset($_SESSION['step1']);
		unset($_SESSION['step2']);
		unset($_SESSION['step3']);
			$step=1;
				
	}
	
if(isset($_SESSION['customers'])) {
	$customer = mysqli_query($con, "SELECT * FROM customers WHERE id = ".$_SESSION['customers']);
	if($customer)
		$customer = mysqli_fetch_object($customer);
}
