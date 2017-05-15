<?php
	include '../../Header/Header.php';
?>
<div class="content-wrapper">
    <section class="content-header">
      <h1> User Balance Alert</h1>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
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
        				  <th>Status</th>
                  <th></th>
                  <th>BalanceLevel</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td colspan="10">Loading users...</td>
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
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/UserBalance/userBalance.js"></script>