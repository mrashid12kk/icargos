<?php

// ini_set('display_errors', 1);

// ini_set('display_startup_errors', 1);

// error_reporting(E_ALL);

class Mobile_gateway_helper

{

    private $api_key_web = '';

    private $api_url_web = '';

    private $device_id = 1;

    private $sim_slot = 0;

    public function __construct()

    {

        include 'conn.php';

        $sql = mysqli_query($con, "SELECT * FROM sms_settings WHERE id=1") or die(mysqli_error($con));

        $result = mysqli_fetch_array($sql);

        $api_web = ($result) ? (object)$result : null;

        if ($api_web && isset($api_web->api_key_web)) {

            $this->api_key_web = $api_web->api_key_web;

        }

        if ($api_web && isset($api_web->api_url_web)) {

            $this->api_url_web = $api_web->api_url_web;

        }

        if ($api_web && isset($api_web->device_id) && $api_web->device_id > 0) {

            $this->device_id = $api_web->device_id;

        }

        if ($api_web && isset($api_web->sim_slot)) {

            if ($api_web->sim_slot == 1) {

                $this->sim_slot = 0;

            } else if ($api_web->sim_slot == 2) {

                $this->sim_slot = 1;

            } else {

                $this->sim_slot = 0;

            }

        }

        define("SERVER", $this->api_url_web);

        define("API_KEY", $this->api_key_web);



        define("USE_SPECIFIED", 0);

        define("USE_ALL_DEVICES", 1);

        define("USE_ALL_SIMS", 2);

    }

    public function send_sms_mobile_gateway($to = null, $message = null)

