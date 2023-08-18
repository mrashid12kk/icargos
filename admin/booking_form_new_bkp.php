<?php
session_start();
require 'includes/conn.php';
if (isset($_SESSION['users_id']) && ($_SESSION['type'] !== 'driver' && $_SESSION['type'] == 'admin')) {
    require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'], 8, 'add_only', $comment = null)) {

        header("location:access_denied.php");
    }
    include "includes/header.php";

    ?>
    <style>
        a {
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

        label {
            font-weight: bold;
        }

        .hide_city {
            display: none;
        }

        .btn-purple:hover,
        .btn-purple:focus {
            color: #fff !important;
        }

        .calculation_label {
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

                include "pages/booking/booking_form_new-redesign.php";

                ?>


            </div>
            <!-- Warper Ends Here (working area) -->


            <?php

            include "includes/footer.php";
        } else {
            header("location:index.php");
        }
        ?>
        <script type="text/javascript">
// origin            
$(document).ready(function() {
    origin_country();
    function origin_country() {
        var active_customer_id = $('body').find('.active_customer').val();
        $.ajax({
            type: 'POST',
            data: {
                active_customer_id: active_customer_id,
                get_origin_country: 1
            },
            url: 'ajax_internation_booking.php',
            success: function(response) {
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
//destination
$(document).ready(function() {
    $('.other_charges').val('0');
    calculateCharges();
    let val = $('.order_type').val();
    checkDestinationToShow(val);

    let country = $('.country_select').val();
    checkOriginAndStateToShow(country)
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
$(document).on('keyup', '.ci_length', function() {
    var lenght = $(this).val();
    var height = $(this).parent().parent().parent().find(".ci_height").val();
    var width = $(this).parent().parent().parent().find(".ci_width").val();
     var total = parseFloat(width) * parseFloat(length) * parseFloat(height);
    var total = parseFloat(total) / 5000;
    $(this).parent().parent().parent().find(".ci_weight").val(total);
     $( ".ci_weight" ).keyup();
});
$(document).on('keyup', '.ci_width', function() {
    var width = $(this).val();
    var length = $(this).parent().parent().parent().find(".ci_length").val();
    var height = $(this).parent().parent().parent().find(".ci_height").val();
     var total = parseFloat(width) * parseFloat(length) * parseFloat(height);
    var total = parseFloat(total) / 5000;
    $(this).parent().parent().parent().find(".ci_weight").val(total);
    $( ".ci_weight" ).keyup();
});
$(document).on('keyup', '.ci_height', function() {
    var height = $(this).val();
    var length = $(this).parent().parent().parent().find(".ci_length").val();
    var width = $(this).parent().parent().parent().find(".ci_width").val();
     var total = parseFloat(width) * parseFloat(length) * parseFloat(height);
    var total = parseFloat(total) / 5000;
    $(this).parent().parent().parent().find(".ci_weight").val(total);
     $( ".ci_weight" ).keyup();
});

$(document).on('keyup','.ci_weight',function(){
    var sum = 0;
    $('.ci_weight').each(function(i, obj) {
    var weight = $(this).val();
    sum = sum +  parseFloat(weight);
    });
    sum = sum.toFixed(4);
    $('.weight').val(sum);
    // alert(sum);

});
$('body').on('change', '.change_charges', function() {
    if ($(this).prop("checked") == true) {
        var data_charf = $(this).attr('data-charges');
        var data_char = $(this).val();
        $('.' + data_char).val(data_charf);
        $(this).closest('tr').find('.other_charges').removeAttr('disabled');
        calculateCharges();
    } else {
        var data_char = $(this).val();
        $('.' + data_char).val('0');
        calculateCharges();
    }
});
$('body').on('click', '.btn_commercial_invoice', function(e) {
    var array_count = $('.count_array_commercial_invoice').val();
    var array_value = parseFloat(array_count) + 1;
    $('.count_array_commercial_invoice').val(array_value);
    e.preventDefault();
    var html =
    "<div class='row'><div class='col-sm-6 sidegap'><div class='form-group'><label class='calculation_label'>Discription</label><input type='text' class='form-control' name='c_i_discription[" +
    array_value +
    "]'></div></div><div class='col-sm-1 sidegap'><div class='form-group'><label class='calculation_label'>Pieces</label><input value='0' type='text' name='c_i_pieces[" +
    array_value +
    "]' class='form-control c_i_pieces'></div></div><div class='col-sm-1 sidegap'><div class='form-group'><label class='calculation_label'>Price</label><input value='0' type='text' name='c_i_price[" +
    array_value +
    "]' class='form-control c_i_price'></div></div><div class='col-sm-1 sidegap'><div class='form-group'><label class='calculation_label'>COO</label><input value='USD' type='text' name='c_i_coo[" +
    array_value +
    "]' class='form-control' value='PK'></div></div><div class='col-sm-2 sidegap'><div class='form-group'><label class='calculation_label'>HS Code</label><input value='0' type='text' name='c_i_hs_code[" +
    array_value +
    "]' value='0000.0000' class='form-control c_i_hs_code'></div></div><div class='col-sm-1 sidegap'><div class='form-group'><label class='calculation_label'>Total</label><input type='text' name='c_i_hs_total[" +
    array_value +
    "]' value='0' class='form-control c_i_hs_total'></div></div><div class='col-sm-1 sidegap'><div class='form-group'><label class='calculation_label'>Length</label><input type='text' name='c_i_hs_length[" +
    array_value +
      "]' value='0' class='form-control ci_length'></div></div><div class='col-sm-1 sidegap'><div class='form-group'><label class='calculation_label'>Width</label><input type='text' name='c_i_hs_width[" +
    array_value +
      "]' value='0' class='form-control ci_width'></div></div><div class='col-sm-1 sidegap'><div class='form-group'><label class='calculation_label'>Height</label><input type='text' name='c_i_hs_height[" +
          array_value +
    "]' value='0' class='form-control ci_height'></div></div><div class='col-sm-2 sidegap'><div class='form-group'><label class='calculation_label'>Dimensional weight</label><input type='text' name='c_i_hs_dweight[" +
    array_value +
    "]' value='0' class='form-control ci_weight'></div></div><div class='col-sm-1 sidegap'><div class='form-group add_more_row '><a href='' class='btn btn-danger btn_commercial_invoice_romove' >-</a></div></div></div></div>";
    $('body').find('.plus_commercial_invoice').append(html);
});
$(document).on('click', '.save_areas_booking', function(e) {
    e.preventDefault();
    var destination_country = $('.destination').val();
    var destination_state = $('.destination_state').val();
    var areas = $('.add_areas_booking').val();
    $('.add_areas_booking_msg').html('');
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
$(document).on('click', '.btn_commercial_invoice_romove', function(e) {
    e.preventDefault();
    $(this).parent().parent().parent().remove();
    var array_count = $('.count_array_commercial_invoice').val();
    var array_value = parseFloat(array_count) - 1;
    $('.count_array_commercial_invoice').val(array_value);
});
$('body').on('change', '.manual_rates', function(e) {
    event.preventDefault();
    var element = $(this);
    var value = $(this).val();
    var is_checked = '';
    if ($(this).is(":checked")) {
        $(this).attr('checked', true);
        is_checked = 1;
        $('body').find('[name="delivery_charges"]').attr('readonly', false);
    } else {
        $(this).attr('checked', false);
        is_checked = 0;
        $('body').find('[name="delivery_charges"]').attr('readonly', true);
    }
    $(this).val(is_checked);
});
var getGst = function() {
    if ($('.origin').length > 0) {
        var origin = $('.origin').val();
        var active_customer_id = $('body').find('.active_customer').val();
        $.ajax({
            type: 'POST',
            data: {
                origin: origin,
                getorigin: 1,
                active_customer_id: active_customer_id
            },
            url: 'getcustomer.php',
            success: function(response) {
                $('.total_gst').val('');
                $('.total_gst').val(response);
                execute();
            }
        });
    }
}
getGst();
$('body').on('change', '.origin', function() {
    var origin = $('.origin').val();
    var active_customer_id = $('body').find('.active_customer').val();
    $.ajax({
        type: 'POST',
        data: {
            origin: origin,
            getorigin: 1,
            active_customer_id: active_customer_id
        },
        url: 'getcustomer.php',
        success: function(response) {
            $('.total_gst').val('');
            $('.total_gst').val(response);
            execute();
        }
    })
})
$('body').on('keyup change', '.insurance_rate', function(e) {
    var delivery_rate = $('body').find('.insured_item_value').val();
    if (delivery_rate > 500000) {
        alert('Amount should be below or equal 5 lac');
        $('body').find('.insured_item_value').val(0);
        return false;
    }
    var delivery_rate = $('body').find('.insured_item_value').val();
    var is_fragile = $('body').find('.is_fragile').val();
    var rate = $('#insurancedata' + is_fragile).attr('data-attr');
    var pft_amount = (delivery_rate / 100) * rate;
    var pft_amount = pft_amount.toFixed(2);
    $("input[name=insured_premium]").val(0);
    $("input[name=insured_premium]").val(pft_amount);
            // $('.pft_amount').val(pft_amount);
            calculateCharges();
        })
$(function() {
    $('.datetimepicker4').datetimepicker({
        format: 'YYYY/MM/DD',
    });
});
$('body').on('click', '.main_select', function(e) {
    var check = $('#basic-datatable').find('tbody > tr > td:first-child .order_check');
    if ($('.main_select').prop("checked") == true) {
        $('#basic-datatable').find('tbody > tr > td:first-child .order_check').prop('checked', true);
    } else {
        $('#basic-datatable').find('tbody > tr > td:first-child .order_check').prop('checked', false);
    }

    $('#basic-datatable').find('tbody > tr > td:first-child .order_check').val();
})
var mydata = [];
$('body').on('click', '.update_received_status', function(e) {
    e.preventDefault();
    $('#basic-datatable > tbody  > tr').each(function() {
        var checkbox = $(this).find('td:first-child .order_check');
        if (checkbox.prop("checked") == true) {
            var order_id = $(checkbox).data('id');
            mydata.push(order_id);
        }
    });
    var order_data = JSON.stringify(mydata);
    $('#print_data').val(order_data);
    $('#bulk_received_submit').submit();
})
$('body').on('change', '.active_customer_detail', function() {
    var customer_id = $(this).val();
    $.ajax({
        type: 'POST',
        data: {
            customer_id: customer_id
        },
        dataType: "json",
        url: 'getcustomer.php',
        success: function(response) {
            // alert();
            $('.shipper_fname').val(response.fname);
            $('.shipper_bname').val(response.bname);
            $('.shipper_mob').val(response.mobile_no);
            $('.shipper_email').val(response.email);
            $('.shipper_address').val(response.address);
            execute();
        }
    });
});
var type = null;
$('body').on('click', '.submit_order', function(e) {
    e.preventDefault();
    // alert('1');
    type = 'submit_order';
    $('#booking_form [name="save_order"]').trigger('click');
})
$('body').on('submit', '#booking_form', function(e) {
            // alert('hello')
            e.stopImmediatePropagation();
            e.preventDefault();
            var form = $('body').find('#booking_form');
            var body = $('body');
            $('.submit_btns').attr('disabled', 'disabled');
            // var data = new FormData(this);

            var data = {};
            body.find('#booking_form').find('input,select,textarea').each(function(i) {
                if ($(this).attr('name') && $(this).attr('type') != 'submit')
                    data[$(this).attr('name')] = $(this).val();
            })
            var customer_id = 0;
            // alert('2');
            // var origin_branch = $('.origin_cal :selected').data('origin');
            var origin_branch = $('.origin_branch').val();
            if (type == 'submit_order') {
                data['save_order'] = 0;
                data['submit_order'] = 1;
                data['customer_id'] = customer_id;
                data['origin_branch'] = origin_branch;
            } else {
                data['save_order'] = 1;
                data['submit_order'] = 0;
                data['customer_id'] = customer_id;
                data['origin_branch'] = origin_branch;
            }
            var destination_selected = body.find('.destination').find(':selected').val();
            var print_template = body.find('.print_template').val();
            $.ajax({
                url: 'pages/booking/booking_form_new.php',
                type: 'POST',
                data: data,
                cache: false,
                // headers: { "cache-control": "no-cache" },
                // processData:false,
                dataType: 'json',
                // enctype: 'multipart/form-data',
                // contentType: false,
                success: function(response) {
                    // alert(response);
                    if (response) {
                        if (response.error) {
                            var msg =
                            '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> ' +
                            response.alert_msg + '</div>';
                            body.find('#msg').html(msg);
                            body.find('.msgs').html(msg);
                            body.find('.submit_btns').removeAttr('disabled');
                            return false;
                        }
                        var track_no = response.track_no;
                        if (response.print) {
                            // alert('order_id='+response.id);
                            // form.trigger("reset");
                            window.location.href = "../../portal/admin/print.php?order_id="+response.id;
                            return 0;
                            /*

                            // https://a.icargos.com/portal/admin/print.php?order_id=786
                            window.open('../' + print_template + '?order_id=' + response.id +
                                '&print=1&frontdesk=1&booking=1&airway_bill=1', 'mywindow',
                                'width:1000,height:400 status=1');*/
                        } else {
                            var msg =
                            '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Order#' +
                            track_no + '  Booked Successfully.</div>';
                            body.find('#msg').html(msg);
                            body.find('.msgs').html(msg);
                        }
                        var msg =
                        '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Order#' +
                        track_no + '  Booked Successfully.</div>';
                        body.find('#msg').html(msg);
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
                        execute();
                        // $('.tracking_number').text(track_no+1);
                        window.location.reload();
                        return false;
                    } else {
                        var msg =
                        '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Unable to submit your request, Please contact Administrator!.</div>';
                        body.find('#msg').html(msg);
                    }
                    body.find('.submit_btns').removeAttr('disabled');
                    return false;
                    // execute();
                }
            });
        });
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
$('body').on('keyup', '.weight', function(e) {
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
                var origin = body.find(".origin_city option:selected").val();
                // $('.origin_branch_id').val(origin);
                var destination = body.find(".destination_city option:selected").val();
                // var order_type = body.find('.order_type option:selected').attr('data-id');
                var order_type = body.find('.order_type option:selected').val();
                var weight = body.find('.weight').val();
                var gst = body.find('.total_gst').val();
                var insurance = parseInt(body.find('.insurance').val());
                var trade_discount = parseInt(body.find('.trade_discount').val());
                var customer_id = body.find('.active_customer').val();
                var product_type_id = body.find('.product_type_id option:selected').val();
                var gst = body.find('.total_gst').val();
                $.ajax({
                    url: 'pages/booking/booking_form_new-redesign.php',
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
                        console.log(data)
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
        </script>
        <script type="text/javascript">
            $('input[name=receiver_phone]').on('keyup', function(event) {
                event.preventDefault();
                if (event.key == 'Enter' || event.keyCode == 13) {
                    event.preventDefault();
                    getPhoneDetails();
                }
            });


            $(document).on('click', '.search_phone', function() {
                getPhoneDetails();
            })

            function getPhoneDetails() {
                var mobile_search = $('input[name=receiver_phone]').val();
                $.ajax({
                    url: 'ajax.php',
                    type: 'POST',
                    data: {
                        rphone_no: mobile_search
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        if (data.status === 1) {
                            $('input[name=receiver_name]').val(data.response.rname);
                            $('input[name=receiver_email]').val(data.response.remail);
                            $('input[name=receiver_address]').val(data.response.receiver_address);
                            $('.r_phone_msg').html('');
                        } else {
                            $('input[name=receiver_name]').val("");
                            $('input[name=receiver_email]').val("");
                            $('input[name=receiver_address]').val("");
                            $('.r_phone_msg').html(data.response);
                        }
                    }
                });
            }
            $(document).on('click', '.sender_search_phone', function() {
                var mobile_search = $('input[name=mobile_no]').val();
                $.ajax({
                    url: 'ajax.php',
                    type: 'POST',
                    data: {
                        sphone_no: mobile_search
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        if (data.status === 1) {
                            $('input[name=fname]').val(data.response.sname);
                            $('input[name=email]').val(data.response.semail);
                            $('input[name=scnic]').val(data.response.scnic);
                            $('.shipper_address').val(data.response.sender_address);
                            $('.s_phone_msg').html('');
                        } else {
                        // $('input[name=receiver_name]').val("");
                        // $('input[name=receiver_email]').val("");
                        // $('input[name=receiver_address]').val("");
                        $('.s_phone_msg').html(data.response);
                    }
                }
            });
            })
        </script>