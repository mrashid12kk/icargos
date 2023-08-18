<?php
session_start();
require 'includes/conn.php';

if (isset($_SESSION['users_id'])) {
    require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'], 59, 'view_only', $comment = null)) {

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
               
                <?php include "pages/location/location_sidebar.php"; ?>
                <div class="col-sm-10 table-responsive" id="setting_box">
                    <?php
                        $msg = "";
                        if (isset($_GET['delete_id'])) {
                            $id = $_GET['delete_id'];
                            $query1 = mysqli_query($con, "DELETE from country where id=$id") or die(mysqli_error($con));
                            $rowscount = mysqli_affected_rows($con);
                            if ($rowscount > 0) {
                                echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>' . getLange('Well_done') . '!</strong>You delete a Country Successfully</div>';
                            } else {
                                echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>' . getLange('unsuccessful') . '!</strong>Ypu cannot delete Country unsuccessfully.</div>';
                            }
                        }
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
                            if (isset($_FILES["image"]["name"]) && $_FILES["image"]["name"]!='') {

                                $target_dir = "assets/country/";
                                $target_file = $target_dir . uniqid() . basename($_FILES["image"]["name"]);

                                $extension = pathinfo($target_file, PATHINFO_EXTENSION);
                                if ($extension == 'jpg' || $extension == 'png' || $extension == 'JPG' || $extension == 'PNG' || $extension == 'gif' || $extension == 'GIF' || $extension == 'JPEG ' || $extension == 'jpeg ') {
                                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                                        $image = $target_file;
                                        $query2 = mysqli_query($con, "UPDATE `country` set image= '$image' where id=$id") or die(mysqli_error($con));
                                    }
                                } else {
                                    $_SESSION['fail_add'] = 'Your Image Type in Wrong<br>';
                                    header("Location:" . $_SERVER['HTTP_REFERER']);
                                    exit();
                                }
                            }
                            $query2 = mysqli_query($con, "UPDATE `country` set country_name='$country_name',title= '$title',country_code= '$country_code',description= '$description',keyword= '$keyword',zone_type_id= '$zone_type_id' where id=$id") or die(mysqli_error($con));
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
                    <div class="panel panel-default">
                        <div class="panel-heading">Country</div>
                        <div class="panel-body" id="same_form_layout">
                            
                            <form role="form" data-toggle="validator" action="country.php<?php echo isset($_GET['edit_id']) ? '?edit_id='.$_GET['edit_id'] : ''; ?>" method="post"  enctype="multipart/form-data">
                                <div id="cities">

                                    <div class="row" id="select_citites">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Country Name</label>
                                                <input type="text" class="form-control" name="country_name" value="<?php echo isset($edit['country_name']) ? $edit['country_name'] : ''; ?>" required>
                                                <div class="help-block with-errors "></div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Image</label>
                                                <input type="file" class="form-control" name="image" >
                                                  <?php if (isset($edit['image']) && $edit['image'] != '') { ?>
                                                    <img src="<?php echo $edit['image']; ?>"
                                                        style="width: 67%;">
                                                    <?php } ?>
                                            </div>
                                        </div>
                                        <div class="col-md-3" style="display: none;">
                                            <div class="form-group">
                                                <label class="control-label">Title</label>
                                                <input type="text" class="form-control" name="title" value="<?php echo isset($edit['title']) ? $edit['title'] : ''; ?>">
                                                <div class="help-block with-errors "></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Country Code</label>
                                                <input type="text" class="form-control" name="country_code" value="<?php echo isset($edit['country_code']) ? $edit['country_code'] : ''; ?>" required>
                                                <div class="help-block with-errors "></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">

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
                                        </div>
                                        <div class="row">
                                        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Description</label>
                                                <textarea class="form-control" name="description" ><?php if (isset($edit)) {echo $edit['description'];}  ?></textarea>
                                            </div>
                                        </div>
                                         <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Keyword</label>
                                                <textarea class="form-control" name="keyword"><?php if (isset($edit)) {echo $edit['keyword'];}  ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 rtl_full">
                                            <button type="submit" name="<?php if (isset($edit)) {echo 'update_country';} else {echo 'add_country';} ?>" class="add_form_btn"><?php echo getLange('submit'); ?></button>
                                        </div>
                                    </div>
                                </div>
                                <br>
                            </form>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">Country

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
                                                style="width: 179px;">Country Name</th>
                                            <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;">Flag </th>
                                            <!-- <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;">Title </th> -->
                                            <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;">Code </th>
                                            <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;">Description </th>
                                            <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;">keywords </th>


                                            <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1"
                                                colspan="1" aria-label="CSS grade: activate to sort column ascending"
                                                style="width: 108px;"><?php echo getLange('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $query1 = mysqli_query($con, "SELECT * from country ORDER BY id desc");
                                            $sr = 1;
                                            while ($fetch1 = mysqli_fetch_array($query1)) {
                                                 $flag='';
                                               if (isset($fetch1['image']) && $fetch1['image']!='') {
                                                  $flag="<img src='".$fetch1['image']."' class='circle' style='width: 100px;'>";
                                               }
                                            ?>
                                        <tr class="gradeA odd" role="row">
                                            <td><?php echo $sr; ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['country_name']; ?></td>
                                            <td class="sorting_1" style="text-transform: uppercase;">
                                                <?php echo $flag; ?></td>
                                            <!-- <td class="sorting_1"><?php echo $fetch1['title']; ?></td> -->
                                            <td class="sorting_1"><?php echo $fetch1['country_code']; ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['description']; ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['keyword']; ?></td>
                                            <td class="center">
                                                <a href="country.php?edit_id=<?php echo $fetch1['id']; ?>">
                                                    <span class="glyphicon glyphicon-edit"></span>
                                                </a>
                                                <a href="country.php?delete_id=<?php echo $fetch1['id']; ?>"
                                                    onclick="return confirm('Are you sure you want to delete this Country ?');">
                                                    <span class="glyphicon glyphicon-trash"></span>
                                                </a>
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