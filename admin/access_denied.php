<?php

session_start();

require 'includes/conn.php';

if (isset($_SESSION['users_id'])) {
	require_once "includes/role_helper.php";
	if (checkRolePermission($_SESSION['user_role_id'], 1, 'view_only', $comment = null)) {

		header("location:index.php");
	}

	include "includes/header.php";

?>

<body data-ng-app style="background:#fff; ">

    <style type="text/css">
    #basic-datatable button {

        width: auto !important;

    }

    #basic-datatable form {

        display: inline;

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
        <div class="warper container-fluid" style="background:#fff; ">


            <div class="access_denied" style="background:#fff; ">
                <div class="head_denied">
                    <h3>Access Denied</h3>
                </div>
                <img src="images/access-denied.jpg">
                <div class="head_denied">
                    <a href="dashboard.php">Go to Dashboard</a>
                </div>
            </div>

        </div>

        <!-- Warper Ends Here (working area) -->

        <?php
		include "includes/footer.php";
	} else {

		header("location:index.php");
	}

		?>