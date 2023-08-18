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
$country_name = $_POST['country_name'];
$state_id = $_POST['state_id'];
// $date_to = $_POST['date_to'];

// $number = $_POST['number'];

// $message = $_POST['message'];

// $customer = $_POST['customer'];

// $datetime = $_POST['datetime'];


$searchQuery = "";
if($country_name != ''){

    $searchQuery .= " and (country.id like '%".$country_name."%' or

      country.country_name like '%".$country_name."%' or

      state.state_name like '%".$country_name."%' or

      city.city_name like '%".$country_name."%'

        ) ";

}
if($state_id != ''){

   $searchQuery .= " AND (city.state_id='".$state_id."') ";

}
if($searchValue != ''){

   $searchQuery .= " and (country.id like '%".$searchValue."%' or

      country.country_name like '%".$searchValue."%' or

      state.state_name like '%".$searchValue."%' or

      city.city_name like '%".$searchValue."%'

        ) ";
}

## Total number of records without filtering
$sel = mysqli_query($con,"SELECT count(*) as allcount FROM state");

$records = mysqli_fetch_assoc($sel);

$totalRecords = $records['allcount'];

# Total number of records with filtering
// echo "SELECT count(*) as allcount,customers.bname as businessname FROM sms_detail inner join customers on sms_detail.contact_id=customers.id WHERE 1 ".$searchQuery;
// die;
$sel = mysqli_query($con,"SELECT count(*) as allcount FROM city INNER JOIN country on city.country_id=country.id INNER JOIN state on city.state_id=state.id WHERE 1 ".$searchQuery."");
$records = mysqli_fetch_assoc($sel);

$totalRecordwithFilter = $records['allcount'];

## Fetch records

$empQuery = "SELECT country.country_name,country.id as county_id,city.id as city_id,state.state_name,state.id as state_id,city.city_name FROM city INNER JOIN country on city.country_id=country.id INNER JOIN state on city.state_id=state.id WHERE 1 ".$searchQuery." order by city_id  ".$columnSortOrder." limit ".$row.",".$rowperpage;
// echo $empQuery;
// die;
$empRecords = mysqli_query($con, $empQuery);

$data = array();

$sr_no =$row+1;

while ($fetch1 = mysqli_fetch_assoc($empRecords)) {
   $flag='';
   if (isset($fetch1['image']) && $fetch1['image']!='') {
      $flag="<img src='".$fetch1['image']."' class='circle' style='width: 100px;'>";
   }
   $data[] = array(
      "id"=>$sr_no++,
      "country"=>'<a href="route_list.php?country_id='.$fetch1['county_id'].'" class="">'.$fetch1['country_name'].'</i></a>',
       "state"=>'<a href="route_list.php?state_id='.$fetch1['state_id'].'" class="">'.$fetch1['state_name'].'</i></a>',
        "city"=>'<a href="route_list.php?city_id='.$fetch1['city_id'].'" class="">'.$fetch1['city_name'].'</i></a>',
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
