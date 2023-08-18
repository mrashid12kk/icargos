<?php
if (file_exists('language_helper.php')){
    include 'language_helper.php';
}else if (file_exists('../language_helper.php')) {
    include '../language_helper.php';
}else if (file_exists('../../language_helper.php')) {
    include '../../language_helper.php';
}else if (file_exists('../../../language_helper.php')){
    include '../../../language_helper.php';
}else if (file_exists('../../../../language_helper.php')){
    include '../../../../language_helper.php';
}
if(!function_exists('getConfig'))
{
function getConfig($name) {
    global $con;
    $result = mysqli_query($con, "SELECT * FROM config WHERE name = '$name'");
    return mysqli_fetch_assoc($result)['value'];
}
}
if(!function_exists('getKeyWord'))
{
    function getKeyWord($name) {
        global $con;
        $names = trim($name);
        $sql =  "SELECT * FROM `language_translator` WHERE keyword = '$names' AND language_id = ".$_SESSION['language_id']." order by id desc";
        $result = mysqli_query($con, $sql);
        $response =  mysqli_fetch_array($result);
        if ($response['translation']) {
            return $response['translation'];
        }else{
            return $name;
            // return 'not translated';
        }
    }
}
if (!function_exists('getmulti_user')){
        function getmulti_user() {
            global $con;
            $sql =  "SELECT * FROM `customers` WHERE id = ".$_SESSION['customers'];
            $result = mysqli_query($con, $sql);
            $response =  mysqli_fetch_array($result);
            if ($response['multi_user']) {
                return $response['multi_user'];
            }else{
                return '';
            }
        }
    }
if (!function_exists('getKeyWordCustomer')){
    function getKeyWordCustomer($id,$name) {
        global $con;
        $names = trim($name);
        $customer_data = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM customers WHERE id=".$id));
        $sql =  "SELECT * FROM `language_translator` WHERE keyword = '$names' AND language_id = ".$customer_data['language_priority']." order by id desc";
        $result = mysqli_query($con, $sql);
        $response =  mysqli_fetch_array($result);
        if ($response['translation']) {
            return $response['translation'];
        }else{
            return $name;
            // return 'not translated';
        }
    }
}
if (!function_exists("custom_track_numbers")) {
    function custom_track_numbers($customer_id)
    {
        global $con;
        $next_number = 0;
        $customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $customer_id . " ");
        $customer_data = mysqli_fetch_array($customer_query);
        $client_code = isset($customer_data['client_code']) ? $customer_data['client_code'] : '';
        $customer_city = isset($customer_data['city']) ? $customer_data['city'] : '';
        $cityQ = mysqli_fetch_assoc(mysqli_query($con, "SELECT area_code from cities where city_name='$customer_city'"));
        $area_code = isset($cityQ['area_code']) ?  $cityQ['area_code'] : '';
        $nextNoSql = "SELECT track_no  from custom_track_nos where customer_id=$customer_id";
        $nextNoQ = mysqli_query($con, $nextNoSql);
        $nextNoRes = mysqli_fetch_assoc($nextNoQ);
        $nextNo = isset($nextNoRes['track_no']) ? intval($nextNoRes['track_no']) : '';
        if (isset($nextNo) && !empty($nextNo) && $nextNo > 0) {
            $next_number = $nextNo + 1;
            $track_no_sql = "UPDATE custom_track_nos set track_no = '$next_number' WHERE customer_id = $customer_id";
        } else {
            $next_number = 1;
            $track_no_sql  = "INSERT INTO `custom_track_nos`(`track_no`, `customer_id`) VALUES ('" . $next_number . "',$customer_id)";
        }
        $get_number = $client_code;
        $nextnumber = $get_number.sprintf("%04d", $next_number);
        $track_number = $area_code . $nextnumber;
        $tracking_no = intval($track_number);
        mysqli_query($con, $track_no_sql);
        return intval($tracking_no);
    }
}
if (!function_exists("getFuelValue")){
    function getFuelValue($customer_id=null)
    {
        global $con;
        $return_val=0;
        $config_has = getConfig('fuel_surcharge');
        $config_says = getConfig('customer_fuel_charge');
        if (isset($config_says) && $config_says == 0) {
            $return_val = $config_has;
        }else{
            $customer_query = mysqli_query($con,"SELECT * FROM customers WHERE id=".$customer_id);
            $customer_data = mysqli_fetch_array($customer_query);
            if(isset($customer_data['is_fuelsurcharge']) && $customer_data['is_fuelsurcharge'] == 0)
            {
               $return_val = 0;
            }else{
                $fuel_sur_value = mysqli_fetch_array(mysqli_query($con,"SELECT charge_value FROM customer_wise_charges WHERE customer_id = ".$customer_id." AND charge_name = 'fuel_surcharge' "));
                $fuel_charge_value = isset($fuel_sur_value['charge_value']) ? $fuel_sur_value['charge_value'] : 0;
                $return_val = $fuel_charge_value;
            }
        }
        return $return_val;
    }
}
if (!function_exists("checkOrdersLimit")){
    function checkOrdersLimit()
    {
        global $con; 
        $limit_enable = getConfig('enable_orders_limit');
        $orders_limit = getConfig('orders_limit');
        $limit_message = getConfig('limit_message');
        if (isset($limit_enable) && $limit_enable==1) {
            return $orders_limit;
        }else{
            return false;
        }
    }
}
if (!function_exists("disable_customer_type_field")){
    function disable_customer_type_field()
    {
         global $con; 
         $customer_type=$_SESSION['customer_type'];
        $account_types_query = mysqli_query($con,"SELECT * FROM account_types WHERE id=".$customer_type);
        $account_types_data = mysqli_fetch_array($account_types_query);
        if(isset($account_types_data['account_type']) && $account_types_data['account_type'] == 'Walk in Customer')
        {
            return true;
        }else{
            return false;
        }
    }
}
if (!function_exists("tariff_service_types")){
    function tariff_service_types($customer_id)
    {
        global $con; 
        $c_tarif_sql = "SELECT * FROM customer_tariff_detail WHERE customer_id = $customer_id";
        $tarifCust_query = mysqli_query($con,$c_tarif_sql);
        $customer_tariff_ids = '';
        while($custRes=mysqli_fetch_assoc($tarifCust_query)){
            $customer_tariff_ids .=$custRes['tariff_id'].',';
        }
        $customer_tariff_ids = rtrim($customer_tariff_ids,',');
        $c_mapping_id_sql = "SELECT * FROM tariff WHERE id IN ($customer_tariff_ids)";
        $mapping_query = mysqli_query($con,$c_mapping_id_sql);
        $customer_mapping_ids = '';
        while($mapRes=mysqli_fetch_assoc($mapping_query)){
            $customer_mapping_ids .=$mapRes['tariff_mapping_id'].',';
        }
        $customer_mapping_ids = rtrim($customer_mapping_ids,',');
    }
}