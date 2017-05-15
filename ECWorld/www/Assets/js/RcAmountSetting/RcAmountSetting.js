
function loadRcAmountSetting(){
	function getCommaSeperated(val){
		if(val) return val.replace(/#/g , ",");
		else return "";
	}
	var dt=ecwDatatable.init(
		$("#idTblRCAmtSetting"),
		{			
			"url" : _actionUrl+"/RechargeAmountSetting/RcAmountSettingAction.php",
			"type" : "POST",
			"data" : {
				"Action" : "GetRcAmount_DT",
			}
		},
		500,
		true,false,false,0,1,0,
		[
			{"mData" : null,'bVisible':false,'sTitle':'RcAmountID','mRender':function(data){
				return data[0];
			}},
			{"mData" : null,'sTitle':'Network Name','mRender':function(data){
				return data[1];
			}},
			{"mData" : null,'sTitle':'Default Recharge','mRender':function(data){
				return data[2];
			}},
			{"mData" : null,'sTitle':'Recharge Denomination','mRender':function(data){
				return "<p style='width:300px'>"+getCommaSeperated(data[3])+"</p>";
			},"createdCell": function(td, cellData, rowData, row, col) {
				$(td).css({"word-wrap":'break-word'});
			}},
			{"mData" : null,'sTitle':'Topup Denomination','mRender':function(data){
				return getCommaSeperated(data[4]);
			}},
			{"mData" : null,'sTitle':'Invalid Amount','mRender':function(data){
				return getCommaSeperated(data[5]);
			}}
		],function(e,id,name,all){
			upsertRCAmountPopup(false,all);
			e.preventDefault();
		});
}

function upsertRCAmountPopup(isAdd,data){
	
	$modalPopup = $("#myModal");
	$modalPopup.modal("toggle");

		dataArr = data.split(',');
		$modalPopup.find(".modal-title").html("Edit Amount");
		$modalPopup.find("#RcAmountID").val(dataArr[0]);
		$modalPopup.find("#Name").val(dataArr[1]);
		$modalPopup.find("#ServiceID").val(dataArr[6]);
		//$modalPopup.find("#DefaultType").val(dataArr[2]);
		$modalPopup.find("#DefaultType").val(dataArr[7]).change();
		$modalPopup.find("#RCDenomination").val(dataArr[3].replace(/#/g , ","));
		$modalPopup.find("#TPDenomination").val(dataArr[4].replace(/#/g , ","));
		$modalPopup.find("#InvalidAmount").val(dataArr[5].replace(/#/g , ","));
		if(dataArr[7] == '')
			$modalPopup.find("#DefaultType").val(0).change();
		else 
			$modalPopup.find("#DefaultType").val(dataArr[7]).change();
		
	
}

function getRechargeType(){
	ajaxRequest({
		type: 'post',
		//url: '/AdminWorld/Services/ServiceAction.php', //if Server enabel this one
		//url: '/ECWorld/www/AdminWorld/Services/ServiceAction.php',
		 url: _actionUrl+'/Services/ServiceAction.php',
		data: "Action=GetRechargeTypes",
		success: function(data){
		//alert(data);
			var jsondata = JSON.parse(data);
			$userContainer = $("#DefaultType");
			$userContainer.html('');
			$userContainer.append("<option value='0'>Select</option>");
			$(jsondata).each(function(index,value){
				$userContainer.append("<option value='"+value.RechargeTypeID+"'>"+value.Name+"</option>");
			});
		},
		error: function(error){
			alert('Failed to add service');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
}

function refreshRcAmountSetting(){	
	$('#idTblRCAmtSetting').DataTable().ajax.reload();
}
function upsertEditAmount(){
	ajaxRequest({
		type: 'post',
		url: _actionUrl+'/RechargeAmountSetting/RcAmountSettingAction.php',
		data: $('form#idFrmAddAmount').serialize(),
		success: function(data){
			var jsonData = JSON.parse(data);
			$('#errorMsg').fadeIn(100,function(){});
			$('#errorMsg').fadeOut(5000,function(){});
			if(jsonData.isSuccess){
				$("#errorMsg").html("<span class='alert alert-success'>"+jsonData.message+"</span>").show();
				$('#errorMsg').fadeOut(2000,function(){
					$('#myModal').modal('hide');
					$('#idBtnAddAmount').attr('disabled', false);
				});
				refreshRcAmountSetting();
			} else{
				$("#errorMsg").html("<span class='alert alert-danger'>"+jsonData.message+"</span>").show();	
				$('#idBtnAddAmount').attr('disabled', false);
			}
		},
		error: function(error){
			alert('Failed to add service');
		}
	},{
		isLoader:1,
		loaderElem:$('.box-body')
	});
}

$(function(){
	loadRcAmountSetting();
	getRechargeType();
	
	$("#idBtnAddAmount").click(function(e){
		$(this).attr('disabled', true);
		upsertEditAmount();
		return false;
	});
});
