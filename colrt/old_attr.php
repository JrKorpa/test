<?php
/**
 * @Author: anchen
 * @Date:   2015-08-20 11:01:17
 * @Last Modified by:   anchen
 * @Last Modified time: 2015-08-25 10:26:29
 */
header("Content-type:text/html;charset=utf8;");
set_time_limit(0);
//error_reporting(0);
error_reporting( E_ALL&~E_NOTICE );
$db1 = mysqli_connect('127.0.0.1', 'root', '', 'kela_style');
$sql = "set names utf8;";
$arr = mysqli_query($db1, $sql);

$db = mysqli_connect('127.0.0.1', 'root', '', 'test');
$sql = "set names utf8;";
$arr = mysqli_query($db, $sql);

$sql = "select `style_sn`,`is_weizuan`,`zhua_xingtai`,`zhua_xingzhuang`,`zhua_daizuan`,`zhua_num`,`bi_xingtai`,`bi_daizuan`,`is_fushi`,`zhushishu`,`style_caizhi`,`kezuo_yanse`,`is_kezi`,`style_gaiquan` from `style_style` where `is_new` = 1";
$arr = mysqli_query($db1, $sql);
while($w=mysqli_fetch_assoc($arr)){

    $data[$w['style_sn']] = $w;
}
foreach ($data as $key => $value) {
    # code...
    $sql = "select `rel_id` from `rel_style_attribute` where `style_sn` = '".$value['style_sn']."'";
    $arr = mysqli_query($db, $sql);
    $t = array();
    while ($w=mysqli_fetch_assoc($arr)) {
        # code..
        $t[$key][$w['rel_id']] = $w['rel_id'];
    }
    if(empty($t)){
        $n[$key] = $value; 
    }
}
//echo '<pre>';
//print_r($n);die;

$config = array(

        'is_weizuan' => 9,
        'zhua_xingtai' => 11,
        'zhua_xingzhuang' => 19,
        'zhua_daizuan' => 21,
        'zhua_num' => 15,
        'bi_xingtai' => 23,
        'bi_daizuan' => 25,
        'is_fushi' => 29,
        'style_caizhi' => 3,
        'kezuo_yanse' => 33,
        'is_kezi' => 7,
        'style_gaiquan' => 31
    );

$config_s = array(9=>4,11=>4,19=>4,21=>2,15=>4,23=>2,25=>2,29=>2,3=>3,33=>3,7=>4,31=>4);//展示类型
  
$zhua_xingtai = array(0=>71,1=>63,2=>65,3=>67,4=>69);//爪形态array(=>'无',=>'直',=>'扭',=>'花型',=>'雪花');

$zhua_xingzhuang = array(0=>342,1=>119,2=>125,3=>131,4=>133,5=>127,6=>121,7=>121);//爪形状

$zhua_daizuan = array(0=>139,1=>137);//爪带钻

$zhua_num  = array(0=>87,1=>89,2=>91,3=>93,4=>95,5=>97,6=>99,7=>101,8=>103,9=>105,10=>107,11=>109,12=>111);//爪数量

$bi_xingtai = array(0=>'',1=>141,2=>143,3=>145,4=>147);//臂形态

$bi_daizuan = array(0=>'',1=>151);//臂带钻

$is_fushi = array(0=>167,1=>169);//是否有副石

$style_caizhi = array(0=>'',1=>37,2=>39,3=>'37,39');//可做材质

$kezuo_yanse = array(0=>'',1=>181,2=>183,3=>185,4=>'187,336,337,345');//可做颜色

$is_kezi = array(0=>51,1=>53);//是否刻字

$style_gaiquan = array(0=>'',1=>171,2=>173,3=>175,4=>393);

