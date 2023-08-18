<?php
$current = basename($_SERVER['PHP_SELF']);
$logo_img = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='logo' "));
$email = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='email' "));
$contactno = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='contactno' "));
$webfavicon = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='webfavicon' "));
$webtitle = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='webtitle' "));

$query = mysqli_query($con, "SELECT * from users where type='admin' ");
$fetch = mysqli_fetch_array($query);
$phone = $fetch['phone'];
$address = $fetch['address'];
$sql_portal_lang = mysqli_query($con, "SELECT * FROM portal_language WHERE is_active = 1");
$active_page = $_SERVER['SCRIPT_URI'];
$header_query = mysqli_query($con, "SELECT * FROM header_setting WHERE name='header_link' AND is_active = 1");
$header_query_m = mysqli_query($con, "SELECT * FROM header_setting WHERE name='header_link' AND is_active = 1");
$language_query = mysqli_query($con, "SELECT * FROM header_setting WHERE name='language' AND is_active = 1");
$fetch_lang = mysqli_fetch_array($language_query);
$active_lang = isset($fetch_lang['is_active']);
$customer_query = mysqli_query($con, "SELECT * FROM header_setting WHERE name='customer_link' AND is_active = 1");
$logout_query = mysqli_query($con, "SELECT * FROM header_setting WHERE name='logout_link' AND is_active = 1");
$customer_query_m = mysqli_query($con, "SELECT * FROM header_setting WHERE name='customer_link' AND is_active = 1");
$logout_query_m = mysqli_query($con, "SELECT * FROM header_setting WHERE name='logout_link' AND is_active = 1");
$logo_link_q = mysqli_query($con, "SELECT link FROM header_setting WHERE name='logo_link' AND is_active = 1");
$logoLink = mysqli_fetch_assoc($logo_link_q);

?>
<!DOCTYPE html>
<html>

<head>
    <title><?php echo $webtitle['value'] ?> </title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="assets/<?php echo $webfavicon['value'] ?>">
    <!-- Bootstrap Css -->
    <link href="bootstrap/css/bootstrap.min.css" media="screen" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="admin/assets/css/datatables.min.css" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
    <!-- Bootstrap Select Css -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">

    

    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

    <link rel="stylesheet" type="text/css"
        href="assets/plugins/bootstrap-select-1.10.0/dist/css/bootstrap-select.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
        rel="stylesheet">


    <link rel="stylesheet" type="text/css" href="assets/css/select2.min.css">
    <!-- Fonts Css -->
    <link rel="stylesheet" type="text/css" href="assets/css/all.css">

    <link rel="stylesheet" type="text/css"
        href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="assets/plugins/font-elegant/elegant.css">
    <!-- OwlCarousel2 Slider Css -->
    <link rel="stylesheet" type="text/css" href="assets/plugins/owl.carousel.2/assets/owl.carousel.css">
    <link rel="stylesheet" href="css/style.css" />
    <link href="css/custom.css" media="screen" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://bossanova.uk/jexcel/v3/jexcel.css" type="text/css" />
    <!-- <link rel="stylesheet" href="https://bossanova.uk/jtools/v2/japp.css" type="text/css" /> -->

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">

    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <!-- Animate Css -->
    <link rel="stylesheet" type="text/css" href="assets/css/animate.css">
    <!-- Main Css -->
    <link rel="stylesheet" type="text/css" href="assets/css/theme.css">
    <link rel="stylesheet" type="text/css" href="assets/css/segoe-ui.css">

    <script src="admin/assets/js/jquery/jquery-1.9.1.min.js" type="text/javascript"></script>
    <!-- <script src="assets/plugins/bootstrap-3.3.6/js/bootstrap.min.js" type="text/javascript"></script>    -->
    <script type="text/javascript" src="admin/assets/js/bootstrap/bootstrap.min.js"></script>

    <script src="admin/assets/js/plugins/bootstrap-validator/bootstrapValidator.min.js"></script>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/css/intlTelInput.css" rel="stylesheet"
        media="screen">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/intlTelInput.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/utils.js"></script>
    <!-- Include Axios from a CDN (Content Delivery Network) -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>



    <script src="admin/assets/js/moment/moment.js"></script>
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
    <script src="js/custom.js" type="text/javascript"></script>
    </script>
</head>
<style>
#home {
    padding: 0 !important;
}

.wrapper:after {
    content: "";
    display: block;
    height: auto !important;
}
.cust_btn_c {
    background: #f7931e;
    color: #fff !important;
}
.theme-menu a.cust_btn_c:hover{
    color: #fff !important;
}
</style>

