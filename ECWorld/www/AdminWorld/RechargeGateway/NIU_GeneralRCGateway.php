<div class="row">
        <div class="col-xs-12">
          <div class="box" style="border-top: none; ">
			<div class="box-header">
				<div class="col-md-6">
				<select class="form-control select2" name="selectGatewayID" id="selectGatewayID" style="width: 100%;">
		 
				</select>
				</div>
				<div class="col-md-6">
				<div align="left" style="padding:2px">
					<a href="#" id="idBtnAddGatewayPopup" class="btn btn-success" data-toggle="modal" data-target="#myModal" >Add</a> &nbsp;&nbsp;
				</div>
				</div>
            </div><hr>
			<div class="box-body">
              <table id="idTblGeneralGateway" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><?php echo $lang['network_label']; ?></th>
                  <th><?php echo $lang['code_label']; ?></th>
                  <th><?php echo $lang['action_label']; ?></th>
				  <th></th>
                </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>