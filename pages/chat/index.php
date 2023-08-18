<?php

 include 'chat.php';

$chat = new chat();

// $specificContactKey = '-NbLADMxFMco-0f78cd_';
//$specificContact = $chat->getAllContacts('order1234');
$getByClientID = $chat->getOrdersByClientId("5");

// $getAllMessages = $chat->getAllMessages('order123456');

// $getAllOrders = $chat->getAllOrders();

// echo "<pre>".var_dump($getByClientID)."</pre>";

// echo "<pre>".var_dump($getAllMessages)."</pre>";


// echo "<pre>".var_dump($getAllOrders)."</pre>";


$data = [

        "11200001001" => [ // order_number
      "chat_master" => [
        "rider_id" => "3", //order_number
        "client_id" =>"5",
        "created_at" => "22-11-2022",
        "image" => "https://www.theportlandclinic.com/wp-content/uploads/2019/07/Person-Curtis_4x5-e1564616444404.jpg",
        "last_seen" => "22-11-2022",
        "name" =>  "sajid",
        "orderId" => "123421",
        "phone" => "92312228921"
      ],
      "messages" =>[
        "message_123"=>[
         "created_at" => "22-11-2022",
         "master_id" =>  "TestID123", //order number same as contact
         "message" => "hi", //message sent
         "sender" => "0",
        ]
        ]
        ]
]; // it will created first time
 $chat->insert($data);
echo "yes";
// $chat->insertMessage($data, "order1234567");
?>