
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
$courier_query=mysqli_query($con,"Select * from users where type='driver'");
$customer_fetch_q = mysqli_query($con,"SELECT  cus.id as customer_id,cus.fname as business FROM orders o INNER JOIN customers cus ON (o.customer_id = cus.id) WHERE o.status='New Booked' GROUP BY cus.id ");
$status_query=mysqli_query($con,"Select * from order_status where active='1'  and pickup_rider = 1 order by sort_num ");

$reasons_list = mysqli_query($con,"Select * from order_reason where active='1' ");
?>
<div class="panel panel-default">

	<div class="panel-heading" >Pickup Order Processing</div>

	<div class="panel-body" id="same_form_layout">

		<div class="col-sm-12">
			<div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
				<div class="row">
					<?php
					if(isset($_SESSION['succ_msg']) && !empty($_SESSION['succ_msg'])){
						$msg = $_SESSION['succ_msg'];
						echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong>'.$msg.'</div>';
						unset($_SESSION['succ_msg']);
					}
				 	?>

				 	<?php
						if(isset($_SESSION['error_msg']) && !empty($_SESSION['error_msg'])){
							$msg = $_SESSION['error_msg'];
							echo '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">X</button><strong>Error!</strong>'.$msg.'</div>';
							unset($_SESSION['error_msg']);
						}
				 	?>
					<div class="row">
						<div class="col-sm-6 table-responsive gap-none">
						    <textarea autofocus="true" class="form-control status_update_run_for_rider" rows="8" placeholder="Please enter order ids"></textarea>
						    <div class="help-info orders-count" style="float: right;font-size: 12px;color: #999;">Orders Count: 0</div>

						    <form method="POST" action="bulk_status_assign.php" id="bulk_status_assign">

						    	<div class="col-sm-4 left_right_none enable_reason" style="display: none;" >
									<div class="form-group">
										<label>Reason</label>
										<input type="hidden" name="reason_enable" class="reason_enable" value="">
										<select class="form-control reason_list js-example-basic-single" name="reason_list">
											<option selected value="">Select reason</option>
											<?php while($row=mysqli_fetch_array($reasons_list)){ ?>
												<option value="<?php echo $row['id']; ?>"  data-valuestat="<?php echo $row['reason_desc']; ?>"  ><?php echo getKeyWord($row['reason_desc']); ?></option>
											<?php } ?>
										</select>
									</div>
								</div>


								<div class="col-sm-6 left_right_none" >
									<div class="form-group">

										<?php while($row=mysqli_fetch_array($status_query)){ ?>
											<a href="#" data-reasonenable="0"  data-value="<?php echo $row['status']; ?>" class="update_status btn btn-success " style="margin-top: 23px;background-color: <?php echo $row['color_code']; ?>  !important;   "><?php echo getKeyWord($row['status']); ?></a>

										<?php } ?>

									</div>
								</div>
					    		<input type="hidden" name="status_value" class="status_value">
					    		<input type="hidden" name="order_ids" id="print_data">
					    		<input type="hidden" name="is_for" value="pickup_rid">
					    		<input type="hidden" name="users_id" class="customer_id_rider" value="<?php echo $_SESSION['users_id'] ?>">
					    		<!-- <div class="col-sm-2 left_right_none upate_Btn">
					    			<a href="#" class="update_status btn btn-success" style="margin-top: 10px;">Update</a>
					    		</div>  -->
				    		</form>
						</div>
						<div class="col-md-6">
							<div class="order_logs" style="border: 1px solid #ccc; min-height: 355px; ">
								<ul id="order_sts_lg">
								</ul>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>

	</div>
</div>

<script type="text/javascript">
	document.addEventListener('DOMContentLoaded', function(){
		$(document).on('click','.update_status',function(){
			if($(this).attr('data-reasonenable') == 1)
			{
				$('.enable_reason').show();
			} else {
				$('.enable_reason').hide();
			}
		})
	}, false);
</script>
