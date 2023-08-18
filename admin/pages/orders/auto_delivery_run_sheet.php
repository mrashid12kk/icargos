
 <style type="text/css">
	.zones_main{
		margin-bottom: 20px;
	}
	.panel-default>.panel-heading {
    color: #333 !important;
    background-color: #f5f5f5 !important;
    border-color: #ddd !important;
    
}
.panel-default>.panel-heading a{
	font-weight: bold !important; 
}
</style>
<?php
$courier_query = mysqli_query($con,"Select * from users where  user_role_id = 3 or user_role_id = 4    ");
$zone_query    = mysqli_query($con,"Select * from zone where 1");

$destination_zone_q = mysqli_query($con," SELECT DISTINCT destination FROM zone_cities WHERE 1 ");
$destination = '';
$destination_q = '';
$customer_q = '';
if(isset($_GET['destination']) && !empty($_GET['destination'])){
	$destination = $_GET['destination'];
	$destination_q = ' AND destination = "'.$destination.'" ';
	$customer_q =  ' AND o.destination = "'.$destination.'" ';
}
$customer_fetch_q = mysqli_query($con,"SELECT  cus.id as customer_id,cus.fname as business FROM orders o INNER JOIN customers cus ON (o.customer_id = cus.id) WHERE o.status='Parcel Received at office' and o.delivery_assignment_no IS NULL  $customer_q GROUP BY cus.id ");
 ?>
<div class="panel panel-default">

	<div class="panel-heading" >Delivery Run Sheet</div>

		<div class="panel-body" id="same_form_layout">

	<div class="col-sm-12">
		<div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
			<div class="row">
				<form method="POST" action="bulk_delivery_assign.php" id="bulk_submit">
	    			<div class="col-sm-4 left_right_none" >
						<div class="form-group">
							<label>Select destination</label>
							<select onchange="window.location.href='delivery_run_sheet.php?destination='+this.value" class="form-control courier_list js-example-basic-single" name="active_zone">
								<option selected disabled>Select destination</option>
								<?php while($row=mysqli_fetch_array($destination_zone_q)){ ?>
								<option <?php if($row['destination'] == $destination){ echo "selected"; } ?> value="<?php echo $row['destination']; ?>"><?php echo $row['destination']; ?></option>
							<?php } ?>
							</select>
						</div>
					</div>
					<div class="col-sm-4 left_right_none" >
						<div class="form-group">
							<label>Assign delivery (Rider/Vendor) </label>
							<select class="form-control courier_list" name="active_courier">
								<option selected disabled>Select delivery (Rider/Vendor)</option>
								<?php while($row=mysqli_fetch_array($courier_query)){ ?>
								<option value="<?php echo $row['id']; ?>"><?php echo $row['Name']; ?></option>
							<?php } ?>
							</select>
						</div>
					</div>
		    		<input type="hidden" name="order_ids" id="print_data">
		    		<div class="col-sm-1 left_right_none upate_Btn">
		    			<a href="#" class="update_status btn btn-success" style="margin-top: 10px;">Assign</a> 
		    		</div> 
	    		</form>
				<div class="row"> 
					<div class="col-sm-12 table-responsive gap-none"> 
					    <div class="panel-group" id="faqAccordion">
					    	<div class="row">
				    			<div class="col-sm-2">
				    				<label><input type="checkbox" class="select_all" name=""> Select all orders</label>
				    			</div>
				    		</div>
					    	<?php 
					    	$row_start = 0;
					    	while($single = mysqli_fetch_array($customer_fetch_q))
					    	{
					    		$customer_id = $single['customer_id'];

					    		$order_query = mysqli_query($con,"SELECT id,rname,receiver_address,track_no,rphone FROM orders WHERE customer_id='".$customer_id."' AND status='Parcel Received at office' $destination_q  ORDER BY id DESC ");
					    	 ?>
					        <div class="panel panel-default ">
					            <div class="panel-heading "  data-target="#question<?php echo $row_start; ?>">
					                 <h4 class="panel-title">
					                    <a href="#" class="ing"><?php echo $single['business']; ?></a>
					              </h4>
					            </div>
					            <!-- <div id="question<?php echo $row_start; ?>" class="panel-collapse collapse" style="height: 0px;"> -->
					                <div class="panel-body">
					                     <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer pickup_tbl" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info">
					                     	<thead>
					                     		<tr>
					                     			<th style="width: 20px;"><input type="checkbox" name="" class="main_select"></th>
					                     			<th style="width: 100px;">Tracking</th>
					                     			<th style="width: 100px;">Receiver Name</th>
					                     			<th style="width: 100px;">Receiver Phone</th>
					                     			<th style="width: 265px;">Receiver Address</th>
					                     		</tr>
					                     	</thead>
					                     	<tbody>
					                     		<?php while($order_row = mysqli_fetch_array($order_query)){ ?>
					                     		<tr>
					                     			<td><input type="checkbox" class="order_check" data-id="<?php echo $order_row['track_no']; ?>" name=""></td>
					                   				<td><?php echo $order_row['track_no']; ?></td>
					                   				<td><?php echo $order_row['rname']; ?></td>
					                   				<td><?php echo $order_row['rphone']; ?></td>
					                   				<td><?php echo $order_row['receiver_address']; ?></td>
					                   				
					                     		</tr>
					                     	<?php } ?>
					                     	</tbody>
					                     </table>
					                </div>
					            <!-- </div> -->
					        </div>
					       <?php $row_start++; } ?> 
					    </div> 
					</div> 
				</div> 
			</div> 
		</div>
	</div> 
</div>
 