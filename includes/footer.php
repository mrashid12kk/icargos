<style>
.fix_footer_but:focus {
    color: #ffffff !important;
}

.footer-header-wrapper .form-control:focus {
    background: #fff;
    box-shadow: none;
    outline: 0 none;
}
</style>
<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
$current = basename($_SERVER['PHP_SELF']);
$query = mysqli_query($con, "select * from users where type='admin' ");
$fetch = mysqli_fetch_array($query);
$phone = $fetch['phone'];
$address = $fetch['address'];
$customer_id = $_SESSION['customers'];
$customer_data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM customers WHERE id=" . $customer_id . " "));
$customer_origin_zone_q = mysqli_query($con, " SELECT GROUP_CONCAT(DISTINCT zone_id SEPARATOR ',') as zone_ids FROM customer_pricing WHERE customer_id='" . $customer_id . "'  ");

if (mysqli_num_rows($customer_origin_zone_q) > 0) {
    $origin_zone_res = mysqli_fetch_array($customer_origin_zone_q);
    $zone_ids = $origin_zone_res['zone_ids'];
    $origin_q = mysqli_query($con, " SELECT DISTINCT origin FROM zone_cities WHERE zone IN(" . $zone_ids . ") ");
    $destination_q = mysqli_query($con, " SELECT DISTINCT destination FROM zone_cities WHERE zone IN(" . $zone_ids . ") ");
    //service types queries
    $service_type_q = mysqli_query($con, " SELECT GROUP_CONCAT(DISTINCT service_type SEPARATOR ',') as service_types FROM zone WHERE id IN (" . $zone_ids . ") ");
    if (mysqli_num_rows($service_type_q) > 0) {
        $service_type_id_res = mysqli_fetch_array($service_type_q);
        $service_types = $service_type_id_res['service_types'];
        $get_service_types = mysqli_query($con, " SELECT DISTINCT id,service_type FROM services WHERE id IN(" . $service_types . ") ");
    }
}

$from_cities_data = mysqli_query($con, "SELECT * from cities ");
$from_city_array = array();
while ($row = mysqli_fetch_array($from_cities_data)) {
    $from_city_array[] = strtolower($row['city_name']);
}
$to_cities_data = mysqli_query($con, "SELECT * from cities ");
$to_city_array = array();
while ($row = mysqli_fetch_array($to_cities_data)) {
    $to_city_array[] = strtolower($row['city_name']);
}
$weight_array = array();
$from_bulk_city = json_encode($from_city_array);
$to_bulk_city = json_encode($to_city_array);
$bulk_city = array_merge($from_city_array, $to_city_array);
for ($i = 0.5; $i <= 30; $i = $i + 0.5) {
    $weight_array[] = $i;
}
$weight_array = json_encode($weight_array);
$conatactno2 = mysqli_query($con, "SELECT value FROM config WHERE `name`='contactno'");
$conatactno = mysqli_fetch_array($conatactno2);

$footer = mysqli_query($con, "SELECT value FROM config WHERE `name`='footer'");
$invoicefooter = mysqli_fetch_array($footer);

$email1 = mysqli_query($con, "SELECT value FROM config WHERE `name`='email'");
$email = mysqli_fetch_array($email1);

$address1 = mysqli_query($con, "SELECT value FROM config WHERE `name`='address'");
$address = mysqli_fetch_array($address1);

$service_type_arr = array();
while ($row = mysqli_fetch_array($get_service_types)) {
    $service_type_arr[] = strtolower($row['service_type']);
}
$service_types = json_encode($service_type_arr);


// Dynamic links queries
$social_link_q = mysqli_query($con, "SELECT * FROM footer_setting WHERE name='social_link' AND is_active = 1");
$usefull_link_q = mysqli_query($con, "SELECT * FROM footer_setting WHERE name='usefull_link' AND is_active = 1");
$about_link_q = mysqli_query($con, "SELECT * FROM footer_setting WHERE name='about_link' AND is_active = 1");

