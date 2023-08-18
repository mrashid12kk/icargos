<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
date_default_timezone_set("Asia/Karachi");

require 'includes/conn.php';
if(isset($_POST['assign'])){
	
	$customer_id =isset($_POST['customer_id']) ? $_POST['customer_id'] :'';
	$tariff_id =isset($_POST['tariff_id']) ? $_POST['tariff_id'] :'';
	mysqli_query($con, "DELETE FROM customer_tariff_detail WHERE `tariff_id`=" . $tariff_id . " AND customer_id =  ".$customer_id);
	foreach ($_POST['start_range'] as $key => $row) {
		$start_range = $_POST['start_range'][$key];
		$end_range = $_POST['end_range'][$key];
		$rate = isset($_POST['rate'][$key]) ? $_POST['rate'][$key] : 0;

		mysqli_query($con, " INSERT INTO `customer_tariff_detail`(`tariff_id`, `customer_id`, `start_range`, `end_range`,`rate`) VALUES (" . $tariff_id . "," . $customer_id . ",'" . $start_range . "','" . $end_range . "','" . $rate . "') ");
	}
	
	header("Location:customer_detail.php?customer_id=".$customer_id);
}
if(isset($_POST['update_customer_tariff'])){
	
	$customer_id =isset($_POST['customer_id']) ? $_POST['customer_id'] :'';
	$tariff_id =isset($_POST['tariff_id']) ? $_POST['tariff_id'] :'';
	mysqli_query($con, "DELETE FROM customer_tariff_detail WHERE `tariff_id`=" . $tariff_id . " AND customer_id =  ".$customer_id);
	foreach ($_POST['start_range'] as $key => $row) {
		$start_range = $_POST['start_range'][$key];
		$end_range = $_POST['end_range'][$key];
		$rate = isset($_POST['rate'][$key]) ? $_POST['rate'][$key] : 0;

		mysqli_query($con, " INSERT INTO `customer_tariff_detail`(`tariff_id`, `customer_id`, `start_range`, `end_range`,`rate`) VALUES (" . $tariff_id . "," . $customer_id . ",'" . $start_range . "','" . $end_range . "','" . $rate . "') ");
	}
	
	header("Location:customer_detail.php?customer_id=".$customer_id);
}
	?>