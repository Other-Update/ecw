<?php
	include_once '../../../Resource/Service.php';
	include '../../Header/Header.php';
?>
<style>
.errorMsg { display:none; color:red;}
</style>
<div class="content-wrapper">
   <section class="content-header" style="padding-top: 0px;">
      <div class="service_header" style="width:80%; float:left;"><h3><?php echo $lang['service_view_title']; ?></h3></div>
	  <div class="service_add pull-right" style="width:15%; padding: 10px;"><a href="#" id="idBtnAddServicePopup" class="btn btn-success" data-toggle="modal" data-target="#myModal" >Add</a></div>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">	
          <div class="box">
			<div class="box-body">
              <table id="idTblServiceList" class="table table-bordered table-striped" style="width:100%;">
                <thead>
                <tr>
				  <th></th>
				  <th></th>
                  <th><?php echo $lang['service_name_label']; ?></th>
                  <th><?php echo $lang['rc_code_label']; ?></th>
                  <th><?php echo $lang['tp_code_label']; ?></th>
                  <th><?php echo $lang['service_type_name_label']; ?></th>
				  <th></th>
                </tr>
                </thead>
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
<?php include 'AddService.php'; ?>	
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/Service/service_ilaiya.js"></script>
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/Service/service.js"></script>

<script>
  $(function () {
    $(".select2").select2();
  });

$(function () {
   $('#example1').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  }); 
</script>