?>
<script type="text/javascript">
var active_cities = JSON.parse('<?= json_encode($bulk_city); ?>');
</script>
<!-- kamran start-->

<div id="<?php if (isset($dynamic_id) && !empty($dynamic_id)) {
                echo $dynamic_id;
            } ?>">

    <div class="footer-header-wrapper">
    
        <div class="container">

            <div class="row" id="new_footer">

                <div class="col-lg-5 col-md-5  footer_wig footer_wig_1">  

                     <a href="https://apnadakiya.com/"><img src="admin/<?php echo getConfig("footer_logo");  ?>" alt="logo"></a>
                     <p>iCargos is multi functional cargo management system software which assists you with complete end-to-end control over revenue accounting processes. iCargos can revolutionize your business. Our cargo management system solution is specially developed for freight companies.</p>
                    
                 <ul class="social-icons">
                    <li><a style="background: #3b5998;" href="https://web.facebook.com/icargos"><i class="fa fa-facebook-f"></i></a></li>
                    <li><a style="background: #25d366;" href="https://web.facebook.com/icargos"><i class="fab fa-whatsapp"></i></a></li>
                    <li><a style="background: #00aff0;" href="skype:Muhammad%20Idrees%20|%20Web%20&%20Software%20Technologies%20|%20IT%20VISION%20(Pvt.)%20Ltd."><i class="fab fa-skype"></i></a></li>
                    <li><a style="background: #cd201f;" href="https://www.youtube.com/user/friends4it"><i class="fab fa-youtube"></i></a></li>
                   
                </ul>

                </div>

                <div class="col-lg-2 col-md-2 footer_wig">  

                    <h3>About</h3>
                   
                 <ul>
                    <li><a href="https://www.icargos.com/"><i class="fas fa-chevron-right"></i> Home</a></li>
                    <li><a href="https://www.icargos.com/features/"><i class="fas fa-chevron-right"></i> Features</a></li>
                    
                    <li><a href="https://www.icargos.com/membership-account/membership-levels/"><i class="fas fa-chevron-right"></i> Prices</a></li>
                  
                    <li><a href="https://www.icargos.com/demo-request/"><i class="fas fa-chevron-right"></i> Demo Request</a></li>
                    <li><a href="https://www.icargos.com/contact-us/"><i class="fas fa-chevron-right"></i> Contact Us</a></li>
                    
                    
                </ul>

                </div>

                 

                <div class="col-lg-5 col-md-5 footer_wig">  

                    <h3>Company Info</h3>
                   
                 <ul>
                    <li><a href="www.icargos.com"><i class="fas fa-globe"></i>  www.icargos.com</a></li>

                     
                    <li><a href="mailto:info@icargos.com"><i class="fas fa-envelope"> </i>   info@icargos.com</a></li>

                    <li><a href="tel:+92 332 4510131"><i class="fas fa-phone"> </i>   Call / WhatsApp: +92 332 4510131</a></li>
                    <li><a href="tel:+92 300 4510131"><i class="fas fa-phone"> </i>   Call / WhatsApp: +92 300 4510131</a></li>
                    <li><a href="tel:+44 7441 429700"><i class="fas fa-phone"> </i>   Call / WhatsApp: +44 7441 429700</a></li>

                    <li><a ><b>IT Vision Pvt. Ltd.</b>  | www.itvision.com.pk</a></li>
                    <li><a ><b>Global IT Vision Pvt. Ltd.</b> | www.globalitvision.com</a></li>
                    <li><a ><b>Rainbow Graphics Design Ltd.</b> |  www.rainbowgraphicsuk.com</a></li>
                     
                     
                </ul>

                </div>
 
            </div>

            

        </div>


 
    </div>
    <div class="footer_bottom">     
           

           <p>iCargos © Copyright 2022. All Rights Reserved. - Powered By <a class="hide_company" href="https://itvision.com.pk" target="_blank">IT Vision (Pvt.) LTD.</a></p>
         </div>
</div>
</main>
<style type="text/css">
    .hide_company,.hide_company:hover{
      color: #101314;  
    }
    .footer_bottom p a::selection {
      color:#101314;
    }
    
