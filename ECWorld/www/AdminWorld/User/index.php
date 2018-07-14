<?php
	include '../../Header/Header.php';
    include '../Connect/config.php';
  $query = "SELECT UserID,DisplayID,Name,Mobile FROM m_users WHERE Active=1 ORDER BY UserID ASC " ;
  $res = $connect->query($query);

  $query1 = " SELECT * FROM `m_role` ";
  $res1 = $connect->query($query1);
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
					     <?php while($row = $res->fetch()){  ?>
            <option value="<?php echo $row['UserID']; ?>" > <?php echo $row['DisplayID'].'-'.$row['Name'].'-'.$row['Mobile']; ?></option>
            <?php } ?>
					</select>
				</div>
				<div class="col-md-4">
					<select class="form-control select2" name="selectUserID" id="idSelectRole" style="width: 100%;">
					 <?php while($row1 = $res1->fetch()){  ?>
					<option selected="selected" value="<?php echo $row1['RoleID']; ?>"> Select User </option>
					 <option  value="<?php echo $row1['RoleID']; ?>" > <?php echo $row1['Name']; ?></option>
            <?php } ?>
            <option value="0">All</option>
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
                  <th>Refundable</th>
                  <th>ParentID</th>
                  <th>ClientLimit</th>
                  <th>DOB</th>
				  <th>Wallet</th>
				  <th>Status</th>
				  <th>Action</th>
				  <th>UniqueUserID</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td colspan="10">Select user and role to load the user list...</td>
                </tr>
                </tbody>
                <!--<tfoot>
                <tr>
                  <th>Name</th>
                  <th>Mobile</th>
                  <th>RoleID</th>
                </tr>
                </tfoot>-->
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
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/User/user_DT.js"></script>
<script type="text/javascript" language="javascript" class="init">
$(".select2").select2();
	/* $(document).ready(function(){
		$('#example1').dataTable({
			"aProcessing": true,
			"aServerSide": true,
			"ajax": "datatable.php",
			"sEcho":10,
			"pageLength":5
		});
	}); */

	</script>