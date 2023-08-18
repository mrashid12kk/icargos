<?php

  session_start(); 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
  require 'includes/conn.php';

// var_dump($_POST);

  if(isset($_POST['save_grp'])){
       
    foreach ($_POST['desc'] as $key => $value) {
      $group_id = (isset($_POST['acc_grp'][$key])?$_POST['acc_grp'][$key]:0);
      save_group($value, $group_id);
    }
  	// code here then 
  	 header("location:accounting.php");
  }
  function save_group($type, $group_id){
  	require 'includes/conn.php';
  	  $sql = "UPDATE `tbl_grp_mapping` SET `group_id` = '".$group_id ."' where `description` = '".$type."' ";
     $query = mysqli_query($con,$sql);
     return $type;
  }
  if(isset($_POST['save_role'])){
      foreach($_POST['name'] as $k=> $v)
      {
          $group_id= (isset($_POST['account_group'][$k])?$_POST['account_group'][$k]:0);
          $ledger_id= (isset($_POST['is_active'][$k])?$_POST['is_active'][$k]:0);
          // var_dump($ledger_id);/
          save($v,$group_id,$ledger_id);
      }
  	  // code here then
      header("location:accounting.php");
  }

 function save($name,$group_id,$ledger_id){
  	require 'includes/conn.php';
  	  $sql = "UPDATE `tbl_ledger_mapping` SET `group_id` = '".$group_id ."', `ledger_id` = '".$ledger_id."' where type = '".$name."' ";
     $query = mysqli_query($con,$sql);
     // echo $sql;
     return $name;
  }
  


  if(isset($_SESSION['users_id']) && $_SESSION['type']=='admin'){
 require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'],23,'view_only',$comment =null)){
        header("location:access_denied.php");
    }
  include "includes/header.php";

  

  $return_query = mysqli_query($con,"SELECT * FROM tbl_ledger_mapping ");
  $group = mysqli_query($con, "SELECT * FROM `tbl_accountgroup`");
  function getAccountGroupName($id){
    require 'includes/conn.php';
      $getAccountGroupName = mysqli_query($con, "SELECT * FROM tbl_accountgroup where id = '".$id."'");
      $fetch = mysqli_fetch_array($getAccountGroupName);
      $name = $fetch['accountGroupName'];
      return $name;
  }
  function getAccountLedgerName($id){
    require 'includes/conn.php';
      $getAccountLedgerName = mysqli_query($con, "SELECT * FROM tbl_accountledger where id = '".$id."'");
      $fetch = mysqli_fetch_array($getAccountLedgerName);
      $name = $fetch['ledgerName'];
      return $name;
  }

?>

