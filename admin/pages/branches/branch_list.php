<?php

    if(isset($_POST['delete'])){

        $id=mysqli_real_escape_string($con,$_POST['id']);
    if($id==1){
         echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not delete Admin branch unsuccessfully.</div>';

    }
    else{
        $query1=mysqli_query($con,"delete from branches where id=$id") or die(mysqli_error($con));

        $rowscount=mysqli_affected_rows($con);

        if($rowscount>0){

            echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you have delete a branch successfully</div>';

        }

        else{

            echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not delete a branch unsuccessfully.</div>';

        }

    }
}

    function getBranchNameById($id)
 {
    global $con;
    $branchQ = mysqli_query($con, "SELECT name from branches where id = $id");

    $res = mysqli_fetch_array($branchQ);

    return $res['name'];
 }
     function getCityById($id)
 {
    global $con;
    $city = mysqli_query($con, "SELECT city_name from cities where id =". $id);

    $res = mysqli_fetch_assoc($city);

    return $res['city_name'];
 }

    function getoriginById($id)
 {
    global $con;
    $city = mysqli_query($con, "SELECT city_name from cities where id =". $id);

    $res = mysqli_fetch_assoc($city);

    return $res['city_name'].', ';

 }
?>

<div class="panel panel-default">
<div class="panel-heading"><?php echo getLange('branchlist'); ?>
        <a href="add_branch.php" class="add_form_btn" style="float: right;font-size: 11px;"><?php echo getLange('addnewbranch'); ?>  </a>
    </div>
    



        <div class="panel-body" id="same_form_layout" style="padding: 11px;">

            <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">

                <div class="row">

                    <div class="col-sm-12 table-responsive">

                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info">

                            <thead>

                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 35px;"><?php echo getLange('srno'); ?></th>
                                   <th class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php echo getLange('branchname'); ?></th>
                                   <th class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;">Branch Phone</th>
                                   <th class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php echo getLange('branchcity'); ?></th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php echo getLange('branchorigin'); ?></th>
                                    
                                    <th class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php echo getLange('createdat'); ?></th>
                                   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 108px;"><?php echo getLange('action'); ?></th>

                                </tr>

                            </thead>

                            <tbody>

                            <?php

                                if(isset($_GET['id'])){

                                    $id=mysqli_real_escape_string($con,$_GET['id']);

                                    $query1=mysqli_query($con,"SELECT * from barnches");

                                    while($fetch1=mysqli_fetch_array($query1)){

                            ?>

                                    <tr class="gradeA odd" role="row">

                                    <td class="sorting_1"><?php echo $fetch1['name']; ?></td>
                                    <td class="sorting_1"><?php echo isset($fetch1['phone']) ? $fetch1['phone'] : ''; ?></td>


                                    <td class="center">

                                        <form action="editdrivers.php" method="post" >

                                            <input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">

                                            <button type="submit" name="edit" class="btn_stye_custom" >

                                              <span class="glyphicon glyphicon-edit"></span>

                                            </button>

                                        </form>



                                        <form action="" method="post">

                                            <input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">

                                            <button type="submit" name="delete" class="btn_stye_custom" onclick="return confirm('Are You Sure Delete this Branch')" >

                                              <span class="glyphicon glyphicon-trash"></span>

                                            </button>

                                        </form>

                                    </td>

                                </tr>

                                <?php

                                    }

                                }

                                else{

                                    $where = '1';

                                    if(isset($_SESSION['branch_id']))
                                    {
                                        $where= ' users.branch_id = '.$_SESSION['branch_id'];
                                    }

                                $query1=mysqli_query($con,"SELECT * from branches");
                                $sr=1;
                                while($fetch1=mysqli_fetch_array($query1)){

                            ?>



                                <tr class="gradeA odd" role="row">
                                    <td class="sorting_1"><?php echo $sr; ?></td>
                                    <td class="sorting_1"><?php echo $fetch1['name']; ?></td>
                                    <td class="sorting_1"><?php echo isset($fetch1['phone']) ? $fetch1['phone'] : ''; ?></td>
                                    <td class="sorting_1"><?php echo getCityById($fetch1['branch_city']); ?></td>
                                     <td class="sorting_1"><?php 
                                    $get='';
                                   $branch_origin=(explode(",",$fetch1['branch_origin']));
                                    foreach ($branch_origin as $key) {
                                      $get.=getoriginById($key);

                                    }
                                    $get_trim = rtrim($get, ', ');
                                     echo $get_trim;
                                     ?></td>
                                     <td class="sorting_1"><?php echo isset($fetch1['created_at']) ? date('Y-m-d H:i:s', strtotime($fetch1['created_at'])) : ''; ?></td>
                                    <td class="center">

                                        <a href="edit_branch.php?id=<?php echo $fetch1['id'] ?>"><i class="fa fa-edit"></i></a>



                                        <form action="" method="post">

                                            <input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">

                                            <button type="submit" name="delete" class="btn_stye_custom" onclick="return confirm('Are You Sure Delete this Branch')" >

                                              <span class="glyphicon glyphicon-trash"></span>

                                            </button>

                                        </form>

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
