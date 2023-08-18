<?php
session_start();
// die('code by nimra');
include_once '../includes/conn.php';
if($_REQUEST['ledger'] == '1'){
	$group_id = $_REQUEST['val'];
	$query = "SELECT * FROM `tbl_accountledger` WHERE `accountGroupId` = '".$group_id."'";
	$getRecord = mysqli_query($con, $query);
	// for selected
		$qu = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `tbl_ledger_mapping`  where id = '".$_REQUEST['key']."'"));
		$get = $qu['ledger_id'];
	// for selected

	$data = array();
	while ($row = mysqli_fetch_assoc($getRecord)) {
		$selected=isset($row['id']) && $row['id'] == $get ? 'selected' : '';
		$data['options'].='<option '.$selected.' value="'.$row['id'].'">' . $row["ledgerName"] . '</option>';
	}
	echo json_encode($data);
}
?>