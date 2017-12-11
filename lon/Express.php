<?php
header("Content-type: text/html;charset=utf-8");
set_time_limit(0);
error_reporting(E_ALL);

$localhost = '192.168.0.131';
$db_user   = 'root';
$db_pass   = '123456';

//创建对象并打开连接，最后一个参数是选择的数据库名称 
$mysqli = new mysqli($localhost, $db_user ,$db_pass, 'cuteframe');
//检查连接是否成功 
if (mysqli_connect_errno()){ 
    //注意mysqli_connect_error()新特性 
    die('Unable to connect!'). mysqli_connect_error(); 
}

function con($arr)
{
    $data_info = array();
    while($row = $arr->fetch_array(MYSQLI_ASSOC)){

        $data_info[] = $row;
    }

    return $data_info;
}

/*BOSS-1110；
 1. 物流派送总档 （功能： 输入指定物流公司，指定区域，指定时间内，若订单送货地址正好符合这个条件，则自动其他物流公司。现在公司使用的就3个， 只要中通，顺丰，有这个效果就可以 。若这2个的物流公司的条件内都符合，则由物流部自动选择转EMS.
此事1月26日需要上线使用
 */

//取出所以快递公司的信息（主要包含特殊处理的信息）；
$sql = "select `id`,`pause_send_time`,`recovery_send_time`,`pause_exp_areas` from `cuteframe`.`express`";

$result = $mysqli->query($sql);
$expressInfo = array();
$expressInfo = con($result);

//取出需要做出限制的快递信息
foreach ($expressInfo as $key => $value) {
    # code...
    if($value['pause_send_time'] == '' || $value['pause_send_time'] == '0000-00-00 00:00:00' || $value['recovery_send_time'] == '' || $value['recovery_send_time'] == '0000-00-00 00:00:00' || $value['pause_exp_areas'] == ''){
        unset($expressInfo[$key]);//如果不需要特殊处理则删除
    }
}

//如果有特殊处理的订单号；
if(!empty($expressInfo)){

    //根据快递信息和时间要求查出需要特殊处理的订单信息；
    foreach ($expressInfo as $key => $value) {
        # code... 条件：已审核、未发货和允许发货、'配送方式：总公司到客户、订单审核时间区间、发货物流、无法发货地区；
        $sql = "select `boi`.`id`,`boi`.`order_sn` from `app_order`.`base_order_info` `boi` inner join `app_order`.`app_order_address` `aoa` on `boi`.`id` = `aoa`.`order_id` where `boi`.`order_status` = 2 and `boi`.`send_good_status` in(1,4) and `aoa`.`distribution_type` <> 1 and `boi`.`check_time` >= '".$value['pause_send_time']."' and `boi`.`check_time` <= '".$value['recovery_send_time']."' and `aoa`.`express_id` = ".$value['id']." and `aoa`.`province_id` in(".$value['pause_exp_areas'].")";
        echo $sql;die;
        $result = $mysqli->query($sql);
        $list = array();
        if($result){

            $list = con($result);
        }
        
        $orderInfo[] = $list;
    }

    //如果查出在条件范围内的订单，将物流方式改为顺风发货；统一用顺风，有问题找业务；
    $expressIdArr = array();
    if(!empty($orderInfo)){

        foreach ($orderInfo as $key => $value) {
            # code...
            if(!empty($value)){

                foreach ($value as $k => $v) {
                    # code...
                    $expressIdArr[] = $v['id']; 
                }
            }
        }
    }
    //需要修改的订单；
    //echo '<pre>';
    //var_dump($expressIdArr);
    if(!empty($expressIdArr)){

        $sql = "update `app_order`.`app_order_address` set `express_id` = 4 where `order_id` in(".implode(",", $expressIdArr).")";
        //echo $sql;die;
        $mysqli->query($sql);//修改物流方式为顺风：暂时；
    }
}

