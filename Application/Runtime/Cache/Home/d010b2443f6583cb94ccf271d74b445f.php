<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link rel="stylesheet" href="/Public/assets/css/style.css"/>
    <script src="/Public/assets/js/jquery.min.js"></script>
</head>
<body>

	<div class="login_bg">
		<div class="login_center">
			<div class="login_center_panel">
			<form action="<?php echo U('Login/login');?>" method="post" style="display: block;">
				<ul class="input_ul">
					<li><input type="text" name='username' class="username" id="usn"></li>
					<li style="margin-top: 17px"><input type="password" name='password' class="password" id="pwd"></li>

					<li style="margin-top: 52px;"><input type="submit" class="login_btn" value=""></li>
				</ul>

			</form>
			<p id="state" style="margin-top: 170px; color: #fff;visibility:hidden; font-size: 13px;margin-left :17px">警告: 您已被异地登陆,如果您不是本人登陆，请及时修改密码!</p>
			</div>

		</div>
	</div>
</body>

<script>

function getQueryVariable(variable)
{
       var query = window.location.search.substring(1);
       var vars = query.split("&");
       for (var i=0;i<vars.length;i++) {
               var pair = vars[i].split("=");
               if(pair[0] == variable){return pair[1];}
       }
       return(false);
}
function check_state() {
	var state = getQueryVariable("state");
	if(state) {
		$('#state').css('visibility', "visible");
	} else {

	}
}
check_state();
</script>
</html>