<?php

  session_start(); 

  require 'includes/conn.php';

  if(isset($_POST['save_role'])){

    $name = $_POST['name'];

    $is_active = $_POST['is_active'];



    $addQuery = mysqli_query($con,"INSERT INTO `user_role`(`name`, `is_active`) VALUES ('$name','$is_active')");



    header("location:user_role.php");

  }

  if(isset($_POST['edit_button'])){

    $id = $_POST['id'];

    $selectQuery = mysqli_query($con,"SELECT * FROM user_role WHERE id = '$id'");

    $selectRow = mysqli_fetch_array($selectQuery);

  }







  if(isset($_POST['update_role'])){

    $name = $_POST['name'];

    $is_active = $_POST['is_active'];

    $id = $_POST['id'];

    $addQuery = mysqli_query($con,"UPDATE `user_role` SET `name`='$name',`is_active`='$is_active' WHERE id = '$id'");



    header("location:user_role.php");

  }



  if(isset($_POST['delete_button'])){

  	$id = $_POST['id'];

    $addQuery = mysqli_query($con,"DELETE FROM `user_role` WHERE id = '$id'");



    header("location:user_role.php");

  }

  if(isset($_SESSION['users_id']) && $_SESSION['type']=='admin'){
 require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'],23,'view_only',$comment =null)){
        header("location:access_denied.php");
    }
  include "includes/header.php";

  

  $return_query = mysqli_query($con,"SELECT * FROM user_role ");



?>

<body data-ng-app>  

  

    

  <?php

  

  include "includes/sidebar.php";

  

  ?>

    <!-- Aside Ends-->

    

    <section class="content">

       

  <?php

  include "includes/header2.php";

  ?>

       <style type="text/css">

          .city_to option.hide {

            /*display: none;*/

          }

          .form-group{

            margin-bottom: 0px !important;

          }

        </style>  

        <!-- Header Ends -->

        

        

        <div class="warper container-fluid">

          

            <div class="page-header"><h3><?php echo getLange('userroles'); ?>  </h3></div>
              <div class="row">
                <?php
            require_once "setup-sidebar.php";
          ?>
          <div class="col-sm-10 table-responsive" id="setting_box">
             <form method="POST" action="">

              <div class="panel panel-primary">

                <div class="panel-heading"><?php echo getLange('createuserrole'); ?>  </div>

                <div class="panel-body">

                  <form method="POST" action="#">

                      <div class="row">  

                          <div class="col-sm-3">

                              <input type="text" class="form-control" required="" value="<?php echo isset($selectRow['name']) ? $selectRow['name'] : '' ?>" placeholder="<?php echo getLange('role').' '.getLange('name'); ?>" name="name">

                          </div> 

                          <div class="col-sm-2">

                              <select class="form-control" name="is_active">

                                  <option <?php if($selectRow['is_active'] && $selectRow['is_active']==1){

                                  	echo "selected";

                                  } ?> value="1">Active</option>

                                  <option <?php if($selectRow['is_active'] && $selectRow['is_active']==0){

                                  	echo "selected";

                                  } ?> value="0">Not Active</option>

                              </select>

                          </div>   

                          <div class="col-sm-2 create_role_btn">

                          		<?php if(isset($selectRow['id'])): ?>

                              		<button type="submit" class="btn btn-sm btn-success" name="update_role"><?php echo getLange('save'); ?></button>

                              		<input type="hidden" name="id" value="<?php echo $selectRow['id']; ?>">

                      			<?php else: ?>

                              		<button type="submit" class="btn btn-sm btn-success" name="save_role"><?php echo getLange('save'); ?></button>

                      			<?php endif; ?>

                          </div>   

                      </div>

                  </form>

                </div>

              </div>

             </form>



              <div class="panel-body user_role_table">
                <table class="table table-bordered data-table">

                <thead>

                  <th><?php echo getLange('srno'); ?>.</th>

                  <th><?php echo getLange('name'); ?></th>

                  <th><?php echo getLange('status'); ?></th>

                  <th style="width: 160px;"><?php echo getLange('action'); ?></th>

                </thead>

                <tbody>

                  <?php $sr_no=1; ?>

                  <?php while ($row = mysqli_fetch_array($return_query)) { ?>

                    <tr>

                    <td><?php echo $sr_no++; ?></td>

                    <td><?php echo $row['name'] ?></td>

                    <td><?php echo ($row['is_active']==1) ? "Active" : "Not Active"; ?></td>

                    <td>
                     
                      <?php if ($row['id'] != 1 and $row['id'] != 2 and $row['id'] != getfranchisemanagerId() and $row['id'] != 3 and $row['id'] != 4): ?> 

                        <form method="POST" action="user_role.php">

                          <input type="submit" name="edit_button" class="btn btn-info btn-sm" style="margin-right: 10px;" value="Edit">

                          <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                          <input type="submit" name="delete_button" class="btn btn-danger btn-sm" value="Delete">

                        </form>
                      <?php endif ?>
                    </td>

                  </tr>

                 <?php } ?>

                  

                </tbody>

              </table>
              </div>

           

            

        </div>
        </div>
        </div>

        <!-- Warper Ends Here (working area) -->

        

        

      <?php

  

  include "includes/footer.php";

  }

  else{

    header("location:index.php");

  }

  ?>



  <script type="text/javascript">

    $(document).ready(function(){

      $(".data-table").dataTable();

    })

  </script>

