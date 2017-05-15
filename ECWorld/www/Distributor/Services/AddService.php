<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-info">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><?php echo $lang['add_service_title']; ?></h4>
			</div>
			<div class="modal-body" style="background-color: #ffffff !important;">
			<form role="form" id="idFrmAddService" method="post">
				<input type="hidden" id="idServiceID" name="ServiceID" value="0" />
				<input type="hidden" name="Action" value="Upsert" />
              <div class="box-body">
                <div class="form-group">
                  <label style="color:#000"><?php echo $lang['service_name_label']; ?></label><span class="serviceNameErr error"></span>
                  <input type="text"  class="form-control" id="Name"  name="Name" placeholder="Enter Network Name">
                </div>
				<div class="form-group">
					<label style="color:#000"><?php echo $lang['service_type_label']; ?></label><span class="typeErr error"></span>
					<select class="form-control select2" name="DefaultType" id="DefaultType" style="width: 100%;">
						<option value="1">Recharge</option>
						<option value="2">TopUp</option>
						<option value="3">PostPaid</option>
					</select>
				</div>
				<div class="form-group">
					<label style="color:#000">Network Provider</label><span class="networkErr error"></span>
					<select class="form-control select2" name="NetworkProviderID" id="NetworkProvider" style="width: 100%;">
					</select>
				</div>
				<div class="form-group">
					<label style="color:#000">Network Mode</label><span class="modeErr error"></span>
					<select class="form-control select2" name="NetworkMode" id="NetworkMode" style="width: 100%;">
					</select>
				</div>
                <div class="form-group">
                  <label style="color:#000"><?php echo $lang['recharge_code_label']; ?></label><span class="serviceCodeErr error"></span>
                  <input type="text"  class="form-control" id="rechargeCode" name="RechargeCode" placeholder="Recharge Code" onpaste="return false;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                </div>
				<div class="form-group">
				  <label style="color:#000"><?php echo $lang['topup_code_label']; ?></label>
                  <input type="text"  class="form-control" id="topupCode" name="TopupCode" placeholder="TopUp Code" onpaste="return false;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                </div>
				
              </div>

              <div class="box-footer">
                <button id="idBtnAddService" type="submit" class="btn btn-success"><?php echo $lang['submit_button']; ?></button>
              </div>
				<div id="idSpnSuccessErr"></div>
            </form>
			<div class="box-footer validate_msg" style="color:red; text-align:center;display:none ">
				Please fill all the required fields!
			</div>
			<div class="NameExistErr errorMsg">Service Name Already Exist</div>
			<div class="RCodeExistErr errorMsg">Recharge Code Already Exist</div>
			<div class="TCodeExistErr errorMsg">TopUp Code Already Exist</div>
			</div>
		</div>
	</div>
</div>