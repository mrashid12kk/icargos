<?php
    session_start();
    require 'includes/conn.php';
    if(isset($_SESSION['users_id']) && $_SESSION['type']=='admin'){
         require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'],47,'view_only',$comment =null)) {

        header("location:access_denied.php");
    }
    include "includes/header.php";

?>
<body data-ng-app>


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

            <div class="page-header"><h1><?php echo getLange('dashboard'); ?> <small><?php echo getLange('letsgetquick'); ?></small></h1></div>
              <div class="row">
                <?php
            require_once "setup-sidebar.php";
          ?>
          <div class="col-sm-10 table-responsive" id="setting_box">
            <?php

            include "pages/transport/voucher_type.php";

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
