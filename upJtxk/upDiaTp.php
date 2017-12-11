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
	'dsn'=>"mysql:host=192.168.0.95;dbname=app_order",
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

/*
历史订单数据需要清洗至少近一个月的数据，以备做数据对比，商品类型是现货默认现货钻，商品类型期货，没证书号的就是现货钻，有证书号的再去裸钻列表判断裸钻的类型是现货还是期货，证书号需要去掉GIA、EGL等字符再去匹配，找不到的证书号也默认是现货钻；
 */
$db = new MysqlDB($new_conf);

$time = $_REQUEST['time'];
if(!$time){
    exit('die');
}

$sql = "select `od`.`id`,`od`.`is_stock_goods`,`od`.`goods_type`,`od`.`zhengshuhao` from `base_order_info` `oi` 
inner join `app_order_details` `od` on `oi`.`id` = `od`.`order_id`
where `oi`.`order_status` = 2 
and `oi`.`create_time` >= '$time'";
//echo $sql;die;
$data = $db->getAll($sql);
if($data){
    foreach ($data as $key => $val) {
        # code...
        if($val['goods_type'] == 'qiban' || $val['goods_type'] == 'caizuan_goods'){//起版、彩钻默认是期货
            $dia_type = 2;
        }else{
            if($val['is_stock_goods'] == 1){//现货
                $dia_type = 1;
            }elseif($val['is_stock_goods'] == 0 && $val['zhengshuhao'] == ''){//期货
                $dia_type = 1;
            }elseif($val['is_stock_goods'] == 0 && $val['zhengshuhao'] != ''){
                $cert_id = str_replace(array("GIA", "EGL"), "", $val['zhengshuhao']);
                $sql="select `good_type` from `front`.`diamond_info` where `cert_id`='".$cert_id."'";
                $check_dia = $db->getRow($sql);
                if(empty($check_dia)){
                    $sql="select `good_type` from `front`.`diamond_info_all` where `cert_id`='".$cert_id."'";
                    $check_dia = $db->getRow($sql);
                }
                if(!empty($check_dia) && isset($check_dia['good_type'])){
                    if($check_dia['good_type'] == 1){
                        $dia_type = 1;
                    }elseif($check_dia['good_type'] == 2){
                        $dia_type = 2;
                    }else{
                        $dia_type = 0;
                    }
                }else{
                    $dia_type = 1;
                }
            }else{
                $dia_type = 0;
            }//判断是现货钻 1、期货钻 2
        }
        //echo $dia_type;die;
        $sql = "update `app_order_details` set `dia_type` = '".$dia_type."' where `id` = ".$val['id'];
        $res = $db->query($sql);
        echo $val['id']."|".$dia_type."<br>";
    }
}
