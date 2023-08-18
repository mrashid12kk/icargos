<?php
require_once('../../admin/includes/conn.php');
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$data_post = (array)json_decode(file_get_contents("php://input"));
$type = $data_post['type'];
$email = $data_post['email'];
$phone = $data_post['email'];
// echo "<pre>";
// print_r($data_post);
// die;
$messageReturn = '';
$responseCode = 0;
$checkEmail = $result = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM users WHERE email='" . $email . "' OR phone='" . $email . "'"));
$validEmail = isset($checkEmail['email']) ? $checkEmail['email'] : '';
$rider_id = isset($checkEmail['id']) ? $checkEmail['id'] : '';
$riderName = getusernameById($rider_id);
$vericode = rand(9999, 999999);

if ($type == 'email') {
    if ($validEmail && !empty($validEmail) && $validEmail == $email) {
        $data['email'] = $email;
        $message['subject'] = 'Reset Password';
        $message['body'] = "<b>Hello " . $riderName . " </b>";
        $message['body'] .= '<p>Your verification code is:.</p>';
        $message['body'] .= '<h1>' . $vericode . '</h1>';
        mysqli_query($con, "UPDATE users set verify_code = '" . $vericode . "' WHERE id= $rider_id");
        require_once '../admin/includes/functions.php';
        sendEmail($data, $message);
        $messageReturn = 'Verification code sent to your email!';
        $responseCode = 1;
    } else {
        $messageReturn = 'Email does not exists!';
    }
}
if ($type == 'phone') {
    $sms_data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM sms_settings WHERE id=2 "));
    //////////////SMS///////////////
    $sms = "";
    $sms .= "Hello " . $riderName . ", \r\n";
    $sms .= "Your verification code is: \r\n";
    $sms .= $vericode . " \r\n";
    $rphone = $phone;
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
        'sms'     => $sms,
    ]);

    $url = 'https://login.brandedsms.me/sms/api?' . $http_query;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    ob_start();
    $response = curl_exec($ch);
    ob_end_clean();
    curl_close($ch);
    // echo $url;
    // die;
    mysqli_query($con, "UPDATE users set verify_code = '" . $vericode . "' WHERE id= $rider_id");
    $messageReturn = 'Verification code sent to your phone!';
    $responseCode = 1;
}

echo json_encode(array("response" =>  $responseCode, "rider_id" => $rider_id, "message" => $messageReturn, "code" => $vericode));

// $setPass = mysqli_real_escape_string($con, password_hash($new_password, PASSWORD_DEFAULT));
// $query = mysqli_query($con, "SELECT * from users where id=$rider_id ") or die(mysqli_error($con));
// $fetch = mysqli_fetch_assoc($query);
// $password = mysqli_real_escape_string($con, $data_post['old_password']);
// $hash = $fetch['password'];
// if (password_verify($password, $hash)) {
//     mysqli_query($con, "UPDATE users set password = '" . $setPass . "' WHERE id= $rider_id");
//     http_response_code(201);
//     exit();
// }
exit();