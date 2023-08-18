<?php
session_start();
require 'includes/conn.php';

if (isset($_SESSION['users_id'])) {
    require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'], 61, 'view_only', $comment = null)) {

        header("location:access_denied.php");
    }
    include "includes/header.php";

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

    .form-control {
        border-radius: 0;
    }

    .show_customer ul {
        padding: 0;
        background: #fff;
        border: 1px solid #cccccc9c;
        list-style: none;
        border-bottom: none;
        position: absolute;
        width: 90%;
        top: 57px;
        z-index: 100000;
    }

    .show_customer ul li:hover {
        background: #416baf;
        color: #fff;
    }

    .show_customer ul li {
        cursor: pointer;
        padding: 3px 11px;
        border-bottom: 1px solid #cccccc6b;
    }

    .customer_label label {
        display: block;
    }

    .customer_code {
        width: 22%;
        display: inline-block;
    }

    .customer_name {
        width: 70%;
        display: inline-block;
    }
    </style>

    <?php

        include "includes/sidebar.php";

        ?>
    <!-- Aside Ends-->

    <section class="content">

        <?php
            include "includes/header2.php";
            ?>

        <!-- Header Ends -->


        <div class="warper container-fluid">

            <div class="page-header">
                <h1><?php echo getLange('servicelist'); ?> <small><?php echo getLange('letsgetquick'); ?></small></h1>
            </div>
            <div class="row">
                <?php
                    require_once "setup-sidebar.php";
                    ?>
                <div class="col-sm-10 table-responsive" id="setting_box">
                    <?php
                        $msg = "";
                        if (isset($_GET['delete_id'])) {
                            $id = $_GET['delete_id'];
                            $query1 = mysqli_query($con, "DELETE from cn_allocation where id=$id") or die(mysqli_error($con));
                            $query1 = mysqli_query($con, "DELETE from cn_allocation_master where cn_allocation_id='$id'") or die(mysqli_error($con));
                            $rowscount = mysqli_affected_rows($con);
                            if ($rowscount > 0) {
                                echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>' . getLange('Well_done') . '!</strong>You DELETE a Cn Allocation Successfully</div>';
                            } else {
                                echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>' . getLange('unsuccessful') . '!</strong>You cannot DELETE CN Allocation unsuccessfully.</div>';
                            }
                        }
                        if (isset($_POST['addcn_allocation'])) {
                            $customer_code = $_POST['customer_code'];
                            $customer_id = $_POST['customer_id'];
                            $area_code = $_POST['area_code'];
                            $city = $_POST['city'];
                            $from = $_POST['from'];
                            $to = $_POST['to'];
                            $user_id = $_SESSION['users_id'];
                            $inert_query = " INSERT INTO cn_allocation(`customer_code`,`customer_id`,`area_code`,`city`,`from`,`to`,`user_id`) VALUES('" . $customer_code . "','" . $customer_id . "','" . $area_code . "','" . $city . "','" . $from . "','" . $to . "','" . $user_id . "') ";
                            mysqli_query($con, $inert_query);
                            $insert_id = mysqli_insert_id($con);
                            for ($i = $from; $i <= $to; $i++) {
                                $cn = $area_code . $i;
                                $inert_query = " INSERT INTO cn_allocation_master(`user_id`,`cn_allocation_id`,`cn`,`customer_id`) VALUES('" . $user_id . "','" . $insert_id . "','" . $cn . "','" . $customer_id . "') ";
                                mysqli_query($con, $inert_query);
                            }
                            $rowscount = mysqli_affected_rows($con);
                            if ($rowscount > 0) {
                                $msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you added a New Cn Allocation successfully</div>';
                            } else {
                                $msg = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not added a New Cn Allocation unsuccessfully.</div>';
                            }
                        }

                        echo $msg;
                        if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {

                            $edit_id = $_GET['edit_id'];
                            $cn_allocation_q = mysqli_query($con, "SELECT * FROM cn_allocation WHERE id='" . $edit_id . "' ");
                            $edit = mysqli_fetch_array($cn_allocation_q);
                        }
                        ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">Cn Allocation</div>
                        <div class="panel-body" id="same_form_layout">
                            <form id="formid" action="cn_allocation.php" role="form" data-toggle="validator" action=""
                                method="post">
                                <div class="row">
                                    <div class="col-md-4 customer_label">
                                        <div class="form-group">
                                            <label class="control-label">Customer </label>
                                            <input type="text" class="form-control customer_code" name="customer_code"
                                                placeholder="Code" required>
                                            <input type="hidden" name="customer_id" class="customer_id">
                                            <input type="text" class="form-control customer_name" required
                                                placeholder="Customer Select">
                                            <div class="show_customer" style="display:none;">
                                            </div>
                                            <div class="help-block with-errors error_customer_code"></div>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-4">
                                    <div class="form-group">
                                       <label class="control-label">Customer Select</label>
                                       
                                       <div class="help-block with-errors "></div>
                                    </div>
                                 </div> -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">City</label>
                                            <input type="hidden" name="area_code" class="area_code">
                                            <input type="hidden" name="city" class="city">
                                            <input type="text" class="form-control city_name" required readonly="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">No Of CN</label>
                                            <input type="number" class="form-control no_of_cn" value="1" name="cn"
                                                required>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">From</label>
                                            <input type="text" class="form-control from" name="from" value="" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">To</label>
                                            <input type="text" class="form-control to" name="to" value="" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 rtl_full">
                                        <button type="submit" name="addcn_allocation"
                                            class="add_form_btn"><?php echo getLange('submit'); ?></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">CN Allocation
                        </div>
                        <div class="panel-body" id="same_form_layout" style="padding: 11px;">
                            <div id="basic-datatable_wrapper"
                                class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
                                <table cellpadding="0" cellspacing="0" border="0"
                                    class="table table-striped table-bordered dataTable no-footer" id="basic-datatable"
                                    role="grid" aria-describedby="basic-datatable_info">
                                    <thead>
                                        <tr role="row">
                                            <th style="width: 2%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;"><?php echo getLange('srno'); ?></th>
                                            <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;">Customer Name </th>
                                            <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;">Customer Code </th>
                                            <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;">City Name </th>
                                            <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;">Area Code </th>

                                            <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;">Cn From </th>

                                            <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;">Cn To </th>

                                            <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1"
                                                colspan="1" aria-label="CSS grade: activate to sort column ascending"
                                                style="width: 108px;"><?php echo getLange('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $query1 = mysqli_query($con, "SELECT cna.id,cna.customer_code,cna.area_code,cna.from,cna.to,customers.fname,cities.city_name FROM cn_allocation as cna LEFT JOIN customers on cna.customer_id=customers.id LEFT JOIN cities on cna.city=cities.id ORDER BY id desc");
                                            $sr = 1;
                                            while ($fetch1 = mysqli_fetch_array($query1)) {
                                                $check_q = mysqli_query($con, "SELECT is_used from cn_allocation_master where cn_allocation_id='" . $fetch1['id'] . "' && is_used='1'");
                                                $rowscount = mysqli_affected_rows($con);
                                                if ($rowscount > 0) {
                                                    $delete_cn = false;
                                                } else {
                                                    $delete_cn = true;
                                                }
                                            ?>
                                        <tr class="gradeA odd" role="row">
                                            <td><?php echo $sr; ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['fname']; ?></td>
                                            <td class="sorting_1" style="text-transform: uppercase;">
                                                <?php echo $fetch1['customer_code']; ?></td>
                                            <td class="sorting_1" style="text-transform: uppercase;">
                                                <?php echo $fetch1['city_name']; ?></td>
                                            <td class="sorting_1" style="text-transform: uppercase;">
                                                <?php echo $fetch1['area_code']; ?></td>
                                            <td class="sorting_1" style="text-transform: uppercase;">
                                                <?php echo $fetch1['from']; ?></td>
                                            <td class="sorting_1" style="text-transform: uppercase;">
                                                <?php echo $fetch1['to']; ?></td>
                                            <td class="center">
                                                <?php if ($delete_cn) { ?>
                                                <a href="cn_allocation.php?delete_id=<?php echo $fetch1['id']; ?>"
                                                    onclick="return confirm('Are you sure you want to delete this CN Allocation?');">
                                                    <span class="glyphicon glyphicon-trash"></span>
                                                </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                                $sr++;
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
        <?php

        include "includes/footer.php";
    } else {
        header("location:index.php");
    }
        ?>
        <script type="text/javascript">
        $('.select2').select2();
        </script>
        <script type="text/javascript">
        $('#formid').on('keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                // return false;
            }
        });
        var no_of_cn = $('.no_of_cn').val();
        var customer_code = $('.customer_code').val();
        customer_code = customer_code ? customer_code : 0;
        var from = parseFloat(customer_code) * 10000000;
        var to = parseFloat(customer_code) * 10000000 + parseFloat(no_of_cn);
        $('.from').val(from);
        $('.to').val(to);
        $(document).on("keyup", ".no_of_cn", function() {
            var no_of_cn = $(this).val();
            var from = parseFloat($('.from').val()) - 1;
            if (no_of_cn == '') {
                var no_of_cn = 1;
            }
            if (no_of_cn == 0) {
                var from = parseFloat($('.from').val()) - 0;
            }
            var to = parseFloat(from) + parseFloat(no_of_cn);
            // $('.from').val(from);
            $('.to').val(to);
        });
        $('body').on('keyup', '.customer_code', function() {
            var customer_code = $(this).val();
            $.ajax({
                type: 'POST',
                data: {
                    customer_code: customer_code,
                    get_customer_name_code: 1
                },
                dataType: "json",
                url: 'getcustomer.php',
                success: function(response) {
                    if (response != null) {
                        $('.customer_name').val(response.fname);
                        $('.customer_id').val(response.id);
                        $('.error_customer_code').html('');
                        $(".add_form_btn").prop("disabled", false);
                        getcustomer_city();
                        getcustomer_cn();
                    } else {
                        $('.customer_name').val('');
                        $('.customer_id').val('');
                        $('.error_customer_code').html('This Customer Code is Not Exist');
                        $(".add_form_btn").prop("disabled", true);
                    }
                }
            });
        });
        $('body').on('keyup', '.customer_name', function() {
            var customer_name = $(this).val();
            if (customer_name != '') {
                $.ajax({
                    type: 'POST',
                    data: {
                        customer_name: customer_name,
                        get_customer_name: 1
                    },
                    dataType: "json",
                    url: 'getcustomer.php',
                    success: function(response) {
                        if (response != '') {
                            $('.show_customer').show();
                            $('.show_customer').html(response);
                            $(".add_form_btn").prop("disabled", false);
                            $('.error_customer_code').html('');
                        } else {
                            $('.show_customer').hide();
                            $('.show_customer').html('');
                            $(".add_form_btn").prop("disabled", true);
                            $('.error_customer_code').html('This Customer Name is Not Exist');
                        }
                    }
                });
            } else {
                $('.show_customer').hide();
                $('.show_customer').html('');
            }
        });
        $(document).on("click", ".select_customer", function() {
            var customer_name = $(this).html();
            var customer_id = $(this).attr('data-id');
            var customer_code = $(this).attr('data-code');
            $('.customer_name').val(customer_name);
            $('.customer_id').val(customer_id);
            $('.customer_code').val(customer_code);
            $('.show_customer').hide();
            $('.show_customer').html('');
            getcustomer_city();
            getcustomer_cn();
        });

        function getcustomer_city() {
            var customer_id = $('.customer_id').val();
            $.ajax({
                type: 'POST',
                data: {
                    customer_id_city: customer_id,
                    getcustomer_city: 1
                },
                dataType: "json",
                url: 'getcustomer.php',
                success: function(response) {
                    if (response != null) {
                        $('.area_code').val(response.area_code);
                        $('.city').val(response.id);
                        $('.city_name').val(response.city_name);
                        $('.error_customer_code').html('');
                    } else {
                        $('.area_code').val('');
                        $('.city').val('');
                        $('.city_name').val('');
                        $('.error_customer_code').html('The City Of this Customer is not exist');
                    }
                }
            });
        }

        function getcustomer_cn() {
            var customer_id = $('.customer_id').val();
            $.ajax({
                type: 'POST',
                data: {
                    customer_id_cn: customer_id,
                    getcustomer_cn: 1
                },
                dataType: "json",
                url: 'getcustomer.php',
                success: function(response) {
                    if (response != '') {
                        var no_of_cn = $('.no_of_cn').val();
                        var from = parseFloat(response) + parseFloat(1);
                        var to = parseFloat(response) + parseFloat(no_of_cn);
                        $('.from').val(from);
                        $('.to').val(to);
                    } else {
                        var no_of_cn = $('.no_of_cn').val();
                        var customer_code = $('.customer_code').val();
                        var from = parseFloat(customer_code) * 10000000;
                        var to = parseFloat(customer_code) * 10000000 + parseFloat(no_of_cn);
                        $('.from').val(from);
                        $('.to').val(to);
                    }
                }
            });
        }
        </script>