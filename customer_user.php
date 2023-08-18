<?php
   session_start();
   include_once "includes/conn.php";
   $id = $_SESSION['customers'];
   if(isset($_SESSION['customers'])){
            require_once "includes/role_helper.php";
    if (!checkRolePermission(9 ,'view_only','')) {

        header("location:access_denied.php");
    }
       include "includes/header.php";
       
   ?>
<section class="bg padding30">
   <div class="container-fluid dashboard">
      <div class="col-lg-2 col-md-3 col-sm-4 profile" style="margin-left:0">
         <?php
            include "includes/sidebar.php";
            ?>
      </div>
      <?php 
    if(isset($_POST['delete'])){
        $id=mysqli_real_escape_string($con,$_POST['id']);
        $query1=mysqli_query($con,"DELETE FROM customer_user WHERE id=$id") or die(mysqli_error($con));
        $rowscount=mysqli_affected_rows($con);
        if($rowscount>0){
            echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you have delete a transport company successfully</div>';
        }
        else{
            echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not delete a transport company unsuccessfully.</div>';
        }
    }
        if(isset($_POST['adduser'])){
            $created_by=isset($_SESSION['customers']) ? $_SESSION['customers'] : '';

            $name=mysqli_real_escape_string($con,$_POST['name']);

            $phone=mysqli_real_escape_string($con,$_POST['phone']);

            $email=mysqli_real_escape_string($con,$_POST['email']);

            $password=md5($_POST['password']);

            $query2=mysqli_query($con,"INSERT INTO `customer_user`(name,phone,email,password,created_by)values('$name','$phone','$email','$password','$created_by')") or die(mysqli_error($con));
            $rowscount=mysqli_affected_rows($con);
            if($query2){
                echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you Add a New User successfully</div>';
            }
            else{
                echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not Add a New User unsuccessfully.</div>';
            }

        }
         if(isset($_POST['edit_id'])){
            
            $id=mysqli_real_escape_string($con,$_POST['edit_id']);

            $name=mysqli_real_escape_string($con,$_POST['name']);

            $phone=mysqli_real_escape_string($con,$_POST['phone']);

            $email=mysqli_real_escape_string($con,$_POST['email']);

            $password=isset($_POST['password']) && $_POST['password']!='' ? md5($_POST['password']) :md5($_POST['old_password']);

            $query2=mysqli_query($con,"update customer_user set name='$name',phone='$phone',email='$email',password='$password' where id=$id") or die(mysqli_error($con));
            $rowscount=mysqli_affected_rows($con);

            if($query2){

                echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you updated a Transport company successfully</div>';

            }

            else{

                echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not upadated a Transport company unsuccessfully.</div>';

            }

        }

        if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
            $query2=mysqli_query($con,"SELECT * FROM customer_user WHERE id=".$_GET['edit_id']);
                $edit=mysqli_fetch_assoc($query2);
              
        } ?>
      <div class="col-lg-10 col-md-9 col-sm-8 dashboard">
         <div class="white" style="    margin-bottom: 25px;">
            <h4 class="Order_list" style="color:#000;">Customer User</h4>
            <div class="row">
                        <div class="panel panel-default">

            <div class="panel-heading">Add Customer User</div>

            <div class="panel-body">



                <form role="form" class="" data-toggle="validator" action="" method="post" novalidate="true">

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Name</label>
                                <input type="text" class="form-control" name="name" value="<?php echo  isset($edit['name']) ? $edit['name'] : ''; ?>" placeholder="Name" required="">
<div class="help-block with-errors email_errorr"></div>

                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">

                                <label class="control-label">Phone</label>

                                 <input type="text" class="form-control" name="phone" value="<?php echo  isset($edit['phone']) ? $edit['phone'] : ''; ?>" placeholder="Phone No" required="">
<div class="help-block with-errors email_errorr"></div>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">

                                <label class="control-label">Email</label>

                                 <input type="email" class="form-control emailleee" name="email" value="<?php echo  isset($edit['email']) ? $edit['email'] : ''; ?>" placeholder="Email" required="">

                               <div class="help-block with-errors email_errorr"></div>

                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">

                                <label class="control-label">Password</label>

                                 <input type="password" class="form-control" name="password" value="" placeholder="Password" <?php echo isset($edit['password']) ? '' : 'required'; ?>>
                                 <div class="help-block with-errors email_errorr"></div>
                            </div>
                        </div>
                    </div>
                     <input type="hidden" class="user_id" <?php echo isset($edit['id']) ? "name='edit_id'" : ''; ?> value="<?php echo isset($edit['id']) ? $edit['id'] : ''; ?>">
                    <input type="hidden" <?php echo isset($edit['id']) ? "name='old_password'" : ''; ?> value="<?php echo isset($edit['password']) ? $edit['password'] : ''; ?>">
                 <button type="submit" class="add_form_btn btn btn-info submit" <?php echo isset($edit['password']) ? '' : "name='adduser'"; ?>>Add</button>
                </form>
            </div>
        </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">Customer User
    </div>
        <div class="panel-body" id="same_form_layout" style="padding: 11px;">
            <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info">
                            <thead>
                                <tr role="row">
                                    <th style="width: 5%;"><?php echo getLange('sr'); ?>#</th>
                                   <th style="width: 25%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php echo getLange('name'); ?> </th>
                                   <th style="width: 25%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;">Phone </th>
                                   <th style="width: 25%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;">Email </th>
                                  <th  style="width: 25%;" class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 108px;"><?php echo getLange('action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $sr_no = 1;
                                $query1=mysqli_query($con,"SELECT * FROM customer_user WHERE created_by='".$_SESSION['customers']."' ORDER BY id DESC");
                                while($fetch1=mysqli_fetch_array($query1)){
                            ?>
                                <tr class="gradeA odd" role="row">
                                    <td class="sorting_1"><?php echo $sr_no++; ?></td>
                                    <td class="sorting_1"><?php echo $fetch1['name']; ?></td>
                                    <td class="sorting_1"><?php echo $fetch1['phone']; ?></td>
                                    <td class="sorting_1"><?php echo $fetch1['email']; ?></td></td>
                                    <td class="inline_Btn">
                                        <form action="" method="get" style="display: inline-block;">
                                            <input type="hidden" name="edit_id" value="<?php echo $fetch1['id']; ?>">
                                            <button type="submit">
                                              <span class="glyphicon glyphicon-edit"></span>
                                            </button>
                                        </form>

                                        <form action="" method="post" style="display: inline-block;">
                                            <input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">
                                            <button type="submit" name="delete" onclick="return confirm('Are You Sure Delete this User')" >
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
   </div>
</section>
</div>
<?php
   }
   else{
       header("location:index.php");
   }
   ?>
<?php include 'includes/footer.php'; ?>
<script type="text/javascript">
    
    $(document).on('blur','.emailleee',function(){
    var email=$(this).val();
    var user_id=$('.user_id').val();
    var email_current=$(this);
    error=$(this).parent().find("div.help-block");
    if(email!=""){
        var postdata="cusaction=cusaction&cusemail="+email+"&user_id="+user_id;
        $.ajax({
            type:'POST',
            data:postdata,
            url:'ajax.php',
            success:function(fetch){
            error.html(fetch);
                    if(error.html()!==""){
                        $(email_current).parent().addClass("has-error").addClass("has-danger");
                        $('.submit').attr('disabled' , true);
                    }else{
                        $('.submit').attr('disabled' , false);
                         $('.help-block').html("");
                    }
                }
            });
        }
});
</script>