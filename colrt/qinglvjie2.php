<?php
/**
 * @Author: anchen
 * @Date:   2015-08-20 18:19:57
 * @Last Modified by:   anchen
 * @Last Modified time: 2015-08-21 13:28:39
 */
header("Content-type:text/html;charset=utf8;");
set_time_limit(0);
error_reporting(0);
//error_reporting( E_ALL&~E_NOTICE );
$db = mysqli_connect('127.0.0.1', 'root', '', 'test');
$db1 = mysqli_connect('127.0.0.1', 'root', '', 'kela_style');
$sql = "set names utf8;";
$arr = mysqli_query($db, $sql);

$sql = "select `style_sn` from `style_style` where `ring_type` = 3 and `pro_line` in(2,4)";
$arr = mysqli_query($db1, $sql);
while($w=mysqli_fetch_assoc($arr)){

    $qinglvjie[] = $w['style_sn'];
}
//echo '<pre>';
//print_r($qinglvjie);die;
$sql = "select `rec_id`,`goods_sn` from `ecs_order_goods`";
$arr = mysqli_query($db, $sql);
while ($w=mysqli_fetch_assoc($arr)) {
    # code...
    $data[$w['rec_id']] = $w['goods_sn'];
}

foreach ($qinglvjie as $key => $value) {
    foreach ($data as $k => $v) {
        # code...
        if($value == $v){
            $f[$value][$v] = $v;
        }
    }
}
echo '<pre>';
print_r($f);die;
/*foreach ($qinglvjie as $key => $value) {
    # code...
    //$sql = "select `x`.`rec_id`,`x`.`goods_sn` from `ecs_order_info` as `y` left join `ecs_order_goods` as `x` on `x`.`order_id` = `y`.`order_id` where `x`.`goods_sn` = '{$value}' and `y`.`order_status` = 1";
    $sql = "select `rec_id`,`goods_sn` from `ecs_order_goods` where `goods_sn` = '{$value}'";
    //echo $sql;die;
    $arr = mysqli_query($db, $sql);
    while ($w=mysqli_fetch_assoc($arr)) {
        # code...
        $qlj[$key][$w['rec_id']] = $w['goods_sn'];
    }
}*/
echo '<pre>';
print_r($qlj);die;
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