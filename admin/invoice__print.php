<?php
	session_start();
	require 'includes/conn.php';
	$gst_query = mysqli_query($con,"SELECT value FROM config WHERE `name`='gst' ");
	$city = mysqli_query($con,"SELECT * FROM cities");
	$city_q = mysqli_query($con,"SELECT * FROM cities");
	$logo_img = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='logo' "));
  $total_gst = mysqli_fetch_array($gst_query);
	if(isset($_SESSION) && isset($_GET['payment_id']))
	{
		$payment_id = $_GET['payment_id'];
		$query1 =mysqli_query($con,"SELECT non_customer_ledger_payments.*, customers.fname as customer  FROM non_customer_ledger_payments LEFT JOIN customers ON (customers.id = non_customer_ledger_payments.customer_id) where non_customer_ledger_payments.id = '".$payment_id."' order by id DESC");




		$query2_f =mysqli_query($con,"SELECT non_customer_ledger_payments.*, customers.fname as customer  FROM non_customer_ledger_payments LEFT JOIN customers ON (customers.id = non_customer_ledger_payments.customer_id) where non_customer_ledger_payments.id = '".$payment_id."' order by id DESC");
		//geting customer data
		$customer_is     = 0;
		$reference_no    = 0;
		$cod_amount      = 0;
		$total_payable   = 0;
		$gst_amount      = 0;
		$delivery_charges= 0;
		$returned_amount = 0;
		$total_total_charges = 0;
		$total_insuredpremium_charges = 0;
		$total_extra_charges = 0;
		$total_returned_fee = 0;
		$sell_flyers_amount = 0;
		$query2_c = mysqli_query($con,"SELECT * FROM non_customer_ledger_payments  where id = '".$payment_id."' order by id DESC");
		$fetch2_c=mysqli_fetch_array($query2_c);

			$customer_is   = $fetch2_c['customer_id'];
			$reference_no  = $fetch2_c['reference_no'];
			$cod_amount    = $fetch2_c['cod_amount'];
			$total_payable = $fetch2_c['total_payable'];
			$gst_amount    = $fetch2_c['gst_amount'];
		 $delivery_charges = $fetch2_c['delivery_charges'];
		  $returned_amount = $fetch2_c['returned_amount'];
		  $total_total_charges = $fetch2_c['total_total_charges'];
		  $total_extra_charges = $fetch2_c['total_extra_charges'];
		  $total_insuredpremium_charges = $fetch2_c['total_insuredpremium_charges'];
		  $total_returned_fee = $fetch2_c['total_returned_fee'];
	   $sell_flyers_amount = $fetch2_c['sell_flyers_amount'];
	   $cash_handling = $fetch2_c['cash_handling'];

		function getLedgerOrder($ledger_id)
		{
			$data = "";
			global $con;
			$sql= "SELECT * FROM orders where id  = '".$ledger_id."'";

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
			$sql= "SELECT * FROM customers   where id  = '".$customer_id."'  ";
			$query_order = mysqli_query($con,$sql);
			while($row_data =mysqli_fetch_array($query_order))
			{
				$data = $row_data;
			}
			return $data;
		}
		function fuelcharges($id)
		{
			$data = "";
			global $con;
			$sql= "SELECT * FROM order_charges where charges_id  = '10' and order_id='".$id."' ";
			$query_order = mysqli_query($con,$sql);
			while($row_data =mysqli_fetch_array($query_order))
			{
				$data = $row_data['charges_amount'];
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
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
<style>
	body{
		font-family: 'Roboto', sans-serif;
	}
	.table_wrapper , .wrapper_table_2 , .table_tree{
		max-width: 80%;
		margin: 21px auto;
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
    height:187.5px;
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
    background: #d4d3d3;
    border: 1px solid #08242c;
    font-size: 13px;
    padding: 6px 5px;
    text-align: center;
    font-weight: bold;
    -webkit-print-color-adjust: exact !important;
}
	.wrapper_table_2 table td {
    border: 1px solid #08242c;
    font-size: 14px;
    text-align: center;
    padding: 5px;
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
.print_btn {
    display: inline-block;
    padding: 6px 12px;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: normal;
    line-height: 1.42857143;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-image: none;
    border: 1px solid transparent;
    border-radius: 4px;
    color: #fff;
    background-color: #324584 !important;
    border-color: #324584;
    text-decoration: none;
}
.header_logo img {
    width: 182px;
}
.border_bottom_new_fix {
    margin-top: 2px !important;
    padding: 5px 0px;
}
.header_logo{
	padding: 0;
}
.main_row {
    display: flex;
    padding: 0;
}
.col_6 {
    width: 50%;
}
.bottom_border_info {
    display: flex;
}
.bottom_border_info ul li {
    font-size: 14px;
    color: #000;
    list-style: none;
    margin-bottom: 6px;
}
.bottom_border_info ul li b {
    font-weight: 600;
    width: 115px;
    display: inline-block;
    text-align: right;
    margin-right: 5px;
}
.bottom_border_info ul {
    padding: 0;
}
.bottom_border_info ul li span {
    /* font-weight: bold; */
    /* width: 160px; */
    /* display: inline-block; */
}
.fl{ float:left}
.fr{ float:right}
.cl{ clear:both; font-size:0; height:0; }
.clearfix:after {
	clear: both;
	content: ' ';
	display: block;
	font-size: 0;
	line-height: 0;
	visibility: hidden;
	width: 0;
	height: 0;
}
.left_invoice {
    float: left;
    width: 49%;
    margin-right: 21px;
}
.right_invoice {
    float: left;
    width: 49%;
}
.booked_origin h4 {
    margin: 14px 0 4px;
}
.net_amount {
    padding: 6px 0 0;
    border-bottom: 1px solid #000;
    margin: 0 0 6px;
}
.net_amount p {
    margin: 0 0 6px;
    font-size: 14px;
    color: #000;
    font-weight: 500;
}


@media print
{
/*table {page-break-after:always}
}*/
</style>
</head>
<body  >
	<?php $url = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>
	<?php if(!isset($_GET['print'])){ ?>

<?php }else{ ?>
  <script type="text/javascript">window.print();</script>
  <?php } ?>

	<div class="wrapper_table_2">
		<!-- <a href="<?php echo $url ?>&print=1"  class="print_btn btn btn-info" target="_blank" >Print</a> -->
		<h3 style="text-decoration: underline;clear: both;padding-top: 0;text-align: center;font-size: 31px;margin: 2px 0 5px;">Invoice</h3>
		<div class="header_logo">
			<img src="<?php echo $logo_img['value'] ?>">
		</div>
		<div class="border_bottom_new_fix" style="    margin: 0 0 9px;    padding: 0 0 0 9px;border: 1px solid #08242c;">
		   <div class="main_row">
		      <div class="col_6 bottom_border_info">
		         <ul>
		            <li><b>Customer Id:</b> <span><?=$customerData['id'];?></span></li>
		            <li><b>Customer Name:</b> <span><?=$customerData['fname'];?></span></li>
		            <li><b>Invoice No.:</b> <span><?=$payment_id?></span></li>
		            <li><b>Billing Month:</b> <span><?php echo date('Y-m-d'); ?></span></li>
		            <!-- <li><b>Booked Origin:</b> <span>KHI</span></li> -->
		            <li ><b>NTN No:</b> <span></span></li>
		            <li><b>STN No:</b> <span></span></li>
		         </ul>
		      </div>
		      <div class="col_6 invoice-left-right padd-top- p-padd imagge">

		      </div>
		   </div>
		</div>
		<div class="clearfix">
			<div class="left_invoice">



				      <?php
				$net_amount = 0;
				$total_net_amount = 0;
				$fuel_charges = 0;
				$total_fuel_charges = 0;
				$gst = 0;
				$total_gst = 0;
				$amount = 0;
				$total_amount = 0;
				while($fetch1=mysqli_fetch_array($query1))
				{
					$ordernumber = explode(",",$fetch1['ledger_orders']);
					$sql= "SELECT price, weight, status, destination,origin FROM orders where id IN (".$fetch1['ledger_orders'].") GROUP BY origin";

					$query_order = mysqli_query($con,$sql);
					while($row_data =mysqli_fetch_assoc($query_order))
					{
						 ?>

						 <div class="booked_origin">
							<h4>Booked Origin <?php echo $row_data['origin']; ?></h4>
							</div>
						 <table style=" width: 100%;margin-bottom: 10px;    margin-top: 5px;">
							<thead>
								<tr>
						         <th>Cn No.</th>
						         <th>Desti</th>
						         <th>Weight</th>
						         <th>Piece</th>
						         <th>Net Amount</th>
						         <th>Fuel Charge</th>
						         <th>G.S.T</th>
						         <th>Amount</th>
						      </tr>
							</thead>
						   <tbody>
						   	<?php
						   	$sqliO = mysqli_query($con,"SELECT * FROM orders where id IN (".$fetch1['ledger_orders'].")
						   		AND origin = '".$row_data['origin']."' ");
						   	$sr=0;
						   	while ($oderData=mysqli_fetch_assoc($sqliO)) { 
						   		$net_amount += $oderData['net_amount'];
								$fuel_charges += fuelcharges($oderData['id']);;
								$gst += $oderData['pft_amount'];
								$amount += $oderData['price'];
						   		?>
						   		<tr>
	                             <td><?php echo ++$sr; ?></td>
	                             
	                             <td><?=$oderData['destination'];?></td>
	                             <td><?=$oderData['weight'];?></td>
	                             <td><?=$oderData['quantity'];?></td>
	                             <td><?=$oderData['net_amount'];?></td>
	                             <td><?php echo fuelcharges($oderData['id']); ?></td>
	                             <td><?=$oderData['pft_amount'];?></td>
	                             <td><?=$oderData['price'];?></td>
	                          </tr>
						   	<?php } ?>
						   	</tbody>
						   	<tfoot>
						   		<tr>
						   			<?php 
									$total_net_amount +=$net_amount;
									$total_fuel_charges += $fuel_charges;
									$total_gst += $gst;
									$total_amount += $amount; ?>
						   			<td></td>
						   			<td></td>
						   			<td></td>
						   			<td></td>
						   			<td> <?php echo $net_amount;?></td>
						   			<td> <?php echo $fuel_charges;?></td>
						   			<td> <?php echo $gst;?></td>
						   			<td> <?php echo $amount;?></td>
						   		</tr>
						   	</tfoot>
						</table>
						 <?php

					}


			}
			?>

			</div>
			
			</div>
		
		<div class="net_amount" style="    text-align: center;">
			<p>Net Amount= <!-- (A1+A2+A3+A4+A5+A6) --><?php echo getConfig('currency').' '.$total_net_amount ?>/-</p>
			<p>Fuel Surcharge= <!-- (b1+b2+b3+b4+b5+b6)= --> <?php echo getConfig('currency').' '.$total_fuel_charges ?>/-</p>
			<p>G.S.T= <!-- (C1+C2+C3+C4+C5+C6)= --> <?php echo getConfig('currency').' '.$total_gst ?>/-</p>
			<p>Gross Amount= <!-- (D1+D2+D3+D4+D5+D6)= --> <?php echo getConfig('currency').' '.$total_amount ?>/-</p>
		</div>
		<div class="net_amount">
			<p>Net Amount = Tariff +Special Service + Service + Extra Charges + Insurance Premium</p>
			<p>Fuel Charge</p>
			<p>G.S.T</p>
		</div>
		<div class="gross_amount">
			<b>Gross Amount</b>
		</div>
	</div>
	<?php if($fetch2_c['ledger_flyers'] != ''): ?>

<?php endif; ?>


</body>
</html>
<?php
	}
	else{
		header("location:index.php");
	}
?>
