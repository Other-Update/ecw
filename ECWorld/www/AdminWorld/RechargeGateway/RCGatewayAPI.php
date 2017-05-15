<div class="row">
        <div class="col-xs-12">
          <div class="box" style="border-top: none; ">
			<div class="box-header">
				<div class="col-md-6">
					<select class="form-control select2 selectGatewayID" name="selectGatewayID" id="selectGatewayID" style="width: 100%;">
			 
					</select>
				</div>
				<div class="col-md-6">
					<div class="col-sm-2">
						<div align="left" style="padding:2px">
							<a href="#" id="idBtnAddGatewayPopup" class="btn btn-success" data-toggle="modal" data-target="#idPopupAddEditGateway" >Add</a> &nbsp;&nbsp;
						</div>
					</div>
					<div class="col-sm-2">
						<div align="left" style="padding:2px">
							<a href="#" id="idBtnEditGatewayPopup" class="btn btn-success" data-toggle="modal" data-target="#idPopupAddEditGateway" >Edit</a> &nbsp;&nbsp;
						</div>
					</div>
					<div class="col-sm-2">
						<div align="left" style="padding:2px">
							<a href="#" id="idBtnDeleteGatewayPopup" class="btn btn-danger"  >Delete</a> &nbsp;&nbsp;
						</div>
					</div>
				</div>
				<div class="col-xs-12">
					<div align="left" style="padding:2px">
						<div id="idDivSelectedGatewayURL"></div>
					</div>
				</div>
            </div><hr>
			<div class="box-body">
              <table id="idTblGatewayAPI" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><?php echo $lang['network_label']; ?></th>
                  <th><?php echo $lang['rechargecode_label']; ?></th>
				  <th><?php echo $lang['topupcode_label']; ?></th>
                  <th><?php echo $lang['action_label']; ?></th>
				  <th></th>
                </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>