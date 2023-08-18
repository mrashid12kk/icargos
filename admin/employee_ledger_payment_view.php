<?php
	session_start();
	require 'includes/conn.php';
	$logo_img = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='logo' "));
	if(isset($_SESSION['users_id']) && $_SESSION['type']!=='driver' and isset($_GET['payment_id']))
	{
		$payment_id = $_GET['payment_id'];
		$pickup_query =mysqli_query($con,"SELECT ledger_pickup FROM employee_ledger_payments WHERE employee_ledger_payments.id = '".$payment_id."' AND ledger_pickup IS NOT NULL  order by id DESC");
	
		$delivery_query =mysqli_query($con,"SELECT ledger_delivered FROM employee_ledger_payments WHERE employee_ledger_payments.id = '".$payment_id."' AND ledger_delivered IS NOT NULL  order by id DESC");
		$query2_f =mysqli_query($con,"SELECT employee_ledger_payments.*, users.Name as customer  FROM employee_ledger_payments LEFT JOIN users ON (users.id = employee_ledger_payments.customer_id) where employee_ledger_payments.id = '".$payment_id."' order by id DESC");
		//geting customer data
		$customer_is     = 0;
		$reference_no    = 0;
		$cod_amount      = 0;
		$total_payable   = 0;
		$total_addition   = 0;
		$total_deduction   = 0;
		$gst_amount      = 0;
		$delivery_charges= 0;
		$returned_amount = 0;
		$total_returned_fee = 0;
		$sell_flyers_amount = 0;
		$query2_c = mysqli_query($con,"SELECT * FROM employee_ledger_payments  where id = '".$payment_id."' order by id DESC");
		$fetch2_c=mysqli_fetch_array($query2_c);
			 
			$customer_is   = $fetch2_c['customer_id'];
			$reference_no  = $fetch2_c['reference_no'];
			$cod_amount    = $fetch2_c['cod_amount'];
			$total_payable = $fetch2_c['total_payable'];
			$total_addition = isset($fetch2_c['total_addition']) ? $fetch2_c['total_addition']:0;
			$total_deduction = isset($fetch2_c['total_deduction']) ? $fetch2_c['total_deduction']:0;
			$gst_amount    = $fetch2_c['gst_amount'];
		 $delivery_charges = $fetch2_c['delivery_charges'];
		  $returned_amount = $fetch2_c['returned_amount'];
		  $total_returned_fee = $fetch2_c['total_returned_fee'];
	   $sell_flyers_amount = $fetch2_c['sell_flyers_amount'];
	   $payment_date = isset($fetch2_c['payment_date']) ? date('M d,Y',strtotime($fetch2_c['payment_date'])):'';
	   $cash_handling = $fetch2_c['cash_handling'];
		
		function getLedgerOrder($ledger_id)
		{
			$data = "";
			global $con;
			$sql= "SELECT * FROM orders   where id  = '".$ledger_id."'  ";
			$query_order = mysqli_query($con,$sql);
			while($row_data =mysqli_fetch_array($query_order))
			{
				$data = $row_data;
			}
			return $data; 
		}
		function getFlyerOrder($flyer)
		{
			$data = "";
			global $con;
			$sql= "SELECT * FROM flayer_order_index   where id  = '".$flyer."'  ";
			$query_order = mysqli_query($con,$sql);
			while($row_data =mysqli_fetch_array($query_order))
			{
				$data = $row_data;
			}
			return $data; 
		}
		function getTotal($flayer_id)
		{
			$sql_t = "Select * from flayer_orders WHERE flayer_order_index = ".$flayer_id;
			global $con;
			$query11=mysqli_query($con,$sql_t);
			$total = 0;
			while($fetch12=mysqli_fetch_array($query11))
			{
				$total += $fetch12['total_price'];
			}
			return $total;
		}
		function getCustomer($customer_id)
		{
			$data = "";
			global $con;
			$sql= "SELECT * FROM users   where id  = '".$customer_id."'  ";
			$query_order = mysqli_query($con,$sql);
			while($row_data =mysqli_fetch_array($query_order))
			{
				$data = $row_data;
			}
			return $data;  
		} 
		$customerData = getCustomer($customer_is);
		// function getBarCodeImage($text = '', $code = null, $index) 
		// { 
		// 	require_once('../includes/BarCode.php');
		// 	$barcode = new BarCode();
		// 	$path = '../assets/barcodes/imagetemp'.$index.'.png';
		// 	$barcode->barcode($path, $text);
		// 	$folder_path='assets/barcodes/imagetemp'.$index.'.png';
		// 	return $folder_path;
		// }
		// $path = getBarCodeImage(sprintf('%06d', $payment_id),null,$payment_id);
		 
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Ledger Payment Invoice</title>
<style>
	.table_wrapper , .wrapper_table_2 , .table_tree{
		max-width: 80%;
		margin: 50px 0;
	}
	.table_wrapper table {
    border-collapse: collapse;
    width: 60%;
    float: left;
    margin-bottom: 15px;
}
h3,	.table_wrapper table{
	font-family: arial, sans-serif;
}
	.table_wrapper tr th:nth-child(1) {
    text-align: left;
    font-weight: 800;
    font-size: 14px;
    background: #e8e8e8;
}
	.table_wrapper tr th:nth-child(2){
		background: #ebebeb;
	}
	.table_wrapper th:nth-child(3){
	    background: #ebebeb;
	    font-size: 16px;
	    font-weight: 300;
	}
	.table_wrapper td {
    font-size: 14px;
    border: 1px solid #08242c;
    text-align: left;
    padding: 5px 10px;
    background: #e8e8e8;
}
	.table_wrapper  td:last-child{
		background: #fff;
	}
	.table_wrapper th {
	  border: 1px solid #08242c;
	  text-align: left;
	  font-size: 12px;
	  padding: 8px;
	}
	.table_wrapper .table_height{
	}
	.table_tow {
    width: 25.4% !important;
}
	.table_tow th{
	    background: #ebebeb;
	    font-size: 16px;
	    font-weight: 300;
	    padding: 10px 80px;
	    text-align: center;	
	    border-left: none;    
	}
	.table_tow td {
    border-left: none;
    height:160.5px;
    text-align: center;
}
	.wrapper_table_2 table {
    margin-top: 15px;
    width: 87.3%;
    font-family: arial, sans-serif;
    border-collapse: collapse;
    float: left;
}
.wrapper_table_2 table th {
    background: #e8e8e8;
    border: 1px solid #08242c;
    font-size: 14px;
    padding: 10px 22.2px;
    text-align: center;
    font-weight: bold;
}
	.wrapper_table_2 table td{
	    border: 1px solid #08242c;
	    font-size: 14px;
	    text-align: center; 
	    padding: 5px 10px;       
	}
	.table_tree table {
	    margin-top: 25px;	
		  font-family: arial, sans-serif;
		  border-collapse: collapse;
		  width: 50%;
		  float: left;
	}
	.table_tree table th{
	    background: #e8e8e8;
	    border: 1px solid #08242c;
	    font-size: 14px;
	    text-align: left;
	    padding: 6px 10px;
	}
	/*.table_tree table th:last-child{
		border-left: none;
	}*/
	.table_tree table td{
	    border: 1px solid #08242c;
	    font-size: 14px;
	    text-align: left;
	    padding: 5px 10px;      
	}
	.table_tree table td:first-child{
		background:#e8e8e8;
	}
</style>
</head>
<body>
	<script type="text/javascript">window.print();</script>
	<div class="table_wrapper">
		<table>
			<tr>
				 
				<th colspan="2"><b>Payment Details</b></th> 
			</tr>
		    <tr>
		    	<td><b>Payment ID</b></td>
		    	<td><?=sprintf('%05d',$payment_id)?></td>
		  	</tr>
			<tr>
		    	<td><b>Payment Date</b></td>
		    	<td><?php echo isset($payment_date) ? $payment_date:''; ?></td>
		  	</tr>
		  	<tr>
		    	<td><b>Employee Name</b></td>
		    	<td><?=$customerData['Name'];?></td>
		  	</tr>
			<tr>
				<td><b>Employee Email</b></td>
				<td><?=$customerData['email'];?></td>
			</tr>
		  	<tr>
		    	<td><b>Employee Phone</b></td>
		    	<td><?=$customerData['phone'];?></td>
		  	</tr>
 		 
			<tr>
				<td><b>Cheque No./Transaction ID</b></td>
				<td><?=$reference_no?></td>
			</tr>
			 
		</table>
		<table class="table_tow">
			 
			<tr>
				<td><img src="<?php echo $logo_img['value'] ?>" width="150px;"></td>
			</tr>
		</table> 
	</div>
<?php
$fetch1 =mysqli_fetch_array($pickup_query);
if(!empty($fetch1['ledger_pickup']) ){  ?>
<div class="wrapper_table_2">
		<h3 style="clear: both;margin: 0;    padding-top: 21px;">Pickup Orders</h3>
		<table style=" width: 50%;margin-right: 10%;margin-bottom: 32px;    margin-top: 5px;">
			<tr>
				<th>S.No.</th>
				<th>Tracking No.</th>  
				<th>Consignee</h>
				<th>Phone</h>
				<th>Origin</th> 
				<th>Destination</th> 
				<th>Weight (kg)</th>
				<th>Collection Amount (<?php echo getConfig('currency'); ?>)</th>
				<th>Delivery Charges (<?php echo getConfig('currency'); ?>)</th> 
				<th>Status</th>
			</tr>
			<?php 
				$col_t = 0;
				$del_t = 0;
				$sr = 1;
					 
					$ordernumber = explode(",",$fetch1['ledger_pickup']);
					 
					for ($i=0; $i < count($ordernumber); $i++) 
					{ 
						$oderData =	getLedgerOrder($ordernumber[$i]);
						if(!empty($oderData))
						{
							?>
							<tr>
								<td><?=$sr;?></td>
								<td><?=$oderData['track_no'];?></td> 
								<td><?=$oderData['rname'];?>   </td>
								<td><?=$oderData['rphone'];?></td>
								<td><?=$oderData['origin'];?></td>
								<td><?=$oderData['destination'];?></td>
								
								<td><?=$oderData['weight'];?></td>
								<td><?=$oderData['collection_amount'];?></td>
								<td><?=$oderData['price'];?></td> 
								<td><?=ucfirst($oderData['status']);?></td> 
							</tr>
							<?php 
							$sr++;
							
						} 
					}
					
				
			?>
			
		</table>
	</div>
<?php } 
 ?>
	<?php
	$fetch1 = mysqli_fetch_array($delivery_query);
	if(!empty($fetch1['ledger_delivered']) ){ ?>
		<div class="wrapper_table_2">
		<h3 style="clear: both;margin: 0;    padding-top: 21px;">Delivery Orders</h3>
		<table style=" width: 50%;margin-right: 10%;margin-bottom: 32px;    margin-top: 5px;">
			<tr>
				<th>S.No.</th>
				<th>Tracking No.</th>  
				<th>Consignee</h>
				<th>Phone</h>
				<th>Origin</th> 
				<th>Destination</th> 
				<th>Weight (kg)</th>
				<th>Collection Amount (PKR)</th>
				<th>Delivery Charges (PKR)</th> 
				<th>Status</th>
			</tr>
			<?php 
				$col_t = 0;
				$del_t = 0;
				$sr = 1;
				
					 
					$ordernumber = explode(",",$fetch1['ledger_delivered']);
					 
					for ($i=0; $i < count($ordernumber); $i++) 
					{ 
						$oderData =	getLedgerOrder($ordernumber[$i]);
						if(!empty($oderData))
						{
							?>
							<tr>
								<td><?=$sr;?></td>
								<td><?=$oderData['track_no'];?></td> 
								<td><?=$oderData['rname'];?>   </td>
								<td><?=$oderData['rphone'];?></td>
								<td><?=$oderData['origin'];?></td>
								<td><?=$oderData['destination'];?></td>
								
								<td><?=$oderData['weight'];?></td>
								<td><?=$oderData['collection_amount'];?></td>
								<td><?=$oderData['price'];?></td> 
								<td><?=ucfirst($oderData['status']);?></td> 
							</tr>
							<?php 
							$sr++;
							
						} 
					}
					
				
			?>
			
		</table>
	</div>
	<?php } 
	?>
	<?php if($fetch2_c['ledger_flyers'] != ''): ?>
	<div class="wrapper_table_2">
		<h3  style="clear: both;margin: 0;">Flyer's Information</h3>
		<table    style="    margin-right: 10px;    margin-top: 5px;"  >
			<tr>
				<th>S.No.</th>
				<th>Invoice No</th>
				<th>Date</th>
				<th>Description</th>
				<th>Total Amount</th>
				 
			</tr>
			<?php 
				$total_f = 0;
				$sr = 1;
				while($fetch_flyer=mysqli_fetch_array($query2_f))
				{ 
					$flyer_ids =  explode(",",$fetch_flyer['ledger_flyers']);
		 			for ($j=0; $j < count($flyer_ids); $j++) 
					{ 
						$flyerData =	getFlyerOrder($flyer_ids[$j]);
						if(!empty($flyerData))
						{
							 $flayer_order_query = mysqli_query($con,"SELECT flayers.flayer_name,flayer_orders.qty FROM flayer_orders LEFT JOIN flayers ON(flayers.id = flayer_orders.flayer ) WHERE flayer_orders.flayer_order_index=".$flyerData['id']." ");
 							$total = getTotal($flyerData['id']);
					 		$total_f = $total +$total_f;
							?>
								<tr>
									<td><?=$sr;?></td>
									<td><?=sprintf("%04d",$flyerData['id']);?></td>
									<td><?=$flyerData['order_date'];?></td> 
									<td>
				                    <?php 
				                      while($rec2 = mysqli_fetch_array($flayer_order_query)){
				                        ?>
				                      <p><b>Flayer: </b><?php echo $rec2['flayer_name']; ?>, <b>Qty: </b><?php echo $rec2['qty']; ?></p>
				                      <?php } ?>
				                     
				                   </td>
									<td><?=$total;?></td> 
								</tr>
							<?php 
							$sr++;
					 	}
					}
				}
			?>
			<tr>
				<td colspan="3"></td>
				<td  style="    background: #c8c8c8;" ><b  >Total</b></td>
				<td  style="    background: #e8e8e8;" ><b  ><?=$total_f;?></b></td>
				 
			</tr>
		</table>
	</div>
<?php endif; ?>
	<div class="table_tree">
		<table style="margin-bottom: 15px;">
			<tr>
				<th style="width: 68%;" colspan="2">Charges Summary (PKR)</th>
			
			</tr>
			 
			<tr>
				<td><b>Prev Balance</b></td>
				<td><?=number_format($fetch2_c['prev_balance']);?></td>
			</tr>
			
			<tr>
				<td><b>Total Payable </b></td>
				<td style="font-weight: 600;font-size: 16px;"><?=number_format($total_payable, 0);?> </td>
			</tr>
			<tr>
				<td><b><?php echo getLange('addition'); ?> </b></td>
				<td style="font-weight: 600;font-size: 16px;"><?=number_format($total_addition, 0);?> </td>
			</tr>
			<tr>
				<td><b><?php echo getLange('deduction'); ?> </b></td>
				<td style="font-weight: 600;font-size: 16px;"><?=number_format($total_deduction, 0);?> </td>
			</tr>
			<tr>
				<td><b>Payment </b></td>
				<td style="font-weight: 600;font-size: 16px;"><?=number_format($fetch2_c['total_paid'], 0);?> </td>
			</tr>
			 <tr>
				<td><b>Balance </b></td>
				<td style="font-weight: 600;font-size: 16px;"><?=((float)$total_payable - (float)$fetch2_c['total_paid']);?> </td>
			</tr>
			 
		</table>
	</div>
	 
</body>
</html>
<?php
	}
	else{
		header("location:index.php");
	}
?>