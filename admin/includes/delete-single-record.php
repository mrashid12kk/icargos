<?php 

session_start();

include_once 'includes/conn.php';

function deleteSingleRecord($query)
{
    if(!$con)
    {
        include "conn.php";
    }

    if(mysqli_query($con,$query))
    {
        echo true;
    }
    else{
        echo false;
    }
    

   
}

if(isset($_POST['recordId']))
{
    $recordId = $_POST['recordId'];
    $tableName = $_POST['tableName'];
    $query = "DELETE FROM `{$tableName}` WHERE id = '$recordId' ";
    
    deleteSingleRecord($query);
     
}



?>