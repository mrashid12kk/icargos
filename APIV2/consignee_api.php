<?php

require_once('../admin/includes/conn.php');
$type = $_GET['type'];

if($type=='login')
{
    login();
}elseif($type=='consignee_login')
{
    consignee_login();
}elseif($type=='get_admin_number')
{
    get_admin_number();
}elseif($type=='save_record')
{
    save_record();
}elseif($type=='track_history')
{
    track_history();
}elseif($type=='track_history_single')
{
    track_history_single();
}elseif($type=='cosignee_signup')
{
    cosignee_signup();
}elseif($type=='dashboard')
{
    dashboard();
}elseif($type=='pickup')
{
    pickup();
}
elseif($type=='delivery')
{
    delivery();
}
elseif($type=='search_track')
{
    search_track();
}
elseif($type=='profile')
{
    profile();
}
elseif($type=='change_password')
{
    change_password();
}
elseif($type=='contact_us')
{
    contact_us();
}
elseif($type=='update_profile')
{
    update_profile();
}
elseif($type=='track_shipment')
{
    track_shipment();
}
elseif($type=='track_shipments')
{
    track_shipments();
}
elseif($type=='pickup_request')
{
    pickup_request();
}
elseif($type=='get_all_orders')
{
    get_all_orders();
}
elseif($type=='scan_delivery')
{
    scan_delivery();
}
elseif($type=='complete_delivery')
{
    complete_delivery();
}elseif($type=='cancel_order')
{
    cancel_order();
}
elseif($type=='getCities')
{
    getCities();
}
elseif($type=='getQoutes')
{
    getQoutes();
}
elseif($type=='getQoutesDetails')
{
    getQoutesDetails();
}
function get_admin_number()
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

    $query=mysqli_query($con,"SELECT * from users where id= 1") or die(mysqli_error($con));
    $fetch=mysqli_fetch_assoc($query);
    http_response_code(201);
    echo json_encode(array("response"=>1,'data'=>$fetch,"message" => "User Details."));
    exit();

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
    $fetch['image'] = 'https://sokojoint.com/portalv2/admin/'.$fetch['image'];
    http_response_code(201);
    echo json_encode(array("response"=>1,'data'=>$fetch,"message" => "User Details."));
    exit();

}
function track_history_single()
{

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    // header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    // $REQUEST=file_get_contents("php://input");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    // echo '<pre>',print_r($data_post),'</pre>';exit();
    global $con;
    // print_r($data_post['name']);
    $track_no = $data_post['track_no'];
    $consignee_id = $data_post['consignee_id'];

    $track_query= "SELECT orders.origin, orders.sname,orders.rname,orders.rphone,orders.remail,orders.receiver_address, consignee_records.track_no,consignee_records.id, orders.sbname, orders.status, orders.sbname, orders.sender_address, orders.semail, orders.collection_amount, consignee_records.created_at from orders join consignee_records on consignee_records.track_no = orders.track_no Where consignee_records.consignee_id=".$consignee_id." AND consignee_records.track_no LIKE '%".$track_no."%'";
    // echo $track_query;die();

    $track_response = mysqli_query($con,$track_query);

    $data = array();

    while($row= mysqli_fetch_assoc($track_response)) {
        array_push($data, $row) ;
    }


    http_response_code(201);
    echo json_encode(array("response"=>1,'data'=>$data, "message" => "Total Track records."));
    exit();

}
function track_history()
{
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    // header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    // $REQUEST=file_get_contents("php://input");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    // echo '<pre>',print_r($data_post),'</pre>';exit();
    global $con;
    // print_r($data_post['name']);
    $consignee_id = $data_post['consignee_id'];
    $limit = $data_post['limit'];
    $limit_query = '';

    if(isset($limit) && !empty($limit))
    {
        $limit_query = " LIMIT ".$limit;
    }

    $track_query= "SELECT orders.origin, orders.sname,orders.rname,orders.rphone,orders.remail,orders.receiver_address, consignee_records.track_no,consignee_records.id, orders.sbname, orders.status, orders.sbname, orders.sender_address, orders.semail, orders.collection_amount, consignee_records.created_at from orders join consignee_records on consignee_records.track_no = orders.track_no Where consignee_records.consignee_id=".$consignee_id." ORDER BY consignee_records.created_at DESC $limit_query";
    // echo $track_query;die();

    $track_response = mysqli_query($con,$track_query);

    $data = array();

    while($row= mysqli_fetch_assoc($track_response)) {
        array_push($data, $row) ;
    }


    http_response_code(201);
    echo json_encode(array("response"=>1,'data'=>$data, "message" => "Total Track records."));
    exit();
}

