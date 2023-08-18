<?php
	session_start();
	require 'includes/conn.php';
	if(isset($_SESSION['users_id']) &&($_SESSION['type']!=='driver')){
		 require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'],36,'view_only',$comment =null)) {

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

			include "pages/orders/my_orders.php";

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
 $(window).on('scroll', function()
     { var scrollTop = $(window).scrollTop();
     if(scrollTop > 300) {
       $('.order_info_box').css('position', 'fixed');
       $('.order_info_box').css('z-index', '999');
       $('.order_info_box').css('top', '10px');
       $('.order_info_box').css('right', '16px');
       $('.order_info_box').css('margin-top', '10px');
       $('.order_info_box').css('height', '90vh');
     }
     else {
      $('.order_info_box').css('position', 'absolute');
       $('.order_info_box').css('top', 'auto');
     }
   })

$(window).on('scroll', function() 
     { var scrollTop = $(window).scrollTop(); 
     if(scrollTop > 320) { 
       $('.cargo_banner').css('position', 'fixed'); 
       $('.cargo_banner').css('z-index', '999'); 
       $('.cargo_banner').css('top', '10px');  
       $('.cargo_banner').css('right', '16px'); 
       $('.cargo_banner').css('margin-top', '10px');
     } 
     else { 
      $('.cargo_banner').css('position', 'absolute');
       $('.cargo_banner').css('margin-top', '-6px');  
       $('.cargo_banner').css('top', 'auto');  
       $('.cargo_banner').css('right', '16px');  
     } 
   })

$(".view_detail_show").click(function(){
	var id=$(this).attr('data-id');
 	$("#"+id).toggle();
 })


 

 $('body').on('click','.close_details',function(e){
		e.preventDefault();
 	$("#view_box_detail").addClass('hidden');
 })

	$('body').on('click','.view_detail',function(e){
		e.preventDefault();
		var id=$(this).attr('data-id');
					$.ajax({
					type:'POST',
					data:{id:id,track_id:1},
					url:'ajax.php',
					success:function(response){
					$('.cargo_banner').addClass('hidden');	
					$('#view_box_detail').removeClass('hidden');
					$('.order_info_box').html('');
					$('.order_info_box').html(response);
					}
					});
		 })

	$('body').on('click','.live_tracking',function(e){
		e.preventDefault();
		var track=$(this).attr('data-track');
			$.ajax({
			type:'POST',
			data:{track:track,order_log_detail:1},
			url:'ajax.php',
			success:function(response){
				$('.cargo_banner').addClass('hidden');	
			$('#view_box_detail').removeClass('hidden');
			$('.fix_wrapper_h').html('');
			$('.fix_wrapper_h').html(response);
			}
			});
		 })
	$('body').on('click','.relaod',function(e){
		location.reload()
		 })
</script>
