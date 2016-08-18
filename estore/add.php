<?php
header("content-type:text/html;charset=utf-8");
	$db = new PDO("mysql:host=localhost;dbname=xiangmu",
			"root","");
	$db->exec("set names utf-8");
	include 'check.class.php';
	session_start();
	 if(!isset($_COOKIE['userid'])){
	 	
	 	echo "<script> alert('请先登陆!'); parent.location.href='index.php';</script>";
	 }else{
	 $pro_id=$_GET['pro_id'];
	 $obj3=$db->exec("INSERT INTO car (use_id, pro_id, buynum) VALUES ('{$_COOKIE["userid"]}', '{$_GET["pro_id"]}', 1);");
	 //$op=$obj3->fetchALL(PDO::FETCH_ASSOC);
	 $obj5=$db->exec("select pro_quantity where pro_id='{$_GET["pro_id"]}'");
	/* $count2=$obj5->fetchAll(PDO::FETCH_ASSOC);
	 $count=$count2[0]['pro_quantity']-1;
	 $obj4=$db->exec("update products set pro_quantity ='pro_quantity-1' where pro_id='{$_GET["pro_id"]}'");*/
	 echo "  <script> alert('加入购物车成功!'); parent.location.href='cart.php';</script> " ;
	 //echo "<script> alert('加入购物车成功！')</script>";
	 }
	 
?>