function save_record()
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

    $track_no=$data_post['track_no'];
    $consignee_id = $data_post['consignee_id'];
    $date = date('Y-m-d H:i:s');

    $check_track_no_q = mysqli_query($con,"SELECT id FROM consignee_records WHERE track_no = '".$track_no."' AND  consignee_id = $consignee_id");
    if(mysqli_num_rows($check_track_no_q) > 0 )
    {
        $last_id_res = mysqli_fetch_array($check_track_no_q);
        $last_id = $last_id_res['id'];
    }
    else
    {
        $insert=mysqli_query($con,"INSERT INTO consignee_records(`consignee_id`,`track_no`,`created_at`) VALUES ('$consignee_id','$track_no', '$date')") or die(mysqli_error($con));
        $last_id = mysqli_insert_id($con);
    }

    if($last_id > 0){
        http_response_code(201);
        echo json_encode(array("response"=>1, "message" => "Record saved for future"));
        exit();
    }else{
        http_response_code(200);
        echo json_encode(array("response"=>0,"message" => "Error occured"));
        exit();
    }
}

function consignee_login()
{
    global $con;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    // header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    // $REQUEST=file_get_contents("php://input");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    // print_r($data_post);
    // die();

    $email=$data_post['email'];
    $query=mysqli_query($con,"SELECT * FROM consignee WHERE email = '$email' ") or die(mysqli_error($con));
    $fetch=mysqli_fetch_assoc($query);
    $password=mysqli_real_escape_string($con,$data_post['password']);
    $hash=$fetch['password'];
    // if(password_verify($password,$hash)){
    if($hash){
        http_response_code(201);
        echo json_encode(array("response"=>1,'consignee_id'=>$fetch['id'],"message" => "Login successfull."));
        exit();
    }else{
        http_response_code(200);
        echo json_encode(array("response"=>0,"message" => "Login Failed"));
        exit();
    }

}

