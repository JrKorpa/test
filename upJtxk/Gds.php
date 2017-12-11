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

$name = "goods.csv";

$file = fopen($name,"r");
while(! feof($file))
{
    $data[] = fgetcsv($file);
}
fclose($file);

$data = array_filter(eval('return '.iconv('gbk','utf-8',var_export($data,true)).';'));

foreach ($data as $key => $value) {

    $sql = "insert into `new_style_info` values(null, '".$value[1]."', '".$value[0]."', '".$value[2]."')";
    $db->query($sql);
    echo $sql."<br>";
}