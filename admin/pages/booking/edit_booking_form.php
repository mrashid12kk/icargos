<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include_once "../../includes/conn.php";
function getBarCodeImage($text = '', $code = null, $index) {
	require_once('../../../includes/BarCode.php');
	$barcode = new BarCode();
	$path = '../../../assets/barcodes/imagetemp'.$index.'.png';
	$barcode->barcode($path, $text);
	$folder_path='../../../assets/barcodes/imagetemp'.$index.'.png';
	return $folder_path;
}

function encrypt($string) {
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
$customer_id = '';
if(isset($_GET['customer_id'])){
	$customer_id = $_GET['customer_id'];
	$customer_query = mysqli_query($con,"SELECT * FROM customers WHERE id=".$customer_id." ");
	$customer_data = mysqli_fetch_array($customer_query);
}
if(isset($_POST['settle']) ){
	$customer_id = $_POST['customer_id'];
	$weight = (float)$_POST['weight'];
	$order_type = $_POST['order_type'];
	$origin = $_POST['origin'];
	$destination = $_POST['destination'];
	include '../../../price_calculation.php';
	$delivery = delivery_calculation($origin,$destination,$weight,$customer_id,$order_type);
	echo $delivery; exit();
}
if(isset($_POST['submit_order']) || isset($_POST['save_order']))
{
	
	$customer_id = $_POST['active_customer_id'];
	$customer_query = mysqli_query($con,"SELECT * FROM customers WHERE id=".$customer_id." ");
	$customer_data = mysqli_fetch_array($customer_query);
	$date=date('Y-m-d H:i:s');

	$plocation='';

	$_POST['receiver_address'] = strip_tags(trim($_POST['receiver_address']));
	$_POST['receiver_address'] = htmlentities($_POST['receiver_address'],ENT_NOQUOTES);
	$_POST['receiver_address'] = str_replace("'", '"', $_POST['receiver_address']);

	$_POST['pickup_address'] = strip_tags(trim($_POST['pickup_address']));
	$_POST['pickup_address'] = htmlentities($_POST['pickup_address'],ENT_NOQUOTES);
	$_POST['pickup_address'] = str_replace("'", '"', $_POST['pickup_address']);

	$_POST['product_desc'] = strip_tags(trim($_POST['product_desc']));
	$_POST['product_desc'] = htmlentities($_POST['product_desc'],ENT_NOQUOTES);
	$_POST['product_desc'] = str_replace("'", '"', $_POST['product_desc']);

	$_POST['special_instruction'] = strip_tags(trim($_POST['special_instruction']));
	$_POST['special_instruction'] = htmlentities($_POST['special_instruction'],ENT_NOQUOTES);
	$order_date = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['order_date'])));
	
	$_POST['special_instruction'] = str_replace("'", '"', $_POST['special_instruction']);
	$insert_qry="INSERT INTO `orders`(`sname`,`sbname`,`sphone`, `semail`, `sender_address`, `rname`, `rphone`, `receiver_address`,`pickup_date`,`price`,`collection_amount`,`order_date`,`payment_method`,`customer_id`,`origin`,`destination`,`tracking_no`,`weight`,`product_desc`,`special_instruction`,`quantity`,`product_id`, `order_type`,`ref_no`,`excl_amount`,`pft_amount`,`inc_amount`,`is_ondesk`) VALUES ('".$_POST['fname']."','".$_POST['bname']."','".$_POST['mobile_no']."','".$_POST['email']."','".$_POST['pickup_address']."','".$_POST['receiver_name']."','".$_POST['receiver_phone']."','".$_POST['receiver_address']."','".$date."','".$_POST['total_amount']."','".$_POST['collection_amount']."','".$order_date."','CASH','".$customer_id."','".$_POST['origin']."','".$_POST['destination']."','".$_POST['tracking_no']."','".$_POST['weight']."','".$_POST['product_desc']."','".$_POST['special_instruction']."' ,'".$_POST['quantity']."','".$_POST['product_id']."', '".$_POST['order_type']."','".$_POST['ref_no']."','".$_POST['excl_amount']."','".$_POST['pft_amount']."','".$_POST['inc_amount']."','1' ) ";
	
	$query=mysqli_query($con,$insert_qry);
	$insert_id=mysqli_insert_id($con);
	
	
	fclose($file);
	if($insert_id > 0) {
		$track_no = $_POST['track_no'];
		$barcode_image = getBarCodeImage($track_no, null, $last_id);
		
		mysqli_query($con, "UPDATE orders SET barcode = '".$track_no."', barcode_image = '".$barcode_image."', track_no = '".$track_no."' WHERE id = $insert_id");
		
		mysqli_query($con,"INSERT INTO order_logs(`order_no`,`order_status`,`location`,`created_on`) VALUES ('".$track_no."', 'Order is Booked', '".$_POST['origin']."','".$date."') ");
		$iddd=encrypt($insert_id."-TRS767###");
		if(isset($_POST['submit_order']) && $_POST['submit_order'] == '1' ) {
			ob_clean();
			echo json_encode(['id' => $iddd, 'print' => 1,'track_no'=>$track_no]);
			exit();
		} else {
			ob_clean();
			echo json_encode(['id' => $iddd, 'track_no'=>$track_no]);
			exit();
		}
	}
	
	exit();
}


	//order process////////////////
