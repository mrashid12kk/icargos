<?php

session_start();
require 'includes/conn.php';
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {
    // echo "<pre>";
    // print_r($_POST);
    // die;

    $customer_id = $_POST['item']['customer_id'];
    $prev_balance = isset($_POST['item']['prev_balance']) ? $_POST['item']['prev_balance'] : 0;
    $prev_balance_history = isset($_POST['item']['prev_balance']) ? $_POST['item']['prev_balance'] : 0;
    $to_date = isset($_POST['item']['to_date']) ? date('Y-m-d', strtotime($_POST['item']['to_date'])) : date('Y-m-d'); //action date
    $from_date = isset($_POST['item']['from_date']) ? date('Y-m-d', strtotime($_POST['item']['from_date'])) : date('Y-m-d');
    $where = " AND action_date>='" . $from_date . "' AND action_date<='" . $to_date . "'";
    $invoices_query = mysqli_query($con, "SELECT * FROM orders WHERE (status='Delivered' OR status='Returned to Shipper') AND payment_status = 'Pending' AND customer_id = $customer_id  $where");
    $customerDetail = mysqli_fetch_assoc(mysqli_query($con,"SELECT return_fee_per_parcel from customers where id = $customer_id"));
    $collection_amount = 0;
    $payment_date = date('Y-m-d H:i:s');
    $last_id_q = mysqli_query($con, "SELECT max(id) as id from customer_ledger_payments");
    $lastIdRes = mysqli_fetch_assoc($last_id_q);
    $lastId = isset($lastIdRes['id']) ? $lastIdRes['id'] : 0;
    $nextId = $lastId + 1;
    $reference_no = 'SI-' . $nextId;

    
    
    
    // $reference_no = strtoupper(substr(hash('sha256', mt_rand() . microtime()), 0, 8));
    $total_shipments = 0;
    $total_delivered = 0;
    $total_returned = 0;
    $total_returned_fee = 0;
    $cod_amount = 0;
    $delivery_charges = 0;
    $gst_amount = 0;
    $returned_amount = 0;
    $sell_flyers_amount = 0;
    $total_payable = 0;
    $total_sell_flyers = 0;
    $ledger_orders = '';
    $ledger_returned = '';
    $ledger_delivered = '';
    $ledger_flyers = 0;
    $cash_handling = 0; 
    $total_charges = 0;
    $fuel_surcharge = 0;
    $net_amount = 0; 
    //  Flyers calcuations:
    function getTotal($flayer_id)
    {
        $sql_t = "Select * from flayer_orders WHERE flayer_order_index = " . $flayer_id;
        global $con;
        $query11 = mysqli_query($con, $sql_t);
        $total = 0;
        while ($fetch12 = mysqli_fetch_array($query11)) {
            $total += $fetch12['total_price'];
        }
        return $total;
    }
    $flyer_query = mysqli_query($con, "SELECT * FROM flayer_order_index WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '" . $from_date . "' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '" . $to_date . "'  AND customer=" . $customer_id . " AND payment_status = 'Pending'  order by id desc ");
    while ($row = mysqli_fetch_array($flyer_query)) {
        $flayer_order_index = $row['id'];
        $flayer_order_query = mysqli_query($con, "SELECT flayers.flayer_name,flayer_orders.qty FROM flayer_orders LEFT JOIN flayers ON(flayers.id = flayer_orders.flayer ) WHERE flayer_orders.flayer_order_index=" . $flayer_order_index . " ");
        $flyer_total = getTotal($row['id']);
        $total_sell_flyers +=$flyer_total;
        $ledger_flyers++;
    }
    while ($resFetch = mysqli_fetch_assoc($invoices_query)) {
        $track_no = $resFetch['track_no'];
        $id = $resFetch['id'];
        $total_shipments++;
        $ledger_orders .= $id . ',';
        if ($resFetch['status'] == 'Delivered') {
            $total_delivered++;
            $ledger_delivered .= $id . ',';
        }
        if ($resFetch['status'] == 'Returned to Shipper') {
            $ledger_returned .= $id . ',';
            $total_returned++;
            $returned_amount += isset($resFetch['collection_amount']) ? $resFetch['collection_amount'] : 0;
        }

        $cod_amount += isset($resFetch['collection_amount']) ? $resFetch['collection_amount'] : 0;
        $delivery_charges += isset($resFetch['price']) ? $resFetch['price'] : 0;
        $gst_amount += isset($resFetch['pft_amount']) ? $resFetch['pft_amount'] : 0;
        $net_amount += isset($resFetch['net_amount']) ? $resFetch['net_amount'] : 0;
        $total_charges += isset($resFetch['grand_total_charges']) ? $resFetch['grand_total_charges'] : 0;
        $fuel_surcharge += isset($resFetch['fuel_surcharge']) ? $resFetch['fuel_surcharge'] : 0;
    }
    if($total_returned > 0){
        $personlize_fee = isset($customerDetail['return_fee_per_parcel']) ? $customerDetail['return_fee_per_parcel'] : '';
        $total_returned_fee = $personlize_fee * $total_returned;
    }
    $total_cash = $cod_amount - $returned_amount;
    if($total_cash > 10000){
        $config_fee =getConfig('cash_handling');
        $handeling_fee  = isset($config_fee) ? $config_fee : 0;
        $cash_handling = $total_cash * $handeling_fee / 100;
    }
    $total_payable = $cod_amount - $net_amount - $returned_amount - $total_sell_flyers - $total_returned_fee - $cash_handling;
    $ledger_orders = rtrim($ledger_orders, ',');
    $ledger_returned = rtrim($ledger_returned, ',');
    $ledger_delivered = rtrim($ledger_delivered, ',');
    // echo $ledger_orders;
    // die();
    $customer_id =  (isset($customer_id) && $customer_id) ? $customer_id : '';
    $payment_date =  (isset($payment_date) && $payment_date) ? $payment_date : '';
    $reference_no =  (isset($reference_no) && $reference_no) ? $reference_no : '';
    $total_shipments =  (isset($total_shipments) && $total_shipments) ? $total_shipments : 0;
    $total_delivered =  (isset($total_delivered) && $total_delivered) ? $total_delivered : 0;
    $total_returned =  (isset($total_returned) && $total_returned) ? $total_returned : 0;
    $total_returned_fee =  (isset($total_returned_fee) && $total_returned_fee) ? $total_returned_fee : 0;
    $cod_amount =  (isset($cod_amount) && $cod_amount) ? $cod_amount : 0;
    $delivery_charges =  (isset($delivery_charges) && $delivery_charges) ? $delivery_charges : 0;
    $gst_amount =  (isset($gst_amount) && $gst_amount) ? $gst_amount : 0;
    $returned_amount =  (isset($returned_amount) && $returned_amount) ? $returned_amount : 0;
    $total_paid =   0;
    // $total_paid =  (isset($total_paid) && $total_paid) ? $total_paid : 0;
    $sell_flyers_amount =  (isset($sell_flyers_amount) && $sell_flyers_amount) ? $sell_flyers_amount : 0;
    $total_payable =  (isset($total_payable) && $total_payable) ? $total_payable : 0;
    $total_sell_flyers =  (isset($total_sell_flyers) && $total_sell_flyers) ? $total_sell_flyers : 0;
    $ledger_orders =  (isset($ledger_orders) && $ledger_orders) ? $ledger_orders : '';
    $ledger_returned =  (isset($ledger_returned) && $ledger_returned) ? $ledger_returned : '';
    $ledger_delivered =  (isset($ledger_delivered) && $ledger_delivered) ? $ledger_delivered : '';
    $ledger_flyers =  (isset($ledger_flyers) && $ledger_flyers) ? $ledger_flyers : '';
    $cash_handling =  (isset($cash_handling) && $cash_handling > 0) ? $cash_handling : 0;
    $prev_balance =  (isset($prev_balance) && $prev_balance) ? $prev_balance : 0;
    $total_charges =  (isset($total_charges) && $total_charges) ? $total_charges : 0;
    $fuel_surcharge =  (isset($fuel_surcharge) && $fuel_surcharge) ? $fuel_surcharge : 0;
    $net_amount =  (isset($net_amount) && $net_amount) ? $net_amount : 0;
    $prev_balance_history =  (isset($prev_balance_history) && $prev_balance_history) ? $prev_balance_history : 0;
    $sql = "INSERT INTO `customer_ledger_payments`(`customer_id`,`payment_date`,`reference_no`,`total_shipments`,`total_delivered`,`total_returned`,`total_returned_fee`,`cod_amount`,`delivery_charges`,`gst_amount`,`returned_amount`,`total_paid`,`sell_flyers_amount`,`total_payable`,`total_sell_flyers`,`ledger_orders`,`ledger_returned`,`ledger_delivered`,`ledger_flyers`,`cash_handling`,`prev_balance`,`prev_balance_history`,`total_charges`,`fuel_surcharge`,`net_amount`) VALUES (" . $customer_id . ",'" . $payment_date . "','" . $reference_no . "'," . $total_shipments . "," . $total_delivered . "," . $total_returned . "," . $total_returned_fee . "," . $cod_amount . "," . $delivery_charges . "," . $gst_amount . "," . $returned_amount . "," . $total_paid . "," . $sell_flyers_amount . "," . $total_payable . "," . $total_sell_flyers . ",'" . $ledger_orders . "','" . $ledger_returned . "','" . $ledger_delivered . "','" . $ledger_flyers . "'," . $cash_handling . "," . $prev_balance . "," . $prev_balance_history . "," . $total_charges . "," . $fuel_surcharge . "," . $net_amount . ")";

    $query = mysqli_query($con, $sql);
    $payment_id = mysqli_insert_id($con);
    if ($payment_id) {
        mysqli_query($con, "UPDATE orders SET payment_status = 'Paid',payment_ledger_id=$payment_id WHERE id IN (" . $ledger_orders . ")");
    }
    echo json_encode([
        'status' => 1,
        'message' => "Payment Id " . $reference_no . " successfully generated for customer: " . $_POST['item']['client_name'],
        'customer_id' => $_POST['item']['customer_id'],  // remove table row by customer id with jquery
    ]);
}