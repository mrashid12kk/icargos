<?php 


session_start();

include_once 'includes/conn.php';

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



// ***** Cash / Bank ledger data fetch ******
if(isset($_POST['searchItem']))
{
    $searchItem = $_POST['searchItem'];

    $query = "SELECT led.ledgerName,led.ledgerCode,led.id,grp_map.description  FROM `tbl_accountledger` led LEFT JOIN tbl_grp_mapping as grp_map ON grp_map.group_id = led.accountGroupId where led.ledgerName LIKE '%{$searchItem}%' AND (grp_map.description = 'Cash Ledger' OR grp_map.description = 'Bank Ledger' )";
    
     // $query = "SELECT * FROM `tbl_accountledger` WHERE ledgerName LIKE '%{$searchItem}%' AND tbl_accountledger.accountGroupId IN (27,28)";
    
    fetchLedgerData($query);
     
}
else
{
    
     $query = "SELECT led.ledgerName,led.ledgerCode,led.id,grp_map.description  FROM `tbl_accountledger` led LEFT JOIN tbl_grp_mapping as grp_map ON grp_map.group_id = led.accountGroupId where grp_map.description = 'Cash Ledger' OR grp_map.description = 'Bank Ledger' ";

     // $query = "SELECT * FROM `tbl_accountledger` WHERE tbl_accountledger.accountGroupId IN (27,28) ";

     // SELECT led.ledgerName,led.ledgerCode,led.id,led.narration,grp_map.description  FROM `tbl_accountledger` led LEFT JOIN tbl_grp_mapping as grp_map ON grp_map.group_id = led.accountGroupId where grp_map.description = 'Cash Ledger' OR grp_map.description = 'Bank Ledger'
    
    fetchLedgerData($query);
}


?>