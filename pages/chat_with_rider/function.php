<?php
session_start();
$cus = $_SESSION['customers'];
require '../../includes/conn.php';



if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"])) {
    
    if ($_POST["action"] === "curl") {
        $s = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 5)), 0, 5);
        $curl = curl_init();

            $order_id = $_POST["order_id"];
            $order_no = $_POST["order_no"];
            $customer = $_POST["customer"];
            $user_id = $_POST["user_id"];
            $c_name = $_POST["c_name"];
            $c_phone = $_POST["c_phone"];
            $message = $_POST["message"];
            
            $data = [
                $order_no => [
                    "chat_master" => [
                        "rider_id" => $user_id,
                        "client_id" => $customer,
                        "client_id" =>"5",
                        "created_at" => date('m/d/Y h:i:s a', time()),
                        "image" => "https://www.theportlandclinic.com/wp-content/uploads/2019/07/Person-Curtis_4x5-e1564616444404.jpg",
                        "last_seen" => date('m/d/Y h:i:s a', time()),
                        "name" =>  $c_name,
                        "orderId" => $order_id,
                        "phone" => $c_phone
                    ],
                    "messages" => [
                        $s => [
                            "created_at" => date('m/d/Y h:i:s a', time()),
                            "master_id" => $order_id,
                            "message" => $message,
                            "sender" => "1"
                        ]
                    ]
                ]
            ];
            
            $data_json = json_encode($data);
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://c.a.icargos.com/api/insert",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $data_json,
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json"
                ),
            ));
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            
            curl_close($curl);
            
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                echo $response;
            }
        
    }
    
    if ($_POST["action"] === "check") {
        
        $order_number = $_POST["order_number"];
        $rider = $_POST["rider"];

        global $con;
        $sql = "SELECT * FROM orders WHERE track_no = '$order_number' AND $rider !='' ";
        $result = mysqli_query($con, $sql);
        $response =  mysqli_fetch_array($result);
        $sql1 = "SELECT * FROM users WHERE id = '$response[$rider]'  ";
        $result1 = mysqli_query($con, $sql1);
        $response1 =  mysqli_fetch_array($result1);
        $sql2 = "SELECT * FROM customers WHERE id = '$cus'  ";
        $result2 = mysqli_query($con, $sql2);
        $response2 =  mysqli_fetch_array($result2);
        $messages= array(
            "track_no" => $response["track_no"],
            "rider" => $response[$rider],
            "rider_type"=>$rider,
            "order_id"=>$response['id'],
            "user_id"=>$response1['id'],
            "customer_id"=>$response['customer_id'],
            "rider_name"=>$response1['Name'],
            "name"=>$response2['fname'],
            "phone"=>$response2['mobile_no'],
        );
        echo json_encode($messages);
        
    }
    
    
    if ($_POST["action"] === "start") {
        $master_id = $_POST["master_id"];
        global $con;
        $update = "UPDATE chat_detail SET read_mess='1' WHERE master_id='$master_id' ";
        $updated = mysqli_query($con, $update);
        $sql1 = "SELECT * FROM chat_master WHERE id = '$master_id' ";
        $result1 = mysqli_query($con, $sql1);
        $response1 =  mysqli_fetch_array($result1);
        
        $user = $response1['user_id'];
        $sql2 = "SELECT * FROM users WHERE id = '$user' ";
        $result2 = mysqli_query($con, $sql2);
        $response2 =  mysqli_fetch_array($result2);
        $image ="<img src='https://a.icargos.com/portal/admin/".$response2['image']."' id='image' alt='avatar'>";
        
        $sql = "SELECT * FROM chat_detail WHERE master_id = '$master_id' ";
        $result = mysqli_query($con, $sql);
        $data = array(
            "master"=>$response1,
            "user"=>$response2,
            'image'=>$image,
            );
    
        echo json_encode($data);
        
    }
    if ($_POST["action"] === "send") {
        
        $master_id = $_POST["master_id"];
        $user_id = $_POST["user"];
        $mass = $_POST["message"];
        $sender = 'Customer';
        $sender_id = $_SESSION['customers'];
        global $con;
        $sql  = "INSERT INTO `chat_detail`(`master_id`,`customer_id`,`user_id`,`message`, `sender_id`, `sender`) VALUES ('$master_id','$sender_id','$user_id','$mass','$sender_id','$sender')";
        mysqli_query($con, $sql);
        $data = 'Inserted';
        echo json_encode($data);
    }
    
    
     if ($_POST["action"] === "initiate_chat") {
        $order_id = $_POST["order_id"];
        $order_no = $_POST["order_no"];
        $customer = $_SESSION['customers'];
        $user_id = $_POST["user_id"];
        $rider_type = $_POST["rider_type"];
        $master  = "INSERT INTO `chat_master`(`customer_id`,`order_id`,`user_id`,`order_number`, `rider_type`) VALUES ('$customer','$order_id','$user_id','$order_no','$rider_type')";
        mysqli_query($con, $master);
        $last_id = mysqli_insert_id($con);
       
        $mass = $_POST["message"];
        $sender = 'Customer';
        $sender_id = $_SESSION['customers'];
        global $con;
        $sql  = "INSERT INTO `chat_detail`(`master_id`,`customer_id`,`user_id`,`message`, `sender_id`, `sender`) VALUES ('$last_id','$sender_id','$user_id','$mass','$sender_id','$sender')";
        mysqli_query($con, $sql);
        $data = array(
            "master_id"=>$last_id,
            );
        echo json_encode($data);
    }
    
    
    if ($_POST["action"] === "check_chat") {
        $order_no = $_POST["order_no"];
        $sql1 = "SELECT * FROM chat_master WHERE order_number = '$order_no'  ";
        $result1 = mysqli_query($con, $sql1);
        $response3 =  mysqli_fetch_array($result1);
        $rowcount=mysqli_num_rows($result1);
        if($rowcount >  0){
            $id = $response3['id'];
        }else{
            $id = "";
        }
        $data = array(
            "master_id"=>$id,
            );
        echo json_encode($data);
    }
    
    
        
    }
