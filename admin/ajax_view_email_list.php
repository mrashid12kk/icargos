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
$subject = $_POST['send_to'];

$datetime = $_POST['datetime'];


$data = "";


$searchQuery = " ";

if($date_from != '' && $date_to !=''){

    $from = date('Y-m-d',strtotime($_POST['date_from']));

    $to = date('Y-m-d',strtotime($_POST['date_to']));

   $searchQuery .= " and DATE_FORMAT(email_detail.created_on, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(email_detail.created_on, '%Y-%m-%d') <= '".$to."' ";

}
if($datetime != ''){
	$searchQuery .= " and (DATE_FORMAT(`email_detail.created_on`, '%Y-%m-%d') like '".$datetime."') ";
}
if($number != ''){
	$searchQuery .= " and ( email_detail.contact_email like '%".$number."%') ";
}
if($subject != ''){
  $searchQuery .= " and ( email_detail.subject like '%".$subject."%') ";
}
if($message != ''){
	$searchQuery .= " and ( email_detail.message like '%".$message."%') ";
}
if($searchValue != ''){

   $searchQuery .= " and (email_detail.id like '%".$searchValue."%' or

      email_detail.contact_email like '%".$searchValue."%' or

      email_detail.message like '%".$searchValue."%' or

      email_detail.created_on like '%".$searchValue."%' or

      email_detail.status like '%".$searchValue."%' or
      
      email_templates.sms_events like '%".$searchValue."%' or

      email_detail.created_on like '%".$searchValue."%') ";
}

## Total number of records without filtering
$sel = mysqli_query($con,"SELECT count(*) as allcount FROM email_detail inner join email_templates on email_detail.template_id=email_templates.id");

$records = mysqli_fetch_assoc($sel);

$totalRecords = $records['allcount'];

# Total number of records with filtering
// echo "SELECT count(*) as allcount,customers.bname as businessname FROM email_detail inner join customers on email_detail.contact_id=customers.id WHERE 1 ".$searchQuery;
// die;
$sel = mysqli_query($con,"SELECT count(*) as allcount,email_templates.sms_events as sms_events  FROM email_detail inner join email_templates on email_detail.template_id=email_templates.id WHERE 1 ".$searchQuery);

$records = mysqli_fetch_assoc($sel);

$totalRecordwithFilter = $records['allcount'];

## Fetch records

$empQuery = "SELECT email_detail.*,email_templates.sms_events as sms_event FROM email_detail inner join email_templates on email_detail.template_id=email_templates.id WHERE 1 ".$searchQuery." order by email_detail.id  ".$columnSortOrder." limit ".$row.",".$rowperpage;
// echo $empQuery;
// die;
$empRecords = mysqli_query($con, $empQuery);

$data = array();

$sr_no =1;

while ($fetch1 = mysqli_fetch_assoc($empRecords)) {
  if($fetch1['status']==1){$sts='<span class="label label-default ">Sent</span>';}
  else if($fetch1['status']==0){$sts='<span class="label label-default ">Not Sent</span>';}
   $data[] = array(
      "id"=>$sr_no ++,
      "number"=>$fetch1['contact_email'],
      "message_content"=>$fetch1['message'],
      "send_to"=>$fetch1['subject'],
      "date_time"=>$fetch1['created_on'],
      "sms_events"=>$fetch1['sms_event'],
      "status"=> $sts,
      "action"=>"<a href='email/sendEmail/resend_email.php?email_id=".$fetch1['id']."'  class='btn btn-info btn-xs' onclick='return confirm(".'"Are you sure you want to Resend?"'."); return false'>Resend</a>
           <a href='delete_sms.php?email_id=".$fetch1['id']."'  class='btn btn-danger btn-xs ng-scope delete' onclick='return confirm(".'"Are you sure you want to Delete?"'."); return false'>Delete</a>",
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
