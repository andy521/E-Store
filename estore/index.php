<?php
header("content-type:text/html;charset=utf-8");
$db = new PDO("mysql:host=localhost;dbname=xiangmu",
    "root","");
$db->exec("set names utf8");
include 'check.class.php';
session_start();

//接收数据

$flag=false;

if(isset($_POST['username']))
{

    $username=$_POST['username'];
    $password=$_POST['password'];
    $checkcode=$_POST['checkCode'];
    $check=new Check();

    //用户名，密码，验证码检查
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


//注销
if(isset($_GET['action'])&& $_GET['action']=="zhuxiao"&&isset($_COOKIE['username']))
{
    unset($_COOKIE['username']);
    unset($_COOKIE['userid']);
    //header("location:index.php");
}

//右下角显示

$obj2=$db->query("select pro_id,pro_img,pro_name,pro_oldprice,pro_newprice from products");
$products=$obj2->fetchAll(PDO::FETCH_ASSOC);
//print_r($products);


//大分类
$obj3=$db->query("select type_id,type from type");
$B_type=$obj3->fetchAll(PDO::FETCH_ASSOC);


//小分类
$obj4=$db->query("select distinct type.type_id,pro_type,type from type,products where type.type_id=products.type_id");
$S_type=$obj4->fetchAll(PDO::FETCH_ASSOC);
//print_r($products[0]['id']);



?>


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <title>我的商店-首页</title>
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
                <span>
                    <a href="index.php">首页</a>
			    </span>
            </li>
            <li>
                <span>
                    <a href="">用户中心</a>
                </span>
            </li>
            <li>
                <span>
                    <a href="">后台管理</a>
                </span>
            </li>
        </ul>
    </div>
    <div id="wrapper">
        <div id="sidebar">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <?php
            if(!isset($_COOKIE["username"]))
            {?>
                <div>
                    <div class="box_title">会员登录</div>
                    <div class="box_list">
                        <form action="index.php" method="post">
                            <div class="login">
                                <ul>
                                    <li>
                                        <label>账号</label>
                                        <input name="username" id="useraccount" type="text">
                                        <?php if(isset($check_name)&& $check_name=="yes")
                                        {?>
                                            <span style="color: green;">√</span>
                                        <?php }
                                        else if(isset($check_name)&& $check_name=="no")
                                        {?>
                                            <span style="color: red;">X</span>
                                        <?php }?>
                                    </li>

                                    <li>
                                        <label>密码</label>
                                        <input name="password" id="userpwd" type="password">
                                        <?php if(isset($check_pwd)&& $check_pwd=="yes")
                                        {?>
                                            <span style="color: green;">√</span>
                                        <?php }
                                        else if(isset($check_pwd)&& $check_pwd=="no")
                                        {?>
                                            <span style="color: red;">X</span>
                                        <?php }?>
                                    </li>

                                    <li>
                                        <label>验证码	</label>
                                        <input name="checkCode" id="code" type="text" style="width:50px">
                                        <img src="checkCode.php" onclick="this.src='checkCode.php?code='+Math.random()" alt="2312" style="cursor:pointer">
                                        <?php if(isset($check_code)&& $check_code=="yes")
                                        {?>
                                            <span style="color: green;">√</span>
                                        <?php }
                                        else if(isset($check_code)&& $check_code=="no")
                                        {?>
                                            <span style="color: red;">X</span>
                                        <?php }?>
                                    </li>

                                    <li class="formbt">
                                        <input type="submit" value="登录" class="bt">
                                        <input type="button" value="注册" class="bt" onclick='javascript:window.location.href="register.html"'>
                                    </li>
                                </ul>
                            </div>
                            <input name="act" type="hidden" value="checkLogin">
                        </form>
                    </div>
                    <div class="box_bottom">
                        <img src="images//0.gif" width="10" height="1" alt=""/>
                    </div>
                </div>
            <?php }?>
            <div style="margin-top:10px;"></div>

            <div class="category">
                <div class="box_title">商品分类</div>
                <div class="box_list">
                    <dl>
                        <!--左侧分类-->
                        <?php foreach ($B_type as $v)
                        {?>
                            <dt>
                                <a href="goodsList.php?act=listgoods&cateid=<?php echo $v['type_id'];?>"><?php echo $v['type'];?></a>
                            </dt>
                            <?php foreach ($S_type as $v1)
                            {?>
                                <?php if ($v1['type_id']==$v['type_id'])
                                {?>
                                    <dd>
                                        |__ <a href="goodsList.php?act=listgoods&cateid=<?php echo $v1['type_id']?>"><?php  echo $v1['pro_type']?></a>
                                    </dd>

                                <?php }?>
                            <?php } ?>
                        <?php } ?>
                    </dl>

                       <!-- <dl>
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
                        -->
                </div>
                <div class="box_bottom"><img src="images//0.gif" width="10" height="1" alt=""/></div>
            </div>
        </div>

        <div id="content">
            <div id="showgoods">
                <h4>店主推荐</h4>

                <!--右侧商品展示-->
                <div class="goodslist">
                    <ul>
                        <?php for($i=5;$i<8;$i++)
                        {?>
                            <li>
                                <a href="goodDetail.php?act=detailedgood&gid=<?php  echo $products[$i]['pro_id']?>">
                                    <img src="<?php  echo $products[$i]['pro_img']?>" width="125" height="120" />
                                </a>
                                <span><?php echo $products[$i]['pro_name']?></span>
                                <div class="price">￥<?php echo $products[$i]['pro_newprice']?></div>
                            </li>
                        <?php } ?>

                        <!--  <li>
                            <a href="goodDetail2.php">
                                <img src="images//goodsimg/200901080150574.jpg" width="125" height="120" />
                            </a>
                            <span>【真皮白包】</span>
                            <div class="price">￥12</div>
                        </li>


                        <li>
                            <a href="goodDetail3.php">
                                <img src="images//goodsimg/2009010802031178.jpg" width="125" height="120" />
                            </a>
                            <span>【运动鞋】</span>
                            <div class="price">￥10</div>
                        </li>
                    -->
                    </ul>
                </div>
            </div>
            <div id="buylist">
                <h5>☆最新商品<a href="goodsList.php?act=listgoods&cateid=0">.o0更多</a></h5>
                <div class="newgoods">
                    <ul>
                        <?php for($i=6;$i<12;$i++)
                        {?>
                            <li>
                                <a href="goodDetail.php?act=detailedgood&gid=<?php  echo $products[$i]['pro_id']?>">
                                    <img src="<?php  echo $products[$i]['pro_img']?>" width="150" height="150" />
                                </a>
                                <p><?php echo $products[$i]['pro_name']?></p>
                                <p>市场价格￥<label class="quchu"><?php echo $products[$i]['pro_oldprice']?></label></p>
                                <p class="red">本店价格￥<?php echo $products[$i]['pro_newprice']?></p>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div id="footer">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <label>『 Right By Will Jack 』</label>
    </div>

</div>
</body>
</html>