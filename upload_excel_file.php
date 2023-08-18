<?php
session_start();
include_once "includes/conn.php";
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
$services = mysqli_query($con, "SELECT * FROM services order by service_type asc ");
function getProductsbyID($id){
    global $con;
    $product = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM products where id = '".$id."'"));
    return $product['name'];
}
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
        .table_upload{
            width: 33%;
        }
        .file-upload{
            padding-right: 20px;
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

        /*.bulk-bg {
    background: #f5f5f5;
    padding: 13px 7px 13px 0;
    border-top: 1px solid #cccccc85;
}*/
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

        .progressbar {
            margin: 0 0 16px;
        }

        section .dashboard .dashboard {
            padding: 20px 0 0 32px;
        }

        .bg {
            padding: 15px 0 0 !important;
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

            .register_title {
                margin-top: 0;
            }
        }
    </style>
    
    <section class="bg padding30 upload_excel_file">
        <div class="container-fluid dashboard">
            <div class="col-lg-2 col-md-3 col-sm-4 profile" style="margin-left:0">
                <!--sidebar come here!-->
                <?php
                include "includes/sidebar.php";
                ?>
            </div>
            <div class="col-lg-10 col-md-9 col-sm-8 dashboard">
            <section>
        
    </section>
                <div class="row ">
                    <div class="col-sm-8 complete_profile" style="text-align: left !important;">
                        <?php if (isset($customer_data['is_order_manual']) and $customer_data['is_order_manual'] == 1) { ?>
                            <a class="go_to sample_sheet" href="<?php echo BASE_URL ?>sample/bulk_booking_manual.xlsx" download><?php echo getLange('downloadsamplesheet'); ?></a>
                        <?php } else { ?>
                            <a class="go_to sample_sheet" href="sample/bulk_booking.xlsx" download><?php echo getLange('downloadsamplesheet'); ?></a>
                        <?php } ?>

                    </div>
                    <br>
                    <br>
                    <br>
                    <div class="col-sm-8 progressbar">
                        <h3>ImPort Excel Sheet</h3>
                    </div>

                </div>

                <?php if (isset($_SESSION['bulk_message']) && !empty($_SESSION['bulk_message'])) {
                ?><div class="alert alert-success"><?php echo $_SESSION['bulk_message']; ?></div>
                <?php
                    unset($_SESSION['bulk_message']);
                } ?>
                <input type="hidden" id="customer_id" value="<?php echo $_SESSION['customers']; ?>">
                <div class="row">
                    <div class="col-lg-3 m_zero">
                        <label class="filelabel">
                            <i class="fa fa-paperclip">
                            </i>
                            <span class="title">
                                Import File
                            </span>
                            <input class="FileUpload1" id="FileInput" name="booking_attachment" type="file" />

                        </label>
                        <div id="msg"></div>


                        <div class="buttons">
                            <input type="hidden" class="oldfile form-control" value="">
                            <label>Rename File</label>
                            <input type="text" name="" class="change_file_name" value="">
                            <div class="msg"></div>
                            <button id="submit" class="submit btn btn-info hidden">Submit</button>
                            <div class="rename_msg"></div>
                            <button class="remanefile hidden btn btn-info">Rename File</button>
                        </div>
                    </div>
                    <img src="<?php echo BASE_URL; ?>images/loader_se.gif" style="width: 150px;display: none;" id="image1">
                    <div class="col-lg-9 main_box_hide">

                        <!-- multistep form -->
                        <section>
                            <div class="wizard hidden">

                                <div class="wizard-inner">
                                    <div class="connecting-line"></div>
                                    <ul class="nav nav-tabs" role="tablist">

                                        <li role="presentation" class="active">
                                            <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" title="Step 1">
                                                <span class="round-tab">1</span>
                                            </a>
                                            <b>processing </b>
                                        </li>

                                        <li role="presentation" class="disabled">
                                            <a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" title="Step 2">
                                                <span class="round-tab">2</span>
                                            </a>
                                            <b> submit </b>
                                        </li>





                                    </ul>
                                </div>

                                <form autocomplete="off" class="validateform" id="contactForm" action="" method="post" class="City:" role="form" enctype="multipart/form-data">
                                    <div class="tab-content">
                                        <div class="bulk_msg"></div>
                                        <div class="tab-pane active" role="tabpanel" id="step1">
                                            <img src="<?php echo BASE_URL ?>admin/img/excel.png" alt="">

                                            <p>Upload</p>
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                                    <span>
                                                        <p style="display: inline;" class="upload_processing">0 </p>%
                                                        Complete
                                                    </span>
                                                </div>
                                            </div>
                                            <button style="display: inline;" class="validation btn btn-info">Check
                                                Validation</button>
                                            <div>Validation</div>
                                            <div class="progress" id="progressbar">

                                                <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 0%" id="progressbar_validation">
                                                    <span>
                                                        <p class="validation_process" style="display: inline;">0 </p>%
                                                        Complete
                                                    </span>
                                                </div>
                                            </div>

                                            <ul class="list-inline pull-right">
                                                <li><button type="button" class="btn btn-primary next-step" id="submit_step_data1" disabled="true"><?php echo getLange('next'); ?></button></li>
                                            </ul>
                                        </div>
                                        <div class="tab-pane" role="tabpanel" id="step2">
                                            <img src="<?php echo BASE_URL; ?>images/loader_se.gif" style="width: 62px;display: none;" id="image">
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="100" aria-valuemax="100" style="width: 0%" id="progressbar_submit">
                                                    <span>
                                                        <p style="display: inline;" class="submit_process">0 </p>% Complete
                                                    </span>
                                                </div>
                                            </div>

                                            <ul class="list-inline pull-right">
                                                <li><button type="button" class="btn btn-default prev-step"><?php echo getLange('previous'); ?></button>
                                                </li>
                                                <li><button type="button" class="btn btn-primary" onclick="Loading.show()" id="submit_step_data"><?php echo getLange('submit'); ?></button>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="clearfix"></div>
                                    </div>
                                </form>
                            </div>
                    </div>

                    


                </div>
                <div class="row file-upload"> 
           <table class="table_upload">
                <thead>
                    <tr>
                        <td>Sr No.</td>
                        <td>Product Name</td>
                        <td>Service Code</td>
                    </tr>
                </thead>
                <?php 
                    if(getConfig('tariff_type') == '2'){
                ?>
                    <tbody>
                    <?php
                    $i= 0;
                    $customer_id = $_SESSION['customers'];
                    $c_tarif_sql = "SELECT * FROM customer_tariff_detail WHERE customer_id ='". $customer_id ."'";
                    $tarifCust_query = mysqli_query($con,$c_tarif_sql);
                    $customer_tariff_ids = '';
                    while($custRes=mysqli_fetch_assoc($tarifCust_query)){
                        $customer_tariff_ids .=$custRes['tariff_id'].',';
                    }
                    $customer_tariff_ids = rtrim($customer_tariff_ids,',');
                    $c_mapping_id_sql = "SELECT * FROM tariff WHERE id IN ($customer_tariff_ids)";
                    $mapping_query = mysqli_query($con,$c_mapping_id_sql);
                    $customer_service_ids = '';
                    while($mapRes=mysqli_fetch_assoc($mapping_query)){
                        $customer_service_ids .=$mapRes['service_type'].',';
                    }
                    $customer_service_ids = rtrim($customer_service_ids,',');
                    $product = mysqli_query($con,"SELECT * FROM products order by id ASC");
                    while($product_row = mysqli_fetch_array($product)){
                        $product_type_id = $product_row['id'];
                    $sql =  "SELECT * FROM tariff WHERE  product_id = " . $product_type_id . " AND  service_type IN ($customer_service_ids) GROUP BY service_type ORDER BY id DESC";
                    // echo $sql;
                    $result = mysqli_query($con, $sql);
                    while ($row = mysqli_fetch_array($result)) {
                        $i++;
                        $id = $row['service_type'];
                        $product_id = $row['product_id'];
                        $services_sql =  "SELECT * FROM services WHERE id = " . $id . " ORDER BY id DESC";
                        $services_result = mysqli_query($con, $services_sql);
                        $single = mysqli_fetch_assoc($services_result);
                        // var_dump($single);
                        ?>
                        
                       <tr> 
                        <td> <?php echo $i;?> </td>
                        <td> <?php echo getProductsbyID($product_id); ?> </td>
                        <td> <?php echo $single['service_code']; ?> </td>
                    </tr>
                        <?php
                    }
                    }
                    ?>
            
                </tbody>
                <?php  
                  }else{
                ?>
                  <tbody>
                    <?php 
                    $srno=1; 
                    $services = mysqli_query($con, "SELECT * FROM services order by service_type asc ");
                    while ($res = mysqli_fetch_assoc($services)) {  
                        $product = mysqli_fetch_array(mysqli_query($con, "SELECT  * FROM products where id = '".$res['product_id']."'"));
                        // var_dump($product);
                        ?>
                        <tr>
                            <td><?php echo $srno++; ?></td>
                            <td><?php echo $product['name']; ?></td>
                            <td><?php echo $res['service_code']; ?></td>
                            
                        </tr>
                    <?php } ?>

                </tbody>

                <?php
            }

                ?>
            </table>

            <div style="margin-top: 20px;">
                <div class="white shipper_box" >
        <div class="alert alert-info">
          <strong>Info!</strong> Download sample sheet &amp; update according to your bookings  .
        </div>
        <div class="alert alert-info">
          <strong>Info!</strong>Copy records from excel sheet &amp; paste here .
        </div>
        <div class="alert alert-info">
          <strong>Info!</strong>Use valid Service type,Origin &amp; Destination.
        </div>
        
        
            </div>
            </div>
        </div>
    </section>

    
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
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $("#submit").click(function() {
            var remanefile = $('.change_file_name').val();
            var oldfile = $('.oldfile').val();
            var fd = new FormData();
            fd.append('file', $('#FileInput')[0].files[0]);
            fd.append('remanefile', remanefile);
            fd.append('oldfile', oldfile);
            $.ajax({
                url: 'ajax_bulk_import.php',
                dataType: 'Json',
                beforeSend: function() {
                    $('#image1').show();
                },
                complete: function() {
                    $('#image1').hide();
                },
                cache: false,
                contentType: false,
                processData: false,
                data: fd,
                type: 'post',
                success: function(output) {
                    if (output.percentage == 0) {
                        $('#msg').html("This File is always available Choose other file");
                        $('.FileUpload1').val("");
                        $('.wizard').addClass('hidden');
                    } else {
                        $('.upload_processing').html(output.percentage);
                        $('.change_file_name').val(output.filename);
                        $('.upload_msg').html('succcess');
                        $('#msg').html('');
                        $('.wizard').removeClass('hidden');
                        $('#submit').addClass('hidden');
                        $('.FileUpload1').prop('disabled', true);
                        $('.change_file_name').prop('disabled', true);
                        $('.remanefile').addClass('hidden');
                    }
                }
            })
        });
        $('body').on('click', '.remanefile', function(e) {
            e.preventDefault();
            var remanefile = $('.change_file_name').val();
            var oldfile = $('.oldfile').val();
            if (remanefile != "") {
                $.ajax({
                    type: 'POST',
                    data: {
                        remane: 1,
                        remanefile: remanefile,
                        oldfile: oldfile
                    },
                    url: 'ajax_bulk_import.php',
                    success: function(fetch) {
                        if (fetch == 19) {
                            var fatch_msg =
                                'This File Is Alredy Exist Please Chose Another File Name';
                            $('.rename_msg').html(fatch_msg);
                            $('.wizard').addClass('hidden');
                        } else {
                            $('.rename_msg').html(fetch);
                            $('.remanefile').addClass('hidden');
                            $('.wizard').removeClass('hidden');
                            $('.FileUpload1').prop('disabled', true);
                            $('.change_file_name').prop('disabled', true);
                        }
                    }
                });
            }
        });
        //          $('body').on('click', '#delete', function (e) {
        // e.preventDefault();
        //  var remanefile=$('.change_file_name').val();
        // if(remanefile!=""){
        //     $.ajax({
        //         type:'POST',
        //         data:{delete:1,remanefile:remanefile},
        //         url:'ajax_bulk_import.php',
        //         success:function(fetch){
        //                  $('.rename_msg').html(fetch);
        //             }
        //         });
        //     }
        // });
        $('body').on('change', '.change_file_name', function(e) {
            e.preventDefault();
            var validExtensions = ["xlsx", "xlsm"]
            var file = $(this).val().split('.').pop();
            if (validExtensions.indexOf(file) == -1) {
                var msg = ("Only formats are allowed : " + validExtensions.join(', '));
                $('.msg').html('');
                $('.msg').html(msg);
                $('#submit').addClass('hidden');
            } else {
                $('.msg').html('');
                $('#submit').removeClass('hidden');
            }
        });
        $(".FileUpload1").change(function() {
            var validExtensions = ["xlsx", "xlsm"]
            var file = $(this).val().split('.').pop();
            if (validExtensions.indexOf(file) == -1) {
                var msg = ("Only formats are allowed : " + validExtensions.join(', '));
                $('#msg').html('');
                $('#msg').html(msg);
                $(this).val("");
            } else {
                $('#msg').html('');
                $('#submit').removeClass('hidden');
            }
        });

        $('body').on('click', '.validation', function(e) {
            e.preventDefault();
            var change_file_name = $('.change_file_name').val();
            var customer_id = $('#customer_id').val();
            $.ajax({
                url: 'ajax_bulk_import.php',
                // beforeSend: function(){
                //         $('#image1').show();
                //         $('#process').css('display','block');
                //         },
                // complete: function(){
                //     $('#image1').hide();
                // },
                type: 'POST',
                data: {
                    bulk_booking: 1,
                    change_file_name: change_file_name,
                    customer_id: customer_id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.error) {
                        $('.bulk_msg').html(response.alert_msg);
                        $('.bulk_save').removeAttr('disabled');
                        $('.bulk_save').removeClass('disabled');
                        $('#submit_step_data1').prop('disabled', true);
                        $('#submit').addClass('hidden');
                        $('.FileUpload1').prop('disabled', false);
                        $('.change_file_name').prop('disabled', false);
                        var remanefile = $('.change_file_name').val();
                        $.ajax({
                            type: 'POST',
                            data: {
                                delete: 1,
                                remanefile: remanefile
                            },
                            url: 'ajax_bulk_import.php',
                            success: function(fetch) {}
                        });
                        return;
                    } else {
                        var percentage = 0;
                        var timer = setInterval(function() {
                            percentage = percentage + 20;
                            progress_bar_process(percentage, timer);
                        }, 1000);
                        // $('.validation_process').html(response);
                        // $('#submit_step_data1').prop('disabled', false);
                    }
                }
            });
        });

        function progress_bar_process(percentage, timer) {
            if (percentage < 101) {
                $('#progressbar_validation').css('width', percentage + '%');
                $('.validation_process').html(percentage);
            }
            if (percentage > 100) {
                clearInterval(timer);
                $('.valiation_success').html('Success');
                $('#submit_step_data1').prop('disabled', false);
            }
        }
        $('body').on('click', '#submit_step_data', function(e) {
            e.preventDefault();
            var change_file_name = $('.change_file_name').val();
            var customer_id = $('#customer_id').val();
            $.ajax({
                url: 'ajax_bulk_import.php',
                beforeSend: function() {
                    $('#image').show();
                },
                complete: function() {
                    $('#image').hide();
                },
                type: 'POST',
                data: {
                    save_booking: 1,
                    change_file_name: change_file_name,
                    customer_id: customer_id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.error) {
                        $('.bulk_msg').html(response.alert_msg);
                        return;
                    } else {
                        var percentage = 0;
                        var timer = setInterval(function() {
                            percentage = percentage + 10;
                            progress_bar_process_submit(percentage, timer);
                        }, 1000);
                        // $('.submit_process').html(response.process)
                    }
                }
            });
        });

        function progress_bar_process_submit(percentage, timer) {
            if (percentage < 101) {
                $('#progressbar_submit').css('width', percentage + '%');
                $('.submit_process').html(percentage);
            }
            if (percentage > 100) {
                clearInterval(timer);
                $('.valiation_success').html('Success');
                $('#submit_step_data1').prop('disabled', false);
                var remanefile = $('.change_file_name').val();
                $.ajax({
                    type: 'POST',
                    data: {
                        delete: 1,
                        remanefile: remanefile
                    },
                    url: 'ajax_bulk_import.php',
                    success: function(fetch) {
                        location.reload();
                    }
                });
            }
        }
    });
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
        $(document).find('.change_file_name').val('');
        $(document).find('.change_file_name').val(fileName);
        $(document).find('.oldfile').val('');
        $(document).find('.oldfile').val(fileName);
        $('.bulk_msg').html('');
        $('#msg').html('');
        $('.upload_msg').html('');
        $('.rename_msg').html('');
        $('.msg').html('');

    });
    // rename text
</script>