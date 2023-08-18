<?php
session_start();
require 'includes/conn.php';
if (isset($_SESSION['users_id']) && ($_SESSION['type'] !== 'driver')) {
    require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'], 30, 'view_only', $comment = null)) {

        header("location:access_denied.php");
    }
    include "includes/header.php";
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

        <!-- Header Ends -->


        <div class="warper container-fluid">

            <!-- <div class="page-header"><h1>Dashboard <small>Let's get a quick overview...</small></h1></div> -->
            <?php
                $message = '';
                if (isset($_GET['delete_id']) && $_GET['delete_id'] != '') {
                    $q ="SELECT * FROM `tbl_ledgerposting` where `ledgerId` = '".$_GET['delete_id']."'";
                    $sql  = mysqli_query($con, $q);
                    if (mysqli_num_rows($sql) > 0) {
               echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button>It has transactions. Can not be removed.</div>';
                }
                else{
                    $query = "DELETE FROM tbl_accountledger where id = '".$_GET['delete_id']."'";
                    $run = mysqli_query($con, $query);
                    if($run){
                          echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button>Record Deleted</div>';
                    }
                }
                }
                $branch_query = '';
                $branch_id = $_SESSION['branch_id'];
                $franchisen_role = '';
                if (isset($_SESSION['user_role_id']) && $_SESSION['user_role_id'] == getfranchisemanagerId()) {
                    $franchisen_role = "AND user_id=" . $_SESSION['users_id'];
                }
                if (isset($branch_id) && !empty($branch_id)) {
                    $branch_query .= " AND branch_id= $branch_id";
                } else {
                    $branch_query .= " AND (branch_id = 1 OR branch_id IS NULL)";
                }
                if (isset($_GET['from']) && !empty($_GET['from'])) {
                    $from = date('Y-m-d', strtotime($_GET['from']));
                    $to = date('Y-m-d', strtotime($_GET['to']));
                    $query1 = mysqli_query($con, "SELECT * FROM manifest_master WHERE DATE_FORMAT(`date`, '%Y-%m-%d') >= '" . $from . "' AND  DATE_FORMAT(`date`, '%Y-%m-%d') <= '" . $to . "'  AND (origin IN ($all_allowed_origins) OR destination IN ($all_allowed_origins) OR receiving_branch = $branch_id ) $franchisen_role order by manifest_no desc ");
                } else {

                    $from = date('Y-m-d', strtotime('today - 30 days'));
                    $to = date('Y-m-d');
                    if($branch_id = 1){
                     $sql = "SELECT `tbl_accountledger`.`id` as `id`, `tbl_accountledger`.`ledgerName` as `ledgerName`,`tbl_accountledger`.`customer_id` as `customer_id`,  `tbl_accountledger`.`company_id` as `company_id`, `tbl_accountledger`.`ledgerCode` as `ledgerCode`, `ledgerName`, `tbl_accountledger`.`openingBalance` as `openingBalance`, `tbl_accountledger`.`accountGroupId` as `accountGroupId`, `tbl_accountledger`.`crOrDr` as `crOrDr`, `tbl_accountledger`.`bankAccountNumber` as `bankAccountNumber`, `tbl_accountgroup`.`accountGroupName` as `accountGroupName` , `tbl_accountgroup`.`chart_account_id_child` as `chart_account_id_child` FROM `tbl_accountledger` INNER JOIN `tbl_accountgroup` ON `tbl_accountgroup`.`id` = `tbl_accountledger`.`accountGroupId` WHERE (`tbl_accountledger`.`company_id` IS NULL OR `tbl_accountledger`.`company_id` = '1') AND `tbl_accountledger`.`status` = '1' ORDER BY `id` DESC ";
                    }
                    else{
                         $sql = "SELECT `tbl_accountledger`.`id` as `id`, `tbl_accountledger`.`ledgerName` as `ledgerName`, `tbl_accountledger`.`customer_id` as `customer_id`, `tbl_accountledger`.`company_id` as `company_id`, `tbl_accountledger`.`ledgerCode` as `ledgerCode`, `ledgerName`, `tbl_accountledger`.`openingBalance` as `openingBalance`, `tbl_accountledger`.`accountGroupId` as `accountGroupId`, `tbl_accountledger`.`crOrDr` as `crOrDr`, `tbl_accountledger`.`bankAccountNumber` as `bankAccountNumber`, `tbl_accountgroup`.`accountGroupName` as `accountGroupName` , `tbl_accountgroup`.`chart_account_id_child` as `chart_account_id_child` FROM `tbl_accountledger` INNER JOIN `tbl_accountgroup` ON `tbl_accountgroup`.`id` = `tbl_accountledger`.`accountGroupId` WHERE (`tbl_accountledger`.`company_id` IS NULL OR `tbl_accountledger`.`company_id` = '1') AND`tbl_accountledger`.`status` = '1' ORDER BY `id` DESC ";

                    }
                    $query1 = mysqli_query($con, $sql);
                    // var_dump($sql);
                    // die();
                }

                ?>
            <style type="text/css">
            .zones_main {
                margin-bottom: 20px;
            }

            .badge {
                width: 100%;
                border-radius: 2px;
                padding: 6px 5px;
                line-height: 1.6;
            }
            </style>
   


            <div class="panel panel-default">
                <?php if (isset($message) && !empty($message)) {
                        echo $message;
                    } ?>
                <div class="panel-heading"><?php echo getLange('Account Leder'); ?></div>

                <div class="panel-body" id="same_form_layout">

                    <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">

                        <div class="row">
                       
                            <div class="col-sm-12 table-responsive gap-none">

                                <table class="table table-striped table-bordered dataTable_with_sorting no-footer"
                                    id="basic-datatable">


                                    <thead>

                                        <tr role="row">
                                            <th><?php echo getLange('srno'); ?></th>
                                            <th><?php echo getLange('Ledger Code'); ?> </th>
                                            <th><?php echo getLange('Account Ledger Name'); ?> </th>
                                            <th><?php echo getLange('Account Group Name'); ?> </th>
                                            <th><?php echo getLange('Parent Code'); ?> </th>
                                            <th><?php echo getLange('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $sr = 1;
                                            while ($fetch1 = mysqli_fetch_array($query1)) {
                                            $post_fix = (isset($fetch1['crOrDr']) && $fetch1['crOrDr'] == 'Debit') ? 'Dr' : 'Cr';
                                            $openingBalance=isset($fetch1['openingBalance']) ? $fetch1['openingBalance']:'0.00';
                                            $openingBalance=$openingBalance.' '.$post_fix;
                                            ?>
                                        <tr class="gradeA odd" role="row">
                                            <td><?php echo $sr; ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['ledgerCode']; ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['ledgerName']; ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['accountGroupName']; ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['chart_account_id_child']; ?></td>
                                            </td>
                                            <!--  -->
                                            <td class="sorting_1">
                                                <?php
                                                   if(isset($fetch1['customer_id'])){
                                                ?>
                                                      <a href="account_ledger.php?delete_id=<?php echo $fetch1['id']; ?>"
                                                    onclick="return confirm('Are you sure you want to delete this Account Group?');"
                                                    class="btn btn-info delete-record" style="display: none;"><i class="fa fa-trash"></i></a>
                                                    <?php
                                                        }else{
                                                    ?>
                                                     <a href="account_ledger.php?delete_id=<?php echo $fetch1['id']; ?>"
                                                    onclick="return confirm('Are you sure you want to delete this Account Group?');"
                                                    class="btn btn-info delete-record"><i class="fa fa-trash"></i></a>
                                                    <?php
                                                        }
                                                    ?>
                                                   <a href="accountledger_form.php?edit=<?php echo $fetch1['id']; ?>" target="_blank" class="btn btn-info"><i class="fa fa-edit"></i></a>
                                            </td>


                                        </tr>

                                        <?php
                                                $sr++;
                                            }



                                            ?>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



        </div>
        <!-- Warper Ends Here (working area) -->


        <?php

        include "includes/footer.php";
    } else {
        header("location:index.php");
    }
        ?>
        <script type="text/javascript">
        $(function() {
            $('.datetimepicker4').datetimepicker({
                format: 'YYYY/MM/DD',
            });

        });
    </script>
    <script>
    // function ConfirmDelete(id) {
        $('#ConfirmDelete').on('click',function(e){
        
        e.preventDefault();
        var link = $(this).attr('data-id');
        var x = confirm("Are you sure you want to delete account group?");
        var url = 'ajax_getaccount.php';
        if (x) {
       $.ajax({
        url: url,
        dataType: "json",
        type: "Post",
        async: true,
        data: {link:link, delete:1},
        success: function (data) {
      
        },
        error: function (xhr, exception) {
            }
    }); 
        }
    })
        </script>