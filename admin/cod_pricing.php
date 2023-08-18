<?php 
	session_start();
	require 'includes/conn.php';
	if(isset($_POST['submit_cod'])){
		$data = $_POST;
		unset($data['submit_cod']);
		$pricing = $data['prices'];
		foreach($pricing as $row){
			  $price_query = mysqli_query($con," UPDATE cod_pricing SET city_to ='".$row['city_to']."',point_5_kg ='".$row['point_5_kg']."',upto_1_kg ='".$row['upto_1_kg']."', upto_2_kg ='".$row['upto_2_kg']."',other_kg ='".$row['other_kg']."' WHERE city_to='".$row['city_to']."' ");
		}
		header("Location: ".$_SERVER['HTTP_REFERER']);
	}

	if(isset($_POST['submit_overlong'])){
		$data = $_POST;
		unset($data['submit_overlong']);
		$pricing = $data['prices'];
		foreach($pricing as $row){
			  $price_query = mysqli_query($con," UPDATE overlong_pricing SET city_to ='".$row['city_to']."',price_per_kg ='".$row['price_per_kg']."' WHERE city_to='".$row['city_to']."' ");
		}
		header("Location: ".$_SERVER['HTTP_REFERER']);
	}

	if(isset($_POST['customer_overlong'])){
		$data = $_POST;
		unset($data['customer_overlong']);
		$pricing = $data['prices'];
		$customer_id = $_POST['customer_id'];
		foreach($pricing as $row){
			$overland_price_id = $row['overland_price_id'];
			if(isset($overland_price_id) && !empty($overland_price_id)){
					$price_query = mysqli_query($con," UPDATE overlong_pricing SET city_to ='".$row['city_to']."',price_per_kg ='".$row['price_per_kg']."' WHERE overland_price_id='".$overland_price_id."' AND customer_id=".$customer_id." ");
			}else{
				
				mysqli_query($con,"INSERT INTO customer_overlong_pricing(`city_to`,`price_per_kg`,`customer_id`) VALUES('".$row['city_to']."','".$row['price_per_kg']."','".$customer_id."') ");
			  
			}
		}
		header("Location: ".$_SERVER['HTTP_REFERER']);
	}
?>