
<?php 
require 'includes/conn.php';
require 'includes/setting_helper.php';


/*$url = 'https://kvstore.p.rapidapi.com/collections';
$collection_name = 'RapidAPI';
$request_url = $url . '/' . $collection_name;

$curl = curl_init($request_url);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, [
  'X-RapidAPI-Host: kvstore.p.rapidapi.com',
  'X-RapidAPI-Key: 7xxxxxxxxxxxxxxxxxxxxxxx',
  'Content-Type: application/json'
]);

$response = curl_exec($curl);
curl_close($curl);

echo $response . PHP_EOL;*/



if (isset($_GET['api_id'])) {
            $api_id = $_GET['api_id'];

        $return_arr = array();     
        $query=mysqli_query($con,"select * from api_statues where api_id = '$api_id';");
        // $query = array('name' => 'bilal' , 'age' => '29' );

        while($row = mysqli_fetch_array($query)){
    $id = $row['id'];
    $api_id = $row['api_id'];
    $status = $row['status'];

    $return_arr[] = array("id" => $id,
                    "api_id" => $api_id,
                    "status" => $status);
}

        echo json_encode($return_arr);
        // echo json_encode($_GET['stateID']);
                                                                    
 }

  
 ?>