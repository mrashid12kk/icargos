<?php

// die('nimra');
require 'includes/conn.php';
require 'includes/role_helper.php';

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

## Read value

$draw = $_POST['draw'];

$row = $_POST['start'];

$rowperpage = $_POST['length']; // Rows display per page

$columnIndex = $_POST['order'][0]['column']; // Column index

$columnName = $_POST['columns'][$columnIndex]['data']; // Column name

$columnSortOrder = 'desc'; // asc or desc

$searchValue = $_POST['search']['value']; // Search value


## Total number of records without filtering
$sql = "select count(*) as allcount from custom_tariff_pricing";
$sel = mysqli_query($con,$sql);
// var_dump($sql);
// die();
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

$query = "SELECT * FROM custom_tariff_pricing order by id asc LIMIT ".$rowperpage." OFFSET ".$row." ";

$empRecords = mysqli_query($con, $query);
// var_dump($empRecords);
// die();
// $filter = mysqli_fetch_array($empRecords);
// var_dump($filter);
// die();
$data = array();

$sr_no =$_POST['start']+1;
$i =0;

while ($fetch1 = mysqli_fetch_assoc($empRecords)) {
  $i++;
    $product_query = mysqli_query($con, "SELECT * FROM products WHERE id=" .$fetch1['product_id']);
    $product_fetch = mysqli_fetch_array($product_query);
    $product_name = $product_fetch['name']; 

    $service_query = mysqli_query($con, "SELECT * FROM services WHERE id='" . $fetch1['service_id'] . "' ");
    $services_fetch = mysqli_fetch_array($service_query);
    $service_type = $services_fetch['service_type'];

    $customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id='" . $fetch1['customer_id'] . "' ");
    $customers_fetch = mysqli_fetch_array($customer_query);
    // var_dump($customers_fetch)
    $customers = $customers_fetch['bname'];
  $data[] = array(

   "id"=>"<td class='all_brands'><div class='srno'>".$sr_no."</div></td>",
   "customername"=>"<td class='brand_info'>".$customers."</td>",

   "ProductType"=>"<td class='date_bx'>".$product_name."</td>",

   "service_type"=> "<td class='cod_box'>".$service_type."</td>",

   "origin"=>"<td class='client_info'>".$fetch1['origin']."</td>",

   "destination"=>"<td class='from_to1'>".$fetch1['destination']."</td>",
   "minweight" =>"<td class='from_to1'>".$fetch1['min_weight']."</td> ",
   "minweightprice" =>" <td class='from_to1'>".$fetch1['min_weight_price']."</td>",
   "additionalkgs" =>"<td class='from_to1'>".$fetch1['additional_kg']."</td> ",
   "additionalkgsprice" =>"<td class='from_to1'>".$fetch1['additional_kg_price']."</td> ",
   "action"=>"<td class='percel_box'>  <a href='custom_tariff_pricing.php?edit_id=".$fetch1['id']."'
                                                    <span class='glyphicon glyphicon-edit'></span>
                                                </a>
                                                <a href='custom_tariff_pricing.php?delete_id=".$fetch1['id']."'
                                                    onclick='return confirm('Are you sure you want to delete this Settlement Period?');'>
                                                    <span class='glyphicon glyphicon-trash'></span>
                                                </a></td>",
 );
  $sr_no ++;
}
$totalRecordwithFilter = $i;

$ret = array (
  'draw' => intval($draw),
  'recordsTotal' => $totalRecords,
  'recordsFiltered' => $totalRecords,
  'data' => $data
  
);
echo json_encode($ret);