</style>
<!-- / Main Wrapper -->
<!-- Top -->
<div class="to-top theme-clr-bg transition"> <i class="fa fa-angle-up"></i> </div>
<!-- Popup: Login -->
<div class="modal fade login-popup" id="login-popup" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md">
        <a class="close close-btn" data-dismiss="modal" aria-label="Close"> x </a>
        <div class="modal-content">
            <div class="login-wrap text-center">
                <h2 class="title-3"> sign in </h2>
                <p> Sign in to <strong> Fast X Courier Courier & Logistics </strong> for getting all details </p>
                <?php
                if (isset($_POST['login'])) {
                    $email = mysqli_real_escape_string($con, $_POST['email']);
                    $password = mysqli_real_escape_string($con, $_POST['password']);
                    $query = mysqli_query($con, "select * from customers where email='$email' OR client_code = '$email' OR mobile_no = '$email'");
                    $count = mysqli_affected_rows($con);
                    if ($count > 0) {
                        $fetch = mysqli_fetch_array($query);
                        $hash = $fetch['password'];
                        if (Password_verify($password, $hash)) {
                            $_SESSION['customers'] = $fetch['id'];
                            $_SESSION['address'] = $fetch['address'];
                            // header("location:profile.php?status=approved");
                            if (isset($_GET['redirect'])) {
                                $query = mysqli_query($con, "INSERT INTO `orders`(`plocation`, `daddress`, `customer_id`, `weight_id`, `package_type`, `price`, `pickup_date`, `cash_by`, `payment_method`, `distance`, `rname`, `rphone`, `remail`) VALUES ('" . $_SESSION['step1']['plocation'] . "','" . $_SESSION['step1']['daddress'] . "','" . $_SESSION['customers'] . "','" . $_SESSION['step1']['weight'] . "','" . $_SESSION['step1']['package_type'] . "','" . $_SESSION['step1']['price'] . "','" . $_SESSION['step1']['pickup_date'] . "','" . $_SESSION['step1']['cash_by'] . "','" . $_SESSION['step1']['payment_type'] . "','" . $_SESSION['step1']['distance'] . "','" . $_SESSION['step2']['rname'] . "','" . $_SESSION['step2']['rphone'] . "','" . $_SESSION['step2']['remail'] . "')") or die(mysqli_error($con));
                                $insert_id = mysqli_insert_id($con);
                                unset($_SESSION['step1']);
                                unset($_SESSION['step2']);
                                $iddd = encrypt($insert_id . "-usUSMAN767###");
                                $src = "invoicehtml.php?id=$iddd";
                                echo "<script>window.location.href='$src'</script>";
                            } else
                                $urll = "profile.php?status=approved";
                            echo "<script>window.location.href='$urll';</script>";
                        } else {
                            echo '<div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" >×</button>
    <h4><i class="icon fa fa-check"></i> Alert!</h4>
    Invalid Password.
  </div>';

                            // echo "<script>alert('Invalid Password');</script>";
                        }
                    } else {
                        echo '<div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" >×</button>
    <h4><i class="icon fa fa-check"></i> Alert!</h4>
    No such email or email availble please signup first then login
  </div>';
                        // echo "<script>alert('No such email or email availble please signup first then login');</script>";
                    }
                }
                ?>
                <div class="login-form clrbg-before">
                    <form action="" method="post" class="login">
                        <div class="form-group"><input type="text" name="email" placeholder="Email address"
                                class="form-control"></div>
                        <div class="form-group"><input type="password" name="password" placeholder="Password"
                                class="form-control"></div>
                        <div class="form-group">
                            <button class="btn-1 " type="submit"> Sign in now </button>
                        </div>
                    </form>
                    <!--<a href="index.php#" class="gray-clr"> Forgot Passoword? </a>    -->
                </div>
            </div>
            <div class="create-accnt">
                <!-- <a href="index.php#" class="white-clr"> Don’t have an account? </a>  -->
                <h2 class="title-2"> <a href="register.php#" class="green-clr under-line">Create a free account</a>
                </h2>
            </div>
        </div>
    </div>
