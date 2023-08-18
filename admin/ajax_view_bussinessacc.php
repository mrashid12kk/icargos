<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
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


    ## Search
    $searchQuery = " ";
if($searchValue != ''){
   $searchQuery .= " and (id like '%".$searchValue."%' or
   customers.id like '%".$searchValue."%' or
   customers.dates like '%".$searchValue."%' or
   customers.client_code like '%".$searchValue."%' or
   customers.fname like '%".$searchValue."%' or
   customers.bname like '%".$searchValue."%' or
   customers.business_manager like '%".$searchValue."%' or
   customers.email like '%".$searchValue."%' or
   customers.mobile_no like '%".$searchValue."%' or
   customers.status like '%".$searchValue."%')";
}

## Total number of records without filtering

$sel = mysqli_query($con,"SELECT count(*) as allcount FROM customers  WHERE 1 and status=1 ");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];
## Total number of records with filtering

$sel = mysqli_query($con,"SELECT count(*) as allcount FROM customers WHERE  1 and status=1 ".$searchQuery."");
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];
## Fetch records
if($rowperpage == -1)
{
  $countQuery=mysqli_query($con,"SELECT COUNT(id) AS id FROM customers WHERE 1  and status=1 ".$searchQuery."");
  $totalRow=mysqli_fetch_assoc($countQuery);
  // echo '<pre>',print_r($totalRow['id']),'</pre>';exit();
  $rowperpage = $totalRow['id'];
}
$empQuery = "SELECT * FROM customers WHERE 1 and status=1 ".$searchQuery." ORDER by id desc  limit ".$row.",".$rowperpage ;
// echo $empQuery;
// die;
// echo $empQuery;die();
$empRecords = mysqli_query($con, $empQuery);
$data = array();
$sr_no =1;
  // echo '<pre>',print_r($empRecords),'</pre>';exit();
if(!empty($empRecords))
{
  while($fetch1 = mysqli_fetch_assoc($empRecords)) {
    $rate = 0;

     $data[] = array(
       "id"=>$sr_no,
       "image"=>"<img src='".BASE_URL."".$fetch1['image']."' style='width: 100px;'>",
       "date"=>date("d M Y",strtotime($fetch1['dates'])),
       "client_code"=>$fetch1['client_code'],
       "fname"=>$fetch1['fname'],
       "bname"=>$fetch1['bname'],
       "business_manager"=>$fetch1['business_manager'],
       "email"=>$fetch1['email'],
       "mobile_no"=>$fetch1['mobile_no'],
       "cnic_copy"=>isset($fetch1['cnic_copy']) ? "<img src='".BASE_URL."".$fetch1['cnic_copy']."'style='width: 63px;'>" :'',
       "status"=>isset($fetch1['status']) && $fetch1['status']==1 ? "<span class='btn btn-success'>Approved</span>" : "<span class='btn btn-info'>Pending</span>",
        "action"=>"<a href='customer_detail.php?customer_id=".$fetch1['id']." '> <i class='fa fa-eye' style='font-size: 14px;'></i></a><a href='bussiness_account_sheet.php?account_no=".$fetch1['id']." ' target='_blank'> <i class='fa fa-print' style='font-size: 14px;'></i></a>"

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
