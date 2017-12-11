<?php

error_reporting(E_ALL);
header("Content-type:text/html;charset=utf8;");
define('ROOT_PATH', str_replace('user.php', '', str_replace('\\', '/', __FILE__)));
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
$conNewConf_W = [
    'dsn'=>"mysql:host=192.168.0.91;dbname=warehouse_shipping",
    'user'=>"root",
    'password'=>"123456",
    'charset' => 'utf8'
];
$conNewConf_F = [
    'dsn'=>"mysql:host=192.168.0.91;dbname=front",
    'user'=>"root",
    'password'=>"123456",
    'charset' => 'utf8'
];

$conOld = new MysqlDB($conOldConf);
$conNew = new MysqlDB($conNewConf);
$conNewW = new MysqlDB($conNewConf_W);
$conNewF = new MysqlDB($conNewConf_F);


$data = $argv[1];
//$data = $_REQUEST['data'];
if(empty($data)){
    echo "data can't be empty. ";
}else{
    if(!preg_match('/^\d{4}-\d{2}/',$data)){
        echo "data preg faild";die;
    }
}

//用户信息
$sql="select id,order_sn,mobile,consignee,customer_source_id,department_id,create_user,create_time from app_order.base_order_info where create_time like '$data%' AND user_id = 0;";
$sql='';
$all = $conOld->getAll($sql);

foreach($all as $order){
    $order_id = $order['id'];
    $order_sn = $order['order_sn'];

    $orderNewInfo = array();
    $orderNewInfo['mobile'] = $order['mobile'];
    $orderNewInfo['consignee'] = $order['consignee'];
    $orderNewInfo['customer_source_id'] = $order['customer_source_id'];
    $orderNewInfo['department_id'] = $order['department_id'];
    $orderNewInfo['create_user'] = $order['create_user'];
    $orderNewInfo['create_time'] = $order['create_time'];

    $sql = "SELECT * FROM `app_order`.`app_order_address` where order_id = '".$order_id."';";
    $app_order_addressInfo = $conNew->getRow($sql);
    if($app_order_addressInfo)
    {
        $app_order_addressInfo['country_id'] = intval($app_order_addressInfo['country_id']); 
        $app_order_addressInfo['province_id'] = intval($app_order_addressInfo['province_id']); 
        $app_order_addressInfo['city_id'] = intval($app_order_addressInfo['city_id']); 
        $app_order_addressInfo['regional_id'] = intval($app_order_addressInfo['regional_id']); 
        $app_order_addressInfo['address'] = trim($app_order_addressInfo['address']); 
    }else{
        $app_order_addressInfo['country_id'] = 0;
        $app_order_addressInfo['province_id'] = 0;
        $app_order_addressInfo['city_id'] = 0; 
        $app_order_addressInfo['regional_id'] = 0; 
        $app_order_addressInfo['address'] = ''; 
    }

    if(preg_match('/^1\d{10}$/',$orderNewInfo['mobile'])){
        $member_sql = "SELECT * FROM `front`.`base_member_info` where member_phone='".$orderNewInfo['mobile']."'; ";
        $member_info=$conNew->getRow($member_sql);
        if($member_info){
            echo "用户已存在"; 
            $member_id = $member_info['member_id'];
        }else{
            $member = array();

            $member['country_id'] = $app_order_addressInfo['country_id'];
            $member['province_id'] = $app_order_addressInfo['province_id'];
            $member['city_id'] = $app_order_addressInfo['city_id'];
            $member['region_id'] = $app_order_addressInfo['regional_id'];
            $member['source_id'] = 0;
            $member['member_name'] = $orderNewInfo['consignee'];
            $member['department_id'] = $orderNewInfo['department_id'];
            $member['customer_source_id'] = $orderNewInfo['customer_source_id'];

            $member['mem_card_sn'] = '';
            $member['member_phone'] = $orderNewInfo['mobile'];
            $member['member_age'] = 0;
            $member['member_qq'] = '';
            $member['member_email'] = '';
            $member['member_aliww'] = '';
            $member['member_dudget'] = 0;
            $member['member_maristatus'] = 0;
            $member['member_address'] = $app_order_addressInfo['address'];
            $member['member_peference'] = 0;
            $member['member_type'] = 3;
            $member['member_truename'] = $orderNewInfo['consignee'];
            $member['member_tel'] = '';
            $member['member_msn'] = '';
            $member['member_sex'] = 1;
            $member['member_birthday'] = NULL;
            $member['member_wedding'] = NULL;
            $member['member_question'] = NULL;
            $member['member_answer'] = NULL;
            $member['reg_time'] = strtotime($orderNewInfo['create_time']);
            $member['last_login'] = 0;
            $member['last_ip'] = '';
            $member['visit_count'] = 0;
            $member['head_img'] = '';
            $member['make_order'] = $orderNewInfo['create_user'];
            $member['email_valid'] = 1;
            $member['complete_info'] = 1;
            $member['member_password'] = '';

            $member_id = $conNewF->insert('base_member_info',$member);
            $conNewW->insert('toboss',array('tb'=>'base_member_info','zid'=>$member_id,'order_sn'=>$order_sn,'order_id'=>$order_id));
        }
        if($member_id){
            $conNew->update('base_order_info',array('user_id',$member_id)," order_id = '{$order_id}'");
            echo "3.用户信息-{$member_id}-{$order_id}更新成功"."<hr>";
        }
    }
}

$sql="select order_id,order_sn from kela_order_part.ecs_return_goods where (leader_status=0 OR (leader_status=1 AND (order_goods_id=0 AND deparment_finance_status=0) OR (order_goods_id>0 AND goods_status=0)));";
$all = $conOld->getAll($sql);

foreach($all as $key => $val)
{
    $id = $val['order_id'];
    $sql="update app_order.base_order_info set apply_return=1 where id = $id;";
    $conNew->query($sql);
}
