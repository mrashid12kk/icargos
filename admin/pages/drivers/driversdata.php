<?php

if (isset($_POST['delete'])) {

	$id = mysqli_real_escape_string($con, $_POST['id']);

	$query1 = mysqli_query($con, "delete from deliver where driver_id=$id") or die(mysqli_error($con));

	$query1 = mysqli_query($con, "delete from users where id=$id") or die(mysqli_error($con));

	$rowscount = mysqli_affected_rows($con);

	if ($rowscount > 0) {

		echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you have delete an employee successfully</div>';
	} else {

		echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not delete an employee unsuccessfully.</div>';
	}
}

function getBranchNameById($id)
{
	global $con;
	$branchQ = mysqli_query($con, "SELECT name from branches where id = $id");

	$res = mysqli_fetch_array($branchQ);

	return $res['name'];
}
// var_dump($_GET['select_branches']);

?>
		
        <?php
		
		$branch_id = $_SESSION['branch_id'];
								if ($branch_id == 1) { ?>
<form id="selectform">
 <select class="form-control" name="select_branches" style="    width: 126PX;" id="select_branches">
    <option value="0">All Branches</option>
     <?php
     
$query12 = mysqli_query($con, "SELECT * FROM `branches` ORDER BY `branches`.`id` ASC");
while ($fetch12 = mysqli_fetch_array($query12)) {
     ?>
     <option value="<?php echo $fetch12['id']; ?>" <?php if(isset($_GET['select_branches']) && $_GET['select_branches'] == $fetch12['id']){echo 'selected';}?>><?php echo $fetch12['name']; ?></option>
     <?php } ?>
 </select>
</form>
 <?php	
 	}
 ?>