    {

        global $con;

        $querySql = "SELECT * FROM sms_settings WHERE is_default=1";

        $sql = mysqli_query($con, $querySql) or die(mysqli_error($con));

        while ($result = mysqli_fetch_array($sql,MYSQLI_ASSOC)) {

            $api_name_web = $result['api_name_web'];

            $sms_type = $result['sms_type'];
    
            $account_id = $result['account_id'];
    
            $secrect = $result['api_key'];
    
            $device_id= $result['device_id'];
    
            $api_url_web=$result['api_url_web'];
    
            $api_key_web= $result['api_key_web'];
    
            $sim_slot = $result['sim_slot'];
    
            if ($api_name_web == 'Mobile gateway') {
    
                try {
    
                    // Send a message using the primary device.
    
                    // $msg = $this->sendSingleMessage("+923346963079", "This is a test of single message.");
    
    
    
                    // Send a message using the Device ID 1.
    
                    // $msg = $this->sendSingleMessage("+923346963079", "This is a test of single message.", 5);
    
    
    
                    // Send a message using the SIM in slot 1 of Device ID 1 (Represented as "1|0").
    
                    // SIM slot is an index so the index of the first SIM is 0 and the index of the second SIM is 1.
    
                    // In this example, 1 represents Device ID and 0 represents SIM slot index.
    
                    // $msg = $this->sendSingleMessage($to, $message);
    
                    // $msg = $this->sendSingleMessage("+923346963079", "This is a test of single message.", "5|0");
    
                    $msg = $this->sendSingleMessage($to, $message, $this->device_id . "|" . $this->sim_slot);
    
                    return $msg;
    
                } catch (Exception $e) {
    
                    return $e->getMessage();
    
                }
    
            }
    
            if ($api_name_web == 'BrandedSMS.me') {
    
                $sms_data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM sms_settings WHERE api_name_web='BrandedSMS.me'"));
    
                $current_date = date('Y-m-d H:i:s');
    
                $schedule_date = date("Y-m-d H:i:s",strtotime("+2 minutes", strtotime($current_date)));
    
                $schedule_date = date('m/d/Y h:i A',strtotime($schedule_date));
    
                
    
                //////////////SMS///////////////
    
                $rphone = $to;
    
                $rphone  = preg_replace('/[^0-9]/s', '', $rphone);
    
                $pos0 = substr($rphone, 0, 1);
    
                if ($pos0 == '3') {
    
                    $alterno = substr($rphone, 1);
    
                    $alterno = '0' . $rphone;
    
                    $rphone = $alterno;
    
                }
    
                $pos = substr($rphone, 0, 2);
    
                if ($pos == '03') {
    
                    $alterno = substr($rphone, 1);
    
                    $alterno = '92' . $alterno;
    
                    $rphone = $alterno;
    
                }
    
                $pos2 = substr($rphone, 0, 4);
    
                if ($pos2 == '0092') {
    
                    $alterno = substr($rphone, 2);
    
                    $alterno =  $alterno;
    
                    $rphone = $alterno;
    
                }
    
                $http_query = http_build_query([
    
                    'action'  => 'send-sms',
    
                    'api_key' => $sms_data['api_key'],
    
                    'from'    => $sms_data['mask_from'], //sender ID
    
                    'to'      => trim($rphone),
    
                    'sms'     => $message,
    
                    'schedule' => $schedule_date,
    
                ]);
    
    
    
                $url = 'https://login.brandedsms.me/sms/api?' . $http_query;
    
                // echo $url;
    
                // die;
    
                $ch = curl_init();
    
                curl_setopt($ch, CURLOPT_URL, $url);
    
                curl_setopt($ch, CURLOPT_HEADER, 0);
    
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    
                ob_start();
    
                $response = curl_exec($ch);
    
                ob_end_clean();
    
                curl_close($ch);
    
                //////////////SMS///////////////
    
            }
    
            if($api_name_web == 'watilio_whatsapp_sms_api'){
    
                $rphone = $to;
    
                $rphone  = preg_replace('/[^0-9]/s', '', $rphone);
    
                $pos0 = substr($rphone, 0, 1);
    
                if ($pos0 == '3') {
    
                    $alterno = substr($rphone, 1);
    
                    $alterno = '0' . $rphone;
    
                    $rphone = $alterno;
    
                }
    
                $pos = substr($rphone, 0, 2);
    
                if ($pos == '03') {
    
                    $alterno = substr($rphone, 1);
    
                    $alterno = '92' . $alterno;
    
                    $rphone = $alterno;
    
                }
    
                $pos2 = substr($rphone, 0, 4);
    
                if ($pos2 == '0092') {
    
                    $alterno = substr($rphone, 2);
    
                    $alterno =  $alterno;
    
                    $rphone = $alterno;
    
                }
    
                $chat = [
    
                    "secret" => $secrect,
    
                    "account" => $account_id,
    
                    "recipient" => "+".$rphone,
    
                    "type" => $sms_type,
    
                    "message" => $message
    
                  ];
    
                  
    
                  $cURL = curl_init("https://watilio.com/api/send/whatsapp");
    
                  curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
    
                  curl_setopt($cURL, CURLOPT_POSTFIELDS, $chat);
    
                  $response = curl_exec($cURL);
    
                  curl_close($cURL);
    
                  $result = json_decode($response, true);
    
                  
    
                  // do something with response
    
                  print_r($result);
    
            }
    
            /* Watilio SMS API */
            if($api_name_web == 'Watilio SMS API'){
    
                $rphone = $to;
    
                $rphone  = preg_replace('/[^0-9]/s', '', $rphone);
    
                $pos0 = substr($rphone, 0, 1);
    
                if ($pos0 == '3') {
    
                    $alterno = substr($rphone, 1);
    
                    $alterno = '0' . $rphone;
    
                    $rphone = $alterno;
    
                }
    
                $pos = substr($rphone, 0, 2);
    
                if ($pos == '03') {
    
                    $alterno = substr($rphone, 1);
    
                    $alterno = '92' . $alterno;
    
                    $rphone = $alterno;
    
                }
    
                $pos2 = substr($rphone, 0, 4);
    
                if ($pos2 == '0092') {
    
                    $alterno = substr($rphone, 2);
    
                    $alterno =  $alterno;
    
                    $rphone = $alterno;
    
                }
    
                $postData = [
                    "secret" => $api_key_web, // your API secret from (Tools -> API Keys) page
                    "mode" => "devices",
                    "device" => $device_id,
                    "sim" => $sim_slot,
                    "priority" => 1,
                    "phone" => $rphone,
                    "message" => $message,
                ];
    
                $cURL = curl_init($api_url_web);
                curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($cURL, CURLOPT_POSTFIELDS, $postData);
                $response = curl_exec($cURL);
                curl_close($cURL);
    
                $result = json_decode($response, true);
                  // do something with response
    
                //   print_r($result);
    
            }
            /* Watilio SMS API */

            /* Watilio Whatsapp API */
            if($api_name_web == 'Watilio Whatsapp API'){

                
                $rphone = $to;
    
                $rphone  = preg_replace('/[^0-9]/s', '', $rphone);
    
                $pos0 = substr($rphone, 0, 1);
    
                if ($pos0 == '3') {
    
                    $alterno = substr($rphone, 1);
    
                    $alterno = '0' . $rphone;
    
                    $rphone = $alterno;
    
                }
    
                $pos = substr($rphone, 0, 2);
    
                if ($pos == '03') {
    
                    $alterno = substr($rphone, 1);
    
                    $alterno = '92' . $alterno;
    
                    $rphone = $alterno;
    
                }
    
                $pos2 = substr($rphone, 0, 4);
    
                if ($pos2 == '0092') {
    
                    $alterno = substr($rphone, 2);
    
                    $alterno =  $alterno;
    
                    $rphone = $alterno;
    
                }


                $postData = [
                    "secret" => $api_key_web, // your API secret from (Tools -> API Keys) page
                    "account" => $account_id,
                    "recipient" => $rphone,
                    "type" => "text",
                    "message" => $message,
                ];
                
                $cURL = curl_init($api_url_web);
                curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($cURL, CURLOPT_POSTFIELDS, $postData);
                $response = curl_exec($cURL);
                curl_close($cURL);
                
                $result = json_decode($response, true);
                
                // do something with response
                // print_r($result);
            }
            /* Watilio Whatsapp API */
        }
        return true;
    }

