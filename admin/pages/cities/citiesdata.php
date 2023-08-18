<?php
if (isset($_POST['delete'])) {
	$id = mysqli_real_escape_string($con, $_POST['id']);
	$query1 = mysqli_query($con, "delete from cities where id=$id") or die(mysqli_error($con));
	$rowscount = mysqli_affected_rows($con);
	if ($rowscount > 0) {
		echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you have delete a city successfully</div>';
	} else {
		echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not delete a city unsuccessfully.</div>';
	}
}
?>
<?php
$msg = "";
if (isset($_POST['addcities'])) {
	$query1 = mysqli_query($con, "INSERT INTO `cities`(`zone_type_id`,`country_id`,`state_id`,`stn_code`,`city_name`,`area_code`,`title`) VALUES ('" . $_POST['zone_type_id'] . "','" . $_POST['country_id'] . "','" . $_POST['state_id'] . "','" . $_POST['stn_code'] . "','" . $_POST['city_name'] . "','" . $_POST['area_code'] . "','" . $_POST['title'] . "')") or die(mysqli_error($con));

       $rowscount = mysqli_affected_rows($con);

       if ($rowscount > 0) {

          $msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you added a  City/Area successfully</div>';

		/* 	$query=mysqli_query($con,"select * from admin");

			$fetch=mysqli_fetch_array($query);

			$reciever=$fetch['email'];

			$subject = "Signup Request";

			$txt = "$user_name send a signup request to you please check the the details from admin panel";

			$headers = "From: $email" . "\r\n";

			mail('muhammad.usman93333@gmail.com',$subject,$txt,$headers);*/
       } else {

          $msg = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not added a new City unsuccessfully.</div>';
      }
  }

  echo $msg;
  $zone_type = mysqli_query($con, "SELECT * from zone_type order by id desc");
  $country = mysqli_query($con, "Select * from country order by id desc");
  $state = mysqli_query($con, "Select * from state order by id desc");

  ?>

  <div class="panel panel-default">

     <div class="panel-heading"><?php echo getLange('addcities'); ?> 
     <a href="citiesdata_excel.php" class="btn btn-info pull-right" style="margin-top:-7px;" ><i class="fa fa-plus"></i>Import Cities:</a></div>


     <div class="panel-body">



        <form role="form" data-toggle="validator" action="" method="post">
            <div class="row">
                <div id="cities" class="col-sm-4">

                    <div class="form-group">

                        <label class="control-label">Country</label>

                        <select type="text" class="form-control select2" id="country" name="country_id" required>
                            <option value="">Select Country</option>
                            <?php while ($row = mysqli_fetch_array($country)) { ?>
                                <option value="<?php echo $row['id'] ?>"><?php echo $row['country_name']; ?></option>
                            <?php } ?>
                        </select>
                        <div class="help-block with-errors "></div>

                    </div>

                </div>
                <div class="col-sm-4">

                    <div class="form-group">

                        <label class="control-label">State / Province</label>

                        <select type="text" class="form-control select2 state_data" name="state_id" required>
                            <option value="">Select State / Province</option>
                            <?php while ($row = mysqli_fetch_array($state)) { ?>
                                <option value="<?php echo $row['id'] ?>"><?php echo $row['state_name']; ?></option>
                            <?php } ?>
                        </select>
                        <div class="help-block with-errors "></div>

                    </div>

                </div>
                <div class="col-sm-4">

                    <div class="form-group">

                        <label class="control-label"><?php echo getLange('cityareaname'); ?></label>

                        <input type="text" class="form-control select2" name="city_name"
                        placeholder="<?php echo getLange('cityareaname'); ?>" required>

                        <div class="help-block with-errors "></div>

                    </div>

                </div>
            </div>
            <div class="col-sm-4">

                <div class="form-group">

                    <label class="control-label">STN Code</label>

                    <input type="text" class="form-control select2" name="stn_code" placeholder="STN Code">

                    <div class="help-block with-errors "></div>

                </div>

            </div>
            
            <div class="col-sm-4" style="display: none;">

                <div class="form-group">

                    <label class="control-label">Title</label>

                    <input type="text" class="form-control select2" name="title" placeholder="Title" >

                    <div class="help-block with-errors "></div>

                </div>

            </div>
            <div class="col-sm-4">

                <div class="form-group">

                    <label class="control-label">Area Code</label>

                    <input type="text" class="form-control select2" name="area_code" placeholder="area_code">

                    <div class="help-block with-errors "></div>

                </div>

            </div>
            <div class="col-sm-6" style="display: none;">



                <div class="form-group">

                    <label class="control-label"><?php echo getLange('gst'); ?>%</label>

                    <input type="text" class="form-control" name="gst[]" placeholder="<?php echo getLange('gst'); ?>"
                    value="0" required>

                    <div class="help-block with-errors "></div>

                </div>

            </div>
            <div class="col-sm-4">

                <div class="form-group">

                    <label class="control-label">Zone Type</label>

                    <select type="text" class="form-control select2" name="zone_type_id" required>
                        <option value="">Select Zone Type</option>
                        <?php while ($row = mysqli_fetch_array($zone_type)) { ?>
                            <option value="<?php echo $row['id'] ?>"><?php echo $row['zone_name']; ?></option>
                        <?php } ?>
                    </select>
                    <div class="help-block with-errors "></div>

                </div>

            </div>

            <div>
                <div class="row">
                    <div class="col-sm-12" style="margin-bottom: 10px;">
                        <button type="button" id="addmore" class="btn btn-info btn-sm"
                        style="display: none;"><?php echo getLange('addmorecities'); ?> </button>
                        <button style="margin-left: 3px; vertical-align: middle; padding: 5px 8px;" type="submit"
                        name="addcities" class="add_form_btn"><?php echo getLange('submit'); ?></button>
                    </div>
                </div>

            </div>




        </form>



    </div>

