<?php
session_start();
require 'includes/conn.php';
if (isset($_SESSION['users_id']) && ($_SESSION['type'] !== 'driver')) {
    require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'], 29, 'view_only', $comment = null)) {

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

                include "pages/reports/comments_report_backup.php";

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
        $(document).ready(function() {
            $('.datepicker').datetimepicker({
                format: 'dd/mm/yyyy',

            });

            $(".comment_modal_id").click(function() {
                location.reload();
            })
            $(document).on("click", ".send_reply", function() { 
                var track = $('.reply_message').attr('data-track');
                var id = $('.reply_message').attr('data-id');
                var name = $('.reply_message').attr('data-name');
                var subject = $('.reply_message').attr('data-subject');
                var comment = $('.reply_message').attr('data-comment');
                var commentby = $('.reply_message').attr('data-commentby');

                $(document).find(".order_id_comment").val(id);
                $(document).find(".track_no_comment").val(track);
                $(document).find(".customer_id_comment").val(name); 
            })

             
            $(document).on("click", ".read_msg", function() { 
                var track = $(this).attr('data-track');
                var date = $(this).attr('data-date');
                var name = $(this).attr('data-name');
                var subject = $(this).attr('data-subject');
                var comment = $(this).attr('data-comment');
                var commentby = $(this).attr('data-commentby');

                $(document).find(".track_no_val").text(track);
                $(document).find(".order_date_val").text(date);
                $(document).find(".customer_name_val").text(name);
                $(document).find(".subject_val").text(subject);
                $(document).find(".comment_by_val").text(commentby);
                $(document).find(".order_comment_val").text(comment);

                var id = $(this).attr('data-id');

                $.ajax({
                    url: "ajax.php", //the page containing php script
                    type: "post", //request type,
                    dataType: 'json',
                    data: {
                        comentid: id
                    },
                    success: function(result) {

                    }
                });
            })



        })
        </script>
        </script>
        <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var dataTable = $('#comment_datatable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                // 'scrollCollapse': true,
                // 'ordering': false,
                'responsive': true,
                'pageLength': 10,
                'lengthMenu': [
                    [10, 25, 50, 100, 200, 300],
                    [10, 25, 50, 100, 200, 300]
                ],
                'dom': "<'row'<'col-sm-4'l><'col-sm-4'f><'col-sm-4 text-right'B>>t<'bottom'p><'clear'>",
                // dom: '<"html5buttons"B>lTfgitp',
                'buttons': [{
                        extend: 'copy'
                    },
                    {
                        extend: 'csv'
                    },
                    {
                        extend: 'excel',
                        title: 'ExampleFile'
                    },
                    {
                        extend: 'pdf',
                        title: 'ExampleFile'
                    },
                    {
                        extend: 'print',

                        customize: function(win) {
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
                ],
                //'searching': false, // Remove default Search Control
                'ajax': {
                    'url': 'ajax_view_comment.php',
                    beforeSend: function() {
                        $('#image').show();
                    },
                    complete: function() {
                        $('#image').hide();
                    },
                    'data': function(data) {
                        // Read values
                        var customer_id = $('#customer_id').val();
                        var from = $('#from').val();
                        var to = $('#to').val();
                        // Append to data
                        data.customer_id = customer_id;
                        data.from = from;
                        data.to = to;
                    }
                },

                'columns': [{
                        data: 'id'
                    },
                    {
                        data: 'tracno'
                    },
                    {
                        data: 'createdon'
                    },
                    {
                        data: 'customername'
                    },
                    {
                        data: 'subject'
                    },
                    {
                        data: 'orderamount'
                    },
                    {
                        data: 'commentby'
                    },
                    {
                        data: 'itemvalue'
                    },
                    {
                        data: 'action'
                    }
                ]
            });
            $('#submit_order').click(function(e) {
                e.preventDefault();
                dataTable.draw();
            });



            $('#print_data').click(function(e) {
                e.preventDefault();
                var date_range = $('#date_range').val();
                var date_from = $('#date_from').val();
                var date_to = $('#date_to').val();
                var collection_centers = $('#collection_centers').val();
                window.open('https://transco.itvision.pk/admin/print_sale_report.php?date_range=' +
                    date_range + '&date_from=' + date_from + '&date_to=' + date_to +
                    '&collection_centers=' + collection_centers + '&print=1');
            });
        }, false);
        </script>