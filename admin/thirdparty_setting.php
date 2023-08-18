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


    if (isset($_POST['delete_status_map'])) {


        $table_id = $_POST['delete_post_id'];

        if (mysqli_query($con, "DELETE FROM third_party_api_status_mapping  where id ='" . $table_id . "'  ")) {
            $_SESSION['success_msg'] = 'data has been Deleted successfully.';
        } else {
            $_SESSION['error_msg'] = ' Please try again latter.';
        }

        header('location: thirdparty_setting.php');
    }



    if (isset($_GET['delete_mapped'])) {
        $id  = $_GET['delete_mapped'];
        mysqli_query($con, "DELETE  FROM city_mapping where id =" . $id . " ");
        header('location: thirdparty_setting.php');
    }

    if (isset($_GET['delete_apis'])) {
        $id  = $_GET['delete_apis'];
        mysqli_query($con, "DELETE  FROM third_party_apis where id =" . $id . " ");
        header('location: thirdparty_setting.php');
    }



    if (isset($_POST['save_key'])) {
        $title          = $_POST['title'];
        $api_key        = $_POST['api_key'];
        $user_name        = $_POST['user_name'];
        $password        = $_POST['password'];
        $account_no        = $_POST['account_no'];
        $services        = $_POST['services'];
        $status        = $_POST['status'];
        $authorization        = $_POST['authorization'];
        if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
            $edittable_id=$_POST['edit_id'];
            $pass_q='';
            if (isset($password) && !empty($password)) {
                $pass_q=", `password`='" . $password . "'";
            }
            $query=  mysqli_query($con, "UPDATE third_party_apis SET `title` = '" . $title . "' , `api_key`='" . $api_key . "', `user_name`='" . $user_name . "', `account_no`='" . $account_no . "', `services`='" . $services . "', `status`=" . $status . ", `authorization`='" . $authorization . "'".$pass_q." WHERE id =" . $edittable_id . "");
            if (mysqli_affected_rows($con) > 0) {
                $_SESSION['success_msg'] = 'data has been Updated successfully.';
            } else {
                $_SESSION['error_msg'] = ' Please try again latter.';
            }
        }else{
            mysqli_query($con, "INSERT into third_party_apis (`title`,`api_key`,`user_name`,`password`,`account_no`,`services`,`status`,`authorization`) VALUES ('$title', '$api_key','$user_name','$password','$account_no','$services',$status,'$authorization' ) ");
            header('location: thirdparty_setting.php');
        }
    }

    $thirdparties = mysqli_query($con, "SELECT * FROM  third_party_apis Where status = 1  ");
    if (isset($_GET['edit'])) {
        $edit=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM third_party_apis WHERE id=".$_GET['edit']));
    }
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
                                        <div class="tab-pane active" id="ClientInfo">
                                           <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                <?php echo getLange('add') . ' ' . getLange('api'); ?> </div>
                                                <div class="panel-body">

                                                    <form method="post" action="" autocomplete="off">
                                                        <div class="row">
                                                            <input type="hidden" name="<?php echo isset($edit['id']) && !empty($edit['id']) ? 'edit_id' : ''; ?>" value="<?php echo isset($edit['id']) && !empty($edit['id']) ? $edit['id'] : ''; ?>">
                                                            <div class="col-md-3 padd_none form-group">
                                                                <div class="form-group">
                                                                    <label><?php echo getLange('title'); ?></label>
                                                                    <input required type="text" name="title"
                                                                    class="form-control" value="<?php echo isset($edit['title']) && !empty($edit['title']) ? $edit['title'] : ''; ?>">
                                                                </div>
                                                            </div>


                                                            <div class="col-md-3 padd_none form-group">
                                                                <div class="form-group">
                                                                    <label><?php echo getLange('apikey'); ?></label>
                                                                    <input required type="text" name="api_key"
                                                                    class="form-control" value="<?php echo isset($edit['api_key']) && !empty($edit['api_key']) ? $edit['api_key'] : ''; ?>">
                                                                </div>
                                                            </div>


                                                            <div class="col-md-3 padd_none form-group">
                                                                <div class="form-group">
                                                                    <label><?php echo getLange('username'); ?></label>
                                                                    <input type="text" name="user_name"
                                                                    class="form-control" value="<?php echo isset($edit['user_name']) && !empty($edit['user_name']) ? $edit['user_name'] : ''; ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 padd_none form-group">
                                                                <div class="form-group">
                                                                    <label><?php echo getLange('password'); ?></label>
                                                                    <input type="password" name="password"
                                                                    class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 padd_none form-group">
                                                                <div class="form-group">
                                                                    <label><?php echo getLange('accountno'); ?></label>
                                                                    <input type="text" name="account_no"
                                                                    class="form-control" value="<?php echo isset($edit['account_no']) && !empty($edit['account_no']) ? $edit['account_no'] : ''; ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 padd_none form-group">
                                                                <div class="form-group">
                                                                    <label><?php echo getLange('Services'); ?></label>
                                                                    <input type="text" name="services" class="form-control" value="<?php echo isset($edit['services']) && !empty($edit['services']) ? $edit['services'] : ''; ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 padd_none form-group">
                                                                <div class="form-group">
                                                                    <label><?php echo getLange('Authorization'); ?></label>
                                                                    <input type="text" name="authorization"
                                                                    class="form-control" value="<?php echo isset($edit['authorization']) && !empty($edit['authorization']) ? $edit['authorization'] : ''; ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 padd_none form-group">
                                                                <div class="form-group">
                                                                    <label><?php echo getLange('status'); ?></label>
                                                                    <select name="status" class="form-control">
                                                                        <option value="1" <?php echo isset($edit['status']) && $edit['status']==1 ? 'selected' : ''; ?> >ACTIVE</option>
                                                                        <option value="0" <?php echo isset($edit['status']) && $edit['status']==0 ? 'selected' : ''; ?> >InActive</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-1 padd_none">
                                                                <div class="form-group">
                                                                    <input type="submit" name="save_key"
                                                                    value="<?php echo getLange('save'); ?>"
                                                                    class="add_apibtn btn btn-primary pull-right">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div> 
                                            <div class="panel panel-primary" style="margin-top: 5px">
                                                <div class="panel-heading"><?php echo getLange('apilist') ?> </div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <table class="table table-stripped table-bordered dataTable">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="width: 5%;">#</th>
                                                                        <th><?php echo getLange('title') ?></th>
                                                                        <th><?php echo getLange('api'); ?></th>
                                                                        <th style="width: 5%;">
                                                                            <?php echo getLange('action'); ?></th>
                                                                        </tr>
                                                                    </thead>

                                                                    <tbody>
                                                                        <?php
                                                                        $i = 1;
                                                                        while ($row = mysqli_fetch_array($thirdparties)) {
                                                                            $row = (object) $row;
                                                                            if ($row->id) {
                                                                                ?>
                                                                                <tr>
                                                                                    <td><?php echo $i++; ?></td>
                                                                                    <td><?php echo $row->title ?></td>
                                                                                    <td><?php echo $row->api_key ?></td>
                                                                                    <td><a onclick="return confirm('Are you sure you want to delete?');"
                                                                                        href="thirdparty_setting.php?delete_apis=<?php echo $row->id ?>"><i
                                                                                        class="fa fa-trash"></i></a>
                                                                                        <a href="thirdparty_setting.php?edit=<?php echo $row->id ?>"><i
                                                                                            class="fa fa-pencil"></i></a></td>
                                                                                        </tr>
                                                                                        <?php
                                                                                    }
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
                        $('.transoServiceType').hide();
                        $('.forrunServiceType').hide();
                        $('.apiName').on('change', function() {

                            var apiId = $(this).find(':selected').val();
            //   alert(apiId);
            if (apiId == 10) {


                $('.forrunServiceType').show();
                $('.transoServiceType').hide();
            }
            if (apiId == 1) {


                $('.forrunServiceType').hide();
                $('.transoServiceType').show();
            }
        });
    </script>
    <script type="text/javascript">
        //   alert('ok');
        $('.onclickapi').on('change', function() {

            var apiId = $(this).find(':selected').val();
            $.ajax({
                url: "includes/ajax/ajax_api_cities.php", //the page containing php script
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
            // if (apiId == 10) {

            //     $('.forrunCities').css("display", "block");
            //     $('.transoCities').css("display", "none");
            //     $('.leopardCities').css("display", "none");
            //     $('.transoCities').hide();
            // }
            // if (apiId == 1) {

            //     $('.transoCities').css("display", "block");
            //     $('.leopardCities').css("display", "none");
            //     $('.forrunCities').css("display", "none");
            //     $('.forrunCities').hide();
            // }
            // if (apiId == 14) {

            //     $('.transoCities').css("display", "none");
            //     $('.leopardCities').css("display", "block");
            //     $('.forrunCities').css("display", "none");
            //     $('.forrunCities').hide();
            // }
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