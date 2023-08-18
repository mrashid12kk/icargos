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


        <div class="warper container-fluid" style="<?php if($_SESSION['language']!='english'){?>direction: rtl;text-align: right;<?php }?>">

            <!-- <div class="page-header"><h1>Dashboard <small>Let's get a quick overview...</small></h1></div> -->

            <?php

			include "pages/orders/order_processing.php";

			?>


        </div>
        <!-- Warper Ends Here (working area) -->


      <?php include "includes/footer.php";

  	}else{
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
		});
    	var mydata = [];
		$('body').on('click','.update_status',function(e){
			e.preventDefault();
			if($('.status_list').val() ==''){
				alert('Please choose status');
				return false;
			}
			if($('.status_update_run').val() ==''){
				alert('Please add order trackings');
				return false;
			}

			if($('.status_list').find(':selected').attr('data-reasonenable') == 1)
			{
				$('.reason_enable').val($('.reason_list').find(':selected').attr('data-valuestat'));
			}else{
				$('.reason_enable').val('');
			}

			var order_data = $('.status_update_run').val();
	        $('#print_data').val(order_data);
	        $('#bulk_status_assign').submit();
		})
    </script>

<script type="text/javascript">
        	$(document).on('click','.edit_weight',function(){
        		var track_no=$(this).attr('data-trackno');
        		$.ajax({
				type:'POST',
				data:{orderprocessing:6,track_no:track_no},

				url:'ajax.php',
				success:function(response){
					$(".list").html("");
					$(".list").append(response);
					console.log(response);
				}
				});
        	})
        </script>

      <script>
document.addEventListener('DOMContentLoaded', function(){
  $('title').text($('title').text()+' Order Processing')
}, false);



	$('body').on('keyup','.weight',function(e){
  			e.preventDefault();
  			execute();
  		})
	function execute(){
		$('.submit_btns').attr('disabled', 'disabled');
		var origin 		= $(".origin").val();
		// $('.origin_branch_id').val(origin);
		var destination = $(".destination").val();
		var order_type  = $('.order_type').val();
		var weight 		= $('.weight').val();
		var gst 		= $('.total_gst').val();
		var customer_id = $('.customer_id').val();
		var gst = $('.total_gst').val();
		$.ajax({
		    url: 'pages/booking/booking_form.php',
		    type: 'POST',
		    data: {settle:1,origin:origin,destination:destination,weight:weight,order_type:order_type,customer_id:customer_id},
		    success: function (data) {
		    	var delivery_rate = Number(data);
		    	$('.total_amount').val(delivery_rate.toFixed(2));
		    	$('.total_amount_hidden').val(delivery_rate.toFixed(2));
		    	var excl_amount = delivery_rate;
		    	var pft_amount = (excl_amount/100)*gst;
		    	var incl_amount = excl_amount+pft_amount;
		    	$('.excl_amount').val(excl_amount.toFixed(2));
		    	$('.pft_amount').val(pft_amount.toFixed(2));
		    	$('.inc_amount').val(incl_amount.toFixed(2));
		    	$('.submit_btns').removeAttr('disabled');
		    },
		    complete: function(){

		    	// var delivery_rate = $('.total_amount_hidden').val();
		    	// $('.total_amount').val(delivery_rate.toFixed(2));
		    }
		});
	}
</script>
<script type="text/javascript">


    document.addEventListener('DOMContentLoaded', function(){
        $(document).on('submit','#change_weight_from',function(event){
            event.preventDefault();
            var inc_amount=$('.inc_amount').val();
            var track_no=$('.track_no').val();
            var weight=$('.weight').val();
            var delivery_charges=$('.delivery_charges').val();
            var pft_amount=$('.pft_amount').val();
            var status=$('.status').val();
            var track=$('.data-track').attr('data-track');
            $("."+track_no).closest('li')[0].remove();
            $.ajax({
            url: 'ajax.php',
            type: 'POST',
            dataType: "json",
            data: {inc_amount:inc_amount,track_no:track_no,weight:weight,delivery_charges:delivery_charges,pft_amount:pft_amount,status:status},
            success: function (data) {
                // kojv.attr("display","none");
                $('#order_sts_lg').append(data.list);
				  $(".modal").removeClass("in");
				  $(".modal-backdrop").remove();
				  $('body').removeClass('modal-open');
				  $('body').css('padding-right', '');
				  $(".modal").hide();
				  $('#msg').html('');
            	$('#msg').html(data.msg);
            	console.log(data);
            },
            complete: function(){

                // var delivery_rate = $('.total_amount_hidden').val();
                // $('.total_amount').val(delivery_rate.toFixed(2));
            }
        });
        })

    }, false);


</script>
