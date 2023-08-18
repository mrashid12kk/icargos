<?php

session_start();

require 'includes/conn.php';

if (isset($_SESSION['users_id']) && $_SESSION['type'] == 'admin') {
    require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'], 21, 'view_only', $comment = null)) {

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



                <div class="page-header">
                    <h1><?php echo getLange('city'); ?> <small><?php echo getLange('letsgetquick'); ?></small></h1>
                </div>

                <div class="row">
                    <?php
                    include "pages/location/location_sidebar.php";
                    ?>
                    <div class="col-sm-10 table-responsive" id="setting_box">

                        <?php



                        include "pages/cities/citiesdata.php";



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
                    $('.select2').select2();
                </script>
                <script type="text/javascript">
                    document.addEventListener('DOMContentLoaded', function() {
                      var dataTable = $('#citiesdata_datatable').DataTable({
                        'processing': true,
                        'serverSide': true,
                        'serverMethod': 'post',
    // 'scrollCollapse': true,
    // 'ordering': false,
    'responsive': true,
    'pageLength': 10,
    'lengthMenu':[[10,25,50,100,200,300],[10,25,50,100,200,300]],
    'dom': "<'row'<'col-sm-4'l><'col-sm-4'f><'col-sm-4 text-right'B>>t<'bottom'p><'clear'>",
       // dom: '<"html5buttons"B>lTfgitp',
       'buttons': [
       {extend: 'copy'},
       {extend: 'csv'},
       {extend: 'excel', title: 'ExampleFile'},
       {extend: 'pdf', title: 'ExampleFile'},
       {extend: 'print',

       customize: function (win){
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
       'url':'ajax_citiesdata.php',
       beforeSend: function(){
        $('#image').show();
    },
    complete: function(){
        $('#image').hide();
    },

},

'columns': [
{ data: 'id' },
{ data: 'country' },
{ data: 'state' },
{ data: 'stn_code' },
{ data: 'city_name' },
{ data: 'area_code' },
{ data: 'zone_type' },
{ data: 'action' }
]
});
  // $('#submit_order').click(function(e){
  //   e.preventDefault();
  //   charges_count();
  //   dataTable.draw();
  // });
}, false);
</script>