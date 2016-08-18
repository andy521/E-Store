<?php
	/*
	 * 制作验证码流程：
	 * 1.声明文件类型为图片
	 * 2.创建图片
	 * 3.图颜色之前需要资源
	 * 4.涂颜色
	 * 5.写字/写单个字符
	 * 6.输出图片
	 */

	//1.声明文件类型为图片
	header("content-type:image/png");
	
	//2.新建一个基于调色板的图像
	/*
	 * 说明
	 * resource imagecreate(int $x_size,int $y_size)
	 * 返回一个图像标识符
	 * 代表了一幅大小为x_size和 y_size的空白图像。
	 */
	$img = imagecreate(80,30);
	//3.为一幅图像分配颜色
	/*
	 * 说明
	 * int imagecolorallocate(resource $image,int $red,int $green,int $blue)
	 * 返回一个标识符，代表了由给定的 RGB 成分组成的颜色。
	 * red，green 和 blue 分别是所需要的颜色的红，绿，蓝成分。
	 * 这些参数是 0 到 255 的整数或者十六进制的 0x00 到 0xFF。
	 */
	//给基于调色板的图像填充背景色
	$color = imagecolorallocate($img, rand(0,255), rand(0,255),rand(0,255));
	//4.区域填充
	/*
	 * 说明
	 * bool imagefill(resource $image,int $x,int $y,int $color)
	 * 在 image 图像的坐标 x，y（图像左上角为 0, 0）处
	 * 用 color 颜色执行区域填充
	 * （即与 x, y 点颜色相同且相邻的点都会被填充）。 
	 */ 
	imagefill($img, 0, 0, $color);
	$string="0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
	
	$sysck = "";
	for($i=0;$i<4;$i++){
		$char = $string{rand(0,61)};
		$sysck .= $char;
		//给字母分配颜色
		$char_color=imagecolorallocate($img,rand(0,255),rand(0,255),rand(0,255));
		
		//5.水平地画一个字符
		/*
		 * 说明
		 * bool imagechar(resource $image,int $font,int $x,int $y,string $c,int $color)
		 * 将字符串 c 的第一个字符画在 image 指定的图像中，
		 * 其左上角位于 x，y（图像左上角为 0, 0），
		 * 颜色为 color。
		 * 如果 font 是 1，2，3，4 或 5，
		 * 则使用内置的字体（更大的数字对应于更大的字体）。
		 */
		imagechar($img, 5, ($i+1)*15, rand(0,18), $char, $char_color);
	}
	
	session_start();
	$_SESSION['sysck'] = $sysck;
	
	
	
	//6.以 PNG 格式将图像输出到浏览器或文件
	imagepng($img);
?>
