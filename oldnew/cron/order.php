<?php	
/*
http://192.168.0.157:8181/browse/BOSS-997
要求只导入最终状态的数据，不理中间状态，相关信息如下:
1. 迁移有效订单到boss订单表；
2. 迁移步骤1中的订单明细到boss订单明细表；
3. 对于步骤2中的订单明细商品，需要将对应商品信息迁移至boss仓库商品列表且状态为绑定订单；
4. 迁移步骤1中订单的用户信息到boss顾客信息表；
5. 迁移步骤1中对应的发票到boss发票表；
6. 迁移步骤1中订单的销售单及销售单明细到boss对应表；
7. 迁移步骤1中订单金额到boss对应的订单金额表中；
8. 迁移步骤1中订单的操作日志到boss对应的操作日志表；

1.有效订单为所有订单。针对各自都要有对应关系。

2.
ecs_order_info 
base_order_info

3.
ecs_order_goods
app_order_details

4.
ecs_order_info
app_member_info

5.
ecs_order_info
app_order_invoice

6.
jxc_order
jxc_order_goods
type=S AND status=2

7.
ecs_order_info
app_order_account

8.
ecs_order_action
app_order_action


*/

error_reporting(E_ALL);
header("Content-type:text/html;charset=utf8;");
define('ROOT_PATH', str_replace('order.php', '', str_replace('\\', '/', __FILE__)));
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
$t1 = microtime(true);

$conOld = new MysqlDB($conOldConf);
$conNew = new MysqlDB($conNewConf);
$conNewW = new MysqlDB($conNewConf_W);
$conNewF = new MysqlDB($conNewConf_F);

$data = $argv[1];
//$data = $_REQUEST['data'];
if(empty($data)){
    echo "data can't be empty. ";
}else{
    if(!preg_match('/^\d{4}/',$data)){
        echo "data preg faild";die;
    }
}

$sql="select order_id from kela_order_part.ecs_order_info where 1 ";
//$sql.=" AND order_sn = '2015041113716' ";
$sql.=" AND order_time like '$data%'";
//echo $sql;die;
$all = $conOld->getAll($sql);

