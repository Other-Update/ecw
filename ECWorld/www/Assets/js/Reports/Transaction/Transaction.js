var count=1;
function loadTransactions(){
	function getTransactiontype(value){
		switch(parseInt(value)){ 
			case 1:
				return "Payment";break;
			case 2:
				return "Recharge";break;
			case 3:
				return "SMS";break;
			case 4:
				return "New User";break;
			default:
				return value;
		}
	}
	function getStatus(value){
		switch(parseInt(value)){
			case 1:
				return "Pending"; break;
			case 2:
				return "Suspense";break;
			case 3:
				return "Success";break;
			case 4:
				return "Failed";break;
			default:
				return value;
		}
	}
	count=1;
	var dt=ecwDatatable.init(
		$("#idTblTransaction"),
		{			
			"url"  : "../../../Action/Reports/Transaction/TransactionAction.php",
			"type" : "POST",
			 async : false,
			"data" : {
				"Action" 	: "TransactionReport_DT",
				"userId" 	: function(d){ return $("#idSelectUserID").val();}, 
				"mobile" 	: function(d){ return $("#mobile_no").val();},
				"network" 	: function(d){ return $("#network").val();}, 
				"requestId" : function(d){ return $("#requestId").val();},
				"fromDate"	: function(d){ return $("#fromDate").val();},
				"toDate"	: function(d){ return $("#toDate").val();} 
			}
		},
		2000,
		false,false,false,0,1,0,
		[
			{"mData" : null,'sTitle':'S.No','mRender':function(data){
				return count++;
			}/* ,
			"fnRowCallback" : function(nRow, aData, iDisplayIndex){
                $("td:first", nRow).html(iDisplayIndex +1);
               return nRow;
            } */
			},
			{"mData" : null,'bVisible':false,'sTitle':'TransactionID','mRender':function(data){
				return data[0];
			}},
			{"mData" : null,'sTitle':'UserID','mRender':function(data){
				return data[1]+'-'+data[2];
			}},
			{"mData" : null,'sTitle':'RequestID','mRender':function(data){
				return data[3];
			}},
			{"mData" : null,'sTitle':'DateTime','mRender':function(data){
				//return getTransactiontype(data[3]);
				return data[4];
			}},
			{"mData" : null,'sTitle':'Resp.Time','mRender':function(data){
				return data[5];
			}},
			{"mData" : null,'sTitle':'Trans_ID','mRender':function(data){
				return data[6];  
			}},
			{"mData" : null,'sTitle':'Mobile','mRender':function(data){
				return data[7];
			}} ,
			{"mData" : null,'sTitle':'Rs.','mRender':function(data){
				return Math.abs(data[8]);
			}},
			{"mData" : null,'sTitle':'Network','mRender':function(data){
				return data[10];
			}},
			{"mData" : null,'sTitle':'Description','mRender':function(data){
				return data[11];
			}} ,
			{"mData" : null,'sTitle':'Status','mRender':function(data){
				return getStatus(data[12]);
			},"createdCell": function(td, cellData, rowData, row, col) {
				var color;
				//console.log('cellData='+cellData[12]);
				switch(cellData[12]) {
					case "1":
						color = '#E4E400';
						break;
					case "2":
						color = '#57AEE0';
						break;
					case "3":
						color = '#45CA3F';
						break;
					case "4":
						color = '#EA1F22';
						break;
					default:
						color = '#FF3229';
						break;
				}
				$(td).css({"color":color, "font-weight": "700"});
			}},
			{"mData" : null,'sTitle':'Credit','mRender':function(data){
				if(data[9] > 0) 
					return data[9];
			}},
			{"mData" : null,'sTitle':'Debit','mRender':function(data){
				if(data[9] < 0) 
					return data[9];
			}},
			{"mData" : null,'sTitle':'Balance','mRender':function(data){
				return data[13];
			}} ,
			{"mData" : null,'sTitle':'Type','mRender':function(data){
				return getTransactiontype(data[14]);
			}},
			{"mData" : null,'bVisible':false,'sTitle':'Type','mRender':function(data){
				return data[15];
			}}
		]);
}  
var isDTLoaded =false;
function refreshTransactions(){
	if(isDTLoaded)	{
		count =1;
		$('#idTblTransaction').DataTable().ajax.reload();
		
	}
	else {
		loadTransactions();
		isDTLoaded = true;
		count =1;
	}

   // $('#idTblTransaction tbody').append(row2);


}
 

function  getOpenCloseBalance() {
	var userId = $("#idSelectUserID").val();
	var fromDate = $("#fromDate").val();
	var toDate = $("#toDate").val();
	ajaxRequest ({
		type: 'post',
		 url: '../../../Action/Reports/Transaction/TransactionAction.php',
		data: {"Action" : 'TransactionOpenCloseBalance', 'userId':userId, 'fromDate':fromDate, 'toDate':toDate},
		success: function(data){
			var jsonData = JSON.parse(data);
			var balance = JSON.parse(jsonData);
			var opening_bal = balance.OpeningBalance.ClosingBalance;
			var closing_bal = balance.ClosingBalance.ClosingBalance;
			if(!opening_bal) opening_bal=0;
			$('#idTblTransaction tbody').append('<tr><td colspan="7" class="text-right">Opening Balance</td><td colspan="8">'+opening_bal+'</tr>');
			$('#idTblTransaction tbody').prepend('<tr><td colspan="7" class="text-right">Closing Balance</td><td colspan="8">'+closing_bal+'</tr>');
			
			//var employersTable = $('#idTblTransaction').DataTable();
			//employersTable.row.add(['1','', '', '', '', '', 'Opening_Bal',opening_bal]).draw(false);
			//employersTable.row.add(['','', '', '', '', '', 'Closing_Bal',closing_bal]).draw(false);
			
		},
		error: function(error){
			alert(0);
		}
	},{
		isLoader:0,
		loaderElem:$('body')
	});
} 
 
/* function showUsers(){
	loadUsersForReport($("#idSelectUserID"),{
		"Action":"GetAllUsers",
		"ParentID":"0",
		"IncludeParent":"1",
		"IncludeAllSubUsers":"1",
		"ExcludeRoleIDs":"2"
	},true,function(isSuccess){
		$('#idSelectUserID').prepend('<option selected="selected" value=""> Select User </option>');
		//loadTransactions();
	});
}

//Get Network list from network provider name
function getNetworkList(){
	ajaxRequest({
		type: 'post',
		url: '../../../Action/AutoMNP/AutoMnpAction.php',
		data: "Action=GetNetworkList",
		success: function(data){
			parentsList = data;
			var jsondata = JSON.parse(data);
			$(".SelectedNetwork").html('');
			$(".SelectedNetwork").append("<option value='' > --Select Network-- </option>");
			$(jsondata).each(function(index,value){
				$(".SelectedNetwork").append("<option value='"+value.Name+"' >"+value.Name+"</option>");
			});
			
		},
		error: function(error){
			alert(0);
		}
	},{
		isLoader:0,
		loaderElem:$('body')
	});
}
 */

$(function(){
	//showUsers();
	//getNetworkList();
	$("#Search_PayCollection").click(function(e){
		refreshTransactions();
		return false;
	});

	$("#Search_PayCollection").click(function(e){
		var userId = $("#idSelectUserID").val(); 
		if(userId != '') {
			getOpenCloseBalance();
			return false;
		}
	});

	
});

$('#idSelectUserID').change(function(){
	//refreshTransactions();
	//$('#mobile_no').val($("#idSelectUserID").select2().find(":selected").data("mobile"));

});

