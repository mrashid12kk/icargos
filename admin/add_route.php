<?php
    session_start();
    require 'includes/conn.php';
    if(isset($_SESSION['users_id']) &&($_SESSION['type']!=='driver' && $_SESSION['type'] == 'admin')){
    require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'],8,'add_only',$comment =null)) {

        header("location:access_denied.php");
    }
    include "includes/header.php";
 ?>
<body data-ng-app>
<style type="text/css">
  .tox-statusbar__branding{
    display: none;
  }
  .tox-statusbar__text-container{
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
 <?php

            include "pages/location/location_sidebar.php";
            include "pages/location/add_route.php";

            ?>


        </div>
        <!-- Warper Ends Here (working area) -->


      <?php

    include "includes/footer.php";
    }
    else{
        header("location:index.php");
    }
    ?>