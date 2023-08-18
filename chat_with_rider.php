<?php
session_start();
$url = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

require 'includes/conn.php';
if (isset($_SESSION['customers'])) {
	require_once "includes/role_helper.php";
	if (!checkRolePermission(6, 'view_only', '')) {

		header("location:access_denied.php");
	}
		include "includes/header.php";

	$customer_id = $_SESSION['customers'];
?>
		<!DOCTYPE html>
		<html>

		<head>
			<link href="bootstrap/css/bootstrap.min.css" media="screen" rel="stylesheet" type="text/css" />
		</head>

		<body>
		<section class="bg padding30" id="padd_none">
			<div class="container-fluid dashboard">
				<div class="col-lg-2 col-md-3 col-sm-4 profile" style="margin-left:0">
					<?php
						include "includes/sidebar.php";
					?>
				</div>
				<div class="col-lg-10 col-md-9 col-sm-8 dashboard" style="    padding-top: 0;">
					<?php
						include "pages/chat_with_rider/chat.php";
					?>
				</div>
			</div>
		</section>
		</div>
	<?php

} else {
	header("location:index.php");
}
	?>

	<?php
	
		include 'includes/footer.php';
	?>
	<!-- <script type="text/javascript">
		jQuery(document).bind("keyup keydown", function(e) {
			e.preventDefault();
			if (e.ctrlKey && e.keyCode == 80) {
				$('.print_btn').trigger('click');
			}
		});
		$('body').on('click', '.print_btn', function(e) {
			e.preventDefault();
			var invoice = $(this).attr('href');
			window.open(invoice, 'mywindow', 'width = 800, height = 800');
		})
	</script> -->
	<?php if (isset($_GET['print'])) { ?>
		</body>
	<?php } ?>

	<script>
		document.addEventListener('DOMContentLoaded', function() {
			$('title').text($('title').text() + ' Ledger Payments')
		}, false);
	</script>