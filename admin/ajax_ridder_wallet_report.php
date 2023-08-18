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
       $searchQuery .= " and (rider_wallet_ballance.id like '%".$searchValue."%' or
       rider_wallet_ballance.rider_name like '%".$searchValue."%' or
       rider_wallet_ballance.ballance like '%".$searchValue."%') ";
    }
    // if($customer_id != ''){

    //      $searchQuery .= " and (order_comments.customer_id=".$customer_id.") ";

    //   }
      // if($read != ''){

      //    $searchQuery .= " and (order_comments.is_read=".$read.") ";

      // }
         
      // if($from != '' && $to !=''){
      //   $from = date('Y-m-d',strtotime($_POST['from']));

      //     $to = date('Y-m-d',strtotime($_POST['to']));

      //    $searchQuery .= " and DATE_FORMAT(`created_on`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`created_on`, '%Y-%m-%d') <= '".$to."' ";

      // }


## Total number of records without filtering

$sel = mysqli_query($con,"SELECT count(*) as allcount FROM rider_wallet_ballance");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];
## Total number of records with filtering

$sel = mysqli_query($con,"SELECT count(*) as allcount,rider_wallet_ballance.id from rider_wallet_ballance ");
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];
## Fetch records
if($rowperpage == -1)
{
  $countQuery=mysqli_query($con,"SELECT COUNT(id) AS id from rider_wallet_ballance WHERE 1 ".$searchQuery."");
  $totalRow=mysqli_fetch_assoc($countQuery);
  $rowperpage = $totalRow['id'];
}

$empQuery="SELECT * from rider_wallet_ballance WHERE 1 ".$searchQuery."";
// echo $empQuery;
// die;
$empRecords = mysqli_query($con, $empQuery);
$data = array();
$sr_no =1;


 function getRiderNameById($id)
{
  global $con;
  $sql = "SELECT * from users WHERE id=".$id;
// echo $sql; die;
  $query =mysqli_query($con, $sql);
  $result = mysqli_fetch_assoc($query);
  return $result['Name'];
}

if($empRecords)
{
  while($fetch1 = mysqli_fetch_assoc($empRecords)) {
    $data[] = array(
      "id"=> $fetch1['id'],
      "ridername"=>getRiderNameById($fetch1['rider_id']),
      "ballance"=> $fetch1['balance'],
      "viewdetail"=> '<td class="center">
                          <form action="rider_wallet_ballance_log.php" method="POST">
                            <input type="hidden" name="id" value=" '.$fetch1["id"].'">
                              <button type="submit" name="edit" class="btn_stye_custom" >View Detail
                                <span class="glyphicon glyphicon-eye-open"></span>
                              </button>
                          </form>
                      </td>'
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
