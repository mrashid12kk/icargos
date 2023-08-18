<?php
session_start();
 ?>
<style>
.fs-12 {
    font-size: 16px;
}
.tracking-form .form-group .btn-1:
.tracking-form .form-group .btn-1{
        font-size: 16px;
    letter-spacing: 1.5px;
    background-color: #21a0c7;
    border-radius: 35px 35px 35px 35px;
    padding: 13px 36px 13px 36px;
    margin-top: 18px;
    text-transform: capitalize;
}
.menu-bar {
    padding: 5px 0px 29px 0px !important;
}
.tracking-form .form-control,.tracking-form .form-control:focus {
    height: 47px;
    text-transform: none;
    background-color: #ffffff;
    border-color: #c4c4c4;
    font-family: "Roboto", Sans-serif;
    font-weight: 400;
    color: #7a7a7a !important;
    font-size: 15px;
}


@media (max-width: 1250px){
    .container{
        width: 100%;
    }

}


@media (max-width: 1024px){
    .container{
        width: 100%;
    }
  

}

@media (max-width: 767px){
    .container{
        width: auto;
    }

}

</style>

<?php
    // c
include_once "includes/conn.php";
$page_title = 'Package Tracking';
include "includes/header.php";
?>

<!-- Content Wrapper -->
<article>
    <!-- Breadcrumb -->
    <!-- <section class="theme-breadcrumb pad-50">
        <div class="theme-container container ">
            <div class="row">
                <div class="col-sm-8 pull-left packge-left_box">
                    <div class="title-wrap">
                        <h2 class="section-title no-margin about_info"> Package tracking </h2>
                        <p class="fs-16 no-margin"> Track your Package & see the current condition </p>
                    </div>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb-menubar list-inline">
                        <li><a href="tracking.html#" class="gray-clr">Home</a></li>
                        <li class="active">Track</li>
                    </ol>
                </div>
            </div>
        </div>
    </section> -->
    <!-- /.Breadcrumb -->

    <!-- Tracking -->
    <?php //echo $_SESSION['success'];?>
    <div class="tracking_Bg">
        <h1>Package Tracking</h1>
    </div>
    <section class="pt-50  tracking-wrap">
        <div class="theme-container container ">
            <div class="row pad-10  tracking-form">
                <div class="col-md-12" >
                <?php if(isset($_SESSION['track_not_found']) && !empty($_SESSION['track_not_found'])){ ?>
                    <div>
        <div class="alert alert-warning" style="text-align: center;">
          <strong><?php echo $_SESSION['track_not_found']; ?></strong>

        </div>

    </div>
                <?php
                unset($_SESSION['track_not_found']);
                 }

                 ?>

                    <div class="row tracking_form">
                    <h2 class="title-1"><?php echo getLange('trackyourpackage'); ?></h2>
                    <span class="font2-light fs-12">
                       <?php echo getLange('track10number'); ?>
                    </span>
                        <form action="track-details.php" method="POST" role="form" data-toggle="validator">
                            <div class="col-sm-12" style="display: flex;">
                            <div class="form-check col-sm-5">
                                  <input class="form-check-input" type="radio"  id="exampleRadios1" value="option1" checked>
                                  <label class="form-check-label" for="exampleRadios1">
                                    Tracking No
                                  </label>
                                </div>
                                <div class="form-check col-sm-7">
                                  <input class="form-check-input" type="radio" id="exampleRadios2" value="option2">
                                  <label class="form-check-label" for="exampleRadios2">
                                    Referece No/Order ID
                                  </label>
                                </div>
                            </div>
                    <div class=" col-sm-12">
                                <div class="form-group">
                                    <input type="text" name="track_code" placeholder="<?php echo getLange('entertrackingcode'); ?>" required class="form-control box-shadow tracking_no">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="ref_no" placeholder="<?php echo getLange('enterrefno'); ?>"  class="form-control box-shadow ref_no" style="display: none;">
                                </div>
                            </div>
                            <div class=" col-sm-12">
                                <div class="form-group">
                                    <button type="submit" name="submit" class="btn-1"><?php echo getLange('trackyourpackage'); ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
         </div>
    </section>
    <!-- /.Tracking -->

</article>
<!-- /.Content Wrapper -->

<?php

include "includes/footer.php";

// }

?>
<script>
document.addEventListener('DOMContentLoaded', function(){
    $('title').text($('title').text()+' Tracking')
}, false);
</script>
<script type="text/javascript">
    $('#exampleRadios2').click(function(){
        $('.ref_no').attr('required', 'true');
         $('.ref_no').css({'display':'block'});
        $('.tracking_no').removeAttr('required');
        $('.tracking_no').css({'display':'none'});
    });
      $('#exampleRadios1').click(function(){
        $('.tracking_no').attr('required', 'true');
        $('.tracking_no').css({'display':'block'});
        $('.ref_no').css({'display':'none'});
        $('.ref_no').removeAttr('required');
    });
</script>