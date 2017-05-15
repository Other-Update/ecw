<?php
include '../../Header/Header.php';
?>
<style>
.form-group { margin-bottom: 5px; }
</style>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Edit User</h1>
    </section>
	<?php 
	include 'UserForm.php';?>
  </div>
<?php
	include '../../Header/Footer.php';
?>

<script>
function getUserEdit_NIU(callbackfn){
	var userID = $('#UserId').val();
	if(userID =='') 
		return false;
	ajaxRequest({
		type: 'post',
		url: 'UserAction.php',
		data: "Action=GetByID&UserID="+userID,
		success: function(data){		
			var jsonData = JSON.parse(data);
			callbackfn(jsonData);//editUserObj = jsonData;
		},
		error: function(error){
			alert('Error:Unable to get user data');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
}
$(function () {
    $(".select2").select2();

    //Date picker
    $('#dob').datepicker({
	  format: 'yyyy-m-d',
      autoclose: true
    });

	
	
	
});

</script>
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/User/user_ilaiya.js"></script>
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/User/user.js"></script>
<script src="<?php echo $WebsiteUrl; ?>/Assets/js/User/user_DT.js"></script>
