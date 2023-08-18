<?php

require_once('../admin/includes/conn.php');
$type = $_GET['type'];

if ($type == 'login') {
    login();
} elseif ($type == 'dashboard') {
    dashboard();
} elseif ($type == 'pickup') {
    pickup();
} elseif ($type == 'delivery') {
    delivery();
} elseif ($type == 'search_track') {
    search_track();
} elseif ($type == 'profile') {
    profile();
} elseif ($type == 'change_password') {
    change_password();
} elseif ($type == 'contact_us') {
    contact_us();
} elseif ($type == 'update_profile') {
    update_profile();
} elseif ($type == 'track_shipment') {
    track_shipment();
} elseif ($type == 'track_shipments') {
    track_shipments();
} elseif ($type == 'pickup_request') {
    pickup_request();
} elseif ($type == 'get_all_orders') {
    get_all_orders();
} elseif ($type == 'scan_delivery') {
    scan_delivery();
} elseif ($type == 'scan_pickup') {
    scan_pickup();
} elseif ($type == 'cancel_order') {
    cancel_order();
} elseif ($type == 'getCities') {
    getCities();
} elseif ($type == 'getQoutes') {
    getQoutes();
} elseif ($type == 'getQoutesDetails') {
    getQoutesDetails();
} elseif ($type == 'historyChat') {
    getChat();
} elseif ($type == 'cList') {
    chatList();
} elseif ($type == 'sendMessage') {
    sendMessage();
}
elseif ($type == 'searchChat') {
    getNewMessage();
}

function profile()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    // header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    // $REQUEST=file_get_contents("php://input");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    // print_r($data_post['name']);

    $rider_id=$data_post['rider_id'];
    $query=mysqli_query($con,"SELECT * from users where id=".$rider_id) or die(mysqli_error($con));
    $fetch=mysqli_fetch_assoc($query);
    http_response_code(201);
    echo json_encode(array("response"=>1,'data'=>$fetch,"message" => "User Details."));
    exit();

}



function login()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    // header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    // $REQUEST=file_get_contents("php://input");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    // print_r($data_post['name']);

    $user_name=$data_post['user_name'];
    $query=mysqli_query($con,"select * from users where user_name='$user_name' or email='$user_name'") or die(mysqli_error($con));
    $fetch=mysqli_fetch_assoc($query);
    $password=mysqli_real_escape_string($con,$data_post['password']);
    $hash=$fetch['password'];
    if(password_verify($password,$hash)){
        http_response_code(201);
        if (isset($fetch['id']) && !empty($fetch['id'])) {
            $sql = "SELECT balance FROM rider_wallet_ballance WHERE rider_id=".$fetch['id'];
            $response = mysqli_query($con,$sql);
            $result = mysqli_fetch_assoc($response);
            $rider_balance = isset($result['balance']) ? $result['balance'] : 0;
            echo json_encode(array("response"=>1,'rider_balance'=>$rider_balance, 'user_id'=>$fetch['id'],"message" => "Login successfull."));
            exit();
        }
    }else{
        http_response_code(200);
        echo json_encode(array("response"=>0,"message" => "Login Failed"));
        exit();
    }

}
function change_password()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    // header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    // $REQUEST=file_get_contents("php://input");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    // print_r($data_post['name']);

    $old_password=$data_post['old_password'];
    $new_password=$data_post['new_password'];
    $user_id=$data_post['user_id'];

    $query=mysqli_query($con,"SELECT * from users where id='$user_id'");

    $fetch=mysqli_fetch_assoc($query);

    $password=mysqli_real_escape_string($con,$old_password);

    $hash=$fetch['password'];

    if(password_verify($password,$hash)){
        $newpass=mysqli_real_escape_string($con,password_hash($new_password,PASSWORD_DEFAULT));
        $query=mysqli_query($con,"UPDATE `users` SET `password`='$newpass' WHERE id='$user_id'");
        $rowcount=mysqli_affected_rows($con);
        if($rowcount > 0){
            http_response_code(201);
            echo json_encode(array("response"=>1,"message" => "Password updated successfully."));
            exit();
        }
        else{
            http_response_code(200);
            echo json_encode(array("response"=>0,"message" => "Faild to update password."));
            exit();
        }

    }
    else{
        http_response_code(200);
        echo json_encode(array("response"=>0,"message" => "Error occured try again later."));
        exit();
    }
}