//     if($_SERVER["REQUEST_METHOD"] === "GET") {
//         if ($_GET["action"] === "get_chat") {
//     $master_id = $_GET["master_id"];
//     global $con;
//     $sql1 = "SELECT * FROM chat_detail WHERE master_id = '$master_id' ";
//     $result1 = mysqli_query($con, $sql1);
//     $rowcount = mysqli_num_rows($result1);

//     if ($rowcount > 0) {
//         $output = ""; // Initialize $output outside the loop to accumulate data

//         foreach ($result1 as $v) {
//             $user = $v['user_id'];
//         $sql2 = "SELECT * FROM users WHERE id = '$user' ";
//         $result2 = mysqli_query($con, $sql2);
//         $response2 =  mysqli_fetch_array($result2);
        
//         $user1 = $v['customer_id'];
//         $sql3 = "SELECT * FROM customers WHERE id = '$user1' ";
//         $result3 = mysqli_query($con, $sql3);
//         $response3 =  mysqli_fetch_array($result3);
            
//             $dateTime = new DateTime($v['date_time']);
//             $formattedDate = $dateTime->format('d/m/Y');
//             $formattedTime = $dateTime->format('h:iA');
//             if($formattedDate == date('d/m/Y')){
//                 $date = 'Today';
//             }else{
//                 $date = $formattedDate;
//             }
//             // Your existing loop code...
//             // (omitted for brevity)
            
