<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'includes/conn.php';

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
if ($searchValue != '') {
  $searchQuery .= " and (country.country_name like '%" . $searchValue . "%' or
  state.state_name like '%" . $searchValue . "%' or
  cities.stn_code like '%" . $searchValue . "%' or
  cities.city_name like '%" . $searchValue . "%' or
  cities.area_code like '%" . $searchValue . "%' or
  zone_type.zone_name like'%" . $searchValue . "%' ) ";
}

## Total number of records without filtering

$sel = mysqli_query($con, "SELECT count(*) as allcount FROM cities");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];
## Total number of records with filtering

$sel = mysqli_query($con, "SELECT count(*) as allcount,country.country_name,state.state_name,zone_type.zone_name from cities LEFT JOIN country on cities.country_id=country.id LEFT JOIN state on cities.state_id=state.id LEFT JOIN zone_type on cities.zone_type_id=zone_type.id WHERE 1 " . $searchQuery . " order by cities.id desc");
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];
## Fetch records
if ($rowperpage == -1) {
  $countQuery = mysqli_query($con, "SELECT COUNT(id) AS id FROM order_comments inner join customers on order_comments.customer_id=customers.id WHERE 1 " . $searchQuery . "");
  $totalRow = mysqli_fetch_assoc($countQuery);
  $rowperpage = $totalRow['id'];
}
$empQuery = "SELECT cities.*,country.country_name,state.state_name,zone_type.zone_name from cities LEFT JOIN country on cities.country_id=country.id LEFT JOIN state on cities.state_id=state.id LEFT JOIN zone_type on cities.zone_type_id=zone_type.id WHERE 1 " . $searchQuery . " order by cities.id desc limit " . $row . "," . $rowperpage;
$empRecords = mysqli_query($con, $empQuery);
$data = array();
$sr_no = $_POST['start'] + 1;

if ($empRecords) {

  while ($fetch1 = mysqli_fetch_assoc($empRecords)) {
    $me = '';
    $action = "<form action='editcities.php' method='post' style='display: inline-block;'><input type='hidden' name='id' value='".$fetch1['id']."'><button type='submit' name='edit_id'><span class='glyphicon glyphicon-edit'></span></button><input type='hidden' name='id' value='".$fetch1['id']."'></form><form action='citiesdata.php' method='post' style='display: inline-block;'><input type='hidden' name='id' value='". $fetch1['id']."'><button type='submit' name='delete' onclick='return confirm(" . '"Are you sure you want to Delete?"' . "); return false'><span class='glyphicon glyphicon-trash'></span></button></form>";
    $data[] = array(
      "id" => $sr_no,
      "country" => $fetch1['country_name'],
      "state" => $fetch1['state_name'],
      "stn_code" => $fetch1['stn_code'],
      "city_name" => $fetch1['city_name'],
      "area_code" => $fetch1['area_code'],
      "zone_type" => $fetch1['zone_name'],
      "action" => $action
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