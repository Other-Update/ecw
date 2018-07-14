//Common url for call action file
var _actionUrl = '../../Action';

//Dont use this loadUsers() , instead use loadUsersToSelectDD().
function loadUsers(userContainer,data,includeadmin,callbackfn){
	loadUsersToSelectDD(userContainer,data,includeadmin,callbackfn);
}
function userAjax(data,successFn,errorFn){
	ajaxRequest({
		type: 'post',
		url:'../../Action/User/UserAction.php',
		data: data,
		success: function(data){
			successFn(data);
		},
		error: function(error){
			alert('Failed to get users');
			errorFn(0);
		}
	},{
		isLoader:0,
		loaderElem:$('body')
	});
}
function getUsers(data,callbackfn){
	userAjax(data,callbackfn);
}
function loadUsersToSelectDD(userContainer,dataIP,includeadmin,callbackfn){

	if(sessionStorage.AllUsersJson && sessionStorage.AllUsersJson!="")
	{
		var userList = JSON.parse(sessionStorage.AllUsersJson);
		$userContainer = $(userContainer);
		$userContainer.html('');
		$(userList).each(function(index,value){
			//Don't show Admin (UserID is 1)
			if(value.UserID!=1 || includeadmin==true){
				$userContainer.append("<option data-mobile='"+value.Mobile+"' value='"+value.UserID+"'>"+value.DisplayID+"-"+value.Mobile+"-"+value.Name+"</option>");
			}
		});
		callbackfn(1,userList);
	}else{
		getUsers(dataIP,function(data){
			if(data==0){
				callbackfn(0);
			}else{
				//
				$userContainer = $(userContainer);
				$userContainer.html('');
				//alert(jsondata.fat);
				//alert(includeadmin==true);
				//debugger
				var userList = {};
				if(dataIP.Action=='GetAllUsers'){
					var jsondata = JSON.parse(JSON.parse(data));
					userList = jsondata.users;
				}else{
					userList= JSON.parse(data);
				}
				sessionStorage.AllUsersJson=JSON.stringify(userList);
				$(userList).each(function(index,value){
					//Don't show Admin (UserID is 1)
					if(value.UserID!=1 || includeadmin==true){
						$userContainer.append("<option data-mobile='"+value.Mobile+"' value='"+value.UserID+"'>"+value.DisplayID+"-"+value.Mobile+"-"+value.Name+"</option>");
					}
				});
				//$userContainer.select2();
				//alert($userContainer.html());
				callbackfn(1,jsondata);
			}
		});
	}

}
//TODO: Try to make use of loadUsersToSelectDD and remove this. 
//Report
function loadUsersForReport(userContainer,dataIP,includeadmin,callbackfn){
	if(sessionStorage.AllUsersForReportJson && sessionStorage.AllUsersForReportJson!="")
	{
		var jsondata = JSON.parse(sessionStorage.AllUsersForReportJson);
		$userContainer = $(userContainer);
		$userContainer.html('');
		$(jsondata).each(function(index,value){
			//Don't show Admin (UserID is 1)
			if(value.UserID!=1 || includeadmin==true){
				$userContainer.append("<option data-mobile='"+value.Mobile+"' value='"+value.UserID+"'>"+value.DisplayID+"-"+value.Name+"</option>");
			}
		});
		callbackfn(1);
	}else{
		ajaxRequest({
			type: 'post',
			url:'../../../Action/User/UserAction.php',
			data: dataIP,
			success: function(data){
				//console.log(data);
				var jsondata = JSON.parse(data);
				$userContainer = $(userContainer);
				$userContainer.html('');
				//alert(includeadmin==true);
				var jsondata = {};
				if(dataIP.Action=='GetAllUsers'){
					var jsondata = JSON.parse(JSON.parse(data));
					jsondata = jsondata.users;
				}else{
					jsondata = JSON.parse(data);
				}
				sessionStorage.AllUsersForReportJson=JSON.stringify(jsondata);
				$(jsondata).each(function(index,value){
					//Don't show Admin (UserID is 1)
					if(value.UserID!=1 || includeadmin==true){
						$userContainer.append("<option data-mobile='"+value.Mobile+"' value='"+value.UserID+"'>"+value.DisplayID+"-"+value.Name+"</option>");
					}
				});
				//$userContainer.select2();
				//alert($userContainer.html());
				callbackfn(1);
			},
			error: function(error){
				alert('Failed to get users');
				callbackfn(0);
			}
		},{
			isLoader:0,
			loaderElem:$('body')
		});
	}
}

function getUserToolTip(userID,displayID,name,mobile,link){
	if(link!="")
		return "<a class='clsEcwToolTip' href='"+link+"' title='"+displayID+"-"+name+"-"+mobile+"'>"+displayID+"</a>";
	else
		return "<div class='clsEcwToolTip' title='"+displayID+"-"+name+"-"+mobile+"'>"+displayID+"</div>";
}
function getUserBalance(userID,callbackFn,errorFn){
	userAjax("Action=GetUserBalance&UserID="+userID,function(data){
		var jsonObj = JSON.parse(data);
		if(jsonObj.isSuccess)
			callbackFn(JSON.parse(jsonObj.data));
		else
			errorFn(jsonObj.message);
	});
}

function getUserDetailsByID(userID,successFn,errorFn){
	userAjax("Action=GetByID&UserID="+userID,function(data){
		var jsonObj = JSON.parse(data);
		successFn(jsonObj);
	});
}