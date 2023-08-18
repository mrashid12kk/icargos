<?php
    session_start();
    require 'includes/conn.php';
    if(isset($_SESSION['users_id']) &&($_SESSION['type']!=='driver' && $_SESSION['type'] == 'admin')){
    require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'],8,'add_only',$comment =null)) {

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

            include "pages/location/location_sidebar.php";
            include "pages/location/city_list.php";

            ?>


        </div>
        <!-- Warper Ends Here (working area) -->


      <?php

    include "includes/footer.php";
    }
    else{
        header("location:index.php");
    }
    ?>

     <script type="text/javascript">
      $(function () {
          $('.datetimepicker4').datetimepicker({
            format: 'YYYY/MM/DD',
          });
      });
document.addEventListener('DOMContentLoaded', function() {

  var dataTable = $('#country_table').DataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    // 'scrollCollapse': true,
        // 'ordering': false,
        // pageLength: 5,
        'responsive': true,
        'dom': "<'row'<'col-sm-4'l><'col-sm-4'f><'col-sm-4 text-right'B>>t<'bottom'p><'clear'>",
       // dom: '<"html5buttons"B>lTfgitp',
         'buttons': [
                   {extend: 'copy'},
                   {extend: 'csv'},
                   {extend: 'excel', title: 'ExampleFile'},
                   {extend: 'pdf', title: 'ExampleFile'},
              ],
    'ajax': {
       'url':'ajax_city_list.php',
       'data': function(data){
          var country_name = $('#country_name').val();
          data.country_name = country_name;
          data.state_id = <?php echo isset($_GET['state_id']) ? $_GET['state_id'] : ''; ?>;
       },
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
       { data: 'flag' }, 
       { data: 'state' }, 
       { data: 'city' }, 
       { data: 'action' }, 

    ]
  });
 $('.search_pincode').click(function(e){
    e.preventDefault();
    dataTable.draw();
  });
}, false);
</script>