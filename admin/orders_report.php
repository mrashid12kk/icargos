<?php
session_start();
require 'includes/conn.php';
if(isset($_SESSION['users_id']) &&($_SESSION['type']!=='driver')){
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

         include "pages/reports/orders_report.php";

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

      $(function () {
        $('.datetimepicker4').datetimepicker({
          format: 'YYYY/MM/DD',
        });
      });
    </script>
    <script type="text/javascript">
      $('body').on('click','.main_select',function(e){
       $('#order_datatable thead>tr>th').unbind('click');
       var check = $('#order_datatable').find('tbody > tr > td:first-child .order_check');
       if($('.main_select').prop("checked") == true){
        $('#order_datatable').find('tbody > tr > td:first-child .order_check').prop('checked',true);
      }else{
        $('#order_datatable').find('tbody > tr > td:first-child .order_check').prop('checked',false);
      }

      $('#order_datatable').find('tbody > tr > td:first-child .order_check').val();
    })
      var mydata = [];
      $('body').on('click','.lable_print_ver',function(e){
        // alert();
        e.preventDefault();
        $('.orders_tbl > tbody  > tr').each(function() {
          var checkbox = $(this).find('td:first-child .order_check');
          if(checkbox.prop("checked") ==true){
            var order_id = $(checkbox).data('id');
            mydata.push(order_id);
          }
        });
        var order_data = mydata.join(',');

        $('#print_data_ver').val(order_data);
        $('#bulk_submit_ver').submit();
        location.reload();
      })
      var mydata = [];
      $('body').on('click','.lable_print_hor',function(e){
        e.preventDefault();
        $('.orders_tbl > tbody  > tr').each(function() {
          var checkbox = $(this).find('td:first-child .order_check');
          if(checkbox.prop("checked") ==true){
            var order_id = $(checkbox).data('id');
            mydata.push(order_id);
          }
        });
        var order_data = mydata.join(',');

        $('#print_data_hor').val(order_data);
        $('#bulk_submit_hor').submit();
        location.reload();
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
        location.reload();
      })
      var mydata = [];
      $('body').on('click','.update_status',function(e){
        e.preventDefault();
        $('#order_datatable > tbody  > tr').each(function() {
          var checkbox = $(this).find('td:first-child .order_check');
          if(checkbox.prop("checked") ==true){
            var order_id = $(checkbox).data('id');
            mydata.push(order_id);
          }
        });
        var order_data = JSON.stringify(mydata);
        $('#print_data').val(order_data);
        $('#bulk_submit').submit();
      })
    </script>
    <script type="text/javascript">
      document.addEventListener('DOMContentLoaded', function() {
        var dataTable = $('#order_datatable').DataTable({
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
        .css( 'font-size', '10pt' )
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
     'url':'ajax_view_reports.php',
     beforeSend: function(){
      $('#image').show();
    },
    complete: function(){
      $('#image').hide();
    },
    'data': function(data){
          // Read values
          var tracking_no=$('#tracking_no').val();
          var origin = $('#origin').val();
          var destination = $('#destination').val();
          var customer_id = $('#customer_id').val();
          var customer_type = $('#customer_type').val();
          var payment_status = $('#payment_status').val();
          var status = $('#status').val();
          var courier = $('#courier').val();
          var date_type = $('#date_type').val();
          var from = $('#from').val();
          var to = $('#to').val();
          // Append to data
          data.tracking_no = tracking_no;
          data.origin = origin;
          data.destination = destination;
          data.customer_id = customer_id;
          data.customer_type = customer_type;
          data.payment_status = payment_status;
          data.status = status;
          data.courier = courier;
          data.date_type = date_type;
          data.from = from;
          data.to = to;
        }
      },

      'columns': [
      { data: 'id' },
      { data: 'cnno' },
      { data: 'service_type' },
      { data: 'order_type' },
      { data: 'user' },
      { data: 'status' },
      { data: 'status_date' },
      { data: 'cndate' },
      { data: 'pickupname' },
      { data: 'pickupcompany' },
      { data: 'pickupphone' },
      { data: 'pickupaddress' },
      { data: 'deliveryname' },
      { data: 'deliveryphone' },
      { data: 'deliveryaddress' },
      { data: 'pickupcity' },
      { data: 'deliverycity' },
      { data: 'refernceno' },
      { data: 'orderid' },
      { data: 'noofpiece' },
      { data: 'parcelweight'},
      { data: 'fragile' },
      { data: 'insureditemdeclare' },
      { data: 'codamount' },
      { data: 'deliveryfee' },
      { data: 'specialcharges' },
      { data: 'extra_charges' },
      { data: 'insurancepremium' },
      { data: 'grand_total_charges' },
      { data: 'fuelsurcharge' },
      { data: 'salestax' },
      { data: 'netamount' },
      { data: 'invoice' },
      { data: 'paymentstatus' },
      { data: 'action' },


      ]
    });
        $('#submit_order').click(function(e){
          e.preventDefault();
          charges_count();
          dataTable.draw();
        });



        $('#print_data').click(function(e){
          e.preventDefault();
          var date_range=$('#date_range').val();
          var date_from = $('#date_from').val();
          var date_to = $('#date_to').val();
          var collection_centers = $('#collection_centers').val();
          window.open('https://transco.itvision.pk/admin/print_sale_report.php?date_range='+date_range+'&date_from='+date_from+'&date_to='+date_to+'&collection_centers='+collection_centers+'&print=1');
        });
      }, false);
    </script>

  </script>


  <script type="text/javascript">
   $(document).ready(function(){
    charges_count();
  })
   function export_csv(page = 0)
   {
       var btn = '#csv_btn';
       $(btn).hide();
    var tracking_no=$('#tracking_no').val();
    var origin = $('#origin').val();
    var destination = $('#destination').val();
    var customer_id = $('#customer_id').val();
    var customer_type = $('#customer_type').val();
    var payment_status = $('#payment_status').val();
    var status = $('#status').val();
    var courier = $('#courier').val();
    var date_type = $('#date_type').val();
    var from = $('#from').val();
    var to = $('#to').val();
    var data= {
      tracking_no:tracking_no,
      courier:courier,
      date_type:date_type,
      from:from,
      to:to,
      page:page,
      customer_id:customer_id,
      customer_type:customer_type,
      payment_status:payment_status,
      status:status,
      destination:destination,
      origin:origin,
      charges_report:1,
    };
      //alert(order_status);
      $.ajax({
        type:'POST',
        data:data,
        dataType:'json',
        url:'reports/ajax_order_charges.php',
        success:function(response){
            
            $('#csv_progress').show();
            $('#csv_progress .progress-bar').css('width',response.percent+'%');
            $('#csv_progress .progress-bar').html(response.percent+'% completed');
            if(response.npage)
            {
            console.log(response.npage);
            
            export_csv(response.npage);
            }
            else
            {
                $(btn).show();
                 window.open(response.url, '_blank');

                // alert(response.url);
            }
        }
      });
    }
   function charges_count()
   {
    var tracking_no=$('#tracking_no').val();
    var origin = $('#origin').val();
    var destination = $('#destination').val();
    var customer_id = $('#customer_id').val();
    var customer_type = $('#customer_type').val();
    var payment_status = $('#payment_status').val();
    var status = $('#status').val();
    var courier = $('#courier').val();
    var date_type = $('#date_type').val();
    var from = $('#from').val();
    var to = $('#to').val();
    var data= {
      tracking_no:tracking_no,
      courier:courier,
      date_type:date_type,
      from:from,
      to:to,
      customer_id:customer_id,
      customer_type:customer_type,
      payment_status:payment_status,
      status:status,
      destination:destination,
      origin:origin,
      charges_report:1,
    };
      //alert(order_status);
      $.ajax({
        type:'POST',
        data:data,
        dataType:'json',
        url:'ajax_order_charges.php',
        success:function(response){
          $('.noofpiece').html(response.noofpiece);
          $('.parcelweight').html(response.parcelweight);
          $('.fragile').html(response.fragile);
          $('.insureditemdeclare').html(response.insureditemdeclare);
          $('.codamount').html(response.codamount);
          $('.deliveryfee').html(response.deliveryfee);
          $('.specialcharges').html(response.specialcharges);
          $('.extra_charges').html(response.extra_charges);
          $('.insurancepremium').html(response.insurancepremium);
          $('.grand_total_charges').html(response.grand_total_charges);
          $('.fuelsurcharge').html(response.fuelsurcharge);
          $('.salestax').html(response.salestax);
          $('.netamount').html(response.netamount);
        }
      });
    }
  </script>
<!-- <script type="text/javascript">
    function myFunction() {
      var date_range=$('#date_range').val();
      var date_from = $('#date_from').val();
      var date_to = $('#date_to').val();
      var collection_centers = $('#collection_centers').val();
    window.open('https://transco.itvision.pk/admin/print_sale_report.php?date_range='+date_range'&date_from='+date_from'&date_to='+date_to'&collection_centers='+collection_centers);
}


</script> -->
