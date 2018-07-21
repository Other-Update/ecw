function getGeneralSettings(){
	ajaxRequest({
		type: 'post',
		url: '../../Action/GeneralSettings/GeneralSettingAction.php',
		data: "Action=GetGeneralSettings",
		success: function(data){
			var jsondata = JSON.parse(data);
			//Fees
			$("#DistributorFees").val(jsondata.DistributorFee);
			$("#SubDistributorFees").val(jsondata.SubDistributorFee);
			$("#RetailerFees").val(jsondata.RetailerFee);
			//UserBalanceAlert
			var checked1=(jsondata.UB_Distributor_AlertEnable==true)?"check":"uncheck";
			$('#UB_Distributor_AlertEnable').iCheck(checked1) ;
			$("#UB_Distributor_MinAmt").val(jsondata.UB_Distributor_MinAmt);
			$("#UB_Distributor_MaxAmt").val(jsondata.UB_Distributor_MaxAmt);
			
			var checked2=(jsondata.UB_SubDistributor_AlertEnable==true)?"check":"uncheck";
			$('#UB_SubDistributor_AlertEnable').iCheck(checked2) ;
			$("#UB_SubDistributor_MinAmt").val(jsondata.UB_SubDistributor_MinAmt);
			$("#UB_SubDistributor_MaxAmt").val(jsondata.UB_SubDistributor_MaxAmt);
			
			var checked3=(jsondata.UB_Retailer_AlertEnable==true)?"check":"uncheck";
			$('#UB_Retailer_AlertEnable').iCheck(checked3) ;
			$("#UB_Retailer_MinAmt").val(jsondata.UB_Retailer_MinAmt);
			$("#UB_Retailer_MaxAmt").val(jsondata.UB_Retailer_MaxAmt);
			//SMS Cost
			$("#SC_FirstSMS_Cost").val(jsondata.SC_FirstSMS_Cost);
			var checked4=(jsondata.SC_FirstSMS_Enable==true)?"check":"uncheck";
			$('#SC_FirstSMS_Enable').iCheck(checked4);
			$("#SC_FailedRecharge_Cnt").val(jsondata.SC_FailedRecharge_Cnt);
			$("#SC_FailedRecharge_Cost").val(jsondata.SC_FailedRecharge_Cost);
			$("#SC_OfferSMS_Cnt").val(jsondata.SC_OfferSMS_Cnt);
			$("#SC_OfferSMS_Cost").val(jsondata.SC_OfferSMS_Cost);
			$("#SC_OTP_Cnt").val(jsondata.SC_OTP_Cnt);
			$("#SC_OTP_Cost").val(jsondata.SC_OTP_Cost);
			
			//Recharge Setting
			$('#RS_SmNo_SmAmt_Delay').val(jsondata.RS_SmNo_SmAmt_Delay);
			$("#RS_SmNo_DiffAmt_Delay").val(jsondata.RS_SmNo_DiffAmt_Delay);
			var checked5=(jsondata.RS_MNP_AutoRC_Enable==true)?"check":"uncheck";
			$('#RS_MNP_AutoRC_Enable').iCheck(checked5);
			var checked6=(jsondata.RS_OTPRC_Enable==true)?"check":"uncheck";
			$('#RS_OTPRC_Enable').iCheck(checked6);
			
			//SMS Setting
			var checked7=(jsondata.SS_Success_Msg==true)?"check":"uncheck";
			$('#SS_Success_Msg').iCheck(checked7);
			var checked8=(jsondata.SS_Failed_Msg==true)?"check":"uncheck";
			$('#SS_Failed_Msg').iCheck(checked8);
			var checked9=(jsondata.SS_Suspense_Msg==true)?"check":"uncheck";
			$('#SS_Suspense_Msg').iCheck(checked9);
			var checked10=(jsondata.SS_AfterSuspence_Msg==true)?"check":"uncheck";
			$('#SS_AfterSuspence_Msg').iCheck(checked10);
			$('#SS_Time_Delay').val(jsondata.SS_Time_Delay);
			
			$('#RA_MinAmt').val(jsondata.RA_MinAmt);
			$("#RA_MaxAmt").val(jsondata.RA_MaxAmt);
			$("#TA_MinAmt").val(jsondata.TA_MinAmt);
			$("#TA_MaxAmt").val(jsondata.TA_MaxAmt);
			$("#DTH_MinAmt").val(jsondata.DTH_MinAmt);
			$("#DTH_MaxAmt").val(jsondata.DTH_MaxAmt);	
			$("#PAY_MinAmt").val(jsondata.PAY_MinAmt);
			$("#PAY_MaxAmt").val(jsondata.PAY_MaxAmt);	
			
		},
		error: function(error){
			alert(0);
		}
	},{
		isLoader:0,
		loaderElem:$('body')
	});
}

