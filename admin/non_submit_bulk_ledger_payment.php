<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
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

function formated_value($value = 0)
{
  return str_replace(',','',$value);
}
function insertToLedgerTable($connection, $table, $data) {
  // echo $table;
  // echo '<pre>',print_r($data),'</pre>';
  $customer_id =  (isset($data['customer_id']) && $data['customer_id']) ? $data['customer_id']:'';
  $payment_date =  (isset($data['payment_date']) && $data['payment_date']) ? $data['payment_date']:'';
  $reference_no =  (isset($data['reference_no']) && $data['reference_no']) ? $data['reference_no']:'';
  $total_shipments =  (isset($data['total_shipments']) && $data['total_shipments']) ? $data['total_shipments']:0;
  $total_delivered =  (isset($data['total_delivered']) && $data['total_delivered']) ? $data['total_delivered']:0;
  $total_returned =  (isset($data['total_returned']) && $data['total_returned']) ? $data['total_returned']:0;
  $total_returned_fee =  (isset($data['total_returned_fee']) && $data['total_returned_fee']) ? $data['total_returned_fee']:0;
  $cod_amount =  (isset($data['cod_amount']) && $data['cod_amount']) ? $data['cod_amount']:0;
  $delivery_charges = (isset($data['delivery_charges']) && $data['delivery_charges']) ? $data['delivery_charges']:0;
  $gst_amount =  (isset($data['gst_amount']) && $data['gst_amount']) ? $data['gst_amount']:0;
  $returned_amount =  (isset($data['returned_amount']) && $data['returned_amount']) ? $data['returned_amount']:0;
  $total_paid =  (isset($data['total_paid']) && $data['total_paid']) ? $data['total_paid']:0;
  $sell_flyers_amount =  (isset($data['sell_flyers_amount']) && $data['sell_flyers_amount']) ? $data['sell_flyers_amount']:0;
  $total_payable =  (isset($data['total_payable']) && $data['total_payable']) ? $data['total_payable']:0;
  $total_sell_flyers =  (isset($data['total_sell_flyers']) && $data['total_sell_flyers']) ? $data['total_sell_flyers']:0;
  $ledger_orders =  (isset($data['ledger_orders']) && $data['ledger_orders']) ? $data['ledger_orders']:'';
  $ledger_returned =  (isset($data['ledger_returned']) && $data['ledger_returned']) ? $data['ledger_returned']:'';
  $ledger_delivered =  (isset($data['ledger_delivered']) && $data['ledger_delivered']) ? $data['ledger_delivered']:'';
  $ledger_flyers =  (isset($data['ledger_flyers']) && $data['ledger_flyers']) ? $data['ledger_flyers']:'';
  $cash_handling =  (isset($data['cash_handling']) && $data['cash_handling'] > 0) ? $data['cash_handling']:0;
  $prev_balance =  (isset($data['prev_balance']) && $data['prev_balance']) ? $data['prev_balance']:0;
  $total_extra_charges =  (isset($data['total_extra_charges']) && $data['total_extra_charges'] > 0) ? $data['total_extra_charges']:0;
  $total_charges =  (isset($data['total_charges']) && $data['total_charges']) ? $data['total_charges']:0;
  $fuel_surcharge =  (isset($data['fuel_surcharge']) && $data['fuel_surcharge']) ? $data['fuel_surcharge']:0;
  $net_amount =  (isset($data['net_amount']) && $data['net_amount']) ? $data['net_amount']:0;
  $total_insuredpremium_charges =  isset($data['total_insuredpremium_charges']) ? $data['total_insuredpremium_charges']:0;
  $prev_balance_history =  isset($data['prev_balance_history']) ? $data['prev_balance_history']:0;
  $sql = "INSERT INTO `non_customer_ledger_payments`(`customer_id`,`payment_date`,`reference_no`,`total_shipments`,`total_delivered`,`total_returned`,`total_returned_fee`,`cod_amount`,`delivery_charges`,`gst_amount`,`returned_amount`,`total_paid`,`sell_flyers_amount`,`total_payable`,`total_sell_flyers`,`ledger_orders`,`ledger_returned`,`ledger_delivered`,`ledger_flyers`,`cash_handling`,`prev_balance`,`prev_balance_history`,`total_extra_charges`,`total_charges`,`fuel_surcharge`,`net_amount`,`total_insuredpremium_charges`) VALUES (".$customer_id.",'".$payment_date."','".$reference_no."',".$total_shipments.",".$total_delivered.",".$total_returned.",".$total_returned_fee.",".$cod_amount.",".$delivery_charges.",".$gst_amount.",".$returned_amount.",".$total_paid.",".$sell_flyers_amount.",".$total_payable.",".$total_sell_flyers.",'".$ledger_orders."','".$ledger_returned."','".$ledger_delivered."','".$ledger_flyers."',".$cash_handling.",".$prev_balance.",".$prev_balance_history.",".$total_extra_charges.",".$total_charges.",".$fuel_surcharge.",".$net_amount.",".$total_insuredpremium_charges.")"; 
  // echo $sql;die();
  return mysqli_query($connection, $sql);
}


