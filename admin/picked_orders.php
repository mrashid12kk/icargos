<?php
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
	$message = '';
	session_start();
	require 'includes/conn.php';
	if(isset($_SESSION['users_id'])){
	include "includes/header.php";
	if(isset($_POST['update_order']) && isset($_GET['id'])) {
		$id = (int)$_GET['id'];
		$data = $_POST;
		unset($data['update_order']);
		$sql = "UPDATE orders SET";
		$index = 0;
		foreach ($data as $key => $value) {
			$sql .= " $key = '$value'";
			$index++;
			if($index != count($data))
				$sql .= ",";
		}
		$sql .= " WHERE id = $id";
		if(mysqli_query($con, $sql))
			{
				$collection_amount = (int)trim($data['collection_amount']);
				$price = (int)trim($data['price']);
				$order_data = mysqli_query($con,"SELECT track_no FROM orders WHERE id =".$id." ");
				$order_number_data = mysqli_fetch_array($order_data);
				$order_no = $order_number_data['track_no'];

				mysqli_query($con,"UPDATE ledger SET delivery_charges ='".$price."', collected_amount='".$collection_amount."' WHERE order_no=".$order_no." ");
				$message = '<div class="alert alert-success">Order is updated successfully!</div>';
				
			}else{
				$message = '<div class="alert alert-warning">Order is not updated!</div>';
			}
	}
if(isset($_POST['branch_id']) && $_POST['branch_id'] != '') {
	$branch_id = $_POST['branch_id'];
	$order_id = $_GET['id'];
	mysqli_query($con, "UPDATE orders SET branch_id = '".$branch_id."' WHERE id = '".$order_id."' ");
}
$id =(int)$_GET['id'];
$message_query = mysqli_query($con,"SELECT * FROM order_comments WHERE order_id =".$id." order by id   ");
$total_comments = mysqli_num_rows($message_query);



	$cities2 = mysqli_query($con,"SELECT * FROM cities WHERE 1 order by id desc ");
?>
<body data-ng-app>
 	<style type="text/css">
 	@media(max-width: 767px ){
 		.container{
 			width: auto;
 		}
 		.content>.container-fluid {
		    padding-left: 5px;
		    padding-right: 6px;
		}
		table.detail a, table.detail select, table.detail input {
		    margin-bottom: 7px;
		    margin-right: 10px;
		}
		#same_form_layout{
			    padding: 0 !important;
		}
		.table-bordered {
		    border: 1px solid #ddd;
		    border-right: none;
		}
		.panel-body {
		    padding: 10px 8px;
		}
 	}
 	</style>
    
	<?php
	
	include "includes/sidebar.php";
	
	?>
    <!-- Aside Ends-->
    
    <section class="content">
    	 
	<?php
	include "includes/header2.php";
	?>
        
        <!-- Header Ends -->
        <style type="text/css">
        	table.detail a, table.detail select, table.detail input {
        		margin-right: 10px;
        	}
        </style>
        
        <div class="warper container-fluid">
        	<?php if(isset($_SESSION['order_message'])){ ?>
	        	<div class="alert alert-success">
	        		<?php
	        		    echo  $_SESSION['order_message'] ;
	        		    unset($_SESSION['order_message']);
	        		?>
	        	</div>
	        <?php } ?>
		<?php
		if(isset($_GET['id']) && !empty($_GET['id'])) {
			if(isset($_GET['message']) && !empty($_GET['message']))
				echo $_GET['message'];
			$id =(int)$_GET['id'];
			$query = mysqli_query($con, "SELECT * FROM orders WHERE id = '".$id."'");
			$data = mysqli_fetch_array($query);
			$prev_order = mysqli_query($con, "SELECT * FROM orders WHERE id < '".$id."' ORDER BY id DESC LIMIT 1");
			$prev_order = ($prev_order) ? mysqli_fetch_object($prev_order) : null;
			$next_order = mysqli_query($con, "SELECT * FROM orders WHERE id > '".$id."' ORDER BY id LIMIT 1");
			$next_order = ($next_order) ? mysqli_fetch_object($next_order) : null;
			if($data['status'] == 'null' || $data['status'] == '')
				$data['status'] = 'New Booked';
			$type = $_SESSION['type'];
			$userID = $_SESSION['users_id'];
			$query = mysqli_query($con, "SELECT * FROM orders WHERE id = '".$id."' ") or die(mysqli_error($con));
			$deliverData = mysqli_fetch_array($query);
			$status = $deliverData['status'];
			$driverID = $deliverData['driver_id'];
			$deliverdriverID = isset($deliverData['assign_driver']) ? $deliverData['assign_driver']:'';
			$deliverID = $deliverData['id'];
			$status = $data['status'];
			
			//pickup query 
			$pickup_query = mysqli_query($con,"SELECT * FROM zones WHERE city='".$data['origin']."' ");
			$delivery_query = mysqli_query($con,"SELECT * FROM zones WHERE city='".$data['destination']."' ");
			echo '<div class="panel panel-default">';
				echo '<div class="panel-heading">Order Details</div>';
				echo '<div class="panel-body">';
				echo $message;
				?>
				<div id="same_form_layout" style="padding:0 9px;">
					<div class="row">
					<div class="col-sm-1 text-left upate_Btn left_right_none">
					<?php if(isset($prev_order->id)) { ?>
						<a href="order.php?id=<?php echo $prev_order->id; ?>" class="btn btn-primary"><i class="fa fa-angle-left"></i>&nbsp;Prev</a>
					<?php } ?>
					</div>
					<?php if($data['pickup_zone'] == '' || $data['delivery_zone'] == ''){ ?>
					<form method="POST" action="savezone.php">
						<input type="hidden" name="customer_id" value="<?php echo $data['customer_id']; ?>">
						<input type="hidden" name="order_id" value="<?php echo $_GET['id']; ?>">
					<div class="col-sm-2 left_right_none">
						<div class="form-group">
							<label>Select Pickup Zone</label>
							<select class="form-control js-example-basic-single" name="pickup_zone">
								<option selected disabled>Select Pickup Zone</option>
								<?php while($rec = mysqli_fetch_array($pickup_query)){ ?>
								<option <?php if($rec['id'] == $data['pickup_zone']){ echo "selected"; } ?> value="<?php echo $rec['id']; ?>"><?php echo $rec['zone']; ?></option>
							<?php } ?>
							</select>
						</div>
					</div> 
					<div class="col-sm-2 left_right_none">
						<div class="form-group">
							<label>Select Delivery Zone</label>
							<select class="form-control js-example-basic-single" name="delivery_zone">
								<option selected disabled>Select Delivery Zone</option>
								<?php while($rec2 = mysqli_fetch_array($delivery_query)){ ?>
								<option <?php if($rec2['id'] == $data['delivery_zone']){ echo "selected"; } ?> value="<?php echo $rec2['id']; ?>"><?php echo $rec2['zone']; ?></option>
							<?php } ?>
							</select>
						</div>
					</div>
					<div class="col-sm-1 upate_Btn left_right_none">
						<input type="submit" name="save_zone" class="btn btn-info" value="Save">
					</div>
				</form>
			<?php } ?>
		<?php
			$ispickup = true;
			$ispickuprider = true;
			if($data['pickup_zone'] == '' || $data['delivery_zone'] == ''){
				$ispickup = false;
			}
			if($data['pickup_rider'] == ''){
				$ispickuprider = false;
			}
			$rider_query = mysqli_query($con,"SELECT * FROM users WHERE type='driver'  "); 
			?>
			<?php if($ispickup == true && $ispickuprider == false){ ?>
			<form method="POST" action="savezone.php" id="assign_rider">
				<input type="hidden" name="order_id" value="<?php echo $data['id']; ?>">
				<div class="row">
				<div class="col-sm-2 left_right_none">
					<label>Select Pickup Rider</label>
					<select name="rider_id"  class="form-control js-example-basic-single pickup_rider">
						<option value="0" selected disabled>Select Pickup Rider</option>
						<?php while($rec = mysqli_fetch_array($rider_query)){ ?>
						<option value="<?php echo $rec['id']; ?>" <?php if($pickup_rider == $rec['id']){ echo "selected"; } ?> ><?php echo $rec['Name']; ?></option>
					<?php } ?>
					</select>
				</div>
				<div class="col-sm-2 upate_Btn left_right_none">
					<input type="submit" name="assign_pickup_zone" class=" btn btn-info" value="Assign Pickup Rider">
				</div>
			</div>
			</form>
		<?php } ?>
					<div class="col-sm-1 text-right upate_Btn left_right_none pull-right next_Btn_box">
					<?php if(isset($next_order->id)) { ?>
						<a href="order.php?id=<?php echo $next_order->id; ?>" class="btn btn-primary">Next&nbsp;<i class="fa fa-angle-right"></i></a>
					<?php } ?>
					</div>
					 
				</div>
				
				<?php
					echo '<table class="table table-bordered detail order-detail-form">';
					
						

						echo '<tr>';
							
							echo '<td colspan="2" class="other-actions">';
								
								if($type == 'driver') {
								
								} else {
									if($type == 'admin') {
										echo '<a href="#" class="btn btn-info edit-order pull-right">Edit</a>';
									}
									if($data['status'] == 'cancelled') {
										?>
										<a href="orderAction.php?order=<?=$id;?>&stat=booked" class="btn btn-primary">Restore Order</a>
										<?php
									}
								
									if($data['is_received'] == 0 AND $ispickup == true AND $ispickuprider == true ){
										?>
										<a href="orderAction.php?order=<?php echo $id; ?>&stat=received" class="btn btn-success">Item Received</a>
										<a href="orderAction.php?order=<?php echo $id; ?>&stat=not_received" class="btn btn-primary">Item Not Received</a>
									<?php }

									if($data['is_shipped'] == 0 && $data['destination'] !='Islamabad' && ($data['status'] == 'received' ) ){
										?>
										<a href="orderAction.php?order=<?php echo $id; ?>&shipped=1" class="btn btn-success">Shipped to Outstation</a>
									<?php }
									$instation = true;
									if($data['status'] == 'received' && $data['destination'] != 'Islamabad' ){
										$instation = false;
									}
									if( ($data['status'] == 'received' || $data['status'] == 'dispatch' || $data['status'] =='New Booked' ) && $instation == true ){

											echo '<form style="display: inline-block;" action="orderAction.php" method="POST" >';
												 {
													?>
													<!-- <input type="hidden" name="from_return" value="1"> -->
													<?php
													?><input type="hidden" name="assign_driver" value="0_<?php echo $id; ?>"><?php
												}
												echo '<select style="width: auto; display: inline-block" name="dassign_driver" class="form-control">';
													echo '<option value="">Assign Delivery Rider</option>';
													$query=mysqli_query($con,"Select * from users where type='driver'");
													while($driver = mysqli_fetch_array($query)) {
														echo '<option value="'.$driver['id'].'">'.$driver['Name'].'</option>';
													}
												echo '</select>';
												echo '<input type="submit" name="assign" class="btn btn-success" value="Assign" />';
											echo '</form>';
										}
										if($status == 'assigned' || $status == 'accepted' || $status == 'returned'){
											?>
											<form method="POST" action="orderAction.php" novalidate>
												<input type="hidden" name="order_id" value="<?php echo $id; ?>">
												<div class="row">
													<div class="col-sm-3">
														<div class="form-group">
															<label>Status</label>
															<select class="form-control order_sts" name="order_status">
																<option selected disabled>Select Status</option>
																<option value="New Booked">New Booked</option>
																<option value="delivered">Delivered</option>
																<option value="returned">Returning</option>
															</select>
														</div>
													</div>
													<div class="col-sm-3 pending_reson_main" style="display: none;">
														<div class="form-group">
															<label>Pending Reason</label>
															<select class="form-control other_reason" name="pending_reason">
																<option selected disabled>Select Reason</option>
																<option>Bad weather</option>
																<option>Address incorrect</option>
																<option>Consignee not available</option>
																<option>Delivery address closed</option>
																<option>No such person</option>
																<option>Refused to receive</option>
																<option>Restricted area</option>
																<option>Incomplete Contact details</option>
																<option>Insufficient funds/ Payment not available</option>
																<option>Sealed delivery not accepted</option>
																<option>Other</option>
															</select>
														</div>
													</div>
													
													<div class="col-sm-3 returned_reson_main"  style="display: none;">
														<div class="form-group">
															<label>Return Reason</label>
															<select class="form-control other_reason" required="true" name="returned_reason">
																<option value="" selected disabled>Select Reason</option>
																<option>Mobile Powered Off</option>
																<option>Address Incorrect</option>
																<option>Refused</option>
																<option>Order By Mistake</option>
																<option>Bad Weather</option>
																<option>Don’t Have Money</option>
																<option>Other</option>
															</select>
														</div>
													</div>
													<div class="col-sm-2 other_reason_main" style="display: none;">
														<label>Other Reason</label>
														<input type="text" name="other_reason" class="form-control">
													</div>
													<div class="col-sm-3 received_by" style="display: none;">
														<label>Received By</label>
														<input type="text" name="received_by" class="form-control">
													</div>
													<div class="col-sm-3">
														<input style="margin-top:24px; display: none;" type="submit" name="update_status" class="btn btn-info update_status" value="Submit">
													</div>
												</div>
												
												
											</form>
										<?php }
									
									
								}
							
							echo '</td>';
						echo '</tr>';
						echo '<form method="POST" action="" />';	
						echo '<tr>';
							echo '<td width="20%">Order/Track#</td>';
							echo '<td class="form-input">';
							echo '<span >'.$data['track_no'].'</span>';
							echo '</td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td width="20%">Order Type</td>';
							echo '<td class="form-input">';
							echo '<span >'.(($data['order_type'] == 'cod') ? 'COD' : 'Overland').'</span>';
							?>
							<select hidden="true" class=" order_type" name="order_type">
								<option value="cod" <?=($data['order_type'] == 'cod') ? 'selected' : '';?>>COD</option>
								<option value="overlong" <?=($data['order_type'] == 'overlong') ? 'selected' : '';?>>Overland</option>
							</select>
							<?php
							echo '</td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td width="20%">Origin</td>';
							echo '<td class="form-input">';
							echo '<span class="main_origin">'.$data['origin'].'</span>';
							echo '</td>';
						echo '</tr>';
							echo '<tr>';
							echo '<td width="20%">Destination</td>';
							echo '<td class="form-input">';
							echo '<span class="main_destination">'.$data['destination'].'</span>';
							?>
							<select style="display: none;" class=" destination destination_select " name="destination">
								<?php while($row = mysqli_fetch_array($cities2)){ ?>
								<option value="<?php echo $row['city_name']; ?>" <?php if($row['city_name'] == $data['destination']){ echo " selected"; } ?>><?php echo $row['city_name']; ?></option>
							<?php } ?>
							</select>
							<?php
							echo '<input type="hidden" class="customer_id" value='.$data['customer_id'].' >';
							echo '</td>';
						echo '</tr>';
							if(isset($data['branch_id']) && $type == 'admin') {
								$branch_idss=$data['branch_id'];
								$qrry="SELECT * FROM branches WHERE id = '".$branch_idss."' ";
								$branch = mysqli_query($con, $qrry);
								if($branch) {
									$branch = mysqli_fetch_object($branch);
									if(isset($branch->name)) {
									echo '<tr>';
										echo '<td width="20%">Branch Name</td>';
										echo '<td class="form-input">';
										echo '<span >'.$branch->name.'</span>';
										echo '</td>';
									echo '</tr>';
									}
								}
							}
						echo '<tr>';
							echo '<td width="20%">Sender</td>';
							echo '<td class="form-input">';
							echo '<strong>Name:</strong><span >'.$data['sname'].'</span><input hidden type="text" name="sname" value="'.$data['sname'].'"><br>';
							echo '<strong>Company:</strong><span >'.$data['sbname'].'</span><input hidden type="text" name="sbname" value="'.$data['sbname'].'"><br>';
							echo '<strong>Email:</strong><span >'.$data['semail'].'</span><input hidden type="text" name="semail" value="'.$data['semail'].'"><br>';
							echo '<strong>Phone:</strong><span >'.$data['sphone'].'</span><input hidden type="text" name="sphone" value="'.$data['sphone'].'"><br>';
							echo '<strong>Address:</strong><span >'.$data['sender_address'].'</span><input hidden type="text" name="sender_address" value="'.$data['sender_address'].'"><br>';
							echo '</td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td width="20%">Receiver</td>';
							echo '<td class="form-input">';
							echo '<strong>Name:</strong><span >'.$data['rname'].'</span><input hidden type="text" name="rname" value="'.$data['rname'].'"><br>';
							// echo '<span ><strong>Email:</strong>'.$data['remail'].'</span><br>';
							echo '<strong>Phone:</strong><span >'.$data['rphone'].'</span><input hidden type="text" name="rphone" value="'.$data['rphone'].'"><br>';
							echo '<strong>Address:</strong><span >'.$data['receiver_address'].'</span><input hidden type="text" name="receiver_address" value="'.$data['receiver_address'].'"><br>';
							echo '</td>';
						echo '</tr>';
						
						
						echo '<tr>';
							echo '<td width="20%">COD Amount</td>';
							echo '<td class="form-input">';
							echo '<span >Rs '.(int)$data['collection_amount'].'</span>';
							echo '<input hidden type="text" name="collection_amount" value="'.$data['collection_amount'].'" />';
							echo '</td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td width="20%">Delivery Fee</td>';
							echo '<td class="form-input">';
							echo '<span >Rs '.(int)$data['price'].'</span>';
							echo '<input hidden type="text" class="delivery" name="price" value="'.$data['price'].'" />';
							echo '</td>';
						echo '</tr>';
							echo '<tr>';
							echo '<td width="20%">Parcel Weight</td>';
							echo '<td class="form-input">';
							echo '<span>'.$data['weight'].'Kg</span>';
							echo '<select hidden class="weighting" hidden name="weight" />';
							for($i=0.5; $i<=20; $i+=0.5){ 
								$selected = ($i == $data['weight']) ? 'selected' : '';
								?>
								<option <?=$selected;?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
							<?php } 
							echo '<select/>';
							echo '</td>';
						echo '</tr>';
						echo '</tr>';
							echo '<tr>';
							echo '<td width="20%">Product Code</td>';
							echo '<td class="form-input">';
							echo '<span>'.$data['product_id'].'</span>';
							echo '<input hidden type="text" class="ins" name="product_id" value="'.$data['product_id'].'" />';
							echo '</td>';
						echo '</tr>';
						echo '</tr>';
							echo '<tr>';
							echo '<td width="20%">Product Description</td>';
							echo '<td class="form-input">';
							echo '<span>'.$data['product_desc'].'</span>';
							echo '<input hidden type="text" class="desc" name="product_desc" value="'.$data['product_desc'].'" />';
							echo '</td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td width="20%">Special Instruction</td>';
							echo '<td class="form-input">';
							echo '<span>'.$data['special_instruction'].'</span>';
							echo '<input hidden type="text" class="ins" name="special_instruction" value="'.$data['special_instruction'].'" />';
							echo '</td>';
						$pickup_zone_query = mysqli_query($con,"SELECT * FROM zones WHERE id=".$data['pickup_zone']." ");
						$pickup_zone_rec = mysqli_fetch_array($pickup_zone_query);
						echo '</tr>';
							echo '<tr>';
							echo '<td width="20%">Pickup Zone</td>';
							echo '<td class="">';
							echo '<span>'.$pickup_zone_rec['zone'].'</span>';
							echo '<input hidden type="text" class="ins" name="pickup_zone" value="'.$pickup_zone_rec['id'].'" />';
							echo '</td>';
						echo '</tr>';
						$delivery_zone_query = mysqli_query($con,"SELECT * FROM zones WHERE id=".$data['delivery_zone']." ");
						$delivery_zone_rec = mysqli_fetch_array($delivery_zone_query);
						echo '</tr>';
							echo '<tr>';
							echo '<td width="20%">Delivery Zone</td>';
							echo '<td class="">';
							echo '<span>'.$delivery_zone_rec['zone'].'</span>';
							echo '<input hidden type="text" class="ins" name="delivery_zone" value="'.$delivery_zone_rec['id'].'" />';
							echo '</td>';
						echo '</tr>';
						if($type != 'driver') {
							if($data['status']!='New Booked'){
								$qrrry="SELECT * FROM users WHERE id = '".$data['pickup_rider']."' ";
								$query33=mysqli_query($con,$qrrry) or die(mysqli_error($con));
								$fetch33=mysqli_fetch_array($query33);
								$qrrry1="SELECT * FROM users WHERE  type='driver' ";
								$query34=mysqli_query($con,$qrrry1) or die(mysqli_error($con));
								
								
								echo '<tr>';
									echo '<td width="20%">Pickup Rider</td>';
									echo '<td class="form-input">';
									echo '<span>'.$fetch33['Name'].'</span>';
									if($data['status'] =='assigned' || $data['status'] =='delivered'){
									echo '<select hidden class="assign_driver" hidden name="pickup_rider" />';
									while($rec = mysqli_fetch_array($query34)){
										if($rec['id'] == $data['pickup_rider']){
										echo "<option selected value=".$rec['id'].">".$rec['Name']."</option>";
									   }else{
									echo "<option value=".$rec['id'].">".$rec['Name']."</option>";
									   }
									}
							
							echo '<select/>';
						    }
									echo '</td>';
								echo '</tr>';

								$qrrry12="SELECT * FROM users WHERE  type='driver' ";
								$query123=mysqli_query($con,$qrrry12) or die(mysqli_error($con));
								

								$qrrry12="SELECT * FROM users WHERE id = '".$data['assign_driver']."' AND type='driver' ";
								$query35=mysqli_query($con,$qrrry12) or die(mysqli_error($con));
								$total = mysqli_num_rows($query35);
								$query35_rec = mysqli_fetch_array($query35);
								if($total == 1){
								echo '<tr>';
									echo '<td width="20%">Delivery Rider</td>';
									echo '<td class="form-input">';
									echo '<span>'.$query35_rec['Name'].'</span>';
									if($data['status'] =='assigned' || $data['status'] =='delivered'){
									echo '<select hidden class="assign_driver" hidden name="assign_driver" />';
									while($rec = mysqli_fetch_array($query123)){
										if($rec['id'] == $data['assign_driver']){
										echo "<option selected value=".$rec['id'].">".$rec['Name']."</option>";
									   }else{
									echo "<option value=".$rec['id'].">".$rec['Name']."</option>";
									   }
									}
							
							echo '<select/>';
						    }
									echo '</td>';
								echo '</tr>';
							   }
							}
						}
						if(isset($data['barcode_image']) && $data['barcode_image'] != '') {
							echo '<tr>';
								echo '<td width="20%">Barcode</td>';
								echo '<td class="form-input">';
								echo '<div style="width: 200px; text-align: center;">';
								echo '<span><img src="../'.$data['barcode_image'].'" /></span><br>';
								echo '<div>'.$data['barcode'].'</div>';
								echo '</div>';
								echo '</td>';
							echo '</tr>';
						}
						if(isset($data['reason']) && $data['reason'] != '') {
							echo '<tr>';
								echo '<td width="20%">Reason</td>';
								echo '<td class="form-input">';
								echo '<span>'.$data['reason'].'</span>';
								echo '</td>';
							echo '</tr>';
						}
						echo '<tr>';
							echo '<td width="20%">Status</td>';
							echo '<td class="form-input">';
							
							if($data['status']=='accepted'){
								$statusss='Assigned to Rider';
							}
							else if($userID == $deliverdriverID){
								if($data['status']=='in process'){
									$statusss='Pickup is done';
								}
								else if($data['status']=='accepted'){
									$statusss='Assigned to Rider';
								}
								else{
									$statusss=$data['status'];
								}
							}
							else{
								$statusss=$data['status'];
							}
							if(isset($data['is_returned']) && $data['is_returned'] == 1 && $data['status'] != 'returned') {
								$statusss .= ' (Returned)';
							}
							$status_reason = '';
							if($data['status_reason'] != ''){
								if($data['status'] == 'delivered'){
									if(!empty($data['status_reason'])){
										$status_reason = '- Received By ('.$data['status_reason'].')';
									}else{
										$status_reason = ' ('.$data['status_reason'].')';
									}
								
							    }else{
							    $status_reason = ' ('.$data['status_reason'].')';	
							    }
							}
							echo '<span >'.ucfirst($statusss).$status_reason.'</span>';
							// echo '<input hidden type="text" name="status" value="'.$data['status'].'" />';
							echo '</td>';
						echo '</tr>';
						?>
						<?php if($total_comments >0){ ?>
						<tr>
							<td width="20%">Order Comments</td>
							<td>
									<table class="table table-bordered">
										<thead>
											<tr>
												<th>Subject</th>
											    <th>Message</th>
											</tr>
										</thead> 
										<tbody> 
											<?php while($row = mysqli_fetch_array($message_query)){ ?>
											 
											<tr>
												<td><?php echo $row['subject']; ?></td>
												<td><?php echo $row['order_comment']; ?></td>
											</tr>
											<?php } ?>
										</tbody>
									</table>
								</td>
							</td>
						</tr>
						<?php
					}
						echo '<tr>';
							echo '<td width="20%">Actions</td>';
							echo '<td>';
							$iddd=encrypt($data['id']."-usUSMAN767###");
							echo '<a target="_blank" href="../invoicehtml.php?id='.$iddd.'">View Invoice</a>';
							echo '</td>';
						echo '</tr>';
						if($userID == $driverID){
							$query33=mysqli_query($con,"SELECT * from users where id='".$deliverdriverID."' ") or die(mysqli_error($con));
							$fetch33=mysqli_fetch_array($query33);
						echo '<tr>';
							echo '<td width="20%">Rider Name</td>';
							echo '<td class="form-input">';
							echo '<span >'.$fetch33['Name'].'</span>';
							echo '</td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td width="20%">Rider Email</td>';
							echo '<td class="form-input">';
							echo '<span >'.$fetch33['email'].'</span>';
							echo '</td>';
						echo '</tr>';							
						}
						if($userID == $deliverdriverID){
							$query33=mysqli_query($con,"SELECT * from users where id='".$driverID."' ") or die(mysqli_error($con));
							$fetch33=mysqli_fetch_array($query33);
						echo '<tr>';
							echo '<td width="20%">Pickup Driver Name</td>';
							echo '<td class="form-input">';
							echo '<span >'.$fetch33['Name'].'</span>';
							echo '</td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td width="20%">Pickup Driver Email</td>';
							echo '<td class="form-input">';
							echo '<span >'.$fetch33['email'].'</span>';
							echo '</td>';
						echo '</tr>';							
						}
						echo '<tr>';
							echo '<td hidden colspan="2" class="order-buttons">';
								echo '<input type="button" class="btn btn-default reset-form" value="Cancel" />';
								echo '<input type="submit" class="btn btn-success" name="update_order" value="Update" />';
							echo '</td>';
							echo '</form>';
							echo '<td colspan="2" class="other-actions">';
								
								if($type == 'driver') {
								
								} else {
									if($type == 'admin') {
										echo '<a href="#" class="btn btn-info edit-order pull-right">Edit</a>';
									}
									$ispickup = true;
									$ispickuprider = true;
									if($data['pickup_zone'] == '' || $data['delivery_zone'] == ''){
										$ispickup = false;
									}
									if($data['pickup_rider'] == ''){
										$ispickuprider = false;
									}
									if($data['is_received'] == 0 AND $ispickup == true AND $ispickuprider == true ){
										?>
										<a href="orderAction.php?order=<?php echo $id; ?>&stat=received" class="btn btn-success">Item Received</a>
										<a href="orderAction.php?order=<?php echo $id; ?>&stat=not_received" class="btn btn-primary">Item Not Received</a>
									<?php }

									if($data['is_shipped'] == 0 && $data['destination'] !='Islamabad' && ($data['status'] == 'received' ) ){
										?>
										<a href="orderAction.php?order=<?php echo $id; ?>&shipped=1" class="btn btn-success">Shipped to Outstation</a>
									<?php }
									$instation = true;
									if($data['status'] == 'received' && $data['destination'] != 'Islamabad' ){
										$instation = false;
									}
									if( ($data['status'] == 'received' || $data['status'] == 'dispatch' || $data['status'] =='New Booked') && $instation == true ){

											echo '<form style="display: inline-block;" action="orderAction.php" method="POST" >';
												 {
													?>
													<!-- <input type="hidden" name="from_return" value="1"> -->
													<?php
													?><input type="hidden" name="assign_driver" value="0_<?php echo $id; ?>"><?php
												}
												echo '<select style="width: auto; display: inline-block" name="dassign_driver" class="form-control">';
													echo '<option value="">Assign Delivery Rider</option>';
													$query=mysqli_query($con,"Select * from users where type='driver'");
													while($driver = mysqli_fetch_array($query)) {
														echo '<option value="'.$driver['id'].'">'.$driver['Name'].'</option>';
													}
												echo '</select>';
												echo '<input type="submit" name="assign" class="btn btn-success" value="Assign" />';
											echo '</form>';
										}
										if($status == 'assigned' || $status == 'accepted' || $status == 'returned'){
											?>
											<form method="POST" action="orderAction.php" novalidate>
												<input type="hidden" name="order_id" value="<?php echo $id; ?>">
												<div class="row">
													<div class="col-sm-3">
														<div class="form-group">
															<label>Status</label>
															<select class="form-control order_sts" name="order_status">
																<option selected disabled>Select Status</option>
																<option value="New Booked">New Booked</option>
																<option value="delivered">Delivered</option>
																<option value="returned">Returned</option>
															</select>
														</div>
													</div>
													<div class="col-sm-3 pending_reson_main" style="display: none;">
														<div class="form-group">
															<label>Pending Reason</label>
															<select class="form-control" name="pending_reason">
																<option selected disabled>Select Reason</option>
																<option>Bad weather</option>
																<option>Address incorrect</option>
																<option>Consignee not available</option>
																<option>Delivery address closed</option>
																<option>No such person</option>
																<option>Refused to receive</option>
																<option>Restricted area</option>
																<option>Incomplete Contact details</option>
																<option>Insufficient funds/ Payment not available</option>
																<option>Sealed delivery not accepted</option>
															</select>
														</div>
													</div>
													<div class="col-sm-3 returned_reson_main"  style="display: none;">
														<div class="form-group">
															<label>Return Reason</label>
															<select class="form-control" required="true" name="returned_reason">
																<option value="" selected disabled>Select Reason</option>
																<option>Mobile Powered Off</option>
																<option>Address Incorrect</option>
																<option>Refused</option>
																<option>Order By Mistake</option>
																<option>Bad Weather</option>
																<option>Don’t Have Money</option>
															</select>
														</div>
													</div>
													<div class="col-sm-3 received_by" style="display: none;">
														<label>Received By</label>
														<input type="text" name="received_by" class="form-control">
													</div>
													<div class="col-sm-3">
														<input style="margin-top:24px; display: none;" type="submit" name="update_status" class="btn btn-info update_status" value="Submit">
													</div>
												</div>
												
											</form>
										<?php }
									
									
								}
							
							echo '</td>';
						echo '</tr>';
					echo '</table>';
				echo '</div>';
			echo '</div>';
		} else {
			$ref = $_SERVER['HTTP_REFERER'];
			echo '<script>window.location.href="../admin/"</script>';
		}
		?>  
        </div>
        </div>
        <!-- Warper Ends Here (working area) -->
 <!-- Modal -->