function dashboard()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    // header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    // $REQUEST=file_get_contents("php://input");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    // print_r($data_post['name']);
    $rider_id= $data_post['rider_id'];
    // print_r($data_post['rider_id']);
    // die();
    $query1 = mysqli_query($con,"SELECT * FROM assignments WHERE rider_id =".$rider_id." AND assignment_type='Delivery'  order by id desc ");
    $ordersdeliveriescount=mysqli_affected_rows($con);

    $query3 = mysqli_query($con,"SELECT * FROM assignments WHERE rider_id =".$rider_id." AND assignment_type='Pickup' order by id desc ");
    $orderspickupcount=mysqli_affected_rows($con);
    http_response_code(201);
    echo json_encode(array("response"=>1,'ordersdeliveriescount'=>$ordersdeliveriescount,'orderspickupcount'=>$orderspickupcount,"message" => "Total count."));
    exit();

}

function pickup()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    // $REQUEST=file_get_contents("php://input");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    $rider_id= $data_post['rider_id'];
    $limit = $data_post['limit'];

    $pickQ= "SELECT assignment_record.order_num, orders.track_no, orders.collection_amount, orders.status, orders.origin, orders.destination, orders.sname, orders.sbname, orders.semail, orders.sender_address from assignment_record join orders on assignment_record.order_num = orders.track_no WHERE assignment_record.rider_status_done_no = '0' AND assignment_record.assignment_type=1  AND  assignment_record.user_id =".$rider_id."   order by assignment_record.id desc LIMIT ".$limit." ";

    $pickupresponse = mysqli_query($con,$pickQ);
    $data = [];
    while($row= mysqli_fetch_assoc($pickupresponse)) {
        array_push($data, $row) ;
    }

    http_response_code(201);
    echo json_encode(array("response"=>1,'data'=>$data, "message" => "Total Pickup."));
    exit();
}

