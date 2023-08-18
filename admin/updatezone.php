<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
date_default_timezone_set("Asia/Karachi");

require 'includes/conn.php';
$msg='';
if(isset($_POST['updatedzone'])){
	$zone_id = $_POST['zone_id'];
	$zone = $_POST['zone'];
	$product_id = $_POST['product_id'];
	$service_type = $_POST['service_type'];
	$point_5_kg = isset($_POST['point_5_kg']) ? $_POST['point_5_kg']:0;
	$upto_1_kg = isset($_POST['upto_1_kg']) ? $_POST['upto_1_kg']:0;
	$upto_3_kg = isset($_POST['upto_3_kg']) ? $_POST['upto_3_kg']:0;
	$upto_10_kg = isset($_POST['upto_10_kg']) ? $_POST['upto_10_kg']:0;
	$other_kg = isset($_POST['other_kg']) ? $_POST['other_kg']:0;
	$additional_point_5_kg = isset($_POST['additional_point_5_kg']) ? $_POST['additional_point_5_kg']:0;
	$addition_kg_type = isset($_POST['addition_kg_type']) ? $_POST['addition_kg_type']:'';
	mysqli_query($con,"UPDATE zone SET `zone`='".$zone."', `service_type`='".$service_type."', `product_id`='".$product_id."',`point_5_kg` ='".$point_5_kg."',`upto_1_kg`='".$upto_1_kg."',`upto_3_kg`='".$upto_3_kg."',`upto_10_kg`='".$upto_10_kg."',`other_kg`='".$other_kg."',`additional_point_5_kg`='".$additional_point_5_kg."',`addition_kg_type`='".$addition_kg_type."' WHERE id='".$zone_id."' ");
	mysqli_query($con,"DELETE FROM zone_cities WHERE `zone`='".$zone_id."' ");
	if(!empty($_POST['pricing'])){
		foreach($_POST['pricing'] as $row){
			$origin = $row['city_form'];
			$destination = $row['city_to'];
			mysqli_query($con," INSERT INTO zone_cities(`zone`,`origin`,`destination`) VALUES('".$zone_id."','".$origin."','".$destination."') ");
		}
	}
	 $rowscount=mysqli_affected_rows($con);
    if($rowscount > 0){
        $msg= '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you Update a new ZONE successfully</div>';
        }else{
        $msg= '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not Update a new ZONE unsuccessfully.</div>';
      }

      $_SESSION['zone_msg']=$msg;
	header("Location:".$_SERVER['HTTP_REFERER']);
}

	?>
