<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>EC World | Log in</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
 <link rel="stylesheet" href="Assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="Assets/dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="Assets/plugins/iCheck/square/blue.css">

</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href=""><b>EC</b>WORLD</a>
  </div>
  <div class="login-box-body">
    <p class="login-box-msg">Sign in</p>

    <form id="idFormLogin" action="" method="post">
      <div class="form-group has-feedback">
        <input name="Name" type="type" value="" class="form-control" placeholder="Email">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input name="Password" value="" type="password" class="form-control" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <a href="#">I forgot my password</a>
            </label>
          </div>
        </div>
        <div class="col-xs-4">
          <button id="idbtnLogin" type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
      </div>
    </form>

  </div>
</div>


<script src="Assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="Assets/bootstrap/js/bootstrap.min.js"></script>
<script src="Assets/plugins/iCheck/icheck.min.js"></script>
<script src="Assets/js/Common/ajax.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
	$('#idbtnLogin').click(function(){
		ajaxRequest({
			type: 'post',
			url: 'indexSession.php',
			data: $('form#idFormLogin').serialize(),
			success: function(data){
				var jsonData = JSON.parse(data);
				if(jsonData.isSuccess==true){
					var jsonUserDetails = JSON.parse(jsonData.data);
					//alert(jsonData.message+'-'+jsonUserDetails.user.Name);
					window.location.href = 'AdminWorld/Dashboard/' ;
				}else
					alert(jsonData.message);
			},
			error: function(error){
				alert('Something went wrong. Try again.');
			}
		},{
			isLoader:1,
			loaderElem:$('.login-box-body')
		});
		return false;
	});
  });
</script>
</body>
</html>
