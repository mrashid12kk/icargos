<?php
    session_start();
    require 'includes/conn.php';
    if(isset($_SESSION['users_id'])){
         require_once "includes/role_helper.php";
    // if (!checkRolePermission($_SESSION['user_role_id'],44,'view_only',$comment =null)) {

    //     header("location:access_denied.php");
    // }
    include "includes/header.php";
?>
<body data-ng-app>
<style type="text/css">
#basic-datatable button {
    width: auto !important;
}
#basic-datatable form {
    display: inline;
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

            <div class="page-header"><h1><?php echo getLange('rider'); ?>  <small><?php echo getLange('letsgetquick'); ?></small></h1></div>

            <?php

            include "pages/reports/rider_wallet_report.php";

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
document.addEventListener('DOMContentLoaded', function() {
  var dataTable = $('#ridder_datatable').DataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    // 'scrollCollapse': true,
    // 'ordering': false,
    'responsive': true,
    'pageLength': 10,
    'lengthMenu':[[10,25,50,100,200,300],[10,25,50,100,200,300]],
    'dom': "<'row'<'col-sm-4'l><'col-sm-4'f><'col-sm-4 text-right'B>>t<'bottom'p><'clear'>",
       // dom: '<"html5buttons"B>lTfgitp',
     'buttons': [
              {extend: 'copy'},
              {extend: 'csv'},
              {extend: 'excel', title: 'ExampleFile'},
              {extend: 'pdf', title: 'ExampleFile'},
              {extend: 'print',

               customize: function (win){
                $(win.document.body)
                    .css('font-size', '10pt')
                    .prepend(
                        '<div>xxxxxxxxxxxxxxxxxxxxxxxx</div><img src="http://datatables.net/media/images/logo-fade.png" style="position:absolute; top:0; left:0;" />'
                    );
                      $(win.document.body).addClass('white-bg');
                      $(win.document.body).css('font-size', '10px');
                      $(win.document.body).find('table')
                              .addClass('compact')
                              .css('font-size', 'inherit');
              }
              }
          ],
    //'searching': false, // Remove default Search Control
    'ajax': {
       'url':'ajax_ridder_wallet_report.php',
        beforeSend: function(){
        $('#image').show();
    },
    complete: function(){
        $('#image').hide();
    },
       'data': function(data){
          // Read values
          // var customer_id = $('#customer_id').val();
          // var from = $('#from').val();
          // var to = $('#to').val();
          // // Append to data
          // data.customer_id = customer_id;
          // data.from = from;
          // data.to = to;
       }
    },

    'columns': [
       { data: 'id' },
       { data: 'ridername' },
       { data: 'ballance' },
       { data: 'viewdetail' }
    ]
  });
  $('#submit_order').click(function(e){
    e.preventDefault();
    charges_count();
    dataTable.draw();
  });
}, false);
</script>




<script type="text/javascript">
 $(document).ready(function(){
    charges_count();
     })
  function charges_count()
    {
    
          var ballance = $('.walletbalance').val();
   var data= {
          ballance:ballance,
          
      };
      //alert(order_status);
      $.ajax({
      type:'POST',
      data:data,
       dataType:'json',
      url:'ajax_wallet_total_bal.php',
       success:function(response){
        console.log(response);

      $('.walletbalance').html(response.balance);
     
      }
      });
     }
</script>