<?php
/**
 * @Author: anchen
 * @Date:   2015-08-20 11:01:17
 * @Last Modified by:   anchen
 * @Last Modified time: 2015-08-27 16:59:58
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

$sql = "select `style_sn` from `style_style` where `is_new` = 2 and `zuofei_type` = 1";
$arr = mysqli_query($db1, $sql);
while($w=mysqli_fetch_assoc($arr)){

    $data[$w['style_sn']] = $w;
}
//echo '<pre>';
//var_dump($data);die;
foreach ($data as $key => $value) {
    # code...
    $sql = "select `rel_id` from `rel_style_attribute` where `style_sn` = '{$key}'";
    $arr = mysqli_query($db, $sql);
    $t = array();
    while ($w=mysqli_fetch_assoc($arr)) {
        # code..
        $t[$key][$w['rel_id']] = $w['rel_id'];
    }
    if(empty($t)){
        $n[$value['style_sn']] = $value; 
    }
}
echo '<pre>';
//var_dump($n);die;
print_r($n);die;
foreach ($n as $key => $value) {
    # code...
    $n[$key]['style_cat_attr'] = unserialize($value['style_cat_attr']);
    $n[$key]['metal_info'] = unserialize($value['metal_info']);
}
//echo '<pre>';
//print_r($n);die;
$config = array(
        'is_3d' => 91,
        'gaodu' => 37,
        'kuandu' => 41,
        'hankou' => 39,
        'could_world' => 7,
        'metal_info' => 3,
        'zhengshu' => 35,
        'face_work' => 27
    );

$is_3d = array(0=>'',1=>401,2=>402,3=>403);

$config_s = array(37=>1,41=>1,39=>3,7=>4,3=>3,35=>3,27=>3,91=>2);//展示类型

$style_caizhi = array(0=>'',1=>225,2=>37,3=>223,4=>39,5=>235,6=>243,7=>233,8=>227,9=>241,10=>221);//可做材质

$hankou = array(0=>239,1=>237);

$is_kezi = array(0=>53,1=>51);//是否刻字

$face_work  = array(1=>157,2=>155,3=>321,4=>159,5=>231);

$zhengshu = array(1=>197,2=>203,3=>201,4=>189,5=>191,6=>207,7=>193,8=>199,9=>205);

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
foreach ($n as $key => $value) {
    # code...
    if($value['style_type'] != 3){
        unset($n[$key]);
    }
}
//echo '<pre>';
//print_r($n);die;
foreach ($n as $key => $value) {
    # code...
    foreach ($value as $k => $v) {
        # code...
        if($k == 'style_cat_attr'){

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
        if($k == 'style_cat_attr'){
            $str_v = '';
            if($a == 4){
                $str_l = '';
                $k = 'zhengshu';
                if(!empty($b) && is_array($b)){
                    foreach ($b as $x => $y) {

                        $str_l = $zhengshu[$y].",";
                    }
                }
                $str_v = $str_l;
            }
            $str_value = $str_v;
        }
        if($config[$k] != ''){
            $sql.= "INSERT INTO `rel_style_attribute` (`cat_type_id`, `product_type_id`, `style_sn`, `attribute_id`, `attribute_value`, `show_type`, `create_time`, `create_user`, `info`, `style_id`) VALUES
(".$v['style_type'].", ".$v['product_type'].", '".$v['style_sn']."', ".$config[$k].", '".$str_value."',".$config_s[$config[$k]].", '".$time."', 'admin', '', ".$v['style_id'].");<br>";
        }
    }
}
echo $sql;die;