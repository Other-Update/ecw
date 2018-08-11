var touserYesterdayPurchase=0;
var touserYesterdayBilling=0;
var touserTodayPurchase=0;
var touserTodayBilling=0;
var touserIsOpeningBalanceReached = 0;
var touserMargin;
function getPaymentModeName(val){
	switch(val){
		case '1':	
			return 'Normal';
		break;
		case '2':
			return 'Cash';
		break;
		case '3':
			return 'Cheque';
		break;
		case '4':
			return 'Bank Deposit';
		break;
		case '5':
			return 'Bank Transfer';
		break;
		case '6':
			return 'Other';
		break;
		default:
		return val;
	}
}
function getCredit(amount,totalAmount){
	if(amount>0) return totalAmount;
	else return '-';
}
function getDebit(amount,totalAmount){
	if(amount<0) return totalAmount;
	else return '-';
		
}
function getPaymentDescription(user,fromtouser,amount,totalAmount,type){
	console.log(type);
	if(type==1){
		if(amount>0)
			return 'Rs.'+Math.abs(totalAmount)+' has been received from '+fromtouser;
		else 
			return 'Rs.'+Math.abs(totalAmount)+' has been credited to '+fromtouser;
	}else{
		if(amount>0)
			return 'Rs.'+Math.abs(totalAmount)+' has been debited from '+fromtouser;
		else 
			return 'Rs.'+Math.abs(totalAmount)+' has been reverted to '+fromtouser;
	}
}
function loadTransfers_DT(){
	//alert($("#idSelectUserID").val());
	
	var dt=ecwDatatable.init(
		$("#idPaymentTransfer"),
		{			
			"url" : "../../Action/Payment/PaymentAction.php",
			"type" : "POST",
			"data" : {
				"Action" : "GetTransfers_DT",
				"SearchStr" : function(d){ return $("#idSelectUserID").val();},
				"ParentID" : function(d){ return $("#idSelectUserID").attr("data-userid");},
				"fromDate"	: function(d){ return $("#fromDate").val();},
				"toDate"	: function(d){ return $("#toDate").val();} 				
			}
		},
		500,
		false,false,false,0,1,0,
		[
			{"mData" : null,'bVisible':false,'sTitle':'PaymentID','mRender':function(data){
				return data[0];
			}},
			{"mData" : null,'sTitle':'S.No','mRender':function(data){
				return data[0];
			}},
			{"mData" : null,'sTitle':'Date & Time','mRender':function(data){
				return data[1];
			}},
			{"mData" : null,'sTitle':'UserID','mRender':function(data){
				return getUserToolTip(data[2],data[4],data[5],data[6],"");
			}},
			{"mData" : null,'sTitle':'Description','mRender':function(data){
				return getPaymentDescription(data[4],data[3],data[8],data[14],data[10]);
			}},
			{"mData" : null,'sTitle':'Amount','mRender':function(data){
				return Math.abs(data[8]);
			}},
			{"mData" : null,'sTitle':'Commission','mRender':function(data){
				return Math.abs(data[9]);
			}},
			{"mData" : null,'sTitle':'Credit','mRender':function(data){
				return getCredit(data[8],data[14]);
			}},
			{"mData" : null,'sTitle':'Debit','mRender':function(data){
				return getDebit(data[8],data[14]);
			}},
			{"mData" : null,'sTitle':'Balance','mRender':function(data){
				return data[11];
			}},
			{"mData" : null,'sTitle':'Remark','mRender':function(data){
				return data[12];
			}},
			{"mData" : null,'sTitle':'Mode','mRender':function(data){
				return getPaymentModeName(data[13]);
			}}
		],function(e,id,name,all){
			alert('Edit');
			e.preventDefault();
		},function(e,id,name,all){
			alert('Delete');
			e.preventDefault();
		},function(oSettings){
			if(oSettings.json)
			if(oSettings.json.ecwIsUserFound=="0")
				$("#idLabelUserName").html(oSettings.json.ecwMessage);
			else 
				$("#idLabelUserName").html(oSettings.json.ecwUser.Name);
			console.log(oSettings.json);
			//alert(JSON.stringify(oSettings));
		});
}
function reloadTransfers_DT(){
	//alert('table reload');
	$('#idPaymentTransfer').DataTable().ajax.reload();
}
function changeUserSelectionPopup(triggeredBy,from,to){	
	if(triggeredBy=='from'){
		//If system is selected then show only Admin in the to field
		if($(from).val()==0){
			$(to).val(1);
		}
	}
	/*//Either FROM user or TO user shoud be the selected user from the mail dropdown of payment transfer page
	
	var selectedUser = $("#idSelectUserID").val();//Main user selection in payment transfer page
	$(to+" option").prop("disabled", false);
	$(from+" option").prop("disabled", false);
	if(triggeredBy=='from'){
		if($(from).val()!=selectedUser)
			$(to).val(selectedUser);
	}else{
		if($(to).val()!=selectedUser)
			$(from).val(selectedUser);
	}*/
	//$(to+" option[value="+$(from).val()+"]").attr("disabled",true);
	//$(from+" option[value="+$(to).val()+"]").attr("disabled",true);
	//$(to).select2();
	//$(from).select2();
}

