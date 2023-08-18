<?php
	session_start();
	require 'includes/conn.php';
	if(isset($_SESSION['users_id']) &&($_SESSION['type']!=='driver'))
	{ 
		if(isset($_POST['save_order']))
		{ 
			$customer   = mysqli_real_escape_string($con,$_POST['customer']);
			$order_date = mysqli_real_escape_string($con,$_POST['order_date']); 
			$sql = "INSERT INTO `flayer_order_index`(`customer`,`order_date`) VALUES ('$customer','$order_date') ";

			$query1=mysqli_query($con,$sql) or die(mysqli_error($con));
			$rowscount=mysqli_affected_rows($con);
			$flyer_order_id = $con->insert_id;
			$sub_total = 0;

			foreach ($_POST['flayer'] as $value) 
			{
				$sub_total += $value['total_price'];
				$flayer_id       = $value['flayer_id'];
				$original_price  = $value['original_price'];
				$qty             = $value['qty'];
				$total_price     = $value['total_price'];
				
				$row = ['flayer' => $flayer_id, 'qty' => $qty, 'flayer_order_index' => $flyer_order_id, 'original_price' => $original_price, 'total_price' => $total_price];
				
				$index = 0;
				if(isset($value['flayer_id']) && !empty($value['flayer_id'])){
				foreach ($row as $key => &$value) {
					if(trim($value) == "") {
						array_splice($row, $index, 1);
						$index--;
					}
					$index++;
				}
				$sql2 = "INSERT INTO `flayer_orders`(`".implode("`,`", array_keys($row))."`) VALUES (".implode(",", $row).") ";
				 
				$query2=mysqli_query($con,$sql2) or die(mysqli_error($con));
				 }
			}
			mysqli_query($con,"INSERT INTO ledger(`paid`,`customer_id`,`ledger_type`) VALUES('".$sub_total."','".$customer."','Sell Flayer') ");
		 
			 header("location:flyer_sell.php");
		}
	}else{
		header("location:index.php");
	}
?>