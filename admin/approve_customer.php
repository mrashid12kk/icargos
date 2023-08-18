<?php
session_start();
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require 'includes/conn.php';
if (isset($_GET['id']) && !empty($_GET['id'])) {
	function addQuote($value)
	{
		return (!is_string($value) == true) ? $value : "'" . $value . "'";
	}
	function getLedgerCode()
	{
		global $con;
		$ledgerCode = '';
		$getMaximum_query = "SELECT MAX(ledgerCode) AS ledgerCode FROM tbl_accountledger";
		$getMaximum = mysqli_query($con, $getMaximum_query) or die(mysqli_error($con));
		$maximumnumber =  mysqli_fetch_row($getMaximum);
		$maximumnumber = isset($maximumnumber[0]) ? $maximumnumber[0] : '';
		if (!$maximumnumber) {
			$maximumnumber = 20;
		}
		$maximumnumber = $maximumnumber + 1;
		$ledgerCode = $maximumnumber;
		return $ledgerCode;
	}
	$customer_id = $_GET['id'];
	$customer = mysqli_query($con, "SELECT * FROM customers WHERE id = " . $customer_id);
	$customer = mysqli_fetch_object($customer);
	$customer_type = $customer->customer_type;
	if ($customer && $customer->id) {
		$customerPassword = "Your chosen password.";
		if (isset($customer->pass) && !empty($customer->pass)) {
			$customerPassword = $customer->pass;
		}
		$data['email'] = trim($customer->email);
		$message['subject'] = 'Account Approval';
		$message['body'] = '<p>Your account has been approved</p>';
		$message['body'] .= '<p>Please click below link to login your account.</p>';
		$message['body'] .= 'Login: <a href="' . BASE_URL . 'index.php">' . BASE_URL . '</a>';
		$message['body'] .= '<p>Your username is: ' . $customer->email . '.</p>';
		$message['body'] .= '<p>Your password is: ' . $customerPassword . '.</p>';
		require_once 'includes/functions.php';
		sendEmail($data, $message);
		$code = 1000 + $customer_id;
		mysqli_query($con, "UPDATE customers SET status=1,client_code = '" . $code . "' WHERE id =" . $customer_id . " ");
		mysqli_query($con, "UPDATE tbl_accountledger set status = '1' where customer_id = '".$customer_id."'");
		// $accountLedgerData = [];
		// $accountLedgerData['ledgerName'] = isset($customer->fname) ? $customer->fname : '';
		// $accountLedgerData['ledgerName'] .= isset($customer->client_code) ? ' (' . $customer->client_code . ')' : '';
		// $accountLedgerData['account_type']=$data['account_type'];
		// $accountLedgerData['company_id'] = 1;
		// $accountLedgerData['accountGroupId'] = 26;
		// $accountLedgerData['mobile'] = isset($customer->mobile_no) ? $customer->mobile_no : '';
		// $accountLedgerData['address'] = isset($customer->address) ? $customer->address : '';
		// $accountLedgerData['ledgerCode'] = getLedgerCode();
		// $accountLedgerData['is_external'] = $customer_id;
		// $accountLedgerData['email'] = isset($customer->email) ? $customer->email : '';
		// $creditor_data = $accountLedgerData;
		// foreach ($accountLedgerData as $j => &$row) {
		// 	$row = addQuote($row);
		// }
		// $keyss = implode(", ", array_keys($accountLedgerData));
		// $valuess = implode(",", $accountLedgerData);
		// $ledger_sql = "INSERT INTO tbl_accountledger ($keyss) VALUES($valuess)";
		// $ledger_sql_query = mysqli_query($con, $ledger_sql) or die(mysqli_error($con));
		// $ledger_id = mysqli_insert_id($con);
		// $post = [
		// 	"ledger_id" => $ledger_id,
		// 	"customer_id" => $customer_id,
		// 	"account_group_id" => $accountLedgerData['accountGroupId'],
		// ];
		// $url = 'https://pla.a.eloerp.net/accounting/chart_account_api/generateChartOfAccount';
		// $ch = curl_init();
		// curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json;text/html;charset=utf-8'));
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// curl_setopt($ch, CURLOPT_URL, $url);
		// curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
		// $all = curl_exec($ch);
		// curl_close($ch);
		// $res = json_decode($all);

		// if ($customer_type != 2) {
		// 	$creditor_data['accountGroupId'] = 22;
		// 	$creditor_data['ledgerCode'] = getLedgerCode();
		// 	foreach ($creditor_data as $j => &$row) {
		// 		$row = addQuote($row);
		// 	}
		// 	$keyss = implode(", ", array_keys($creditor_data));
		// 	$valuess = implode(",", $creditor_data);
		// 	$ledger_sql = "INSERT INTO tbl_accountledger ($keyss) VALUES($valuess)";
		// 	$ledger_sql_query = mysqli_query($con, $ledger_sql) or die(mysqli_error($con));
		// 	$ledger_id = mysqli_insert_id($con);
		// 	$post = [
		// 		"ledger_id" => $ledger_id,
		// 		"customer_id" => $customer_id,
		// 		"account_group_id" => $creditor_data['accountGroupId'],
		// 	];
		// 	$url = 'https://pla.a.eloerp.net/accounting/chart_account_api/generateChartOfAccount';
		// 	$ch = curl_init();
		// 	curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		// 	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
		// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// 	curl_setopt($ch, CURLOPT_URL, $url);
		// 	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
		// 	$all = curl_exec($ch);
		// 	curl_close($ch);
		// 	$res = json_decode($all);
		// }
	}
	header("Location:businessacc.php");
}