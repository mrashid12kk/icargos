<?php
// echo "faisal";
// die;
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
require 'includes/conn.php';
// echo "string";
// die;
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
// $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
## Custom Field value
$from = $_POST['from'];
$to = $_POST['to'];

## Search
$searchQuery = " ";
if ($searchValue != '') {
  $searchQuery .= " and (customer_ledger_payments.id like '%" . $searchValue . "%' or
   customers.fname like '%" . $searchValue . "%' or
   customer_ledger_payments.id like '%" . $searchValue . "%' or
   customer_ledger_payments.reference_no like '%" . $searchValue . "%' or
   customer_ledger_payments.payment_date like '%" . $searchValue . "%' or
   customer_ledger_payments.total_shipments like '%" . $searchValue . "%' or
   customer_ledger_payments.total_delivered like '%" . $searchValue . "%' or
   customer_ledger_payments.total_returned like '%" . $searchValue . "%' or
   customer_ledger_payments.cod_amount like '%" . $searchValue . "%' or  
   customer_ledger_payments.delivery_charges like '%" . $searchValue . "%' or
   customer_ledger_payments.total_sell_flyers like '%" . $searchValue . "%' or
   customer_ledger_payments.gst_amount like '%" . $searchValue . "%')";
}
//if($from != '' && $to !=''){
if (isset($_POST['from']) && $_POST['to']) {

  $from = date('Y-m-d', strtotime($_POST['from']));

  $to = date('Y-m-d', strtotime($_POST['to']));

  $searchQuery .= " and DATE_FORMAT(customer_ledger_payments.payment_date, '%Y-%m-%d') >= '" . $from . "' AND  DATE_FORMAT(customer_ledger_payments.payment_date, '%Y-%m-%d') <= '" . $to . "' ";
}
if (isset($_POST['cid']) && !empty($_POST['cid'])) {
  $customer_id = $_POST['cid'];

  $searchQuery .= " and customer_ledger_payments.customer_id = $customer_id   ";
}
## Total number of records without filtering
$sel = mysqli_query($con, "SELECT count(*) as allcount FROM customer_ledger_payments  WHERE 1 ");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];
## Total number of records with filtering
$sel = mysqli_query($con, "SELECT count(*) as allcount FROM customer_ledger_payments WHERE 1 " . $searchQuery . "");

$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];
## Fetch records
if ($rowperpage == -1) {
  $countQuery = mysqli_query($con, "SELECT COUNT(id) AS id FROM customer_ledger_payments WHERE 1 " . $searchQuery . "");
  $totalRow = mysqli_fetch_assoc($countQuery);
  // echo '<pre>',print_r($totalRow['id']),'</pre>';exit();
  $rowperpage = $totalRow['id'];
}

$where = (isset($_GET['customer_id']) && $_GET['customer_id'] != '') ? "WHERE customer_id = " . $_GET['customer_id'] : "";
$sr = 1;

$empQuery =  "SELECT customer_ledger_payments.*,customers.fname as customer,customers.bname as company_name,customers.client_code FROM customer_ledger_payments LEFT JOIN  customers ON customers.id=customer_ledger_payments.customer_id WHERE 1 " . $searchQuery . " ORDER by id desc  limit " . $row . "," . $rowperpage;
// echo $empQuery;
// die;
// echo $empQuery;die();
$empRecords = mysqli_query($con, $empQuery);
$data = array();
$sr_no = 1;

