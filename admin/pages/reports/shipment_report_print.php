<div class="panel panel-default" style="margin-top:0;">
    
    <div class="panel-body" id="same_form_layout">
        <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">

            <div class="row">
                <div class="col-sm-12 table-responsive gap-none bordernone" style="padding:0;">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered no-footer" role="grid">
                            <thead>
                                <tr role="row">
                                    <th><input type="checkbox" name="" class="main_select"></th>
                                    <th><?php echo getLange('trackingno'); ?> </th>
                                    <th><?php echo getLange('servicetype'); ?> </th>
                                    <th><?php echo getLange('pickupname'); ?> </th>
                                    <th><?php echo getLange('pickupaddress'); ?> </th>
                                    <th><?php echo getLange('deliveryname'); ?> </th>
                                    <th><?php echo getLange('deliveryaddress'); ?> </th>
                                    <th><?php echo getLange('deliveryphone'); ?> </th>
                                    <th><?php echo getLange('weightkg'); ?></th>
                                    <th><?php echo getLange('codamount'); ?> </th>
                                    <th><?php echo getLange('rider'); ?> </th>
                                    <th><?php echo getLange('receivedby'); ?> </th>
                                    <th><?php echo getLange('signature'); ?> </th>
                                    <th>Receiver CNIC</th>
                                    <th>Receiver CNIC Image</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="8" style="background-color: #F5F5F5;"> <?php echo getLange('total'); ?>
                                    </td>
                                    <td class="parcelweight" style="background-color: #b6dde8;"></td>
                                    <td class="codamount" style="background-color: #c2d69a;"></td>
                                    <td colspan="4" style="background-color: #F5F5F5;"></td>
                                </tr>
                            </tfoot>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>