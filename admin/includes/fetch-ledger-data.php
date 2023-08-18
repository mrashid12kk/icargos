<?php 

session_start();

include_once 'http://localhost/icargos/admin/includes/conn.php';

function fetchLedgerData($query)
{
    if(!$con)
    {
        include "conn.php";
    }

    $query_result = mysqli_query($con,$query);
    

    if($query_result->num_rows > 0)
    {
        $data  = [];
        $records = mysqli_fetch_all($query_result,MYSQLI_ASSOC);
        foreach($records as $record)
        {
            $data[] = array("id"=>$record['id'],"text"=>$record['ledgerName']);
        }

        echo json_encode($data);

    }
}

if(isset($_POST['searchTerm']))
{
    
 
    $searchItem = $_POST['searchTerm'];
    echo $query = "SELECT * FROM `tbl_accountledger` WHERE branchCode = ".$_SESSION['branch_id'] ." AND ledgerName LIKE '%{$searchItem}%'";
    
    fetchLedgerData($query);
     
}
else
{
  echo  $query = "SELECT * FROM `tbl_accountledger` WHERE branchCode = ".$_SESSION['branch_id'] ." LIMIT 5";

    fetchLedgerData($query);
}


?>