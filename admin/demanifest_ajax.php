<?php

	session_start();

	include_once 'includes/conn.php';
	include_once 'includes/role_helper.php';

	if(isset($_POST['manifest']) && !empty($_POST['manifest'])){
		$manifest=$_POST['manifest'];
        $truck_query = '';
        if (isset($_POST['truck_no']) && !empty($_POST['truck_no'])) {
            $truck_query  = " AND truck_no=".$_POST['truck_no'];
        }
		$cnno_query="";

		if(isset($_POST['track_no']) && $_POST['track_no']!=''){
			$cnno_query .= " AND track_no = '".$_POST['track_no']."' ";
		}
        $r_b_q =mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM manifest_master WHERE manifest_no=".$manifest_no));
        $receiving_branch = $r_b_q['receiving_branch'];
        $receiving_branch_query = '';
        if (isset($receiving_branch) && !empty($receiving_branch)) {
            $branch_id=1;
            if (isset($_SESSION['branch_id']) && !empty($_SESSION['branch_id'])) {
                $branch_id = $_SESSION['branch_id'];
            }
            $receiving_branch_query .= " AND manifest_master.receiving_branch = $branch_id";
        }
		$total=array();
		$query=mysqli_query($con,"SELECT manifest_detail.track_no, manifest_detail.manifest_no, manifest_detail.is_demanifest, manifest_master.sending_branch, manifest_master.receiving_branch from `manifest_detail` LEFT JOIN manifest_master ON manifest_detail.manifest_no = manifest_master.manifest_no  WHERE manifest_detail.manifest_no=".$manifest." $receiving_branch_query ");

		$sr=1;
		$total_q=0;
		$total_wt=0;
        $already_demanifested='';
		while ($row=mysqli_fetch_array($query)){

			$track_no= '';

            if ($row['is_demanifest']==0) {
                $track_no= $row['track_no'];
            }
            if ($row['is_demanifest']==1) {
              $already_demanifested .= "'".$row['track_no']."'".',';
            }


				$query2=mysqli_query($con,"SELECT * from `orders` where track_no='".$track_no."'")or die(mysqli_error($con));
			while ($row2=mysqli_fetch_assoc($query2)){
				$total_q+=$row2['quantity'];
				$total_wt+=$row2['weight'];
				$total['table'].= "<tr><td></td><input type='hidden' name='all_cn_no[]' class='all_cn_no' value='".$row2['track_no']."' /><td>".$sr++."</td><td>".$row2['track_no']."</td><input type='hidden' class='hidden_qunatity_value' value=".$row2['quantity']. " /><td>".$row2['quantity']." </td><td> ".$row2['weight']."</td><td ></td><td ><a class='delect_row' data-wt='".$row2['weight']."' data-qt='".$row2['quantity']."' title='Trash'><i class='fa fa-trash' ></i></a></td><input type='hidden'class='track_no' name='track_no[]' value='".$row2['track_no']."'><input type='hidden' class='hidden_weight' value=".$row2['weight']. " /><input type='hidden'class='receiving_branch' name='receiving_branch[]' value='".$receiving_branch_number['receiving_branch']."'></tr>";

			}

		}



		$query3=mysqli_query($con,"SELECT count(track_no) as count from `manifest_detail` where is_demanifest=0 AND manifest_no=".$manifest)or die(mysqli_error($con));
		$row3=mysqli_fetch_assoc($query3);

        $demanifes_array = trim($already_demanifested,',');

        $comma_query = mysqli_query($con,"SELECT GROUP_CONCAT(DISTINCT track_no SEPARATOR ',') as tracks FROM manifest_detail WHERE is_demanifest = 1 AND track_no IN(".$demanifes_array.") ");

        $row4=mysqli_fetch_assoc($comma_query);
        if (isset($row4) && !empty($row4)) {
            $total['error']=1;
            $total['msg']= $row4['tracks']. " Already Demanifested";
        }

        $total['cn']=$sr;
        $total['qt']=$total_q;
        $total['wt']=$total_wt;
        echo json_encode($total);
        exit;


	}

    if(isset($_POST['demanifest_no'])&& !empty($_POST['demanifest_no'])){
        $demani_no = $_POST['demanifest_no'];
        $mani_no = $_POST['manifest_no'];
        $query=mysqli_query($con,"SELECT orders.track_no, orders.quantity, orders.weight, manifest_detail.is_demanifest FROM `orders` join manifest_detail on orders.track_no = manifest_detail.track_no WHERE orders.track_no = '".$_POST['demanifest_no']."' AND manifest_detail.manifest_no =".$_POST['manifest_no']." AND manifest_detail.is_demanifest = 0");

        $sr_no = isset($_POST['length'] ) ? $_POST['length'] : 0;

        // $condition= 1;
        if (mysqli_num_rows($query) > 0) {


        while($row2=mysqli_fetch_array($query)){

            echo "<tr>";
                echo "<td></td><td>".++$sr_no."</td>";
                echo "<td>".$row2['track_no']."<input type='hidden' name='all_cn_no[]' class='all_cn_no' value='".$row2['track_no']."' /></td>";
                echo "<td>".$row2['quantity']." </td><input type='hidden' class='hidden_qunatity_value' value=".$row2['quantity']. " />";
                echo "<td> ".$row2['weight']."</td><input type='hidden' class='hidden_weight' value=".$row2['weight']. " />";
                echo "<td ></td>";
                echo "<td ><a class='delect_row' data-wt='".$row2['weight']."' data-qt='".$row2['quantity']."' title='Trash'><i class='fa fa-trash' ></i></a></td><input type='hidden'class='track_no' name='track_no[]' value='".$row2['track_no']."'><input type='hidden'class='receiving_branch' name='receiving_branch[]' value='".$receiving_branch_number['receiving_branch']."'>";
            echo '</tr>';

        }
    }else{
        echo 'No Record Found';
    }

    }

