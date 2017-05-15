/* 
	
*/
jQuery(function($) {
	var validation_holder;
	$("#idBtnAddService").click(function() {
	$(this).attr('disabled', true);
	var validation_holder = 0;
	var Name   			= $("#Name").val();
	var DefaultType 	= $("#DefaultType").val();
	var rechargeCode 	= $("#rechargeCode").val(); 
	var topupCode 		= $("#topupCode").val();
	var NetworkProvider = $("#NetworkProvider").val(); 
	var NetworkMode 	= $("#NetworkMode").val();
		
		if($.trim(Name) == "") {
			$("span.serviceNameErr").html(" is required.").addClass('validate');
			validation_holder = 1;
		} else { $("span.serviceNameErr").html(""); }
		
		if($.trim(DefaultType) == "" || $.trim(DefaultType)==0) {
			$("span.typeErr").html(" is required.").addClass('validate');
			validation_holder = 1;
		} else { $("span.typeErr").html(""); }
		
		/* if($.trim(NetworkProvider) == "") {
			$("span.networkErr").html(" is required.").addClass('validate');
			validation_holder = 1;
		} else { $("span.networkErr").html(""); }  */
		
		if($.trim(NetworkMode) == "") {
			$("span.modeErr").html(" is required.").addClass('validate');
			validation_holder = 1;
		} else { $("span.modeErr").html(""); }
		
		//Both code can be empty but not same if not empty
		if($.trim(rechargeCode)!="" && $.trim(topupCode)!="")
		if($.trim(rechargeCode) == $.trim(topupCode)){
			$("span.serviceCodeErr").html(" Both Code should not be same .").addClass('validate');
			validation_holder = 1;} else { $("span.serviceCodeErr").html("");
		}
		if(validation_holder == 1) { 
			$('#idBtnAddService').attr('disabled', false);
			$("div.validate_msg").slideDown("fast");
			return false;
		}  else { 
			validation_holder = 0; 
			$("div.validate_msg").slideUp("fast"); 
			upsertService();
			return false;
		}
	}); 

}); 




