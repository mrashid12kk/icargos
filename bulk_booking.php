<?php
session_start();
include_once "includes/conn.php";
include "admin/includes/sms_helper.php";
if (isset($_POST['settle']) && isset($_SESSION['customers'])) {
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $weight = $_POST['weight'];
    $customer_id = $_SESSION['customers'];
    $pricing_query = mysqli_query($con, "SELECT * FROM customer_pricing WHERE city_from ='" . $origin . "' AND city_to ='" . $destination . "' AND customer_id=" . $customer_id . " ");
    $check_sta = mysqli_num_rows($pricing_query);
    if ($check_sta == 0) {
        $pricing_query = mysqli_query($con, "SELECT * FROM pricing WHERE city_from ='" . $origin . "' AND city_to ='" . $destination . "' ");
    }
    $record = mysqli_fetch_array($pricing_query);
    echo $record['price'] * ($weight - 1) + $record['first_kg_price'];
    exit();
}
$cities1 = mysqli_query($con, "SELECT * FROM cities WHERE 1 ");
$cities2 = mysqli_query($con, "SELECT * FROM cities WHERE 1 order by id desc ");
function encrypt($string)
{
    $key = "usmannnn";
    $result = '';
    for ($i = 0; $i < strlen($string); $i++) {
        $char = substr($string, $i, 1);
        $keychar = substr($key, ($i % strlen($key)) - 1, 1);
        $char = chr(ord($char) + ord($keychar));
        $result .= $char;
    }
    return base64_encode($result);
}
if (isset($_SESSION['customers'])) {
    require_once "includes/role_helper.php";
    if (!checkRolePermission(2, 'view_only', '')) {

        header("location:access_denied.php");
    }
    include "includes/header.php";
    $page_title = 'Dashboard';
    $is_profile_page = true;
    $customer_id = $_SESSION['customers'];
    $customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $customer_id . " ");
    $customer_data = mysqli_fetch_array($customer_query);
?>
<style>
a {
    text-decoration: none !important;
}

input::-webkit-input-placeholder,
textarea::-webkit-input-placeholder {
    color: #b8b8b8 !important;
}

input:-moz-placeholder,
textarea:-moz-placeholder {
    color: #b8b8b8 !important;
}

input::-moz-placeholder,
textarea::-moz-placeholder {
    color: #b8b8b8 !important;
}

input:-ms-input-placeholder,
textarea:-ms-input-placeholder {
    color: #b8b8b8 !important;
}

label {
    font-weight: bold;
}

section .dashboard .white {
    padding: 11px 7px 11px 12px !important;
}

.go_Dashboard {
    background: #f5f5f5;
    padding: 12px 11px;
    margin: 0;
}

table.jexcel>thead>tr>td {
    font-size: 13px;
}

.progressbar h3 {
    margin: 0;
    padding: 8px 0 0;
    font-weight: 600;
    font-size: 20px;
}

.complete_profile {
    text-align: right;
    padding: 0 5px;
}

.complete_profile a {
    background-color: #4cade0;
    border: #00a5b3;
    color: #fff;
    font-size: 13px;
    padding: 9px 18px;
    margin-left: 10px;
    border-radius: 3px;
    display: inline-block;
}

table.jexcel>thead>tr>td {
    font-size: 11px;
}

.bulk-bg {
    background: #f5f5f5;
    padding: 13px 7px 13px 0;
    border-top: 1px solid #cccccc85;
}

.go_to:hover {
    color: #fff !important;
}

.go_to:focus {
    color: #fff !important;
}

.sample_sheet {
    background-color: #449d44 !important;
    border-color: #398439 !important;
}

/*.jexcel_content table tr >td:first-child{
    display: none;
}*/
@media(max-width: 1250px) {
    .container {
        width: 100%;
    }

    .complete_profile a {
        padding: 9px 7px;
    }
}

@media(max-width: 1024px) {
    .container {
        width: 100%;
    }

    .complete_profile a {
        margin-left: 3px;
        font-size: 11px;
        padding: 9px 5px;
    }
}

@media(max-width: 767px) {
    .container {
        width: auto;
    }

    #spreadsheet .jexcel_content {
        min-height: .01%;
        overflow-x: auto;
    }

    .progressbar h3,
    .complete_profile {
        text-align: center;
    }

    .complete_profile a {
        margin-left: 3px;
        font-size: 11px;
        padding: 9px 15px;
    }

    .site-logo img {
        top: -2px !important;
        left: 10px !important;
    }
}

