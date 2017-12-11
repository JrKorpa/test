<?php
error_reporting(E_ALL);
header("Content-type:text/html;charset=utf8;");
define('ROOT_PATH', str_replace('clear.php', '', str_replace('\\', '/', __FILE__)));
require_once(ROOT_PATH.'MysqlDB.class.php');
require_once(ROOT_PATH.'function.php');
set_time_limit(0);
date_default_timezone_set("PRC");
ini_set('memory_limit','2000M');


$conNewConf_W = [
    'dsn'=>"mysql:host=192.168.0.91;dbname=warehouse_shipping",
    'user'=>"root",
    'password'=>"123456",
    'charset' => 'utf8'
];


$conNewW = new MysqlDB($conNewConf_W);


$sql="DELETE FROM app_order.`app_order_address` where order_id < 1935211;
DELETE FROM app_order.`app_order_action` where order_id < 1935211;
DELETE FROM app_order.`app_order_details` where order_id < 1935211;
delete FROM app_order.`base_order_info` where id < 1935211;
DELETE FROM app_order.`app_order_account` where order_id < 1935211;
DELETE FROM app_order.`app_order_invoice` where order_id < 1935211;
delete from warehouse_shipping.warehouse_bill where bill_type='S' AND bill_status=2 AND length(bill_no)<10;
delete from warehouse_shipping.warehouse_bill_goods where bill_type='S' AND length(bill_no)<10;
truncate warehouse_shipping.toboss;
truncate warehouse_shipping.toboss_order_goods_id; ";
$all = $conNewW->query($sql);