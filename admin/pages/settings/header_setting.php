<?php

if (isset($_GET['active_id'])) {

    $active_id = $_GET['active_id'];

    mysqli_query($con,"UPDATE portal_language set is_default = 0");
    mysqli_query($con,"UPDATE portal_language set is_default = 1 WHERE id=".$active_id);
    header("location:portal_language.php");
}
if(isset($_POST['addlanguage'])){
        $direction=mysqli_real_escape_string($con,$_POST['direction']);
        $language=mysqli_real_escape_string($con,$_POST['language']);
        $is_active=mysqli_real_escape_string($con,$_POST['is_active']);
        $dynamic_id = $language.'_lang';
        $query = "INSERT INTO `portal_language`(`language`,`direction`,`is_active`,`dynamic_id`,`is_default`) VALUES ('$language','$direction',$is_active,'$dynamic_id',0)";
        // echo $query;
        // die();
        $query1=mysqli_query($con,$query) or die(mysqli_error($con));
        $rowscount=mysqli_affected_rows($con);
        if($rowscount>0){
            echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you added a new language successfully</div>';

            }
        else{
            echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not added a new language unsuccessfully.</div>';
        }
    }
    if(isset($_POST['updatedata'])){
        $direction=mysqli_real_escape_string($con,$_POST['direction']);
        $language=mysqli_real_escape_string($con,$_POST['language']);
        $is_active=$_POST['is_active'];
        $edit_id = mysqli_real_escape_string($con,$_POST['edit_id']);
        $query = "UPDATE `portal_language` set `direction`='$direction',`language`='$language',`is_active`=$is_active WHERE id=$edit_id";
        // echo $query;
        // die();
        $query1=mysqli_query($con,$query) or die(mysqli_error($con));
        $rowscount=mysqli_affected_rows($con);
        if($rowscount>0){
            echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you update Langauge successfully</div>';

            }
        else{
            echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not update Langauge unsuccessfully.</div>';
        }
    }
     if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
            $id=$_GET['edit_id'];
            $query1=mysqli_query($con,"SELECT * FROM portal_language where id=$id");
                $edit=mysqli_fetch_assoc($query1);

        }

    $languages = mysqli_query($con,"SELECT * FROM portal_language");
?>


<div class="panel panel-default">
    <div class="panel-heading"><?php echo getLange(''); ?>Add Language</div>
    <div class="panel-body">

        <form role="form"  action="" method="post">
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                <label  class="control-label"><?php echo getLange(''); ?>Langauge</label>
                <input type="text" class="form-control" name="language" value="<?php if(isset($edit)){echo $edit['language']; } ?>" placeholder="Enter Languge" required>

            </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="exampleInputEmail1"><?php echo getLange(''); ?>Direction</label>
                   <select name="direction" class="form-control" required>
                        <option value="rtl" <?php if(isset($edit) && $edit['direction']=='rtl'){echo 'Selected'; } ?>>rtl</option>
                        <option value="ltr" <?php if(isset($edit) && $edit['direction']=='ltr'){echo 'Selected'; } ?>>ltr</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="exampleInputEmail1"><?php echo getLange(''); ?>Is Active</label>
                    <select name="is_active" class="form-control" required>
                        <option value="1" <?php if(isset($edit) && $edit['is_active']==1){echo 'Selected'; } ?>>Active</option>
                        <option value="0" <?php if(isset($edit) && $edit['is_active']==0){echo 'Selected'; } ?>>In Active</option>
                    </select>
                </div>
            </div>
        </div>
        <input type="hidden" name="<?php if(isset($edit)){echo 'edit_id'; } ?>" value="<?php if(isset($edit)){echo $_GET['edit_id']; } ?>">


        <button style="margin: 0 0 8px 3px;" type="submit" name="<?php if(isset($edit)){echo 'updatedata'; }else{ echo 'addlanguage';} ?>" class="add_form_btn" ><?php echo getLange(''); ?>Submit</button>
        </form>

    </div>
</div>

<div class="panel panel-default">

    <div class="panel-heading"><?php echo getLange(''); ?>All Language

    </div>



        <div class="panel-body" id="same_form_layout" style="padding: 11px;">

            <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">





                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info">

                            <thead>

                                <tr >
                                   <th><?php echo getLange(''); ?>Sr No</th>
                                   <th><?php echo getLange(''); ?>Language</th>
                                   <th><?php echo getLange(''); ?>Direction</th>
                                   <th><?php echo getLange(''); ?>is Active</th>
                                   <th><?php echo getLange(''); ?>Default</th>
                                   <th>Action</th>

                                </tr>

                            </thead>

                            <tbody>

                            <?php
                                $query1=mysqli_query($con,"SELECT * FROM portal_language ");


                                $sr=1;
                                while($fetch1=mysqli_fetch_array($query1)){


                            ?>



                                <tr>


                                    <td><?php echo $sr; ?></td>
                                    <td><?php echo ucfirst($fetch1['language']); ?></td>

                                    <td>  <?php echo $fetch1['direction']; ?> </td>
                                    <td><?php echo isset($fetch1['is_active']) &&  $fetch1['is_active'] ==1 ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">InActive</span>'; ?> </td>

                                    <td><?php echo isset($fetch1['is_default']) &&  $fetch1['is_default'] ==1 ? '<span class="label label-success">Default</span>' : '<span class="label label-danger"><a href="portal_language.php?active_id='.$fetch1['id'].'" style="color:#fff;">Set as default</a></span>'; ?> </td>

                                    <td class="center">

                                        <a href="portal_language.php?edit_id=<?php echo $fetch1['id']; ?>"   >
                                              <span class="glyphicon glyphicon-edit"></span>
                                            </a>
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
