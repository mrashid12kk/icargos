<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
include_once "../includes/conn.php";
if (!function_exists('getTarifPrice')) {
    function getTarifPrice($product_id = null, $origin = null, $destination = null, $weight = 0, $customer_id = null, $order_type = null)
    {
        global $con; 
        define('SAME_ZONE_AUTO', 1);
        define('DIFFERENT_ZONE_CROSS_MAPPING', 2);
        define('MANUAL_ZONE_MAPPING', 3);
        define('SAME_CITY_AUTO', 4);
        define('MANUAL_CITY_MAPPING', 5);
        define('SAME_STATE_PROVINCE_AUTO', 6);
        define('DIFFERENT_STATE_PROVINCE_CROSS_MAPPING', 7);
        define('MANUAL_STATE_PROVINCE_MAPPING', 8);
        define('SAME_COUNTRY_AUTO', 9);
        define('DIFFERENT_COUNTRY_CROSS_MAPPING', 10);
        define('MANUAL_COUNTRY_MAPPING', 11);
        $SAME_ZONE_AUTO = SAME_ZONE_AUTO;
        $DIFFERENT_ZONE_CROSS_MAPPING = DIFFERENT_ZONE_CROSS_MAPPING;
        $MANUAL_ZONE_MAPPING = MANUAL_ZONE_MAPPING;
        $SAME_CITY_AUTO = SAME_CITY_AUTO;
        $MANUAL_CITY_MAPPING = MANUAL_CITY_MAPPING;
        $SAME_STATE_PROVINCE_AUTO = SAME_STATE_PROVINCE_AUTO;
        $DIFFERENT_STATE_PROVINCE_CROSS_MAPPING = DIFFERENT_STATE_PROVINCE_CROSS_MAPPING;
        $MANUAL_STATE_PROVINCE_MAPPING = MANUAL_STATE_PROVINCE_MAPPING;
        $SAME_COUNTRY_AUTO = SAME_COUNTRY_AUTO;
        $DIFFERENT_COUNTRY_CROSS_MAPPING = DIFFERENT_COUNTRY_CROSS_MAPPING;
        $MANUAL_COUNTRY_MAPPING = MANUAL_COUNTRY_MAPPING;
        // global $con, $SAME_ZONE_AUTO, $SAME_CITY_AUTO, $MANUAL_CITY_MAPPING, $DIFFERENT_ZONE_CROSS_MAPPING, $SAME_STATE_PROVINCE_AUTO, $MANUAL_ZONE_MAPPING, $DIFFERENT_STATE_PROVINCE_CROSS_MAPPING, $MANUAL_STATE_PROVINCE_MAPPING, $SAME_COUNTRY_AUTO, $DIFFERENT_COUNTRY_CROSS_MAPPING, $MANUAL_COUNTRY_MAPPING;
        if (!$origin || !$destination) {
            return 0;
            exit();
        }

        // Check Valid Origin and Destination
        $inValidOrigin = false;
        $inValidDestination  = false;
        $origin_in_q = mysqli_fetch_assoc(mysqli_query($con,"SELECT * from cities where city_name = '$origin'"));
        $inValidOrigin = isset($origin_in_q['city_name']) && $origin_in_q['city_name'] !="" ? false : true;
        $desti_in_q = mysqli_fetch_assoc(mysqli_query($con,"SELECT * from cities where city_name = '$destination'"));
        $inValidDestination = isset($desti_in_q['city_name']) &&  $desti_in_q['city_name'] !="" ? false : true;
        if($inValidOrigin){
            return 0;
            exit();
        }
        if($inValidDestination){
            return 0;
            exit();
        }
        // echo $origin.'next'.$destination.'next'.$weight.'next'.$customer_id.'next'.$order_type.'next'.$product_id;
        // die;
        // echo 'same city auto is' . $SAME_ZONE_AUTO;
        // die;
        $originZoneQuery = mysqli_query($con, "SELECT zone_type_id from cities where city_name = '" . $origin . "'");
        $originRes = mysqli_fetch_assoc($originZoneQuery);
        $originZoneId = isset($originRes['zone_type_id']) ? $originRes['zone_type_id'] : '';
        $destiZoneQuery = mysqli_query($con, "SELECT zone_type_id from cities where city_name = '" . $destination . "'");
        $destiZoneRes = mysqli_fetch_assoc($destiZoneQuery);
        $destiZoneId = isset($destiZoneRes['zone_type_id']) ? $destiZoneRes['zone_type_id'] : '';
        $c_tarif_sql = "SELECT * FROM customer_tariff_detail WHERE customer_id = $customer_id";
        $tarifCust_query = mysqli_query($con,$c_tarif_sql);
        $customer_tariff_ids = '';
        while($custRes=mysqli_fetch_assoc($tarifCust_query)){
            $customer_tariff_ids .=$custRes['tariff_id'].',';
        }
        $customer_tariff_ids = rtrim($customer_tariff_ids,',');
         // Get zone name form $originZoneId and $destiZoneId
        $originZonename_q = mysqli_query($con, "SELECT * from zone_type where id = " . $originZoneId);
        $destiZonename_q = mysqli_query($con, "SELECT * from zone_type where id = " . $destiZoneId);
        $originZonename = mysqli_fetch_assoc($originZonename_q);
        $destiZonename = mysqli_fetch_assoc($destiZonename_q);
        
        $different_zone_query = mysqli_query($con, "SELECT * from tariff_cities join tariff on tariff.id = tariff_cities.tariff_id where tariff_cities.origin = '" . $originZonename['zone_name'] ."' AND tariff_cities.destination = '" . $destiZonename['zone_name'] ."' AND tariff_cities.mappingFor='Zone Mapping' AND tariff.service_type = $order_type");
        $different_zone = mysqli_fetch_assoc($different_zone_query);
        //exist Get zone name form $originZoneId and $destiZoneId
        $price = 0;
        $customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $customer_id . " ");
        $customer_data = mysqli_fetch_array($customer_query);
        $customer_city = $customer_data['city'];
        $customer_type = $customer_data['customer_type'];
        $customerPaySql = "SELECT * FROM pay_mode WHERE account_type = '" . $customer_type . "'";
        $c_pay_mode_q = mysqli_query($con, $customerPaySql);
        $paymodeRes = mysqli_fetch_assoc($c_pay_mode_q);
        $customerPayMode = isset($paymodeRes['pay_mode']) ? $paymodeRes['pay_mode'] : '';
        $customerPayModeId = isset($paymodeRes['id']) ? $paymodeRes['id'] : '';
        // Mapping Starts from here
        // echo "SELECT * FROM tariff WHERE pay_mode = $customerPayModeId AND product_id = $product_id AND service_type  = $order_type AND tariff_mapping_id = $SAME_CITY_AUTO";
        // die;
        $getDefineTarif = mysqli_query($con, "SELECT * FROM tariff WHERE pay_mode = $customerPayModeId AND product_id = $product_id AND service_type  = $order_type AND tariff_mapping_id = $SAME_CITY_AUTO");
        
        $countrow = mysqli_num_rows($getDefineTarif);
        if ($countrow > 0 && $origin == $destination) {
            $tariff_data = mysqli_fetch_array($getDefineTarif);
            $tariff_id = isset($tariff_data['id']) ? $tariff_data['id'] : '';
            // $price = getSameCityPrice($tariff_id, $weight, $product_id);
            $price = priceForAll($customer_id,$tariff_id, $weight, $product_id);
            return $price;
            exit();
        }
        $getDefineTarifManualZone = mysqli_query($con, "SELECT * FROM tariff WHERE pay_mode = $customerPayModeId AND product_id = $product_id AND service_type  = $order_type AND tariff_mapping_id = $MANUAL_ZONE_MAPPING");
        // echo "SELECT * FROM tariff WHERE pay_mode = $customerPayModeId AND product_id = $product_id AND service_type  = $order_type AND tariff_mapping_id = $MANUAL_ZONE_MAPPING";
        // die;
        // echo "<pre>";
        // print_r($different_zone);
        // die;
        $countrow_manual_zone = mysqli_num_rows($getDefineTarifManualZone);
        if ($countrow_manual_zone > 0) {
            if (isset($different_zone) && !empty($different_zone)) {            
                $tariff_data = mysqli_fetch_array($getDefineTarifManualZone);
                $tariff_id = isset($different_zone['tariff_id']) ? $different_zone['tariff_id'] : '';
                // $price = getManualZone($tariff_id, $weight, $product_id);
                $price = priceForAll($customer_id,$tariff_id, $weight, $product_id);
                return $price;
                exit();
            }
        }
        $getDefineTarifSameZone = mysqli_query($con, "SELECT * FROM tariff WHERE pay_mode = $customerPayModeId AND product_id = $product_id AND service_type  = $order_type AND tariff_mapping_id = $SAME_ZONE_AUTO");
        $countrow_same_zone = mysqli_num_rows($getDefineTarifSameZone);
        if ($countrow_same_zone > 0 && $originZoneId == $destiZoneId) {
            $tariff_data = mysqli_fetch_array($getDefineTarifSameZone);
            $tariff_id = isset($tariff_data['id']) ? $tariff_data['id'] : '';
            // $price = getSameZone($tariff_id, $weight, $product_id);
            $price = priceForAll($customer_id,$tariff_id, $weight, $product_id);
            return $price;
            exit();
        }
        
        $getDefineTarifDifferentZone = mysqli_query($con, "SELECT * FROM tariff WHERE pay_mode = $customerPayModeId AND product_id = $product_id AND service_type  = $order_type AND tariff_mapping_id = $DIFFERENT_ZONE_CROSS_MAPPING");
        $countrow_different_zone = mysqli_num_rows($getDefineTarifDifferentZone);
        if ($countrow_different_zone > 0) {
            // echo "HERE";
            // die;
            $tariff_data = mysqli_fetch_array($getDefineTarifDifferentZone);
            $tariff_id = isset($tariff_data['id']) ? $tariff_data['id'] : '';
            // $price = getDifferentZone($tariff_id, $weight, $product_id);
            $price = priceForAll($customer_id,$tariff_id, $weight, $product_id);
            return $price;
            exit();
        }
        $getDefineTarifManualCity = mysqli_query($con, "SELECT * FROM tariff WHERE pay_mode = $customerPayModeId AND product_id = $product_id AND service_type  = $order_type AND tariff_mapping_id = $MANUAL_CITY_MAPPING");
        $countrow_manual_city = mysqli_num_rows($getDefineTarifManualCity);
        if ($countrow_manual_city > 0) {
            // echo "here";
            // die;
            $tariff_data = mysqli_fetch_array($getDefineTarifManualCity);
            $tariff_id = isset($tariff_data['id']) ? $tariff_data['id'] : '';
            // $price = getManuallCity($tariff_id, $weight, $product_id);
            $price = priceForAll($customer_id,$tariff_id, $weight, $product_id);
            return $price;
            exit();
        }
        
        
        $getDefineTarifSameState = mysqli_query($con, "SELECT * FROM tariff WHERE pay_mode = $customerPayModeId AND product_id = $product_id AND service_type  = $order_type AND tariff_mapping_id = $SAME_STATE_PROVINCE_AUTO");
        $countrow_same_state = mysqli_num_rows($getDefineTarifSameState);
        if ($countrow_same_state > 0) {
            $tariff_data = mysqli_fetch_array($getDefineTarifSameState);
            $tariff_id = isset($tariff_data['id']) ? $tariff_data['id'] : '';
            // $price = getSameProvince($tariff_id, $weight, $product_id);
            $price = priceForAll($customer_id,$tariff_id, $weight, $product_id);
            return $price;
            exit();
        }
        $getDefineTarifDifferentStateProvinceCross = mysqli_query($con, "SELECT * FROM tariff WHERE pay_mode = $customerPayModeId AND product_id = $product_id AND service_type  = $order_type AND tariff_mapping_id = $DIFFERENT_STATE_PROVINCE_CROSS_MAPPING");
        $countrow_different_state_province_cross = mysqli_num_rows($getDefineTarifDifferentStateProvinceCross);
        if ($countrow_different_state_province_cross > 0) {
            $tariff_data = mysqli_fetch_array($getDefineTarifDifferentStateProvinceCross);
            $tariff_id = isset($tariff_data['id']) ? $tariff_data['id'] : '';
            // $price = getDifferentStateProvinceCross($tariff_id, $weight, $product_id);
            $price = priceForAll($customer_id,$tariff_id, $weight, $product_id);
            return $price;
            exit();
        }
        $getDefineTarifManualStateProvinceMapping = mysqli_query($con, "SELECT * FROM tariff WHERE pay_mode = $customerPayModeId AND product_id = $product_id AND service_type  = $order_type AND tariff_mapping_id = $MANUAL_STATE_PROVINCE_MAPPING");
        $countrow_manual_state_province_mapping = mysqli_num_rows($getDefineTarifManualStateProvinceMapping);
        if ($countrow_manual_state_province_mapping > 0) {
            $tariff_data = mysqli_fetch_array($getDefineTarifManualStateProvinceMapping);
            $tariff_id = isset($tariff_data['id']) ? $tariff_data['id'] : '';
            // $price = getManualStateProvinceMapping($tariff_id, $weight, $product_id);
            $price = priceForAll($customer_id,$tariff_id, $weight, $product_id);
            return $price;
            exit();
        }
        $getDefineTarifSameCountryAuto = mysqli_query($con, "SELECT * FROM tariff WHERE pay_mode = $customerPayModeId AND product_id = $product_id AND service_type  = $order_type AND tariff_mapping_id = $SAME_COUNTRY_AUTO");
        $countrow_same_country_auto = mysqli_num_rows($getDefineTarifSameCountryAuto);
        if ($countrow_same_country_auto > 0) {
            $tariff_data = mysqli_fetch_array($getDefineTarifSameCountryAuto);
            $tariff_id = isset($tariff_data['id']) ? $tariff_data['id'] : '';
            // $price = getSameCountryAuto($tariff_id, $weight, $product_id);
            $price = priceForAll($customer_id,$tariff_id, $weight, $product_id);
            return $price;
            exit();
        }
        $getDefineTarifDifferentCountryCrossMapping = mysqli_query($con, "SELECT * FROM tariff WHERE pay_mode = $customerPayModeId AND product_id = $product_id AND service_type  = $order_type AND tariff_mapping_id = $DIFFERENT_COUNTRY_CROSS_MAPPING");
        $countrow_differen_country_cross_mapping = mysqli_num_rows($getDefineTarifDifferentCountryCrossMapping);
        if ($countrow_differen_country_cross_mapping > 0) {
            $tariff_data = mysqli_fetch_array($getDefineTarifDifferentCountryCrossMapping);
            $tariff_id = isset($tariff_data['id']) ? $tariff_data['id'] : '';
            // $price = getDifferentCountryCrossMaping($tariff_id, $weight, $product_id);
            $price = priceForAll($customer_id,$tariff_id, $weight, $product_id);
            return $price;
            exit();
        }
        $getDefineTarifManualCountryMaping = mysqli_query($con, "SELECT * FROM tariff WHERE pay_mode = $customerPayModeId AND product_id = $product_id AND service_type  = $order_type AND tariff_mapping_id = $MANUAL_COUNTRY_MAPPING");
        $countrow_manual_country_maping = mysqli_num_rows($getDefineTarifManualCountryMaping);
        if ($countrow_manual_country_maping > 0) {
            $tariff_data = mysqli_fetch_array($getDefineTarifManualCountryMaping);
            $tariff_id = isset($tariff_data['id']) ? $tariff_data['id'] : '';
            // $price = getManualCountryMaping($tariff_id, $weight, $product_id);
            $price = priceForAll($customer_id,$tariff_id, $weight, $product_id);
            return $price;
            exit();
        }
    }
}
if (!function_exists('priceForAll')) {
    function priceForAll($customer_id,$tariff_id = null, $weight = 0, $product_id = null)
    {
        global $con; 
        if ($tariff_id != null) {
            $priceFrom = 'customer';

            $mainTarifSql = "SELECT * FROM customer_tariff_detail WHERE tariff_id = $tariff_id AND customer_id = $customer_id Order by id asc";
            
            $tariff_detail_query = mysqli_query($con, $mainTarifSql);
            $customer_detail_rows = mysqli_num_rows($tariff_detail_query);
            if ($customer_detail_rows == 0) {
                $priceFrom = 'tariff';
                $mainTarifSql = "SELECT * FROM tariff_detail WHERE tariff_id = $tariff_id";
                $tariff_detail_query = mysqli_query($con, $mainTarifSql);
            } 
            $customer_detail_rows = mysqli_num_rows($tariff_detail_query);
            if ($customer_detail_rows == 0) {
                return 0;
                exit;
            }
            $price_s = 0;
            //  echo "SELECT * FROM tariff_detail WHERE tariff_id = $tariff_id";die();
            while ($row = mysqli_fetch_array($tariff_detail_query)) {
                $start_range = isset($row['start_range']) ? $row['start_range'] : '';
                $end_range = isset($row['end_range']) ? $row['end_range'] : '';
                if ($weight >= $start_range and $weight <= $end_range) {
                    $checkFactor = checkDivisionFactors($start_range, $end_range, $product_id); 
                    $division_factor = $checkFactor['division_factor'];
                    $type_id = $checkFactor['type_id'];
                    $price_s = isset($row['rate']) ? $row['rate'] : '';
                    if (isset($division_factor) && $division_factor > 0) {
                        $id = $row['id'];
                        $lastid = $id - 1;
                        if ($priceFrom == 'customer') {

                            $t_query_sql = "SELECT * FROM customer_tariff_detail WHERE id = $lastid AND customer_id = $customer_id ORDER BY id ASC";

                        }else{
                            $t_query_sql = "SELECT * FROM tariff_detail WHERE id = $lastid";
                        } 
                        $t_detail_query = mysqli_query($con, $t_query_sql);
                        $fetch_row = mysqli_fetch_assoc($t_detail_query);
                        $lastRate = $fetch_row['rate']; // second Last Row Rate
                        $lastEndRange = $fetch_row['end_range'];
                        $divisionFactorRate = $row['rate']; // Rate with division factor
                        $extraWeight = $weight - $lastEndRange;
                        $totalExtraWeight = $extraWeight / $division_factor;
                        $wholeWeight = floor($totalExtraWeight);      // 1
                        $fraction = $totalExtraWeight - $wholeWeight;
                        $fractionPrice = 0;
                        if ($fraction > 0) {
                            $fractionPrice = $divisionFactorRate;
                        }
                        $wholePrice = 0;
                        if ($wholeWeight > 0) {
                            $wholePrice = $divisionFactorRate * $wholeWeight;
                        }
                        $price_s = $wholePrice + $fractionPrice + $lastRate;
                    }
                    break;
                }
            }
            return round($price_s);
        }
    }
}
if (!function_exists('checkDivisionFactors')) {
    function checkDivisionFactors($start_range, $end_range, $product_id)
    {
        global $con;
        if ($product_id != null) {
            $types_query = "SELECT * FROM product_type_prices WHERE product_id = " . $product_id . " AND start_range = " . $start_range . " AND end_range = " . $end_range . "";
            // echo $types_query;
            // die;
            $product_price_query = mysqli_query($con, $types_query);
            $returnArray = array();
            //  echo "SELECT * FROM tariff_detail WHERE tariff_id = $tariff_id";die();
            $row = mysqli_fetch_array($product_price_query);
            $division_factor_exist = isset($row['division_factor']) ? $row['division_factor'] : 0;
            $type_id = isset($row['id']) ? $row['id'] : 0;
            $returnArray['division_factor'] = $division_factor_exist;
            $returnArray['type_id'] = $type_id;
            return $returnArray;
        }
    }
}