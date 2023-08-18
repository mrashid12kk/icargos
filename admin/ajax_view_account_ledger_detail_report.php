<?php
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
require 'includes/conn.php';
    // echo "sring";
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

//     if(isset($_POST['filter']))
// {
    // if(isset($_POST['account_group_name']) && !empty($_POST['account_group_name'])){
    //     $filter_query .= " AND accountGroupId = '".$_POST['account_group_name']."' ";
    //     $active_acc_grp_name = $_POST['account_group_name'];
    // }
    // if(isset($_POST['ledger_name']) && !empty($_POST['ledger_name'])){
    //     $filter_query .= " AND ledgerName = '".$_POST['ledger_name']."' ";
    //     $active_ledger_name = $_POST['ledger_name'];
    // }
    // if(isset($_POST['voucher_type']) && !empty($_POST['voucher_type'])){
    //     $filter_query .= " AND voucherTypeName = '".$_POST['voucher_type']."' ";
    //     $active_voucher_type = $_POST['voucher_type'];
    // }
    // if(isset($_POST['customer_email']) && !empty($_POST['customer_email'])){
    //     $filter_query .= " AND semail = '".$_POST['customer_email']."' ";
    //     $active_customer_email = $_POST['customer_email'];
    // }
    // if(isset($_POST['active_customer']) && !empty($_POST['active_customer'])){
    //     $filter_query .= " AND customer_id = '".$_POST['active_customer']."' ";
    //     $active_customer_id = $_POST['active_customer'];
    // }
  
    // $from = date('Y-m-d',strtotime($_POST['from']));
    // $to = date('Y-m-d',strtotime($_POST['to']));
    // $query1 = mysqli_query($con,"SELECT * FROM tbl_accountledger WHERE DATE_FORMAT(`created_on`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`created_on`, '%Y-%m-%d') <= '".$to."'   $filter_query order by id desc ");
    // $query1 = mysqli_query($con,"SELECT * FROM tbl_accountledger WHERE 1 $filter_query order by id desc ");

    // echo "<pre>";
   
    // // $data = mysqli_fetch_all($query1,MYSQLI_ASSOC);
    // print_r($query1->num_rows);
    // exit();

// }

    // $ledger_id = $_POST['ledger_id'];


    $acc_group_name = $_POST['acc_group_name'];
    $ledger_id = $_POST['ledger_id'];
    $voucher_type = $_POST['voucher_type'];
    $date_range = $_POST['date_range'];
    $from = $_POST['from'];
    $to = $_POST['to'];


    ## Search
    $searchQuery = " ";

   //  if($searchValue != '')
   //  {

   //   $searchQuery .= " and (tbl_accountledger.ledgerCode like '%".$searchValue."%' or
   //   tbl_accountledger.ledgerName like '%".$searchValue."%' ) ";

   // }
    
   if($ledger_id != ''){

     $searchQuery .= " and (tbl_ledgerposting.ledgerId='".$ledger_id."') ";

   }
  
    $postOnCondition = '';
   if($date_range != '' && $date_range == 'last30Days')
   {
     $postOnCondition .= " and (tbl_ledgerposting.created_on > now() - INTERVAL 30 day ) ";

   }
   if($date_range != '' && $date_range == 'last15Days')
   {
     $postOnCondition .= " and (tbl_ledgerposting.created_on > now() - INTERVAL 15 day ) ";

   }
    if($date_range != '' && $date_range == 'today')
   {
     $postOnCondition .= " and (DATE(tbl_ledgerposting.created_on) = CURDATE() ) ";

   }

 


## Total number of records without filtering

// $sel = mysqli_query($con,"SELECT count(*) as allcount FROM tbl_accountledger inner Join customers on tbl_accountledger.customer_id=customers.id");
$sel = mysqli_query($con,"SELECT count(*) as allcount FROM tbl_ledgerposting WHERE tbl_ledgerposting.ledgerId = $ledger_id");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

// echo $totalRecords;exit();
## Total number of records with filtering

// $sel = mysqli_query($con,"SELECT count(*) as allcount,customers.bname as businessname,customers.customer_type, services.service_type as order_type_name FROM tbl_accountledger  inner Join customers on tbl_accountledger.customer_id=customers.id WHERE 1 ".$searchQuery."");

$sel = mysqli_query($con,"SELECT count(*) as allcount FROM tbl_ledgerposting  WHERE 1 ".$searchQuery."");
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];
## Fetch records
if($rowperpage == -1)
{
  $countQuery=mysqli_query($con,"SELECT COUNT(id) AS id FROM tbl_ledgerposting WHERE 1  ".$searchQuery."");
  $totalRow=mysqli_fetch_assoc($countQuery);
  $rowperpage = $totalRow['id'];
}

// $empQuery = "SELECT tbl_accountledger.* WHERE 1 ".$searchQuery." order by id desc  limit ".$row.",".$rowperpage ;

// $empQuery = "SELECT tbl_accountledger.*,accgrp.accountGroupName as grp_name  from tbl_accountledger LEFT JOIN tbl_accountgroup as accgrp ON accgrp.id = tbl_accountledger.accountGroupId  WHERE 1 ".$searchQuery." order by id desc  limit ".$row.",".$rowperpage ;

$empQuery = "SELECT DATE(tbl_ledgerposting.date) as ledgerDate, TIME(tbl_ledgerposting.date) as ledgerTime,
            tbl_ledgerposting.voucherNo,SUM(tbl_ledgerposting.debit) as total_debit,
            SUM(tbl_ledgerposting.credit) as total_credit, tbl_ledgerposting.narration, 
            tbl_vouchertype.voucherTypeName
            FROM tbl_ledgerposting  
            LEFT JOIN tbl_vouchertype ON tbl_vouchertype.voucherTypeId = tbl_ledgerposting.voucherTypeId
            WHERE 1 $postOnCondition ".$searchQuery." 
            order by id desc limit ".$row.",".$rowperpage ;


$empRecords = mysqli_query($con, $empQuery);

$data = array();
$sr_no =$row+1;

if($empRecords->num_rows > 0)
{
  while($fetch1 = mysqli_fetch_assoc($empRecords)) 
  {
    

    $data[] = array(
     "date" => $fetch1['ledgerDate'],
     "time" => $fetch1['ledgerTime'],
     "voucher_type" => $fetch1['voucherTypeName'],
     "party_name" => "party name",
     "description" => $fetch1['narration'],
     "voucher_no" => $fetch1['voucherNo'],
     "debit"=>"Rs. ".number_format((float)$fetch1['total_debit'], 2, '.', '')."/-",
     "credit"=>"Rs. ".number_format((float)$fetch1['total_credit'], 2, '.', '')."/-",
     "closingblnc"=>number_format((float)($fetch1['total_credit'] - $fetch1['total_debit']),2,'.','')."/-",
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
