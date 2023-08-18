(function ($) {

})(jQuery);
$(document).ready(function () {
	execute();
	setTimeout(function(){
		execute();
	 }, 1000);
	 
$(document).on('click', '.save_areas_booking', function(e) {
    e.preventDefault();
    var destination_country = $('.destination').val();
    var destination_state = $('.destination_state').val();
    var areas = $('.add_areas_booking').val();
    $('.add_areas_booking_msg').html('');
	$('.destination_city').html('');
    if (areas!='') {
        $.ajax({
            type: 'POST',
			dataType: 'json',
            data: {
                destination_country: destination_country,
                destination_state: destination_state,
                areas: areas,
                add_areas_booking: 1
            },
            url: 'ajax_internation_booking.php',
            success: function(response) {
                if(response.msg!==''){
					$('.city_msg').html(response.msg);
					$("#exampleModal .close_modal_booking").click();
				}else{
					$('.city_msg').html('');
					$('.destination_city').html(response.options);
                $("#exampleModal .close_modal_booking").click();
                $('.add_areas_booking').val('');
				}
            }
        });
    }
    else{
        $('.add_areas_booking_msg').html('Please Write Area Name');
    }
});
	$(document).on('keyup', '.length', function() {
		var length = $(this).val();
		var height = $('body').find('.height').val();
		var width = $('body').find('.width').val();
		var total = parseFloat(width) * parseFloat(length) * parseFloat(width);
		var total = parseFloat(total) / 5000;
		$('.weight').val(total);
		execute();
	});
	$(document).on('keyup', '.width', function() {
		var width = $(this).val();
		var length = $('body').find('.length').val();
		var height = $('body').find('.height').val();
		var total = parseFloat(width) * parseFloat(length) * parseFloat(height);
		var total = parseFloat(total) / 5000;
		$('.weight').val(total);
		execute();
	});
	$(document).on('keyup', '.height', function() {
		var height = $(this).val();
		var length = $('body').find('.length').val();
		var width = $('body').find('.width').val();
		var total = parseFloat(width) * parseFloat(length) * parseFloat(height);
		var total = parseFloat(total) / 5000;
		$('.weight').val(total);
		execute();
	});

	// execute();
	$(".datepicker").attr("autocomplete", "off");
	$(".datetimepicker4").attr("autocomplete", "off");
	$(".allownumericwithdecimal").on("input", function (event) {
		$(this).val($(this).val().replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1'));
		if ((event.which != 46) && (event.which < 48 || event.which > 57)) {
			event.preventDefault();
		}
	});
	var getGst = function () {
		if ($('body').find('.origin').length > 0) {
			var origin = $('body').find('.origin').find(':selected').val();
			var active_customer_id = $('body').find('#active_customer_id').val();
			$.ajax({
				type: 'POST',
				data: { origin: origin, getorigin: 1, active_customer_id: active_customer_id },
				url: 'admin/getcustomer.php',
				success: function (response) {
					$('body').find('.total_gst').val('');
					$('body').find('.total_gst').val(response);
				}
			});
		}
	}
	getGst();
	$('body').on('change', '.origin', function () {

		var origin = $('body').find('.origin').val();
		var active_customer_id = $('body').find('#active_customer_id').val();
		$.ajax({
			type: 'POST',
			data: { origin: origin, getorigin: 1, active_customer_id: active_customer_id },
			url: 'admin/getcustomer.php',
			success: function (response) {
				$('body').find('.total_gst').val('');
				$('body').find('.total_gst').val(response);
				execute();
			}
		})
	});

	$('body').on('keyup change', '.insurance_rate', function (e) {
		var delivery_rate = $('body').find('.insured_item_value').val();
		var is_fragile = $('body').find('.is_fragile').val();
		var rate = $('body').find('#insurancedata' + is_fragile).attr('data-attr');
		var pft_amount = (delivery_rate / 100) * rate;
		var pft_amount = pft_amount.toFixed(2);
		$('body').find("input[name=insured_premium]").val(0);
		$('body').find("input[name=insured_premium]").val(pft_amount);
		calculateCharges();
	})

	$('body').on('keyup', '.extra_charges', function (e) {
		e.preventDefault();
		calculateCharges();
	});
	$(document).on('click', '#rate_calculate', function () {
		ratecalculte();
	})

	function ratecalculte() {
		var origin = $(".rate_origin option:selected").val();
		var destination = $(".rate_destination option:selected").val();
		var order_type = $(".rate_service option:selected").val();
		var weight = $('.rate_weight').val();
		var gst = $('.total_gst').val();
		var product_type_id = body.find('.product_type_id option:selected').val();
		$.ajax({
			url: 'rate_calculate_action.php',
			type: 'POST',
			data: { settle: 1, origin: origin, destination: destination, weight: weight, order_type: order_type, product_type_id: product_type_id },
			success: function (data) {
				var delivery_rate = Number(data);
				var excl_amount = delivery_rate;
				var pft_amount = (excl_amount / 100) * gst;
				var incl_amount = excl_amount + pft_amount;
				$('.rate_total_amount').val(delivery_rate.toFixed(1));
				$('.rate_excl_amount').val(excl_amount.toFixed(1));
				$('.rate_pft_amount').val(pft_amount.toFixed(1));
				$('.rate_inc_amount').val(incl_amount.toFixed(1));
			}
		});
	}
	var type = null;

	$('body').on('submit', '#editbooking_form_new', function (e) {
		e.preventDefault();
		var body = $('body');
		var print_template = body.find('.print_template').val();
		body.find('.submit_btns').attr('disabled', 'disabled');
		// var data = new FormData(this);
		var data = {};
		body.find('#editbooking_form_new').find('input,select,textarea').each(function (i) {
			if ($(this).attr('name') && $(this).attr('type') != 'submit')
				data[$(this).attr('name')] = $(this).val();
		});
		if (type == 'submit_order') {
			data['save_order'] = 0;
			data['submit_order'] = 1;
		} else {
			data['save_order'] = 1;
			data['submit_order'] = 0;
		}
		$.ajax({
			url: 'edituserbooking_new.php',
			type: 'POST',
			data: data,
			cache: false,
			// headers: { "cache-control": "no-cache" },
			// processData:false,
			dataType: 'json',
			// enctype: 'multipart/form-data',
			// contentType: false,
			success: function (response) {
				if (response) {
					if (response.error) {
						var msg = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> ' + response.alert_msg + '</div>';
						body.find('.msgs').html(msg);
						body.find('.msgs').html(msg);
						body.find('.submit_btns').removeAttr('disabled');
						return false;
					}
					var track_no = response.track_no;
					if (response.print) {
						window.open(print_template + '?order_id=' + response.id + '&print=1&frontdesk=1', 'mywindow', 'width:1000,height:400 status=1');

					} else {
						var msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Order Updated Successfully.</div>';
						body.find('.msgs').html(msg);
						body.find('.msgs').html(msg);
					}
					// $('.tracking_number').text(track_no+1);
					// body.find('#booking_form').trigger("reset");
				} else {
					var msg = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Unable to submit your request, Please contact Administrator!.</div>';
					body.find('.msgs').html(msg);
					body.find('.msgs').html(msg);
				}
				$('.submit_btns').removeAttr('disabled');
				// editexecute();
			}
		});
	})
	$('body').on('submit', '#editbooking_form', function (e) {
		e.preventDefault();
		var body = $('body');
		var print_template = body.find('.print_template').val();
		body.find('.submit_btns').attr('disabled', 'disabled');
		// var data = new FormData(this);
		var data = {};
		body.find('#editbooking_form').find('input,select,textarea').each(function (i) {
			if ($(this).attr('name') && $(this).attr('type') != 'submit')
				data[$(this).attr('name')] = $(this).val();
		});
		if (type == 'submit_order') {
			data['save_order'] = 0;
			data['submit_order'] = 1;
		} else {
			data['save_order'] = 1;
			data['submit_order'] = 0;
		}
		$.ajax({
			url: 'edituserbooking.php',
			type: 'POST',
			data: data,
			cache: false,
			// headers: { "cache-control": "no-cache" },
			// processData:false,
			dataType: 'json',
			// enctype: 'multipart/form-data',
			// contentType: false,
			success: function (response) {
				if (response) {
					if (response.error) {
						var msg = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> ' + response.alert_msg + '</div>';
						body.find('.msgs').html(msg);
						body.find('.msgs').html(msg);
						body.find('.submit_btns').removeAttr('disabled');
						return false;
					}
					var track_no = response.track_no;
					if (response.print) {
						window.open(print_template + '?order_id=' + response.id + '&print=1&frontdesk=1', 'mywindow', 'width:1000,height:400 status=1');

					} else {
						var msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Order Updated Successfully.</div>';
						body.find('.msgs').html(msg);
						body.find('.msgs').html(msg);
					}
					// $('.tracking_number').text(track_no+1);
					// body.find('#booking_form').trigger("reset");
				} else {
					var msg = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Unable to submit your request, Please contact Administrator!.</div>';
					body.find('.msgs').html(msg);
					body.find('.msgs').html(msg);
				}
				$('.submit_btns').removeAttr('disabled');
				// editexecute();
			}
		});
	})
	$('body').on('change', '.order_type', function (e) {
		e.preventDefault();
		var val = $(this).val();
		if (val == 'overlong') {
			$('.karachi').prop('disabled', !$('.karachi').prop('disabled'));
			$('.destination_select').find('option:not(.karachi)').first().prop('selected', true);
			$('.js-example-basic-single').select2();

		} else {
			$('.karachi').removeAttr('disabled');
			$('.destination_select').find('option.karachi').first().prop('selected', true);
			$('.js-example-basic-single').select2();
		}
		// getGst();
		execute();
	})
	$('body').on('keyup', '#password', function (e) {
		// e.preventDefault();
		$('#password').attr('type', 'text');
		setTimeout(function () {
			$('#password').attr('type', 'password');
		}, 800);
	})
	$('body').on('click', '.view_pass', function (e) {
		if ($('#password').attr('type') == 'text') {
			$('#password').attr('type', 'password');
		} else {
			$('#password').attr('type', 'text');
		}

	});

	// $('body').on('click', '[name="submit_order"]', function(e) {
	// 	e.preventDefault();
	// 	type = 'submit_order';
	// 	$('#booking_form').submit();
	// })

	$('body').on('click', '.submit_order_new', function (e) {
		e.preventDefault();
		type = 'submit_order';
		$('#booking_form_new [name="save_order"]').trigger('click');
	});
	$('body').on('click', '.submit_order', function (e) {
		e.preventDefault();
		type = 'submit_order';
		$('#booking_form [name="save_order"]').trigger('click');
	});
	$('body').on('click', '.submit_order_edit', function (e) {
		e.preventDefault();
		type = 'submit_order';
		$('#editbooking_form [name="save_order"]').trigger('click');
	});

	$('body').on('click', '.submit_order_edit_new', function (e) {
		e.preventDefault();
		type = 'submit_order';
		$('#editbooking_form_new [name="save_order"]').trigger('click');
	});
	// $('body').find('.other_charges').val('0');
	// calculateCharges();
	$('body').on('change', '.change_charges', function () {
		if ($(this).prop("checked") == true) {
			var data_charf = $(this).attr('data-charges');
			var data_char = $(this).val();
			$('.' + data_char).val(data_charf);
			calculateCharges();
		}
		else {
			var data_char = $(this).val();
			$('.' + data_char).val('0');
			calculateCharges();
		}
	});
	$('body').on('submit', '#booking_form', function (e) {
		e.preventDefault();
		var form = $('body').find('#booking_form');
		var body = $('body');
		$('.submit_btns').attr('disabled', 'disabled');
		// var data = new FormData(this);
		var data = {};
		$('#booking_form').find('input,select,textarea').each(function (i) {
			if ($(this).attr('name') && $(this).attr('type') != 'submit')
				data[$(this).attr('name')] = $(this).val();
		})
		var destination_selected = body.find('.destination').find(':selected').val();
		var origin_selected = body.find('.origin').find(':selected').val();
		var selected_order_type = body.find('.order_type').find(':selected').val();
		var print_template = $('.print_template').val();
		if (type == 'submit_order') {
			data['save_order'] = 0;
			data['submit_order'] = 1;
		} else {
			data['save_order'] = 1;
			data['submit_order'] = 0;
		}
		$.ajax({
			url: 'booking.php',
			type: 'POST',
			data: data,
			cache: false,
			// headers: { "cache-control": "no-cache" },
			// processData:false,
			dataType: 'json',
			// enctype: 'multipart/form-data',
			// contentType: false,
			success: function (response) {
				if (response) {
					if (response.error) {
						var msg = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> ' + response.alert_msg + '</div>';
						body.find('.msgs').html(msg);
						body.find('.submit_btns').removeAttr('disabled');
						return false;
					}
					var track_no = response.track_no;
					if (response.print) {
						window.open(print_template + '?order_id=' + response.id + '&print=1&frontdesk=1&booking=1', 'mywindow', 'width:1000,height:400 status=1');
					}
					var msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Order#' + track_no + '  Booked Successfully.</div>';
					body.find('.msgs').html(msg);
					form.trigger("reset");
					form.find('[name="delivery_charges"]').val('0');
					form.find('[name="total_charges"]').val('0');
					form.find('[name="fuel_surcharge"]').val('0');
					form.find('[name="pft_amount"]').val('0');
					form.find('[name="net_amount"]').val('0');
					form.find('[name="net_amount"]').val('0');
					form.find('.insurance_value').val('0');
					form.find('[name="extra_charges"]').val('0');
					form.find('[name="special_charges"]').val('0');
					form.find('.destination').val(destination_selected);
					form.find('.origin').val(origin_selected);
					body.find('.other_charges').val('0');
					body.find(".order_type").val(selected_order_type);
					execute();
					window.location.reload();
				} else {
					var msg = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Unable to submit your request, Please contact Administrator!.</div>';
					body.find('.msgs').html(msg);
				}
				body.find('.submit_btns').removeAttr('disabled');
				execute();
			}
		});
	})

	$('body').on('submit', '#booking_form_new', function (e) {
		e.preventDefault();
		var form = $('body').find('#booking_form_new');
		var body = $('body');
		$('.submit_btns').attr('disabled', 'disabled');
		// var data = new FormData(this);
		var data = {};
		$('#booking_form_new').find('input,select,textarea').each(function (i) {
			if ($(this).attr('name') && $(this).attr('type') != 'submit')
				data[$(this).attr('name')] = $(this).val();
		})
		var destination_selected = body.find('.destination').find(':selected').val();
		var origin_selected = body.find('.origin').find(':selected').val();
		var selected_order_type = body.find('.order_type').find(':selected').val();
		var print_template = $('.print_template').val();
		if (type == 'submit_order') {
			data['save_order'] = 0;
			data['submit_order'] = 1;
		} else {
			data['save_order'] = 1;
			data['submit_order'] = 0;
		}
		$.ajax({
			url: 'booking_new.php',
			type: 'POST',
			data: data,
			cache: false,
			// headers: { "cache-control": "no-cache" },
			// processData:false,
			dataType: 'json',
			// enctype: 'multipart/form-data',
			// contentType: false,
			success: function (response) {
				if (response) {
					if (response.error) {
						var msg = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> ' + response.alert_msg + '</div>';
						body.find('.msgs').html(msg);
						body.find('.submit_btns').removeAttr('disabled');
						return false;
					}
					var track_no = response.track_no;
					if (response.print) {
						window.open(print_template + '?order_id=' + response.id + '&print=1&frontdesk=1&booking=1&airway_bill=1', 'mywindow', 'width:1000,height:400 status=1');
					}
					var msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Order#' + track_no + '  Booked Successfully.</div>';
					body.find('.msgs').html(msg);
					form.trigger("reset");
					form.find('[name="delivery_charges"]').val('0');
					form.find('[name="total_charges"]').val('0');
					form.find('[name="fuel_surcharge"]').val('0');
					form.find('[name="pft_amount"]').val('0');
					form.find('[name="net_amount"]').val('0');
					form.find('[name="net_amount"]').val('0');
					form.find('.insurance_value').val('0');
					form.find('[name="extra_charges"]').val('0');
					form.find('[name="special_charges"]').val('0');
					form.find('.destination').val(destination_selected);
					form.find('.origin').val(origin_selected);
					body.find('.other_charges').val('0');
					body.find(".order_type").val(selected_order_type);
					execute();
					window.location.reload();
				} else {
					var msg = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Unable to submit your request, Please contact Administrator!.</div>';
					body.find('.msgs').html(msg);
				}
				body.find('.submit_btns').removeAttr('disabled');
				execute();
			}
		});
	})
	$(document).on('change', '#service_type', function () {
		var service_type = $(this).val();
		var customer_id = $('body').find('#active_customer_id').val();

		if (service_type != "") {

			$.ajax({
				type: 'POST',
				dataType: 'json',
				data: { is_service_type: 1, service_type: service_type, customer_id: customer_id },
				url: 'ajax.php',
				success: function (response) {
					$('.origin').html('');
					$('.origin').html(response.origin_cities);
					$('.destination').html('');
					$('.destination').html(response.destination_cities);
					getGst();
					execute();
				},
				complete: function () {
					var origin = $('body').find('.destination').val();
					$.ajax({
						type: 'POST',
						data: { origin: origin, getoriginData: 1 },
						url: 'ajax.php',
						success: function (response) {
							$('.origin_select').html(response);
						}
					});
				}
			});
		}
	});

	$(document).on('blur', '.emaill', function () {
		var email = $(this).val();
		var email_current = $(this);
		error = $(this).parent().find("div.help-block");
		if (email != "") {
			var postdata = "action=email&email=" + email;
			$.ajax({
				type: 'POST',
				data: postdata,
				url: 'ajax.php',
				success: function (fetch) {
					error.html(fetch);
					if (error.html() !== "") {
						$(email_current).parent().addClass("has-error").addClass("has-danger");
						$('input[type="submit"]').attr('disabled', true);
						var wringmsg = 'wringmsg';
						$('.msg_email').val('');
						$('.msg_email').val(wringmsg);
						step1_submit();
					} else {
						$('input[type="submit"]').attr('disabled', false);
						$('.msg_email').val('');
						step1_submit();
					}
				}
			});
		}
	});
	$(".allownumericwithoutdecimal").on("keypress keyup blur", function (event) {
		$(this).val($(this).val().replace(/[^\d].+/, ""));
		if ((event.which < 48 || event.which > 57)) {
			event.preventDefault();
		}
	});

	$('body').on('keypress keyup', '.weight', function (e) {
		execute();
	})
	$('body').on('change', '.origin', function (e) {
		e.preventDefault();
		execute();
	})
	$('body').on('change', '.destination', function (e) {
		e.preventDefault();
		execute();
	});
	$('body').on('change', '.product_type_id', function (e) {
		e.preventDefault();
		execute();
	});
	function execute() {
		var body = $('body');
		body.find('.submit_btns').attr('disabled', 'disabled');
		var origin = body.find(".origin option:selected").val();
		var destination = body.find(".destination option:selected").val();
		var order_type = body.find(".order_type option:selected").val();
		var weight = body.find('.weight').val();
		var gst = body.find('.total_gst').val();
		var product_type_id = body.find('.product_type_id option:selected').val();
		var booking_type = body.find('.booking_type').val();
		let int_booking = booking_type == "international" ? 1 : 0;
		var url  = 'booking.php';
		if(int_booking==1){
			url  = 'booking_new.php';
		}
		$.ajax({
			url: url,
			type: 'POST',
			data: { settle: 1, origin: origin, destination: destination, weight: weight, order_type: order_type, product_type_id: product_type_id },
			success: function (data) {
				var delivery_rate = Number(data);
				var excl_amount = delivery_rate;
				var pft_amount = (excl_amount / 100) * gst;
				var incl_amount = excl_amount + pft_amount;
				body.find('.total_amount').val(delivery_rate.toFixed(2));
				body.find('.excl_amount').val(excl_amount.toFixed(2));
				body.find('.pft_amount').val(pft_amount.toFixed(2));
				body.find('.inc_amount').val(incl_amount.toFixed(2));
				body.find('.submit_btns').removeAttr('disabled');
				calculateCharges();
			}
		});
	}
	$(document).on('keyup', '.other_charges', function () {
		calculateCharges();
	});
	$(document).on('keyup', "input[name=delivery_charges]", function () {
		calculateCharges();
	});
	function calculateCharges() {
		var body = $('body');
		var deliverCharges = body.find("input[name=delivery_charges]").val();
		deliverCharges = (deliverCharges && deliverCharges > 0) ? deliverCharges : 0;
		var extraCharges = body.find('.extra_charges').val();
		extraCharges = (extraCharges && extraCharges > 0) ? extraCharges : 0;
		var charge_value = 0;
		if (!extraCharges) {
			extraCharges = 0;
		}
		var insurance_value = body.find('.insurance_value').val();
		insurance_value = (insurance_value && insurance_value > 0) ? insurance_value : 0;
		if (!insurance_value) {
			insurance_value = 0;
		}
		var totaltarget = 0;
		var totalcharges = 0;
		$('body').find(".other_charges").each(function () {
			var otherCharges = $(this).val();
			var dataType = $(this).attr('data-type');
			totaltarget = parseFloat(totaltarget) + parseFloat(otherCharges);
		});

		totaltarget = parseFloat(totaltarget).toFixed(2);
		body.find("input[name=special_charges]").val(parseFloat(totaltarget).toFixed(2));
		totalcharges = parseFloat(totaltarget) + parseFloat(deliverCharges);
		totalcharges = parseFloat(totalcharges) + parseFloat(extraCharges);
		totalcharges = parseFloat(totalcharges) + parseFloat(insurance_value);
		body.find("input[name=total_charges]").val(parseFloat(totalcharges).toFixed(2));
		var calculation_fc = body.find('.fuel_surcharge_percentage').val();
		calculation_fc = (calculation_fc && calculation_fc > 0) ? calculation_fc : 0;
		var fc_value = parseFloat(totalcharges / 100 * calculation_fc).toFixed(2);
		body.find('.fuel_surcharge').val(fc_value);
		calculatingNetAmout();
		serviceCharges();
	}
	function serviceCharges() {
		var parent_body = $('body');
		var deliverCharges = parent_body.find("input[name=delivery_charges]").val();
		var collection_amount = parent_body.find("input[name=collection_amount]").val();
		var pft_amount = parent_body.find("input[name=pft_amount]").val();
		var totalserviceCharges = parseFloat(deliverCharges) + parseFloat(collection_amount) + parseFloat(pft_amount);
		parent_body.find("input[name=inc_amount]").val(parseFloat(totalserviceCharges).toFixed(2));
	}
	function calculatingNetAmout() {
		var parent_body = $('body');
		var service_charge = parent_body.find(".inc_amount").val();
		var total_charges = parent_body.find("input[name=total_charges]").val();
		var fuel_surcharge = parent_body.find("input[name=fuel_surcharge]").val();
		var net_amount = 0;
		net_amount = parseFloat(total_charges) + parseFloat(fuel_surcharge);
		net_amount = (net_amount && net_amount > 0) ? net_amount : 0;
		var excl_amount = net_amount;
		var gst = parent_body.find('.total_gst').val();
		var pft_amount = (excl_amount / 100) * gst;
		var total_net_amount = parseFloat(excl_amount) + parseFloat(pft_amount);
		parent_body.find(".pft_amount").val(parseFloat(pft_amount).toFixed(2));
		total_net_amount = (total_net_amount && total_net_amount > 0) ? total_net_amount : 0;
		parent_body.find("input[name=net_amount]").val(parseFloat(total_net_amount).toFixed(2));
	}
	// function editexecute(){
	// 	$('.submit_btns').attr('disabled', 'disabled');
	// 	var origin = $(".origin option:selected").val();
	// 	var destination = $(".destination option:selected").val();
	// 	var order_type = $(".order_type option:selected").val();
	// 	var weight = $('.weight').val();
	// 	var gst = $('.total_gst').val();

	// 	$.ajax({
	// 	    url: 'booking.php',
	// 	    type: 'POST',
	// 	    data: {settle:1,origin:origin,destination:destination,weight:weight,order_type:order_type},
	// 	    success: function (data) {

	// 	    	var delivery_rate = Number(data);
	// 	    	var excl_amount = delivery_rate;
	// 	    	var pft_amount = (excl_amount/100)*gst;
	// 	    	var incl_amount = excl_amount+pft_amount;
	// 	    	$('.total_amount').val(delivery_rate.toFixed(1));
	// 	    	$('.excl_amount').val(excl_amount.toFixed(1));
	// 	    	$('.pft_amount').val(pft_amount.toFixed(1));
	// 	    	$('.inc_amount').val(incl_amount.toFixed(1));
	// 	    	$('.submit_btns').removeAttr('disabled');
	// 	    }
	// 	});
	// }
	$(window).on('load', function () {
		// $('#preloader img').delay(200).animate({
		// 	'right': '43%'
		// }, 500);
		setTimeout(function () {
			$('#preloader').hide();
			// $('.navbar-logo').animate({
			// 	'left':  '0px'
			// }, 1200);
		}, 600)

	});
	$('body').on('change', '[name="payment_type"]', function (e) {
		method = $(this).val();
		if (method == 'CASH') {
			$(".cash_by").show();
		}
		else {
			$(".cash_by").hide();
		}
	});
	$('.editp').click(function () {
		if ($('.email_errorr').html() == '' && $('.emaill').val() != '') {
			$('.validateform').unbind('submit').submit();
		}
	});
	$(document).on('change', '#selpickup_alternative', function () {
		address2 = $(this).val();
		geocode(address2);
	});
	$(document).on('blur', '#delivery_address', function (e) {
		delivery_address = $(this).val();
		pickup_location = $("#pickup_location").val();

	});
	$(document).on('keyup', '#pickup_location', function (e) {
		address2 = $(this).val();
		if (e.which == 13) {
			geocode(address2);
		}
		$("#pickup_alternative").val(address2);
		$("#delivery_address").val(address2);
	});
	$(document).on('keyup', '#pickup_location', function (e) {
		address2 = $(this).val();
		if (e.which == 13) {
			geocode(address2);
		}
		$("#pickup_alternative").val(address2);
		$("#delivery_address").val(address2);
	});
	$(document).on('change', '#bussiness_type1', function () {
		if ($(this).val() == 'In-Home Business' || $(this).val() == 'Shop') {
			$('#paddress').hide();
		}
		else {
			$('#paddress').show();
		}
		if ($(this).val() == 'Shop') {
			$('#license').show();
			$('#landline_no').show();
			// $('#paddress').hide();
			$('#fname').hide();
			$('#fam_name').hide();

		}
		else {
			$('#license').hide();
			$('#landline_no').hide();
			// $('#paddress').show();
			$('#fname').show();
			$('#fam_name').show();

		}
		if ($(this).val() == 'Personal') {
			$('#bname').hide();
			$('#btype').hide();
			$('#bank_tranfer').hide();
			$('#baddress').hide();
			$('#bank_tranfe').hide();
		}
		else {
			$('#bname').show();
			$('#btype').show();
			$('#bank_tranfer').show();
			$('#baddress').show();
			$('#bank_tranfe').show();

		}
	});
	$(document).on('change', '#bussiness_type1_new', function () {
		if ($(this).val() == 'Shop') {
			$('#license1').show();
		}
		else {
			$('#license1').hide();

		}
	});
	$(document).on('change', '#payment_method', function () {
		if ($(this).val() == 'Bank Tranfer' || $(this).val() == 'Cheque') {
			$('#bank_tranfer').show();
			$('#cheque').hide();
		}
		else {
			$('#bank_tranfer').hide();
			$('#cheque').show();
		}
	});
	$(document).on('click', '#add_more', function () {
		$(this).parent().append('<label for="pwd">Business Address(Area,City,Country):</label><textarea class="form-control" onkeypress="apply_autocomplete(this);" name="address[]" ></textarea>');
	});

	var latitudee = 0, longitudee = 0;
	$(document).on('blur', '#latitude', function () {
		latitudee = parseFloat($(this).val());
		if (latitudee !== 0 && longitudee !== 0) {
			latlng = { lat: latitudee, lng: longitudee };
			geocodePosition(latlng);
		}

	})
	$(document).on('blur', '#longitude', function () {
		longitudee = parseFloat($(this).val());
		if (latitudee !== 0 && longitudee !== 0) {
			latlng = { lat: latitudee, lng: longitudee };
			geocodePosition(latlng);
		}
	});

	// Jawad Work Start
	function charts() {
		// alert("hello")
		var orderElement = $('#orderChart');
		var paymentElement = $('#paymentChart');
		var labels = [];
		var data = [];
		var colors = [];
		var orders = orderElement.parent().find('input[type="hidden"]');
		orders.each(function (index) {
			labels[index] = $(this).attr('name');
			data[index] = $(this).val().split('_')[0];
			colors[index] = $(this).val().split('_')[1];
		});
		var orderData = {
			labels: labels,
			datasets: [{
				data: data,
				backgroundColor: colors,
				hoverBackgroundColor: colors
			}]
		};
		var order = new Chart(orderElement, {
			type: 'pie',
			data: orderData
		});
		var paymentlabels = [];
		var paymentDataSet = [];
		var paymentColors = [];
		var payments = paymentElement.parent().find('input[type="hidden"]');
		payments.each(function (index) {
			paymentlabels[index] = $(this).attr('name');
			paymentDataSet[index] = $(this).val().split('_')[0];
			paymentColors[index] = $(this).val().split('_')[1];
		});
		var paymentData = {
			labels: paymentlabels,
			datasets: [{
				data: paymentDataSet,
				backgroundColor: paymentColors,
				hoverBackgroundColor: paymentColors
			}]
		};
		var payment = new Chart(paymentElement, {
			type: 'pie',
			data: paymentData
		});
		// Jawad Work end
	}
});

var map, object;
var dropoff_location = '';
function initMap() {
	var j, i;
	var latitude = 12.9715987, longitude = 77.59456269999998;
	map = new google.maps.Map(document.getElementById('map-canvas'), {
		center: {
			lat: latitude,
			lng: longitude
		},
		zoom: 18
	});
	var geocoder = new google.maps.Geocoder();
	var calculate_loc_from = new google.maps.places.Autocomplete(document.getElementById('calculate_loc_from'));
	var calculate_loc_to = new google.maps.places.Autocomplete(document.getElementById('calculate_loc_to'));
	calculate_loc_from.addListener('place_changed', function () {
		var place = pickup_autocomplete.getPlace();
		if (place.address_components) {
			for (var i in place.address_components) {
				var types = place.address_components[i].types;
				if ($.inArray('locality', types) > -1 || $.inArray('political', types) > -1) {
					$('.calc_pickup_city').val(place.address_components[i].long_name);
					return;
				}
			}
		}
		var weight = $('[name="calc_weight"]').val();
		var city = $('.calc_pickup_city').val();
		//     calculatePrice(weight, city, function(price) {
		// 	$('.pricee1').text(price);
		// });
	});
	var autocomplete = new google.maps.places.Autocomplete(document.getElementById('pickup_location'));
	if ($('#pickup_alternative').length > 0) {
		var directionsService = new google.maps.DirectionsService;
		var directionsDisplay = new google.maps.DirectionsRenderer({
			draggable: true
		});
		directionsDisplay.setMap(map);
		directionsDisplay.addListener('directions_changed', function () {
			var myRoute = directionsDisplay.getDirections().routes[0].legs[0];
			console.log(directionsDisplay.getDirections());
			$('#distance').val(myRoute.distance.text)
			$(".distancee1").text(myRoute.distance.text);
			$('#pickup_alternative').val(myRoute.start_address);
			$('#delivery_address').val(myRoute.end_address);
			geocoder.geocode({ address: myRoute.start_address }, function (results, status) {
				if (status === 'OK') {
					if (results[0]) {
						if (results[0].address_components) {
							for (var i in results[0].address_components) {
								var types = results[0].address_components[i].types;
								if ($.inArray('locality', types) > -1 || $.inArray('political', types) > -1) {
									$('.pickup_city').val(results[0].address_components[i].long_name);
									return;
								}
							}
						}



					} else {
						console.log('No results found');
					}
				} else {
					console.log('Geocoder failed due to: ' + status);
				}
			});
			// computeTotalDistance(directionsDisplay.getDirections());
		});
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function (position) {
				var latlng = { lat: position.coords.latitude, lng: position.coords.longitude };
				if ($('#pickup_alternative').val() == "") {
					objecttt = { 'location': latlng };
					positionnn = latlng

				}
				else {
					objecttt = { 'address': $('#pickup_alternative').val() };

				}
				geocoder.geocode(objecttt, function (results, status) {
					if (status === 'OK') {
						if (results[0]) {
							// console.log(position);
							console.log(results[0].geometry.location);
							map.setCenter(results[0].geometry.location);
							if ($('#delivery_address').val() == "") {
								// alert("usman");
								if ($('#pickup_alternative').val() == "") {
									marker = new google.maps.Marker({
										map: map,
										draggable: true,
										position: latlng
									});
								}
								else {
									marker = new google.maps.Marker({
										map: map,
										draggable: true,
										position: results[0].geometry.location
									});
								}

								google.maps.event.addListener(marker, 'dragend', function (evt) {

									geocodePosition(marker.getPosition());
								});
							}
							else {
								calculateAndDisplayRoute(directionsService, directionsDisplay);
							}

							$('#pickup_alternative').val(results[0].formatted_address);
							// geocode(results[0].formatted_address);
							if (results[0].address_components) {

								for (var i in results[0].address_components) {
									var types = results[0].address_components[i].types;
									if ($.inArray('locality', types) > -1 || $.inArray('political', types) > -1) {
										$('.pickup_city').val(results[0].address_components[i].long_name);
										return;
									}
								}
							}


							if (results[0].geometry.viewport) {
								map.fitBounds(results[0].geometry.viewport);
								map.setZoom(7);
							} else {
								map.setCenter(results[0].geometry.location);
								map.setZoom(17);  // Why 17? Because it looks good.
							}
						} else {
							console.log('No results found');
						}
					} else {
						console.log('Geocoder failed due to: ' + status);
					}
				});
			}, function (error) {
				alert(error.message);
			});
		} else {
			// Browser doesn't support Geolocation
			alert("Browser doesn't support Geolocation");
		}
		// var autocomplete2 = new google.maps.places.Autocomplete(document.getElementById('save_address'));
		// $(document).ready(function(){
		// 	address2=$("#pickup_location").val();
		//  if(address2!==""){
		// 				geocode(address2);
		// 				// console.log(arrayy[0].lat);
		// 		   }
		// 		   if($("#multiple").val()=='on'){
		// 			   multiple($("#pick").val(),$("#del").val());
		// 		   }
		// });


		var pickup_autocomplete = new google.maps.places.Autocomplete(document.getElementById('pickup_alternative'));
		pickup_autocomplete.bindTo('bounds', map);
		pickup_autocomplete.addListener('place_changed', function () {
			var place = pickup_autocomplete.getPlace();
			if (!place.geometry) {
				// User entered the name of a Place that was not suggested and
				// pressed the Enter key, or the Place Details request failed.
				window.alert("No details available for input: '" + place.name + "'");
				return;
			}
			var breakOut = false;
			if (place.address_components) {
				for (var i in place.address_components) {
					var types = place.address_components[i].types;
					if (!breakOut) {
						if ($.inArray('locality', types) > -1 || $.inArray('political', types) > -1) {
							$('.pickup_city').val(place.address_components[i].long_name);
							breakOut = true;
						}
					}
				}
			}
			// If the place has a geometry, then present it on a map.
			var location = '';
			if (place.geometry.viewport) {
				map.fitBounds(place.geometry.viewport);
				map.setZoom(7);
			} else {
				map.setCenter(place.geometry.location);
				map.setZoom(17);  // Why 17? Because it looks good.
			}
			calculateAndDisplayRoute(directionsService, directionsDisplay);
		});
	}

	var dropoff_autocomplete = new google.maps.places.Autocomplete(document.getElementById('delivery_address'));
	dropoff_autocomplete.bindTo('bounds', map);
	dropoff_autocomplete.addListener('place_changed', function () {

		var place = dropoff_autocomplete.getPlace();
		if (!place.geometry) {
			// User entered the name of a Place that was not suggested and
			// pressed the Enter key, or the Place Details request failed.
			window.alert("No details available for input: '" + place.name + "'");
			return;
		}
		var breakOut = false;
		console.log(place);
		if (place.address_components) {
			for (var i in place.address_components) {
				var types = place.address_components[i].types;
				if (!breakOut) {
					if ($.inArray('locality', types) > -1 || $.inArray('political', types) > -1) {
						dropoff_location = place.address_components[i].long_name;
						breakOut = true;
					}
				}
			}
		}
		// If the place has a geometry, then present it on a map.
		var location = '';
		if (place.geometry.viewport) {
			map.fitBounds(place.geometry.viewport);
			map.setZoom(7);
		} else {
			map.setCenter(place.geometry.location);
			map.setZoom(17);  // Why 17? Because it looks good.
		}
		calculateAndDisplayRoute(directionsService, directionsDisplay);
	});

}
function showPosition(position) {
	console.log(showPosition);
}
function calculateAndDisplayRoute(directionsService, directionsDisplay) {
	marker.setMap(null);

	directionsService.route({
		origin: document.getElementById('pickup_alternative').value,
		destination: document.getElementById('delivery_address').value,
		travelMode: 'DRIVING'
	}, function (response, status) {
		if (status === 'OK') {
			directionsDisplay.setDirections(response);
			var myRoute = response.routes[0].legs[0];
			console.log(response);
			$('#distance').val(myRoute.distance.text)
			$(".distancee1").text(myRoute.distance.text);

			/*  var marker = new google.maps.Marker({
			   map: map,
			 });
			 marker.setPosition(myRoute.steps[0].start_location);
			 // console.log(myRoute.steps[myRoute.steps.length-1].end_location);
			 var marker = new google.maps.Marker({
			   map: map,
			 });
			 marker.setPosition(myRoute.steps[myRoute.steps.length-1].end_location); */
			} else {
			// window.alert('Directions request failed due to ' + status);
		}
		var weight = $('[name="weight"]').val();
		var city = $('.pickup_city').val();
		//     calculatePrice(weight, city, function(price) {
		// 	$('[name="price"]').val(price);
		// });
	});
}
function calculatePrice(weight, city, callback) {
	if (weight) {
		$.ajax({
			url: 'ajax.php',
			type: 'POST',
			data: { action: 'calculatePrice', weight: weight, city: city, dropoff_location: dropoff_location },
			success: function (response) {
				callback(response);
			}
		})
	}
}
function geocode(addresss) {
	var j, i;
	var geocoder = new google.maps.Geocoder();
	var latitude = 12.9715987, longitude = 77.59456269999998;
	geocoder.geocode({ 'address': addresss }, function (results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			latitude = results[0].geometry.location.lat();
			longitude = results[0].geometry.location.lng();
			map = new google.maps.Map(document.getElementById('map-canvas'), {
				center: {
					lat: latitude,
					lng: longitude
				},
				zoom: 12
			});
			var marker = new google.maps.Marker({
				draggable: true,
				position: { lat: latitude, lng: longitude },
				map: map
			});
			google.maps.event.addListener(marker, 'dragend', function (evt) {
				geocodePosition(marker.getPosition());
			});
			map.setZoom(Math.min(map.getZoom(), 16));

		}

	});



}
function apply_autocomplete(input) {
	// alert(input);
	var autocomplete = new google.maps.places.Autocomplete(input);
	// autocomplete.bindTo('bounds', map);
	return false;
}

