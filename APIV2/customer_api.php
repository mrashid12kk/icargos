<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require_once('../admin/includes/conn.php');

$type = $_GET['type'];

if ($type == 'customer_login') {
    customer_login();
} elseif ($type == 'customer_orders') {
    customer_orders();
} elseif ($type == 'track_one_order') {
    track_one_order();
} elseif ($type == 'customer_info') {
    customer_info();
} elseif ($type == 'getCities') {
    getCities();
} elseif ($type == 'save_order') {
    save_order();
} elseif ($type == 'getAllServiceTypes') {
    getAllServiceTypes();
} elseif ($type == 'getAllProducts') {
    getAllProducts();
} elseif ($type == 'get_areas') {
    get_areas();
} elseif ($type == 'getDashboardData') {
    getDashboardData();
} elseif ($type == 'getServiceOrigins') {
    getServiceOrigins();
} elseif ($type == 'live_orders') {
    live_orders();
} elseif ($type == 'get_areas_with_service') {
    get_areas_with_service();
} elseif ($type == 'submit_signup') {
    submit_signup();
} elseif ($type == 'checkValidEmail') {
    checkValidEmail();
} elseif ($type == 'getFilteredData') {
    getFilteredData();
} elseif ($type == 'getDashOrders') {
    getDashOrders();
}
function getDashOrders()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    $data_post = (array)json_decode(file_get_contents("php://input"));

    $customer_id = $data_post['customer_id'];
    $status_get = isset($data_post['search_status']) ? $data_post['search_status'] : '';
    $search_key = isset($data_post['search']) ? $data_post['search'] : '';
    $status_query = '';
    if (isset($status_get) && $status_get != 'all') {
        $status_query = ' orders.status="' . $status_get . '" AND';
    }

    $search_key_query = '';
    if (isset($search_key) && $search_key != '') {
        $status_query = ' (orders.status="' . $search_key . '" OR orders.track_no ="' . $search_key . '" OR orders.rname ="' . $search_key . '" )AND ';
    }

    $return_data = array();

    $sql = "SELECT orders.track_no,orders.order_time,orders.destination,orders.status,orders.origin,orders.rname,orders.rphone,orders.order_type,orders.delivery_rider,orders.net_amount,orders.weight,orders.collection_amount,orders.receiver_address,services.service_code FROM orders join services ON orders.order_type = services.id  WHERE  payment_status='Pending' AND $search_key_query $status_query orders.customer_id=" . $customer_id . " ORDER BY orders.id DESC";

    $query = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_assoc($query)) {
        array_push($return_data, $row);
    }

    foreach ($return_data as $key => &$singleOrder) {
        // echo "SELECT * FROM users Where id = ".$singleOrder['delivery_rider'];
        // die();
        $single_user_q = mysqli_query($con, "SELECT * FROM users Where id = " . $singleOrder['delivery_rider']);
        $fetchRider = mysqli_fetch_object($single_user_q);

        $singleOrder['Name'] = isset($fetchRider->Name) ? $fetchRider->Name : '';
        $singleOrder['phone'] = isset($fetchRider->phone) ? $fetchRider->phone : '';
    }

    if ($return_data) {
        http_response_code(201);
        echo json_encode(array("response" => 1, "orders" => $return_data,   "message" => "All orders."));
        exit();
    } else {
        http_response_code(200);
        echo json_encode(array("response" => 0, "message" => "No record found."));
        exit();
    }
}
function getFilteredData()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    $search =  $data_post['serach'];
    $customer_id =  $data_post['customer_id'];
    $return_data = array();

    $query = mysqli_query($con, "SELECT orders.track_no,orders.order_time,orders.destination,orders.status,orders.origin,orders.rname,orders.rphone,orders.order_type,orders.weight,orders.collection_amount,orders.receiver_address,services.service_code,users.Name,users.phone FROM orders join services ON orders.order_type = services.id join users on orders.delivery_rider=users.id WHERE orders.customer_id = $customer_id AND (orders.sname LIKE '%$search%' OR  orders.rname LIKE '%$search%' OR orders.origin LIKE '%$search%' OR orders.destination LIKE '%$search%' OR orders.collection_amount LIKE '%$search%' OR orders.status LIKE '%$search%' OR orders.track_no LIKE '%$search%') ORDER BY orders.id DESC ");
    while ($row = mysqli_fetch_assoc($query)) {
        array_push($return_data, $row);
    }

    if (mysqli_affected_rows($con) >  0) {
        http_response_code(201);
        echo json_encode(array("response" => 1, "data" => $return_data,   "message" => "All orders."));
        exit();
    } else {
        http_response_code(200);
        echo json_encode(array("response" => 0, "message" => "No record found."));
        exit();
    }
}
function checkValidEmail()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    $email = $_GET['email'];

    $query = mysqli_fetch_assoc(mysqli_query($con, "SELECT id FROM customers WHERE email='" . $email . "'"));

    $emailExists = $query['id'];
    if ($emailExists > 0) {
        http_response_code(201);
        echo json_encode(array("response" => 1, "message" => "Email Exists"));
        exit();
    } else {
        http_response_code(200);
        echo json_encode(array("response" => 0, "message" => "No Email Exists"));
        exit();
    }
}
function submit_signup()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    // var_dump($data_post);
    $companyname = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='companyname' "));
    $bname = isset($data_post['bname']) ? $data_post['bname'] : '';
    $fname = isset($data_post['fname']) ? $data_post['fname'] : '';
    $mobile_no = isset($data_post['mobile_no']) ? $data_post['mobile_no'] : '';
    $email = isset($data_post['email']) ? $data_post['email'] : '';
    $address = isset($data_post['address']) ? $data_post['address'] : '';
    $cnic = isset($data_post['cnic']) ? $data_post['cnic'] : '';
    $cnic_copy = isset($data_post['cnic_copy']) ? $data_post['cnic_copy'] : '';
    $image = isset($data_post['image']) ? $data_post['image'] : '';
    $bank_name = isset($data_post['bank_name']) ? $data_post['bank_name'] : '';
    $acc_title = isset($data_post['acc_title']) ? $data_post['acc_title'] : '';
    $bank_ac_no = isset($data_post['bank_ac_no']) ? $data_post['bank_ac_no'] : '';
    $branch_name = isset($data_post['branch_name']) ? $data_post['branch_name'] : '';
    $branch_code = isset($data_post['branch_code']) ? $data_post['branch_code'] : '';
    $swift_code = isset($data_post['swift_code']) ? $data_post['swift_code'] : '';
    $iban_no = isset($data_post['iban_no']) ? $data_post['iban_no'] : '';
    $website_url = isset($data_post['website_url']) ? $data_post['website_url'] : '';
    $city = isset($data_post['city']) ? $data_post['city'] : '';
    $product_type = isset($data_post['product_type']) ? $data_post['product_type'] : '';
    $expected_shipment = isset($data_post['expected_shipment']) ? $data_post['expected_shipment'] : '';
    $password = isset($data_post['password']) ? md5($data_post['password']) : '';
    $customer_type = isset($data_post['customer_type']) ? $data_post['customer_type'] : '';
    $insert_query = "INSERT INTO `customers`(`customer_type`, `bname`, `fname`, `cnic`, `email`, `password`,   `address`,  `bank_name`, `bank_ac_no`, `acc_title`, `branch_code`, `swift_code`, `branch_name`, `iban_no`,  `city`,  `mobile_no`,  `website_url`, `product_type`, `expected_shipment`) VALUES ('" . $customer_type . "','" . $bname . "','" . $fname . "','" . $cnic . "','" . $email . "','" . $password . "','" . $address . "','" . $bank_name . "','" . $bank_ac_no . "','" . $acc_title . "','" . $branch_code . "','" . $swift_code . "','" . $branch_name . "','" . $iban_no . "','" . $city . "','" . $mobile_no . "','" . $website_url . "','" . $product_type . "','" . $expected_shipment . "')";
    // var_dump($insert_query);
    // die();
    $isert_record = mysqli_query($con, $insert_query);
    $customer_id = mysqli_insert_id($con);
    $code = 1000 + $customer_id;
    $query5 = mysqli_query($con, "UPDATE customers SET client_code = '" . $code . "'  WHERE id = " . $customer_id);


    $target_dir = "../users/";
    $target_dir_cnic = "../cnic_copy/";
    $target_file = '';
    $target_file_cnic = '';
    if (isset($data_post['image']) && $data_post['image'] != '' && $data_post['image']) {
        $image_name = 'uploaded_image_' . rand() . '.jpg';
        $path = $target_dir . $image_name;
        $recievedJson = $data_post['image'];
        $base = $recievedJson;
        $binary = base64_decode($base);
        header('Content-Type: bitmap; charset=utf-8');
        $file = fopen($path, 'wb');
        fwrite($file, $binary);
        fclose($file);
        $target_file = $image_name;
        mysqli_query($con, "UPDATE customers SET image='users/" . $target_file . "' WHERE id = $customer_id ");
    }

    if (isset($data_post['cnic_copy']) && $data_post['cnic_copy'] != '' && $data_post['cnic_copy']) {
        $image_name = 'uploaded_image_' . rand() . '.jpg';
        $path = $target_dir_cnic . $image_name;
        $recievedJson = $data_post['image'];
        $base = $recievedJson;
        $binary = base64_decode($base);
        header('Content-Type: bitmap; charset=utf-8');
        $file = fopen($path, 'wb');
        fwrite($file, $binary);
        fclose($file);
        $target_file_cnic = $image_name;
        mysqli_query($con, "UPDATE customers SET cnic_copy='cnic_copy/" . $target_file_cnic . "' WHERE id = $customer_id ");
    }

    $code = 1000 + $customer_id;
    if (isset($data_post['email'])) {
        $data_post['email'] = $email;
        $customer_name = $data_post['fname'];
        $message['subject'] = 'Account Registration';
        $message['body'] = "<b>Hello " . $customer_name . " </b>";
        $message['body'] .= '<p>Thank you for registering with ' . $companyname['value'] . '</p>';
        $message['body'] .= '<p>Your account has been created but must be activated before you can start booking your shipments. Our admin will review your information and approve within 24 hours.</p>';
        require_once '../admin/includes/functions.php';
        sendEmail($data_post, $message);
        // Admin
        $path = BASE_URL . 'admin/customer_detail.php?customer_id=' . $customer_id;
        $message['body'] = '<p>New User Account has been created</p>';
        $message['body'] .= '<p>Click below link to view customer.</p>';
        $message['body'] .= "<a href='$path'>$path</a>";
        sendEmailToAdmin($data_post, $message);
    }
    // code by nimra
    if(isset($customer_type)){
        $customer_name = mysqli_fetch_array(mysqli_query($con , "SELECT * FROM customers where id = '".$customer_id."' "));
        $customerName = $customer_name['bname'];
        $id = $customer_type;
        $fetch = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM pay_mode where id = '".$id."' "));
        $acc_id1 = $fetch['payable'];
        $acc_id2 = $fetch['receivable'];
        if(!empty($fetch['payable']) && !empty($fetch['receivable'])){
            $record = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM tbl_accountgroup where id = '".$fetch['payable']."'"));
        
        $groupIds = $record['id'];
        $lastGroupQuery = mysqli_query($con, "SELECT * FROM tbl_accountgroup WHERE company_id IS NULL AND groupUnder= '".$groupIds."' ORDER BY id DESC LIMIT 1");
        $lastGroupData = mysqli_fetch_array($lastGroupQuery);
        
      $child = '';
        if(isset($lastGroupData['chart_account_id_child']) && $lastGroupData['chart_account_id_child'])
            {
                $explodedData = array_reverse(explode('-', $lastGroupData['chart_account_id_child']));
                $index = $explodedData[0];
                $newIndex = $index+1;
                $newIndex = $index+1;
                $explodedData[0]  = sprintf("%02d", $newIndex);
                $explodedData = array_reverse($explodedData);
                $child = implode('-', $explodedData).'-01-L';
            }
            else
            {
                $existingId = $record['chart_account_id_child'];
                $child =$existingId.'-01-L';
            }

        $parent = $record['chart_account_id_child'];


        $forReceivable = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM tbl_accountgroup where id = '".$fetch['receivable']."'"));
        $groupIds = $forReceivable['id'];

        $lastGroupQuery = mysqli_query($con, "SELECT * FROM tbl_accountledger WHERE company_id IS NULL AND groupUnder= '".$groupIds."' ORDER BY id DESC LIMIT 1");
        $lastGroupData = mysqli_fetch_array($lastGroupQuery);
        $child1 = '';
        if(isset($lastGroupData['chart_account_id_child']) && $lastGroupData['chart_account_id_child'])
            {
                $explodedData = array_reverse(explode('-', $lastGroupData['chart_account_id_child']));
                $index = $explodedData[0];
                if($fetch['payable'] == $fetch['receivable']){
                    $newIndex = $index+2;
                }else{
                    $newIndex = $index+1;
                }
                $explodedData[0]  = sprintf("%02d", $newIndex);
                $explodedData = array_reverse($explodedData);
                $child1 = implode('-', $explodedData).'-01-L';
            }
            else
            {
                $existingId = $forReceivable['chart_account_id_child'];
                $child1 =$existingId.'-01-L';
            }
            
        // $parent1 = $child1;
        $parent1 = $forReceivable['chart_account_id_child'];
            
        }elseif(!empty($fetch['payable']) && empty($fetch['receivable'])){
            $record = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM tbl_accountgroup where id = '".$fetch['payable']."'"));
        
        $groupIds = $record['id'];
        $lastGroupQuery = mysqli_query($con, "SELECT * FROM tbl_accountledger WHERE company_id IS NULL AND groupUnder= '".$groupIds."' ORDER BY id DESC LIMIT 1");
        $lastGroupData = mysqli_fetch_array($lastGroupQuery);
        // var_dump($lastGroupQuery);
        $child = '';
        if(isset($lastGroupData['chart_account_id_child']) && $lastGroupData['chart_account_id_child'])
            {
                $explodedData = array_reverse(explode('-', $lastGroupData['chart_account_id_child']));
                $index = $explodedData[0];
                $newIndex = $index+1;
                $explodedData[0]  = sprintf("%02d", $newIndex);
                $explodedData = array_reverse($explodedData);
                $child = implode('-', $explodedData).'-01-L';
            }
            else
            {
                $existingId = $record['chart_account_id_child'];
                $child =$existingId.'-01-L';
            }

        $parent = $record['chart_account_id_child'];
        $child1 = '';
        $parent1 = '';
        }elseif(empty($fetch['payable']) && !empty($fetch['receivable'])){
            // echo "string";
        $forReceivable = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM tbl_accountgroup where id = '".$fetch['receivable']."'"));
        $groupIds = $forReceivable['id'];
        $lastGroupQuery = mysqli_query($con, "SELECT * FROM tbl_accountgroup WHERE company_id IS NULL AND groupUnder= '".$groupIds."' ORDER BY id DESC LIMIT 1");
        $lastGroupData = mysqli_fetch_array($lastGroupQuery);
        if(!$lastGroupData){
            echo mysqli_error($con);
        }
        // var_dump($lastGroupQuery);
        $child1 = '';
        if(isset($lastGroupData['chart_account_id_child']) && $lastGroupData['chart_account_id_child'])
            {
                $explodedData = array_reverse(explode('-', $lastGroupData['chart_account_id_child']));
                $index = $explodedData[0];
                $newIndex = $index+1;
                $explodedData[0]  = sprintf("%02d", $newIndex);
                $explodedData = array_reverse($explodedData);
                $child1 = implode('-', $explodedData).'-01-L';
            }
            else
            {
                $existingId = $forReceivable['chart_account_id_child'];
                $child1 =$existingId.'-01-L';
            }
            
        $parent1 = $forReceivable['chart_account_id_child'];
        $child = '';
        $parent = '';
        }else
        {
            $child = '';
            $child1 = '';
            $parent = '';
            $parent1 = '';
        }

        $output = array(
            'payable_parent' => $parent,
            'payable' => $child,
            'payable_acc_id' => $acc_id1,
            'receivable_parent' => $parent1,
            'receivable' => $child1,
            'recievable_acc_id' => $acc_id2,
        );
        // echo json_encode($output);
   
    $ledgercode = mysqli_query($con , "SELECT MAX(`ledgerCode`) AS `ledgerCode` FROM `tbl_accountledger`");

                $getLedgerCode = mysqli_fetch_assoc($ledgercode);

                $ledgerCode = $getLedgerCode['ledgerCode'];
                if(!empty($ledgerCode)){
                        $ledgerCode = $ledgerCode + 1 ;
                }else{
                        $ledgerCode=13; 
                }
    $dateToday = date("Y-m-d H:i:s");
      
        if(!empty($output['payable']) && !empty($output['payable_parent']) && !empty($output['payable_acc_id'])){
    
        $sql =  "INSERT into tbl_accountledger (`ledgerCode`,`ledgerName`,`customer_id`,`created_on`,`accountGroupId`,`chart_account_id`, `chart_account_id_child`) VALUES ('".$ledgerCode."', '".$customerName."', '".$customer_id."','".$dateToday."','".$output['payable_acc_id']."', '".$output['payable_parent']."', '".$output['payable']."' )";
        // echo $sql;
        $query1 =mysqli_query($con ,$sql);

    }
        if($query1 == TRUE){
                $ledgerCode = $ledgerCode+1;
            }else{
                $ledgerCode = $ledgerCode;
            }
        if(!empty($output['receivable']) && !empty($output['receivable_parent']) && !empty($output['recievable_acc_id'])){
                    $sql =  "INSERT into tbl_accountledger (`ledgerCode`,`ledgerName`,`customer_id`,`created_on`,`accountGroupId`,`chart_account_id`, `chart_account_id_child`) VALUES ('".$ledgerCode."', '".$customerName."', '".$customer_id."','".$dateToday."','".$output['recievable_acc_id']."', '".$output['receivable_parent']."', '".$output['receivable']."' )";
                    // echo $sql;
                $query1 = mysqli_fetch_array(mysqli_query($con ,$sql));
            }
        }
    // code by nimra
    $id = mysqli_insert_id($con);
    $query = mysqli_query($con, "Select * from customers where id=$id") or die(mysqli_error($con));
    $fetch = mysqli_fetch_array($query);

    if ($customer_id > 0) {
        http_response_code(201);
        echo json_encode(array("response" => 1, "insert_id" => $customer_id, "message" => "Your registration is successful. Please wait for account approval email by " . $companyname['value'] . ""));
        exit();
    } else {
        http_response_code(200);
        echo json_encode(array("response" => 0, "message" => "your registration is unsuccessful, please try again."));
        exit();
    }
}
function get_areas_with_service()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    $zone_id = '';
    $customer_id = isset($_GET['customer_id']) ? $_GET['customer_id'] : 1;
    $service_type = isset($_GET['service_type']) ? $_GET['service_type'] : 1;

    $zone_id_q = mysqli_query($con, "SELECT zone_id from customer_pricing where customer_id =" . $customer_id);

    while ($zone_id_rs = mysqli_fetch_assoc($zone_id_q)) {
        $zone_id .= $zone_id_rs['zone_id'] . ',';
    }
    $zone_id = rtrim($zone_id, ',');

    $origin_cities = mysqli_query($con, " SELECT DISTINCT origin FROM zone_cities WHERE zone IN(" . $zone_id . ") ");

    $destination_cities = mysqli_query($con, " SELECT DISTINCT destination FROM zone_cities WHERE zone IN(" . $zone_id . ") ");

    $destination_return = array();
    while ($fetch_2 = mysqli_fetch_assoc($destination_cities)) {
        array_push($destination_return, $fetch_2);
    }
    $origin_return = array();
    while ($row = mysqli_fetch_assoc($origin_cities)) {
        array_push($origin_return, $row);
    }

    if (mysqli_num_rows($origin_cities)) {
        http_response_code(201);
        echo json_encode(array("response" => 1, "origin" => $origin_return, "destination" => $destination_return, "message" => "All orders."));
        exit();
    } else {
        http_response_code(200);
        echo json_encode(array("response" => 0, "message" => "No record found."));
        exit();
    }
}
function live_orders()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    $page = $_GET['page'];
    $limit = $_GET['limit'];
    $customer_id = $_GET['customer_id'];
    $status_get = isset($_GET['search_status']) ? $_GET['search_status'] : '';
    $status_query = '';
    if (isset($status_get) && $status_get != 'all') {
        $status_query = ' orders.status="' . $status_get . '" AND';
    }

    $return_data = array();
    $sql = "SELECT orders.track_no,orders.order_time,orders.destination,orders.status,orders.origin,orders.rname,orders.rphone,orders.order_type,orders.net_amount,orders.delivery_rider,orders.weight,orders.collection_amount,orders.receiver_address,services.service_code FROM orders join services ON orders.order_type = services.id  WHERE payment_status='Pending' AND $status_query orders.customer_id=" . $customer_id . " ORDER BY orders.id DESC LIMIT $page, $limit";

    // $sql ="SELECT orders.track_no,orders.order_time,orders.destination,orders.status,orders.origin,orders.rname,orders.rphone,orders.order_type,orders.weight,orders.collection_amount,orders.receiver_address,services.service_code,users.Name,users.phone FROM orders join services ON orders.order_type = services.id join users on orders.delivery_rider=users.id WHERE $status_query orders.customer_id=".$customer_id." ORDER BY orders.id DESC LIMIT $page, $limit";

    // echo $sql;
    // die();

    $query = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_assoc($query)) {
        array_push($return_data, $row);
    }

    foreach ($return_data as $key => &$singleOrder) {
        // echo "SELECT * FROM users Where id = ".$singleOrder['delivery_rider'];
        // die();
        $single_user_q = mysqli_query($con, "SELECT * FROM users Where id = " . $singleOrder['delivery_rider']);
        $fetchRider = mysqli_fetch_object($single_user_q);

        $singleOrder['Name'] = isset($fetchRider->Name) ? $fetchRider->Name : '';
        $singleOrder['phone'] = isset($fetchRider->phone) ? $fetchRider->phone : '';
    }

    if ($return_data) {
        http_response_code(201);
        echo json_encode(array("response" => 1, "data" => $return_data,   "message" => "All orders."));
        exit();
    } else {
        http_response_code(200);
        echo json_encode(array("response" => 0, "message" => "No record found."));
        exit();
    }
}
function getServiceOrigins()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    $data_post = (array)json_decode(file_get_contents("php://input"));

    $service_type = isset($data_post['service_type']) ? $data_post['service_type'] : '';
    $customer_id = isset($data_post['customer_id']) ? $data_post['customer_id'] : '';
    $query = mysqli_query($con, "SELECT * FROM customers Where id=" . $customer_id);
    $cityname = mysqli_fetch_assoc($query);
    $customer_city = $cityname['city'];
    $zone_id_q = mysqli_query($con, "SELECT zone_id FROM customer_pricing WHERE customer_id='" . $customer_id . "' AND `service_type` ='" . $service_type . "' ");

    $i = 1;
    $whr = "";
    while ($zone_id_r = mysqli_fetch_array($zone_id_q)) {
        $zone_id = $zone_id_r['zone_id'];
        if ($i == 1) {
            $whr .= " ( `zone` = '" . $zone_id . "'  ";
        } else {
            $whr .= " or `zone` = '" . $zone_id . "'  ";
        }
        $i++;
    }
    $whr .= " ) ";

    $origin_zone_q = mysqli_query($con, "SELECT DISTINCT origin FROM zone_cities WHERE  " . $whr . " ORDER BY origin ");
    $serveice_array = array();
    while ($origin_r = mysqli_fetch_array($origin_zone_q)) {
        array_push($serveice_array, $origin_r);
    }
    $destination_array = array();

    $destination_zone_q = mysqli_query($con, "SELECT DISTINCT destination FROM zone_cities WHERE  " . $whr . "  ORDER BY destination ");


    while ($destination_r = mysqli_fetch_array($destination_zone_q)) {
        $city = $destination_r['destination'];
        if ($city == 'Other') {
            $city_q = mysqli_query($con, "SELECT DISTINCT city_name as destination FROM cities WHERE city_name !='Other' AND city_name !='LAHORE' ");
            while ($city_q_r = mysqli_fetch_array($city_q)) {
                array_push($destination_array, $city_q_r);
            }
        } else {
            array_push($destination_array, $destination_r);
        }
    }


    $if_area_enable = getConfig('manual_area');

    if (isset($origin_zone_q) &&  mysqli_num_rows($origin_zone_q) > 0) {
        http_response_code(201);
        echo json_encode(array("response" => 1, 'origins' => $serveice_array, 'destination' => $destination_array, "manual_area" => $if_area_enable, "message" => "Service Types"));
        exit();
    } else {
        http_response_code(200);
        echo json_encode(array("response" => 0, "message" => "No record found"));
        exit();
    }
}
function getDashboardData()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    // header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    $customer_id = isset($data_post['customer_id']) ? $data_post['customer_id'] : '';
    $input_lang = isset($data_post['lang']) ? $data_post['lang'] : '';
    $total_cod = 0;
    $total_delivery_charges = 0;
    $total_pft = 0;
    $total_return_fee = 0;
    $lang_query = mysqli_query($con, "SELECT * FROM portal_language WHERE language = '" . $input_lang . "'");
    $get_lang = mysqli_fetch_assoc($lang_query);
    $langid = isset($get_lang['id']) ? $get_lang['id']  : 1;
    $trns_query = mysqli_query($con, "SELECT * FROM language_translator WHERE language_id = '" . $langid . "'");
    $get_trans_array = array();
    while ($get_trans = mysqli_fetch_assoc($trns_query)) {

        array_push($get_trans_array, $get_trans);
    }
    // $get_trans_array = json_encode($trans_array);
    function ordersts_get($status, $customer_id)
    {

        global $con;

        $result = mysqli_query($con, "SELECT COUNT(id) as status_count FROM orders WHERE customer_id=" . $customer_id . " AND status = '$status'");

        return mysqli_fetch_assoc($result)['status_count'];
    }
    $main_query = mysqli_query($con, "SELECT * FROM order_status WHERE is_dashboard = 1 GROUP BY status ORDER BY sort_num ");
    $return_array = array();
    while ($single = mysqli_fetch_array($main_query)) {

        $orderstatus_get = ordersts_get($single['status'], $customer_id);
        $return_data = array(
            'status_name' => $single['status'],
            'status_count' => $orderstatus_get,
            'color_code' => $single['color_code']
        );
        array_push($return_array, $return_data);
    }
    $balance_query = mysqli_query($con, "SELECT (prev_balance + (total_payable - total_paid)) as total FROM customer_ledger_payments WHERE customer_id = '" . $customer_id . "' ORDER BY id DESC LIMIT 1");
    $balance_query = ($balance_query) ? mysqli_fetch_object($balance_query) : null;
    $customer_balance = ($balance_query) ? $balance_query->total : 0;


    $querycod = mysqli_fetch_array(mysqli_query($con, "SELECT SUM(collection_amount) AS collection_amount FROM orders WHERE customer_id='" . $customer_id . "' AND status='Delivered' AND payment_status='Pending'"));

    $total_CODS = $querycod['collection_amount'];
    $querycod = mysqli_query($con, "SELECT * FROM orders WHERE  (status ='Delivered' || status='Returned to Shipper' ) AND customer_id=" . $customer_id . "  AND payment_status = 'Pending' ");
    while ($singleer = mysqli_fetch_array($querycod)) {

        if ($singleer['status'] == 'Returned to Shipper') {
            $total_return += $singleer['collection_amount'];
        }
        $total_cod += $singleer['collection_amount'];
        $total_delivery_charges += $singleer['price'];
        $total_pft += $singleer['pft_amount'];
        $total_return_fee += $singleer['return_delivery_fee'];
    }
    $total_payable = $total_cod - $total_delivery_charges - $total_pft - $total_return - $total_flyer - $total_return_fee - $total_cash_handling;
    $pending_cod = $total_payable + $customer_balance;
    if (mysqli_num_rows($main_query) >  0) {
        http_response_code(201);
        echo json_encode(array("response" => 1, 'translation' => $get_trans_array, 'language' => $get_lang, "status" => $return_array, "total_cod" => $total_cod, 'pending_cod' => $pending_cod,  "message" => "Areas statuses."));
        exit();
    } else {
        http_response_code(200);
        echo json_encode(array("response" => 0, "message" => "No record found."));
        exit();
    }
}
function getAllServiceTypes()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    $customer_id = isset($data_post['customer_id']) ? $data_post['customer_id'] : '';
    $product_type_id = isset($data_post['product_id']) ? $data_post['product_id'] : '';
    $response = [];
    $service = [];
    $sql =  "SELECT service_type FROM tariff WHERE product_id = " . $product_type_id . " GROUP BY service_type ORDER BY id DESC";
    // echo $sql;die();
    $result = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_array($result)) {
        if (isset($row['service_type'])) {
            $row = (object)$row;
            $id = $row->service_type;
            $services_sql =  "SELECT * FROM services WHERE id = " . $id . " ORDER BY id DESC";
            $services_result = mysqli_query($con, $services_sql);
            $single = mysqli_fetch_array($services_result);
            // echo '<pre>',print_r($single),'</pre>';exit();
            $row->id = $id;
            $row->service_type = $single['service_type'];
            array_push($service, $row);
        }
    }
    $response  = $service;

    if (isset($response) &&  !empty($response)) {
        http_response_code(201);
        echo json_encode(array("response" => 1, 'sTypes' => $response, "message" => "Service Types"));
        exit();
    } else {
        http_response_code(200);
        echo json_encode(array("response" => 0, "message" => "No record found"));
        exit();
    }
}
function getAllProducts()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    $data_post = (array)json_decode(file_get_contents("php://input"));


    if (!function_exists('getProducts')) {
        function getProducts()
        {
            global $con;
            $customer_id = isset($_GET['id']) ? $_GET['id'] : '';
            // echo "SELECT * FROM customers WHERE id=" . $customer_id . " ";
            // die;
            $customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $customer_id . " ");
            $customer_data = mysqli_fetch_array($customer_query);
            $customer_type = $customer_data['customer_type'];
            $cus_pro_ids = '';
            $customerPaySql = "SELECT * FROM pay_mode WHERE account_type = '" . $customer_type . "'";

            $c_pay_mode_q = mysqli_query($con, $customerPaySql);
            $paymodeRes = mysqli_fetch_assoc($c_pay_mode_q);
            $customerPayMode = isset($paymodeRes['pay_mode']) ? $paymodeRes['pay_mode'] : '';
            $customerPayModeId = isset($paymodeRes['id']) ? $paymodeRes['id'] : '';
            $tariffSql =  "SELECT product_id FROM `tariff` Where pay_mode=" . $customerPayModeId;
            $tariffResult = mysqli_query($con, $tariffSql);
            while ($t_row = mysqli_fetch_array($tariffResult)) {
                $cus_pro_ids .= $t_row['product_id'] . ',';
            }
            $cus_pro_ids = rtrim($cus_pro_ids, ',');
            $all_products = [];
            $product_sql =  "SELECT * FROM `products` Where id IN(" . $cus_pro_ids . ") ORDER BY id DESC";
            // echo $customerPaySql;
            // die;
            $product_result = mysqli_query($con, $product_sql);
            while ($p_row = mysqli_fetch_array($product_result)) {
                if (isset($p_row['id'])) {
                    $p_row = (object)$p_row;
                    array_push($all_products, $p_row);
                }
            }
            return $all_products;
        }
    }
    $getProducts = getProducts();
    if (isset($getProducts)) {
        http_response_code(201);
        echo json_encode(array("response" => 1, 'products' => $getProducts, "message" => "All Products"));
        exit();
    } else {
        http_response_code(200);
        echo json_encode(array("response" => 0, "message" => "No Product Found for this customer"));
        exit();
    }
}
function customer_login()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    $email = mysqli_real_escape_string($con, $data_post['email']);
    $email = strtolower($email);
    $password = mysqli_real_escape_string($con, md5($data_post['password']));
    $query = mysqli_query($con, "SELECT * from customers where LOWER(email)='$email' AND password = '" . $password . "' AND status = 1 ");
    $count = mysqli_affected_rows($con);
    if ($count > 0) {
        $fetch = mysqli_fetch_array($query);
        $customer_id = $fetch['id'];
        mysqli_query($con, "UPDATE customers SET is_online = 1 WHERE id = " . $fetch['id']);
        http_response_code(201);
        echo json_encode(array("response" => 1, 'customer_id' => $customer_id, "message" => "Login Successfull"));
        exit();
    } else {
        http_response_code(200);
        echo json_encode(array("response" => 0, "message" => "Login Failed"));
        exit();
    }
}

