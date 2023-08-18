<?php

require 'includes/conn.php';
require 'includes/role_helper.php';
$city_default = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='city' "));
$country_default = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='country' "));
$name = $_POST['name'];
$country_q = mysqli_query($con, "SELECT * FROM country where country_name = '$name'");
$row = mysqli_fetch_array($country_q);
// var_dump();
$id = $row['id'];
$area_q = mysqli_query($con, "SELECT * FROM cities where country_id='$id'");
while($rows = mysqli_fetch_array($area_q)){
	$selected = $rows['city_name'] == $city_default['value']?'selected':'';
    echo '<option value="'.$rows['city_name'].'" '.$selected.'>'.$rows['city_name'].'</option>';

}
?>
