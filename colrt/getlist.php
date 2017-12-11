<?php
define('IN_ECS', true);
define('ROOT_PATHS', str_replace('getlist.php', '', str_replace('\\', '/', __FILE__)));
require(ROOT_PATHS.'includes_website/init.php');
$ms['bespok_remark'] = 'hunjie';
$arr = set_bespokes($ms);
$data = array();
foreach ($arr as $k => $v) {
    $data[$k]['mobile'] = $v['mobile'];
    $data[$k]['dp_name'] = getDepartmentByid($v['bespoke_shop']);
}
echo json_encode($data);
/**
 *  电话加密、解密
 */
function mobile_shop_api($vals,$method)
{
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,'http://192.168.1.53:8081/index.php');
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS, "act=".$method."&val=".$vals);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_TIMEOUT,$timeout);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
function getDepartmentByid($id){
    $sql = "SELECT `a`.`shop_name` FROM `ecs_shop_cfg` AS `a` , `ecs_department_channel` AS `b` WHERE `a`.`dep_id` = `b`.`dc_id` AND `b`.`is_sec` = 1 AND `b`.`dc_id` = ".$id;
    $res = $GLOBALS['db']->getOne($sql);
    return $res;
}

function set_bespokes($ms)
{
    $api_url = "http://order.kela.cn/yuyue.php?a=Bespoke&api=yuyue&act=list&gundong=1";
    $fields_string = http_build_query ( $ms, '&' );
    //post形式提交
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$api_url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120 );
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
    curl_setopt($ch, CURLOPT_POST, 1); // 发送一个常规的Post请求 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string );
    $data = curl_exec($ch);
    $return_res = json_decode($data, true);
    //返回信息
    return $return_res;
}

