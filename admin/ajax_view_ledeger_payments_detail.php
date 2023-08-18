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
    $customer_id = $_POST['customer_id'];

    ## Search
    $searchQuery = " ";
if($searchValue != ''){
   $searchQuery .= " and (customer_ledger_payments_detail.id like '%".$searchValue."%' or
   customers.fname like '%".$searchValue."%' or
   customer_ledger_payments_detail.id like '%".$searchValue."%' or
   customer_ledger_payments_detail.reference_no like '%".$searchValue."%' or
   customer_ledger_payments_detail.payment_date like '%".$searchValue."%' or
   customer_ledger_payments_detail.total_shipments like '%".$searchValue."%' or
   customer_ledger_payments_detail.total_delivered like '%".$searchValue."%' or
   customer_ledger_payments_detail.total_returned like '%".$searchValue."%' or
   customer_ledger_payments_detail.cod_amount like '%".$searchValue."%' or  
   customer_ledger_payments_detail.delivery_charges like '%".$searchValue."%' or
   customer_ledger_payments_detail.total_sell_flyers like '%".$searchValue."%' or
   customer_ledger_payments_detail.gst_amount like '%".$searchValue."%')";
}
 //if($from != '' && $to !=''){
  if(isset($_POST['from']) && $_POST['to']){
          $from = date('Y-m-d',strtotime($_POST['from']));

            $to = date('Y-m-d',strtotime($_POST['to']));

           $searchQuery .= " and DATE_FORMAT(customer_ledger_payments_detail.payment_date, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(customer_ledger_payments_detail.payment_date, '%Y-%m-%d') <= '".$to."' ";

        }
        if ($customer_id!='') {
          $searchQuery .= "AND customer_ledger_payments_detail.customer_id='".$customer_id."'";
        }
## Total number of records without filtering
$sel = mysqli_query($con,"SELECT count(*) as allcount FROM customer_ledger_payments_detail  WHERE 1 ");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];
## Total number of records with filtering
$sel = mysqli_query($con,"SELECT count(*) as allcount FROM customer_ledger_payments_detail WHERE 1 ".$searchQuery."");

$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];
## Fetch records
if($rowperpage == -1)
{
  $countQuery=mysqli_query($con,"SELECT COUNT(id) AS id FROM customer_ledger_payments_detail WHERE 1 ".$searchQuery."");
  $totalRow=mysqli_fetch_assoc($countQuery);
  // echo '<pre>',print_r($totalRow['id']),'</pre>';exit();
  $rowperpage = $totalRow['id'];
}

$where = (isset($_GET['customer_id']) && $_GET['customer_id'] != '') ? "WHERE customer_id = ".$_GET['customer_id'] : "";
              $sr=1;

$empQuery =  "SELECT customer_ledger_payments_detail.*,customers.fname as customer,customers.bname as company_name,customers.client_code,users.Name as user_name FROM customer_ledger_payments_detail LEFT JOIN  customers ON customers.id=customer_ledger_payments_detail.customer_id LEFT JOIN  users ON users.id=customer_ledger_payments_detail.user_id WHERE 1 ".$searchQuery." ORDER by id desc  limit ".$row.",".$rowperpage;
   // echo $empQuery;
   // die;
// echo $empQuery;die();
$empRecords = mysqli_query($con, $empQuery);
$data = array();
$sr_no =1;
   
if(!empty($empRecords))
{
  while($fetch1 = mysqli_fetch_assoc($empRecords)) {
    // echo '<pre>',print_r($fetch1),'</pre>';die;
     $data[] = array(
       "srno"=>$sr_no,
       "transactionid"=>$fetch1['transaction_id'],
       "invoiceno"=>$fetch1['invoice_no'],
       "amount"=>getConfig('currency').' '.$fetch1['amount'],
       "customername"=>$fetch1['customer'] .'('.$fetch1['company_name'].')',
       "User"=> $fetch1['user_name'],
       "paymentdate"=>date('Y-m-d', strtotime($fetch1['payment_date'])),
       "createdon" => date('Y-m-d', strtotime($fetch1['created_no'])),
       "action"=> "<a href='ledger_payments_detail.php?detele_id=".$fetch1['id']."' onclick='return confirm(" . '"Are you sure you want to Delete?"' . "); return false'><i class='fa fa-trash' style='font-size: 14px;''></i></a>"
       // "action"=> ""
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