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

    $acc_group_name = $_POST['acc_group_name'];
    $ledger_name = $_POST['ledger_name'];
    $voucher_type = $_POST['voucher_type'];
    $date_range = $_POST['date_range'];
    $from = $_POST['from'];
    $to = $_POST['to'];


  
    ## Search
    $searchQuery = " ";
    if($searchValue != '')
    {

     $searchQuery .= " and (tbl_accountledger.ledgerCode like '%".$searchValue."%' or
     tbl_accountledger.ledgerName like '%".$searchValue."%' ) ";

   }
   if($ledger_name != ''){

     $searchQuery .= " and (tbl_accountledger.ledgerName='".$ledger_name."') ";

   }
    if($acc_group_name != '' && $acc_group_name != 'all'){

     $searchQuery .= " and (tbl_accountledger.accountGroupId='".$acc_group_name."') ";

   }

   // if($voucher_type != '' && $voucher_type != 'all')
   // {
   //   $searchQuery .= " and (tbl_accountledger.accountGroupId='".$acc_group_name."') ";

   // }

   $postOnCondition = '';
   if($date_range != '' && $date_range == 'last30Days')
   {
     $postOnCondition .= " and (post.created_on > now() - INTERVAL 30 day ) ";

   }
   if($date_range != '' && $date_range == 'last15Days')
   {
     $postOnCondition .= " and (post.created_on > now() - INTERVAL 15 day ) ";

   }
    if($date_range != '' && $date_range == 'today')
   {
     $postOnCondition .= " and (DATE(post.created_on) = CURDATE() ) ";

   }
   if($date_range == 'specific')
   {
      if($from != '' && $to !='')
      {
        $from = date('Y-m-d',strtotime($from));

        $to = date('Y-m-d',strtotime($to));

         $postOnCondition .= " and DATE_FORMAT(post.created_on,'%Y-%m-%d') >= '$from' AND DATE_FORMAT(post.created_on,'%Y-%m-%d') <= '$to' ";
      }
   }
   
  

## Total number of records without filtering

// $sel = mysqli_query($con,"SELECT count(*) as allcount FROM tbl_accountledger inner Join customers on tbl_accountledger.customer_id=customers.id");
$sel = mysqli_query($con,"SELECT count(*) as allcount FROM tbl_accountledger");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

// echo $totalRecords;exit();
## Total number of records with filtering

// $sel = mysqli_query($con,"SELECT count(*) as allcount,customers.bname as businessname,customers.customer_type, services.service_type as order_type_name FROM tbl_accountledger  inner Join customers on tbl_accountledger.customer_id=customers.id WHERE 1 ".$searchQuery."");

$sel = mysqli_query($con,"SELECT count(*) as allcount FROM tbl_accountledger  WHERE 1 ".$searchQuery."");
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];
## Fetch records
if($rowperpage == -1)
{
  $countQuery=mysqli_query($con,"SELECT COUNT(id) AS id FROM tbl_accountledger WHERE 1 ".$searchQuery."");
  $totalRow=mysqli_fetch_assoc($countQuery);
  $rowperpage = $totalRow['id'];
}


if($voucher_type != '' && $voucher_type != 'all')
   {
     $searchQuery .= " and (post.voucherTypeId ='".$voucher_type."') ";

   }

$empQuery = "SELECT tbl_accountledger.ledgerCode,tbl_accountledger.id as ledger_id,tbl_accountledger.ledgerName,tbl_accountledger.phone,tbl_accountledger.mobile,tbl_accountledger.openingBalance,SUM(post.debit) as total_debit,SUM(post.credit) as total_credit,
            accgrp.accountGroupName as grp_name  
            from tbl_accountledger 
            LEFT JOIN tbl_accountgroup as accgrp ON accgrp.id = tbl_accountledger.accountGroupId 
            LEFT join tbl_ledgerposting as post 
              on post.ledgerId = tbl_accountledger.id ".$postOnCondition."
            WHERE 1 ".$searchQuery." GROUP BY tbl_accountledger.id  order by tbl_accountledger.id desc limit ".$row.",".$rowperpage ;

// echo $empQuery;
// exit;

$empRecords = mysqli_query($con, $empQuery);



$data = array();
$sr_no =$row+1;


if($empRecords)
{
  while($fetch1 = mysqli_fetch_assoc($empRecords)) 
  {
    
  

    $data[] = array(
     "id"=>"<input type='checkbox' name='' class='order_check' data-id='".$fetch1['ledger_id']."'>",
     "cnno"=>$sr_no,
     "code"=>$fetch1['ledgerCode'],
     "ledgname"=>$fetch1['ledgerName'],
     "accgrp"=>$fetch1['grp_name'],
     "mobile"=>$fetch1['mobile'],
     // "cndate"=> date(DATE_FORMAT,strtotime($fetch1['order_date'])),
     "phone"=>$fetch1['phone'],
     "cnic"=>$fetch1['phone'],
     "city"=>$fetch1['phone'],
     "address"=>$fetch1['phone'],
     "debit"=>"Rs. ".number_format((float)$fetch1['total_debit'], 2, '.', '')."/-",
     "credit"=>"Rs. ".number_format((float)$fetch1['total_credit'], 2, '.', '')."/-",
     // "deliveryphone"=>$fetch1['rphone'],
     "closingblnc"=>$fetch1['openingBalance'],
     "action"=>"<a href='account_ledger_detail_report.php?led_id=".$fetch1['ledger_id']." '> <i class='fa fa-eye' style='font-size: 14px;'></i></a>"
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
