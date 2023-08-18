<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
if (!function_exists('sendSmsMobileGateWay')) {
    function sendSmsMobileGateWay($track_no = null, $status_type = null)
    {
        
        include "mobile_gateway_helper.php";
        include 'conn.php';
        $vars = new Mobile_gateway_helper();
        if ($track_no != null) {
            $admindata = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM users WHERE id=100"));
            $order_detail = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM orders WHERE track_no='" . $track_no . "'"));
            $customer_name = isset($order_detail['rname']) ? $order_detail['rname'] : '';
            $rphone = isset($order_detail['rphone']) ? $order_detail['rphone'] : '';
            $sphone = isset($order_detail['sphone']) ? $order_detail['sphone'] : '';
            $order_status = isset($order_detail['status']) ? $order_detail['status'] : '';
            if ($status_type != null) {
                $message = '';
                $template_contents = applyShortCodes($track_no, $status_type);

                $template_data = isset($template_contents['template']) ? $template_contents['template'] : '';
                $template_status = isset($template_data['status']) ? $template_data['status'] : '';
                
                $send_to = isset($template_data['send_to']) ? explode(',', $template_data['send_to']) : '';
                $message = isset($template_contents['template_content']) ? $template_contents['template_content'] : '';
                $message_one = isset($template_contents['template_content']) ? $template_contents['template_content'] : '';
                $message_two = isset($template_contents['template_content']) ? $template_contents['template_content'] : '';
                $message_three = isset($template_contents['template_content']) ? $template_contents['template_content'] : '';

                if ($message && $template_status == 1) {
                    if (!empty($send_to)) {
                        foreach ($send_to as $key => $row) {

                            if ($row == 1) {
                                $admin_number = isset($admindata['phone']) ? $admindata['phone'] : '';
                                if ($admin_number) {
                                    $number = numberFormat($admin_number);
                                    $message_one = strip_tags($message_one);
                                    $message_one = $vars->send_sms_mobile_gateway($number, $message_one);
                                }
                            }
                            if ($row == 2) {

                                if ($sphone) {
                                    $number = numberFormat($sphone);
                                    $message_two = strip_tags($message_two);
                                    $message_two = $vars->send_sms_mobile_gateway($number, $message_two);
                                }
                            }

                            if ($row == 3) {
                                if ($rphone) {
                                    $number = numberFormat($rphone);
                                    $message_three = strip_tags($message_three);
                                    $message_three = $vars->send_sms_mobile_gateway($number, $message_three);
                                }
                            }
                        }
                    }
                }
            }
        }
        return true;
    }
}
if (!function_exists('numberFormat')) {
    function numberFormat($number = null)
    {
        if ($number != null) {
            $number  = preg_replace('/[^0-9]/s', '', $number);
            $pos0 = substr($number, 0, 1);
            if ($pos0 == '3') {
                $alterno = substr($number, 1);
                $alterno = '0' . $number;
                $number = $alterno;
            }
            $pos = substr($number, 0, 2);
            if ($pos == '03') {
                $alterno = substr($number, 1);
                $alterno = '92' . $alterno;
                $number = $alterno;
            }
        }
        return $number;
    }
}
if (!function_exists('applyShortCodes')) {
    function applyShortCodes($track_no = null, $type = null)
    {
        
        global $con;
        $content = [];
        if ($track_no != null && $type != null) {
            
            $sms_setting = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM sms_settings WHERE id=1"));
            $admindata = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM users WHERE id=100"));

            $order_data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM orders WHERE track_no='" . $track_no . "'"));
            $customer_id = isset($order_data['customer_id']) ? $order_data['customer_id'] : '';
            $pickup_rider = isset($order_data['pickup_rider']) ? $order_data['pickup_rider'] : '';
            $delivery_rider = isset($order_data['delivery_rider']) ? $order_data['delivery_rider'] : '';
            if ($type == 'Pickup SMS') {
                $riderData = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM users WHERE id=$pickup_rider"));
            }
            if ($type == 'Delivery SMS') {
                $riderData = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM users WHERE id=$pickup_rider"));
            }

            $customer_data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM customers WHERE id=" . $delivery_rider));
            $template = getSmsTemplate($type);
           
            $sendContent = true;
            if ($type == 'Status Update') {
                $currentStatus = isset($order_data['status']) ? $order_data['status']  : '';
                $allowedArray =  isset($template['status_allowed']) ? explode(',', $template['status_allowed']) : '';
                if (!in_array($currentStatus, $allowedArray)) {
                    $sendContent = false;
                } else {
                    $sendContent = true;
                }
            }
            $sendContent = true;
            if ($sendContent) {
                $content['template'] = $template;
                $template_content = isset($template['template_content']) ? $template['template_content'] : '';
                $template_content = str_replace('[OKSXPRESS COURIER]', $sms_setting['thanku_company'], $template_content);
                $template_content = preg_replace('/@Origin_City/', $order_data['origin'], $template_content);
                $template_content = preg_replace('/@Sender_Name/', $order_data['sname'], $template_content);
                $template_content = preg_replace('/@Sender_Phone/', $order_data['sphone'], $template_content);
                $template_content = preg_replace('/@Sender_Address/', $order_data['sender_address'], $template_content);
                $template_content = preg_replace('/@Destination_City/', $order_data['destination'], $template_content);
                $template_content = preg_replace('/@Receiver_Name/', $order_data['rname'], $template_content);
                $template_content = preg_replace('/@Receiver_Phone/', $order_data['rphone'], $template_content);
                $template_content = preg_replace('/@Reciover_Email/', $order_data['remail'], $template_content);
                $template_content = preg_replace('/@Receiver_Address/', $order_data['receiver_address'], $template_content);
                $template_content = preg_replace('/@Tracking_NO/', $order_data['track_no'], $template_content);
                $template_content = preg_replace('/@Item_Detail/', $order_data['product_desc'], $template_content);
                $template_content = preg_replace('/@Special_instruction/', $order_data['special_instruction'], $template_content);
                $template_content = preg_replace('/@Reference_No/', $order_data['ref_no'], $template_content);
                $template_content = preg_replace('/@Order_id/', $order_data['product_id'], $template_content);
                $template_content = preg_replace('/@No_of_pieces/', $order_data['quantity'], $template_content);
                $template_content = preg_replace('/@Weight/', $order_data['weight'], $template_content);
                $template_content = preg_replace('/@COD_amount/', $order_data['collection_amount'], $template_content);
                $template_content = preg_replace('/@Order_Status/', $order_data['status'], $template_content);
                $template_content = preg_replace('/@Rider_Name/', $riderData['Name'], $template_content);
                $template_content = preg_replace('/@Rider_Phone/', $riderData['phone'], $template_content);
                $template_content = preg_replace('/@Rider_Location/', $riderData['location'], $template_content);
                $template_content = preg_replace('/@Received_By/', $order_data['received_by'], $template_content);
                $template_content = preg_replace('/@Tracking_History/', $order_data['status'], $template_content);
                $template_content  = str_replace('@Tracking_url', $sms_setting['track_from_url'] . '=' . $track_no, $template_content);
                $content['template_content'] = $template_content;
            }
        }
        
        return $content;
    }
}
if (!function_exists('getSmsTemplate')) {
    function getSmsTemplate($type = null)
    {
        global $con;
        $template = '';
        if ($type != null) {
            $template = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM sms_templates WHERE sms_events='" . $type . "' AND status = 1"));
        }
        return $template;
    }
}