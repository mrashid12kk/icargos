<?php

include_once 'includes/conn.php';

// ******** Reading CSV file to server code ***************
if(isset($_FILES['file']))
{
	$file_name = $_FILES['file']['name'];
	$file_tmp_name = $_FILES['file']['tmp_name'];
	$file_type = $_FILES['file']['type'];
  $file_array = explode(".",$file_name);
  $file_extension = end($file_array);
  $allowed_extensions = array("csv");
  if(in_array($file_extension,$allowed_extensions))
  {
    $file = fopen($file_tmp_name, 'r');
    $headers = fgetcsv($file);
    $ret = array();
    while (($line = fgetcsv($file)) !== FALSE) 
    {

      $query = "SELECT id, ledgerName FROM tbl_accountledger WHERE chart_account_id_child = '{$line[0]}'";
      
      $ledgerRow = mysqli_fetch_assoc(mysqli_query($con, $query));

      if(!$ledgerRow) {
        continue;
      }

      $ret[] = [
        "description"=> "",
        "ca_code"=> $line[0],
        "type"=> ucfirst($line[1]),
        "amount"=> (float)$line[2] ?? 0,
        "chequeNo"=> $line[3] ?? '',
        "detailChequeDate"=> $line[4] ?? '',
        "detail_ledger_id"=> $ledgerRow['id'] ?? '',
        "narration"=> $line[5] ?? '',
        "detailLedgerName"=> $ledgerRow['ledgerName'] ?? '',
      ];
    }
    fclose($file);
    echo json_encode($ret);
    exit();
  }
  else{
    echo 0;
    exit;
  }
	
}


?>