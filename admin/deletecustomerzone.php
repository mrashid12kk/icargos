<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
date_default_timezone_set("Asia/Karachi");

require 'includes/conn.php';
if(isset($_GET['zone_id']) && isset($_GET['customer_id']) && !empty($_GET['zone_id']) && !empty($_GET['customer_id']) ){
	$zone_id     = $_GET['zone_id'];
	$customer_id = $_GET['customer_id'];
	$main_id     = $_GET['main_id'];
	mysqli_query($con,"DELETE FROM customer_pricing WHERE id='".$main_id."' AND customer_id='".$customer_id."' ");
}
header("Location:".$_SERVER['HTTP_REFERER']);

	?>