function delivery()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    // header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    // $REQUEST=file_get_contents("php://input");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    // print_r($data_post['name']);
    $rider_id= $data_post['rider_id'];
    $limit = $data_post['limit'];

    $deliverQ= "SELECT assignment_record.order_num, orders.track_no, orders.collection_amount, orders.status, orders.origin, orders.destination, orders.sname, orders.sbname, orders.semail, orders.sender_address from assignment_record join orders on assignment_record.order_num = orders.track_no WHERE assignment_record.rider_status_done_no = '0' AND assignment_record.assignment_type=2  AND  assignment_record.user_id =".$rider_id."  order by assignment_record.id desc LIMIT ".$limit." ";

    $deliverResponse = mysqli_query($con,$deliverQ);
    $data = [];
    while($row= mysqli_fetch_assoc($deliverResponse)) {
        array_push($data, $row) ;
    }
    // unset(count($data)-1);

    http_response_code(201);
    echo json_encode(array("response"=>1,'data'=>$data, "message" => "Total Deliveries."));
    exit();


}
function chatList()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    // Get the request data from the request body
    $data_post = json_decode(file_get_contents("php://input"), true);
    $user_id = isset($data_post['user_id']) ? $data_post['user_id'] : null;

    if ($user_id === null) {
        // User ID is missing, handle the error
        http_response_code(400);
        echo json_encode(array("response" => 0, "message" => "User ID is missing"));
        exit();
    }

    $recordsPerPage = 10;

    // Get the current page number from the client-side (you can pass it as a query parameter, e.g., ?page=1)
    $pageNumber = isset($_GET['page']) ? $_GET['page'] : 1;
    
    // Calculate the offset for pagination
    $offset = ($pageNumber - 1) * $recordsPerPage;
    
    // Modify your original query to include the LIMIT and OFFSET clauses
    $deliverQ = "SELECT cm.id, cm.order_id, cm.order_number, cm.rider_type, cm.creation_date,cs.bname as cust_name,CONCAT('https://a.icargos.com/portal/',cs.image) as custmer_photo FROM chat_master as cm inner join users as u on u.id = cm.user_id inner join customers as cs on cm.customer_id = cs.id
                    WHERE cm.user_id = ".$user_id."
                    ORDER BY cm.creation_date ASC
                    LIMIT ".$recordsPerPage." OFFSET ".$offset;
    
    $deliverResponse = mysqli_query($con, $deliverQ);
    $data = [];
    while ($row = mysqli_fetch_assoc($deliverResponse)) {
        array_push($data, $row);
    }
    // unset(count($data)-1);

    http_response_code(201);
    echo json_encode(array("response"=>1,'data'=>$data, "message" => "Chat List"));
    exit();


}
function getChat()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    // Get the request data from the request body
    $data_post = json_decode(file_get_contents("php://input"), true);
    $user_id = isset($data_post['master_id']) ? $data_post['master_id'] : null;
    if ($user_id === null) {
        // User ID is missing, handle the error
        http_response_code(400);
        echo json_encode(array("response" => 0, "message" => "Master ID is missing"));
        exit();
    }

    $recordsPerPage = 10;

    // Get the current page number from the client-side (you can pass it as a query parameter, e.g., ?page=1)
    $pageNumber = isset($_GET['page']) ? $_GET['page'] : 1;
    
    // Calculate the offset for pagination
    $offset = ($pageNumber - 1) * $recordsPerPage;
    
    // Modify your original query to include the LIMIT and OFFSET clauses
    $deliverQ = "SELECT cd.id as messageid,cm.id as master_id, cd.customer_id,cd.user_id,cd.message,cd.read_mess,cd.sender_id,cd.sender FROM chat_detail as cd inner join chat_master as cm on cm.id = cd.master_id WHERE cm.id = ".$user_id."
                    ORDER BY cm.creation_date ASC
                    LIMIT ".$recordsPerPage." OFFSET ".$offset;
    
    $deliverResponse = mysqli_query($con, $deliverQ);
    $data = [];
    while ($row = mysqli_fetch_assoc($deliverResponse)) {
        array_push($data, $row);
    }
    // unset(count($data)-1);

    http_response_code(201);
    echo json_encode(array("response"=>1,'data'=>$data, "message" => "Chat"));
    exit();


}
function sendMessage()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    // Get the request data from the request body
    $data_post = json_decode(file_get_contents("php://input"), true);
    $master_id = isset($data_post['master_id']) ? $data_post['master_id'] : null;
    $customer_id = isset($data_post['customer_id']) ? $data_post['customer_id'] : null;
    $user_id = isset($data_post['user_id']) ? $data_post['user_id'] : null;
    $message = isset($data_post['message']) ? $data_post['message'] : null;
    // $sender_id = isset($data_post['sender_id']) ? $data_post['sender_id'] : null;
    // $user_id = isset($data_post['master_id']) ? $data_post['master_id'] : null;
    if ($master_id === null) {
        // User ID is missing, handle the error
        http_response_code(400);
        echo json_encode(array("response" => 0, "message" => "Master ID is missing"));
        exit();
    }
    if ($customer_id === null) {
        // User ID is missing, handle the error
        http_response_code(400);
        echo json_encode(array("response" => 0, "message" => "Customer ID is missing"));
        exit();
    }
    if ($user_id === null) {
        // User ID is missing, handle the error
        http_response_code(400);
        echo json_encode(array("response" => 0, "message" => "User ID is missing"));
        exit();
    }
    if ($message === null) {
        // User ID is missing, handle the error
        http_response_code(400);
        echo json_encode(array("response" => 0, "message" => "Message is missing"));
        exit();
    }
    $sender = "Rider";
    $sender_id = $user_id;
    // Modify your original query to include the LIMIT and OFFSET clauses
    $deliverQ = "INSERT INTO chat_detail (master_id, customer_id, user_id, message, sender_id, sender) 
             VALUES ($master_id, $customer_id, $user_id, '$message', $user_id, 'Rider')";

    // Execute the query and check for success or failure
    if (mysqli_query($con, $deliverQ)) {
        // Insertion successful
        $response = array("response" => 1, "message" => "Message inserted successfully");
        http_response_code(201);
        
    } else {
        // Insertion failed
        $response = array("response" => 0, "message" => "Message insertion failed: " . mysqli_error($con));
        http_response_code(500); // Internal Server Error
    }

    echo json_encode($response);
    exit();
}
function searchByBname($bname) {
    global $con;
    $bname = "%" . $bname . "%";
    
    $sql = "SELECT cm.id, cm.order_id, cm.order_number, cm.rider_type, cm.creation_date,cs.bname as cust_name,CONCAT('https://a.icargos.com/portal/',cs.image) as custmer_photo FROM chat_master as cm inner join users as u on u.id = cm.user_id inner join customers as cs on cm.customer_id = cs.id
    WHERE cs.bname LIKE ?";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $bname);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    return $data;
}

