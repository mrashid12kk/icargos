<?php
  session_start();
  require 'includes/conn.php';
  if(isset($_SESSION['users_id'])){
     require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'],22,'view_only',$comment =null)) {

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
            <div class="page-header"><h1><?php echo getLange('setup'); ?> <small><?php echo getLange('letsgetquick'); ?></small></h1></div>
              <div class="row">
                 
                   <?php
                      require_once "setup-sidebar.php";
                   ?>
                     
                
                <div class="col-lg-10 " id="setting_box">
                  <?php
                    include "pages/settings/settings.php";
                  ?>
                </div>
            </div>
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

    //     // alert();
    //       $.ajax({
    //     url: 'ajax_new_country.php',
    //     type: "Post",
    //     async: true,
    //     data: { 
    //         name:$('#country').val()
    //     },
    //     success: function (data) {
    //        // alert(data);
    //        $('#city').html(data);

    //     },
    //     error: function (xhr, exception) {
          
    //     }
    // }); 
     $('#country').on('change' , function(){
        // alert();
          $.ajax({
        url: 'ajax_new_country.php',
        type: "Post",
        async: true,
        data: { 
            name:$(this).val()
        },
        success: function (data) {
           // alert(data);
           $('#city').html(data);

        },
        error: function (xhr, exception) {
          
           
        }
    }); 
    });
});
</script>