@media(max-width: 1250px) {
    .container {
        width: 100%;
    }
}

@media(max-width: 1024px) {
    .container {
        width: 100%;
    }

    #header_wrap .theme-menu>li:nth-child(5),
    #header_wrap .theme-menu>li:nth-child(6) {
        padding-top: 8px !important;
    }

    .navbar-nav .active:last-child a {
        padding: 5px 0 1px;
    }

    .site-logo img {
        top: 7px !important;
    }

    section .dashboard .white {
        padding: 20px 10px !important;
    }
}

@media(max-width: 767px) {
    .container {
        width: auto;
    }

    .menu_icon i {
        top: -42px;
    }

    #header_wrap .menu-bar {
        padding: 17px 0px 0 !important;
    }

    .site-logo img {
        top: -7px !important;
    }
}

.jdropdown-container {
    min-width: 101px;
    bottom: auto !important;
    z-index: 999999;
    background: #f5f5f5;
    position: absolute;
}

.jdropdown-content {
    line-height: 1.6;
    z-index: 999999;
}

.jdropdown-close {
    display: none !important;
}

.alert {
    padding: 6px !important;
    margin-bottom: 6px !important;
}


.filelabel {

    border: 2px dashed grey;
    border-radius: 5px;
    display: block;
    padding: 5px;
    transition: border 300ms ease;
    cursor: pointer;
    text-align: center;
    margin: 0;
}

.filelabel i {
    display: block;
    font-size: 30px;
    padding-bottom: 5px;
}

.filelabel i,
.filelabel .title {
    color: grey;
    transition: 200ms color;
}

.filelabel:hover {
    border: 2px solid #1665c4;
}

.filelabel:hover i,
.filelabel:hover .title {
    color: #1665c4;
}

#FileInput {
    display: none;
}

/*tabs*/
/*custom font*/
@import url(https://fonts.googleapis.com/css?family=Montserrat);

/*basic reset*/
* {
    margin: 0;
    padding: 0;
}

html {
    height: 100%;
    /*Image only BG fallback*/

    /*background = gradient + image pattern combo*/
    background:
        linear-gradient(rgba(196, 102, 0, 0.6), rgba(155, 89, 182, 0.6));
}

body {
    font-family: montserrat, arial, verdana;
}

/*form styles*/
#msform {
    width: 400px;
    margin: 50px auto;
    text-align: center;
    position: relative;
}

#msform fieldset {
    background: white;
    border: 0 none;
    border-radius: 3px;
    box-shadow: 0 0 15px 1px rgba(0, 0, 0, 0.4);
    padding: 20px 30px;
    box-sizing: border-box;
    width: 80%;
    margin: 0 10%;

    /*stacking fieldsets above each other*/
    position: relative;
}

/*Hide all except first fieldset*/
#msform fieldset:not(:first-of-type) {
    display: none;
}

/*inputs*/
#msform input,
#msform textarea {
    padding: 15px;
    border: 1px solid #ccc;
    border-radius: 3px;
    margin-bottom: 10px;
    width: 100%;
    box-sizing: border-box;
    font-family: montserrat;
    color: #2C3E50;
    font-size: 13px;
}

/*buttons*/
#msform .action-button {
    width: 100px;
    background: #27AE60;
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 1px;
    cursor: pointer;
    padding: 10px 5px;
    margin: 10px 5px;
}

#msform .action-button:hover,
#msform .action-button:focus {
    box-shadow: 0 0 0 2px white, 0 0 0 3px #27AE60;
}

/*headings*/
.fs-title {
    font-size: 15px;
    text-transform: uppercase;
    color: #2C3E50;
    margin-bottom: 10px;
}

.fs-subtitle {
    font-weight: normal;
    font-size: 13px;
    color: #666;
    margin-bottom: 20px;
}

/*progressbar*/
#progressbar {
    margin-bottom: 30px;
    overflow: hidden;
    /*CSS counters to number the steps*/
    counter-reset: step;
}

#progressbar li {
    list-style-type: none;
    color: white;
    text-transform: uppercase;
    font-size: 9px;
    width: 33.33%;
    float: left;
    position: relative;
}

#progressbar li:before {
    content: counter(step);
    counter-increment: step;
    width: 20px;
    line-height: 20px;
    display: block;
    font-size: 10px;
    color: #333;
    background: white;
    border-radius: 3px;
    margin: 0 auto 5px auto;
}

