<div class="col-sm-12 outer_shadow">
			<div class="row">
				<div class="col-sm-8 colums_gapp pl-0">
					<div class="top_heading">
						<h3 class="ng-binding">Select Receipient Details</h3>
					</div>
					<div class="row">
						<div class="col-sm-6 form_box">
							<label for="" class="ng-binding">Select Groups</label>
							<select ng-model="model.groupID" ng-options="option.id as option.group_name for option in contactGroups" class="ng-pristine ng-untouched ng-valid ng-empty" aria-invalid="false"><option value="" class="ng-binding" selected="selected">All Customers</option><option label="undefined" value="string:1"></option><option label="undefined" value="string:2"></option><option label="undefined" value="string:7"></option></select>
						</div>
						<div class="col-sm-6 form_box">
							<label for="" class="ng-binding">Select Template</label>
							<select ng-model="model.templateID" ng-options="option.id as option.template_name for option in smsTemplates" class="ng-pristine ng-untouched ng-valid ng-empty" aria-invalid="false"><option value="" class="" selected="selected">Choose</option><option label="Sale Template" value="string:1">Sale Template</option><option label="Installment Voucher" value="string:2">Installment Voucher</option><option label="Receipt SMS" value="string:4">Receipt SMS</option><option label="Lead Template" value="string:5">Lead Template</option></select>
						</div>
						<div class="col-sm-12 form_box">
							<label for="" class="ng-binding">Massege</label>
							<textarea id="messageArea" type="text" ng-trim="false" ng-model="model.message" class="ng-pristine ng-untouched ng-valid ng-empty" aria-invalid="false"></textarea>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-3 form_box">
							<label for="" class="ng-binding">Letter Count</label>
							<input type="text" ng-disabled="true" ng-value="model.message.length" disabled="disabled">
						</div>
						<div class="col-sm-3 form_box">
							<label for="" class="ng-binding">SMS Count</label>
							<input type="text" ng-disabled="true" ng-value="(model.message.length/160) | ceil" value="0" disabled="disabled">
						</div>
						<div class="col-sm-6 send_btn">
							<button class="refresh_btn ng-binding" ng-click="resetForm()" type="button">Refresh</button>
							<button class="send_button ng-binding" ng-click="sendMessage($event)" type="button">Send</button>
						</div>
					</div>
				</div>
				<div class="col-sm-4 parametres_box">
					<!-- ngInclude: public_url+'sms/partials/shortcodes.html' --><div ng-include="public_url+'sms/partials/shortcodes.html'" class="ng-scope"><div class="sale_invoice ng-scope">
    <!-- <h3>{{ 'general_parameters_sms_title' | translate }}</h3> -->
    <h3 class="parameters">Invoice Parameters <i class="fa fa-plus "></i></h3>
    <ul class="parameters_box">
        <!-- ngRepeat: (key, code) in general_shortcodes --><li ng-repeat="(key, code) in general_shortcodes" class="ng-scope"><a href="#" ng-click="insertShortCode($event)" class="ng-binding">@Employee_Name</a></li><!-- end ngRepeat: (key, code) in general_shortcodes --><li ng-repeat="(key, code) in general_shortcodes" class="ng-scope"><a href="#" ng-click="insertShortCode($event)" class="ng-binding">@Customer_Code</a></li><!-- end ngRepeat: (key, code) in general_shortcodes --><li ng-repeat="(key, code) in general_shortcodes" class="ng-scope"><a href="#" ng-click="insertShortCode($event)" class="ng-binding">@Customer_Name</a></li><!-- end ngRepeat: (key, code) in general_shortcodes --><li ng-repeat="(key, code) in general_shortcodes" class="ng-scope"><a href="#" ng-click="insertShortCode($event)" class="ng-binding">@Supplier_Code</a></li><!-- end ngRepeat: (key, code) in general_shortcodes --><li ng-repeat="(key, code) in general_shortcodes" class="ng-scope"><a href="#" ng-click="insertShortCode($event)" class="ng-binding">@Supplier_Name</a></li><!-- end ngRepeat: (key, code) in general_shortcodes --><li ng-repeat="(key, code) in general_shortcodes" class="ng-scope"><a href="#" ng-click="insertShortCode($event)" class="ng-binding">@lead_Name</a></li><!-- end ngRepeat: (key, code) in general_shortcodes --><li ng-repeat="(key, code) in general_shortcodes" class="ng-scope"><a href="#" ng-click="insertShortCode($event)" class="ng-binding">@Invoice_Date</a></li><!-- end ngRepeat: (key, code) in general_shortcodes --><li ng-repeat="(key, code) in general_shortcodes" class="ng-scope"><a href="#" ng-click="insertShortCode($event)" class="ng-binding">@Invoice_Total</a></li><!-- end ngRepeat: (key, code) in general_shortcodes --><li ng-repeat="(key, code) in general_shortcodes" class="ng-scope"><a href="#" ng-click="insertShortCode($event)" class="ng-binding">@Invoice_Payment</a></li><!-- end ngRepeat: (key, code) in general_shortcodes --><li ng-repeat="(key, code) in general_shortcodes" class="ng-scope"><a href="#" ng-click="insertShortCode($event)" class="ng-binding">@Invoice_Balance</a></li><!-- end ngRepeat: (key, code) in general_shortcodes --><li ng-repeat="(key, code) in general_shortcodes" class="ng-scope"><a href="#" ng-click="insertShortCode($event)" class="ng-binding">@Ledger_Balance</a></li><!-- end ngRepeat: (key, code) in general_shortcodes --><li ng-repeat="(key, code) in general_shortcodes" class="ng-scope"><a href="#" ng-click="insertShortCode($event)" class="ng-binding">@Invoice_No</a></li><!-- end ngRepeat: (key, code) in general_shortcodes --><li ng-repeat="(key, code) in general_shortcodes" class="ng-scope"><a href="#" ng-click="insertShortCode($event)" class="ng-binding">@Company_Name</a></li><!-- end ngRepeat: (key, code) in general_shortcodes -->
    </ul>