function track_one_order()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    $customer_id = $data_post['customer_id'];
    $track_no = $data_post['track_no'];

    $query = mysqli_query($con, "SELECT orders.track_no,orders.order_time,orders.status,orders.destination,orders.origin,orders.rname,orders.rphone,orders.sname,orders.sender_address,orders.sphone,orders.product_desc,orders.order_type,orders.weight,orders.order_type_booking,orders.receiver_address,orders.collection_amount,services.service_code FROM orders join services ON orders.order_type = services.id WHERE orders.customer_id=" . $customer_id . " AND orders.track_no='" . $track_no . "' ORDER BY orders.id DESC");
    $ra_one = mysqli_fetch_assoc($query);
    $historyData = [];
    $historyQ = mysqli_query($con, "SELECT * from order_logs WHERE order_no='" . $track_no . "'");

    while ($row = mysqli_fetch_assoc($historyQ)) {
        array_push($historyData, $row);
    }
    if (mysqli_affected_rows($con) >  0) {
        http_response_code(201);
        echo json_encode(array("response" => 1, "orders" => $ra_one, "history" => $historyData, "message" => "All orders."));
        exit();
    } else {
        http_response_code(200);
        echo json_encode(array("response" => 0, "message" => "No record found."));
        exit();
    }
}

function customer_orders()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    $customer_id = $data_post['customer_id'];
    $return_data = array();

    $query = mysqli_query($con, "SELECT orders.track_no,orders.order_time,orders.destination,orders.status,orders.origin,orders.rname,orders.rphone,orders.order_type,orders.weight,orders.collection_amount,orders.receiver_address,services.service_code FROM orders join services ON orders.order_type = services.id WHERE orders.customer_id=" . $customer_id . " ORDER BY orders.id DESC");
    while ($row = mysqli_fetch_assoc($query)) {
        array_push($return_data, $row);
    }

    if (mysqli_affected_rows($con) >  0) {
        http_response_code(201);
        echo json_encode(array("response" => 1, "orders" => $return_data,   "message" => "All orders."));
        exit();
    } else {
        http_response_code(200);
        echo json_encode(array("response" => 0, "message" => "No record found."));
        exit();
    }
}
function customer_info()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    $customer_id = $_GET['id'];

    $return_data = array();
    $query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $customer_id . " ORDER BY id DESC");
    $row = mysqli_fetch_assoc($query);
    if (mysqli_affected_rows($con) >  0) {
        http_response_code(201);
        echo json_encode(array("response" => 1, "detail" => $row,   "message" => "Customer Detail."));
        exit();
    } else {
        http_response_code(200);
        echo json_encode(array("response" => 0, "message" => "No record found."));
        exit();
    }
}


