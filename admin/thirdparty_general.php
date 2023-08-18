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

    include "includes/header.php";



    if (isset($_GET['delete_mapped'])) {
        $id  = $_GET['delete_mapped'];
        mysqli_query($con, "DELETE  FROM city_mapping where id =" . $id . " ");
        header('location: thirdparty_general.php');
    }




    if (isset($_POST['save_city'])) {

        $city_origin   = $_POST['city_origin'];
        $api_id        = $_POST['api_id'];
        $api_city_id   = $_POST['api_city_id'];
        $api_city_name = $_POST['city_origin'];
        if (!empty(mysqli_fetch_array(mysqli_query($con, "SELECT * FROM city_mapping WHERE city_id = '" . $city_origin . "'  AND api_id = '" . $api_id . "'    ")))) {
            $_SESSION['error_msg'] = 'Same data already exist.';
        } else {
            if (mysqli_query($con, "INSERT into city_mapping (`city_id`, `api_id`, `api_city_id` , `api_city_name` ) VALUES ('$city_origin', '$api_id' , '$api_city_id', '$api_city_name') ")) {
                $_SESSION['success_msg'] = 'New data has been inserted successfully.';
            } else {
                $_SESSION['error_msg'] = 'Please try again latter. ' . mysqli_error($con);
            }
            // echo "<pre>";
            // print_r($_SESSION);
            // die();
            header('location: thirdparty_setting.php');
        }
    }

    $thirdparties = mysqli_query($con, "SELECT * FROM  third_party_apis Where status = 1  ");
    $cites        = mysqli_query($con, "SELECT * FROM cities  ");
    $order_status = mysqli_query($con, "SELECT * FROM order_status  ");


    $cityapi_mapped = mysqli_query($con, "SELECT * from city_mapping");




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
                                            <div class="panel-heading"><?php echo getLange('citymaping'); ?> </div>
                                            <div class="panel-body">
                                                <form method="post" action="">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label><?php echo getLange('city'); ?></label>
                                                            <select class="form-control js-example-basic-single" name="city_origin" required>
                                                                <option value="">Select</option>
                                                                <?php
                                                                while ($row = mysqli_fetch_array($cites)) {
                                                                    $row = (object) $row;  ?>
                                                                    <option value="<?php echo $row->city_name; ?>">
                                                                        <?php echo $row->city_name; ?></option>
                                                                <?php
                                                                }

                                                                ?>
                                                            </select>
                                                        </div>


                                                        <div class="col-md-4">
                                                            <label><?php echo getLange('api'); ?></label>
                                                            <select class="form-control onclickapi" name="api_id" required="">
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
                                                            <label><?php echo getLange('api') . ' ' . getLange('cities'); ?>

                                                            </label>
                                                            <!--forrun Api Cities-->
                                                            <select class="form-control js-example-basic-single api_city_id get_cities_with_api" name="api_city_id" required="">
                                                                <option value=""><?php echo getLange('select'); ?>
                                                                </option>

                                                            </select>


                                                            <input type="hidden" class="api_city_name" name="api_city_name" value="">
                                                        </div>


                                                        <div class="col-md-12">
                                                            <input type="submit" class="btn btn-info btn_style pull-right" name="save_city" value="<?php echo getLange('save'); ?>" />
                                                        </div>
                                                    </div>
                                                </form>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table table-stripped table-bordered " id="city_mapping_dataTable">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 5%;">#</th>
                                                                    <th><?php echo getLange('city'); ?></th>
                                                                    <th><?php echo getLange('api'); ?></th>
                                                                    <th><?php echo getLange('api') . ' ' . getLange('city'); ?>
                                                                    </th>
                                                                    <th style="width: 5%;">
                                                                        <?php echo getLange('action'); ?></th>
                                                                </tr>
                                                            </thead>
                                                            <!-- <tbody>
                                                    <?php
                                                    $i = 1;
                                                    while ($row = mysqli_fetch_array($cityapi_mapped)) {
                                                        $row = (object) $row;
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $i++; ?></td>
                                                            <td><?php echo $row->city_id ?></td>
                                                            <td><?php echo $row->api_id ?></td>
                                                            <td><?php echo $row->api_city_name ?></td>
                                                            <td><a onclick="return confirm('Are you sure you want to delete?');"
                                                                href="thirdparty_setting.php?delete_mapped=<?php echo $row->id ?>"><i
                                                                class="fa fa-trash"></i></a></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                            ?>
                                                    </tbody> -->
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
            $('.onclickapi').on('change', function() {

var apiId = $(this).find(':selected').val();
$.ajax({
    url: "includes/API/get_api_cities.php", //the page containing php script
    type: "post", //request type,
    data: {
        getApiCity: 1,
        apiId: apiId
    },
    beforeSend: function() {
        let loading = '<option>Loading.....</option>';
        $('body').find('.get_cities_with_api').html(loading);
    },
    success: function(result) {
        $('body').find('.get_cities_with_api').html(result);
    }
}); 
});
            document.addEventListener('DOMContentLoaded', function() {
                var dataTable = $('#city_mapping_dataTable').DataTable({
                    'processing': true,
                    'serverSide': true,
                    'serverMethod': 'post',
                    // 'scrollCollapse': true,
                    // 'ordering': false,
                    // pageLength: 5,
                    'responsive': true,
                    'dom': "<'row'<'col-sm-4'l><'col-sm-4'f><'col-sm-4 text-right'B>>t<'bottom'p><'clear'>",
                    // dom: '<"html5buttons"B>lTfgitp',
                    'buttons': [
                        // {extend: 'copy'},
                        // {extend: 'csv'},
                        // {extend: 'excel', title: 'ExampleFile'},
                        // {extend: 'pdf', title: 'ExampleFile'},
                        // {extend: 'print',

                        //  customize: function (win){
                        //    $(win.document.body)
                        //       .css( 'font-size', '10pt' )
                        //       .prepend(
                        //           '<div>xxxxxxxxxxxxxxxxxxxxxxxx</div><img src="http://datatables.net/media/images/logo-fade.png" style="position:absolute; top:0; left:0;" />'
                        //       );
                        //         $(win.document.body).addClass('white-bg');
                        //         $(win.document.body).css('font-size', '10px');
                        //         $(win.document.body).find('table')
                        //                 .addClass('compact')
                        //                 .css('font-size', 'inherit');
                        // }
                        // }
                    ],
                    //'searching': false, // Remove default Search Control
                    'ajax': {

                        'url': 'includes/API/ajax_view_city_mapping.php',

                    },
                    'columns': [{
                            data: 'id'
                        },
                        {
                            data: 'city'
                        },
                        {
                            data: 'api'
                        },
                        {
                            data: 'api_city'
                        },
                        {
                            data: 'action'
                        },
                    ]
                });

            }, false);
        </script>