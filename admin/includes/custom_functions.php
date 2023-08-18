<?php
if (file_exists('language_helper.php')) {
    include_once 'language_helper.php';
} else if (file_exists('../language_helper.php')) {
    include_once '../language_helper.php';
} else if (file_exists('../../language_helper.php')) {
    include_once '../../language_helper.php';
} else if (file_exists('../../../language_helper.php')) {
    include_once '../../../language_helper.php';
} else if (file_exists('../../../../language_helper.php')) {
    include_once '../../../../language_helper.php';
}
if (!function_exists("getusernameById")) {
    function getusernameById($id)
    {
        global $con;
        $output = '';
        $result = mysqli_fetch_assoc(mysqli_query($con, "SELECT name FROM users WHERE id=" . $id));
        if (isset($result['name'])) {
            $output = $result['name'];
        }
        return $output;
    }
}
if (!function_exists("getpaymodeById")) {
    function getpaymodeById($id)
    {
        global $con;
        $output = '';
        $result = mysqli_fetch_assoc(mysqli_query($con, "SELECT pay_mode,id FROM pay_mode WHERE id=" . $id));
        if (isset($result['pay_mode'])) {
            $output = $result['pay_mode'];
        }
        return $output;
    }
}
if (!function_exists("getStatusName")) {
    function getStatusName($id)
    {
        global $con;
        $result = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM order_status WHERE sts_id = '$id'  "));
        $output = '';
        if (isset($result['status'])) {
            $output = $result['status'];
        }
        return $output;
    }
}
if (!function_exists("getfranchisemanagerId")) {
    function getfranchisemanagerId()
    {
        global $con;
        $output = '0';
        $result = mysqli_fetch_assoc(mysqli_query($con, "SELECT id FROM user_role WHERE act_as='franchise_manager'"));
        if (isset($result['id'])) {
            $output = $result['id'];
        }
        return $output;
    }
}
if (!function_exists("getUserNameById")) {
    function getUserNameById($id)
    {
        global $con;
        $result = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM users WHERE id = '$id'  "));
        $output = '';
        if (isset($result['Name'])) {
            $output = "By " . $result['Name'];
        }
        return $output;
    }
}
if (!function_exists("getAPIConfig")) {
    function getAPIConfig($value)
    {
        global $con;
        $result = mysqli_query($con, "SELECT * FROM thirdpary_api_config WHERE id=1");
        return mysqli_fetch_assoc($result)[$value];
    }
}
if (!function_exists("getConfig")) {
    function getConfig($name)
    {
        global $con;
        $result = mysqli_query($con, "SELECT * FROM config WHERE name = '$name'");
        return mysqli_fetch_assoc($result)['value'];
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
if (!function_exists('getKeyWord')) {
    function getKeyWord($name)
    {
        global $con;
        $names = trim($name);
        $sql =  "SELECT * FROM `language_translator` WHERE keyword = '$names' AND language_id = " . $_SESSION['language_id'] . " order by id desc";
        $result = mysqli_query($con, $sql);
        $response =  mysqli_fetch_array($result);
        if ($response['translation']) {
            return $response['translation'];
        } else {
            return $name;
            // return 'not translated';
        }
    }
}
if (!function_exists('getKeyWordCustomer')) {
    function getKeyWordCustomer($id, $name)
    {
        global $con;
        $names = trim($name);
        $customer_data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM customers WHERE id=" . $id));
        $sql =  "SELECT * FROM `language_translator` WHERE keyword = '$names' AND language_id = " . $customer_data['language_priority'] . " order by id desc";
        $result = mysqli_query($con, $sql);
        $response =  mysqli_fetch_array($result);
        if ($response['translation']) {
            return $response['translation'];
        } else {
            return $name;
            // return 'not translated';
        }
    }
}
if(!function_exists("ledgerPosting")){
    function ledgerPosting($voucher_type=0,$voucherNo=0,$ledgerId=0,$debit=0,$credit=0,$date,$description=''){
        global $con;
        $created_by = isset($_SESSION['users_id']) ? $_SESSION['users_id'] : 0;
        $date = isset($date) ? $date : date("Y-m-d H:i:s");
        $insert_query = "INSERT INTO tbl_ledgerposting(voucherTypeId,voucherNo,ledgerId,date,debit,credit,chequeDate,narration,created_by) VALUES('".$voucher_type."','".$voucherNo."','".$ledgerId."','".$date."','".$debit."','".$credit."','".$date."','".$description."',$created_by)";
        mysqli_query($con,$insert_query);
        if(mysqli_error($con)){
            echo "Eror in mysqli_query : ".mysqli_error($con);
        }
        return mysqli_insert_id($con);
    }
}
if (!function_exists("getBranchCity")) {
    function getBranchCity($id)
    {
        global $con;
        $result = mysqli_fetch_assoc(mysqli_query($con, "SELECT branch_city FROM branches WHERE id =" . $id));
        $output = '';
        if (isset($result['branch_city'])) {
            $output = $result['branch_city'];
        }
        return $output;
    }
}
if (!function_exists("getProduct")) {
    function getProduct($id)
    {
        global $con;
        $result = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM products WHERE id =" . $id));

        return $result;
    }
}
if (!function_exists("getFuelValue")) {
    function getFuelValue($customer_id = null)
    {
        global $con;
        $return_val = 0;
        $config_has = getConfig('fuel_surcharge');
        $config_says = getConfig('customer_fuel_charge');
        if (isset($config_says) && $config_says == 0) {
            $return_val = $config_has;
        } else {
            $customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $customer_id);
            $customer_data = mysqli_fetch_array($customer_query);
            if (isset($customer_data['is_fuelsurcharge']) && $customer_data['is_fuelsurcharge'] == 0) {
                $return_val = 0;
            } else {
                $fuel_sur_value = mysqli_fetch_array(mysqli_query($con, "SELECT charge_value FROM customer_wise_charges WHERE customer_id = " . $customer_id . " AND charge_name = 'fuel_surcharge' "));
                $fuel_charge_value = isset($fuel_sur_value['charge_value']) ? $fuel_sur_value['charge_value'] : 0;
                $return_val = $fuel_charge_value;
            }
        }
        return $return_val;
    }
}
if (!function_exists("getBusinessName")) {
    function getBusinessName($id)
    {
        global $con;
        $result = mysqli_fetch_assoc(mysqli_query($con, "SELECT bname FROM customers WHERE id =" . $id));
        $output = '';
        if (isset($result['bname'])) {
            $output = $result['bname'];
        }
        return $output;
    }
}

if (!function_exists("getServiceTypeName")) {
    function getServiceTypeName($id)
    {
        global $con;
        $result = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM services WHERE id =" . $id));

        return $result;
    }
}
if (!function_exists("updateRiderWalletBalance")) {
    function updateRiderWalletBalance($order_id, $rider_id)
    {
        global $con;
        if (isset($rider_id) && !empty($rider_id)) {
            $date = date('Y-m-d H:i:s');
            $rider_name_q =  mysqli_fetch_array(mysqli_query($con, "SELECT Name FROM users WHERE id ='" . $rider_id . "' "));
            $rider_name = $rider_name_q['Name'];
            $credit = 0;
            $cod_q = "SELECT collection_amount from orders where track_no ='" . $order_id . "'";
            $cod_result = mysqli_query($con, $cod_q);
            $check_cod = mysqli_fetch_array($cod_result);
            $rider_b = "SELECT * from rider_wallet_ballance where rider_id=" . $rider_id;
            $rider_res = mysqli_query($con, $rider_b);
            $rider_prev_balance_q = mysqli_fetch_array($rider_res);
            $rider_prev_balance = $rider_prev_balance_q['balance'];
            $newBalance = $rider_prev_balance + $check_cod['collection_amount'];
            $check_q = "SELECT * from rider_wallet_ballance where rider_id =" . $rider_id;
            $check_res = mysqli_query($con, $check_q);
            $check_rider_exists  = mysqli_fetch_array($check_res);
            $master_id = '';
            if (isset($check_rider_exists['rider_id']) && !empty($check_rider_exists['rider_id'])) {
                $query = "UPDATE  rider_wallet_ballance set balance = " . $newBalance . ", update_date = '" . date('Y-m-d H:i:s') . "' WHERE rider_id =  " . $rider_id;
                $cod_q = mysqli_query($con, $query);
                $master_id = $rider_prev_balance_q['id'];
            } else {
                $query2 = "INSERT INTO `rider_wallet_ballance`(`rider_id`, `rider_name`, `balance`, `update_date`) VALUES (" . $rider_id . " , '" . $rider_name . "' , " . $newBalance . ",'" . date('Y-m-d H:i:s') . "')";
                $cod_q = mysqli_query($con, $query2);
                $master_id = mysqli_insert_id($con);
            }
            $date = date('Y-m-d H:i:s');
            mysqli_query($con, "INSERT INTO `rider_wallet_ballance_log`(`order_id`,`order_no`,`rider_id`,`rider_name`, `debit`,`credit`,`date`)VALUES (" . $master_id . " ," . $order_id . "," . $rider_id . ",'" . $rider_name . "','" . $check_cod['collection_amount'] . "','" . $credit . "','" . $date . "')");
            mysqli_query($con, "UPDATE orders SET rider_collection = 1 WHERE track_no = '" . $order_id . "'");
        }
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
if (!function_exists("addToSmsLog")) {
    function addToSmsLog($track_no, $status_type)
    {
        global $con;
        $date = date('Y-m-d H:i:s');
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

            if ($message && $template_status == 1) {
                if (!empty($send_to)) {
                    foreach ($send_to as $key => $row) {
                        if ($row == 1) {
                            $admin_number = isset($admindata['phone']) ? $admindata['phone'] : '';
                            if ($admin_number) {
                                $number = numberFormat($admin_number);
                                $message = strip_tags($message);
                                mysqli_query($con, "INSERT INTO cron_sms(`track_no`, `type`, `created_at`,`sms`,`number`) VALUES ('" . $track_no . "','$status_type','" . $date . "','" . $message . "','" . $number . "') ");
                            }
                        }
                        if ($row == 2) {

                            if ($sphone) {
                                $number = numberFormat($sphone);
                                $message = strip_tags($message);
                                mysqli_query($con, "INSERT INTO cron_sms(`track_no`, `type`, `created_at`,`sms`,`number`) VALUES ('" . $track_no . "','$status_type','" . $date . "','" . $message . "','" . $number . "') ");
                            }
                        }

                        if ($row == 3) {
                            if ($rphone) {
                                $number = numberFormat($rphone);
                                $message = strip_tags($message);
                                mysqli_query($con, "INSERT INTO cron_sms(`track_no`, `type`, `created_at`,`sms`,`number`) VALUES ('" . $track_no . "','$status_type','" . $date . "','" . $message . "','" . $number . "') ");
                            }
                        }
                    }
                }
            }
        }
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
            if ($type == 'Delivery SMS' || $type=='Status Update') {
                $riderData = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM users WHERE id=$delivery_rider"));
            }

            $customer_data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM customers WHERE id=" . $customer_id));
            $template = getSmsTemplate($type);

            $sendContent = true;
            if ($type == 'Status Update') {
                $currentStatus = isset($order_data['status']) ? $order_data['status']  : '';
                $allowedArray =  isset($template['status_allowed']) ? explode(',', $template['status_allowed']) : '';
                // if (!in_array($currentStatus, $allowedArray)) {
                //     $sendContent = false;
                // } else {
                //     $sendContent = true;
                // }
            }
            if ($sendContent) {
                $content['template'] = $template;
                $template_content = isset($template['template_content']) ? $template['template_content'] : '';
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
                // echo $content['template_content'];
                // die;
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
if (!function_exists("checkOrdersLimit")) {
    function checkOrdersLimit()
    {
        global $con;
        $return_array = array();
        $limit_enable = getConfig('enable_orders_limit');
        $orders_limit = getConfig('orders_limit');
        $limit_message = getConfig('limit_message');
        $start_date = date('Y-m-01');
        $end_date = date('Y-m-30');
        $count_query = mysqli_query($con, "SELECT count(id) as total_count FROM orders WHERE DATE_FORMAT(order_date, '%Y-%m-%d') >= '" . $start_date . "' AND  DATE_FORMAT(order_date, '%Y-%m-%d') <= '" . $end_date . "'");
        $result_query = mysqli_fetch_array($count_query);
        $count = isset($result_query['total_count']) ? $result_query['total_count'] : 0;
        if (isset($limit_enable) && $limit_enable == 1) {
            $return_array['orders_limit'] = $orders_limit;
            $return_array['limit_message'] = $limit_message;
            $return_array['orders_count'] = $count;
            return $return_array;
        } else {
            return false;
        }
    }
}
if (!function_exists("branchAllowedOrigins")) {
    function branchAllowedOrigins($brnach_id = null)
    {
        global $con;
        $current_branch_city = '';
        if (isset($brnach_id) && $brnach_id != null) {
            $currentBranchQuery = mysqli_query($con, "SELECT * from branches where id=" . $brnach_id);
            $currentBranch = mysqli_fetch_array($currentBranchQuery);
            $current_branch = $currentBranch['name'];
            $current_branch_city_id = $currentBranch['branch_city'];
            $single_city_query = mysqli_query($con, "SELECT * FROM cities WHERE id=" . $current_branch_city_id);
            $single_city = mysqli_fetch_assoc($single_city_query);
            $current_branch_city = $single_city['city_name'];
        }
        return $current_branch_city;
    }
}
$current_branch_id = '';
if (isset($_SESSION['branch_id']) && $_SESSION['branch_id'] != null) {
    $current_branch_id = $_SESSION['branch_id'];
    $check_branch = " branch_id = " . $_SESSION['branch_id'];
} else {
    $check_branch = "1";
    $current_branch_id = '';
}
$current_branch = '';
$current_branch_city = '';
if (isset($_SESSION['branch_id']) && $_SESSION['branch_id'] != null) {
    $currentBranchQuery = mysqli_query($con, "SELECT * from branches where id=" . $_SESSION['branch_id']);
    $currentBranch = mysqli_fetch_array($currentBranchQuery);
    $current_branch = $currentBranch['name'];
    $current_branch_city_id = $currentBranch['branch_city'];
    $single_city_query = mysqli_query($con, "SELECT * FROM cities WHERE id=" . $current_branch_city_id);
    $single_city = mysqli_fetch_assoc($single_city_query);
    $current_branch_city = $single_city['city_name'];
}
$this_current_branch = '';
$allowed_origins_ids = "";
if (isset($_SESSION['branch_id']) && !empty($_SESSION['branch_id'])) {
    $this_current_branch = $_SESSION['branch_id'];
} else {
    $this_current_branch = '1';
}
if (isset($this_current_branch) && !empty($this_current_branch)) {
    $allowed_origin_q = mysqli_query($con, "SELECT * FROM branches WHERE id=" . $this_current_branch);
    $allowed_origins_ids_result = mysqli_fetch_assoc($allowed_origin_q);
    $allowed_origins_ids = $allowed_origins_ids_result['branch_origin'];
}
$allowe_ids_arry = explode(',', $allowed_origins_ids);
$city_names = "";
$origin_names = "";
foreach ($allowe_ids_arry as $key => $value) {
    $single_city_query = mysqli_query($con, "SELECT * FROM cities WHERE id=" . $value);
    $single_city = mysqli_fetch_assoc($single_city_query);
    if (strtoupper($single_city['city_name']) == 'OTHER' || strtoupper($single_city['city_name']) == 'OTHERS') {
        $single_city_query = mysqli_query($con, "SELECT * FROM cities");
        while ($single_city = mysqli_fetch_assoc($single_city_query)) {
            $origin_names .= $single_city['city_name'] . ',';
            $city_names .= '"' . $single_city['city_name'] . '"' . ',';
        }
    } else {
        $origin_names .= $single_city['city_name'] . ',';
        $city_names .= '"' . $single_city['city_name'] . '"' . ',';
    }
}
$all_allowed_origins = rtrim($city_names, ',');
$all_allowed_cities = rtrim($origin_names, ',');
$all_allowed_origins_array = explode(',', $all_allowed_cities);



// ***** Ledger data select and search ajax code ********



// function 







if(isset($_POST['ledgerId']))
{
    if(!$con)
    {
        include "conn.php";
    }
    
    $ledgerId = $_POST['ledgerId'];
    $query = "SELECT * FROM `tbl_accountledger` WHERE id = '{$ledgerId}'";
    
    $query_result = mysqli_query($con,$query);
    
    if($query_result->num_rows > 0)
    {    
        $record = mysqli_fetch_assoc($query_result);   
        echo $record['chart_account_id_child'];
        // echo $record['ledgerCode'];
    }
}

if(isset($_POST['ledgerCode']))
{
    if(!$con)
    {
        include "conn.php";
    }
    
    $ledgerCode = $_POST['ledgerCode'];
    $query = "SELECT * FROM `tbl_accountledger` WHERE chart_account_id_child = '{$ledgerCode}'";
    
    $query_result = mysqli_query($con,$query);
    
    if($query_result->num_rows > 0)
    {    
        $record = mysqli_fetch_assoc($query_result);
        $ledgerData = array("id"=>$record['id'],"ledgerName"=>$record['ledgerName']);   
        echo json_encode($ledgerData);
    }
}

if(isset($_POST['debit_note_save']) || isset($_POST['debit_note_save_print']))
{
    // echo "<pre>",print_r($_POST);exit;

    if(!empty($_POST['debit_note_save_print']))
    {
      $save_print = true;
      $print_file_path = "debit_note_print.php";
    }


    insertDebitCreditData("tbl_debit_note_master","tbl_debit_note_detail",$save_print,$print_file_path);
         
}

if(isset($_POST['credit_note_save']) || isset($_POST['credit_note_save_print']))
{
   if(!empty($_POST['credit_note_save_print']))
    {
      $save_print = true;
      $print_file_path = "credit_note_print.php";
    }

    insertDebitCreditData("tbl_credit_note_master","tbl_credit_note_detail",$save_print,$print_file_path);
         
}

if(isset($_POST['journal_voucher_save']) || isset($_POST['journal_voucher_save_print']))
{
   if(!empty($_POST['journal_voucher_save_print']))
    {
      $save_print = true;
      $print_file_path = "journal_voucher_print.php";
    }
    insertDebitCreditData("tbl_journal_voucher_master","tbl_journal_voucher_detail",$save_print,$print_file_path);
         
}

if(isset($_POST['payment_voucher_save']) || isset($_POST['payment_voucher_save_print']))
{

    if(!empty($_POST['payment_voucher_save_print']))
    {
      $save_print = true;
      $print_file_path = "payment_voucher_print.php";
    }
    insertPaymentReceiptVoucherData("tbl_payment_voucher_master","tbl_payment_voucher_detail",$save_print,$print_file_path);
         
}

if(isset($_POST['reciept_voucher_save']) || isset($_POST['reciept_voucher_save_print']))
{

    if(!empty($_POST['reciept_voucher_save_print']))
    {
      $save_print = true;
      $print_file_path = "reciept_voucher_print.php";
    }
    insertPaymentReceiptVoucherData("tbl_reciept_voucher_master","tbl_reciept_voucher_detail",$save_print,$print_file_path);
         
}


function insertDebitCreditData($masterTable,$detailTable,$save_print = false,$print_file_path = "")
{

    // die("tess");
   
    if(!$con)
    {
        include "conn.php";
    }
    
// ****  fields for debitNote_master_DB_table *******

    $voucherNumber = $_POST['voucher_number'];
    $masterChequeDate = $_POST['master_cheque_date'];
    $user_id = $_SESSION['users_id'];
    $description = $_POST['description'];

   

// **** fields for debitNote_detail_DB_table *******
   
    $ledgerCode = $_POST['ledger_code'];
    $ledgerId = $_POST['ledger_id'];
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $chequeNumber = $_POST['cheque_number'];
    $detailChequeDate = $_POST['detail_cheque_date'];
    $narration = $_POST['narration'];
    

    if($save_print)
    {
       $location = "https://a.icargos.com/portal/admin/{$print_file_path}?voucher_number=".$voucherNumber; 

    }
    else
    {
       $location = $_SERVER['HTTP_REFERER']; 

    }

    if(empty($voucherNumber))
    {
        $_SESSION['msg'] = "<div class='alert alert-danger'>Voucher number is required  field</div>";
   
        header("Location:".$_SERVER['HTTP_REFERER']);
        exit();
    }

    if(empty($ledgerCode) || count(array_filter($ledgerCode)) != count($ledgerCode) || !checkLedgerCodeValid($con, $ledgerCode))
    {
        $_SESSION['msg'] = "<div class='alert alert-danger'>Please fill valid ledger code field</div>";
   
        header("Location:".$_SERVER['HTTP_REFERER']);
        exit();
    }

    if(empty($ledgerId) || count(array_filter($ledgerId)) != count($ledgerId))
    {
        $_SESSION['msg'] = "<div class='alert alert-danger'>Please Select ledger Name field</div>";
   
        header("Location:".$_SERVER['HTTP_REFERER']);
        exit();
    }


    if(!isset($_POST['master_id']) || empty($_POST['master_id']))
    {
        $debit_master_query = "INSERT INTO $masterTable (user_id,voucher_no,cheque_date,description) VALUES ('{$user_id}','{$voucherNumber}','{$masterChequeDate}','{$description}') ";
        if(mysqli_query($con,$debit_master_query))
        {
            $last_master_id = mysqli_insert_id($con);
            $message = "Data inserted successfully";

        }
        
    }
    else
    {
            $last_master_id = $_POST['master_id'];

        $debit_master_query = "UPDATE  $masterTable SET cheque_date = '{$masterChequeDate}' , description = '{$description}' where id = '{$last_master_id}'";
        if(mysqli_query($con,$debit_master_query))
        {
            $message = "Data updated successfully";
            

        }


    }



    
  // ***** detail table forms fields data **********
    $ids = array();
    foreach($ledgerCode as $key => $value)
    {
        if (isset($_POST['id'][$key]) && !empty($_POST['id'][$key])) 
        {
            $record_id = $_POST['id'][$key];

            $detail_query = "UPDATE $detailTable SET chart_account_id = '{$value}',
                                    ledgerId = '{$ledgerId[$key]}',type = '{$type[$key]}',
                                    amount = '{$amount[$key]}',chequeNo = '{$chequeNumber[$key]}',
                                    chequeDate = '{$detailChequeDate[$key]}',
                                    narration = '{$narration[$key]}' WHERE voucherNo = '{$voucherNumber}' AND id = '{$record_id}'";
            $result = mysqli_query($con,$detail_query);
            $ids[] = $record_id;
            
        }
        else
        {
            $detail_query = "INSERT INTO $detailTable (chart_account_id,user_id,ledgerId,type,voucherNo,amount,chequeNo,chequeDate,narration,master_id) VALUES ('{$value}','{$user_id}','{$ledgerId[$key]}','{$type[$key]}','{$voucherNumber}','{$amount[$key]}','{$chequeNumber[$key]}','{$detailChequeDate[$key]}','{$narration[$key]}','{$last_master_id}') ";
            $result = mysqli_query($con,$detail_query);
            $ids[] = mysqli_insert_id($con);
        }

    }

    if($result)
    {
        $idsString = implode(',', $ids);
        mysqli_query($con, "DELETE FROM $detailTable WHERE master_id = $last_master_id AND id NOT IN ({$idsString})");
        $_SESSION['msg'] = "<div class='alert alert-success'>{$message}</div>";
   
        header("Location:".$location);
        exit();
    }

    
    
        
}


function insertPaymentReceiptVoucherData($masterTable,$detailTable,$save_print = false,$print_file_path = "")
{
    if(!$con)
    {
        include "conn.php";
    }
    
// ****  fields for debitNote_master_DB_table *******

  
    $voucherNumber = $_POST['voucher_number'];
    $masterChequeDate = $_POST['master_cheque_date'];
    $user_id = $_SESSION['users_id'];
    $description = $_POST['description'];
    $masterLedgerId = $_POST['master_ledger_id'];


// **** fields for debitNote_detail_DB_table *******

    $ledgerCode = $_POST['ledger_code'];
    $ledgerId = $_POST['ledger_id'];
    $type = $_POST['type'];
    $amount = $_POST['amount'];   
    $chequeNumber = $_POST['cheque_number'];
    $detailChequeDate = $_POST['detail_cheque_date'];
    $narration = $_POST['narration'];

    if($save_print)
    {
       $location = "https://a.icargos.com/portal/admin/{$print_file_path}?voucher_number=".$voucherNumber; 

    }
    else
    {
       $location = $_SERVER['HTTP_REFERER']; 

    }

    if(empty($voucherNumber))
    {
        $_SESSION['msg'] = "<div class='alert alert-danger'>Voucher number is required  field</div>";
   
        header("Location:".$_SERVER['HTTP_REFERER']);
        exit();
    }

    if(empty($masterLedgerId))
    {
        $_SESSION['msg'] = "<div class='alert alert-danger'>Please Select Cash/bank ledger</div>";
   
        header("Location:".$_SERVER['HTTP_REFERER']);
        exit();
    }

    if(empty($ledgerCode) || count(array_filter($ledgerCode)) != count($ledgerCode)  || !checkLedgerCodeValid($con, $ledgerCode))
    {
        $_SESSION['msg'] = "<div class='alert alert-danger'>Please fill valid ledger code field</div>";
   
        header("Location:".$_SERVER['HTTP_REFERER']);
        exit();
    }

    if(empty($ledgerId) || count(array_filter($ledgerId)) != count($ledgerId))
    {
        $_SESSION['msg'] = "<div class='alert alert-danger'>Please Select ledger Name field</div>";
   
        header("Location:".$_SERVER['HTTP_REFERER']);
        exit();
    }

    if(!isset($_POST['master_id']) || empty($_POST['master_id']))
    {
        $debit_master_query = "INSERT INTO $masterTable (user_id,voucher_no,cheque_date,description,cash_bank_ledger_id) VALUES ('{$user_id}','{$voucherNumber}','{$masterChequeDate}','{$description}','{$masterLedgerId}') ";
        if(mysqli_query($con,$debit_master_query))
        {
            $last_master_id = mysqli_insert_id($con);
            $message = "Data inserted successfully";

        }
        
        
    }
    else
    {
        $last_master_id = $_POST['master_id'];

        $debit_master_query = "UPDATE  $masterTable SET cheque_date = '{$masterChequeDate}' , description = '{$description}',cash_bank_ledger_id = '{$masterLedgerId}' where id = '{$last_master_id}'";
        if(mysqli_query($con,$debit_master_query))
        {
            $message = "Data updated successfully";
            

        }
    }


   // ***** detail table forms fields data **********
    $ids = array();
    foreach($ledgerCode as $key => $value)
    {
        if (isset($_POST['id'][$key]) && !empty($_POST['id'][$key])) 
        {
            $record_id = $_POST['id'][$key];

            $detail_query = "UPDATE $detailTable SET chart_account_id = '{$value}',
                                    ledgerId = '{$ledgerId[$key]}',type = '{$type[$key]}',
                                    amount = '{$amount[$key]}',chequeNo = '{$chequeNumber[$key]}',
                                    chequeDate = '{$detailChequeDate[$key]}',
                                    narration = '{$narration[$key]}' WHERE voucherNo = '{$voucherNumber}' AND id = '{$record_id}'";
            $result = mysqli_query($con,$detail_query);
            $ids[] = $record_id;
            mysqli_query($con,"DELETE from tbl_ledgerposting where voucherNo = '$voucherNumber'");
            if($detailTable=='tbl_reciept_voucher_detail'){
                $ledger_insert_id=ledgerPosting(5,$voucherNumber,$masterLedgerId,$amount[$key],0,$masterChequeDate,$narration[$key]);
                $ledger_insert_id=ledgerPosting(5,$voucherNumber,$ledgerId[$key],0,$amount[$key],$detailChequeDate[$key],$narration[$key]);
            }
            if($detailTable=='tbl_payment_voucher_detail'){
                $ledger_insert_id=ledgerPosting(4,$voucherNumber,$ledgerId[$key],$amount[$key],0,$detailChequeDate[$key], $narration[$key]);                
                $ledger_insert_id=ledgerPosting(4,$voucherNumber,$masterLedgerId,0,$amount[$key],$masterChequeDate,$narration[$key]);                
            }
            
        }
        else
        {
            $detail_query = "INSERT INTO $detailTable (chart_account_id,user_id,ledgerId,type,voucherNo,amount,chequeNo,chequeDate,narration,master_id) VALUES ('{$value}','{$user_id}','{$ledgerId[$key]}','{$type[$key]}','{$voucherNumber}','{$amount[$key]}','{$chequeNumber[$key]}','{$detailChequeDate[$key]}','{$narration[$key]}','{$last_master_id}') ";
            $result = mysqli_query($con,$detail_query);
            $ids[] = mysqli_insert_id($con);
            if($detailTable=='tbl_reciept_voucher_detail'){
                $ledger_insert_id=ledgerPosting(5,$voucherNumber,$masterLedgerId,$amount[$key],0,$masterChequeDate,$narration[$key]);
                $ledger_insert_id=ledgerPosting(5,$voucherNumber,$ledgerId[$key],0,$amount[$key],$detailChequeDate[$key],$narration[$key]);
            }
            if($detailTable=='tbl_payment_voucher_detail'){
                $ledger_insert_id=ledgerPosting(4,$voucherNumber,$ledgerId[$key],$amount[$key],0,$detailChequeDate[$key], $narration[$key]);                
                $ledger_insert_id=ledgerPosting(4,$voucherNumber,$masterLedgerId,0,$amount[$key],$masterChequeDate,$narration[$key]);                
            }
        }
        


    }

   if($result)
   {
         $idsString = implode(',', $ids);
        mysqli_query($con, "DELETE FROM $detailTable WHERE master_id = $last_master_id AND id NOT IN ({$idsString})");
        $_SESSION['msg'] = "<div class='alert alert-success'>{$message}</div>";
   
        header("Location:".$location);
        exit();
   }
    
        
}

function checkLedgerCodeValid($con, $data)
{
    
    $dataString = implode("','",$data);
   $check_exist_query = "SELECT count(id) as total FROM `tbl_accountledger` WHERE `chart_account_id_child` IN ('{$dataString}') ";
   // echo $check_exist_query;
   // exit();
   $res = mysqli_query($con,$check_exist_query);
   
   // echo $check_exist_query;exit;
   $totalCodes = count(array_unique($data));
   $db_codes = mysqli_fetch_assoc($res);

   return $totalCodes == $db_codes['total'];
}

