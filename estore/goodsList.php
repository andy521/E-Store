<?php include 'check.class.php';
session_start();
$text="上衣' or `pro_type`='裤子' or `pro_type`='衬衫' or `pro_type`='棉袄";
$db = new PDO("mysql:host=localhost;dbname=xiangmu","root","");
$db->exec("set names utf8");
if(isset($_GET['action']) && $_GET['action']=='loginout' && isset($_COOKIE['username']))
{
		setcookie("username","",time()-3600);
		setcookie("userid","",time()-3600);
		header("location: goodsList.php");
}
else if(isset($_POST['action']) && $_POST['action']=='index')
{
	$check=new Check();
	if(isset($_POST['username']))
	{
		$username=$_POST['username'];
		$password=$_POST['password'];
		$checkcode=$_POST['checkCode'];
	}
	if(!empty($username) && $check->checkname($username)==1)
	{
		$obj=$db->query("select id from userinfo where name='{$username}'");
		$id=$obj->fetchALL(PDO::FETCH_ASSOC);
		if(!empty($id[0]["id"]))
		{
			$check_name="yes";
		}
		else
		{
			$check_name="no";
		}
	}
	else
	{
		$check_name="no";
	}
	if(!empty($password) && $check->checkpsd($password)==1)
	{
		$obj1=$db->query("select password from userinfo where id='{$id[0]["id"]}'");
		$pwd=$obj1->fetchALL(PDO::FETCH_ASSOC);
		if($password==$pwd[0]["password"])
		{
			$check_pwd="yes";
		}
		else
		{
			$check_pwd="no";
		}
	}
	else
	{
		$check_pwd="no";
	}
	if(!empty($checkcode) && strtolower($checkcode)==strtolower($_SESSION['sysck']))
	{
		$check_code="yes";
	}
	else
	{
		$check_code="no";
	}
	if(isset($check_name) && isset($check_pwd) && isset($check_code) && $check_name=="yes" && $check_pwd=="yes" && $check_code=="yes")
	{
		setcookie("username",$username,time()+3600);
		setcookie("userid",$id[0]["id"],time()+3600);
		header("location: goodsList.php");
	}
}
else if(isset($_GET['action']) && $_GET['action']=='cart')
{
	switch($_GET['cateid'])
	{
		case 1:
		{
			$text="上衣' or `pro_type`='裤子' or `pro_type`='衬衫' or `pro_type`='棉袄";
			break;
		}
		case 2:
		{
			$text='上衣';
			break;
		}
		case 3:
		{
			$text='裤子';
			break;
		}
		case 4:
		{
			$text='衬衫';
			break;
		}
		case 5:
		{
			$text='棉袄';
			break;
		}
		case 6:
		{
			$text='帽子';
			break;
		}
		case 7:
		{
			$text="女士包包' or `pro_type`='旅行包";
			break;
		}
		case 8:
		{
			$text='女士包包';
			break;
		}
		case 9:
		{
			$text='旅行包';
			break;
		}
		case 10:
		{
			$text='鞋子';
			break;
		}
	}
}
else if(isset($_GET['action']) && $_GET['action']=='buy')
{
	$obj4=$db->query("SELECT `pro_quantity` FROM `products` WHERE `pro_id`='{$_GET['pro_id']}'");
	$nowcount=$obj4->fetchALL(PDO::FETCH_ASSOC);
	$realcount=0;
	if($_GET['buycount']>$nowcount[0]["pro_quantity"])
	{
		$realcount=$nowcount[0]["pro_quantity"];
	}
	else if($_GET['buycount']<=0)
	{
		$realcount=0;
	}
	else
	{
		$realcount=$_GET['buycount'];
	}
	//print_r($nowcount);
	$obj3=$db->exec("INSERT INTO `xiangmu`.`car` (`use_id`, `pro_id`, `buynum`) VALUES ('{$_COOKIE["userid"]}', '{$_GET["pro_id"]}', '{$realcount}');");
	//$obj3->fetchALL(PDO::FETCH_ASSOC);
	$kucun=$nowcount[0]["pro_quantity"] -$realcount;
	//print_r($kucun);
	$obj5=$db->query("update products set pro_quantity ={$kucun} where 'pro_id'='{$_GET["pro_id"]}'");
	//$obj5->fetchALL(PDO::FETCH_ASSOC);

}
$obj2=$db->query("SELECT * FROM `products` WHERE `pro_type`='{$text}'");
$pro=$obj2->fetchALL(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="css/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/functions.js"></script>
<script type="text/javascript">function disp_alert()
{
alert("购买成功！！")

}</script>
<title>我的商店-首页</title>
</head>
<body>
<div id="container">
		<div id="header">
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
				<a href="register.html">注册</a>
				<a href="cart.php?act=show">购物车</a>
				<a href="#">结帐中心</a>
				<a href="#">用户管理</a>

				<a href="admin/login.php?act=login">后台管理</a>
				<a href="index.php?action=zhuxiao">注销</a>
			</div>
		</div>
		<div id="nav">
			<ul>
				<li>
					<span><a href="index.php">首页</a></span>
				</li>
				<li>
					<span><a href="">用户中心</a></span>
				</li>
				<li>
					<span><a href="">后台管理</a></span>
				</li>
			</ul>
		</div>
		<div id="wrapper">
				<div id="sidebar">
					<div class="category">
						<?php if(!isset($_COOKIE['username']))
						{?>
					<div>
							<div class="box_title">会员登录</div>
							<div class="box_list">
								<form action="goodsList.php" method="post">
									<div class="login">
										<ul>
											<li>
												<label>账号</label>
												<input name="username" id="useraccount" type="text">
												<?php if(isset($check_name) && $check_name=="yes"){?><span style="color:green">√</span><?php }else if(isset($check_name) && $check_name=="no"){?>
												<span style="color:red">×</span><?php } ?>
											</li>

											<li>
												<label>密码</label>
												<input name="action" type="hidden" value="index">
												<input name="password" id="userpwd" type="password"><?php if(isset($check_pwd) && $check_pwd=="yes"){?><span style="color:green">√</span><?php }else if(isset($check_pwd) && $check_pwd=="no"){?><span style="color:red">×</span><?php } ?>
											</li>
											<li>
												<label>验证码	</label><?php if(isset($check_code) && $check_code=="yes"){?><span style="color:green">√</span><?php }else if(isset($check_code) && $check_code=="no"){?><span style="color:red">×</span><?php } ?>
												<input name="checkCode" id="code" type="text" style="width:50px">
												<img src="checkCode.php" onclick="this.src='randCode.php?code='+Math.random()" alt="2312" style="cursor:pointer">

											</li>
											<li class="formbt">
												<input type="submit" value="登录" class="bt">
												<input type="button" value="注册" class="bt" onclick='javascript:window.location.href="register.html"'>
											</li>
										</ul>
									</div>
								</form>
							</div>

							<div class="box_bottom"><img src="images//0.gif" width="10" height="1" alt=""/></div>
						</div>
						<?php }?>
						<div class="box_title">商品分类</div>
							<div class="box_list">
								<dl>
									<dt> <a href="goodsList.php?action=cart&cateid=1">『衣服』</a> </dt>
									<dd> |__ <a href="goodsList.php?action=cart&cateid=2">上衣</a> </dd>
									<dd> |__ <a href="goodsList.php?action=cart&cateid=3">裤子</a> </dd>
									<dd> |__ <a href="goodsList.php?action=cart&cateid=4">衬衫</a> </dd>
									<dd> |__ <a href="goodsList.php?action=cart&cateid=5">棉袄</a> </dd>
									<dt> <a href="goodsList.php?action=cart&cateid=6">『帽子』</a> </dt>
									<dt> <a href="goodsList.php?action=cart&cateid=7">『箱包』</a> </dt>
									<dd> |__ <a href="goodsList.php?action=cart&cateid=8">女土包包</a> </dd>
									<dd> |__ <a href="goodsList.php?action=cart&cateid=9">旅行包</a> </dd>
									<dt> <a href="goodsList.php?action=cart&cateid=10">『鞋子』</a> </dt>
								</dl>
							</div>
							<div class="box_bottom"><img src="images//0.gif" width="10" height="1" alt=""/></div>
						</div>
				</div>
				<div id="content">
				<div class="glist">
					<ul>
		
						<?php for($i=0;$i<count($pro);$i++)
						{?>
								<li>
									<form action="goodsList.php" method="get">
										<a href='goodDetail.php?pro_id=<?php echo $pro[$i]["pro_id"];?>'>
											<img src='<?php echo $pro[$i]["pro_img"];?>' width="150" height="150" /></a>
										<p>【<?php echo $pro[$i]["pro_name"];?>】</p>
										<p>市场价格￥<label class="quchu"><?php echo $pro[$i]["pro_oldprice"];?></label></p>
										<p class="red"><?php echo $pro[$i]["pro_newprice"];?></p>
										<p>库存量：<?php echo $pro[$i]["pro_quantity"];?></p>
										<p>购买数量：<input type="text" value="1" class="goodcount" name="buycount" id="6"/></p>
										<?php if(isset($_COOKIE['username']))
										{?>
											<input type="submit" onclick="disp_alert()" class="bt" value="购买">
										<?php } ?>
										<input type="hidden" name="action" value="buy">
										<input type="hidden" name="pro_id" value='<?php echo $pro[$i]["pro_id"];?>'>
									</form>
								</li>
						<?php }?>
						
					</ul>
				</div>
			</div>
		</div>
		<div id="footer">
				<label>『Right By Christy Lan』</label>
		</div>
</div>
</body>
</html>
