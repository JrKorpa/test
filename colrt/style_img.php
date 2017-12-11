<?php
/**
 * @Author: anchen
 * @Date:   2015-07-09 11:16:07
 * @Last Modified by:   anchen
 * @Last Modified time: 2016-01-07 16:14:39
 */
header("Content-type:text/html;charset=utf8;");
set_time_limit(0);
error_reporting(E_ALL);

//$db1 = mysqli_connect('192.168.1.55', 'style_zyy', 'KELAzhangyuanyuan123', 'kela_style');
$db1 = mysqli_connect('127.0.0.1', 'root', '', 'test');
$sql = "set names utf8;";
$arr = mysqli_query($db1, $sql);
$sql = "SELECT style_sn,image_place,thumb_img,count(1) h
FROM  `app_style_gallery` 
where image_place = 1 
group by style_sn,image_place
having h = 2;";
echo $sql;die;
$arr = mysqli_query($db1, $sql);
$data= array();
while($w=mysqli_fetch_assoc($arr)){
    $data[] = $w;
}

echo '<pre>';
print_r($data);die;
foreach ($data as $key => $value) {
    # code...
    $sql = "select `g_id`,`thumb_img`,`style_sn`,`image_place` from `app_style_gallery` where `style_sn` = '".$value['style_sn']."' and `image_place` = ".$value['image_place']."";
    //echo $sql;die;
    $arr = mysqli_query($db1,$sql);
    while ($w = mysqli_fetch_assoc($arr)) {
        //print_r($w);die;
        # code...
        $fac_data[$w['style_sn']."_".$w['g_id']] = $w;
        //print($fac_data);die;
    }//
}
//echo '<pre>';
//var_dump($fac_data);die;
//print_r($fac_data);die;
$data_fak = array_values($fac_data);

echo '<pre>';
print_r($data_fak);die;
$sql = '';
$fake = array();
foreach ($data_fak as $key => $value) {
    # code...
    if($key%2 == 0){
        $sql .= "delete from `app_style_gallery` where `g_id` = ".$value['g_id']." limit 1;<br/>";
        
        //$arr = mysqli_query($db1,$sql);
    }
    
}
echo $sql;die;
echo 'ok!';die;