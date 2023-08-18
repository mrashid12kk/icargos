
 <style type="text/css">
	.zones_main{
		margin-bottom: 20px;
	}
	.panel-default>.panel-heading {
    color: #fff !important;
    background-color: #0d0150 !important;
    border-color: #0d0150 !important;
}
.panel-default>.panel-heading a{
	font-weight: bold !important; 
}
</style>
<?php
$courier_query=mysqli_query($con,"Select * from users where type='driver'");
$customer_fetch_q = mysqli_query($con,"SELECT  cus.id as customer_id,cus.fname as business FROM orders o INNER JOIN customers cus ON (o.customer_id = cus.id) WHERE o.status='New Booked' GROUP BY cus.id ");

 ?>
<div class="panel panel-default">
 
	<div class="panel-heading" ><?php echo getLange('returnrunsheet'); ?></div>

		<div class="panel-body" id="same_form_layout">

	<div class="col-sm-10">
		<div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
			<div class="row">
				<?php
				if(isset($_SESSION['succ_msg']) && !empty($_SESSION['succ_msg'])){
					$msg = $_SESSION['succ_msg'];
					echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong>'.$msg.'</div>';
					unset($_SESSION['succ_msg']);
				} 
				 ?>
				
		<div class="row">
					
			<div class="col-sm-12 table-responsive gap-none">
					
		    <textarea autofocus="true" class="form-control delivery_run" rows="8" placeholder="Please enter order ids"></textarea>
		    <div class="help-info orders-count" style="float: right;font-size: 12px;color: #999;"><?php echo getLange('ordercount'); ?></div>
				</div>
						

			</div>

		</div>
		<div class="row">
			<form method="POST" action="bulk_return_assign.php" id="bulk_delivery_submit">
				    			
								<div class="col-sm-4 left_right_none" >
									<div class="form-group">
										<label><?php echo getLange('assignreturnrider'); ?></label>
										<select class="form-control courier_list" name="active_courier">
											<option selected value="">Select Return Rider</option>
											<?php while($row=mysqli_fetch_array($courier_query)){ ?>
											<option value="<?php echo $row['id']; ?>"><?php echo $row['Name']; ?></option>
										<?php } ?>
										</select>
									</div>
								</div>
				    		<input type="hidden" name="order_ids" id="print_data">
				    		<div class="col-sm-1 left_right_none upate_Btn">
				    			<a href="#" class="update_status btn btn-success" style="margin-top: 10px;"><?php echo getLange('assign'); ?></a>
				    			
				    		</div>

				    		</form>
		</div>

	</div>
	</div>

</div>
 