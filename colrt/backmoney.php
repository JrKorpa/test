<?php
header("Content-type:text/html;charset=utf8;");
set_time_limit(0);
error_reporting(E_ALL);
//$warehouse = $_SESSION['warehouse'];
$act = isset($_REQUEST['act']) ? $_REQUEST['act'] : '';

$start_time = isset($_REQUEST['start_time']) ? $_REQUEST['start_time'] : date("Y-m-d");
$end_time = isset($_REQUEST['end_time']) ? $_REQUEST['end_time'] : date("Y-m-d");
def();
switch ($act){
	case 'backmoney':
		backmoney();
		break;
	case 'backmoney_detail':
		backmoney_detail();
		break;
}
//====================Search Function==================================
function def(){
	global $act,$start_time,$end_time;
	$time = date("Y-m-d");
	$checked1 = $act == 'backmoney'?'checked':'';
	$checked2 = $act == 'backmoney_detail'?'checked':'';
	echo <<<HTML
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
		<form method="post">
		<center><div style="padding-top:100px;font-size:24px;">
开始时间：<input type="input" name="start_time" value="$start_time">
结束时间:<input type="input" name="end_time" value="$end_time">
<input type="radio" name="act" $checked1 value="backmoney">回款统计查看
<input type="radio" name="act" $checked2 value="backmoney_detail">回款详情查看
<input type="submit"  value="提交查询">
		</div></center>
</form>

<center><div style="width:90%">
</div></center>
</body>
</html>
HTML;
}

function backmoney_detail(){
	global $start_time,$end_time;

	$sql = "select 
	`sc`.`channel_name` as '销售渠道',
	`oi`.`order_sn` as '订单号'
FROM
  `cuteframe`.`sales_channels` AS `sc`, 
  `app_order`.`base_order_info` AS `oi` ,
  `app_order`.`app_order_details` AS `od`,
  `app_order`.`app_order_account` AS `oa`
WHERE `oi`.`id` = `oa`.`order_id` 
  AND `oi`.`id` = `od`.`order_id` 
  AND `oi`.`department_id` = `sc`.`id`
  AND `oa`.`money_unpaid`>0
  AND `sc`.`channel_name` like '%体验店'
  AND `oi`.`order_status`=2
";
	$sql .=" and `oi`.`create_time` >='{$start_time} 00:00:00' and `oi`.`create_time` <='{$end_time} 23:59:59' order by `oi`.`department_id` asc  ";
	//$db1 = mysqli_connect('192.168.1.93', 'cuteman', 'QW@W#RSS33#E#', 'app_order');
	$db1 = mysqli_connect('192.168.1.94', 'root', '82ISzF[l81He', 'app_order');
	$db1 = mysqli_connect('192.168.1.94', 'sudong', 'yieJ9Ghe8iP7', 'app_order');
	$arr = mysqli_query($db1, $sql);
	$data= array();
	while($w=mysqli_fetch_assoc($arr)){
		$data[] = $w;
	}
//	var_dump($data);
	make($data,__FUNCTION__);}

function backmoney(){
	global $start_time,$end_time;

	$sql = "select 
	`sc`.`channel_name` as '销售渠道',
	COUNT(`oi`.`id`) as '订单数量',
	SUM(`oa`.`order_amount`) as '订单总金额',
	SUM(`oa`.`money_unpaid`) as '订单未付金额',
	SUM(`od`.`goods_count`) as '订单商品数量'
FROM
  `cuteframe`.`sales_channels` AS `sc`, 
  `app_order`.`base_order_info` AS `oi` ,
  `app_order`.`app_order_details` AS `od`,
  `app_order`.`app_order_account` AS `oa`
WHERE `oi`.`id` = `oa`.`order_id` 
  AND `oi`.`id` = `od`.`order_id` 
  AND `oi`.`department_id` = `sc`.`id`
  AND `oa`.`money_unpaid`>0
  AND `sc`.`channel_name` like '%体验店'
  AND `oi`.`order_status`=2
";

	$sql .=" and `oi`.`create_time` >='{$start_time} 00:00:00' and `oi`.`create_time` <='{$end_time} 23:59:59' ";
	$sql .=" group by `oi`.`department_id` order by `oi`.`department_id` asc ";
	//$db1 = mysqli_connect('192.168.1.93', 'cuteman', 'QW@W#RSS33#E#', 'app_order');
	$db1 = mysqli_connect('192.168.1.94', 'root', '82ISzF[l81He', 'app_order');
	$arr = mysqli_query($db1, $sql);
	$data= array();

	$total = array();
	$total['销售渠道']='总计';
	$total['订单数量']=0;
	$total['订单总金额']=0;
	$total['订单未付金额']=0;
	$total['订单商品数量']=0;


	while($w=mysqli_fetch_assoc($arr)){
		$data[] = $w;
                $total['订单数量']+=$w['订单数量'];
                $total['订单总金额']+=$w['订单总金额'];
                $total['订单未付金额']+=$w['订单未付金额'];
                $total['订单商品数量']+=$w['订单商品数量'];
	}
	$data[]=$total;
//	var_dump($data);
	make($data,__FUNCTION__);
}


function make($data,$name='file'){

echo "<table border=1 cellspacing=1 cellpadding=1>";
foreach($data as $k => $v){
	if($k == 0){
		echo "<tr>";
		foreach($v as $kk => $vv){
			echo "<td align=right>".$kk."</td>";
		}
		echo "</tr>";
	}
	echo "<tr>";
	foreach($v as $kk => $vv){
		echo "<td align=right>".$vv."</td>";
	}
	echo "</tr>";
}
echo "</table>";
}



