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
$pincode = $_POST['pincode'];

// $date_to = $_POST['date_to'];

// $number = $_POST['number'];

// $message = $_POST['message'];

// $customer = $_POST['customer'];

// $datetime = $_POST['datetime'];


$searchQuery = "";
if($pincode != ''){

   $searchQuery .= " AND (pincode.pincode='".$pincode."') ";

}
if($searchValue != ''){

   $searchQuery .= " and (pincode.id like '%".$searchValue."%' or

      pincode.pincode like '%".$searchValue."%' or
     
      city.city_name like '%".$searchValue."%'
        ) ";
}

## Total number of records without filtering
$sel = mysqli_query($con,"SELECT count(*) as allcount FROM pincode");

$records = mysqli_fetch_assoc($sel);

$totalRecords = $records['allcount'];

# Total number of records with filtering
// echo "SELECT count(*) as allcount,customers.bname as businessname FROM sms_detail inner join customers on sms_detail.contact_id=customers.id WHERE 1 ".$searchQuery;
// die;
$sel = mysqli_query($con,"SELECT count(*) as allcount FROM pincode INNER JOIN city  WHERE 1 ".$searchQuery."");
$records = mysqli_fetch_assoc($sel);

$totalRecordwithFilter = $records['allcount'];

## Fetch records

$empQuery = "SELECT pincode.id as pincodeid,pincode.pincode,country.country_name,cities.city_name,state.state_name,cities.id FROM pincode INNER JOIN cities on pincode.city_id=cities.id INNER JOIN country on pincode.country_id=country.id INNER JOIN state on pincode.state_id=state.id WHERE 1 ".$searchQuery." order by ".$columnName."  ".$columnSortOrder." limit ".$row.",".$rowperpage;
// echo $empQuery;
// die;
$empRecords = mysqli_query($con, $empQuery);

$data = array();

$sr_no =$row+1;

while ($fetch1 = mysqli_fetch_assoc($empRecords)) {
  
   $data[] = array(
      "id"=>$sr_no++,
      "country"=>$fetch1['country_name'],
      "state"=>$fetch1['state_name'],
      "city"=>$fetch1['city_name'],
      "pincode"=>$fetch1['pincode'],
      "action"=>'<a href="add_pincode.php?edit_id='.$fetch1['pincodeid'].'" class=""><i class="fa fa-edit"></i></a>
        <a href="pincode_list.php?delete_id='.$fetch1['pincodeid'].'"  onclick="return confirm('."'Are you sure you want to Delete?'".'); return false"><i class="fa fa-trash"></i></a>'
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
