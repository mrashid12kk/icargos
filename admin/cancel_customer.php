<?php
session_start();
include_once "includes/conn.php";
if (isset($_GET['delete_id'])) {
  $id = $_GET['delete_id'];
  mysqli_query($con, "DELETE FROM customers WHERE id=" . $id . " ");
                                $sql = "SELECT * FROM `tbl_accountledger` WHERE `customer_id` = '". $id."'";
                                $query = mysqli_fetch_array(mysqli_query($con, $sql));
                                
                                $sql1 = "SELECT * FROM `tbl_ledgerposting` where ledgerId = '".$query['id']."'" ;
                                $q = mysqli_query($con, $sql1);
                                if(mysqli_num_rows($q) == 0){
                                	mysqli_query($con, "DELETE FROM tbl_accountledger WHERE customer_id='" . $id . "'");
                                }
  $_SESSION['message']  = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Customer is Deleted Sucessfully.</div>';
  header('Location: pendingbusinessacc.php');
}