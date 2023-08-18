<?php
function getSonicModeName($id)
{
	$output ='';
	if ($id == 1) {
		$output = 'Overnight';
	}else if ($id == 2)
	{
		$output = 'Overland';
	}else if ($id == 3)
	{
		$output = 'Detain';
	}else if ($id == 4)
	{
		$output = 'Same-day';
	} 
	return $output;
} 



function getDataById($table_name,$where)
{
	global $con; 
	$output = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM $table_name $where  "));
	return $output;
} 


function getSonicCities()
{
	global $con; 
	$api_data = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM third_party_apis WHERE id = 1 "));
	$api_key = $api_data['api_key'];

	$ch = curl_init('https://sonic.pk/api/cities');
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: '.$api_key));
	$result_cities = curl_exec($ch);
	curl_close($ch);
	$cities_listing = json_decode($result_cities);

 	return $cities_listing;
}

?>