<?php

    session_start();

    require 'includes/conn.php';

    if(isset($_SESSION['users_id']) && $_SESSION['type']=='admin'){
 require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'],42,'view_only',$comment =null)) {

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

       <style type="text/css">

          .city_to option.hide {

            /*display: none;*/

          }

          .form-group{

            margin-bottom: 0px !important;

          }

        </style>

        <!-- Header Ends -->





        <div class="warper container-fluid">

          <div class="row">
            <?php require_once "setup-sidebar.php"; ?>
          <div class="col-sm-10 table-responsive" id="setting_box">
            <?php include('pages/settings/branding.php'); ?>
          </div>

        </div>
        </div>

      <?php



  include "includes/footer.php";

  }

  else{

    header("location:index.php");

  }

  ?>

<script type="text/javascript">
  $('.country').on('change',function(){
    var name = $(this).val();
      $.ajax({
        url: 'ajax_new_country.php',
        type: "Post",
        async: true,
        data: {name:name},
        success: function (data) {
            $('#city').html(data);
        },
        error: function (xhr, exception) {
         }
    });
  });
</script>