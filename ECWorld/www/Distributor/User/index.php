<?php
	include '../../Header/Header.php';
?>
<div class="content-wrapper">
    <section class="content-header">
      <h1> Business User Details</h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">			
				<div class="col-md-4">
					<select class="form-control select2" name="selectUserID" id="idSelectUser" style="width: 100%;">
					</select>
				</div>
				<div class="col-md-4">
					<select class="form-control select2" name="selectUserID" id="idSelectRole" style="width: 100%;">
					</select>
				</div>	
                <div align="left" style="padding:2px">
					<a href="AddUser.php" class="btn btn-success">Add</a> &nbsp;&nbsp;
				</div>
            </div>
            <div class="box-body">
              <table id="idTblUsers" class="table table-bordered table-striped" style="width:100%">
                <thead>
                <tr>
                  <th>SNo</th>
                  <th>UserID</th>
                  <th>Name</th>
                  <th>RoleName</th>
                  <th>Mobile</th>
                  <th>Wallet</th>
                  <th>ParentID</th>
                  <th>Client Limit</th>
        				  <th>Status</th>
                  <th></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td colspan="10">Select user and role to load the user list...</td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
<?php
	include '../../Header/Footer.php';
?>
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/User/user_DT_user.js"></script>
<script type="text/javascript" language="javascript" class="init">
  $(".select2").select2();
</script>