<?php 
session_start();
require '../../includes/conn.php';
function getBarCodeImage($text = '', $code = null, $index) {
  require_once('../../../includes/BarCode.php');
  $barcode = new BarCode();
  $path = '../../../assets/barcodes/imagetemp'.$index.'.png';
  $barcode->barcode($path, $text);
  $folder_path='assets/barcodes/imagetemp'.$index.'.png';
  return $folder_path;
}
if(isset($_POST['submit_order']))
{
	// echo '<pre>',print_r($_SESSION),'</pre>';
	$customer_id=0;
	// echo $_SESSION['users_id'];
	$plocation='';
	$dlocation='';
	if(isset($_SESSION['users_id'])) {
		$user_query = mysqli_query($con, "SELECT * FROM users WHERE id = '".$_SESSION['users_id']."'");
			if(mysqli_num_rows($user_query) > 0) {
				$row = mysqli_fetch_object($user_query);
				if(isset($row->id)){
					$user_email = $row->email;
					$cust_query = mysqli_query($con, "SELECT * FROM customers WHERE email = '".$user_email."'");
					if(mysqli_num_rows($cust_query) > 0) {
						$value = mysqli_fetch_object($cust_query);
						if(isset($value->id)){
							$customer_id = $value->id;
						}
					}
				}
				if(isset($row->branch_id))
				{
					$branch_query = mysqli_query($con, "SELECT * FROM branches WHERE id = '".$row->branch_id."'");
					if(mysqli_num_rows($branch_query) > 0) {
						$value = mysqli_fetch_object($branch_query);
						if(isset($value->name)){
							$plocation.= isset($value->name) ? $value->name:'';
						}
					}
				}
				if(isset($row->type) && $row->type == 'admin')
				{
					$plocation.='Head Office';
				}
			}
			if(isset($_POST['receiver_branch']) && $_POST['receiver_branch']!='')
			{
				$r_branch_query = mysqli_query($con, "SELECT * FROM branches WHERE id = '".$_POST['receiver_branch']."'");
				if(mysqli_num_rows($r_branch_query) > 0) {
					$value_r = mysqli_fetch_object($r_branch_query);
					if(isset($value_r->name)){
						$dlocation.= isset($value_r->name) ? $value_r->name:'';
					}
				}
			}
		}
	   $date=date('Y-m-d');
	   $plocation.='<br>';
	   $plocation.='Name:'.$_POST['sender_name'].'<br>Email:'.$_POST['sender_email'].'<br>Address:'.$_POST['sender_address'];
	   $dlocation.='<br>';
	   $dlocation.='Name:'.$_POST['receiver_name'].'<br>Email:'.$_POST['receiver_email'].'<br>Address:'.$_POST['receiver_address'];
	   // echo $plocation;
	   // echo '<br>';
	   // echo $dlocation;
	   // echo $customer_id;
	   // exit();
	   $insert_qry="INSERT INTO `orders`(`pickup_type`,`plocation`,`sname`, `sphone`, `semail`, `sender_address`,`daddress`, `rname`, `rphone`, `remail`, `receiver_address`, `package_type`,`pickup_date`,`price`,`collection_amount`,`order_date`,`branch_id`,`payment_method`,`cash_by`) VALUES ('".$_POST['pickup_type']."','".$plocation."','".$_POST['sender_name']."','".$_POST['sender_phone']."','".$_POST['sender_email']."','".$_POST['sender_address']."','".$dlocation."','".$_POST['receiver_name']."','".$_POST['receiver_phone']."','".$_POST['receiver_email']."','".$_POST['receiver_address']."','".$_POST['detail'][0]['package_type']."','".$date."','".$_POST['total_amount']."','".$_POST['collection_amount']."','".$date."','".$_POST['receiver_branch']."','CASH','".$_POST['cash_by']."') ";
	   // echo $insert_qry;
		$query=mysqli_query($con,$insert_qry) or die(mysqli_error($con));
		$insert_id=mysqli_insert_id($con);
		if($insert_id > 0) {
			$barcode = rand(1000000, 9999999);
			$barcode = substr($barcode, 0, strlen($barcode)-strlen($insert_id));
			$barcode .= $insert_id;
			$barcode_image = getBarCodeImage($barcode, null, $insert_id);
			$track_no = sprintf('%06d', $insert_id);
			mysqli_query($con, "UPDATE orders SET barcode = '".$barcode."', barcode_image = '".$barcode_image."', track_no = '".$track_no."' WHERE id = $insert_id");
			
		}
		$_SESSION['order_message'] = 'Dear "'.$_POST['sender_name'].'", Your order #'.$track_no.' has been created.Order Tracking Code is '.$barcode;
		$src='../../order.php?id='.$insert_id;
		header("Location:".$src, true, 301);
		exit();
		// echo '<pre>',print_r($_POST),'</pre>';exit();
}
?>