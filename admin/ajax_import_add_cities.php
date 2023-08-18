<?php

require 'includes/conn.php';
include "admin/includes/sms_helper.php";
//  ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
if (isset($_POST['delete'])) {
	$file = $_POST['remanefile'];
	if (file_exists('excel/' . $file)) {
		unlink('excel/' . $file);
		echo 'Successfully';
	} else {
		echo 'UnSuccessfully';
	}
}
if (isset($_FILES) && !empty($_FILES)) {
	if ($_FILES['file']['error'] > 0) {
		echo 'Error: ' . $_FILES['file']['error'] . '<br>';
	} else {
		$extension  = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
		$target_file = uniqid() . basename($_FILES["file"]["name"]);
		$basename   = $target_file;
		$source       = $_FILES["file"]["tmp_name"];
		$destination  = "assets/excel/" . $basename;
		if (move_uploaded_file($source, $destination)) {
			$data['msg'] = "<div class='alert alert-success'>File Uploaded Successfully (Please Wait The Data will updated)</div>";
			$data['filename'] = $target_file;
			echo json_encode($data);
		} else {
			$data['percentage'] = '0';
			echo json_encode($data);
		}
	}
}

if (isset($_POST['update_cities_excel']) && !empty($_POST['update_cities_excel'])) {
	mysqli_query($con,"TRUNCATE TABLE cities_excel");
	require_once('../PHPExcel/Classes/PHPExcel/IOFactory.php');
	$file_name_org = $_POST['file_name_org'];
	$objPHPExcel = PHPExcel_IOFactory::load('assets/excel/' . $file_name_org);
	$getsheet = $objPHPExcel->getActiveSheet()->toArray(null);
	$order_ids = array();
	$date = date('Y-m-d H:i:s');
	//check if all data is correct
	unset($getsheet[0]);
	foreach ($getsheet as $row) {
		if (isset($row[1]) && !empty($row[1])) {
			$country=$row[1];
			$state=$row[2];
			$city_name=$row[3];
			$stn_code=$row[4];
			$area_code=$row[5];
			$zone_type=$row[6];
			$insert_q="INSERT INTO `cities_excel`( `country_id`,`state_id`,`city_name`,`stn_code`,`area_code`,`zone_type_id`) VALUES ('$country','$state','$city_name','$stn_code','$area_code','$zone_type')";
			$query=mysqli_query($con,$insert_q);
		}
	}
	$query=mysqli_query($con,"SELECT COUNT(*) as allcount FROM `cities_excel`");
	$records = mysqli_fetch_assoc($query);
	$totalRecords = $records['allcount'];
	$row=$totalRecords/10;
	echo ceil($row);
	exit();
}
if (isset($_POST['update_cities']) && !empty($_POST['update_cities'])) {
	$limit='';
	$response=false;
	if (isset($_POST['limit_no']) && !empty($_POST['limit_no'])) {
		$no=$_POST['limit_no'] * 10;
		$start=($no-10) +1;
		$end=$no;
		$limit="limit ".$start.",".$end."";
	}
	$date = date('Y-m-d H:i:s');
	$city_data_query=mysqli_query($con,"SELECT * FROM cities_excel ORDER BY id ASC ".$limit."");
	foreach ($city_data_query as $key=> $row) {
		if (isset($row['city_name']) && !empty($row['city_name'])) {
			$country=$row['country_id'];
			$state=$row['state_id'];
			$city_name=$row['city_name'];
			$stn_code=$row['stn_code'];
			$area_code=$row['area_code'];
			$zone_type=$row['zone_type_id'];
			$country_check=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM country WHERE country_name='".$country."'"));
			if (isset($country_check['id']) && !empty($country_check['id'])) {
				$country_id=$country_check['id'];
			}
			else{
				$insert_q="INSERT INTO `country`( `country_name`,`created_on`) VALUES ('$country','.$date')";
				$query=mysqli_query($con,$insert_q);
				$country_id = mysqli_insert_id($con);
			}
			$state_check=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM state WHERE state_name='$state' AND country_id='$country_id'"));
			if (isset($state_check['id']) && !empty($state_check['id'])) {
				$state_id=$state_check['id'];
			}
			else{
				$insert_q="INSERT INTO `state`( `state_name`, `country_id`,`created_on`) VALUES ('$state','$country_id','$date')";
				$query=mysqli_query($con,$insert_q);
				$state_id = mysqli_insert_id($con);
			}
			$zone_check=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM zone_type WHERE zone_name='$zone_type'"));
			if (isset($zone_check['id']) && !empty($zone_check['id'])) {
				$zone_type_id=$zone_check['id'];
			}
			else{
				$insert_q="INSERT INTO `zone_type`( `zone_name`) VALUES ('$zone_type')";
				$query=mysqli_query($con,$insert_q);
				$zone_type_id = mysqli_insert_id($con);
			}
			$city_check=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM cities WHERE city_name='$city_name' AND country_id='$country_id' AND state_id='$state_id'"));
			if (isset($city_check['id']) && !empty($city_check['id'])) {
			}
			else{
				$insert_q= "INSERT INTO `cities`(`zone_type_id`,`country_id`,`state_id`,`stn_code`,`city_name`,`area_code`,`title`) VALUES ('$zone_type_id','$country_id','$state_id','$stn_code','$city_name','$area_code','$city_name')";
				$query=mysqli_query($con,$insert_q);
			}
		}
	}
	$_SESSION['upload_msg']='<div class="row"><div class="alert alert-success">Cities Uploaded Successfully</div></div>';
	echo true;
	exit();
}