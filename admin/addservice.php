<?php
	session_start();
  require 'includes/conn.php';

  require 'includes/role_helper.php';
  $user_role_id = $_SESSION['user_role_id'];
  $checkAllowed = checkRolePermission($user_role_id , 17 ,'add_only','Service Module with add enabled');

	if(isset($_SESSION['users_id']) && $checkAllowed){
	include "includes/header.php";
	$origincitydata=mysqli_query($con,"Select * from cities order by city_name");
	$destcitydata=mysqli_query($con,"Select * from cities order by city_name");

	$riderdata=mysqli_query($con,"Select * from users WHERE type='driver' ");
?>
<body data-ng-app>
 	<style type="text/css">
 		.label {
    display: inline;
    padding: .2em .6em .3em;
    font-size: 100%;
    font-weight: bold;
    line-height: 1;
    color: #fff;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: .25em;
    float: left;
    margin: 2px;
    width: 100%;
}
.city_dropdown {
    max-height: 186px;
    overflow-y: auto;
    overflow-x: hidden;
    min-height: auto;
}
 	</style>
    
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
        	
            <div class="page-header"><h1><?php echo getLange('dashboard'); ?> <small><?php echo getLange('letsgetquick'); ?></small></h1></div>
            
            <?php
	$msg="";
	if(isset($_POST['addcities'])){
		for($i=0;$i<count($_POST['city']);$i++){
		
		$query1=mysqli_query($con,"INSERT INTO `cities`(`city_name`) VALUES ('".$_POST['city'][$i]."')") or die(mysqli_error($con));
		$rowscount=mysqli_affected_rows($con);
		if($rowscount>0){
			$msg= '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>'.getLange('Well_done').'!</strong> '.getLange('you_added_a_new_city_successfully').'</div>';
		
			}
		else{
			$msg= '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>'.getLange('unsuccessful').'!</strong> '.getLange('you_have_not_added_a_new_city_unsuccessfully').'.</div>';
		}
		}
	}
echo $msg;
?>
<div class="panel panel-default">
	<div class="panel-heading"><?php echo getLange('addservicetype'); ?></div>
	<div class="panel-body" id="same_form_layout">
	
		<form role="form" data-toggle="validator" action="saveservice.php" method="post">
			<div id="cities"> 
			
					<div class="row">
						<div class="col-md-4">
						<div class="form-group">
							<label  class="control-label"><?php echo getLange('servicetype'); ?></label>
							<input type="text" class="form-control" name="service_type" placeholder="Service type" required>
							<div class="help-block with-errors "></div>
						</div>
					</div>
					<div class="col-md-4">
            <div class="form-group">
              <label  class="control-label"><?php echo getLange('code'); ?></label>
              <input type="text" class="form-control" name="service_code" placeholder="Service type code" required>
              <div class="help-block with-errors "></div>
            </div>
          </div>
					
					</div>
				
					<div class="row">
						<div class="col-md-4">
							<button type="submit" name="addservice" class="btn btn-purple" ><?php echo getLange('submit'); ?></button>
						</div>
					</div>
				</div>
			
			<br>
			
		</form>
	
	</div>
</div>
			
            
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