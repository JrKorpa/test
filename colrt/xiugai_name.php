<?php
/**
 * @Author: anchen
 * @Date:   2015-08-14 18:33:54
 * @Last Modified by:   anchen
 * @Last Modified time: 2015-08-17 21:24:44
 */


$db1 = mysqli_connect('127.0.0.1', 'root', '', 'test');
$sql = "set names utf8;";
$arr = mysqli_query($db1, $sql);

$sql = "select * from `base_style_info` where `style_name` = ''";
$arr = mysqli_query($db1,$sql);
while($w=mysqli_fetch_assoc($arr)){

    $data[$w['style_id']] = $w;
}
//echo '<pre>';
//print_r($data);die;

$str = '';
foreach ($data as $key => $value) {
    # code...
    $sql = "SELECT `product_type_name` FROM `app_product_type` WHERE `product_type_id` = ".$value['product_type']."";
    //echo $sql;die;
    $arr = mysqli_query($db1, $sql);

    while ($w=mysqli_fetch_assoc($arr)) {
        # code..
        $product_type_name = $w['product_type_name'];
    }

    $sql = "SELECT `cat_type_name` FROM `app_cat_type` WHERE `cat_type_id` = ".$value['style_type']."";
    //echo $sql;die;
    $arr = mysqli_query($db1, $sql);

    while ($w=mysqli_fetch_assoc($arr)) {
        # code..
        $cat_type_name = $w['cat_type_name'];
    }

    $style_name = $product_type_name.$cat_type_name;
    $str .= "update `base_style_info` set `style_name` = '{$style_name}' where style_id = ".$value['style_id']." limit 1;<br>";

}

echo $str;die;

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