/*progressbar connectors*/
#progressbar li:after {
    content: '';
    width: 100%;
    height: 2px;
    background: white;
    position: absolute;
    left: -50%;
    top: 9px;
    z-index: -1;
    /*put it behind the numbers*/
}

#progressbar li:first-child:after {
    /*connector not needed before the first step*/
    content: none;
}

/*marking active/completed steps green*/
/*The number of the step and the connector before it = green*/
#progressbar li.active:before,
#progressbar li.active:after {
    background: #27AE60;
    color: white;
}

.m_zero {
    margin: 0;
    padding-left: 0;
}

.main_box_hide {
    display: none;
}
</style>
<style>
.form-control,
.input-group-addon,
.bootstrap-select .btn {
    background-color: #ffffff;
    border-color: #ccc;
    border-radius: 3px;
    box-shadow: none;
    color: #000;
    font-size: 14px;
    height: 34px;
    padding: 0 20px;
    font-weight: 300;
}

label {
    font-weight: normal;
    margin: 0;
    color: #000;
    margin-bottom: 7px;
    font-weight: bold;
}

.modal-header {
    padding: 6px 11px;
    border-bottom: 1px solid #e5e5e5;
    margin-top: 0;
}

.profile-page-title,
.col-lg-4 {
    padding: 0 15px;
}

.modal-title {
    text-align: center;
}

.register_page {
    max-width: 660px;
}

.form-group input,
input.emaill {
    background-color: #f8fbff7d !important;
}

.wizard {
    width: 538px;
    margin-left: 0 !important;
    margin: 0px auto 0px;
    padding: 21px 21px;
    background: #fff;
    border: 1px solid #cccccc78;
    text-align: center;
    box-shadow: unset;
    border-radius: 6px;
    max-width: unset;
}

.wizard .nav-tabs>li {
    width: 50%;
    margin: 0 auto;
    text-align: center;
}

.wizard .nav-tabs {
    margin: 0;

}

.connecting-line {

    width: 60%;

}

.bg {
    padding: 17px 0 0 !important;
}

.bulk_box {
    margin: 10px 0 13px;
}

section .dashboard .dashboard {
    padding: 0px 0 0 12px;
}

label {
    margin: 6px 0;
    font-weight: 500;
    font-size: 14px;
}

.term_label {
    color: #0a68bb;
}


@media (max-width: 1250px) {
    .container {
        width: 100%;
    }


}

@media (max-width: 1024px) {
    .container {
        width: 100%;
    }


}

@media (max-width: 767px) {
    .container {
        width: auto;
    }

    section .dashboard .dashboard {
        padding: 0px 0 0 0;
    }

    .register_title {
        margin-top: 0;
    }
}
</style>
<section class="bg padding30">
    <div class="container-fluid dashboard">

        <div class="row">
            <div class="col-lg-2 col-md-3 col-sm-4 profile" style="margin-left:0">
                <!--sidebar come here!-->
                <?php
                    include "includes/sidebar.php";
                    ?>
            </div>

            <div class="col-lg-10 col-md-9 col-sm-8 dashboard">
                <div class="row bulk_box">
                    <div class="col-sm-4 progressbar">
                        <h3><?php echo getLange('bulkbooking'); ?> </h3>
                    </div>
                    <div class="col-sm-8 complete_profile">
                        <a class="go_to sample_sheet" href="upload_excel_file.php">Upload File</a>
                        <?php if (isset($customer_data['is_order_manual']) and $customer_data['is_order_manual'] == 1) { ?>
                        <a class="go_to sample_sheet" href="<?php echo BASE_URL ?>sample/bulk_booking_manual.xlsx"
                            download><?php echo getLange('downloadsamplesheet'); ?></a>
                        <?php } else { ?>
                        <a class="go_to sample_sheet" href="sample/bulk_booking.xlsx"
                            download><?php echo getLange('downloadsamplesheet'); ?></a>
                        <?php } ?>
                        <a class="go_to"
                            href="<?php echo BASE_URL ?>profile.php"><?php echo getLange('gotodashboard'); ?></a>
                    </div>
                </div>
                <div class="col-lg-12 dashboard">
                    <div class="white shipper_box">
                        <div class="alert alert-info">
                            <strong><?php echo getLange('info'); ?></strong><?php echo getLange('downloadsamplesheetupdate'); ?>
                            .
                        </div>
                        <!-- <div class="alert alert-info">
          <strong><?php echo getLange('info'); ?></strong><?php echo getLange('coyrecordfromexcel'); ?> .
        </div> -->
                        <div class="alert alert-info">
                            <strong><?php echo getLange('info'); ?></strong><?php echo getLange('usecalidservice'); ?>.
                        </div>

                        <!-- <div id="spreadsheet"></div>

            </div>
        <form method="POST" action="<?php echo getconfig('print_template'); ?>">
            <input type="hidden" name="order_id" id="print_data" >
            <a href="#"  style="color: #fff !important;" class="btn btn-success bulk_save"><?php echo getLange('saveprint'); ?> </a>
             <input style="color: #fff !important;" type="submit" name="save_print" class="btn btn-success" value="Save & Print"> 
        </form> -->



                        <input type="hidden" class="print_template" value="<?php echo getconfig('print_template'); ?>">
                    </div>
                </div>
            </div>
        </div>


    </div>
    <style>
    section .dashboard .shipper_box {
        display: unset;
        padding: 0px !important;
    }

    .whitee {
        color: white !important;
    }

    .whitee:hover {
        color: white !important;
    }

    .jexcel_container,
    .jexcel_content,
    .jexcel {
        width: 100% !important;
    }
    </style>
