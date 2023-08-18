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

            <!-- <div class="page-header"><h1>Dashboard <small>Let's get a quick overview...</small></h1></div> -->

            <div class="row">
                <?php include "pages/location/location_sidebar.php"; ?>


                <div class="col-lg-10 " id="setting_box">
                    <div class="warper container-fluid">
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="panel panel-primary">
                                <div class="panel-heading">Add City</div>
                                <div class="panel-body">
                                    <div class="">
                                        <?php
                                            ini_set('display_errors', 1);
                                            ini_set('display_startup_errors', 1);
                                            error_reporting(E_ALL);
                                            $date = date('Y-m-d H:i:s');
                                            if (isset($_POST['add_city'])) {
                                                $country_id = mysqli_real_escape_string($con, $_POST['country_id']);

                                                $state_id = mysqli_real_escape_string($con, $_POST['state_id']);

                                                $city_name = mysqli_real_escape_string($con, $_POST['city_name']);

                                                $title = mysqli_real_escape_string($con, $_POST['title']);

                                                $description = mysqli_real_escape_string($con, $_POST['description']);

                                                $keyword = mysqli_real_escape_string($con, $_POST['keyword']);

                                                $query2 = mysqli_query($con, "INSERT into `city`(country_id,state_id,city_name,title,description,keyword,created_on)values('$country_id','$state_id','$city_name','$title','$description','$keyword','$date')") or die(mysqli_error($con));
                                                $rowscount = mysqli_affected_rows($con);
                                                if ($query2) {
                                                    echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> You Add a New Country successfully</div>';
                                                } else {
                                                    echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not Add a New Country unsuccessfully.</div>';
                                                }
                                            }
                                            if (isset($_POST['update_city'])) {
                                                $id = $_GET['edit_id'];

                                                $country_id = mysqli_real_escape_string($con, $_POST['country_id']);

                                                $state_id = mysqli_real_escape_string($con, $_POST['state_id']);

                                                $city_name = mysqli_real_escape_string($con, $_POST['city_name']);

                                                $title = mysqli_real_escape_string($con, $_POST['title']);

                                                $description = mysqli_real_escape_string($con, $_POST['description']);

                                                $keyword = mysqli_real_escape_string($con, $_POST['keyword']);

                                                $query2 = mysqli_query($con, "UPDATE `city` set country_id='$country_id',state_id='$state_id',city_name= '$city_name',title= '$title',description= '$description',keyword= '$keyword' where id=$id") or die(mysqli_error($con));
                                                $rowscount = mysqli_affected_rows($con);
                                                if ($query2) {
                                                    echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> You Updated  City Successfully</div>';
                                                } else {
                                                    echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not upadated City UnSuccessfully.</div>';
                                                }
                                            }
                                            $country = mysqli_query($con, "SELECT * from country order by id desc");
                                            $state = mysqli_query($con, "SELECT * from state order by id desc");

                                            if (isset($_GET['edit_id']) && $_GET['edit_id'] != '') {
                                                $edit = mysqli_fetch_assoc(mysqli_query($con, "SELECT * from city WHERE id=" . $_GET['edit_id']));
                                            }

                                            ?>
                                        <div class="row">
                                            <form method="post" action="" enctype="multipart/form-data">
                                                <div class="col-sm-12 template_form side_gapp">

                                                    <div class="row">
                                                        <div class="col-sm-3 form_box">
                                                            <label>Country</label>
                                                            <select type="text" class="js-example-basic-single country"
                                                                required name="country_id">
                                                                <option value="" disabled selected>Select Country
                                                                </option>
                                                                <?php
                                                                    while ($row = mysqli_fetch_array($country)) {
                                                                        $selected = isset($edit['country_id']) && $edit['country_id'] == $row['id'] ? 'selected' : '';
                                                                        echo "<option value='" . $row['id'] . "' " . $selected . ">" . $row['country_name'] . "</option>";
                                                                    }
                                                                    ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-3 form_box">
                                                            <label>State</label>
                                                            <div class="get_state">
                                                                <select type="text"
                                                                    class="js-example-basic-single state" required
                                                                    name="state_id" autocomplete="off">
                                                                    <option value="" disabled selected>Select State
                                                                    </option>
                                                                    <?php
                                                                        while ($row = mysqli_fetch_array($state)) {

                                                                            $selected = isset($edit['state_id']) && $edit['state_id'] == $row['id'] ? 'selected' : '';
                                                                            echo "<option value='" . $row['id'] . "' " . $selected . ">" . $row['state_name'] . "</option>";
                                                                        }
                                                                        ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3 form_box">
                                                            <label>City Name</label>
                                                            <input type="text" class="" name="city_name"
                                                                autocomplete="off"
                                                                value="<?php echo isset($edit['city_name']) ? $edit['city_name'] : ''; ?>"
                                                                required>
                                                        </div>
                                                        <div class="col-sm-3 form_box">
                                                            <label>Title</label>
                                                            <input type="text" class="" name="title" autocomplete="off"
                                                                value="<?php echo isset($edit['title']) ? $edit['title'] : ''; ?>"
                                                                required>
                                                        </div>

                                                    </div>
                                                    <div class="row">


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
                                                                                                        echo 'update_city';
                                                                                                    } else {
                                                                                                        echo 'add_city';
                                                                                                    } ?>"
                                                                type="SUBMIT">Submit</button>
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

            <!--  <?php

                        // include "pages/location/location_sidebar.php";
                        // include "pages/location/add_city.php";

                        ?> -->


        </div>
        <!-- Warper Ends Here (working area) -->


        <?php

        include "includes/footer.php";
    } else {
        header("location:index.php");
    }
        ?>
        <script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
$('body').on('change','.country',function(e){
    e.preventDefault();
    var country_id=$(this).val();
          $.ajax({
          type:'POST',
          data:{country_id:country_id,get_country:1},
          url:'ajax.php',
          success:function(response){
          $('.get_state').html('');
          $('.get_state').html(response);
          $('.js-example-basic-single').select2();
          }
          });
     })
}, false);
</script>