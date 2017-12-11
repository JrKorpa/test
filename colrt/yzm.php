<?php
require './includes/validate_form.class.php';
$a = strtolower($_REQUEST['code']);
$_cmt = new class_post();
if($_cmt->fun_text1(4,5,$a)){

	$b = $_SESSION['code'];
	if($a == $b){
		$msg = '验证码输入正确！亲';
	}else{
		$msg = '验证码输入错误！亲';
	}
}else{
	$msg = 'no';
}
echo json_encode($msg);die;