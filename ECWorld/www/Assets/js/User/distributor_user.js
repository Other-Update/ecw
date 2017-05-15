

function AddDistUser(){
	ajaxRequest({
		type: 'post',
		url: "../../Action/User/distributorUserAction.php",
		data: $('form#idDistUserForm').serialize(),
		success: function(data){				
			var jsonData = JSON.parse(data);
			$('#errorMsg').fadeIn(100,function(){});
			$('#errorMsg').fadeOut(5000,function(){});
			if(jsonData.isSuccess && $("#UserID").val()==0){
				$('.form-control').val('');
				$("#errorMsg").html("<div class='alert alert-success alert-dismissible'><p>"+jsonData.message+"</p></div>").show().fadeOut(5000,function(){
					window.location.href="../User";
				});
			} else if(!jsonData.isSuccess) { 
				$("#errorMsg").html("<div class='alert alert-danger alert-dismissible'><p>"+jsonData.message+"</p></div>").show();
				$('#idBtnDistUserForm').attr('disabled', false);
			} else {
				$("#errorMsg").html("<div class='alert alert-success alert-dismissible'><p>"+jsonData.message+"</p></div>").show().fadeOut(5000,function(){
					window.location.href="../User";
				});
			}
			//getParents();
		},
		error: function(error){
			alert('Error:Unable to add user');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
}



jQuery(function($) {
	var validation_holder;
	$("#idBtnDistUserForm").click(function() {
	var validation_holder = 0;
		var parentId   			= $("#parentId").val();
		var userType 			= $("#userType").val();
		var username 			= $("#username").val();
		var phone 				= $("#mobileNo").val();
		var phone_regex			= /^[789][0-9]{9}$/; 
		var phoneExist 			= $("#mobileNo").data('isExists');
		
		if($.trim(username) == "") {
			$("span.usernameErr").html(" is required.").addClass('validate');
			validation_holder = 1;
		} else { $("span.usernameErr").html(""); }
		

		if($.trim(parentId) == "") {
			$("span.ParentIDErr").html(" is required.").addClass('validate');
			validation_holder = 1;
		} else { $("span.ParentIDErr").html(""); }
		
		if($.trim(userType) == "") {
			$("span.userTypeErr").html(" is required.").addClass('validate');
			validation_holder = 1;
		} else { $("span.userTypeErr").html(""); }
		
		
		if($.trim(phone) == "") {
			$("span.mobileNoErr").html(" is required.").addClass('validate');
			validation_holder = 1;
		} else {
			if(!phone_regex.test(phone)){ 
				$("span.mobileNoErr").html(" Invalid Number!").addClass('validate');
				validation_holder = 1;
			} else { if(phoneExist != 0){
				$("span.mobileNoErr").html(" already exist.").addClass('validate');
				validation_holder = 1;
			} else { $("span.mobileNoErr").html(""); } }
		} 
		
		if(validation_holder == 1) {
			$('#idBtnDistUserForm').attr('disabled', false);
			$("div.validate_msg").slideDown("fast");
			return false;
		}  else { 
			$('#idBtnDistUserForm').attr('disabled', true);
			validation_holder = 0; 
			$("div.validate_msg").slideUp("fast"); 
			AddDistUser(); return false;
		}
	}); 

}); 

$(function () {
    $('#dob').datepicker({
	  format: 'yyyy-mm-dd',
      autoclose: true
    });
});