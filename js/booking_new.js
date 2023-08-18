  var order_id_or=$('.id').val();
  // alert(order_id_or);
  $(document).ready(function () {

    // $(document).on('keyup', '.length', function() {
    //     var length = $(this).val();
    //     var height = $('body').find('.height').val();
    //     var width = $('body').find('.width').val();
    //     var total = parseFloat(width) * parseFloat(length) * parseFloat(width);
    //     var total = parseFloat(total) / 5000;
    //     $('.weight').val(total);
    //     execute();
    // });
    // $(document).on('keyup', '.width', function() {
    //     var width = $(this).val();
    //     var length = $('body').find('.length').val();
    //     var height = $('body').find('.height').val();
    //     var total = parseFloat(width) * parseFloat(length) * parseFloat(height);
    //     var total = parseFloat(total) / 5000;
    //     $('.weight').val(total);
    //     execute();
    // });
    // $(document).on('keyup', '.height', function() {
    //     var height = $(this).val();
    //     var length = $('body').find('.length').val();
    //     var width = $('body').find('.width').val();
    //     var total = parseFloat(width) * parseFloat(length) * parseFloat(height);
    //     var total = parseFloat(total) / 5000;
    //     $('.weight').val(total);
    //     execute();
    // });

    $(document).on('keyup', '.c_i_pieces', function() {
        var c_i_pieces = $(this).val();
        var c_i_price = $(this).parent().parent().parent().find(".c_i_price").val();
        var total = parseFloat(c_i_pieces) * parseFloat(c_i_price);
        $(this).parent().parent().parent().find(".c_i_hs_total").val(total);
    });
    $(document).on('keyup', '.c_i_price', function() {
        var c_i_price = $(this).val();
        var c_i_pieces = $(this).parent().parent().parent().find(".c_i_pieces").val();
        var total = parseFloat(c_i_pieces) * parseFloat(c_i_price);
        $(this).parent().parent().parent().find(".c_i_hs_total").val(total);
    });
    $(document).on('keyup', '.c_i_hs_code', function() {
        var count = $(this).val().length;
        var value = $(this).val();
        if (count == 4) {
            $(this).val(value + '.')
        }
    });

    $('body').on('click', '.btn_commercial_invoice', function(e) {
        var array_count = $('.count_array_commercial_invoice').val();
        var array_value = parseFloat(array_count) + 1;
        $('.count_array_commercial_invoice').val(array_value);
        e.preventDefault();
        var html =
        "<div class='row'><div class='col-sm-12 padd_left'><div class='form-group'><label class='calculation_label'>Discription</label><input type='text' class='form-control' name='c_i_discription[" +
        array_value +
        "]'></div></div><div class='col-sm-2 padd_left'><div class='form-group'><label class='calculation_label'>Pieces</label><input value='0' type='text' name='c_i_pieces[" +
        array_value +
        "]' class='form-control c_i_pieces'></div></div><div class='col-sm-2 padd_left'><div class='form-group'><label class='calculation_label'>Price</label><input value='0' type='text' name='c_i_price[" +
        array_value +
        "]' class='form-control c_i_price'></div></div><div class='col-sm-2 padd_left'><div class='form-group'><label class='calculation_label'>COO</label><input value='USD' type='text' name='c_i_coo[" +
        array_value +
        "]' class='form-control' value='PK'></div></div><div class='col-sm-2 padd_left'><div class='form-group'><label class='calculation_label'>HS Code</label><input value='0' type='text' name='c_i_hs_code[" +
        array_value +
        "]' value='0000.0000' class='form-control c_i_hs_code'></div></div><div class='col-sm-2 padd_left'><div class='form-group'><label class='calculation_label'>Total</label><input type='text' name='c_i_hs_total[" +
        array_value +
        "]' value='0' class='form-control c_i_hs_total'></div></div><div class='col-sm-2 padd_left'><div class='form-group '><a href='' class='btn btn-danger btn_commercial_invoice_romove' style='margin-top: 24px;margin-left: 36px;'>-</a></div></div></div></div>";
        $('body').find('.plus_commercial_invoice').append(html);
    });
    
    $(document).on('click', '.btn_commercial_invoice_romove', function(e) {
        e.preventDefault();
        $(this).parent().parent().parent().remove();
        var array_count = $('.count_array_commercial_invoice').val();
        var array_value = parseFloat(array_count) - 1;
        $('.count_array_commercial_invoice').val(array_value);
    });

    
    $(document).ready(function() {
        origin_country();
        function origin_country() {
            // alert("origin_country func")
            var active_customer_id = $('body').find('.active_customer').val();
            $.ajax({
                type: 'POST',
                data: {

                    order_id_or: order_id_or,
                    active_customer_id: active_customer_id,
                    get_origin_country: 1
                },
                url: 'ajax_internation_booking.php',
                success: function(response) {
                    // console.log(response)
                    $('.origin_cha').html(response);    
                    $('.js-example-basic-single').select2();
                    origin_state();
                }
            });
        }
        $('body').on('change', '.origin_cha', function(e) {
            e.preventDefault();
            origin_state();
        })
        function origin_state() {
            var origin_country = $('.origin_cha').val();
            var active_customer_id = $('body').find('.active_customer').val();
            $.ajax({
                type: 'POST',
                data: {

                    order_id_or: order_id_or,
                    origin_country: origin_country,
                    active_customer_id: active_customer_id,
                    get_origin_state: 1
                },
                url: 'ajax_internation_booking.php',
                success: function(response) {
                    $('.origin_state').html(response);
                    $('.js-example-basic-single').select2();
                    origin_city();
                }
            });
        }
        $('body').on('change', '.origin_state', function(e) {
            e.preventDefault();
            origin_city();
        })
        function origin_city() {
            var origin_country = $('.origin_cha').val();
            var origin_state = $('.origin_state').val();
            var active_customer_id = $('body').find('.active_customer').val();
            $.ajax({
                type: 'POST',
                data: {

                    order_id_or: order_id_or,
                    origin_country: origin_country,
                    origin_state: origin_state,
                    active_customer_id: active_customer_id,
                    get_origin_city: 1
                },
                url: 'ajax_internation_booking.php',
                success: function(response) {
                    $('.origin_city').html(response);  
                    $('.js-example-basic-single').select2();  
                }
            });
        }

    });
//origin
//destination
destination_country();
function destination_country() {
    var active_customer_id = $('body').find('.active_customer').val();
    $.ajax({
        type: 'POST',
        data: {

            order_id_or: order_id_or,
            active_customer_id: active_customer_id,
            get_destination_country: 1
        },
        url: 'ajax_internation_booking.php',
        success: function(response) { 
            $('.destination').html(response);    
            $('.js-example-basic-single').select2();
            destination_state();
        }
    });
}
$('body').on('change', '.destination', function(e) {
    e.preventDefault();
    destination_state();
})
function destination_state() {
    var destination_country = $('.destination').val();
    var active_customer_id = $('body').find('.active_customer').val();
    $.ajax({
        type: 'POST',
        data: {

            order_id_or: order_id_or,
            destination_country: destination_country,
            active_customer_id: active_customer_id,
            get_destination__state: 1
        },
        url: 'ajax_internation_booking.php',
        success: function(response) {
            $('.destination_state').html(response);
            $('.js-example-basic-single').select2();
            destination_city();
        }
    });
}
$('body').on('change', '.destination_state', function(e) {
    e.preventDefault();
    destination_city();
})
function destination_city() {
    var destination_country = $('.destination').val();
    var destination_state = $('.destination_state').val();
    var active_customer_id = $('body').find('.active_customer').val();
    $.ajax({
        type: 'POST',
        data: {

            order_id_or: order_id_or,
            destination_country: destination_country,
            destination_state: destination_state,
            active_customer_id: active_customer_id,
            get_destintion_city: 1
        },
        url: 'ajax_internation_booking.php',
        success: function(response) {
            $('.destination_city').html(response);    
            $('.js-example-basic-single').select2();
        }
    });
}
})



  // New code




  $('body').on('keyup', '.total_amount', function(e) {
    var body = $('body');
    var delivery_rate = body.find('.total_amount').val();
    var gst = body.find('.total_gst').val();

    var excl_amount = delivery_rate;
    var pft_amount = (excl_amount / 100) * gst;
    var incl_amount = parseInt(excl_amount) + pft_amount;
            //alert(excl_amount);
            body.find('.excl_amount').val(excl_amount);
            body.find('.pft_amount').val(pft_amount);
            body.find('.inc_amount').val(incl_amount);
        });


  $('body').on('change', '.order_type', function(e) {
    var body = $('body');
    e.preventDefault();
    var val = $(this).val();
    checkDestinationToShow(val);
    if (val == 'overlong') {
        body.find('.karachi').prop('disabled', !$('.karachi').prop('disabled'));
        body.find('.destination_select').find('option:not(.karachi)').first().prop('selected', true);
        body.find('.js-example-basic-single').select2();
    } else {
        body.find('.karachi').removeAttr('disabled');
        body.find('.destination_select').find('option.karachi').first().prop('selected', true);
        body.find('.js-example-basic-single').select2();
    }
    execute();
});



