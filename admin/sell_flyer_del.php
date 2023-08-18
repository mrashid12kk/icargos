<?php
	session_start();
	require 'includes/conn.php';
	if(isset($_SESSION['users_id']) &&($_SESSION['type']!=='driver'))
	{ 

		if(isset($_POST['delete']))
		{

			  
			$flyer_id   = mysqli_real_escape_string($con,$_POST['flayer_order_id']); 

			 
			 
			$orders_del = "DELETE FROM `flayer_orders` where flayer_order_index= ".$flyer_id; 
			$querydel=mysqli_query($con,$orders_del) or die(mysqli_error($con));
  
			$index_del = "DELETE FROM `flayer_order_index` where id= ".$flyer_id; 
			$querydel=mysqli_query($con,$index_del) or die(mysqli_error($con));
		 
			 header("location:flyer_sell.php");

		}


	}else{
		header("location:index.php");
	}


?>