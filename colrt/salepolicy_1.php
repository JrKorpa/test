<?php
header("Content-type:text/html;charset=utf8;");
set_time_limit(0);
error_reporting(E_ALL);

$localhost = '192.168.10.23';
$db_user   = 'root';
$db_pass   = '1308b8dac1e577';

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

$sql = "select `id`,`goods_id` from `base_salepolicy_goods` where `is_sale` = 1 and `isXianhuo` = 0 and `category` not in(2,10,11)";
$result = $mysqli->query($sql);
$data = array();
$data = con($result);

$info = array();

$tmp_str = '';
//print_r($data);die;

foreach ($data as $key => $value) {
    # code...
    $info = explode("-",$value['goods_id']);
    if($info[4]!='0'){
        $tmp_str .= $value['id'].",";
    }
}

$tmp_str = rtrim($tmp_str,",");
if($tmp_str){
    $sql = "update `base_salepolicy_goods` set `is_sale` = '0' where `id` IN(".$tmp_str.")";
    //echo $sql;die;
    $result = $mysqli->query($sql);
    $res = mysqli_affected_rows($mysqli);
    echo "影响".$res;
}