//             // Accumulate the HTML data in the $output variable
//             if ($v['sender_id'] == $_SESSION['customers'] && $v['sender'] == 'Customer') {
//                 $output .= "<li class='clearfix'>
//                     <div class='message-data align-right'>
//                         <span class='message-data-time'>" . $formattedTime . ", " . $date . "</span>
//                         <span class='message-data-name'>" . $response3['fname'] . "</span> <i class='fa fa-circle me'></i>
//                     </div>
//                     <div class='message other-message float-right'>
//                         " . $v['message'] . "
//                     </div>
//                 </li>";
//             } else {
//                 $output .= "
//                     <li>
//                         <div class='message-data'>
//                             <span class='message-data-name'><i class='fa fa-circle online'></i> " . $response2['Name'] . "</span>
//                             <span class='message-data-time'>" . $formattedTime . ", " . $date . "</span>
//                         </div>
//                         <div class='message my-message'>
//                             " . $v['message'] . "
//                         </div>
//                     </li>
//                 ";
//             }
//         }

//         // Clean up the HTML output by removing unnecessary whitespace and newlines
//         $output = trim($output);
//         $output = preg_replace('/\s+/', ' ', $output);

//         // Encode the final output as a JSON string and send it as the AJAX response
//         echo json_encode($output, JSON_UNESCAPED_SLASHES);
//     } else {
//         $output = '';
//         echo json_encode($output, JSON_UNESCAPED_SLASHES);
//     }
// }

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if ($_GET["action"] === "get_chat") {
        $master_id = $_GET["master_id"];
        
        $last_message_id = $_GET["last_message_id"]; // ID of the last displayed message

        global $con;

        // Modify the SQL query to load newer messages based on the last displayed message ID
        
            $sql1 = "SELECT * FROM chat_detail WHERE master_id = '$master_id'  ORDER BY id DESC  LIMIT 50";
        
        $result1 = mysqli_query($con, $sql1);
        $rowcount = mysqli_num_rows($result1);
        $chatMessages = array();

// Fetch the results and store them in the $chatMessages array
        while ($row = mysqli_fetch_assoc($result1)) {
            $chatMessages[] = $row;
        }
        
        // Reverse the array of chat messages using array_reverse
        $reversedChatMessages = array_reverse($chatMessages);

        if ($rowcount > 0) {
            $output = "";  // Initialize $output as an empty array

            foreach ($reversedChatMessages as $v) {
                $user = $v['user_id'];
                $sql2 = "SELECT * FROM users WHERE id = '$user' ";
                $result2 = mysqli_query($con, $sql2);
                $response2 =  mysqli_fetch_array($result2);

                $user1 = $v['customer_id'];
                $sql3 = "SELECT * FROM customers WHERE id = '$user1' ";
                $result3 = mysqli_query($con, $sql3);
                $response3 =  mysqli_fetch_array($result3);

                $dateTime = new DateTime($v['date_time']);
                $formattedDate = $dateTime->format('d/m/Y');
                $formattedTime = $dateTime->format('h:iA');
                if ($formattedDate == date('d/m/Y')) {
                    $date = 'Today';
                } else {
                    $date = $formattedDate;
                }

                if ($v['sender_id'] == $_SESSION['customers'] && $v['sender'] == 'Customer') {
                $output .= "<li class='clearfix'>
                    <div class='message-data align-right'>
                        <span class='message-data-time'>" . $formattedTime . ", " . $date . "</span>
                        <span class='message-data-name'>" . $response3['fname'] . "</span> <i class='fa fa-circle me'></i>
                    </div>
                    <div class='message other-message float-right'>
                        " . $v['message'] . "
                    </div>
                </li>";
            } else {
                $output .= "
                    <li>
                        <div class='message-data'>
                            <span class='message-data-name'><i class='fa fa-circle online'></i> " . $response2['Name'] . "</span>
                            <span class='message-data-time'>" . $formattedTime . ", " . $date . "</span>
                        </div>
                        <div class='message my-message'>
                            " . $v['message'] . "
                        </div>
                    </li>
                ";
            }
                $id = $v['id'];
                
            }

             // Clean up the HTML output by removing unnecessary whitespace and newlines
                $output = trim($output);
                $output = preg_replace('/\s+/', ' ', $output);
                
                $res = array(
                    'mess'=>$output,
                    'mess_id'=>$id
                    );
                // Encode the final output as a JSON string and send it as the AJAX response
                echo  json_encode($res, JSON_UNESCAPED_SLASHES);
        } else {
            $output = 'ok'; // Return an empty array if no messages found
            echo json_encode($output);
        }
    }
    
    if ($_GET["action"] === "pre_get_chat") {
        $master_id = $_GET["master_id"];
        
        $last_message_id = $_GET["last_message_id"]; // ID of the last displayed message

        global $con;

        // Modify the SQL query to load newer messages based on the last displayed message ID
        if($last_message_id > 0){
        $sql1 = "SELECT * FROM chat_detail WHERE master_id = '$master_id' AND id < $last_message_id ORDER BY id DESC  LIMIT 50";
        
        $result1 = mysqli_query($con, $sql1);
        $rowcount = mysqli_num_rows($result1);
        $chatMessages = array();

// Fetch the results and store them in the $chatMessages array
        while ($row = mysqli_fetch_assoc($result1)) {
            $chatMessages[] = $row;
        }
        
        // Reverse the array of chat messages using array_reverse
        $reversedChatMessages = array_reverse($chatMessages);

        if ($rowcount > 0) {
            $output = "";  // Initialize $output as an empty array

            foreach ($reversedChatMessages as $v) {
                $user = $v['user_id'];
                $sql2 = "SELECT * FROM users WHERE id = '$user' ";
                $result2 = mysqli_query($con, $sql2);
                $response2 =  mysqli_fetch_array($result2);

                $user1 = $v['customer_id'];
                $sql3 = "SELECT * FROM customers WHERE id = '$user1' ";
                $result3 = mysqli_query($con, $sql3);
                $response3 =  mysqli_fetch_array($result3);

                $dateTime = new DateTime($v['date_time']);
                $formattedDate = $dateTime->format('d/m/Y');
                $formattedTime = $dateTime->format('h:iA');
                if ($formattedDate == date('d/m/Y')) {
                    $date = 'Today';
                } else {
                    $date = $formattedDate;
                }

                if ($v['sender_id'] == $_SESSION['customers'] && $v['sender'] == 'Customer') {
                $output .= "<li class='clearfix'>
                    <div class='message-data align-right'>
                        <span class='message-data-time'>" . $formattedTime . ", " . $date . "</span>
                        <span class='message-data-name'>" . $response3['fname'] . "</span> <i class='fa fa-circle me'></i>
                    </div>
                    <div class='message other-message float-right'>
                        " . $v['message'] . "
                    </div>
                </li>";
            } else {
                $output .= "
                    <li>
                        <div class='message-data'>
                            <span class='message-data-name'><i class='fa fa-circle online'></i> " . $response2['Name'] . "</span>
                            <span class='message-data-time'>" . $formattedTime . ", " . $date . "</span>
                        </div>
                        <div class='message my-message'>
                            " . $v['message'] . "
                        </div>
                    </li>
                ";
            }
                $id = $v['id'];
                
            }

             // Clean up the HTML output by removing unnecessary whitespace and newlines
                $output = trim($output);
                $output = preg_replace('/\s+/', ' ', $output);
                
                $res = array(
                    'mess'=>$output,
                    'mess_id'=>$id
                    );
                // Encode the final output as a JSON string and send it as the AJAX response
                echo  json_encode($res, JSON_UNESCAPED_SLASHES);
                
        } else {
            $output = 'ok'; // Return an empty array if no messages found
            echo json_encode($output);
        }
        }else{
            $output = 'ok'; // Return an empty array if no messages found
            echo json_encode($output);
        }
    }


        
         if ($_GET["action"] === "get_list") {
                $cus = $_SESSION['customers'];
                $sql = "SELECT * FROM chat_master WHERE customer_id = '$cus' ";
                    $result = mysqli_query($con, $sql);
                    foreach($result as $v){
                         $sql1 = "SELECT * FROM users WHERE id = '$v[user_id]'  ";
                        $result1 = mysqli_query($con, $sql1);
                        $response1 =  mysqli_fetch_array($result1);
                        
                        $dateTime = new DateTime($v['creation_date']);
                        $formattedTime = $dateTime->format('h:iA');
                        $formattedDate = $dateTime->format('d/m/Y');
                        
                        $id = $v['id'];
                        $sql3 = "SELECT * FROM chat_detail WHERE master_id = '$id' AND  sender = 'Rider' AND  read_mess = '0' ";
                        $result3 = mysqli_query($con, $sql3);
                        $rowcount = mysqli_num_rows($result3);
                        if($rowcount > 0){
                            $count = "<span>'.$rowcount.'</span>";
                        }else{
                            $count = "";
                        }
                        $output .= '<li class="clearfix start" id="start'. $v['id'].'" onclick="start_chat('. $v['id'].');">
                		            <input type="hidden" class="master_id" value="'.$v['id'].'" >
                		           <img src="https://a.icargos.com/portal/admin/'. $response1['image'].' " alt="avatar"> 
                		          <div class="about">
                		            <div class="status">
                		               <i class="fa fa-circle online"></i>'.$v['order_number'].' 
                		            </div>
                		             <div class="date_box">
                		               '. $formattedDate.'  '.$formattedTime.' 
                		            </div>
                		             <div class="name chat_noti">'.$response1['Name'].' 
                                              '.$count.'
                                     </div> 
                		            
                		          </div>
                		        </li>';
                    }
                     // Clean up the HTML output by removing unnecessary whitespace and newlines
        $output = trim($output);
        $output = preg_replace('/\s+/', ' ', $output);

        // Encode the final output as a JSON string and send it as the AJAX response
        echo json_encode($output, JSON_UNESCAPED_SLASHES);
            }
            
            
            
        if ($_GET["action"] === "get_search_list") {
        $cus = $_SESSION['customers'];
        $searchTerm = $_GET["search_term"]; // Get the search term

        $sql = "SELECT * FROM chat_master WHERE customer_id = '$cus' AND order_number LIKE '%$searchTerm%' "; // Modify the SQL query to include the search term
        $result = mysqli_query($con, $sql);
       foreach($result as $v){
                         $sql1 = "SELECT * FROM users WHERE id = '$v[user_id]'  ";
                        $result1 = mysqli_query($con, $sql1);
                        $response1 =  mysqli_fetch_array($result1);
                        
                        $dateTime = new DateTime($v['creation_date']);
                        $formattedTime = $dateTime->format('h:iA');
                        $formattedDate = $dateTime->format('d/m/Y');
                        
                        $id = $v['id'];
                        $sql3 = "SELECT * FROM chat_detail WHERE master_id = '$id' AND  sender = 'Rider' AND  read_mess = '0' ";
                        $result3 = mysqli_query($con, $sql3);
                        $rowcount = mysqli_num_rows($result3);
                        if($rowcount > 0){
                            $count = "<span>'.$rowcount.'</span>";
                        }else{
                            $count = "";
                        }
                        $output .= '<li class="clearfix start" id="start'. $v['id'].'" onclick="start_chat('. $v['id'].');">
                		            <input type="hidden" class="master_id" value="'.$v['id'].'" >
                		           <img src="https://a.icargos.com/portal/admin/'. $response1['image'].' " alt="avatar"> 
                		          <div class="about">
                		            <div class="status">
                		               <i class="fa fa-circle online"></i>'.$v['order_number'].' 
                		            </div>
                		             <div class="date_box">
                		               '. $formattedDate.'  '.$formattedTime.' 
                		            </div>
                		             <div class="name chat_noti">'.$response1['Name'].' 
                                              '.$count.'
                                     </div> 
                		            
                		          </div>
                		        </li>';
                    }
        // Clean up the HTML output by removing unnecessary whitespace and newlines
        $output = trim($output);
        $output = preg_replace('/\s+/', ' ', $output);

        // Encode the final output as a JSON string and send it as the AJAX response
        echo json_encode($output, JSON_UNESCAPED_SLASHES);
    }
        
        
    }



        
        
        
        
        
        


?>