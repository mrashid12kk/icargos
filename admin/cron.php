<?php
require 'includes/conn.php';
require 'includes/setting_helper.php';
require 'phpmailer/PHPMailerAutoload.php';
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
if (!function_exists("update_api_status")) {
    function update_api_status($api_name, $api_id, $status, $api_row)
    {
        global $con;
        $status_mapper = mysqli_query($con, "SELECT *  from third_party_api_status_mapping WHERE api_status='$status' AND api_provider_id = '$api_name'");
        if (!$status_mapper) {
            echo ("Error description: " . mysqli_error($con));
            exit();
        }
        //   echo $status;
        $status_map_res = mysqli_fetch_array($status_mapper);
        
        $portal_status = $status_map_res['status_id'] ? $status_map_res['status_id'] : $status;
        $action_date = date('Y-m-d H:i:s');
        $orderID = $api_row['id'];
        $update_orders = mysqli_query($con, "UPDATE orders set status = '$portal_status',action_date='$action_date' where id = '$orderID'");
        // echo "UPDATE orders set status = '$portal_status',action_date='$action_date' where id = '$orderID'";
        // die;
        $track_no = $api_row['track_no'];
        $SELECT_log_q = mysqli_query($con, "SELECT id from order_logs where order_no = '$track_no' AND order_status = '$portal_status'");
        $log_result = mysqli_fetch_assoc($SELECT_log_q);
        $log_id = isset($log_result['id']) ? $log_result['id'] : '';
        $user_id = $_SESSION['users_id'] ? $_SESSION['users_id'] : 100;
        if (empty($log_id)) {
            mysqli_query($con, "INSERT into cron_job_logs (order_id,api_id,api_status,site_status,status_updated,cron_activity_id ) values ('$track_no','$api_id','$status','$portal_status','$portal_status','0')");

            $insert_log = mysqli_query($con, "INSERT into order_logs (user_id,order_no,order_status,created_on) values ('$user_id','$track_no','$portal_status','$action_date')");
            if (!$insert_log) {
                echo ("Error description: " . mysqli_error($con));
                exit();
            }
        }
    }
}
$total_orders_requested = 0;
$total_matched = 0;
$total_unmatched = 0;

$status_mapper = mysqli_query($con, "SELECT *  from third_party_api_status_mapping");
$apis_query = mysqli_query($con, "SELECT * from third_party_apis where status = 1");