//Update Fees
$("#idBtnAddFees").click(function(e){
	e.preventDefault();
	ajaxRequest({
		type: 'post',
		url: '../../Action/GeneralSettings/GeneralSettingAction.php',
		data: $('form#GeneralSettingsFees').serialize(),
		success: function(data){
			var jsonData = JSON.parse(data);
			var errorData = JSON.parse(jsonData.data);
			$('#FeesMsg1').fadeIn(100,function(){});
			$('#FeesMsg1').fadeOut(5000,function(){});
			if(jsonData.isSuccess) {
				$("#FeesMsg1").html("<div class='alert alert-success alert-dismissible'><p>"+jsonData.message+"</p></div>").show();
			}else{
				$("#FeesMsg1").html("<div class='alert alert-danger alert-dismissible'><p>"+jsonData.message+"</p></div>").show();
			}
		},
		error: function(error){
			alert('Failed');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
});

//Update Recharge Amount
$("#idBtnUpdateRCAmt").click(function(e){
	e.preventDefault();
	ajaxRequest({
		type: 'post',
		url: '../../Action/GeneralSettings/GeneralSettingAction.php',
		data: $('form#GeneralSettingsRCAmt').serialize(),
		success: function(data){
			var jsonData = JSON.parse(data);
			var errorData = JSON.parse(jsonData.data);
			$('#RechargeAmtMsg1').fadeIn(100,function(){});
			$('#RechargeAmtMsg1').fadeOut(5000,function(){});
			if(jsonData.isSuccess) {
				$("#RechargeAmtMsg1").html("<div class='alert alert-success alert-dismissible'><p>"+jsonData.message+"</p></div>").show();
			}else{
				$("#RechargeAmtMsg1").html("<div class='alert alert-danger alert-dismissible'><p>"+jsonData.message+"</p></div>").show();
			}
		},
		error: function(error){
			alert('Failed');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
});

//Update Transfer Amount
$("#idBtnUpdateTRAmt").click(function(e){
	e.preventDefault();
	ajaxRequest({
		type: 'post',
		url: '../../Action/GeneralSettings/GeneralSettingAction.php',
		data: $('form#GeneralSettingsTRAmt').serialize(),
		success: function(data){
			var jsonData = JSON.parse(data);
			var errorData = JSON.parse(jsonData.data);
			$('#TransferAmtMsg1').fadeIn(100,function(){});
			$('#TransferAmtMsg1').fadeOut(5000,function(){});
			if(jsonData.isSuccess) {
				$("#TransferAmtMsg1").html("<div class='alert alert-success alert-dismissible'><p>"+jsonData.message+"</p></div>").show();
			}else{
				$("#TransferAmtMsg1").html("<div class='alert alert-danger alert-dismissible'><p>"+jsonData.message+"</p></div>").show();
			}
		},
		error: function(error){
			alert('Failed');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
});

//Update DTH Amount
$("#idBtnUpdateDTHAmt").click(function(e){
	e.preventDefault();
	ajaxRequest({
		type: 'post',
		url: '../../Action/GeneralSettings/GeneralSettingAction.php',
		data: $('form#GeneralSettingsDTHAmt').serialize(),
		success: function(data){
			var jsonData = JSON.parse(data);
			var errorData = JSON.parse(jsonData.data);
			$('#DTHAmtMsg1').fadeIn(100,function(){});
			$('#DTHAmtMsg1').fadeOut(5000,function(){});
			if(jsonData.isSuccess) {
				$("#DTHAmtMsg1").html("<div class='alert alert-success alert-dismissible'><p>"+jsonData.message+"</p></div>").show();
			}else{
				$("#DTHAmtMsg1").html("<div class='alert alert-danger alert-dismissible'><p>"+jsonData.message+"</p></div>").show();
			}
		},
		error: function(error){
			alert('Failed');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
});
//Update Payment Amount
$("#idBtnUpdatePAYAmt").click(function(e){
	e.preventDefault();
	ajaxRequest({
		type: 'post',
		url: '../../Action/GeneralSettings/GeneralSettingAction.php',
		data: $('form#GeneralSettingsPAYAmt').serialize(),
		success: function(data){
			var jsonData = JSON.parse(data);
			var errorData = JSON.parse(jsonData.data);
			$('#PAYAmtMsg1').fadeIn(100,function(){});
			$('#PAYAmtMsg1').fadeOut(5000,function(){});
			if(jsonData.isSuccess) {
				$("#PAYAmtMsg1").html("<div class='alert alert-success alert-dismissible'><p>"+jsonData.message+"</p></div>").show();
			}else{
				$("#PAYAmtMsg1").html("<div class='alert alert-danger alert-dismissible'><p>"+jsonData.message+"</p></div>").show();
			}
		},
		error: function(error){
			alert('Failed');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
});

//Update User Balance Amount
$("#idBtnUpdateUserBalance").click(function(e){
	e.preventDefault();
	ajaxRequest({
		type: 'post',
		url: '../../Action/GeneralSettings/GeneralSettingAction.php',
		data: $('form#GeneralSettingsUserBalance').serialize(),
		success: function(data){
			var jsonData = JSON.parse(data);
			var errorData = JSON.parse(jsonData.data);
			$('#UserBalanceMsg1').fadeIn(100,function(){});
			$('#UserBalanceMsg1').fadeOut(5000,function(){});
			if(jsonData.isSuccess) {
				$("#UserBalanceMsg1").html("<div class='alert alert-success alert-dismissible'><p>"+jsonData.message+"</p></div>").show();
			}else{
				$("#UserBalanceMsg1").html("<div class='alert alert-danger alert-dismissible'><p>"+jsonData.message+"</p></div>").show();
			}
		},
		error: function(error){
			alert('Failed');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
});


//Update User SMS Cost
$("#idBtnUpdateSMSCost").click(function(e){
	e.preventDefault();
	ajaxRequest({
		type: 'post',
		url: '../../Action/GeneralSettings/GeneralSettingAction.php',
		data: $('form#GeneralSettingsSMSCost').serialize(),
		success: function(data){
			var jsonData = JSON.parse(data);
			var errorData = JSON.parse(jsonData.data);
			$('#SMSCostMsg1').fadeIn(100,function(){});
			$('#SMSCostMsg1').fadeOut(5000,function(){});
			if(jsonData.isSuccess) {
				$("#SMSCostMsg1").html("<div class='alert alert-success alert-dismissible'><p>"+jsonData.message+"</p></div>").show();
			}else{
				$("#SMSCostMsg1").html("<div class='alert alert-danger alert-dismissible'><p>"+jsonData.message+"</p></div>").show();
			}
		},
		error: function(error){
			alert('Failed');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
});


//Update User SMS Setting
$("#idBtnUpdateSMSSetting").click(function(e){
	e.preventDefault();
	ajaxRequest({
		type: 'post',
		url: '../../Action/GeneralSettings/GeneralSettingAction.php',
		data: $('form#GeneralSettingsSMSSetting').serialize(),
		success: function(data){
			var jsonData = JSON.parse(data);
			var errorData = JSON.parse(jsonData.data);
			$('#SMSSettingMsg1').fadeIn(100,function(){});
			$('#SMSSettingMsg1').fadeOut(5000,function(){});
			if(jsonData.isSuccess) {
				$("#SMSSettingMsg1").html("<div class='alert alert-success alert-dismissible'><p>"+jsonData.message+"</p></div>").show();
			}else{
				$("#SMSSettingMsg1").html("<div class='alert alert-danger alert-dismissible'><p>"+jsonData.message+"</p></div>").show();
			}
		},
		error: function(error){
			alert('Failed');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
});

//Update User Recharge Setting
$("#idBtnUpdateRCSetting").click(function(e){
	e.preventDefault();
	ajaxRequest({
		type: 'post',
		url: '../../Action/GeneralSettings/GeneralSettingAction.php',
		data: $('form#GeneralSettingsRCSetting').serialize(),
		success: function(data){
			var jsonData = JSON.parse(data);
			var errorData = JSON.parse(jsonData.data);
			$('#RCSettingMsg1').fadeIn(100,function(){});
			$('#RCSettingMsg1').fadeOut(5000,function(){});
			if(jsonData.isSuccess) {
				$("#RCSettingMsg1").html("<div class='alert alert-success alert-dismissible'><p>"+jsonData.message+"</p></div>").show();
			}else{
				$("#RCSettingMsg1").html("<div class='alert alert-danger alert-dismissible'><p>"+jsonData.message+"</p></div>").show();
			}
		},
		error: function(error){
			alert('Failed');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
});


$(function(){
	getGeneralSettings();
});