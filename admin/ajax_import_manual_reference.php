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
if (isset($_POST['update_booking']) && !empty($_POST['update_booking'])) {
	require_once('../PHPExcel/Classes/PHPExcel/IOFactory.php');
	$file_name_org = $_POST['file_name_org'];
	$objPHPExcel = PHPExcel_IOFactory::load('assets/excel/' . $file_name_org);
	$getsheet = $objPHPExcel->getActiveSheet()->toArray(null);
	$order_ids = array();
	//check if all data is correct
	unset($getsheet[0]);
	$data_msg = '<table class="table_box"><thead><tr><th>Track No#</th><th>Reference No</th><th>Message</th></tr></thead><tbody class="response_table_body">';
	foreach ($getsheet as $row) {
		if (isset($row[0]) && !empty($row[0])) {
			$track_no=$row[0];
			$ref_no=$row[1];
			if (isset($track_no) && !empty($track_no)) {
							$query = "UPDATE orders SET ref_no=".$ref_no." WHERE track_no='".$track_no."'";
							$query1=mysqli_query($con,$query) or die(mysqli_error($con));
							$rowscount=mysqli_affected_rows($con);
							if($rowscount>0){
								$data_msg.='<tr><td>'.$track_no.'</td><td>'.$ref_no.'</td><td><div class="alert alert-success">Updated</div></td></tr>';
							}
							else{
								$data_msg.='<tr><td>'.$track_no.'</td><td>'.$vendor_code.'</td><td><div class="alert alert-danger">Not Update</div></td></tr>';
							}
						
					
				
				}
				
			
			else{
				$data_msg.='<tr><td>'.$track_no.'</td><td>'.$ref_no.'</td><td><div class="alert alert-danger">The Data is missing From these Field</div></td></tr>';
			}
		}
	}
	$data_msg.='</tbody></table>';
	$err_response = array();
	// $err_response['bulk_message'] = '<div class="alert alert-success">Data Updated Successfully</div>';
	$err_response['data_msg'] = $data_msg;
	echo json_encode($err_response);
	exit();
}