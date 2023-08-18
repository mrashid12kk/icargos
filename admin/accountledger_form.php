<?php
// ini_set('display_errors', 1);ini_set('display_startup_errors', 1);error_reporting(E_ALL);
session_start();
require 'includes/conn.php';
require 'includes/role_helper.php';
if(isset($_SESSION['users_id']) && $_SESSION['type']=='admin')
{
     require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'],30,'add_only',$comment =null)) {

        header("location:access_denied.php");
    }
    include "includes/header.php";
    ?>


        <!-- Header Ends -->
        <body data-ng-app>
            <style type="text/css">
                .display_none{
                    display: none;
                }
            </style>

        <?php include "includes/sidebar.php"; ?>
        <!-- Aside Ends-->

        <section class="content">

            <?php include "includes/header2.php"; ?>

<?php

function getAccountParentGroup($con,$under_id=null){
		if($under_id)
	{
		$query="SELECT * FROM tbl_accountgroup WHERE company_id IS NULL AND groupUnder = $under_id";
	}
	else
	{
		$query="SELECT * FROM tbl_accountgroup WHERE company_id IS NULL";
	}
	$record=mysqli_query($con, $query);
	$ret = array();
	if (mysqli_num_rows($record) > 1) {
  while($row = mysqli_fetch_assoc($record)) {
    $ret[] = $row;
}
  }
  else {
  $ret = mysqli_fetch_array($record);
}
	return $ret;
}


function checkGroupUnder($con ,$under_id = null ){
if($under_id!=null)
	{
		$query="SELECT * FROM tbl_accountgroup WHERE company_id IS NULL AND groupUnder = $under_id AND groupUnder!=0";
	}
	else
	{
		$query="SELECT * FROM tbl_accountgroup WHERE company_id IS NULL AND groupUnder = 0";
	}
	$record=mysqli_query($con, $query);
	$model = mysqli_fetch_array($record);
	if(!empty($model))
		return true;
	else
		return false;
}

function getAccountGroup($con, $id=null)
{
	if($id!=null)
	{
		
		$query="SELECT * FROM tbl_accountgroup WHERE company_id IS NULL AND id =".$id;
		$record=mysqli_query($con, $query);
		$model = mysqli_fetch_array($record);
		return isset($model['accountGroupName']) ? $model['accountGroupName']:'';
		
	}
}
$ledgercode = mysqli_query($con , "SELECT MAX(`ledgerCode`) AS `ledgerCode` FROM `tbl_accountledger`");
$getLedgerCode = mysqli_fetch_assoc($ledgercode);

