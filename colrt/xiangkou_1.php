<?php
header("Content-type:text/html;charset=utf8;");
set_time_limit(0);
error_reporting(E_ALL);

$localhost = '192.168.0.91';
$db_user   = 'root';
$db_pass   = '123456';

//创建对象并打开连接，最后一个参数是选择的数据库名称 
$mysqli = new mysqli($localhost, $db_user ,$db_pass, 'front');
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

$sql = "select `style_id`,`attribute_id` from `rel_style_attribute` where `attribute_id` = 5 and cat_type_id not in(2,10,11)";

//$sql = "select * from ``";
$result = $mysqli->query($sql);
$data = array();
$data = con($result);

$info = array();

$sql = '';
foreach ($data as $value) {
    # code...
    $addtime = $value['addtime'];
    $endtime = $value['endtime'];
    $total_age = intval(((strtotime($endtime)-strtotime($addtime))/86400));

    if($total_age == 0){
        
        $total_age = 1;
    }
    $info[$total_age][] = $value['goods_id'];
}

foreach ($info as $day => $arr) {
    # code...
    $goods_ids = '';
    $goods_ids = implode("','",$arr);
    $sql = "update `warehouse_goods_age` set `total_age` = '{$day}' where `goods_id` IN('".$goods_ids."')";
    $result = $mysqli->query($sql);
    echo $goods_ids;var_dump($result);echo '<br/>';
}