</div>
</div>

<script src="admin/assets/js/datatables.min.js"></script>
<script src="assets/js/select2.min.js"></script>
<script type="text/javascript">
$('.js-example-basic-single').select2();
</script>
<script type="text/javascript">
if ($("[data-toggle=popover]").length > 0) {
    $("[data-toggle=popover]").popover();
}

$('.dataTable').DataTable({
    ordering: false,
    // pageLength: 5,
    responsive: true,
    dom: "<'row'<'col-sm-4'l><'col-sm-4'f><'col-sm-4 text-right'B>>t<'bottom'p><'clear'>",
    // dom: '<"html5buttons"B>lTfgitp',
    buttons: [{
            extend: 'copy'
        },
        {
            extend: 'csv'
        },
        {
            extend: 'excel',
            title: 'ExampleFile'
        },
        {
            extend: 'pdf',
            title: 'ExampleFile'
        },
        {
            extend: 'print',
            customize: function(win) {
                $(win.document.body).addClass('white-bg');
                $(win.document.body).css('font-size', '10px');
                $(win.document.body).find('table')
                    .addClass('compact')
                    .css('font-size', 'inherit');
            }
        }
    ]
})
</script>
<script src="assets/js/jexcel.js"></script>
<!-- <script src="https://bossanova.uk/jtools/v2/japp.js"></script>  -->
<!-- <script src="https://bossanova.uk/jsuites/v2/jsuites.js"></script>  -->
<script src="assets/js/jsuites.js"></script>
<script>
var data = [
    ["", "", "", "", "", "", "", "", "", "", ""]
];
var selectedRow = -1;
var changed = function(instance, cell, x, y, value) {
    selectedRow = y;
    var row = myTable.getData()[0];
    // console.log(row);
}
var enterKeyAction = function() {
    var row = myTable.getRowData([selectedRow]);
    var validate_records = row.slice(0, -1);
    var hasID = (row[12]) ? true : false;
    // Validate row data here
    var hasEmpty = false;
    $.each(validate_records, function(index, val) {
        if (val == "") {
            hasEmpty = true;
            return false;
        }
    });
    if (hasEmpty) {
        // show error message
        return false;
    }
    insertedRow(row, function() {
        if (!hasID)
            myTable.insertRow();
    })
}
var print_ids = [];
var insertedRow = function(row, callback) {
    var currentRow = selectedRow;
    $.ajax({
        url: 'pages/orders/bulk_booking.php',
        type: 'POST',
        data: {
            bulk_booking: 1,
            row: row
        },
        success: function(data) {
            if (data != '') {
                print_ids.push(data);
                var order_data = JSON.stringify(print_ids);
                $('#print_data').val(order_data);
                myTable.setValue('M' + (parseInt(currentRow) + 1), data);
            }
            callback();
        }
    });
}
// alert($('body').find('#spreadsheet').length);
if ($('body').find('#spreadsheet').length > 0) {
    myTable = jexcel(document.getElementById('spreadsheet'), {
        data: data,
        columns: [{
                type: 'autocomplete',
                title: 'Product',
                source: ["1"]
            }, {
                type: 'autocomplete',
                title: '<?php echo getLange('servicetype') ?>',
                source: <?php echo $service_types; ?>
            },
            <?php if (isset($customer_data['is_order_manual']) and $customer_data['is_order_manual'] == 1) { ?> {
                type: 'autocomplete',
                title: '<?php echo getLange('orderno') ?>'
            },
            <?php } ?> {
                type: 'text',
                title: '<?php echo getLange('pickupcity') ?>',
                source: <?php echo $from_bulk_city; ?>
            },
            {
                type: 'text',
                title: '<?php echo getLange('deliverycity') ?>',
                source: <?php echo $to_bulk_city; ?>
            },
            {
                type: 'text',
                title: '<?php echo getLange('areas') ?>',
                source: ["1"]
            },
            {
                type: 'text',
                title: '<?php echo getLange('sender') . ' ' . getLange('name') ?>',
                source: ["1"]
            },
            {
                type: 'numeric',
                title: '<?php echo getLange('sender') . ' ' . getLange('phone') ?>',
                mask: '#'
            },

            {
                type: 'text',
                title: '<?php echo getLange('sender') . ' ' . getLange('address') ?>'
            },
            {
                type: 'text',
                title: '<?php echo getLange('receiver') . ' ' . getLange('name') ?>'
            },

            {
                type: 'numeric',
                title: '<?php echo getLange('receiver') . ' ' . getLange('phone') ?>',
                mask: '#'
            },
            {
                type: 'text',
                title: '<?php echo getLange('receiver') . ' ' . getLange('address') ?>'
            },
            {
                type: 'text',
                title: '<?php echo getLange('refernceno'); ?>'
            },
            {
                type: 'text',
                title: '<?php echo getLange('orderid'); ?>'
            },
            {
                type: 'numeric',
                title: '<?php echo getLange('noofpiece'); ?>',
                mask: '#'
            },
            {
                type: 'autocomplete',
                title: '<?php echo getLange('weightkg'); ?>',
                source: <?php echo $weight_array; ?>
            },
            {
                type: 'numeric',
                title: '<?php echo getLange('codamount'); ?>',
                mask: '#.#'
            },
            {
                type: 'text',
                title: '<?php echo getLange('productdescription'); ?>'
            },

        ],
        updateTable: function(instance, cell, col, row, val, label, cellName) {
            if (col == 0 || col == 1 || col == 2 || col == 3) {
                var v = val;
                cell.innerHTML = val.toLowerCase();
            }
        },
        onchange: changed,
        // onenterkey: enterKeyAction
    });

}

