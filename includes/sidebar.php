<?php

$id = $_SESSION['customers'];
$query = mysqli_query($con, "select * from customers where id='$id'");
$fetch = mysqli_fetch_array($query);
$id = $fetch['id'];
$images = $fetch['image'];
$fname = $fetch['fname'];
$bname = $fetch['bname'];
$api_status = $fetch['api_status'];
$is_booking_manual = $fetch['is_booking_manual'];
// if($is_booking_manual == 1)
// {
// }
$current = basename($_SERVER['PHP_SELF']);
?>
<style>
    section .white {
        min-height: auto;
    }

    @media(max-width: 1199px) {
        .container {
            width: 1000px;
        }

        .navbar-nav li:last-child {
            padding: 8px 22px 3px;
        }

        .profile-page-title {
            padding-top: 0;
        }

        #license .col-lg-8 {
            padding: 0 !important;
        }
    }

    @media(max-width: 1024px) {
        .container {
            width: 740px;
        }

        #menu-main .active a {
            color: #000 !important;
            background: none !important;
        }

        .lang_box {
            display: none;
        }

        .sign-in {
            background: #000 !important;
            color: #fff !important;
        }

        section .profile .active a {
            color: #000 !important;
            background: none;
        }

        .theme-clr a {
            color: #ff9800 !important;
        }

        .socail_icons li a:hover {
            color: #fff !important;
        }

        .socail_icons li a {
            margin-right: 4px;
            background: #3b5998 !important;
            border-radius: 100%;
            height: 26px;
            width: 26px;
            color: #fff !important;
            display: inline-block;
            line-height: 1.9;
            text-align: center;
        }

        .socail_icons .bg2 {
            background: #00c1f7 !important;
        }

        .socail_icons .bg3 {
            background: #8bb7f0 !important;
        }

        .socail_icons .bg4 {
            background: #d11718 !important;
        }

        .socail_icons .bg5 {
            background: #d71c4e !important;
        }

        #changepassform .col-sm-3 {
            margin-top: 0px;
            margin-bottom: 15px;
            padding: 0;
        }

        .col-lg-12 {
            padding: 0;
        }

        .change-pas {
            margin-top: 30px;
        }

        .hidden-sidebar-menu {
            display: none;
        }

        .padding30 .dashboard {
            margin-top: 35px;
        }

        .third-colum {
            float: right;
            padding-top: 17px;
        }

        .white .col-sm-4 {
            width: 100%;
        }

        .profile {
            width: 100%;
        }

        .dashboard .col-sm-8 {
            width: 100%;
        }

        .dashboard .col-sm-5 {
            width: 50%;
        }

        .sign_btn a {
            color: #fff !important;
        }
    }

    @media(max-width: 767px) {
        .container {
            width: auto;
        }

        .third-colum {
            float: right !important;
            width: 23% !important;
            padding-top: 7px;
            text-align: right;
            padding-left: 0px;
            margin-left: 0px;
            padding-right: 8px;
        }

        .third-colum a {
            padding: 12px 0 4px;
        }

        .second-colum {
            width: 66.6% !important;
            padding-right: 0;
            float: left;
            text-align: center;
        }

        .dashboard .col-sm-5 {
            width: 100%;
        }

        #header-left .toggle-btn.active {
            background: none;
            left: 87%;
        }

        .sorting_asc {
            width: 100% !important;
        }

        #update {
            width: 100%;
        }

        .change-pas {
            margin-top: 30px;
        }

        .hidden-sidebar-menu {
            display: none;
        }

        .white h2 {
            margin-top: 60px;
        }

        .sign-in {
            padding: 13px;

            width: 100%;
            font-size: 16px;
        }

        i {
            margin-right: 5px;
        }

        .top-bar {
            display: none;
        }

        .lang_box {
            padding: 10px;
        }

        #menu-toggle {
            display: block;
        }

        .navbar-logo {
            right: 0px !important;
        }

        #menu li a {
            position: relative;
            left: 0;
            border-bottom: 1px solid #333;
        }

        .navbar-logo {
            position: absolute !important;
            right: 0 !important;
            top: 0;
        }
    }
</style>

