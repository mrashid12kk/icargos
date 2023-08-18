<?php

	if(isset($_POST['delete'])){

		$id=mysqli_real_escape_string($con,$_POST['id']);

		$query1=mysqli_query($con,"delete from areas where id=$id") or die(mysqli_error($con));

		$rowscount=mysqli_affected_rows($con);

		if($rowscount>0){

			echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you have delete an area successfully</div>';

		}

		else{

			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not delete an area unsuccessfully.</div>';

		}

	}


	$msg="";



if(isset($_POST['addareas'])){
	$area_code=implode("','" , $_POST['area_code']);
	$check=mysqli_query($con,"SELECT * FROM areas WHERE (area_code IN ('".$area_code."'))");
      $rowscount=mysqli_affected_rows($con);
      if($rowscount>0){
      	$area_code='';
      	while ($areacode=mysqli_fetch_array($check)) {
      		$area_code.=$areacode['area_code'].',';
      	}
      	$trim_area_code = trim($area_code, ',');
        $msg='<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> This '.$trim_area_code.' Area Code is Already Exist</div>';
        $edit=$_POST;
      }
      else{
		foreach ($_POST['area'] as $key => $value) {

			$city_name = $_POST['city_name'][$key];
			$area_code = $_POST['area_code'][$key];

			$query1=mysqli_query($con,"INSERT INTO `areas`(`area_name`,`area_code`, `city_name`) VALUES ('".$value."','".$area_code."',".$city_name.")") or die(mysqli_error($con));
		}

		if($query1)

		{

			$msg= '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you added a Area successfully</div>';

		}

		else{



			$msg= '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not added a new Area unsuccessfully.</div>';

		}
	}
}







echo $msg;



		$destcitydata=mysqli_query($con,"SELECT * from cities") or die(mysqli_error($con));



?>


<div class="panel panel-default">



	<div class="panel-heading"><?php echo getLange('addareas'); ?></div>



	<div class="panel-body">



	



		<form role="form" data-toggle="validator" action="" method="post">



			<div id="areas">



			



				<div class="form-group add_areas" style="    margin-bottom: 0;">



					<div class="row">
						<div class="col-lg-4" style="padding-left: 0;">

							<label  class="control-label">Area Code</label>

							<div class="area_name">

								<input type="text" class="form-control" name="area_code[]" placeholder="Area Code" required>

							</div>

							<div class="help-block with-errors "></div>

						</div>
						<div class="col-lg-4" style="padding-left: 0;">

							<label  class="control-label"><?php echo getLange('areaname'); ?></label>

							<div class="area_name">

								<input type="text" class="form-control area_name" name="area[]" placeholder="<?php echo getLange('areaname'); ?>" required>

							</div>

							<div class="help-block with-errors "></div>

						</div>

						<div class="col-lg-4" style="padding-left: 0;">

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
					<div class="alert alert-danger error_area_code" style="display: none;"></div>
				<div class="col-sm-12" style="margin-bottom: 0;">
					<button type="button" id="addmorearea" class="btn btn-info btn-sm" ><?php echo getLange('addmorearea'); ?></button>
					<button style="margin-left: 3px; vertical-align: middle; padding: 5px 8px;" type="submit" name="addareas" class="add_form_btn" ><?php echo getLange('submit'); ?></button>
						
				</div>
			</div>

				



			</div>



			<br>



			



		</form>



	



	</div>



</div>

<div class="panel panel-default">

	<div class="panel-heading"><?php echo getLange('arealist'); ?> 

		<!-- <a href="addcities.php" class="btn btn-info pull-right" style="margin-top:-7px;" ><i class="fa fa-plus"></i>Add New City</a> -->

	</div>

		<div class="panel-body" id="same_form_layout" style="padding: 11px;">

			<div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">

				<div class="row">

					<div class="col-sm-12 table-responsive">

						<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info"> 

							<thead>

								<tr role="row">

								   <th style="width: 88%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;">Area Code </th>
								   <th style="width: 88%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php echo getLange('areaname'); ?> </th>
								   

								  <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 108px;"><?php echo getLange('action'); ?></th>

								</tr>

							</thead>

							<tbody>

							<?php
							

								$query1=mysqli_query($con,"SELECT areas.* , cities.city_name from areas join cities on areas.city_name = cities.id order by areas.id desc");


								while($fetch1=mysqli_fetch_array($query1)){

							?>

								<tr class="gradeA odd" role="row">

									<td class="sorting_1"><?php echo $fetch1['area_code']; ?></td>
									<td class="sorting_1"><?php echo $fetch1['area_name'].' ('.$fetch1['city_name'].')' ?></td>

									<td class="center inline_Btn">

										<form action="editareas.php" method="post"  style="display: inline-block;">

											<input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">

											<button type="submit" name="edit"  >

											  <span class="glyphicon glyphicon-edit"></span> 

											</button>

											<input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">

											

										</form>

										

										<form action="areaslist.php" method="post" style="display: inline-block;">

											<input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">

											<button type="submit" name="delete" onclick="return confirm('Are You Sure Delete this Employee')" >

											  <span class="glyphicon glyphicon-trash"></span> 

											</button>

										</form>

									

									</td>

								</tr>

								<?php

									

								}

								

								?>

							</tbody>

						</table>

			

				</div>

			</div>

		</div>

	</div>

</div>
</div>