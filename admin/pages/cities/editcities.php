<?php

		if(isset($_POST['updatecities'])){

			$id=mysqli_real_escape_string($con,$_POST['id']);

			$city_name=mysqli_real_escape_string($con,$_POST['city_name']);
			$country_id=mysqli_real_escape_string($con,$_POST['country_id']);
			$state_id=mysqli_real_escape_string($con,$_POST['state_id']);
			$stn_code=mysqli_real_escape_string($con,$_POST['stn_code']);
			$title=mysqli_real_escape_string($con,$_POST['title']);
			$area_code=mysqli_real_escape_string($con,$_POST['area_code']);
			$old_city=mysqli_real_escape_string($con,$_POST['old_city']);
			$zone_type_id=mysqli_real_escape_string($con,$_POST['zone_type_id']);
			// $gst=mysqli_real_escape_string($con,$_POST['gst']);
			// $prices = json_encode($_POST['prices']);
		

			$query2=mysqli_query($con,"update cities set city_name='$city_name',country_id='$country_id',state_id='$state_id',stn_code='$stn_code',zone_type_id='$zone_type_id',title='$title',area_code='$area_code' where id=$id") or die(mysqli_error($con));
			$rowscount=mysqli_affected_rows($con);
			$query3 = mysqli_query($con,"update zone_cities  set destination='$city' WHERE  destination='".$old_city."' ");
			$query4 = mysqli_query($con,"update zone_cities  set origin='$city' WHERE  origin='".$old_city."' ");
			

			if($query2){

				echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you updated a City successfully</div>';

			}

			else{

				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not upadated a City unsuccessfully.</div>';

			}

		}

	

	if(isset($_POST['id'])){

		$id=mysqli_real_escape_string($con,$_POST['id']);

		$query1=mysqli_query($con,"select * from cities where id=$id") or die(mysqli_error($con));

		$fetch1=mysqli_fetch_array($query1);


	}

$zone_type = mysqli_query($con, "SELECT * from zone_type order by id desc");
$country=mysqli_query($con,"Select * from country order by id desc");
$state=mysqli_query($con,"Select * from state order by id desc");
?>
<div class="row">
                <?php
            require_once "setup-sidebar.php";
          ?>
          <div class="col-sm-10 table-responsive" id="setting_box">
<div class="panel panel-default">

	<div class="panel-heading"><?php echo getLange('edit').' '.getLange('cities'); ?><a href="citiesdata.php" class="add_form_btn" style="float: right;font-size: 11px;"><?php echo getLange('cities'); ?>  </a></div>

	<div class="panel-body">

	

		<form role="form" class="" data-toggle="validator" action="" method="post">

			<div class="row">
				<div class="col-sm-4">

                    <div class="form-group">

                        <label class="control-label">Zone Type</label>

                        <select type="text" class="form-control select2" name="zone_type_id" required>
                            <option value="">Select Zone Type</option>
                            <?php while ($row = mysqli_fetch_array($zone_type)) { ?>
                            <option value="<?php echo $row['id'] ?>" <?php echo isset($fetch1) && $fetch1['zone_type_id']==$row['id'] ? 'selected' : ''; ?>><?php echo $row['zone_name']; ?></option>
                            <?php } ?>
                        </select>
                        <div class="help-block with-errors "></div>

                    </div>

                </div>
			<div id="cities" class="col-sm-4">
				<input type="hidden" name="old_city" value="<?php echo isset($fetch1['city_name'])?$fetch1['city_name']:""; ?>">
				<div class="form-group">

					<label  class="control-label">Country</label>

					<select type="text" class="form-control select2" id="country" name="country_id" required>
						<option value="">Select Country</option>
						<?php while ($row=mysqli_fetch_array($country)) {?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($fetch1) && $fetch1['country_id']==$row['id'] ? 'selected' : ''; ?>><?php echo $row['country_name']; ?></option>
						<?php } ?>
					</select>
					<div class="help-block with-errors "></div>

				</div>

			</div>
			<div class="col-sm-4">

				<div class="form-group">

					<label  class="control-label">State / Province</label>

					<select type="text" class="form-control select2 state_data" name="state_id"required>
					<option value="">Select State / Province</option>
						<?php while ($row=mysqli_fetch_array($state)) {?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($fetch1) && $fetch1['state_id']==$row['id'] ? 'selected' : ''; ?>><?php echo $row['state_name']; ?></option>
						<?php } ?>
					</select>
					<div class="help-block with-errors "></div>

				</div>

			</div>
			
		</div>
		<div class="row">
		<div class="col-sm-4">

				<div class="form-group">

					<label  class="control-label">STN Code</label>

					<input type="text" class="form-control select2" value="<?php echo $fetch1['stn_code']; ?>" name="stn_code" placeholder="STN Code" >

					<div class="help-block with-errors "></div>

				</div>

			</div>
		<div class="col-sm-4">

				<div class="form-group">

					<label  class="control-label"><?php echo getLange('cityareaname'); ?></label>

					<input type="text" class="form-control select2" name="city_name" placeholder="<?php echo getLange('cityareaname'); ?>" value="<?php echo $fetch1['city_name']; ?>">

					<div class="help-block with-errors "></div>

				</div>

			</div>
			<div class="col-sm-4">

				<div class="form-group">

					<label  class="control-label">Title</label>

					<input type="text" class="form-control select2" name="title" value="<?php echo $fetch1['title']; ?>" placeholder="Title"  required>

					<div class="help-block with-errors "></div>

				</div>

			</div>
			<div class="col-sm-4">

				<div class="form-group">

					<label  class="control-label">Area Code</label>

					<input type="text" class="form-control select2" name="area_code" value="<?php echo $fetch1['area_code']; ?>" placeholder="area_code"  required>

					<div class="help-block with-errors "></div>

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
<script>
   document.addEventListener('DOMContentLoaded', function(){ 
   	$('.select2').select2();
   $('body').on('change','#country',function(e){
           e.preventDefault();
           var country  = $(this).val();
           $.ajax({ 
                  url:'ajax.php',
                  method: "POST",
                  data:{country:country,get_country_all:1},
                 success:function(content)
                 {
                 	if (content!='') {
                 		$('.state_data').html('');
                 		$('.state_data').html(content);
                 	}
                 }
           });
       });
   }, false);
   
</script>