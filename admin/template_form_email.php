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

            include "email/email_sidebar.php";
            include "email/template_form_email.php";

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

 

    <script type="text/javascript">
        $(document).ready(function(){
            var len = $('#messageArea').val().length;
            $('#count').val(len);
        });
        $(document).ready(function(){
          var event=$('#sms_events').val();
          if(event === 'Status Update'){
             $(".status").show();
             $("#select_status").attr("required", true);
          }
          else{
             $(".status").hide();
             $("#select_status").attr("required", false);
          }
        });
         $("#sms_events").on("change", function(){
          var event=$(this).val();
          if(event === 'Status Update'){
             $(".status").show();
             $("#select_status").attr("required", true);
          }
          else{
             $(".status").hide();
             $("#select_status").attr("required", false);
             $("#select_status").val("");
          }
        });
        $('#messageArea').keyup(function(){
             var len = $('#messageArea').val().length;
            $('#count').val(len);
        });
        $("#list_t_railway li").on("click", function(){
           var textAreaTxt = CKEDITOR.instances.messageArea.getData()
           var $txt = $("#messageArea");
           var caretPos = $txt[0].selectionStart;
           var txtToAdd = ($(this).text());
           // value = CKEDITOR.instances['DOM-ID-HERE'].getData()
           var updatedVal=$txt.val(textAreaTxt+txtToAdd);
            for(var i in CKEDITOR.instances) {
                CKEDITOR.instances[i].setData(updatedVal);
            }
           var len = $('#messageArea').val().length;
            $('#count').val(len);
        });
        $("#list_t_railway2 li").on("click", function(){
          var $txt = $("#messageArea");
           var caretPos = $txt[0].selectionStart;
           var textAreaTxt = $txt.val();
           var txtToAdd = ($(this).text());
           var updatedVal=$txt.val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos) );
           var len = $('#messageArea').val().length;
            $('#count').val(len);
        });
        $("#list_t_railway3 li").on("click", function(){
           var $txt = $("#messageArea");
           var caretPos = $txt[0].selectionStart;
           var textAreaTxt = $txt.val();
           var txtToAdd = ($(this).text());
           var updatedVal=$txt.val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos) );
           var len = $('#messageArea').val().length;
            $('#count').val(len);
        });
       
    </script>
      



