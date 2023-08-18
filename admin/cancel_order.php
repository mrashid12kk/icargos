<?php
session_start();
include_once "includes/conn.php";
function decrypt($string) {
  $key="usmannnn";
  $result = '';
  $string = base64_decode($string);
  for($i=0; $i<strlen($string); $i++) {
    $char = substr($string, $i, 1);
    $keychar = substr($key, ($i % strlen($key))-1, 1);
    $char = chr(ord($char)-ord($keychar));
    $result.=$char;
}
return $result;
}


if(isset($_GET['cancel_id'])){
    $ex = explode('-',decrypt($_GET['cancel_id']));
    $order_id = $ex[0];
    $query_data = mysqli_query($con,"SELECT * FROM orders WHERE id =".$order_id." ");
    $record = mysqli_fetch_array($query_data);
    mysqli_query($con,"UPDATE orders SET status ='cancelled', is_received =2 WHERE id=".$order_id." ");
    mysqli_query($con,"INSERT INTO order_logs(`order_no`,`order_status`) VALUES ('".$record['track_no']."', 'Order is Cancelled') ");
    $message = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Order Cancelled Sucessfully.</div>';
    header('Location: view_order.php?message='.$message);
}

if(isset($_GET['delete_id']))
{
    $ex = explode('-',decrypt($_GET['delete_id']));
    $order_id = $ex[0];

    $query_data = mysqli_query($con,"SELECT * FROM orders WHERE id =".$order_id." ");
    $record     = mysqli_fetch_array($query_data);


    mysqli_query($con,"DELETE FROM orders  WHERE id=".$order_id." ");
    mysqli_query($con,"INSERT INTO order_logs(`order_no`,`order_status`) VALUES ('".$record['track_no']."', 'Order is DELETED') ");
    $message = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Order Deleted Sucessfully.</div>';
    header('Location: view_order.php?message='.$message);
    
}

if(isset($_GET['delete_id_new']))
{
    $ex = explode('-',decrypt($_GET['delete_id_new']));
    $order_id = $ex[0];

    $query_data = mysqli_query($con,"SELECT * FROM orders WHERE id =".$order_id." ");
    $record     = mysqli_fetch_array($query_data);
    

    mysqli_query($con,"DELETE FROM orders  WHERE id=".$order_id." ");
    mysqli_query($con, "DELETE FROM order_commercial_invoice WHERE order_id='" . $order_id . "'");
    mysqli_query($con,"INSERT INTO order_logs(`order_no`,`order_status`) VALUES ('".$record['track_no']."', 'Order is DELETED') ");
    $message = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Order Deleted Sucessfully.</div>';
    header('Location: view_order.php?message='.$message);
    
}

?>