function cosignee_signup()
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
    if (isset($data_post) &&  !empty($data_post)) {
        $eamil=$data_post['email'];
        $query = "SELECT * FROM consignee where email= '$eamil'";
        $q = mysqli_query($con, $query);

        $res = mysqli_fetch_assoc($q);
        $existing = $res['email'];
        if (isset($res['email']) && !empty($res['email'])) {
            http_response_code(200);
            echo json_encode(array("response"=>2,"message" => "Email or phone already exists."));
            exit();
        }else{

            $password = PASSWORD_HASH($_POST["password"], PASSWORD_DEFAULT);
            $insert=mysqli_query($con,"INSERT INTO consignee(`email`,`password`) VALUES ('$eamil','$password')") or die(mysqli_error($con));

            $last_id = mysqli_insert_id($con);

            if($last_id > 0){
                http_response_code(201);
                echo json_encode(array("response"=>1,'consignee_id'=>$last_id,"message" => "Consignee Registered Successfylly."));
                exit();
            }else{
                http_response_code(200);
                echo json_encode(array("response"=>0,"message" => "Register Failed"));
                exit();
            }
        }
    }



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
        echo json_encode(array("response"=>1,'user_id'=>$fetch['id'],"message" => "Login successfull."));
        exit();
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
    // header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    // $REQUEST=file_get_contents("php://input");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    // print_r($data_post['name']);
    $rider_id= $data_post['rider_id'];
    $limit = $data_post['limit'];
    $pickQ= "SELECT assignment_record.order_num, orders.track_no, orders.collection_amount, orders.status, orders.origin, orders.destination, orders.sname, orders.sbname, orders.semail, orders.sender_address from assignment_record join orders on assignment_record.order_num = orders.track_no WHERE assignment_record.rider_status_done_no = '0' AND assignment_record.assignment_type=1  AND  assignment_record.user_id =".$rider_id."   order by assignment_record.id desc LIMIT ".$limit." ";

    $pickupresponse = mysqli_query($con,$pickQ);

    $data = array();

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
    $track_no= $data_post['track_no'];
    $trackQ= "SELECT * from orders where track_no = ".$track_no;

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
    $email = '$email';
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

    $target_dir = "../admin/img/";

    // if($_FILES["user_image"]["name"]!=""){

        // $target_file = $target_dir .uniqid(). basename($_FILES["user_image"]["name"]);

        // $extension = pathinfo($target_file,PATHINFO_EXTENSION);
        $target_file='';
        if(isset($data_post['profile_pic']) && $data_post['profile_pic']!=''&& $data_post['profile_pic'])
        {
            $image_name='uploaded_image_'.rand().'.jpg';
            $path=$target_dir.$image_name;
            $recievedJson=$data_post['profile_pic'];
            $base=$recievedJson;
            $binary=base64_decode($base);
            header('Content-Type: bitmap; charset=utf-8');
            $file = fopen($path, 'wb');
            fwrite($file, $binary);
            fclose($file);
            $target_file=$image_name;
        }
        if(isset($data_post['profile_pic'])){
            unset($data_post['profile_pic']);
        }
        // if($extension=='jpg'||$extension=='png'||$extension=='jpeg') {

        //     $size=$_FILES["user_image"]["size"];

        //     if($size > 2000000)

        //     {

        //         echo "file size too large";

        //     }

        //     if(!move_uploaded_file($_FILES["user_image"]["tmp_name"],$target_file))
        //     {
        //     }

        // }

        $query2=mysqli_query($con,"UPDATE users SET image='".$target_file."' WHERE id = $user_id ") or die(mysqli_error($con));


    // }

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

    $recordQ = mysqli_query($con, "SELECT * from orders where track_no = ".$order_id);
    $orderData= mysqli_fetch_assoc($recordQ);

    $historyData = [];
    $historyQ = mysqli_query($con, "SELECT * from order_logs where order_no = ".$order_id);

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
    // print_r($data_post['name']);
    $track_no= $data_post['track_no'];

    $status_query=mysqli_query($con,"SELECT * from orders where track_no=".$track_no);

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


function complete_delivery()
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
    $digital_sign= $data_post['order_signature'];
    $order_status= $data_post['order_status'];

    $query = mysqli_query($con,"SELECT orders.status,allowed_status FROM orders LEFT JOIN order_status ON orders.status=order_status.status WHERE  orders.track_no =".$track_no."   ");
    $record = mysqli_fetch_assoc($query);


    $allowed_status = explode(',', $record['allowed_status']);

    $check_status  = mysqli_query($con,"SELECT sts_id FROM order_status WHERE status ='".$active_status."'   ");
    $status_record = mysqli_fetch_array($check_status);

    $id_check = $status_record['sts_id'];


    if (!in_array($id_check, $allowed_status))
    {
        http_response_code(200);
        echo json_encode(array("response"=>0, "message" => "Order ".$track_no." can't be assigned as ".$order_status));
        exit();
    }else{

        // Start Adding Signature
        // http_response_code(201);
        // echo json_encode(array("response"=>1, "message" => "Order status done"));
        // exit();
        if (isset($_FILES["order_signature"]["name"]) and !empty($_FILES["order_signature"]["name"]))
        {
            if (!file_exists("images/order_signature/".$track_no."/")) {
                    mkdir("images/order_signature/".$track_no."/");
                }
            $target_dir = "images/order_signature/$track_no/";

            $target_file = $target_dir .uniqid(). basename($_FILES["order_signature"]["name"]);

            $extension = pathinfo($target_file,PATHINFO_EXTENSION);
            if($extension=='jpg'||$extension=='png'||$extension=='jpeg') {
                if (move_uploaded_file($_FILES["order_signature"]["tmp_name"], $target_file))
                {
                // echo $target_file;
                    mysqli_query($con,"UPDATE order SET order_signature='".$target_file."' WHERE `track_no`='".$track_no."' ");
                }
            }
        }
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