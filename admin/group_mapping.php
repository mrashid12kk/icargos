<?php

  session_start(); 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
  require 'includes/conn.php';

  if(isset($_POST['save_role'])){
    $name = $_POST['name'];
    $group_id = $_POST['account_group'];
    $addQuery = mysqli_query($con,"INSERT INTO `tbl_grp_mapping`(`description`, `group_id`) VALUES ('$name','$group_id')");

// if(!$addQuery){
//   echo "INSERT INTO `tbl_grp_mapping`(`type`, `group_id`,`ledger_id`) VALUES ('$name','$group_id','ledger_id')";
//   echo mysqli_error($con);
// }

    header("location:group_mapping.php");

  }

  if(isset($_POST['edit_button'])){

    $id = $_POST['id'];

    $selectQuery = mysqli_query($con,"SELECT * FROM tbl_grp_mapping WHERE id = '$id'");

    $selectRow = mysqli_fetch_array($selectQuery);

  }
  if(isset($_POST['update_role'])){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $group_id = $_POST['account_group'];

    $addQuery = mysqli_query($con,"UPDATE `tbl_grp_mapping` SET `description`='$name',`group_id`='$group_id' WHERE id = '$id'");
    header("location:group_mapping.php");

  }

  if(isset($_POST['delete_button'])){

    $id = $_POST['id'];

    $addQuery = mysqli_query($con,"DELETE FROM `tbl_grp_mapping` WHERE id = '$id'");
    header("location:group_mapping.php");

  }

  if(isset($_SESSION['users_id']) && $_SESSION['type']=='admin'){
 require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'],23,'view_only',$comment =null)){
        header("location:access_denied.php");
    }
  include "includes/header.php";

  

  $return_query = mysqli_query($con,"SELECT * FROM tbl_grp_mapping ");
  $group = mysqli_query($con, "SELECT * FROM `tbl_accountgroup`");
  function getAccountGroupName($id){
    require 'includes/conn.php';
      $getAccountGroupName = mysqli_query($con, "SELECT * FROM tbl_accountgroup where id = '".$id."'");
      $fetch = mysqli_fetch_array($getAccountGroupName);
      $name = $fetch['accountGroupName'];
      return $name;
  }
  function getAccountLedgerName($id){
    require 'includes/conn.php';
      $getAccountLedgerName = mysqli_query($con, "SELECT * FROM tbl_accountledger where id = '".$id."'");
      $fetch = mysqli_fetch_array($getAccountLedgerName);
      $name = $fetch['ledgerName'];
      return $name;
  }

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

          

            <div class="page-header"><h3><?php echo getLange('ledgermapping'); ?>  </h3></div>
              <div class="row">
                <?php
            require_once "setup-sidebar.php";
          ?>
          <div class="col-sm-10 table-responsive" id="setting_box">
             <form method="POST" action="">

              <div class="panel panel-primary">

                <div class="panel-heading"><?php echo getLange('createledgermapping'); ?>  </div>

                <div class="panel-body">

                  <form method="POST" action="#">

                      <div class="row">  

                          <div class="col-sm-3">
                            <label>Name</label>
                              <input type="text" class="form-control" required="" value="<?php echo isset($selectRow['description']) ? $selectRow['description'] : '' ?>" placeholder="<?php echo getLange('name'); ?>" name="name">

                          </div> 

                          <div class="col-sm-2">
                            <label>Account Group</label>
                              <select class="form-control select2 " name="account_group" id="getLedger" >
                                <option>Select </option>
                                  <?php
                                    while ($fetch = mysqli_fetch_array($group)) {
                                      // var_dump($fetch);
                                      ?>
                                      <option value="<?php echo $fetch['id'];?>" <?php if(isset($selectRow['group_id']) && $selectRow['group_id'] == $fetch['id']){
                                        echo "Selected";
                                      }?>><?php echo $fetch['accountGroupName']?></option>
                                      <?php
                                      }
                                    ?>

                              </select>

                          </div>
                          <div class="col-sm-2 create_role_btn">

                              <?php if(isset($selectRow['id'])): ?>

                                  <button type="submit" class="btn btn-sm btn-success" name="update_role"><?php echo getLange('save'); ?></button>

                                  <input type="hidden" name="id" value="<?php echo $selectRow['id']; ?>">

                            <?php else: ?>
                                  <label></label>
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
                  <th><?php echo getLange('accountgroup'); ?></th>

                  <th style="width: 160px;"><?php echo getLange('action'); ?></th>

                </thead>

                <tbody>

                  <?php $sr_no=1; ?>

                  <?php while ($row = mysqli_fetch_array($return_query)) { ?>

                    <tr>

                    <td><?php echo $sr_no++; ?></td>

                    <td><?php echo $row['description'] ?></td>
                    <td><?php echo getAccountGroupName($row['group_id']); ?></td>
                    <td>
                     

                        <form method="POST" action="group_mapping.php">

                          <input type="submit" name="edit_button" class="btn btn-info btn-sm" style="margin-right: 10px;" value="Edit">

                          <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                          <input type="submit" name="delete_button" class="btn btn-danger btn-sm" value="Delete">

                        </form>
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

  <script>
        $('.select2').select2();
    </script>

  <script type="text/javascript">

    $(document).ready(function(){

      $(".data-table").dataTable();

    })

  </script>
