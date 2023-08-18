<?php
    session_start();
    require 'includes/conn.php';
    if(isset($_SESSION['users_id']) &&($_SESSION['type']!=='driver')){
         require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'],29,'view_only',$comment =null)) {

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

            include "pages/reports/comments_report.php";

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
 $(document).ready(function(){

     var customer_id = $('#customer_id').val();
          var from = $('#from').val();
          var to = $('#to').val();
          var comments_report = comments_report;
        
   var data= {
         customer_id:customer_id,
          from:from,
          to:to,
          comments_report:1,
      };
      $.ajax({
      type:'POST',
      data:data,
       dataType:'json',
      url:'ajax_delivery.php',
      success:function(response){
       $('#all').html('');
      $('#all').html(response.all);
        $('#read').html('');
      $('#read').html(response.read);
        $('#unread').html('');
      $('#unread').html(response.unread);
      }
      });
     })
  $('#submit_order').click(function(e){
    e.preventDefault();
    var customer_id = $('#customer_id').val();
          var from = $('#from').val();
          var to = $('#to').val();
   var data= {
          customer_id:customer_id,
          from:from,
          to:to,
          comments_report:1,
      };
      //alert(order_status);
      $.ajax({
      type:'POST',
      data:data,
       dataType:'json',
      url:'ajax_delivery.php',
       success:function(response){
      $('#all').html('');
      $('#all').html(response.all);
        $('#read').html('');
      $('#read').html(response.read);
        $('#unread').html('');
      $('#unread').html(response.unread);
      }
      });
     })
}, false);
</script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.datepicker').datetimepicker({
            format: 'dd/mm/yyyy',

        });

            $(".comment_modal_id").click(function(){
                location.reload();
            })

            $(".read_msg").click(function(){
                var track= $(this).attr('data-track');
                var date= $(this).attr('data-date');
                var name= $(this).attr('data-name');
                var subject= $(this).attr('data-subject');
                var comment= $(this).attr('data-comment');
                var commentby= $(this).attr('data-commentby');
                $(".track_no_val").text(track);
                $(".order_date_val").text(date);
                $(".customer_name_val").text(name);
                $(".subject_val").text(subject);
                $(".comment_by_val").text(commentby);
                $(".order_comment_val").text(comment);


                var id = $(this).attr('data-id');

                $.ajax({
                        url:"ajax.php",    //the page containing php script
                        type: "post",    //request type,
                        dataType: 'json',
                        data: {comentid:id},
                        success:function(result){

                        }
                    });
            })



        })
    </script>
    </script>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
  var dataTable = $('#comment_datatable').DataTable({
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
       'url':'ajax_view_comment.php',
        beforeSend: function(){
        $('#image').show();
    },
    complete: function(){
        $('#image').hide();
    },
       'data': function(data){
          // Read values
          var customer_id = $('#customer_id').val();
          var from = $('#from').val();
          var to = $('#to').val();
          var read = $('.action_btns').find('.active').attr('data-read');
          // Append to data
          data.customer_id = customer_id;
          data.from = from;
          data.to = to;
          data.read = read;
       }
    },

    'columns': [
       { data: 'id' },
       { data: 'tracno' },
       { data: 'createdon' },
       { data: 'customername' },
       { data: 'subject' },
       { data: 'orderamount' },
       { data: 'commentby' },
       { data: 'itemvalue' },
       { data: 'action' }
    ]
  });
 $('#submit_order').click(function(e){
    e.preventDefault();
    dataTable.draw();
  });
  $('body').on('click','.readhref',function(e){
    e.preventDefault();
   var status=$(this).attr('data-status');
    $('.readhref').removeClass('active');
    $(this).addClass('active');
     dataTable.draw();
     })
}, false);
</script>
