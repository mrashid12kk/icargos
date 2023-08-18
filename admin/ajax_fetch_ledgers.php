<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);


  require 'includes/conn.php';
  require 'includes/role_helper.php';


  if (isset($_POST['ledger_group_id']) && !empty($_POST['ledger_group_id'])) 
  {
    $account_group_id = $_POST['ledger_group_id'];

    $query = "SELECT * FROM `tbl_accountledger` 
              WHERE accountGroupId = $account_group_id";
             
    $result = mysqli_query($con,$query);

    if($result->num_rows > 0)
    {
      $data = mysqli_fetch_all($result,MYSQLI_ASSOC);
      echo json_encode($data);
    }
    else
    {
      echo json_encode("no");
    }
    
  }
  else
  {
    echo json_encode("all");
  }
  


?>