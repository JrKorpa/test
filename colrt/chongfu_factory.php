<?php
/**
 * @Author: anchen
 * @Date:   2015-08-16 17:19:08
 * @Last Modified by:   anchen
 * @Last Modified time: 2015-08-16 17:20:15
 */
$db1 = mysqli_connect('127.0.0.1', 'root', '', 'test');
$sql = "set names utf8;";
$arr = mysqli_query($db1, $sql);

$sql = "select `style_sn` from `rel_style_factory`";
$arr = mysqli_query($db1,$sql);
while($w=mysqli_fetch_assoc($arr)){

    $data[] = $w['style_sn'];
}