function indexToChar(i) {
    return String.fromCharCode(i + 97); //97 in ASCII is 'a', so i=0 returns 'a',
    // i=1 returns 'b', etc
}

$('body').on('click', '.bulk_save', function(e) {
    e.preventDefault();
    var body = $('body');
    if (body.find(this).hasClass('disabled')) {
        return false;
    }
    var validated = true;
    var singlerow = '';
    var active_cell = '';
    body.find('.jexcel > tbody > tr > td').css({
        "border-color": "#ccc"
    });
    body.find('.bulk_save').attr('disabled', 'disabled');
    if (body.find('.bulk_save').hasClass('disabled') === false) {
        body.find('.bulk_save').addClass('disabled');
    }

    var bulk_data = myTable.getData();
    $.each(bulk_data, function(index1, row) {
        singlerow = row;
        $.each(singlerow, function(index2, val) {
            var singlevalue = val;
            var city_validation = true;
            // if(index2 == 0 || index2 == 1){

            //   if($.inArray(singlevalue, active_cities) == -1) {
            //     city_validation = false;
            //    }
            // }
            if (index2 != 18 && index2 != 13 && index2 != 12 && index2 != 14 && index2 != 9) {
                if (typeof singlevalue === "undefined" || singlevalue === "" ||
                    city_validation == false) {
                    active_cell_index = indexToChar(index2).toUpperCase();
                    var active_cell = active_cell_index + (index1 + 1);
                    myTable.setStyle(active_cell, 'border', '1px solid red');
                    validated = false;
                    $('.bulk_save').removeAttr('disabled');
                    $('.bulk_save').removeClass('disabled');
                }
            }
        });
    });
    var print_template = $('.print_template').val();
    if (validated == true) {
        $.ajax({
            url: 'pages/orders/bulk_booking.php',
            type: 'POST',
            data: {
                bulk_booking: 1,
                bulk_data: bulk_data
            },
            dataType: 'json',
            success: function(response) {
                if (response.error) {
                    $('.bulk_save').removeAttr('disabled');
                    $('.bulk_save').removeClass('disabled');
                    alert(response.alert_msg);
                    return;
                }

                // //print invoices
                $.ajax({
                    url: print_template + '?save_print=1&order_id=' + response.data_ids +
                        '&print=1',
                    data: {
                        save_print: 1,
                        print_data: response.data_ids
                    },
                    cache: false,
                    success: function(response2) {
                        var w = window.open('', 'mynewwindow', 'height=600,width=900');
                        w.document.write(response2);
                        w.document.close();
                        window.location.reload();
                    }
                });
            }
        });

    } else if (validated == false) {}

})
</script>