$customer_origin_zone_q = mysqli_query($con," SELECT GROUP_CONCAT(DISTINCT zone_id SEPARATOR ',') as zone_ids
	FROM customer_pricing WHERE customer_id='".$customer_id."'  ");
if(mysqli_num_rows($customer_origin_zone_q) >0){
	$origin_zone_res = mysqli_fetch_array($customer_origin_zone_q);
	$zone_ids = $origin_zone_res['zone_ids'];
	$origin_q = mysqli_query($con," SELECT DISTINCT origin FROM zone_cities WHERE zone IN(".$zone_ids.") ");
	$destination_q = mysqli_query($con," SELECT DISTINCT destination FROM zone_cities WHERE zone IN(".$zone_ids.") ");
		//service types queries
	$service_type_q = mysqli_query($con," SELECT GROUP_CONCAT(DISTINCT service_type SEPARATOR ',') as service_types FROM zone WHERE id IN (".$zone_ids.") ");
	if(mysqli_num_rows($service_type_q) >0){
		$service_type_id_res = mysqli_fetch_array($service_type_q);
		$service_types = $service_type_id_res['service_types'];
		$get_service_types = mysqli_query($con," SELECT DISTINCT id,service_type FROM services WHERE id IN(".$service_types.") ");
	}
}

$customers = mysqli_query($con,"SELECT * FROM customers WHERE status=1");
$gst_query = mysqli_query($con,"SELECT value FROM config WHERE `name`='gst' ");
$total_gst = mysqli_fetch_array($gst_query);
$customer_id = isset($_GET['customer_id']) ? $_GET['customer_id'] :  '';
$order_id = $_GET['id'];
$order_q = mysqli_query($con,"SELECT * FROM orders WHERE id ='".$order_id."' ");
$order_res = mysqli_fetch_array($order_q);
?>


<style type="text/css">
	.calculation_label {
		font-size: 14px !important;
	}
	label,.calculation_label {
		font-weight: 500;
	}
</style>
<div class="panel-heading order_box">Edit Booking Form</div>
<div class="panel-body" id="same_form_layout">
	<div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
		<div class="row">
			
			<div class="col-sm-12 dashboard" style="padding: 0;">
				<div class="white shipper_box" style="    padding: 10px 0;">
					<div id='msg'></div>
					<form role="form" action="booking.php" method="POST" id="booking_form">
						<input type="hidden" name="active_customer_id" class="active_customer" value="<?php echo $customer_id; ?>">
						<input type="hidden" name="" class="total_gst" value="<?php echo isset($total_gst['value']) ? $total_gst['value'] : '0'; ?>">
						<div class="row">
							
							<div class="col-sm-3 sidegap">
								<div class="form-group">
									<label>Service type</label>
									<select class="form-control order_type" name="order_type">
										<?php if(isset($get_service_types) && !empty($get_service_types)){
											while($row = mysqli_fetch_array($get_service_types)){
												
												
												?>
												
												<option value="<?php echo $row['service_type']; ?>" data-id="<?php echo $row['id']; ?>"><?php echo isset($row['service_type']) ? $row['service_type'] : ''; ?></option>
											<?php  } } ?>
										</select>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group">
										<label>Order Date</label>
										<input type="text" value="<?php echo date('d/m/Y'); ?>" class="form-control datepicker" name="order_date">
									</div>
								</div>
					<!-- <div class="col-sm-3">
								<div class="form-group">
								<label>CN#</label>
								<input type="text" class="form-control" placeholder="Consignment No" name="track_no" required="true">
							     </div>
							 </div> -->
							</div>
							<div class="row">
								<div class="col-sm-6">
									<div class="panel panel-default">
										<div class="panel-heading">Pickup Details<span style="float: right;" class="right_order"></span></div>
										<div class="panel-body">
											
											
											<div class="col-sm-12 padd_left">
												<div class="form-group">
													<label><span style="color: red;">*</span> City</label>
													<input type="hidden" name="origin_branch" class="origin_branch_id" value="0">
													<select class="form-control origin origin_cal js-example-basic-single" name="origin">
														<?php while($row = mysqli_fetch_array($origin_q)){ ?>
															<option <?php if($order_res['origin'] == $row['origin'] ){ echo "selected"; } ?> ><?php echo $row['origin']; ?></option>
															<?php } ?>>
														</select>
													</div>
												</div>
												
												
												<div class="row">
													
													<div class="col-sm-12 padd_left">
														
														<div class="form-group" >
															<label  class="control-label"><span style="color: red;">*</span> Name</label>
															<input type="hidden" name="bname" value="<?php echo isset($customer_data['bname']) ? $customer_data['bname'] :'';  ?>">
															<input type="text" class="form-control shipper_fname" value="<?php echo isset($customer_data['fname']) ? $customer_data['fname'] :'';  ?>" name="fname" placeholder="Shipper Name" readonly="true" >
														</div>
													</div>
													
												</div>
												<div class="row">
													
													<div class="col-sm-6 padd_left" style="padding-right:0;">
														<div class="form-group">
															<label  class="control-label"><span style="color: red;">*</span> Phone</label>
															<input type="text" class="form-control shipper_mob" value="<?php echo isset($customer_data['mobile_no']) ? $customer_data['mobile_no'] :'';  ?>" name="mobile_no" placeholder="Shipper Phone" required="true" readonly="true">
														</div>
													</div>
													<div class="col-sm-6">
														<div class="form-group">
															<label  class="control-label"><span style="color: red;"></span> Email</label>
															<input type="email" value="<?php echo isset($customer_data['email']) ? $customer_data['email'] :'';  ?>" class="form-control shipper_email" name="email" placeholder="Shipper Email" readonly="true">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-sm-12 padd_left" style="padding-right:0;">
														<div class="form-group">
															<label  class="control-label"><span style="color: red;">*</span> Address</label>
															<textarea readonly="true" class="form-control shipper_address"  name="pickup_address"  placeholder="Shipper Address" required="true"><?php echo isset($customer_data['address']) ? $customer_data['address'] :'';  ?></textarea>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="panel panel-default">
											<div class="panel-heading">Delivery Details <span style="float: right;" class="right_order"></span></div>
											<div class="panel-body">
												
												
												<div class="row">
													<div class="col-sm-12 padd_left padd_right">
														<div class="form-group">
															<label><span style="color: red;">*</span> City</label>
															<select class="form-control destination destination_select js-example-basic-single" name="destination">
																<?php while($row = mysqli_fetch_array($destination_q)){ ?>
																	<option value="<?php echo $row['destination']; ?>" <?php if($row['destination'] == $order_res['destinatio']){ echo "class='karachi' selected"; } ?>><?php echo $row['destination']; ?></option>
																<?php } ?>
															</select>
														</div>
													</div>
													<div class="col-sm-6 padd_left">
														<div class="form-group">
															<label  class="control-label"><span style="color: red;">*</span> Name</label>
															<input type="text" class="form-control" name="receiver_name" placeholder="Consignee name" value="<?php echo $order_res['rname']; ?>" required="true">
														</div>
													</div>
													<div class="col-sm-6 padd_right">
														<div class="form-group">
															<label  class="control-label"><span style="color: red;">*</span> Phone</label>
															<input type="text" class="form-control" name="receiver_phone" placeholder="Consignee Phone" value="<?php echo $order_res['rphone']; ?>" required="true">
														</div>
													</div>
												</div>
												<div class="row">
													
													<div class="col-sm-12 padd_left" style="padding-right:0;">
														<div class="form-group">
															<label  class="control-label"><span style="color: red;"></span> Email</label>
															<input type="email" class="form-control" name="receiver_email" placeholder="Consignee Email" value="<?php echo $order_res['remail']; ?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-sm-12 padd_left" style="padding-right:0;">
														<div class="form-group">
															<label  class="control-label"><span style="color: red;">*</span> Address</label>
															<textarea class="form-control" name="receiver_address"  placeholder="Consignee Address" required="true" ><?php echo $order_res['receiver_address']; ?></textarea>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									
									
								</div>
								<div class="row">
									<div class="col-sm-12 sidegap" style="padding: 0;">
										<div class="row">
											<div class="col-sm-9" style="padding-right: 0;">
												<div class="panel panel-default" style="padding-bottom: 120px;">
													<div class="panel-heading">Shipment Details <span style="float: right;" class="right_order"></span></div>
													<div class="panel-body">
														<div class="row">
															<div class="col-sm-6">
																<div class="form-group">
																	<label><span style="color: red;">*</span> Item Detail</label>
																	<textarea class="form-control" name="product_desc" required="true"><?php echo  $order_res['product_desc']; ?></textarea>
																</div>
															</div>
															<div class="col-sm-6">
																<div class="form-group">
																	<label><span style="color: red;"></span> Special Instruction</label>
																	<textarea class="form-control" name="special_instruction"><?php echo  $order_res['special_instruction']; ?></textarea>
																</div>
															</div>
															<div class="col-sm-6">
																<div class="form-group">
																	<label> Reference No.</label>
																	<input type="text" name="ref_no" class="form-control" value="<?php echo $order_res['ref_no']; ?>" >
																</div>
															</div>
															<div class="col-sm-6">
																<div class="form-group">
																	<label> Order ID.</label>
																	<input type="text" name="product_id" class="form-control" value="<?php echo $order_res['product_id']; ?>">
																</div>
															</div>
															<div class="col-sm-6 padd_left padd_right">
																<div class="form-group">
																	<label class="calculation_label"><span style="color: red;">*</span> No. of Pieces</label>
																	<input type="myNumber" name="quantity" class="form-control pieces" required="true" value="<?php echo $order_res['quantity']; ?>">
																</div>
															</div>
															<div class="col-sm-6 padd_left">
																<div class="form-group">
																	<label class="calculation_label"><span style="color: red;">*</span> Weight (Kg)</label>
																	
																	<input type="myNumber" name="weight" class="form-control weight" required="true" value="<?php echo $order_res['weight']; ?>"> 
																</div>
															</div>
															

															<div class="col-sm-12">
																<div class="form-group">
																	<label><span style="color: red;"></span> Special Instruction</label>
																	<textarea class="form-control" name="special_instruction"><?php echo  $order_res['special_instruction']; ?></textarea>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="col-sm-3">
												<div class="panel panel-default">
													<div class="panel-heading">Price Information <span style="float: right;" class="right_order"></span></div>
													<div class="panel-body">
														<div class="row">
															
															<div class="col-sm-12 padd_right" style="display: none;">
																<div class="form-group">
																	<label class="calculation_label"><span style="color: red;">*</span> Delivery Charges</label>
																	<input type="text" name="delivery_charges"  class="form-control total_amount allownumericwithdecimal" value="<?php echo $order_res['price']; ?>" >
																</div>
															</div>
															
															
															
															<div class="col-sm-12">
																<div class="form-group">
																	<label><span style="color: red;">*</span>COD Amount</label>
																	<input type="text" name="collection_amount" class="form-control allownumericwithdecimal" required="true" value="<?php echo $order_res['collection_amount']; ?>">
																</div>
															</div>
															<div class="col-sm-12">
																<div class="form-group">
																	<label><span style="color: red;">*</span>Service charges</label>
																	<input type="text" value="<?php echo $order_res['excl_amount']; ?>" name="excl_amount" class="form-control allownumericwithdecimal excl_amount" required="true" >
																</div>
															</div>
															<div class="col-sm-12">
																<div class="form-group">
																	<label><span style="color: red;">*</span>Sales tax</label>
																	<input type="text" name="pft_amount" value="<?php echo $order_res['pft_amount']; ?>" class="form-control allownumericwithdecimal pft_amount" required="true" >
																</div>
															</div>
															<div class="col-sm-12">
																<div class="form-group">
																	<label><span style="color: red;">*</span>Total service charges</label>
																	<input type="text" value="<?php echo $order_res['inc_amount']; ?>" name="inc_amount" class="form-control allownumericwithdecimal inc_amount" required="true">
																</div>
															</div>
														</div>
														
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<input type="submit" name="update_order" class="add_form_btn " value="Update"  >
							</form>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
</div>