// Function to search based on order_number
function searchByOrderNumber($orderNumber) {
    global $con;
    
    $sql = "SELECT cm.id, cm.order_id, cm.order_number, cm.rider_type, cm.creation_date,cs.bname as cust_name,CONCAT('https://a.icargos.com/portal/',cs.image) as custmer_photo FROM chat_master as cm inner join users as u on u.id = cm.user_id inner join customers as cs on cm.customer_id = cs.id
    WHERE cm.order_number  = ?";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $orderNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    return $data;
}
// function searchChat()
// {

// }
function getNewMessage()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    // Get the request data from the request body
    $data_post = json_decode(file_get_contents("php://input"), true);
    $query = isset($data_post['query']) ? $data_post['query'] : null;
    
    if ($query === null) {
        // User ID is missing, handle the error
        http_response_code(400);
        echo json_encode(array("response" => 0, "message" => "Search Query is missing"));
        exit();
    }
    $resultsByBname = searchByBname($query);
    $resultsByOrderNumber = searchByOrderNumber($query);
    $results = array_merge($resultsByBname, $resultsByOrderNumber);
    // while (empty($newMessages)) {
    //     usleep(500000); // Wait for 0.5 seconds (adjust this value as needed)
    //     $newMessages = getNewMessagesForClient($user_id, $lastMessageId);
    // }
    // unset(count($data)-1);

    http_response_code(201);
    echo json_encode(array("response"=>1,'data'=>$results, "message" => "Chats"));
    exit();


}
function getNewMessagesForClient($clientId, $lastMessageId) {

    $queryText = "SELECT cd.id as messageid,cm.id as master_id, cd.customer_id,cd.user_id,cd.message,cd.read_mess,cd.sender_id,cd.sender FROM chat_detail as cd inner join chat_master as cm on cm.id = cd.master_id WHERE cm.id = ".$clientId."
    ORDER BY cm.creation_date ASC ";
    $query=mysqli_query($con,$queryText) or die(mysqli_error($con));
    $fetch=mysqli_fetch_assoc($query);
    $newMessages = [];
    foreach ($fetch as $message) {
        if ($message['id'] > $lastMessageId) {
            $newMessages[] = $message;
        }
    }

    return $newMessages;
}
function search_track()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    // header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    // $REQUEST=file_get_contents("php://input");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    // print_r($data_post['name']);
    // $track_no= '6000296';
    $track_no= $data_post['track_no'];
    $trackQ= "SELECT * from orders where track_no = '".$track_no."'";

    $trackres = mysqli_query($con,$trackQ);

    $trackresponse = mysqli_fetch_assoc($trackres);

    if(mysqli_affected_rows($con) > 0){
        http_response_code(201);
        echo json_encode(array("response"=>1,'data'=>$trackresponse, "message" => "Search successfull."));
        exit();
    }else{
        http_response_code(200);
        echo json_encode(array("response"=>0, "message" => "No record found"));
        exit();
    }


}

function contact_us()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    // header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    // $REQUEST=file_get_contents("php://input");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    // print_r($data_post['name']);
    $rider_id= $data_post['rider_id'];
    $name    = $data_post['name'];
    $mobile  = $data_post['phone_no'];
    $messages = $data_post['message'];

    // print_r($data_post);
    // die();
    // $response = false;

    $query = mysqli_query($con,"select * from users where type='admin' ");
    $fetch=mysqli_fetch_assoc($query);
    $phone=$fetch['phone'];
    // $email = $fetch['email'];

    $email = 'fakharabbas2f@gmail.com';
    $data['email'] = $email;
    //  print_r($data_post);
    // die();
    // $customer_name = "Fakhar abbas";
    $message['subject'] = '';
    $message['body'] = "<b>Name: ".$name."</b>";
    $message['body'] .= '<p>Phone No: '.$mobile.'</p>';
    $message['body'] .= '<p>Message: '.$messages.'</p>';
    require_once '../admin/includes/functions.php';
    sendEmail($data, $message);

    if($message){
        http_response_code(201);
        echo json_encode(array("response"=>1, "message" => "Message sent successfully."));
        exit();
    }else{
        http_response_code(200);
        echo json_encode(array("response"=>0, "message" => "Faild to send message."));
        exit();
    }


}

