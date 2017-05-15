/* 
	
*/
jQuery(function($) {
	var validation_holder;
	$("#idBtnUserForm").click(function() {
	var validation_holder = 0;
		
		var parentId   			= $("#parentId").val();
		var userType 			= $("#userType").val();
		var username 			= $("#username").val();
		var password 			= $("#password").val();
		var email 				= $("#emailId").val();
		var email_regex 		= /^[\w%_\-.\d]+@[\w.\-]+.[A-Za-z]{2,6}$/; 
		var phone 				= $("#mobileNo").val();
		var phone_regex			= /^[789][0-9]{9}$/; 
		var dob   				= $("#dob").val();
		var clientLimit 		= $("#clientLimit").val();
		var distributorFee   	= $("#distributorFee").val();
		var subDistributorFee 	= $("#subDistributorFee").val();
		var depositeAmt		   	= $("#depositeAmt").val();
		var retailerFees   		= $("#retailerFees").val();
		var balanceLevel   		= $("#balanceLevel").val();
		var phoneExist = $("#mobileNo").data('isExists');
		
		if($.trim(username) == "") {
			$("span.usernameErr").html(" is required.").addClass('validate');
			validation_holder = 1;
		} else { $("span.usernameErr").html(""); }
		
		if($.trim(password) == "") {
			$("span.passwordErr").html(" is required.").addClass('validate');
			validation_holder = 1;
		} else { $("span.passwordErr").html(""); }

		if($.trim(parentId) == "") {
			$("span.ParentIDErr").html(" is required.").addClass('validate');
			validation_holder = 1;
		} else { $("span.ParentIDErr").html(""); }
		
		if($.trim(userType) == "") {
			$("span.userTypeErr").html(" is required.").addClass('validate');
			validation_holder = 1;
		} else { $("span.userTypeErr").html(""); }
		
		if($.trim(email) == "") { $("span.emailIdErr").html("");  } 
		else if($.trim(email) != "") {
			if(!email_regex.test(email)){ 
				$("span.emailIdErr").html(" Invalid Email!").addClass('validate');
				 validation_holder = 1;
			} else { $("span.emailIdErr").html(""); }
		} 
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
		if($.trim(userType) != 2){
			if($.trim(balanceLevel) == "") {
				$("span.balanceLevelErr").html(" is required.").addClass('validate');
				validation_holder = 1;
			} else { $("span.balanceLevelErr").html(""); }
			
			if($.trim(depositeAmt) == "") {
				$("span.depositeAmtErr").html(" is required.").addClass('validate');
				validation_holder = 1;
			} else { $("span.depositeAmtErr").html(""); }
			
			if($.trim(retailerFees) == "") {
				$("span.retailerFeeErr").html(" is required.").addClass('validate');
				validation_holder = 1;
			} else { $("span.retailerFeeErr").html(""); }
			
			if($.trim(subDistributorFee) == "") {
				$("span.subDistFeeErr").html(" is required.").addClass('validate');
				validation_holder = 1;
			} else { $("span.subDistFeeErr").html(""); }
			
			if($.trim(distributorFee) == "") {
				$("span.distFeeErr").html(" is required.").addClass('validate');
				validation_holder = 1;
			} else { $("span.distFeeErr").html(""); }
			
			if($.trim(clientLimit) == "") {
				$("span.clientLimitErr").html(" is required.").addClass('validate');
				validation_holder = 1;
			} else { $("span.clientLimitErr").html(""); }
		}

		if(validation_holder == 1) {
			$('#idBtnUserForm').attr('disabled', false);
			$("div.validate_msg").slideDown("fast");
			return false;
		}  else { 
			$('#idBtnUserForm').attr('disabled', true);
			validation_holder = 0; 
			$("div.validate_msg").slideUp("fast"); 
			AddUser(); return false;
		}
	}); 

}); 

$(function() {
	var chars = "23456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghikmnopqrstuvwxyz";
	var string_length = 8;
	var randomstring = '';
	for (var i=0; i<string_length; i++) {
		var rnum = Math.floor(Math.random() * chars.length);
		randomstring += chars.substring(rnum,rnum+1);
	}
	$('#password').val(randomstring);
});



$(document).ready(function () {
  $("#mobileNo").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
        $(".mobileNoErr").html("Digits Only").show().fadeOut("slow");
               return false;
    }
   });
});


//ID Proof == Check file type PDF or not 
$(document).ready(function () {
	$('#idProof').change(function () {
	var val = $(this).val().toLowerCase();
	var regex = new RegExp("(.*?)\.(pdf)$");
		if(!(regex.test(val))) {
			$(this).val('');
			alert('Please select correct file format');
		} 
	}); 
});
$(function () {
    $(".select2").select2();
    $('#dob').datepicker({
	  format: 'yyyy-m-d',
      autoclose: true
    });
	$('.Business_user').hide();
	$('#userType').change(function(){
		var roleId = $('#userType').val();
		if(roleId == 2){
			$('.Business_user').hide();
			$('#clientLimit').val('0');
		} else if(roleId == 6){
			$('.Business_user').show();
			$('.clientLimitField').hide();
			$('#clientLimit').val('0');
		} else {
			$('.Business_user').show();
			$('.clientLimitField').show();
			$('#clientLimit').val('25');
		}
	});

});

$(function(){
	$('#userType').on('change', function() {
	var userType = $(this).val();
		if(userType == 1){
			$("#refundable").val(1).change();
			$('#distFee').show();
			$('#subDistFee').show();
			$('#retailerFee').show();
		} else if(userType == 2){
			$("#refundable").val(2).change();
			$('#distFee').hide();
			$('#subDistFee').hide();
			$('#retailerFee').hide();
		} else if(userType == 3){
			$("#refundable").val(1).change();
			$('#distFee').show();
			$('#subDistFee').show();
			$('#retailerFee').show();
		} else if(userType == 4){
			$("#refundable").val(1).change();
			$('#distFee').hide();
			$('#subDistFee').show();
			$('#retailerFee').show();
		} else if(userType == 5){
			$("#refundable").val(2).change();
			$('#distFee').hide();
			$('#subDistFee').hide();
			$('#retailerFee').show();
		} else if(userType == 6){
			$("#refundable").val(2).change();
			$('#distFee').hide();
			$('#subDistFee').hide();
			$('#retailerFee').hide();
		}
	});
	
});

$('#userType').change(function(){
	var parentIdVal = $('#parentId').val();
	var userType = $('#userType').val();
	if(parentIdVal == 1 && userType == 2){
		$('.depositAmtField').hide();
		$('#depositeAmt').val('0');
	} else if(parentIdVal == 1 && userType != 2){
		$('.depositAmtField').show();
		//$('#depositeAmt').val('');
	} else {
		$('.depositAmtField').hide();
		$('#depositeAmt').val('0');
	}
});
