<?php
  session_start();
  require 'includes/conn.php';
  $language = mysqli_query($con,"SELECT * FROM portal_language WHERE is_default=1");
    $response = mysqli_fetch_assoc($language);
    if (!isset($response) && empty($response)) {
        $response = array(
            'language'=>'english',
            'direction'=>'ltr',
            'id'=>1
        );
    }
  if(isset($_POST['customer_id'])) {
    $query=mysqli_query($con,"select * from customers where  id = ".$_POST['customer_id']);
    $count=mysqli_affected_rows($con);

    if($count>0){

      $fetch=mysqli_fetch_array($query);

      $_SESSION['customers']=$fetch['id'];
      $_SESSION['language']=$response['language'];
      $_SESSION['language_id']=$response['id'];
      $_SESSION['customer_type']=$fetch['customer_type'];
      $_SESSION['address']=$fetch['address'];
      $_SESSION['customer_type']=$fetch['customer_type'];
      $_SESSION['user_customer_id']='';
      mysqli_query($con, "UPDATE customers SET is_online = 1 WHERE id = ".$fetch['id']);

      echo '<script>window.location.href="../profile.php";</script>';
      exit();
  }
}
header('Location: '.$_SERVER['HTTP_REFERER']);
exit();
