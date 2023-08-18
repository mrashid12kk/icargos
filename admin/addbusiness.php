<?php
  session_start();
  require 'includes/conn.php';
  if(isset($_SESSION['users_id'])){
     require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'],2,'add_only',$comment =null)){
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
          
            <div class="page-header"><h1><?php echo getLange('add_bussiness'); ?> <small><?php echo getLange('letsgetquick'); ?></small></h1></div>
            
            <?php
  
      include "pages/business/addbusiness.php";
      
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
     $(function() {
                $('.datetimepicker4').datetimepicker({
                    format: 'YYYY-MM-DD',
                });
            });
    $(document).ready(function(){
      var counter =1;
      var selected_to_array = [];
      

      var updateSelectedCites = function(element = null) {
        let rows = $('#price_table > tbody tr');
        if(element) {
        rows = element.siblings();
      }
        selected_to_array = [];
        if(element)
          selected_to_array.push(element.find('.city_to').val());
        rows.each(function(i){
          var selected_to = $(this).find('.city_to :selected').val();
          console.log(selected_to);
          if($.inArray(selected_to, selected_to_array) == -1) {
            selected_to_array.push(selected_to);
        } else {
          let available_options = 0;
          $(this).find('.city_to option').each(function(i) {
            var value = $(this).text();
            if($.inArray(value, selected_to_array) > -1) {
              $(this).addClass('hide_city');
            } else {
              $(this).removeClass('hide_city');
              available_options++;
            }
          });
          if(available_options == 0)
            $(this).remove();
          else {
            $(this).find('.city_to option:not(.hide_city)').first().prop('selected', true);
            selected_to = $(this).find('.city_to').val();
            if($.inArray(selected_to, selected_to_array) == -1) {
                selected_to_array.push(selected_to);
          }
          }
        }

        });
        console.log(selected_to_array);

      }
      updateSelectedCites();
      $('body').on('change', '#price_table .city_to', function(e) {
        updateSelectedCites($(this).closest('tr'));
      })
     
      $('body').on('click','.add_row',function(e){
        e.preventDefault();
        var counter = $('#price_table > tbody tr').length;
        var row = $('#price_table > tbody tr').first().clone();
        row.find('input,select').each(function(){
          var name = $(this).attr('name').split('[0]');
          $(this).attr('name',name[0]+'['+counter+']'+name[1]);
        })
        

        


        row.find('.add_row').addClass('remove_row');
        row.find('.add_row').addClass('btn btn-danger');
        row.find('.fa-plus').addClass('fa-trash');
        row.find('.fa-plus').removeClass('fa-plus');
        row.find('.add_row').removeClass('add_row');
        $('#price_table').append(row);
        updateSelectedCites();
      })
      $('body').on('click','.remove_row',function(e){
        e.preventDefault();
        $(this).closest('tr').remove();
        updateSelectedCites();
      })
    })
  </script>
  <script type="text/javascript">
  	$('#cust_type').on('change',function(){
  		var id = $(this).val();
  		 $.ajax({
        url: 'ajax.php',
        dataType: "json",
        type: "Post",
        data: { getAccount:1, id:id},
        success: function (data) {
           console.log(data);
           $('#getValue').val(data.payable);
           $('#getValueparent1').val(data.payable_parent);
           $('#p_acc_id').val(data.payable_acc_id);
           $('#getValue1').val(data.receivable);
           $('#getValueparent2').val(data.receivable_parent);
           $('#r_acc_id').val(data.recievable_acc_id);
        },
     
    });
    });
  	
  </script>