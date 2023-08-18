<?php
include_once('inc/conn.php');
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Courier Shipping APP - CODS Courier</title>
	<link rel="stylesheet" href="<?php echo BASE_URL ?>css/bootstrap.css" >
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL ?>css/style.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL ?>css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL ?>css/dataTables.bootstrap.min.css">


</head>
<body>
	<!-- Fixed navbar -->
    <nav class="navbar navbar-default">
      <div class="container_box">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo BASE_URL ?>index.php">Courier Shipping App</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <!-- <li><a href="<?php echo BASE_URL ?>index.php">Dashboard</a></li> -->
            <li><a href="<?php echo BASE_URL ?>index.php">Preferences</a></li>
            <li><a href="<?php echo BASE_URL ?>create_orders.php">Create Orders</a></li>
            <li><a href="<?php echo BASE_URL ?>api_orders.php">API Orders</a></li>
            
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>