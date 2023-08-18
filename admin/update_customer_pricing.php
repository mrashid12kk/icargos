<?php 
	session_start();
	require 'includes/conn.php';
	$customer_id = $_POST['customer_id'];
		if(isset($_POST['submit'])){
		$pricing = $_POST['pricing'];
		
		$includes = [];
		 mysqli_query($con,"DELETE FROM pricing WHERE customer_id='".$customer_id."' ");
		foreach($pricing as $row){
      $from = $row['city_form'];
      $to = $row['city_to'];

      mysqli_query($con,"INSERT INTO pricing(`city_from`,`city_to`,`price`,`customer_id`,`first_kg_price`,`point_5_kg_price`) VALUES('".$row['city_form']."','".$row['city_to']."', '".$row['price']."','".$customer_id."','".$row['first_kg_price']."','".$row['point_5_kg_price']."') ");

		}
		
		// echo $impolde_arr; exit();
	}
	header("Location: ".$_SERVER['HTTP_REFERER']);
?>