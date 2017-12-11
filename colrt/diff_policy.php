<?php
/**
 * @Author: anchen
 * @Date:   2015-07-10 22:57:59
 * @Last Modified by:   anchen
 * @Last Modified time: 2015-07-11 13:00:19
 */

header("Content-type:text/html;charset=utf8;");
set_time_limit(0);
error_reporting(0);
//error_reporting( E_ALL&~E_NOTICE );
$db = mysqli_connect('127.0.0.1', 'root', '', 'test');
$sql = "set names utf8;";
$arr = mysqli_query($db, $sql);
$sql = "select `goods_id` from `test`.`app_salepolicy_goods`;";
$arr = mysqli_query($db, $sql);
$app_data= array();
while($w=mysqli_fetch_assoc($arr)){
    $app_data[$w['goods_id']] = $w['goods_id'];
}

$sql = "select `goods_id`,`isXianhuo` from `test`.`base_salepolicy_goods`;";
$arr = mysqli_query($db, $sql);
$base_data = array();
$data = array();
while($w=mysqli_fetch_assoc($arr)){
    $base_data[$w['goods_id']] = $w;
}
$app_data = array_filter($app_data);
//echo '<pre>';
//print_r($app_data);die;
//$diff_arr = array();
foreach ($app_data as $key => $value) {
    # code...
    if(isset($base_data[$key])){
        unset($base_data[$key]);
    }
}

foreach ($base_data as $k => $v) {
    # code...
    if($v['isXianhuo'] != 1){
        unset($base_data[$k]);
    }
}
$str = '';
foreach ($base_data as $key => $value) {
    # code...
    $diff[] = $key;
    $str .= "'".$key."',";
}
$str = rtrim($str,',');
//echo $str;die;
$sql = "select * from `test`.`app_salepolicy_goods` where `goods_id` in($str);";
$arr = mysqli_query($db, $sql);
$a= array();
while($w=mysqli_fetch_assoc($arr)){
    $a[] = $w['goods_id'];
}
//echo '<pre>';
print_r($diff);die;