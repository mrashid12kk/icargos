<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
session_start();
// date_default_timezone_set("Asia/Karachi");

require 'includes/conn.php';
if(isset($_GET['customer_id']))
{
	$customer_id  =  $_GET['customer_id'];
	$zones_list = mysqli_query($con,"SELECT * FROM zone ");
	while ($row = mysqli_fetch_array($zones_list)) 
	{
	 	 
		$zone_id      =  $row['id'];
		 
		$check_pricing = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM customer_pricing where customer_id='".$customer_id."'  and  zone_id='".$zone_id."'  "));
		if (empty($check_pricing)) 
		{
			 
			$service_type   = $row['service_type']; 
			$product_id   = $row['product_id']; 
			$point_5_kg     = $row['point_5_kg'];
			$upto_1_kg      = $row['upto_1_kg']; 
			$other_kg       = $row['other_kg'];
			// $return_charges = $row['return_charges'];
		
			mysqli_query($con,"INSERT INTO customer_pricing(`customer_id`,`product_id`,`zone_id`,`service_type`,`point_5_kg`,`upto_1_kg`,`other_kg` ) VALUES('".$customer_id."','".$product_id."','".$zone_id."', '".$service_type."', '".$point_5_kg."','".$upto_1_kg."','".$other_kg."' ) ");

		}  
	} 
		 
	header("Location:customer_detail.php?customer_id=".$customer_id);
}