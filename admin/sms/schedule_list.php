<div class="col-sm-12 outer_shadow">
			<div class="top_heading">
				<h3 class="ng-binding">Filters</h3>
			</div>
	        <div class="row">
	            <div class="col-sm-3 form_box">
	                <label for="" class="ng-binding">Date Form</label>
	                <div class="form-group">
											<input type="date" name="">
						<!-- <div class="input-group  calendar">
							<input type="text" name="from_date" class="form-control datepicker" required="required" value="Jan 02,2019">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						 </div> -->
					</div>
	            </div>
	            <div class="col-sm-3 form_box">
	                <label for="" class="ng-binding">Date To</label>
	                <div class="form-group">
											<input type="date" name="">
						<!-- <div class="input-group  calendar">
							<input type="text" name="from_date" class="form-control datepicker" required="required" value="Jan 02,2019">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						 </div> -->
					</div>
	            </div>
	            <div class="col-sm-3 form_box">
	                <label for="" class="ng-binding">Contact Group</label>
	                <select ng-model="model.group" id="" ng-options="option.id as option.name for option in contactGroups" class="ng-pristine ng-untouched ng-valid ng-empty" aria-invalid="false"><option value="" class="" selected="selected">All Groups</option><option label="Wholesale Customer" value="string:1">Wholesale Customer</option><option label="Regular Customer" value="string:2">Regular Customer</option><option label="cash customer" value="string:7">cash customer</option></select>
	            </div>
	            <div class="col-sm-3 send_btn">
	        		<button class="refresh_btn ng-binding" ng-click="resetForm()" type="button">Refresh</button>
	        		<button class="send_button ng-binding" ng-click="filterList($event)" type="button">Search</button>
	        	</div>
	            
	        </div>

			<table class="table_responsive">
			  <tbody><tr>
			    <th>#</th>
			    <th class="ng-binding">Mobile No</th>
			    <th class="ng-binding">Receipient Name</th>
			    <th class="ng-binding">Group </th>
			  </tr>
			  <tr>
			    <td>1</td>
			    <td>023588</td>
			    <td>Asad khalid</td>
			    <td>Customers</td>
			  </tr>
			</tbody></table>		

			<!-- <div class="row">
	        	<div class="col-sm-3 form_box">
	                
	            </div>
	            <div class="col-sm-3 form_box">
	                
	            </div>
	        	<div class="col-sm-6 send_btn">
	        		<button class="refresh_btn" type="button">Cancel All SMS</button>
	        		<button class="send_button" type="button">Send All SMS</button>
	        	</div>
	        </div> -->
	        
	    </div>