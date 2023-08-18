<?
include "functions.php";

// JSON CLIENTS
if($_GET['tip']=='client'){
	if(!$_GET['id'])$q=" client_name like '%".$_GET['name']."%'";else $q=" id='".$_GET['id']."'";
	$res=mysql_query("SELECT * FROM `invoices` where".$q." group by client_location,client_name limit 0,15")or die(mysql_error());
	$cnt=mysql_num_rows($res);
?>
{"totalResultsCount":<?=$cnt?>,"clients":[<?

$i=1;
	while($row=mysql_fetch_array($res)){
		$row['client_address']=preg_replace("/\r/", '',preg_replace("/\n/", '\n', $row['client_address']));
		
			$data[]='{"name":"'.strip_tags($row['client_name']).'","email":"'.strip_tags($row['client_email']).'","phone":"'.strip_tags($row['client_phone']).'","address": "'.strip_tags($row['client_address']).'","location":"'.strip_tags($row['client_location']).'"}';
	}
echo implode(',',$data);?>]}
<?}





// JSON products
if($_GET['tip']=='products'){
	if(!$_GET['id'])$q=" title like '%".$_GET['name']."%' or description like '%".$_GET['name']."%'";else $q=" id='".$_GET['id']."'";
	$res=mysql_query("SELECT * FROM `products` where".$q." group by title,description limit 0,15")or die(mysql_error());
	$cnt=mysql_num_rows($res);
?>
{"totalResultsCount":<?=$cnt?>,"products":[<?

$i=1;
	while($row=mysql_fetch_array($res)){
		$row['description']=preg_replace("/\r/", '',preg_replace("/\n/", '\n', $row['description']));
		$data[]='{"title":"'.strip_tags($row['title']).'","description":"'.strip_tags($row['description']).'","price":"'.strip_tags($row['price']).'"}';
	}
echo implode(',',$data);?>]}
<?}






// JSON PAYMENT METHODS
if($_GET['tip']=='payment'){
$pm=array();
	$res=mysql_query("select distinct payment_method from invoices where payment_method like '%".$_GET['name']."%' order by payment_method limit 0,15")or die(mysql_error());
	$cnt=mysql_num_rows($res);	
?>
{"totalResultsCount":<?=$cnt?>,"pmethods":[<?

$i=1;
	while($row=mysql_fetch_array($res)){
			$data[]='{"meth":"'.strip_tags(trim($row['payment_method'])).'"}';
	}
echo implode(',',$data);?>]}
<?}?>