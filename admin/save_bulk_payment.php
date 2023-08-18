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



if(isset($_POST['save_bulk_pay'])) {
  $payments_records = $_POST['payments'];
  foreach($payments_records as $key=>$single_record)
  {


  $customer_id = $key;
  if(isset($single_record['is_checked']) && $single_record['is_checked'] == 'on'){
  $count_delivery = (int)$single_record['total_del_records'];
  $count_return = (int)$single_record['total_ret_records'];
  $count_flyers = (int)$single_record['count_flyer'];
  $count_shipments = $count_delivery + $count_return;

  
  $delivered_orders = $single_record['total_del_ids'];
  $returned_orders = $single_record['total_ret_ids'];
  $orders = $single_record['total_orders_ids'];
  if($orders) {
    mysqli_query($con, "UPDATE orders SET payment_status = 'Paid' WHERE id IN (".$orders.")");
  }
  $flyers = $single_record['flyer_ids_res'];
  if($flyers) {
    mysqli_query($con, "UPDATE flayer_order_index SET payment_status = 'Paid' WHERE id IN (".$flyers.")");
  }

  $insert_data = [
    'customer_id' => (int)$customer_id,
    'payment_date' => date('Y-m-d'),
    'reference_no' => '',
    'total_returned_fee' => 0,
    'cash_handling' => 0,
    'total_shipments' => $count_shipments,
    'total_delivered' => $count_delivery,
    'total_returned' => $count_return,
    'cod_amount' => (float)$single_record['total_cod'],
    'delivery_charges' => (float)$single_record['total_delivery'],
    'gst_amount' => (float)$single_record['total_gst'],
    'returned_amount' => (float)$single_record['total_return'],
    'total_paid' => (float)$single_record['total_paid'],
    'sell_flyers_amount' => (float)$single_record['total_flyer'],
    'total_payable' => (float)$single_record['total_payable'],
    'total_sell_flyers' => (int)$count_flyers,
    'ledger_orders' =>  $orders,
    'ledger_returned' =>  $returned_orders,
    'ledger_delivered' =>  $delivered_orders,
    'ledger_flyers' => $flyers,
    'prev_balance' => 0,
    'prev_balance_history' =>0,
  ];
  mysqli_query($con, "UPDATE customer_ledger_payments SET update_able = 0 WHERE customer_id = ".$insert_data['customer_id']);


  if(insertToDB($con, 'customer_ledger_payments', $insert_data)) {
    $id = mysqli_insert_id($con);


  } 
  }
}
  header('Location: ledger_payments.php');
} 
  
  