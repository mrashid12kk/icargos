<?php
session_start();
require 'includes/conn.php';
include "includes/sms_helper.php";
if (isset($_SESSION['users_id']) && ($_SESSION['type'] !== 'driver')) {
    require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'], 40, 'view_only', $comment = null)) {

        header("location:access_denied.php");
    }
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
                include "pages/orders/bulk_status_update.php";
                ?>
        </div>
        <!-- Warper Ends Here (working area) -->
        <?php include "includes/footer.php";
    } else {
        header("location:index.php");
    }
        ?>
        <script type="text/javascript">
            $(document).ready(function(){
                 // alert();
          $.ajax({
        url: 'ajax_new_country.php',
        type: "Post",
        async: true,
        data: { 
            name:$('#country').val()
        },
        success: function (data) {
           // alert(data);
           $('#city').html(data);

        },
        error: function (xhr, exception) {

        }
    }); 
    $('#country').on('change' , function(){
        // alert();
          $.ajax({
        url: 'ajax_new_country.php',
        type: "Post",
        async: true,
        data: { 
            name:$(this).val()
        },
        success: function (data) {
           // alert(data);
           $('#city').html(data);

        },
        error: function (xhr, exception) {
         
           
        }
    }); 
    });
});

$(document).ready(function(){

        // alert();
          $.ajax({
        url: 'ajax_new_country.php',
        type: "Post",
        async: true,
        data: { 
            name:$('#country1').val()
        },
        success: function (data) {
           // alert(data);
           $('#city1').html(data);

        },
        error: function (xhr, exception) {
        }
    }); 
     $('#country1').on('change' , function(){
        // alert();
          $.ajax({
        url: 'ajax_new_country.php',
        type: "Post",
        async: true,
        data: { 
            name:$(this).val()
        },
        success: function (data) {
           // alert(data);
           $('#city1').html(data);

        },
        error: function (xhr, exception) {
           
           
        }
    }); 
    });
});

