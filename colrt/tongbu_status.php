<?php
/**
 * @Author: anchen
 * @Date:   2015-08-14 18:33:54
 * @Last Modified by:   anchen
 * @Last Modified time: 2015-08-18 18:43:12
 */

$db = mysqli_connect('127.0.0.1', 'root', '', 'kela_style');
$sql = "set names utf8;";
$arr = mysqli_query($db, $sql);

$db1 = mysqli_connect('127.0.0.1', 'root', '', 'test');
$sql = "set names utf8;";
$arr = mysqli_query($db1, $sql);

$sql = "select `style_sn` from `style_style` where `is_confirm` in(0,1) and `zuofei_type` = 1";
$arr = mysqli_query($db,$sql);
while($w=mysqli_fetch_assoc($arr)){

    $data[] = $w['style_sn'];
}
//echo '<pre>';
//print_r($data);die;
$data_dd = array();
foreach ($data as $key => $value) {
    # code...
    $sql = "select `check_status` from `base_style_info` where `style_sn` = '{$value}'";
    //echo $sql;die;
    $arr = mysqli_query($db1,$sql);
    $style_check = '';
    while ($w=mysqli_fetch_assoc($arr)) {
        # code...
        $style_check = $w['check_status'];
    }
    //print_r($style_check);die;
    if($style_check != 3){

        $data_dd[$value] = $style_check;
    }

}

echo '<pre>';
    print_r($data_dd);die;
$data_old = array();
$sql = "select `style_sn`,`style_id` from `style_style`";
$arr = mysqli_query($db,$sql);
while($w=mysqli_fetch_assoc($arr)){

    $data_old[$w['style_sn']] = $w['style_id'];
}
//echo '<pre>';
//print_r($data_old);die;
$data_aa = array();
foreach ($data as $key => $value) {
    # code...
    $check = array();
    $sql = "select `f_id` from `rel_style_factory` where `style_sn` = '{$value}'";
    //echo $sql;die;
    $arr = mysqli_query($db1, $sql);

    while ($w=mysqli_fetch_assoc($arr)) {
        # code..
        $check[] = $w;
    }
    //var_dump($check);die;
    if(empty($check)){

        $data_aa[$key] = $value;
    }
}

//echo '<pre>';
//var_dump($data_aa);die;
//print_r($data_aa);die;

$data_ff = array();
foreach ($data_aa as $key => $value) {

    # code...
    $data_cc = '';
    $sql = "select `f_id` from `style_factory` where `style_id` = {$data_old[$value]}";
    //echo $sql;die;
    $arr = mysqli_query($db,$sql);
    while($w=mysqli_fetch_assoc($arr)){

        $data_cc = $w['f_id'];
    }
    //print_r($data_cc);die;

    //var_dump(!empty($data_cc));die;

    if($data_cc != ''){
        $data_ff[$key] = $data_old[$value];
    }
}

echo '<pre>';
print_r($data_ff);die;