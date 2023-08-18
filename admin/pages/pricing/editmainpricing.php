<?php
if(isset($_POST['updatecustomerpricing'])){
			$zone_id=$_GET['zone_id'];

			$city=mysqli_real_escape_string($con,$_POST['city']);
			$point_5_kg=mysqli_real_escape_string($con,$_POST['point_5_kg']);
			$onekg = mysqli_real_escape_string($con,$_POST['onekg']);
			$other_kg=mysqli_real_escape_string($con,$_POST['other_kg']);
			$zone_id = $_POST['zone_id'];
			
				//update
				mysqli_query($con,"UPDATE `zone` SET `point_5_kg`='".$point_5_kg."',`upto_1_kg`='".$onekg."',`other_kg`='".$other_kg."' WHERE id='".$zone_id."'  ");
			


			$rowscount=mysqli_affected_rows($con);

			if($rowscount>0){

				echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you updated Pricing successfully</div>';

				echo "<script>document.location.href='addzone.php';</script>";

			}

			else{

				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not upadated a driver unsuccessfully.</div>';
				echo "<script>document.location.href='addzone.php';</script>";
			}

		}

	if(isset($_GET['zone_id'])){
		
	$zone_id = $_GET['zone_id'];


		$get_query = mysqli_query($con,"SELECT * FROM zone WHERE id ='".$zone_id."' ");
		$get_query_fetch = mysqli_fetch_array($get_query);

	$zone = $get_query_fetch['zone'];
	$point_5_kg = $get_query_fetch['point_5_kg'];
	$onekg = $get_query_fetch['upto_1_kg'];
	$other_kg = $get_query_fetch['other_kg'];
	$zone_id = $_GET['zone_id'];
	
	
 	
	}

	

?>

<div class="panel panel-default">

	<div class="panel-heading">Update Zone Pricing</div>

	<div class="panel-body" style="padding: 14px;">

	

		<form role="form" class=""  action="" method="post">

		<div class="col-sm-6">
			

		

			  

			  <div class="form-group">

				<label for="exampleInputEmail1">0.5 kg price (Rs)</label>

				<input type="number" class="form-control " name="point_5_kg" value="<?php echo $point_5_kg; ?>"  required >

				<div class="help-block with-errors "></div>

			</div>

			  <div class="form-group">

				<label for="exampleInputEmail1">Upto 1 kg price (Rs)</label>

				<input type="text" class="form-control " name="onekg" value="<?php echo $onekg; ?>"  required >

				<div class="help-block with-errors "></div>

			</div>
			
			<div class="form-group">

				<label for="exampleInputEmail1">Other kg price(Rs)</label>

				<input type="text" class="form-control " name="other_kg" value="<?php echo $other_kg; ?>"   required>

				<div class="help-block with-errors "></div>

			</div>
			<input type="hidden" name='zone_id' value="<?php echo $zone_id;?>">

		 <button type="submit" name="updatecustomerpricing" class="btn btn-purple" >Update</button>
		</div>

		</form>

	

	</div>

</div>

<?php

	

?>