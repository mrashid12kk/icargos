<?php
	session_start();
	require 'includes/conn.php';
	if(isset($_SESSION['users_id']) &&($_SESSION['user_role']==='rider')){
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
	
			include "pages/orders/view_orders_delivered.php";
			
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