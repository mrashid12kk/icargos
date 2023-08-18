<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
date_default_timezone_set("Asia/Karachi");

require 'includes/conn.php';
if(isset($_POST['assign'])){
	$zone = $_POST['zone'];
	$product_id = $_POST['product_id'];
	$service_type_q = mysqli_query($con,"SELECT service_type FROM zone WHERE id='".$zone."' ");
	$service_type_q_r = mysqli_fetch_array($service_type_q);
	$service_type = $service_type_q_r['service_type'];
	$customer_id = $_POST['customer_id'];
	$point_5_kg = isset($_POST['point_5_kg']) ? $_POST['point_5_kg']:0;
	$upto_1_kg = isset($_POST['onekg']) ? $_POST['onekg']:0;
	$upto_3_kg = isset($_POST['upto_3_kg']) ? $_POST['upto_3_kg']:0;
	$upto_10_kg = isset($_POST['upto_10_kg']) ? $_POST['upto_10_kg']:0;
	$other_kg = isset($_POST['other_kg']) ? $_POST['other_kg']:0;
	$additional_point_5_kg = isset($_POST['additional_point_5_kg']) ? $_POST['additional_point_5_kg']:0;
	$addition_kg_type = isset($_POST['addition_kg_type']) ? $_POST['addition_kg_type']:'';
	mysqli_query($con,"INSERT INTO customer_pricing(`customer_id`,`zone_id`,`product_id`,`service_type`,`point_5_kg`,`upto_1_kg`,`upto_3_kg`,`upto_10_kg`,`other_kg`,`additional_point_5_kg`,`addition_kg_type`) VALUES('".$customer_id."','".$zone."','".$product_id."', '".$service_type."','".$point_5_kg."','".$upto_1_kg."','".$upto_3_kg."','".$upto_10_kg."','".$other_kg."','".$additional_point_5_kg."','".$addition_kg_type."') ");


	header("Location:customer_detail.php?customer_id=".$customer_id);
}

	?>
