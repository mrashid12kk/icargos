<?php
session_start();
require 'includes/conn.php';

if (isset($_SESSION['users_id'])) {
    // require_once "includes/role_helper.php";
    // if (!checkRolePermission($_SESSION['user_role_id'], 17, 'view_only', $comment = null)) {
    //     header("location:access_denied.php");
    // }
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
                        if (isset($_POST['addvendor'])) {
                            $logo='';
                            $name = isset($_POST['name']) ? $_POST['name'] : '';
                            $vendor_url = isset($_POST['vendor_url']) ? $_POST['vendor_url'] : '';
                            $is_active = isset($_POST['is_active']) ? $_POST['is_active'] : '0';
                            $vendor_code = isset($_POST['vendor_code']) ? $_POST['vendor_code'] : '';
                            if (isset($_FILES["logo"]["name"]) && !empty($_FILES["logo"]["name"])) {
                                $target_dir = "assets/images/vendor/";
                                $uniqid_img= uniqid() . basename($_FILES["logo"]["name"]);
                                $target_file = $target_dir . $uniqid_img;
                                $extension = pathinfo($target_file, PATHINFO_EXTENSION);

                                if ($extension == 'jpg' || $extension == 'png' || $extension == 'JPG' || $extension == 'PNG' || $extension == 'gif' || $extension == 'GIF' || $extension == 'jpeg' || $extension == 'JPEG') {
                                    if (move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file)) {
                                        $logo = $uniqid_img;
                                    }
                                } else {
                                    $msg='<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button>Our Logo Image Type In Wrong</div>';
                                    $_SESSION['message_service'] = $msg;
                                    header("Location:vendors.php");
                                    exit();
                                }
                            }
                            // echo  " INSERT INTO vendors(`name`,`vendor_url`,`is_active`,`logo`) VALUES('" . $name . "','" . $vendor_url . "','" . $is_active . "','" . $logo . "')";die;
                            mysqli_query($con, " INSERT INTO vendors(`name`,`vendor_url`,`vendor_code`,`is_active`,`logo`) VALUES('" . $name . "','" . $vendor_url . "','" . $vendor_code . "','" . $is_active . "','" . $logo . "') ");
                            $rowscount = mysqli_affected_rows($con);
                            if ($rowscount > 0) {
                                $msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you added a new Vendor successfully</div>';
                            } else {
                                $msg = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not added a new Vendor unsuccessfully.</div>';
                            }
                        }
                        if (isset($_POST['updatevendor'])) {
                            $name = isset($_POST['name']) ? $_POST['name'] : '';
                            $vendor_url = isset($_POST['vendor_url']) ? $_POST['vendor_url'] : '';
                            $vendor_code = isset($_POST['vendor_code']) ? $_POST['vendor_code'] : '';
                            $is_active = isset($_POST['is_active']) ? $_POST['is_active'] : '0';
                            if (isset($_FILES["logo"]["name"]) && !empty($_FILES["logo"]["name"])) {
                                $target_dir = "assets/images/vendor/";
                                $uniqid_img= uniqid() . basename($_FILES["logo"]["name"]);
                                $target_file = $target_dir . $uniqid_img;
                                $extension = pathinfo($target_file, PATHINFO_EXTENSION);

                                if ($extension == 'jpg' || $extension == 'png' || $extension == 'JPG' || $extension == 'PNG' || $extension == 'gif' || $extension == 'GIF' || $extension == 'jpeg' || $extension == 'JPEG') {
                                    if (move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file)) {
                                        $logo = ", logo='".$uniqid_img."'";
                                    }
                                } else {
                                    echo $target_file.'1232';die;
                                    $msg='<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button>our Logo Image Type In Wrong</div>';
                                    $_SESSION['message_service'] = $msg;
                                    header("Location:vendors.php");
                                    exit();
                                }
                            }
                            $query1 = mysqli_query($con, " UPDATE vendors SET name='" . $name . "',vendor_url='" . $vendor_url . "',vendor_code='" . $vendor_code . "',is_active='" . $is_active . "' ". $logo."  WHERE id='" . $_GET['edit_id'] . "' ") or die(mysqli_error($con));
                            $rowscount = mysqli_affected_rows($con);
                            if ($rowscount > 0) {
                                $msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> Vendor updated successfully</div>';
                            } else {
                                $msg = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> Vendor have not updated.</div>';
                            }
                        }
                        echo $msg;
                        if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
                            $edit_id = $_GET['edit_id'];
                            $vendor_q = mysqli_query($con, "SELECT * FROM vendors WHERE id='" . $edit_id . "' ");
                            $edit = mysqli_fetch_array($vendor_q);
                        }
                        ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">Vendors</div>
                            <div class="panel-body" id="same_form_layout">

                                <form action="" method="post" enctype="multipart/form-data">
                                    <div id="cities">
                                        <div class="row" id="select_citites">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Name</label>
                                                    <input type="text" class="form-control" name="name" value="<?php echo isset($edit['name']) ? $edit['name'] : ''; ?>" required>
                                                    <div class="help-block with-errors "></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Vendor Code</label>
                                                    <input type="text" class="form-control" name="vendor_code" value="<?php echo isset($edit['vendor_code']) ? $edit['vendor_code'] : ''; ?>" required>
                                                    <div class="help-block with-errors "></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Active/InActive</label>
                                                    <select class="form-control select2" name="is_active" required>
                                                        <option <?php echo isset($edit) && $edit['is_active'] == '1' ? 'selected' : ''; ?>
                                                        value="1">Active</option>
                                                        <option <?php echo isset($edit) && $edit['is_active'] == '0' ? 'selected' : ''; ?>     value="0">InActive</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Logo</label>
                                                <?php echo isset($edit['name']) ? "<img src='assets/images/vendor/".$edit['logo']."' style='width:100px;'>" : ''; ?>
                                                <input type="file" class="form-control" name="logo" accept="image/*">
                                                <div class="help-block with-errors "></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Vendor Url</label>
                                                    <textarea type="text" class="form-control" name="vendor_url" placeholder="Vendor Url" ><?php echo isset($edit['vendor_url']) ? $edit['vendor_url'] : ''; ?></textarea>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 rtl_full">
                                                <button type="submit" name="<?php if (isset($edit)) {
                                                    echo 'updatevendor';} else { echo 'addvendor';} ?>" class="add_form_btn"><?php echo getLange('submit'); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                    </form>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">Vendors
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
                                            style="width: 179px;">Name </th>
                                            <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                            aria-controls="basic-datatable" rowspan="1" colspan="1"
                                            aria-sort="ascending"
                                            aria-label="Rendering engine: activate to sort column descending"
                                            style="width: 179px;">Vendor Code </th>
                                            <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                            aria-controls="basic-datatable" rowspan="1" colspan="1"
                                            aria-sort="ascending"
                                            aria-label="Rendering engine: activate to sort column descending"
                                            style="width: 179px;">Logo</th>
                                            <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                            aria-controls="basic-datatable" rowspan="1" colspan="1"
                                            aria-sort="ascending"
                                            aria-label="Rendering engine: activate to sort column descending"
                                            style="width: 179px;">Vendor Url</th>
                                            <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                            aria-controls="basic-datatable" rowspan="1" colspan="1"
                                            aria-sort="ascending"
                                            aria-label="Rendering engine: activate to sort column descending"
                                            style="width: 179px;">Active/InActive</th>
                                            <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1"
                                            colspan="1" aria-label="CSS grade: activate to sort column ascending"
                                            style="width: 108px;"><?php echo getLange('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query1 = mysqli_query($con, "SELECT * from vendors ORDER BY id desc");
                                        $sr = 1;
                                        while ($fetch1 = mysqli_fetch_array($query1)) {
                                            ?>
                                            <tr class="gradeA odd" role="row">
                                                <td><?php echo $sr; ?></td>
                                                <td class="sorting_1"><?php echo $fetch1['name']; ?></td>
                                                <td class="sorting_1"><?php echo $fetch1['vendor_code']; ?></td>
                                                <td class="sorting_1" style="text-transform: uppercase;"><img src="<?php echo BASE_URL; ?>admin/assets/images/vendor/<?php echo $fetch1['logo']; ?>" style="width: 100px;"></td>
                                                <td class="sorting_1" style="text-transform: uppercase;"><?php echo $fetch1['vendor_url']; ?></td>
                                                <td class="sorting_1" style="text-transform: uppercase;"><?php echo isset($fetch1['is_active']) && $fetch1['is_active']==1 ? 'Active' : 'InActive'; ?></td>
                                                <td class="center">
                                                    <a href="vendors.php?edit_id=<?php echo $fetch1['id']; ?>">
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