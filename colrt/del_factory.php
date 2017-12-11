<?php
/**
 * @Author: anchen
 * @Date:   2015-07-09 11:16:07
 * @Last Modified by:   anchen
 * @Last Modified time: 2015-08-16 19:00:15
 */
header("Content-type:text/html;charset=utf8;");
set_time_limit(0);
error_reporting(E_ALL);

//$db1 = mysqli_connect('192.168.1.55', 'style_zyy', 'KELAzhangyuanyuan123', 'kela_style');
$db1 = mysqli_connect('127.0.0.1', 'root', '', 'test');
$sql = "set names utf8;";
$arr = mysqli_query($db1, $sql);
$sql = "SELECT style_sn,factory_id,factory_sn,xiangkou,count(1) h FROM `rel_style_factory` where `is_cancel` = 1 group by style_sn,factory_id,factory_sn,xiangkou having h = 2;";
//echo $sql;die;
$arr = mysqli_query($db1, $sql);
$data= array();
while($w=mysqli_fetch_assoc($arr)){
    $data[] = $w;
}

//echo '<pre>';
//print_r($data);die;
foreach ($data as $key => $value) {
    # code...
    $sql = "select * from `rel_style_factory` where `style_sn` = '".$value['style_sn']."' and `factory_id` = ".$value['factory_id']." and `factory_sn` = '".$value['factory_sn']."' and `xiangkou` = '".$value['xiangkou']."'";
    //echo $sql;die;
    $arr = mysqli_query($db1,$sql);
    while ($w = mysqli_fetch_assoc($arr)) {
        //print_r($w);die;
        # code...
        $fac_data[$w['f_id']] = $w;
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
        $sql .= "delete from `rel_style_factory` where `f_id` = ".$value['f_id']." limit 1;<br/>";
        
        //$arr = mysqli_query($db1,$sql);
    }
    
}
echo $sql;die;