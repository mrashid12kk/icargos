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
	$data_msg = '<table class="table_box"><thead><tr><th>Track No#</th><th>Vendor Code</th><th>Vendor Tracking No</th><th>Message</th></tr></thead><tbody class="response_table_body">';
	foreach ($getsheet as $row) {
		if (isset($row[0]) && !empty($row[0])) {
			$track_no=$row[0];
			$vendor_code=$row[1];
			$vendor_track_no=$row[2];
			if (isset($track_no) && isset($vendor_code) && isset($vendor_track_no) && !empty($track_no) && !empty($vendor_code) && !empty($vendor_track_no)) {
				$vendor_name=mysqli_fetch_assoc(mysqli_query($con,"SELECT id,name FROM vendors WHERE vendor_code='".$vendor_code."'"));
				if (isset($vendor_name['id']) && isset($vendor_name['name'])) {
					$vendor_check=mysqli_fetch_assoc(mysqli_query($con,"SELECT vendor_track_no,vendor_id FROM orders WHERE track_no='".$track_no."'"));
					if (!isset($vendor_check['vendor_track_no']) && !isset($vendor_check['api_id'])) {
						$vendor_track_no_check=mysqli_fetch_assoc(mysqli_query($con,"SELECT track_no,vendor_track_no,vendor_id FROM orders WHERE vendor_track_no='".$vendor_track_no."'"));
						if (!isset($vendor_track_no_check['vendor_track_no']) && !isset($vendor_track_no_check['vendor_id'])) {
							$query = "UPDATE orders SET vendor_track_no='".$vendor_track_no."',vendor_id=".$vendor_name['id']." WHERE track_no='".$track_no."'";
							$query1=mysqli_query($con,$query) or die(mysqli_error($con));
							$rowscount=mysqli_affected_rows($con);
							if($rowscount>0){
								$data_msg.='<tr><td>'.$track_no.'</td><td>'.$vendor_code.'</td><td>'.$vendor_track_no.'</td><td><div class="alert alert-success">Updated</div></td></tr>';
							}
							else{
								$data_msg.='<tr><td>'.$track_no.'</td><td>'.$vendor_code.'</td><td>'.$vendor_track_no.'</td><td><div class="alert alert-danger">Not Update</div></td></tr>';
							}
						}
						else{
							$data_msg.='<tr><td>'.$track_no.'</td><td>'.$vendor_code.'</td><td>'.$vendor_track_no.'</td><td><div class="alert alert-danger">This Vendor Track No '.$vendor_track_no.' Is Also Assign To This Track No '.$vendor_track_no_check['track_no'].'</div></td></tr>';
						}
					}
					else{
						$vendor_name=mysqli_fetch_assoc(mysqli_query($con,"SELECT name FROM vendors WHERE id=".$vendor_check['vendor_id'].""));
						$data_msg.='<tr><td>'.$track_no.'</td><td>'.$vendor_code.'</td><td>'.$vendor_track_no.'</td><td><div class="alert alert-danger">This Parcel is alrady booked on '.$vendor_code.' with Track no '.$vendor_check['vendor_track_no'].' .</div></td></tr>';
					}
				}
				else{
					$data_msg.='<tr><td>'.$track_no.'</td><td>'.$vendor_code.'</td><td>'.$vendor_track_no.'</td><td><div class="alert alert-danger">No Vendor Find Against This Name</div></td></tr>';
				}
			}
			else{
				$data_msg.='<tr><td>'.$track_no.'</td><td>'.$vendor_code.'</td><td>'.$vendor_track_no.'</td><td><div class="alert alert-danger">The Data is missing From these Field</div></td></tr>';
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