$('body').on('keyup', '.extra_charges', function(e) {
    e.preventDefault();
    calculateCharges();
})
$('body').on('keyup', '#charweight', function(e) {
    e.preventDefault();
    execute();
})
$('body').on('change', '.origin_city', function(e) {
    e.preventDefault();
    execute();
})

$('body').on('change', '.destination_city', function(e) {

    e.preventDefault();
    execute();
    getOriginViseArea($(this));
})



function execute() {
                // alert('ok');
    var body = $('body');
    body.find('.submit_btns').attr('disabled', 'disabled');
    var origin = body.find(".origin_city  option:selected").val();
    // $('.origin_branch_id').val(origin);
    var destination = body.find(".destination_city option:selected").val();
    // var order_type = body.find('.order_type option:selected').attr('data-id');
    var order_type = body.find('.order_type option:selected').val();
    var weight = body.find('#charweight').val();
    var gst = body.find('.total_gst').val();
    var insurance = parseInt(body.find('.insurance').val());
    var trade_discount = parseInt(body.find('.trade_discount').val());
    var customer_id = body.find('.active_customer').val();
    var product_type_id = body.find('.product_type_id option:selected').val();
    var gst = body.find('.total_gst').val();
    $.ajax({
        url: 'admin/pages/booking/booking_form_new-redesign.php',
        type: 'POST',
        data: {
            settle: 1,
            origin: origin,
            destination: destination,
            weight: weight,
            order_type: order_type,
            customer_id: customer_id,
            product_type_id:product_type_id
        },
        success: function(data) {
            var delivery_rate = data;
            body.find('.total_amount').val(delivery_rate);
            body.find('.total_amount_hidden').val(delivery_rate);
            var excl_amount = delivery_rate;
            var pft_amount = (excl_amount / 100) * gst;
            var incl_amount = excl_amount + pft_amount;
            body.find('.excl_amount').val(excl_amount);
            body.find('.pft_amount').val(pft_amount);
            body.find('.inc_amount').val(incl_amount);
            body.find('.submit_btns').removeAttr('disabled');
            calculateCharges();
        },
        complete: function() {
            calculateCharges();
        }
    });
    

 }




