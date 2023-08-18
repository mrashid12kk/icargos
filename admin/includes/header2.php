<style>

</style>
<header class="top-head container-fluid">
	<button type="button" class="navbar-toggle pull-left">
		<span class="sr-only">Toggle navigation</span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	</button>
	
	<input type="text" id="barcodeInput" class="order_search form-control" value="" autofocus="true" placeholder="<?php echo getLange('searchorder'); ?>" />
	<?php

	$sql_portal_lang = mysqli_query($con, "SELECT * FROM portal_language WHERE is_active = 1");

	 ?>
	<ul class="nav-toolbar">
		<li class="dropdown translate_wrapper">
	        <a href="#" class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo getLange('language'); ?> </a>
	       <!--  <form method="POST" action=""> -->
	        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
	        	<?php while ($response = mysqli_fetch_assoc($sql_portal_lang)) {?>
	        		<?php $name = isset($response['language']) ? $response['language'] : ''; ?>
	        		<?php $id = isset($response['id']) ? $response['id'] : ''; ?>
	        	    	<a class="dropdown-item" href="../language_helper.php?language=<?php echo $name; ?>&language_id=<?php echo $id; ?>"><?php echo ucfirst($name); ?></a>
	        	<?php } ?>

	      </div>
	      <!-- </form> -->
	    </li>
	    

		<li class="dropdown">
			<a href="#" data-toggle="dropdown">
				<img src="<?php echo $fetch['image']; ?>" width="30" style="margin: -5px 0px 0px -3px;" class="img-circle" alt="...">
			</a>
			<div class="dropdown-menu lg pull-right arrow panel panel-default arrow-top-right">
				<div class="panel-heading">
					More Apps
				</div>
				<div class="panel-body text-center">
					<div class="row">
						<div class=" col-sm-4"><a href="editprofile.php" class="text-green"><span class="h2"><i class="fa fa-user"></i></span><p style="color: #000;" class="text-gray no-margn"><?php echo getLange('profile'); ?></p></a></div>
						<div class=" col-sm-4"><a href="changepassword.php" class="text-purple"><span class="h2"><i class="fa fa-lock"></i></span><p style="color: #000;" class="text-gray no-margn"><?php echo getLange('changepassword'); ?></p></a></div>
							<div class=" col-sm-4 ">
								<a href="logout.php" class="text-red"><span class="h2"><i class="fa fa-sign-out"></i></span><p style="color: #000;" class="text-gray no-margn"><?php echo getLange('logout'); ?></p></a>
							</div>
				  	</div>
				</div>
			</div>
		</li>
		<div class="logout_btn">
		<a href="logout.php" class="text-red"><span class="h2"></span><p class="text-gray no-margn"><i class="fa fa-sign-out"></i> <?php echo getLange('logout'); ?></p></a>
	</div>
	</ul>
</header>
