<?php
	$msg="";
	if(isset($_POST['save_status'])){
		
		$all_staus = implode(',', $_POST['allowed_status']);

			$query1=mysqli_query($con,"INSERT INTO `order_status`(`status`, `color_code`, `sort_num`, `allowed_status`) VALUES ('".$_POST['status_name']."','".$_POST['color_code']."',".$_POST['sort_num'].",'".$all_staus."')") or die(mysqli_error($con));
			
			$rowscount=mysqli_affected_rows($con);
			if($rowscount>0){
				$msg= '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you added a status successfully</div>';

				}
			else{

				$msg= '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not added a new status unsuccessfully.</div>';

			}

			$_SERVER['HTTP_REFERER'];;
	}

	echo $msg;
?>

<style type="text/css">

 		.label {

		    display: inline;

		    padding: .2em .6em .3em;

		    font-size: 100%;

		    font-weight: bold;

		    line-height: 1;

		    color: #fff;

		    text-align: center;

		    white-space: nowrap;

		    vertical-align: baseline;

		    border-radius: .25em;

		    float: left;

		    margin: 2px;

		    width: 100%;

		}

		.city_dropdown {

		    max-height: 186px;

		    overflow-y: auto;

		    overflow-x: hidden;

		    min-height: auto;

		}

		.page-header {

		    display: none;

		}

		.select2-container--default.select2-container--focus .select2-selection--multiple {

		    border: solid #e3e3e3 1px;

		}

		.select2-container--default .select2-selection--multiple {

		    border: 1px solid #d0d0d0;

		}

</style>

<?php 
	$orderQ = mysqli_query($con,"SELECT * from order_status order by sort_num ASC");
	$stQuery = mysqli_query($con,"SELECT * FROM order_status");
	$statusRow= mysqli_fetch_assoc($stQuery);
	// print_r($statusRow['allowed_status']);
	// die();

	$allStatus= mysqli_query($con,"SELECT * from order_status");

	function checkStatusName($id)
	{
		global $con;
		$order_ids = explode(',', $id);
		$allNames = '';
		foreach ($order_ids as $key => $value) {
			$namq = mysqli_query($con, "SELECT status FROM order_status where sts_id = ".$value);

			$nameGet = mysqli_fetch_assoc($namq);
			$allNames .= $nameGet['status'].', ';
		}
			return $allNames;
	}
 ?>


 <div class="row">
    <?php
        require_once "setup-sidebar.php";
      ?>
      <div class="col-sm-10 table-responsive" id="setting_box">
      	<div class="panel panel-default">
	<div class="panel-heading"><?php echo getLange('addorderstatus'); ?></div>
	<div class="panel-body">
		<form role="form" action="#" method="POST">
			<div id="cities"> 	

				<div class="row">
					<div class="col-md-4 side_gapp">
						<div class="form-group">
							<label  class="control-label"><?php echo getLange('statusname') ?></label>
							<input type="hidden" name="status_id" >
							<input type="text" class="form-control" placeholder="<?php echo getLange('statusname') ?>" name="status_name" required>
						</div>
					</div>
					<div class="col-md-4 side_gapp">
						<div class="form-group">
							<label  class="control-label"><?php echo getLange('colorcode'); ?></label>
							<input type="color" class="form-control" placeholder="<?php echo getLange('colorcode'); ?>" name="color_code" required>
							<div class="help-block with-errors "></div>
						</div>
					</div>
					<div class="col-md-4 side_gapp">
						<div class="form-group">
							<label  class="control-label"><?php echo getLange('sortingno'); ?></label>
							<input type="text" class="form-control" placeholder="<?php echo getLange('sortingno'); ?>" name="sort_num">
							<div class="help-block with-errors "></div>
						</div>
					</div>
					<!-- <div class="col-md-3 side_gapp">
						<div class="form-group">
							<label  class="control-label">SMS Template Id</label>
							<input type="text" placeholder="SMS Template Id" name="sms_template_id" class="form-control">
							<div class="help-block with-errors "></div>
						</div>
					</div> -->
				</div>
				<div class="row">
					<div class="col-md-12 side_gapp">
						<div class="form-group">
							<label  class="control-label"><?php echo getLange('allowedstatus'); ?></label>
							<!-- <select class="js-example-basic-multiple" name="allowed_status[]" multiple="multiple">
					        	<?php if (isset($statusRow['allowed_status']) && !empty($statusRow['allowed_status'])): ?>
					        	<?php $allowed_statuses = explode(',', $statusRow['allowed_status']); ?>
								<?php foreach ($statusRow['allowed_status'] as $key => $value) {
									$indexxx = array_search($value['sts_id'], $allowed_statuses);
									if(isset($indexxx)  )
									{
										$val_id = $allowed_statuses[$indexxx];
									}
								?>	
									<option value="<?php echo $value['sts_id'] ?>"   <?php if (isset($val_id) and $val_id == $value['sts_id']): ?> selected <?php endif ?>  ><?php echo $value['status']; ?></option>

									<?php

								} ?>

								<?php endif; ?>
							</select> -->
							<select class="js-example-basic-multiple" name="allowed_status[]" multiple="multiple" required>
								<option disabled><?php echo getLange('select'); ?></option>
								<?php while ($allS = mysqli_fetch_array($allStatus)) { ?>
									<option value="<?php echo $allS['sts_id']; ?>"><?php echo $allS['status']; ?></option>
								<?php } ?>
					       		
							</select>
							<div class="help-block with-errors "></div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4 side_gapp save_print_btn rtl_full">
						<input type="submit" name="save_status" class="btn btn-purple submit_btns" value="<?php echo getLange('save'); ?>" >
					</div>
				</div>