    function sendSingleMessage($number, $message, $device = 1)

    {

        $url = SERVER . "/services/send.php";

        $postData = array('number' => $number, 'message' => $message, 'key' => API_KEY, 'devices' => $device);

        return $this->sendRequest($url, $postData);

    }

    function sendMessages($messages, $option = USE_SPECIFIED, $devices = [])

    {

        $url = SERVER . "/services/send.php";

        $postData = [

            'messages' => json_encode($messages),

            'key' => API_KEY,

            'devices' => json_encode($devices),

            'option' => $option

        ];

        return $this->sendRequest($url, $postData);

    }

    function getMessageByID($id)

    {

        $url = SERVER . "/services/read-messages.php";

        $postData = [

            'key' => API_KEY,

            'id' => $id

        ];

        $msg = $this->sendRequest($url, $postData);

        return $msg;

    }

    function getMessagesByGroupID($groupID)

    {

        $url = SERVER . "/services/read-messages.php";

        $postData = [

            'key' => API_KEY,

            'groupId' => $groupID

        ];

        return $this->sendRequest($url, $postData)["messages"];

    }

    function sendRequest($url, $postData)

    {

        // echo $url;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

        $response = curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {

            throw new Exception(curl_error($ch));

        }

        curl_close($ch);

        if ($httpCode == 200) {

            $json = json_decode($response, true);

            if ($json == false) {

                if (empty($response)) {

                    throw new Exception("Missing data in request. Please provide all the required information to send messages.");

                } else {

                    throw new Exception($response);

                }

            } else {

                if ($json["success"]) {

                    return $json;

                } else {

                    throw new Exception($json["error"]["message"]);

                }

            }

        } else {

            throw new Exception("HTTP Error Code : {$httpCode}");

        }

    }

}