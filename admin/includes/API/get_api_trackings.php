<?php
// require '../conn.php';
if (!function_exists("get_api_trackings")) {
    function get_api_trackings($api_title,$api_tracking_no)
    {
        global $con;
        $apis_query = mysqli_query($con, "SELECT * from third_party_apis where title = '$api_title'");
        $api_response = mysqli_fetch_assoc($apis_query);
        $api_id = $api_response['id'];
        $api_name  = $api_response['title'];
        $authorization  = $api_response['authorization'];
        $account_no  = $api_response['account_no'];
        $password  = $api_response['password'];
        $user_name  = $api_response['user_name'];
        $api_key  = $api_response['api_key'];
        if($api_title=='Leopards'){
            $curl_handle = curl_init();
			curl_setopt($curl_handle, CURLOPT_URL, 'http://new.leopardscod.com/webservice/trackBookedPacket/format/json/');
			curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl_handle, CURLOPT_POST, 1);
			curl_setopt($curl_handle, CURLOPT_POSTFIELDS, json_encode(array(
				'api_key' => $api_key,
				'api_password' => $password,
				'track_numbers' => $api_tracking_no
				// 'track_numbers' => 'JD588086656'
			)));
			curl_setopt($curl_handle, CURLOPT_HTTPHEADER, [
				'Authorization: '.$api_key,
				'Content-Type: application/json'
			]);

			$buffer = curl_exec($curl_handle);
			curl_close($curl_handle);
            echo $buffer;
            die;
			$data = json_decode($buffer);
			$leopard_tracking = array();
			$packet_list = $data->packet_list;
            echo "<pre>";
            print_r($packet_list);
            die;
			foreach ($packet_list as $key => $value) {

				foreach ($value as $key => $val) {
					$leopard_tracking = $val;
				}
			}

        }
        if($api_title=='BlueEX'){

        }
       
    }
}

?>