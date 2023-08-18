<?php 
	session_start();
	require 'includes/conn.php';
	$customer_id = $_POST['customer_id'];
	if(isset($_POST['submit_cod'])){
		$data = $_POST;
		unset($data['submit_cod']);
		$pricing = $data['prices'];
		foreach($pricing as $row){
			$price_id = $row['price_id'];

			if(isset($price_id) && !empty($price_id)){

			  $price_query = mysqli_query($con," UPDATE customer_cod_pricing SET city_to ='".$row['city_to']."',point_5_kg ='".$row['point_5_kg']."',upto_1_kg ='".$row['upto_1_kg']."', upto_2_kg ='".$row['upto_2_kg']."',other_kg ='".$row['other_kg']."'  WHERE price_id=".$price_id." AND customer_id=".$customer_id." ");
			}else{

				mysqli_query($con,"INSERT INTO customer_cod_pricing(`city_to`,`point_5_kg`,`upto_1_kg`,`upto_2_kg`,`other_kg`,`customer_id`) VALUES('".$row['city_to']."','".$row['point_5_kg']."','".$row['upto_1_kg']."','".$row['upto_2_kg']."','".$row['other_kg']."',".$customer_id.") ");
			}
		}
		header("Location: ".$_SERVER['HTTP_REFERER']);
	}

	
?>