if (!$apis_query) {
    echo ("Error description: " . mysqli_error($con));
    exit();
} else {
    while ($api_response = mysqli_fetch_assoc($apis_query)) {
        $api_id = $api_response['id'];
        $api_name  = $api_response['title'];
        $authorization  = $api_response['authorization'];
        $account_no  = $api_response['account_no'];
        $password  = $api_response['password'];
        $user_name  = $api_response['user_name'];
        $api_key  = $api_response['api_key'];
        if ($api_name == 'Movex') {
            $api_orders_q = mysqli_query($con, "SELECT id,status,track_no,api_id,api_tracking_no from orders where status != 'Return In Process' AND status != 'Delivered' AND status != 'Returned to Shipper' AND status != 'Discarded' AND api_id = '$api_id'");
            if (!$api_orders_q) {
                echo ("No Order Found for $api_name Error description: " . mysqli_error($con));
                exit();
            }
            $requested_orders_count = mysqli_num_rows($api_orders_q);
            // echo $requested_orders_count;
            // die;
            while ($api_row = mysqli_fetch_array($api_orders_q)) {
                $status_matched_orders[] = $api_row;
                $tracking_number = $api_row['api_tracking_no'];
                $curl = curl_init();        
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://tracking.movexpk.com/api/consignment/current-status?consignment_numbers=' . $tracking_number,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        // 'Authorization: KlrN7Dhub195ZocnCLIOF6p8',
                        // 'Cookie: ci_session=d7b8c1cbc737fb444529323b5778be2c'
                    ),
                ));        
                $response = curl_exec($curl);        
                curl_close($curl);
                // echo $response;
                $data =  json_decode($response);

                $status = '';
                $success_data = $data->response;
                foreach ($success_data as $value) {
                    foreach ($value as $sts) {
                        $status = $sts->consignment_data->status;
                    }
                }
                update_api_status($api_name,$api_id,$status,$api_row);
            }            
        }
        if($api_name=='BlueEX'){

            $api_orders_q = mysqli_query($con, "SELECT id,status,track_no,api_id,api_tracking_no from orders where status != 'Return In Process' AND status != 'Delivered' AND status != 'Returned to Shipper' AND status != 'Discarded' AND api_id = '$api_id'");
            if (!$api_orders_q) {
                echo ("No Order Found for $api_name Error description: " . mysqli_error($con));
                exit();
            }
            $requested_orders_count = mysqli_num_rows($api_orders_q);
            // echo $requested_orders_count;
            // die;
            while ($api_row = mysqli_fetch_array($api_orders_q)) {
                $status_matched_orders[] = $api_row;
                $tracking_number = $api_row['api_tracking_no'];
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://bigazure.com/api/json_v3/status/get_status.php',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => '{"consignment_no": "'.$tracking_number.'"}',
                    CURLOPT_HTTPHEADER => array(
                        'Authorization:'.$authorization,
                        'Content-Type: application/json'
                    ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
                $data = json_decode($response);
                // print_r($data);
                // die;
                if ($data->status == 1) { 
                   $updated_status_name = $data->response;
                   update_api_status($api_name,$api_id,$updated_status_name,$api_row);
                }

            } 
        }
        if ($api_name == 'Leopards') {

            $api_orders_q = mysqli_query($con, "SELECT id,status,track_no,api_id,api_tracking_no from orders where status != 'Return In Process' AND status != 'Delivered' AND status != 'Returned to Shipper' AND status != 'Discarded' AND api_id = '$api_id'");
            if (!$api_orders_q) {
                echo ("No Order Found for $api_name Error description: " . mysqli_error($con));
                exit();
            }
            $requested_orders_count = mysqli_num_rows($api_orders_q);
             
            while ($api_row = mysqli_fetch_array($api_orders_q)) {
                $status_matched_orders[] = $api_row;
                $tracking_number = $api_row['api_tracking_no'];
                $curl_handle = curl_init();
                curl_setopt($curl_handle, CURLOPT_URL, 'http://new.leopardscod.com/webservice/trackBookedPacket/format/json/');  // Write here Test or Production Link
                curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl_handle, CURLOPT_POST, 1);
                curl_setopt($curl_handle, CURLOPT_POSTFIELDS, json_encode(array(
                    'api_key' => $api_key,
                    'api_password' => $password,
                    'track_numbers' => $tracking_number
                )));
                curl_setopt($curl_handle, CURLOPT_HTTPHEADER, [
                    'Authorization: ' . $api_key,
                    'Content-Type: application/json'
                ]);
                $buffer = curl_exec($curl_handle);
                curl_close($curl_handle);
                $data =  json_decode($buffer);
                $packet_list = $data->packet_list;
               
                foreach ($packet_list as $key => $value) {
                   
                    update_api_status($api_name, $api_id, $value->booked_packet_status, $api_row);
                }
            }
        }
        if($api_name=='Sonic'){             
            $api_orders_q = mysqli_query($con, "SELECT id,status,track_no,api_id,api_tracking_no from orders where status != 'Return In Process' AND status != 'Delivered' AND status != 'Returned to Shipper' AND status != 'Discarded' AND api_id = '$api_id'");
            if (!$api_orders_q) {
                echo ("No Order Found for $api_name Error description: " . mysqli_error($con));
                exit();
            }
            $requested_orders_count = mysqli_num_rows($api_orders_q);

            while ($api_row = mysqli_fetch_array($api_orders_q)) {
                $status_matched_orders[] = $api_row;
                $tracking_number = $api_row['api_tracking_no'];
                $url = 'https://sonic.pk/api/shipment';
            $collection_name = 'status?tracking_number=' . $tracking_number . '&type=0';
            // $collection_name = 'status?tracking_number=2232233738742&type=0';
            $request_url = $url . '/' . $collection_name;
            $curl = curl_init($request_url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Host: sonic.pk',
                'Authorization: '.$authorization,
                'Content-Type: application/json'
            ]);
            $response = curl_exec($curl);
            curl_close($curl);
            // echo $response . PHP_EOL; 
            $data =  json_decode($response);
            foreach ($data as $key => $value) {
                update_api_status($api_name,$api_id,$value,$api_row);
            }

            } 
        }
        if ($api_name == 'Forrun') {
            $api_orders_q = mysqli_query($con, "SELECT id,status,track_no,api_id,api_tracking_no from orders where status != 'Return In Process' AND status != 'Delivered' AND status != 'Returned to Shipper' AND status != 'Discarded' AND api_id = '$api_id'");
            if (!$api_orders_q) {
                echo ("No Order Found for $api_name Error description: " . mysqli_error($con));
                exit();
            }
            $requested_orders_count = mysqli_num_rows($api_orders_q);

            while ($api_row = mysqli_fetch_array($api_orders_q)) {
                $status_matched_orders[] = $api_row;
                $tracking_number = $api_row['api_tracking_no'];
                // $origin = $row['origin'];
                $ch = curl_init();
                $fields = "account_id=".$account_no."&api_token=".$api_key."&order_id=" . $tracking_number;
                curl_setopt($ch, CURLOPT_URL, "https://forrun.co/api/v1/getOrderStatus");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);
                $data =  json_decode($response);


                if (isset($data)) {

                    if (isset($data) && $data->code == 200) {

                        $value = $data->status;
                        update_api_status($api_name,$api_id,$value,$api_row);
                    }
                }
            }
        }
    }
}


echo "Api Results: <br>";
echo "<b>Results For the  API :</b> <br>";
echo "Requested : " . $total_orders_requested . "<br>";
echo "Matched : " . $total_matched . "<br>";
echo "Not Matched : " . $total_unmatched . "<br>";
// echo "API Name : " . $api_name . "<br>";
echo "<b>=================================</b><br>";
// die('ok');

// die(); 
?>
<br><br>
<a href="dashboard.php?status=active"><button type="button" style="border: unset;padding: 9px 22px;background: #1a1a64;color: #fff;border-radius: 7px;">Go to Dashboard</button></a>