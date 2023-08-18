document.addEventListener('DOMContentLoaded', function(){
	var body  = $('body');
	$('body').on('click','.edit_weight',function(){
		var weight=$(this).attr("data-id");
		var track_no=$(this).attr('data-trackno');
		var dimensional_weight=$(this).attr('data-dimension');
		var status=body.find('.data-status').html();
		body.find(".edituserweight").val(weight);
		body.find(".dimensional_weight").val(dimensional_weight);
		body.find(".editusertrackno").val(track_no);
		body.find(".status").val(status);
	});
	$('body').on('keyup','.calculate_delivery_charges',function(){
		var calculate_delivery_charges = $(this).val();
		calculate_delivery_charges = (calculate_delivery_charges && calculate_delivery_charges > 0) ? calculate_delivery_charges:0;
		rateCalculation(calculate_delivery_charges);
	});
	$('body').on('keyup','.edituserweight',function(){
		rateCalculation();
	});
	$('body').on('click','#weight_bulk_update',function(){
		var body = $('body');
		var tbody = $('body').find('.response_table_body');
		var weight = body.find('#weight_bulk_update_val').val();
		 if (weight == '' || weight < 0.1) {
	        Swal.fire({
	            position: 'bottom-end',
	            icon: 'warning',
	            title: 'Please enter a valid number.',
	            showConfirmButton: false,
	            timer: 2500
	        });
	       	return false;
	    }
		var total_weight = 0;
		var all_cn_no = [];
		tbody.find('tr').each(function(){
			if($(this).find('.all_cn_no').val())
			{
				var new_cn_no = $(this).find('.all_cn_no').val();
				all_cn_no.push(new_cn_no);
			}
		});
		if(all_cn_no.length > 0)
		{
		 	$.ajax({
		 		url:'includes/weight_calculations.php',
		 		type:'POST',
		 		data:{is_all_cn_no:1,all_cn_no:all_cn_no,weight:weight},
		 		success:function(response)
		 		{
		 			var result = jQuery.parseJSON(response);
		 			if(result.response == 1)
		 			{
			 			Swal.fire({
				            position: 'bottom-end',
				            icon: 'success',
				            title: 'Weight updated successfully',
				            showConfirmButton: false,
				            timer: 2500
				        });
						tbody.find('tr').each(function(){
							var  html = weight;
							html+=' <input type="hidden" class="hidden_weight" value="'+weight+'">';
							$(this).find('.single_weight').html(html);
							if(weight > 0)
							{
								total_weight = parseFloat(total_weight) + parseFloat(weight);
							}
						});
						$('body').find('.total_weight').val(total_weight);
		 			}
		 		}
		 	});
		}
	});
	$('body').on('click','.save_new_weight',function(){
		var tr = $(this).closest('tr');
		var tbody = $('body').find('.response_table_body');
		var weight = tr.find('.new_weight').val();
		if(weight == '' || weight < 0.1)
		{
	        Swal.fire({
	             position: 'bottom-end',
	             icon: 'warning',
	             title: 'Please enter a valid number.',
	             showConfirmButton: false,
	             timer: 2500
	           })
	       	return false;
	    }
		var track_no = tr.find('.all_cn_no').val();
		var total_weight = 0;
		rateCalculation(0,weight,track_no);
		var  html = weight;
		html+=' <input type="hidden" class="hidden_weight" value="'+weight+'">';
		tr.find('.single_weight').html(html);
		tbody.find('tr').each(function()
		{
			if($(this).find('.hidden_weight').val() > 0)
			{
				total_weight = parseFloat(total_weight) + parseFloat($(this).find('.hidden_weight').val());
			}
		});
		$('body').find('.total_weight').val(total_weight);
	});
	var rateCalculation =function(delivery_charges = 0,weight = 0,track_no = 0)
	{
		if(weight == 0)
		{
			var weight=body.find('.edituserweight').val();
		}
		if(track_no == 0)
		{
			var track_no = body.find('.track_no').val();
		}
		if(weight  == '' || weight < 0)
		{
			body.find('.viewcharges').addClass('hidden');
		}
		if(track_no)
		{
		 	$.ajax({
		 		url:'includes/weight_calculations.php',
		 		type:'POST',
		 		data:{is_calculate:1,track_no:track_no,weight:weight,delivery_charges:delivery_charges},
		 		success:function(response)
		 		{
		 			var result = jQuery.parseJSON(response);
		 			body.find('.delivery_charges').val(result.delivery_charges);
		 			body.find('.fuel_surcharge').val(parseFloat(result.fuel_surcharge).toFixed(2));
		 			body.find('.insurance_value').val(result.insured_premium);
		 			body.find('.net_amount').val(parseFloat(result.net_amount).toFixed(2));
		 			body.find('.pft_amount').val(parseFloat(result.pft_amount).toFixed(2));
		 			body.find('.special_charges').val(parseFloat(result.special_charges).toFixed(2));
		 			body.find('.total_charges').val(parseFloat(result.total_charges).toFixed(2));
					if(weight > 0)
					{
						body.find('.viewcharges').removeClass('hidden');
					}
		 		}
		 	});
		}
	}
	$('body').on('click','.update_new_value',function(event)
	{
        event.preventDefault();
        var net_amount=body.find('.net_amount').val();
        var track_no=body.find('.track_no').val();
        var dimensional_weight=body.find('.dimensional_weight').val();
        var weight=body.find('.edituserweight').val();
        var delivery_charges=body.find('.delivery_charges').val();
        var pft_amount=body.find('.pft_amount').val();
        var total_charges=body.find('.total_charges').val();
        var fuel_surcharge=body.find('.fuel_surcharge').val();
        var status=body.find('.status').val();
        var track=body.find('.data-track').attr('data-track');
        $.ajax({
	        url:'includes/weight_calculations.php',
	        type: 'POST',
	        data: {update_value:1,total_charges:total_charges,fuel_surcharge:fuel_surcharge,net_amount:net_amount,dimensional_weight:dimensional_weight,track_no:track_no,weight:weight,delivery_charges:delivery_charges,pft_amount:pft_amount,status:status},
	        success: function (response) {
	            var data = jQuery.parseJSON(response);
	            var html = '';
	            if(data.response == 1)
	            {
					html='<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button>Updated successfully </div>';

	            }
	            else
	            {
	            	html='<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button>Not updated try again! </div>';
	            }
	        	body.find('#msg').html(html);
	        	body.find('.'+track_no).find('.weight_html').html('Weight '+weight);
	        	body.find('.'+track_no).find('.edit_weight').data('id',weight);
	            body.find(".modal").hide();
				body.find(".modal").removeClass("in");
				body.find(".modal-backdrop").remove();
				body.removeClass('modal-open');
				body.css('padding-right', '');
	        }
        });
    });
}, false);
