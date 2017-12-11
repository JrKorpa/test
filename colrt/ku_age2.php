<?php
header("Content-type:text/html;charset=utf8;");
set_time_limit(0);
error_reporting(E_ALL);

$localhost = '192.168.0.91';
$db_user   = 'root';
$db_pass   = '123456';

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

$sql = "select `g`.`goods_id`,`g`.`addtime`,`g`.`change_time`,`a`.`endtime` from `warehouse_goods` `g`
inner join `warehouse_goods_age` `a` on `g`.`goods_id` = `a`.`goods_id` 
where `g`.`is_on_sale` <> 12 
and `a`.`endtime` is null";
$result = $mysqli->query($sql);
$data = array();
$data = con($result);

$info = array();

$nowtime = date('Y-m-d H:i:s',time());

$sql = '';
foreach ($data as $value) {
    # code...
    $addtime = $value['addtime'];
    $change_time = $value['change_time'];

    if($change_time != '' && $change_time !='0000-00-00 00:00:00'){

        $self_age = intval(((strtotime($nowtime)-strtotime($change_time))/86400));
    }else{

        $self_age = intval(((strtotime($nowtime)-strtotime($addtime))/86400));
    }

    if($self_age == 0){
        
        $self_age = 1;
    }

    $info[$self_age][] = $value['goods_id'];
}

foreach ($info as $day => $arr) {
    # code...
    $goods_ids = '';
    $goods_ids = implode("','",$arr);
    $sql = "update `warehouse_goods_age` set `self_age` = '{$day}' where `goods_id` IN('".$goods_ids."')";
    $result = $mysqli->query($sql);
    echo $goods_ids;var_dump($result);echo '<br/>';
}

