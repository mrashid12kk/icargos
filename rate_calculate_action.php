<?php
	include_once "includes/conn.php";
	$price = 0;
	// echo "string";
	// die;
	$origin = $_POST['origin'];
	$destination = $_POST['destination'];
	$weight = $_POST['weight'];
	$order_type = $_POST['order_type'];
	$zone_id = getConfig('rate_calculator_zone');
	if(empty($origin) || empty($destination) || empty($order_type) || empty($zone_id)  ){
		echo 0; exit();
	}
	//get zone
	$whr_dist = '';
		// if($destination != 'other')
		// {
		// 	$whr_dist = " AND (  zc.destination ='other' or  zc.destination ='others' ) ";
		// }
		$pricing_query = mysqli_query($con,"SELECT cp.point_5_kg,cp.upto_1_kg,cp.other_kg FROM customer_pricing cp INNER JOIN  zone_cities zc ON(cp.zone_id = zc.zone) INNER JOIN zone z ON(z.id = cp.zone_id) WHERE zc.origin ='".$origin."' AND zc.destination ='".$destination."'  AND z.service_type='".$order_type."'GROUP by z.service_type");
			// echo ("SELECT cp.point_5_kg,cp.upto_1_kg,cp.other_kg FROM customer_pricing cp INNER JOIN  zone_cities zc ON(cp.zone_id = zc.zone) INNER JOIN zone z ON(z.id = cp.zone_id) WHERE zc.origin ='".$origin."' AND zc.destination ='".$destination."'  AND z.service_type='".$order_type."'GROUP by z.service_type");
			// die;
		$record = mysqli_fetch_array($pricing_query);

		if($weight <=0.5){
			$price = $record['point_5_kg'];
			echo $price*1;
			exit();
		}elseif($weight >0.5 && $weight<=1){
			$price = $record['upto_1_kg'];
			echo $price;
			exit();
		}elseif($weight>5){
			echo ($record['other_kg'])*ceil(($weight-1))+($record['upto_1_kg']); exit();
		}
		// if($weight <=0.5){
		// 	$price = $record['point_5_kg'];
		// 	echo $price*1;  exit();
		// }elseif($weight >0.5 && $weight<=1){
		// 	$price = $record['upto_1_kg'];
		// 	echo $price; exit();
		// }elseif($weight>1){
		// 	echo ($record['other_kg'])*ceil(($weight-1))+($record['upto_1_kg']); exit();
		// }
		echo 0; exit();

?>
