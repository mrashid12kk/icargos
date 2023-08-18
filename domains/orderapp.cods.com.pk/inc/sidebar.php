<?php
include_once('inc/conn.php');
$current=$_SERVER['REQUEST_URI'];
$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH); 
$current = end(explode('/', $url));
 ?>
<!DOCTYPE html>
<html>
<head>
  <title>Courier Shipping APP - IT Vision</title>
  <link rel="stylesheet" href="<?php echo BASE_URL ?>css/bootstrap.css" >
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL ?>css/style.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL ?>css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL ?>css/dataTables.bootstrap.min.css">


</head>
<body>

  <div class="main_content">
  <div class="row">
    <div class="col-sm-12 sidebar">
      <ul>
        <li>
          <a  href="<?php echo BASE_URL ?>dashboard.php" class="<?php if($current == 'dashboard.php'){ echo 'active'; } ?>">
            <svg  viewBox="0 0 24 24"><path d="M16 8.414l-4.5-4.5L4.414 11H6v8h3v-6h5v6h3v-8h1.586L17 9.414V6h-1v2.414zM2 12l9.5-9.5L15 6V5h3v4l3 3h-3v7.998h-5v-6h-3v6H5V12H2z" fill="#fff"/></svg>
            Dashboard
          </a>
        </li>
        
        <li>
          <a href="<?php echo BASE_URL ?>create_orders.php" class="<?php if($current == 'create_orders.php'){ echo 'active'; } ?>">
            <svg  viewBox="0 0 24 24"><path d="M16 18a2 2 0 1 1 0 4a2 2 0 0 1 0-4zm0 1a1 1 0 1 0 0 2a1 1 0 0 0 0-2zm-9-1a2 2 0 1 1 0 4a2 2 0 0 1 0-4zm0 1a1 1 0 1 0 0 2a1 1 0 0 0 0-2zM18 6H4.273l2.547 6H15a.994.994 0 0 0 .8-.402l3-4h.001A1 1 0 0 0 18 6zm-3 7H6.866L6.1 14.56L6 15a1 1 0 0 0 1 1h11v1H7a2 2 0 0 1-1.75-2.97l.72-1.474L2.338 4H1V3h2l.849 2H18a2 2 0 0 1 1.553 3.26l-2.914 3.886A1.998 1.998 0 0 1 15 13z" fill="#fff"/></svg>
            Orders
          </a>
        </li>
        <li>
          <a href="<?php echo BASE_URL ?>api_orders.php" class="<?php if($current == 'api_orders.php'){ echo 'active'; } ?>" >
            <svg viewBox="0 0 24 24"><path d="M5 7h3V5l2-2h3l2 2v2h3a3 3 0 0 1 3 3v8a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3v-8a3 3 0 0 1 3-3zm5.414-3L9 5.414V7h5V5.414L12.586 4h-2.172zM5 8a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h13a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2H5z" fill="#fff"/></svg>
            Booked Packets
          </a>
        </li>
        <!---<li>
          <a href="<?php echo BASE_URL ?>load_sheet.php" class="<?php if($current == 'load_sheet.php'){ echo 'active'; } ?>" >
            <svg viewBox="0 0 24 24"><path d="M5 7h3V5l2-2h3l2 2v2h3a3 3 0 0 1 3 3v8a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3v-8a3 3 0 0 1 3-3zm5.414-3L9 5.414V7h5V5.414L12.586 4h-2.172zM5 8a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h13a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2H5z" fill="#fff"/></svg>
            Load Sheets
          </a>
        </li>----->
        <li>
          <a href="<?php echo BASE_URL ?>preferences.php" class="<?php if($current == 'preferences.php'){ echo 'active'; } ?>" >
            <svg viewBox="0 0 24 24"><path d="M14 20a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2h1a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1h1zm1-3a2 2 0 0 1-2 2h-3a2 2 0 0 1-2-2v-2.022a6.5 6.5 0 1 1 7 0V17zm-6 0a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-2.6a5.5 5.5 0 1 0-5 0V17zm-.871-6.879L10.5 7.75l2 2L14.25 8l.707.707l-2.457 2.457l-2-2l-1.664 1.664l-.707-.707z" fill="#fff"/></svg>
            Integrations
          </a>
        </li>
        <li>
          <a href="https://cods.com.pk/" target="_blank">
            <svg viewBox="0 0 24 24"><path d="M14 20a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2h1a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1h1zm1-3a2 2 0 0 1-2 2h-3a2 2 0 0 1-2-2v-2.022a6.5 6.5 0 1 1 7 0V17zm-6 0a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-2.6a5.5 5.5 0 1 0-5 0V17zm-.871-6.879L10.5 7.75l2 2L14.25 8l.707.707l-2.457 2.457l-2-2l-1.664 1.664l-.707-.707z" fill="#fff"/></svg>
            Login to CODS Courier
          </a>
          </li>
              <li>
          <a href="https://orderapp.cods.com.pk/setup_instruction/CODS_Shopify_App_Setup_Instructions.pdf" target="_blank">
            <svg viewBox="0 0 24 24"><path d="M14 20a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2h1a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1h1zm1-3a2 2 0 0 1-2 2h-3a2 2 0 0 1-2-2v-2.022a6.5 6.5 0 1 1 7 0V17zm-6 0a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-2.6a5.5 5.5 0 1 0-5 0V17zm-.871-6.879L10.5 7.75l2 2L14.25 8l.707.707l-2.457 2.457l-2-2l-1.664 1.664l-.707-.707z" fill="#fff"/></svg>
            Setup Manual
          </a>
        </li>
        <!-- <li>
          <a href="#">
            <svg  viewBox="0 0 24 24"><path d="M2 4h1v16h2V10h4v10h2V6h4v14h2v-6h4v7H2V4zm16 11v5h2v-5h-2zm-6-8v13h2V7h-2zm-6 4v9h2v-9H6z" fill="#fff"/></svg>
            Load Sheets
          </a>
        </li>
        <li>
          <a href="#">
            <svg  viewBox="0 0 24 24"><path d="M3 4h17v17H3V4zm1 1v7h7V5H4zm15 7V5h-7v7h7zM4 20h7v-7H4v7zm15 0v-7h-7v7h7z" fill="#fff"/></svg>
            Integration
          </a>
        </li> -->
      </ul>
    </div>