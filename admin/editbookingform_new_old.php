<?php
session_start();
require 'includes/conn.php';
if (isset($_SESSION['users_id']) && ($_SESSION['type'] !== 'driver' && $_SESSION['type'] == 'admin')) {
    require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'], 8, 'edit_only', $comment = null)) {
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
                include "pages/booking/editbookingform_new.php";
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

            var order_id_or=$('.id').val();
            $(document).ready(function() {
                origin_country();
                function origin_country() {
                    var active_customer_id = $('body').find('.active_customer').val();
                    $.ajax({
                        type: 'POST',
                        data: {
                            active_customer_id: active_customer_id,
                            order_id_or: order_id_or,
                            get_origin_country: 1
                        },
                        url: 'ajax_internation_booking.php',
                        success: function(response) {
                            $('.origin_cha').html(response);    
                            $('.destination').html(response);    
                            $('.js-example-basic-single').select2();
                            origin_state();
                            destination_state();
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
                            order_id_or: order_id_or,
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
                            order_id_or: order_id_or,
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
            order_id_or: order_id_or,
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
            order_id_or: order_id_or,
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
    "]' class='form-control c_i_price'></div></div><div class='col-sm-2 padd_left'><div class='form-group'><label class='calculation_label'>COO</label><input value='0' type='text' name='c_i_coo[" +
    array_value +
    "]' class='form-control' value='PK'></div></div><div class='col-sm-2 padd_left'><div class='form-group'><label class='calculation_label'>HS Code</label><input value='0' type='text' name='c_i_hs_code[" +
    array_value +
    "]' value='0000.0000' class='form-control c_i_hs_code'></div></div><div class='col-sm-2 padd_left'><div class='form-group'><label class='calculation_label'>Total</label><input type='text' name='c_i_hs_total[" +
    array_value +
    "]' value='0' class='form-control c_i_hs_total'></div></div><div class='col-sm-2 padd_left'><div class='form-group '><a href='' class='btn btn-danger btn_commercial_invoice_romove' style='margin-top: 24px;margin-left: 36px;'>-</a></div></div></div></div>";
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
            data: {
                destination_country: destination_country,
                destination_state: destination_state,
                areas: areas,
                add_areas_booking: 1
            },
            url: 'ajax_internation_booking.php',
            success: function(response) {
                $('.destination_city').html(response);
                $("#exampleModal .close_modal_booking").click();
                $('.add_areas_booking').val('');
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
// $('body').on('keyup', 'input[type=text]', function(e) {
//     $('input[type=text]').val (function () {
//         return this.value.toUpperCase();
//     })
// })
// $('body').on('keyup', 'input[type=email]', function(e) {
//     $('input[type=email]').val (function () {
//         return this.value.toUpperCase();
//     })
// })
// $('body').on('keyup', 'input[type=myNumber]', function(e) {
//     $('input[type=myNumber]').val (function () {
//         return this.value.toUpperCase();
//     })
// })
// $('body').on('keyup', 'textarea', function(e) {
//     $('textarea').val (function () {
//         return this.value.toUpperCase();
//     })
// })
$('body').on('change', '.change_charges', function() {
    if ($(this).prop("checked") == true) {
        var data_charf = $(this).attr('data-charges');
        var data_char = $(this).val();
        $('.' + data_char).val(data_charf);
        calculateCharges();
    } else {
        var data_char = $(this).val();
        $('.' + data_char).val('0');
        calculateCharges();
    }
})
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
                        // execute();
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
</script>
<script type="text/javascript">
    $('body').on('keyup change', '.insurance_rate', function(e) {
        var delivery_rate = $('.insured_item_value').val();
        var is_fragile = $('.is_fragile').val();
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
</script>
<script type="text/javascript">
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
</script>
<script type="text/javascript">
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
                $('.shipper_fname').val(response.fname);
                $('.shipper_bname').val(response.bname);
                $('.shipper_mob').val(response.mobile_no);
                $('.shipper_email').val(response.email);
                $('.shipper_address').val(response.address);
                execute();
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
    $('body').on('submit', '#booking_form', function(e) {
        e.preventDefault();
        $('.submit_btns').attr('disabled', 'disabled');
            // var data = new FormData(this);
            var data = {};
            $('#booking_form').find('input,select,textarea').each(function(i) {
                if ($(this).attr('name') && $(this).attr('type') != 'submit')
                    data[$(this).attr('name')] = $(this).val();
            })
            var customer_id = 0;
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
            $.ajax({
                url: 'pages/booking/editbookingform_new.php',
                type: 'POST',
                data: data,
                cache: false,
                // headers: { "cache-control": "no-cache" },
                // processData:false,
                dataType: 'json',
                // enctype: 'multipart/form-data',
                // contentType: false,
                success: function(response) {
                    if (response) {
                        if (response.error) {
                            var msg =
                            '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> ' +
                            response.alert_msg + '</div>';
                            $('#msgs').html(msg);
                            $('.msgs').html(msg);
                            $('.submit_btns').removeAttr('disabled');
                            return false;
                        }
                        var track_no = response.track_no;
                        if (response.print) {
                            window.open('invoicehtml.php?id=' + response.id +
                                '&print=1&frontdesk=1', 'mywindow', 'status=1');
                        } else {
                            var msg =
                            '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Order#' +
                            track_no + '  Updated Successfully.</div>';
                            $('#msgs').html(msg);
                            $('.msgs').html(msg);
                            location.reload();
                        }
                        // $('.tracking_number').text(track_no+1);
                        window.location.relaod();
                        $('#booking_form').trigger("reset");
                    } else {
                        var msg =
                        '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Unable to submit your request, Please contact Administrator!.</div>';
                        $('#msgs').html(msg);
                    }
                    $('.submit_btns').removeAttr('disabled');
                    execute();
                }
            });
        })
    </script>
    <script type="text/javascript">
        $('body').on('keyup', '.total_amount', function(e) {
            var delivery_rate = $('.total_amount').val();
            var gst = $('.total_gst').val();
            var excl_amount = delivery_rate;
            var pft_amount = parseInt(excl_amount / 100) * gst;
            var incl_amount = parseInt(excl_amount) + pft_amount;
            //alert(excl_amount);
            $('.excl_amount').val(excl_amount);
            $('.pft_amount').val(pft_amount);
            $('.inc_amount').val(incl_amount);
        })
        $('body').on('change', '.order_type', function(e) {
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
            execute();
        })
        $('body').on('keyup', '.extra_charges', function(e) {
            e.preventDefault();
            calculateCharges();
        })
        $('body').on('keyup', '.weight', function(e) {
            e.preventDefault();
            execute();
        })
        $('body').on('change', '.origin_cal', function(e) {
            e.preventDefault();
            execute();
        })
        $('body').on('change', '.destination', function(e) {
            e.preventDefault();
            getOriginViseArea($(this));
            execute();
        })

        function execute() {
            // alert('ok');
            var body = $('body');
            body.find('.submit_btns').attr('disabled', 'disabled');
            var origin = body.find(".origin option:selected").val();
            // $('.origin_branch_id').val(origin);
            var destination = body.find(".destination option:selected").val();
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
                url: 'pages/booking/booking_form.php',
                type: 'POST',
                data: {
                    settle: 1,
                    origin: origin,
                    destination: destination,
                    weight: weight,
                    order_type: order_type,
                    customer_id: customer_id,
                    product_type_id: product_type_id
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
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.wother_charges').val('0');
            // calculateCharges();
        })
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
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('title').text($('title').text() + ' Booking Form')
        }, false);
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
    </script>