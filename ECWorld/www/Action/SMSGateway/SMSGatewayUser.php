<div class="row">
        <div class="col-xs-12">
          <div class="box" style="border-top: none; ">
			<div class="box-header">
				<div class="col-md-6">
				<select class="form-control select2" name="selectUserID" id="idSelectUserID" style="width: 100%;">
		 
				</select>
				</div>
				<div class="col-md-6">
					<div align="right" style="padding:2px">
						<a href="#" class="btn btn-success" data-toggle="modal" id="myModalApiUser" data-target="#myModalApi" data-type="User" ><?php echo $lang['editApi_label']; ?></a> 
					</div>
				</div>
				
            </div><hr>
			<div class="box-body">
              <table id="idTblUserGateway" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <!--<th><?php //echo $lang['serial_no']; ?></th>-->
                  <th><?php echo $lang['network_label']; ?></th>
                  <th><?php echo $lang['primary_label']; ?></th>
                  <th><?php echo $lang['primary_label']; ?></th>
                  <th><?php echo $lang['primary_label']; ?></th>
                  <th><?php echo $lang['secondary_label']; ?></th>
				  <th><?php echo $lang['amount_label']; ?></th>
                  <th><?php echo $lang['check_label']; ?></th>
                  <th><?php echo $lang['editAmt_label']; ?></th>
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