<?php
session_start();
require 'includes/conn.php';
require_once "includes/role_helper.php";

if(isset($_SESSION['users_id'])){


    include "includes/header.php";
    $customers = mysqli_query($con,"SELECT * FROM customers WHERE status=1");
    $currency = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='currency' "));
    ?>
    <body data-ng-app >
        <?php
    // echo "zafir";
    // die;




        include "includes/sidebar.php";

        ?>
        <!-- Aside Ends-->

        <section class="content" >

            <?php
            include "includes/header2.php";
            if (checkRolePermission($_SESSION['user_role_id'],1,'view_only',$comment =null)) {
                ?>

                <!-- Header Ends -->


                <div class="warper container-fluid dashboard_icons">

                    <div class="page-header"><h1>
                        <?php echo getlange('dashboard') ?> <small><?php echo getlange('letsgetquick'); ?></small>
                        <?php if(isset($_SESSION['type']) && $_SESSION['type'] == 'admin') {
                            include_once 'includes/functions.php';
                            ?>
                            <?php
                            $active_id = "";
                            if(isset($_GET['active_customer'])){
                                $active_id = $_GET['active_customer'];
                            }
                            ?>

                        <?php } ?>
                    </h1></div>

                    <?php if ($_SESSION['user_role_id'] == 1): ?>
                        <?php include "pages/dashboard.php"; ?>
                    <?php endif ?>

                    <?php if ($_SESSION['user_role_id'] == 4 or  $_SESSION['user_role_id'] == 3 ): ?>
                        <?php include "pages/dashboard_rider.php"; ?>
                    <?php endif ?>

                    <?php if ($_SESSION['user_role_id'] != 1 and $_SESSION['user_role_id'] != 4 and   $_SESSION['user_role_id'] != 3): ?>
                        <?php include "pages/dashboard.php"; ?>
                    <?php endif ?>

                </div>
                <!-- Warper Ends Here (working area) -->


                <?php
            }
            include "includes/footer.php";

        }
        else{
            header("location:index.php");
        }
        ?>
        <script type="text/javascript">
         $(function () {
            $('.datetimepicker4').datetimepicker({
                format: 'YYYY/MM/DD',
            });
        });
    </script>

