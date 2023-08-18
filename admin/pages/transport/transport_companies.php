<?php
    if(isset($_POST['delete'])){
        $id=mysqli_real_escape_string($con,$_POST['id']);
        $query1=mysqli_query($con,"delete from transport_company where id=$id") or die(mysqli_error($con));
        $rowscount=mysqli_affected_rows($con);
        if($rowscount>0){
            echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you have delete a transport company successfully</div>';
        }
        else{
            echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not delete a transport company unsuccessfully.</div>';
        }
    }

        if(isset($_POST['addcompany'])){



            $mode_id=mysqli_real_escape_string($con,$_POST['mode_id']);

            $company_name=mysqli_real_escape_string($con,$_POST['company_name']);

            $query2=mysqli_query($con,"INSERT into `transport_company`(name,mode_id)values('$company_name',$mode_id)") or die(mysqli_error($con));
            $rowscount=mysqli_affected_rows($con);
            if($query2){
                echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you Add a Transport company successfully</div>';
            }
            else{
                echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not Add a Transport company unsuccessfully.</div>';
            }

        }
         if(isset($_POST['updatecompany'])){



            $id=mysqli_real_escape_string($con,$_POST['edit_id']);
            $mode_id=mysqli_real_escape_string($con,$_POST['mode_id']);

            $company_name=mysqli_real_escape_string($con,$_POST['company_name']);

            $query2=mysqli_query($con,"update transport_company set name='$company_name', mode_id = $mode_id where id=$id") or die(mysqli_error($con));
            $rowscount=mysqli_affected_rows($con);



            if($query2){

                echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you updated a Transport company successfully</div>';

            }

            else{

                echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not upadated a Transport company unsuccessfully.</div>';

            }

        }
        $sql= "SELECT * From modes ";

        $allmodes = mysqli_query($con,$sql);
        if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {

        	$query2=mysqli_query($con,"SELECT * FROM transport_company WHERE id=".$_GET['edit_id']);
                $edit=mysqli_fetch_assoc($query2);
              
        }

?>
<?php

function getmodenamebyid($id)
{
    global $con;

    $sql= "SELECT * From modes where id=".$id;

    $query = mysqli_query($con,$sql);

    $result = mysqli_fetch_array($query);

    return $result['name'];
}

 ?>
 <div class="panel panel-default">

    <div class="panel-heading"><?php echo getLange('add').' '.getLange('transportcompany'); ?></div>

    <div class="panel-body">



        <form role="form" class="" data-toggle="validator" action="" method="post">

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">

                        <label  class="control-label"><?php echo getLange('company').' '.getLange('name'); ?></label>
                        <input type="text"  class="form-control" name="company_name" value="<?php if(isset($edit)){ echo $edit['name'];} ?>" placeholder="<?php echo getLange('enter').' '.getLange('company').' '.getLange('name'); ?>" required>

                        <div class="help-block with-errors "></div>

                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">

                        <label  class="control-label"><?php echo getLange('mode') ?></label>

                        <select name="mode_id" class="form-control">
                            <?php while ($row = mysqli_fetch_array($allmodes)) { ?>

                                <option <?php if ($row['id']==$fetch1['mode_id']): ?>
                                    selected
                                <?php endif ?> value="<?php echo $row['id']; ?>" <?php if(isset($edit) && $edit['mode_id']==$row['id']){ echo 'Selected';} ?>><?php echo $row['name']; ?></option>
                            <?php } ?>

                        </select>

                        <div class="help-block with-errors "></div>

                    </div>
                </div>
            </div>

            <input type="hidden" name="<?php if(isset($edit)){ echo 'edit_id';} ?>" value="<?php if(isset($edit)){ echo $edit['id'];} ?>">

                <input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">

         <button type="submit" name="<?php if(isset($edit)){ echo 'updatecompany';}else{echo 'addcompany';} ?>" class="add_form_btn" ><?php echo getLange('add'); ?></button>

        </form>



    </div>

</div>
<div class="panel panel-default">
    <div class="panel-heading"><?php echo getLange('transportcompany'); ?>

    </div>
        <div class="panel-body" id="same_form_layout" style="padding: 11px;">
            <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">

                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info">
                            <thead>
                                <tr role="row">
                                    <th style="width: 5%;"><?php echo getLange('sr'); ?>#</th>
                                   <th style="width: 30%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php echo getLange('company').' '.getLange('name'); ?> </th>
                                   <th style="width: 30%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php getLange('mode'); ?> </th>
                                  <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 108px;"><?php echo getLange('action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php

                                $sr_no = 1;
                                $query1=mysqli_query($con,"Select * from transport_company order by id desc");
                                while($fetch1=mysqli_fetch_array($query1)){
                            ?>
                                <tr class="gradeA odd" role="row">
                                    <td class="sorting_1"><?php echo $sr_no++; ?></td>
                                    <td class="sorting_1"><?php echo $fetch1['name']; ?></td>
                                    <td class="sorting_1"><?php echo getmodenamebyid($fetch1['mode_id']); ?></td>
                                    <td class="center inline_Btn">
                                        <form action="transport_companies.php" method="get" style="display: inline-block;">
                                            <input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">
                                            <button type="submit" name="edit_id" value="<?php echo $fetch1['id']; ?>">
                                              <span class="glyphicon glyphicon-edit"></span>
                                            </button>
                                            <input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">

                                        </form>

                                        <form action="transport_companies.php" method="post" style="display: inline-block;">
                                            <input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">
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