</div>
<div class="panel panel-default">
    <div class="panel-heading"><?php echo getLange('citiesdata'); ?>
    <!-- <a href="addcities.php" class="btn btn-info pull-right" style="margin-top:-7px;" ><i class="fa fa-plus"></i>Add New City</a> -->
</div>
<div class="panel-body" id="same_form_layout" style="padding: 11px;">
    <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">


        <table cellpadding="0" cellspacing="0" border="0"
        class="table table-striped table-bordered no-footer" id="citiesdata_datatable">
        <thead>
            <tr role="row">
                <th style="width: 10%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable"
                rowspan="1" colspan="1" aria-sort="ascending"
                aria-label="Rendering engine: activate to sort column descending" style="width: 179px;">SR
            No </th>
            <th style="width: 20%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable"
            rowspan="1" colspan="1" aria-sort="ascending"
            aria-label="Rendering engine: activate to sort column descending" style="width: 179px;">
        Country</th>
        <th style="width: 20%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable"
        rowspan="1" colspan="1" aria-sort="ascending"
        aria-label="Rendering engine: activate to sort column descending" style="width: 179px;">
    State / Province</th>
    <th style="width: 20%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable"
    rowspan="1" colspan="1" aria-sort="ascending"
    aria-label="Rendering engine: activate to sort column descending" style="width: 179px;">STN
CODE</th>
<th style="width: 20%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable"
rowspan="1" colspan="1" aria-sort="ascending"
aria-label="Rendering engine: activate to sort column descending" style="width: 179px;">City
Name</th>
                        <!-- <th style="width: 20%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable"
                            rowspan="1" colspan="1" aria-sort="ascending"
                            aria-label="Rendering engine: activate to sort column descending" style="width: 179px;">
                        Title</th> -->
                        <th style="width: 20%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable"
                        rowspan="1" colspan="1" aria-sort="ascending"
                        aria-label="Rendering engine: activate to sort column descending" style="width: 179px;">Area
                    Code</th>
                    <th style="width: 20%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable"
                    rowspan="1" colspan="1" aria-sort="ascending"
                    aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"> Zone Type</th>
                    <!-- <th style="width: 88%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php echo getLange('gst'); ?> </th> -->
                    <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1"
                    aria-label="CSS grade: activate to sort column ascending" style="width: 108px;">
                    <?php echo getLange('action'); ?></th>
                </tr>
            </thead>
            <!-- <tbody>
                <?php
                $srno = 1;
                $query1 = mysqli_query($con, "SELECT cities.*,country.country_name,state.state_name,zone_type.zone_name from cities LEFT JOIN country on cities.country_id=country.id LEFT JOIN state on cities.state_id=state.id LEFT JOIN zone_type on cities.zone_type_id=zone_type.id order by cities.id desc");
                while ($fetch1 = mysqli_fetch_array($query1)) {
                 ?>
                 <tr class="gradeA odd" role="row">
                    <td class="sorting_1"><?php echo $srno++;; ?></td>
                    <td class="sorting_1"><?php echo $fetch1['country_name']; ?></td>
                    <td class="sorting_1"><?php echo $fetch1['state_name']; ?></td>
                    <td class="sorting_1"><?php echo $fetch1['stn_code']; ?></td>
                    <td class="sorting_1"><?php echo $fetch1['city_name']; ?></td>
                    <td class="sorting_1"><?php echo $fetch1['area_code']; ?></td>
                    <td class="sorting_1"><?php echo $fetch1['zone_name']; ?></td>
                    <td class="center inline_Btn">
                        <form action="editcities.php" method="post" style="display: inline-block;">
                            <input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">
                            <button type="submit" name="edit_id">
                                <span class="glyphicon glyphicon-edit"></span>
                            </button>
                            <input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">

                        </form>

                        <form action="citiesdata.php" method="post" style="display: inline-block;">
                            <input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">
                            <button type="submit" name="delete"
                            onclick="return confirm('Are You Sure Delete this Employee')">
                            <span class="glyphicon glyphicon-trash"></span>
                        </button>
                    </form>

                </td>
            </tr>
            <?php

        }

        ?>
    </tbody> -->
</table>

</div>
</div>
</div>
</div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('body').on('change', '#country', function(e) {
            e.preventDefault();
            var country = $(this).val();
            $.ajax({
                url: 'ajax.php',
                method: "POST",
                data: {
                    country: country,
                    get_country_all: 1
                },
                success: function(content) {
                    if (content != '') {
                        $('.state_data').html('');
                        $('.state_data').html(content);
                    }
                }
            });
        });
    }, false);
</script>