// Create a map object and specify the DOM element for display.
function insertt(lat, lng) {
	var marker = new google.maps.Marker({
		draggable: true,
		position: new google.maps.LatLng(lat, lng),
		map: map
	});
}
function geocodePosition(pos) {
	// console.log(pos);
	var geocoder = new google.maps.Geocoder();

	geocoder.geocode({
		latLng: pos
	}, function (responses) {
		if (responses && responses.length > 0) {
			var pickup_alternative = document.getElementById('pickup_alternative');
			var delivery_address = document.getElementById('delivery_address');
			if (pickup_alternative != null) {
				pickup_alternative.value = responses[0].formatted_address;
				if (responses[0].address_components) {

					for (var i in responses[0].address_components) {
						var types = responses[0].address_components[i].types;
						if ($.inArray('locality', types) > -1 || $.inArray('political', types) > -1) {
							$('.pickup_city').val(responses[0].address_components[i].long_name);
							return;
						}
					}
				}
				// geocode( pickup_alternative.value);

			}
			// else{

			// delivery_address.value=responses[0].formatted_address;
			// geocode(delivery_address.value);

			// }
		}
	});
}
function multiple(pickup, delivery) {
	var flightPlanCoordinates = [];
	var directionsService = new google.maps.DirectionsService;
	var directionsDisplay = new google.maps.DirectionsRenderer;

	var geocoder = new google.maps.Geocoder();
	// var latitude=12.9715987,longitude=77.59456269999998;
	geocoder.geocode({ 'address': pickup }, function (results, status) {
		if (status == google.maps.GeocoderStatus.OK) {

			latitude = results[0].geometry.location.lat();
			longitude = results[0].geometry.location.lng();
			map = new google.maps.Map(document.getElementById('map-canvas'), {
				center: {
					lat: parseInt(latitude),
					lng: parseInt(longitude)
				},

				zoom: 8
			});

			var marker = new google.maps.Marker({
				// draggable: true,
				position: { lat: latitude, lng: longitude },
				map: map
			});
			var p1 = new google.maps.LatLng(latitude, longitude);
			var object1 = { lat: latitude, lng: longitude };
			flightPlanCoordinates.push(object1);
		}


	});
	// map.setZoom(Math.min(map.getZoom(),16));

	geocoder.geocode({ 'address': delivery }, function (results, status) {
		if (status == google.maps.GeocoderStatus.OK) {

			latitudee = results[0].geometry.location.lat();
			longitudee = results[0].geometry.location.lng();
			var marker = new google.maps.Marker({
				// draggable: true,
				position: { lat: latitudee, lng: longitudee },
				map: map
			});
			var object2 = { lat: latitudee, lng: longitudee };
			flightPlanCoordinates.push(object2);
			// console.log(flightPlanCoordinates);
			var p1 = new google.maps.LatLng(flightPlanCoordinates[0].lat, flightPlanCoordinates[0].lng);
			var p2 = new google.maps.LatLng(latitudee, longitudee);

			// var flightPath = new google.maps.Polyline({
			// path: flightPlanCoordinates,
			// geodesic: true,
			// strokeColor: '#FF0000',
			// strokeOpacity: 1.0,
			// strokeWeight: 2
			// });
			// flightPath.setMap(map);
			directionsDisplay.setMap(map);
			directionsService.route({
				origin: pickup,
				destination: delivery,
				travelMode: 'DRIVING'
			}, function (response, status) {
				if (status === 'OK') {
					directionsDisplay.setDirections(response);
				} else {
					window.alert('Directions request failed due to ' + status);
				}
			});
			document.getElementById('distance').value = calcDistance(p1, p2) + " km's";
			$(".distancee1").text(calcDistance(p1, p2) + " km's");
			// document.getElementById('price').value=calcDistance(p1,p2)*1;
			// alert(calcDistance(p1,p2)+" km's");
		}

	});
}
function calcDistance(p1, p2) {
	return (google.maps.geometry.spherical.computeDistanceBetween(p1, p2) / 1000).toFixed(2);
}

