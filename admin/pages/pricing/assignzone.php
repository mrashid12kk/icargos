<?php
		if(isset($_POST['updatecustomerpricing'])){
			$zone_id=$_GET['zone_id'];
			$zone=mysqli_real_escape_string($con,$_POST['zone']);
			$city=mysqli_real_escape_string($con,$_POST['city']);
			$point_5_kg=mysqli_real_escape_string($con,$_POST['point_5_kg']);
			$onekg = mysqli_real_escape_string($con,$_POST['onekg']);
			$other_kg=mysqli_real_escape_string($con,$_POST['other_kg']);
			$customer_id=mysqli_real_escape_string($con,$_POST['customer_id']);
			$zone_id = $_POST['zone_id'];
			$product_id = $_POST['product_id'];
			$get_query = mysqli_query($con,"SELECT * FROM customer_pricing WHERE zone_id ='".$zone_id."' AND customer_id='".$customer_id."' ");

			$rowcount = mysqli_num_rows($get_query);
			if($rowcount == 0){
			//insert
			mysqli_query($con,"INSERT INTO `customer_pricing` SET `point_5_kg`='".$point_5_kg."',`upto_1_kg`='".$onekg."',`other_kg`='".$other_kg."',`customer_id`='".$customer_id."',`zone_id`='".$zone_id."' ");
			}else{
				//update
				mysqli_query($con,"UPDATE `customer_pricing` SET `point_5_kg`='".$point_5_kg."',`upto_1_kg`='".$onekg."', `other_kg`='".$other_kg."' WHERE zone_id='".$zone_id."' AND customer_id='".$customer_id."' ");
			}
			$rowscount=mysqli_affected_rows($con);
			if($rowscount>0){
				echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you updated Pricing successfully</div>';
				echo "<script>document.location.href='customer_detail.php?customer_id=$customer_id';</script>";
			}
			else{
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not upadated a driver unsuccessfully.</div>';
				echo "<script>document.location.href='customer_detail.php?customer_id=$customer_id';</script>";
			}
		}
	if(isset($_GET['customer_id'])){

	$customer_id = $_GET['customer_id'];
	$get_query = mysqli_query($con,"SELECT * FROM customer_pricing WHERE customer_id='".$customer_id."' ");

	$rowcount = mysqli_num_rows($get_query);
	if($rowcount == 0){
		$get_query = mysqli_query($con,"SELECT * FROM zone WHERE id ='".$zone_id."' ");
		$get_query_fetch = mysqli_fetch_array($get_query);
	}else{
		$get_query_fetch = mysqli_fetch_array($get_query);
	}
	$zone = $get_query_fetch['zone'];
	$point_5_kg = $get_query_fetch['point_5_kg'];
	$onekg = $get_query_fetch['upto_1_kg'];
	$twokg = $get_query_fetch['upto_2_kg'];
	$other_kg = $get_query_fetch['other_kg'];
	$zone_id = $_GET['zone_id'];



	}else{
		header("Location:".$_SERVER['HTTP_REFERER']);
	}
	$zone_q = mysqli_query($con,"SELECT * FROM zone WHERE 1 ");
	$products = mysqli_query($con,"SELECT * FROM products ORDER BY id DESC ");
?>
<div class="panel panel-default">
	<div class="panel-heading">Assign zone</div>
		<div class="panel-body" style="padding: 14px;">

			<form role="form" class=""  action="assignzoneaction.php" method="post">
				<div class="row">
					<div class="col-sm-3">
						<div class="form-group">
							<label  class="control-label">Zone</label>
							<select class="form-control assignzone" name="zone" >
								<option value="" selected disabled>select zone</option>
								<?php while($row = mysqli_fetch_array($zone_q)){
									$zone_id = $row['id'];
									$check_zone = mysqli_query($con,"SELECT * FROM customer_pricing WHERE zone_id='".$zone_id."' AND customer_id='".$customer_id."' ");
									$rowcount = mysqli_num_rows($check_zone);
									if($rowcount == 0){ ?>
										<option value="<?php echo $row['id'] ?>"><?php echo $row['zone']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</div>
					 </div>
					 <div class="col-sm-3">
						<div class="form-group">
							<label  class="control-label">Product</label>
							<select class="form-control select2" name="product_id" >
								<?php while($row = mysqli_fetch_array($products)){
									 ?>
										<option value="<?php echo $row['id'] ?>"><?php echo $row['name']; ?></option>
								<?php } ?>
							</select>
						</div>
					 </div>
					 <div class="col-sm-3">
					 	<div class="form-group">
							<label for="exampleInputEmail1">0.5 kg price (Rs)</label>
							<input type="number" class="form-control point_5_kg" name="point_5_kg" step="0.01"  required >
							<div class="help-block with-errors "></div>
						</div>
					 </div>
				 	<div class="col-sm-3">
				 		<div class="form-group">
							<label for="exampleInputEmail1">Upto 1 kg price (Rs)</label>
							<input type="number" class="form-control upto_1_kg" name="onekg"  step="0.01" required >
							<div class="help-block with-errors "></div>
						</div>
				 	</div>
				 	<div class="col-sm-3">
				 		<div class="form-group">
							<label for="exampleInputEmail1">Upto 3 kg price (Rs)</label>
							<input type="number" class="form-control upto_3_kg" name="upto_3_kg"  step="0.01">
							<div class="help-block with-errors "></div>
						</div>
				 	</div>
				 	<div class="col-sm-3">
				 		<div class="form-group">
							<label for="exampleInputEmail1">Additional 10 price(Rs)</label>
							<input type="number" class="form-control upto_10_kg" name="upto_10_kg"  step="0.01">
							<div class="help-block with-errors "></div>
						</div>
				 	</div>
				 	<div class="col-sm-3">
		              <div class="form-group">
		                <label>Additional Weight</label>
		                <select name="addition_kg_type" class="form-control addition_kg_type" required="">
		                  <option value="Additional Weight 0.5 kg">Additional Weight 0.5 kg</option>
		                  <option value="Additional Weight 1 kg">Additional Weight 1 kg</option>
		                </select>
		                <!-- <label  class="control-label"><?php echo getLange('additionalkg'); ?> </label>
		                <input type="text" class="form-control allownumericwithdecimal" name="other_kg">
		                <div class="help-block with-errors "></div> -->
		              </div>
		            </div>
		            <div class="col-sm-3">
		                <div class="form-group addition_kg_input">
		                  
		                    <label  class="control-label">Additional Weight 0.5 kg</label>
		                    <input type="text" class="form-control allownumericwithdecimal additional_point_5_kg" value="" name="additional_point_5_kg"  required>
		                    <div class="help-block with-errors "></div>
		                </div>
		            </div>
				</div>

				<input type="hidden" name='customer_id' value="<?php echo $customer_id;?>">
				 <div class="row">
				 	<div class="col-sm-6">
				 		<button type="submit" name="assign" class="add_form_btn" >Submit</button>
				 	</div>
				 </div>
			</form>
		</div>

	</div>
</div>
