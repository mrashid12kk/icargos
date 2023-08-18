<?php
session_start();



require 'includes/conn.php';

if (isset($_SESSION['users_id']) && $_SESSION['type'] == 'admin') {

    require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'], 48, 'add_only', $comment = null)) {
        header("location:access_denied.php");
    }

    include "includes/header.php";



    $origincitydata = mysqli_query($con, "SELECT areas.*, cities.city_name from areas left join cities on areas.city_name=cities.id");







    $riderdata = mysqli_query($con, "Select * from users WHERE type='driver' ");



    $servicetypes = mysqli_query($con, "Select * from products  ");

    $country = mysqli_query($con, "SELECT * FROM country ORDER bY id DESC");
    $state = mysqli_query($con, "SELECT * FROM state ORDER bY id DESC");
    $cities = mysqli_query($con, "SELECT * FROM cities ORDER bY id DESC");



?>

<body data-ng-app>
    <style type="text/css">
    .label {
        display: inline;
        padding: .2em .6em .3em;
        font-size: 100%;
        font-weight: bold;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: .25em;
        float: left;
        margin: 2px;
        width: 100%;
    }

    .city_dropdown {
        max-height: 186px;
        overflow-y: auto;
        overflow-x: hidden;
        min-height: auto;
    }
    </style>
    <?php
        include "includes/sidebar.php";
        ?>
    <!-- Aside Ends-->
    <section class="content">
        <?php
            include "includes/header2.php";
            $msg = "";
            if (isset($_POST['addareas'])) {

                $check = mysqli_query($con, "SELECT * FROM delivery_zone WHERE route_code='" . $_POST['route_code'] . "'");
                $rowscount = mysqli_affected_rows($con);
                if ($rowscount > 0) {
                    $msg = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> This ' . $_POST['route_code'] . ' Route Code is Already Exist</div>';
                    $edit = $_POST;
                } else {

                    $route_name = $_POST['route_name'];

                    $product = $_POST['product'];

                    $route_code = $_POST['route_code'];

                    $country_id = $_POST['country_id'];

                    $state_id = $_POST['state_id'];

                    $city_id = $_POST['city_id'];

                    $pickup_commission = $_POST['pickup_commission'];

                    $delivery_commission = $_POST['delivery_commission'];
                    $zonesql =  "INSERT INTO `delivery_zone`(`route_name`, `route_code`,`country_id`, `state_id`, `city_id`, `pickup_commission`, `delivery_commission`, `product`) VALUES ('$route_name','$route_code','$country_id','$state_id','$city_id','$pickup_commission','$delivery_commission',$product)";

                    $insert_master  = mysqli_query($con, $zonesql);

                    if ($insert_master) {

                        $master_id = mysqli_insert_id($con);

                        foreach ($_POST['areas'] as $key => $value) {

                            $area_name = $_POST['areas'][$key];

                            $area_query =  mysqli_query($con, "INSERT INTO `delivery_zone_areas`(`delivery_zone_id`, `zone_area_name`) VALUES ($master_id,$area_name)");
                        }
                    }

                    $rowscount = mysqli_affected_rows($con);

                    if ($rowscount > 0) {

                        $msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>' . getLange('Well_done') . '!</strong> Delivery Route created successfully</div>';
                    } else {

                        $msg = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>' . getLange('unsuccessful') . '!</strong> Delivery Route not crated.</div>';
                    }
                }
                // header("location:add_delivery_zone.php");

            }



            echo $msg;



            ?>
        <!-- Header Ends -->
        <div class="warper container-fluid">
            <div class="page-header">
                <h1><?php echo getLange('deliveryarealist'); ?> <small><?php echo getLange('letsgetquick'); ?></small>
                </h1>
            </div>
            <div class="row">
                <?php
                    require_once "setup-sidebar.php";
                    ?>
                <div class="col-sm-10 table-responsive" id="setting_box">
                    <div class="panel panel-default">
                        <div class="panel-heading">Add Delivery Route<span style="float: right;"></span>
                        </div>
                        <div class="panel-body" id="same_form_layout">
                            <form action="" method="post">
                                <div id="cities">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Select Country</label>
                                                <select required class="form-control select2 country" name="country_id">
                                                    <option value="">Select Country</option>
                                                    <?php if (isset($country) && !empty($country)) {
                                                            foreach ($country as $row) { ?>
                                                    <option value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>"
                                                        <?php echo isset($edit) && $edit['country_id'] == $row['id'] ? 'slected' : ''; ?>>
                                                        <?php echo isset($row['country_name']) ? $row['country_name'] : ''; ?>
                                                    </option>
                                                    <?php }
                                                        } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Select State</label>
                                                <div class="get_state">
                                                    <select required class="form-control select2 state" name="state_id">
                                                        <option value="">Select State</option>
                                                        <?php if (isset($state) && !empty($state)) {
                                                                foreach ($state as $row) { ?>
                                                        <option
                                                            value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>"
                                                            <?php echo isset($edit) && $edit['state_id'] == $row['id'] ? 'slected' : ''; ?>>
                                                            <?php echo isset($row['state_name']) ? $row['state_name'] : ''; ?>
                                                        </option>
                                                        <?php }
                                                            } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Select City</label>
                                                <div class="get_city">
                                                    <select required class="form-control select2" name="city_id">
                                                        <option value="">Select City</option>
                                                        <?php if (isset($cities) && !empty($cities)) {
                                                                foreach ($cities as $row) { ?>
                                                        <option
                                                            value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>"
                                                            <?php echo isset($edit) && $edit['city_id'] == $row['id'] ? 'slected' : ''; ?>>
                                                            <?php echo isset($row['city_name']) ? $row['city_name'] : ''; ?>
                                                        </option>
                                                        <?php }
                                                            } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Route Code</label>
                                                <input type="text" class="form-control" name="route_code"
                                                    value="<?php echo isset($edit) ? $edit['route_code'] : '' ?>"
                                                    placeholder="Route Code" required>
                                                <div class="help-block with-errors "></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Route Name</label>
                                                <input type="text" class="form-control" name="route_name"
                                                    value="<?php echo isset($edit) ? $edit['route_name'] : '' ?>"
                                                    placeholder="Route Name" required>
                                                <div class="help-block with-errors "></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Pickup Commission</label>
                                                <input type="text" class="form-control" name="pickup_commission"
                                                    value="<?php echo isset($edit) ? $edit['pickup_commission'] : '' ?>"
                                                    placeholder="Delivery Commission" required>
                                                <div class="help-block with-errors "></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Delivery Commission</label>
                                                <input type="text" class="form-control" name="delivery_commission"
                                                    value="<?php echo isset($edit) ? $edit['delivery_commission'] : '' ?>"
                                                    placeholder="Delivery Commission" required>
                                                <div class="help-block with-errors "></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Product</label>
                                                    <select class="form-control select2" name="product" required>
                                                        <option value="">--select product--</option>
                                                        <?php if (isset($servicetypes) && !empty($servicetypes)) {
                                                                foreach ($servicetypes as $row) { ?>
                                                        <option
                                                            value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>"
                                                            <?php echo isset($edit) && $edit['product'] == $row['id'] ? 'slected' : ''; ?>>
                                                            <?php echo isset($row['name']) ? $row['name'] : ''; ?>
                                                        </option>
                                                        <?php }
                                                            } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="panel panel-default">
                                        <div class="panel-heading"><?php echo getLange('addareas'); ?></div>
                                        <div class="panel-body">
                                            <table class="table add_cities" id="price_table">
                                                <thead>
                                                    <tr>
                                                        <th><?php echo getLange('area'); ?></th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <?php
                                                    $loop = 0;



                                                    mysqli_data_seek($origincitydata, 0);



                                                    ?>
                                                <tr>
                                                    <td>
                                                        <div class="form-group">
                                                            <select class="form-control" name="areas[]">
                                                                <?php while ($row = mysqli_fetch_assoc($origincitydata)) { ?>
                                                                <option value="<?php echo $row['id']; ?>">
                                                                    <?php echo $row['area_name'] . ' (' . $row['city_name'] . ')'; ?>
                                                                </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td>
                                                    </td>
                                                    <td>
                                                        <?php if ($loop == 0) { ?>
                                                        <a href="#" class="add_row btn btn-info"><i
                                                                class="fa fa-plus"></i></a>
                                                        <?php } else { ?>
                                                        <a href="#" class="remove_row btn btn-danger"><i
                                                                class="fa fa-trash"></i></a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                    $loop++;



                                                    ?>
                                            </table>
                                            <div class="row">
                                                <div class="col-md-4 submit_padd">
                                                    <input type="submit" name="addareas" class="add_form_btn"
                                                        value="<?php echo getLange('submit'); ?>">
                                                    <!-- <button type="submit" name="addareas"
                                                        class="add_form_btn"><?php echo getLange('submit'); ?></button> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Warper Ends Here (working area) -->
                <?php
                include "includes/footer.php";
            } else {



                header("location:index.php");
            }



                ?>
                <script type="text/javascript">
                $('.select2').select2();


                $(document).ready(function() {



                    var counter = 1;



                    var selected_to_array = [];















                    // updateSelectedCites();



                    // $('body').on('change', '#price_table .city_to', function(e) {



                    //   updateSelectedCites($(this).closest('tr'));



                    // })







                    $('body').on('click', '.add_row', function(e) {



                        e.preventDefault();



                        var counter = $('#price_table > tbody tr').length;



                        var row = $('#price_table > tbody tr').first().clone();



                        row.find('input,select').each(function() {



                        })























                        row.find('.add_row').addClass('remove_row');



                        row.find('.add_row').addClass('btn btn-danger');



                        row.find('.fa-plus').addClass('fa-trash');



                        row.find('.fa-plus').removeClass('fa-plus');



                        row.find('.add_row').removeClass('add_row');



                        $('#price_table').append(row);



                        // updateSelectedCites();



                    })



                    $('body').on('click', '.remove_row', function(e) {



                        e.preventDefault();



                        $(this).closest('tr').remove();



                        // updateSelectedCites();



                    })



                })
                </script>
                <script type="text/javascript">
                document.addEventListener('DOMContentLoaded', function() {
                    $('body').on('change', '.country', function(e) {
                        e.preventDefault();
                        var country_id = $(this).val();
                        $.ajax({
                            type: 'POST',
                            data: {
                                country_id: country_id,
                                get_country: 1
                            },
                            url: 'ajax.php',
                            success: function(response) {
                                $('.get_state').html('');
                                $('.get_state').html(response);
                                $('.js-example-basic-single').select2();
                            }
                        });
                    })
                    $('body').on('change', '.state', function(e) {
                        e.preventDefault();
                        var state_id = $(this).val();
                        var country_id = $('.country').val();
                        $.ajax({
                            type: 'POST',
                            data: {
                                state_id: state_id,
                                country_id: country_id,
                                get_city: 1
                            },
                            url: 'ajax.php',
                            success: function(response) {
                                $('.get_city').html('');
                                $('.get_city').html(response);
                                $('.js-example-basic-single').select2();
                            }
                        });
                    })
                }, false);
                </script>