//echo '<pre>';
//print_r($data);die;
foreach ($n as $key => $value) {

    $sql = "select `style_id`,`style_type`,`product_type` from `base_style_info` where `style_sn` = '{$key}'";
    $arr = mysqli_query($db, $sql);
    while($w=mysqli_fetch_assoc($arr)){
        $n[$key]['style_id'] = $w['style_id'];
        $n[$key]['style_type'] = $w['style_type'];
        $n[$key]['product_type'] = $w['product_type'];
    }
}
//echo '<pre>';
//print_r($data);die;
//
foreach ($n as $key => $value) {
    # code...
    foreach ($value as $k => $v) {
        # code...
        if($k == 'is_weizuan' || $k == 'zhua_xingtai' || $k == 'zhua_xingzhuang' || $k == 'zhua_daizuan' || $k == 'zhua_num' || $k == 'bi_xingtai' || $k == 'bi_daizuan' || $k == 'zhushishu' || $k == 'is_fushi' || $k == 'style_caizhi' || $k == 'kezuo_yanse' || $k == 'is_kezi' || $k == 'style_gaiquan'){
            $Hat[$key][$k][$k] = $v;
            $Hat[$key][$k]['style_id'] = $value['style_id'];
            $Hat[$key][$k]['style_sn'] = $value['style_sn'];
            $Hat[$key][$k]['style_type'] = $value['style_type'];
            $Hat[$key][$k]['product_type'] = $value['product_type'];
        }
    }
}
//echo '<pre>';
//print_r($Hat);die;
$sql = '';
$time = date('Y-m-d H:i:s',time());
foreach ($Hat as $key => $value) {
    # code..
    foreach ($value as $k => $v) {
        # code...
        //echo '<pre>';
        //print_r($value);die;
        if($k == 'is_weizuan'){

            if($v['is_weizuan'] == 1 && $value['zhushishu']['zhushishu'] != 0){
                $str_value = 57;
            }
            if($v['is_weizuan'] == 1 && $value['zhushishu']['zhushishu'] == 0){
                $str_value = 59;
            }
            if($v['is_weizuan'] == 0 && $value['zhushishu']['zhushishu'] != 0){
                $str_value = 55;
            }
            if($v['is_weizuan'] == 0 && $value['zhushishu']['zhushishu'] == 0){
                $str_value = 61;
            }
            //print_r($str_value);die;
        }elseif($k == 'zhua_xingtai'){

            $str_value = $zhua_xingtai[$v['zhua_xingtai']];
        }elseif($k == 'zhua_xingzhuang'){

            $str_value = $zhua_xingzhuang[$v['zhua_xingzhuang']];
        }elseif($k == 'zhua_daizuan'){

            $str_value = $zhua_daizuan[$v['zhua_daizuan']];
        }elseif($k == 'zhua_num'){

            $str_value = $zhua_num[$v['zhua_num']];
        }elseif($k == 'bi_xingtai'){

            $str_value = $bi_xingtai[$v['bi_xingtai']];
        }elseif($k == 'bi_daizuan'){

            $str_value = $bi_daizuan[$v['bi_daizuan']];
        }elseif($k == 'is_fushi'){

            $str_value = $is_fushi[$v['is_fushi']];
        }elseif($k == 'style_caizhi'){

            $str_value = $style_caizhi[$v['style_caizhi']];
        }elseif($k == 'kezuo_yanse'){
            //print_r($k);die;
            $arr_a = explode(",", $v['kezuo_yanse']);
            $x_kez = '';
            foreach ($arr_a as $va) {
                # code...
                $x_kez.=$kezuo_yanse[$va].",";
            }
            $str_value = trim($x_kez,',');
        }elseif($k == 'is_kezi'){

            $str_value = $is_kezi[$v['is_kezi']];
        }elseif($k == 'style_gaiquan'){
            
            $str_value = $style_gaiquan[$v['style_gaiquan']];
        }
        $sql.= "INSERT INTO `rel_style_attribute` (`cat_type_id`, `product_type_id`, `style_sn`, `attribute_id`, `attribute_value`, `show_type`, `create_time`, `create_user`, `info`, `style_id`) VALUES
(".$v['style_type'].", ".$v['product_type'].", '".$v['style_sn']."', ".$config[$k].", '".$str_value."',".$config_s[$config[$k]].", '".$time."', 'admin', '', ".$v['style_id'].");<br>";
    }
}
echo $sql;die;