<div class="panel panel-default">

    <div class="panel-heading"><?php echo getLange('userdata'); ?>
        <a href="adddrivers.php" class="btn btn-info btn-sm pull-right" style="margin-top:-5px;"><i
                class="fa fa-plus"></i><?php echo getLange('addnewuser'); ?> </a>
    </div>


   
    <div class="panel-body" id="same_form_layout" style="padding: 11px;">

        <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">

            <div class="row">

                <div class="col-sm-12 table-responsive">

                    <table cellpadding="0" cellspacing="0" border="0"
                        class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid"
                        aria-describedby="basic-datatable_info">

                        <thead>

                            <tr role="row">
                                <th class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1"
                                    colspan="1" aria-sort="ascending"
                                    aria-label="Rendering engine: activate to sort column descending"
                                    style="width: 35px;"><?php echo getLange('srno'); ?></th>
                                <th class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1"
                                    colspan="1" aria-sort="ascending"
                                    aria-label="Rendering engine: activate to sort column descending"
                                    style="width: 179px;"><?php echo getLange('name'); ?></th>

                                <th class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1"
                                    colspan="1" aria-sort="ascending"
                                    aria-label="Rendering engine: activate to sort column descending"
                                    style="width: 179px;"><?php echo getLange('role'); ?></th>

                                <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1"
                                    aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">
                                    Staff ID</th>
                                <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1"
                                    aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">
                                    <?php echo getLange('email'); ?></th>

                                <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1"
                                    aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">
                                    <?php echo getLange('phone'); ?> #</th>
                                <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1"
                                    aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">
                                    <?php echo getLange('cnic'); ?></th>
                                <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1"
                                    aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">
                                    <?php echo getLange('pickupcommision'); ?>(Rs)</th>
                                <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1"
                                    aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">
                                    <?php echo getLange('deliverycommision'); ?> (Rs)</th>
                                    
                                <?php
								$branch_id = $_SESSION['branch_id'];
								if ($branch_id == 1) { ?>
                                <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1"
                                    aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">
                                    <?php echo getLange('branch'); ?></th>
                                <?php }
								?>
                                <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1"
                                    aria-label="CSS grade: activate to sort column ascending" style="width: 108px;">
                                    <?php echo getLange('action'); ?></th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php

							if (isset($_GET['id'])) {

								$id = mysqli_real_escape_string($con, $_GET['id']);

								$query1 = mysqli_query($con, "Select users.*,user_role.name as role_name from users  LEFT JOIN user_role on users.user_role_id=user_role.id where user_role_id != 1 and  user_role.id=$id");

								while ($fetch1 = mysqli_fetch_array($query1)) {

							?>

                            <tr class="gradeA odd" role="row">

                                <td class="sorting_1"><?php echo $fetch1['Name']; ?></td>

                                <!-- <td><img src="<?php echo $fetch1['image']; ?>" width="100" class="img-circle"></td> -->

                                <td class="center"></td>


                                <td class="center"><?php echo $fetch1['staff_id']; ?></td>
                                <td class="center"><?php echo $fetch1['email']; ?></td>

                                <td class="center"><?php echo $fetch1['phone']; ?></td>
                                <td class="center"><?php echo $fetch1['cnic']; ?></td>


                                <td class="center">

                                    <form action="editdrivers.php" method="post">

                                        <input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">

                                        <button type="submit" name="edit" class="btn_stye_custom">

                                            <span class="glyphicon glyphicon-edit"></span>

                                        </button>

                                    </form>



                                    <form action="" method="post">

                                        <input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">

                                        <button type="submit" name="delete" class="btn_stye_custom"
                                            onclick="return confirm('Are You Sure Delete this Employee')">

                                            <span class="glyphicon glyphicon-trash"></span>

                                        </button>

                                    </form>

                                </td>

                            </tr>

                            <?php

								}
							} else {

								$where = '1';

								if (isset($_SESSION['branch_id']) and $_SESSION['branch_id'] != 1) {
									$where = ' users.branch_id = ' . $_SESSION['branch_id'];
								}

                                if($_GET['select_branches']){
                                    $where = 'users.branch_id =' .$_GET['select_branches'];
                                }
                                $sqli = "Select users.*,user_role.name as role_name from users  LEFT JOIN user_role on users.user_role_id=user_role.id left join branches on users.branch_id =branches.id WHERE $where order by users.id desc" ; 
								$query1 = mysqli_query($con, $sqli);
                                // var_dump($sqli);
								$sr = 1;
								while ($fetch1 = mysqli_fetch_array($query1)) {
                                        // var_dump($fetch1);
								?>



                            <tr class="gradeA odd" role="row">

                                <td class="sorting_1"><?php echo $sr; ?></td>
                                <td class="sorting_1"><?php echo $fetch1['Name']; ?></td>

                                <td class="center"> <?php echo $fetch1['role_name']; ?> </td>

                                <?php
										$branch_id = $_SESSION['branch_id'];
										if (empty($branch_id)) { ?>
                                <td class="center"> <?php echo getBranchNameById($fetch1['branch_id']); ?> </td>
                                <?php }
										?>
                                <td class="center"><?php echo $fetch1['user_name']; ?></td>
                                <td class="center"><?php echo $fetch1['email']; ?></td>

                                <td class="center"><?php echo $fetch1['phone']; ?></td>
                                <td class="center"><?php echo $fetch1['cnic']; ?></td>
                                <td class="center"><?php echo $fetch1['pickup_comm']; ?></td>
                                <td class="center"><?php echo $fetch1['delivery_comm']; ?></td>
                                <?php
                                	$branch_id = $_SESSION['branch_id'];
                                if($branch_id = 1){
                                ?>
                                <td class="center"><?php echo getBranchNameById($fetch1['branch_id']); ?></td>
                                <?php
								}
                                ?>
                                <td class="center">

                                    <a href="editdrivers.php?id=<?php echo $fetch1['id'] ?>"><i
                                            class="fa fa-edit"></i></a>


                                    <?php if (checkRolePermission($_SESSION['user_role_id'], 67, 'delete_only', $comment = null)) { ?>

                                    <form action="" method="post">

                                        <input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">

                                        <button type="submit" name="delete" class="btn_stye_custom"
                                            onclick="return confirm('Are You Sure Delete this Employee')">

                                            <span class="glyphicon glyphicon-trash"></span>

                                        </button>

                                    </form>
                                    <?php } ?>
                                </td>

                            </tr>

                            <?php
									$sr++;
								}
							}



							?>

                        </tbody>

                    </table>



                </div>

            </div>

        </div>

    </div>

</div>