<?php
header("Content-type:text/html;charset=utf8;");
set_time_limit(0);
error_reporting(E_ALL);
$localhost = '192.168.0.95';
$db_user   = 'cuteman';
$db_pass   = 'QW@W#RSS33#E#';

//$localhost = '192.168.0.91';
//$db_user   = 'root';
//$db_pass   = '123456';

//创建对象并打开连接，最后一个参数是选择的数据库名称 
$mysqli = new mysqli($localhost, $db_user ,$db_pass, 'warehouse_shipping'); 
//检查连接是否成功 
if (mysqli_connect_errno()){ 
    //注意mysqli_connect_error()新特性 
    die('Unable to connect!'). mysqli_connect_error(); 
}

function con($arr)
{
    $data_info = array();
    while($row = $arr->fetch_array(MYSQLI_ASSOC)){

        $data_info[] = $row;
    }

    return $data_info;
}

//1、结束时间不等于空，不再库中。总库龄；2、结束时间不等于空，不再库中。本库龄
$sql = "select `g`.`goods_id`, 
`g`.`addtime`, 
`g`.`change_time`, 
`a`.`endtime` 
from `warehouse_goods` `g` 
inner join `warehouse_goods_age` `a` on `g`.`goods_id` = `a`.`goods_id` 
where `a`.`endtime` <> '' 
and `a`.`endtime` is not null 
and `a`.`endtime` <> '0000-00-00 00:00:00' 
and `g`.`is_on_sale` in(1,3,7,9,11,12,100)
";
$result = $mysqli->query($sql);
$data = array();
$data = con($result);

$info1 = array();
$info2 = array();

$sql = '';
if(!empty($data))
foreach ($data as $value) {
    # code...
	//总库龄
    $addtime = $value['addtime'];
    $endtime = $value['endtime'];
    $total_age = ceil(((strtotime($endtime)-strtotime($addtime))/86400));
    $info1[$total_age][] = $value['goods_id'];
	
	//本库龄
	$change_time = $value['change_time'];
    if(!empty($change_time) && $change_time !='0000-00-00 00:00:00'){
        $self_age = ceil(((strtotime($endtime)-strtotime($change_time))/86400));
    }else{
        $self_age = ceil(((strtotime($endtime)-strtotime($addtime))/86400));
    }
	$info2[$self_age][] = $value['goods_id'];
	
}

echo '1、结束时间不等于空，不再库中。本库龄开始：<br/>';
if(!empty($info1))
foreach ($info1 as $day => $arr) {
    # code...
    $goods_ids = '';
    $goods_ids = implode("','",$arr);
    $sql = "update `warehouse_goods_age` set `total_age` = '{$day}' where `goods_id` IN('".$goods_ids."')";
    $result = $mysqli->query($sql);
    echo $goods_ids;
    var_dump($result);
    echo '<br/>';
}
echo '1、结束时间不等于空，不再库中。总库龄结束：<br/>';

echo '2、结束时间不等于空，不再库中。本库库龄开始：<br/>';
if(!empty($info2))
foreach ($info2 as $day => $arr) {
    # code...
    $goods_ids = '';
    $goods_ids = implode("','",$arr);
    $sql = "update `warehouse_goods_age` set `self_age` = '{$day}' where `goods_id` IN('".$goods_ids."')";
    $result = $mysqli->query($sql);
    echo $goods_ids;
    var_dump($result);
    echo '<br/>';
}
echo '2、结束时间不等于空，不再库中。本库库龄结束：<br/>';
 
//3、结束时间等于空，在库中。总库龄；4、结束时间等于空，在库中。本库龄
$sql = "select `g`.`goods_id`,
`g`.`addtime`,
`g`.`change_time`,
`a`.`endtime` 
from `warehouse_goods` `g` 
inner join `warehouse_goods_age` `a` on `g`.`goods_id` = `a`.`goods_id` 
where `g`.`is_on_sale` in(2,4,5,6,8,10,100) 
and (`a`.`endtime` is null or `a`.`endtime` = '0000-00-00 00:00:00' or `a`.`endtime` = '')
";
$result = $mysqli->query($sql);
$data = array();
$data = con($result);

$info3 = array();
$info4 = array();

$nowtime = time();
$sql = '';

if(!empty($data))
foreach ($data as $value) {
    # code...
    $addtime = $value['addtime'];
    $change_time = $value['change_time'];

	$total_age = ceil((($nowtime-strtotime($addtime))/86400));
    $info3[$total_age][] = $value['goods_id'];
	
    if(!empty($change_time) && $change_time !='0000-00-00 00:00:00'){

        $self_age = ceil((($nowtime-strtotime($change_time))/86400));
    }else{

        $self_age = ceil((($nowtime-strtotime($addtime))/86400));
    }
    $info4[$self_age][] = $value['goods_id'];	
}

echo '3、结束时间等于空，在库中。总库龄开始:<br/>';
if(!empty($info3))
foreach ($info3 as $day => $arr) {
    # code...
    $goods_ids = '';
    $goods_ids = implode("','",$arr);
    $sql = "update `warehouse_goods_age` set `total_age` = '{$day}' where `goods_id` IN('".$goods_ids."')";
    $result = $mysqli->query($sql);
    echo $goods_ids;
	var_dump($result);
	echo '<br/>';
}
echo '3、结束时间等于空，在库中。总库龄结束<br/>';

echo '4、结束时间等于空，在库中。本库龄开始：<br/>';
if(!empty($info4))
foreach ($info4 as $day => $arr) {
    # code...
    $goods_ids = '';
    $goods_ids = implode("','",$arr);
    $sql = "update `warehouse_goods_age` set `self_age` = '{$day}' where `goods_id` IN('".$goods_ids."')";
    $result = $mysqli->query($sql);
    echo $goods_ids;
	var_dump($result);
	echo '<br/>';
}
echo '4、结束时间等于空，在库中。本库龄结束<br/>';
