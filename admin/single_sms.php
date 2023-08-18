<?php
    session_start();
    require 'includes/conn.php';
    if(isset($_SESSION['users_id']) &&($_SESSION['type']!=='driver' && $_SESSION['type'] == 'admin')){
    
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

            include "sms/sms_sidebar.php";
            include "sms/single_sms.php";

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
        })
        $("#messageArea").on("keyup", function(){
           var len = $('#messageArea').val().length;
            $('#count').val(len);
        });
        $("#list_t_railway li").on("click", function(){
          var messagetext = $('#messageArea').val();
          var invoce_parmeter=($(this).text());
          let updatedVal = messagetext+' '+invoce_parmeter;
          $('#messageArea').val(updatedVal);
        });
        $("#list_t_railway2 li").on("click", function(){
          var messagetext = $('#messageArea').val();
          var invoce_parmeter=($(this).text());
          let updatedVal = messagetext+' '+invoce_parmeter;
          $('#messageArea').val(updatedVal);
        });
        $("#list_t_railway3 li").on("click", function(){
          var messagetext = $('#messageArea').val();
          var invoce_parmeter=($(this).text());
          let updatedVal = messagetext+' '+invoce_parmeter;
          $('#messageArea').val(updatedVal);
        });
    </script>
    <script type="text/javascript">
  $('body').on('change','.template_id ',function(e){
    e.preventDefault();
       var id=$(this).val();
          $.ajax({
          type:'POST',
          data:{template_id:id},
          url:'ajax.php',
          success:function(response){
          $('.message_content').val('');
          $('.message_content').val(response);
           var len = $('#messageArea').val().length;
            $('#count').val(len);
          }
          });
     })

</script>