<?php
if (isset($_GET['delete_id'])) {
   $ex = $_GET['delete_id'];

   mysqli_query($con, "DELETE FROM country WHERE id=" . $ex . " ");
   $_SESSION['msg'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Country Deleted Sucessfully.</div>';
   header('Location: location_list.php');
}
?>
<div class="col-sm-12 outer_shadow table_template">
    <div class="top_heading">
        <h3 class="ng-binding">Location List</h3>
        <a href="add_city.php" class="btn btn-info" style="margin-top: -60px;margin-left: 785px;">Add New</a>
    </div>
    <?php if (isset($_SESSION['msg']) && !empty($_SESSION['msg'])) {
      echo $_SESSION['msg'];
      unset($_SESSION['msg']);
   } ?>
    <style>
    md-table-container thead input {
        width: 100%;
    }

    .overaly_container.list_popup_box {
        max-width: 99% !important;
    }

    table.md-table th.md-column md-icon {
        height: 12px;
        width: 9px;
        font-size: 9px !important;
        line-height: 16px !important;
    }

    .list_popup_box table.md-table td.md-cell,
    .list_popup_box table.md-table th.md-column {
        padding: 2px 3px !important;
    }

    md-icon svg {
        width: 12px;
        vertical-align: middle;
        margin-top: 1px;
    }

    .md-row th:nth-child(2) {
        width: 46px !important;
    }

    md-icon {
        height: 6px;
        width: 6px;
        min-height: 6px;
        min-width: 6px;
    }

    table.md-table td.md-cell {
        font-size: 11px;
    }

    table.md-table th.md-column {
        font-size: 10px;
    }

    md-table-container table.md-table thead.md-head>tr.md-row {
        height: 30px;
    }

    md-table-pagination .md-button md-icon {
        color: #000;
    }

    md-table-pagination .md-button[disabled] md-icon {
        color: #6d6d6d;
    }

    md-table-container img {
        height: 35px;
    }

    table.md-table th.md-column md-icon:not(:first-child) {
        margin-left: 0;
    }

    .template_content {
        padding: 7px 8px 10px;
        line-height: 16px;
        height: 24px;
        display: -webkit-box;
        max-width: 410px;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    </style>
    <!-- <form>
        <div class="row">
            <div class="col-sm-9">
                <input type="text" class="form-control" id="country_name" placeholder="Enter For Search">
            </div>
            <div class="col-sm-1"></div>
            <div class="col-sm-2">
                <button class="btn btn-primary search_pincode"> Submit</button>
            </div>
        </div>
    </form> -->
    <br>
    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer"
        id="basic-datatable" role="grid" aria-describedby="basic-datatable_info">
        <thead>
            <tr role="row">
                <th style="width: 10%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1"
                    colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending"
                    style="width: 179px;">SR
                    No </th>
                <th style="width: 20%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1"
                    colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending"
                    style="width: 179px;">
                    Country</th>
                <th style="width: 20%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1"
                    colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending"
                    style="width: 179px;">
                    State / Province</th>
                <th style="width: 20%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1"
                    colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending"
                    style="width: 179px;">STN
                    CODE</th>
                <th style="width: 20%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1"
                    colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending"
                    style="width: 179px;">City
                    Name</th>
                <th style="width: 20%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1"
                    colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending"
                    style="width: 179px;">
                    Title</th>
                <th style="width: 20%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1"
                    colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending"
                    style="width: 179px;">Area
                    Code</th>
                <!-- <th style="width: 88%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php echo getLange('gst'); ?> </th> -->
                <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1"
                    aria-label="CSS grade: activate to sort column ascending" style="width: 108px;">
                    <?php echo getLange('action'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
         $srno = 1;
         $stateWhere = ' 1';
         if (isset($_GET['state_id']) && !empty($_GET['state_id'])) {
            $stateWhere = " cities.state_id =" . $_GET['state_id'];
         }
         $query1 = mysqli_query($con, "SELECT cities.*,country.country_name,state.state_name from cities LEFT JOIN country on cities.country_id=country.id LEFT JOIN state on cities.state_id=state.id Where $stateWhere order by cities.id desc");
         while ($fetch1 = mysqli_fetch_array($query1)) {
         ?>
            <tr class="gradeA odd" role="row">
                <td class="sorting_1"><?php echo $srno++;; ?></td>
                <td class="sorting_1"><?php echo $fetch1['country_name']; ?></td>
                <td class="sorting_1"><?php echo $fetch1['state_name']; ?></td>
                <td class="sorting_1"><?php echo $fetch1['stn_code']; ?></td>
                <td class="sorting_1"><?php echo $fetch1['city_name']; ?></td>
                <td class="sorting_1"><?php echo $fetch1['title']; ?></td>
                <td class="sorting_1"><?php echo $fetch1['area_code']; ?></td>
                <!-- <td class="sorting_1"><?php echo $fetch1['gst']; ?>%</td> -->
                <td class="center inline_Btn">
                    <form action="editcities.php" method="post" style="display: inline-block;">
                        <input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">
                        <button type="submit" name="edit_id">
                            <span class="glyphicon glyphicon-edit"></span>
                        </button>
                        <input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">

                    </form>

                    <form action="citiesdata.php" method="post" style="display: inline-block;">
                        <input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">
                        <button type="submit" name="delete"
                            onclick="return confirm('Are You Sure Delete this Employee')">
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