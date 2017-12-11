<?php
/**
* 通过读取csv文档，根据款号更新款的某一项属性；
*/
header("Content-type:text/html;charset=utf-8");
date_default_timezone_set('Asia/Shanghai');
require_once('MysqlDB.class.php');
set_time_limit(0);
ini_set('memory_limit','2000M');

$new_conf = [
	'dsn'=>"mysql:host=192.168.0.95;dbname=warehouse_shipping",
	'user'=>"cuteman",
	'password'=>"QW@W#RSS33#E#",
	'charset' => 'utf8'
];

/*$new_conf = [
    'dsn'=>"mysql:host=192.168.0.131;dbname=warehouse_shipping",
    'user'=>"root",
    'password'=>"123456",
    'charset' => 'utf8'
];*/

$db = new MysqlDB($new_conf);

$sort_flag = array('0.001', '0.002', '0.003', '0.004', '0.005', '0.006', '0.007', '0.008', '0.009', '0.01', '0.02', '0.03', '0.04', '0.05', '0.06', '0.07', '0.08', '0.09', '0.1', '0.11', '0.12', '0.13', '0.14', '0.15', '0.16', '0.17', '0.18', '0.19', '0.2', '0.25', '0.3', '0.35', '0.4', '0.45', '0.5', '0.55', '0.6', '0.65', '0.7', '0.75', '0.8', '0.85', '0.9', '0.95', '1', '1.05', '1.1', '1.15', '1.2', '1.25', '1.3', '1.35', '1.4', '1.45', '1.5', '1.55', '1.6', '1.65', '1.7', '1.75', '1.8', '1.85', '1.9', '1.95', '2', '2.05', '2.1', '2.3', '2.4', '2.45', '3', '4.3', '5', '10', '11', '12', '13', '15', '16', '17', '18', '19', '20', '21', '25', '26', '29', '30', '33');

$sql = "select `goods_id`,`zuanshidaxiao` from `mase_jtxk_hxw`";
$data = array_combine(array_column($db->getAll($sql), 'goods_id'), array_column($db->getAll($sql), 'zuanshidaxiao'));
$papa = array();
foreach ($data as $goods_id => $zuanshidaxiao) {

    $sql = "select `jietuoxiangkou` from `mase_jtxk_hxw` where `goods_id` = $goods_id";
    $ck = $db->getOne($sql);
    if(!empty($ck)) continue;
    if(in_array($zuanshidaxiao, $sort_flag)){
        $jietuoxiangkou = $zuanshidaxiao;
    }else{
        $aft = array_merge(array($zuanshidaxiao), $sort_flag);sort($aft);
        $i = array_search($zuanshidaxiao, $aft);
        $asdfsdf = $aft[$i-1];
        $jietuoxiangkou = $asdfsdf;
    }
    $sql = "update `mase_jtxk_hxw` set `jietuoxiangkou` = '".$jietuoxiangkou."' where `goods_id` = $goods_id";
    $res = $db->query($sql);
    echo $goods_id;var_dump($res);echo "<br />";
}