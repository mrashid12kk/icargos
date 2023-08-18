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
    
    if (isset($_POST['save_status_map'])) {

        $status_id = $_POST['status_id'];
        $api_provider_id   = $_POST['api_provider_id'];
        $api_status  = $_POST['api_status'];



        if (!empty(mysqli_fetch_array(mysqli_query($con, "SELECT * FROM third_party_api_status_mapping WHERE status_id = '" . $status_id . "'  AND api_provider_id = '" . $api_provider_id . "'  AND api_status = '" . $api_status . "'   ")))) {
            $_SESSION['error_msg'] = ' Same data already exist.';
        } else {
            if (mysqli_query($con, "INSERT into third_party_api_status_mapping (`status_id`,`api_provider_id`,`api_status`) VALUES ('" . $status_id . "','" . $api_provider_id . "', '" . $api_status . "') ")) {
                $_SESSION['success_msg'] = ' New data has been inserted successfully.';
            } else {
                $_SESSION['error_msg'] = ' Please try again latter.';
            }
        }


        header('location: thirdparty_setting.php');
    }

    $thirdparties = mysqli_query($con, "SELECT * FROM  third_party_apis Where status = 1  ");
    $statuses       = mysqli_query($con, "SELECT * FROM order_status  ");
    $statusapi_mapped = mysqli_query($con, "SELECT *, third_party_api_status_mapping.id as post_id FROM third_party_api_status_mapping  
        join order_status on
        third_party_api_status_mapping.status_id =  order_status.sts_id
        join third_party_apis on 
        third_party_api_status_mapping.api_provider_id =third_party_apis.id
        ");

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
                                            <div class="panel-heading"><?php echo getLange('statusmapping'); ?></div>
                                            <div class="panel-body">
                                                <form method="post" action="">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label><?php echo getLange('status'); ?></label>
                                                            <select class="form-control" name="status_id" required>
                                                                <option value=""><?php echo getLange('select'); ?>
                                                            </option>
                                                            <?php

                                                            while ($row = mysqli_fetch_array($statuses)) {
                                                                $row = (object) $row;  ?>

                                                                <option value="<?php echo $row->sts_id; ?>">
                                                                    <?php echo $row->status; ?></option>


                                                                    <?php
                                                                    echo  $our_status_id =   $row->sts_id;
                                                                }

                                                                ?>
                                                            </select>
                                                        </div>


                                                        <div class="col-md-4">
                                                            <label><?php echo getLange('api'); ?></label>
                                                            <select class="form-control" name="api_provider_id"
                                                            required="" id="api_providerr">
                                                            <option><?php echo getLange('select'); ?></option>
                                                            <?php
                                                            mysqli_data_seek($thirdparties, 0);
                                                            while ($row = mysqli_fetch_array($thirdparties)) {
                                                                $row = (object) $row;  ?>
                                                                <option value="<?php echo $row->title; ?>">
                                                                    <?php echo $row->title; ?>
                                                                </option>


                                                                <?php
                                                            }

                                                            ?>
                                                        </select>
                                                    </div>


                                                    <div class="col-md-4">
                                                        <label><?php echo getLange('api') . ' ' . getLange('status'); ?></label>
                                                        <select class="form-control" name="api_status"
                                                        id="api_status_id" required="">
                                                        <option value=""><?php echo getLange('select'); ?>
                                                    </option>

                                                </select>
                                            </div>


                                            <div class="col-md-12">
                                                <input type="submit"
                                                class="btn btn-info btn_style pull-right"
                                                name="save_status_map"
                                                value="<?php echo getLange('save'); ?>" />
                                            </div>
                                        </div>
                                    </form>
                                       <!--  <?PHP

                                        if (isset($_POST['save_status_map'])) {

                                            echo  $status_id = $_POST['status_id'];
                                            echo  $api_provider_id = $_POST['api_provider_id'];
                                            echo  $api_status = $_POST['api_status'];

                                            $query = mysqli_query($con, "INSERT INTO third_party_api_status_mapping (status_id, api_provider_id, api_status)
                                                VALUES ($status_id, $api_provider_id, $api_status");
                                            }

                                        ?> -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="table table-stripped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 5%;">#</th>
                                                            <th><?php echo getLange('status'); ?></th>
                                                            <th><?php echo getLange('api'); ?></th>
                                                            <th><?php echo getLange('api') . ' ' . getLange('status'); ?>
                                                        </th>
                                                        <th style="width: 5%;">
                                                            <?php echo getLange('action'); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        <?php
                                                        mysqli_data_seek($statusapi_mapped, 0);
                                                        while ($row = mysqli_fetch_array($statusapi_mapped)) {
                                                            $row = (object) $row;

                                                            ?>

                                                            <tr>
                                                                <td><?php echo $row->post_id ?></td>
                                                                <td><?php echo $row->status ?></td>
                                                                <td><?php echo $row->title ?></td>
                                                                <td><?php echo $row->api_status;

                                                                                // if($row->active == 1){
                                                                                //     echo  'Active';
                                                                                // }else{
                                                                                //     echo "In Active";
                                                                                // }
                                                            ?></td>
                                                            <td style="width: 10%;">
                                                                <input type="hidden"
                                                                value="<?php echo $row->title ?>"
                                                                id="postTitle<?php echo $row->post_id ?>">
                                                                <input type="hidden"
                                                                value="<?php echo $row->status ?>"
                                                                id="postStatus<?php echo $row->post_id ?>">

                                                                <a><i class="fa fa-trash text-danger"
                                                                    style="margin-left:6px;"
                                                                    onclick="showDeleteModel(this.id)"
                                                                    id="<?php echo $row->post_id ?>"></i></a>

                                                                    <a onclick="showEditModel(this.id)"
                                                                    id="<?php echo $row->post_id ?>"><i
                                                                    class="fa fa-edit text-success"
                                                                    style="margin-left:6px;cursor:pointer;"></i></a>
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
                $.ajax({
                    url: 'includes/API/get_api_services.php',
                    type: "post", //request type,
                    data: {
                        getApiStatus: 1,
                        apiId: apiId
                    },
                    beforeSend: function() {
                        let loading = '<option>Loading.....</option>';
                        $('body').find('#api_status_id').html(loading);
                    },
                    success: function(result) {
                        $('body').find('#api_status_id').html(result);
                    }
                });
            } else {
                alert('Please Select a Valid API');
            }
        });
    </script>
