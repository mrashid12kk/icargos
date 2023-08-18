<?php
session_start();
require 'includes/conn.php';

if(!isset($_SESSION['users_id'])) {
  header('Location: '.$_SERVER['HTTP_REFERER']);
  exit();
}

function insertToDB($connection, $table, $data) {
	foreach ($data as &$value) {
		if(!is_numeric($value))
			$value = "'".$value."'";
	}
	$sql = "INSERT INTO {$table}(`".implode("`,`", array_keys($data))."`) VALUES(".implode(",", $data).")";
	return mysqli_query($connection, $sql);
}
function insertToLedgerTable($connection, $table, $data) {
  // echo $table;
  $customer_id =  (isset($data['customer_id']) && $data['customer_id']) ? $data['customer_id']:'';
  $total_pickup_comm =  (isset($data['total_pickup_comm']) && $data['total_pickup_comm']) ? $data['total_pickup_comm']:0;
  $total_delivery_comm =  (isset($data['total_delivery_comm']) && $data['total_delivery_comm']) ? $data['total_delivery_comm']:0;
  $payment_date =  (isset($data['payment_date']) && $data['payment_date']) ? $data['payment_date']:'';
  $reference_no =  (isset($data['reference_no']) && $data['reference_no']) ? $data['reference_no']:'';
  $total_paid =  (isset($data['total_paid']) && $data['total_paid']) ? $data['total_paid']:0;
  $total_payable =  (isset($data['total_payable']) && $data['total_payable']) ? $data['total_payable']:0;

  $ledger_orders =  (isset($data['ledger_orders']) && $data['ledger_orders']) ? $data['ledger_orders']:'';
  $ledger_pickup =  (isset($data['ledger_pickup']) && $data['ledger_pickup']) ? $data['ledger_pickup']:'';
  $ledger_delivered =  (isset($data['ledger_delivered']) && $data['ledger_delivered']) ? $data['ledger_delivered']:'';
  $prev_balance =  isset($data['prev_balance']) ? $data['prev_balance']:0;
  $total_addition =  isset($data['total_addition']) ? $data['total_addition']:0;
  $total_deduction =  isset($data['total_deduction']) ? $data['total_deduction']:0;

  $sql = "INSERT INTO `employee_ledger_payments`(`customer_id`,`total_pickup_comm`,`total_delivery_comm`,`payment_date`,`reference_no`,`total_paid`,`total_payable`,`ledger_orders`,`ledger_pickup`,`ledger_delivered`,`prev_balance`,`total_addition`,`total_deduction`) VALUES (".$customer_id.",".$total_pickup_comm.",".$total_delivery_comm.",'".$payment_date."','".$reference_no."',".$total_paid.",".$total_payable.",'".$ledger_orders."','".$ledger_pickup."','".$ledger_delivered."',".$prev_balance.",".$total_addition.",".$total_deduction.")";
  return mysqli_query($connection, $sql);
}


if(isset($_POST['submit'])) {
  $delivered_orders = (isset($_POST['delivery_orders']) && !empty($_POST['delivery_orders'])) ? array_keys($_POST['delivery_orders']) : [];
  $pickup_orders = (isset($_POST['pickup_orders']) && !empty($_POST['pickup_orders'])) ? array_keys($_POST['pickup_orders']) : [];
  $orders = array_merge($delivered_orders, $pickup_orders);
  $delivered_orders = implode(',', $delivered_orders);
  $pickup_orders = implode(',', $pickup_orders);
  $orders = implode(',', $orders);
 if(empty($pickup_orders)){
    $pickup_orders = null;
 }
if(empty($delivered_orders)){
    $delivered_orders = null; 
 }
  $insert_data = [
    'customer_id' => isset($_POST['customer_id']) ? $_POST['customer_id']:'',
    'total_pickup_comm' => (isset($_POST['total_pickup_comm']) && $_POST['total_pickup_comm']) ? $_POST['total_pickup_comm'] :0,
    'total_delivery_comm' => (isset($_POST['total_delivery_comm']) && $_POST['total_delivery_comm']) ? $_POST['total_delivery_comm']:0,
    'payment_date' => isset($_POST['date']) ? date('Y-m-d', strtotime($_POST['date'])):'',
    'reference_no' => isset($_POST['reference_no']) ? $_POST['reference_no']:'',
    'total_paid' => (isset($_POST['total_payment']) && $_POST['total_payment']) ? $_POST['total_payment']:0,
    'total_payable' => (isset($_POST['total_payable']) && $_POST['total_payable']) ? $_POST['total_payable']:0,
    'ledger_orders' =>  $orders,
    'ledger_pickup' =>  $pickup_orders,
    'ledger_delivered' =>  $delivered_orders,
    'prev_balance' => (isset($_POST['prev_balance']) && $_POST['prev_balance']) ? $_POST['prev_balance']:0,
    'total_addition' => (isset($_POST['total_addition']) && $_POST['total_addition']) ? $_POST['total_addition']:0,
    'total_deduction' => (isset($_POST['total_deduction']) && $_POST['total_deduction']) ? $_POST['total_deduction']:0,
  ];
  if(insertToLedgerTable($con, 'employee_ledger_payments', $insert_data)) {
    $id = mysqli_insert_id($con);
      mysqli_query($con, "UPDATE employee_ledger_payments SET update_able = 0 WHERE customer_id = ".$insert_data['customer_id']);
      mysqli_query($con, "UPDATE employee_ledger_payments SET update_able = 1 WHERE id = ".$id);
    if($orders) {
      mysqli_query($con, "UPDATE orders SET employee_payment_status = 'Paid',employee_payment_id = $id WHERE id IN (".$orders.")");
    }
    $_SESSION['success']="Payment created successfully";
    header('Location:employee_payments.php');
  } else  {
    $_SESSION['errors']="Error!";
    header('Location:'.$_SERVER['HTTP_REFERER']);
  }
  exit();
}
else if(isset($_GET['delete']) && isset($_GET['customer_id']) && $_GET['delete'] != "")
{
  $customer_id = $_GET['customer_id'];
  $order = mysqli_query($con, "SELECT * FROM employee_ledger_payments WHERE id = ".$_GET['delete']);
  $order = mysqli_fetch_object($order);
  if($order) {
    if(isset($order->id) && $order->id != '') {
      mysqli_query($con, "UPDATE orders SET employee_payment_status = 'Pending',employee_payment_id = 0 WHERE employee_payment_id = ".$order->id." ");
    }
    mysqli_query($con, "DELETE FROM employee_ledger_payments WHERE id = ".$order->id);
    mysqli_query($con, "UPDATE employee_ledger_payments SET update_able = 1 WHERE customer_id = ".$customer_id." ORDER BY id DESC LIMIT 1");
  }
  $_SESSION['success']="Delete successfully";
  header('Location: '.$_SERVER['HTTP_REFERER']);
  exit();
}
  
  