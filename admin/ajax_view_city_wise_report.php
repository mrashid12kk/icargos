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

$customer_id = $_POST['customer_id'];
$from = $_POST['from'];
$to = $_POST['to'];
$read = $_POST['read'];
    ## Search
    $searchQuery = " ";
    if($searchValue != ''){
      $searchQuery .= " and (order_comments.track_no like '%".$searchValue."%' or
      order_comments.created_on like '%".$searchValue."%' or
      order_comments.subject like '%".$searchValue."%' or
      order_comments.order_comment like '%".$searchValue."%' or
      order_comments.comment_by like '%".$searchValue."%' or
      customers.bname like'%".$searchValue."%' ) ";
    }
    if($customer_id != ''){

         $searchQuery .= " and (order_comments.customer_id=".$customer_id.") ";

      }
      if($read != ''){

         $searchQuery .= " and (order_comments.is_read=".$read.") ";

      }
         
      if($from != '' && $to !=''){
        $from = date('Y-m-d',strtotime($_POST['from']));

          $to = date('Y-m-d',strtotime($_POST['to']));

         $searchQuery .= " and DATE_FORMAT(`created_on`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`created_on`, '%Y-%m-%d') <= '".$to."' ";

      }


## Total number of records without filtering

$sel = mysqli_query($con,"SELECT count(*) as allcount FROM order_comments inner join customers on order_comments.customer_id=customers.id");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];
## Total number of records with filtering

$sel = mysqli_query($con,"SELECT count(*) as allcount,customers.bname FROM order_comments inner Join customers on order_comments.customer_id=customers.id WHERE 1 ".$searchQuery."");
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];
## Fetch records
if($rowperpage == -1)
{
  $countQuery=mysqli_query($con,"SELECT COUNT(id) AS id FROM order_comments inner join customers on order_comments.customer_id=customers.id WHERE 1 ".$searchQuery."");
  $totalRow=mysqli_fetch_assoc($countQuery);
  $rowperpage = $totalRow['id'];
}
$empQuery = "SELECT order_comments.*,customers.bname From order_comments inner join customers on order_comments.customer_id=customers.id WHERE 1 ".$searchQuery." order by id desc  limit ".$row.",".$rowperpage ;
// echo $empQuery;
// die;
if(!function_exists('customers')){
  function customers($id=null)
  {
      global $con;
      if($id)
      {
        $query = mysqli_query($con,"SELECT * from customers where id =".$id);
        $resposne = mysqli_fetch_assoc($query);
        return $resposne['bname'];
      }
  }
}

$empRecords = mysqli_query($con, $empQuery);
$data = array();
$sr_no =1;

if($empRecords)
{

  while($fetch1 = mysqli_fetch_assoc($empRecords)) {
   $me='';
    $action='';
   if($fetch1["is_read"]==0){
      $action="<a href='#' class='read_msg'
                 data-track = '".$fetch1['track_no']."'
                 data-id = '".$fetch1['id']."'
                 data-date = '".$fetch1['created_on']."'
                 data-name = '".customers($fetch1['customer_id'])."'
                 data-subject = '".$fetch1['subject']."'
                 data-comment = '".$fetch1['order_comment']."'
                 data-commentby = '".$fetch1['comment_by']."'
                 ><i class='fa fa-book' data-toggle='modal' data-target='#exampleModal'></i></a>";
                   }

    if($fetch1['is_read']==0){$me='<span class="label label-default">Unread</span>';}
    else if($fetch1['is_read']==1){$me='<span class="label label-default">Read</span>';}
    if ($fetch1['is_read']==0){}
     $data[] = array(
      "id"=>"<input type='checkbox' name='' class='order_check' data-id='".$fetch1['id']."'>",
      "tracno"=>$fetch1['track_no'],
      "createdon"=> $fetch1['created_on'],
      "customername"=> $fetch1['bname'],
      "subject"=> $fetch1['subject'],
      "orderamount"=> $fetch1['order_comment'],
      "commentby"=>$fetch1['comment_by'],
      "itemvalue"=> $me,
      "action"=>$action
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
