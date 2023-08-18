<?php
	session_start();
	require 'includes/conn.php';
	function formate_value($value = null)
	{
		if($value!=null)
		{
			return number_format($value,2);
		}
		return 0;
	}
	$gst_query = mysqli_query($con,"SELECT value FROM config WHERE `name`='gst' ");
	$city = mysqli_query($con,"SELECT * FROM cities");
	$city_q = mysqli_query($con,"SELECT * FROM cities");
	$logo_img = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='logo' "));
  	$total_gst = mysqli_fetch_array($gst_query);
	if(isset($_SESSION) && isset($_GET['payment_id']))
	{
		$payment_id = $_GET['payment_id'];
		$query1 =mysqli_query($con,"SELECT non_customer_ledger_payments.*, customers.fname AS customer  FROM non_customer_ledger_payments LEFT JOIN customers ON (customers.id = non_customer_ledger_payments.customer_id) WHERE non_customer_ledger_payments.id = '".$payment_id."' order by id DESC");




		$query2_f =mysqli_query($con,"SELECT non_customer_ledger_payments.*, customers.fname AS customer  FROM non_customer_ledger_payments LEFT JOIN customers ON (customers.id = non_customer_ledger_payments.customer_id) WHERE non_customer_ledger_payments.id = '".$payment_id."' order by id DESC");
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
		$total_payable = isset($fetch2_c['total_payable']) ? $fetch2_c['total_payable']:0;
	 	$delivery_charges = $fetch2_c['delivery_charges'];
	  	$returned_amount = $fetch2_c['returned_amount'];
	  	$total_total_charges = $fetch2_c['total_total_charges'];
	  	$total_extra_charges = $fetch2_c['total_extra_charges'];
	  	$total_insuredpremium_charges = $fetch2_c['total_insuredpremium_charges'];
	  	$total_returned_fee = $fetch2_c['total_returned_fee'];
	   	$sell_flyers_amount = $fetch2_c['sell_flyers_amount'];
	   	$cash_handling = $fetch2_c['cash_handling'];
	   	$fuel_surcharge = isset($fetch2_c['fuel_surcharge']) ? $fetch2_c['fuel_surcharge']:0;
	   	$gst_amount = isset($fetch2_c['gst_amount']) ? $fetch2_c['gst_amount']:0;
	   	$total_net_amounts = isset($fetch2_c['net_amount']) ? $fetch2_c['net_amount']:0;
	   	$total_charges_amount = isset($fetch2_c['total_charges']) ? $fetch2_c['total_charges']:0;
	   	$total_prev_balance = isset($fetch2_c['prev_balance_history']) ? $fetch2_c['prev_balance_history']:0;
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
		function totaltraf($id)
		{
			$data = "";
			global $con;
			$sql= "SELECT * FROM order_charges where  order_id='".$id."' ";
			$query_order = mysqli_query($con,$sql);
			while($row_data =mysqli_fetch_array($query_order))
			{
				$data += $row_data['charges_amount'];
			}
			return $data;
		}
		$customerData = getCustomer($customer_is);

		function getServiceType($service_id = null)
		{
			$data = "";
			global $con;
			if($service_id!=null)
			{
				$sql= "SELECT * FROM services WHERE  id=$service_id";
				$query_order = mysqli_fetch_array(mysqli_query($con,$sql));
				$data = isset($query_order['service_type']) ? $query_order['service_type']:'';
			}
			return $data;
		}
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
    body {
        font-family: 'Roboto', sans-serif;
    }

    .table_wrapper,
    .wrapper_table_2,
    .table_tree {
        max-width: 80%;
        margin: 21px auto;
    }

    .table_wrapper table {
        border-collapse: collapse;
        width: 60%;
        float: left;
        margin-bottom: 15px;
    }

    h3,
    .table_wrapper table {
        font-family: arial, sans-serif;
    }

    .table_wrapper tr th:nth-child(1) {
        text-align: left;
        font-weight: 800;
        font-size: 14px;
        background: #e8e8e8;
    }

    .table_wrapper tr th:nth-child(2) {
        background: #ebebeb;
    }

    .table_wrapper th:nth-child(3) {
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

    .table_wrapper td:last-child {
        background: #fff;
    }

    .table_wrapper th {
        border: 1px solid #08242c;
        text-align: left;
        font-size: 12px;
        padding: 8px;
    }

    .table_wrapper .table_height {}

    .table_tow {
        width: 25.4% !important;
    }

    .table_tow th {
        background: #ebebeb;
        font-size: 16px;
        font-weight: 300;
        padding: 10px 80px;
        text-align: center;
        border-left: none;
    }

    .table_tow td {
        border-left: none;
        height: 187.5px;
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
        font-size: 12px;
        padding: 6px 3px;
        text-align: center;
        font-weight: bold;
        -webkit-print-color-adjust: exact !important;
    }

    .wrapper_table_2 table td {
        border: 1px solid #08242c;
        font-size: 12px;
        text-align: center;
        padding: 2px;
    }

    .table_tree table {
        margin-top: 25px;
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 50%;
        float: left;
    }

    .table_tree table th {
        background: #e8e8e8;
        border: 1px solid #08242c;
        font-size: 14px;
        text-align: left;
        padding: 6px 10px;
    }

    /*.table_tree table th:last-child{
		border-left: none;
	}*/
    .table_tree table td {
        border: 1px solid #08242c;
        font-size: 14px;
        text-align: left;
        padding: 5px 10px;
    }

    .table_tree table td:first-child {
        background: #e8e8e8;
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
        margin: 0 0 8px;
    }

    .border_bottom_new_fix {
        margin-top: 2px !important;
        padding: 5px 0px;
    }

    .header_logo {
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
        font-weight: 500;
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

    .fl {
        float: left
    }

    .fr {
        float: right
    }

    .cl {
        clear: both;
        font-size: 0;
        height: 0;
    }

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

    /*.left_invoice {
    float: left;
    width: 49%;
    margin-right: 21px;
}
.right_invoice {
    float: left;
    width: 49%;
}*/
    .booked_origin h4 {
        margin: 14px 0 4px;
        font-weight: 500;
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

    .booked_origin h4 {

        padding: 8px 10px;
    }

    .divider_table {
        float: left;
        width: 100%;
        margin: 0;
        padding: 0 6px;
    }

    .divider_table:last-child {
        margin-right: 0;
    }

    .wrapper_table_2 table td:first-child {
        height: 35px;
    }

    .wrapper_table_2 table.width_fix {
        /* width: 49%; */
        margin: 0 5px 15px;
    }

    /* .wrapper_table_2 table th:first-child {
    width: 128px;
    min-width: 100px;
}
.wrapper_table_2 table th:nth-child(2) {
    min-width: 72px;
    width: 75px;
} */
    .booked_origin {}

    @media print {
        table {
            page-break-after: always
        }

        .divider_table {
            display: flex;
        }

        @page {
            margin: 0;
        }

        .wrapper_table_2 table th {
            font-size: 11px !important;
        }

        .divider_table:last-child {
            margin-right: 10px;
        }

        .wrapper_table_2 table td:first-child {
            height: 25px !important;
        }

        .booked_origin h4 {

            display: block !important;
            min-width: 100% !important;
            width: 100%;

        }

        .wrapper_table_2 table td {
            font-size: 12px !important;

        }

        table.width_fix {
            /* width: 50% !important; */
        }

        /* .wrapper_table_2 table th:first-child {
    width: auto !important;
    min-width: auto !important;
}
.wrapper_table_2 table th:nth-child(2) {
    min-width: auto !important;
    width: auto !important;
} */
        .table_wrapper,
        .wrapper_table_2,
        .table_tree {
            max-width: unset !important;
            margin: 21px auto;
        }
    }
    </style>
</head>

<body>
    <?php $url = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>
    <?php if(!isset($_GET['print'])){ ?>

    <?php }else{ ?>
    <script type="text/javascript">
    window.print();
    </script>
    <?php } ?>
    <div class="wrapper_table_2">
        <!-- <a href="<?php echo $url ?>&print=1"  class="print_btn btn btn-info" target="_blank" >Print</a> -->
        <h3
            style="text-decoration: underline;clear: both;padding-top: 0;text-align: center;font-size: 31px;margin: 2px 0 5px;">
            Invoice</h3>
        <div class="header_logo">
            <img src="<?php echo $logo_img['value'] ?>">
        </div>
        <div class="border_bottom_new_fix"
            style="    margin: 0 0 9px;    padding: 0 0 0 9px;border: 1px solid #08242c;">
            <div class="main_row">
                <div class="col_6 bottom_border_info">
                    <ul>
                        <li><b><?php echo getLange('customerid') ?>:</b>
                            <span><?php echo isset($customerData['client_code']) ? $customerData['client_code']:'';?></span>
                        </li>
                        <li><b><?php echo getLange('customername'); ?>:</b>
                            <span><?php echo $customerData['bname'];?></span>
                        </li>
                        <li><b><?php echo getLange('invoiceno'); ?>.:</b> <span><?php echo $payment_id?></span></li>
                        <li><b><?php echo getLange('billingmonth'); ?>:</b> <span><?php echo date('Y-m-d'); ?></span>
                        </li>
                        <!-- <li><b>Booked Origin:</b> <span>KHI</span></li> -->
                        <li><b><?php echo getLange('ntnno') ?>:</b>
                            <span><?php echo isset($customerData['ntn_no']) ? $customerData['ntn_no']:'';?></span>
                        </li>
                        <li><b><?php echo getLange('stnno'); ?>:</b>
                            <span><?php echo isset($customerData['stn_no']) ? $customerData['stn_no']:'';?></span>
                        </li>
                    </ul>
                </div>
                <div class="col_6 invoice-left-right padd-top- p-padd imagge">

                </div>
            </div>
        </div>
        <div class="left_invoice clearfix">



            <?php
		      $net_count=array();
		      $fuel_count=array();
		      $gst_count=array();
		      $amount_count=array();

				$total_net_amount = 0;
				$total_charges = 0;
				$total_fuel_charges = 0;
				$total_gst = 0;
				$insured_premium=0;
				$tariff=0;
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
            <div class="divider_table clearfix">

                <table class="width_fix">
                    <thead>
                        <tr>
                            <th><?php echo getLange('cnno'); ?>.</th>
                            <th><?php echo getLange('orderdate'); ?>.</th>
                            <th><?php echo getLange('servicetype'); ?></th>
                            <th><?php echo getLange('destination'); ?></th>
                            <th><?php echo getLange('weight'); ?></th>
                            <th><?php echo getLange('piece') ?></th>
                            <th><?php echo getLange('totalcharges'); ?></th>
                            <th><?php echo getLange('fuelsurcharge'); ?></th>
                            <th><?php echo getLange('gst'); ?></th>
                            <th><?php echo getLange('netamount'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
						   	$net_amount = 0;
							$fuel_charges = 0;
							$gst = 0;
							$totalcharges = 0;
						   	$sqliO = mysqli_query($con,"SELECT * FROM orders where id IN (".$fetch1['ledger_orders'].")
						   		AND origin = '".$row_data['origin']."'   ");
						   	$sr=0;
                            $sr_no = 1;
                            $checkTable=0;

						   	while ($oderData=mysqli_fetch_assoc($sqliO)) {
						   		?>
                        <?php 
                                    // if (($sr_no % 2) != 0) {
                                    if (1==1) {
                                	$net_amount += isset($oderData['net_amount']) ? $oderData['net_amount']:0;
                                    $fuel_charges += isset($oderData['fuel_surcharge']) ? $oderData['fuel_surcharge']:0;
                                    $gst += isset($oderData['pft_amount']) ? $oderData['pft_amount']:0;
                                    $insured_premium += $oderData['insured_premium'];
                                    $totalcharges += isset($oderData['grand_total_charges']) ? $oderData['grand_total_charges']:0;
									$tariff += totaltraf($oderData['id']);?>
                        <tr>
                            <td><?=$oderData['track_no'];?></td>
                            <td>
                                <?php 
                                     		echo isset($oderData['order_date']) ? date('M d,Y',strtotime($oderData['order_date'])):'';
                                     	?>
                            </td>
                            <td><?php echo isset($oderData['order_type']) ? getServiceType($oderData['order_type']):''; ?>
                            </td>

                            <td><?=$oderData['destination'];?></td>
                            <td><?=$oderData['weight'];?></td>
                            <td><?=$oderData['quantity'];?></td>
                            <td><?php echo isset($oderData['grand_total_charges']) ? formate_value($oderData['grand_total_charges']):0;?>
                            </td>
                            <td><?php echo isset($oderData['fuel_surcharge']) ? formate_value($oderData['fuel_surcharge']):0;?>
                            </td>
                            <td><?php echo isset($oderData['pft_amount']) ? formate_value($oderData['pft_amount']):0;?>
                            </td>
                            <td><?php echo isset($oderData['net_amount']) ? formate_value($oderData['net_amount']):0;?>
                            </td>
                        </tr>
                        <?php }else{
                                $checkTable=1;
                               } ?>
                        <?php $sr_no++;
                           } ?>
                    </tbody>
                    <tfoot>
                        <tr>

                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td> <?php echo formate_value($totalcharges);?></td>
                            <td> <?php echo formate_value($fuel_charges);?></td>
                            <td> <?php echo formate_value($gst);?></td>
                            <td> <?php echo formate_value($net_amount);?></td>
                        </tr>
                    </tfoot>
                </table>


                <!-- Second Table start from here -->
                <?php if (isset($checkTable) && $checkTable==1): ?>
                <table class="width_fix">
                    <thead>
                        <tr>
                            <th><?php echo getLange('cnno'); ?>.</th>
                            <th><?php echo getLange('orderdate'); ?>.</th>
                            <th><?php echo getLange('servicetype'); ?></th>
                            <th><?php echo getLange('destination'); ?></th>
                            <th><?php echo getLange('weight'); ?></th>
                            <th><?php echo getLange('piece') ?></th>
                            <th><?php echo getLange('totalcharges'); ?></th>
                            <th><?php echo getLange('fuelsurcharge'); ?></th>
                            <th><?php echo getLange('gst'); ?></th>
                            <th><?php echo getLange('netamount'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                                $net_amount = 0;
                                $fuel_charges = 0;
                                $gst = 0;
                                $totalcharges = 0;
                                $sqliO = mysqli_query($con,"SELECT * FROM orders where id IN (".$fetch1['ledger_orders'].")
                                    AND origin = '".$row_data['origin']."'   ");
                                $sr=0;
                                $sr_no = 1;
                                while ($oderData=mysqli_fetch_assoc($sqliO)) {?>

                        <?php if (($sr_no % 2) == 0) {
                                    $net_amount += $oderData['net_amount'];
                                    $fuel_charges += isset($oderData['fuel_surcharge']) ? $oderData['fuel_surcharge']:0;
                                    $gst += isset($oderData['pft_amount']) ? $oderData['pft_amount']:0;
                                    $insured_premium += $oderData['insured_premium'];
                                    $totalcharges += isset($oderData['grand_total_charges']) ? $oderData['grand_total_charges']:0;
                                    $tariff += totaltraf($oderData['id']); ?>
                        <tr>
                            <td><?php echo $oderData['track_no'];?></td>
                            <td>
                                <?php 
	                                     		echo isset($oderData['order_date']) ? date('M d,Y',strtotime($oderData['order_date'])):'';
	                                     	?>
                            </td>
                            <td><?php echo isset($oderData['order_type']) ? getServiceType($oderData['order_type']):''; ?>
                            </td>
                            <td><?php echo $oderData['destination'];?></td>
                            <td><?php echo $oderData['weight'];?></td>
                            <td><?php echo $oderData['quantity'];?></td>
                            <td><?php echo isset($oderData['grand_total_charges']) ? formate_value($oderData['grand_total_charges']):0;?>
                            </td>
                            <td><?php echo isset($oderData['fuel_surcharge']) ? formate_value($oderData['fuel_surcharge']):0;?>
                            </td>
                            <td><?php echo isset($oderData['pft_amount']) ? formate_value($oderData['pft_amount']):0;?>
                            </td>
                            <td><?php echo isset($oderData['net_amount']) ? formate_value($oderData['net_amount']):0;?>
                            </td>
                        </tr>
                        <?php } ?>

                        <?php $sr_no++; } ?>
                    </tbody>
                    <tfoot>
                        <tr>

                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td> <?php echo formate_value($totalcharges);?></td>
                            <td> <?php echo formate_value($fuel_charges);?></td>
                            <td> <?php echo formate_value($gst);?></td>
                            <td> <?php echo formate_value($net_amount);?></td>
                        </tr>
                    </tfoot>
                </table>
                <?php endif ?>
                <!-- Second table ends here -->
            </div>
            <?php } } ?>
            <?php 
						$net_countq='';
						$fuel_countq='';
						$gst_countq='';
						$amount_countq='';
						foreach ($net_count as $key) {
						$net_countq.=$key.'+';}
						foreach ($fuel_count as $key) {
						$fuel_countq.=$key.'+';}
						foreach ($gst_count as $key) {
						$gst_countq.=$key.'+';}
						foreach ($amount_count as $key) {
						$amount_countq.=$key.'+';}
						$totalnetamount=$insured_premium+$extra_charges+$tariff;
					?>

        </div>
        <br>
        <div class="gross_amount">
            <b><?php echo getLange('balance'); ?>=
                <?php echo getConfig('currency').' '. formate_value($total_prev_balance); ?></b>
        </div>
        <div class="net_amount" style="text-align: center;">
        </div>
        <div class="net_amount">
            <p><?php echo getLange('totalcharges'); ?> =
                <?php echo getConfig('currency').' '. formate_value($total_charges_amount); ?> </p>
            <p><?php echo getLange('fuelsurcharge'); ?>=
                <?php echo getConfig('currency').' '. formate_value($fuel_surcharge); ?></p>
            <p><?php echo getLange('gst'); ?>= <?php echo getConfig('currency').' '. formate_value($gst_amount); ?></p>
            <p><?php echo getLange('codamount'); ?>=
                <?php echo getConfig('currency').' '. formate_value($cod_amount); ?></p>
        </div>
        <div class="gross_amount">
            <b><?php echo getLange('grossamount'); ?>=
                <?php echo getConfig('currency').' '. formate_value($total_payable); ?></b>
        </div>
    </div>
</body>

</html>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    window.print();
}, false);
</script>
<?php
	}
	else{
		header("location:index.php");
	}
?>