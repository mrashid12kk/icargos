<?php

require 'includes/conn.php';
if (isset($_POST['payment_ids']) && $_POST['payment_ids'] != '') {
	$ids_array = explode(',',$_POST['payment_ids']);
	$paid_ids = '';
	$unpaid_ids = '';
	foreach ($ids_array as $key => $ledger_id) {
		$fetch= mysqli_fetch_assoc(mysqli_query($con,"SELECT * from customer_ledger_payments WHERE id=$ledger_id"));
		$invoice_no = isset($fetch['reference_no']) ? $fetch['reference_no'] : 0;
		$total_payable = isset($fetch['total_payable']) ? $fetch['total_payable'] : 0;
		$total_paid = isset($fetch['total_paid']) ? $fetch['total_paid'] : 0;
		$customer_id = isset($fetch['customer_id']) ? $fetch['customer_id'] : 0;
		$status = isset($fetch['status']) ? $fetch['status'] : 0;
		$detail_payment = $total_payable - $total_paid;
		$update_payment = $detail_payment + $total_paid;
		$last_id_q = mysqli_query($con, "SELECT max(id) as id from customer_ledger_payments_detail");
		$lastIdRes = mysqli_fetch_assoc($last_id_q);
		$lastId = isset($lastIdRes['id']) ? $lastIdRes['id'] : 0;
		$nextId = $lastId + 1;
		$reference_no = 'PI-' . $nextId;
		$payment_date =date('Y-m-d');
		$date =date('Y-m-d');
		
		if($status==0){
			$paid_ids .= $invoice_no.',';
			$sql="INSERT INTO `customer_ledger_payments_detail`(`customer_payment_id`, `amount`, `transaction_id`, `invoice_no`, `user_id`, `customer_id`, `payment_date`, `created_no`) VALUES (".$ledger_id.",".$detail_payment.",'".$reference_no."','".$invoice_no."',".$_SESSION['users_id'].",'".$customer_id."','".$payment_date."','".$date."')";
			$query=mysqli_query($con,$sql);
			$rowcount=mysqli_affected_rows($con);
			if ($rowcount > 0) {
				$update_sql="UPDATE `customer_ledger_payments` SET `total_paid`=".$update_payment." ,  status= 1  WHERE id=".$ledger_id;
				$query=mysqli_query($con,$update_sql);
			}
		}else{
			$unpaid_ids .= $invoice_no.',';
		}

		
	}
	$paid_ids = rtrim($paid_ids,',');
	$unpaid_ids = rtrim($unpaid_ids,',');
	$_SESSION['update_class']="success";
	$_SESSION['update_message']="Payment for the transactions (".$paid_ids.") made successfully!";
	$_SESSION['update_title']="WelDone!";
}else{
	$_SESSION['update_class']="danger";
	$_SESSION['update_message']="Please select transactions for payment!";
	$_SESSION['update_title']="Error!";
}
// die;
header("Location: ledger_payments.php");