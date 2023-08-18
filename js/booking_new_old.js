  var order_id_or=$('.id').val();
  // alert(order_id_or);
  $(document).ready(function () {

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