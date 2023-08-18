<?php
session_start();
require '../conn.php';
if (isset($_POST['getApiservice'])) {
    
    $api_title = mysqli_real_escape_string($con, $_POST['apiId']);
    $api_res = mysqli_fetch_assoc(mysqli_query($con,"SELECT * from third_party_apis where title='$api_title'"));
    $api_id = isset($api_res['id']) ? $api_res['id'] :'';
    $api_key = isset($api_res['api_key']) ? $api_res['api_key'] :'';
    $api_user_name = isset($api_res['user_name']) ? $api_res['user_name'] :'';
    $api_password = isset($api_res['password']) ? $api_res['password'] :'';
    $api_account_no = isset($api_res['account_no']) ? $api_res['account_no'] :'';
    $api_authorization = isset($api_res['authorization']) ? $api_res['authorization'] :'';
    if ($api_title == 'Leopards') {
        $serviceArray = ['Overnight','Detained','Overland'];
        foreach ($serviceArray as $key => $service) {
            echo '<option data-cityname="' . $service . '" value="'.$service.'" class="api_cities">' . $service . '</option>';
        }
    }
    if ($api_title == 'BlueEX') {
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://bigazure.com/api/json_v3/services/get_services.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST', 
        CURLOPT_HTTPHEADER => array(
            'Authorization: '.$api_authorization,
            'Content-Type: application/json'
        ),
        ));        
        $response = curl_exec($curl);
        curl_close($curl);
        $api_cities =  json_decode($response); 
        // echo $api_authorization; die;
        // print_r($api_cities);
        // die;
        $apiCitiesArray = $api_cities->response->detail;
        foreach ($apiCitiesArray as $key => $cityRes) {
            echo '<option data-cityname="' . $cityRes->service_code . '" value="'.$cityRes->service_code.'" class="api_cities">' . $cityRes->service_name . '</option>';
        }
    }




    
    if ($apId == 10) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://forrun.co/api/v1/getCities");
        // curl_setopt($ch, CURLOPT_GET, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


        $result1 = curl_exec($ch);
        curl_close($ch);
        /* echo "<pre>";
        print_r($result1);*/
        $data1 =  json_decode($result1);

        foreach ($data1 as $key1 => $value1) {

            foreach ($value1 as $val) {
                echo '<option data-cityname="' . $val . '" value="0"class="api_cities">' . $val . '</option>';
            }
        }
    }
}