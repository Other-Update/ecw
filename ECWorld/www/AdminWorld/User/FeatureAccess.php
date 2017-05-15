<?php
	include '../../Header/Header.php';
?>
<div class="content-wrapper">
    <section class="content-header">
      <h1> Feature Access </h1>
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
            </div>
            <div class="box-body">
				<div  id="idFeatureContainer">
					<!--<div class="box-header" style="background: #3c8dbc; color:#ffffff;">
					  <h3 class="box-title">Feature Access</h3>
					  <div class="box-tools">
						<i class="fa fa-refresh"></i>
					  </div>
					</div>-->
					<div class="box-body table-responsive no-padding">
					  <form id="idFormUpdateFat">
						<input type="hidden" name="Action" value="UpdateFat" />
						<input type="hidden" name="UserID" id="idTxtUserID" value="0" />
					  <table class="table table-hover" id="idFeaturesListContainer">
						<tr>
						  <th>#</th>
						  <th>Feature</th>
						  <th>Read</th>
						  <th>Add/Update</th>
						</tr>
						<!-- <tr>
						  <td>
							<div class="clsLblFeatureSNo">1</div>
						  </td>
						  <td>
							<div class="clsLblFeatureName">User</div>
						  </td>
						  <td>
							<div class="clsChkFeatureRead"></div>
						  </td>
						  <td>
							<div class="clsChkFeatureAddupdate"></div>
						  </td>
						</tr> -->
					  </table>
					  </form>
					</div>
				</div>
			<div>
          </div>
			<div align="left" style="padding:2px">
				<a href="#" class="btn btn-success" id="idUpdateFat">Update</a> &nbsp;&nbsp;
			</div>
        </div>
      </div>
    </section>
  </div>
<?php
	include '../../Header/Footer.php';
?>
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/User/featureAccess.js"></script>
<script type="text/javascript" language="javascript" class="init">
$(".select2").select2();
</script>