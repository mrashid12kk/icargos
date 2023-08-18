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
$msg='';
	// if(isset($_POST['updateweight'])){
	// 		$track_no=$_POST['track_no'];
	// 		$weight=$_POST['weight'];
	// 		$delivery_charges=$_POST['delivery_charges'];
	// 		$pft_amount=$_POST['pft_amount'];
	// 		$inc_amount=$_POST['inc_amount'];
	// 		$query=mysqli_query($con,"update orders set weight='".$weight."',price='".$delivery_charges."',pft_amount='".$pft_amount."',inc_amount='".$inc_amount."' where track_no='".$track_no."'")or die(mysqli_error($con));
	// 		if(mysqli_affected_rows($con)>0){
	// 			$msg="<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>X</button><strong>Well done!</strong> Weight of This track_no '".$track_no."' Is Updated Successfully </div>";
	// 		}
	// 		else{
	// 			$msg="<div class='alert alert-danger'><button type='button' class='close' data-dismiss='alert'>X</button><strong>!</strong> Weight of This Track_no '".$track_no."' Is Not Updated Successfully </div>";
	// 		}
	// 	}
$current_branch = $_SESSION['branch_id'];
if (!isset($_SESSION['branch_id'])) {
	$current_branch = 1;
}
$branch_query=mysqli_query($con,"Select * from branches where id !=".$current_branch);
$courier_query=mysqli_query($con,"Select * from users where type='driver'");
$customer_fetch_q = mysqli_query($con,"SELECT  cus.id as customer_id,cus.fname as business FROM orders o INNER JOIN customers cus ON (o.customer_id = cus.id) WHERE o.status='New Booked' GROUP BY cus.id ");
$status_query=mysqli_query($con,"Select * from order_status where active='1' and hide_from_listing = '0' order by sort_num");

$reasons_list = mysqli_query($con,"Select * from order_reason where active='1' ");
$courier_query = mysqli_query($con,"Select * from users where  user_role_id = 3 or user_role_id = 4  AND $check_branch  ");
$gst_query = mysqli_query($con,"SELECT value FROM config WHERE `name`='gst' ");
$total_gst = mysqli_fetch_array($gst_query);
 ?>
<div class="panel panel-default">

	<div class="panel-heading" ><?php echo getLange('orderprocessing'); ?> </div>

	<div class="panel-body" id="same_form_layout">

		<div class="col-sm-12">
			<div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
				<div class="row" >
					<div id="msg"></div>
					<?php
						echo $msg;
					if(isset($_SESSION['succ_msg']) && !empty($_SESSION['succ_msg'])){
						$msg = $_SESSION['succ_msg'];
						echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> '.$msg.'</div>';
						unset($_SESSION['succ_msg']);
					}
				 	?>


				 	<?php
					if(isset($_SESSION['error_msg']) && !empty($_SESSION['error_msg'])){
						$msg = $_SESSION['error_msg'];
						echo '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">X</button><strong>Error !</strong> '.$msg.'</div>';
						unset($_SESSION['error_msg']);
					}
				 	?>
						<!-- Modal -->
<div class="modal fade modal" id="exampleModal"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><center><?php echo getLange('update').' '.getLange('data'); ?> </center></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" id="change_weight_from">
      	<input type="hidden" name="" class="total_gst" value="<?php echo isset($total_gst['value']) ? $total_gst['value'] : '0'; ?>">
      <div class="modal-body">
      	<label><?php echo getLange('weight'); ?></label>
        <input type="text" name="weight" class="edituserweight weight form-control">
        <input type="hidden" name="track_no" class="track_no editusertrackno" value="">
        <input type="hidden" name="status" class="status" value="">
        <input type="hidden" name="" class="total_gst" value="<?php echo isset($total_gst['value']) ? $total_gst['value'] : '0'; ?>">
        <div class="list hidden"></div>
         <div class="viewcharges hidden">
      <label><?php echo getLange('deliveycharges'); ?> </label>
       <input type="text" name="delivery_charges" class="total_amount delivery_charges form-control" readonly>
       <label><?php echo getLange('salestax'); ?> </label>
        <input type="text" name="pft_amount" class="pft_amount form-control" readonly>
        <label><?php echo getLange('totalservicescharges'); ?></label>
         <input type="text" name="inc_amount" class="inc_amount form-control" readonly>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo getLange('close'); ?></button>
        <input type="submit" class="btn btn-primary" id="change_weight" name="updateweight" value="<?php echo getLange('submit'); ?>">
      </div>
       </form>
    </div>
  </div>