function update_profile()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    // header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    // $REQUEST=file_get_contents("php://input");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    // print_r($data_post['name']);

    $user_id=$data_post['user_id'];

    $target_dir = "img/";

    if($_FILES["user_image"]["name"]!=""){

        $target_file = $target_dir .uniqid(). basename($_FILES["user_image"]["name"]);

        $extension = pathinfo($target_file,PATHINFO_EXTENSION);

        if($extension=='jpg'||$extension=='png'||$extension=='jpeg') {

            $size=$_FILES["user_image"]["size"];

            if($size>2000000)

            {

                echo "file size too large";

            }

            if(!move_uploaded_file($_FILES["user_image"]["tmp_name"],$target_file))
            {
            }

        }

        $query2=mysqli_query($con,"update users set image='$target_file' where id='$user_id'") or die(mysqli_error($con));


    }

    $Name=mysqli_real_escape_string($con,$data_post['name']);

    // $staff_id=mysqli_real_escape_string($con,$data_post['staff_id']);

    $plate_no=mysqli_real_escape_string($con,$data_post['plate_no']);

    $phone=mysqli_real_escape_string($con,$data_post['phone']);

    $email=mysqli_real_escape_string($con,$data_post['email']);

    $query=mysqli_query($con,"UPDATE `users` SET `Name`='$Name',`phone`='$phone',`plate_no`='$plate_no',email='".$email."' where id=$user_id");

    $rowcount=mysqli_affected_rows($con);

    if($rowcount > 0){
        http_response_code(201);
        echo json_encode(array("response"=>1,"message" => "Profile updated successfully."));
        exit();
    }
    else{
        http_response_code(200);
        echo json_encode(array("response"=>0,"message" => "Faild to update profile."));
        exit();
    }

}

function track_shipment()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    // header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    // $REQUEST=file_get_contents("php://input");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    // print_r($data_post['name']);
    $rider_id= $data_post['rider_id'];
    $order_id    = $data_post['order_id'];

    $recordQ = mysqli_query($con, "SELECT * from orders where track_no = '".$order_id."'");

    $orderData= mysqli_fetch_assoc($recordQ);

    $historyData = [];
    $historyQ = mysqli_query($con, "SELECT * from order_logs where order_no = '".$order_id."'");

    while($row= mysqli_fetch_assoc($historyQ)) {
        array_push($historyData, $row) ;
    }
    // print_r($data);
    // die();

    if(mysqli_affected_rows($con) >  0){
        http_response_code(201);
        echo json_encode(array("response"=>1, "result"=>$orderData, "history"=>$historyData, "message" => "Order record found."));
        exit();
    }else{
        http_response_code(200);
        echo json_encode(array("response"=>0, "message" => "No record found."));
        exit();
    }


}

function track_shipments()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    // header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    // $REQUEST=file_get_contents("php://input");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    // print_r($data_post['name']);
    $rider_id= $data_post['rider_id'];
    $order_id    = $data_post['track_no'];
    // print_r($order_id);
    // die();
    $ids = explode(',', $order_id);
    $historyData = [];
    $orderData = [];
    $history = [];
    foreach ($ids as $key => $value) {
        # code...
        $recordQ = mysqli_query($con, "SELECT * from orders where track_no = ".$value);
        $orderResult= mysqli_fetch_assoc($recordQ);
        $orderData[$key]['order_detail'] = $orderResult;
        $historyQ = mysqli_query($con, "SELECT * from order_logs where order_no = ".$value);
        $historyData=[];
        while ($row = mysqli_fetch_assoc($historyQ)) {
            array_push($historyData, $row);
        }
        // $history[$key] = $historyData;
        $orderData[$key]['order_history'] = $historyData;
        // array_push($orderData, $orderResult);

        // $historyQ = mysqli_query($con, "SELECT * from order_logs where order_no = ".$value);
        // $historyData=[];
        // while ($row = mysqli_fetch_assoc($historyQ)) {
        //     array_push($historyData, $row);
        // }
        // $history[$key] = $historyData;

        // while($historyData[] = mysqli_fetch_assoc($historyQ)){}
        //     unset($historyData[1]);
        //     $history[$key] = $historyData;

    }


    // echo '<pre>',print_r($orderData),'</pre>';die();
    if(mysqli_affected_rows($con) >  0){
        http_response_code(201);
        echo json_encode(array("response"=>1, "result"=>$orderData, "history"=>$history,  "message" => "Order record found."));
        exit();
    }else{
        http_response_code(200);
        echo json_encode(array("response"=>0, "message" => "No record found."));
        exit();
    }
}


