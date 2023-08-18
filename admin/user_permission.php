<?php

  session_start();

  require 'includes/conn.php';





  if(isset($_POST['save_changes'])){

    $role_id = $_POST['role_id'];

    foreach ($_POST['module_id'] as $key => $value) {

        if(isset($_GET['role_id'])){

            $existing_role_id = $_GET['role_id'];

            $role_query = mysqli_query($con,"SELECT * FROM permissions WHERE role_id=".$existing_role_id." AND module_id=".$value." ");

            $role_data = mysqli_fetch_array($role_query);

        }



        $view = isset($_POST['view_'.$value]) ? $_POST['view_'.$value] : '0';

        $add = isset($_POST['add_'.$value]) ? $_POST['add_'.$value] : '0';

        $edit = isset($_POST['edit_'.$value]) ? $_POST['edit_'.$value] : '0';

        $delete = isset($_POST['delete_'.$value]) ? $_POST['delete_'.$value] : '0';

        $approve = isset($_POST['approve_'.$value]) ? $_POST['approve_'.$value] : '0';

        $print = isset($_POST['print_'.$value]) ? $_POST['print_'.$value] : '0';

        $import = isset($_POST['import_'.$value]) ? $_POST['import_'.$value] : '0';

        $export = isset($_POST['export_'.$value]) ? $_POST['export_'.$value] : '0';



        if (isset($role_data)) {

            $role_data_id = $role_data["role_id"];

           $updateQuery = "UPDATE `permissions` SET `view_id`='$view',`add_id`='$add',`edit_id`='$edit',`delete_id`='$delete',`approve_id`='$approve',`print_id`='$print',`import_id`='$import',`export_id`='$export' WHERE role_id = '$role_data_id' AND module_id= '$value' ";



            $insertRecord = mysqli_query($con,$updateQuery);

        }else{

            $role_id = $_GET['role_id'];

            $addQuery = "INSERT INTO `permissions`(`role_id`,`module_id`, `view_id`, `add_id`, `edit_id`, `delete_id`, `approve_id`, `print_id`, `import_id`, `export_id`) VALUES ('$role_id','$value','$view','$add','$edit','$delete','$approve','$print','$import','$export')";

           // echo $addQuery;

           // die;

            $insertRecord = mysqli_query($con,$addQuery);

        }



    }



    header("location:user_permission.php?role_id=".$_GET['role_id']);

  }

  if(isset($_SESSION['users_id']) && $_SESSION['type']=='admin'){
 require_once "includes/role_helper.php";
 if (!checkRolePermission($_SESSION['user_role_id'],24,'view_only',$comment =null)){
        header("location:access_denied.php");
    }
  include "includes/header.php";



  $modules = mysqli_query($con,"SELECT * FROM modules where deleted='0'");

  $roles = mysqli_query($con,"SELECT * FROM user_role");



  $permissions = mysqli_query($con,"SELECT * FROM permissions");



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



            <div class="page-header">

              <!-- <h3>User Permissions</h3> -->

            </div>
              <div class="row">
                <?php
            require_once "setup-sidebar.php";
          ?>
          <div class="col-sm-10 table-responsive" id="setting_box">
             <form method="POST" action="#">

              <div class="panel panel-primary">

                <div class="panel-heading"><?php echo getLange('userpermission'); ?> </div>

                <div class="panel-body">

                      <div class="row">

                          <div class="col-sm-4 colums_gapp">

                            <label><?php echo getLange('selectuserrule'); ?> </label>

                            <select class="form-control active_customer_detail js-example-basic-single"  onchange="window.location.href='user_permission.php?role_id='+this.value">

                                <?php foreach($roles as $role){ ?>

                                <option  <?php if(isset($_GET['role_id']) && $_GET['role_id'] == $role['id']){ echo "Selected"; } ?> value="<?php echo $role['id']; ?>"><?php echo $role['name']; ?></option>

                                <?php } ?>

                            </select>

                          </div>

                      </div>

                </div>

              </div>

              <div class="row cn_table">

                  <b><?php echo getLange('selectpermissionselected'); ?> </b>

                 <div class="col-sm-12 right_contents colums_gapp">

                    <div class="inner_contents table-responsive">

                        <table class="table_box">

                          <tbody>

                            <tr>

                            <th></th>

                            <th><?php echo getLange('view'); ?></th>

                            <th><?php echo getLange('add'); ?></th>

                            <th><?php echo getLange('edit'); ?></th>

                            <th><?php echo getLange('delete'); ?></th>

                            <th><?php echo getLange('approve'); ?></th>

                            <th><?php echo getLange('print'); ?></th>

                            <th><?php echo getLange('import'); ?></th>

                            <th><?php echo getLange('export'); ?></th>

                          </tr>

                          <?php while ($row = mysqli_fetch_array($modules)) {



                                if(isset($_GET['role_id']))
                                {

                                    $role_id = $_GET['role_id'];

                                    $module_id = $row['id'];

                                    $role_query = mysqli_query($con,"SELECT * FROM permissions WHERE role_id=".$role_id." AND module_id=".$module_id." ");

                                    $role_data = mysqli_fetch_array($role_query);
                                }



                            ?>

                             <tr>

                            <td><?php echo $row['name']; ?></td>

                            <input type="hidden" name="module_id[]" value="<?php echo $row['id']; ?>">

                            <td>
                                <?php if ($row['view_mode'] == 1): ?>
                                    <div id="app-cover">

                                      <div class="row">

                                        <div class="toggle-button-cover">

                                          <div class="button-cover">

                                            <div class="button r" id="button-1">

                                              <input type="checkbox" class="checkbox" name="view_<?php echo $row['id']; ?>" value="1" <?php if(isset($role_data['view_id']) && $role_data['view_id'] == 1){ echo "checked"; } ?>>

                                              <div class="knobs"></div>

                                              <div class="layer"></div>

                                            </div>

                                          </div>

                                        </div>

                                      </div>

                                    </div>
                                <?php endif ?>
                            </td>

                            <td>
                                <?php if ($row['add_mode'] == 1): ?>
                                    <div id="app-cover">

                                        <div class="row">

                                            <div class="toggle-button-cover">

                                              <div class="button-cover">

                                                <div class="button r" id="button-1">

                                                  <input type="checkbox" class="checkbox" name="add_<?php echo $row['id']; ?>" value="1" <?php if(isset($role_data['add_id']) && $role_data['add_id'] == 1){ echo "checked"; } ?>>

                                                  <div class="knobs"></div>

                                                  <div class="layer"></div>

                                                </div>

                                              </div>

                                            </div>

                                        </div>

                                    </div>
                                <?php endif ?>
                            </td>

                            <td>
                                <?php if ($row['edit_mode'] == 1): ?>
                                    <div id="app-cover">

                                      <div class="row">

                                        <div class="toggle-button-cover">

                                          <div class="button-cover">

                                            <div class="button r" id="button-1">

                                              <input type="checkbox" class="checkbox" name="edit_<?php echo $row['id']; ?>" value="1" <?php if(isset($role_data['edit_id']) && $role_data['edit_id'] == 1){ echo "checked"; } ?>>

                                              <div class="knobs"></div>

                                              <div class="layer"></div>

                                            </div>

                                          </div>

                                        </div>

                                      </div>

                                    </div>
                                <?php endif ?>
                            </td>

                            <td>
                                <?php if ($row['delete_mode'] == 1): ?>
                                    <div id="app-cover">

                                      <div class="row">

                                        <div class="toggle-button-cover">

                                          <div class="button-cover">

                                            <div class="button r" id="button-1">

                                              <input type="checkbox" class="checkbox" name="delete_<?php echo $row['id']; ?>" value="1" <?php if(isset($role_data['delete_id']) && $role_data['delete_id'] == 1){ echo "checked"; } ?>>

                                              <div class="knobs"></div>

                                              <div class="layer"></div>

                                            </div>

                                          </div>

                                        </div>

                                      </div>

                                    </div>
                                <?php endif ?>
                            </td>

                            <td>
                                <?php if ($row['approve_mode'] == 1): ?>
                                    <div id="app-cover">

                                      <div class="row">

                                        <div class="toggle-button-cover">

                                          <div class="button-cover">

                                            <div class="button r" id="button-1">

                                              <input type="checkbox" class="checkbox" name="approve_<?php echo $row['id']; ?>" value="1" <?php if(isset($role_data['approve_id']) && $role_data['approve_id'] == 1){ echo "checked"; } ?>>

                                              <div class="knobs"></div>

                                              <div class="layer"></div>

                                            </div>

                                          </div>

                                        </div>

                                      </div>

                                    </div>
                                <?php endif ?>
                            </td>

                            <td>
                                <?php if ($row['print_mode'] == 1): ?>
                                    <div id="app-cover">

                                      <div class="row">

                                        <div class="toggle-button-cover">

                                          <div class="button-cover">

                                            <div class="button r" id="button-1">

                                              <input type="checkbox" class="checkbox" name="print_<?php echo $row['id']; ?>" value="1" <?php if(isset($role_data['print_id']) && $role_data['print_id'] == 1){ echo "checked"; } ?>>

                                              <div class="knobs"></div>

                                              <div class="layer"></div>

                                            </div>

                                          </div>

                                        </div>

                                      </div>

                                    </div>
                                <?php endif ?>
                            </td>

                            <td>
                                <?php if ($row['import_excel_mode'] == 1): ?>
                                    <div id="app-cover">

                                      <div class="row">

                                        <div class="toggle-button-cover">

                                          <div class="button-cover">

                                            <div class="button r" id="button-1">

                                              <input type="checkbox" class="checkbox" name="import_<?php echo $row['id']; ?>" value="1" <?php if(isset($role_data['import_id']) && $role_data['import_id'] == 1){ echo "checked"; } ?>>

                                              <div class="knobs"></div>

                                              <div class="layer"></div>

                                            </div>

                                          </div>

                                        </div>

                                      </div>

                                    </div>
                                <?php endif ?>
                            </td>

                            <td>
                                <?php if ($row['export_excel_mode'] == 1): ?>
                                    <div id="app-cover">

                                      <div class="row">

                                        <div class="toggle-button-cover">

                                          <div class="button-cover">

                                            <div class="button r" id="button-1">

                                              <input type="checkbox" class="checkbox" name="export_<?php echo $row['id']; ?>" value="1" <?php if(isset($role_data['export_id']) && $role_data['export_id'] == 1){ echo "checked"; } ?>>

                                              <div class="knobs"></div>

                                              <div class="layer"></div>

                                            </div>

                                          </div>

                                        </div>

                                      </div>

                                    </div>
                                <?php endif ?>
                            </td>

                          </tr>

                         <?php } ?>



                        </tbody>

                    </table>



                    </div>

                 </div>

              </div>

           <div class="submit_btn rtl_full">

                <button type="submit" name="save_changes"><?php echo getLange('save'); ?></button>

            </div>

            </form>

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



