<?php
/**
 * @Author: anchen
 * @Date:   2015-08-05 10:51:22
 * @Last Modified by:   anchen
 * @Last Modified time: 2015-08-05 11:38:46
 */
header("Content-type:text/html;charset=utf8;");
set_time_limit(0);
error_reporting(0);
//error_reporting( E_ALL&~E_NOTICE );
$db = mysqli_connect('127.0.0.1', 'root', '', '');
$sql = "set names utf8;";

function getnewimg(){
    //$db1 = mysqli_connect('203.130.44.199', 'cuteman', '.P$qk5tvzW!,', 'front');
    $db1 = mysqli_connect('127.0.0.1', 'root', '', 'test');
    $sql = "set names utf8;";
    $arr = mysqli_query($db1, $sql);
    $sql = "select style_id,style_sn from `test`.`base_style_info` where `check_status` = 5;";
    $arr = mysqli_query($db1, $sql);
    $data= array();
    while($w=mysqli_fetch_assoc($arr)){
        $data[$w['style_id']] = $w['style_sn'];
    }
    $sql = "select * from `test`.`app_style_gallery` where `image_place` > 0";
    $arr = mysqli_query($db1, $sql);
    $fac_data= array();
    while($w=mysqli_fetch_assoc($arr)){
        $fac_data[$w['style_id']][$w['image_place']] = $w['thumb_img'];
        $big_data[$w['style_id'].".".$w['image_place']] = $w;
    }
    $sql = "select style_id,image_place,thumb_img from `test`.`app_style_gallery` where `image_place` = 0";
    //$arr = mysqli_query($db1, $sql);
    $fac_data_x= array();
    while($w=mysqli_fetch_assoc($arr)){
        $fac_data_x[$w['style_id']][] = $w;
    }
    $ret = array('base'=>$data,'fac'=>$fac_data,'big_data'=>$big_data);
    return $ret;
}

function getoldimg(){
    //$db1 = mysqli_connect('192.168.1.55', 'style_zyy', 'KELAzhangyuanyuan123', 'kela_style');
    $db1 = mysqli_connect('127.0.0.1', 'root', '', 'kela_style');
    $sql = "set names utf8;";
    $arr = mysqli_query($db1, $sql);
    $sql = "select style_id,style_sn from `kela_style`.`style_style` where `zuofei_type` = 2;";
    $arr = mysqli_query($db1, $sql);
    $data= array();
    while($w=mysqli_fetch_assoc($arr)){
        $data[$w['style_id']] = $w['style_sn'];
    }
    $sql = "select style_id,image_place,thumb_img from `kela_style`.`style_gallery` where `image_place` > 0";
    $arr = mysqli_query($db1, $sql);
    $fac_data= array();
    while($w=mysqli_fetch_assoc($arr)){
        $fac_data[$w['style_id']][$w['image_place']] = $w['thumb_img'];
    }
    $sql = "select style_id,image_place,thumb_img from `kela_style`.`style_gallery` where `image_place` = 0";
    //$arr = mysqli_query($db1, $sql);
    $fac_data_x= array();
    while($w=mysqli_fetch_assoc($arr)){
        $fac_data_x[$w['style_id']][] = $w;
    }
    $ret = array('base'=>$data,'fac'=>$fac_data,'fac_x'=>$fac_data_x);
    return $ret;
}


$newimgData = getnewimg();
$oldimgData = getoldimg();

//$new_style = array_keys($oldimgData['base']);
//$old_style = array_keys($newimgData['base']);

//echo '<pre>';
//print_r($newimgData['base']);die;


$old = $oldimgData['base'];
$new = $newimgData['base'];
//echo '<pre>';
//print_r($new);die;
//var_dump($old);die;
foreach ($old as $key => $value) {
    # code...
    if(in_array($value,$new)){
        unset($old[$key]);
    }
}

echo '<pre>';
print_r($old);die;