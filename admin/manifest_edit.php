<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
session_start();
require 'includes/conn.php';
require 'includes/role_helper.php';
if(isset($_SESSION['users_id']) && $_SESSION['type']=='admin')
{
     require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'],30,'add_only',$comment =null)) {

        header("location:access_denied.php");
    }
    include "includes/header.php";

    ?>


        <!-- Header Ends -->
        <body data-ng-app>
            <style type="text/css">
                .display_none{
                    display: none;
                }
            </style>

        <?php include "includes/sidebar.php"; ?>
        <!-- Aside Ends-->

        <section class="content">

            <?php include "includes/header2.php"; ?>

<?php

if (isset($_POST['submit'])) {

}

$records_query = "SELECT * from orders where origin IN($all_allowed_origins)";
$records_q = mysqli_query($con,$records_query);

$brnach_query = mysqli_query($con, "SELECT * from branches ");
if (isset($_SESSION['branch_id']) && !empty($_SESSION['branch_id'])) {
    $brnach_querys = mysqli_query($con, "SELECT * from branches WHERE id !=".$_SESSION['branch_id']);
}else{

    $brnach_querys = mysqli_query($con, "SELECT * from branches ");
}

$city_default = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='city' "));
$country_default = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='country' "));
$type_query = mysqli_query($con, "SELECT * from types ");
$mode_query = mysqli_query($con, "SELECT * from modes ");
$transport_q = mysqli_query($con, "SELECT * from transport_company Where mode_id = 1 ");
$service_by_q = mysqli_query($con, "SELECT * from manifest_services ");
$cities_q = mysqli_query($con, "SELECT * from cities ");
$city_q = mysqli_query($con, "SELECT * from cities ");
$status_querys = mysqli_query($con, "SELECT * from order_status ");
// $status_querys = mysqli_query($con, "SELECT * from order_status ");

$query = mysqli_query($con,"SELECT * from manifest_master where id = '".$_GET['id']."' ");
$data = mysqli_fetch_assoc($query);
$manifestdetail = mysqli_query($con,"SELECT * from manifest_detail where manifest_id = '".$_GET['id']."' ");
// $manifestdata = mysqli_fetch_assoc($manifestdetail);

