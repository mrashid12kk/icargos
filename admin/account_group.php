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
                if (isset($_GET['delete_id']) && $_GET['delete_id'] != '') {
                    $q ="SELECT * FROM `tbl_accountledger` where `accountGroupId` = '".$_GET['delete_id']."'";
                    $sql  = mysqli_query($con, $q);
                    $row = mysqli_fetch_array($sql);
                    if (mysqli_num_rows($sql) > 0) {
                          $qu ="SELECT * FROM `tbl_ledgerposting` where `ledgerId` = '".$row['id']."'";
                          
                    $sql  = mysqli_query($con, $qu);
                    if (mysqli_num_rows($sql) > 0) {
                            echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button>Its account ledger(s) has transactions. Can not be removed</div>';
                            }else{
                               echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button>Can not delete this because it has ledger against it </div>';
                            }
                     }else{
                        $query = "DELETE FROM tbl_accountgroup where id = '".$_GET['delete_id']."'";
                    $run = mysqli_query($con, $query);
                    if($run){
                          echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button>Record Deleted</div>';
                    }
                }
             
                }
                $branch_query = '';
                $branch_id = $_SESSION['branch_id'];
           
                    if($branch_id = 1){
                              $sql = "SELECT * FROM tbl_accountgroup order by id ASC";
                    }
                    else{
                       $sql = "SELECT * FROM tbl_accountgroup order by id ASC";

                    }
                

                    $query1 = mysqli_query($con, $sql);
                   
                if(!function_exists('getAccountGroup')){
                    function getAccountGroup($id = null){
                         global $con;
                         $querygetAccountGroup = mysqli_query($con, "SELECT accountGroupName FROM tbl_accountgroup where id=".$id);
                         $QueryResponse = mysqli_fetch_assoc($querygetAccountGroup);
                         return $QueryResponse['accountGroupName'];
                    }
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
            <?php
                function getBranchNameById($id)
                {
                    global $con;
                    $branchQ = mysqli_query($con, "SELECT name from branches where id = $id");

                    $res = mysqli_fetch_array($branchQ);

                    return $res['name'];
                }
                function encrypt($string)
                {
                    $key = "usmannnn";
                    $result = '';
                    for ($i = 0; $i < strlen($string); $i++) {
                        $char = substr($string, $i, 1);
                        $keychar = substr($key, ($i % strlen($key)) - 1, 1);
                        $char = chr(ord($char) + ord($keychar));
                        $result .= $char;
                    }

                    return base64_encode($result);
                }
                ?>


            <div class="panel panel-default">
              
                <div class="panel-heading"><?php echo getLange('Account Group'); ?></div>

                <div class="panel-body" id="same_form_layout">

                    <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">

                        <div class="row">
                          
                            <div class="col-sm-12 table-responsive gap-none">

                                <table class="table table-striped table-bordered dataTable_with_sorting no-footer"
                                    id="basic-datatable">


                                    <thead>

                                        <tr role="row">
                                            <th><?php echo getLange('srno'); ?></th>
                                            <th><?php echo getLange('Group IDs'); ?> </th>
                                            <th><?php echo getLange('Account Name'); ?> </th>
                                            <th><?php echo getLange('Group Under'); ?> </th>
                                            <th><?php echo getLange('Code'); ?> </th>
                                            <th><?php echo getLange('Parent ID'); ?> </th>
                                            <th><?php echo getLange('Nature'); ?> </th>
                                            <th><?php echo getLange('Actual Gross Profit'); ?></th>
                                            <th><?php echo getLange('Narration'); ?> </th>
                                          
                                            <th><?php echo getLange('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $sr = 1;
                                            while ($fetch1 = mysqli_fetch_array($query1)) {
                                                                                          
                                            ?>
                                        <tr class="gradeA odd" role="row">
                                            <td><?php echo $sr; ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['id']; ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['accountGroupName']; ?></td>
                                            <td class="sorting_1"><?php echo getAccountGroup($fetch1['groupUnder']); ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['chart_account_id_child']; ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['chart_account_id_fgroup']; ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['nature']; ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['affectGrossProfit']; ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['narration']; ?>
                                            </td>
                                            <td class="sorting_1">
                                            
                                                <a href="account_group.php?delete_id=<?php echo $fetch1['id']; ?>"
                                                    onclick="return confirm('Are you sure you want to delete this Account Group?');"
                                                    class="btn btn-info delete-record"><i class="fa fa-trash"></i></a>
                                                   <a href="accountgroup_form.php?edit=<?php echo $fetch1['id']; ?>" target="_blank" class="btn btn-info"><i class="fa fa-edit"></i></a>
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