</section>
</div>
<?php
} else {
    header("location:index.php");
}
?>
<?php include 'includes/footer.php'; ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    $('title').text($('title').text() + ' Bulk Booking')
}, false);

// select file




// input file 


$("#FileInput").on('change', function(e) {
    var labelVal = $(".title").text();
    var oldfileName = $(this).val();
    fileName = e.target.value.split('\\').pop();

    if (oldfileName == fileName) {
        return false;
    }
    var extension = fileName.split('.').pop();

    if ($.inArray(extension, ['jpg', 'jpeg', 'png']) >= 0) {
        $(".filelabel i").removeClass().addClass('fa fa-file-image-o');
        $(".filelabel i, .filelabel .title").css({
            'color': '#208440'
        });
        $(".filelabel").css({
            'border': ' 2px solid #208440'
        });
    } else if (extension == 'pdf') {
        $(".filelabel i").removeClass().addClass('fa fa-file-pdf-o');
        $(".filelabel i, .filelabel .title").css({
            'color': 'red'
        });
        $(".filelabel").css({
            'border': ' 2px solid red'
        });

    } else if (extension == 'doc' || extension == 'docx') {
        $(".filelabel i").removeClass().addClass('fa fa-file-word-o');
        $(".filelabel i, .filelabel .title").css({
            'color': '#2388df'
        });
        $(".filelabel").css({
            'border': ' 2px solid #2388df'
        });
    } else {
        $(".filelabel i").removeClass().addClass('fa fa-file-o');
        $(".filelabel i, .filelabel .title").css({
            'color': 'black'
        });
        $(".filelabel").css({
            'border': ' 2px solid black'
        });
    }

    if (fileName) {
        if (fileName.length > 10) {
            $(".filelabel .title").text(fileName.slice(0, 4) + '...' + extension);
        } else {
            $(".filelabel .title").text(fileName);
        }
    } else {
        $(".filelabel .title").text(labelVal);
    }
});

// tabs
$('.nav-tabs > li a[title]').tooltip();
$('a[data-toggle="tab"]').on('show.bs.tab', function(e) {

    var $target = $(e.target);

    if ($target.parent().hasClass('disabled')) {
        return false;
    }
});

$(".next-step").click(function(e) {

    var $active = $('.wizard .nav-tabs li.active');
    $active.next().removeClass('disabled');
    nextTab($active);

});
$(".prev-step").click(function(e) {

    var $active = $('.wizard .nav-tabs li.active');
    prevTab($active);

});

function nextTab(elem) {
    $(elem).next().find('a[data-toggle="tab"]').click();
}

function prevTab(elem) {
    $(elem).prev().find('a[data-toggle="tab"]').click();
}



$("#FileInput").change(function(e) {
    //submit the form here
    $(".main_box_hide").css({
        "display": "block"
    });
    var fileName = e.target.files[0].name;
    // alert('The file "' + fileName +  '" has been selected.');
    $(document).find('.change_file_name').val('');
    $(document).find('.change_file_name').val(fileName);
    $(document).find('.oldfile').val('');
    $(document).find('.oldfile').val(fileName);

});
// rename text
</script>