<?php

	session_start();

	require 'includes/conn.php';

	if(isset($_SESSION['users_id']) && $_SESSION['type']=='admin'){

	include "includes/header.php";

	$origincitydata=mysqli_query($con,"Select * from cities order by city_name");

	$destcitydata=mysqli_query($con,"Select * from cities order by city_name");



	$riderdata=mysqli_query($con,"Select * from users WHERE type='driver' ");

	$servicetypes=mysqli_query($con,"Select * from services WHERE 1 ");

?>

<body data-ng-app>

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

.select2-container--default.select2-container--focus .select2-selection--multiple {

		    border: solid #cccccceb 1px;

		}

		.select2-container--default .select2-selection--multiple {

		    border: 1px solid #d0d0d0;

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

        

        

        <div class="warper container-fluid">

        	

            <div class="page-header"><h1><?php echo getLange('dashboard'); ?> <small><?php echo getLange('letsgetquick'); ?></small></h1></div>

            



<?php 

	

	$status_id = $_POST['status_id'];



	$status_query = "SELECT * from order_status Where sts_id=$status_id";



	$allStatus = mysqli_query($con,$status_query);



	$statusRow = mysqli_fetch_assoc($allStatus);

	// print_r($statusRow);

	// die();



	$statuses = mysqli_query($con,"SELECT * FROM order_status");



	function getStatusName($s_id=null)

	{

		global $con;



		$squery = "SELECT * from order_status Where sts_id=$s_id";



		$allS = mysqli_query($con,$squery);



		// echo $s_id;

		$staRow = mysqli_fetch_assoc($allS);



		return  $staRow['status'];

	}





 ?>



 <?php

	$msg="";

	if(isset($_POST['update_status'])){

		// echo "<pre>";

		// print_r($_POST);

		// die();

		$status_id = $_POST['status_id'];

		$allowed_statuses = implode(',', $_POST['allowed_status']);

		$sms_temlate_id = isset($_POST['sms_temlate_id']) ? $_POST['sms_temlate_id'] : null;

		$update_query = "UPDATE `order_status` SET `status`='".$_POST['status_name']."', `color_code`='".$_POST['color_code']."',`sort_num`=".$_POST['sort_num'].",`active`=".$_POST['active'].",`allowed_status`='".$allowed_statuses."',`pickup_rider`=".$_POST['pickup_rider'].",`delivery_rider`=".$_POST['delivery_rider'].",`vendor_status`=".$_POST['vendor_status']." WHERE sts_id=".$status_id."";

		// echo $update_query;

		// die();

		$query1=mysqli_query($con,$update_query);

		



		$rowscount=mysqli_affected_rows($con);



		if($rowscount > 0){

			$msg= '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you updated the status successfully</div>';

			}

		else{

			$msg= '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not updated the status successfully.</div>';

		}

		header("location:order_status.php");

	}

echo $msg;

?>







<div class="panel panel-default">

	<div class="panel-heading">Order Status</div>

	<div class="panel-body" id="same_form_layout">

	

		<form role="form" action="#" method="POST">

			<div id="cities"> 

		

				<div class="row">

					<div class="col-md-4">

						<div class="form-group">

							<label  class="control-label">Status Name</label>

							<input type="hidden" name="status_id" value="<?php echo $_POST['status_id']; ?>">

							<input type="text" class="form-control" name="status_name" value="<?php echo $statusRow['status'] ?>">

						</div>

					</div>

					<div class="col-md-4">

						<div class="form-group">

							<label  class="control-label">Color Code</label>

							<input type="text" class="form-control" name="color_code" value="<?php echo $statusRow['color_code'] ?>" required>

							<div class="help-block with-errors "></div>

						</div>

					</div>

					<div class="col-md-4">

						<div class="form-group">

							<label  class="control-label">Font Name</label>

							<input type="text" class="form-control" name="font_name" placeholder="Font Name" value="<?php echo $statusRow['font_name'] ?>">

							<div class="help-block with-errors "></div>

						</div>

					</div>

					<div class="col-md-4">

						<div class="form-group">

							<label  class="control-label">Sort #</label>

							<input type="text" class="form-control" name="sort_num" value="<?php echo $statusRow['sort_num'] ?>" required>

							<div class="help-block with-errors "></div>

						</div>

					</div>

					<div class="col-md-4">

						<div class="form-group">

							<label  class="control-label">Active</label>

							<select class="form-control" name="active">

								<option <?php if(isset($statusRow['active']) && $statusRow['active'] == '1'){ echo "Selected"; } ?> value="1">Active</option>

								<option <?php if(isset($statusRow['active']) && $statusRow['active'] == '0'){ echo "Selected"; } ?> value="0">InActive</option>

							</select>

							<div class="help-block with-errors "></div>

						</div>

					</div>

					

					<div class="col-md-4">

						<div class="form-group">

							<label  class="control-label">Pickup Rider</label>

							<select class="form-control" name="pickup_rider">

								<option <?php if(isset($statusRow['pickup_rider']) && $statusRow['pickup_rider'] == '1'){ echo "Selected"; } ?> value="1">Yes</option>

								<option <?php if(isset($statusRow['pickup_rider']) && $statusRow['pickup_rider'] == '0'){ echo "Selected"; } ?> value="0">No</option>

							</select>

							<div class="help-block with-errors "></div>

						</div>

					</div>

					<div class="col-md-4">

						<div class="form-group">

							<label  class="control-label">Delivery Rider</label>

							<select class="form-control" name="delivery_rider">

								<option <?php if(isset($statusRow['delivery_rider']) && $statusRow['delivery_rider'] == '1'){ echo "Selected"; } ?> value="1">Yes</option>

								<option <?php if(isset($statusRow['delivery_rider']) && $statusRow['delivery_rider'] == '0'){ echo "Selected"; } ?> value="0">No</option>

							</select>

							<div class="help-block with-errors "></div>

						</div>

					</div>

					<div class="col-md-4">

						<div class="form-group">

							<label  class="control-label">Vendor Status</label>

							<select class="form-control active_customer_detail js-example-basic-single" name="vendor_status">

									<option selected value="0">Select status</option>

								<?php foreach($statuses as $status){ ?>

								<option <?php if(isset($statusRow['vendor_status']) && $statusRow['vendor_status'] == $status['sts_id']){ echo "Selected"; } ?> value="<?php echo $status['sts_id']; ?>"><?php echo $status['status']; ?></option>

								<?php } ?>

							</select>

							<div class="help-block with-errors "></div>

						</div>

					</div>

					<div class="col-md-4">

						<div class="form-group">

							<label  class="control-label">Payment Status</label>

							<select class="form-control active_customer_detail js-example-basic-single" name="payment_status">

									<option selected value="0">Select status</option>

								<?php foreach($statuses as $status){ ?>

								<option <?php if(isset($statusRow['payment_status']) && $statusRow['payment_status'] == $status['sts_id']){ echo "Selected"; } ?> value="<?php echo $status['sts_id']; ?>"><?php echo $status['status']; ?></option>

								<?php } ?>

							</select>

							<div class="help-block with-errors "></div>

						</div>

					</div>

					<div class="col-md-4">

						<div class="form-group">

							<label  class="control-label">SMS Template Id</label>

							<input type="text" class="form-control" name="sms_temlate_id" placeholder="SMS template id"  value="<?php echo $statusRow['sms_temlate_id'] ?>">

							<div class="help-block with-errors "></div>

						</div>

					</div>



					<div class="col-md-4">

						<div class="form-group">

							<label  class="control-label">Allowed Status</label>

							<select class="js-example-basic-multiple" name="allowed_status[]" multiple="multiple">

					        	<?php if (isset($statusRow['allowed_status']) && !empty($statusRow['allowed_status'])): ?>

					        	<?php $allowed_statuses = explode(',', $statusRow['allowed_status']); ?>



								<?php foreach ($statuses as $key => $value) {



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

							</select>

							<div class="help-block with-errors "></div>

						</div>

					</div>

				

				</div>

				

				<div class="row">

					<div class="col-md-4">

						<input type="submit" name="update_status" class="btn btn-purple" value="Update" >

					</div>

				</div>

			</div>

			

			<br>

			

		</form>

	

	</div>

</div>

			

            

        </div>

        <!-- Warper Ends Here (working area) -->

        

        

      <?php

	

	include "includes/footer.php";

	}

	else{

		header("location:index.php");

	}

	?>



	<script type="text/javascript">

    $(document).ready(function() {

	    $('.js-example-basic-multiple').select2();

	});

  </script>