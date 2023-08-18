 <?php

session_start();

include_once 'includes/conn.php';
include_once 'includes/role_helper.php';

if (isset($_POST['ballance'])) {

      $data  = array();
      $balance=0;
    
    $empQueryd =  "SELECT * from rider_wallet_ballance";

    $empRecordsd = mysqli_query($con, $empQueryd);
    while ($fetch1 = mysqli_fetch_assoc($empRecordsd)) {
         // print_r($fetch1);
         // die;
        $balance +=$fetch1['balance'];  
    }
  
    $data['balance']=number_format((float)$balance,2);
    
    echo json_encode($data);
}
?>