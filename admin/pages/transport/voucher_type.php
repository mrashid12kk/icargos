<?php
    if(isset($_POST['delete'])){
        $id=mysqli_real_escape_string($con,$_POST['id']);
        $query1=mysqli_query($con,"delete from vouchertype where voucherTypeId=$id") or die(mysqli_error($con));
        $rowscount=mysqli_affected_rows($con);
        if($rowscount>0){
            echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you have delete a Voucher type successfully</div>';
        }
        else{
            echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not delete a Voucher Type unsuccessfully.</div>';
        }
    }
?>
<?php

        if(isset($_POST['addcompany'])){
            $voucher_type=mysqli_real_escape_string($con,$_POST['vouchertype']);
            $type_of_voucher=mysqli_real_escape_string($con,$_POST['typeofvoucher']);
            $method_of_voucher=mysqli_real_escape_string($con,$_POST['methodofvoucher']);
            $tax_applicable=mysqli_real_escape_string($con,$_POST['taxapplicable']);

            $query2=mysqli_query($con,"INSERT into `vouchertype`(voucherTypeName,typeOfVoucher,methodOfVoucherNumbering,isTaxApplicable)values('$voucher_type','$type_of_voucher','$method_of_voucher','$tax_applicable')") or die(mysqli_error($con));
            $rowscount=mysqli_affected_rows($con);
            if($query2){
                echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you Add a Voucher Type successfully</div>';
            }
            else{
                echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not Add a Voucher type successfully.</div>';
            }
        }
         if(isset($_POST['updatecompany'])){
            $id=mysqli_real_escape_string($con,$_POST['edit_id']);

            $voucher_type=mysqli_real_escape_string($con,$_POST['vouchertype']);
            $type_of_voucher=mysqli_real_escape_string($con,$_POST['typeofvoucher']);
            $method_of_voucher=mysqli_real_escape_string($con,$_POST['methodofvoucher']);
            $tax_applicable=mysqli_real_escape_string($con,$_POST['taxapplicable']);

            $query2=mysqli_query($con,"UPDATE vouchertype SET voucherTypeName='$voucher_type', typeOfVoucher = '$type_of_voucher', methodOfVoucherNumbering = '$method_of_voucher', isTaxApplicable = '$tax_applicable' where voucherTypeId=$id") or die(mysqli_error($con));
            $rowscount=mysqli_affected_rows($con);

            if($query2){

                echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you updated a Voucher Type successfully</div>';
            }
            else{

                echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not upadated a Voucher Type unsuccessfully.</div>';
            }
        }
        $sql= "SELECT * From vouchertype ";

        $allmodes = mysqli_query($con,$sql);
        if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {

        	$query2=mysqli_query($con,"SELECT * FROM vouchertype WHERE voucherTypeId=".$_GET['edit_id']);
                $edit=mysqli_fetch_assoc($query2);
              
        }

