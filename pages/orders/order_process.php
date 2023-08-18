<?php 
date_default_timezone_set("Asia/Karachi");
session_start();
function encrypt($string){
	$key="usmannnn";
	  $result = '';
	  for($i=0; $i<strlen($string); $i++) {
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)+ord($keychar));
		$result.=$char;
	  }
	  return base64_encode($result);
	}
require '../../includes/conn.php';
function getBarCodeImage($text = '', $code = null, $index) {
  require_once('../../includes/BarCode.php');
  $barcode = new BarCode();
  $path = '../../assets/barcodes/imagetemp'.$index.'.png';
  $barcode->barcode($path, $text);
  $folder_path='assets/barcodes/imagetemp'.$index.'.png';
  return $folder_path;
}
if(isset($_POST['submit_order']) || isset($_POST['save_order']))
{
	$customer_id=0;
	// echo $_SESSION['users_id'];
	$plocation='';
	$dlocation='';
	if(isset($_SESSION['customers'])) {
	   $customer_id = $_SESSION['customers'];
	   $date=date('Y-m-d H:i:s');
	  // $customer_query = mysqli_query($con,"SELECT * FROM customers WHERE id=".$customer_id." ");
	  // $customer_data = mysqli_fetch_array($customer_query);
	 
	   $insert_qry="INSERT INTO `orders`(`sname`, `sphone`, `semail`, `sender_address`, `rname`,`remail`, `rphone`, `receiver_address`,`pickup_date`,`price`,`collection_amount`,`order_date`,`payment_method`,`customer_id`,`origin`,`destination`,`tracking_no`,`weight`,`product_id`,`product_desc`,`special_instruction`,`quantity`) VALUES ('".$_POST['fname']."','".$_POST['mobile_no']."','".$_POST['email']."','".$_POST['address']."','".$_POST['receiver_name']."','".$_POST['receiver_email']."','".$_POST['receiver_phone']."','".$_POST['receiver_address']."','".$date."','".$_POST['total_amount']."','".$_POST['collection_amount']."','".$date."','CASH','".$customer_id."','".$_POST['origin']."','".$_POST['destination']."','".$_POST['tracking_no']."','".$_POST['weight']."','".$_POST['product_id']."','".$_POST['product_desc']."','".$_POST['special_instruction']."' ,'".$_POST['quantity']."' ) ";
		$query=mysqli_query($con,$insert_qry);
		$insert_id=mysqli_insert_id($con);
		if($insert_id > 0) {
			$track_no = $insert_id+20000000;
			$barcode = rand(1000000, 9999999);
			$barcode = substr($barcode, 0, strlen($barcode)-strlen($insert_id));
			$barcode .= $insert_id;
			$barcode_image = getBarCodeImage($track_no, null, $insert_id);
			
			mysqli_query($con, "UPDATE orders SET barcode = '".$track_no."', barcode_image = '".$barcode_image."', track_no = '".$track_no."' WHERE id = $insert_id");
			mysqli_query($con,"INSERT INTO order_logs(`order_no`,`order_status`,`location`) VALUES ('".$track_no."', 'Order is Booked', '".$_POST['origin']."') ");
		}
		$iddd=encrypt($insert_id."-usUSMAN767###");
		if(isset($_POST['submit_order'])){
		// $src = "../../invoicehtml.php?id=".$iddd;
		$src = "https://pacecourierservice.com/invoicehtml.php?id=".$iddd;
		echo "<script type=\"text/javascript\">
        window.open('".$src."','mywindow','status=1');
        
    </script>";
}

 echo "<script>window.location.href='https://pacecourierservice.com/booking.php';</script>";
		?>
		<!-- <script type="text/javascript">window.open('<?php echo $src; ?>', '_blank');</script> -->
		<?php 
		 // header("Location:../../booking.php", true, 301);
}
}
?>