</div>


					<div class="row">

						<div class="col-sm-6 table-responsive gap-none">

						    <textarea autofocus="true" class="form-control status_update_run" rows="8" placeholder="<?php echo getLange('please').' '.getLange('enter').''.getLange('orderid'); ?>"><?php if (isset($_SESSION['old_orders_list']) and !empty($_SESSION['old_orders_list'])){ echo $_SESSION['old_orders_list']; } ?></textarea>
						    <div class="help-info orders-count" style="float: right;font-size: 12px;color: #999;"><?php echo getLange('ordercount'); ?></div>

						    <?php
						    	if (isset($_SESSION['old_orders_list']) and !empty($_SESSION['old_orders_list']))
						    	{
						    		unset($_SESSION['old_orders_list']);
						    	}
						    ?>
						    <form method="POST" action="bulk_status_assign.php" id="bulk_status_assign" style="clear: both;">
						    	<div class="row">
								<div class="col-sm-6 left_right_none" >
									<div class="form-group">
										<label><?php echo getLange('orderstatus'); ?> </label>
										<select class="form-control status_list js-example-basic-single" name="order_status">
											<option selected value=""><?php echo getLange('select').' '.getLange('status'); ?></option>
											<?php while($row=mysqli_fetch_array($status_query)){ ?>
											<option  data-reasonenable="<?php echo $row['reason_id']; ?>"   value="<?php echo $row['status']; ?>"><?php echo getKeyWord($row['status']); ?></option>
										<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-md-4 receive hidden" >
									<label><?php echo getLange('receiverpersonname'); ?> </label>
									<input type="text" name="received_by" class="form-control " value="Self" >
								</div>
								<div class="col-md-4 branch_to hidden" >
									<label><?php echo getLange('assign').' '.getLange('branch'); ?> </label>
									<select class="form-control js-example-basic-single" name="assign_branch">
										<option selected disabled><?php echo getLange('select').' '.getLange('branch'); ?></option>
										<?php while($row=mysqli_fetch_array($branch_query)){ ?>
										<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
									<?php } ?>
									</select>
								</div>
								<div class="col-sm-4 left_right_none rider_assign hidden" >
									<div class="form-group">
										<label><?php echo getLange('rider').' '.getLange('vender'); ?> </label>
										<select class="form-control courier_list js-example-basic-single" name="active_courier">
											<option selected disabled><?php echo getLange('selectdelivery').' ('.getLange('rider').' '.getLange('vender').')'; ?></option>
											<?php mysqli_data_seek($courier_query,0); while($row=mysqli_fetch_array($courier_query)){ ?>
											<option value="<?php echo $row['id']; ?>"><?php echo $row['Name']; ?></option>
										<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-sm-4 left_right_none enable_reason" style="display: none;" >
									<div class="form-group">
										<label><?php echo getLange('reason'); ?></label>
										<input type="hidden" name="reason_enable" class="reason_enable" value="">
										<select class="form-control reason_list js-example-basic-single" name="reason_list">
											<option selected value=""><?php echo getLange('select').' '.getLange('reason'); ?></option>
											<?php while($row=mysqli_fetch_array($reasons_list)){ ?>
												<option value="<?php echo $row['id']; ?>"  data-valuestat="<?php echo $row['reason_desc']; ?>"  ><?php echo getKeyWord($row['reason_desc']); ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								</div>
				    			<input type="hidden" name="order_ids" id="print_data">
					    		<div class="col-sm-2 left_right_none upate_Btn">
					    			<a href="#" class="update_status btn btn-success" style="margin-top: 7px;"><?php echo getLange('update'); ?></a>
					    		</div>
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

<script type="text/javascript">
	document.addEventListener('DOMContentLoaded', function(){
		$(document).on('change','.status_list',function(){
			if($(this).find(':selected').attr('data-reasonenable') == 1)
			{
				$('.enable_reason').show();
				$('.receive').removeClass('hidden');
			} else {
				$('.enable_reason').hide();
				$('.receive').addClass('hidden');
			}
		})
	}, false);
	document.addEventListener('DOMContentLoaded', function(){
		$(document).on('change','.status_list',function(){
			var id=$(this).val();
			if(id == 'Delivered'){
			$('.receive').removeClass('hidden');
			$('.branch_to').addClass('hidden');
			$('.rider_assign').addClass('hidden');
			// $('.receive').show();
		}
		else if(id==='Parcel in Transit to Destination')
		{
			$('.branch_to').removeClass('hidden');
			$('.rider_assign').addClass('hidden');
			$('.receive').addClass('hidden');
		}
		else if(id==='Returned to origin city'){
			$('.branch_to').removeClass('hidden');
			$('.rider_assign').addClass('hidden');
			$('.receive').addClass('hidden');
		}
		else if(id==='Out for Delivery')
		{
			$('.rider_assign').removeClass('hidden');
			$('.branch_to').addClass('hidden');
			$('.receive').addClass('hidden');
		}
		else
		{
			$('.receive').addClass('hidden');
			$('.branch_to').addClass('hidden');
			$('.rider_assign').addClass('hidden');
		}
		})
	}, false);
		document.addEventListener('DOMContentLoaded', function(){
		$(document).on('click','.edit_weight',function(){
			var weight=$(this).attr("data-id");
			var track_no=$(this).attr('data-trackno');
			var status=$('.data-status').html();
			console.log(status);
			$(".edituserweight").val(weight);
			$(".editusertrackno").val(track_no);
			$(".status").val(status);
		})
	}, false);
	document.addEventListener('DOMContentLoaded', function(){
		$(document).on('keyup','.weight',function(){
			var weight=$(this).val();
		if(weight == ''){
			$('.viewcharges').addClass('hidden');
			// $('.receive').show();
		}
		else{
				$('.viewcharges').removeClass('hidden');
		}
		})
	}, false);
</script>
