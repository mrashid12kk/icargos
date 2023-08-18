<?php
	if(isset($_POST['updatecustomerpricing']))
	{
		$zone_id=$_GET['zone_id'];
		$zone=mysqli_real_escape_string($con,$_POST['zone']);
		$city=mysqli_real_escape_string($con,$_POST['city']);
		$point_5_kg=mysqli_real_escape_string($con,$_POST['point_5_kg']);
		$onekg = mysqli_real_escape_string($con,$_POST['onekg']);
		$threekg = mysqli_real_escape_string($con,$_POST['upto_3_kg']);
		$upto_10_kg = mysqli_real_escape_string($con,$_POST['upto_10_kg']);
		$other_kg=mysqli_real_escape_string($con,$_POST['other_kg']);
		$additional_point_5_kg=mysqli_real_escape_string($con,$_POST['additional_point_5_kg']);
		$addition_kg_type=mysqli_real_escape_string($con,$_POST['addition_kg_type']);
		$customer_id=mysqli_real_escape_string($con,$_POST['customer_id']);
		$zone_id = $_POST['zone_id'];
		$service_type_q = mysqli_query($con,"SELECT service_type FROM zone WHERE id='".$zone_id."' ");
		$service_type_q_r = mysqli_fetch_array($service_type_q);
		$service_type = $service_type_q_r['service_type'];
		$get_query = mysqli_query($con,"SELECT * FROM customer_pricing WHERE zone_id ='".$zone_id."' AND customer_id='".$customer_id."' ");
		$rowcount = mysqli_num_rows($get_query);
		if($rowcount == 0){
		//insert
		mysqli_query($con,"INSERT INTO `customer_pricing` SET `point_5_kg`='".$point_5_kg."' ,`upto_1_kg`='".$onekg."',`upto_3_kg`='".$threekg."',`other_kg`='".$other_kg."',`additional_point_5_kg`='".$additional_point_5_kg."',`service_type`='".$service_type."',`customer_id`='".$customer_id."',`zone_id`='".$zone_id."',`addition_kg_type`='".$addition_kg_type."' ");
		}else{
			//update
			mysqli_query($con,"UPDATE `customer_pricing` SET `point_5_kg`='".$point_5_kg."' ,`upto_1_kg`='".$onekg."',`upto_3_kg`='".$threekg."', `upto_10_kg`='".$upto_10_kg."',`service_type`='".$service_type."', `other_kg`='".$other_kg."',`additional_point_5_kg`='".$additional_point_5_kg."',`addition_kg_type`='".$addition_kg_type."' WHERE zone_id='".$zone_id."' AND customer_id='".$customer_id."' ");
		}
		$rowscount=mysqli_affected_rows($con);
		if($rowscount>0){
			echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you updated Pricing successfully</div>';
			echo "<script>document.location.href='customer_detail.php?customer_id=$customer_id';</script>";
		}
		else{
			// echo "UPDATE `customer_pricing` SET `point_5_kg`='".$point_5_kg."',`return_charges`='".$return_charges."',`upto_1_kg`='".$onekg."', `service_type`='".$service_type."', `other_kg`='".$other_kg."' WHERE zone_id='".$zone_id."' AND customer_id='".$customer_id."' ";
			// die();
			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not upadated Pricing successfully.</div>';
			echo "<script>document.location.href='customer_detail.php?customer_id=$customer_id';</script>";
		}
	}
	if(isset($_GET['zone_id'])){
		$zone_id     = $_GET['zone_id'];
		$customer_id = $_GET['customer_id'];
		$get_query = mysqli_query($con,"SELECT * FROM customer_pricing WHERE zone_id ='".$zone_id."' AND customer_id='".$customer_id."' ");
		$rowcount = mysqli_num_rows($get_query);
		if($rowcount == 0)
		{
			$get_query = mysqli_query($con,"SELECT * FROM zone WHERE id ='".$zone_id."' ");
			$get_query_fetch2 = mysqli_fetch_array($get_query);
		}else{
			$get_query_fetch = mysqli_fetch_array($get_query);
		}
		$get_query = mysqli_query($con,"SELECT * FROM zone WHERE id ='".$zone_id."' ");
		$get_query_fetch2 = mysqli_fetch_array($get_query);
		// echo "<pre>";
		// print_r($get_query_fetch);
		// die();
		$zone       = $get_query_fetch2['zone'];
		$point_5_kg = $get_query_fetch['point_5_kg'];
		$onekg      = $get_query_fetch['upto_1_kg'];
		$threekg      = $get_query_fetch['upto_3_kg'];
		$upto_10_kg      = $get_query_fetch['upto_10_kg'];
		$other_kg   = $get_query_fetch['other_kg'];
		$addition_kg_type   = $get_query_fetch['addition_kg_type'];
		$additional_point_5_kg   = $get_query_fetch['additional_point_5_kg'];
		$zone_id        = $_GET['zone_id'];
	}
