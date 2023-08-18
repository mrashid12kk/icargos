<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);


  require 'includes/conn.php';
  require 'includes/role_helper.php';



## Read value

$draw = $_POST['draw'];

$row = $_POST['start'];

$rowperpage = $_POST['length']; // Rows display per page

$columnIndex = $_POST['order'][0]['column']; // Column index

$columnName = $_POST['columns'][$columnIndex]['data']; // Column name

$columnSortOrder = 'desc'; // asc or desc

$searchValue = $_POST['search']['value']; // Search value

## Custom Field value
$date_from = $_POST['date_from'];

$date_to = $_POST['date_to'];

$number = $_POST['number'];

$message = $_POST['message'];

$customer = $_POST['customer'];

$datetime = $_POST['datetime'];


$data = "";
// if(isset($_POST['data'])&&$_POST['data']!==''){
//     $id=mysqli_real_escape_string($con,$_POST['data']);
//     $delet=mysqli_query($con,"delete from sms_detail WHERE id='$id'")or die(mysqli_error($con)) ;
//     if(mysqli_affected_rows($con)>0){
//       $msg="<div class='alert alert-success'>product deleted Successfully</div>";
//     }
//     else{
//       $msg="<div class='alert alert-danger'>product not deleted Successfully</div>";
//     }
//     $response=$msg;
//     //echo json_encode($response);
// }
$searchQuery = "";

if($date_from != '' && $date_to !=''){

    $from = date('Y-m-d',strtotime($_POST['date_from']));

    $to = date('Y-m-d',strtotime($_POST['date_to']));

   $searchQuery .= " and DATE_FORMAT(sms_detail.created_on, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(sms_detail.created_on, '%Y-%m-%d') <= '".$to."' ";

}
if($datetime != ''){
	$searchQuery .= " and (DATE_FORMAT(`sms_detail.created_on`, '%Y-%m-%d') like '".$datetime."') ";
}
if($customer != ''){
	$searchQuery .= " and ( customers.bname like '%".$customer."%') ";
}
if($number != ''){
	$searchQuery .= " and ( sms_detail.contact_number like '%".$number."%') ";
}
if($message != ''){
	$searchQuery .= " and ( sms_detail.message like '%".$message."%') ";
}
if($searchValue != ''){

   $searchQuery .= " and (sms_detail.id like '%".$searchValue."%' or

      sms_detail.contact_number like '%".$searchValue."%' or

      sms_detail.message like '%".$searchValue."%' or

      sms_detail.created_on like '%".$searchValue."%' or

      sms_detail.status like '%".$searchValue."%' or

      customers.bname like '%".$searchValue."%' or

      sms_templates.sms_events like '%".$searchValue."%'  ) ";
}

## Total number of records without filtering
$sel = mysqli_query($con,"SELECT count(*) as allcount FROM sms_detail inner Join customers on sms_detail.contact_id=customers.id inner join sms_templates on sms_detail.template_id=sms_templates.id");

$records = mysqli_fetch_assoc($sel);

$totalRecords = $records['allcount'];

# Total number of records with filtering
// echo "SELECT count(*) as allcount,customers.bname as businessname FROM sms_detail inner join customers on sms_detail.contact_id=customers.id WHERE 1 ".$searchQuery;
// die;
$sel = mysqli_query($con,"SELECT count(*) as allcount,customers.bname as businessname,sms_templates.sms_events FROM sms_detail inner join customers on sms_detail.contact_id=customers.id inner join sms_templates on sms_detail.template_id=sms_templates.id WHERE 1 ".$searchQuery);

$records = mysqli_fetch_assoc($sel);

$totalRecordwithFilter = $records['allcount'];

## Fetch records

$empQuery = "SELECT sms_detail.*,customers.bname as businessname,sms_templates.sms_events as sms_event FROM sms_detail inner join customers on sms_detail.contact_id=customers.id inner join sms_templates on sms_detail.template_id=sms_templates.id WHERE 1 ".$searchQuery." order by sms_detail.id  ".$columnSortOrder." limit ".$row.",".$rowperpage;
// echo $empQuery;
// die;
$empRecords = mysqli_query($con, $empQuery);

$data = array();

$sr_no =1;
while ($fetch1 = mysqli_fetch_assoc($empRecords)) {
  

  if($fetch1['status']==0){$sts='<span class="label label-default ">Sent</span>';}
  else if($fetch1['status']==1){$sts='<span class="label label-default ">Not Sent</span>';}
   $data[] = array(
      "id"=>$fetch1['id'],
      "number"=>$fetch1['contact_number'],
      "message_content"=>$fetch1['message'],
      "customer_name"=>$fetch1['businessname'],
      "date_time"=>$fetch1['created_on'],
      "sms_events"=>$fetch1['sms_event'],
      "status"=> $sts,
      "action"=>'<button class="btn btn-primary btn-xs ng-scope"ng-click="doAction("resend", 522)">Resend</button>
          <a href="delete_sms.php?sms_id='.$fetch1['id'].'" class="btn btn-danger btn-xs ng-scope" ng-click="doAction("delete", 522)">Delete</a>',
   );
   // $sr_no ++;
}
## Response
$response = array(
  "draw" => intval($draw),
  "iTotalRecords" => $totalRecords,
  "iTotalDisplayRecords" => $totalRecordwithFilter,
  "aaData" => $data
);
// echo '<pre>';
// print_r($response);
// die;
echo json_encode($response);
