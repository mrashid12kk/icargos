<?php

require 'includes/conn.php';

$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length'];
$columnIndex = $_POST['order'][0]['column'];
$columnName = $_POST['columns'][$columnIndex]['data'];
$searchValue = $_POST['search']['value'];



$from = $_POST['from'];
$to = $_POST['to'];
$customer=$_POST['customer'];
$saleman=$_POST['saleman'];

$where="";

#search

if (isset($searchValue) && $searchValue != '') {
    $searchQuery .= " And
    customers.bname like '%".$searchValue."%' or
    customers.fname like '%".$searchValue."%' or
    users.Name like '%".$searchValue."%'
    ";
    $where.=$searchQuery;
}

#search

$orderby=" GROUP BY orders.customer_id";
$where.= " and DATE_FORMAT(`orders`.`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`orders`.`order_date`, '%Y-%m-%d') <= '".$to."' ";
if (isset($customer) && $customer != '') {
    $where .= " AND orders.customer_id=" . $customer;
}
if (isset($saleman) && $saleman != '') {
    $where .= " AND customers.sale_man_id=" . $saleman;
}
$where.=$orderby;

// echo $where.'<br>';

## Total number of records without filtering
$sel = mysqli_query($con, "SELECT count(*) AS allcount from orders INNER JOIN customers ON customers.id=orders.customer_id INNER JOIN users ON users.id=customers.sale_man_id");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];


## Total number of records with filtering

$sel = mysqli_query($con, "SELECT SUM(orders.quantity) AS no_of_parcels,SUM(orders.price) AS total_price,SUM(orders.fuel_surcharge) AS total_fuel_surcharge,users.Name AS saleman,customers.fname,customers.bname from orders INNER JOIN customers ON customers.id=orders.customer_id  INNER JOIN users ON users.id=customers.sale_man_id WHERE 1 AND orders.status!='cancelled' ".$where);
$totalRecordwithFilter = mysqli_num_rows($sel);


## Fetch records
if ($rowperpage == -1) {
    $countQuery = mysqli_query($con, "SELECT COUNT(id) AS id FROM orders WHERE 1 " . $searchQuery . "");
    $totalRow = mysqli_fetch_assoc($countQuery);
    $rowperpage = $totalRow['id'];
}
    $empQuery = "SELECT SUM(orders.quantity) AS no_of_parcels,SUM(orders.price) AS total_price,SUM(orders.fuel_surcharge) AS total_fuel_surcharge,users.Name AS saleman,customers.fname,customers.bname from orders INNER JOIN customers ON customers.id=orders.customer_id  INNER JOIN users ON users.id=customers.sale_man_id WHERE 1 AND orders.status!='cancelled' ".$where." LIMIT ".$row.",".$rowperpage; //msla idr ha


    $empRecords = mysqli_query($con, $empQuery);
    $data = array();
    $sr_no = 1;
    if ($empRecords) {
        while ($fetch1 = mysqli_fetch_assoc($empRecords)) {
            $data[] = array(
                "srno" => $sr_no,
                "businessname" => $fetch1['fname'].' ('.$fetch1['bname'].')',
                "saleman"=>$fetch1['saleman'],
                "parcels"=>$fetch1['no_of_parcels'],
                "service_charges"=>$fetch1['total_price'],
                "fuel_surcharge"=>$fetch1['total_fuel_surcharge'],
                "deliveryprice"=>$fetch1['total_price'] + $fetch1['total_fuel_surcharge'],
                "action"=>"<form action='".BASE_URL."admin/charges_report.php' method='post'><input type='hidden' name='customer_filter' value='".$customer."'><input type='hidden' name='from_filter' value='".$from."'><input type='hidden' name='to_filter' value='".$to."'><button type='submit' name='submit_filter' class='btn_stye_custom'>View Detail<span class='glyphicon glyphicon-eye-open'></span></button></form>"
            );
            $sr_no++;
        }
    }
    $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
    );

    echo json_encode($response);
