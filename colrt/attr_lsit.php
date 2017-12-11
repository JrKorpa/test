<?php
/**
 * @Author: anchen
 * @Date:   2015-08-20 11:01:17
 * @Last Modified by:   anchen
 * @Last Modified time: 2015-08-20 17:11:36
 */
header("Content-type:text/html;charset=utf8;");
set_time_limit(0);
error_reporting(0);
//error_reporting( E_ALL&~E_NOTICE );
$db = mysqli_connect('127.0.0.1', 'root', '', 'test');
$sql = "set names utf8;";
$arr = mysqli_query($db, $sql);

$sql = "select `attribute_id`,`attribute_name` from `app_attribute`";
$arr = mysqli_query($db, $sql);
while($w=mysqli_fetch_assoc($arr)){

    $attribute[$w['attribute_id']] = $w['attribute_name'];
}

$sql = "select `att_value_id`,`att_value_name` from `app_attribute_value`";
$arr = mysqli_query($db, $sql);
while($w=mysqli_fetch_assoc($arr)){

    $attribute_values[$w['att_value_id']] = $w['att_value_name'];
}

$sql = "select `style_sn`,`attribute_id`,`attribute_value` from `test`.`rel_style_attribute` where `style_sn` = 'W113_001';";

$arr = mysqli_query($db, $sql);
$app_data= array();
while($w=mysqli_fetch_assoc($arr)){

    if($w['attribute_value']){

        $attribute_value = explode(',', $w['attribute_value']);

        foreach ($attribute_value as $key => &$value) {
            # code...
            $value = $attribute_values[$value];
        }
    }

    $app_data[$w['style_sn']][$attribute[$w['attribute_id']]] = $attribute_value;
}

echo '<pre>';
print_r($app_data);die;