<body data-ng-app>  

  

    

  <?php

  

  include "includes/sidebar.php";

  

  ?>

    <!-- Aside Ends-->

    

    <section class="content">

       

  <?php

  include "includes/header2.php";

  ?>

       <style type="text/css">

          .city_to option.hide {

            /*display: none;*/

          }

          .form-group{

            margin-bottom: 0px !important;

          }

        </style>  

        <!-- Header Ends -->

        

        

        <div class="warper container-fluid">

          

            <div class="page-header"><h3><?php echo getLange('accounting'); ?>  </h3></div>
              <div class="row">
                <?php
            require_once "setup-sidebar.php";
          ?>
          <div class="col-sm-10 table-responsive" id="setting_box">
             <form method="POST" action="">

              <div class="panel panel-primary">

                <div class="panel-heading"><?php echo getLange('ledgermapping'); ?>  </div>

                <div class="panel-body">

                  <form method="POST" action="#">
                       <?php 
                          while ($row = mysqli_fetch_array($return_query)) {
                              $group = mysqli_query($con, "SELECT * FROM `tbl_accountgroup`");
                            
                        ?>
                      <div class="row">  
                     
                          <div class="col-sm-2">
                            <h5><?= $row['type']?></h5>
                            <input type="hidden" name="name[]" value="<?= $row['type']?>">
                          </div> 

                          <div class="col-sm-3">
                            <label>Account Group</label>
                              <select class="form-control select2 getLedger" key="<?= $row['id'] ?>" name="account_group[]" >
                                <option>Select </option>
                                  <?php
                                    while ($fetch = mysqli_fetch_array($group)) {
                                      ?>
                                      <option value="<?php echo $fetch['id'];?>" <?php if(isset($row['group_id']) && $row['group_id'] == $fetch['id']){
                                        echo "Selected";
                                      }?>><?php echo $fetch['accountGroupName']?></option>
                                      <?php
                                      }
                                    ?>

                              </select>

                          </div>
                          <div class="col-sm-3">
                            <label>Account Ledger</label>
                              <select class="form-control select2 " name="is_active[]" id="ledger_<?= $row['id']; ?>"></select>

                          </div>      

                      </div>
                       <?php
                            }
                          ?>
                              <button type="submit" class="btn btn-sm btn-success" name="save_role"><?php echo getLange('save'); ?></button>
                  </form>

                </div>

              </div>

             </form>




           

            

        </div>
              <div class="col-sm-10 table-responsive" id="setting_box">
             <form method="POST" action="">

              <div class="panel panel-primary">

                <div class="panel-heading"><?php echo getLange('groupmapping'); ?>  </div>

                <div class="panel-body">

                  <form method="POST" action="#">
					<?php
						$group = mysqli_query($con, "SELECT * FROM `tbl_grp_mapping`");
						while ($row = mysqli_fetch_array($group)) {
					?>
                      <div class="row">  

                          <div class="col-sm-2">
                            <h5><?= $row['description'] ; ?></h5>
                              <input type="hidden" class="form-control" required="" value="<?php echo   $row['description']; ?>" placeholder="<?php echo getLange('name'); ?>" name="desc[]">

                          </div> 

                          <div class="col-sm-3">
                            <label>Account Group</label>
                              <select class="form-control select2 " name="acc_grp[]" id="getLedger" >
                                <option>Select </option>
                                  <?php
                                  $group1 = mysqli_query($con, "SELECT * FROM `tbl_accountgroup`");
                                    while ($fetch = mysqli_fetch_array($group1)) {
                                      // var_dump($fetch);
                                      ?>
                                      <option value="<?php echo $fetch['id'];?>" <?php if(isset($row['group_id']) && $row['group_id'] == $fetch['id']){
                                        echo "Selected";
                                      }?>><?php echo $fetch['accountGroupName']?></option>
                                      <?php
                                      }
                                    ?>

                              </select>

                          </div>  

                      </div>
                      <?php
                      	}
                      ?>
                      	  <button type="submit" class="btn btn-sm btn-success" name="save_grp"><?php echo getLange('save'); ?></button>
                  </form>

                </div>

              </div>

             </form>




           

            

        </div>
        </div>
        </div>

        <!-- Warper Ends Here (working area) -->

        

        

      <?php

  

  include "includes/footer.php";

  }

  else{

    header("location:index.php");

  }

  ?>

  <script>
        $('.select2').select2();
    </script>

  <script type="text/javascript">

    $(document).ready(function(){

      $(".data-table").dataTable();

    })

  </script>
<script type="text/javascript">
   $(document).ready(function () {
    $( ".getLedger" ).each(function( index ) {
     var val = $(this).val();
    var key = $(this).attr('key');
      $.ajax({
        url: 'transactions/ajax_ledger_mapping.php',
        dataType: "json",
        type: "Post",
        async: true,
        data: {val:val ,ledger:1 ,key:key},
        success: function (data) {
           console.log(data.options);
           $('#ledger_'+key).html(data.options);     
        },
    });
});
     }); 
</script>
<script type="text/javascript">
  $('.getLedger').on('change',function(){
    var val = $(this).val();
    var key = $(this).attr('key');
    $('#ledger_'+key).html('');  
      $.ajax({
        url: 'transactions/ajax_ledger_mapping.php',
        dataType: "json",
        type: "Post",
        async: true,
        data: {val:val ,ledger:1 },
        success: function (data) {
           console.log(data.options);
           $('#ledger_'+key).html(data.options);     
        },
    });
  })
</script>