<script type="text/javascript">
$('#preloader').hide();
</script>
<script type="text/javascript">
function googleTranslateElementInit() {
    // ,includedLanguages:'en,ar'
    new google.translate.TranslateElement({
        pageLanguage: 'en'
    }, 'google_translate_element');
}
</script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-142111405-1"></script>
<script>
window.dataLayer = window.dataLayer || [];

function gtag() {
    dataLayer.push(arguments);
}
gtag('js', new Date());
gtag('config', 'UA-142111405-1');
</script>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
</script>
<!-- /Popup: Login -->
<!-- Search Popup
<div class="search-popup">
<div>
<div class="popup-box-inner">
<form>
  <input class="search-query" type="text" placeholder="Search and hit enter" />
</form>
</div>
</div>
<a href="javascript:void(0)" class="close-search"><i class="fa fa-close"></i></a>
</div>-->
<!-- / Search Popup -->
<!-- Main Jquery JS -->
<!-- Bootstrap JS -->

<!-- OwlCarousel2 Slider JS -->
<script src="assets/plugins/owl.carousel.2/owl.carousel.min.js" type="text/javascript"></script>
<!-- Sticky Header -->
<script src="assets/js/jquery.sticky.js"></script>

<!-- Wow JS -->
<script src="assets/plugins/WOW-master/dist/wow.min.js" type="text/javascript"></script>
<!-- Data binder -->

<script src="assets/plugins/data.binder.js/data.binder.js" type="text/javascript"></script>

<!-- Slider JS -->
<!-- Theme JS -->
<script src="assets/js/theme.js" type="text/javascript"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.0.1/Chart.min.js"></script>

<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDJoTnvdGgAuOLEBLbjQsxyqy8r3pY5I7g&libraries=geometry,places&callback=initialize" async defer></script> -->
<script src="<?php echo getConfig('map_api'); ?>" async defer></script>

<script type="text/javascript">
    var telInput = $("#phone"),
  errorMsg = $("#error-msg"),
  validMsg = $("#valid-msg");

// initialise plugin
telInput.intlTelInput({

  allowExtensions: true,
  formatOnDisplay: true,
  autoFormat: true,
  autoHideDialCode: true,
  autoPlaceholder: true,
  defaultCountry: "auto",
  ipinfoToken: "yolo",

  nationalMode: false,
  numberType: "MOBILE",
  //onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
  preferredCountries: ['sa', 'ae', 'qa','om','bh','kw','ma'],
  preventInvalidNumbers: true,
  separateDialCode: true,
  initialCountry: "auto",
  geoIpLookup: function(callback) {
  $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
    var countryCode = (resp && resp.country) ? resp.country : "";
    callback(countryCode);
  });
},
   utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/utils.js"
});

var reset = function() {
  telInput.removeClass("error");
  errorMsg.addClass("hide");
  validMsg.addClass("hide");
};

// on blur: validate
telInput.blur(function() {
  reset();
  if ($.trim(telInput.val())) {
    if (telInput.intlTelInput("isValidNumber")) {
      validMsg.removeClass("hide");
    } else {
      telInput.addClass("error");
      errorMsg.removeClass("hide");
    }
  }
});

// on keyup / change flag: reset
telInput.on("keyup change", reset);

</script>
<script type="text/javascript">
   $(".country-list li").click(function() {
    $(".default_number,.flag_default").hide();
});
</script>
<!-- <script type="text/javascript">
    $(".chat_icon a").click(function(){
        $(".popup_box").fadeIn();
    });
    $(".close_popup img").click(function(){
        $(".popup_box").fadeOut();
    });

    
</script> -->


</body>

</html>