function pickup_request()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    // header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    // print_r($data_post['name']);
    $rider_id= $data_post['rider_id'];
    $name= $data_post['name'];
    $phone_no= $data_post['phone_no'];
    $user_email= $data_post['email'];
    $province= $data_post['province'];
    $pieces= $data_post['pieces'];
    $weight= $data_post['weight'];
    $shipment_value= $data_post['shipment_value'];
    $pick_up_city= $data_post['pick_up_city'];
    $shipper_address= $data_post['shipper_address'];

    // print_r($data_post);
    // die();

    $insertQuery = "";

    $query = mysqli_query($con,"SELECT * from users where type='admin' ");
    $fetch=mysqli_fetch_assoc($query);
    $phone=$fetch['phone'];
    // $email = $fetch['email'];

    $email = 'fakharabbas2f@gmail.com';
    $data['email'] = $email;
    // $customer_name = "Fakhar abbas";
    $message['subject'] = '';
    $message['body'] = "<b>Name: $name</b>";
    $message['body'] .= '<p>Phone No: $phone_no</p>';
    $message['body'] .= '<p>Email: $user_email</p>';
    $message['body'] .= '<p>Province: $province</p>';
    $message['body'] .= '<p>Pieces: $pieces</p>';
    $message['body'] .= '<p>Weight: $weight</p>';
    $message['body'] .= '<p>Shipment_value: $shipment_value</p>';
    $message['body'] .= '<p>Pick up City: $pick_up_city</p>';
    $message['body'] .= '<p>Shipper Address: $shipper_address</p>';
    require_once '../admin/includes/functions.php';
    sendEmail($data, $message);
    // Admin

    // $message['body'] = '<p>New User Account has been created</p>';
    // $message['body'] .= '<p>Click below link to view customer.</p>';
    // sendEmailToAdmin($data, $message);



    // $rphone = "03037492694";
    // $rphone  = preg_replace('/[^0-9]/s','',$rphone);
    // $pos0 = substr($rphone, 0,1);
    // if($pos0 == '3'){
    //     $alterno=substr($rphone,1);
    //     $alterno = '0'.$rphone;
    //     $sphone = $alterno;
    // }
    // $pos = substr($rphone, 0,2);
    // if($pos == '03'){
    //     $alterno=substr($rphone,1);
    //     $alterno = '92'.$alterno;
    //     $rphone = $alterno;
    // }

    // $sms_data = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM sms_settings WHERE id=1 "));
    // //////////////SMS///////////////
    // $sms = "";
    // $sms .= "Dear Customer, \r\n";
    // // $sms .= "Your shipment from ".$record['sbname']." with tracking number ".$record['track_no']." has been picked by ".$sms_data['thanku_company'].". Track at ".$sms_data['track_from_url']." ";

    // $http_query = http_build_query([
    //     'action'  => 'send-sms',
    //     'api_key' => $sms_data['api_key'],
    //     'from'    => $sms_data['mask_from'],//sender ID
    //     'to'      => trim($rphone),
    //     'sms'     => $sms,
    // ]);

    // $url = 'https://login.brandedsms.me/sms/api?'.$http_query;
    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL, $url);
    // curl_setopt($ch, CURLOPT_HEADER, 0);
    // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    // ob_start();
    // $response = curl_exec($ch);
    // ob_end_clean();
    // curl_close($ch);





    if($fetch){
        http_response_code(201);
        echo json_encode(array("response"=>1, "message" => "Email Sent for the pickup request."));
        exit();
    }else{
        http_response_code(200);
        echo json_encode(array("response"=>0, "message" => "Error found."));
        exit();
    }
}

