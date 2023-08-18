<?php
	session_start();
	include_once "includes/conn.php";
			?>
			
			<?php
	
	if(isset($_SESSION['customers'])){
	
		include "includes/header.php";
?>
<style type="text/css">
	.access_denied {
    text-align: center;
    padding: 45px 0 0;
}
.access_denied img{
    display: block;
    margin: 25px auto 13px;
    border-radius: 35px;
    width: 592px;
}
.head_denied h3{
    font-size: 30px;
    color: #000;
}
.head_denied a{
display: inline-block;
    padding: 9px 26px !important;
    color: #fff;
    text-align: center;
    border-radius: 35px;
    background-image: linear-gradient(#416baf, #274f90, #416baf);
    font-size: 15px;
    margin: 25px 0 35px !important;
}
.head_denied a:hover {
    background-image: linear-gradient(#7ec34f, #7ec34f, #76c045);
}
</style>
<section class="bg padding30">
  <div class="container-fluid dashboard">
     <div class="col-lg-2 col-md-3 col-sm-4 profile" style="margin-left:0">
	  <?php
		include "includes/sidebar.php";
	  ?>
    </div>
    <div class="col-lg-10 col-md-9 col-sm-8 dashboard">
      <div class="white">
	  <div id="same_form_layout">
		  <div class="access_denied" style="background:#fff; ">
			<div class="head_denied">
				<h3>Access Denied</h3>
			</div>
			<img src="admin/images/access-denied.jpg">
			<div class="head_denied">
				<a href="profile.php">Go to Dashboard</a>
			</div>
		</div>
		</div>
      </div>
    </div>
  </div>
</section>
</div>
	<?php

	}
	else{
		header("location:index.php");
	}
	?>
	 <?php include 'includes/footer.php'; ?>
