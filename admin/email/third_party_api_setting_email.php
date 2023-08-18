<div class="col-sm-12 outer_shadow">
    		<div class="row">
				<!-- <div class="col-sm-6 form_box">
					<label for="">API Key</label>
					<input type="text" ng-model="model.api_key">
				</div>
				<div class="col-sm-6 form_box">
					<label for="">API Secret</label>
					<input type="text"  ng-model="model.api_secret">
				</div> -->
	            <div class="col-sm-6 form_box">
	                <label for="">API Name</label>
	                <input type="text" ng-model="model.api_name" class="ng-pristine ng-untouched ng-valid ng-empty" aria-invalid="false">
	            </div>
	            <div class="col-sm-6 form_box">
	                <label for="">API URL</label>
	                <input type="text" ng-model="model.api_url" class="ng-pristine ng-untouched ng-valid ng-empty" aria-invalid="false">
	            </div>
	            <div class="col-sm-3 form_box">
	                <label for="" class="ng-binding">Username Variable</label>
	                <input type="text" ng-model="model.username_variable" class="ng-pristine ng-untouched ng-valid ng-empty" aria-invalid="false">
	            </div>
	            <div class="col-sm-3 form_box">
	                <label for="" class="ng-binding">Username Value</label>
	                <input type="text" ng-model="model.username_value" class="ng-pristine ng-untouched ng-valid ng-empty" aria-invalid="false">
	            </div>
	            <div class="col-sm-3 form_box">
	                <label for="" class="ng-binding">Password Variable</label>
	                <input type="text" ng-model="model.password_variable" class="ng-pristine ng-untouched ng-valid ng-empty" aria-invalid="false">
	            </div>
	            <div class="col-sm-3 form_box">
	                <label for="" class="ng-binding">Password Value</label>
	                <input type="text" ng-model="model.password_value" class="ng-pristine ng-untouched ng-valid ng-empty" aria-invalid="false">
	            </div>
	            <div class="col-sm-3 form_box">
	                <label for="" class="ng-binding">Company Name</label>
	                <input type="text" ng-model="model.company_name" class="ng-pristine ng-untouched ng-valid ng-empty" aria-invalid="false">
	            </div>
	            <div class="col-sm-3 form_box">
	                <label for="" class="ng-binding">Single SMSDestination Variable</label>
	                <input type="text" ng-model="model.destination_variable" class="ng-pristine ng-untouched ng-valid ng-empty" aria-invalid="false">
	            </div>
	            <div class="col-sm-3 form_box">
	                <label for="" class="ng-binding">Message Variable</label>
	                <input type="text" ng-model="model.message_variable" class="ng-pristine ng-untouched ng-valid ng-empty" aria-invalid="false">
	            </div>

	            <div class="col-sm-3 form_box">
	                <label for="" class="ng-binding">Success Code</label>
	                <input type="text" ng-model="model.success_code" class="ng-pristine ng-untouched ng-valid ng-empty" aria-invalid="false">
	            </div>
	            
	            
	            
	        </div>
	        <div class="row">
				<div class="col-sm-12 send_btn">
					<!-- <button class="refresh_btn" ng-click="resetForm()" type="button">Refresh</button> -->
					<button class="trash_button ng-binding" ng-click="saveSettings($event)" type="button">Save</button>
				</div>
			</div>
	        	
	    </div>