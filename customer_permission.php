<?php
   session_start();
//    ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
   include_once "includes/conn.php";
   if(isset($_SESSION['customers'])){
    $id = $_SESSION['customers'];
    require_once "includes/role_helper.php";
        if (!checkRolePermission(13 ,'view_only','')) {

            header("location:access_denied.php");
        }
       include "includes/header.php";
       
  if(isset($_POST['save_changes']))if(isset($_POST['save_changes'])){

    $role_id = $_POST['role_id'];

    foreach ($_POST['module_id'] as $key => $value) {

        if(isset($_SESSION['customers'])){

            $existing_role_id = $_SESSION['customers'];

            $role_query = mysqli_query($con,"SELECT * FROM customer_permissions WHERE role_id=".$existing_role_id." AND module_id=".$value." ");

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

           $updateQuery = "UPDATE `customer_permissions` SET `view_id`='$view',`add_id`='$add',`edit_id`='$edit',`delete_id`='$delete',`approve_id`='$approve',`print_id`='$print',`import_id`='$import',`export_id`='$export' WHERE role_id = '$role_data_id' AND module_id= '$value' ";



            $insertRecord = mysqli_query($con,$updateQuery);

        }else{

            $role_id = $_SESSION['customers'];

            $addQuery = "INSERT INTO `customer_permissions`(`role_id`,`module_id`, `view_id`, `add_id`, `edit_id`, `delete_id`, `approve_id`, `print_id`, `import_id`, `export_id`) VALUES ('$role_id','$value','$view','$add','$edit','$delete','$approve','$print','$import','$export')";

           // echo $addQuery;

           // die;

            $insertRecord = mysqli_query($con,$addQuery);

        }



    }



    header("location:user_permission.php");

  }


  $modules = mysqli_query($con,"SELECT * FROM customer_modules where deleted='0'");

  $permissions = mysqli_query($con,"SELECT * FROM customer_permissions");
   ?>
   <style type="text/css">

          .city_to option.hide {

            /*display: none;*/

          }

          .form-group{

            margin-bottom: 0px !important;

          }

        </style>
<section class="bg padding30">
   <div class="container-fluid dashboard">
      <div class="col-lg-2 col-md-3 col-sm-4 profile" style="margin-left:0">
         <?php
            include "includes/sidebar.php";
            ?>
      </div>
      <div class="col-lg-10 col-md-9 col-sm-8 dashboard">
         <div class="white" style="    margin-bottom: 25px;">
            <h4 class="Order_list" style="color:#000;">Customer Permissions</h4>
            <div class="panel panel-default">
               <div class="panel-heading">Customer Permissions
               </div>
               <div class="panel-body" id="same_form_layout" style="padding: 11px;">
                      <form method="POST" action="">
              <div class="row cn_table">
                 <div class="col-sm-12 right_contents colums_gapp">

                    <div class="inner_contents table-responsive">

                        <table class="table_box">

                          <tbody>

                            <tr>

                            <th style="width: 55%;"></th>

                            <th style="width: 15%;"><?php echo getLange('view'); ?></th>

                            <th style="width: 15%;"><?php echo getLange('add'); ?></th>

                            <th style="width: 15%;"><?php echo getLange('edit'); ?></th>

                          </tr>

                          <?php while ($row = mysqli_fetch_array($modules)) {

                                if(isset($_SESSION['customers']))
                                {

                                    $role_id = $_SESSION['customers'];

                                    $module_id = $row['id'];

                                    $role_query = mysqli_query($con,"SELECT * FROM customer_permissions WHERE role_id=".$role_id." AND module_id=".$module_id." ");

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

                            <!-- <td>
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
                            </td> -->

                            <!-- <td>
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
                            </td> -->

                            <!-- <td>
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
                            </td> -->

                            <!-- <td>
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
                            </td> -->

                            <!-- <td>
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
                            </td> -->

                          </tr>

                         <?php } ?>



                        </tbody>

                    </table>

                    </div>

                 </div>

              </div>

           <div class="submit_btn rtl_full">

                <button type="submit" name="save_changes" class="btn btn-info"><?php echo getLange('save'); ?></button>

            </div>

            </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
</div>
<?php
   }
   else{
       header("location:index.php");
   }
   ?>
<?php include 'includes/footer.php'; ?>
<script type="text/javascript">
    
    $(document).on('blur','.emailleee',function(){
    var email=$(this).val();
    var user_id=$('.user_id').val();
    var email_current=$(this);
    error=$(this).parent().find("div.help-block");
    if(email!=""){
        var postdata="cusaction=cusaction&cusemail="+email+"&user_id="+user_id;
        $.ajax({
            type:'POST',
            data:postdata,
            url:'ajax.php',
            success:function(fetch){
            error.html(fetch);
                    if(error.html()!==""){
                        $(email_current).parent().addClass("has-error").addClass("has-danger");
                        $('.submit').attr('disabled' , true);
                    }else{
                        $('.submit').attr('disabled' , false);
                         $('.help-block').html("");
                    }
                }
            });
        }
});
</script>