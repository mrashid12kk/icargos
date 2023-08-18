<?php
session_start();
require 'includes/conn.php';
if (isset($_POST['fav'])) {
    echo "<pre>";
    print_r($_POST);
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $value = isset($_POST['value']) ? $_POST['value'] : '';
    mysqli_query($con, "UPDATE services set is_favourite = 0");

    if (isset($value) && $value == 1) {
        mysqli_query($con, "UPDATE services set is_favourite = 1 Where id = $id");
    }
}
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
                        if (isset($_POST['updateservice'])) {
                            for ($i = 0; $i < count($_POST['city']); $i++) {

                                $query1 = mysqli_query($con, "INSERT INTO `cities`(`city_name`) VALUES ('" . $_POST['city'][$i] . "')") or die(mysqli_error($con));
                                $rowscount = mysqli_affected_rows($con);
                                if ($rowscount > 0) {
                                    $msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you added a new City successfully</div>';
                                } else {
                                    $msg = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not added a new City unsuccessfully.</div>';
                                }
                            }
                        }
                        $icon = '';
                        if (isset($_POST['updateservice'])) {
                            $is_pnd = isset($_POST['is_pnd']) ? $_POST['is_pnd'] : 0;
                            if (isset($_FILES["icon"]["name"]) && !empty($_FILES["icon"]["name"])) {
                                $target_dir = "assets/services/";
                                $target_file = $target_dir . uniqid() . basename($_FILES["icon"]["name"]);
                                // $db_dir = "users/";
                                // $db_file = $db_dir .uniqid(). basename($_FILES["icon"]["name"]);
                                $extension = pathinfo($target_file, PATHINFO_EXTENSION);
                                if ($extension == 'jpg' || $extension == 'png' || $extension == 'JPG' || $extension == 'PNG' || $extension == 'gif' || $extension == 'GIF' || $extension == 'jpeg' || $extension == 'JPEG') {
                                    if (move_uploaded_file($_FILES["icon"]["tmp_name"], $target_file)) {
                                        // echo $target_file;
                                        $icon = ", icon='" . $target_file . "'";
                                    }
                                } else {
                                    $msg = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button>our Logo Image Type In Wrong</div>';
                                    $_SESSION['message_service'] = $msg;
                                    header("Location:servicelist.php");
                                    exit();
                                }
                            }
                            $query1 = mysqli_query($con, " UPDATE services SET service_type='" . $_POST['service_type'] . "',service_code='" . $_POST['service_code'] . "',product_id='" . $_POST['product_id'] . "',additional_charges='" . $_POST['additional_charges'] . "', is_pnd = " . $is_pnd . " $icon WHERE id='" . $_GET['edit_id'] . "' ") or die(mysqli_error($con));
                            $rowscount = mysqli_affected_rows($con);
                            if ($rowscount > 0) {
                                $msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> Service type updated successfully</div>';
                            } else {
                                $msg = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> Service type have not updated.</div>';
                            }
                        }
                        if (isset($_POST['addservice'])) {
                            $msg = '';
                            $service_type = $_POST['service_type'];
                            $service_code = $_POST['service_code'];
                            $product_id = $_POST['product_id'];
                            $is_pnd = isset($_POST['is_pnd']) ? $_POST['is_pnd'] : 0;
                            $additional_charges = $_POST['additional_charges'];
                            $icon = '';
                            if (isset($_FILES["icon"]["name"]) && !empty($_FILES["icon"]["name"])) {
                                $target_dir = "assets/services/";
                                $target_file = $target_dir . uniqid() . basename($_FILES["icon"]["name"]);
                                // $db_dir = "users/";
                                // $db_file = $db_dir .uniqid(). basename($_FILES["icon"]["name"]);
                                $extension = pathinfo($target_file, PATHINFO_EXTENSION);
                                if ($extension == 'jpg' || $extension == 'png' || $extension == 'JPG' || $extension == 'PNG' || $extension == 'gif' || $extension == 'GIF' || $extension == 'jpeg' || $extension == 'JPEG') {
                                    if (move_uploaded_file($_FILES["icon"]["tmp_name"], $target_file)) {
                                        // echo $target_file;

                                        $icon = $target_file;
                                    }
                                } else {
                                    $msg = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button>our Logo Image Type In Wrong</div>';
                                    $_SESSION['message_service'] = $msg;
                                    exit();
                                }
                            }
                            mysqli_query($con, " INSERT INTO services(`service_type`,`service_code`,`product_id`,`additional_charges`,`icon`,`is_pnd`) VALUES('" . $service_type . "','" . $service_code . "','" . $product_id . "','" . $additional_charges . "','" . $icon . "','" . $is_pnd . "') ");
                            $rowscount = mysqli_affected_rows($con);
                            if ($rowscount > 0) {
                                $msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you added a new Service successfully</div>';
                            } else {
                                $msg = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not added a new Service unsuccessfully.</div>';
                            }
                            $_SESSION['message_service'] = $msg;
                        }
                        echo $msg;
                        if (isset($_SESSION['message_service'])) {
                            // echo $_SESSION['message_service'];
                            unset($_SESSION['message_service']);
                        }
                        if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {

                            $service_type = $_GET['edit_id'];
                            $service_q = mysqli_query($con, "SELECT * FROM services WHERE id='" . $service_type . "' ");
                            $edit = mysqli_fetch_array($service_q);
                        }
                        $products = mysqli_query($con, "SELECT * FROM products ORDER BY id DESC")
                        ?>
                        <div class="panel panel-default">
                            <div class="panel-heading"><?php echo getLange('addservicetype'); ?></div>
                            <div class="panel-body" id="same_form_layout">

                                <form role="form" data-toggle="validator" enctype="multipart/form-data" action="#" method="post">
                                    <div id="cities">

                                        <div class="row" id="select_citites">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label"><span style="color: red;">*</span><?php echo getLange('servicetype'); ?></label>
                                                    <input type="text" class="form-control" name="service_type" placeholder="<?php echo getLange('servicetype'); ?>" value="<?php if (isset($edit)) {  echo $edit['service_type']; } ?>" required>
                                                    <div class="help-block with-errors "></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label"><span style="color: red;">*</span><?php echo getLange('code'); ?></label>
                                                    <input type="text" class="form-control" name="service_code" placeholder="<?php echo getLange('servicetype') . ' ' . getLange('code'); ?>" value="<?php if (isset($edit)) { echo $edit['service_code']; } ?>" required>
                                                    <div class="help-block with-service_code "></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label"><span style="color: red;">*</span>Product</label>
                                                    <select class="form-control select2" name="product_id" required>
                                                        <option value="">Select Product</option>
                                                        <?php while ($row = mysqli_fetch_array($products)) {
                                                        ?>
                                                            <option value="<?php echo $row['id']; ?>" <?php echo isset($edit) && $edit['product_id'] == $row['id'] ? 'selected' : ''; ?>>
                                                                <?php echo $row['name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <div class="help-block with-service_code "></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Additional Charges</label>
                                                    <input type="text" class="form-control" name="additional_charges" placeholder="Additional Charges" value="<?php if (isset($edit)) { echo $edit['additional_charges']; } ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Service Icon</label>
                                                    <input type="file" class="form-control" name="icon" placeholder="Icon">
                                                </div>
                                            </div>
                                            <div class="col-md-4 " style="margin-top:30px;">
                                                <div class="form-group">

                                                    <input type="checkbox" class="" name="is_pnd" value="1" <?php if (isset($edit) && $edit['is_pnd'] == 1) { echo "checked"; } ?>>
                                                    <label class="control-label">Is P&D</label>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="<?php if (isset($edit)) { echo 'edit_id'; } ?>" value="<?php if (isset($edit)) { echo $_GET['edit_id']; } ?>">

                                        <div class="row">
                                            <div class="col-md-4 rtl_full">
                                                <button type="submit" name="<?php if (isset($edit)) { echo 'updateservice';  } else { echo 'addservice'; } ?>" class="add_form_btn"><?php echo getLange('submit'); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                </form>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading"><?php echo getLange('servicetype'); ?>

                            </div>
                            <div class="panel-body" id="same_form_layout" style="padding: 11px;">
                                <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info">
                                        <thead>
                                            <tr role="row">
                                                <th style="width: 2%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php echo getLange('srno'); ?></th>
                                                <th style="width: 20%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php echo getLange('servicetype'); ?> </th>
                                                <th style="width: 20%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php echo getLange('servicecode'); ?> </th>
                                                <th style="width: 20%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;">Product </th>
                                                <th style="width: 20%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;">Additional Charges</th>
                                                <th style="width: 20%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;">Icon</th>
                                                <th style="width: 20%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;">Favourite</th>
                                                <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 108px;"><?php echo getLange('action'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query1 = mysqli_query($con, "SELECT services.id,services.service_type,services.icon,services.is_favourite,services.service_code,services.additional_charges,products.name FROM services LEFT JOIN products on services.product_id=products.id ORDER BY id desc");
                                            $sr = 1;
                                            while ($fetch1 = mysqli_fetch_array($query1)) {
                                                $check_q = mysqli_query($con, "SELECT service_type from zone where service_type='" . $fetch1['id'] . "'");
                                                $rowscount = mysqli_affected_rows($con);
                                                if ($rowscount > 0) {
                                                    $checkserviceDelete = false;
                                                } else {
                                                    $checkserviceDelete = true;
                                                }
                                                $icon = '';
                                                if (file_exists($fetch1['icon'])) {
                                                    $icon = $fetch1['icon'];
                                                }
                                            ?>
                                                <tr class="gradeA odd" role="row">
                                                    <td><?php echo $sr; ?></td>
                                                    <td class="sorting_1"><?php echo $fetch1['service_type']; ?></td>
                                                    <td class="sorting_1" style="text-transform: uppercase;">
                                                        <?php echo $fetch1['service_code']; ?></td>
                                                    <td class="sorting_1" style="text-transform: uppercase;">
                                                        <?php echo $fetch1['name']; ?></td>
                                                    <td class="sorting_1" style="text-transform: uppercase;">
                                                        <?php echo $fetch1['additional_charges']; ?></td>
                                                    <td class="sorting_1">
                                                        <img src="<?php echo $icon; ?>" alt="" style="width:100px;">
                                                    </td>
                                                    <td class="sorting_1">
                                                        <span>
                                                            <input <?php echo isset($fetch1['is_favourite']) && $fetch1['is_favourite'] == 1 ? 'checked' : ''; ?> type="checkbox" value="<?php echo $fetch1['id']; ?>" class="mark_favourite">
                                                        </span>
                                                    </td>

                                                    <td class="center">

                                                        <a href="servicelist.php?edit_id=<?php echo $fetch1['id']; ?>">
                                                            <span class="glyphicon glyphicon-edit"></span>
                                                        </a>

                                                        <?php if ($checkserviceDelete) : ?>
                                                            <a href="deleteservice.php?service_id=<?php echo $fetch1['id']; ?>" onclick="return confirm('Are you sure you want to delete this service?');">
                                                                <span class="glyphicon glyphicon-trash"></span>
                                                            </a>
                                                        <?php endif ?>
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
            $(document).on('click', '.mark_favourite', function() {
                let id = $(this).val();
                let value = 0;
                if ($('.mark_favourite').is(':checked')) {
                    value = 1;
                } else {
                    value = 0;
                }
                $.ajax({
                    url: "servicelist.php",
                    type: "post",
                    data: {
                        fav: 1,
                        id: id,
                        value: value
                    },
                    success: function(response) {
                        location.reload();
                    }
                });
            })
        </script>