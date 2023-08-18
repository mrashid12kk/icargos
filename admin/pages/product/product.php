<?php
    if(isset($_POST['delete'])){
        $id=mysqli_real_escape_string($con,$_POST['id']);
        $query1=mysqli_query($con,"delete from products where id=$id") or die(mysqli_error($con));
        $rowscount=mysqli_affected_rows($con);
        if($rowscount>0){
            echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you have delete a Product successfully</div>';
        }
        else{
            echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not delete a Product unsuccessfully.</div>';
        }
    }
?>
<?php

        if(isset($_POST['addproduct'])){




            $name=mysqli_real_escape_string($con,$_POST['name']);

            $query2=mysqli_query($con,"INSERT into `products`(name)values('$name')") or die(mysqli_error($con));
            $rowscount=mysqli_affected_rows($con);
            if($query2){
                echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you Add a Product successfully</div>';
            }
            else{
                echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not Add a Product unsuccessfully.</div>';
            }

        }
         if(isset($_POST['updateproduct'])){



            $id=mysqli_real_escape_string($con,$_POST['edit_id']);

            $name=mysqli_real_escape_string($con,$_POST['name']);

            $query2=mysqli_query($con,"update products set name='$name' where id=$id") or die(mysqli_error($con));
            $rowscount=mysqli_affected_rows($con);



            if($query2){

                echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you updated a Product successfully</div>';

            }

            else{

                echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not upadated a Product unsuccessfully.</div>';

            }

        }
      
        $allmodes = mysqli_query($con,$sql);
        if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
        	$query2=mysqli_query($con,"SELECT * FROM products WHERE id=".$_GET['edit_id']);
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

    <div class="panel-heading"><?php echo getLange('add').' '.getLange('Product'); ?></div>

    <div class="panel-body">



        <form role="form" class="" data-toggle="validator" action="" method="post">

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">

                        <label  class="control-label"><?php echo getLange('Product').' '.getLange('name'); ?></label>
                        <input type="text"  class="form-control" name="name" value="<?php if(isset($edit)){ echo $edit['name'];} ?>" placeholder="<?php echo getLange('enter').' '.getLange('Product').' '.getLange('name'); ?>" required>

                        <div class="help-block with-errors "></div>

                    </div>
                </div>
                
            </div>

            <input type="hidden" name="<?php if(isset($edit)){ echo 'edit_id';} ?>" value="<?php if(isset($edit)){ echo $edit['id'];} ?>">

                <input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">

         <button type="submit" name="<?php if(isset($edit)){ echo 'updateproduct';}else{echo 'addproduct';} ?>" class="add_form_btn" ><?php echo getLange('add'); ?></button>

        </form>



    </div>

</div>
<div class="panel panel-default">
    <div class="panel-heading"><?php echo getLange('Product'); ?>

    </div>
        <div class="panel-body" id="same_form_layout" style="padding: 11px;">
            <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">

                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info">
                            <thead>
                                <tr role="row">
                                    <th style="width: 5%;"><?php echo getLange('sr'); ?>#</th>
                                   <th style="width: 30%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php echo getLange('Product').' '.getLange('name'); ?> </th>
                                  <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 108px;"><?php echo getLange('action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php

                                $sr_no = 1;
                                $query1=mysqli_query($con,"Select * from products order by id desc");
                                while($fetch1=mysqli_fetch_array($query1)){
                            ?>
                                <tr class="gradeA odd" role="row">
                                    <td class="sorting_1"><?php echo $sr_no++; ?></td>
                                    <td class="sorting_1"><?php echo $fetch1['name']; ?></td>
                                    <td class="center inline_Btn">
                                        <form action="product.php" method="get" style="display: inline-block;">
                                            <input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">
                                            <button type="submit" name="edit_id" value="<?php echo $fetch1['id']; ?>">
                                              <span class="glyphicon glyphicon-edit"></span>
                                            </button>
                                            <input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">

                                        </form>

                                        <form action="product.php" method="post" style="display: inline-block;">
                                            <input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">
                                            <button type="submit" name="delete" onclick="return confirm('Are You Sure Delete this Product')" >
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
