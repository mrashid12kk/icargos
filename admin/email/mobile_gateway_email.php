<div class="col-sm-12 outer_shadow">
    		<div class="row">
	            <div class="col-sm-6 form_box">
	                <label for="">API Name</label>
	                <input type="text" ng-model="model.api_name_web" class="ng-pristine ng-untouched ng-valid ng-not-empty" aria-invalid="false" style="">
	            </div>
				<div class="col-sm-6 form_box">
					<label for="">API Key</label>
					<input type="text" ng-model="model.api_key_web" class="ng-pristine ng-untouched ng-valid ng-not-empty" aria-invalid="false" style="">
				</div> 
	            <div class="col-sm-6 form_box">
	                <label for="">API URL</label>
	                <input type="text" ng-model="model.api_url_web" class="ng-pristine ng-untouched ng-valid ng-not-empty" aria-invalid="false" style="">
	            </div>
	            <div class="col-sm-6 form_box">
					<label for="">Device ID</label>
					<input type="text" value="1" ng-model="model.device_id" class="ng-pristine ng-untouched ng-valid ng-not-empty" aria-invalid="false">
				</div> 
	            <div class="col-sm-6 form_box">
	                <label for="">SIM Slot (SIM1 OR SIM2)</label>
	                <input type="text" ng-model="model.sim_slot" value="1" class="ng-pristine ng-untouched ng-valid ng-not-empty" aria-invalid="false">
	            </div>
	        </div>
	        <div class="row">
				<div class="col-sm-12 send_btn">
					<!-- <button class="refresh_btn" ng-click="resetForm()" type="button">Refresh</button> -->
					<button class="trash_button ng-binding" ng-click="saveWebSettings($event)" type="button">Save</button>
				</div>
			</div>
	        	
	    </div>