$(document).on('keyup', '.other_charges', function() {
    calculateCharges();
});
$(document).on('keyup', "input[name=delivery_charges]", function() {
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
                $('body').find(".other_charges").each(function() {
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
    var totalserviceCharges = parseFloat(deliverCharges) + parseFloat(collection_amount) + parseFloat(
        pft_amount);
    parent_body.find("input[name=inc_amount]").val(parseFloat(totalserviceCharges).toFixed(2));
}
$(document).on('keyup', "input[name=pft_amount]", function() {

    calculatingNetAmout();
});
$(document).on('keyup', "input[name=fuel_surcharge]", function() {
    calculatingNetAmout();
});




function calculatingNetAmout() {
    var parent_body = $('body');
    var service_charge = parent_body.find(".inc_amount").val();
    var total_charges = parent_body.find("input[name=total_charges]").val();
    var fuel_surcharge = parent_body.find("input[name=fuel_surcharge]").val();
    var feul_percent = 0;
    if (fuel_surcharge == 0 || fuel_surcharge == "") {
        fuel_surcharge = 0;
    }else{
        feul_percent = total_charges * fuel_surcharge / 100;
    }
    var pft_percent = 0;
    var pft_amount = parent_body.find("input[name=pft_amount]").val();
    if (pft_amount == 0 || pft_amount == "") {
        pft_amount = 0;
    }else{
        pft_percent = total_charges * pft_amount / 100;
    }
    var net_amount = 0;
    net_amount = parseFloat(total_charges) + parseFloat(feul_percent);
    net_amount = (net_amount && net_amount > 0) ? net_amount : 0;
    var excl_amount = net_amount;
    var total_net_amount = parseFloat(excl_amount) + parseFloat(pft_percent);
    parent_body.find(".pft_amount").val(parseFloat(pft_amount));
    total_net_amount = (total_net_amount && total_net_amount > 0) ? total_net_amount : 0;
    parent_body.find("input[name=net_amount]").val(parseFloat(total_net_amount).toFixed(2));
}


 function getOriginViseArea(value) {
    var origin = $(value).val();
    $.ajax({
        type: 'POST',
        data: {
            origin: origin,
            getoriginData: 1
        },
        url: 'ajax.php',
        success: function(response) {
            $('.origin_select').html(response);
        }
    });
}

 function checkDestinationToShow(val) {
    var active_customer_id = $('body').find('.active_customer').val();
    $.ajax({
        type: 'POST',
        data: {
            order_type_val: val,
            check_desti: 1,
            customer_id: active_customer_id
        },
        url: 'ajax.php',
        success: function(response) {
            $('.destination_select').html(response);
            getOriginViseArea($('.destination'));
        }
    });
    }


 function checkOriginAndStateToShow(val) {
    var active_customer_id = $('body').find('.active_customer').val();
    $.ajax({
        type: 'POST',
        data: {
            country_id: val,
            check_state_city: 1,
            customer_id: active_customer_id
        },
        url: 'ajax.php',
        success: function(response) {
            $('.destination_select').html(response);
        }
    });
}