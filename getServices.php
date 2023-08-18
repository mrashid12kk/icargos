<?php
include_once "includes/conn.php";
// var_dump($_POST);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$edit = 0;
        if(isset($_POST['updatesettlement_period']))
        {
            $edit = $_POST['updatesettlement_period'];
        }
if (isset($_POST['is_product'])) {
        // $service = [];
        $product_type_id = isset($_POST['product_type_id']) ? $_POST['product_type_id'] : '';
        $customer_id = isset($_POST['customer_id']) ? $_POST['customer_id'] : '';
        $c_tarif_sql = "SELECT * FROM services where product_id =".$_POST['product_type_id'];
        $tarifCust_query = mysqli_query($con,$c_tarif_sql);
        while ($row = mysqli_fetch_array($tarifCust_query)) {
               $service = $row['service_type'];
               $id = $row['id'];
        }
        // var_dump($service);
if($edit){
    $sql = "SELECT * FROM custom_tariff_pricing where id=".$edit;
    $query = mysqli_query($con, $sql);
    $fetch = mysqli_fetch_array($query);
    $sid = $fetch['service_id'];
}
// foreach ($service  as $key => $r) {
if(isset($service)){
    $selected = (isset($edit) && $sid == $id )?'selected':'';
    echo '<option value="0">Select Service</option>';
    echo '<option value="'.$id.'" '.$selected.'>'.$service.'</option>';
}else{
       echo '<option value="0">Select Service</option>';
 
}
}