<?php
	session_start();
	require 'includes/conn.php';
	if(isset($_SESSION['users_id']) &&($_SESSION['type']!=='driver'))
	{ 

		if(isset($_POST['save_order']))
		{

			$customer   = mysqli_real_escape_string($con,$_POST['customer']);

			$order_date = mysqli_real_escape_string($con,$_POST['order_date']); 
			$flyer_id   = mysqli_real_escape_string($con,$_POST['flyer_order']); 

			$sql = "UPDATE `flayer_order_index` set `customer`='$customer',`order_date`='$order_date' where id= ".$flyer_id;
			 
			$query1=mysqli_query($con,$sql) or die(mysqli_error($con));

			 
			$sql_del = "DELETE FROM `flayer_orders` where flayer_order_index= ".$flyer_id;
		 
		 
			$querydel=mysqli_query($con,$sql_del) or die(mysqli_error($con));
 

			 


			foreach ($_POST['flayer'] as $value) 
			{
				$flayer_id       = $value['flayer_id'];
				$original_price  = $value['original_price'];
				$qty             = $value['qty'];
				$total_price     = $value['total_price'];

				$sql2 = "INSERT INTO `flayer_orders`(`flayer`,`qty`,`flayer_order_index`,`original_price`,`total_price`) VALUES ('$flayer_id','$qty','$flyer_id','$original_price','$total_price') ";
				 
				$query2=mysqli_query($con,$sql2) or die(mysqli_error($con));  
			}

		 
			 header("location:flyer_sell.php");

		}


	}else{
		header("location:index.php");
	}


?>