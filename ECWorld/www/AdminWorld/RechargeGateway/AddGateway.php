<div class="modal fade" id="idPopupAddEditGateway" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-info">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><?php echo $lang['add_gateway_title']; ?></h4>
			</div>
			<div class="modal-body" style="background-color: #ffffff !important;">
			<form role="form" id="idFrmAddGateway" method="post" >
				<input type="hidden" id="idGatewayID" name="RCGatewayID" value="0" />
				<input type="hidden" name="Action" value="UpsertGatewayAPI" />
              <div class="box-body">
                <div class="form-group">
                  <label style="color:#000"><?php echo $lang['gateway_name_label']; ?></label><span class="UrlgatewayNameErr error"></span>
                  <input type="text" class="form-control" id="idAddGatewayTxtName"  name="Name" placeholder="Enter Gateway Name" required>
                </div>
				
                <div class="form-group">
                  <label style="color:#000"><?php echo $lang['gateway_url_label']; ?></label><span class="gatewayErr error"></span>
                  <textarea  class="form-control" id="idAddgatewayTxtURL" name="URL" rows="4" placeholder="Give Valid Gateway URL..." required ></textarea>
                </div>
				
              </div>

              <div class="box-footer">
                <button id="idBtnAddGateway" type="submit" class="btn btn-success"><?php echo $lang['submit_button']; ?></button>
              </div>
				<div id="errorMsg"></div>
            </form>
			</div>
		</div>
	</div>
</div>