<?php 
session_start();
require 'includes/conn.php';
$date=date('Y-m-d H:i:s');

if(isset($_POST['order_ids']) && !empty(json_decode($_POST['order_ids']))){
	$order_ids = json_decode($_POST['order_ids']);
	$pickup_zone = $_POST['pickup_zone'];
	$delivery_zone = $_POST['delivery_zone'];
	foreach($order_ids as $row){
		 mysqli_query($con,"UPDATE orders SET pickup_zone='".$pickup_zone."', delivery_zone='".$delivery_zone."' WHERE id=".$row." ");
	}
	header("Location:".$_SERVER['HTTP_REFERER']);
}else{
	header("Location:".$_SERVER['HTTP_REFERER']);
}
?>