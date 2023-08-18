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

            include "email/email_sidebar.php";
            include "email/sent_list_email.php";
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
    $('body').on('click', '.main_select', function(e) {
        $('#sms_table thead>tr>th').unbind('click');
        var check = $('#sms_table').find('tbody > tr > td:first-child .email_check');
        if ($('.main_select').prop("checked") == true) {
            $('#sms_table').find('tbody > tr > td:first-child .email_check').prop('checked', true);
        } else {
            $('#sms_table').find('tbody > tr > td:first-child .email_check').prop('checked', false);
        }

        $('#sms_table').find('tbody > tr > td:first-child .email_check').val();
    })
    var mydata = [];
    $('body').on('click', '.delete_email_btn', function(e) {
        e.preventDefault();
        $('#sms_table > tbody  > tr').each(function(){
            var checkbox = $(this).find('td:first-child .email_check');
            if (checkbox.prop("checked") == true) {
                var order_id = $(checkbox).attr('data-id');
                mydata.push(order_id);
            }
        });
        var order_data = mydata.join(',');
        $('#delete_email').val(order_data);
        $('#delete_form').submit();
    })    
</script>
    <script type="text/javascript">
      $(function () {
          $('.datetimepicker4').datetimepicker({
            format: 'YYYY/MM/DD',
          });
      });
document.addEventListener('DOMContentLoaded', function() {
  var dataTable = $('#sms_table').DataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    // 'scrollCollapse': true,
        // 'ordering': false,
        // pageLength: 5,
        'responsive': true,
        'dom': "<'row'<'col-sm-4'l><'col-sm-4'f><'col-sm-4 text-right'B>>t<'bottom'p><'clear'>",
       // dom: '<"html5buttons"B>lTfgitp',
         'buttons': [
                  // {extend: 'copy'},
                  // {extend: 'csv'},
                  // {extend: 'excel', title: 'ExampleFile'},
                  // {extend: 'pdf', title: 'ExampleFile'},
                  // {extend: 'print',

                  //  customize: function (win){
                  //    $(win.document.body)
                  //       .css( 'font-size', '10pt' )
                  //       .prepend(
                  //           '<div>xxxxxxxxxxxxxxxxxxxxxxxx</div><img src="http://datatables.net/media/images/logo-fade.png" style="position:absolute; top:0; left:0;" />'
                  //       );
                  //         $(win.document.body).addClass('white-bg');
                  //         $(win.document.body).css('font-size', '10px');
                  //         $(win.document.body).find('table')
                  //                 .addClass('compact')
                  //                 .css('font-size', 'inherit');
                  // }
                  // }
              ],
    //'searching': false, // Remove default Search Control
    'ajax': {
       'url':'ajax_view_email_list.php',
       beforeSend: function(){
        $('#image').show();
    },
    complete: function(){
        $('#image').hide();
    },
       'data': function(data){
          // Read values
         //console.log(response);
          var number=$('#number').val();
          var message=$('#message').val();
          var send_to=$('#send_to').val();
          var datetime=$('#datetime').val();
          var date_from = $('#date_from').val();
          var date_to = $('#date_to').val();
          //alert(date_from);
          data.date_from = date_from;
          data.date_to = date_to;
          data.number = number;
          data.message = message;
          data.send_to = send_to;
          data.datetime = datetime;
       }
    },
    'columns': [
       { data: 'id' },
       { data: 'number' },
       { data: 'message_content' },
       { data: 'send_to' },
       { data: 'date_time' },
       { data: 'sms_events' },
       { data: 'status' },
       { data: 'action' },
       
    ]
  });

  $('#search_button').click(function(e){
    e.preventDefault();
    dataTable.draw();
  });
  $('#number').keyup(function(e){
    e.preventDefault();
    dataTable.draw();
  });
  $('#message').keyup(function(e){
    e.preventDefault();
    dataTable.draw();
  });
  $('#send_to').keyup(function(e){
    e.preventDefault();
    dataTable.draw();
  });
  $('#datetime').keyup(function(e){
    e.preventDefault();
    dataTable.draw();
  });
   // $('body').on('click','.orders_items',function(e){
   //  e.preventDefault();
   // var status=$(this).attr('data-status');
   //  $('.orders_items').removeClass('active_orders');
   //  $(this).addClass('active_orders');
   //   dataTable.draw();
   //   });

    $('#tracking_no').keypress(function (e) {
        var key = e.which;
        if(key == 13)  // the enter key code
        {
            $('#submit_order').click();
            return false;
        }
    });
}, false);
</script>
<!-- <script type="text/javascript">
     $(document).on("click",".delete",function(e){
      var data = $(this).attr("data-id");
      alert(data);
     
          
          $.ajax({
            'url':'ajax_view_sms_list.php',
            data:{
                
              data:data,  

            },
            type:"post",
            dataType:"json",
            success:function(response){
              //console.log(response);
                 thiss.parent().parent().remove();              
            }
          });
            
        
    });

    </script> -->