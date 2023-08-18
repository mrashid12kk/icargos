<?php

  session_start();

  require 'includes/conn.php';



  if(isset($_SESSION['users_id']) && $_SESSION['type']=='admin'){
 require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'], 60,'view_only',$comment =null)) {

        header("location:access_denied.php");
    }
  include "includes/header.php";

    $admin_other_charges        = getconfig('admin_other_charges');
    $admin_extra_charges        = getconfig('admin_extra_charges');
    $admin_insured_premium      = getconfig('admin_insured_premium');
    $customer_other_charges     = getconfig('customer_other_charges');
    $customer_extra_charges     = getconfig('customer_extra_charges');
    $customer_insured_premium   = getconfig('customer_insured_premium');

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

    if(isset($_POST['save_charges'])){
        $admin_other_charges        = isset($_POST['admin_other_charges']) ? 1 : 0;
        $admin_extra_charges        = isset($_POST['admin_extra_charges']) ? 1 : 0;
        $admin_insured_premium      = isset($_POST['admin_insured_premium']) ? 1 : 0;
        $customer_other_charges     = isset($_POST['customer_other_charges']) ? 1 : 0;
        $customer_extra_charges     = isset($_POST['customer_extra_charges']) ? 1 : 0;
        $customer_insured_premium   = isset($_POST['customer_insured_premium']) ? 1 : 0;

        mysqli_query($con,"UPDATE config SET value='".$admin_other_charges."' WHERE `name`='admin_other_charges' ");
        mysqli_query($con,"UPDATE config SET value='".$admin_extra_charges."' WHERE `name`='admin_extra_charges' ");
        mysqli_query($con,"UPDATE config SET value='".$admin_insured_premium."' WHERE `name`='admin_insured_premium' ");
        mysqli_query($con,"UPDATE config SET value='".$customer_other_charges."' WHERE `name`='customer_other_charges' ");
        mysqli_query($con,"UPDATE config SET value='".$customer_extra_charges."' WHERE `name`='customer_extra_charges' ");
        mysqli_query($con,"UPDATE config SET value='".$customer_insured_premium."' WHERE `name`='customer_insured_premium' ");
        echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> Charges settings saved successfully.</div>';
    }
          ?>

          <div class="col-sm-10 table-responsive" id="setting_box">
             <form method="POST" action="#">
              <div class="panel panel-primary">
                <div class="panel-heading"><?php echo getLange('admin').' '.getLange('bookingform'); ?></div>
                <div class="row cn_table">
                 <div class="col-sm-12 right_contents colums_gapp " id="Charges_boxes">
                    <div class="inner_contents table-responsive">
                       <table class="table_box">
                          <tbody>
                             <tr>
                                <th><?php echo getLange('othercharges'); ?></th>
                                <!-- <th>Extra Charges</th> -->
                                <th><?php echo getLange('insurancepremium'); ?></th>
                             </tr>
                             <tr>
                                <td>
                                   <div id="app-cover">
                                      <div class="row">
                                         <div class="toggle-button-cover">
                                            <div class="button-cover">
                                               <div class="button r" id="button-1">
                                                <?php
                                                $a_o_c_c = '';
                                                if ($admin_other_charges==1) {
                                                    $a_o_c_c = 'checked';
                                                }
                                                 ?>
                                                  <input type="checkbox" class="checkbox" name="admin_other_charges" value="1"
                                                  <?php echo $a_o_c_c; ?>>
                                                  <div class="knobs"></div>
                                                  <div class="layer"></div>
                                               </div>
                                            </div>
                                         </div>
                                      </div>
                                   </div>
                                </td>
                                <!-- <td>
                                 <div id="app-cover">
                                    <div class="row">
                                       <div class="toggle-button-cover">
                                          <div class="button-cover">
                                             <div class="button r" id="button-1">
                                                <?php
                                                $ad_ex_ch = '';
                                                if ($admin_extra_charges==1) {
                                                    $ad_ex_ch = 'checked';
                                                }
                                                 ?>
                                                <input type="checkbox" class="checkbox" name="admin_extra_charges" value="1" <?php echo $ad_ex_ch; ?>>
                                                <div class="knobs"></div>
                                                <div class="layer"></div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </td> -->
                              <td>
                               <div id="app-cover">
                                  <div class="row">
                                     <div class="toggle-button-cover">
                                        <div class="button-cover">
                                           <div class="button r" id="button-1">
                                            <?php
                                                $a_insured = '';
                                                if ($admin_insured_premium==1) {
                                                    $a_insured = 'checked';
                                                }
                                                 ?>
                                              <input type="checkbox" class="checkbox" <?php echo $a_insured; ?> name="admin_insured_premium" value="1">
                                              <div class="knobs"></div>
                                              <div class="layer"></div>
                                           </div>
                                        </div>
                                     </div>
                                  </div>
                               </div>
                            </td>
                             </tr>
                          </tbody>
                       </table>
                    </div>
                 </div>
              </div>

              </div>

              <div class="panel panel-primary">
                <div class="panel-heading"><?php echo getLange('customer').' '.getLange('bookingform'); ?></div>
                <div class="row cn_table">
                 <div class="col-sm-12 right_contents colums_gapp " id="Charges_boxes">
                    <div class="inner_contents table-responsive">
                       <table class="table_box">
                          <tbody>
                             <tr>
                                <th><?php echo getLange('othercharges') ?></th>
                                <th><?php echo getLange('extracharges'); ?></th>
                                <th><?php echo getLange('insurancepremium'); ?></th>
                             </tr>
                             <tr>
                                <td>
                                   <div id="app-cover">
                                      <div class="row">
                                         <div class="toggle-button-cover">
                                            <div class="button-cover">
                                               <div class="button r" id="button-1">
                                                <?php
                                                $cus_other_charge = '';
                                                if ($customer_other_charges==1) {
                                                    $cus_other_charge = 'checked';
                                                }
                                                 ?>
                                                  <input type="checkbox" class="checkbox" <?php echo $cus_other_charge; ?> name="customer_other_charges" value="1">
                                                  <div class="knobs"></div>
                                                  <div class="layer"></div>
                                               </div>
                                            </div>
                                         </div>
                                      </div>
                                   </div>
                                </td>
                                <td>
                                 <div id="app-cover">
                                    <div class="row">
                                       <div class="toggle-button-cover">
                                          <div class="button-cover">
                                             <div class="button r" id="button-1">
                                                <?php
                                                $cus_ex_ch = '';
                                                if ($customer_extra_charges==1) {
                                                    $cus_ex_ch = 'checked';
                                                }
                                                 ?>
                                                <input type="checkbox" class="checkbox" <?php echo $cus_ex_ch; ?> name="customer_extra_charges" value="1">
                                                <div class="knobs"></div>
                                                <div class="layer"></div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </td>
                              <td>
                               <div id="app-cover">
                                  <div class="row">
                                     <div class="toggle-button-cover">
                                        <div class="button-cover">
                                           <div class="button r" id="button-1">
                                            <?php
                                                $cus_insured = '';
                                                if ($customer_insured_premium==1) {
                                                    $cus_insured = 'checked';
                                                }
                                                 ?>
                                              <input type="checkbox" class="checkbox" <?php echo $cus_insured; ?> name="customer_insured_premium" value="1">
                                              <div class="knobs"></div>
                                              <div class="layer"></div>
                                           </div>
                                        </div>
                                     </div>
                                  </div>
                               </div>
                            </td>
                             </tr>
                          </tbody>
                       </table>
                    </div>
                 </div>
              </div>

              </div>

           <div class="submit_btn rtl_full" style="padding:0;">
                <button type="submit" name="save_charges"><?php echo getLange('save'); ?></button>
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



