<?php



		if(isset($_POST['updatecities'])){


			$id=mysqli_real_escape_string($con,$_POST['id']);
			$check=mysqli_query($con,"SELECT * FROM areas WHERE area_code='".$_POST['area_code']."' AND id!=".$id);
          $rowscount=mysqli_affected_rows($con);
          if($rowscount>0){
            echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> This '.$_POST['delivery_zone_code'].' Area Code is Already Exist</div>';
            $edit=$_POST;
          }
          else{



			$city_name=mysqli_real_escape_string($con,$_POST['city_name']);

			$area_name=mysqli_real_escape_string($con,$_POST['area_name']);

			$area_code=mysqli_real_escape_string($con,$_POST['area_code']);



			$query2=mysqli_query($con,"UPDATE areas set city_name='$city_name', area_name = '$area_name', area_code = '$area_code' where id=$id") or die(mysqli_error($con));

			$rowscount=mysqli_affected_rows($con);

			



			if($query2){



				echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you updated an area successfully</div>';



			}



			else{



				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not upadated an Area unsuccessfully.</div>';



			}

}

		}



	



	if(isset($_POST['id'])){



		$id=mysqli_real_escape_string($con,$_POST['id']);



		$query1=mysqli_query($con,"SELECT areas.* , cities.city_name from areas join cities on areas.city_name = cities.id where areas.id=$id") or die(mysqli_error($con));



		$fetch1=mysqli_fetch_array($query1);



		$destcitydata=mysqli_query($con,"SELECT * from cities") or die(mysqli_error($con));



	}



?>

<div class="row">
                <?php
            require_once "setup-sidebar.php";
          ?>
          <div class="col-sm-10 table-responsive" id="setting_box">

<div class="panel panel-default">



	<div class="panel-heading"><?php echo getLange('edit').' '.getLange('area'); ?><a href="areaslist.php" class="add_form_btn" style="float: right;font-size: 11px;"><?php echo getLange('areas'); ?>  </a></div>



	<div class="panel-body">



	



		<form role="form" class="" data-toggle="validator" action="" method="post">



			<div class="form-group">



				<div class="row">
					<div class="col-lg-4">

							<label  class="control-label"><?php echo getLange('areas').' '.getLange('code'); ?></label>

							<div class="area_code">

								<input type="text" class="form-control area_code" name="area_code" value="<?php echo isset($fetch1['area_code'])?$fetch1['area_code']:""; ?>">

							</div>

						</div>
						<div class="col-lg-4">

							<label  class="control-label"><?php echo getLange('areas').' '.getLange('name'); ?></label>

							<div class="area_name">

								<input type="text" class="form-control area_name" name="area_name" value="<?php echo isset($fetch1['area_name'])?$fetch1['area_name']:""; ?>">

							</div>

						</div>

						<div class="col-lg-4">

							<label  class="control-label"><?php echo getLange('city').' '.getLange('name'); ?></label>

							<div class="city_name">

							<select class="form-control  city_to get_city_name" name="city_name">

	           					

	           				 <?php while($row = mysqli_fetch_array($destcitydata)){ ?>

	           					<option value="<?php echo $row['id']; ?>" <?php if(trim($fetch1['city_name']) == trim($row['city_name'])) { echo "selected"; } ?>><?php echo $row['city_name']; ?></option>

	           				<?php } ?> 

	           				</select>

	           				</div>

						</div>

					</div>





				</div>

			

				<input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">



		 		<button type="submit" name="updatecities" class="btn btn-purple add_form_btn" ><?php echo getLange('update'); ?></button>



		</form>



	



	</div>



</div>
</div>
</div>



<?php



	



?>