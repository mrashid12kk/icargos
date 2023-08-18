$(document).ready(function () {
	// alert('hi');

	// if($("[data-toggle=popover]").length > 0) {
	// 	$("[data-toggle=popover]").popover();
	// }
	var parseFloatFormatted = function (value) {
		if (value == undefined || value == '') {
			return value;
		}
		return parseFloat(value.toString().replaceAll(',', '').replaceAll(' ', ''));
	}
	$('body').on('change', '.addition_kg_type', function (event) {
		event.preventDefault();
		var addition_kg_type = $(this).find(':selected').val();
		if (addition_kg_type) {
			$('body').find('.addition_kg_input').show();
			$('body').find('.addition_kg_input').find('label').html(addition_kg_type);
			if (addition_kg_type == 'Additional Weight 1 kg') {
				$('body').find('.addition_kg_input').find('input').attr('name', 'other_kg');
			}
			else {
				$('body').find('.addition_kg_input').find('input').attr('name', 'additional_point_5_kg');
			}
		}
	});
	$('body').on('change', '.default_cmnt', function () {
		var com = $(".default_cmnt option:selected").text();
		$('.comment_sec').val('');
		$('.comment_sec').val(com);
	})
	$('body').on('change', '.assignzone', function (e) {
		var val = $(this).val();
		$.ajax({
			url: 'getassignpricing.php',
			data: { getpricing: 1, zone_id: val },
			type: 'POST',
			dataType: 'json',
			success: function (response) {
				json = JSON.parse(JSON.stringify(response));
				console.log(response);
				var point_5_kg = json.point_5_kg;
				var upto_1_kg = json.upto_1_kg;
				var upto_3_kg = json.upto_3_kg;
				var upto_10_kg = json.upto_10_kg;
				var other_kg = json.other_kg;
				var addition_kg_type = json.addition_kg_type;
				var additional_point_5_kg = json.additional_point_5_kg;
				$('body').find('.point_5_kg').val(point_5_kg);
				$('body').find('.upto_1_kg').val(upto_1_kg);
				$('body').find('.upto_10_kg').val(upto_10_kg);
				$('body').find('.upto_3_kg').val(json.upto_3_kg);
				$('body').find('.other_kg').val(other_kg);
				var value = '';
				var name = '';
				if (addition_kg_type && addition_kg_type == 'Additional Weight 0.5 kg') {
					value = json.additional_point_5_kg;
					name = 'additional_point_5_kg';
				}
				else {
					value = json.other_kg;
					name = 'other_kg';
				}
				$('body').find('.additional_point_5_kg').val(value);
				$('body').find('.additional_point_5_kg').attr('name', name);
				$('body').find('.addition_kg_type').val(addition_kg_type);
				return false;
			}
		});
	})
	$('body').on('change', '.order_sts', function (e) {
		e.preventDefault();
		var status = $(this).val();
		$('.pending_reson_main').hide();
		$('.returned_reson_main').hide();
		$('.other_reason_main').hide();
		$('.received_by').hide();
		alert(status)
		if (status == 'pending') {
			$('.pending_reson_main').show();
		} else if (status == 'returned') {
			$('.returned_reson_main').show();
		} else if (status == 'delivered') {
			$('.received_by').show();
		}
		$('.update_status').show();
	})
	$('body').on('change', '.other_reason', function () {
		var active = $(this).val();
		if (active == 'Other') {
			$('.other_reason_main').show();
		} else {
			$('.other_reason_main').hide();
		}
	})
	$('body').on('change', '.choose_product', function () {
		var active_row = $(this).closest('tr');
		execute(active_row);
	})
	$('body').on('change, blur, keyup', '.qty', function () {
		var active_row = $(this).closest('tr');
		execute(active_row);
	})

	$('body').on('change, blur, keyup', '.original_price', function () {
		var active_row = $(this).closest('tr');
		execute(active_row);
	})
	function execute(active_row) {
		var choose_product = active_row.find('.choose_product').val();
		var qty = $(active_row).find('.qty').val();
		var default_price = 0;
		var total_price = 0;
		$.ajax({
			url: 'flyer_sell.php',
			data: { getflayer: 1, productid: choose_product },
			type: 'POST',
			async: false,
			success: function (response) {
				default_price = response;
			}
		});
		active_row.find('.original_price').val(default_price);
		total_price = default_price * qty;
		active_row.find('.total_price').val(total_price);
	}
	//Assign Zone
	$('body').on('click', '.process_zone', function (e) {
		e.preventDefault();
		var mydata = [];
		var check = false;
		$('#basic-datatable > tbody  > tr').each(function () {
			var checkbox = $(this).find('td:first-child .order_check');
			if (checkbox.prop("checked") == true) {
				var order_id = $(checkbox).data('id');
				mydata.push(order_id);
				check = true;
			}
		});
		if (check == false) {
			alert('Please select orders to process');
			return false;
		}
		var order_data = JSON.stringify(mydata);
		$('#order_ids').val(order_data);
		if (order_data.length != 0) {
			$("#pickup_form").submit();
		}

	})
	$('body').on('click', '.assign_rider', function (e) {
		e.preventDefault();
		var mydata = [];
		var check = false;
		$('#basic-datatable > tbody  > tr').each(function () {
			var checkbox = $(this).find('td:first-child .order_check');
			if (checkbox.prop("checked") == true) {
				var order_id = $(checkbox).data('id');
				mydata.push(order_id);
				check = true;
			}
		});
		if (check == false) {
			alert('Please select orders to process');
			return false;
		}
		var rider_id = $('.rider_id').val();
		if (rider_id == 'null' || rider_id == '' || rider_id == null) {
			alert('Please select rider to process');
			return false;
		}
		$('.pickup_rider').val(rider_id);
		var order_data = JSON.stringify(mydata);
		$('#assign_orders').val(order_data);
		if (order_data.length != 0) {
			$("#assign_rider").submit();
		}

	})
	$('body').on('click', '.assign_delivery_rider', function (e) {
		e.preventDefault();
		var mydata = [];
		var check = false;
		$('#basic-datatable > tbody  > tr').each(function () {
			var checkbox = $(this).find('td:first-child .order_check');
			if (checkbox.prop("checked") == true) {
				var order_id = $(checkbox).data('id');
				mydata.push(order_id);
				check = true;
			}
		});
		if (check == false) {
			alert('Please select orders to process');
			return false;
		}
		var rider_id = $('.rider_id').val();
		if (rider_id == 'null' || rider_id == '' || rider_id == null) {
			alert('Please select rider to process');
			return false;
		}
		$('.pickup_rider').val(rider_id);
		var order_data = JSON.stringify(mydata);
		$('#assign_delivery_orders').val(order_data);
		if (order_data.length != 0) {
			$("#assign_delivery_rider").submit();
		}

	})
	$('body').on('submit', '#flyer_save', function (e) {
		var customerfiedl = $('body').find('#flyer_customer').val();
		var flyerfiedl = $('body').find('.choose_product').val();
		if (customerfiedl != null && flyerfiedl != null) {
			$('#cmsg').html('');
			//e.preventDefault();
			$("#flyer_save").submit();
		} else {
			var msg = "<div class='alert alert-danger'><button type='button' class='close' data-dismiss='alert'>X</button><strong>Unsuccessful!</strong> Customer And Flyer Field is required.</div>";
			$('#cmsg').html('');
			$('#cmsg').html(msg);
			e.preventDefault();
		}
	})

	$('body').on('click', '.btn_add', function (e) {
		e.preventDefault();
		$(".choose_product").chosen("destroy");
		var row = $('#flayer_tbl > tbody tr:first ').clone();
		row.find(".original_price").val("");
		row.find(".qty").val(1);
		row.find(".total_price").val("");
		var rowfly = $('#flayer_tbl > tbody tr:first td').clone();
		var row2 = $('#flayer_tbl > tbody tr:last').clone();
		var counter = $('#flayer_tbl > tbody tr').length;
		row.find('.triger_price,select').each(function () {
			var name = $(this).attr('name').split('[0]');
			$(this).attr('name', name[0] + '[' + counter + ']' + name[1]);
		})
		$('#flayer_tbl > tbody tr:last').remove();
		$('#flayer_tbl > tbody').append(row);
		$('#flayer_tbl > tbody tr:last td:last').append('<a   href="#" class="btn btn-danger btn_trash"><i class="fa fa-trash"></i></a>');
		$('#flayer_tbl > tbody tr:last td:last a:first').remove();
		$('#flayer_tbl > tbody').append(row2);
		$(".choose_product").chosen();
	})
	$('body').on('click', '.btn_update', function (e) {
		e.preventDefault();
		$(".choose_product").chosen("destroy");
		var row = $('#flayer_tbl > tbody tr:first ').clone();

		var rowfly = $('#flayer_tbl > tbody tr:first td').clone();
		var row2 = $('#flayer_tbl > tbody tr:last').clone();
		var counter = $('#flayer_tbl > tbody tr').length;
		row.find('.triger_price,select').each(function () {
			var name = $(this).attr('name').split('[0]');
			$(this).attr('name', name[0] + '[' + counter + ']' + name[1]);
		})
		$('#flayer_tbl > tbody tr:last').remove();
		$('#flayer_tbl > tbody').append(row);
		$('#flayer_tbl > tbody tr:last td:last').append('<a   href="#" class="btn btn-danger btn_trash"><i class="fa fa-trash"></i></a>');
		$('#flayer_tbl > tbody tr:last td:last a:first').remove();
		$('#flayer_tbl > tbody').append(row2);
		$(".choose_product").chosen();
	})
	// Flyer sell total
	$('body').on('click', '.select_all_flyer_sell', function (e) {
		var isChecked = $(this).prop('checked');
		$('#flyer_list >tbody tr').each(function (i) {
			$(this).find('.orderid').prop('checked', isChecked);
		});
		calculatebulklaedger();
	})


	$('body').on('click', '.select_all_orders', function (e) {
		var isChecked = $(this).prop('checked');
		$('#ledger_list >tbody tr').each(function (i) {
			$(this).find('.orderid').prop('checked', isChecked);
		});
		calculatebulklaedger();
	})



	$('body').on('click', '.orderid', function () {
		calculatebulklaedger();
	})
	function calculatebulklaedger() {
		var orderids = [];
		var total_cod = 0;
		var total_delivery_charges = 0;
		var total_charges = 0;
		var total_fuel_surcharge = 0;
		var total_extra_charges = 0;
		var total_insuredpremium_charges = 0;
		var total_return_cod = 0;
		var total_return_count = 0;
		var total_flyer = 0;
		var count_checked_flyer = 0;
		var count_checked_del = 0;
		var count_checked_return = 0;
		var total_cash_handling = 0;
		var total_pft = 0;
		var total_net_amount = 0;
		var body = $('body');
		$('#ledger_list > tbody  > tr').each(function () {
			var checkbox = $(this).find('td:first-child .orderid');
			if (checkbox.prop("checked") == true) {
				let cod = checkbox.attr("data-cod");
				cod = (cod) ? (cod) : 0;
				let delivery = checkbox.attr("data-delivery");
				delivery = (delivery) ? delivery : 0;
				let totalcharges = checkbox.data("totalcharges");
				totalcharges = (totalcharges) ? totalcharges : 0;

				let totalfuelsurcharge = checkbox.data("totalfuelsurharges");
				totalfuelsurcharge = (totalfuelsurcharge) ? totalfuelsurcharge : 0;
				let totalNetAmount = checkbox.data("totalnetamount");
				totalNetAmount = (totalNetAmount) ? totalNetAmount : 0;
				let extracharge = checkbox.attr("data-extracharge");
				extracharge = (extracharge) ? extracharge : 0;
				let insuredpremium = checkbox.attr("data-insuredpremium");
				insuredpremium = (insuredpremium) ? insuredpremium : 0;
				total_cod += parseFloat(cod);
				total_delivery_charges += parseFloat(delivery);
				total_charges += parseFloat(totalcharges);
				total_fuel_surcharge += parseFloat(totalfuelsurcharge);
				total_extra_charges += parseFloat(extracharge);
				total_insuredpremium_charges += parseFloat(insuredpremium);
				total_net_amount += parseFloat(totalNetAmount);
				if (checkbox.attr("data-status") == "Delivered") {

					count_checked_del = parseFloat(count_checked_del + 1);
					var pft = checkbox.attr("data-pft");
					pft = (pft) ? pft : 0;
					total_pft += parseFloat(pft);

				} else if (checkbox.attr('data-status') == 'Returned to Shipper') {
					// count_checked_del = parseFloat(count_checked_del + 1);
					var pft = checkbox.attr("data-pft");
					pft = (pft) ? pft : 0;
					total_pft += parseFloat(pft);
					total_return_cod += parseFloat(cod);
					total_return_count += parseFloat(1);
					count_checked_return = parseFloat(count_checked_return + 1);
				}
				else {
					count_checked_del = parseFloat(count_checked_del + 1);
					var pft = checkbox.attr("data-pft");
					pft = (pft) ? pft : 0;
					total_pft += parseFloat(pft);
				}
			}
		});

		$('#flyer_list > tbody  > tr').each(function () {
			var checkbox = $(this).find('td:first-child .orderid');
			if (checkbox.prop("checked") == true) {
				let flyer = checkbox.attr("data-flyer");
				flyer = (flyer) ? flyer : 0;
				total_flyer += parseFloat(flyer);
				count_checked_flyer = parseFloat(count_checked_flyer + 1);

			}
		});


		var cash_handling_fee = body.find('#cash_handling_fee_setting').val();
		cash_handling_fee = (cash_handling_fee) ? cash_handling_fee : 0;

		var total_gst_per = body.find('#total_gst').val();
		// total_gst = (total_pft) ? Number(total_pft) : 0;

		var total_cash = parseFloat(total_cod - total_return_cod);
		var total_cash_handling = (total_cash / 100) * cash_handling_fee;
		var return_fee = body.find('#return_fee_setting').val();
		return_fee = (return_fee) ? return_fee : 0;
		var total_return_fee = parseFloat(total_return_count * return_fee);
		var total_gst = 0;
		total_gst = total_pft;
		// total_gst = parseFloat((total_delivery_charges)/100 * total_gst_per);
		var cod = body.find('#ledger_list').attr("data-cod");

		if (cod == 1) {
			// var total_payable = (total_delivery_charges  - total_cod - total_gst - total_return_cod - total_flyer - total_return_fee - total_cash_handling);
			var total_payable = (total_cod - total_net_amount - total_flyer - total_return_cod - total_return_fee - total_cash_handling);
		} else {
			// var total_payable = (total_cod - total_delivery_charges - total_gst - total_return_cod - total_flyer - total_return_fee - total_cash_handling);
			var total_payable = (total_cod - total_net_amount - total_flyer - total_return_cod - total_return_fee - total_cash_handling);
		}
		var balance = body.find('.customer_balance').val();
		balance = parseFloatFormatted(balance);
		balance = (balance) ? balance : 0;
		total_payable = parseFloat(total_payable) + parseFloat(balance);
		total_payable = parseFloat(total_payable).toFixed(number_format);
		total_gst = parseFloat(total_gst).toFixed(number_format);
		total_cod = parseFloat(total_cod).toFixed(number_format);
		total_return_cod = parseFloat(total_return_cod).toFixed(number_format);
		total_delivery_charges = parseFloat(total_delivery_charges).toFixed(number_format);
		total_charges = parseFloat(total_charges).toFixed(number_format);
		total_extra_charges = parseFloat(total_extra_charges).toFixed(number_format);
		total_insuredpremium_charges = parseFloat(total_insuredpremium_charges).toFixed(number_format);
		total_flyer = parseFloat(total_flyer).toFixed(number_format);
		total_return_fee = parseFloat(total_return_fee).toFixed(number_format);
		total_cash_handling = parseFloat(total_cash_handling).toFixed(number_format);
		total_fuel_surcharge = parseFloat(total_fuel_surcharge).toFixed(number_format);
		total_net_amount = parseFloat(total_net_amount).toFixed(number_format);

		body.find('#totalCOD').text(total_cod);
		body.find('#totalDelivery').text(total_delivery_charges);
		body.find('#totalextracharges').text(total_extra_charges);
		body.find('#totalinsuredpremium').text(total_insuredpremium_charges);
		body.find('#totalFlyerSell').text(total_flyer);
		body.find('[name="total_flyer"]').val(total_flyer);
		body.find('#totalGST').text(total_gst);
		body.find('[name="total_gst"]').val(total_gst);
		body.find('#totalRETURNCHARGES').text(total_return_fee);
		body.find('#totalChashhandling').text(total_cash_handling);
		body.find('[name="chash_handling"]').val(total_cash_handling);
		body.find('#totalPayables').text(total_payable);
		body.find('#totalBalance').text(total_payable);
		body.find('[name="total_payable_price"]').val(total_payable);
		body.find('[name="total_cod"]').val(total_cod);
		body.find('[name="total_delivery"]').val(total_delivery_charges);

		body.find('[name="total_charges"]').val(total_charges);
		body.find('#totalCharges').text(total_charges);

		body.find('[name="net_amount"]').val(total_net_amount);
		body.find('#total_net_amount').text(total_net_amount);

		body.find('[name="fuel_surcharge"]').val(total_fuel_surcharge);
		body.find('#totalfuelsurcharge').text(total_fuel_surcharge);


		body.find('[name="total_extra_charges"]').val(total_extra_charges);
		body.find('[name="total_insuredpremium_charges"]').val(total_insuredpremium_charges);
		body.find('[name="total_return"]').val(total_return_cod);
		body.find('#totalReturnCod').text(total_return_cod);
		body.find('[name="total_return_fee"]').val(total_return_fee);
		body.find('#totalreturnfee').text(total_return_fee);
		body.find('[name="total_cash_handling"]').val(total_cash_handling);
		body.find('[name="total_payments"]').val(0);
		body.find('#totalBalance').text(total_payable);
		// body.find('[name="total_payments"]').val(total_payable);

		body.find('[name="count_total_del_checked"]').val(count_checked_del);
		body.find('[name="count_total_flyer_checked"]').val(count_checked_flyer);
		body.find('[name="count_total_return_checked"]').val(count_checked_return);
		calculateBalance();
	}
	$('body').on('keyup', '[name="total_payments"]', function (event) {
		event.preventDefault();
		calculateBalance();

	});
	var calculateBalance = function () {
		var body = $('body');
		var total_payable = body.find('#totalPayables').text();
		var payment = body.find('[name="total_payments"]').val();
		var balance = 0;
		if (total_payable > 0 && payment > 0) {
			balance = parseFloat(total_payable) - parseFloat(payment);
			balance = parseFloat(balance).toFixed(2);
		}
		body.find('#totalBalance').text(total_payable);
	}
	if ($('#ledger_list').length > 0) {
		calculatebulklaedger();
		calculateemployeeledger();
	}
	if ($('#employee_ledger_list').length > 0) {
		calculateemployeeledger();
	}
	$('body').on('click', '.btn_trash', function (e) {
		e.preventDefault();
		$(this).parent('td').parent('tr').remove();

	})


	function calculateemployeeledger() {
		var orderids = [];
		var total_cod = 0;
		var total_pickup_comm = 0;
		var total_delivery_comm = 0;
		total_pickup_comm = Number($('.total_pickup_comm').val());
		total_delivery_comm = Number($('.total_delivery_comm').val());
		var total_payable = Number(total_pickup_comm + total_delivery_comm);
		var balance = parseFloat($('.employee-balance').val());
		balance = (balance) ? balance : 0;
		balance = parseFloatFormatted(balance);
		total_payable = (total_payable) ? total_payable : 0;
		$('#emptotalPayable').text(total_payable.toFixed(2));
		$('[name="total_payable"]').val(total_payable);
		$('[name="total_payment"]').val((total_payable >= 0) ? total_payable : 0);

	}
	$(document).on("change", ".receivesatatus", function () {
		var id = $(this).val();
		if (id == 'Delivered' || id=='Returned to Shipper') {
			$(".receive").removeClass("hidden");
		}
		else {
			$(".receive").addClass("hidden");
		}
	});
	$(document).ready(function () {
		var id = $(".receivesatatus").val();
		if (id == 'Delivered') {
			$(".receive").removeClass("hidden");
		}
		else {
			$(".receive").addClass("hidden");
		}
	});
	$(document).on("change", ".receivesatatus", function () {
		var id = $('option:selected', this).attr('data-id');
		if (id == '1') {
			$(".reason").removeClass("hidden");
		}
		else {
			$(".reason").addClass("hidden");
		}
	});
	$(document).ready(function () {
		var id = $('option:selected', this).attr('data-id');
		if (id == '1') {
			$(".reason").removeClass("hidden");
		}
		else {
			$(".reason").addClass("hidden");
		}
	});

	$('body').on('change', '.received_sts', function () {
		$('.couriers').hide();
		var active_sts = $(this).find('option:selected').val();
		if (active_sts == 'assign') {
			$('.couriers').show();
		}
	})



	$(document).ready(function () {
		$(document.body).on('change', '.triger_price', function () {
			getTotal();
		})
	})
	function getTotal() {
		let total = 0;
		$(".total_price").each(function () {
			var tot_pric = parseFloat($(this).val());
			if (!isNaN(tot_pric)) {
				total = parseFloat(total) + tot_pric;
			}

		});

		$("#sub_total").text(total);

	}

	$(document).ready(function () {
		$(document.body).on('click', '.btn_trash', function () {
			getTotal();
		})
	})

	$(document).ready(function () {
		$(document.body).on('click', '.btn_add', function () {
			getTotal();
		})
	})
	$(document).ready(function () {
		$(document.body).on('click', '.btn_update', function () {
			getTotal();
		})
	})
	$(".delivery_run").focus();
	//delivery run barcode
	$('.delivery_run').on('keyup change', function (e) {
		$(this).val(function (index, value) {
			return value.replace(/\n/g, ",").replace(/ /g, ",");
		});

		var count_ids = $(this).val().replace(/,\s*$/, "");
		var charCount = count_ids.split(',').length;
		// var charCount = $(this).val().split(',').length;
		if ($(this).val() == "") charCount = 0;
		$(".orders-count").html("Orders Count: " + charCount);
	});

	$(".status_update_run").focus();
	//delivery run barcode
	$('.status_update_run').on('keyup change', function (e) {
		$(this).val(function (index, value) {
			return value.replace(/\n/g, ",").replace(/ /g, ",");
		});
		var order_ids = $(this).val();

		var count_ids = $(this).val().replace(/,\s*$/, "");
		var charCount = count_ids.split(',').length;
		// var charCount = $(this).val().split(',').length;
		if ($(this).val() == "") charCount = 0;
		$(".orders-count").html("Orders Count: " + charCount);
		var keycode = (event.keyCode ? event.keyCode : event.which);
		if (keycode == '13') {
			$(this).val(function (index, value) {
				return value.replace(/\n/g, ",").replace(/ /g, ",");
			});
			$.ajax({
				type: 'POST',
				data: { tracking_status: 1, order_ids: order_ids },
				url: 'ajax.php',
				success: function (fetch) {
					if (fetch != '') {
						$('#order_sts_lg').html(fetch);
					}
				}
			});
		}

	});


	var currentTimeStamp = 0;


	$('body').on('change, blur, keyup', 'input#barcodeInput', function (ev) {
		if (ev.keyCode == 13) {
			getOrderByBarCode($(this).val().trim(), function (response) {
				if (response.id)
					window.location.href = 'order.php?id=' + response.id;
				else

					return true;
			});
		} else {
			if (currentTimeStamp == 0) {
				currentTimeStamp = ev.timeStamp;
			} else {
				if (ev.timeStamp - currentTimeStamp <= 10) {
					if ($(this).val().length >= 7) {
						currentTimeStamp = 0;
						getOrderByBarCode($(this).val().trim(), function (response) {
							if (response.id)
								window.location.href = 'order.php?id=' + response.id;
							else
								return true;
						});
					}
				}
				currentTimeStamp = ev.timeStamp;
			}
		}
	});
	function getOrderByBarCode(barcode, callback) {
		$.ajax({
			url: 'orderAction.php',
			data: { action: 'getOrderByBarCode', barcode: barcode },
			type: 'POST',
			success: function (response) {
				if (response) {
					response = jQuery.parseJSON(response);
				}
				callback(response);
			}
		});
	}

	$('body').on('change', '#payment_method', function (e) {
		if ($(this).val() == 'Cash') {
			$('#bank_tranfer').hide();
		} else {
			$('#bank_tranfer').show();
		}
	})
	// $('#basic-datatable').DataTable( {
	// dom: 'Bfrtip',
	// buttons: [
	// 'copy', 'csv', 'excel', 'pdf', 'print'
	// ]
	// } );

	// $('#basic-datatable').dataTable( {
	// "scrollX": true
	// } );
	// var oTable = $('#basic-datatable').dataTable({
	// "bDestroy": true,
	// "scrollX": true
	// });
	// var oTable = $('#basic-datatable').DataTable({
	// 		scrollY:        "370px",
	//       scrollCollapse: true,
	//       // pageLength: 5,
	//       responsive: true,
	//       dom: "<'row'<'col-sm-4'l><'col-sm-4'f><'col-sm-4 text-right'B>>t<'bottom'p><'clear'>",
	//       // dom: '<"html5buttons"B>lTfgitp',
	//       buttons: [
	//           {extend: 'copy'},
	//           {extend: 'csv'},
	//           {extend: 'excel', title: 'ExampleFile'},
	//           {extend: 'pdf', title: 'ExampleFile'},
	//           {extend: 'print',
	//            customize: function (win){
	//                   $(win.document.body).addClass('white-bg');
	//                   $(win.document.body).css('font-size', '10px');
	//                   $(win.document.body).find('table')
	//                           .addClass('compact')
	//                           .css('font-size', 'inherit');
	//           }
	//           }
	//       ]
	// });
	$('.dataTable').DataTable({
		// scrollY:        "370px",
		scrollCollapse: true,
		ordering: false,
		// pageLength: 5,
		responsive: true,
		dom: "<'row'<'col-sm-4'l><'col-sm-4'f><'col-sm-4 text-right'B>>t<'bottom'p><'clear'>",
		// dom: '<"html5buttons"B>lTfgitp',
		buttons: [
			{ extend: 'copy' },
			{ extend: 'csv' },
			{ extend: 'excel', title: 'ExampleFile' },
			{ extend: 'pdf', title: 'ExampleFile' },
			{
				extend: 'print',
				customize: function (win) {
					$(win.document.body)
						.css('font-size', '10pt')
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
		]
	})


	$('.dataTable_with_sorting').DataTable({
		scrollCollapse: true,
		ordering: true,
		// pageLength: 5,
		responsive: true,
		dom: "<'row'<'col-sm-4'l><'col-sm-4'f><'col-sm-4 text-right'B>>t<'bottom'p><'clear'>",
		// dom: '<"html5buttons"B>lTfgitp',
		buttons: [
			{ extend: 'copy' },
			{ extend: 'csv' },
			{ extend: 'excel', title: 'ExampleFile' },
			{ extend: 'pdf', title: 'ExampleFile' },
			{
				extend: 'print',

				customize: function (win) {
					$(win.document.body)
						.css('font-size', '10pt')
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
		]
	})
	// console.log(oTable);
	$(document).on('keyup blur', '.user_name', function () {
		// $('.user_errorr').html();
		// $('.user_name').css("border","1px solid #0070bb");

		var user_name = $(this).val();
		var current = $(this);

		error = $(this).parent().find("div.help-block");
		if (user_name != "") {
			var postdata = "action=user_name&user_name=" + user_name;
			$.ajax({
				type: 'POST',
				data: postdata,
				url: 'ajax.php',
				success: function (fetch) {
					error.html(fetch);
					if (error.html() !== "") {
						$(current).parent().addClass("has-error").addClass("has-danger");
					}
				}
			});

		}
		// $('#incountry').val(country_name);
	});
	$(document).on('keyup blur', '.emaill', function () {
		var email = $(this).val();
		var email_current = $(this);
		error = $(this).parent().find("div.help-block");
		if (email != "") {
			var postdata = "action=email&email=" + email;
			$.ajax({
				type: 'POST',
				data: postdata,
				dataType: 'json',
				url: 'ajax.php',
				success: function (fetch) {
					error.html(fetch);
					if (fetch !== "") {
						$(email_current).parent().addClass("has-error").addClass("has-danger");
						$('.register_btn').prop('disabled', true);
					}
					else {
						$('.register_btn').prop('disabled', false);
					}
				}
			});
		}
	});
	$(document).on('click', '#gen_pdf', function (e) {
		e.preventDefault();
		$(this).parent().find('img').show();
		$(this).hide();
		thiss = $("#down_pdf");
		var html = "<table border='2px'><caption></caption>";
		html += $(this).parent().parent().find("thead").html();
		html += $(this).parent().parent().find("tbody").html();
		html += "</table>";
		var wrapper = $('.pdf').clone();
		var summary = wrapper.find('.summary').html();
		html = '<h2>Reports</h2><div>' + summary + '</div>' + html;
		// $(this).parent().find('textarea').val(html);
		// $(this).click();
		// var href =document.location.origin+'/delivery/admin/pdf.php?pdf='+html;
		// window.open(href);
		postdata = "pdf=" + html;
		$.ajax({
			type: 'POST',
			data: postdata,
			url: 'pdf.php',
			success: function (fetch) {
				thiss.parent().find('img').hide();
				thiss.show();
				thiss.attr('href', fetch);
				// thiss.attr('download',true);
				// $("#down_pdf").click();
				var href = document.location.origin + '/delivery/admin/' + fetch;
				window.open(href);

				// $("#gen_pdf").click();
			}
		});

	});


	$(document).on('click', '#assign', function () {
		$("#drivers").parent().css("display", "none");
		var postdata = "action=assign";
		$.ajax({
			type: 'POST',
			data: postdata,
			url: 'ajax.php',
			success: function (fetch) {
				$("#drivers").parent().css("display", "block");
				$("#drivers").html(fetch);
			}
		});
		$('#drivers').attr('type', 'submit');
	});
	$('.confirmpass').keyup(function () {
		$('.password_errorr').html('');
		$('.confirmpass').css("border", "1px solid #0070bb");

		var password = $('.passwordd').val();
		var confirmpass = $('.confirmpass').val();
		// alert(confirmpass);
		if (confirmpass != password) {
			$('.password_errorr').html('please enter the same password').css("color", "#a94442");
			$('.confirmpass').css({
				"border-color": " #a94442",
				"-webkit-box-shadow": "inset 0 1px 1px rgba(0, 0, 0, .075)",
				"box-shadow": "inset 0 1px 1px rgba(0, 0, 0, .075)"
			});
		}
	});
	$('.validateform').submit(function (e) {
		e.preventDefault();
	});

	$('.editp').click(function () {
		if ($('.email_errorr').html() == '' && $('.user_errorr').html() == '') {
			// alert('true');
			$('.validateform').unbind('submit').submit();
		}
	});
	$('#changepass').click(function () {
		if ($('.password_errorr').html() == '') {
			// alert('true');
			$('.validateform').unbind('submit').submit();
		}
	});
	$('#addmore').click(function () {
		$("#cities").append('<div class="form-group col-sm-6"><label  class="control-label">City Name</label><input type="text" class="form-control" name="city[]" placeholder="Enter City name" required><div class="help-block with-errors "></div></div><div class="form-group col-sm-6"><label  class="control-label">GST%</label><input type="text" class="form-control" name="gst[]" placeholder="GST" value="0" required><div class="help-block with-errors "></div></div>');
	});

	$("#pickup_locations input").each(function (i) {
		thisss = $(this);
		address = $(this).val();
		pgeocode(address, thisss);
	})
	$("#delivery_locations input").each(function (i) {
		thisss = $(this);
		address = $(this).val();
		dgeocode(address, thisss);
	})
	$(document).on('change', '#postponed', function () {
		var status = $(this).val();
		if (status == 'postponed') {
			$('#postponed_date').parent().show();
		}
		else {
			$('#postponed_date').parent().hide();
		}
	})

});

var pickup_locations = [];
var delivery_locations = [];

function pgeocode(address, thisss) {
	var geocoder = new google.maps.Geocoder();
	// var latitude=12.9715987,longitude=77.59456269999998;
	geocoder.geocode({ 'address': address }, function (results, status) {

		if (status == google.maps.GeocoderStatus.OK) {
			latitude = results[0].geometry.location.lat();
			longitude = results[0].geometry.location.lng();
			thisss.attr('data-lat', latitude);
			thisss.attr('data-lng', longitude);
			object = { address: address, lat: latitude, lng: longitude, info: thisss.attr('data-details') };
			pickup_locations.push(object);
		}
	});

}
function dgeocode(address, thisss) {
	var geocoder = new google.maps.Geocoder();
	// var latitude=12.9715987,longitude=77.59456269999998;
	geocoder.geocode({ 'address': address }, function (results, status) {

		if (status == google.maps.GeocoderStatus.OK) {
			latitude = results[0].geometry.location.lat();
			longitude = results[0].geometry.location.lng();
			thisss.attr('data-lat', latitude);
			thisss.attr('data-lng', longitude);
			object = { address: address, lat: latitude, lng: longitude, info: thisss.attr('data-details') };
			delivery_locations.push(object);
		}
	});

}
obj = {};

obj.pickup_map = function () {
	var map = new google.maps.Map(document.getElementById('map-canvas'), {
		center: {
			lat: 25,
			lng: 55
		},
		zoom: 7
	});
	var infowindow = new google.maps.InfoWindow();
	var marker, i;
	for (i = 0; i < pickup_locations.length; i++) {
		marker = new google.maps.Marker({
			position: new google.maps.LatLng(pickup_locations[i].lat, pickup_locations[i].lng),
			map: map
		});
		google.maps.event.addListener(marker, 'click', (function (marker, i) {
			return function () {
				infowindow.setContent(pickup_locations[i].info);
				infowindow.open(map, marker);
			}
		})(marker, i));
	}
}
obj.delivery_map = function () {
	var map = new google.maps.Map(document.getElementById('delivery_map'), {
		center: {
			lat: 25,
			lng: 55
		},
		zoom: 7
	});
	var infowindow = new google.maps.InfoWindow();
	var marker, i;
	for (i = 0; i < delivery_locations.length; i++) {
		marker = new google.maps.Marker({
			position: new google.maps.LatLng(delivery_locations[i].lat, delivery_locations[i].lng),
			map: map
		});
		google.maps.event.addListener(marker, 'click', (function (marker, i) {
			return function () {
				infowindow.setContent(delivery_locations[i].info);
				infowindow.open(map, marker);
			}
		})(marker, i));
	}
}
// $(document).ready(function() {
// obj.pickup_map();
// obj.delivery_map();
// alert("hell");
// charts();
// });
// $(function(){
// alert("hell");
// charts();
// });
window.onload = function () {
	obj.pickup_map();
	obj.delivery_map();
	alert("hell");
	charts();
};

function charts() {
	// alert("hello");
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
}

function assign(id) {
	$(document).ready(function () {
		$("#drivers" + id).parent().css("display", "none");
		var postdata = "action=assign";
		$.ajax({
			type: 'POST',
			data: postdata,
			url: 'ajax.php',
			success: function (fetch) {
				$("#drivers" + id).parent().css("display", "block");
				$("#drivers" + id).html(fetch);
			}
		});
		$('#assign' + id).attr('id', 'assignn' + id).attr('value', 'Assign').attr('onclick', 'submitt(' + id + ');');
		$('#assign' + id).parent().attr('id', 'assignfrom' + id);
	});
}
function submitt(id) {
	$('#assignfrom' + id).submit();


}
/********************************
Preloader
********************************/
$(function () {

	/*$('.dropdown-menu').click(function(event){
	  event.stopPropagation();
	});*/




	/********************************
	Toggle Aside Menu
	********************************/

	$(document).on('click', '.navbar-toggle', function () {

		$('aside.left-panel').toggleClass('collapsed');
	});





	/********************************
	Aside Navigation Menu
	********************************/
	$("aside.left-panel nav.navigation > ul > li:has(ul) > a").click(function () {

		if ($("aside.left-panel").hasClass('collapsed') == false || $(window).width() < 768) {


			$("aside.left-panel nav.navigation > ul > li > ul").slideUp(300);
			$("aside.left-panel nav.navigation > ul > li").removeClass('active');

			if (!$(this).next().is(":visible")) {

				$(this).next().slideToggle(300, function () { $("aside.left-panel:not(.collapsed)").getNiceScroll().resize(); });
				$(this).closest('li').addClass('active');
			}

			return false;

		}

	});



	/********************************
	popover
	********************************/
	if ($.isFunction($.fn.popover)) {
		$('.popover-btn').popover();
	}



	/********************************
	tooltip
	********************************/
	if ($.isFunction($.fn.tooltip)) {
		$('.tooltip-btn').tooltip()
	}



	/********************************
	NanoScroll - fancy scroll bar
	********************************/
	if ($.isFunction($.fn.niceScroll)) {
		$(".nicescroll").niceScroll({

			cursorcolor: '#9d9ea5',
			cursorborderradius: '0px'

		});
	}

	if ($.isFunction($.fn.niceScroll)) {
		$("aside.left-panel:not(.collapsed)").niceScroll({
			cursorcolor: '#8e909a',
			cursorborder: '0px solid #fff',
			cursoropacitymax: '0.5',
			cursorborderradius: '0px'
		});
	}




	/********************************
	Input Mask
	********************************/
	if ($.isFunction($.fn.inputmask)) {
		$(".inputmask").inputmask();
	}





	/********************************
	TagsInput
	********************************/
	if ($.isFunction($.fn.tagsinput)) {
		$('.tagsinput').tagsinput();
	}





	/********************************
	Chosen Select
	********************************/
	if ($.isFunction($.fn.chosen)) {
		$('.chosen-select').chosen();
		$('.chosen-select-deselect').chosen({ allow_single_deselect: true });
	}




	/********************************
	DateTime Picker
	********************************/
	if ($.isFunction($.fn.datetimepicker)) {
		$('#datetimepicker').datetimepicker();
		$('#datepicker').datetimepicker({ pickTime: false });
		$('#timepicker').datetimepicker({ pickDate: false });

		$('#datetimerangepicker1').datetimepicker();
		$('#datetimerangepicker2').datetimepicker();
		$("#datetimerangepicker1").on("dp.change", function (e) {
			$('#datetimerangepicker2').data("DateTimePicker").setMinDate(e.date);
		});
		$("#datetimerangepicker2").on("dp.change", function (e) {
			$('#datetimerangepicker1').data("DateTimePicker").setMaxDate(e.date);
		});
	}
	// $('#datepicker').datetimepicker();


	/********************************
	wysihtml5
	********************************/
	if ($.isFunction($.fn.wysihtml5)) {
		$('.wysihtml').wysihtml5();
	}



	/********************************
	wysihtml5
	********************************/
	if ($.isFunction($.fn.ckeditor)) {
		CKEDITOR.disableAutoInline = true;
		$('#ckeditor').ckeditor();
		$('.inlineckeditor').ckeditor();
	}









	/********************************
	Scroll To Top
	********************************/
	$('.scrollToTop').click(function () {
		$('html, body').animate({ scrollTop: 0 }, 800);
		return false;
	});

	$('body').on('click', '.edit-order', function (event) {
		event.preventDefault();
		$('.order-detail-form .form-input input').show();
		$('.order-detail-form .form-input select').show();
		;
		$('.order-detail-form .other-actions').hide();
		$('.order-detail-form .form-input span').hide();

		$('.order-detail-form .order-buttons').show();
		$('.order-detail-form .form-input .weighting').show()
		$('.order-detail-form .form-input .assign_driver').show()

		$('.order-detail-form .form-input .destination_select').select2();
	});
	$('body').on('change', '.weighting, .order_type, .destination', function (e) {
		e.preventDefault();
		var weight = $(".weight").val();
		var origin = $(".origin").val();
		origin = (origin) ? origin.trim() : '';
		var product_id = $('.product_type_id').val();
		var order_type = $('.order_type').val();

		var destination = $(".destination").val();
		destination = (destination) ? destination.trim() : '';
		var customer_id = $('.active_customer_detail').val();

		$.ajax({
			type: 'POST',
			data: { weight: weight, origin: origin, destination: destination, customer_id: customer_id, order_type: order_type, product_id: product_id },
			url: 'delivery_calculation.php',
			success: function (response) {
				$('.delivery').val(response);
			}
		});
	})
	$('body').on('click', '.order-detail-form .reset-form', function (event) {
		event.preventDefault();
		$('.order-detail-form .form-input span').show();
		$('.order-detail-form .form-input input').hide();
		$('.order-detail-form .form-input select').hide();
		$('.order-detail-form .other-actions').show();
		$('.order-detail-form .order-buttons').hide();
		$('.order-detail-form .form-input .destination_select').select2('destroy');
	});
	// Toggle Driver Detail
	$('body').on('click', '.toggle-driver-detail', function (event) {
		event.preventDefault();
		$(this).parent().find('.driver-detail').slideToggle();
		$(this).text(function (i, text) {
			return text === "Show Driver Details" ? "Hide Driver Details" : "Show Driver Details";
		});
	});
	$('body').on('click', '.reject-order', function (event) {
		event.preventDefault();
		$(this).parent().slideToggle();
		$(this).parent().parent().find('form.reason-box').slideToggle();
	});
	$('body').on('click', '.cancel-reject', function (event) {
		event.preventDefault();
		$('body').find('.reject-order').click();
	});
	$('body').on('click', '.show-driver-attach', function (event) {
		event.preventDefault();
		$(this).parent().parent().next('tr').slideToggle();
		if ($(this).text() == 'Show Attachments')
			$(this).text('Hide Attachments');
		else
			$(this).text('Show Attachments');
	});
	/********************************
	Toggle Full Screen
	********************************/
	function toggleFullScreen() {
		if ((document.fullScreenElement && document.fullScreenElement !== null) || (!document.mozFullScreen && !document.webkitIsFullScreen)) {
			if (document.documentElement.requestFullScreen) {
				document.documentElement.requestFullScreen();
			} else if (document.documentElement.mozRequestFullScreen) {
				document.documentElement.mozRequestFullScreen();
			} else if (document.documentElement.webkitRequestFullScreen) {
				document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
			}
		} else {
			if (document.cancelFullScreen) {
				document.cancelFullScreen();
			} else if (document.mozCancelFullScreen) {
				document.mozCancelFullScreen();
			} else if (document.webkitCancelFullScreen) {
				document.webkitCancelFullScreen();
			}
		}
	}
});