?>
<div class="panel panel-default">
	<div class="panel-heading">Update Pricing</div>
	<div class="panel-body" style="padding: 14px;">
	<form role="form" class=""  action="" method="post">
		<div class="row">
			<div class="col-sm-3">
				<div class="form-group">
				<label  class="control-label">Zone</label>
				<input type="text" class="form-control"  value="<?php echo $zone; ?>" placeholder="Zone" disabled="true">
				<input type="hidden" name="zone" value="<?php echo $zone; ?>" >
				<div class="help-block with-errors "></div>
		 	</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
				<label for="exampleInputEmail1">0.5 kg price (Rs)</label>
				<input type="number" class="form-control " name="point_5_kg" value="<?php echo $point_5_kg; ?>"  required >
				<div class="help-block with-errors "></div>
			</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
				<label for="exampleInputEmail1">Upto 1 kg price (Rs)</label>
				<input type="text" class="form-control " name="onekg" value="<?php echo $onekg; ?>"  required >
				<div class="help-block with-errors "></div>
			</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
				<label for="exampleInputEmail1">Upto 3 kg price (Rs)</label>
				<input type="text" class="form-control " name="upto_3_kg" value="<?php echo $threekg; ?>" >
				<div class="help-block with-errors "></div>
			</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
				<label for="exampleInputEmail1">Upto 10 kg price (Rs)</label>
				<input type="text" class="form-control " name="upto_10_kg" value="<?php echo $upto_10_kg; ?>" >
				<div class="help-block with-errors "></div>
			</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">

				<label>Additional Weight</label>
                <select name="addition_kg_type" class="form-control addition_kg_type" required="">
                  <option <?php echo (isset($addition_kg_type) && $addition_kg_type == 'Additional Weight 0.5 kg') ? 'selected':''; ?> value="Additional Weight 0.5 kg">Additional Weight 0.5 kg</option>
                  <option <?php echo (isset($addition_kg_type) && $addition_kg_type == 'Additional Weight 1 kg') ? 'selected':''; ?> value="Additional Weight 1 kg">Additional Weight 1 kg</option>
                </select>

				<!-- <label for="exampleInputEmail1">Additional kg price(Rs)</label>
				<input type="text" class="form-control " name="other_kg" value="<?php echo $other_kg; ?>"   required>
				<div class="help-block with-errors "></div> -->
			</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group addition_kg_input">
				 <?php
                    if(isset($addition_kg_type) && $addition_kg_type == 'Additional Weight 0.5 kg')
                    {
                      $name='additional_point_5_kg';
                      $value = isset($additional_point_5_kg) ? $additional_point_5_kg:'';
                    }
                    else
                    {
                      $name='other_kg';
                      $value = isset($other_kg) ? $other_kg:'';
                    }
                  ?>
				<label for="exampleInputEmail1"><?php echo isset($addition_kg_type) ? $addition_kg_type:''; ?>(RS)</label>
				<input type="text" class="form-control " value="<?php echo isset($value) ? $value:''; ?>" name="<?php echo isset($name) ? $name:''; ?>"    required>
				<div class="help-block with-errors "></div>
			</div>
			</div>

			<div class="col-sm-12">
				<input type="hidden" name='zone_id' value="<?php echo $zone_id;?>">
				<input type="hidden" name='customer_id' value="<?php echo $customer_id;?>">
		 		<button type="submit" name="updatecustomerpricing" class="add_form_btn" >Update</button>
			</div>
		</div>
	</form>
	</div>
</div>
<?php
?>