function getCities()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    // header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    // $REQUEST=file_get_contents("php://input");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    $cities = array();
    $query = "SELECT DISTINCT city_name FROM cities ";
    $result = mysqli_query($con, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($cities, $row);
    }
    echo json_encode($cities);

    die();
}

function get_areas()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    // header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    $city_name = isset($_GET['cityname']) ? $_GET['cityname'] : '';
    $cities = mysqli_fetch_array(mysqli_query($con, "select id from cities where city_name='" . $city_name . "'"));
    $city_id = $cities['id'];
    $area_array = array();
    $query = mysqli_query($con, "select area_name,id from areas where city_name=" . $city_id);

    while ($row = mysqli_fetch_assoc($query)) {
        array_push($area_array, $row);
    }
    if (mysqli_affected_rows($con) >  0) {
        http_response_code(201);
        echo json_encode(array("response" => 1, "areas" => $area_array,   "message" => "Areas Detail."));
        exit();
    } else {
        http_response_code(200);
        echo json_encode(array("response" => 0, "message" => "No record found."));
        exit();
    }
}

function save_order()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    // header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    // $REQUEST=file_get_contents("php://input");
    $dataPost  =   (array)json_decode(file_get_contents("php://input"));

    $customer_id =   isset($dataPost['customer_id']) ? $dataPost['customer_id'] : '';
    $sname      =   isset($dataPost['sname']) ? $dataPost['sname'] : '';
    $sphone     =   isset($dataPost['sphone']) ? $dataPost['sphone'] : '';
    $origin     =   isset($dataPost['origin']) ? $dataPost['origin'] : '';
    $saddress   =   isset($dataPost['saddress']) ? $dataPost['saddress'] : '';
    $rname      =   isset($dataPost['rname']) ? $dataPost['rname'] : '';
    $rphone     =   isset($dataPost['rphone']) ? $dataPost['rphone'] : '';
    $raddress   =   isset($dataPost['raddress']) ? $dataPost['raddress'] : '';
    $destination =   isset($dataPost['destination']) ? $dataPost['destination'] : '';
    $area       =   isset($dataPost['area']) ? $dataPost['area'] : '';
    $item_detail =   isset($dataPost['item_detail']) ? $dataPost['item_detail'] : '';
    $special_isntructions
        =   isset($dataPost['special_isntructions']) ? $dataPost['special_isntructions'] : '';
    $pieces     =   isset($dataPost['pieces']) ? $dataPost['pieces'] : '';
    $cod_amount =   isset($dataPost['cod_amount']) ? $dataPost['cod_amount'] : '';
    $weight     =   isset($dataPost['weight']) ? $dataPost['weight'] : '';
    $service_type = isset($dataPost['service_type_val']) ? $dataPost['service_type_val'] : '';
    $product_type_id = isset($dataPost['product_type_id']) ? $dataPost['product_type_id'] : '';
    include_once('../price_calculation.php');
    // echo $origin.'next'.$destination.'next'.$weight.'next'.$customer_id.'next'.$service_type.'next'.$product_type_id;
    $price = delivery_calculation($origin, $destination, $weight, $customer_id, $service_type, $product_type_id);
    // echo $price;
    //  die;
    $customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $customer_id . " ");
    $customer_data = mysqli_fetch_array($customer_query);
    $customer_type = $customer_data['customer_type'];
    $pickup_latitude = isset($customer_data['customer_latitude']) ? $customer_data['customer_latitude'] : '';
    $pickup_longitude = isset($customer_data['customer_longitude']) ? $customer_data['customer_longitude'] : '';
    $zone_id = '0';
    $zone_q = mysqli_query($con, "SELECT zone FROM zone_cities WHERE origin='" . $origin . "' AND  ( destination='" . $destination . "' or destination ='other' or destination ='others')  ");
    if (mysqli_num_rows($zone_q) > 0) {
        $zone_r = mysqli_fetch_array($zone_q);
        $zone_id = $zone_r['zone'];
    }

    $original_no = $dataPost['rphone'];
    $original_no  = preg_replace('/[^0-9]/s', '', $original_no);
    $pos0 = substr($original_no, 0, 1);
    if ($pos0 == '3') {
        $alterno = substr($original_no, 1);
        $alterno = '0' . $original_no;
        $original_no = $alterno;
    }
    $pos = substr($original_no, 0, 2);
    if ($pos == '03') {
        $alterno = substr($original_no, 1);
        $alterno = '92' . $alterno;
        $original_no = $alterno;
    }
    $date = date('Y-m-d H:i:s');
    $excl_amount = $price;
    $incl_amount = 0;
    $pft_amount = 0;
    // to calculate pft_amount inc_amount price
    function getBarCodeImage($text = '', $code = null, $index)
    {
        require_once('../includes/BarCode.php');
        $barcode = new BarCode();
        $path = '../assets/barcodes/imagetemp' . $index . '.png';
        $barcode->barcode($path, $text);
        $folder_path = 'assets/barcodes/imagetemp' . $index . '.png';
        return $folder_path;
    }
    function encrypt($string)
    {
        $key = "usmannnn";
        $result = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) + ord($keychar));
            $result .= $char;
        }

        return base64_encode($result);
    }
    $net_amount = $price;
    $fuel_surcharge_percent = getConfig('fuel_surcharge');
    $fuel_surcharge = ($net_amount / 100) * $fuel_surcharge_percent;
    $net_amount = $price + $fuel_surcharge;
    $q = mysqli_query($con, "SELECT * FROM cities WHERE city_name ='" . $origin . "' ");
    $res = mysqli_fetch_array($q);
    $state_id = isset($res['state_id']) ? $res['state_id'] : '';
    $gst_percentage = 0;
    if (isset($state_id) && !empty($state_id)) {
        $stateQ = mysqli_query($con, "SELECT tax FROM state WHERE id =" . $state_id);
        $stateResult = mysqli_fetch_array($stateQ);
        $gst_percentage = isset($stateResult['tax']) ? $stateResult['tax'] : '';
    }
    $gst_percentage =  $gst_percentage;

    $pft_amount = ($net_amount / 100) * $gst_percentage;
    $net_amount = $price + $pft_amount + $fuel_surcharge;
    $insert_qry = "INSERT INTO `orders`(`sname`,`sbname`,`sphone`, `semail`, `sender_address`, `rname`, `rphone`, `receiver_address`,`google_address`,`pickup_date`,`price`,`net_amount`,`collection_amount`,`order_date`,`payment_method`,`customer_id`,`origin`,`destination`,`weight`,`special_instruction`,`quantity`,`product_id`, `order_type`,`ref_no`,`excl_amount`,`pft_amount`,`inc_amount`,`zone_id`,`order_type_booking`,`Pick_location`,`product_desc` ,`product_type_id`,`pickup_latitude`,`pickup_longitude`,`fuel_surcharge`)
    VALUES ('" . $sname . "','" . $sname . "','" . $sphone . "','','" . $saddress . "','" . $rname . "','" . $original_no . "','" . $raddress . "','', '" . $date . "','" . $price . "','" . $net_amount . "','" . $cod_amount . "','" . $date . "','CASH','" . $customer_id . "','" . $origin . "','" . $destination . "','" . $weight . "','" . $special_isntructions . "' ,'" . $pieces . "','', '" . $service_type . "','','','" . $pft_amount . "','" . $incl_amount . "'," . $zone_id . ",5,'" . $saddress . "','" . $item_detail . "','" . $product_type_id . "','" . $pickup_latitude . "','" . $pickup_longitude . "','" . $fuel_surcharge . "') ";

    // echo $insert_qry;
    // die;

    $next_number = 0;
    $custom_track_numbers = getConfig('custom_track_numbers');
    if (isset($custom_track_numbers) && $custom_track_numbers == 1) {
        $next_number = custom_track_numbers($customer_id);
    }
    if ($next_number > 0) {
        $track_no = $next_number;
    } else {
        $track_no = $insert_id + 6000000;
    }
    $get_number = $client_code * 10000000;
    $next_number = $get_number + $nextNo;
    $next_number = $area_code . $next_number;
    $query = mysqli_query($con, $insert_qry);
    $insert_id = mysqli_insert_id($con);

    if ($insert_id > 0) {
        // backendCalculations($price, $customer_id, $insert_id);
        if ($next_number > 0) {
            $track_no = $next_number;
        } else {
            $track_no = $insert_id + 6000000;
        }
        // $track_no = $insert_id + 6000000;
        $barcode = rand(1000000, 9999999);
        $barcode = substr($barcode, 0, strlen($barcode) - strlen($insert_id));
        $barcode .= $insert_id;
        $barcode_image = getBarCodeImage($track_no, null, $insert_id);


        /**
         * Walk in Customer
         * Change status to Parcel Received at Office
         * else Order is Booked
         *
         */

        if (isset($customer_id) and $customer_id == 1) {
            $status_update = 'Parcel Received at office';
            mysqli_query($con, "UPDATE orders SET status = '" . $status_update . "'  WHERE id = $last_id");
        } else {
            $status_update = 'Order is Booked';
        }


        mysqli_query($con, "UPDATE orders SET barcode = '" . $track_no . "', current_branch=1, booking_branch=1, barcode_image = '" . $barcode_image . "', track_no = '" . $track_no . "' WHERE id =" . $insert_id);
        mysqli_query($con, "INSERT INTO order_logs(`order_no`,`order_status`,`location`,`created_on`) VALUES ('" . $track_no . "', '" . $status_update . "', '" . $origin . "','" . $date . "') ");

        $iddd = encrypt($insert_id . "-usUSMAN767###"); 

        if ($insert_id >  0) {
            http_response_code(201);
            echo json_encode(array("response" => 1,  "message" => "Data inserted."));
            exit();
        } else {
            http_response_code(200);
            echo json_encode(array("response" => 0, "message" => "Failed."));
            exit();
        }
    }
}