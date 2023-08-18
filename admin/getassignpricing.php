<?php
	session_start();
	require 'includes/conn.php';
		if(isset($_POST['getpricing']) && !empty($_POST['getpricing'])){
		$zone_id = $_POST['zone_id'];
		$data_query = mysqli_query($con,"SELECT point_5_kg,upto_1_kg,upto_10_kg,upto_3_kg,other_kg,additional_point_5_kg,addition_kg_type FROM zone WHERE id=".$zone_id." ");
		$response_data = mysqli_fetch_array($data_query);
		$arr = array('point_5_kg'=>$response_data['point_5_kg'],'upto_1_kg'=>$response_data['upto_1_kg'],'upto_3_kg'=>$response_data['upto_3_kg'],'other_kg'=>$response_data['other_kg'],'additional_point_5_kg'=>$response_data['additional_point_5_kg'],'upto_10_kg'=>$response_data['upto_10_kg'],'addition_kg_type'=>$response_data['addition_kg_type']);
		echo json_encode($arr); exit();
	}
?>