function getUserDetailsForTranser(searchStr,userElem,balanceElem,callbackFn){
	getUserDetails(searchStr,function(json){
		//console.log("getUserDetailsForTranser="+JSON.stringify(json));
		if(json.isSuccess){
				debugger;
			//ar resp=JSON.parse(json);
			var label=$(userElem).parent().find("label");
			if(json.ecwIsUserFound=="0")
				$(label).html(($(label).html().indexOf("From")!=-1?"From":"To")+"("+json.ecwMessage+")");
			else {
				$(label).html(($(label).html().indexOf("From")!=-1?"From":"To")+"("+json.ecwUser.Name+")");
				//$(userElem).find("label").html(json.ecwUser.Name);
				$(userElem).val(json.ecwUser.UserID);
			}
				
			var data=JSON.parse(json.data);
			//var userWallet = JSON.parse(data.UserWallet);
			//alert(isNaN(data.UserWallet.Wallet)?0:data.UserWallet.Wallet);
			balanceElem.val(data.UserWallet.Wallet);
			balanceElem.data("balancelevel",isNaN(data.UserWallet.BalanceLevel)?0:data.UserWallet.BalanceLevel);
			balanceElem.data("actualbalance",isNaN(data.UserWallet.Wallet)?0:data.UserWallet.Wallet);
			//alert(data.todayPurchase);
			
			if(callbackFn) callbackFn(data.IsOpeningBalReached,data.YesterdayPurchase,data.YesterdayBilling,data.TodayPurchase,data.TodayBilling,data.Margin);
		}else{
		}
	});
}/*
function getWalletBalance(userID,callbackFn,errorFn){
	getUserBalance(userID,function(balance){
		callbackFn(balance.Wallet,balance.BalanceLevel);
	},errorFn);
}
function showWalletBalance(elem,userID){
	if(userID!=0){	
		getWalletBalance(userID,function(wallet,blevel){
			elem.val(wallet);//alert(blevel);
			elem.data("balancelevel",blevel);
		},function(msg){
			alert("Error:"+msg);
		});
	}
	else{
		elem.val(0);//Wallet
		elem.data("balancelevel",0);//Balance level
	}
}*/
function calculateMargin(){
	var enteredAmount = $("#idAmount").val();
	if($("#idType").val()==1)//Credit
		calculateMarginForCredit(enteredAmount);
	else
		calculateMarginForDebit(enteredAmount);
	//reCalculateTransfer();
	validateAddTransferForm();
}
function getMarginByAmount(margins, amount){
	var appliedMargin = null;
	$(margins).each(function(index,mar){
		var from = parseFloat(mar.FromAmount);
		var to = parseFloat(mar.ToAmount);
		//console.log("from="+from+",To="+to);
		if(from<=amount && amount<=to)
			appliedMargin = mar;
	});
	return appliedMargin;
}
function getBilling(yestPurchaseAmnt,yestBilling,curMargin){
	var billing = 0;
	if(curMargin)
		if(touserIsOpeningBalanceReached==0 || yestPurchaseAmnt == 0)
			billing = curMargin.NormalBilling;
		else if((yestBilling==curMargin.NormalBilling || yestBilling==curMargin.RegularBilling))
		{
			billing=curMargin.RegularBilling;
		}else{
			if(yestBilling > curMargin.NormalBilling)
				billing = curMargin.RegularBilling;
			else
				billing = curMargin.NormalBilling;
		}
	return billing;
}
function calculateMarginForDebit(amountToDebit){
	var yesterdayPurchaseAmnt=isNaN(touserYesterdayPurchase)?0:touserYesterdayPurchase;
	var todayPurchaseAmnt=isNaN(touserTodayPurchase)?0:touserTodayPurchase;
	
	//Final amount has to be purchased today by this user. In case of 5k purchased yesterday and 2k needs to be debited then finalAmount will be 3k(5k-2k).
	var finalAmount = todayPurchaseAmnt - amountToDebit;
	var finalMargin = getMarginByAmount(touserMargin,parseFloat(finalAmount));
	var finalBilling = getBilling(yesterdayPurchaseAmnt,touserYesterdayBilling,finalMargin);
	//extra billing percent to be debited for final amount. Incase of 5% has been applied for today purchase(5k). and 2% billing is applied for finalAmount(3k). So extraBilling percentage will be 3%(5%-2%). This 3% should have been deducted now.
	
	var extraBillingToDebit = touserTodayBilling - finalBilling;
	//alert(extraBillingToDebit);
	$("#idCommissionAmountPrevPur").data("billing",extraBillingToDebit);
	$("#idExtraCommForDebit").val(extraBillingToDebit);
	$("#idCommissionPercent").val(touserTodayBilling);
	console.log("finalBilling="+finalBilling+",touserTodayBilling="+touserTodayBilling+",extraBillingToDebit="+extraBillingToDebit+",touserYesterdayBilling="+touserYesterdayBilling+",yesterdayPurchaseAmnt="+yesterdayPurchaseAmnt+",todayPurchaseAmnt="+todayPurchaseAmnt+",touserIsOpeningBalanceReached="+touserIsOpeningBalanceReached+",FinalAmount="+finalAmount+",amountToDebit="+amountToDebit);
}
function calculateMarginForCredit(amountToTransfer){
	//var toUserID = $("#idSelectToUser").val();
	//console.log("calculateMargin------------------------"+amountToTransfer);
	
	//alert(touserIsOpeningBalanceReached);
	//Yesterday last applied margin
	/* if(touserYesterdayPurchase!=null)
		if(touserYesterdayPurchase.length>0)
			yesterdayPurchaseAmnt = touserYesterdayPurchase[0].Amount; */
	var yesterdayPurchaseAmnt=isNaN(touserYesterdayPurchase)?0:touserYesterdayPurchase;
	//var yesterdayMargin = getMarginByAmount(touserMargin,parseFloat(yesterdayPurchaseAmnt));
	
	//Today last applied margin
	var todayPurchaseAmnt=isNaN(touserTodayPurchase)?0:touserTodayPurchase;
	//var todayLastMargin = getMarginByAmount(touserMargin,parseFloat(todayPurchaseAmnt));
	/* console.log("touserTodayPurchase="+touserTodayPurchase);
	console.log('Today total pur='+(parseFloat(amountToTransfer)+parseFloat(todayPurchaseAmnt))); */
	//Margin for the entered amount+today purchase
	
	var currentMargin = getMarginByAmount(touserMargin,parseFloat(amountToTransfer)+parseFloat(todayPurchaseAmnt));
	/* console.log('yesterdayMargin='+JSON.stringify(yesterdayMargin));
	console.log('todayLastMargin='+JSON.stringify(todayLastMargin));
	console.log('currentMargin='+JSON.stringify(currentMargin)); */
	
	var billingPercentCur = getBilling(yesterdayPurchaseAmnt,touserYesterdayBilling,currentMargin);
	var billingPercentTodayPur = touserTodayBilling;//getBilling(yesterdayMargin,todayLastMargin);
	var billingIncrease = 0;//Difference between lastpurcahse of today and current purchase margins. 
	if(touserTodayBilling < billingPercentCur){
		billingIncrease = billingPercentCur - billingPercentTodayPur;
	}
	console.log("billingPercentCur="+billingPercentCur+",billingPercentTodayPur="+billingPercentTodayPur+",yestBilling="+touserYesterdayBilling+",yesterdayPurchaseAmnt="+yesterdayPurchaseAmnt+",todayPurchaseAmnt="+todayPurchaseAmnt);
	$("#idCommissionAmountPrevPur").data("billing",billingIncrease);
	//$("#idCommissionAmountPrevPur").val(billingIncrease);
	$("#idCommissionPercent").val(billingPercentCur);
	/* console.log('touserYesterdayPurchase='+JSON.stringify(touserYesterdayPurchase));
	console.log('Margin='+JSON.stringify(touserMargin));
	console.log('yesterdayMargin='+JSON.stringify(yesterdayMargin));
	console.log('currentMargin='+JSON.stringify(currentMargin)); */
	
}
function onAddTransferOpeningPoup(){
	var selectedUser = $("#idSelectUserID").val();
	//Load all users all time in the from DD
	/*loadDDusers($("#idSelectFromUser"),1,true,"2,6",function(){
		//$("#idSelectFromUser").val(selectedUser).select2().change();
		$("#idSelectFromUser").change();
		getUserDetailsForTranser($("#idBalanceFromUser"),$("#idSelectFromUser").val());
	});*/
	getUserDetailsForTranser($("#idSelectFromUserSearch").val(),$("#idSelectFromUser"),$("#idBalanceFromUser"));
	/* loadDDusers($("#idSelectToUser"),selectedUser,false,"0",function(){
		getUserDetailsForTranser($("#idBalanceToUser"),$("#idSelectToUser").val(),function(isOpeningBalReached,yesterdayPurchase,yesterdayBilling,todayPurchase,todayBilling,margin){
			//alert(lastPurchase);
			touserIsOpeningBalanceReached = isOpeningBalReached;
			touserYesterdayPurchase=yesterdayPurchase;
			touserYesterdayBilling=yesterdayBilling;
			touserTodayPurchase = todayPurchase;
			touserTodayBilling = todayBilling;
			touserMargin=margin;
			//alert(JSON.stringify(margin));
			calculateMargin();
		});
	}); */
	//alert(selectedUser);
	$("#idAmount").val(0);
	$("#idCommissionPercent").val(0);
	$("#idCommissionAmount").val(0);
	$("#idTotalAmount").val(0);
	$("#idRemark").val(0);
	$("#idPaidAmount").val(0);
	$("#idTransSendSMS").attr('checked','checked');
	clearPopupErrors();
	/* $("#idType").val('');
	$("#idMode").val(''); */
}
function showError(elem,msg){
	elem.html(msg).css({"color":"red"}).show();
}
function clearPopupErrors(){
	$("#idAmount").parent().find(".error").hide();
	$("#idCommissionPercent").parent().find(".error").hide();
	$("#idSpnSuccessErr").html('').hide();
}
function caluculateAmount_NIU(){
	if($("#idType").val()==1)//Credit
		reCalculateTransferCredit();
	else
		reCalculateTransferDebit();
}
function validateAddTransferForm(){
	clearPopupErrors();
	var isValidated = true;
	//debugger;
	var type=$("#idType").val();
	var walletFromUser = parseFloat($("#idBalanceFromUser").val());
	var balanceLevelFromUser = parseFloat($("#idBalanceFromUser").data("balancelevel"));
	var amountToTransfer=parseFloat($("#idAmount").val());
	var amountTransferedToday =isNaN(touserTodayPurchase)?0:touserTodayPurchase;
	var commission=parseFloat($("#idCommissionPercent").val());
	var commissionForPrevPur = $("#idCommissionAmountPrevPur").data("billing");
	//alert("commissionRemaining="+commissionRemaining);
	if(amountToTransfer=="" || isNaN(amountToTransfer)){
		showError($("#idAmount").parent().find(".error"),"Invalid");
		isValidated = false;
	}else{
		$("#idAmount").parent().find(".error").hide();
	}
	
	if(isNaN(commission)){
		showError($("#idCommissionPercent").parent().find(".error"),"Invalid");
		isValidated = false;
	}else{
		$("#idCommissionPercent").parent().find(".error").hide();
	}
	if(isValidated){
		//caluculateAmount(commission,amountToTransfer,commissionForPrevPur,amountTransferedToday);
		var commAmnt = parseFloat((commission/100)*amountToTransfer);
		var commissionAmntPrevPurchase = 0;
		
		if(!isNaN(commissionForPrevPur))
				if(commissionForPrevPur>0)
					if(type==1)//Credit
						commissionAmntPrevPurchase = parseFloat((commissionForPrevPur/100)*amountTransferedToday);
					else
						commissionAmntPrevPurchase = parseFloat((commissionForPrevPur/100)*(amountTransferedToday-amountToTransfer));
					
		//alert(commissionAmntPrevPurchase);
		commAmnt += commissionAmntPrevPurchase;
		var totalAmount = amountToTransfer+commAmnt;
		//alert(totalAmount);
		if((walletFromUser-balanceLevelFromUser)<totalAmount && $("#idSelectFromUser").val()>0){
			showError($("#idSpnSuccessErr"),"Amount must be less than from user balance");
			isValidated = false;
		}else{
			$("#idSpnSuccessErr").hide();
		}
		//alert(totalAmount.toFixed(2));
		$("#idCommissionAmount").val(commAmnt.toFixed(2));
		$("#idCommissionAmountPrevPur").val(commissionAmntPrevPurchase.toFixed(2));
		$("#idTotalAmount").val(totalAmount.toFixed(2));
		
		if(type==1){//Credit
			$("#idBalanceFromUser").val((parseFloat($("#idBalanceFromUser").data('actualbalance'))-totalAmount).toFixed(2));
			$("#idBalanceToUser").val((parseFloat($("#idBalanceToUser").data('actualbalance'))+totalAmount).toFixed(2));
		}else{//Debit			
			$("#idBalanceFromUser").val((parseFloat($("#idBalanceFromUser").data('actualbalance'))+totalAmount).toFixed(2));
			$("#idBalanceToUser").val((parseFloat($("#idBalanceToUser").data('actualbalance'))-totalAmount).toFixed(2));
		}
	}else{
		$("#idCommissionAmount").val(0.00);
		$("#idTotalAmount").val(0.00);
		$("#idBalanceFromUser").val(parseFloat($("#idBalanceFromUser").data('actualbalance')));
		$("#idBalanceToUser").val(parseFloat($("#idBalanceToUser").data('actualbalance')));
	}
	
	//User selection cannot be same
	//Atleast one selection shuould be main filter user
	//alert(($("#idSelectUserID").val()));
	if(isValidated)
	if($("#idSelectFromUser").val() == $("#idSelectToUser").val()){
		showError($("#idSpnSuccessErr"),"Select different users");
		isValidated=false;
	}/* else if(($("#idSelectUserID").val()!=$("#idSelectFromUser").val()) && ($("#idSelectUserID").val() != $("#idSelectToUser").val())){
		showError($("#idSpnSuccessErr"),"At least one user should be "+$("#idSelectUserID option:selected").html());
		isValidated=false;
	} */
	//alert(isValidated);
	return isValidated;
}
function addTransfer(callbackFn){
	if(validateAddTransferForm()){
		paymentAjax($('form#idFrmAddTransfer').serialize(),function(res){
		debugger;
			callbackFn();
			if(res.isSuccess){
				onAddTransferOpeningPoup();
				$("#idSpnSuccessErr").html(res.message).css({'color':'green'}).show();
			}else 
				showError($("#idSpnSuccessErr"),res.message);
			reloadTransfers_DT();
		},function(){});
	}else{	
		callbackFn();
	}
	return false;
}