// echo "<pre>";
// print_r($number);
// die();
 ?>

            <div class="warper container-fluid">
                <div class="alert alert-success display_none"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> You Edited Manifest Details successfully</div>
                <div class="alert alert-danger display_none"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not Added a Manifest Details .</div>
                <form id="save_manifest_form" method="POST">
                <div class="page-header"><h1><?php echo getLange('cnawbmanifestform'); ?> </h1></div>
                <div class="manifest_box">
                    <div class="row">
                        <div class="col-sm-6 colums_gapp">
                            <div class="colums_content">
                                <div class="col-sm-12 colums_gapp">
                                    <label><input class="with_auto" type="radio" value="Transit Manifest" name="check_manifest" checked>Transit Manifest</label>
                                    <label><input class="with_auto" type="radio" value="Return Manifest" name="check_manifest">Return Manifest</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 colums_gapp gray-bg">
                            <div class="row">
                                <div class="col-sm-6 colums_gapp">
                                    <div class="colums_content">
                                        <label><?php echo getLange('manifestno'); ?></label>
                                        <input type="text" value="<?php echo $data['manifest_no']; ?>" name="manifest_no" class="manifest_no" readonly="">
                                    </div>
                                </div>
                                <div class="col-sm-6 colums_gapp">
                                    <div class="colums_content">
                                        <label><?php echo getLange('date'); ?></label>
                                        <input type="date" placeholder="" value="<?php echo $data['date']; ?>" name="date" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 colums_gapp">
                                    <div class="colums_content">
                                        <label><?php echo getLange('type'); ?></label>
                                        <div class="row radio_box">
                                            <div class="col-sm-12 colums_gapp">
                                                <?php while ($row= mysqli_fetch_assoc($type_query)) {?>
                                                    <?php $checked = '';
                                                            // var_dump($data['type']);
                                                            // var_dump($row['id']);
                                                        if ($row['id'] == $data['type']) {
                                                            $checked = 'Checked';
                                                        }

                                                     ?>
                                                    <label> 
                                                        <input class="with_auto" type="radio" <?php echo $checked; ?> value="<?php echo $row['id']; ?>" name="type" > <?php echo $row['name']; ?>
                                                    </label>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 colums_gapp">
                                    <div class="colums_content">
                                        <label><?php echo getLange('mode'); ?></label>
                                        <div class="row radio_box">
                                            <div class="col-sm-12 colums_gapp">
                                                <?php while ($row= mysqli_fetch_assoc($mode_query)) {?>
                                                    <?php $checked = '';

                                                        if ($row['id']== $data['mode']) {
                                                            $checked = 'Checked';
                                                        }

                                                     ?>
                                                    <label> <input class="with_auto mode_type_name" <?php echo $checked; ?> type="radio" value="<?php echo $row['id']; ?>" name="mode" ><?php echo $row['name']; ?> </label>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 colums_gapp">
                                    <div class="colums_content">
                                        <label><?php echo getLange('biltyno'); ?> </label>
                                        <input type="text" placeholder="16407" name="bilty_no" value="<?= $data['bilty_no']?>">
                                    </div>
                                </div>
                                <div class="col-sm-6 colums_gapp">
                                    <div class="colums_content">
                                        <label><?php echo getLange('serviceby'); ?></label>
                                        <div class="row radio_box">
                                            <div class="col-sm-12 colums_gapp">
                                                <?php while ($row= mysqli_fetch_assoc($service_by_q)) {?>
                                                    <?php $checked = '';

                                                        if ($row['id']==$data['service_by']) {
                                                            $checked = 'Checked';
                                                        }

                                                     ?>
                                                    <label> <input class="with_auto" type="radio" <?php echo $checked; ?> value="<?php echo $row['id']; ?>" name="service_by" > <?php echo $row['name']; ?></label>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 colums_gapp">
                                    <div class="colums_content">
                                        <label><?php echo getLange('transportcompany'); ?></label>
                                        <select class=" transport_company" name="transport_company">
                                            <option value="" selected="">Select <?php echo getLange('transportcompany'); ?></option>
                                           <!--  <?php while ($row= mysqli_fetch_assoc($transport_q)) {?>
                                                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                            <?php } ?> -->
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 colums_gapp">
                                    <div class="colums_content">
                                        <label><?php echo getLange('truckno'); ?></label>
                                        <input type="text" placeholder="SLT-2012" name="truck_no" value="<?= $data['truck_no']?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 colums_gapp">
                                    <div class="colums_content">
                                        <label><?php echo getLange('sealno'); ?></label>
                                        <input type="text" name="seal_no" value="<?= $data['seal_no']?>">
                                    </div>
                                </div>
                                <div class="col-sm-8 colums_gapp">
                                    <div class="colums_content">
                                        <label><?php echo getLange('sendingbranch'); ?></label>
                                        <?php
                                        $sql = "SELECT * from branches where id= '".$data['sending_branch']."'"; 
                                        $brnach_querys = mysqli_query($con, $sql);
                                        $row = mysqli_fetch_array($brnach_querys);
                                        ?>
                                        <input type="text" name="" value="<?= $row['name']; ?>">
                                        <input type="hidden" name="sending_branch" value="<?= $row['id']; ?>">
                                        <!-- <select class=" sending_branch_val" name=""> -->
<!-- 
                                            <?php while ( $row = mysqli_fetch_assoc($brnach_query)) { 
                                                   $checked = '';

                                                        if ($row['id']==$data['sending_branch']) {
                                                            $checked = 'Selected';
                                                        }

                                                ?>
                                                <option value="<?php echo $row['id']; ?>" <?= $checked; ?>><?php echo $row['name']; ?></option>
                                            <?php } ?>
                                        </select> -->
                                        </div>
                                </div>
                            </div>


                        </div>
                        <div class="col-sm-1"></div>
                      <?php /*?>  <div class="col-sm-5  gray-bg colums_gapp" style="padding-right: 8px;">

                            <div class="row">
                                 <div class="col-sm-4 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('origin'); ?> (*)</label>
                                        <select type="text" class="form-control js-example-basic-single country origin"
                                            name="country1" id="country1">
                                          <!--   <option selected value="">
                                                <?php echo getLange('select') . ' ' . getLange('country'); ?></option> -->
                                            <?php 
                                            $country_query=mysqli_query($con,'SELECT * FROM country ORDER BY country_name ASC');
                                            while ($row = mysqli_fetch_array($country_query)) {
                                            // var_dump($row);
                                             ?>
                                            <option <?php echo isset($row['country_name']) && $row['country_name'] == $country_default['value'] ? 'selected' : ''; ?> value="<?php echo $row['country_name']; ?>">
                                                <?php echo getKeyWord($row['country_name']); ?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('city'); ?> </label>
                                        <select class="form-control js-example-basic-single city_origin"
                                            name="city1" id="city1">
                                            <option selected value="">
                                                <?php echo getLange('select') . ' ' . getLange('city'); ?></option>
                                                <option>Select City</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- <div class="col-sm-7 colums_gapp">
                                    <div class="colums_content">
                                        <label><?php echo getLange('origin'); ?> (*)</label>
                                        <div class="row">here

                                            <div class="col-sm-12 colums_gapp padd_none">
                                                  <select class="origin js-example-basic-single" name="origin" >
                                                        <option selected="" value=""><?php echo getLange('select').' '.getLange('origin'); ?></option>
                                                    <?php while ($row= mysqli_fetch_assoc($cities_q)) {?>
                                                        <option  data-code="<?php echo $value['city_code']; ?>" <?php echo $row['city_name']; ?>><?php echo $row['city_name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                      </div>
                                    </div>
                                </div> -->
                                <div class="col-sm-5 colums_gapp">
                                    <div class="colums_content">
                                        <label><?php echo getLange('receivingbranch'); ?>(*)</label>
                                        <select class="receiving_branch" name="receiving_branch">
                                           <option selected="" value=""><?php echo getLange('select').' '.getLange('branch'); ?></option>
                                            <?php while ( $row = mysqli_fetch_assoc($brnach_querys)) { ?>
                                                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div><div class="row">
                                 <div class="col-sm-4 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('destination'); ?> (*)</label>
                                        <select type="text" class="form-control js-example-basic-single country destination"
                                            name="country2" id="country2">
                                          <!--   <option selected value="">
                                                <?php echo getLange('select') . ' ' . getLange('country'); ?></option> -->
                                            <?php 
                                            $country_query=mysqli_query($con,'SELECT * FROM country ORDER BY country_name ASC');
                                            while ($row = mysqli_fetch_array($country_query)) {
                                            // var_dump($row);
                                             ?>
                                            <option <?php echo isset($row['country_name']) && $row['country_name'] == $country_default['value'] ? 'selected' : ''; ?> value="<?php echo $row['country_name']; ?>">
                                                <?php echo getKeyWord($row['country_name']); ?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('city'); ?> </label>
                                        <select class="form-control js-example-basic-single city_destination"
                                            name="city2" id="city2">
                                            <option selected value="">
                                                <?php echo getLange('select') . ' ' . getLange('city'); ?></option>
                                                <option>Select City</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- <div class="col-sm-7 colums_gapp">
                                    <div class="colums_content">
                                        <label><?php echo getLange('destination'); ?> (*)</label>
                                        <div class="row">

                                            <div class="col-sm-12 colums_gapp padd_none">
                                                  <select class="js-example-basic-single destination" name="destination" >
                                                        <option selected="" value=""><?php echo getLange('all').' '.getLange('destination'); ?></option>
                                                    <?php while ($row= mysqli_fetch_assoc($city_q)) {?>
                                                        <option  data-code="<?php echo $value['city_code']; ?>" <?php echo $row['city_name']; ?>><?php echo $row['city_name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                      </div>
                                    </div>
                                </div> -->
                                <div class="col-sm-5 colums_gapp">
                                    <div class="colums_content">
                                        <label><?php echo getLange('departuredate'); ?></label>
                                        <input type="date" name="departure_date" value="<?php echo date('Y-m-d'); ?>" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-5 colums_gapp">
                                    <div class="colums_content Receiver-person">
                                        <label><?php echo getLange('receiverpersonname'); ?>(*)</label>
                                    </div>
                                </div>
                                <div class="col-sm-7 colums_gapp">
                                    <div class="colums_content">
                                        <select class=" receiver_person" name="receiver_name" >
                                                <option selected="" value="0"><?php echo getLange('select'); ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 colums_gapp">
                                    <div class="colums_content">
                                        <label><?php echo getLange('Exclude').' '.getLange('destination'); ?></label>

                                        <select name="exclude_destination" class="js-example-basic-single exclude_destination" multiple>
                                       <?php 
                                       $exclude_city_q = mysqli_query($con, "SELECT * from cities ");
                                       while ($row= mysqli_fetch_assoc($exclude_city_q)) {?>
                                           <?php $selected = '';

                                                        // if ($row['sts_id']=='3' || $row['sts_id']=='5') {
                                                        //     $selected = 'selected';
                                                        // }

                                                     ?>
                                            <option  <?php echo $selected; ?> value="<?php echo $row['city_name'] ?>"><?php echo $row['city_name']; ?></option>
                                        <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 colums_gapp">
                                    <div class="colums_content">
                                        <label><?php echo getLange('selectstatuses'); ?></label>

                                        <select name="status" class="js-example-basic-single allowed_statuses" multiple>
                                        <?php while ($row= mysqli_fetch_assoc($status_querys)) {?>
                                            <?php $selected = '';

                                                        if ($row['sts_id']=='3' || $row['sts_id']=='5') {
                                                            $selected = 'selected';
                                                        }

                                                     ?>
                                            <option  <?php echo $selected; ?> value="<?php echo $row['status'] ?>"><?php echo $row['status']; ?></option>
                                        <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 colums_gapp">
                                    <div class="colums_content">
                                        <label><?php echo getLange('departuretime'); ?></label>
                                        <input type="time" name="departure_time" value="<?php echo date('H:i:s'); ?>" >
                                    </div>
                                </div>
                                <div class="col-sm-6 colums_gapp">
                                    <div class="colums_content">
                                        <label><?php echo getLange('via'); ?></label>
                                        <input type="text" name="pick_via" >
                                    </div>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col-sm-6 colums_gapp">
                                    <div class="colums_content">
                                        <label><?php echo getLange('arrivaldate'); ?></label>
                                        <input type="date" name="arrival_date" value="<?php echo date('Y-m-d'); ?>" >
                                    </div>
                                </div>
                                <div class="col-sm-6 colums_gapp">
                                    <div class="colums_content">
                                        <label><?php echo getLange('arrivaltime'); ?></label>
                                        <div class="row">
                                            <div class="col-sm-8 colums_gapp padd_none">
                                                <input type="time" name="arrival_time" value="<?php echo date('H:i:s'); ?>" >
                                            </div>
                                            <div class="col-sm-4 colums_gapp padd_none">
                                                <button type="button" class="submit_btns Pick_cn"><?php echo getLange('pickcn'); ?></button>
                                            </div>
                                      </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <?php
                            */
                        ?>
                    </div>

                </div>
                <div class="row" style="padding: 15px 0 0;">
                    <div class="col-sm-2 colums_gapp padd_none colums_content">
                       <input type="text" placeholder="<?php echo getLange('enter').' '.getLange('trackingno');; ?>" class="enter_cn">
                    </div>
                     <input type="hidden" class="user_role_id" value="<?php echo isset($_SESSION['user_role_id']) ? $_SESSION['user_role_id'] : '' ?>">
                     <input type="hidden" class="user_id" value="<?php echo isset($_SESSION['users_id']) ? $_SESSION['users_id'] : '' ?>">
                    <div class="col-sm-1 colums_gapp padd_none">
                        <button type="button"  class="submit_btns append_cn_no submit_cn" ><?php echo getLange('submit'); ?></button>
                    </div>
              </div>

                <div class="row cn_table">
                     <div class="col-sm-12 right_contents">
                        <div class="inner_contents table-responsive">
                            <table class="table_box response_table_body" id="table_insert">
                              <thead>
                                <tr>
                                  <th>CN#</th>
                                  <th>Ser</th>
                                  <th>Consigner</th>
                                  <th>Origin</th>
                                  <th>City</th>
                                  <th>Consignee</th>
                                  <th>Destination</th>
                                  <th>City</th>
                                  <th>PCS</th>
                                  <th>Weight</th>
                                  <th>Action</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                $i = 0;
                                    while($row = mysqli_fetch_assoc($manifestdetail)){
                                        $query = mysqli_query($con, "SELECT * From orders where track_no = '".$row['track_no']."'");
                                      $data = mysqli_fetch_array($query);
                                      $service_type_q = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM services WHERE id=" . $data['order_type']));
                                            $service_type = $service_type_q['service_type'];
                                      $i++;
                                ?>
                                <tr>
                                  <td><?= $data['track_no'];?></td>
                                  <td><?= $service_type; ?></td>
                                  <td><?= $data['sname']; ?></td>
                                  <td><?= $data['origin']; ?></td>
                                  <td><?= $data['scity']; ?></td>
                                  <td><?= $data['rname']; ?></td>
                                  <td><?= $data['destination']; ?></td>
                                  <td><?= $data['rcity']; ?></td>
                                  
                                  <td><?= $data['quantity']; ?> <input type="hidden" class="hidden_qunatity_value" value="<?= $data['quantity']; ?>">
                                  </td>
                                  <td><?= $data['weight']; ?><input type="hidden" class="hidden_weight" value="<?= $data['weight']?>">
                                  </td>
                                  <td>
                                    <a data-wt="0.5" data-qt="1" style="cursor:pointer" title="Trash" cn_no="<?= $data['track_no'];?>" class="delete_row del_row">
                                      <i class="fa fa-trash "></i>
                                    </a>
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


                  <div class="manifest_box">
                    <div class="row">
                        <div class="col-sm-9 colums_gapp">
                            <div class="colums_content">
                                <label><?php echo getLange('remarks'); ?></label>
                                <textarea type="text" class="remarks" name="remarks" style="margin: 0px 53.3281px 0px 0px;height: 50px;width: 100%;display: block;"><?= $data['remarks']; ?></textarea>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="row">
                                <div class="col-sm-6 colums_gapp">
                                    <div class="colums_content">
                                        <label><?php echo getLange('pcs'); ?></label>
                                        <input type="text" placeholder="00" value="<?= $data['pieces']?>" name="pieces" class="total_pieces" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6 colums_gapp">
                                    <div class="colums_content">
                                        <label><?php echo getLange('weight'); ?></label>
                                        <input type="text" placeholder="00" name="weight" value="<?= $data['weight']?>" class="total_weight" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 colums_gapp">
                                    <div class="colums_content skip-bag">
                                        <label><?php echo getLange('orderupdatestatus'); ?>   (*)</label>
                                        
                                            <select name="status" class="status js-example-basic-single">
                                            <option value="">None</option>
                                        <?php 
                                       while ($row= mysqli_fetch_assoc($status_querys)) {?>
                                            <?php
                                            $selected = '';
                                            // var_dump($row['status']);
                                            if($row['status'] == $data['status'])
                                            {
                                                $selected = 'Selected';
                                            }

                                             ?>
                                            <option value="<?php echo $row['status'] ?>" <?php echo $selected; ?>><?php echo $row['status']; ?></option>
                                        <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('Country'); ?> </label>
                                        <select type="text" class="form-control js-example-basic-single country"
                                            name="country" id="country">
                                          <!--   <option selected value="">
                                                <?php echo getLange('select') . ' ' . getLange('country'); ?></option> -->
                                            <?php 
                                            $country_query=mysqli_query($con,'SELECT * FROM country ORDER BY country_name ASC');
                                            while ($row = mysqli_fetch_array($country_query)) {
                                            // var_dump($row);
                                             ?>
                                            <option <?php echo isset($row['country_name']) && $row['country_name'] == $country_default['value'] ? 'selected' : ''; ?> value="<?php echo $row['country_name']; ?>">
                                                <?php echo getKeyWord($row['country_name']); ?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('city'); ?> </label>
                                        <select class="form-control js-example-basic-single city"
                                            name="city" id="city">
                                            <option selected value="">
                                                <?php echo getLange('select') . ' ' . getLange('city'); ?></option>
                                                <option>Select City</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('Date Time'); ?> </label>
                                        <input type="text" class="form-control datetimepicker" name="created_on" value="<?php echo date('Y-m-d H:i:s');?>"> 
                                    </div>
                                </div>
                                <div class="col-sm-6 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('Tracking Remarks'); ?> </label>
                                        <input type="text" name="tracking_remarks" class="form-control tracking_remarks"> 
                                    </div>
                                </div>
                                <div class="row save_print_btn">
                                    <div class="col-sm-12 colums_gapp padd_none">
                                        <button data-val="0" type="submit" class="submit_btns save_manifest"><?php echo getLange('save'); ?></button>
                                        <button data-val="1" style="background: #286fad;" type="submit" class="submit_btns save_print_manifest save_manifest"><?php echo getLange('saveprint'); ?></button>
                                    </div>
                              </div>
                            </div>
                        </div>
                    </div>


                </div>

                </form>

                <?php include "pages/manifest/manifestform.php"; ?>

            </div>


          <?php include "includes/footer.php";
} else{
    header("location:index.php");
}
?>


<script type="text/javascript">

$(document).ready(function(){

        // alert();
          $.ajax({
        url: 'ajax_new_country.php',
        type: "Post",
        async: true,
        data: { 
            name:$('#country').val()
        },
        success: function (data) {
           // alert(data);
           $('#city').html(data);

        },
        error: function (xhr, exception) {
        }
    }); 
     $('#country').on('change' , function(){
        // alert();
          $.ajax({
        url: 'ajax_new_country.php',
        type: "Post",
        async: true,
        data: { 
            name:$(this).val()
        },
        success: function (data) {
           // alert(data);
           $('#city').html(data);

        },
        error: function (xhr, exception) {
           
           
        }
    }); 
    });
});

$(document).ready(function(){

        // alert();
          $.ajax({
        url: 'ajax_new_country.php',
        type: "Post",
        async: true,
        data: { 
            name:$('#country1').val()
        },
        success: function (data) {
           // alert(data);
           $('#city1').html(data);

        },
        error: function (xhr, exception) {
        }
    }); 
     $('#country1').on('change' , function(){
        // alert();
          $.ajax({
        url: 'ajax_new_country.php',
        type: "Post",
        async: true,
        data: { 
            name:$(this).val()
        },
        success: function (data) {
           // alert(data);
           $('#city1').html(data);

        },
        error: function (xhr, exception) {
           
           
        }
    }); 
    });
});

$(document).ready(function(){

        // alert();
          $.ajax({
        url: 'ajax_new_country.php',
        type: "Post",
        async: true,
        data: { 
            name:$('#country2').val()
        },
        success: function (data) {
           // alert(data);
           $('#city2').html(data);

        },
        error: function (xhr, exception) {
        }
    }); 
     $('#country2').on('change' , function(){
        // alert();
          $.ajax({
        url: 'ajax_new_country.php',
        type: "Post",
        async: true,
        data: { 
            name:$(this).val()
        },
        success: function (data) {
           $('#city2').html(data);
        },
        error: function (xhr, exception) {  
        }
    }); 
    });
});

 function totalPieces(){
    let totalpcs = 0;
    let nextTotal = $('body').find('.response_table_body').find('tr').find('td').eq(6).html();
    $('body').find('.hidden_qunatity_value').each(function(index,value)
    {
        totalpcs +=parseFloat($(this).val());
    });
    $('body').find('.total_pieces').val(totalpcs);
}

function totalWeight(){
    let totalweight = 0;
    let nextTotal = $('body').find('.response_table_body').find('tr').find('td').eq(6).html();
    $('body').find('.hidden_weight').each(function(index,value)
    {
        totalweight +=parseFloat($(this).val());
    });
    $('body').find('.total_weight').val(totalweight);
}


$(document).on('click','.delete_row',function(){

    var data_wt=$(this).attr('data-wt');
    var data_qt=$(this).attr('data-qt');
    $(this).closest ('tr').remove ();
    var total_wt=$('.total_weight').val();
    var total_pc=$('.total_pieces').val();
    total_pcs=total_pc-data_qt;
    total_wts=total_wt-data_wt;
    $('.total_weight').val("");
    $('.total_weight').val(total_wts);
    $('.total_pieces').val("");
    $('.total_pieces').val(total_pcs);
});



$(document).on("click", ".mode_type_name", function () {

    var a = $('.mode_type_name:checked').val();
    $.ajax({
        url: 'ajax_edit.php',
        type: 'POST',
        data:{mode_id:a},
        cache:false,
        success: function (response) {
           $(".transport_company").html(response);
        }
    });
});

function appendNextRow(){
    var length = $('body').find('.response_table_body').find('tr').length;
    var a = $('body').find('.enter_cn').val();
    var user_role_id = $('body').find('.user_role_id').val();
    var users_id = $('body').find('.user_id').val();
    var tbody = $('body').find('.response_table_body').find('tr');
    var existing_array=[];
    tbody.each(function(index){
        existing_array.push($(this).find('.all_cn_no').val());
    });
    var flag =false;
    tbody.each(function(index){
        if($.inArray(a, existing_array) !== -1){
            flag = true;
            return false;
        }else{
            existing_array.push($(this).find('.all_cn_no').val());
        }
    });
    if(flag){
        flag = false;
        Swal.fire({
                     position: 'bottom-end',
                     icon: 'success',
                     title: 'Track no already exists.',
                     showConfirmButton: false,
                     timer: 2500
                })
        return false;
    }
    $.ajax({
        url: 'ajax_edit.php',
        type:"post",
        dataType:"json",
        data:{enter_cn:a,user_role_id:user_role_id,users_id:users_id,length:length},
        success: function (response) {
            console.log(response.table);
            $('body').find(".response_table_body").append(response.table);
            $('body').find('.enter_cn').val('');
            $('body').find('.enter_cn').focus();
            totalPieces();
            totalWeight();
            if (response.error == 1) {
                Swal.fire({
                     position: 'bottom-end',
                     icon: 'error',
                     title: response.msg,
                     showConfirmButton: false,
                     timer: 2500
                });
            }
         }
    });
}

$('body').on('keydown','.enter_cn',function(event){
    if(event.keyCode == 13)
    {
        appendNextRow();
       event.preventDefault();
    }
});

$(document).on("click", ".append_cn_no", function (event) {
    event.preventDefault();
    appendNextRow();

});

$(document).on("change", ".receiving_branch", function () {
    var a = $('.receiving_branch').val();
    $.ajax({
        url: 'ajax_edit.php',
        type: 'POST',
        data:{receiving_branch:a},
        cache:false,
        success: function (response) {
           $(".receiver_person").html(response);
        }
    });
});

    $(document).on("click", ".Pick_cn", function () {
        var allowed_statuses = $('.allowed_statuses').val();
        if( allowed_statuses !=='' )
        {
            var origin = $('.origin').val();
            var destination = $('.destination').val();
             var city_origin = $('.city_origin').val();
            var city_destination = $('.city_destination').val();
            // var destination = $('.destination').val();
            var allowed_statuses = $('.allowed_statuses').val();
            var exclude_destination = $('.exclude_destination').val();
            var sending_branch = $('.sending_branch_val').val();
            alert(sending_branch);


             $.ajax({
                    url: 'ajax_edit.php',
                    type: 'POST',
                    data:{origin:origin, destination:destination,
                        city1:city_origin,city2:city_destination,
                        pick_cn:1,allowed_statuses:allowed_statuses,exclude_destination:exclude_destination,sending_branch:sending_branch},
                    cache:false,
                    success: function (response) {

                        $(".inner_contents").html(response);

                        $("#basic-datatable").dataTable();

                        var totalpcs = $('body').find(".pieces").val();
                        var weight = $('body').find(".new_weight").val();


                        $(".total_pieces").val(totalpcs);
                        $(".total_weight").val(weight);
                    }
                });
        }else{
            Swal.fire({
                     position: 'bottom-end',
                     icon: 'success',
                     title: 'Please select status first.',
                     showConfirmButton: false,
                     timer: 2500
                })
        }
    });

    $(document).on("click", ".save_manifest", function (e) {
        // alert('1');
        e.preventDefault();
        var length = $('body').find('.response_table_body').find('tr').length;
        var print_value=$(this).attr('data-val');
        // alert(print_value);
        if (length < 1) {
            Swal.fire({
                     position: 'bottom-end',
                     icon: 'success',
                     title: 'Please select track no to manifest.',
                     showConfirmButton: false,
                     timer: 2500
                })
        }else{
            var receiving_branch = $('body').find('.receiving_branch').val();
            var origin           = $('body').find('.origin').val();
            var destination      = $('body').find('.destination').val();
            var status           = $('body').find('.status').val();
            var tracking_remarks = $('body').find('.tracking_remarks').val();
            var country          = $('body').find('.country').val();
            var city             = $('body').find('.city').val();
            // alert(country);
            if(status ==''){
                Swal.fire({
                     position: 'bottom-end',
                     icon: 'success',
                     title: 'Please select status first.',
                     showConfirmButton: false,
                     timer: 2500
                })
            }else{
                var base_url= '<?php echo BASE_URL; ?>';
                $.ajax({
                    type: "POST",
                    url: "edit_manifest.php",
                    dataType:"json",
                    data: $('#save_manifest_form').serialize(),
                    beforeSend: function() {
                        $('body').find('.submit_btns').attr('disabled', 'disabled');
                        $('body').find('.submit_btns').css('cursor', 'no-drop');
                    },
                    success: function(msg){
                        console.log(msg.bilty_no);
                        $('body').find('.response_table_body').html('');
                        $('.manifest_no').val(msg.bilty_no);
                        if(print_value == 1){
                            window.open(base_url+"admin/manifest.php?print_id="+msg.insert_id);
                        }

                        if(msg.bilty_no > 0)
                        {
                            $(".alert-success").removeClass("display_none");
                        }else{
                           $(".alert-danger").removeClass("display_none");
                        }

                        $('.submit_btns').removeAttr('disabled');
                        $('body').find('.submit_btns').css('cursor', 'pointer');

                    }
                });
            }
        }
    });
</script>
<script type="text/javascript">
    $('#search_city').on('keyup',function(event){
        event.preventDefault();
        if (event.key == 'Enter' || event.keyCode == 13) {
            event.preventDefault();
            var city_search = $('#search_city').val();
            $(".origin > option").each(function() {
                var data  =  $(this).attr('data-code');
                if(city_search.toUpperCase() == data)
                {
                    var value  =  $(this).val();
                    $('.origin').val(value).trigger('change');
                }
            });
        }
    });
</script>
<script type="text/javascript">
    $('#destination_city').on('keyup',function(event){
        event.preventDefault();
        if (event.key == 'Enter' || event.keyCode == 13) {
            event.preventDefault();
            var city_search = $('#destination_city').val();
            $(".destination > option").each(function() {
                var data  =  $(this).attr('data-code');

                if(city_search.toUpperCase() == data)
                {
                    var value  =  $(this).val();
                    $('.destination').val(value).trigger('change');
                }
            });
        }
    });
</script>
<script>
    $('.del_row').on('click',function(){
        var cn_no = $(this).attr('cn_no');
        var manifest_no = $('.manifest_no').val();
         $.ajax({
        url: 'ajax_edit.php',
        type: "Post",
        async: true,
        data: {cn_no:cn_no ,delete_track:1, manifest_no:manifest_no},
        success: function (data) {
            if(data == '1'){
                window.open("manifest_report.php", "_self");
            }
        },
        error: function (xhr, exception) {
        }
    }); 
    });
</script>
