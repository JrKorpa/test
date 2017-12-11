<?php
error_reporting(E_ALL);
header("Content-type:text/html;charset=utf8;");
define('ROOT_PATH', str_replace('toboss.php', '', str_replace('\\', '/', __FILE__)));
require_once(ROOT_PATH.'MysqlDB.class.php');
require_once(ROOT_PATH.'function.php');
set_time_limit(0);
date_default_timezone_set("PRC");
ini_set('memory_limit','2000M');

$conOldConf = [
    'dsn'=>"mysql:host=192.168.0.91;dbname=kela_order_part",
    'user'=>"root",
    'password'=>"123456",
    'charset' => 'utf8'
];
$conNewConf = [
    'dsn'=>"mysql:host=192.168.0.91;dbname=app_order",
    'user'=>"root",
    'password'=>"123456",
    'charset' => 'utf8'
];

$conOld = new MysqlDB($conOldConf);
$conNew = new MysqlDB($conNewConf);


$data = '2015-03-03';
//$data = $_REQUEST['data'];
if(empty($data)){
    echo "data can't be empty. ";
}

echo $sql="select * from kela_order_part.ecs_order_info where order_time like '$data%';";
$all = $conOld->getAll($sql);

if($all){
    foreach($all as $key => $val){
        $order_id = $val['order_id'];
        $order_sn = $val['order_sn'];
        $sql="select * from app_order.base_order_info where id = '{$order_id}';";
        $order = $conNew->getRow($sql);
        if($order){
            echo "订单号 {$order_sn} ,已存在 "."<hr>";
        }else{
            echo "订单号 {$order_sn}  "."<hr>";
            file_get_contents("http://cuteframe.kela.cn/cron/sales/order_delivery.php?id=".$order_id);
            //sleep(1);
        }
    }
}
