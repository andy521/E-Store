<?php
header("Content-Type:text/html; charset=utf-8");
$db=new PDO("mysql:host=localhost;dbname=xiangmu","root","");
$db->exec("set names utf-8");

if(isset($_POST['username'])){
	$username=$_POST['username'];
	$useraddr=$_POST['useraddr'];
	$userphone=$_POST['userphone'];
	$userid=$_COOKIE['userid'];
	$email=$_POST['email'];
	
	//var_dump($_POST);
	$sql=$db->exec("insert into orderinfo (use_id,ord_name,ord_address,ord_phone,ord_email) 
			values($userid,'{$username}','{$useraddr}','{$userphone}','{$email}')");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="css/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/utils.js"></script>
<script type="text/javascript" src="js/validator.js"></script>
<title>填写配送地址</title>
</head>
<body>
	<div id="container">
		<div id="header">
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<div id="nav_user">
				<span style="color:yellow">
					<?php
						if(isset($_COOKIE['username']))
						{?>
							<span>您好:<?php echo $_COOKIE["username"];?></span>
						<?php }
						else
						{?>
							<a href="index.php">你还没登录！</a>
						<?php }?>
				</span>
				<a href="register.php">注册</a>
				<a href="cart.php?act=show">购物车</a>
				<a href="#">结帐中心</a>
				<a href="#">用户管理</a>
			
				<a href="admin/login.php?act=login">后台管理</a>
				<a href="logout.php">注销</a>
			</div>		
		</div>
		<div id="nav">
			<ul>
				<li><span><a href="index.php">首页</a></span></li>
				<li><span><a href="">用户中心</a></span></li>
				<li><span><a href="">后台管理</a></span></li>
			</ul>
		</div>
		<div id="wrapper">
			<div id="contentCenter">
				<div id="register">
					<div class="reg_title">填写送货地址</div>
					<div class="reg_body">
						<form action="order.php" method="post" onsubmit="return Validator.checkSubmit(this)">
							<div class="fm_item">
								<label>* 收货人姓名：</label>
								<input type="text" name="username" mode="require" id="username">
								<label id="ckusername" class="forminfo">请输入收货人姓名!</label>
							</div>
							<div class="fm_item">
								<label>* 收货人地址：</label>
								<input type="text" name="useraddr" mode="require" id="address">
								<label id="ckaddress" class="forminfo">请输入收货人地址!</label>
							</div>
							<div class="fm_item">
								<label>* 电话：</label>
								<input type="text" name="userphone" mode="require" id="phone">
								<label id="ckphone" class="forminfo">请输入收货人电话!</label>
							</div>
							<div class="fm_item">
								<label>邮箱：</label>
								<input type="text" name="email">
							</div>
							<div class="fm_btn">
								<input type="submit" value="确认" class="btn">
								<input type="reset" value="重填" class="btn">
							</div>
							<input type="hidden" value="addorder" name="act">
						</form>
					</div>
				</div>
			</div>
		</div>
		<div id="footer"><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<label>『 Right By Christy Lan 』</label>
		</div>
	</div>
</body>
</html>
