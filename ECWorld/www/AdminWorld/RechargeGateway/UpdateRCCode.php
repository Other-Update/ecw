<div class="modal fade" id="updateRC_Code" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-info">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body" style="background-color: #ffffff !important;">
			<form role="form" id="idFrmUpdateRCCode" method="post" >
				<input type="hidden" name="Action" value="UpsertAPIRechargeCode" />
				<input type="hidden" id="idRCGatewayDetailsID" name="RCGatewayDetailsID" value="" />
				<input type="hidden" id="idRcGatewayID" name="RcGatewayID" value="" />
				<input type="hidden" id="idServiceID" name="ServiceID" value="" />
              <div class="box-body">
                <div class="form-group">
                  <label style="color:#000"><?php echo $lang['service_name_label']; ?></label><span class="UrlgatewayNameErr error"></span>
                  <input type="text" class="form-control" id="Name"  name="Name" readonly >
                </div>
				
                <div class="form-group">
                  <label style="color:#000"><?php echo $lang['rc_rechargecode_label']; ?></label><span class="gatewayErr error"></span>
                   <input type="text" class="form-control" id="RechargeCode"  name="RechargeCode" placeholder="Enter Recharge code" required>
                </div>
				<div class="form-group">
                  <label style="color:#000"><?php echo $lang['rc_topupcode_label']; ?></label><span class="gatewayErr error"></span>
                   <input type="text" class="form-control" id="TopupCode"  name="TopupCode" placeholder="Enter Topup code" required>
                </div>
				
              </div>

              <div class="box-footer">
                <button id="idBtnUpdateRCCode" type="submit" class="btn btn-success"><?php echo $lang['submit_button']; ?></button>
              </div>
				<div id="errorMsg1"></div>
            </form>
			</div>
		</div>
	</div>
</div>