</div>
<div class="sale_invoice  ng-scope">
    <!-- <h3>{{ 'general_parameters_sms_title' | translate }}</h3> -->
    <h3 class="parameters-1">Payment, Receipt, JV Voucher Parameters <i class="fa fa-plus"></i></h3>
    <ul class="payment_box">
        <!-- ngRepeat: (key, code) in payment_receipt_jv_voucher_shortcodes --><li ng-repeat="(key, code) in payment_receipt_jv_voucher_shortcodes" class="ng-scope"><a href="#" ng-click="insertShortCode($event)" class="ng-binding">@Ledger_Code</a></li><!-- end ngRepeat: (key, code) in payment_receipt_jv_voucher_shortcodes --><li ng-repeat="(key, code) in payment_receipt_jv_voucher_shortcodes" class="ng-scope"><a href="#" ng-click="insertShortCode($event)" class="ng-binding">@Ledger_Name</a></li><!-- end ngRepeat: (key, code) in payment_receipt_jv_voucher_shortcodes --><li ng-repeat="(key, code) in payment_receipt_jv_voucher_shortcodes" class="ng-scope"><a href="#" ng-click="insertShortCode($event)" class="ng-binding">@Voucher_Date</a></li><!-- end ngRepeat: (key, code) in payment_receipt_jv_voucher_shortcodes --><li ng-repeat="(key, code) in payment_receipt_jv_voucher_shortcodes" class="ng-scope"><a href="#" ng-click="insertShortCode($event)" class="ng-binding">@Ledger_Payment</a></li><!-- end ngRepeat: (key, code) in payment_receipt_jv_voucher_shortcodes --><li ng-repeat="(key, code) in payment_receipt_jv_voucher_shortcodes" class="ng-scope"><a href="#" ng-click="insertShortCode($event)" class="ng-binding">@Voucher_No</a></li><!-- end ngRepeat: (key, code) in payment_receipt_jv_voucher_shortcodes --><li ng-repeat="(key, code) in payment_receipt_jv_voucher_shortcodes" class="ng-scope"><a href="#" ng-click="insertShortCode($event)" class="ng-binding">@Ledger_Balance_After_Payment</a></li><!-- end ngRepeat: (key, code) in payment_receipt_jv_voucher_shortcodes --><li ng-repeat="(key, code) in payment_receipt_jv_voucher_shortcodes" class="ng-scope"><a href="#" ng-click="insertShortCode($event)" class="ng-binding">@Company_Name</a></li><!-- end ngRepeat: (key, code) in payment_receipt_jv_voucher_shortcodes -->
    </ul>
