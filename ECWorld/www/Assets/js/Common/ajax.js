//To add request header to all ajax request.
$.ajaxSetup({
    headers: { 'Token': sessionStorage.Token }
});
function getLoader(){
	var loader='<div class="ajaxloaderContainer">'
		+'<div class="ajaxloader"></div>'
		+'</div>';
	return loader;
	//fa fa-spinner fa-spin fa-3x fa-fw
}
function showloader(elem){
	if(elem){
		$(elem).css('opacity','0.2');
		$(elem).append(getLoader());
	}
}
function hideloader(elem){
	if(elem){
		$(elem).css('opacity','1');
		$(elem).find('.ajaxloader').remove();
	}
}
function logout(data){
	var jsonData = JSON.parse(data);
	alert("Logged Out");
	var url = '../../Action/User/UserAction.php';
	var redirectUrl = '../Login';
	var currentUrl = window.location.href;
	if(currentUrl.includes('Reports')){
		url = '../../../Action/User/UserAction.php';
		redirectUrl = '../../Login';
	}
	sessionStorage.Token = "";
	window.location.href=redirectUrl;
}
var loggedOut=0;//To not to call subsequent ajax response. Because it was throwing some err.
function ajaxRequest(ajaxObj,customData){
	if(customData.isLoader) showloader(customData.loaderElem);
	$.extend( ajaxObj.data, {"Token":sessionStorage.Token?sessionStorage.Token:""} );
	$.ajax({
		type: ajaxObj.type,
		url: ajaxObj.url,
		data: ajaxObj.data,
		success: function(data){
			try{
				expr = /InvalidSession/;  // no quotes here
				if(expr.test(data)){
					loggedOut=1;
					logout(data);
				}
			}catch(err){}
			
			if(loggedOut==0) ajaxObj.success(data);
			hideloader(customData.loaderElem);
		},
		error:function(data){
			ajaxObj.error(data);
			hideloader(customData.loaderElem);
		}
	});
}