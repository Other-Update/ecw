<div class="modal fade" id="myModalApi" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-info">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><?php echo $lang['update_api_title']; ?></h4>
			</div>
			<div class="modal-body" style="background-color: #ffffff !important;">
			<form role="form" id="idFrmUpdateGeneralApi" method="post" >
				<input type="hidden" name="ServiceID" id="ServiceID" class="form-control" />
				<input type="hidden" name="RCUserGatewayID" id="RCUserGatewayID" class="form-control">
				<input type="hidden" name="Action" value="UpsertGeneralApi" />
              <div class="box-body">
                <div class="form-group">
                  <label style="color:#000"><?php echo $lang['user_id_label']; ?></label>
                  <input type="text" class="form-control" id="UserID"  name="UserID" readonly>
                </div>
				
                <div class="form-group">
                  <label style="color:#000"><?php echo $lang['network_label']; ?></label>
                  <textarea  class="form-control" id="NetworkName" name="NetworkName" rows="4"></textarea>
                </div>
				<div class="form-group">
                  <label style="color:#000"><?php echo $lang['primary_label']; ?></label>
                 <select class="form-control select2 selectGatewayID" name="PrimaryGateway" id="PrimaryGateway" style="width: 100%;">
				</select>
                </div>
				<div class="form-group">
                  <label style="color:#000"><?php echo $lang['secondary_label']; ?></label>
                  <select class="form-control select2 selectGatewayID" name="SecondaryGateway" id="SecondaryGateway" style="width: 100%;">
				</select>
                </div>
              </div>

              <div class="box-footer">
                <button id="idBtnUpdateGeneralApi" type="submit" class="btn btn-success"><?php echo $lang['submit_button']; ?></button>
              </div>
            </form>
			<div id="errorMsgApi"></div>
			</div>
		</div>
	</div>
</div>