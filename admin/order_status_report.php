<?php
    session_start();
    require 'includes/conn.php';
    if(isset($_SESSION['users_id']) &&($_SESSION['type']!=='driver')){
       require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'],39,'view_only',$comment =null)) {

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

            <!-- <div class="page-header"><h1>Dashboard <small>Let's get a quick overview...</small></h1></div> -->

            <?php

            include "pages/reports/order_status_report.php";

            ?>

        </div>
        <!-- Warper Ends Here (working area) -->

      <?php

    //include "includes/footer.php";
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
          $(".favourite_checkbox").css('display','none');
          $(".show_panel").show();
      });

      $('body').on('click','.main_select',function(e){
      var check = $('.orders_tbl').find('tbody > tr > td:first-child .order_check');
      if($('.main_select').prop("checked") == true){
      $('.orders_tbl').find('tbody > tr > td:first-child .order_check').prop('checked',true);
      }else{
      $('.orders_tbl').find('tbody > tr > td:first-child .order_check').prop('checked',false);
      }

      $('.orders_tbl').find('tbody > tr > td:first-child .order_check').val();
      })
      var mydata = [];
      $('body').on('click','.print_invoice',function(e){
      e.preventDefault();
      $('.orders_tbl > tbody  > tr').each(function() {
      var checkbox = $(this).find('td:first-child .order_check');
      if(checkbox.prop("checked") ==true){
          var order_id = $(checkbox).data('id');
          mydata.push(order_id);
      }
      });
      var order_data = mydata.join(',');

      $('#print_data').val(order_data);
      $('#bulk_submit').submit();
      })
      $(".hide_box").click(function(){
         $(".favourite_checkbox").hide();
      });
      $(".hide_box").click(function(){
         $(".show_panel").show();
      });

      $(".show_panel button").click(function(){
         $(".favourite_checkbox").show();
      });
      $(".show_panel button").click(function(){
         $(".show_panel").hide();
      });
    $(document).on("click",".submitt",function(){

        var sel = $('input[type=checkbox]:checked').map(function(_, el) {
            return $(el).val();
        }).get();

        //alert(sel);
        $.ajax({
            url:"ajaxx.php",
            type: 'post',
            data:{data:sel,action:"status"},
            //dataType: 'json',
            success:function(response){
                console.log(response);
                if(response !==""){
                }
            }
        });
    });
    //$(document).on("load",,function(){
        //$("li").load(function(){
     var data = $(".id").val();
        //alert(data);
        $.ajax({
            url:"ajaxx.php",
            type: 'post',
            data:{data:sel,action:"status"},
            //dataType: 'json',
            success:function(response){
                //console.log(response);
                if(response !==""){
                }
            }
        });
   </script>
