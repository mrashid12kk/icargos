<?php
	session_start();
	include_once "includes/conn.php";
	if(isset($_SESSION['customers'])){
        require_once "includes/role_helper.php";
    if (!checkRolePermission(12 ,'view_only','')) {

        header("location:access_denied.php");
    }
		$customer_id = $_SESSION['customers'];
		if(isset($_POST['save'])){
			$api_key = trim($_POST['auth_key']);
			if(empty($api_key) || strlen($api_key) <36 || strlen($api_key) >36){

				$_SESSION['message'] = "Undefined API Key";
			}else{
					mysqli_query($con," UPDATE customers SET auth_key='".$api_key."' WHERE id='".$customer_id."' AND api_status=1 ");
						$_SESSION['message'] = "API Updated Successfully";
					
			}
		}
		$query2 = mysqli_query($con,"SELECT auth_key,api_status FROM customers WHERE id=".$customer_id." AND api_status=1 ");
		$record = mysqli_fetch_array($query2);
		$auth_key = $record['auth_key'];
	include "includes/header.php";
	
	$page_title = 'API Setting';
	$is_profile_page = true;	
?>


<style>
section .dashboard .white {
    background: #fff;
    padding: 0;
    box-shadow: 0 0 3px #ccc;
    width: 100%;
    display: table;
}
.rounded_box{background: linear-gradient(90deg, #5893e2 0%, #5893e2 35%, #7eade3 100%);margin: 0 0 16px;border-radius: 6px;}
.inner_info_boxes{padding: 16px 25px;}
.inner_info_boxes h3{color: #fff;font-size: 17px;margin: 0 0 6px;}
.inner_info_boxes p{color: #fff;font-size: 16px;margin: 11px 0 11px;line-height: 1.5;}
.inner_info_boxes a{background: #6d6f7f;color: #fff !important;padding: 8px 29px;display: inline-block;border-radius: 33px;font-weight: 500;}
.inner_info_boxes a i{margin: 0 4px 0 0;}
.inner_info_boxes b{display: block;color: #fff;margin: 0 0 15px;font-size: 13px;}
.inner_info_boxes b i{
    margin: 0 4px 0 0;
    cursor: pointer;
}
.save_key{
	color: #fff !important;
}
.save_key:hover,.save_key:focus{
	color: #fff !important;
}
section .password input[type="submit"] {
  background: #4cb034;
    border: none;
    border-radius: 5px;
    color: #fff !important;
    margin-top: 20px;
    transition: all 0.5s ease-in-out;
    text-transform: uppercase;
    margin-left: 14px;
    margin-bottom: 10px;
}
#changepassform .col-sm-3{
	margin-top: 12px;
}
#keygen{
	color: #fff;
}
#keygen:hover,#keygen:focus{
	color: #fff;
}
table th {
	  		color: #8f8f8f;
	  	}
	  	.table-bordered tr td{
	  		color: #000;
	  	}

@media (max-width: 1024px){
	.container{
			width: 100%;
		}
.padding30 .dashboard {
    margin-top: 0 !important;
    margin-bottom: 30px;
}
}


	@media(max-width: 767px){
		.container{
			width: auto;
		}
		.col-sm-3{
			padding: 0;
			color: #000;
			margin-bottom: 8px;
		}
		.col-lg-12 {
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
  <div class="container-fluid dashboard" style="<?php if($_SESSION['language']!='english'){?> display:flex;flex-direction:row-reverse;<?php }?>">
     <div class="col-lg-2 col-md-3 col-sm-4 profile" style="margin-left:0">
      <?php
		include "includes/sidebar.php";
	  ?>
    </div>
    <div class="col-lg-10 col-md-10 col-sm-8 password">
     
     <?php
if(isset($_SESSION['message']) && !empty($_SESSION['message'])){ ?>
	<div class="alert alert-warning">
	  <?php echo $_SESSION['message']; ?>
	</div>
	<?php 
	unset($_SESSION['message']);
    }
    ?>
      <div class="white profile-right change-pas" style="margin-bottom: 0;">
        <div class="row">
          <div class="change_pasword_title" style="padding:0;">
            <h3 style="color:#000;"><?php echo getLange('apisetting'); ?> </h3>
          </div>
          <div class="clearfix"></div>
        
		  <form  method="POST" action=""  style="padding-top: 18px;">
		  	<div class="row">
		  		<div class="col-md-4">
		  			<input class="form-control" readonly="true"  name="auth_key" required="true" id="apikey" type="text" value="<?php echo isset($auth_key) ? $auth_key : ''; ?>" placeholder="API Auth Key"  />
		  		</div>
		  		<div class="col-md-4">
		  			<button class="btn btn-info" id="keygen"><?php echo getLange('generateapiauthkey'); ?>   </button>
		  		</div>
		  	</div>
           <input type="submit" name="save" class="btn btn-info save_key" style="margin-top:0;color:#fff !important;"  value="<?php echo getLange('save'); ?>">
        </form>
		</div>
      </div>
      <!-- <div class="row">
        	<div class="col-sm-8" style="padding:20px 0 0;">
        		

		        <div class="rounded_box" style="background: linear-gradient(90deg, #e18a59 0%, #e18a59 35%, #e9b087 100%);">
		        	<div class="inner_info_boxes">
		        		<h3>Shopfiy Plugin</h3>
		        		<p>With the help of this video you can learn how to install Shopify plugin</p>
		        		<b><i class="fa fa-play"></i> Shopify Pluggin Tutorial</b>
		        		<a href="https://apps.shopify.com/cods-courier?surface_detail=CODS+Courier&surface_inter_position=1&surface_intra_position=4&surface_type=search" target="_blank"> Download</a>
		        	</div>
		        </div>

		        <div class="rounded_box" style="background: linear-gradient(90deg, #5462d3 0%, #5462d3 35%, #8f98e2 100%);">
		        	<div class="inner_info_boxes">
		        		<h3>Wordpress Plugin</h3>
		        		<p>With the help of this video you can learn how to install Wordpress plugin</p>
		        		<b><i class="fa fa-play"></i> Wordpress Pluggin Tutorial</b>
		        		<a href="https://wp.integrations.icargos.com/wp-content/plugins/cods-courier.zip"><i class="fa fa-download"></i> Download</a>
		        	</div>
		        </div>
		        
        	</div>
        </div> -->
  </div>
</section>
<script type="text/javascript">
function generateUUID()
{
	var d = new Date().getTime();
	
	if( window.performance && typeof window.performance.now === "function" )
	{
		d += performance.now();
	}
	
	var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c)
	{
		var r = (d + Math.random()*16)%16 | 0;
		d = Math.floor(d/16);
		return (c=='x' ? r : (r&0x3|0x8)).toString(16);
	});

return uuid;
}

/**
 * Generate new key and insert into input value
 */
$( '#keygen' ).on('click',function(e)
{
	e.preventDefault();
	$( '#apikey' ).val( generateUUID() );
});
</script>

<?php
// include "includes/footer.php";
	}
	else{
		header("location:index.php");
	}
?>
	  <?php include 'includes/footer.php'; ?>