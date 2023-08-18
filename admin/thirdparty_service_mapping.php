<?php
session_start();
require 'includes/conn.php';

require 'includes/setting_helper.php';
if (isset($_SESSION['users_id']) && $_SESSION['type'] !== 'driver') {

    require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'], 50, 'view_only', $comment = null)) {
        header("location:access_denied.php");
    }

    include "includes/header.php";

?>

    <?php

    // include "includes/sidebar.php";

    ?>
    <?php

    if (isset($_POST['save_service_map'])) {

        $services = $_POST['services'];
        $api_id   = $_POST['api_id'];
        $mode_id  = $_POST['mode_id'];
        if ($mode_id == 5) {
            $mode_name = 'COD';
        } elseif ($mode_id == 6) {
            $mode_name = 'Overland COD';
        } else {
            $mode_name   = getSonicModeName($mode_id);
        }


        $serviceData = getDataById('services', ' WHERE id = "' . $services . '" ');

        $service_name = '';
        if (isset($serviceData['service_type'])) {
            $service_name = $serviceData['service_type'];
        }

        if (!empty(mysqli_fetch_array(mysqli_query($con, "SELECT * FROM third_party_api_service_mapping WHERE service_id = '" . $services . "'  AND api_provider_id = '" . $api_id . "'  AND api_service_id = '" . $mode_id . "'   ")))) {
            $_SESSION['error_msg'] = ' Same data already exist.';
        } else {
            if (
                mysqli_query($con, "INSERT into third_party_api_service_mapping (`service_id`,`service_name`,`api_service_id`,`api_provider_id`,`api_service_name`) VALUES ('$services','$service_name', '$mode_id', '$api_id', '$mode_name' ) ")
            ) {
                $_SESSION['success_msg'] = ' New data has been inserted successfully.';
            } else {
                $_SESSION['error_msg'] = ' Please try again latter.';
            }
        }
        // echo "<pre>";
        // print_r($_SESSION);
        // die();

        header('location: thirdparty_setting.php');
    }
    $thirdparties = mysqli_query($con, "SELECT * FROM  third_party_apis Where status = 1  ");
    $services       = mysqli_query($con, "SELECT * FROM services");

    $serviceapi_mapped = mysqli_query($con, "SELECT * FROM third_party_api_service_mapping");
    include "includes/header.php";




    ?>

    <style type="text/css">
        .city_to option.hide {
            /*display: none;*/
        }

        .form-group {
            margin-bottom: 0px !important;
        }

        .tabs-left {
            border-bottom: none;
        }

        .tabs-left>li {
            float: none;
        }

        .tabs-left>li.active>a,
        .tabs-left>li.active>a:hover,
        .tabs-left>li.active>a:focus {
            background: #0e688c;
            color: #fff;
        }

        .tabs-left>li>a {
            margin-right: 0;
            border-radius: 0;
            display: block;
            font-weight: 600;
            padding: 15px 10px;
            border: 1px solid #3333 !important;
        }

        .panel-body .container {
            width: 100%;
            padding: 0;
        }

        .panel-body .col-xs-3 {
            padding-left: 0;
        }

        .panel-body .col-xs-9 {
            padding: 10px 0;
        }

        .btn_style {
            margin: 9px 0px;
        }
    </style>
    <!-- Header Ends -->

    <body data-ng-app>


        <?php include "includes/sidebar.php"; ?>
        <!-- Aside Ends-->

        <section class="content">

            <?php include "includes/header2.php"; ?>

            <div class="warper container-fluid">
                <div class="page-header">
                    <h1>
                        <?php echo getLange('thirdpasrysetting') ?> <small><?php echo getLange('letsgetquick'); ?></small>

                    </h1>
                </div>
                <form method="POST" action="">
                    <div class=" ">
                        <!-- <div class="panel-heading">Third Party Setting</div> -->
                        <div class=" ">


                            <div class="container_">
                                <?php
                                if (isset($_SESSION['success_msg']) && !empty($_SESSION['success_msg'])) {
                                    $msg = $_SESSION['success_msg'];
                                    echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong>' . $msg . '</div>';
                                }
                                ?>

                                <?php
                                if (isset($_SESSION['error_msg']) && !empty($_SESSION['error_msg'])) {
                                    $msg = $_SESSION['error_msg'];
                                    echo '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">X</button><strong>Error!</strong>' . $msg . '</div>';
                                }
                                ?>
                                <?php
                                include "thirdparty_setting_sidebar.php";
                                ?>
                                <div class="col-xs-9">
                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading"><?php echo getLange('servicemaping'); ?></div>
                                            <div class="panel-body">
                                                <form method="post" action="">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label><?php echo getLange('Services'); ?></label>
                                                            <select class="form-control" name="services" required>
                                                                <option value=""><?php echo getLange('select'); ?>
                                                                </option>
                                                                <?php
                                                                while ($row = mysqli_fetch_array($services)) {
                                                                    $row = (object) $row;  ?>
                                                                    <option value="<?php echo $row->id; ?>">
                                                                        <?php echo $row->service_type; ?>
                                                                        (<?php echo $row->service_code ?>) </option>
                                                                <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>


                                                        <div class="col-md-4">
                                                            <label><?php echo getLange('api'); ?></label>
                                                            <select class="form-control apiName" name="api_id" required="">
                                                                <option value=""><?php echo getLange('select'); ?>
                                                                </option>
                                                                <?php
                                                                mysqli_data_seek($thirdparties, 0);
                                                                while ($row = mysqli_fetch_array($thirdparties)) {
                                                                    $row = (object) $row;  ?>
                                                                    <option value="<?php echo $row->title; ?>">
                                                                        <?php echo $row->title; ?></option>
                                                                <?php
                                                                }

                                                                ?>
                                                            </select>
                                                        </div>


                                                        <div class="col-md-4">
                                                            <label><?php echo getLange('mode'); ?></label>
                                                            <select class="form-control js-example-basic-single mode_id get_services_with_api" name="mode_id" required="">
                                                                <option value=""><?php echo getLange('select'); ?>
                                                                </option>

                                                            </select>

                                                        </div>


                                                        <div class="col-md-12">
                                                            <input type="submit" class="btn btn-info btn_style pull-right" name="save_service_map" value="<?php echo getLange('save'); ?>" />
                                                        </div>
                                                    </div>
                                                </form>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table table-stripped table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 5%;">#</th>
                                                                    <th><?php echo getLange('Services') ?></th>
                                                                    <th><?php echo getLange('api'); ?></th>
                                                                    <th><?php echo getLange('mode'); ?></th>
                                                                    <th style="width: 5%;">
                                                                        <?php echo getLange('action'); ?></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $i = 1;
                                                                while ($row = mysqli_fetch_array($serviceapi_mapped)) {
                                                                    $row = (object) $row;

                                                                    $serviceData = (object) getDataById('services', ' WHERE id = "' . $row->service_id . '" ');

                                                                ?>
                                                                    <tr>
                                                                        <td><?php echo $i++; ?></td>
                                                                        <td><?php echo $serviceData->service_type; ?> (
                                                                            <?php echo $serviceData->service_code ?> )</td>
                                                                        <td><?php echo $row->api_provider_id ?></td>
                                                                        <td><?php echo $row->api_service_id ?></td>
                                                                         
                                                                        <td><a onclick="return confirm('Are you sure you want to delete?');" href="thirdparty_service.php?delete_service_mapped=<?php echo $row->id ?>"><i class="fa fa-trash"></i></a></td>
                                                                    </tr>
                                                                <?php
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        <?php include "includes/footer.php";
    } else {
        header("location:index.php");
    }
        ?>
        <?php

        if (isset($_SESSION['success_msg'])) {
            unset($_SESSION['success_msg']);
        }

        if (isset($_SESSION['error_msg'])) {
            unset($_SESSION['error_msg']);
        }

        ?>
        <script type="text/javascript">
            function showEditModel(id) {

                $("#edit_post_id").val(id);
                jQuery.noConflict();
                $('#editModel').modal('show');

            }

            function showDeleteModel(id) {

                var status = $("#postStatus" + id).val();
                var title = $("#postTitle" + id).val();

                $("#deleteModelBody").append("<h5>Are You sure want to delete setting with title" + title +
                    " and status  " + status + "</h5>")
                $("#delete_post_id").val(id);
                jQuery.noConflict();
                $('#deleteModel').modal('show');

            }

            $(document).on('change', '.api_city_id', function() {
                $('.api_city_name').val($(this).find(':selected').attr('data-cityname'));
            });
        </script>
        <script type="text/javascript">
            //   alert('ok');
            $('.apiName').on('change', function() {

                var apiId = $(this).find(':selected').val();
                $.ajax({
                    url: "includes/API/get_api_services.php", //the page containing php script
                    type: "post", //request type,
                    data: {
                        getApiservice: 1,
                        apiId: apiId
                    },
                    beforeSend: function() {
                        let loading = '<option>Loading.....</option>';
                        $('body').find('.get_services_with_api').html(loading);
                    },
                    success: function(result) {
                        $('body').find('.get_services_with_api').html(result);
                    }
                });
            });


            $('#api_providerr').on('change', function() {
                var stateID = $(this).val();
                if (stateID) {
                    // console.log(stateID);
                    // alert(stateID);
                    $.ajax({
                        // url: "{{url('testapi11.php')}}"+'/'+stateID,
                        // url: 'testapi11.php'+'/'+stateID,
                        url: 'api_ajax_file.php?api_id=' + stateID,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            // console.log(data);
                            $('#api_status_id').empty();
                            $.each(data, function(key, value) {


                                $('#api_status_id').append('<option value="' + value.status +
                                    '">' + value.status + '</option>');
                            });


                        }
                    });
                } else {
                    alert('Please Select a Valid API');
                }
            });
        </script>

        <script type="text/javascript">
            $('#api_providerr_edit').on('change', function() {
                var stateID = $(this).val();
                if (stateID) {
                    // console.log(stateID);
                    // alert(stateID);
                    $.ajax({
                        // url: "{{url('testapi11.php')}}"+'/'+stateID,
                        url: 'api_ajax_file.php?api_id=' + stateID,
                        // url: 'testapi11.php?api_id='+'/'+stateID,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            // console.log(data);
                            $('#api_status_id_edit').empty();
                            $.each(data, function(key, value) {


                                $('#api_status_id_edit').append('<option value="' + value
                                    .status + '">' + value.status + '</option>');
                            });


                        }
                    });
                } else {
                    alert('Please Select a Valid API');
                }
            });
        </script>