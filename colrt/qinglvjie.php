<?php
/**
 * @Author: anchen
 * @Date:   2015-08-20 18:19:57
 * @Last Modified by:   anchen
 * @Last Modified time: 2015-08-21 10:47:07
 */
header("Content-type:text/html;charset=utf8;");
set_time_limit(0);
error_reporting(0);
//error_reporting( E_ALL&~E_NOTICE );
$db = mysqli_connect('127.0.0.1', 'root', '', 'test');
$sql = "set names utf8;";
$arr = mysqli_query($db, $sql);

$sql = "select `style_sn` from `base_style_info` where `check_status` = 3 and `style_type` = 11";
$arr = mysqli_query($db, $sql);
while($w=mysqli_fetch_assoc($arr)){

    $qinglvjie[] = $w['style_sn'];
}

foreach ($qinglvjie as $key => $value) {
    # code...
    $sql = "select `x`.`id`,`x`.`goods_sn` from `base_order_info` as `y` left join `app_order_details` as `x` on `x`.`order_id` = `y`.`id` and `y`.`order_status` = 2 where `x`.`goods_sn` = '{$value}'";
    $arr = mysqli_query($db, $sql);
    while ($w=mysqli_fetch_assoc($arr)) {
        # code...
        $qlj[$key][$w['id']] = $w['goods_sn'];
    }
}

foreach ($qlj as $key => $value) {
    # code...
    $num = count($value);
    foreach ($value as $k => $v) {
        # code...
        $style_sn = $v;break;
    }
    $data[$style_sn] = $num;
}

arsort($data);
//echo '<pre>';
//print_r($data);die;

foreach ($data as $key => $value) {
    # code...
    $style_sn = iconv('utf-8','gb2312',$key);
    $xiaoliang   = iconv('utf-8','gb2312',$value);
    $str.= $style_sn.",".$xiaoliang."\n";
}

$filename = date('Ymd His').'.csv'; //设置文件名   
export_csv($filename,$str); //导出 

function export_csv($filename,$data)   
{   
    header("Content-type:text/csv");   
    header("Content-Disposition:attachment;filename=".$filename);   
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');   
    header('Expires:0');   
    header('Pragma:public');   
    echo $data;   
}  