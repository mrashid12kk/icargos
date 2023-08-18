<?php
session_start();
require 'includes/conn.php';

require 'includes/setting_helper.php';
if (isset($_SESSION['users_id']) && $_SESSION['type'] !== 'driver')
{

    require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'], 50, 'view_only', $comment = null))
    {
        header("location:access_denied.php");
    }

    include "includes/header.php";

    ?>

    <?php
    // include "includes/sidebar.php";
    
    ?>
    <?php
    $all_apis = mysqli_query($con,"SELECT * from third_party_apis");
    if (isset($_POST['update']))
    {
        $booking_on = isset($_POST['booking_on']) ? $_POST['booking_on'] :'';
        $cn_on_booking = isset($_POST['cn_on_booking']) ? $_POST['cn_on_booking'] :'';
        $default_api = isset($_POST['default_api']) ? $_POST['default_api'] :'';
        $query = mysqli_query($con, "UPDATE thirdpary_api_config SET `booking_on` = '" . $booking_on . "' , `cn_on_booking`='" . $cn_on_booking . "', `default_api`='" . $default_api . "'");
        if (mysqli_affected_rows($con) > 0)
        {
            $_SESSION['success_msg'] = 'Data has been Updated successfully.';
        }
        else
        {
            $_SESSION['error_msg'] = ' Please try again latter.'.mysqli_error($con);
        }
    }

    $thirdparties = mysqli_query($con, "SELECT * FROM  thirdpary_api_config ");
    $fetchRes  = mysqli_fetch_assoc($thirdparties);

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
                                if (isset($_SESSION['success_msg']) && !empty($_SESSION['success_msg']))
                                {
                                    $msg = $_SESSION['success_msg'];
                                    echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong>' . $msg . '</div>';
                                }
                                ?>

                                <?php
                                if (isset($_SESSION['error_msg']) && !empty($_SESSION['error_msg']))
                                {
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
                                        <div class="tab-pane active" id="ClientInfo">

                                            <div class="panel panel-primary" style="margin-top: 5px">
                                                <div class="panel-heading">API Configuration</div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <form action="" method="POST">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-4 setting_padd">
                                                                    <div class="form-group">
                                                                        <label>API Booking Process</label>
                                                                        <select name="booking_on" class="form-control">
                                                                            <option <?php if ($fetchRes['booking_on'] == 'booking') : echo "selected";
                                                                            endif ?> value="booking">Booking Form</option>
                                                                            <option <?php if ($fetchRes['booking_on'] == 'order_processing') : echo "selected";
                                                                            endif ?> value="order_processing">Order Processing Form</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4 setting_padd">
                                                                    <div class="form-group">
                                                                        <label>Default API</label>
                                                                        <select name="default_api" class="form-control">
                                                                            <?php while ($fetch_api = mysqli_fetch_assoc($all_apis)) { ?>
                                                                                <option <?php if ($fetchRes['default_api'] == $fetch_api['title']) : echo "selected";
                                                                                endif ?> value="<?php echo $fetch_api['title']; ?>"><?php echo $fetch_api['title']; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4 setting_padd">
                                                                    <div class="form-group">
                                                                        <label>CN on Booking </label>
                                                                        <select name="cn_on_booking" class="form-control">
                                                                            <option <?php if ($fetchRes['cn_on_booking'] == 'default') : echo "selected";
                                                                            endif ?> value="default">Default CN</option>
                                                                            <option <?php if ($fetchRes['cn_on_booking'] == 'api') : echo "selected";
                                                                            endif ?> value="api">API CN</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>
                                                            <div class="row" >
                                                            <div class="col-md-4 setting_padd">
                                                                    <div class="form-group">
                                                                        <input style=" margin-top:10px;" type="submit" value="Update" name="update" class="btn btn-primary">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        </form>
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
        }
        else
        {
            header("location:index.php");
        }
        ?>
        <?php
        if (isset($_SESSION['success_msg']))
        {
            unset($_SESSION['success_msg']);
        }

        if (isset($_SESSION['error_msg']))
        {
            unset($_SESSION['error_msg']);
        }

        ?>
