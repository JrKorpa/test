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
    'dsn'=>"mysql:host=192.168.0.131;dbname=front",
    'user'=>"root",
    'password'=>"123456",
    'charset' => 'utf8'
];*/

$db = new MysqlDB($new_conf);

$name = "upCz.csv";

$file = fopen($name,"r");
while(! feof($file))
{
    $data[] = fgetcsv($file);
}
fclose($file);

$data = array_filter(eval('return '.iconv('gbk','utf-8',var_export($data,true)).';'));

$snKey = array_column($data, 0);

$data = array_combine($snKey, array_column($data, 1));

$str = '';
foreach ($data as $k_sn => $cz) {

    $str.= "('".$k_sn."', '".$cz."'),";
}
$sql = "insert into `mase_czys_hxw` values".rtrim($str, ',');
//echo $sql;die;
$db->query($sql);exit('yes');

$sql = "update `warehouse_shipping`.`warehouse_goods` `a`, `warehouse_shipping`.`mase_czys_hxw` `b` set `a`.`caizhi` = `b`.`caizhi` where `a`.`goods_id` = `b`.`goods_id`";
