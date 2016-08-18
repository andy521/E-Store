<?php
	/*
	 * 判断是否登录
	 * 未登录，跳转到首页index.php
	 */
	if (!isset($_COOKIE['userid']) || empty($_COOKIE['userid']))
	{
		//未登录
		header("Location:index.php");
	}

	header("content-type:text/html;charset=utf-8");
	$db = new PDO("mysql:host=localhost;dbname=xiangmu","root","");
	$db->exec("set names utf8");
	
	//加入
	if(isset($_GET['act']) && $_GET['act']=='add')
	{
		//1.接收数据
		$pro_id = $_GET['pro_id'];
		$user_id = $_COOKIE['userid'];
		if(isset($_GET['buynum']))
		{
			$buynum = $_GET['buynum'];
		}
		else
		{
			$buynum = 1;
		}

		//2.判断是否是第一次加入购物车
		$sql = "select use_id,pro_id,car_id,buynum from car where use_id={$user_id} and pro_id={$pro_id}";
		$result = $db->query($sql);
		$twoarr = $result->fetchAll(PDO::FETCH_ASSOC);
		if(!empty($twoarr))
		{
			//重复购买
			$buynum = $buynum + $twoarr[0]['buynum'];
		}

		//3.是否超过库存量
		$sql2 = "select pro_quantity from products where pro_id={$pro_id}";
		$result2 = $db->query($sql2);
		$twoarr2 = $result2->fetchAll(PDO::FETCH_ASSOC);
		if($buynum > $twoarr2[0]['pro_quantity'] )
		{
			$buynum = $twoarr2[0]['pro_quantity'] ;
		}

		//加入数据库中
		if(empty($twoarr))
		{
			//第一次
			$sql3 = "insert into car(use_id,pro_id,buynum) values({$user_id},{$pro_id},{$buynum})";
		}
		else
		{
			//重复购买
			$sql3 = "update car set buynum={$buynum} where use_id={$user_id} and pro_id={$pro_id}";
		}
		$number = $db->exec($sql3);
		//echo $number;
	}

	//删除
	if(isset($_GET['act']) && $_GET['act']=='delete')
	{
		$car_id = $_GET['car_id'];
		$user_id = $_COOKIE['userid'];
		$sql5 = "delete from car where car_id={$car_id} and use_id={$user_id}";
		$db->exec($sql5);
	}

	//更新
	if (isset($_POST['act']) && $_POST['act']=='update')
	{
		$user_id = $_COOKIE['userid'];
		foreach ($_POST as $key=>$value)
		{
			if ($key=='act')
			{
				continue;
			}
			//判断库存量
			$sql = "select pro_quantity from products where pro_id='{$key}'";
			$result5 = $db->query($sql);
			$twoarr5 = $result5->fetchAll(PDO::FETCH_ASSOC);
			if ($value>$twoarr5[0]['pro_quantity'])
			{
				$value = $twoarr5[0]['pro_quantity'];
			}

			//更新购物车表
			$sql = "update car set buynum = {$value} where pro_id = {$key} and use_id = {$user_id}";
			$db->exec($sql);
		}
	}


	//清空。。。
	if (isset($_GET['act']) && $_GET['act']=='rmall')
	{
		$sql6 = "delete from car where use_id ={$_COOKIE['userid']}";
		$db->exec($sql6);
	}
	
	//测试显示购物车
	$user_id = $_COOKIE['userid'];
	$sql4 = "select * from car,products where car.use_id={$user_id} and car.pro_id=products.pro_id";
	$result4 = $db->query($sql4);
	$twoarr4 = $result4->fetchAll(PDO::FETCH_ASSOC);
//	print_r($twoarr4);


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="css/style.css" rel="stylesheet" type="text/css">
<title>购物车</title>
</head>
<body>
	<div id="container">
		<div id="header">
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<div id="nav_user">
				<span style="color:yellow">
				【 你好,<?php echo $_COOKIE['username']; ?>】
				</span>
				<a href="register.php">注册</a>
				<a href="cart.php?act=show">购物车</a>
				<a href="#">结帐中心</a>
				<a href="#">用户管理</a>
			
				<a href="admin/login.php?act=login">后台管理</a>
				<a href="index.php">注销</a>
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
					<div id="cart">
						<form method="post" action="cart.php">
							<table width="530" border="1" cellpadding="0" cellspacing="3">
								<tr>
									<th scope="col">商品ID</th>
									<th scope="col">商品名称</th>
									<th scope="col">商品图片</th>
									<th scope="col">商品单价</th>
									<th scope="col">市场价格</th>
									<th scope="col">商品数量</th>
									<th scope="col">库存量</th>
									<th scope="col">小计</th>
									<th scope="col">操作</th>
								</tr>
								<?php $sumnewprice=0; $sumoldprice=0;?>
								<?php foreach ($twoarr4 as $goodone) {?>
									<tr>
										<td><?php echo $goodone['pro_id'];?></td>
										<td>【<?php echo $goodone['pro_name'];?>】</td>
										<td><img src="<?php echo $goodone['pro_img'];?>" width="50" height="50"></td>
										<td>￥<?php echo $goodone['pro_newprice'];?></td>
										<td>￥<?php echo $goodone['pro_oldprice'];?></td>
										<td><input type="text" name="<?php echo $goodone['pro_id'];?>" value="<?php echo $goodone['buynum'];?>"></td>
										<td><?php echo $goodone['pro_quantity'];?></td>
										<td>￥<?php $onenewprice = $goodone['buynum']*$goodone['pro_newprice'];
											echo $onenewprice;
											$sumnewprice = $sumnewprice + $onenewprice;
											$sumoldprice = $sumoldprice + $goodone['buynum']*$goodone['pro_oldprice'];
											?>
										</td>
										<td>
											<a href="cart.php?act=delete&car_id=<?php echo $goodone['car_id'];?>">删除</a>
										</td>
									</tr>
								<?php }?>
							</table>
							<table border="0">
								<tr>
									<td>总金额：￥<?php echo $sumnewprice;?>元，市场价总额：￥<?php echo $sumoldprice;?>元，节省了<?php echo $sumoldprice - $sumnewprice;?>元</td>
									<td align="right">
										<span>
											<input type="button" value="清空" onclick="javasript:location.href='cart.php?act=rmall'">
											<input type="submit" value="更新">
											<input type="hidden" name="act" value="update">
										</span>
									</td>
								</tr>
							</table>
						</form>
						<div>
							<span class="continue"><a href="index.php" >继续购买</a></span>
							<span class="checkout"><a href="order.php?act=writeOrder" >结算中心</a></span>
							<div class="clear"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="footer">
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<label>『 Right By Christy Lan 』</label>
		</div>
	</div>
</body>
</html>