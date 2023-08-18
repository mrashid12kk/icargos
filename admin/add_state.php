<?php
session_start();
require 'includes/conn.php';
if (isset($_SESSION['users_id']) && ($_SESSION['type'] !== 'driver' && $_SESSION['type'] == 'admin')) {
    require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'], 8, 'add_only', $comment = null)) {

        header("location:access_denied.php");
    }
    include "includes/header.php";
?>

<body data-ng-app>
    <style type="text/css">
    .tox-statusbar__branding {
        display: none;
    }

    .tox-statusbar__text-container {
        display: none !important;
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

            <div class="row">
                <?php include "pages/location/location_sidebar.php"; ?>


                <div class="col-lg-10 " id="setting_box">
                    <div class="warper container-fluid">
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="panel panel-primary">
                                <div class="panel-heading"><?php echo isset($edit['id']) ? 'Update' : 'Add'; ?> Country
                                </div>
                                <div class="panel-body">
                                    <div class="">
                                        <?php
                                            ini_set('display_errors', 1);
                                            ini_set('display_startup_errors', 1);
                                            error_reporting(E_ALL);
                                            $date = date('Y-m-d H:i:s');
                                            if (isset($_POST['add_country'])) {

                                                $country_name = mysqli_real_escape_string($con, $_POST['country_name']);

                                                $title = mysqli_real_escape_string($con, $_POST['title']);

                                                $country_code = mysqli_real_escape_string($con, $_POST['country_code']);

                                                $description = mysqli_real_escape_string($con, $_POST['description']);

                                                $keyword = mysqli_real_escape_string($con, $_POST['keyword']);

                                                $zone_type_id = mysqli_real_escape_string($con, $_POST['zone_type_id']);

                                                $image = '';
                                                if (isset($_FILES["image"]["name"]) and !empty($_FILES["image"]["name"])) {

                                                    $target_dir = "assets/country/";
                                                    $target_file = $target_dir . uniqid() . basename($_FILES["image"]["name"]);

                                                    $extension = pathinfo($target_file, PATHINFO_EXTENSION);
                                                    if ($extension == 'jpg' || $extension == 'png' || $extension == 'JPG' || $extension == 'PNG' || $extension == 'gif' || $extension == 'GIF' || $extension == 'JPEG ' || $extension == 'jpeg ') {
                                                        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                                                            $image = $target_file;
                                                        }
                                                    } else {
                                                        $_SESSION['fail_add'] = 'Your Image Type in Wrong<br>';
                                                        header("Location:" . $_SERVER['HTTP_REFERER']);
                                                        exit();
                                                    }
                                                }

                                                $query2 = mysqli_query($con, "INSERT into `country`(country_name,image,title,country_code,description,keyword,zone_type_id,created_on)values('$country_name','$image','$title','$country_code','$description','$keyword','$zone_type_id','$date')") or die(mysqli_error($con));
                                                $rowscount = mysqli_affected_rows($con);
                                                if ($query2) {
                                                    echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> You Add a New Country successfully</div>';
                                                } else {
                                                    echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not Add a New Country unsuccessfully.</div>';
                                                }
                                            }

                                            //     ///Update template

                                            if (isset($_POST['update_country'])) {
                                                $id = $_GET['edit_id'];
                                                $country_name = mysqli_real_escape_string($con, $_POST['country_name']);

                                                $title = mysqli_real_escape_string($con, $_POST['title']);

                                                $country_code = mysqli_real_escape_string($con, $_POST['country_code']);

                                                $description = mysqli_real_escape_string($con, $_POST['description']);

                                                $keyword = mysqli_real_escape_string($con, $_POST['keyword']);

                                                $zone_type_id = mysqli_real_escape_string($con, $_POST['zone_type_id']);

                                                $image = '';
                                                if (isset($_FILES["image"]["name"]) and !empty($_FILES["image"]["name"])) {

                                                    $target_dir = "assets/country/";
                                                    $target_file = $target_dir . uniqid() . basename($_FILES["image"]["name"]);

                                                    $extension = pathinfo($target_file, PATHINFO_EXTENSION);
                                                    if ($extension == 'jpg' || $extension == 'png' || $extension == 'JPG' || $extension == 'PNG' || $extension == 'gif' || $extension == 'GIF' || $extension == 'JPEG ' || $extension == 'jpeg ') {
                                                        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                                                            $image = $target_file;
                                                        }
                                                    } else {
                                                        $_SESSION['fail_add'] = 'Your Image Type in Wrong<br>';
                                                        header("Location:" . $_SERVER['HTTP_REFERER']);
                                                        exit();
                                                    }
                                                }
                                                $query2 = mysqli_query($con, "UPDATE `country` set country_name='$country_name',title= '$title',country_code= '$country_code',description= '$description',keyword= '$keyword',zone_type_id= '$zone_type_id',image= '$image' where id=$id") or die(mysqli_error($con));
                                                $rowscount = mysqli_affected_rows($con);
                                                if ($query2) {
                                                    echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> You Updated  Country Successfully</div>';
                                                } else {
                                                    echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not upadated Country UnSuccessfully.</div>';
                                                }
                                            }
                                            if (isset($_GET['edit_id']) && $_GET['edit_id'] != '') {
                                                $edit = mysqli_fetch_assoc(mysqli_query($con, "SELECT * from country WHERE id=" . $_GET['edit_id']));
                                            }

                                            $zone_type = mysqli_query($con, "SELECT * from zone_type order by id desc");
                                            ?>
                                        <div class="row">
                                            <form method="post" action="" enctype="multipart/form-data">
                                                <div class="col-sm-8 template_form side_gapp">

                                                    <div class="row">
                                                        <div class="row">
                                                            <div class="col-sm-4 form_box">
                                                                <label>Country Name</label>
                                                                <input type="text" class="" required name="country_name"
                                                                    autocomplete="off"
                                                                    value="<?php echo isset($edit['country_name']) ? $edit['country_name'] : ''; ?>">
                                                            </div>
                                                            <div class="col-sm-4 form_box">
                                                                <label>Image</label>
                                                                <input type="file" class="" name="image"
                                                                    autocomplete="off">
                                                                <?php if (isset($edit['image']) && $edit['image'] != '') { ?>
                                                                <img src="<?php echo $edit['image']; ?>"
                                                                    style="width: 67%;">
                                                                <?php } ?>
                                                            </div>
                                                            <div class="col-sm-4 form_box">
                                                                <label>Title</label>
                                                                <input type="text" class="" name="title"
                                                                    autocomplete="off" required
                                                                    value="<?php echo isset($edit['title']) ? $edit['title'] : ''; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 form_box">
                                                            <label>Country Code</label>
                                                            <input type="text" class="" name="country_code"
                                                                autocomplete="off"
                                                                value="<?php echo isset($edit['country_code']) ? $edit['country_code'] : ''; ?>">
                                                        </div>
                                                        <div class="col-sm-4">

                                                            <div class="form-group">

                                                                <label class="control-label">Zone Type</label>

                                                                <select type="text" class="form-control select2"
                                                                    name="zone_type_id" required>
                                                                    <option value="">Select Zone Type</option>
                                                                    <?php while ($row = mysqli_fetch_array($zone_type)) { ?>
                                                                    <option value="<?php echo $row['id'] ?>"
                                                                        <?php echo isset($edit) && $edit['zone_type_id'] == $row['id'] ? 'selected' : ''; ?>>
                                                                        <?php echo $row['zone_name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                                <div class="help-block with-errors "></div>

                                                            </div>

                                                        </div>
                                                        <div class="col-sm-12 form_box">
                                                            <label>Description</label>
                                                            <textarea name="description" cols="30" rows="10"
                                                                required><?php echo isset($edit['description']) ? $edit['description'] : ''; ?></textarea>
                                                        </div>

                                                        <div class="col-sm-12 form_box">
                                                            <label>Keyword</label>
                                                            <textarea name="keyword" cols="30" rows="10"
                                                                required><?php echo isset($edit['keyword']) ? $edit['keyword'] : ''; ?></textarea>
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6 send_btn">
                                                            <button class="send_button" name="<?php if (isset($edit['id'])) {
                                                                                                        echo 'update_country';
                                                                                                    } else {
                                                                                                        echo 'add_country';
                                                                                                    } ?>"
                                                                type="SUBMIT"><?php echo isset($edit['id']) ? 'Update' : 'Submit'; ?></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- <div class="page-header"><h1>Dashboard <small>Let's get a quick overview...</small></h1></div> -->
            <!--  <?php

                        // include "pages/location/location_sidebar.php";
                        // include "pages/location/add_state.php";

                        ?> -->


        </div>
        <!-- Warper Ends Here (working area) -->


        <?php

        include "includes/footer.php";
    } else {
        header("location:index.php");
    }
        ?>