?>


 <div class="panel panel-default">
    <div class="panel-heading"><?php //echo getLange('add').' '.getLange('transportcompany'); ?> Add New Voucher Type</div>
    <div class="panel-body">
        <form role="form" class="" data-toggle="validator" action="" method="post">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">

                        <label  class="control-label"><?php //echo getLange('company').' '.getLange('name'); ?>voucher Type</label>
                        <input type="text"  class="form-control" name="vouchertype" value="<?php if(isset($edit)){ echo $edit['voucherTypeName'];} ?>" placeholder="Enter voucher type<?php //echo getLange('enter').' '.getLange('company').' '.getLange('name'); ?>" required>
                        <div class="help-block with-errors "></div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label  class="control-label"> Type of voucher<?php //echo getLange('company').' '.getLange('name'); ?></label>
                        <input type="text"  class="form-control" name="typeofvoucher" value="<?php if(isset($edit)){ echo $edit['typeOfVoucher'];} ?>" placeholder="Enter Type of voucher<?php //echo getLange('enter').' '.getLange('company').' '.getLange('name'); ?>" required>
                        <div class="help-block with-errors "></div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">

                        <label  class="control-label"><?php //echo getLange('company').' '.getLange('name'); ?> Method of voucher Numbering</label>
                        <input type="text"  class="form-control" name="methodofvoucher" value="<?php if(isset($edit)){ echo $edit['methodOfVoucherNumbering'];} ?>" placeholder="Enter Method of voucher numbering<?php //echo getLange('enter').' '.getLange('company').' '.getLange('name'); ?>" required>

                        <div class="help-block with-errors "></div>

                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label  class="control-label">Tax applicable<?php //echo getLange('company').' '.getLange('name'); ?></label>
                        <input type="text"  class="form-control" name="taxapplicable" value="<?php if(isset($edit)){ echo $edit['isTaxApplicable'];} ?>" placeholder="Enter Tax applicable<?php //echo getLange('enter').' '.getLange('company').' '.getLange('name'); ?>" required>
                        <div class="help-block with-errors "></div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="<?php if(isset($edit)){ echo 'edit_id';} ?>" value="<?php if(isset($edit)){ echo $edit['voucherTypeId'];} ?>">

                <input type="hidden" name="id" value="<?php echo $fetch1['voucherTypeId']; ?>">

         <button type="submit" name="<?php if(isset($edit)){ echo 'updatecompany';}else{echo 'addcompany';} ?>" class="add_form_btn" ><?php echo getLange('add'); ?></button>
        </form>
    </div>
</div>



<div class="panel panel-default">
    <div class="panel-heading"><?php //echo getLange('transportcompany'); ?>
        Voucher Type
    </div>
        <div class="panel-body" id="same_form_layout" style="padding: 11px;">
            <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">

                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info">
                            <thead>
                                <tr role="row">
                                    <th style="width: 5%;"><?php echo getLange('sr'); ?>#</th>
                                   <th style="width: 30%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php //echo getLange('company').' '.getLange('name'); ?>Voucher Type Name </th>
                                   <th style="width: 30%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;">Type Of Voucher </th>

                                   <th style="width: 30%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php //echo getLange('company').' '.getLange('name'); ?>Method of Voucher Numbering </th>

                                    <th style="width: 30%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php //echo getLange('company').' '.getLange('name'); ?>Tax Applicable </th>

                                  <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 108px;"><?php echo getLange('action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                //$sr_no = 1;
                                $query1=mysqli_query($con,"Select * from vouchertype");
                                while($fetch1=mysqli_fetch_array($query1)){
                                    //print_r($fetch1);
                                    //die;
                            ?>
                                <tr class="gradeA odd" role="row">
                                    <td class="sorting_1"><?php echo $fetch1['voucherTypeId']; ?></td>
                                    <td class="sorting_1"><?php echo $fetch1['voucherTypeName']; ?></td>
                                    <td class="sorting_1"><?php echo $fetch1['typeOfVoucher']; ?></td>

                                    <td class="sorting_1"><?php echo $fetch1['methodOfVoucherNumbering']; ?></td>
                                     <td class="sorting_1"><?php echo $fetch1['isTaxApplicable']; ?></td>

                                    <td class="center inline_Btn">
                                        <form action="voucher_type.php" method="get" style="display: inline-block;">
                                            <input type="hidden" name="id" value="<?php echo $fetch1['voucherTypeId']; ?>">
                                            <button type="submit" name="edit_id" value="<?php echo $fetch1['voucherTypeId']; ?>">
                                              <span class="glyphicon glyphicon-edit"></span>
                                            </button>
                                            <input type="hidden" name="id" value="<?php echo $fetch1['voucherTypeId']; ?>">

                                        </form>
                                        <form action="voucher_type.php" method="post"style="display: inline-block;">
                                            <input type="hidden" name="id" value="<?php echo $fetch1['voucherTypeId']; ?>">
                                            <button type="submit" name="delete" onclick="return confirm('Are You Sure Delete this Employee')" >
                                              <span class="glyphicon glyphicon-trash"></span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
            </div>
        </div>
    </div>
</div>
</div>
