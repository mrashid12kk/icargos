<?php
	session_start();
	require 'includes/conn.php';
	$gst_query = mysqli_query($con,"SELECT value FROM config WHERE `name`='gst' ");
	$logo_img = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='logo' "));
  	$total_gst = mysqli_fetch_array($gst_query);
    $customer_other_charges   = getconfig('customer_other_charges');
    $customer_extra_charges   = getconfig('customer_extra_charges');
    $customer_insured_premium = getconfig('customer_insured_premium');
    $admin_other_charges   = getconfig('admin_other_charges');
    $admin_extra_charges   = getconfig('admin_extra_charges');
    $admin_insured_premium = getconfig('admin_insured_premium');
	if(isset($_GET['order_id']))
	{
		$order_id = $_GET['order_id'];

		function getOrderDetail($order_id)
		{
			$data = "";
			global $con;
			$sql= "SELECT * FROM orders   WHERE id  = '".$order_id."'  ";
			$query_order = mysqli_query($con,$sql);
			$data = mysqli_fetch_array($query_order);
			return $data;
		}
		$order_detail = getOrderDetail($order_id);

		function getOrderCharges($order_id)
		{
			$data = "";
			global $con;
			$sql= "SELECT * FROM order_charges WHERE order_id  = '".$order_id."'  ";
			$query_order = mysqli_query($con,$sql);
			$data=[];
			if(isset($query_order) && !empty($query_order)){
	            while($row = mysqli_fetch_array($query_order)){
	            	if(isset($row['charges_amount']) && $row['charges_amount'] > 0){
	            		$data[$row['charges_id']] = $row;
	            	}
	            }
	            	unset($data[count($data) - 1]);
	        }
			return $data;
		}
		$order_charges_detail = getOrderCharges($order_id);
		// echo '<pre>',print_r($order_charges_detail),'</pre>';exit();

		function getinsurance_type($id)
		{
			$data = "";
			global $con;
			$sql= "SELECT * FROM insurance_type WHERE id  = '".$id."'  ";
			$query_order = mysqli_query($con,$sql);
			$data = mysqli_fetch_array($query_order);
			return $data;
		}
		$insuranceType = '';
		$is_fragile = isset($order_detail['is_fragile']) ? $order_detail['is_fragile']:'';
		$insuranceType = getinsurance_type($is_fragile);

		function getCustomer($customer_id)
		{
			$cust_detail = "";
			global $con;
			$sql= "SELECT * FROM customers   WHERE id  = '".$customer_id."'  ";
			$query_order_cus = mysqli_query($con,$sql);
			$cust_detail = mysqli_fetch_array($query_order_cus);
			return $cust_detail;
		}
		function servicetype($id)
		{
			$cust_detail = "";
			global $con;
			$sql= "SELECT * FROM services WHERE id  = '".$id."'  ";
			$query_order_cus = mysqli_query($con,$sql);
			$cust_detail = mysqli_fetch_assoc($query_order_cus);
			return $cust_detail['service_type'];
		}
		$customer_id = isset($order_detail['customer_id']) ? $order_detail['customer_id']:'';
		$customerData = getCustomer($customer_id);





		$customer_origin_zone_q = mysqli_query($con," SELECT GROUP_CONCAT(DISTINCT zone_id SEPARATOR ',') as zone_ids FROM customer_pricing WHERE customer_id='".$customer_id."'  ");
		$service_detail = [];
	   if(mysqli_num_rows($customer_origin_zone_q) >0)
	   {
		   	$origin_zone_res = mysqli_fetch_array($customer_origin_zone_q);
		   	$zone_ids = $origin_zone_res['zone_ids'];
		   	if ($_GET['branch_id']=='1') {
		   		$origin_q      = mysqli_query($con," SELECT DISTINCT origin FROM zone_cities WHERE zone IN(".$zone_ids.")   ");
		   	}else{
		    		$origin_q      = mysqli_query($con," SELECT DISTINCT origin FROM zone_cities WHERE zone IN(".$zone_ids.") AND origin IN (".$city_name_trim.")  ");
		   	}
		   	$destination_q = mysqli_query($con," SELECT DISTINCT destination FROM zone_cities WHERE zone IN(".$zone_ids.") ");

		   	//service types queries
		   	$service_type_q = mysqli_query($con," SELECT GROUP_CONCAT(DISTINCT service_type SEPARATOR ',') as service_types FROM zone WHERE id IN (".$zone_ids.") ");
		   	if(mysqli_num_rows($service_type_q) >0)
		   	{
		   		$service_type_id_res = mysqli_fetch_array($service_type_q);
		   		$service_types 	     = $service_type_id_res['service_types'];
		   		$get_service_types   = mysqli_query($con," SELECT DISTINCT id,service_type FROM services WHERE id IN(".$service_types.") ");
		   	}

   			while($service_detail[]= mysqli_fetch_array($get_service_types)){}
   			unset($service_detail[count($service_detail) - 1]);
	    }
	    $other_charges = mysqli_query($con,"SELECT * FROM charges");
	    $special_service = [];
	    if(isset($other_charges) && !empty($other_charges)){
            while($special_service[] = mysqli_fetch_array($other_charges)){}
            	unset($special_service[count($special_service) - 1]);
        }

		// echo '<pre>',print_r($order_detail),'</pre>';exit();
		// echo '<pre>',print_r($customerData),'</pre>';exit();
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



.boking_invoice {
    max-width: 1032px;
    margin: 33px auto 0;
}
.logo_box {
    float: left;
    padding: 11px 0 0;
    width: 24%;
}
.logo_box img {
    width: 96%;
}
.info_shiper_box {
    float: left;
    width: 73%;
    border: 2px solid #899194;
}
.shipper_no {
    float: left;
    width: 38%;
    border-right: 2px solid #899194;
    text-align: center;    padding: 5px 0;
}
.shipper_no p {
    margin: 0;
    border-bottom: 2px solid #899194;
    padding: 8px 14px;
    font-size: 14px;
}
.origin_box {
    float: left;
    width: 30%;
    text-align: left;
    padding: 3px 0 0;
}
.origin_box p {
    margin: 0;
    padding: 5px 11px 0;
    font-size: 14px;
}
.empty p{
	margin: 0 0 5px;
}
.info_box {
    float: right;
    width: 28%;
    border-left: 2px solid #899194;
    height: 64px;
    padding: 8px 0 4px 11px;
    font-size: 14px;
}
.left_boxes {
    float: left;
    width: 75%;
    margin-right: 7px;
}
.from_shiper {
    border: 2px solid #899194;
    border-radius: 13px;
    float: left;
    width: 47%;
    margin: 13px 0 0 11px;
}
.from_ship h3 {
    margin: 0;
    background: #286fad;
    -webkit-print-color-adjust: exact !important;
    color: #fff;
    padding: 7px 12px;
    border-radius: 10px 10px 0 0;
    font-size: 13px;
}
.from_ship p {
    margin: 0;
    padding: 4px 14px 62px;
    font-size: 14px;
    /* text-align: right; */
    height: 62px;
    line-height: 1.5;
}
.cell_phone {
    border-top: 2px solid #899194;
}
.cell_phone ul {
    padding: 0;
    list-style: none;
    margin: 0;
}
.cell_phone ul li {
    font-size: 9px;
    display: inline-block;
    width: 46%;
    padding: 5px 2px;
    border-right: 2px solid #899194;
    text-align: center;
}
.cell_phone ul li:last-child{
	border-right:none;
}
.left_info_para {
    border: 2px solid #899194;
    border-radius: 0;
    float: left;
    width: 47%;
    margin: 13px 0 0 11px;
}
.left_info_para p {
    margin: 0;
    font-size: 12px;
    padding: 4px 11px 5px;
    line-height: 1.7;
    font-weight: 500;
    height: 76px;
    overflow: hidden;
}
.left_info_para b {
    font-size: 13px;
    padding: 9px 11px 7px;
    display: inline-block;
}
.left_info_para b span {
    display: inline-block;
    background: #899194;
    -webkit-print-color-adjust: exact !important;
    height: 2px;
    width: 142px;
}
.table_pickedup {
    border: none !important;
}
.left_info_para {
    border: 2px solid #899194;
    border-radius: 0;
    float: left;
    width: 47%;
    margin: 13px 0 0 11px;
}
.table_pickedup tr th {
    background: #286fad;
    -webkit-print-color-adjust: exact !important;
    color: #fff;
}
.table_pickedup tr th, .table_pickedup tr td {
    font-size: 12px;
    font-weight: 400;
    padding: 4px 7px;
    border: 2px solid #899194;
}
.table_pickedup table {
	    width: 100%;
    font-family: arial, sans-serif;
    border-collapse: collapse;
       float: left;
    margin-bottom: 4px;
}
.table_pickedup tr th input ,.table_pickedup tr td input {
    background: #fff;
    -webkit-print-color-adjust: exact !important;
    margin: -3px 0 0 6px;
    vertical-align: middle;
    border: none;
}
.gst_no{
	width: 24%;
    float: left;
}
.gst_no tr td {
    padding: 4px 7px 3px;
}
.table_pickedup tr td input{
	margin-left: 0;
}
.left_boxes,.gst_no{
	margin-bottom: 27px;
}

@media print{
.logo_box img {
    width: 129px;
}
.cell_phone ul li {
    width: 46%;
    font-size: 9px;
}
.cell_phone {
    border-top: 1px solid #899194;
}
.cell_phone ul li {
    border-right: 1px solid #899194;
}
.from_shiper {
    width: 47%;
}
.left_info_para {
    width: 47%;
}
.left_info_para p {
    font-size: 11px;
    padding: 4px 8px 5px;
}
.table_pickedup tr td input {
    width: 10px;
}
.gst_no tr td {
    padding: 4px 4px 3px;
}
.table_pickedup tr th, .table_pickedup tr td {
    font-size: 11px;
    border: 1px solid #899194;
    padding: 4px 7px;
}
.table_pickedup tr th, .table_pickedup tr td {
    font-size: 11px;
    padding: 4px 5px;
    border: 1px solid #899194;
}
.logo_box img {
    width: 88%;
}
.left_info_para p {
    height: 55px;
}
}


@media(max-width: 767px){
	.container{
		width: auto;
	}
	.left_boxes {
	    width: 70%;
	}
	.info_shiper_box {
	    width: 73%;
	}
	.shipper_no p {
	    padding: 8px 5px;
	    font-size: 9px;
	}
	.origin_box {
	    width: 30%;
	}
	.origin_box p {
	    padding: 2px 14px;
	    font-size: 9px;
	}

	.info_box {
	    width: 22%;
	    border-left: 2px solid #899194;
	    height: 55px;
	    padding: 8px 0 4px 11px;
	    font-size: 9px;
	}
	.from_ship h3 {
	    font-size: 10px;
	}
	.from_shiper {
	    width: 46%;
	}
	.from_ship p {
	    margin: 0;
	    padding: 4px 14px 62px;
	    font-size: 10px;
	    height: 13px;
	}
	.cell_phone ul li {
    	font-size: 7px;
	    display: inline-block;
	    width: 46%;
	    padding: 5px 2px;
	    vertical-align: middle;
	}
	.left_info_para p {
	    font-size: 8px;
	}
	.left_info_para b {
	    font-size: 7px;
	}
	.left_info_para b span {
	    width: 105px;
	}
	.gst_no {
	    width: 28%;
	}
	.table_pickedup tr th, .table_pickedup tr td {
	    font-size: 8px;
	    padding: 4px 7px;
	}
	.table_pickedup tr td b{
		font-size: 12px !important;
	}
}





</style>
</head>
<body  >
	<?php $url = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>
	<?php if(!isset($_GET['print'])){ ?>
	<a href="<?php echo $url ?>&print=1"  class="print_btn"  >Print</a>
<?php }else{ ?>
  <script type="text/javascript">window.print();</script>
  <?php }
if(isset($_GET['order_id']) && !empty($_GET['order_id'])){

    if (!$_GET['booking']) {
        $invoice_ids = explode(',',$_GET['order_id']);
    }else{
        $invoice_ids= array();

        $invoice = $_GET['order_id'];

        $count = getConfig('print');

        for ($i=0; $i < $count ; $i++)
        {
            array_push($invoice_ids, $invoice);
        }
    }



    foreach($invoice_ids as $key => $id_invoice)
    {
     $order_detail = getOrderDetail($id_invoice);
		$customer_id = isset($order_detail['customer_id']) ? $order_detail['customer_id']:'';
		$customerData = getCustomer($customer_id);

  ?>

	<div class="boking_invoice">
		<div class="left_boxes">
			<div class="clearfix">
				<div class="logo_box">
					<img src="admin/<?php echo $logo_img['value'] ?>">
				</div>
				<div class="info_shiper_box">
					<div class="shipper_no">
						 <?php
				        if(isset($order_detail['barcode_image']))
				        {
				            echo '<img  src="'.$order_detail['barcode_image'].'" style="width: 124px;" />';
				            echo '<h2 style="text-align: center; font-size:13px;margin:0;">'.$order_detail['barcode'].'</h2>';
				        }
				      ?>
					</div>
					<div class="origin_box">
						<p><?php echo getLange('origin'); ?></p>
						<p><?php echo isset($order_detail['origin']) ? $order_detail['origin']:''; ?></p>
					</div>
					<div class="info_box empty">
						<p><?php echo getLange('servicetype'); ?></p>
						<p><?php echo isset($order_detail['order_type']) ? servicetype($order_detail['order_type']):''; ?></p>
					</div>
				</div>
			</div>
			<div class="clearfix">
				<div class="from_shiper">
					<div class="from_ship">
						<h3><?php echo getLange('from').'('.getLange('shipper').')'; ?></h3>
						<p>
							<?php echo getLange('name'); ?>: <?php echo isset($customerData['bname'])? $customerData['bname'] : $order_detail['sname']; ?>
							<br>
							<!-- Email: <?php echo isset($order_detail['semail']) ? $order_detail['semail']:''; ?>
							<br> -->
							<?php echo getLange('address'); ?>: <?php echo isset($order_detail['sender_address']) ? $order_detail['sender_address']:''; ?>

						</p>
					</div>
					<div class="cell_phone">
						<ul>
							<li><?php echo getLange('phoneno') ?> # <?php echo isset($order_detail['sphone']) ? $order_detail['sphone']:''; ?></li>
							<!-- <li>Telephone # <?php echo isset($order_detail['sphone']) ? $order_detail['sphone']:''; ?></li> -->
						</ul>
					</div>
				</div>
				<div class="from_shiper">
					<div class="from_ship">
						<h3><?php echo getLange('to').'('.getLange('consignee').')'; ?></h3>
						<p>
							<?php echo getLange('name'); ?>: <?php echo isset($order_detail['rname']) ? $order_detail['rname']:''; ?>
							<br>
							<!-- Email: <?php echo isset($order_detail['remail']) ? $order_detail['remail']:''; ?>
							<br> -->
							<?php echo getLange('address'); ?>: <?php echo isset($order_detail['receiver_address']) ? $order_detail['receiver_address']:''; ?>

						</p>
					</div>
					<div class="cell_phone">
						<ul>
							<li><?php echo getLange('phoneno'); ?> # <?php echo isset($order_detail['rphone']) ? $order_detail['rphone']:''; ?></li>
							<!-- <li>Telephone # <?php echo isset($order_detail['rphone']) ? $order_detail['rphone']:''; ?></li> -->
						</ul>
					</div>
				</div>
			</div>

			<div class="clearfix">
				<div class="left_info_para">
					<p style="border-bottom: 2px solid #899194;"><?php echo getConfig('first_new_footer'); ?></p>
					<p><?php echo getConfig('second_new_footer'); ?></p>
					<b><?php echo getLange('shipper').' '.getLange('signature') ?> <span></span></b>
				</div>
				<div class="left_info_para table_pickedup">
				<table style=" width: 100%;">
					<thead>
						<tr>
				         <th colspan="3"><?php echo getLange('pickedup'); ?></th>
				      </tr>
					</thead>
				   <tbody>
				      <tr>
				         <td><?php echo getLange('couriercode'); ?></td>
				         <td><?php echo getLange('date') ?></td>
				         <td><?php echo getLange('time'); ?></td>
				      </tr>
				      <tr>
				         <td><?php echo isset($order_detail['track_no']) ? $order_detail['track_no']:''; ?></td>
				         <td><?php echo isset($order_detail['pickup_date']) ? date('M d,Y',strtotime($order_detail['pickup_date'])):''; ?></td>
				         <td><?php echo isset($order_detail['pickup_time']) ? date('g:h A',strtotime($order_detail['pickup_time'])):''; ?></td>
				      </tr>
				   </tbody>
				</table>
				<table style=" width: 100%;">
					<thead>
						<tr>
					        <th colspan="3"><?php echo getLange('doyourequirinsurance'); ?> <label>
					        	<input type="checkbox" name="" <?php echo (isset($order_detail['is_fragile']) && $order_detail['is_fragile'] > 1) ? 'checked':''; ?> > Yes</label> <label>
					        	<input type="checkbox" <?php echo (isset($order_detail['is_fragile']) && $order_detail['is_fragile'] ==1) ? 'checked':''; ?> name=""> No</label>
					        </th>
				      </tr>
					</thead>
				   <tbody>
				      <tr>
				         <td><?php echo getLange('discriptionofshipment'); ?>: <?php echo isset($order_detail['product_desc']) ? $order_detail['product_desc']:''; ?></td>
				      </tr>
				      <tr>
				         <td><?php echo getLange('declaredinsurancevalue'); ?> <?php echo isset($order_detail['insured_item_value']) ? $order_detail['insured_item_value']:''; ?></td>
				      </tr>
				   </tbody>
				</table>
				<table style=" width: 100%;">
					<thead>
						<tr>
				         <th colspan="4"><?php echo getLange('insurancecoverage'); ?></th>
				      </tr>
					</thead>
				   <tbody>
				      <tr>
				         <td></td>
				         <td><?php echo isset($insuranceType['name']) ? $insuranceType['name']:''; ?></td>
				         <td></td>
				      </tr>

				      <!-- <tr>
				         <td>Fragile</td>
				         <td>NON Fragile</td>
				         <td>Electronic</td>
				         <td>On Shipper Risk</td>
				      </tr> -->
				   </tbody>
				</table>
			</div>
			</div>
		</div>
		<div class="gst_no">
		<div class="right_boxes table_pickedup ">
			<table style=" width: 100%;">
				<thead>
					<tr>
			         <th colspan="3"><?php echo getLange('gstno') ?></th>
			      </tr>
				</thead>
			   <tbody>
			      <tr>
			         <td><?php echo getLange('destination'); ?></td>
			         <td><?php echo getLange('noofpiece') ?></td>
			         <td><?php echo getLange('weight'); ?></td>
			      </tr>
			      <tr>
			         <td><?php echo isset($order_detail['destination']) ? $order_detail['destination']:''; ?></td>
			         <td><?php echo isset($order_detail['quantity']) ? $order_detail['quantity']:''; ?></td>
			         <td><?php echo isset($order_detail['weight']) ? $order_detail['weight']:''; ?></td>
			      </tr>
			   </tbody>
			</table>
		</div>
		<div class="right_boxes table_pickedup ">
			<table style=" width: 100%;">
				<thead>
					<tr>
			         <th colspan="2"><?php echo getLange('servicetyperequires'); ?></th>
			      </tr>
				</thead>
			   <tbody>
			   	<?php if(!empty($service_detail)){
			   			$index = 0;
                        foreach($service_detail as $key=>$value){
                        	 if($index <= count($service_detail)){?>
					      <tr>
					         <td>
					         	<label>
					         		<input type="checkbox" <?php echo (isset($order_detail['order_type']) && $order_detail['order_type'] == $service_detail[$index]['id']) ? 'checked':''; ?> name=""> <?php echo isset($service_detail[$index]['service_type']) ? $service_detail[$index]['service_type']:''; ?>
					         	</label>
					         </td>
					         <?php $index++; ?>
					         <td>
					         	<?php if(isset($service_detail[$index]['service_type']) && $service_detail[$index]['service_type']){ ?>
						         	<label>
						         		<input type="checkbox" <?php echo (isset($order_detail['order_type']) && $order_detail['order_type'] == $service_detail[$index]['id']) ? 'checked="true"':''; ?> name=""> <?php echo isset($service_detail[$index]['service_type']) ? $service_detail[$index]['service_type']:''; ?>
						         	</label>
						        <?php } ?>
					         </td>
					      </tr>
					      <?php $index++; ?>
			    <?php } } } ?>
			     <!--  <tr>
			         <td><label><input type="checkbox" name=""> Over Night</label></td>
			         <td><label><input type="checkbox" name=""> NVY Overnight</label></td>
			      </tr>
			      <tr>
			         <td><label><input type="checkbox" name=""> 2ND Day</label></td>
			         <td><label><input type="checkbox" name=""> NVY 2ND Day</label></td>
			      </tr> -->
			   </tbody>
			</table>
		</div>
		<div class="right_boxes table_pickedup ">
			<table style=" width: 100%;">
				<thead>
					<tr>
			         <th colspan="3"><?php echo getLange('modeofpaymet'); ?></th>
			      </tr>
				</thead>
			   <tbody>
			      <tr>
			         <td><label><input type="checkbox" name="" <?php echo (isset($customerData['customer_type']) && $customerData['customer_type'] == 0) ? 'checked':''; ?> > COD</label></td>
			         <td><label><input type="checkbox" <?php echo (isset($customerData['customer_type']) && $customerData['customer_type'] == 1) ? 'checked':''; ?> name=""> NON COD</label></td>
			         <!-- <td><label><input type="checkbox" name=""> CashAccount</label></td> -->
			      </tr>
			   </tbody>
			</table>
		</div>
		<div class="right_boxes table_pickedup ">
			<table style=" width: 100%;">
				<thead>
					<tr>
			         <th colspan="2"><?php echo getLange('specialservice'); ?></th>
			      </tr>
				</thead>
			   <tbody>
			     	<?php if(!empty($special_service)){
			   			$index = 0;
                        foreach($special_service as $key=>$value){
                        	 if($index <= count($special_service)){
                        	 	$index2 = $special_service[$index]['id'];
                        	 	?>
					      <tr>
					         <td>
					         	<label>
					         		<input type="checkbox" <?php echo (isset($order_charges_detail[$index2]['charges_id']) && $order_charges_detail[$index2]['charges_id'] == $special_service[$index]['id']) ? 'checked':''; ?> name=""> <?php echo isset($special_service[$index]['charge_name']) ? $special_service[$index]['charge_name']:''; ?>
					         	</label>
					         </td>
					         <?php
					            $index++;
					            $index2 = $special_service[$index]['id'];
					        ?>
					         <td>
					         	<?php if(isset($special_service[$index]['charge_name']) && $special_service[$index]['charge_name']){ ?>
						         	<label>
						         		<input type="checkbox" <?php echo (isset($order_charges_detail[$index2]['charges_id']) && $order_charges_detail[$index2]['charges_id'] == $special_service[$index]['id']) ? 'checked="true"':''; ?> name=""> <?php echo isset($special_service[$index]['charge_name']) ? $special_service[$index]['charge_name']:''; ?>
						         	</label>
						        <?php } ?>
					         </td>
					      </tr>
					      <?php $index++; ?>
			    	<?php } } } ?>
			   </tbody>
			</table>
		</div>
		<div class="right_boxes table_pickedup ">
			<table style=" width: 100%;">
				<thead>
					<tr>
			         <th style="text-align: left;"><?php echo getLange('Services'); ?></th>
			         <th style="text-align: left;"><?php echo getLange('charges'); ?></th>
			      </tr>
				</thead>
			   <tbody>
			   	<?php if(isset($order_detail['weight']) && $order_detail['weight']){ ?>
				    <tr>
				        <td><?php echo getLange('weight') ?></td>
				        <td><?php echo $order_detail['weight']; ?></td>
				    </tr>
			    <?php } ?>
			     <?php if(isset($order_detail['price']) && $order_detail['price']){ ?>
				    <tr>
				        <td><?php echo getLange('deliverycharges'); ?></td>
				        <td><?php echo $order_detail['price']; ?></td>
				    </tr>
			    <?php } ?>
			     <?php if(isset($order_detail['grand_total_charges']) && $order_detail['grand_total_charges']){ ?>
				    <tr>
				        <td><?php echo getLange('totalcharges'); ?></td>
				        <td><?php echo $order_detail['grand_total_charges']; ?></td>
				    </tr>
			    <?php } ?>
                <?php if ($admin_extra_charges==1 && $customer_extra_charges==1): ?>
                    <?php if(isset($order_detail['extra_charges']) && $order_detail['extra_charges']){ ?>
                        <tr>
                            <td><?php echo getLange('extracharges'); ?></td>
                            <td><?php echo $order_detail['extra_charges']; ?></td>
                        </tr>
                    <?php } ?>
                <?php endif; ?>
			    <?php if ($admin_insured_premium==1 && $customer_insured_premium==1): ?>
    			    <?php if(isset($order_detail['insured_premium']) && $order_detail['insured_premium']){ ?>
    				    <tr>
    				        <td><?php echo getLange('extracharges'); ?></td>
    				        <td><?php echo $order_detail['insured_premium']; ?></td>
    				    </tr>
    			    <?php } ?>
                <?php endif; ?>
			    <?php if(isset($order_detail['pft_amount']) && $order_detail['pft_amount']){ ?>
				    <tr>
				        <td><?php echo getLange('salestax'); ?></td>
				        <td><?php echo $order_detail['pft_amount']; ?></td>
				    </tr>
			    <?php } ?>
			    <?php if(isset($order_detail['net_amount']) && $order_detail['net_amount']){ ?>
			      <tr>
			         <td><b style="font-size: 17px;"><?php echo getLange('total'); ?></b></td>
			         <td><?php echo $order_detail['net_amount']; ?></td>
			      </tr>
			    <?php } ?>
			   </tbody>
			</table>
		</div>
		</div>
	</div>
<?php }} ?>
</body>
</html>
<?php
	}
	else{
		header("location:index.php");
	}
?>
