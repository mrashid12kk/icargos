<?php
session_start();
if(isset($_SESSION['branch_id']) && !empty($_SESSION['branch_id'])){
	$branch_id = $_SESSION['branch_id'];
}else{
	$branch_id = 1;
}
	require '../../includes/conn.php';
	function addQuote($value){
		return(!is_string($value)==true) ? $value : "'".$value."'";
	}
	 
	if(isset($_POST['submit'])){

		$data = $_POST;

		

		$pricing = $data['pricing'];
		unset($data['pricing']);
		if(trim($_POST['password']) == trim($_POST['repassword'])){
			$send = true;
		}else{
			$send = false;
			$_SESSION['fail_add'] = 'Password not matched, please try another';
			header("Location:".$_SERVER['HTTP_REFERER']);
		}
		$check_email = mysqli_query($con,"SELECT * FROM customers WHERE email='".$data['email']."' ");
		if(mysqli_num_rows($check_email) >0){
			$send = false;
			$_SESSION['fail_add'] = 'Email already exist, please try another';
			header("Location:".$_SERVER['HTTP_REFERER']);
		}
		else{
		if($send) {

				// $_POST['emirates_id']==$target_file;
				 $password= md5($_POST['password']);

				$_POST['password']=$password;
				// $_POST['address']=implode(',,',$_POST['address']);
				$data = $_POST;
				$data['cnic_copy'] = $target_file;

				$data['bname'] = mysqli_real_escape_string($con,$data['bname']);
				$data['address'] = mysqli_real_escape_string($con,$data['address']);
				$data['fname'] = mysqli_real_escape_string($con,$data['fname']);
				$data['branch_id'] = mysqli_real_escape_string($con,$data['branch_id']);
				$data['is_saletax'] =1;
				$data['is_fuelsurcharge'] =1;
				$data['status'] =1;
				if(isset($data['submit']))
					unset($data['submit']);
					unset($data['repassword']);
					unset($data['c_payable']);
					unset($data['p_payable']);
					unset($data['p_acc_id']);
					unset($data['c_recievable']);
					unset($data['p_recievable']);
					unset($data['r_acc_id']);
				$email = $data['email'];
				$index = 0;
				if (isset($_FILES["image"]["name"]) and !empty($_FILES["image"]["name"]))
			    {
			        $target_dir = "../../../users/";
			        $target_file = $target_dir .uniqid(). basename($_FILES["image"]["name"]);

			        // $db_dir = "users/";
			        // $db_file = $db_dir .uniqid(). basename($_FILES["image"]["name"]);

			        $extension = pathinfo($target_file,PATHINFO_EXTENSION);
			         if($extension=='jpg'||$extension=='png'||$extension=='JPG' ||$extension=='PNG' ||$extension=='gif' ||$extension=='GIF'||$extension=='JPEG '||$extension=='jpeg ') {
			            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file))
			            {
			            // echo $target_file;
			            	$target_file=trim($target_file , '../../../');
			                $data['image'] =$target_file;
			            }
			        }
			        else{
			        	$_SESSION['fail_add'] = 'Your Logo Image Type in Wrong<br>';
			        	header("Location:".$_SERVER['HTTP_REFERER']);
			        	 exit();
			        }
			    }
			    if (isset($_FILES["cnic_copy"]["name"]) and !empty($_FILES["cnic_copy"]["name"]))
			    {
			        $target_dir = "../../../cnic_copy/";
			        $target_file = $target_dir .uniqid(). basename($_FILES["cnic_copy"]["name"]);

			        // $db_dir = "users/";
			        // $db_file = $db_dir .uniqid(). basename($_FILES["cnic_copy"]["name"]);

			        $extension = pathinfo($target_file,PATHINFO_EXTENSION);
			         if($extension=='jpg'||$extension=='png'||$extension=='JPG' ||$extension=='PNG' ||$extension=='JPEG '||$extension=='jpeg ') {
			            if (move_uploaded_file($_FILES["cnic_copy"]["tmp_name"], $target_file))
			            {
			            // echo $target_file;
			            	$target_file=trim($target_file , '../../../');
			                $data['cnic_copy'] =$target_file;
			            }
			        }
			        else{
			        	$_SESSION['fail_add'] = 'Your Logo Image Type in Wrong<br>';
			        	header("Location:".$_SERVER['HTTP_REFERER']);
			        	 exit();
			        }
			    }
			    if(isset($data['is_booking_manual']) && $data['is_booking_manual']==1){
			    	$data['is_booking_manual']=$data['is_booking_manual'];
			    }
			    else{
			    	$data['is_booking_manual']=0;
			    }
				foreach ($data as $key => &$value) {
					if(trim($value) == '') {
						array_splice($data, $index, 1);
						$index--;
					}
					$index++;
				}

				foreach ($data as $k => &$value) {
					$value =addQuote($value);
				}

				$keys = implode(", ", array_keys($data));
				$values = implode(",",$data);
				// var_dump($data);
				$sql = "INSERT INTO customers ($keys) VALUES($values)";
				$query=mysqli_query($con,$sql) or die(mysqli_error($con));
				$customer_id = mysqli_insert_id($con);
				$url = 'admin/bussiness_account_sheet.php?account_no='.$customer_id;
				$_SESSION['print_url'] = $url;
				$code = 1000 + $customer_id;
				$query5=mysqli_query($con, "UPDATE customers SET client_code = '".$code."',language_priority='".getConfig('customer_language_priority')."'  WHERE id = ".$customer_id);
			 	$rowscount=mysqli_affected_rows($con);
			 	
			}
			if($send == true && $rowscount>0 )
			{
				$code = 1000 + $customer_id;
				mysqli_query($con, "UPDATE customers SET client_code = '".$code."',created_by='".$branch_id."' WHERE id = ".$customer_id);

			 	$id=mysqli_insert_id($con);
			  	$query=mysqli_query($con,"SELECT * FROM customers WHERE client_code=$code") or die(mysqli_error($con));
				$fetch=mysqli_fetch_array($query);
				$customerName = $fetch['bname'];
				$ledgercode = mysqli_query($con , "SELECT MAX(`ledgerCode`) AS `ledgerCode` FROM `tbl_accountledger`");
				$getLedgerCode = mysqli_fetch_assoc($ledgercode);

				$ledgerCode = $getLedgerCode['ledgerCode'];
				if(!empty($ledgerCode)){
						$ledgerCode = $ledgerCode + 1 ;
				}else{
						$ledgerCode=13;	
				}
				$dateToday = date("Y-m-d H:i:s");
				if(!empty($_POST['c_payable']) && !empty($_POST['p_payable']) && !empty($_POST['p_acc_id'])){
					$sql =  "INSERT into tbl_accountledger (`ledgerCode`,`ledgerName`,`customer_id`,`created_on`,`accountGroupId`,`chart_account_id`, `chart_account_id_child`,`branchCode`) VALUES ('".$ledgerCode."', '".$customerName."', '".$customer_id."','".$dateToday."','".$_POST['p_acc_id']."', '".$_POST['p_payable']."', '".$_POST['c_payable']."', ".$data['branch_id']." )";
					// echo $sql;
					$query1 =mysqli_query($con ,$sql);

				}
				if($query1 == TRUE){
						$ledgerCode = $ledgerCode+1;
					}else{
						$ledgerCode = $ledgerCode;
					}

				if(!empty($_POST['c_recievable']) && !empty($_POST['p_recievable']) && !empty($_POST['r_acc_id'])){
						$sql =  "INSERT into tbl_accountledger (`ledgerCode`,`ledgerName`,`customer_id`,`created_on`,`accountGroupId`,`chart_account_id`, `chart_account_id_child`,`branchCode`) VALUES ('".$ledgerCode."', '".$customerName."', '".$customer_id."','".$dateToday."','".$_POST['r_acc_id']."', '".$_POST['p_recievable']."', '".$_POST['c_recievable']."', ".$data['branch_id']." )";
						// echo $sql;
					$query1 = mysqli_fetch_array(mysqli_query($con ,$sql));
				}
				

			}
		
			$_SESSION['succ_msg'] = 'Account created successfully';
			header("Location:".$_SERVER['HTTP_REFERER']);

	}
}