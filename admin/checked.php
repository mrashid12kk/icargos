<?php
session_start();
require 'includes/conn.php';



// $table="";
// $sql = mysqli_query($con, "ALTER TABLE products ADD COLUMN checkbox VARCHAR(100) DEFAULT '0'");
// if($sql == TRUE){
// 	echo "1";
// }
// else{
// 	  echo("Error description: " . mysqli_error($con));
// }
// var_dump($_REQUEST['id']);
		$id = $_REQUEST['id'];

		$sql = "SELECT `products`.`checkbox` from `products` where `id`= '".$id."'";
		$v = mysqli_query($con, $sql);
		// $x = mysqli_fetch_array($v);
		$row = mysqli_fetch_assoc($v);
		if($row['checkbox'])
		{
		$check = $row['checkbox'];
		}
		if($check == 0){
		$sqlQuery = "UPDATE `products` set `checkbox` ='1' where id='".$id."'";
		$z = mysqli_query($con, $sqlQuery);
			if($z == TRUE){
			echo "1";
			}
			else{
			echo("Error description: " . mysqli_error($con));
			}
		}
		else{
			$sqlQuery = "UPDATE `products` set `checkbox` ='0' where id='".$id."'";
		$z = mysqli_query($con, $sqlQuery);
			if($z == TRUE){
			echo "2";
			}
			else{
			echo("Error description: " . mysqli_error($con));
			}
		}

	?>