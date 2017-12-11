<?php
/**
 * @Author: anchen
 * @Date:   2015-08-19 18:25:55
 * @Last Modified by:   anchen
 * @Last Modified time: 2015-08-20 10:23:47
 */

$db1 = mysqli_connect('127.0.0.1', 'root', '', 'test');
$sql = "set names utf8;";
$arr = mysqli_query($db1, $sql);
$sql = "select `style_id`,`style_sn` from `base_style_info`";
$arr = mysqli_query($db1,$sql);
while($w=mysqli_fetch_assoc($arr)){

    $data[$w['style_id']] = $w['style_sn'];
}

foreach ($data as $key => $value) {
    
    //print_r($a);die;
    $sql = "select `` from `app_attribute_value` ";
}