$(document).ready(function(){

        // alert();
          $.ajax({
        url: 'ajax_new_country.php',
        type: "Post",
        async: true,
        data: { 
            name:$('#country2').val()
        },
        success: function (data) {
           // alert(data);
           $('#city2').html(data);

        },
        error: function (xhr, exception) {
        }
    }); 
     $('#country2').on('change' , function(){
        // alert();
          $.ajax({
        url: 'ajax_new_country.php',
        type: "Post",
        async: true,
        data: { 
            name:$(this).val()
        },
        success: function (data) {
           $('#city2').html(data);
        },
        error: function (xhr, exception) {  
        }
    }); 
    });
});

        $(document).keypress(
            function(event) {
                if (event.which == '13') {
                    event.preventDefault();
                }
            });
        // $(document).on("click", "#rider_credit_btn", function () {
        //    });
        $(".close_btn,.overlay_popup_fixed").click(function() {
            $(".overlay_popup_fixed,.overly_popup").fadeOut();
        });
        $(document).on("change", ".this_status", function() {
            var length = $('body').find('.response_table_body').find('tr').length;
            if (length < 1) {
                alert('Please select track no first')
            } else {
                var a = $(this).val();
                if (a != 'Pick up in progress' || a != 'Out for Delivery') {
                    $(".branch_selection").addClass("display_none");
                    $(".active_courier_div").addClass("display_none");
                }
                if (a === 'Pick up in progress') {
                    $(".active_courier_div").removeClass("display_none");
                    $(".branch_selection").addClass("display_none");
                }
                if (a === 'Out for Delivery') {
                    $(".active_courier_div").removeClass("display_none");
                    $(".branch_selection").addClass("display_none");
                }
                if (a === 'Parcel in Transit to Destination') {
                    $(".branch_selection").removeClass("display_none");
                    $(".active_courier_div").addClass("display_none");
                }

            }
        });

        function totalPieces() {
            let totalpcs = 0;
            let nextTotal = $('body').find('.response_table_body').find('tr').find('td').eq(6).html();
            $('body').find('.hidden_qunatity_value').each(function(index, value) {
                totalpcs += parseFloat($(this).val());
            });
            $('body').find('.total_pieces').val(totalpcs);
        }

        function totalWeight() {
            let totalweight = 0;
            let nextTotal = $('body').find('.response_table_body').find('tr').find('td').eq(6).html();
            $('body').find('.hidden_weight').each(function(index, value) {
                totalweight += parseFloat($(this).val());
            });
            $('body').find('.total_weight').val(totalweight);
        }
        $(document).on('click', '.delete_row', function() {
            var data_wt = $(this).attr('data-wt');
            var data_qt = $(this).attr('data-qt');
            $(this).closest('tr').remove();
            var total_wt = $('.total_weight').val();
            var total_pc = $('.total_pieces').val();
            total_pcs = total_pc - data_qt;
            total_wts = total_wt - data_wt;
            $('.total_weight').val("");
            $('.total_weight').val(total_wts);
            $('.total_pieces').val("");
            $('.total_pieces').val(total_pcs);
        })
        $(document).on("click", ".mode_type_name", function() {
            var a = $('.mode_type_name:checked').val();
            $.ajax({
                url: 'bulk_status_update_ajax.php',
                type: 'POST',
                data: {
                    mode_id: a
                },
                cache: false,
                success: function(response) {
                    $(".transport_company").html(response);
                }
            });
        });

        function appendNextRow() {
            var length = $('body').find('.response_table_body').find('tr').length;
            var a = $('body').find('.enter_cn').val();
            var tbody = $('body').find('.response_table_body').find('tr');
            var existing_array = [];
            tbody.each(function(index) {
                existing_array.push($(this).find('.all_cn_no').val());
            });
            var flag = false;
            tbody.each(function(index) {
                if ($.inArray(a, existing_array) !== -1) {
                    flag = true;
                    return false;
                } else {
                    existing_array.push($(this).find('.all_cn_no').val());
                }
            });
            if (flag) {
                flag = false;
                alert('Track Number already exists.');
                return false;
            }
            $.ajax({
                url: 'bulk_status_update_ajax.php',
                type: 'POST',
                data: {
                    enter_cn: a,
                    length: length
                },
                success: function(response) {
                    if (response == '') {
                        alert('No record found');
                    } else {
                        $('body').find(".response_table_body").append(response);
                        $('body').find('.enter_cn').val('');
                        $('body').find('.enter_cn').focus();
                        totalPieces();
                        totalWeight();
                    }
                }
            });
        }
        $('body').on('keydown', '.enter_cn_no', function(event) {
            if (event.keyCode == 13) {
                appendNextRow();
                event.preventDefault();
            }
        });
        $(document).on("click", ".append_cn_nos", function(event) {
            event.preventDefault();
            appendNextRow();
        });
        $(document).ready(function() {
            $.ajax({
                url: 'bulk_status_update_ajax.php',
                type: 'POST',
                data: {
                    receiver_person: 1
                },
                cache: false,
                success: function(response) {
                    $(".receiver_person").html(response);
                }
            });
        });
        $(document).on("change", ".destination", function() {
            var a = $(this).val();
            $.ajax({
                url: 'bulk_status_update_ajax.php',
                type: 'POST',
                data: {
                    city_value: a
                },
                cache: false,
                success: function(response) {
                    $(".area").html(response);
                }
            });
        });

        $(document).on("click", "#collect_cash", function() {
            var length = $('body').find('.response_table_body').find('tr').length;
            if (length < 1) {
                Swal.fire({
                    position: 'bottom-end',
                    icon: 'warning',
                    title: 'Please select orders for cash collection.',
                    showConfirmButton: false,
                    timer: 2500
                });
            } else {
                var mydata = [];
                $('.response_table_body  > tr').each(function() {
                    // var checkbox = $(this).find('td:first-child .order_check');
                    var order_id = $(this).find('.all_cn_no').val();
                    mydata.push(order_id);
                });
                $.ajax({
                    url: 'bulk_status_update_ajax.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        delivered_status: 'Delivered',
                        some_cn_no: mydata
                    },
                    cache: false,
                    success: function(response) {
                        $("#rider_balance_report").html(response.table);
                        $(".overlay_popup_fixed,.overly_popup").fadeIn();
                    }
                });
            }

        });

        function filterData(value) {
            var allowed_statuses = $('.allowed_statuses').val();
            var origin = $('.origin').val();
            var origincity = $('.city_origin ').val();
            var destination = $('.destination').val();
            var destinationcity = $('.city_destination ').val();
            var allowed_statuses = $('.allowed_statuses').val();
            var receivingbranch = $('.receiving_branch').val();
            var custom_field = $(value).parent().parent().find('.custom_field').val();
            var field_name = $(value).attr("data-name");
            $.ajax({
                url: 'bulk_status_update_ajax.php',
                type: 'POST',
                data: {
                    custom_field: custom_field,
                    field_name: field_name,
                    origin: origin,
                    destination: destination,
                    pick_update_cn: 1,
                    allowed_statuses: allowed_statuses,
                    receivingbranch: receivingbranch,
                      city1:origincity,city2:destinationcity,
                },
                cache: false,
                success: function(response) {
                    if (response == 'No record found.') {
                        Swal.fire({
                            position: 'bottom-end',
                            icon: 'warning',
                            title: 'No record found.',
                            showConfirmButton: false,
                            timer: 2500
                        })
                    }
                    $(".inner_contents").html(response);
                    var totalpcs = $('body').find(".pieces").val();
                    var weight = $('body').find(".new_weight").val();
                    $(".total_pieces").val(totalpcs);
                    $(".total_weight").val(weight);
                }
            });
        }
        $(document).on("click", ".filter_right", function() {
            filterData(this);
        });
        $(document).on("click", ".pick_cn_number", function() {
            var custom_field = $(this).parent().parent().find('.custom_field').val();
            if (custom_field === '') {
                Swal.fire({
                    position: 'bottom-end',
                    icon: 'warning',
                    title: 'Please enter a value to continue.',
                    showConfirmButton: false,
                    timer: 2500
                })
            } else {
                filterData(this);
            }
        });
        $(document).on("keyup", ".custom_field", function(event) {
            event.preventDefault();
            if (event.keyCode == 13) {
                var custom_field = $(this).val();
                if (custom_field === '') {
                    Swal.fire({
                        position: 'bottom-end',
                        icon: 'warning',
                        title: 'Please enter a value to continue.',
                        showConfirmButton: false,
                        timer: 2500
                    })
                } else {
                    var allowed_statuses = $('.allowed_statuses').val();
                    var origin = $('.origin').val();
                    var destination = $('.destination').val();
                    var allowed_statuses = $('.allowed_statuses').val();
                    var receivingbranch = $('.receiving_branch').val();
                    var custom_field = $(this).val();
                    var field_name = $(this).attr("data-name");
                    $.ajax({
                        url: 'bulk_status_update_ajax.php',
                        type: 'POST',
                        data: {
                            custom_field: custom_field,
                            field_name: field_name,
                            origin: origin,
                            destination: destination,
                            pick_update_cn: 1,
                            allowed_statuses: allowed_statuses,
                            receivingbranch: receivingbranch
                        },
                        cache: false,
                        success: function(response) {
                            if (response == 'No record found.') {
                                Swal.fire({
                                    position: 'bottom-end',
                                    icon: 'warning',
                                    title: 'No record found.',
                                    showConfirmButton: false,
                                    timer: 2500
                                })
                            }
                            $(".inner_contents").html(response);
                            var totalpcs = $('body').find(".pieces").val();
                            var weight = $('body').find(".new_weight").val();
                            $(".total_pieces").val(totalpcs);
                            $(".total_weight").val(weight);
                        }
                    });
                }
            }

        });
        $(document).on("click", "#update_statuses", function(e) {
            var length = $('body').find('.response_table_body').find('tr').length;
            if (length < 1) {
                alert('Please select track no to change status');
            } else {
                var status = $(".status").val();
                var country = $(".country").val();
                var tracking_remarks = $(".tracking_remarks").val();
                var city = $(".city").val();
                // alert(country);
                if (status === '') {
                    alert('Please select a status first.');
                    // Swal.fire({
                    //      position: 'bottom-end',
                    //      icon: 'warning',
                    //      title: 'Please select a status first.',
                    //      showConfirmButton: false,
                    //      timer: 2500
                    //    })
                } else {
                    $('form').submit();
                }
            }
        });
        $(document).on("click", ".edit_row", function(e) {
            var single_weight = $(this).parent().parent().find('.single_weight').text();
            $(this).parent().parent().find(".single_weight").html(
                "<input type='text' class='single_weight new_weight' style='width:45px' value=" +
                single_weight + " /><input type='hidden' style='width:45px' class='hidden_weight' value=" +
                single_weight + " /><i class='fa fa-check save_new_weight'></i>");
        });

        function updatecredit() {
            var riders_names = [];
            var riders_ids = [];
            var riders_collections = [];
            var all_assignment_nos = [];
            var mydata = [];
            $('.response_table_body  > tr').each(function() {
                var order_id = $(this).find('.all_cn_no').val();
                mydata.push(order_id);
            });
            $('#collection_table  > tr').each(function() {
                var rider_n = $(this).find('.riders_names').val();
                riders_names.push(rider_n);
            });
            $('#collection_table  > tr').each(function() {
                var rider_id = $(this).find('.riders_ids').val();
                riders_ids.push(rider_id);
            });
            $('#collection_table  > tr').each(function() {
                var rider_collectioin = $(this).find('.riders_collections').val();
                riders_collections.push(rider_collectioin);
            });
            $('#collection_table  > tr').each(function() {
                var assignment_no = $(this).find('.assignment_no').val();
                all_assignment_nos.push(assignment_no);
            });
            $.ajax({
                url: 'bulk_status_update_ajax.php',
                type: 'POST',
                data: {
                    riders_ids: riders_ids,
                    all_assignment_nos: all_assignment_nos,
                    riders_names: riders_names,
                    riders_collections: riders_collections,
                    all_cn_no: mydata,
                    update_credit: 1
                },
                cache: false,
                success: function(response) {}
            });
        }

        </script>