if(isset($_POST['submit'])) {
  // echo '<pre>',print_r($_POST),'</pre>';exit();
  $count_delivery = (int)$_POST['count_total_del_checked'];
  $count_return = (int)$_POST['count_total_return_checked'];
  $count_flyers = (int)$_POST['count_total_flyer_checked'];
  $count_shipments = $count_delivery + $count_return;

  $delivered_orders = (isset($_POST['delivered']) && !empty($_POST['delivered'])) ? array_keys($_POST['delivered']) : [];
  $returned_orders = (isset($_POST['returned']) && !empty($_POST['returned'])) ? array_keys($_POST['returned']) : [];
  $orders = array_merge($delivered_orders, $returned_orders);
  $delivered_orders = implode(',', $delivered_orders);
  $returned_orders = implode(',', $returned_orders);
  $orders = implode(',', $orders);
  

  $prev_balance = formated_value($_POST['prev_balance']);
  $prev_balance_history = $prev_balance;
  $total_payment = formated_value($_POST['total_payments']);
 if(($total_payment+$prev_balance) >0){
  $prev_balance = 0;
 }else{
   $prev_balance = $total_payment+$prev_balance;
 }
 // if($prev_balance <0){
 //  $prev_balance = 0;
 // }
  $flyers = (isset($_POST['flyer']) && !empty($_POST['flyer'])) ? implode(',', array_keys($_POST['flyer'])) : '';
  $insert_data = [
    'customer_id' => $_POST['customer_id'],
    'payment_date' => date('Y-m-d', strtotime($_POST['date'])),
    'reference_no' => isset($_POST['reference_no']) ? $_POST['reference_no']:0,
    'total_shipments' => isset($count_shipments) ? $count_shipments:0,
    'total_delivered' => isset($count_delivery) ? $count_delivery:0,
    'total_returned' => isset($count_return) ? $count_return:0,
    'total_returned_fee' => isset($_POST['total_return_fee']) ? formated_value($_POST['total_return_fee']):0,
    'cod_amount' => formated_value($_POST['total_cod']),
    'delivery_charges' => formated_value($_POST['total_delivery']),
    'gst_amount' => formated_value($_POST['total_gst']),
    'returned_amount' => isset($_POST['total_return']) ? formated_value($_POST['total_return']):0,
    'total_paid' => formated_value($_POST['total_payments']),
    'sell_flyers_amount' => isset($_POST['total_flyer']) ? formated_value($_POST['total_flyer']):0,
    'total_payable' => formated_value($_POST['total_payable_price']),
    'total_sell_flyers' => isset($count_flyers) ? formated_value($count_flyers):0,
    'ledger_orders' =>  $orders,
    'ledger_returned' =>  $returned_orders,
    'ledger_delivered' =>  $delivered_orders,
    'ledger_flyers' => isset($flyers) ? $flyers:0,
    'cash_handling' => isset($_POST['total_cash_handling']) ? $_POST['total_cash_handling']:0,
    'total_extra_charges' => isset($_POST['total_extra_charges']) ? formated_value($_POST['total_extra_charges']):0,
    'total_charges' => isset($_POST['total_charges']) ? formated_value($_POST['total_charges']):0,
    'fuel_surcharge' => isset($_POST['fuel_surcharge']) ? formated_value($_POST['fuel_surcharge']):0,
    'net_amount' => isset($_POST['net_amount']) ? formated_value($_POST['net_amount']):0,
    'total_insuredpremium_charges' => isset($_POST['total_insuredpremium_charges']) ? formated_value($_POST['total_insuredpremium_charges']):0,
    'prev_balance' =>  0,
    'prev_balance_history' =>  $prev_balance_history,
  ];


  if(insertToLedgerTable($con, 'non_customer_ledger_payments', $insert_data)) {
    $id = mysqli_insert_id($con);
    if($id) {
      mysqli_query($con, "UPDATE non_customer_ledger_payments SET update_able = 0 WHERE customer_id = ".$insert_data['customer_id']);
      mysqli_query($con, "UPDATE non_customer_ledger_payments SET update_able = 1 WHERE id = $id");
      mysqli_query($con, "UPDATE orders SET payment_status = 'Paid',payment_ledger_id=$id WHERE id IN (".$orders.")");
    }
    if($flyers) {
      mysqli_query($con, "UPDATE flayer_order_index SET payment_status = 'Paid',payment_ledger_id=$id WHERE id IN (".$flyers.")");
    }
    header('Location: non_ledger_payments.php');

  }else{
    header($_SERVER['HTTP_REFERER'].'&error=1');
  }
  exit();
}  else if(isset($_GET['delete']) && isset($_GET['customer_id']) && $_GET['delete'] != "") {
  $customer_id = $_GET['customer_id'];
  $order = mysqli_query($con, "SELECT * FROM non_customer_ledger_payments WHERE id = ".$_GET['delete']);
  $order = mysqli_fetch_object($order);
  if($order) {
    if(isset($order->ledger_orders) && $order->ledger_orders != '') {
      mysqli_query($con, "UPDATE orders SET payment_status = 'Pending' WHERE id IN (".$order->ledger_orders.")");
    }
    if(isset($order->ledger_flyers) && $order->ledger_flyers != '') {
      mysqli_query($con, "UPDATE flayer_order_index SET payment_status = 'Pending' WHERE id IN (".$order->ledger_flyers.")");
    }

    mysqli_query($con, "DELETE FROM non_customer_ledger_payments WHERE id = ".$order->id);
    mysqli_query($con, "UPDATE non_customer_ledger_payments SET update_able = 1 WHERE customer_id = ".$customer_id." ORDER BY id DESC LIMIT 1");
  }
  header('Location: '.$_SERVER['HTTP_REFERER']);
  exit();
}
  
  