$ledgerCode = $getLedgerCode['ledgerCode'];
if(!empty($ledgerCode)){
		$ledgerCode = $ledgerCode + 1 ;
}else{
		$ledgerCode=13;	
}
if(isset($_POST['submit']) && $_GET['edit']){
	
    $sql = mysqli_query($con, "SELECT voucherNo FROM tbl_ledgerposting where ledgerId = '". $_GET['edit']."'");
    $fetch =  mysqli_fetch_array($sql);
    	$v = $fetch['voucherNo'];
    	if(isset($v) && !empty($v)){
    		
      	$sqlvoucher = mysqli_query($con, "SELECT * FROM tbl_ledgerposting where `voucherNo` = '". $v."'");	
      	$fetchresult = mysqli_fetch_array($sqlvoucher);
      		if($_POST['opening_balance'] > 0){
      			if($_POST['nature'] == 'Credit'){
      		$sql1 ="UPDATE `tbl_ledgerposting` SET credit = '".$_POST['opening_balance']."' , debit = '0.00' WHERE ledgerId= '".$_GET['edit'] ."'";
      			$update = mysqli_query($con, $sql1);
      		$sql ="UPDATE `tbl_ledgerposting` SET debit = '".$_POST['opening_balance']."' , credit = '0.00' WHERE ledgerId = 15 AND  voucherNo= '".$v ."'";
      			$update = mysqli_query($con, $sql);
      		
      		}else{
      		$sql1 ="UPDATE `tbl_ledgerposting` SET debit = '".$_POST['opening_balance']."' , credit = '0.00' WHERE ledgerId= '".$_GET['edit'] ."'";
      			$update = mysqli_query($con, $sql1);
      		$sql ="UPDATE `tbl_ledgerposting` SET credit = '".$_POST['opening_balance']."' , debit = '0.00' WHERE ledgerId = 15 AND voucherNo= '".$v ."'";
      			$update = mysqli_query($con, $sql);
      		
      		}

      		}
      		else{
      			$sql1 ="DELETE FROM `tbl_ledgerposting` WHERE ledgerId= '".$_GET['edit'] ."'";
      			$update = mysqli_query($con, $sql1);
      		$sql ="DELETE FROM `tbl_ledgerposting` WHERE ledgerId = 15 AND voucherNo= '".$v ."'";
      			$update = mysqli_query($con, $sql);
      		}
  		}
	   $sql = "UPDATE `tbl_accountledger` SET ledgerCode = '".$_POST['ledgercode']."',ledgerName = '".$_POST['ledgerName']."' , chart_account_id = '".$_POST['chart_account_id_child']."',  accountGroupId =  '".$_POST['groupUnder']."' , crOrDr= '".$_POST['nature']."', extra1 = '".$_POST['description']."' , openingBalance =  '".$_POST['opening_balance']."', branchCode =   '".$_POST['branch']."'  WHERE id= '" . $_GET['edit'] . "'" ;
	  
     $update = mysqli_query($con, $sql);
    if($update){
    	
         echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong>  Details Updated successfully</div>'; 
   	 }
   	 else{
          echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> Error in sql </div>';
    	//echo mysqli_error($con);
    	}
}
else{
$sql = "SELECT * FROM `tbl_accountledger` where `ledgerName` like '".$_REQUEST['ledgerName']."'"; 
$match = mysqli_query($con, $sql);
$rec = mysqli_fetch_array($match);
$count1 = mysqli_num_rows($re);
$count = mysqli_num_rows($match);
if($count > 0){
    echo '<div class="alert alert-danger "><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong>Ledger Name Already Exist.</div>';
}else{

$insertquery = "INSERT INTO `tbl_accountledger` (`ledgerCode`, `ledgerName`, `chart_account_id_child`, `chart_account_id`, `accountGroupId`, `crOrDr`, `extra1`, `editable`, `openingBalance`,`branchCode`) VALUES ('".$_POST['ledgercode']."', '".$_POST['ledgerName']."', '".$_POST['chart_account_id_child']."', '".$_POST['chart_account_id_fgroup']."', '".$_POST['groupUnder']."', '".$_POST['nature']."', '".$_POST['description']."', 0, '".$_POST['opening_balance']."', '".$_POST['branch']."')";
$inser_sql = mysqli_query($con, $insertquery);
$id = $con->insert_id;
if($inser_sql){
   echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong>  Added Details successfully</div>';
}
$openingBalance = $_POST['opening_balance'];
if($openingBalance > 0){

	#select voucher number

	$sqlnumber  = mysqli_query($con, "SELECT MAX(`voucherNo`) as MAX FROM `tbl_ledgerposting` WHERE `voucherNo` LIKE '%OB%'
	");
	$fetchrecord = mysqli_fetch_array($sqlnumber);
	$explodedData =array_reverse(explode('-', $fetchrecord['MAX']));
	$index = $explodedData[0];
		$newIndex = $index+1;
		$explodedData[0]  = sprintf("%02d", $newIndex);
		$explodedData = array_reverse($explodedData);
		$voucherNo = implode('-', $explodedData);

// var_dump($id);
	#----------------------
	##insert against selected ledger start
	$insertledgerposting= "INSERT INTO `tbl_ledgerposting` (`ledgerId`, `voucherTypeId`,`voucherNo`, `is_opening`) VALUES ('".$id."',1, '".$voucherNo."' , 1)";
	$insert_sql = mysqli_query($con, $insertledgerposting);
	$ledgerpostingid = $con->insert_id;
	##insert against selected ledger end
	#----------------------
	##insert against opening balance ledger start
	$insertopeninbalance= "INSERT INTO `tbl_ledgerposting` (`ledgerId`, `voucherTypeId`,`voucherNo`, `is_opening`) VALUES ( 15, 1, '".$voucherNo."' , 1)";
	$insert_sql = mysqli_query($con, $insertopeninbalance);
	$openingbalanceid = $con->insert_id;
	##insert against opening balance ledger end
	#----------------------

	
	if($_POST['nature'] == 'Credit'){
 	$sql1 = "UPDATE `tbl_ledgerposting` SET credit = '".$_POST['opening_balance']."'   WHERE id= '".$ledgerpostingid ."'" ;
 	$update = mysqli_query($con, $sql1);
 	$sql = "UPDATE `tbl_ledgerposting` SET debit =  '".$_POST['opening_balance']."'   WHERE id= '".$openingbalanceid ."'" ;
     $update = mysqli_query($con, $sql);
	}else{
	$sql1 = "UPDATE `tbl_ledgerposting` SET debit  = '".$_POST['opening_balance']."'   WHERE id= '".$ledgerpostingid ."'" ;
	$update = mysqli_query($con, $sql1);
	$sql = "UPDATE `tbl_ledgerposting` SET credit = '".$_POST['opening_balance']."'   WHERE id= '".$openingbalanceid ."'" ;
     $update = mysqli_query($con, $sql);
	}
}
}
}

if(isset($_GET['edit'])){
 $groupledger = mysqli_query($con, "SELECT tbl_accountledger.* FROM tbl_accountledger where id = ".$_GET['edit']);   
}else{
$groupledger = mysqli_query($con, "SELECT `tbl_accountledger`.`id` as `id`, `tbl_accountledger`.`ledgerName` as `ledgerName`, `tbl_accountledger`.`company_id` as `company_id`, `tbl_accountledger`.`ledgerCode` as `ledgerCode`, `ledgerName`, `tbl_accountledger`.`openingBalance` as `openingBalance`, `tbl_accountledger`.`accountGroupId` as `accountGroupId`, `tbl_accountledger`.`crOrDr` as `crOrDr`, `tbl_accountledger`.`bankAccountNumber` as `bankAccountNumber`, `tbl_accountgroup`.`accountGroupName` as `accountGroupName` FROM `tbl_accountledger` INNER JOIN `tbl_accountgroup` ON `tbl_accountgroup`.`id` = `tbl_accountledger`.`accountGroupId` order by id desc");
}
$data = mysqli_fetch_array($groupledger);
$number = '';
$id = $data['id'] ;
if(!empty($id)){
    $number = $id + 1;
}
else{
    $number = 1;
}
 ?>

            <div class="warper container-fluid">
                <div class="alert alert-success display_none"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong>  Added Details successfully</div>
                <div class="alert alert-danger display_none"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong>Not Added a  Details .</div>
              <div class="warper container-fluid row">
                <div class="col-md-6">
                 <?php
                if(isset($_GET['edit']))
                {
                    ?>
                <form action="accountledger_form.php?edit=<?= $_GET['edit'];?>" method="post">
                <?php
                }
                else
                {
                    ?>
                <form action="accountledger_form.php" method="POST" role="form">
                <?php
                }
                ?>
            	
							        	<div class="panel panel-body" style="border: none;padding: 17px 3px 17px 10px;margin: 0;">
							        		<div class="row">
							        			<div class="col-sm-12 sidegapp">
									        		<div class="well well-height height29" style="margin-bottom: 0;">
									        			<div class="row">
                                                        
									        				<div class="col-sm-2 right-gapp">
									        					<div class="form-group ">
									        						<label>id</label>
									        					<input type="text" disabled="true" class="form-control"  value="<?= isset($_GET['edit'])? $data['id']:$number ?>">
									        					</div>
									        				</div>
									        				<!-- ledgerCode -->
									        				<div class="col-sm-4 right-gapp">
									        					<div class="form-group ">
									        						<label>Ledger Code</label>
									        						<input required="true" type="text" name="ledgercode" id="ledgercode" class="form-control" value="<?= isset($_GET['edit'])? $data['ledgerCode']:$ledgerCode ?>">
									        					</div>
									        				</div>
									        				<div class="col-sm-4 right-gapp">
									        					<div class="form-group ">
									        						<label>Name</label>
									        						<input required="true" type="text" name="ledgerName" id="ledgerName" class="form-control" value="<?= isset($_GET['edit']) ? $data['ledgerName'] : '' ;?>">
									        					</div>
									        				</div>

									        				<div class="col-sm-2 right-gapp">
									        					<div class="form-group ">
									        						<label>Code</label>
									        						<input readonly="" style="background-color: #F5F5F5 !important;" required="true" type="text" name="chart_account_id_child" class="form-control chart_account_id_child" value="<?= isset($_GET['edit']) ? $data['chart_account_id_child'] : '' ;?>" id="chart_account_id_child">
									        					</div>
									        				</div>


									        				<div class="col-sm-4 right-gapp">
									        					<div class="form-group ">
									        						<label>Parent Code</label>
									        						<input required="true" type="text" name="chart_account_id_fgroup" class="form-control chart_account_id_fgroup" value="<?= isset($_GET['edit']) ? $data['chart_account_id'] : '' ;?>" id="chart_account_id_fgroup">
									        					</div>
									        				</div>
							
									        		
									        					<div class="col-sm-4 right-gapp">
									        					<div class="form-group ">
									        						<label>Opening Balance</label>
									        						<input required="true" type="number" min="0" name="opening_balance" class="form-control opening_balance" value="<?= isset($_GET['edit']) ? $data['openingBalance'] : '' ;?>" id="opening_balance">
									        					</div>
									        				</div>

															<div class="col-sm-4 right-gapp">
																<div class="form-group">
									        						<label>Parent Name</label>
						 <?php
                            $parent = mysqli_query($con, "SELECT * FROM `tbl_accountgroup` WHERE company_id IS NULL AND `groupUnder` LIKE '0'");
                          ?>  						
									<select class="form-control select2 groupUnder" name="groupUnder">
                                <?php
                                  while ($fetch = mysqli_fetch_array($parent)) {
                                 $child = mysqli_query($con, "SELECT * FROM `tbl_accountgroup` WHERE company_id IS NULL AND `groupUnder` = '".$fetch['id']."'");
                                 
                                ?>
                                <optgroup label="<?= $fetch['accountGroupName']?>">
                                  <option value="<?= $fetch['id'] ?>"   <?php echo (isset($_GET['edit']) && $data['accountGroupId'] == $fetch['id'] ? 'selected' : ''); ?>  ><?= $fetch['accountGroupName']?></option>
                                  <?php
                                     while ($fetch1 = mysqli_fetch_array($child)) {
                                  ?>
                                  <option value="<?= $fetch1['id'] ?>"   <?php echo (isset($_GET['edit']) && $data['accountGroupId'] == $fetch1['id'] ? 'selected' : ''); ?>  ><?= $fetch1['accountGroupName']?></option>
                                   <?php
                                    }
                                  ?>
                                </optgroup>
                                <?php
                                  }
                                ?>
                              </select>
									        						
									        					</div>
									        				</div>


									        			</div>
									        			<div class="row">
									        			
									        				<div class="col-sm-3 right-gapp">
									        					<div class="form-group">
									        						<label>Debit/Credit</label>
									        						<select required="true" class="form-control nature" name="nature" id="nature">
									        							<option value="">Select</option>
									        							<option  value="Credit" <?= (isset($_GET['edit']) && $data['crOrDr'] == 'Credit') ? 'selected' : '' ;?>>Credit</option>
									        							<option  value="Debit" <?=  (isset($_GET['edit']) && $data['crOrDr'] == 'Debit') ? 'selected' : '' ;?>>Debit</option>
									        							
									        						</select>
									        					</div>
									        				</div>
									        					<div class="col-sm-5 right-gapp">
									        					<div class="form-group">

									        						<label>Description</label>
									        						<input type="text" name="description" class="form-control" value="<?= isset($_GET['edit']) ? $data['extra1'] : '' ;?>" id="description">
									        					</div>
									        				</div>
									        				<div class="col-sm-4 right-gapp">
									        					<div class="form-group">

									        						<label>Branch</label>
									        						<select class="form-control branch select2" name="branch">
									        							<?php
									        							$branch = mysqli_query($con, "SELECT * FROM `branches` order by id ASC");
									        							while ($get = mysqli_fetch_array($branch)) {
									        							?>
									        							<option value="<?= $get['id']?>" <?= (isset($_GET['edit']) && $data['branchCode'] == $get['id'] ?'selected' : '')?>><?= $get['name'] ?></option>
									        							<?php
									        								# code...
									        							}
									        							?>


									        						</select>
									        					</div>
									        				</div>
									        			</div>
									        		
									        		</div>
							        		    </div>
							        	
							        		</div>
							        		    <div class="row" style="    padding: 0 0 0 6px;">
							                   		<div class="col-sm-8 blue_bg right-gapp">
								                	<input type="submit" value="Save" name="submit" class="form-btn btn btn-shadow btn-primary "/>
								                	<a href="" class="form-btn btn  btn-primary btn-default1 ">
								                	Cancel
								                	</a>
								                	</div>
								                </div>
								         </div>
				</form>
				</div>


            </div>


          <?php include "includes/footer.php";
} else{
    header("location:index.php");
}
?>

<script type="text/javascript">
    $(document).ready(function() {
    $(".select2").select2();
});
$('.groupUnder').on('change', function(){
    var value = $(this).val();
    var url = 'ajax_getaccount.php';
   $.ajax({
        url: url,
        dataType: "json",
        type: "Post",
        async: true,
        data: {ledger:1,value:value },
        success: function (data) {
     
        $('.chart_account_id_fgroup').val(data.parent);
		$('.chart_account_id_child').val(data.child);
        },
        error: function (xhr, exception) {
            }
    }); 
});
</script>
