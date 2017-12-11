<?php	
/*.
将订单商品和商品列表 进行绑定。
规则：
1.订单商品的 id = 商品列表的 order_goods_id
2.通过销售单中商品
3.销售单中商品 属性：1.款号相同 2.同时间价格相等
*/

error_reporting(E_ALL);
header("Content-type:text/html;charset=utf8;");
define('ROOT_PATH', str_replace('order_goods_id.php', '', str_replace('\\', '/', __FILE__)));
require_once(ROOT_PATH.'MysqlDB.class.php');
require_once(ROOT_PATH.'function.php');
set_time_limit(0);
date_default_timezone_set("PRC");
ini_set('memory_limit','2000M');


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

$conNew = new MysqlDB($conNewConf);
$conNewW = new MysqlDB($conNewConf_W);

$data = $argv[1];
//$data = $_REQUEST['data'];
if(empty($data)){
    echo "data can't be empty. ";
}else{
    if(!preg_match('/^\d{4}/',$data)){
        echo "data preg faild";die;
    }
}

$sql="SELECT id FROM `app_order`.`base_order_info` where 1 ";
//$sql.=" AND order_sn = '2015041113716' ";
$sql.=" AND create_time like '$data%' ";
$sql.=" AND id <= 1935211  ";
//$sql.=" AND id =1410628 ";
//echo $sql;die;
$orderlist = $conNew->getAll($sql);

$i=0;
$c = count($orderlist);
foreach($orderlist as $order){
//1.订单
    $i++;

    $p = $i*100/$c;
    
    $order_id = $order['id'];
    //$order_id = $_REQUEST['id'];
    $sql = "SELECT * FROM `app_order`.`base_order_info` where id = '".$order_id."';";
    $row = $conNew->getRow($sql);
    echo $p;
    echo "%--";
    echo $order_sn = $row['order_sn'];
    echo "\r\n";
    $orderInfo = $row;

    $sale_order_sql = "select * from warehouse_shipping.warehouse_bill where bill_type='S' AND bill_status = 2 AND order_sn = '$order_sn';";
    $sale_order = $conNew->getRow($sale_order_sql);
    //var_dump($sale_order);
    //die;

    $order_goods_sql =  "select * from app_order.app_order_details where order_id = '$order_id';";
    $order_goods = $conNew->getAll($order_goods_sql);
    if(empty($order_goods)){
        continue;
    }
    $order_goods_list=array();
    foreach($order_goods as $key => $val)
    {
        $val['real_price'] = $val['goods_price'];
        if($val['favorable_status'] == 3){
            $val['real_price'] -= $val["favorable_price"];
        }
        //var_dump($val);
        //echo "<hr>";
        $order_goods_list[$val['id']] = $val;
    }

    if(empty($sale_order)){
        continue;
    }

    $bill_no = $sale_order['bill_no'];
    if(empty($bill_no)){
        continue;
    }
    $sale_goods_sql =  "select * from warehouse_shipping.warehouse_bill_goods where bill_no = '$bill_no';";
    $sale_goods = $conNew->getAll($sale_goods_sql);
    $sale_goods_list = array();
    foreach($sale_goods as $key => $val)
    {
        $sale_goods_list[$val['goods_id']] = $val;
    }
    if(empty($sale_goods_list)){
        continue;
    }
    $r = array();
    $o_ids = array();
    $s_ids = array();
    $r = array();
    foreach($order_goods_list as $key => & $val){
        foreach($sale_goods_list as $k => & $v){
            //echo "<hr>";
            //var_dump($val['goods_sn'] , $v['goods_sn'] , $val['real_price'] , $v['xiaoshoujia']);
            if(!in_array($val['id'],$o_ids) && !in_array($v['goods_id'],$s_ids) && $val['goods_sn'] == $v['goods_sn'] && $val['real_price'] == $v['xiaoshoujia']){

                $o_ids[] = $val['id'];
                $s_ids[] = $v['goods_id'];

                unset($order_goods_list[$key]);
                unset($sale_goods_list[$k]);
                $rel= array('goods_id'=> $v['goods_id'],'order_goods_id'=> $val['id'],'order_id'=>$order_id,'bill_no'=>$bill_no,'bill_goods_id'=>$v['id']);
                //$ret = $conNewW->insert('toboss_order_goods_id',$rel);
                //var_dump($ret);
                $r[] = $rel;
            }
        }
    }
    if(!empty($order_goods_list)){
        foreach($order_goods_list as $key => $val){
            $rel= array('goods_id'=> 0,'order_goods_id'=> $val['id'],'order_id'=>$order_id,'bill_no'=>'','bill_goods_id'=>0);
            $ret = $conNewW->insert('toboss_order_goods_id',$rel);
            $r[] = $rel;
        }
    }
    if(!empty($sale_goods_list)){
        foreach($sale_goods_list as $key => $val){
            $rel= array('goods_id'=> $val['goods_id'],'order_goods_id'=> 0,'order_id'=>$order_id,'bill_no'=>$bill_no,'bill_goods_id'=>$val['id']);
            $ret = $conNewW->insert('toboss_order_goods_id',$rel);
            $r[] = $rel;
        }
    }

//    var_dump($r);die;

    $rel_key= array('goods_id'=> 0,'order_goods_id'=> 0,'order_id'=>0,'bill_no'=>0,'bill_goods_id'=>0);


    $sql = "INSERT INTO `toboss_order_goods_id`(".implode(',',array_keys($rel_key)).") values ";
    foreach($r as $key => $val){
        $sql .= " ('".implode("','",$val)."'),";
    }
    $sql=trim($sql,",");
    //echo $sql;
    $conNewW->query($sql);
}



/*

SELECT 
CONCAT("update warehouse_goods set order_goods_id = ",order_goods_id," where goods_id = ",goods_id,";")
FROM `toboss_order_goods_id` 
where order_goods_id >0 AND goods_id >0;


*/