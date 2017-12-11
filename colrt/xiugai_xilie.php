<?php
/**
 * @Author: anchen
 * @Date:   2015-08-14 18:33:54
 * @Last Modified by:   anchen
 * @Last Modified time: 2015-08-18 11:45:32
 */
$db1 = mysqli_connect('127.0.0.1', 'root', '', 'test');
$sql = "set names utf8;";
$arr = mysqli_query($db1, $sql);

$sql = "select `style_id`,`xilie` from `base_style_info` where `xilie` <> ''";
$arr = mysqli_query($db1,$sql);
while($w=mysqli_fetch_assoc($arr)){

    $data[$w['style_id']] = $w['xilie'];
}
$sql = '';
foreach ($data as $key => $value) {
    # code...
    $xilie = ','.$value.',';
    $sql .= "update `base_style_info` set `xilie` = '{$xilie}' where `style_id` = {$key} limit 1;<br>";
}

echo $sql;die;