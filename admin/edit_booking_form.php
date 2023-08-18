<?php
	session_start();
	require 'includes/conn.php';
	if(isset($_SESSION['users_id']) &&($_SESSION['type']!=='driver' && $_SESSION['type'] == 'admin')){
  
	include "includes/header.php";
?>
<style>
	a{
	    text-decoration: none !important;
	}
	

input::-webkit-input-placeholder,
textarea::-webkit-input-placeholder {
  color: #b8b8b8 !important;
}
input:-moz-placeholder,
textarea:-moz-placeholder {
  color: #b8b8b8 !important;
}
input::-moz-placeholder,
textarea::-moz-placeholder {
  color: #b8b8b8 !important;
}
input:-ms-input-placeholder,
textarea:-ms-input-placeholder {
  color: #b8b8b8 !important;
}
label{
	font-weight: bold;
}
.hide_city{
	display: none;
}
.btn-purple:hover,.btn-purple:focus{
	color: #fff !important;
}
.calculation_label{
	font-size: 11px !important;
}
</style>
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
	
			include "pages/booking/edit_booking_form.php";
			
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
	$('body').on('click','.update_received_status',function(e){
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
        $('#bulk_received_submit').submit();
	})
        </script>
        <script type="text/javascript">
        	$('body').on('change','.active_customer_detail',function(){
        		var customer_id = $(this).val();
        		$.ajax({
    				  type:'POST',
    				  data:{customer_id:customer_id},
    				  dataType: "json",
      				url:'getcustomer.php',
      				success:function(response){
      					$('.shipper_fname').val(response.fname);
      					$('.shipper_bname').val(response.bname);
      					$('.shipper_mob').val(response.mobile_no);
      					$('.shipper_email').val(response.email);
      					$('.shipper_address').val(response.address);
      				}
    				});
        	})
        </script>


        <script type="text/javascript">
        	var type = null;
              $('body').on('click', '.submit_order', function(e) {
              	e.preventDefault();
              	type = 'submit_order';
              	$('#booking_form [name="save_order"]').trigger('click');
              })
        	$('body').on('submit','#booking_form',function(e){
          	e.preventDefault();
          	
          	$('.submit_btns').attr('disabled', 'disabled');
	// var data = new FormData(this);
	var data ={};
	$('#booking_form').find('input,select,textarea').each(function(i) {
		if($(this).attr('name') && $(this).attr('type') != 'submit')
			data[$(this).attr('name')] = $(this).val();
	})
	var customer_id = 0;
	// var origin_branch = $('.origin_cal :selected').data('origin');
	var origin_branch = $('.origin_branch').val();

	if(type == 'submit_order'){
		data['save_order'] = 0;
		data['submit_order'] = 1;
		data['customer_id'] = customer_id;
		data['origin_branch'] = origin_branch;
	}else{
		data['save_order'] = 1;
		data['submit_order'] = 0;
		data['customer_id'] = customer_id;
		data['origin_branch'] = origin_branch;
	}
	$.ajax({
            url: 'pages/booking/booking_form.php',
            type: 'POST',
            data:data,
            cache:false,
            // headers: { "cache-control": "no-cache" },
            // processData:false,
            dataType: 'json',
            // enctype: 'multipart/form-data',
            // contentType: false,
            success: function (response) {
            	if(response) {
	            	var track_no = response.track_no;
	            	if(response.print){
	            		window.open('invoicehtml.php?id='+response.id+'&print=1&frontdesk=1','mywindow','status=1');

	            	}else{
	            		var msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Order#'+track_no+'  Booked Successfully.</div>';
	            		$('#msg').html(msg);
	            	}
	            	// $('.tracking_number').text(track_no+1);
	            	$('#booking_form').trigger("reset");
	            } else {
	            	var msg = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Unable to submit your request, Please contact Administrator!.</div>';
	            	$('#msg').html(msg);
	            }
	            $('.submit_btns').removeAttr('disabled');
	            execute();
            }
        });
})
        </script>

        <script type="text/javascript">
        	if($('.weight').length >0)
				{
					// execute();
				}

				$('body').on('change','.order_type',function(e){
					e.preventDefault();
					var val = $(this).val();
					if(val == 'overlong'){
						$('.karachi').prop('disabled', !$('.karachi').prop('disabled'));
						$('.destination_select').find('option:not(.karachi)').first().prop('selected', true);
						 $('.js-example-basic-single').select2();

					}else{ 
						$('.karachi').removeAttr('disabled');
						$('.destination_select').find('option.karachi').first().prop('selected', true);
				        $('.js-example-basic-single').select2();
					}
					execute();
				})
		$('body').on('keyup','.weight',function(e){
  			e.preventDefault();
  			execute();
  		})
	$('body').on('change','.origin_cal',function(e){
		e.preventDefault();
		execute();
	})
	$('body').on('change','.destination',function(e){
		e.preventDefault();
		execute();
	})
	function execute(){
        var body = $('body');
        body.find('.submit_btns').attr('disabled', 'disabled');
        var origin      = body.find(".origin option:selected").val();
        // $('.origin_branch_id').val(origin);
        var destination = body.find(".destination option:selected").val();
        var order_type  = body.find('.order_type option:selected').attr('data-id');
        var weight      = body.find('.weight').val();
        var gst         = body.find('.total_gst').val();
        var insurance   = parseInt(body.find('.insurance').val());
        var trade_discount  = parseInt(body.find('.trade_discount').val());
        var customer_id     = body.find('.active_customer').val();
        var gst = body.find('.total_gst').val();
        $.ajax({
            url: 'pages/booking/booking_form.php',
            type: 'POST',
            data: {settle:1,origin:origin,destination:destination,weight:weight,order_type:order_type,customer_id:customer_id},
            success: function (data) {
                var delivery_rate = Number(data);
                body.find('.total_amount').val(delivery_rate.toFixed(2));
                body.find('.total_amount_hidden').val(delivery_rate.toFixed(2));
                var excl_amount = delivery_rate;
                var pft_amount = (excl_amount/100)*gst;
                var incl_amount = excl_amount+pft_amount;
                body.find('.excl_amount').val(excl_amount.toFixed(2));
                body.find('.pft_amount').val(pft_amount.toFixed(2));
                body.find('.inc_amount').val(incl_amount.toFixed(2));
                body.find('.submit_btns').removeAttr('disabled');
                calculateCharges();
            },
              complete:function(){
                calculateCharges();
            }
        });
    }
    function calculateCharges()
    {
       var body = $('body');
       var deliverCharges  = body.find("input[name=delivery_charges]").val();
       deliverCharges = (deliverCharges && deliverCharges > 0) ? deliverCharges:0;
       var extraCharges =  body.find('.extra_charges').val();
       extraCharges = (extraCharges && extraCharges > 0) ? extraCharges:0;
       var charge_value = 0;
       if(!extraCharges){
            extraCharges=0;
       }
       var insurance_value = body.find('.insurance_value').val();
        insurance_value = (insurance_value && insurance_value > 0) ? insurance_value:0;
       if(!insurance_value)
       {
          insurance_value = 0;
       }
       var totaltarget = 0;
       var totalcharges = 0;
       $('body').find(".other_charges").each(function(){
        var otherCharges = $(this).val();
        var dataType =$(this).attr('data-type');
        totaltarget = parseFloat(totaltarget) + parseFloat(otherCharges);
      });

       totaltarget = parseFloat(totaltarget).toFixed(2);
       body.find("input[name=special_charges]").val(parseFloat(totaltarget).toFixed(2));
       totalcharges = parseFloat(totaltarget) + parseFloat(deliverCharges);
       totalcharges = parseFloat(totalcharges) + parseFloat(extraCharges);
       totalcharges = parseFloat(totalcharges) + parseFloat(insurance_value);
       body.find("input[name=total_charges]").val(parseFloat(totalcharges).toFixed(2));
       var calculation_fc = body.find('.fuel_surcharge_percentage').val();
       calculation_fc = (calculation_fc && calculation_fc > 0) ? calculation_fc:0;
       var fc_value = parseFloat(totalcharges/100 * calculation_fc).toFixed(2);
       body.find('.fuel_surcharge').val(fc_value);
       calculatingNetAmout();
       serviceCharges();
    }
    function serviceCharges()
    {
        var parent_body = $('body');
        var deliverCharges  = parent_body.find("input[name=delivery_charges]").val();
        var collection_amount  = parent_body.find("input[name=collection_amount]").val();
        var pft_amount  = parent_body.find("input[name=pft_amount]").val();
        var totalserviceCharges = parseFloat(deliverCharges)+parseFloat(collection_amount)+parseFloat(pft_amount);
        parent_body.find("input[name=inc_amount]").val(parseFloat(totalserviceCharges).toFixed(2));
    }
    function calculatingNetAmout()
    {
        var parent_body = $('body');
        var service_charge  = parent_body.find(".inc_amount").val();
        var total_charges  = parent_body.find("input[name=total_charges]").val();
        var fuel_surcharge = parent_body.find("input[name=fuel_surcharge]").val();
        var net_amount = 0;
        net_amount = parseFloat(total_charges) + parseFloat(fuel_surcharge);
        net_amount = (net_amount && net_amount > 0) ? net_amount:0;
        var excl_amount=net_amount;
        var gst         = parent_body.find('.total_gst').val();
        var pft_amount = (excl_amount/100)*gst;
        var total_net_amount =parseFloat(excl_amount) + parseFloat(pft_amount);
        parent_body.find(".pft_amount").val(parseFloat(pft_amount).toFixed(2));
        total_net_amount = (total_net_amount && total_net_amount > 0) ? total_net_amount:0;
        parent_body.find("input[name=net_amount]").val(parseFloat(total_net_amount).toFixed(2));
    }	
  </script>