<body id="home">
    <div id="<?php if (isset($dynamic_id) && !empty($dynamic_id)) {
                    echo $dynamic_id;
                } ?>">
        <div id="preloader">
            <span class="">

                <img src="admin/<?php echo $logo_img['value'] ?>" alt="logo" style="width:204px;" />

            </span>
        </div>
        <!-- /.Preloader -->
        <!-- sidebar menu -->
        <!-- Main Wrapper -->
        <main class="wrapper" id="header_wrap">

            <!-- <div class="top_bar_fix">
                            <div class="container">
                                <div class="row">

                                     

                                    <div class="col-sm-6 left_data_side social_circle ">
                                    <ul>
                                        <li><a href="#" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                        <li><a href="#" target="_blank"><i class="fab fa-twitter"></i></a></li>
                                        <li><a href="#" target="_blank"><i class="fab fa-instagram"></i></a></li> 
                                    </ul>
                                </div>

                                    <div class="col-sm-6 left_data_side right_data_side text-right">
                                        <ul>
                                            <li>
                                                <a href="tel:+9230834567675">
                                                <i class="fas fa-phone-alt"></i>  +9230834567675
                                                </a>
                                            </li>

                                             

                                            <li>
                                                <a href="mailto:info@apnadakiya.com">
                                                <i class="fas fa-envelope"></i> info@apnadakiya.com
                                                </a>
                                            </li>
                                            
                                           
                                        </ul>
                                    </div>
                                    
                                </div>
                            </div>
                        </div> -->

            <header class="header-main mobile-hidden-menubar">
                <!-- Header Topbar -->

                <!-- /.Header Topbar -->
                <!-- Header Logo & Navigation -->
                <div id="sticky-wrapper" class="sticky-wrapper">
                    <nav class="menu-bar font2-title1">


                        

                        <div class="container header_menu_wrap ">

                            <div class="row">

                                <div class="col-md-3 col-sm-3 padd_left logo_box">
                                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                        data-target="#navbar" aria-controls="navbar">
                                        <span class="sr-only">Toggle navigation</span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                    </button>
                                    <a class="navbar_logo" href="http://www.icargos.com/">
                                        <span class="site_logo ">
                                            <!-- <img src="images/happy gif 220x108.png" alt="logo" />  -->
                                            <img src="admin/<?php echo $logo_img['value'] ?>" alt="logo" />
                                            <!-- <img class="animate" src="admin/<? php // echo $logo_img['value'] 
                                                                                    ?>" alt="logo" /> -->
                                        </span>
                                    </a>
                                </div>

                                <div class="col-sm-9" id="menu_bar">

                                    <div id="navbar" class="collapse navbar-collapse no-pad">
                                        <div class="close_icons">
                                            <i class="fa fa-close"></i>
                                        </div>
                                        <ul class="navbar-nav theme-menu">
                                             <li class="active"><a href="http://www.icargos.com/">Home</a></li>
                                            <li class=""><a href="https://www.icargos.com/pricing-plans/">Pricing</a> </li>
                                            <li class=""><a href="https://www.icargos.com/demo-request/">Demo Request</a> </li>
                                            <li class=""><a href="https://www.icargos.com/demo-request/">Support</a> </li>
                                            <li class=""><a href="https://billing.icargos.com/index.php?rp=/login">Account</a> </li>
                                             <li ><a  href="https://a.icargos.com/portal/" >Login </a> </li>
                                            <li ><a href="https://a.icargos.com/portal/register.php " >Register</a></li>
                                         </ul>

                                        <ul class="mobile-menu">
                                         
                                             <li class="active"><a href="http://www.icargos.com/">Home</a></li>
                                            <li class=""><a href="https://www.icargos.com/pricing-plans/">Pricing</a> </li>
                                            <li class=""><a href="https://www.icargos.com/demo-request/">Demo Request</a> </li>
                                            <li class=""><a href="https://www.icargos.com/demo-request/">Support</a> </li>
                                            <li class=""><a href="https://billing.icargos.com/index.php?rp=/login">Account</a> </li>
                                             <li ><a   href="https://a.icargos.com/portal/" >Login </a> </li>
                                            <li ><a href="https://a.icargos.com/portal/register.php "  >Register</a></li>

                                        </ul>
                                    </div>
                                </div>

                                <!-- <div class="col-md-10 main_box_main">
                            
                        </div> -->


                            </div>
                        </div>
                    </nav>
                </div>
                <!-- /.Header Logo & Navigation -->
            </header>