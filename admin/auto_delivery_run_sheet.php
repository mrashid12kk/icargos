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
	
			include "pages/orders/auto_delivery_run_sheet.php";
			
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
        $('body').on('click','.main_select',function(e){
		var check = $(this).closest('table').find('tbody > tr > td:first-child .order_check');
		if($(this).closest('table').find('.main_select').prop("checked") == true){
			$(this).closest('table').find('tbody > tr > td:first-child .order_check').prop('checked',true);
		}else{
			$(this).closest('table').find('tbody > tr > td:first-child .order_check').prop('checked',false);
		}
		
		$(this).closest('table').find('tbody > tr > td:first-child .order_check').val();
	})
         $('body').on('click','.select_all',function(e){
		var check = $('.pickup_tbl').find('tbody > tr > td:first-child .order_check');
		if($('.select_all').prop("checked") == true){
			$('.pickup_tbl').find('tbody > tr > td:first-child .order_check').prop('checked',true);
		}else{
			$('.pickup_tbl').find('tbody > tr > td:first-child .order_check').prop('checked',false);
		}
		
		$('.orders_tbl').find('tbody > tr > td:first-child .order_check').val();
	})
        	var mydata = [];
	$('body').on('click','.update_status',function(e){
		e.preventDefault();
		$('.pickup_tbl > tbody  > tr').each(function() {
			var checkbox = $(this).find('td:first-child .order_check');
			if(checkbox.prop("checked") ==true){
				var order_id = $(checkbox).data('id');
				mydata.push(order_id);
			}
		});
		// var order_data = JSON.stringify(mydata);
        $('#print_data').val(mydata.join());
        $('#bulk_submit').submit();
	})
        </script>

    <?php 
	    if(isset($_SESSION['print_url']) && !empty($_SESSION['print_url'])) 
	    { 
	      echo "<script>window.open('".$_SESSION['print_url']."', '_blank')</script>";
	     
	      unset($_SESSION['print_url']);
	    }
  ?>