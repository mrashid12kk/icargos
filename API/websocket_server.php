<?php 
require_once('../admin/includes/conn.php');


// Function to fetch new messages for a client (You can replace this with your database logic)
function getNewMessagesForClient($clientId, $lastMessageId) {
    // Your logic to fetch new messages from the database or any other storage mechanism.
    // For simplicity, let's assume we have an array of messages.
    $query=mysqli_query($con,"SELECT * from chat_detail inner join chat_master on chat_detail.master_id = chat_master.id where chat_master.order_id=".$clientId) or die(mysqli_error($con));
    $fetch=mysqli_fetch_assoc($query);
    // $allMessages = [
    //     // Message format: ['id' => 'message_id', 'content' => 'message_content']
    //     ['id' => '1', 'content' => 'Hello, World!'],
    //     ['id' => '2', 'content' => 'How are you?'],
    //     // Add more messages here...
    // ];

    $newMessages = [];
    foreach ($fetch as $message) {
        if ($message['id'] > $lastMessageId) {
            $newMessages[] = $message;
        }
    }

    return $newMessages;
}

// Function to send the response to the client
function sendResponse($data) {
    // Set the appropriate headers to allow cross-origin requests
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json");
    http_response_code(201);
    echo json_encode(array("response"=>1,'data'=>$data,"message" => "Chat Messages"));
    //echo json_encode($data);
}

// Main server logic for handling long polling requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get the client ID from the query string
    $clientId = $_GET['order_id'];

    // Get the last received message ID from the query string
    $lastMessageId = isset($_GET['last_message_id']) ? $_GET['last_message_id'] : 0;

    // Fetch new messages for the client
    $newMessages = getNewMessagesForClient($clientId, $lastMessageId);

    // If there are no new messages, wait for a short time and check again (simulate long polling)
    while (empty($newMessages)) {
        usleep(500000); // Wait for 0.5 seconds (adjust this value as needed)
        $newMessages = getNewMessagesForClient($clientId, $lastMessageId);
    }

    // Send the new messages to the client
    sendResponse(['messages' => $newMessages]);
}



?>