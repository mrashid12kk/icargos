<?php
session_start();
include_once "includes/conn.php";
if(isset($_SESSION['customers'])){
include "includes/header.php";
$cities = mysqli_query($con,"SELECT * FROM cities WHERE 1 ");
$page_title = 'Edit Profile';
$is_profile_page = true;
$customer_id = $_SESSION['customers'];
if(isset($_POST['submit'])){
	$profiling = $_POST['profiling'];
	$id = $_POST['id'];
	foreach($profiling as $row){
		$shipper_name = trim($row['shipper_name']);
		$shipper_phone = trim($row['shipper_phone']);
		$shipper_email = trim($row['shipper_email']);
		$shipper_address = trim($row['shipper_address']);
		mysqli_query($con,"UPDATE profiling SET shipper_name='".$shipper_name."',shipper_phone='".$shipper_phone."',shipper_email='".$shipper_email."',shipper_address='".$shipper_address."' WHERE customer_id=".$customer_id." AND id=".$id." ");
		
		echo "<script type='text/javascript'>window.location.href='".BASE_URL."multiple_profile.php';</script>"; exit;
	}
}
$rec_id = $_GET['id'];
$profile_query = mysqli_query($con,"SELECT * FROM profiling WHERE id=".$rec_id." ");	
$profile_record = mysqli_fetch_array($profile_query);	
?>
<style> 
#fileform .col-lg-12{
	padding: 0;
}section .dashboard .white {
    background: #fff;
    padding: 0;
    box-shadow: 0 0 3px #ccc;
    width: 100%;
    display: table;
}
.btn_save,.btn_process{
	color: #fff !important;
}
.remove_row{
	color: #fff !important;
}
.btn_save:hover,.btn_save:focus{
	color: #fff !important;
}
.btn_process:hover,.btn_process:focus{
	color: #fff !important;
}
.multi_profile_main{
	margin-top: 23px !important;
}
#fileform .col-lg-6{
	padding: 0 15px 0 0;
}
.form-group label{
	color: #000;
	    margin-bottom: 6px;
}
	.white h2{
	  		color: #000;
	  	}
	  	select,input,textarea{
			border: 1px solid #ccc !important;
			color: #000 !important;
	  	}
	  	::-webkit-input-placeholder { /* Chrome/Opera/Safari */
  color: #000 !important;
}
::-moz-placeholder { /* Firefox 19+ */
  color: #000 !important;
}
:-ms-input-placeholder { /* IE 10+ */
  color: #000 !important;
}
:-moz-placeholder { /* Firefox 18- */
  color: #000 !important;
}
}
</style>
<style>
table th {
	  		color: #8f8f8f;
	  	}
	  	.table-bordered tr td{
	  		color: #000;
	  	}
	@media(max-width: 767px){
		.container{
			width: auto;
		}
		.white h2 {
    margin-top: 23px !important;
}
		.col-sm-3{
			padding: 0;
			color: #000;
			margin-bottom: 8px;
		}
		.col-lg-12 ,.col-sm-3{
			padding: 0;
		}
		.btn-danger{
			width: 100%;
		}
		section .white {
    min-height: auto;
}
.bg ,.password{
    padding: 0px 0 5px;
}

	}
</style>
<section class="bg padding30">
  <div class="container-fluid dashboard">
     <div class="col-lg-2 col-md-3 col-sm-4 profile" style="margin-left:0">
     <?php
		include "includes/sidebar.php";
	  ?>
    </div>
    <div class="col-lg-10 col-md-9 col-sm-8 profile">
     
     
      <div class="row">
      <div class="col-lg-12  login">
        <div class="white">
          <h2 style="    background-color: #074e8c;
    border-color: #074e8c;
    margin: 0;
    color: #fff !important;
    font-size: 14px;
    padding: 10px 15px;
    border-bottom: 1px solid transparent;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;">Update Profile</h2>
		 <div class="multi_profile_main">
		 	<form method="POST" action="">
		 		<input type="hidden" name="id" value="<?php echo $rec_id; ?>">
		 	<table class="table" id="multiple_profile">
		 		<thead>
		 			<tr>
		 				<th>Shipper Name</th>
		 				<th>Shipper Phone</th>
		 				<th>Shipper Email</th>
		 				<th>Shipper Address</th>
		 				<th></th>
		 			</tr>
		 		</thead>
		 		<tbody>
		 			<tr>
		 				<td>
		 					<input type="text" value="<?php echo $profile_record['shipper_name'] ?>" name="profiling[0][shipper_name]"  class="form-control" required="true">
		 				</td>
		 				<td>
		 					<input type="text" value="<?php echo $profile_record['shipper_phone'] ?>" name="profiling[0][shipper_phone]" class="form-control" required="true">
		 				</td>
		 				<td>
		 					<input type="email" value="<?php echo $profile_record['shipper_email'] ?>"  name="profiling[0][shipper_email]" class="form-control" required="true">
		 				</td>
		 				<td>
		 					<input type="text" value="<?php echo $profile_record['shipper_address'] ?>" name="profiling[0][shipper_address]" class="form-control" required="true">
		 				</td>
		 				<td>
		 					
		 				</td>
		 			</tr> 

		 		</tbody>

		 	</table>
		 	<input type="submit" name="submit" class="btn btn-success btn_save" value="Update">
		 	</form>
		 </div>

        </div>
       
      </div>
      
    </div>
    
   

    </div>

    
  </div>
</section>

</div>
   
<?php
// include "includes/footer.php";
}
	else{
		header("location:index.php");
	}
?>
  <?php include 'includes/footer.php'; ?>
 <!--  <script type="text/javascript">
  	$('body').on('click','.add_row',function(e){
        e.preventDefault();
        var counter = $('#multiple_profile > tbody tr').length;
        var row = $('#multiple_profile > tbody tr').first().clone();
        row.find('input,select').each(function(){
          var name = $(this).attr('name').split('[0]');
          $(this).attr('name',name[0]+'['+counter+']'+name[1]);
        })
        
        row.find('.add_row').addClass('remove_row');
        row.find('.add_row').addClass('btn btn-danger');
        row.find('.fa-plus').addClass('fa-trash');
        row.find('.add_row').removeClass('btn-info');
        row.find('.fa-plus').removeClass('fa-plus');
        row.find('.add_row').removeClass('add_row');
        $('#multiple_profile').append(row);
      })
  </script> -->