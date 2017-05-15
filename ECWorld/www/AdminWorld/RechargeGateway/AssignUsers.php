<div class="modal fade" id="idPopupAssignUsers" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-info">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><?php echo $lang['title_assign_users']; ?></h4>
			</div>
			<div class="modal-body" style="background-color: #ffffff !important;">
			<form role="form" id="idFrmAssignUsers" method="post" >
				<!--<input type="hidden" id="idRCGenralGatewayAssignID" name="RCGenralGatewayAssignID" value="0" />-->
				<input type="hidden" name="Action" value="UpsertGeneralAssignUsers" />
              <div class="box-body">
                <div class="form-group">
                  <label style="color:#000"><?php echo $lang['lbl_select_users']; ?></label>
                </div>
				
				<div class="row" id="idSelectUsersContent">
					<div class="col-md-2">
						<div class="input-group">
							<span class="input-group-addon"><input type="checkbox"></span>
							<input type="text" class="form-control" value="Airtel" readonly>
						</div>
					</div>
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