// 	if(isset($_POST['cnno']) && $_POST['cnno']!=''){
// 		$manifest_no=$_POST['manifest_no'];
// 		$track_no=$_POST['track_no'];
// 		$demanifest_id=$_POST['demanifest_id'];
// 		$arrive_date=$_POST['arrival_date'];
// 		$branch_id=$_POST['branch_id'];
// 		$truck_no=$_POST['truck_no'];
// 		$total_cn=$_POST['total_cn'];
// 		$total_pieces=$_POST['total_pieces'];
// 		$total_weight=$_POST['total_weight'];
// 		$received_report=$_POST['received_report'];
// 		$query=mysqli_query($con,"INSERT into `demanifest_master` (manifest_no,demanifest_no,arrive_date,branch_id,truck_no,total_cn,total_pieces,total_weight,received_report)values('$manifest_no','$demanifest_id','$arrive_date','$branch_id','$truck_no','$total_cn','$total_pieces','$total_weight','$received_report')")or die(mysqli_error($con));
//     $last_id = mysqli_insert_id($con);
//     if($last_id){

//         foreach ($_POST['track_no'] as $key => $value) {

//             $inQ = "INSERT INTO `demanifest_detail`(`demanifest_id`, `track_no`, `demanifest_no`) VALUES (".$last_id.",'".$value."',".$_POST['demanifest_id'].")";

//             $up_ma_q = "UPDATE manifest_detail set is_demanifest = 1 where track_no = '".$value."'";

//             $updateQuery = mysqli_query($con,$up_ma_q);


//             $insertQu = mysqli_query($con,$inQ);

//             $changeBranchQ = "UPDATE orders set status='".$_POST['status']."' , current_branch= ".$_POST['branch_id']." WHERE track_no = '".$value."'";

//             // echo $changeBranchQ;

//             $order_logs_q = "INSERT INTO `order_logs`(`branch_id`, `assign_branch`, `order_no`, `order_status`) VALUES (".$_POST['branch_id'].",".$_POST['branch_id'].",'".$value."','".$_POST['status']."')";
//             // echo  $order_logs_q;
//             $order_log = mysqli_query($con,$order_logs_q);
//             $changeBranch = mysqli_query($con,$changeBranchQ);



//         }
// // die();
//        if(mysqli_affected_rows($con)>0){
// 			echo  '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> You Added Demanifest Detail successfully</div>';

// 			}
// 			else{
// 				echo  '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not Added a Demanifest Detail .</div>';
//     }

// 	}
// }


	?>
