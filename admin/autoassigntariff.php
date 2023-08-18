<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
session_start();
// date_default_timezone_set("Asia/Karachi");

require 'includes/conn.php';
if(isset($_GET['customer_id']))
{
	$customer_id  =  $_GET['customer_id'];
	$cus_pro_ids = '';
	$pay_mode_ids = '';
	$customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $customer_id . " ");
   $customer_data = mysqli_fetch_array($customer_query);
   $customer_city = $customer_data['city'];
   $customer_type = $customer_data['customer_type'];
   $customerPaySql = "SELECT * FROM pay_mode WHERE account_type = '".$customer_type."'";
       
       $c_pay_mode_q = mysqli_query($con,$customerPaySql);
       $paymodeRes = mysqli_fetch_assoc($c_pay_mode_q);
       $customerPayMode = isset($paymodeRes['pay_mode']) ? $paymodeRes['pay_mode'] : '';
       $customerPayModeId = isset($paymodeRes['id']) ? $paymodeRes['id'] : '';
	   $payModeSql =  "SELECT * FROM `tariff` Where pay_mode =$customerPayModeId ORDER BY id DESC";
		$pay_modeResult = mysqli_query($con, $payModeSql);

		while ($p_row = mysqli_fetch_array($pay_modeResult)) {
            $pay_mode_ids .= $p_row['id'].',';
        }
        $pay_mode_ids = rtrim($pay_mode_ids,',');
		
		$product_sql =  "SELECT * FROM `tariff_detail` Where tariff_id IN(".$pay_mode_ids.") ORDER BY id ASC";
		$product_result = mysqli_query($con, $product_sql);
		mysqli_query($con, "DELETE FROM customer_tariff_detail WHERE  customer_id =  ".$customer_id);
	while ($row = mysqli_fetch_assoc($product_result)) 
	{
		$tariff_id =isset($row['tariff_id']) ? $row['tariff_id'] :'';
		
		$start_range = isset($row['start_range']) ? $row['start_range'] : '';
		$end_range = isset($row['end_range']) ? $row['end_range'] :'';
		$rate = isset($row['rate']) ? $row['rate'] : 0;

		mysqli_query($con, " INSERT INTO `customer_tariff_detail`(`tariff_id`, `customer_id`, `start_range`, `end_range`,`rate`) VALUES (" . $tariff_id . "," . $customer_id . ",'" . $start_range . "','" . $end_range . "','" . $rate . "') ");

	} 
	header("Location:customer_detail.php?customer_id=".$customer_id);
}