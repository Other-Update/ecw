<div class="modal fade" id="idPopUpdateUserRCAmount" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-info">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body" style="background-color: #ffffff !important;">
			<form role="form" id="idFrmUpdateUserRCAmount" method="post" >
				<input type="hidden" id="idRCUserGatewayID" name="RCUserGatewayID" value="" />
				<input type="hidden" name="Action" value="UpdateUserGatewayAmount" />
                <input type="hidden" id="idUserID"  name="UserID" />
                <input type="hidden" id="idServiceID"  name="ServiceID" />
              <div class="box-body">
                <div class="form-group">
                  <label style="color:#000"><?php echo $lang['service_name_label']; ?></label><span class="UrlgatewayNameErr error"></span>
                  <input type="text" class="form-control" id="idServiceName"  name="Name" readonly >
                </div>
				
                <div class="form-group">
                  <label style="color:#000"><?php echo $lang['rc_code_label']; ?></label><span class="gatewayErr error"></span>
                   <input type="text" class="form-control" id="idAmount"  name="Amount" placeholder="Enter Recharge Amount" required>
                </div>
				
              </div>

              <div class="box-footer">
                <button id="idBtnUpdateRCUserAmount" type="submit" class="btn btn-success"><?php echo $lang['submit_button']; ?></button>
              </div>
				<div id="idUpdateAmountResult"></div>
            </form>
			</div>
		</div>
	</div>
</div>