if (!empty($empRecords)) {
  while ($fetch1 = mysqli_fetch_assoc($empRecords)) {
    //echo '<pre>',print_r($fetch1),'</pre>';
    $rate = 0;
    $shipments_html = $deliveries_html = $returned_html = $flyers_html = '';
    $orders = ($fetch1['ledger_orders']) ? explode(',', $fetch1['ledger_orders']) : [];
    if (!empty($orders)) {
      $shipments_html = '<ul>';
      foreach ($orders as $ship) {
        $shipments_html .= '<li>' . ((int)$ship + 20000000) . '</li>';
      }
      $shipments_html .= '</ul>';
    }
    $orders = ($fetch1['ledger_delivered']) ? explode(',', $fetch1['ledger_delivered']) : [];
    if (!empty($orders)) {
      $deliveries_html = '<ul>';
      foreach ($orders as $ship) {
        $deliveries_html .= '<li>' . ((int)$ship + 20000000) . '</li>';
      }
      $deliveries_html .= '</ul>';
    }
    $orders = ($fetch1['ledger_returned']) ? explode(',', $fetch1['ledger_returned']) : [];
    if (!empty($orders)) {
      $returned_html = '<ul>';
      foreach ($orders as $ship) {
        $returned_html .= '<li>' . ((int)$ship + 20000000) . '</li>';
      }
      $returned_html .= '</ul>';
    }
    $orders = ($fetch1['ledger_flyers']) ? explode(',', $fetch1['ledger_flyers']) : [];
    if (!empty($orders)) {
      $flyers_html = '<ul>';
      foreach ($orders as $ship) {
        $flyers_html .= '<li>' . sprintf('%04d', $ship) . '</li>';
      }
      $flyers_html .= '</ul>';
    }
    $balance = number_format(((float)$fetch1['total_payable'] - (float)$fetch1['total_paid']), 0);
    $action = "";
    // if($fetch1['update_able'] == 1){ 
    if ($balance != 0) {
      require_once "includes/role_helper.php";
      if (checkRolePermission($_SESSION['user_role_id'], 35, 'delete_only', $comment = null)) {
        $action = "<a href='submit_bulk_ledger_payment.php?delete=" . $fetch1['id'] . "&customer_id=" . $fetch1['customer_id'] . "' style='color: #da1414;font-size: 14px;margin-left: 34px;' class='fa fa-trash'></i></a>";
      }
    }
    // }

    $data[] = array(
      "srno" => $sr_no . '<input type="checkbox" class="order_check" data-id="' . $fetch1['id'] . '">',
      "client_code" => $fetch1['client_code'],
      "customer_name" => $fetch1['customer'] . '(' . $fetch1['company_name'] . ')',
      // "id"=>$fetch1['id'],
      "reference_no" => $fetch1['reference_no'],
      "payment_date" => date('Y-m-d', strtotime($fetch1['payment_date'])),
      // "total_shipments"=>'<a href="#" title="Shipments" data-trigger="focus" data-toggle="popover"data-html="true" data-content="'.$shipments_html.'"'.$fetch1['total_shipments'].'</a>',
      "total_shipments" => $fetch1['total_shipments'],
      // "total_delivered"=>'<a href="#" title="Delivered" data-trigger="focus" data-toggle="popover" data-html="true" data-content="'.$deliveries_html.' '.$fetch1['total_delivered'].'"</a>',
      "total_delivered" => $fetch1['total_delivered'],
      // "total_returned"=>'<a href="#" title="Returned" data-trigger="focus" data-toggle="popover" data-html="true" data-content="'.$returned_html.' '.$fetch1['total_returned'].'"</a>',
      "total_returned" => $fetch1['total_returned'],

      "cod_amount" => getConfig('currency') . ' ' . number_format($fetch1['cod_amount'], 2),
      "delivery_charges" => getConfig('currency') . ' ' . number_format($fetch1['delivery_charges'], 2),
      "returned_amount" => getConfig('currency') . ' ' . number_format($fetch1['returned_amount'], 2),
      "total_returned_fee" => getConfig('currency') . ' ' . number_format($fetch1['total_returned_fee'], 2),
      "cash_handling" => getConfig('currency') . ' ' . number_format($fetch1['cash_handling'], 2),
      "gst_amount" => getConfig('currency') . ' ' . number_format($fetch1['gst_amount'], 2),
      "total_sell_flyers" => getConfig('currency') . ' ' . number_format($fetch1['sell_flyers_amount'], 2), ('<a href="#" title="Flyers" data-trigger="focus" data-toggle="popover" data-html="true" data-content="' . $flyers_html . ' ' . $fetch1['total_sell_flyers'] . '"</a>'),
      "currency" => getConfig('currency') . ' ' . number_format($fetch1['total_payable'], 2),
      "total_paid" => getConfig('currency') . ' ' . number_format($fetch1['total_paid'], 2),
      "total_payable" => getConfig('currency') . ' ' . number_format(((float)$fetch1['total_payable'] - (float)$fetch1['total_paid']), 2),
      "status" => isset($fetch1['status']) && $fetch1['status'] == 1 ? "<button class='btn btn-info'>PAID</button>" : "<button class='btn btn-danger'>UNPAID</button>" . "<button class='btn btn-info pay_now' data-id='" . $fetch1['id'] . "'>Pay Now</button>",
      "update_able" => "<a target='_blank' href='ledger_payment_view.php?payment_id=" . $fetch1['id'] . "'<i class='fa fa-eye' style='font-size: 14px;''></i></a>" . $action
    );
    $sr_no++;
  }
}
## Response
$response = array(
  "draw" => intval($draw),
  "iTotalRecords" => $totalRecords,
  "iTotalDisplayRecords" => $totalRecordwithFilter,
  "aaData" => $data
);
echo json_encode($response);
