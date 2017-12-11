<?php
header("Content-type:text/html;charset=utf8;");
error_reporting(0);
$db = mysqli_connect('127.0.0.1', 'root', '', 'tenyears');
$sql = "set names utf8;";
$arr = mysqli_query($db, $sql);

$sql = "select `consignee`,`mobile` from `ecs_order_info`";
$arr = mysqli_query($db, $sql);
$app_data= array();
while($w=mysqli_fetch_assoc($arr)){
    if(!preg_match('/^\d*$/',trim($w['mobile']))){
        $app_data[$w['consignee']] = base64_decode($w['mobile']);
    }else{
        $app_data[$w['consignee']] = $w['mobile'];
    }
}

foreach ($app_data as $key => $value) {
    # code...
    $a = iconv('utf-8','gb2312',htmlspecialchars($key));
    $b = iconv('utf-8','gb2312',htmlspecialchars($value));
    $str .= $a.",".$b."\n";
}

$filename = date('Ymd His').'.csv'; //设置文件名   
export_csv($filename,$str); //导出 

function export_csv($filename,$data)   
{   
    header("Content-type:text/csv");
    header("Content-Disposition:attachment;filename=".$filename);
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    header('Expires:0');
    header('Pragma:public');
    echo $data;
}