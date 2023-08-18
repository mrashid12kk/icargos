<?php
//date_default_timezone_set("Asia/Karachi");
if(isset($_SESSION['users_id'])) {

    $user = mysqli_query($con, "SELECT * FROM users WHERE id = ".$_SESSION['users_id']);
    $user = ($user) ? mysqli_fetch_object($user) : null;
    $logo_img = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='logo' "));
    $webfavicon = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='webfavicon' "));
    $timezone = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='timezone' "));
    $webtitle = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='webtitle' "));

}
// date_default_timezone_set($timezone['value']);
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo $webtitle['value'] ?></title>
    <link rel="icon" href="<?php echo BASE_URL?>assets/<?php echo $webfavicon['value'] ?>">
	<meta name="description" content="">
	<meta name="author" content="Akshay Kumar">
	<!-- Bootstrap core CSS -->
    <!-- <link rel="stylesheet" href="assets/css/inputmask.css" />  -->
	<link rel="stylesheet" href="<?php echo BASE_URL?>admin/assets/css/bootstrap/bootstrap.css" />
    <link rel="stylesheet" href="<?php echo BASE_URL?>admin/assets/css/chosen.css" /> 
    <link href='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/css/select2.min.css' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.12.5/sweetalert2.css">
	<!-- DateTime Picker  -->
    <link rel="stylesheet" href="<?php echo BASE_URL?>admin/assets/css/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.css" />
    <!-- <link rel="stylesheet" href="../assets/css/select2.min.css" /> -->
    <!-- Fonts  -->
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Base Styling  -->
    <link rel="stylesheet" href="<?php echo BASE_URL?>admin/assets/css/style.css" />
    <link rel="stylesheet" href="<?php echo BASE_URL?>admin/assets/css/app/app.v1.css" />
    
    <link rel="stylesheet" href="<?php echo BASE_URL?>admin/assets/css/sms.css" />
    <link rel="stylesheet" href="<?php echo BASE_URL?>admin/assets/css/datatables.min.css" />
    <link rel="stylesheet" href="<?php echo BASE_URL?>admin/assets/summernote/summernote.css">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">


     <link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/css/intlTelInput.css" rel="stylesheet" media="screen">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/intlTelInput.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/utils.js"></script>
    
</head>
<div id="<?php if(isset($dynamic_id) && !empty($dynamic_id)){ echo $dynamic_id;}?>">
