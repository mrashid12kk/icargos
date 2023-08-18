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

			include "pages/orders/delivered_order_processing.php";

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


	$(".status_update_run_for_rider").focus();
	//delivery run barcode
 	$('.status_update_run_for_rider').on('keyup change', function (e) {
            $(this).val(function (index, value) {
                return value.replace(/\n/g, ",").replace(/ /g, ",");
            });
            var order_ids = $(this).val();

            var charCount = $(this).val().split(',').length;
            if( $(this).val() == "" )  charCount=0;
            $(".orders-count").html("Orders Count: "+charCount);
            var keycode = (event.keyCode ? event.keyCode : event.which);
		if(keycode == '13'){
			 $(this).val(function (index, value) {
                return value.replace(/\n/g, ",").replace(/ /g, ",");
            });
             var users_id = "<?php echo $_SESSION['users_id'] ?>";
			$.ajax({
				type:'POST',
				data:{ tracking_status:1,order_ids:order_ids,rider_for: 'delivery',users_id:users_id },
				url:'ajax.php',
				dataType: 'JSON',
				success:function(fetch){
					if(fetch.output != ''){
						$('#order_sts_lg').html(fetch.output);
					}

					if (fetch.error === 1)
					{
						$('.update_status').hide();
					} else {
						$('.update_status').show();
					}
				}
			});
		}

    });


	var mydata = [];
	$('body').on('click','.update_status',function(e){
		e.preventDefault();

		let status_name = $(this).attr('data-value');
		$('.status_value').val(status_name);

		if($('.status_list').val() ==''){
			alert('Please choose status');
			return false;
		}
		if($('.status_update_run_for_rider').val() ==''){
			alert('Please add order trackings');
			return false;
		}

		var error  = 0;
		if( $(this).attr('data-reasonenable') == 1)
		{
			if (! $('.enable_reason').is(':visible') )
			{
				error = 1
			}

			$('.reason_enable').val($('.reason_list').find(':selected').attr('data-valuestat'));
		}else{
			$('.reason_enable').val('');
		}

		if( $(this).attr('data-signreceived') == 7)
		{
			if (! $('.receive_by_hdsh').is(':visible') )
			{
				error = 1
			}
		}else{
			$('.received_by').val('');
		}
		var order_data = $('.status_update_run_for_rider').val();
		console.log(order_data);
        $('#print_data').val(order_data);
     	if (error == 0) {
        	$('#bulk_status_assign').submit();
        }
	})

</script>