if($all){
    foreach($all as $key => $o){
	    $order_id = $o['order_id'];
	    $sql="select * from kela_order_part.ecs_order_info where order_id = $order_id ;";
	    $row = $conOld->getRow($sql);
	    //1.订单
	    $order_sn = $row['order_sn'];
	    $favorable_price=0;
	    $unfavorable_price=0;
	    $orderInfo = $row;

	    $orderNewInfo = array();
	    //订单基本信息
	    $orderNewInfo['id'] = $orderInfo['order_id'];
	    $orderNewInfo['order_sn'] = $orderInfo['order_sn'];
	    $orderNewInfo['old_order_id'] = $orderInfo['order_id'];
	    $orderNewInfo['bespoke_id'] = $orderInfo['bespoke_id'];//确认一致
	    $orderNewInfo['old_bespoke_id'] = $orderInfo['bespoke_id'];

	    //订单各类状态
	    //订单状态 old=>new
	    //SQL：SELECT COUNT(*) AS `行数`, `order_status` FROM `ecs_order_info` GROUP BY `order_status` ORDER BY `order_status`
	    //老订单审核状态：$order_status=array(0=>'未审核',1=>'已审核',2=>'审核未通过',3=>'无效',4=>'退货',5=>'关闭');
	    //新订单审核状态1无效（默认待审核）2已审核3取消4关闭
	    $order_status = 99;
	    if($orderInfo['order_status'] == 0){
		$order_status = 1;
	    }
	    if($orderInfo['order_status'] == 1){
		$order_status = 2;
	    }
	    if($orderInfo['order_status'] == 5){
		$order_status = 4;
	    }
	    if($orderInfo['order_status'] == 3){
		$order_status = 3;
	    }
	    $orderNewInfo['order_status'] = $order_status;//99为出错状态

	    //支付状态 old=>new 
	    //SQL：SELECT COUNT(*) AS `行数`, `pay_status` FROM `ecs_order_info` GROUP BY `pay_status` ORDER BY `pay_status`
	    //$pay_status=array('0'=>'未付款','1'=>'付款中','2'=>'已付款','3'=>'网络付款','4'=>'支付定金','5'=>'财务备案');
	    //新支付状态:1未付款2部分付款3已付款4财务备案
	    $pay_status=99;
	    if($orderInfo['pay_status'] == 0 || $orderInfo['pay_status'] == 1){
		$pay_status=1;
	    }
	    if($orderInfo['pay_status'] == 2){
		$pay_status=3;
	    }
	    if($orderInfo['pay_status'] == 4){
		$pay_status=2;
	    }
	    if($orderInfo['pay_status'] == 5){
		$pay_status=4;
	    }
	    $orderNewInfo['order_pay_status'] = $pay_status;//99为出错状态
	    //订购方式
	    //SQL:SELECT COUNT(*) AS `行数`, `pay_name` FROM `ecs_order_info` GROUP BY `pay_name` ORDER BY `pay_name`
	    $pay_name = $orderInfo['pay_name'];
	    $orderNewInfo['order_pay_type'] = getPayType($pay_name);

	    //发货状态
	    //老发货状态：$shipping_status=array(0=>'未发货',1=>'已发货',2=>'已收货',3=>'允许发货',4=>'已到店');
	    //新发货状态:1未发货2已发货3收货确认4允许发货5已到店
	    $send_good_status=99;
	    if($orderInfo['shipping_status'] == 0){
		$send_good_status=1;
	    }
	    if($orderInfo['shipping_status'] == 1){
		$send_good_status=2;
	    }
	    if($orderInfo['shipping_status'] == 2){
		$send_good_status=3;
	    }
	    if($orderInfo['shipping_status'] == 3){
		$send_good_status=4;
	    }
	    if($orderInfo['shipping_status'] == 4){
		$send_good_status=5;
	    }
	    $orderNewInfo['send_good_status'] = $send_good_status;//99为出错状态

	    //配送状态
	    //老系统：$peihuo_status_word = array('0'=>'未配货', '1'=>'配货中', '2'=>'配货缺货', '3'=>'已配完');
	    //新系统：$delivery_status = array('1'=>'未配货',  '2'=>'允许配货', '3'=>'配货中', '4'=>'配货缺货', '5'=>'已配货', '6'=>'无效');
	    $peihuo_status = 99;
	    if($orderInfo['peihuo_status'] == 0){
		$peihuo_status = 1;
	    }
	    if($orderInfo['peihuo_status'] == 1){
		$peihuo_status = 3;
	    }
	    if($orderInfo['peihuo_status'] == 2){
		$peihuo_status = 4;
	    }
	    if($orderInfo['peihuo_status'] == 3){
		$peihuo_status = 5;
	    }
	    $orderNewInfo['delivery_status'] = $peihuo_status;

	    //布产状态
	    //新布产状态：1未操作,2已布产,3生产中,4已出厂,5不需布产
	    $buchan_status = $orderInfo['buchan_status'];
	    $orderNewInfo['buchan_status'] = $orderInfo['buchan_status'];//99为出错状态

	    //客户来源
	    $from_ad = $orderInfo['from_ad'];
	    $orderNewInfo['customer_source_id'] = getCustomerSource($from_ad);

	    //销售渠道
	    $department = $orderInfo['department'];
	    $department_name = getOldDepartmentName($department);
	    $department_id = getNewDepartmentId($department_name);
	    $orderNewInfo['department_id'] = $department_id;

	    $orderNewInfo['create_time'] = $orderInfo['order_time'];
	    $orderNewInfo['create_user'] = $orderInfo['make_order'];
	    $orderNewInfo['check_time'] = date("Y-m-d H:i:s",$orderInfo['confirm_time']);
	    $orderNewInfo['check_user'] = $orderInfo['make_order'];//审单人使用制单人

	    $orderNewInfo['genzong'] = $orderInfo['genzong'];
	    $orderNewInfo['recommended'] = $orderInfo['recommended'];
	    $orderNewInfo['modify_time'] = date("Y-m-d H:i:s");
	    $orderNewInfo['order_remark'] = $orderInfo['postscript'];

	    $referer_allow = array('网络订单','婚博会','展厅订单','批量导入','快速入单','管理员添加','工厂备货单','系统抓单','淘宝用户下单','主站','本站','网站');
	    $orderNewInfo['referer'] = '异常';
	    if(in_array($orderInfo['referer'],$referer_allow))
	    {
		$orderNewInfo['referer'] = $orderInfo['referer'];
	    }
	    elseif(strpos($orderInfo['referer'],'管理员添加') !== false)
	    {
		$orderNewInfo['referer'] = '管理员添加';
	    }
	    elseif(strpos($orderInfo['referer'],'婚博会') !== false)
	    {
		$orderNewInfo['referer'] = '婚博会';
	    }
	    elseif(strpos($orderInfo['referer'],'展厅') !== false)
	    {
		$orderNewInfo['referer'] = '展厅订单';
	    }
	    elseif($orderInfo['referer'] == '')
	    {
		$orderNewInfo['referer'] = '未知';
	    }

	    $orderNewInfo['is_delete'] = 0;
	    $orderNewInfo['apply_close'] = $orderInfo['apply_close'];
	    $orderNewInfo['is_xianhuo'] = $orderInfo['buchan_status']==0?1:0;
	    $orderNewInfo['is_print_tihuo'] = $orderInfo['print_thd']>0?1:0;
	    $orderNewInfo['effect_date'] = $orderInfo['effect_date'];
	    $orderNewInfo['is_zp'] = $orderInfo['is_zp'];
	    $orderNewInfo['pay_date'] = $orderInfo['first_pay_time'];


	    $apply_return = 0;
	    $orderNewInfo['apply_return'] = $apply_return;

	    //维修状态
	    $weixiu_status = 0;
	    $orderNewInfo['weixiu_status'] = $weixiu_status;

	    $shipfreight_time = $orderInfo['shipping_time'];
	    $is_real_invoice = $orderInfo['need_inv'];
	    $out_company = getCompany($is_real_invoice);

	    //用户信息
	    $orderNewInfo['user_id'] = 0;//无法确认用户是否还是以前那个自增的ID
	    $orderNewInfo['consignee'] = $orderInfo['consignee'];//顾客姓名
	    $orderNewInfo['mobile'] = getMobile($orderInfo);//电话字段


	    //var_dump($orderInfo,$orderNewInfo);
	    //delete FROM `base_order_info` where id = 1935207;
	    $ret = $conNew->insert('base_order_info',$orderNewInfo);
	    $conNewW->insert('toboss',array('tb'=>'base_order_info','zid'=>$order_id,'order_sn'=>$order_sn,'order_id'=>$order_id));
	    //var_dump($ret);
	    echo "1.基础信息{$order_sn}-{$order_id}生成成功"."<hr>\r\n";
    }
}

$t2 = microtime(true);
echo '耗时'.round($t2-$t1,3).'秒';



