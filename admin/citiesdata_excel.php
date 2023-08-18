<?php
session_start();



require 'includes/conn.php';



if (isset($_SESSION['users_id']) && $_SESSION['type'] == 'admin') {

 require_once "includes/role_helper.php";

 if (!checkRolePermission($_SESSION['user_role_id'], 21, 'view_only', $comment = null)) {



  header("location:access_denied.php");

}

include "includes/header.php";







?>
<style>



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
      color: #fff;
      font-size: 13px;
      padding: 9px 18px;
      border-radius: 3px;
      display: inline-block;
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
      padding-left: 7px;
   }
   .main_box_hide {
      display: none;
   }
   .upload_excel_file{    padding: 12px 0px;}
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
      background: #fff;
      text-align: center;
      border-radius: 6px;
      max-width: unset;

      border: 1px solid #3333;
      box-shadow: 0 0 6px 4px #1b1b1b0d;
      padding: 30px;
      width: 460px;
      position: relative;

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
    padding: 0 0 0 6px;
 }
 .m_zero .filelabel {
    border: 1px solid #3333;
    border-radius: 5px;
    display: block;
    padding: 16px;
    transition: border 300ms ease;
    cursor: pointer;
    text-align: center;
    box-shadow: 4px 2px 7px 0px #00000021;
    margin: 0;
 }
 .upload_excel_file .change_file_name {
    border: unset;
    width: 100%;
    color: #010101 !important;
    font-weight: 500;
 }
 .upload_excel_file .buttons label {
    margin: 20px 0 0;
    font-weight: 600;
    font-size: 20px;
    color: #286fad;
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
<body>
   <?php
   include "includes/sidebar.php";


   ?>
   <!-- Aside Ends-->
   <section class="content">
      <?php
      include "includes/header2.php";
      
      
      
      ?>
      <div class="warper container-fluid">
         <div class="page-header">
            <h1><?php echo getLange('city'); ?> <small><?php echo getLange('letsgetquick'); ?></small></h1>
         </div>
         <div class="row">
            <?php
            include "pages/location/location_sidebar.php";

            ?>
            <div class="col-sm-10 table-responsive" id="setting_box">
               <div class="panel panel-default">
                  <div class="panel-heading">Upload Cities</div>
                  <div class="panel-body">
                     <div class=" dashboard upload_excel_file">
                        <?php echo isset($_SESSION['upload_msg']) && !empty($_SESSION['upload_msg']) ? $_SESSION['upload_msg'] : ''; unset($_SESSION['upload_msg']); ?>
                        <div class="row ">
                           <div class="col-sm-8 complete_profile" style="text-align: left !important;">
                              <a class="go_to sample_sheet" href="<?php echo BASE_URL; ?>admin/assets/excel/add_cities.xlsx" download="">Download Sample Sheet</a>
                           </div>
                           <div class="col-sm-8 progressbar">
                              <h3>ImPort Excel Sheet</h3>
                           </div>
                        </div>
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

                         </div>
                      </div>
                      <br>
                      <div class="row">
                       <div class="col-sm-12 upload_btn">
                        <input type="hidden" id="file_name_org">
                        <button id="submit" disabled="true" class="submit btn btn-info">Submit</button>
                        <div class="row">
                         <div id="msg"></div>
                         <img src="<?php echo BASE_URL; ?>images/loader_se.gif" style="width: 150px;display: none;" id="image1">
                      </div>
                   </div>
                </div>
                <br>
                <div class="row">
                  <div class="progress">
                     <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%" id="progressbar_validation">
                      <span>
                        <p style="display: inline;" class="validation_process">0</p>%
                        Complete
                     </span>
                  </div>
               </div>
            </div>
            <input type="hidden" value="" id="total_record">
         </div>
      </div>
   </div>
   <?php
   include "includes/footer.php";

} else {



   header("location:index.php");

}



?>
<script>
 $(document).ready(function() {
   $("#FileInput").change(function() {
      var validExtensions = ["xlsx", "xlsm"]
      var file = $(this).val().split('.').pop();
      if (validExtensions.indexOf(file) == -1) {
       var msg = ("Only formats are allowed : " + validExtensions.join(', '));
       $('#msg').html('');
       $('#msg').html(msg);
       $(this).val("");
       $('#submit').prop('disabled', true);
    } else {
       $('#msg').html('');
       $('#submit').prop('disabled', false);
    }
 });
   $("#submit").click(function() {
      var fd = new FormData();
      fd.append('file', $('#FileInput')[0].files[0]);
      $.ajax({
        url: 'ajax_import_add_cities.php',
        dataType:'Json',
        beforeSend: function() {
           $('#image1').show();
        }, 
        cache: false,
        contentType: false,
        processData: false,
        data: fd,
        type: 'post',
        success: function(output) {
          $('#msg').html(output.msg);
          $('#file_name_org').val(output.filename);
          $('#submit').prop('disabled', true);
          $('#FileInput').prop('disabled', true);
          update_data_excel();
       }
    })
   })

   function update_data_excel() {
      var file_name_org = $('#file_name_org').val();
      $.ajax({
        url: 'ajax_import_add_cities.php',
        type: 'POST',
        data: {
         update_cities_excel: 1,
         file_name_org: file_name_org
      },
      dataType: 'json',
      success: function(response) {
         $('#total_record').val(response);
         $('#image1').hide();
         push_data(1);
      }
   });
   }
   function push_data(page)
   {
     $.ajax({
        url: 'ajax_import_add_cities.php',
        type: 'POST',
        data: {
         limit_no: page,
         update_cities: 1
      },
      dataType: 'json',
      success: function(response) {
         if (response) {
            var total_record = $('#total_record').val();
            var cpage = page+1;
            var percentage = (page/total_record) * 100;
            percentage = parseFloat(percentage).toFixed(2);
            if (percentage < 101) {
               $('#progressbar_validation').css('width', parseInt(percentage) + '%');
               $('.validation_process').html(percentage);
               push_data(cpage);
            }
            if (percentage > 100) {
               $('#progressbar_validation').css('width', '100%');
               $('.validation_process').html('100');
               setInterval(function() {
                  location.reload();
               }, 3000);
            }
         }
      }
   });
  }
  function update_data() {
   var total_record = $('#total_record').val();
   total_record=parseInt(total_record);
   var percentage=100 / total_record;
   var percentage_total=0;
   percentage=parseFloat(percentage);
   var upload=false;
   if (total_record > 1) {
      var i;
      for (i = 1; i <= total_record; i++) {

      }
      $('#msg').html('');
      $('#submit').prop('disabled', true);
      $('#FileInput').prop('disabled', false);
      $('#FileInput').val('');
      setInterval(function() {
         location.reload();
      }, 5000);
   }
   else if(total_record == 1){
      $.ajax({
        url: 'ajax_import_add_cities.php',
        type: 'POST',
        data: {
         update_cities: 1
      },
      dataType: 'json',
      success: function(response) {
         $('#progressbar_validation').css('width', '100%');
         $('.validation_process').html('100');
         $('#msg').html('');
         $('#submit').prop('disabled', true);
         $('#FileInput').prop('disabled', false);
         $('#FileInput').val('');
         setInterval(function() {
            location.reload();
         }, 5000);
      }
   });
   }
}
});
</script>
<script type="text/javascript">
   $('.select2').select2();

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
</script>
