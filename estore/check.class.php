<?php
	abstract class Checkson{
		abstract function checkname($name);
		abstract function checkpsd($psd);
	}
	
	
	class Check extends Checkson{
		function checkname($str){
			$pattern="/^[a-z]\w{4,29}$/i";//����ĸ��ͷ����������ĸ�»�����ɣ�5~30λ��
			$result=preg_match($pattern, $str);
			return $result;
		}
		
		function checkpsd($str){
			$pattern="/^\w{6,}$/i";//���ַ���ɣ�6λ���ϣ�
			$result=preg_match($pattern, $str);
			return $result;
		}
	}

	/*$a=new Checkcode();
	echo $a->checkname("45asda");
	echo $a->checkpsd("456");
	echo $a->checkname("asd123151a");
	echo $a->checkpsd("asdw54654a1s");
*/
?>