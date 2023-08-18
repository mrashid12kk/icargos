<?php
include_once "includes/conn.php";
if (isset($_POST['is_product'])) {
        $response = [];
        $service = [];
        $product_type_id = isset($_POST['product_type_id']) ? $_POST['product_type_id'] : '';
        $customer_id = isset($_POST['customer_id']) ? $_POST['customer_id'] : '';
        $c_tarif_sql = "SELECT * FROM customer_tariff_detail WHERE customer_id = $customer_id";
        $tarifCust_query = mysqli_query($con,$c_tarif_sql);
        $customer_tariff_ids = '';
        while($custRes=mysqli_fetch_assoc($tarifCust_query)){
            $customer_tariff_ids .=$custRes['tariff_id'].',';
        }
        $customer_tariff_ids = rtrim($customer_tariff_ids,',');
        $c_mapping_id_sql = "SELECT * FROM tariff WHERE id IN ($customer_tariff_ids)";
        $mapping_query = mysqli_query($con,$c_mapping_id_sql);
        $customer_service_ids = '';
        while($mapRes=mysqli_fetch_assoc($mapping_query)){
            $customer_service_ids .=$mapRes['service_type'].',';
        }
        $customer_service_ids = rtrim($customer_service_ids,',');

        $config_says = getConfig('tariff_type');
        if($config_says==2){
                $sql =  "SELECT service_type FROM tariff WHERE product_id = " . $product_type_id . " AND service_type IN ($customer_service_ids) GROUP BY service_type ORDER BY id DESC";
        }
        if($config_says==1){
                $sql =  "SELECT service_type FROM tariff WHERE product_id = " . $product_type_id . " GROUP BY service_type ORDER BY id DESC";
        }
        
        // echo $sql;
        // die();
        $result = mysqli_query($con, $sql);
        while ($row = mysqli_fetch_array($result)) {
                if (isset($row['service_type'])) {
                        $row = (object)$row;
                        $id = $row->service_type;
                        $services_sql =  "SELECT * FROM services WHERE id = " . $id . " ORDER BY id DESC";
                        // echo $services_sql;
                        // die;
                        $services_result = mysqli_query($con, $services_sql);
                        $single = mysqli_fetch_assoc($services_result);
                        // echo '<pre>', print_r($single), '</pre>';
                        // exit();
                        $row->id = $id;
                        $row->service_type = $single['service_type'];
                        array_push($service, $row);
                }
        }
        $response['service'] = $service;
        echo json_encode($response);
        exit();
}