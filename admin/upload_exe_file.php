<?php
session_start();
require 'includes/conn.php';
if (isset($_SESSION['users_id']) && ($_SESSION['type'] !== 'driver' && $_SESSION['type'] == 'admin')){
    require_once "includes/role_helper.php";
    if(!checkRolePermission($_SESSION['user_role_id'], 8, 'add_only', $comment = null)){
        header("location:access_denied.php");
    }
    include "includes/header.php";
    ?>
    <style>
        .upload_excel_file .change_file_name {
            background: unset;
        }
        .upload_excel_file .m_zero .filelabel {
    border: 1px solid #3333 !important;
    border-radius: 5px;
    display: block;
    padding: 16px;
    transition: border 300ms ease;
    cursor: pointer;
    text-align: center;
    box-shadow: 4px 2px 7px 0px #00000021;
    margin: 0;
}

        .upload_excel_file .change_file_name {
            border: unset;
            width: 100%;
            color: #010101 !important;
            font-weight: 500;
        }
        .upload_excel_file .buttons #submit {
            width: 100%;
            padding: 7px 0;
            font-size: 18px;
            font-weight: 500;
            box-shadow: 4px 2px 7px 0px #00000021;
            margin: 8px 0 10px;
    }
        .form-control, .input-group-addon, .bootstrap-select .btn {
            background-color: #ffffff;
            border-color: #ccc;
            border-radius: 3px;
            box-shadow: none;
            color: #000;
            font-size: 14px;
            height: 34px;
            padding: 0 20px;
            font-weight: 300;
        }
        a {
            text-decoration: none !important;
        }
        .upload_excel_file .buttons label {
                margin: 20px 0 10px;
                font-weight: 600;
                font-size: 20px;
                color: #286fad;
            }
        input::-webkit-input-placeholder,
        textarea::-webkit-input-placeholder {
            color: #b8b8b8 !important;
        }
        input:-moz-placeholder,
        textarea:-moz-placeholder {
            color: #b8b8b8 !important;
        }
        input::-moz-placeholder,
        textarea::-moz-placeholder {
            color: #b8b8b8 !important;
        }
        input:-ms-input-placeholder,
        textarea:-ms-input-placeholder {
            color: #b8b8b8 !important;
        }
        label {
            font-weight: bold;
        }
        .hide_city {
            display: none;
        }
        .btn-purple:hover,
        .btn-purple:focus {
            color: #fff !important;
        }
        .calculation_label {
            font-size: 11px !important;
        }
    </style>
    
    <body data-ng-app>
        <?php
        include "includes/sidebar.php";
        ?>
        <!-- Aside Ends-->
        <section class="content">
            <?php
            include "includes/header2.php";
            ?>
            <!-- Header Ends -->
            <div class="warper container-fluid upload_excel_file">
                <!-- <div class="page-header"><h1>Dashboard <small>Let's get a quick overview...</small></h1></div> -->
                <?php
                include "pages/reports/upload_exe_file.php";
                ?>
            </div>
            <!-- Warper Ends Here (working area) -->
            <?php
            include "includes/footer.php";
        } else {
            header("location:index.php");
        }
        ?>
