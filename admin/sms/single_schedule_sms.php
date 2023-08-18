<div class="col-sm-12 outer_shadow">
			<div class="row">
				<div class="col-sm-8 colums_gapp pl-0">
					<div class="top_heading">
						<h3 class="ng-binding">Select Receipient Details</h3>
					</div>
					<div class="row">
						<div class="col-sm-3 form_box">
							<label>Type</label>
							<select ng-model="model.type" class="ng-pristine ng-untouched ng-valid ng-not-empty" aria-invalid="false">
								<option value="customers" selected="selected">Customers</option>
								<option value="employees">Employees</option>
								<option value="suppliers">Suppliers</option>
							</select>
						</div>
						<div class="col-sm-3 form_box">
							<label>Group</label>
							<select ng-model="model.group" ng-options="item.id as item.name for item in contactGroups" class="ng-pristine ng-untouched ng-valid ng-empty" aria-invalid="false"><option value="" class="ng-binding" selected="selected">All customers</option><option label="Wholesale Customer" value="string:1">Wholesale Customer</option><option label="Regular Customer" value="string:2">Regular Customer</option><option label="cash customer" value="string:7">cash customer</option></select>
						</div>
						<div class="col-sm-2 form_box">
							<label>Send to</label>
							<select ng-model="model.sendTo" class="ng-pristine ng-untouched ng-valid ng-not-empty" aria-invalid="false">
								<option value="group" selected="selected">Group</option>
								<option value="single">Single</option>
							</select>
						</div>
						<div class="col-sm-4 form_box">
							<label for="" class="ng-binding">Contact Number</label>
							<div class="searchBox">
								<!-- ngIf: !model.contact --><input type="text" ng-disabled="model.sendTo == 'group'" ng-if="!model.contact" ng-keyup="loadContacts($event)" ng-model="model.number" class="ng-pristine ng-untouched ng-valid ng-scope ng-empty" disabled="disabled" aria-invalid="false"><!-- end ngIf: !model.contact -->
								<!-- ngIf: model.contact -->
								<!-- ngIf: model.contact -->
								<ul>
									<!-- ngRepeat: (key, contact) in contacts -->
								</ul>
								<div class="overlay" ng-click="hideSearchBox($event)" role="button" tabindex="0"></div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6 form_box">
							<label>Date/Time</label>
							 
							<select ng-model="model.time" class="time_selected_box ng-pristine ng-untouched ng-valid ng-not-empty" aria-invalid="false">
								<option value="00:00">00:00</option>
								<option value="01:00">01:00</option>
								<option value="02:00">02:00</option>
								<option value="03:00">03:00</option>
								<option value="04:00">04:00</option>
								<option value="05:00">05:00</option>
								<option value="06:00">06:00</option>
								<option value="07:00">07:00</option>
								<option value="08:00">08:00</option>
								<option value="09:00">09:00</option>
								<option value="10:00">10:00</option>
								<option value="11:00">11:00</option>
								<option value="12:00" selected="selected">12:00</option>
								<option value="13:00">13:00</option>
								<option value="14:00">14:00</option>
								<option value="15:00">15:00</option>
								<option value="16:00">16:00</option>
								<option value="17:00">17:00</option>
								<option value="18:00">18:00</option>
								<option value="19:00">19:00</option>
								<option value="20:00">20:00</option>
								<option value="21:00">21:00</option>
								<option value="22:00">22:00</option>
								<option value="23:00">23:00</option>
							</select>
							<!-- <ng-datetimepicker ng-model="model.datetime" ng-open="noKadete"></ng-datetimepicker> -->
							<!-- <input type="text" ng-value="model.datetime | date : 'MMM dd,yyyy hh'" readonly="true" ng-click="noKadete()" > -->
						</div>
						<div class="col-sm-6 form_box">
							<label for="" class="ng-binding">Select Template</label>
							<select ng-model="model.templateID" ng-options="option.id as option.template_name for option in smsTemplates" class="ng-pristine ng-untouched ng-valid ng-empty" aria-invalid="false"><option value="" class="" selected="selected">Choose</option><option label="Sale Template" value="string:1">Sale Template</option><option label="Installment Voucher" value="string:2">Installment Voucher</option><option label="Receipt SMS" value="string:4">Receipt SMS</option><option label="Lead Template" value="string:5">Lead Template</option></select>
						</div>

						<div class="col-sm-6 form_box">
							<label>Repeat</label>
							<select ng-model="model.repeatType" class="ng-pristine ng-untouched ng-valid ng-not-empty" aria-invalid="false">
								<option value="never" selected="selected">Never</option>
								<option value="daily">Daily</option>
								<option value="weekly">Weekly</option>
								<option value="monthly">Monthly</option>
							</select>
						</div>
					</div>
					<div class="row">
						
						<!-- ngIf: model.repeatType == 'weekly' -->
						<!-- ngIf: model.repeatType == 'monthly' -->
						<!-- ngIf: model.repeatType == 'monthly' -->
					</div>
					<div class="row">
						<div class="col-sm-12 form_box">
							<label for="" class="ng-binding">Compose SMS</label>
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
							<!-- <button class="refresh_btn" ng-click="resetForm()" type="button">{{ 'refresh_title' | translate }}</button> -->
							<button class="send_button ng-binding" ng-click="sendMessage($event)" type="button">Save</button>
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