function get_all_orders()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    // header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    // print_r($data_post['name']);
    $username= $data_post['username'];
    $password= $data_post['password'];
    $auth_key= $data_post['auth_key'];
    $limit   = $data_post['limit'];
    $auth_query = mysqli_query($con,"SELECT * FROM users  WHERE user_name = '".$username."' AND password = '".$password."' AND auth_key ='".$auth_key."' ");
    $count = mysqli_num_rows($auth_query);
    // $number = 1;
    if($count == 0){
        http_response_code(200);
        echo json_encode(array("response"=>0, "message" => "Invalid Auth key."));
        exit();
    }else{
        $orderQ= mysqli_query($con,"SELECT * from orders order by id desc LIMIT ".$limit." OFFSET 0");

        $data = array();

        while($row= mysqli_fetch_assoc($orderQ)) {
            array_push($data, $row) ;
        }

        if($data){
            http_response_code(201);
            echo json_encode(array("response"=>1,'data'=>$data, "message" => "All orders list."));
            exit();
        }else{
            http_response_code(200);
            echo json_encode(array("response"=>0, "message" => "No record found."));
            exit();
        }

    }
}

function scan_delivery()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    // header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    // print_r($data_post['name']);
    $track_no= $data_post['track_no'];

    $status_query=mysqli_query($con,"SELECT * from order_status where active='1' and delivery_rider = 1 order by sort_num  ");

    $data = array();

    while($row= mysqli_fetch_assoc($status_query)) {
        array_push($data, $row) ;
    }
    if($data){
        http_response_code(201);
        echo json_encode(array("response"=>1,'statuses'=>$data, "message" => "All orders statuses."));
        exit();
    }else{
        http_response_code(200);
        echo json_encode(array("response"=>0, "message" => "No record found."));
        exit();
    }

}

function scan_pickup()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    // header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    $data_post = (array)json_decode(file_get_contents("php://input"));

    $track_no= $data_post['track_no'];
    $status_query=mysqli_query($con,"SELECT * from orders where track_no='".$track_no."' AND status = 'Pick up in Progress'");

    $data = array();

    while($row= mysqli_fetch_assoc($status_query)) {
        array_push($data, $row) ;
    }
    if($data){
        http_response_code(201);
        echo json_encode(array("response"=>1,'statuses'=>$data, "message" => "All orders statuses."));
        exit();
    }else{
        http_response_code(200);
        echo json_encode(array("response"=>0, "message" => "No record found."));
        exit();
    }

}


function cancel_order()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    // header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    // $REQUEST=file_get_contents("php://input");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    // print_r($data_post['name']);

    $auth_key=$data_post['auth_key'];
    $customer_id=$data_post['customer_id'];
    $track_no = $data_post['track_no'];
    $callback = $data_post['callback'];
    $confirm_cus = mysqli_query($con,"SELECT * from customers where auth_key =".$auth_key);
    // if(mysqli_num_rows($confirm_cus) = 0){
        $cancelQuery = "SELECT * FROM orders WHERE customer_id=".$customer_id." AND track_no =".$track_no;
        $cancelResult=mysqli_query($con,$cancelQuery);
        $cancelFetch=mysqli_fetch_assoc($cancelResult);
        // echo json_encode($cancelFetch['status']);
        // die();
        if($cancelFetch['status']=="New Booked"){
            $cancelOrderQuery = "UPDATE orders set status = 'Canceled by Customer' WHERE track_no = ".$track_no;
            $cancelOrderResult = mysqli_query($con,$cancelOrderQuery);
            if($cancelOrderResult)
            {


               http_response_code(201);
                echo json_encode(array("response"=>1,"message" => "Order Canceled Successfully."));
                if($callback==1)
                {
                    call_back($track_no);
                }
                exit();
            }else{
                http_response_code(200);
                echo json_encode(array("response"=>0,"message" => "Error Occured Try again Later."));
                exit();
            }

        }else{
            http_response_code(200);
            echo json_encode(array("response"=>0,"message" => "This Order Can not be canceled."));
            exit();
        }
    // }else{
    //    http_response_code(200);
    //     echo json_encode(array("response"=>0,"message" => "Invalid Auth Key."));
    //     exit();
    // }
}