<div id="signature_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
    <form action="orderAction.php" method="POST">
    	<input type="hidden" name="status">
    	<input type="hidden" name="deliver">
    	<input type="hidden" name="id">
    	<input type="hidden" name="driver">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Order Delivery</h4>
      </div>
      <div class="modal-body">
        <h3>Receiver Signature</h3>
        <div class="signature-pad">
            <input type="hidden" name="receiver_signature" class="value" value="">
            <div style="text-align: center; border: 7px solid #ccc; margin-bottom: 5px;" class="signature-form m-signature-pad--body">
              <canvas style="width: 500px; height: 200px;"></canvas>
            </div>   
        <a href="#" style="width: 100px;" class="btn btn-primary clear">Reset</a>
        </div>
        <h3>Driver Signature</h3>
        <div class="driver-signature-pad">
            <input type="hidden" name="driver_signature" class="value" value="">
            <div style="text-align: center; border: 7px solid #ccc; margin-bottom: 5px;" class="signature-form m-signature-pad--body">
              <canvas style="width: 500px; height: 200px;"></canvas>
            </div>   
        <a href="#" style="width: 100px;" class="btn btn-primary clear">Reset</a>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" name="submit_signature" value="Submit" />
      </div>
    </form>
    </div>
  </div>
</div>       
        
      <?php
	
	include "includes/footer.php";
	}
	else{
		header("location:index.php");
	}
	?>