<a id="sidebar-open" href="#" style="text-align: center;"><i class="fa fa-bars" style="font-size: 17px;"></i></a>

<div class="row mobile-top-bar">
    <div class="col-sm-1 first-colum">
        <div id="container-fluid" class="wrapper">
            <div id="header-wrap">
                <header id="header" class="clearfix">
                    <div id="header-left"> <button class="toggle-btn toggleMenu"> <strong></strong> <em>menu</em>
                    </button>
                </div>
            </header>
        </div>

    </div>
    <div class="sidebar">
        <div class="mobile-logo">
            <a href="#"><img src="assets/img/logo/logo-white.png" alt=""></a>
        </div>
        <ul id="menu-main" class="menu">
            <?php if (isset($is_profile_page) && $is_profile_page) { ?>
                <!-- Profile sidebar -->
                <li <?php if ($current == 'profile.php') {
                    echo "class=''";
                } ?>><a href="profile.php"><i class="fa fa-cog"></i><?php echo getLange('dashboard'); ?></a>
            </li>
            <?php if ($is_booking_manual == 1) { ?>
                <li <?php if ($current == 'booking.php') {
                    echo "class='active'";
                } ?>>
                <a href="booking.php"><i class="fa fa-cog"></i>
                    <?php echo getLange('createbooking'); ?>
                </a>
            </li>
            <li <?php if ($current == 'booking_new.php') {echo "class='active'";} ?>>
                <a href="booking_new.php"><i class="fa fa-cog"></i>Internation Booking</a>
            </li>
                <!-- <li <?php if ($current == 'bulk_booking.php') {
                                echo "class='active'";
                            } ?>>
                    <a href="bulk_booking.php"><i class="fa fa-cog"></i>
                        <?php echo getLange('bulkbooking'); ?>
                    </a>
                </li> -->
            <?php } ?>
            <li <?php if ($current == 'order.php') {
                echo "class='active'";
            } ?>><a href="order.php"><i class="fa fa-cog"></i>
                <?php echo getLange('orderlist'); ?> </a></li>

                <li <?php if ($current == 'booking_sheet.php') {
                    echo "class='active'";
                } ?>><a href="booking_sheet.php"><i
                    class="fa fa-cog"></i><?php echo getLange('bookingsummary'); ?> </a></li>
                    <li <?php if ($current == 'generate_run_sheet.php') {
                        echo "class='active'";
                    } ?>><a href="generate_run_sheet.php"><i
                        class="fa fa-cog"></i>Generate Load Sheet</a></li>
                <!-- <li  <?php if ($current == 'payments.php') {
                                    echo "class='active'";
                                } ?> ><a href="payments.php"><i class="fa fa-cog"></i>Payments</a></li> -->
                                <li <?php if ($current == 'ledger_payments.php') {
                                    echo "class='active'";
                                } ?>><a href="ledger_payments.php"><i
                                    class="fa fa-cog"></i><?php echo getLange('mypayment'); ?> </a></li>
                                    <li <?php if ($current == 'track_deliveries.php') {
                                        echo "class='active'";
                                    } ?>><a href="track_deliveries.php" ><i
                                        class="fa fa-cog"></i><?php echo getLange('trackondelivery'); ?> </a></li>
                                        <li <?php if ($current == '/contact-us/') {
                                            echo "class='active'";
                                        } ?>><a href="https://www.icargos.com/contact-us/"><i
                                            class="fa fa-cog"></i><?php echo getLange('suggestioncpmplaint'); ?> </a></li>
                                            <li <?php if ($current == 'editprofile.php') {
                                                echo "class='active'";
                                            } ?>><a href="editprofile.php"><i class="fa fa-cog"></i><?php echo getLange('editaccount'); ?>
                                        </a></li>
                                        <li <?php if ($current == 'change-password.php') {
                                            echo "class='active'";
                                        } ?>><a href="change-password.php"><i
                                            class="fa fa-lock"></i><?php echo getLange('changepassword'); ?> </a></li>
                                            <?php if ($api_status == 1) { ?>
                                                <li <?php if ($current == 'api_setting.php') {
                                                    echo "class='active'";
                                                } ?>><a href="api_setting.php"><i
                                                    class="fa fa-lock"></i><?php echo getLange('apisetting'); ?> </a></li>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <li class="<?php echo $current == 'index.php' ? "active" : ""; ?>"><a href="index.php">Home </a>
                                                </li>
                                                <li class="<?php echo $current == 'about-us.php' ? "active" : ""; ?>"> <a href="about-us.php">about</a>
                                                </li>
                                                <li style="margin-left: -3px;" class="<?php echo $current == 'track_deliveries.php' ? "active" : ""; ?>"> <a
                                                    target="_blank" href="track_deliveries.php"> tracking </a> </li>

                                                    <li style="margin-left: -3px;" class="<?php echo $current == 'contact-us.php' ? "active" : ""; ?>"> <a
                                                        href="contact-us.php"> contact </a> </li>
                                                        <?php if (isset($_SESSION['customers'])) {
                                                            ?>
                                                            <li class="<?php echo $current == 'profile.php' ? "active" : ""; ?>"><a href="profile.php">Account</a>
                                                            </li>
                                                            <!--<li class="<?php echo $current == 'sendpackage.php' ? "active" : ""; ?>"><a href="sendpackage.php">Add a new Request </a></li>-->
                                                            <?php

                                                        } else { ?>
                                                            <li class="<?php echo $current == 'register.php' ? "active" : ""; ?>">
                                                                <a href="register.php">Register</a>
                                                            </li>
                <!--<li>
              <a href="contacts.php" >contact us</a>
          </li>-->
      <?php } ?>
  <?php } ?>
  <!--<li><span class="search fa fa-search theme-clr transition"> </span></li>-->
  <div class=" sign_btn">
    <?php if (isset($_SESSION['customers'])) {
        echo '<a style="color:#fff !important;" href="logout.php" class="sign-in fs-12 theme-clr-bg">Logout</a>';
    } else {
        ?>
        <a style="color:#fff !important;" href="login.php" class="sign-in fs-12 theme-clr-bg"> sign in </a>
        <?php
    }
    ?>
</div>
<div class="col-sm-6 call_now">
    <p class="contact-num"> <i class="fa fa-phone"></i> Call us now: <span class="theme-clr"> <a
        href="tel:00971 422 7878 2">00971 422 7878 2</a> </span> </p>
    </div>

    <div class="col-sm-2 socail_icons">
        <ul>
            <li><a class="bg1" href="https://www.facebook.com/snap.courier.9"><i
                class="fa fa-facebook"></i></a></li>
                <li><a class="bg2" href="https://twitter.com/SNAPCOURIER_AE"><i class="fa fa-twitter"></i></a>
                </li>
                <li><a class="bg3" href="https://www.linkedin.com/in/snap-courier-services-8b3333154/"><i
                    class="fa fa-linkedin"></i></a></li>
                    <li><a class="bg4" href="https://plus.google.com/discover"><i class="fa fa-google-plus"></i></a>
                    </li>
                    <li><a class="bg5" href="https://www.instagram.com/snapcourierservices.uae/"><i
                        class="fa fa-instagram"></i></a></li>
                    </ul>
                </div>

            </ul>
        </div>

        
    </div>
    <div class="col-sm-8 second-colum">
        <p><?php echo isset($page_title) ? $page_title : ''; ?></p>
    </div>
    <div class="col-sm-2 third-colum">
        <a href="<?php echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php'; ?>"><i
            class="fa fa-angle-left"></i> Back to</a>
        </div>
    </div>
    <div class="profile-sidebar hidden-sidebar-menu " id="<?php if ($_SESSION['language'] != 'english') {
        echo 'ltr-sidbar-customer';
    } ?>">
    <div class="close_icons">
        <i class="fa fa-close"></i>
    </div>
    <!-- SIDEBAR USERPIC -->
    <div class="profile-userpic">
        <!-- <img src="<?php echo $images; ?>" alt="profile image">  -->
    </div>
    <!-- END SIDEBAR USERPIC -->
    <!-- SIDEBAR USER TITLE -->
    <div class="profile-usertitle">
        <div class="profile-usertitle-name"><?php echo $bname; ?></div>
    </div>
    <!-- END SIDEBAR USER TITLE -->

    <!-- SIDEBAR MENU -->
    <?php
    // if($usertype==="Property owner"){
    include('role_helper.php');

    ?>
    <div class="profile-usermenu hidden-sidebar-menu">
        <ul class="nav">
            <li <?php if ($current == 'profile.php') {
                echo "class='active'";
            } ?>><a href="profile.php"><i class="lnr lnr-home"></i> <?php echo getLange('dashboard'); ?></a></li>
            <?php if ($is_booking_manual == 1) { ?>
                <?php if (checkRolePermission(1, 'add_only', '')) {  ?>
                    <li <?php if ($current == 'booking.php') {
                        echo "class='active'";
                    } ?>><a href="booking.php"><i
                        class="lnr lnr-calendar-full"></i><?php echo getLange('createbooking'); ?> </a></li>
                        <li <?php if ($current == 'booking_new.php') {
                            echo "class='active'";
                        } ?>><a href="booking_new.php"><i
                            class="lnr lnr-calendar-full"></i>Internation Booking</a></li>
                        <?php } ?>
                        <?php if (checkRolePermission(2, 'view_only', '')) {  ?>
                            <li id="bulk_book" <?php if ($current == 'upload_excel_file.php') {
                                echo "class='active'";
                            } ?>><a href="upload_excel_file.php"><i
                                class="lnr lnr-calendar-full"></i>Upload Bulk Sheet </a></li>
                                <li id="bulk_book" <?php if ($current == 'upload_excel_file_international.php') {
                                echo "class='active'";
                            } ?>><a href="upload_excel_file_international.php"><i
                                class="lnr lnr-calendar-full"></i>Upload Bulk Sheet International</a></li>  
                            <?php }  ?>
                            <?php if (checkRolePermission(2, 'view_only', '')) {  ?>
            <!-- <li id="bulk_book" <?php if ($current == 'bulk_booking.php') {
                                            echo "class='active'";
                                        } ?>><a href="bulk_booking.php"><i
                                            class="lnr lnr-calendar-full"></i><?php echo getLange('bulkbooking'); ?> </a></li> -->
                                        <?php }
                                    } ?>
                                    <?php if (checkRolePermission(1, 'view_only', '')) {  ?>
                                        <li <?php if ($current == 'order.php') {
                                            echo "class='active'";
                                        } ?>><a href="order.php"><i
                                            class="lnr lnr-text-align-right"></i><?php echo getLange('orderlist'); ?></a></li>
                                        <?php } ?>
                                        <?php if (checkRolePermission(3, 'view_only', '')) {  ?>
                                            <li <?php if ($current == 'booking_sheet.php') {
                                                echo "class='active'";
                                            } ?>><a href="booking_sheet.php"><i
                                                class="fa fa-sort"></i><?php echo getLange('bookingsummary'); ?> </a></li>
                                            <?php } ?>
                                            <?php if (checkRolePermission(4, 'view_only', '')) {  ?>
                                                <li <?php if ($current == 'generate_run_sheet.php') {
                                                    echo "class='active'";
                                                } ?>><a href="generate_run_sheet.php"><i
                                                    class="lnr lnr-file-empty"></i>Generate Load Sheet</a></li>
                                                    <li <?php if ($current == 'order_assignemnts.php') {
                                                        echo "class='active'";
                                                    } ?>><a href="order_assignemnts.php"><i class="fa fa-cog"></i>LOAD SHEET LOG</a></li>
            <!-- <li  <?php if ($current == 'payments.php') {
                                echo "class='active'";
                            } ?> ><a href="payments.php"><i class="fa fa-cog"></i>Payments</a></li> -->
                        <?php } ?>
                        <?php if (checkRolePermission(5, 'view_only', '')) {  ?>
                            <li <?php if ($current == 'order_report.php') {
                                echo "class='active'";
                            } ?>><a href="order_report.php"><i
                                class="lnr lnr-file-empty"></i><?php echo getLange('orderreport'); ?> </a></li>
                            <?php } ?>
                            <?php if (checkRolePermission(5, 'view_only', '')) {  ?>
                                <li <?php if ($current == 'comments_report.php') {
                                    echo "class='active'";
                                } ?>><a href="comments_report.php"><i
                                    class="lnr lnr-file-empty"></i>Comment Report</a></li>
                                <?php } ?>
                                <?php if (checkRolePermission(6, 'view_only', '')) {  ?>
                                    <li <?php if ($current == 'ledger_payments.php') {
                                        echo "class='active'";
                                    } ?>><a href="ledger_payments.php"><i
                                        class="lnr lnr-briefcase"></i><?php echo getLange('mypayment'); ?> </a></li>
                                        
                                    <?php } ?>
                             <?php if (checkRolePermission(6, 'view_only', '')) {  ?>
                                    <li <?php if ($current == 'chat_with_rider.php') {
                                        echo "class='active'";
                                    } ?>><a href="chat_with_rider.php"><i
                                        class="lnr lnr-briefcase"></i><?php echo getLange('Chat with Rider'); ?> </a></li>
                                        
                                    <?php } ?>

                                    <li class="chat_icon"><a href="chat_with_rider.php"><img src="assets/img/chaticon.png"> Chat with Rider </a></li>
                                    <?php if (checkRolePermission(7, 'view_only', '')) {  ?>
                                        <li <?php if ($current == 'track_deliveries.php') {
                                            echo "class='active'";
                                        } ?>><a href="track_deliveries.php" ><i
                                            class="lnr lnr-map-marker"></i><?php echo getLange('trackondelivery'); ?> </a></li>
                                        <?php } ?>
                                        <?php if (checkRolePermission(8, 'view_only', '')) {  ?>
                                            <li <?php if ($current == 'contact-us.php') {
                                                echo "class='active'";
                                            } ?>><a href="https://www.icargos.com/contact-us/"><i
                                                class="lnr lnr-envelope"></i><?php echo getLange('suggestioncomplaint'); ?> </a></li>
                                            <?php } ?>
                                            <?php if (getmulti_user() == '1') { ?>
                                                <?php if (checkRolePermission(9, 'view_only', '')) {  ?>
                                                    <li <?php if ($current == 'customer_user.php') {
                                                        echo "class='active'";
                                                    } ?>><a href="customer_user.php"><i class="fa fa-pencil"></i><?php echo getLange('user'); ?>
                                                </a></li>
                                            <?php } ?>
                                            <?php if (checkRolePermission(13, 'view_only', '')) {  ?>
                                                <li <?php if ($current == 'customer_permission.php') {
                                                    echo "class='active'";
                                                } ?>><a href="customer_permission.php"><i
                                                    class="fa fa-pencil"></i><?php echo getLange('userpermission'); ?></a></li>
                                                <?php }
                                            } ?>
                                            <?php if (checkRolePermission(10, 'view_only', '')) {  ?>
                                                <li <?php if ($current == 'editprofile.php') {
                                                    echo "class='active'";
                                                } ?>><a href="editprofile.php"><i class="fa fa-pencil"></i><?php echo getLange('editaccount'); ?>
                                            </a></li>
                                        <?php } ?>
                                        <li <?php if ($current == 'multiple_profile.php') {
                                            echo "class='active'";
                                        } ?>><a href="multiple_profile.php"><i class="fa fa-pencil"></i>Multiple Profile </a></li>
                                        <?php if (checkRolePermission(11, 'view_only', '')) {  ?>
                                            <li <?php if ($current == 'change-password.php') {
                                                echo "class='active'";
                                            } ?>><a href="change-password.php"><i
                                                class="lnr lnr-lock"></i><?php echo getLange('changepassword'); ?> </a></li>
                                            <?php } ?>
                                            <?php if ($api_status == 1) { ?>
                                                <?php if (checkRolePermission(12, 'view_only', '')) {  ?>
                                                    <li <?php if ($current == 'api_setting.php') {
                                                        echo "class='active'";
                                                    } ?>><a href="api_setting.php"><i class="lnr lnr-cog"></i>
                                                        <?php echo getLange('apisetting'); ?> </a></li>
                                                    <?php } ?>
                                                <?php } ?>
                                                <li><a style="" href="logout.php"><i class="lnr lnr-exit"></i><?php echo getLange('logout'); ?></a></li>
                                                <?php
            // }
                                                ?>


                                                <!-- END MENU -->
                                            </div>
                                            <div id="map-canvas" hidden>
                                            </div>
                                            
                                        </div>



