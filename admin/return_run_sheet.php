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
	
			include "pages/orders/return_run_sheet.php";
			
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
		if($('.courier_list').val() ==''){
			alert('Please choose rider');
			return false;
		}
		if($('.delivery_run').val() ==''){
			alert('Please add order trackings');
			return false;
		}
		var order_data = $('.delivery_run').val();
        $('#print_data').val(order_data);
        $('#bulk_delivery_submit').submit();
	})
        </script>