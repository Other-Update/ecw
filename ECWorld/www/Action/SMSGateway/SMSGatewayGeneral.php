<div class="row">
        <div class="col-xs-12">
          <div class="box" style="border-top: none; ">
			<div class="box-header">
				<div class="col-md-6">
				<input type="hidden" id="idSelectAdminID" value="1"/>
				</div>
				<div class="col-md-6">
					<div align="right" style="padding:2px">
						<a href="#" class="btn btn-success" data-toggle="modal"  data-target="#idPopupAssignUsers" id="idBtnAssignUsers" ><?php echo $lang['assign_users']; ?></a> 
						<a href="#" class="btn btn-success" id="myModalApiGeneral" data-toggle="modal"  data-target="#myModalApi" data-type="General" ><?php echo $lang['editApi_label']; ?></a> 
					</div>
				</div>
				
            </div><hr>
			<div class="box-body">
              <table id="idTblGeneralGateway" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><?php echo $lang['network_label']; ?></th>
                  <th><?php echo $lang['primary_label']; ?></th>
                  <th><?php echo $lang['secondary_label']; ?></th>
				  <th><?php echo $lang['amount_label']; ?></th>
                  <th><?php echo $lang['check_label']; ?></th>
                  <th><?php echo $lang['editAmt_label']; ?></th>
				  <th><?php echo $lang['amount_label']; ?></th>
                  <th><?php echo $lang['check_label']; ?></th>
                  <th><?php echo $lang['editAmt_label']; ?></th>
                </tr>
                </thead>
				<tbody>
				<!--<?php //for($i=1; $i <= 5; $i++ ){ ?>
					<tr>
					  <td><?php //echo $i; ?></td>
					  <td>Airtel</td>
					  <td>API 1</td>
					  <td>API 2</td>
					  <td>20, 30</td>
					  <td><input type="checkbox" class="flat-red"></td>
					  <td>Edit</td>
					</tr>
				<?php //} ?>-->
				</tbody>
              </table>
            </div>
          </div>
        </div>
      </div>