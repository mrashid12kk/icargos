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
  
      include "pages/reports/insales_reports.php";
      
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
    var check = $('#basic-datatable').find('tbody > tr > td:first-child .order_check');
    if($('.main_select').prop("checked") == true){
      $('#basic-datatable').find('tbody > tr > td:first-child .order_check').prop('checked',true);
    }else{
      $('#basic-datatable').find('tbody > tr > td:first-child .order_check').prop('checked',false);
    }
    
    $('#basic-datatable').find('tbody > tr > td:first-child .order_check').val();
  })
          var mydata = [];
  $('body').on('click','.update_status',function(e){
    e.preventDefault();
    $('#basic-datatable > tbody  > tr').each(function() {
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
          var date_range=$('#date_range').val();
          var date_from = $('#date_from').val();
          var date_to = $('#date_to').val();
          var collection_centers = $('#collection_centers').val();
          // Append to data
          data.date_range = date_range;
          data.date_from = date_from;
          data.date_to = date_to;
          data.collection_centers = collection_centers;
       }
    },
    
    'columns': [
       { data: 'id' }, 
       { data: 'cnno' }, 
       { data: 'service_type' }, 
       { data: 'cndate' }, 
       { data: 'collectioncenter' }, 
       { data: 'customername' }, 
       { data: 'shippername' }, 
       { data: 'consigneename' }, 
       { data: 'origin' }, 
       { data: 'des' }, 
       { data: 'mode' }, 
       { data: 'pices' }, 
       { data: 'weight' }, 
       { data: 'rate' }, 
       { data: 'amount' }, 
       { data: 'gst' }, 
       { data: 'amountincl'}, 
       { data: 'patri_expenses'}, 
       { data: 'cn_charges' }, 
       { data: 'challan_fee' }, 
       { data: 'insurance_fee' }, 
       { data: 'totalcharges' },
       { data: 'netamount' }, 
       { data: 'orderamount' }, 
    
       // { data: 'totalprice' },
    ]
  });
  $('#submit_order').click(function(e){
    e.preventDefault();
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
<!-- <script type="text/javascript">
	function myFunction() {
	  var date_range=$('#date_range').val();
      var date_from = $('#date_from').val();
      var date_to = $('#date_to').val();
      var collection_centers = $('#collection_centers').val();
	window.open('https://transco.itvision.pk/admin/print_sale_report.php?date_range='+date_range'&date_from='+date_from'&date_to='+date_to'&collection_centers='+collection_centers);
}


</script> -->