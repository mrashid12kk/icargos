<?php
// require '../conn.php';

if (!function_exists("book_on_api")) {
    function book_on_api($api_title,$track_no,$select_service_type=null)
    {
        // echo "api title is:".$api_title."<br>";
        // echo "api track_no is:".$track_no."<br>";
        // echo "api select_service_type is:".$select_service_type."<br>";
        // die;
        global $con;
        $api_res = mysqli_fetch_assoc(mysqli_query($con,"SELECT * from third_party_apis where title='$api_title'"));
        $api_id = isset($api_res['id']) ? $api_res['id'] :'';
        $api_key = isset($api_res['api_key']) ? $api_res['api_key'] :'';
        $api_user_name = isset($api_res['user_name']) ? $api_res['user_name'] :'';
        $api_password = isset($api_res['password']) ? $api_res['password'] :'';
        $api_account_no = isset($api_res['account_no']) ? $api_res['account_no'] :'';
        $api_authorization = isset($api_res['authorization']) ? $api_res['authorization'] :'';
        $record = mysqli_query($con, "SELECT * FROM orders WHERE track_no ='" . $track_no . "'");
        $row = mysqli_fetch_assoc($record);
        if(isset($row['api_id']) && !empty($row['api_id']) && $row['api_id'] >0){
            $_SESSION['err_msg_for_api'] = "Order No: $track_no already booked at $api_title with $api_title API tracking number ".$row['api_tracking_no'];
        }else{
            $order_type = mysqli_query($con, "SELECT * FROM third_party_api_service_mapping WHERE service_id =" . $row['order_type'] . " AND api_provider_id = '$api_title'");
            $service_row = mysqli_fetch_array($order_type);
            $api_service_name = strtolower($service_row['api_service_name']);
            // Find Origin Id
            $origin = $row['origin'];
            $api_origin_q = mysqli_query($con, "SELECT * FROM city_mapping WHERE city_id ='" . $origin . "' AND api_id = '$api_title' ");
            $api_origin_res = mysqli_fetch_array($api_origin_q);
            $sendercityName = isset($api_origin_res['api_city_id']) ? $api_origin_res['api_city_id'] : $row['origin'] ;
            
            //Find Destination City In API
            $destination = $row['destination']; 
            $api_destination_q = mysqli_query($con, "SELECT * FROM city_mapping WHERE city_id ='" . $destination . "' AND api_id = '$api_title' ");
            $api_destination_res = mysqli_fetch_array($api_destination_q);
            $receivercityName = isset($api_destination_res['api_city_id']) ? $api_destination_res['api_city_id'] : $row['destination'] ;
            
            // Phone number Foramt
            $str_to_replace = "";
            $sphone =  $row['sphone'];
            $firstTwoNumbers = substr($sphone, 0, 2);
            $firstThreeNumbers = substr($sphone, 0, 3);
            if ($firstTwoNumbers == 92) {
                $sphone = $str_to_replace . substr($sphone, 2);
            } elseif ($firstThreeNumbers == '+92') {
                $sphone = $str_to_replace . substr($sphone, 3);
            } else {
                $sphone;
            }
    
            $rphone =  $row['rphone'];
            $firstTwoNumbers = substr($rphone, 0, 2);
            $firstThreeNumbers = substr($rphone, 0, 3);
            if ($firstTwoNumbers == 92) {
                $rphone = $str_to_replace . substr($rphone, 2);
                //echo 'formated r'. $rphone ."<br>" ;
            } elseif ($firstThreeNumbers == +92) {
                $rphone = $str_to_replace . substr($rphone, 3);
                // echo 'formated sb'.     $sphone ."<br>" ;
            } else {
                //echo 'unformated r'.   $rphone."<br>";
                $rphone;
            }
            $sname = $row['sname'];
            $track_no = isset($row['track_no']) ? $row['track_no'] : $track_no;
            $sbname = isset($row['sbname']) && !empty($row['sbname']) && $row['sbname'] != "" ? $row['sbname'] : $row['sname'];
            // 	$sphone = $row['sphone'];
            $sphone = $sphone;
            $sender_address = $row['sender_address'];
            // 	$sender_city = $row['origin'];
            $sender_city = $sendercityName;
            $rname = $row['rname'];
            // 	$rphone = $row['rphone'];
            $rphone =  $rphone;
            // 	$delivery_city = $row['destination'];
            $delivery_city = $receivercityName;
            // 	$amount = $row['net_amount'];
            $amount = $row['collection_amount'];
            $delivery_address = $row['receiver_address'];
            $remail = $row['remail'];
            $semail = $row['semail'];
            $reference_number = $row['sname'];
            $no_of_pieces = isset($row['quantity']) ? $row['quantity'] : 1;
            $ensured_declared = null;
            $dimension_l = null;
            $dimension_w = null;
            $dimension_h = null;
            $weight = isset($row['weight']) ? $row['weight'] : 0.5;
            $item_detail = $row['product_desc'];
            $product_id = isset($row['product_id']) ? $row['product_id'] :'';
            $item_type = null;
            $instructions = $row['special_instruction'];
            $no_of_flyers = $row['flyer_qty'];
            $no_of_flyers = $row['flyer_qty'];
            $code_sku = "1234567890";
            $product_code = "10000";
            $variations = "-";
            if($api_title=='Sonic'){
                $url = 'https://sonic.pk/api/shipment/book';
                $order_data = array(
                    'service_type_id'            => 1,
                    'pickup_address_id'      	 => 15552, //16106
                    'information_display'        => 0,
                    'consignee_city_id'          => $delivery_city,
                    'consignee_name'             => $row['rname'],
                    'consignee_address'          => $delivery_address,
                    'consignee_phone_number_1'   => $rphone,
                    // 'consignee_phone_number_2'   => '',  //////
                    // 'consignee_email_address'   => $row['remail'],
                    'item_product_type_id'      => 24,
                    'item_description'          => $row['product_desc'],
                    'item_quantity'             => $row['quantity'],
                    'item_insurance'            => 0,
                    // 'item_price'                => '1', //////
                    'pickup_date'               => Date('Y-m-d'),
                    // 'special_instructions'      => $row['special_instruction'],
                    'estimated_weight'          => 1,
                    'shipping_mode_id'          => 1,
                    // 'same_day_timing_id'        => '1', //////
                    'amount'              		=> $row['collection_amount'],
                    'payment_mode_id'           => 1,
                    'charges_mode_id'           => 4,
                    'order_id'                  => 12,
                );
                if (isset($_POST['product_id']) && empty($_POST['product_id'])) {
                    unset($order_data['order_id']);
                }
                if (isset($_POST['special_instruction']) && empty($_POST['special_instruction'])) {
                    unset($order_data['special_instructions']);
                }
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $order_data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                // curl_setopt($ch, CURLOPT_USERPWD,  'username:password');
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: ' . $api_key));
                $result = curl_exec($ch);
                curl_close($ch);
                // $result = (object) json_decode($result);
                $data =  json_decode($result);
                /*echo "<pre>";
     print_r($data);*/
                // die();
                //Sonic API ends here
                foreach ($data as $key => $value) {
    
                    if ($key == 'errors') {
                        foreach ($value as $val) {
                            // $_SESSION['err_msg_for_api'] = $val[0];
                            return array('status'=>'error','message'=>$val[0]);
                        }
                    }
                    if ($key == 'tracking_number'  && $value != null) {
                        // echo $value;
                        $update = mysqli_query($con, "UPDATE orders set api_tracking_no = '$value',  api_posted = '$api_title', api_id = $api_id where track_no = '$track_no'");
                        if(getAPIConfig('cn_on_booking')=='api'){
                            mysqli_query($con, "UPDATE orders set track_no='$value' where track_no = '$track_no'");
                        }
                        if ($update) {
                            // Below code to update order tracking no
                            // $order_id = $value;
                            // echo $value;
                            // $_SESSION['succ_msg_for_api'] = 'Order No ' . $track_no . ' Has Been Posted TO ' . $api_title . ' API';
                            return array('status'=>'success','message'=>'<p>Order No ' . $track_no . ' Has Been Posted TO ' . $api_title . ' API</p>');
                        } else {
                            return array('status'=>'error','message'=>mysqli_error($con));
                            // echo mysqli_error($con);
                        }
                        // die();
                    }
                }
            }
            if($api_title=='Forrun'){
                $ch = curl_init();
                $fields = "account_id=".$api_account_no."&api_token=".$api_key."&service_type=" . $select_service_type . "&pickup_name=" . $sname . "&pickup_phone=" . $sphone . "&pickup_address=" . $sender_address . "&pickup_city=" . $sender_city . "&delivery_name=" . $rname . "&delivery_phone=" . $rphone . "&delivery_city=" . $delivery_city . "&amount=" . $amount . "&delivery_address=" . $delivery_address . "&delivery_email=" . $remail . "&reference_number=" . $reference_number . "&no_of_pieces=" . $no_of_pieces . "&ensured_declared=" . $ensured_declared . "&dimension_l=" . $dimension_l . "&dimension_w=" . $dimension_w . "&dimension_h=" . $dimension_h . "&weight=" . $weight . "&item_detail=" . $item_detail . "&item_type=" . $item_type . "&instructions=" . $instructions . "&no_of_flyers=" . $no_of_flyers . "";
                curl_setopt($ch, CURLOPT_URL, "https://forrun.co/api/v1/addnewOrder");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                curl_close($ch);
                $data =  json_decode($result);
                foreach ($data as $key => $value) {
                    if ($key == 'message') {
                        foreach ($value as $val) { 
                            return array('status'=>'error','message'=>$val);
                        }
                    }
                    if ($key == 'order_id'  && $value != null) {
                        if(getAPIConfig('cn_on_booking')=='api'){
                            mysqli_query($con, "UPDATE orders set track_no='$value' where track_no = '$track_no'");
                        }
                       
                        $update = mysqli_query($con, "UPDATE orders set api_tracking_no = '$value', api_posted = '$api_title', api_id = $api_id where track_no = '$track_no'");

                        if ($update) {
                            return array('status'=>'success','message'=>'<p>Order No ' . $track_no . ' Has Been Posted TO ' . $api_title . ' API</p>');
                        } else {

                            echo mysqli_error($con);
                        }
                    }
                }
                
            }
            if($api_title=='BlueEX'){
                $delivery_address = trim(preg_replace('/\s+/', ' ',  $delivery_address));
                $sender_address = trim(preg_replace('/\s+/', ' ',  $sender_address));
                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://bigazure.com/api/json_v3/shipment/create_shipment.php',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                    "shipper_name": "' . $sbname . '",
                    "shipper_email": "'.$semail.'",
                    "shipper_contact": "0' . trim($sphone) . '",
                    "shipper_address": "'.$sender_address.'",
                    "shipper_city": "'.$sender_city.'",
                    "customer_name": "' . $rname . '",
                    "customer_email": "'.$remail.'",
                    "customer_contact": "0' . trim($rphone) . '",
                    "customer_address": "'.$delivery_address.'",
                    "customer_city": "'.$delivery_city.'",
                    "customer_country": "PK",
                    "customer_comment": "---",
                    "shipping_charges": "0",
                    "payment_type": "COD",
                    "service_code": "BG",
                    "total_order_amount": "'.$amount.'",
                    "total_order_weight": "'.$weight.'",
                    "order_refernce_code": "'.$product_id.'",
                    "fragile": "N",
                    "parcel_type": "P",
                    "insurance_require" : "N",
                    "insurance_value" : "0",
                    "testbit": "Y",
                    "cn_generate": "Y",
                    "multi_pickup": "Y",
                    "products_detail": [
                    {
                        "product_code": "' . $product_code . '",
                        "product_name": "' . $item_detail . '",
                        "product_price": "' . $amount . '",
                        "product_weight": "' . $weight . '",
                        "product_quantity": "' . $no_of_pieces . '",
                        "product_variations": "' . $variations . '",
                        "sku_code": "' . $code_sku . '"
                    }
                    ]
                }',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: '.$api_authorization,
                    'Content-Type: application/json'
                ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                $data = json_decode($response);
                // echo $response;die;
                $status = $data->status;
                // echo $status;
                // die;
                if ($status == 0) {
                    $error_msg = isset($data->response) ? $data->response : '';
                    // $_SESSION['err_msg_for_api'] = $error_msg;
                    return array('status'=>'error','message'=>$error_msg);
                } else {
                    $order_code = isset($data->order_code) ? $data->order_code : '';
                    $track_number = isset($data->cn) ? $data->cn : '';

                    if(getAPIConfig('cn_on_booking')=='api'){
                        mysqli_query($con, "UPDATE orders set track_no='$track_number' where track_no = '$track_no'");
                    }
                   
                    $update = mysqli_query($con, "UPDATE orders set api_order_code='$order_code', api_tracking_no = '$track_number',  api_posted = '$api_title', api_id = '$api_id' where track_no = '$track_no'");
                    // $_SESSION['succ_msg_for_api'] = 'Order No ' . $track_no . ' Has Been Posted TO ' . $api_title . ' API. BlueEX API tracking no is ' . $track_number;
                    return array('status'=>'success','message'=>'<p>Order No ' . $track_no . ' Has Been Posted TO ' . $api_title . ' API. BlueEX API tracking no is ' . $track_number.'</p>');
                }
            }
            if($api_title=='Movex'){
                $delivery_address = str_replace(array("\n", "\r"), ' ', $delivery_address);
                $api_posted = 'Movex';
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://tracking.movexpk.com/api/shipment/book',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => '{ "consignee_mobile_number":"0' . $rphone . '", "consignee_name":"' . $rname . '", "consignee_address":"' . $delivery_address . '", "destination_city_id":"' . $delivery_city . '", "pieces":"' . $no_of_pieces . '", "weight":"' . $weight . '", "cod_amount":"' . $amount . '", "product_detail":"' . $item_detail . '", "origin_city_id":"' . $sender_city . '","seller_reference":"' . $track_no . '","customer_reference_number":"' . $track_no . '","remarks":"' . $sbname . '" }',
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: '.$api_key,
                        'Content-Type: application/json'
                    ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                // echo $response;
                $data =  json_decode($response);
                // die;
                // echo "<pre>";
                // print_r($data);
                // die;
                $response = $data->response;
                if (count($response) == 0 || empty($response)) {
                    $error_msg = isset($data->message) ? $data->message : '';
                    // $_SESSION['err_msg_for_api'] = $error_msg;
                    return array('status'=>'error','message'=>$error_msg);
                } else {
                    $track_number = isset($data->response->tracking_number) ? $data->response->tracking_number : '';
                    $update = mysqli_query($con, "UPDATE orders set  api_tracking_no = '$track_number',  api_posted = '$api_posted', api_id = $api_id where track_no = '$track_no'");
                    if(getAPIConfig('cn_on_booking')=='api'){
                        mysqli_query($con, "UPDATE orders set track_no='$track_number' where track_no = '$track_no'");
                    }
                    // $_SESSION['succ_msg_for_api'] = 'Order No ' . $track_no . ' Has Been Posted TO ' . $api_posted . ' API. Movex API tracking no is ' . $track_number;
                    return array('status'=>'success','message'=>'<p>Order No ' . $track_no . ' Has Been Posted TO ' . $api_posted . ' API. Movex API tracking no is ' . $track_number.'</p>');
                }
            }
            if($api_title=='Leopards'){
                $api_posted = 'Leopard';
                        $curl_handle = curl_init();
                        curl_setopt($curl_handle, CURLOPT_URL, 'http://new.leopardscod.com/webservice/bookPacketTest/format/json/');
                        $data_Array = array(
                            'api_key'                       => $api_key,
                            'api_password'                  => $api_password,
                            'booked_packet_weight'          => $weight * 1000,
                            'booked_packet_vol_weight_w'    => $dimension_w,
                            'booked_packet_vol_weight_h'    => $dimension_h,
                            'booked_packet_vol_weight_l'    => $dimension_l,
                            'booked_packet_no_piece'        => $no_of_pieces,
                            'booked_packet_collect_amount'  => $amount,
                            'booked_packet_order_id'        => $track_no,
                            'origin_city'                   => $sender_city,
                            'destination_city'              => $delivery_city,
                            'shipment_name_eng'             => $sbname,
                            'shipment_email'                => $semail,
                            'shipment_phone'                => $sphone,
                            'shipment_address'              => $sender_address,
                            'consignment_name_eng'          => $rname,
                            'consignment_email'             => $remail,
                            'consignment_phone'             => $rphone,
                            'consignment_phone_two'         => null,
                            'consignment_phone_three'       => null,
                            'consignment_address'           => $delivery_address,
                            'special_instructions'          => $item_detail,
                            'shipment_type'                 => $select_service_type
                        );
                        // echo "<pre>";
                        // print_r($data_Array);
                        // die;
                        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, json_encode($data_Array));
                        curl_setopt($curl_handle, CURLOPT_POST, 1);
                        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, 1);
                        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, [
                            'Authorization: '.$api_key,
                            'Content-Type: application/json'
                        ]);
                        $result = curl_exec($curl_handle);
                        $data =  json_decode($result);
                        
                        $status = $data->status;
                        if ($status == 0) {
                            $error_msg = isset($data->error) ? $data->error : '';
                            // $_SESSION['err_msg_for_api'] = $error_msg;
                            return array('status'=>'error','message'=>$error_msg);
                        } else {
                            $track_number = isset($data->track_number) ? $data->track_number : '';
                            $slip_link = isset($data->slip_link) ? $data->slip_link : '';
                            $update = mysqli_query($con, "UPDATE orders set slip_link='$slip_link', api_tracking_no = '$track_number',  api_posted = '$api_posted', api_id = $api_id where track_no = '$track_no'");
                            if(getAPIConfig('cn_on_booking')=='api'){
                                mysqli_query($con, "UPDATE orders set track_no='$track_number' where track_no = '$track_no'");
                            }
                            // $_SESSION['succ_msg_for_api'] = 'Order No ' . $track_no . ' Has Been Posted TO ' . $api_posted . ' API. Leopard API tracking no is ' . $track_number;
                            return array('status'=>'success','message'=>'<p>Order No ' . $track_no . ' Has Been Posted TO ' . $api_posted . ' API. Leopard API tracking no is ' . $track_number.'</p>');
                        }
            }
        }
        

    }
}

?>