<br>

				<div class="inner_contents table-responsive" >
				   <table cellpadding="0" cellspacing="0" border="0" class="table table_box table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info">
				   	<thead>
				   		<tr>
				            <th><?php echo getLange('name'); ?></th>
				            <th><?php echo getLange('colorcode'); ?></th>
				            <th><?php echo getLange('sort'); ?> #</th>
				            <th><?php echo getLange('allowedstatus'); ?></th>
				            <th style="width: 45px;"><?php echo getLange('pickup'); ?></th>
				            <th style="width: 45px;"><?php echo getLange('delivery'); ?></th>
				            <th style="width: 102px;"><?php echo getLange('vendorstatus'); ?></th>
				            <th style="width: 111px;"><?php echo getLange('paymentstatus'); ?></th>
				            <th style="width: 45px;"><?php echo getLange('active'); ?></th>
				            <!-- <th  style="text-align: center;">Action</th> -->
				         </tr>
				   	</thead>
				      <tbody>
				      		<?php 
				      		while ($row = mysqli_fetch_assoc($orderQ)) { ?>
				      			<tr>

				         	<td><?php echo $row['status'] ?></td>

				         	<td style="text-align: center;"> <span style="width: 20px; height: 20px; background:<?php echo $row['color_code'] ?>;display: inline-block; margin: 7px auto 0; border-radius: 100%;"></span></td>

				         	<td><?php echo $row['sort_num'] ?></td>

				         	<td><?php echo checkStatusName($row['allowed_status']); ?></td>

				         	<td><input data-id="<?php echo $row['sts_id']; ?>" class="pick_rider_check" <?php if($row['pickup_rider']==1): {echo "checked";}?> <?php endif; ?> type="checkbox" name=""></td>

				         	<td><input data-id="<?php echo $row['sts_id']; ?>" class="delivery_rider_check" <?php if($row['delivery_rider']==1): {echo "checked";}?> <?php endif; ?> type="checkbox" name=""></td>

				         	<td><input data-id="<?php echo $row['sts_id']; ?>" <?php if($row['vendor_status']==1): {echo "checked";}?> <?php endif; ?> type="checkbox" name=""></td>

				         	<td><input <?php if($row['payment_status']==1): {echo "checked";}?> <?php endif; ?> type="checkbox" name=""></td>

				         	<td><input <?php if($row['active']==1): {echo "checked";}?> <?php endif; ?> type="checkbox" name=""></td>

				         	<!-- <td style="text-align: center;">

				         		<a href="#"><i class="fa fa-edit"></i></a>

				         		<a href="#"><i class="fa fa-trash"></i></a>

				         	</td> -->

				         </tr>

				         <?php
				      		}


				      		 ?>
				         

				      </tbody>



				   </table>

				</div>

			</div>

			

			<br>

			

		</form>

	</div>

</div>
  </div>

