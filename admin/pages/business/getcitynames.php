<?php
include_once '../../includes/conn.php';

if(isset($_POST['searchTerm']) && !empty($_POST['searchTerm'])){ 
$search = $_POST['searchTerm'];
  $fetchData = mysqli_query($con,"select * from cities where city_name like '%".$search."%' limit 2");   
  
}else{ 
  $fetchData = mysqli_query($con,"select * from users order by city_name limit 2");
} 

$data = array();
while ($row = mysqli_fetch_array($fetchData)) {    
  $data[] = array("id"=>$row['id'], "text"=>$row['city_name']);
}
echo json_encode($data);
?>