function reCalculateTransfer_NIU(){
	var amnt=$("#idAmount").val();
	var commission=$("#idCommissionPercent").val();
	if(amnt=="" || commission=="" || isNaN(amnt) || isNaN(commission)) return;
	validateAddTransferForm();
	/*
	commission = parseFloat(amnt);
	commission = parseFloat(amnt);
	var commAmnt = parseFloat(amnt/commission);
	var totAmnt = amnt-commAmnt;
	$("#idCommissionAmount").val(commAmnt);
	$("#idTotalAmount").val(totAmnt);
	var fromUserBalance = $("#idBalanceFromUser").val();
	//alert(amnt);
	if(!isNaN(fromUserBalance))
		if(parseFloat(fromUserBalance)<amnt)
			$("#idSpnSuccessErr").html("Amount must be less than from user balance").css({'color':'red'}).show();
		else
			$("#idSpnSuccessErr").hide();
	*/
}
$(function(){
	//getUserDetailsForTranser(200,function(){},function(){});
	loadTransfers_DT();
	//loadDDusers($("#idSelectUserID"),1,true,"2",function(){
		//reloadTransfers_DT();
	//});
	
	$("#idBtnPayTransferPopup").click(function(){
		onAddTransferOpeningPoup();
	});
	$("#idBtnAddTransfer").click(function(){
		$("#idBtnAddTransfer").prop('disabled', true);
		addTransfer(function(){
			$("#idBtnAddTransfer").prop('disabled', false);
		});
		return false;
	});
	
	$("#idSelectFromUserSearch").change(function(){
		getUserDetailsForTranser($(this).val(),$("#idSelectFromUser"),$("#idBalanceFromUser"));
	});
	$("#idSelectToUserSearch").change(function(){
		getUserDetailsForTranser($(this).val(),$("#idSelectToUser"),$("#idBalanceToUser"));
	});
	/*$("#idSelectFromUser").change(function(){
		changeUserSelectionPopup("from","#idSelectFromUser","#idSelectToUser");
		getUserDetailsForTranser($("#idBalanceFromUser"),$(this).val());
		//alert($("#idSelectFromUser").val());
		//Load users to ToSelect Dropdown
		if($("#idSelectFromUser").val()==0){
			$userContainer = $("#idSelectToUser");
			$userContainer.html('');
			$userContainer.append("<option data-mobile='0' value='1'>1-Admin</option>");
		}else{
		loadDDusers($("#idSelectToUser"),$("#idSelectFromUser").val(),false,"2",function(){
			getUserDetailsForTranser($("#idBalanceToUser"),$("#idSelectToUser").val(),function(isOpeningBalReached,yesterdayPurchase,yesterdayBilling,todayPurchase,todayBilling,margin){
					//alert(lastPurchase);
					touserIsOpeningBalanceReached = isOpeningBalReached;
					touserYesterdayPurchase=yesterdayPurchase;
					touserYesterdayBilling=yesterdayBilling;
					touserTodayPurchase = todayPurchase;
					touserTodayBilling = todayBilling;
					touserMargin=margin;
					//alert(JSON.stringify(margin));
					calculateMargin();
				});
		});
		}
	});*/
	/*$("#idSelectToUser").change(function(){
		//changeUserSelectionPopup("to","#idSelectToUser","#idSelectFromUser");
		
		getUserDetailsForTranser($("#idBalanceToUser"),$("#idSelectToUser").val(),function(isOpeningBalReached,yesterdayPurchase,yesterdayBilling,todayPurchase,todayBilling,margin){
			//alert(lastPurchase);
			touserIsOpeningBalanceReached = isOpeningBalReached;
			touserYesterdayPurchase=yesterdayPurchase;
			touserYesterdayBilling=yesterdayBilling;
			touserTodayPurchase = todayPurchase;
			touserTodayBilling = todayBilling;
			touserMargin=margin;
			//alert(JSON.stringify(margin));
			calculateMargin();
		});
	});*/
	$("#idCommissionPercent").keyup(function(){
		//reCalculateTransfer();
		validateAddTransferForm();
	});
	$("#idAmount").keyup(function(){
		calculateMargin();
		//reCalculateTransfer();
	});
	//calculateMargin();
	//$("#idSelectUserID").change(function(){
		//reloadTransfers_DT();
	//});
	$("#idType").change(function(){
		//alert($("#idMode").children('option').length);
		$("#idMode").children('option').remove();
		if($(this).val()==2){
			$("#idMode").append($("#idModeOptionsBackup").find(".clsDebitModes").clone());
		}else{
			$("#idMode").append($("#idModeOptionsBackup").find(".clsCreditModes").clone());
		}
		calculateMargin();
	});
	$("#idBtnSearch").click(function(){
		var userSearchStr=$("#idSelectUserID").val();
		if(userSearchStr!=""){
			reloadTransfers_DT();
		}else{
			alert("Enter User ID/Mobile");
			$("#idSelectUserID").focus();
		}
	});
});
