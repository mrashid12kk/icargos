<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
session_start();
// require 'includes/conn.php';
include_once '../includes/tarif/tarif_price.php';
$product_id = isset($_POST['product_id']) ? $_POST['product_id']:''; 
$origin = isset($_POST['origin']) ? $_POST['origin']:'';
$destination = isset($_POST['destination']) ? $_POST['destination']:''; 
$weight = isset($_POST['weight']) ? $_POST['weight']:''; 
$customer_id = isset($_POST['customer_id']) ? $_POST['customer_id']:'';
$order_type = ($_POST['order_type']) ? $_POST['order_type'] : 'cod';
// echo $destination;
// echo '<br>';
// echo $origin;die();

$price = getTarifPrice($product_id,$origin,$destination,$weight,$customer_id,$order_type);
return $price;exit();



$pricing_query = null;

	$price = 0;
	//get zone
	$pricing_query = mysqli_query($con,"SELECT * FROM customer_pricing cp INNER JOIN  zone_cities zc ON(cp.zone_id = zc.zone) INNER JOIN zone z ON(z.id = cp.zone_id) WHERE zc.origin ='".$origin."' AND z.service_type='".$order_type."' AND zc.destination='".$destination."' AND cp.customer_id='".$customer_id."'   ");

		// echo "SELECT * FROM customer_pricing cp INNER JOIN  zone_cities zc ON(cp.zone_id = zc.zone) INNER JOIN zone z ON(z.id = cp.zone_id) WHERE zc.origin ='".$origin."' AND z.service_type='".$order_type."' AND zc.destination='".$destination."' AND cp.customer_id='".$customer_id."'   ";die();

		$countrow = mysqli_num_rows($zone_query);		
		$record = mysqli_fetch_array($pricing_query);
		// echo '<pre>',print_r($record),'</pre>';exit();
		// if($weight <=0.5){
		// 	$price = $record['point_5_kg'];
		// 	echo $price*1; 
		// }elseif($weight >0.5 && $weight<=1){
		// 	$price = $record['upto_1_kg'];
		// 	echo $price*$weight; 
		// }elseif($weight>1){
		// 	echo ($record['other_kg'])*ceil(($weight-1))+($record['upto_1_kg']);  
		// }
	$price = 0;
    if($weight <=0.5)
    {
        $price = $record['point_5_kg'];
        return $price*1;
    }
    if($weight >0.5 && $weight<=1)
    {
        $price = $record['upto_1_kg'];
        return $price;
    }
    if($weight > 1 && $weight <= 3 && $record['upto_3_kg'] > 0)
    {
        $price = $record['upto_3_kg'];
        return $price;
    }
    if($weight > 3 && $weight <= 10 && $record['upto_10_kg'] > 0)
    {
        $price = $record['upto_10_kg'];
        return $price;
    }
    $value = 0;
    if($record['additional_point_5_kg'] && $record['additional_point_5_kg'] > 0 && $record['addition_kg_type'] == 'Additional Weight 0.5 kg')
    {
        $value = $record['additional_point_5_kg'];
        if($weight > 1 && ($record['upto_3_kg'] <= 0 && $record['upto_10_kg'] <= 0))
        {

            $wt = explode('.',($weight-1));
            $new_weight = 0;
            if(isset($wt[1]) && $wt[1] <= 5)
            {
                $wt[1] = 5;
                $wt = implode('.', $wt);
                $new_weight = $wt/0.5;
            }
            else
            {
                $new_weight=(ceil($weight-1));
                $new_weight = $new_weight/0.5;
            }
            $price =  (($value * $new_weight)) + ($record['upto_1_kg']);
            return $price;
        }
        if($weight > 3 && ($record['upto_3_kg'] > 0 && $record['upto_10_kg'] <= 0))
        {


            $wt = explode('.',($weight-3));
            $new_weight = 0;
            if(isset($wt[1]) && $wt[1] <= 5)
            {
                $wt[1] = 5;
                $wt = implode('.', $wt);
                $new_weight = $wt/0.5;
            }
            else
            {
                $new_weight=(ceil($weight-3));
                $new_weight = $new_weight/0.5;
            }
            $price =  (($value * $new_weight)) + ($record['upto_3_kg']);
            return $price;

            // $price =  (($value) * (ceil($weight -3 ))) + ($record['upto_3_kg']);
            // return $price;
        }
        if($weight > 10)
        {

            $wt = explode('.',($weight-10));
            $new_weight = 0;
            if(isset($wt[1]) && $wt[1] <= 5)
            {
                $wt[1] = 5;
                $wt = implode('.', $wt);
                $new_weight = $wt/0.5;
            }
            else
            {
                $new_weight=(ceil($weight-10));
                $new_weight = $new_weight/0.5;
            }
            $price =  (($value * $new_weight)) + ($record['upto_10_kg']);
            return $price;

            // $price =  (($value) * (ceil($weight - 10))) + ($record['upto_10_kg']);
            // return $price;
        }
    }
    else if($record['other_kg'] > 0)
    {
         $value = $record['other_kg'];
        if($weight > 1 && ($record['upto_3_kg'] <= 0 && $record['upto_10_kg'] <= 0))
        {
            // echo $value;
            // echo '<br>';
            // echo (floor($weight - 1));die();
            $price =  (($value) * (floor($weight - 1))) + ($record['upto_1_kg']);
            return $price;
        }
        if($weight > 3 && ($record['upto_3_kg'] > 0 && $record['upto_10_kg'] <= 0))
        {
            $price =  (($value) * (floor($weight - 3))) + ($record['upto_3_kg']);
            return $price;
        }
        if($weight > 10)
        {
            // echo ''
            $price =  (($value) * (floor($weight - 10))) + ($record['upto_10_kg']);
            return $price;
        }


    }
    return $price;

// echo $record['price']*($weight-1)+$record['first_kg_price']; exit();
	?>