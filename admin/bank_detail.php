<?php
session_start();
require 'includes/conn.php';

if (isset($_SESSION['users_id'])) {
    require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'], 17, 'view_only', $comment = null)) {

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
                        // if (isset($_GET['delete_zone_type'])) {
                        //     $id = $_GET['delete_zone_type'];
                        //     $query1 = mysqli_query($con, "DELETE from zone_type where id=$id") or die(mysqli_error($con));
                        //     $rowscount = mysqli_affected_rows($con);
                        //     if ($rowscount > 0) {
                        //         echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>' . getLange('Well_done') . '!</strong>You delete a Zone type Successfully</div>';
                        //     } else {
                        //         echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>' . getLange('unsuccessful') . '!</strong>Ypu cannot delete Zone Type unsuccessfully.</div>';
                        //     }
                        // }
                        if (isset($_POST['addbank_detail'])) {
                            $bank_code = isset($_POST['bank_code']) ? $_POST['bank_code'] : '';
                            $bank_name = isset($_POST['bank_name']) ? $_POST['bank_name'] : '';
                            mysqli_query($con, " INSERT INTO bank_detail(`bank_code`,`bank_name`) VALUES('" . $bank_code . "','" . $bank_name . "') ");
                            $rowscount = mysqli_affected_rows($con);
                            if ($rowscount > 0) {
                                $msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you added a new Bank Detaiil successfully</div>';
                            } else {
                                $msg = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not added a new Bank Detaiil unsuccessfully.</div>';
                            }
                        }
                        if (isset($_POST['updatebank_detail'])) {
                            $query1 = mysqli_query($con, " UPDATE bank_detail SET bank_code='" . $_POST['bank_code'] . "',bank_name='" . $_POST['bank_name'] . "' WHERE id='" . $_GET['edit_id'] . "' ") or die(mysqli_error($con));
                            $rowscount = mysqli_affected_rows($con);
                            if ($rowscount > 0) {
                                $msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> Bank Detail updated successfully</div>';
                            } else {
                                $msg = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> Bank Detail have not updated.</div>';
                            }
                        }
                        echo $msg;
                        if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {

                            $edit_id = $_GET['edit_id'];
                            $bank_detail_q = mysqli_query($con, "SELECT * FROM bank_detail WHERE id='" . $edit_id . "' ");
                            $edit = mysqli_fetch_array($bank_detail_q);
                        }
                        ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">Bank Detail</div>
                        <div class="panel-body" id="same_form_layout">

                            <form role="form" data-toggle="validator" action="" method="post">
                                <div id="cities">

                                    <div class="row" id="select_citites">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Bank Name</label>
                                                <input type="text" class="form-control" name="bank_name" value="<?php if (isset($edit)) {echo $edit['bank_name'];} ?>" required>
                                                <div class="help-block with-errors "></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Bank Code</label>
                                                <input type="text" class="form-control" name="bank_code" value="<?php if (isset($edit)) {echo $edit['bank_code'];} ?>" required>
                                                <div class="help-block with-errors "></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 rtl_full">
                                            <button type="submit" name="<?php if (isset($edit)) { echo 'updatebank_detail';} else {echo 'addbank_detail';} ?>" class="add_form_btn"><?php echo getLange('submit'); ?></button>
                                        </div>
                                    </div>
                                </div>
                                <br>
                            </form>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">Bank Detail

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
                                                style="width: 179px;">Bank Name </th>
                                            <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;">Bank Code </th>
                                            <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1"
                                                colspan="1" aria-label="CSS grade: activate to sort column ascending"
                                                style="width: 108px;"><?php echo getLange('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $query1 = mysqli_query($con, "SELECT * from bank_detail ORDER BY id desc");
                                            $sr = 1;
                                            while ($fetch1 = mysqli_fetch_array($query1)) {
                                            ?>
                                        <tr class="gradeA odd" role="row">
                                            <td><?php echo $sr; ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['bank_name']; ?></td>
                                            <td class="sorting_1" style="text-transform: uppercase;">
                                                <?php echo $fetch1['bank_code']; ?></td>
                                            
                                            <td class="center">
                                                <a href="bank_detail.php?edit_id=<?php echo $fetch1['id']; ?>">
                                                    <span class="glyphicon glyphicon-edit"></span>
                                                </a>
                                               <!--  <a href="zone_type.php?delete_zone_type=<?php echo $fetch1['id']; ?>"
                                                    onclick="return confirm('Are you sure you want to delete this service?');">
                                                    <span class="glyphicon glyphicon-trash"></span>
                                                </a> -->
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
        <!-- Warper Ends Here (working area) -->


        <?php

        include "includes/footer.php";
    } else {
        header("location:index.php");
    }
        ?>
        <script type="text/javascript">
        $('.select2').select2();
        </script>