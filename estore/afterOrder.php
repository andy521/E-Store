<?php
header("content-type:text/html;charset=utf-8");
$db = new PDO("mysql:host=localhost;dbname=xiangmu",
		"root","");
$db->exec("set names utf8");
if (isset($_COOKIE['username'])){
$sql = "select * from car, products where use_id={$_COOKIE['userid']} and car.pro_id=products.pro_id";
$result = $db->query($sql);
$twoarr = $result->fetchAll(PDO::FETCH_ASSOC);
if (empty($twoarr)){
	echo"<script>alert('购物车没有东西');parent.location.href='index.php';</script>";
}
$sql2 = "select * from orderinfo where use_id={$_COOKIE['userid']}";
$result2 = $db->query($sql2);
$twoarr2 = $result2->fetchAll(PDO::FETCH_ASSOC);
$num=0;
$new_price=0;
$old_price=0;
}
else {
	echo"<script>alert('未登入');parent.location.href='index.php';</script>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="css/style.css" rel="stylesheet" type="text/css">
<title>订单</title>
</head>
<body>
	<div id="container">
		<div id="header">
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<div id="nav_user">
	<span style="color:yellow">
		<?php if(isset($_COOKIE['username']))
		{?>
		<span>您好!<?php echo $_COOKIE['username'];?></span>
  <?php } 
		else
		{ ?>
		<a href="login.php">你还没登录！</a>
  <?php }?>
		</span>
	<a href="register.php">注册</a>
	<a href="cart.php?act=show">购物车</a>
	<a href="#">结帐中心</a>
	<a href="#">用户管理</a>

	<a href="admin/login.php?act=login">后台管理</a>
	<a href="logout.php">注销</a>
</div>		</div>
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
					<div class="reg_title">购物订单</div>
					<div class="reg_body">
					<table id="buylist" border="0" cellpadding="0" cellspacing="3">
						<tr>
							<th scope="col">商品ID</th>
							<th scope="col">商品名称</th>
							<th scope="col">商品图片</th>
							<th scope="col">商品单价</th>
							<th scope="col">市场价格</th>
							<th scope="col">购买数量</th>
							<th scope="col">商品数量</th>
							<th scope="col">小计</th>
						</tr>
							<?php while (!empty($twoarr[$num])){?>
						<tr>
							<td><?php echo $twoarr[$num]['pro_id']; ?></td>
							<td><?php echo $twoarr[$num]['pro_name']; ?></td>
							<td><img width="50px" height="50px"src="<?php echo $twoarr[$num]['pro_img']; ?>"/></td>
							<td><?php echo $twoarr[$num]['pro_newprice']; ?></td>
							<td><?php echo $twoarr[$num]['pro_oldprice']; ?></td>
							<td><?php echo $twoarr[$num]['buynum']; ?></td>
							<td><?php echo $twoarr[$num]['pro_quantity']; ?></td>
							<td><?php echo $twoarr[$num]['pro_newprice']; ?></td>
						</tr>
						<?php $new_price +=$twoarr[$num]['pro_newprice'];
						$old_price +=$twoarr[$num]['pro_oldprice'];
						$num++;
						?>
						<?php }?>
										</table>
					<table border="0">
						<tr>
							<td>购物总金额<?php echo $new_price; ?>，
						市场价总额：<?php echo $old_price; ?>，
							节省了<?php echo $old_price-$new_price; ?>元</td>
							<td align="right">
								<span><input type="button" value="修改" onclick='javascript:window.location.href="cart.php?act=show"' class="modifyBtn"/></span>
							</td>
						</tr>
					</table>
					<div id="orderuser">
						<h4>订单人的信息</h4>
					    <ul>
					        <li>收货人姓名:<label><?php echo $twoarr2[0]['ord_name']; ?></label></li>
					        <li>收货人地址:<label><?php echo $twoarr2[0]['ord_address']; ?></label></li>
					        <li>收货人电话:<label><label><?php echo $twoarr2[0]['ord_phone']; ?></label></li>
					        <li>收货人邮箱:<label><label><?php echo $twoarr2[0]['ord_email']; ?></label></li>
					    </ul>
						<div class="clear"></div>
					</div>
					<form action="order1.php" method="post">
						<input type="submit" value="提交订单" class="orderBtn">
						<input type="hidden" name="act" value="submitOrder">
						<input type="hidden" name="oid" value="32">
					</form>
					</div>
				</div>
			</div>
		</div>
		<div id="footer"><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<label>『 Right By Xu Guangxiang 』</label></div>
	</div>
</body>
</html>