</div>
<div class="sale_invoice ng-scope">
    <!-- <h3>{{ 'general_parameters_sms_title' | translate }}</h3> -->
    <h3 class="parameters-2">Installment Voucher Parameters <i class="fa fa-plus"></i></h3>
    <ul class="key_box">
        <!-- ngRepeat: (key, code) in installment_voucher_shortcodes --><li ng-repeat="(key, code) in installment_voucher_shortcodes" class="ng-scope"><a href="#" ng-click="insertShortCode($event)" class="ng-binding">@Invoice_No</a></li><!-- end ngRepeat: (key, code) in installment_voucher_shortcodes --><li ng-repeat="(key, code) in installment_voucher_shortcodes" class="ng-scope"><a href="#" ng-click="insertShortCode($event)" class="ng-binding">@Voucher_No</a></li><!-- end ngRepeat: (key, code) in installment_voucher_shortcodes --><li ng-repeat="(key, code) in installment_voucher_shortcodes" class="ng-scope"><a href="#" ng-click="insertShortCode($event)" class="ng-binding">@Ledger_Code</a></li><!-- end ngRepeat: (key, code) in installment_voucher_shortcodes --><li ng-repeat="(key, code) in installment_voucher_shortcodes" class="ng-scope"><a href="#" ng-click="insertShortCode($event)" class="ng-binding">@Ledger_Name</a></li><!-- end ngRepeat: (key, code) in installment_voucher_shortcodes --><li ng-repeat="(key, code) in installment_voucher_shortcodes" class="ng-scope"><a href="#" ng-click="insertShortCode($event)" class="ng-binding">@ledger_Balance_After_Receipt</a></li><!-- end ngRepeat: (key, code) in installment_voucher_shortcodes --><li ng-repeat="(key, code) in installment_voucher_shortcodes" class="ng-scope"><a href="#" ng-click="insertShortCode($event)" class="ng-binding">@Installment_Date</a></li><!-- end ngRepeat: (key, code) in installment_voucher_shortcodes --><li ng-repeat="(key, code) in installment_voucher_shortcodes" class="ng-scope"><a href="#" ng-click="insertShortCode($event)" class="ng-binding">@Installment_Amount</a></li><!-- end ngRepeat: (key, code) in installment_voucher_shortcodes --><li ng-repeat="(key, code) in installment_voucher_shortcodes" class="ng-scope"><a href="#" ng-click="insertShortCode($event)" class="ng-binding">@Receipt_Date</a></li><!-- end ngRepeat: (key, code) in installment_voucher_shortcodes --><li ng-repeat="(key, code) in installment_voucher_shortcodes" class="ng-scope"><a href="#" ng-click="insertShortCode($event)" class="ng-binding">@Receipt_Amount</a></li><!-- end ngRepeat: (key, code) in installment_voucher_shortcodes --><li ng-repeat="(key, code) in installment_voucher_shortcodes" class="ng-scope"><a href="#" ng-click="insertShortCode($event)" class="ng-binding">@OverDue_Amount</a></li><!-- end ngRepeat: (key, code) in installment_voucher_shortcodes --><li ng-repeat="(key, code) in installment_voucher_shortcodes" class="ng-scope"><a href="#" ng-click="insertShortCode($event)" class="ng-binding">@Voucher_Discount</a></li><!-- end ngRepeat: (key, code) in installment_voucher_shortcodes --><li ng-repeat="(key, code) in installment_voucher_shortcodes" class="ng-scope"><a href="#" ng-click="insertShortCode($event)" class="ng-binding">@Invoice_Balance</a></li><!-- end ngRepeat: (key, code) in installment_voucher_shortcodes --><li ng-repeat="(key, code) in installment_voucher_shortcodes" class="ng-scope"><a href="#" ng-click="insertShortCode($event)" class="ng-binding">@Company_Name</a></li><!-- end ngRepeat: (key, code) in installment_voucher_shortcodes -->
    </ul>
</div>
<!-- <div class="sale_invoice" ng-if="model.voucherType == 10 || (model.voucherType >= 13 && model.voucherType <= 16) || model.voucherType == 19">
    <h3>{{ 'invoice_parameters_sms_title' | translate }}</h3>
    <ul>
        <li ng-repeat="(key, code) in invoice_shortcodes"><a href="#" ng-click="insertShortCode($event)">{{code}}</a></li>
    </ul>
</div> -->
<!-- <div class="sale_invoice" ng-if="model.voucherType == 4 || model.voucherType == 5">
    <h3>{{ 'payment_receipt_voucher_parameters_sms_title' | translate }}</h3>
    <ul>
        <li ng-repeat="(key, code) in voucher_shortcodes"><a href="#" ng-click="insertShortCode($event)">{{code}}</a></li>
    </ul>
</div> -->

<script type="text/javascript" class="ng-scope">


$(".parameters").click(function(){
  $(".parameters_box").toggle();
});
$(".parameters-1").click(function(){
  $(".payment_box").toggle();
});
$(".parameters-2").click(function(){
  $(".key_box").toggle();
});

</script></div>
				</div>
			</div>
	    </div>