<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
date_default_timezone_set("Asia/Karachi");

require 'includes/conn.php';
if(isset($_POST['addzone'])){

	$zone = $_POST['zone'];
	$service_type = $_POST['service_type'];
	$product_id = $_POST['product_id'];
	$point_5_kg = isset($_POST['point_5_kg']) ? $_POST['point_5_kg']:0;
    $upto_1_kg = isset($_POST['upto_1_kg']) ? $_POST['upto_1_kg']:0;
	$upto_3_kg = isset($_POST['upto_3_kg']) ? $_POST['upto_3_kg']:0;
	$upto_10_kg = isset($_POST['upto_10_kg']) ? $_POST['upto_10_kg']:0;
    $other_kg = isset($_POST['other_kg']) ? $_POST['other_kg']:0;
	$additional_point_5_kg = isset($_POST['additional_point_5_kg']) ? $_POST['additional_point_5_kg']:0;
	$addition_kg_type = isset($_POST['addition_kg_type']) ? $_POST['addition_kg_type']:'';
	$query = mysqli_query($con,"SELECT * FROM zone WHERE `zone`='".$zone."' ");
	if(mysqli_num_rows($query)>0){
		$zone_data = mysqli_fetch_array($query);
		$zone_id = $zone_data['id'];
		 mysqli_query($con,"DELETE FROM zone WHERE `id`='".$zone_id."' ");
		mysqli_query($con,"DELETE FROM zone_cities WHERE `zone`='".$zone_id."' ");
	}
	mysqli_query($con,"INSERT INTO zone(`zone`,`product_id`,`service_type`,`point_5_kg`,`upto_1_kg`,`upto_3_kg`,`upto_10_kg`,`other_kg`,`additional_point_5_kg`,`addition_kg_type`) VALUES('".$zone."','".$product_id."','".$service_type."','".$point_5_kg."','".$upto_1_kg."','".$upto_3_kg."','".$upto_10_kg."','".$other_kg."','".$additional_point_5_kg."','".$addition_kg_type."') ");


	$zone_id = mysqli_insert_id($con);
	if(!empty($_POST['pricing'])){
		foreach($_POST['pricing'] as $row){
			$origin = $row['city_form'];
			$destination = $row['city_to'];
			mysqli_query($con,'INSERT INTO zone_cities(`zone`,`origin`,`destination`) VALUES("'.$zone_id.'","'.$origin.'","'.$destination.'") ');
			$rowscount=mysqli_affected_rows($con);
    			if($rowscount > 0){
		        $msg= '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you Added a new ZONE successfully</div>';
		        }else{
		        $msg= '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not Added a new ZONE unsuccessfully.</div>';
		      }

		      $_SESSION['zone_msg']=$msg;
				}
			}


	header("Location:addzone.php");
}
if(isset($_GET['zone_id'])){
	$zone_id = $_GET['zone_id'];
	mysqli_query($con,"DELETE FROM zones WHERE id=".$zone_id." ");
	header("Location:addzone.php");
}
if(isset($_POST['save_zone'])){
	$customer_id = $_POST['customer_id'];
	$pickup_zone = $_POST['pickup_zone'];
	$delivery_zone = $_POST['delivery_zone'];
	$order_id = $_POST['order_id'];
	mysqli_query($con,"UPDATE orders SET pickup_zone=".$pickup_zone.", delivery_zone=".$delivery_zone." WHERE customer_id=".$customer_id." AND id='".$order_id."' ");
	header("Location:".$_SERVER['HTTP_REFERER']);
}
if(isset($_POST['assign_orders'])){
    $orders = json_decode($_POST['assign_orders']);
    $rider = $_POST['rider_id'];
    foreach($orders as $order){
    	mysqli_query($con,"UPDATE orders SET pickup_rider='".$rider."' WHERE id=".$order." ");
    }
    header("Location:".$_SERVER['HTTP_REFERER']);
}
if(isset($_POST['assign_delivery_orders'])){
	$date=date('Y-m-d H:i:s');
    $orders = json_decode($_POST['assign_delivery_orders']);
    $rider = $_POST['rider_id'];
    foreach($orders as $order){
    	$query = mysqli_query($con,"SELECT * FROM orders WHERE id =".$order." ");
		$record = mysqli_fetch_array($query);
    	mysqli_query($con,"UPDATE orders SET assign_driver='".$rider."', status='assigned' WHERE id=".$order." ");
    	mysqli_query($con,"INSERT INTO order_logs(`order_no`,`order_status`,`location`,`created_on`) VALUES ('".$record['track_no']."', 'Assigned to Delivery Rider.','','".$date."') ");
    }
    header("Location:".$_SERVER['HTTP_REFERER']);
}

if(isset($_POST['assign_pickup_zone'])){
	$order_id = $_POST['order_id'];
	$rider_id = $_POST['rider_id'];
	mysqli_query($con,"UPDATE orders SET pickup_rider='".$rider_id."' WHERE id=".$order_id." ");
	header("Location:".$_SERVER['HTTP_REFERER']);
}
	?>
