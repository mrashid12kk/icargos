<?php

	

	$msg="";



	if(isset($_POST['addareas'])){



		foreach ($_POST['area'] as $key => $value) {

			$city_name = $_POST['city_name'][$key];

			$query1=mysqli_query($con,"INSERT INTO `areas`(`area_name`, `city_name`) VALUES ('".$value."',".$city_name.")") or die(mysqli_error($con));



		}

		if($query1)

		{

			$msg= '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you added a Area successfully</div>';

		}

		else{



			$msg= '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not added a new Area unsuccessfully.</div>';

		}

	}







echo $msg;



?>







<div class="panel panel-default">



	<div class="panel-heading"><?php echo getLange('addareas'); ?></div>



	<div class="panel-body">



	



		<form role="form" data-toggle="validator" action="" method="post">



			<div id="areas">



			



				<div class="form-group add_areas">



					<div class="row">

						<div class="col-lg-6" style="padding-left: 0;">

							<label  class="control-label"><?php echo getLange('areaname'); ?></label>

							<div class="area_name">

								<input type="text" class="form-control area_name" name="area[]" placeholder="Area Name" required>

							</div>

							<div class="help-block with-errors "></div>

						</div>

						<div class="col-lg-6" style="padding-left: 0;">

							<label  class="control-label"><?php echo getLange('cityname'); ?></label>

							<div class="city_name">

							<select class="form-control  city_to get_city_name" name="city_name[]">

	           					

	           				 <?php while($row = mysqli_fetch_array($destcitydata)){ ?>

	           					<option value="<?php echo $row['id']; ?>" <?php if(trim($record['city_to']) == trim($row['city_name'])) { echo "selected"; } ?>><?php echo $row['city_name']; ?></option>

	           				<?php } ?> 

	           				</select>

	           				</div>

						</div>

					</div>



					



				</div>



			</div>



			<div>

				<div class="row" >
				<div class="col-sm-12" style="margin-bottom: 10px;">
					<button type="button" id="addmorearea" class="btn btn-info btn-sm" ><?php echo getLange('addmorearea'); ?></button>
					<button style="margin-left: 3px; vertical-align: middle; padding: 5px 8px;" type="submit" name="addareas" class="add_form_btn" ><?php echo getLange('submit'); ?></button>
						
				</div>
			</div>

				



			</div>



			<br>



			



		</form>



	



	</div>



</div>
</div>