function call_back($track_no=null)
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    // header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    $trackQuery = "SELECT track_no, status from orders where track_no=".$track_no;
    $trackResult = mysqli_query($con,$trackQuery);
    $trackFetch = mysqli_fetch_assoc($trackResult);
    if($trackFetch){
        http_response_code(201);
        echo json_encode(array("response"=>1,'data'=>$trackFetch, "message" => "Current status of ".$track_no." is ".$trackFetch['status']." "));
        exit();
    }else{
        http_response_code(200);
        echo json_encode(array("response"=>0, "message" => "No record found."));
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
    $query = "SELECT DISTINCT city_name FROM cities WHERE city_name !='Other' AND city_name !='Others' AND city_name !='LAHORE' ";
    $result = mysqli_query($con,$query);
    while ($row= mysqli_fetch_assoc($result)) {
       array_push($cities, $row);
    }
    echo json_encode($cities);

    die();

}

function getQoutes()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    // header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    // $REQUEST=file_get_contents("php://input");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    $services = array();
    $get_service_types = mysqli_query($con," SELECT DISTINCT id,service_type FROM services WHERE 1 ");
    $get_currency = mysqli_query($con," SELECT value  FROM config WHERE name='currency' ");
    // echo "SELECT value  FROM config WHERE name=currency";
    // die();
    while ($row= mysqli_fetch_assoc($get_service_types)) {
       array_push($services, $row);
    }
    $currency= mysqli_fetch_assoc($get_currency);
    $data['services'] = $services;
    $data['currency'] = $currency['value'];
    echo json_encode($data);

    die();

}



function getQoutesDetails(){
    global $con;
    $price = 0;
    //get zone
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    // header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    // $REQUEST=file_get_contents("php://input");
    $data_post = (array)json_decode(file_get_contents("php://input"));


    $origin=$data_post['origin'];

    $destination=$data_post['destination'];

    $weight=$data_post['weight'];

    $order_type=$data_post['order_type'];

    $whr_dist = '';
    if ($destination != 'other' or $destination != 'others')
    {
        $whr_dist = " AND  zc.destination ='".$destination."'  ";
    }
    $pricing_query = mysqli_query($con,"SELECT cp.point_5_kg,cp.upto_1_kg,cp.other_kg FROM customer_pricing cp INNER JOIN  zone_cities zc ON(cp.zone_id = zc.zone) INNER JOIN zone z ON(z.id = cp.zone_id) WHERE zc.origin ='".$origin."' ".$whr_dist." AND z.service_type='".$order_type."'   ");

    // echo "SELECT cp.point_5_kg,cp.upto_1_kg,cp.other_kg FROM customer_pricing cp INNER JOIN  zone_cities zc ON(cp.zone_id = zc.zone) INNER JOIN zone z ON(z.id = cp.zone_id) WHERE zc.origin ='".$origin."' ".$whr_dist." AND z.service_type='".$order_type."'  AND cp.customer_id='".$customer_id."'   ";

    $countrow = mysqli_num_rows($pricing_query);
    if ($countrow == 0)
    {

        $whr_dist = '';
        if ($destination != 'other')
        {
            $whr_dist = " AND (  zc.destination ='other' or  zc.destination ='others' ) ";
        }
        $pricing_query = mysqli_query($con,"SELECT cp.point_5_kg,cp.upto_1_kg,cp.other_kg FROM customer_pricing cp INNER JOIN  zone_cities zc ON(cp.zone_id = zc.zone) INNER JOIN zone z ON(z.id = cp.zone_id) WHERE zc.origin ='".$origin."' ".$whr_dist." AND z.service_type='".$order_type."'  AND cp.customer_id='".$customer_id."'   ");
    }

    $record = mysqli_fetch_array($pricing_query);
    if($weight <=0.5){
        $price = $record['point_5_kg'];
        echo $price*1;
    }elseif($weight >0.5 && $weight<=1){
        $price = $record['upto_1_kg'];
        echo $price;
    }elseif($weight>1){
        echo ($record['other_kg'])*ceil(($weight-1))+($record['upto_1_kg']);
    }
}


?>
