<?php
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);


require '../conn.php';
require '../role_helper.php';



## Read value

$draw = $_POST['draw'];

$row = $_POST['start'];

$rowperpage = $_POST['length']; // Rows display per page

$columnIndex = $_POST['order'][0]['column']; // Column index

$columnName = $_POST['columns'][$columnIndex]['data']; // Column name

$columnSortOrder = 'desc'; // asc or desc

$searchValue = $_POST['search']['value']; // Search value

## Custom Field value

// $date_to = $_POST['date_to'];

// $number = $_POST['number'];

// $message = $_POST['message'];

// $customer = $_POST['customer'];

// $datetime = $_POST['datetime'];


$searchQuery = "";
if($searchValue != ''){

 $searchQuery .= " and (country.id like '%".$searchValue."%' or

 country.country_name like '%".$searchValue."%' or

 state.state_name like '%".$searchValue."%'

) ";
}
## Total number of records without filtering
$sel = mysqli_query($con,"SELECT count(*) as allcount FROM city_mapping");

$records = mysqli_fetch_assoc($sel);

$totalRecords = $records['allcount'];

# Total number of records with filtering
$sel = mysqli_query($con,"SELECT count(*) as allcount FROM city_mapping WHERE 1 ".$searchQuery."");
$records = mysqli_fetch_assoc($sel);

$totalRecordwithFilter = $records['allcount'];

## Fetch records

$empQuery = "SELECT * FROM city_mapping WHERE 1 ".$searchQuery." order by ".$columnName."  ".$columnSortOrder." limit ".$row.",".$rowperpage;
// echo $empQuery;
// die;
$empRecords = mysqli_query($con, $empQuery);

$data = array();

$sr_no =$row+1;

while ($fetch1 = mysqli_fetch_assoc($empRecords)) {
  $data[] = array(
    "id"=>$sr_no++,
    "city"=>isset($fetch1['city_id']) ? $fetch1['city_id'] : '',
    "api"=>isset($fetch1['api_id']) ? $fetch1['api_id'] : '',
    "api_city"=>isset($fetch1['api_city_name']) ? $fetch1['api_city_name'] : '',
    "action"=>'<a href="thirdparty_general.php?delete_mapped='.$fetch1['id'].'"  onclick="return confirm('."'Are you sure you want to Delete?'".'); return false"><i class="fa fa-trash"></i></a>'
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
