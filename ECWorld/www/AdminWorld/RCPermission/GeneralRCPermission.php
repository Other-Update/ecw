
			  <div class="row" id="form-permission">
				<form method="post" action="" id="idGeneralServicePermissionForm" >
				<input type="hidden" name="Action" value="UpdateGeneralServicePermission" />
				<input type="hidden" name="UserID" id="idUserID" value="0" />
				<input type="hidden" name="ServicePermissionID" id="idServicePermissionID" value="0" />
				<div class="col-md-4 text-center">
					<div class="form-group header_label">
					<label>Network Permission</label>
					</div>
				</div>
				<div class="col-md-4 text-center">
					<div class="form-group header_label">
					<label>Minimum Charge(<i class="fa fa-rupee"></i>)</label>
					</div>
				</div>
				<div class="col-md-4 text-center">
					<div class="form-group header_label">
					<label>Network Commission</label>
					</div>
				</div>
				<div id="idServiceContainer">
					<div class="col-md-4">
						<div class="input-group">
							<span class="input-group-addon"><input type="checkbox"></span>
							<input type="text" class="form-control" value="Airtel" readonly>
						</div>
					</div>
					<div class="col-md-4">
					    <div class="form-group">
							<input type="number" name="Name" id="username" class="form-control" >
					    </div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<input type="number" name="Name" id="username" class="form-control" >
						</div>
					</div>
				</div>
				<input type="hidden" value="" name="CheckSequence" id="idCheckSequence"/>
				</div>
				<div class="row" style="margin-top:20px;">
					<div class="col-md-4 text-center">
						<div class="form-group header_label">
							<label>OTP Enable</label>
						</div>
						<div class="input-group" >
							<span class="input-group-addon"><input id="idIsOTFCommission" name="IsOTFCommission" type="checkbox"></span>
							<input type="text" class="form-control" value="OTP enable for network commission " readonly>
						</div>
						<div class="input-group otp-enable">
							<span class="input-group-addon"><input id="idIsOTFMinCharge" name="IsOTFMinCharge" type="checkbox"></span>
							<input type="text" class="form-control" value="OTP enable minimum charge " style="width:75%;" readonly><input type="number" id="idOTFMinCharge" name="OTFMinCharge" class="form-control"  style="width:20%; margin-left:5%;">
						</div>
						<div class="input-group otp-enable">
							<span class="input-group-addon"><input id="idIsFirstSMSCost" name="IsFirstSMSCost" type="checkbox"></span>
							<input type="text" class="form-control" value="First SMS cost" style="width:75%;" readonly>
							<input id="idFirstSMSCost" name="FirstSMSCost" type="number" class="form-control"  style="width:20%; margin-left:5%;">
						</div>
					</div>
				<div>
				<div class="row">
					<div class="col-md-4 text-center"><div class="form-group">&nbsp;</div></div>
					<div class="col-md-4 text-center"><div class="form-group">&nbsp;</div></div>
					<div class="col-md-4 text-center" style="margin-top:10px;">
						<div class="form-group">
							<input type="submit" value="Submit" class="btn btn-success clsBtnServicePermission" id="idBtnServicePermission"/> 
						</div>
					</div>
				</div>
				</form>
			  </div>
			</div>
		  