/* function latlng(addresss){
		var j;
	var geocoder = new google.maps.Geocoder();
	for(j=0;j<addresss.length;j++){
					(function(j){
				geocoder.geocode( {'address': addresss[j].address}, function(results, status) {
						// console.log(addresss[j].lat);
					if (status == google.maps.GeocoderStatus.OK) {
						// alert(addresss[i].address);
					addresss[j].lat = results[0].geometry.location.lat();
					addresss[j].lon = results[0].geometry.location.lng();


					}

				});
					 })(j);
			}
			return addresss;
		} */
// function map(){
/* var searchBox = new google.maps.places.SearchBox(document.getElementById('pickup_location'));
   map.controls[google.maps.ControlPosition.TOP_CENTER].push(document.getElementById('pickup_location'));
	// console.log(searchBox);
 google.maps.event.addListener(searchBox, 'places_changed', function() {
	 searchBox.set('map', null);
		var places = searchBox.getPlaces();

	 var bounds = new google.maps.LatLngBounds();
	 var i, place;
	 for (i = 0; place = places[i]; i++) {
	   (function(place) {
			  // place=document.getElementById('pickup_location');
			  // console.log(searchBox.getPlaces);
		var marker = new google.maps.Marker({
			draggable: true,
			position: place.geometry.location
		 });
		google.maps.event.addListener(marker, 'dragend', function(evt){
			geocodePosition(marker.getPosition());
		});
		 marker.bindTo('map', searchBox, 'map');
		 google.maps.event.addListener(marker, 'map_changed', function() {
		   if (!this.getMap()) {
			 this.unbindAll();
		   }
		   // place=document.getElementById('pickup_location').value;
		 }
		 );
		 bounds.extend(place.geometry.location);
		}(place));
	 }
	 map.fitBounds(bounds);
	 searchBox.set('map', map);
	 map.setZoom(Math.min(map.getZoom(),12));
	}); */

// }



$(document).ready(function () {

	// NAVIGATION MENU

	// menu icon states, opening main nav
	$('#menu-icon').click(function () {
		$(this).toggleClass('open');
		$('#menu,#menu-toggle,#page-content,#menu-overlay').toggleClass('open');
		$('#menu li,.submenu-toggle').removeClass('open');
		$('#menu li').removeClass('disabled');
	});

	// clicking on overlay closes menu
	$('#menu-overlay').click(function () {
		$('*').removeClass('open');
		$('*').removeClass('disabled');
	});

	// add child menu toggles and parent class
	$('#menu li').has('ul').addClass('parent').prepend('<div class="submenu-toggle">open</div>');

	// toggle child menus
	$('.submenu-toggle').click(function () {
		var currentToggle = $(this);
		currentToggle.parent().siblings().toggleClass('disabled');
		currentToggle.parent().toggleClass('open');
		currentToggle.toggleClass('open');
	});
});





$(document).ready(function () {
	$("#sidebar-open").click(function () {
		$(".hidden-sidebar-menu").fadeIn();
	});


	$(".close_icons i").click(function () {
		$(".profile-sidebar").fadeOut();
	});
});
