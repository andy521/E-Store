<?php 
	header("content-type:text/html;charset=utf-8");
	$db = new PDO("mysql:host=localhost;dbname=xiangmu",
			"root","");
	$db->exec("set names utf8");
	include 'check.class.php';
	session_start();
	
	//接收数据
	
	if(isset($_POST['username']))
	{

		$username=$_POST['username'];
		$password=$_POST['password'];
		$checkcode=$_POST['checkCode'];
		$check=new Check();
		if(!empty($username)&& $check->checkname($username)==1)
		{
			$obj=$db->query("select id from userinfo where name='{$username}'");
			$id=$obj->fetchAll(PDO::FETCH_ASSOC);
			//print_r($id);
			if (!empty($id[0]["id"]))
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
			$pwd=$obj1->fetchAll(PDO::FETCH_ASSOC);
			//print_r($password);
			if($password==$pwd[0]["password"])
			{
			$check_pwd="yes";}
			else
			{
				$check_pwd="no";
			}
		}
		else
		{
			$check_pwd="no";
		}
		
		if(!empty($checkcode)&& strtolower($checkcode)==strtolower($_SESSION['sysck']))
		{
			$check_code="yes";
		}
		else
		{
			$check_code="no";
		}
		if(isset($check_name)&&isset($check_code)&&isset($check_pwd)&&$check_name=="yes"&&$check_code=="yes"&&$check_pwd=="yes")
		{
			setcookie("username",$username,time()+3600);
			setcookie("userid",$id[0]["id"],time()+3600);
			$flag=true;
			header("location:index.php");
		}
	}
	
	if(isset($_GET['action'])&& $_GET['action']=="zhuxiao"&&isset($_COOKIE['username']))
	{
		unset($_COOKIE['username']);
		unset($_COOKIE['userid']);
		//header("location:index.php");
	}
	
	//右下角显示
	
	$obj2=$db->query("select pro_id,pro_img,pro_name,pro_oldprice,pro_newprice,pro_quantity,pro_liuyan,pro_date,pro_text,click from products where pro_id='{$_GET['gid']}'");
	$pro=$obj2->fetchAll(PDO::FETCH_ASSOC);
	//print_r($pro);
	//大分类

	$obj3=$db->query("select type_id,type from type");
	$B_type=$obj3->fetchAll(PDO::FETCH_ASSOC);



	//小分类
	$obj4=$db->query("select distinct type.type_id,pro_type,type from type,products where type.type_id=products.type_id");
	$S_type=$obj4->fetchAll(PDO::FETCH_ASSOC);


	//添加点击数
	$obj5=$db->query("select click from products where pro_id='{$_GET['gid']}'");
	$count=$obj5->fetchAll(PDO::FETCH_ASSOC);
	$flag=$count[0]['click']+1;
	$obj6=$db->query("update products set click ='{$flag}' where pro_id='{$_GET['gid']}'");
	//$S_type=$obj4->fetchAll(PDO::FETCH_ASSOC);
	//print_r($_GET['gid']);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="css/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/functions.js"></script>

<title>商品详细信息</title>

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
				<a href="register.php">注册</a>
				<a href="cart.php">购物车</a>
				<a href="#">结帐中心</a>
				<a href="#">用户管理</a>
				<a href="admin/login.php?act=login">后台管理</a>
				<a href="index.php?action=zhuxiao">注销</a>
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
			<div id="sidebar">
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<div style="margin-top:10px;"></div>
					<div class="category">
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
						<div id="goodinfo">
							<div class="gdImg">
								<form action="goodDetail.php">
									<a href="#"><img src=" <?php echo $pro[0]['pro_img']  ?>" width="250" height="250"></a>
									<span class="buy">
										<a href='add.php?action=add&pro_id=<?php echo $pro[0]['pro_id']?>'>
										</a>
									</span>
									<span class="fav">
										<a href="#">收藏</a>
									</span>
									<div class="clear"></div>
								</form>
							</div>
							<div class="gdInfo">
								<h4><?php echo $pro[0]['pro_name']?></h4>
								<p>本店价格：<label style="color:red">￥<?php echo $pro[0]['pro_newprice']?></label></p>
								<p>市场价格：<label style="color:red">￥<?php echo $pro[0]['pro_oldprice']?></label></p>
								<p>商品数量：<?php echo $pro[0]['pro_quantity']?></p>
								<p>上架时间：<?php echo $pro[0]['pro_date']?></p>
								<p>商品点击数：<?php echo $pro[0]['click']?></p>
							</div>
							<div class="clear"></div>
						</div>
						<div id="goodtab1">
							<ul>
								<li class="tab1">商品介绍</li>
								<li class="tab2" onclick="changeTab('goodtab1','goodtab2',2)">留言信息</li>
							</ul>
							<div class="tabcontent">
								<p><?php echo $pro[0]['pro_text']?></p>
							</div>
						</div>
						<div id="goodtab2">
							<ul>
								<li class="tab2" onclick="changeTab('goodtab1','goodtab2',1)">商品介绍</li>
								<li class="tab1">留言信息</li>
							</ul>
							<div class="tabcontent">
							<?php echo $pro[0]['pro_liuyan']?>
							<!-- 留言 -->
							</div>
						</div>
					</div>
		</div>
		<div id="footer"><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<label>『 Right By 7Plus 』</label>
		</div>
	</div>
</body>
</html>