<?php

require 'includes/conn.php';
if (isset($_POST['order_id']) && $_POST['order_id'] != '') {
	$invoice_ids = explode(',', $_POST['order_id']);
	$output_meezan = '';
	$output_others = '';
	$meezan_ids = '';
	$other_ids = '';
	foreach ($invoice_ids as $key => $invoice_id) {
		$query = "SELECT * FROM customer_ledger_payments Where id = $invoice_id";
		$result = mysqli_query($con, $query);
		if (mysqli_num_rows($result) > 0) {

			$fetchLedger = mysqli_fetch_assoc($result);

			$id = isset($fetchLedger['id']) ? $fetchLedger['id'] : '';
			$customer_id = isset($fetchLedger['customer_id']) ? $fetchLedger['customer_id'] : '';
			$total_payable = isset($fetchLedger['total_payable']) ? $fetchLedger['total_payable'] : '';
			$reference_no = isset($fetchLedger['reference_no']) ? $fetchLedger['reference_no'] : '';
			$customerSql = mysqli_query($con, "SELECT * from customers where id = $customer_id");

			$customerRes = mysqli_fetch_assoc($customerSql);

			$bank = isset($customerRes['bank_name']) ? $customerRes['bank_name'] : '';
			$bank_ac_no = isset($customerRes['bank_ac_no']) ? $customerRes['bank_ac_no'] : '';
			$acc_title = isset($customerRes['acc_title']) ? $customerRes['acc_title'] : '';
			$bankCdeSql = mysqli_query($con, "SELECT * from bank_detail where id = $bank");
			$bankRes = mysqli_fetch_assoc($bankCdeSql);
			$bank_code = isset($bankRes['bank_code']) ? $bankRes['bank_code'] : '';

			if (isset($bank) && $bank == 31) {
				$meezan_ids .= $id . ',';
			} else {
				$other_ids .= $id . ',';
			}
		}
	}
}else{
	header("Location: ledger_payments.php");
	exit;
}
if (isset($meezan_ids) and !empty($meezan_ids)) {
	$meezan_ids = rtrim($meezan_ids, ',');

	$allMeezanQuery = mysqli_query($con, "SELECT * from customer_ledger_payments WHERE id IN ($meezan_ids)");

	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=other_bank_customers.csv');
	$output1 = fopen("php://output", "w");
	fputcsv($output1, array('ACCOUNT NUMBER', 'BENEFICAIRY NAME', 'CUSTOMER REFERENCE NUMBER', 'TRANSFER AMOUNT'));
	while ($row = mysqli_fetch_array($allMeezanQuery)) {
		$customer_id = isset($row['customer_id']) ? $row['customer_id'] : '';
		$total_payable = isset($row['total_payable']) ? $row['total_payable'] : '';
		$reference_no = isset($row['reference_no']) ? $row['reference_no'] : '';
		$customerSql = mysqli_query($con, "SELECT * from customers where id = $customer_id");
		$account_no = "'" . $customerRes['bank_ac_no'] . "'";
		$customerRes = mysqli_fetch_assoc($customerSql);
		$bank_ac_no = isset($customerRes['bank_ac_no']) ? "'" . (string)$customerRes['bank_ac_no'] . "'" : '';
		$acc_title = isset($customerRes['acc_title']) ? $customerRes['acc_title'] : '';
		$bankCdeSql = mysqli_query($con, "SELECT * from bank_detail where id = $bank");
		$bankRes = mysqli_fetch_assoc($bankCdeSql);
		$bank_code = isset($bankRes['bank_code']) ? $bankRes['bank_code'] : '';

		$put_array = array($bank_ac_no, $acc_title, $reference_no, $total_payable);
		fputcsv($output1, $put_array);
	}

	fclose($output1);
}
if (isset($other_ids) and !empty($other_ids)) {
	$other_ids = rtrim($other_ids, ',');

	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=other_bank_customers.csv');
	$output = fopen("php://output", "w");
	fputcsv($output, array('ACCOUNT NUMBER', 'BENEFICAIRY NAME', 'CUSTOMER REFERENCE NUMBER', 'TRANSFER AMOUNT', 'BANK CODE'));
	$otherbanksq = mysqli_query($con, "SELECT * from customer_ledger_payments WHERE id IN ($other_ids)");
	while ($row = mysqli_fetch_array($otherbanksq)) {
		$customer_id = isset($row['customer_id']) ? $row['customer_id'] : '';
		$total_payable = isset($row['total_payable']) ? $row['total_payable'] : '';
		$reference_no = isset($row['reference_no']) ? $row['reference_no'] : '';
		$customerSql = mysqli_query($con, "SELECT * from customers where id = $customer_id");
		$account_no = "'" . $customerRes['bank_ac_no'] . "'";
		$customerRes = mysqli_fetch_assoc($customerSql);
		$bank_ac_no = isset($customerRes['bank_ac_no']) ? "'" . (string)$customerRes['bank_ac_no'] . "'" : '';
		$acc_title = isset($customerRes['acc_title']) ? $customerRes['acc_title'] : '';
		$bankCdeSql = mysqli_query($con, "SELECT * from bank_detail where id = $bank");
		$bankRes = mysqli_fetch_assoc($bankCdeSql);
		$bank_code = isset($bankRes['bank_code']) ? $bankRes['bank_code'] : '';

		$put_array = array($bank_ac_no, $acc_title, $reference_no, $total_payable, $bank_code);
		fputcsv($output, $put_array);
	}

	fclose($output);
}
