<?php

header("Content-type:text/html;charset=utf8;");
set_time_limit(0);
error_reporting(0);
//error_reporting( E_ALL&~E_NOTICE );
$db1 = mysqli_connect('127.0.0.1', 'root', '', 'test');
$sql = "set names utf8;";
$arr = mysqli_query($db1, $sql);
$sql = "select `factory_sn`,`factory_id` from `test`.`rel_style_factory`;";
$arr = mysqli_query($db1, $sql);
$data_v= array();
while($w=mysqli_fetch_assoc($arr)){
    $data_v[$w['factory_id']] = $w['factory_sn'];
}

$db2 = mysqli_connect('127.0.0.1', 'root', '', 'kela_style');
$sql = "set names utf8;";
$arr = mysqli_query($db2, $sql);
$sql = "select `factory_sn`,`factory_id` from `kela_style`.`style_factory`;";
$arr = mysqli_query($db2, $sql);
$data_s= array();
while($w=mysqli_fetch_assoc($arr)){
    $data_s[$w['factory_id']] = $w['factory_sn'];
}

foreach ($data_s as $key => $value) {
    # code...
    $sql = "select `name` from `test`.`app_processor_info` where `id` = {$key}";
    $arr = mysqli_query($db1,$sql);
    while($w=mysqli_fetch_assoc($arr)){
        $data[$key] = $w['name'];
    }
}

foreach ($data_v as $key => $value) {
    # code...
    $sql = "select `name` from `test`.`app_processor_info` where `id` = {$key}";
    $arr = mysqli_query($db1,$sql);
    while($w=mysqli_fetch_assoc($arr)){
        $datas[$key] = $w['name'];
    }
}

$data_diff = array_diff($data,$datas);

echo '<pre>';
print_r($data_diff);die;