<?php
/**
 * @Author: anchen
 * @Date:   2015-08-25 19:20:37
 * @Last Modified by:   anchen
 * @Last Modified time: 2015-09-15 15:42:31
 */
header("Content-type:text/html;charset=utf8;");
set_time_limit(0);
//error_reporting(0);
$db = mysqli_connect('127.0.0.1', 'root', '', 'test');
$sql = "set names utf8;";
$arr = mysqli_query($db, $sql);

$db2 = mysqli_connect('127.0.0.1', 'root', '', 'warehouse_shipping');
$sql = "set names utf8;";
$arr = mysqli_query($db2, $sql);

$sql = "select `goods_id`,`addtime` from `test`.`jxc_goods`";
$arr = mysqli_query($db,$sql);
while ($w=mysqli_fetch_assoc($arr)) {
    $old_data[$w['goods_id']] = $w['addtime'];
}

$sql = "select `goods_id`,`addtime` from `warehouse_shipping`.`warehouse_goods`";
$arr = mysqli_query($db2,$sql);
while ($w=mysqli_fetch_assoc($arr)) {
    $new_data[$w['goods_id']] = $w['addtime'];
}

$diff_data = array();
foreach ($new_data as $key => $value) {
    # code...
    $diff_data[$key]['new'] = $value;
    $diff_data[$key]['old'] = isset($old_data[$key])?$old_data[$key]:'';
    if($diff_data[$key]['old'] == ''){
        unset($diff_data[$key]);
    }
}

$str_sql = '';
$j = 0;
$forsize = ceil(count($diff_data) / 10);
$off_st = 1;
foreach ($diff_data as $key => $value) {
    $j++;
    $old_time = $value['old'];
    $new_time = $value['new'];
    # code...
    $str_sql .= "(".$key.",'".$old_time."','".$new_time."'),";
    if($j == 10 || $off_st>=$forsize){
        $j = 0;
        $off_st++;
        $sql = "insert into `test`.`goods_addtime_diff` (goods_id,old_addtime,new_addtime) values ".rtrim($str_sql,",")."";
        mysqli_query($db,$sql);
        $str_sql = '';
    }
}




/*create table `test`.`goods_addtime_diff`
select `goods_id`,`addtime`
from `warehouse_shipping`.`warehouse_goods`;

11111111


2
select `goods_id`,`addtime` from `test`.`jxc_goods`*/



