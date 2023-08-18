<style>
.row {
    margin: 0 !important;
}

.table-responsive {
    padding: 0 !important;
}

.col-md-2 {
    padding-right: 20px !important;
}
</style>
<?php
 $query = mysqli_query($con, "SELECT * FROM rider_wallet_ballance_log where order_id = ".$_POST['id']);
 ?>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>

<div class="panel panel-default">

    <div class="panel-heading order_box"><?php echo getLange('rider').' '.getLange('report'); ?> </div>
    <form method="POST" action="">
        <div class="row form" style="margin:0;">

            <div class="col-sm-2 left_right_none ">
                <div class="form-group">
                    <label><?php echo getLange('from'); ?></label>
                    <input type="text" value="<?php echo isset($_POST['from'])?$_POST['from']:date('Y-m-d'); ?>"
                        class="form-control datetimepicker4" name="from">
                    <input type="hidden" name="id" value=" <?php echo $_POST['id'];?>">
                </div>
            </div>
            <div class="col-sm-2 left_right_none">
                <div class="form-group">
                    <label><?php echo getLange('to'); ?></label>
                    <input type="text" value="<?php echo isset($_POST['from'])?$_POST['to']:date('Y-m-d'); ?>"
                        class="form-control datetimepicker4" name="to">
                </div>
            </div>
            <div class="col-sm-1 sidegapp-submit left_right_none">
                <input type="submit" name="submit" class="btn btn-info" style="margin-top: 25px;"
                    value="<?php echo getLange('submit'); ?>">
            </div>
        </div>
    </form>
    <div class="panel-body" id="same_form_layout">
        <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
            <div class="row gap-none">
                <div class="col-sm-12 table-responsive">
                    <div class="pdf">
                        <table cellpadding="0" cellspacing="0" border="0"
                            class="table table-striped table-bordered dataTable no-footer" id="basic-datatable"
                            role="grid" aria-describedby="basic-datatable_info">
                            <thead>
                                <tr role="row">
                                    <th tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1"
                                        aria-sort="ascending" aria-label="Rendering engine: activate to sort column ">
                                        Sr#</th>
                                    <th tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1"
                                        aria-sort="ascending" aria-label="Rendering engine: activate to sort column ">
                                        <?php echo getLange('orderno'); ?> </th>
                                    <th tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1"
                                        aria-sort="ascending" aria-label="Rendering engine: activate to sort column ">
                                        <?php echo getLange('date'); ?> </th>

                                    <th tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1"
                                        aria-sort="ascending" aria-label="Rendering engine: activate to sort column ">
                                        <?php echo getLange('ridername'); ?> </th>

                                    <!-- <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column ">Package Type</th> -->

                                    <th tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1"
                                        aria-sort="ascending" aria-label="Rendering engine: activate to sort column ">
                                        <?php echo getLange('debit'); ?> </th>

                                    <th tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1"
                                        aria-label="Engine version: activate to sort column ascending">
                                        <?php echo getLange('credit'); ?></th>
                                </tr>
                            </thead>
                            <?php
                                if(isset($_POST['submit'])){
                                    $from = date('Y-m-d',strtotime($_POST['from']));
                                    $to = date('Y-m-d',strtotime($_POST['to']));
                                    $query1= mysqli_query($con,"Select * from rider_wallet_ballance_log where DATE_FORMAT(date, '%Y-%m-%d') >= '$from' and DATE_FORMAT(date, '%Y-%m-%d') <= '$to' and order_id ='".$_POST['id']."' order by id asc");
                                ?>
                            <tbody>
                                <?php
                                    while ($row = mysqli_fetch_assoc($query1)) {
                                ?>
                                <tr class="gradeA odd" role="row">
                                    <td class="sorting_1"><?php echo $row['id']; ?></td>
                                    <td class="sorting_1"><?php echo $row['order_no']; ?></td>
                                    <td class="sorting_1"><?php echo date('Y-m-d',strtotime($row['date'])); ?></td>
                                    <td class="sorting_1"><?php echo $row['rider_name']; ?></td>
                                    <td class="sorting_1"><?php echo isset($row['debit']) ? $row['debit'] : '0'; ?></td>
                                    <td class="sorting_1"><?php echo isset($row['credit']) ? $row['credit'] : '0'; ?>
                                    </td>
                                </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                            <tfoot style="background-color:#0D0150;">
                                <td style="color: #fff;font-size: 14px !important;"><?php echo getLange('total'); ?>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <?php
                                    $total= mysqli_query($con,"SELECT SUM(credit) as total_credit,SUM(debit) as total_debit FROM rider_wallet_ballance_log WHERE DATE_FORMAT(date, '%Y-%m-%d') >= '$from' and DATE_FORMAT(date, '%Y-%m-%d') <= '$to' and order_id= '".$_POST['id']."'");
                                    $result = mysqli_fetch_array($total)
                                ?>
                                <td style="color: #fff;font-size: 14px !important;">
                                    <?php echo isset($result['total_debit']) ? $result['total_debit'] :'0';?></td>
                                <td style="color: #fff;font-size: 14px !important;">
                                    <?php echo isset($result['total_credit']) ? $result['total_credit'] :'0';?></td>
                            </tfoot>

                            <?php
                                }
                                else{
                                ?>
                            <tbody>
                                <?php
                                while ($row = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr class="gradeA odd" role="row">
                                    <td class="sorting_1"><?php echo $row['id']; ?></td>
                                    <td class="sorting_1"><?php echo $row['order_no']; ?></td>
                                    <td class="sorting_1"><?php echo date('Y-m-d',strtotime($row['date'])); ?></td>
                                    <td class="sorting_1"><?php echo $row['rider_name']; ?></td>
                                    <td class="sorting_1"><?php echo isset($row['debit']) ? $row['debit'] : '0'; ?></td>
                                    <td class="sorting_1"><?php echo isset($row['credit']) ? $row['credit'] : '0'; ?>
                                    </td>
                                </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                            <tfoot style="background-color:#0D0150;">
                                <td style="color: #fff;font-size: 14px !important;"><?php echo getLange('total'); ?>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <?php
                                    $total= mysqli_query($con,"SELECT SUM(credit) as total_credit,SUM(debit) as total_debit FROM rider_wallet_ballance_log where order_id = '".$_POST['id']."'");
                                    $result = mysqli_fetch_array($total)
                                ?>
                                <td style="color: #fff;font-size: 14px !important;">
                                    <?php echo isset($result['total_debit']) ? $result['total_debit'] :'0';?></td>
                                <td style="color: #fff;font-size: 14px !important;">
                                    <?php echo isset($result['total_credit']) ? $result['total_credit'] :'0';?></td>
                            </tfoot>
                            <?php
                            }
                            ?>
                        </table>
                    </div>
                    <div class="text-center">
                        <img src="images/raw.gif" style="display:none;">
                        <a href="#" class="btn btn-success center" target="_blank" id="down_pdf"
                            style="display:none;">Download PDF</a>
                        <!-- <a href="#" class="btn btn-success center" id="gen_pdf">Generate PDF</a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>