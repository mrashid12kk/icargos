<?php
require __DIR__.'/vendor/autoload.php';

use Kreait\Firebase\Factory;
//use Kreait\Firebase\ServiceAccount;
// This assumes that you have placed the Firebase credentials in the same directory
// as this PHP file.


class Chat {
   protected $database;
   protected $dbname = 'chats';

   public function __construct(){
       //$acc = ServiceAccount::fromJsonFile(__DIR__ . '/secret/taslim-chat-app-firebase-adminsdk-aelfb-eb92e53cde.json');
      
       $firebase =  (new Factory())
       ->withServiceAccount(__DIR__ . '/secret/taslim-chat-app-firebase-adminsdk-aelfb-eb92e53cde.json')
       ->withProjectId('taslim-chat-app')
       ->withDatabaseUri('https://taslim-chat-app-default-rtdb.firebaseio.com');//(new Factory)->withServiceAccount(__DIR__ . '/secret/taslim-chat-app-firebase-adminsdk-aelfb-eb92e53cde.json');
       $this->database = $firebase->createDatabase();
   }
   public function getAllOrders() {
    try {
        $contactsReference = $this->database->getReference($this->dbname);
        $contactsSnapshot = $contactsReference->getSnapshot();
        $val = $contactsSnapshot->getValue();
        $contacts = [];

        return $val;
    } catch (\Exception $e) {
        // Handle exceptions
        error_log($e->getMessage());
        return false;
    }
}
public function getAllMessages($order_id) {
    try {
        $contactsReference = $this->database->getReference($this->dbname.'/'.$order_id.'/messages');
        $contactsSnapshot = $contactsReference->getSnapshot();
        $val = $contactsSnapshot->getValue();
        $contacts = [];

        return $val;
    } catch (\Exception $e) {
        // Handle exceptions
        error_log($e->getMessage());
        return false;
    }
}
public function getOrdersByClientId($client_id) {
    try {
        $ordersReference = $this->database->getReference($this->dbname);
        $ordersSnapshot = $ordersReference->getSnapshot();
        $orders = [];

        foreach ($ordersSnapshot->getValue() as $orderNumber => $orderData) {
            if (isset($orderData['chat_master']['client_id']) && $orderData['chat_master']['client_id'] === $client_id) {
                $orders[$orderNumber] = $orderData;
            }
        }

        return $orders;
    } catch (\Exception $e) {
        // Handle exceptions
        error_log($e->getMessage());
        return false;
    }
}
   public function get($contact = NULL){    
    if (empty($contact) || !isset($contact)) {
        return FALSE;
    }
    
    try {
        $reference = $this->database->getReference($this->dbname)->getChild($contact);
        $snapshot = $reference->getSnapshot();
        
        if ($snapshot->exists()) {
            return $snapshot->getValue();
        } else {
            return $this->database;
        }
    } catch (\Exception $e) {
        // Handle exceptions here, e.g., log or print the error message
        // You can also return FALSE or an error message as needed
        return $e->getMessage();
    }
}

   public function insert(array $data) {
       if (empty($data) || !isset($data)) { return FALSE; }
       foreach ($data as $key => $value){
           $this->database->getReference()->getChild($this->dbname)->getChild($key)->set($value);
       }
       return TRUE;
   }
   public function insertMessage(array $data , $order_num) {
    if (empty($data) || !isset($data)) { return FALSE; }
    foreach ($data as $key => $value){
        $this->database->getReference()->getChild($this->dbname.'/'.$order_num)->getChild($key)->set($value);
    }
    return TRUE;
}
   public function delete($contact) {
       if (empty($contact) || !isset($contact)) { return FALSE; }
       if ($this->database->getReference($this->dbname)->getSnapshot()->hasChild($contact)){
           $this->database->getReference($this->dbname)->getChild($userID)->remove();
           return TRUE;
       } else {
           return FALSE;
       }
   }
}
?>