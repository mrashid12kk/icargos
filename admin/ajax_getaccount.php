 <?php

session_start();

include_once 'includes/conn.php';
include_once 'includes/role_helper.php';
if($_REQUEST['parent']){
$id = $_REQUEST['value'];
$sql = mysqli_query($con, "SELECT * FROM tbl_accountgroup where id = '".$id."'");
$record = mysqli_fetch_array($sql);
$groupIds = $record['id'];
$lastGroupQuery = mysqli_query($con, "SELECT * FROM tbl_accountgroup WHERE company_id IS NULL AND groupUnder= '".$groupIds."' ORDER BY id DESC LIMIT 1");
$lastGroupData = mysqli_fetch_array($lastGroupQuery);
// var_dump($lastGroupQuery);
$child = '';
if(isset($lastGroupData['chart_account_id_child']) && $lastGroupData['chart_account_id_child'])
	{
		$explodedData = array_reverse(explode('-', $lastGroupData['chart_account_id_child']));
		$index = $explodedData[0];
		$newIndex = $index+1;
		$explodedData[0]  = sprintf("%02d", $newIndex);
		$explodedData = array_reverse($explodedData);
		$child = implode('-', $explodedData);
	}
	else
	{
		$existingId = $record['chart_account_id_child'];
		$child =$existingId.'-01';
	}

$parent = $record['chart_account_id_child'];
$output['parent'] = $parent;
$output['child']  = $child;
echo json_encode($output);
}
if($_REQUEST['ledger']){
$id = $_REQUEST['value'];
$sql = mysqli_query($con, "SELECT * FROM tbl_accountgroup where id = '".$id."'");
$record = mysqli_fetch_array($sql);
$groupIds = $record['id'];
$lastGroupQuery = mysqli_query($con, "SELECT * FROM tbl_accountledger WHERE company_id IS NULL AND groupUnder= '".$groupIds."' ORDER BY id DESC LIMIT 1");
$lastGroupData = mysqli_fetch_array($lastGroupQuery);
// var_dump($lastGroupQuery);
$child = '';
if(isset($lastGroupData['chart_account_id_child']) && $lastGroupData['chart_account_id_child'])
	{
		$explodedData = array_reverse(explode('-', $lastGroupData['chart_account_id_child']));
		$index = $explodedData[0];
		$newIndex = $index+1;
		$explodedData[0]  = sprintf("%02d", $newIndex);
		$explodedData = array_reverse($explodedData);
		$child = implode('-', $explodedData);
	}
	else
	{
		$existingId = $record['chart_account_id_child'];
		$child =$existingId.'-01-L';
	}

$parent = $record['chart_account_id_child'];
$output['parent'] = $parent;
$output['child']  = $child;
echo json_encode($output);
}
if($_REQUEST['nature']){
	$sql = "SELECT * FROM `tbl_accountgroup` where `nature` = '".$_REQUEST['value']."' and CHAR_LENGTH(chart_account_id_child)<6 order by `chart_account_id_child` desc limit 1" ;
	$nature = mysqli_query($con,$sql);
	$data = array();
	$row = mysqli_fetch_array($nature);
$child = '';
if(isset($row['chart_account_id_child']) && $row['chart_account_id_child'])
	{
		$explodedData = array_reverse(explode('-', $row['chart_account_id_child']));
		$index = $explodedData[0];
		$newIndex = $index+1;
		$explodedData[0]  = sprintf("%02d", $newIndex);
		$explodedData = array_reverse($explodedData);
		$child = implode('-', $explodedData);
	}
	else
	{
		$existingId = $row['chart_account_id_child'];
		$child =$existingId.'-01';
	}
$output['child']  = $child;
echo json_encode($output);
}
?>