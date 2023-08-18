<?php
session_start();
require 'includes/conn.php';
		$cust_id = $_REQUEST['cust_id'];
		$sid = $_REQUEST['id'];

		$sql = "SELECT * from `products` where `id`= '".$sid."'";
		$v = mysqli_query($con, $sql);
		$row = mysqli_fetch_assoc($v);
		if($row['checkbox'])
		{
		$check = $row['checkbox'];
		}
		if($check == 1){
			$customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $cust_id . " ");
			$customer_data = mysqli_fetch_assoc($customer_query);
	   		if($customer_data){
	   			echo json_encode($customer_data);
	   		}
	   		else{
	   			echo '0';
	   		}
		}

?>