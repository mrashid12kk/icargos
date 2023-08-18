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
		$query="SELECT * FROM tbl_accountgroup WHERE groupUnder = $under_id";
	}
	else
	{
		$query="SELECT * FROM tbl_accountgroup order by id ASC";
	}
	$record=mysqli_query($con, $query);
	$ret = array();
	if (mysqli_num_rows($record) > 1) {
  // output data of each row
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
		$query="SELECT * FROM tbl_accountgroup WHERE groupUnder = $under_id";
	}
	else
	{
		$query="SELECT * FROM tbl_accountgroup WHERE groupUnder = 0";
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
$brnach_query = mysqli_query($con, "SELECT * from branches ");
if (isset($_SESSION['branch_id']) && !empty($_SESSION['branch_id'])) {
    $brnach_querys = mysqli_query($con, "SELECT * from branches WHERE id !=".$_SESSION['branch_id']);
}else{

    $brnach_querys = mysqli_query($con, "SELECT * from branches ");
}

// var_dump($_POST);
if(isset($_POST['submit']) && $_GET['edit'] ){
   // var_dump($_POST);
   // die()
    $sql = "UPDATE `tbl_accountgroup` SET chart_account_id_fgroup = '".$_POST['chart_account_id_fgroup']."',`groupUnder`= '".$_POST['groupUnder']."',chart_account_id_child = '".$_POST['chart_account_id_child']."' , accountGroupName = '".$_POST['accountGroupName']."',  narration =  '".$_POST['narration']."' , nature= '".$_POST['nature']."', affectGrossProfit = '".$_POST['affectGrossProfit']."' , detail_view =  '".$_POST['detail_view']."'   WHERE id= '" . $_GET['edit'] . "'" ;

// var_dump($sql);
    $update = mysqli_query($con, $sql);
    //dobaara uthao 
    if($update){
    	// echo "<meta http-equiv='refresh' content='0'>";
         echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong>Details Updated successfully</div>'; 
    }
    else{
          echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> Error in sql</div>';
    }

}
else{
$sql = "SELECT * FROM `tbl_accountgroup` where `accountGroupName` like '".$_REQUEST['accountGroupName']."'"; 
$match = mysqli_query($con, $sql);
$rec = mysqli_fetch_array($match);
$count1 = mysqli_num_rows($re);
$count = mysqli_num_rows($match);
if($count > 0){
    echo '<div class="alert alert-danger "><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong>Group Name Already Exist.</div>';
}else{
	if(isset($_POST['submit'])){
		$insertquery = "INSERT INTO `tbl_accountgroup` (`chart_account_id_fgroup`, `chart_account_id_child`, `accountGroupName`, `groupUnder`, `narration`, `nature`, `affectGrossProfit`,`detail_view`) VALUES ( '".$_POST['chart_account_id_fgroup']."', '".$_POST['chart_account_id_child']."', '".$_POST['accountGroupName']."', '".$_POST['groupUnder']."', '".$_POST['narration']."', '".$_POST['nature']."', '".$_POST['affectGrossProfit']."', '".$_POST['detail_view']."')";
		$inser_sql = mysqli_query($con, $insertquery);
		if($inser_sql){
		   echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong>  Added Details successfully</div>';
		}
	}
}
}
if(isset($_GET['edit'])){
 $groupledger = mysqli_query($con, "SELECT * FROM tbl_accountgroup where id = ".$_GET['edit']);   
}else{
$groupledger = mysqli_query($con, "SELECT * FROM tbl_accountgroup order by id desc");
}
$data = mysqli_fetch_array($groupledger);
// var_dump($data);
$number = '';
$id = $data['id'] ;
if(!empty($id)){
    $number = $id + 1;
}
else{
    $number = 1;
}
$sql1 = "SELECT * FROM  tbl_accountgroupnature order by value ASC";
$natue = mysqli_query($con, $sql1);


 ?>

            <div class="warper container-fluid">
              
              <div class="warper container-fluid row">
                <div class="col-md-6">
                        <?php
                if(isset($_GET['edit']))
                {
                    ?>
                <form action="accountgroup_form.php?edit=<?= $_GET['edit'];?>"  method="POST" role="form">
                <?php
                }
                else
                {
                    ?>
                <form action="accountgroup_form.php" method="POST" role="form">
                <?php
                }
                ?>
            	
							        	<div class="panel panel-body" style="border: none;padding: 17px 3px 17px 10px;margin: 0;">
							        		<div class="row">
							        			<div class="col-sm-12 sidegapp">
									        		<div class="well-height height29" style="margin-bottom: 0;">
									        			<div class="row">
                                                        
									        				<div class="col-sm-2 right-gapp">
									        					<div class="form-group ">
									        						<label>id</label>
									        					<input type="text" disabled="true" class="form-control"  value="<?= isset($_GET['edit'])? $data['id']:$number ?>">
									        					</div>
									        				</div>
									        				<div class="col-sm-8 right-gapp">
									        					<div class="form-group ">
									        						<label>Group Name</label>
									        						<input required="true" type="text" name="accountGroupName" id="accountGroupName" class="form-control" value="<?= isset($_GET['edit']) ? $data['accountGroupName'] : '' ;?>">
									        					</div>
									        				</div>

									        				<div class="col-sm-2 right-gapp">
									        					<div class="form-group ">
									        						<label>Code</label>
									        						<input readonly="true" style="background-color: #F5F5F5 !important;" required="true" type="text" name="chart_account_id_child" class="form-control chart_account_id_child" value="<?= isset($_GET['edit']) ? $data['chart_account_id_child'] : '' ;?>" id="chart_account_id_child">
									        					</div>
									        				</div>


									        				<div class="col-sm-4 right-gapp">
									        					<div class="form-group ">
									        						<label>Parent ID</label>
									        						<input type="text" name="chart_account_id_fgroup" class="form-control chart_account_id_fgroup" value="<?= isset($_GET['edit']) ? $data['chart_account_id_fgroup'] : '' ;?>" id="chart_account_id_fgroup">
									        					</div>
									        				</div>
									        		
									        				<div class="col-sm-8 right-gapp">
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
                                  <option value="<?= $fetch['id'] ?>"   data-nature="<?php echo isset($row['nature']) ? $row['nature'] :''; ?>" <?php echo (isset($_GET['edit']) && $data['groupUnder'] == $fetch['id'] ? 'selected' : ''); ?>  ><?= $fetch['accountGroupName']?></option>
                                  <?php
                                     while ($fetch1 = mysqli_fetch_array($child)) {
                                  ?>
                                  <option value="<?= $fetch1['id'] ?>"  data-nature="<?php echo isset($val['nature']) ? $val['nature'] :''; ?>"  <?php echo (isset($_GET['edit']) && $data['groupUnder'] == $fetch1['id'] ? 'selected' : ''); ?>  ><?= $fetch1['accountGroupName']?></option>
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
									        				<div class="col-sm-6 right-gapp">
									        					<div class="form-group">
									        						<label>Narration</label>
									        						<input type="text" name="narration" class="form-control" value="<?= isset($_GET['edit']) ? $data['narration'] : '' ;?>" id="narration">
									        					</div>
									        				</div>
									        				<div class="col-sm-6 right-gapp">
									        					<div class="form-group">
									        						<label>Nature</label>
									        						<select required="true" class="form-control select2 nature" name="nature" id="nature">
									        							<option value="">Select</option>
									        							<?php
									        							 while ($row = mysqli_fetch_array($natue)) {
									        							 		?><option value="<?= $row['value'];?>" <?= (isset($_GET['edit']) && $data['nature'] == $row['value']) ? 'selected' : '' ;?>><?= $row['name'];?></option>
									        							<?php
									        							 }
									        							?>
									        					
									        						</select>
									        					</div>
									        				</div>
									        			</div>
									        			<div class="row">
									        				<div class="col-sm-6 right-gapp">
									        					<div class="" style="margin-bottom: 10px;">
									        						<input type="checkbox" name="affectGrossProfit" id="affectGrossProfit" value="1" class="" <?=  (isset($_GET['edit']) && $data['affectGrossProfit'] == '1') ? 'checked' : '' ; ?>>
									        					    <label style="position: static;">Effect Gross Profit</label>
									        					</div>
									        				</div>
															<div class="col-sm-6 right-gapp">
									        					<div class="" style="margin-bottom: 10px;">
									        						<input  type="checkbox" name="detail_view" id="detail_view" value="1" class=""  <?=  (isset($_GET['edit']) && $data['detail_view'] == '1') ? 'checked' : '' ; ?>>
									        					    <label style="position: static;">Enable Detail View</label>
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
        data: {parent:1,value:value },
        success: function (data) {
     	console.log(data);
		$('.chart_account_id_fgroup').val(data.parent);
		$('.chart_account_id_child').val(data.child);

         
        },
        error: function (xhr, exception) {
            }
    }); 
});
$('.nature').on('change', function(){

if($('#chart_account_id_fgroup').val() == '' ){
    var value = $(this).val();
    var url = 'ajax_getaccount.php';
   $.ajax({
        url: url,
        dataType: "json",
        type: "Post",
        async: true,
        data: {nature:1,value:value },
        success: function (data) {
		$('.chart_account_id_child').val(data.child);
        },
        error: function (xhr, exception) {
            }
    }); 
}
});

</script>
