<?php
session_start();
$access_token = $_SESSION['access_token'];
include_once('inc/conn.php');
include_once('inc/constants.php');
if(isset($_POST['save_preference']) && !empty($_POST['client_code']) && !empty($_POST['auth_key'])){
	$client_code = $_POST['client_code'];
	$auth_key = $_POST['auth_key'];
	mysqli_query($con,"UPDATE preferences SET `client_code`='".$client_code."', `auth_key`='".$auth_key."' WHERE `access_token`='".$access_token."'  ");
	
	header("Location:".$_SERVER['HTTP_REFERER']);

}
if(isset($_POST['cancel_order'])){
	$track_no = $_POST['track_no'];
	$order_id = $_POST['order_id'];
	$get_pref = mysqli_query($con,"SELECT * FROM  preferences WHERE `access_token`='".$access_token."' ");
	$pref_res = mysqli_fetch_array($get_pref);
	$user_auth = $pref_res['auth_key'];
	if(mysqli_num_rows($get_pref) >0){
		
		$url = COURIER_URL.'API/CancelOrder.php?auth_key='.$user_auth.'&tracking_no='.$track_no;
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		$result = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($result);
		if(isset($response->tracking_no) && !empty($response->tracking_no)){
			$_SESSION['response_message'] =  $response->message;
			mysqli_query($con,"DELETE FROM order_logs WHERE order_id ='".$order_id."' ");
		}else{
			$_SESSION['response_message'] = $response;
		}
		
	}
}

if (isset($_POST['save_shipper_info'])) { 
    $client_code = isset($_POST['client_code']) ? $_POST['client_code'] : '';
    $edit_code = isset($_POST['edit_code']) ? $_POST['edit_code'] : '';
    $product = isset($_POST['product']) ? $_POST['product'] : '';
    $service = isset($_POST['service']) ? $_POST['service'] : '';
    $origin = isset($_POST['origin']) ? $_POST['origin'] : '';
    $is_item_name = isset($_POST['is_item_name']) && $_POST['is_item_name']== 1 ?  1 : 0 ;
    $is_item_sku = isset($_POST['is_item_sku']) && $_POST['is_item_sku']== 1 ?  1 : 0 ;
    $is_weight_default = isset($_POST['is_weight_default']) && $_POST['is_weight_default']==1 ?  1 : 0 ;
    $profile_id = isset($_POST['profile_id']) ? $_POST['profile_id'] : '';
	$special_instructions = isset($_POST['special_instructions']) ? $_POST['special_instructions'] : '';
	$error = false;
	if(empty($profile_id)){
		$error = true;
		$error_msg = "Profile ID is required!";
	}
	if(empty($product)){
		$error = true;
		$error_msg = "Product is required!";
	}
	if(empty($service)){
		$error = true;
		$error_msg = "Service is required!";
	}
	if(empty($origin)){
		$error = true;
		$error_msg = "Origin is required!";
	}
    
	if($error){
		$_SESSION['error_message'] = $error_msg;
	}else{
		if (isset($edit_code) && !empty($edit_code) && $edit_code > 0) {
			$ins_qry = "UPDATE `shipper_info` SET `product`='$product',`service`='$service',`profile_id`='$profile_id',`special_instructions`='$special_instructions',`origin`='$origin', `is_item_name`= $is_item_name, `is_item_sku` = $is_item_sku, `is_weight_default` = $is_weight_default WHERE `client_code` = '$client_code'";
			$message = "Data Updated Successfully!";
		} else {
			$ins_qry = "INSERT INTO `shipper_info`(`client_code`, `product`, `service`, `profile_id`, `special_instructions`, `cod_payment_method`, `default_weight`, `origin`,`is_item_name` ,`is_item_sku`,`is_weight_default`) VALUES ('$client_code','$product','$service','$profile_id','$special_instructions','$cod_payment_method','1','$origin',$is_item_name ,$is_item_sku, $is_weight_default)";
			$message = "Data Added Successfully!";
		}
		// echo $ins_qry;
		// die;
		$queryExe = mysqli_query($con, $ins_qry);
		if(!$queryExe){
			$message = mysqli_error($con);
			$_SESSION['error_message'] = $message;
		}else{
			$_SESSION['succ_message'] = $message;
		}
	
		
	}
    
}

header("Location:".$_SERVER['HTTP_REFERER']);
 ?>
