<?php
/**
 * H5 wish
 * @param   string  $act  行为
 * @param   string  $url  biaoshi
 * @return  json  $r  $Author: hxw
 */
define('IN_ECS', true);
define('ROOT_PATHS', str_replace('wish.php', '', str_replace('\\', '/', __FILE__)));
require(ROOT_PATHS.'includes_website/init.php');
//header("Content-type:text/html;charset=utf-8");
$db_host = $db_host_2 = $db_host_3 = $db_host_memcache = "192.168.1.60";
$db_name   = "kela_order_part";
$db_user   = "part_order";
$db_pass   = "sTd253Vs6HKenmRY";
if(false)
{
    $db_host = $db_host_2 = $db_host_3 = $db_host_memcache = "192.168.1.52";
    $db_name   = "kela_order_part";
    $db_user   = "develop";
    $db_pass   = "123456"; 
}
$db1 = new cls_mysql($db_host, $db_user, $db_pass, $db_name);

$act = $_REQUEST['act'] == '' ? '' : $_REQUEST['act'];
$url = $_REQUEST['url'] == '' ? '' : $_REQUEST['url'];

$r = array('err' => 0,'msg' => '','content' => array());
//提交愿望
//act,name,mobile,wish,suggest      {"":"","":""}
if($act == 'insert_info')
{
    $b['xyc_name'] = $_REQUEST['name'] != '' ? $_REQUEST['name'] : '';
    $mobile = $_REQUEST['mobile'] != '' ? $_REQUEST['mobile'] : '';
    $b['xyc_wish'] = $_REQUEST['wish'] != '' ? $_REQUEST['wish'] : '';
    $b['xyc_suggest'] = $_REQUEST['suggest'] != '' ? $_REQUEST['suggest'] : '';

    if(!preg_match('/^1\d{10}$/',$mobile)){
        $r['err'] = 1;
        $r['msg'] = '亲，手机号码不合法！';
        echo json_encode($r);die;
    }
    if(strlen($b['xyc_wish']) > 60){
        $r['err'] = 1;
        $r['msg'] = '亲，愿望不能超过20个字！';
        echo json_encode($r);die;
    }
    $b['xyc_mobile'] = data_en_de_fun($mobile,'en');
    if($b['xyc_name'] == '' || $b['xyc_mobile'] == '' || $b['xyc_wish'] == ''){
        $r['err'] = 1;
        $r['msg'] = '亲，请填写完整信息！';
        echo json_encode($r);die;
    }
    $sql = "SELECT `xyc_name` FROM `zt_mxjj` WHERE `xyc_mobile` = '{$b['xyc_mobile']}'";
    $cus = $GLOBALS['db']->getAll($sql);
    if(count($cus) > 0){
        $r['err'] = 1;
        $r['msg'] = 'sorry！一个电话号码只能许愿一次。';
        echo json_encode($r);die;
    }
    foreach ( $b as $k => $v ){
        if($v != ''){
            $tmp .= '`' . $k . '` = \'' . $v . '\',';
        }
    }
    $tmp = rtrim($tmp,',');
    $sql = "INSERT INTO `zt_mxjj` SET {$tmp}";
    $res = $GLOBALS['db']->query($sql);
    $r['msg'] = '许愿完成！';
    echo json_encode($r);die;
}
elseif($act == 'get_list')//num_lsit 520
{
    $res = array();
    $sql_num = "SELECT count(*) FROM `zt_mxjj`";
    $num = $GLOBALS['db']->getOne($sql_num);
    $sql = "SELECT `xyc_mobile`,`xyc_wish` FROM `zt_mxjj` ORDER BY `id` DESC LIMIT 0,10";
    $res['data'] = $GLOBALS['db']->getAll($sql);
    foreach ($res['data'] as $k => $v) {
        $res['data'][$k]['xyc_mobile'] = data_en_de_fun($v['xyc_mobile'],'de');
    }
    $res['sum'] = $num * 5;
    $r['content'] = $res;
    echo json_encode($r);die;
}
elseif($url == '201505kela520')//520
{
    $lt['name'] = $_REQUEST['bes_name'] != '' ? $_REQUEST['bes_name'] : '';
    $lt['mobile'] = $_REQUEST['bes_phone'] != '' ? $_REQUEST['bes_phone'] : '';
    $lt['sex'] = $_REQUEST['bes_sex'] != '' ? $_REQUEST['bes_sex'] : '';
    $lt['city'] = $_REQUEST['bes_city'] != '' ? $_REQUEST['bes_city'] : '';
    $lt['wish'] = $_REQUEST['bes_address'] != '' ? $_REQUEST['bes_address'] : '';
    if($lt['name'] == '' || $lt['mobile'] == '' || $lt['sex'] == '' || $lt['city'] == '' || $lt['wish'] == ''){
        $r['err'] = 1;
        $r['msg'] = '亲，请填写完整信息！';
        echo json_encode($r);die;
    }
    if(!preg_match('/^1\d{10}$/',$lt['mobile'])){
        $r['err'] = 1;
        $r['msg'] = '亲，手机号码不合法！';
        echo json_encode($r);die;
    }
    $lt['mobile'] = data_en_de_fun($lt['mobile'],'en');
    $sql = "SELECT `name` FROM `zt_wish` WHERE `mobile` = '{$lt['mobile']}'";
    $cus = $GLOBALS['db']->getAll($sql);
    if(count($cus) > 0){
        $r['err'] = 1;
        $r['msg'] = '手机号已有报名记录！';
        echo json_encode($r);die;
    }
    foreach ( $lt as $k => $v ){
        if($v != ''){
            $tmp .= '`' . $k . '` = \'' . $v . '\',';
        }
    }
    $tmp = rtrim($tmp,',');
    $sql = "INSERT INTO `zt_wish` SET {$tmp}";
    $res = $GLOBALS['db']->query($sql);
    $r['msg'] = '报名成功！';
    echo json_encode($r);die;
}
elseif($url == '201505h5lhy')//老会员zt
{
    $mobile = $_REQUEST['phone'] != '' ? $_REQUEST['phone'] : '';
    if($mobile == ''){
        $r['err'] = 1;
        $r['msg'] = '请填写手机号码！';
        echo json_encode($r);die;
    }
    if(!preg_match('/^1\d{10}$/',$mobile)){
        $r['err'] = 1;
        $r['msg'] = '亲，手机号码不合法！';
        echo json_encode($r);die;
    }
    $mobile = data_en_de_fun($mobile,'en');

    $sql = "SELECT `code` FROM `ecs_member_vip` WHERE `mobile` = '{$mobile}'";
    $info = $GLOBALS['db1']->getRow($sql);
    if(!empty($info)){
        $r['err'] = 0;
        $r['content'] = $info['code'];
        echo json_encode($r);die;
    }

    $sql = "SELECT `order_sn`,`mobile` FROM `ecs_order_info` WHERE `mobile` = '{$mobile}'";
    $list = $GLOBALS['db1']->getRow($sql);
    if(empty($list)){
        $r['err'] = 1;
        $r['msg'] = 'sorry！您不是珂兰会员。';
        echo json_encode($r);die;
    }

    $ms['mobile'] = $mobile;
    $ms['order_sn'] = $list['order_sn'];
    $ms['time'] = date('Y-m-d H:i:s',time());
    do{
        $code = random(6,'123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ');
        if(!diff_code($code)){
            break;
        }
    }while(true);
    $ms['code'] = $code;
    foreach ( $ms as $k => $v ){
        if($v != ''){
            $tmp .= '`' . $k . '` = \'' . $v . '\',';
        }
    }
    $tmp = rtrim($tmp,',');
    $sql = "INSERT INTO `ecs_member_vip` SET {$tmp}";
    $res = $GLOBALS['db1']->query($sql);
    $phone = data_en_de_fun($mobile,'de');
    $msl = array("DesNo"=>$phone,"Code"=>$code);
    msm($msl);
    $r['msg'] = '获取CODE成功，稍后兑奖券将已短信形式发送到您的手机，请注意查收！';
    $r['content'] = $code;
    echo json_encode($r);die;
}
elseif($act == 'get_wish')
{
    $sql = "SELECT count(*) FROM `ecs_member_vip`";
    $info = $GLOBALS['db1']->getOne($sql);
    $r['content'] = $info + 650;
    echo json_encode($r);die;
}
elseif($url == '201505520cj')//201505520cj zt
{
    /*$r['err'] = 1;
    $r['msg'] = '只限于520当天，活动已经结束！';
    echo json_encode($r);die; 
    $time = date('Y-m-d H:i:s',time());
    if($time>'2015-5-20 11:59:59'){
        $r['err'] = 1;
        $r['msg'] = '只限于520当天，活动已经结束！';
        echo json_encode($r);die; 
    }*/
    $code_phone = $_REQUEST['code_phone'] != '' ? $_REQUEST['code_phone'] : '';
    if($code_phone == ''){
        $r['err'] = 1;
        $r['msg'] = '请填写兑换码或手机号！';
        echo json_encode($r);die;
    }
    if(!in_array(strlen($code_phone),array(6,11))){
        $r['err'] = 1;
        $r['msg'] = '请输入正确的兑换码或手机号！';
        echo json_encode($r);die;
    }
    $mobile = data_en_de_fun($code_phone,'en');
    $sql = "SELECT `id`,`mobile`,`order_sn` FROM `ecs_member_vip` WHERE (`code` = '{$code_phone}' or `mobile` = '{$mobile}')";
    $codeNum = $GLOBALS['db1']->getRow($sql);

    $sql = "SELECT `order_sn`,`mobile`,`consignee` FROM `ecs_order_info` WHERE `mobile` = '{$mobile}'";
    $orderNum = $GLOBALS['db1']->getRow($sql);

    if(empty($codeNum) && empty($orderNum)){
        $r['err'] = 1;
        $r['msg'] = 'sorry，没有兑换码记录或您不是老会员，请重新输入！';
        echo json_encode($r);die;
    }
    $phone = data_en_de_fun($codeNum['mobile'],'de');
    $sql = "SELECT `id` as `user_id`,`prize`,`rel_name`,`new_phone` FROM `ecs_member_prize` WHERE `new_phone` = '{$phone}'";
    $num = $GLOBALS['db1']->getRow($sql);
    if(empty($num)){
        $phoneOrder = data_en_de_fun($orderNum['mobile'],'de');
        $sql = "SELECT `id` as `user_id`,`prize`,`rel_name`,`new_phone` FROM `ecs_member_prize` WHERE `new_phone` = '{$phoneOrder}'";
        $numOrder = $GLOBALS['db1']->getRow($sql);
        if(!empty($numOrder)){
            $r['err'] = 2;
            $r['msg'] = "手机号或兑换码，已有兑换记录！";
            $r['content'] = $numOrder;
            echo json_encode($r);die;
        }
    }else{
        $r['err'] = 2;
        $r['msg'] = "手机号或兑换码，已有兑换记录！";
        $r['content'] = $num;
        echo json_encode($r);die;
    }
    $r['err'] = 1;
    $r['msg'] = '只限于520当天，活动已经结束！';
    echo json_encode($r);die;
    $sql = "SELECT `prize` FROM `ecs_member_prize`";
    $prizeNum = $GLOBALS['db1']->getAll($sql);
    foreach ($prizeNum as $v) {
        if($v['prize'] == 1){
            $ct['one']++;
        }
        if($v['prize'] == 2){
            $ct['two']++;
        }
        if($v['prize'] == 3){
            $ct['three']++;
        }
        if($v['prize'] == 4){
            $ct['four']++;
        }
    }
    $lol = getCjList($ct);
    //$lol = array('id'=>1,'prize'=>1);
    $orderInfo = array();
    if(empty($codeNum)){
        $orderInfo['rel_name'] = $orderNum['consignee'];
        $orderInfo['new_phone'] = $phoneOrder;
    }else{
        $sql = "SELECT `consignee` FROM `ecs_order_info` WHERE `order_sn` = '{$codeNum['order_sn']}'";
        $consignee = $GLOBALS['db1']->getRow($sql);
        $orderInfo['rel_name'] = $consignee['consignee'];
        $orderInfo['new_phone'] = $phone;
    }
    $orderInfo['prize'] = $lol['prize'];
    $orderInfo['time'] = date('Y:m:d H:i:s',time());
    foreach ( $orderInfo as $k => $v ){
        if($v != ''){
            $tmp .= '`' . $k . '` = \'' . $v . '\',';
        }
    }
    $tmp = rtrim($tmp,',');
    $sql = "INSERT INTO `ecs_member_prize` SET {$tmp}";
    $res = $GLOBALS['db1']->query($sql);
    $sql = "SELECT `id` FROM `ecs_member_prize` WHERE `new_phone` = {$orderInfo['new_phone']}";
    $user_id = $GLOBALS['db1']->getOne($sql);
    $lol['user_id'] = data_en_de_fun($user_id,'en');
    $r['msg'] = '兑换成功！';
    $r['content'] = $lol;
    echo json_encode($r);die;
}
elseif($act == 'insertcj')//201505520cj insertinfo
{
    $user_id = $_REQUEST['user_id'] != '' ? $_REQUEST['user_id'] : '';
    $cj['new_phone'] = $_REQUEST['cj_phone'] != '' ? $_REQUEST['cj_phone'] : '';
    $cj['rel_name'] = $_REQUEST['cj_name'] != '' ? $_REQUEST['cj_name'] : '';
    $cj['time'] = date('Y-m-d H:i:s',time());
    if(!$user_id){
        $r['err'] = 1;
        $r['msg'] = '错误，请检查是否有ID！刷新页重试。';
        echo json_encode($r);die; 
    }
    if($cj['new_phone'] == '' && $cj['rel_name'] == ''){
        $r['err'] = 1;
        $r['msg'] = '填写信息不完整！';
        echo json_encode($r);die;
    }
    if(!preg_match('/^1\d{10}$/',$cj['new_phone'])){
        $r['err'] = 1;
        $r['msg'] = '手机号码不合法！';
        echo json_encode($r);die;
    }
    $user_id = data_en_de_fun($user_id,'de');
    $mobile = $cj['new_phone'];
    /*$sql = "SELECT `prize` FROM `ecs_member_prize` WHERE `new_phone` = '{$mobile}'";
    $num = $GLOBALS['db1']->getRow($sql);
    if(!empty($num)){
        $r['err'] = 1;
        $r['msg'] = "手机号或兑换码，已有兑换记录！";
        $r['content'] = $num['prize'];
        echo json_encode($r);die;
    }*/
    foreach ( $cj as $k => $v ){
        if($v != ''){
            $tmp .= '`' . $k . '` = \'' . $v . '\',';
        }
    }
    $set = rtrim($tmp,',');
    $sql = "UPDATE `ecs_member_prize` SET {$set} WHERE `id` = {$user_id}";
    $res = $GLOBALS['db1']->query($sql);
    if(!$res){
        $r['err'] = 1;
        $r['msg'] = '领取失败！';
        echo json_encode($r);die;
    }
    $r['msg'] = '领取成功！';
    echo json_encode($r);die;
}
elseif($act == 'getCj1505')//201505520cj getprizeinfo
{

    $prizeDtata = array(
        1=>'一等奖“天生一对”钻石对戒（价值7299元）',
        2=>'二等奖银镶绿玉髓吊坠（价值399元）',
        3=>'三等奖砗磲手链（价值299元）',
        4=>'四等奖100元珂兰钻石实体店消费券'
    );
    $sql = "SELECT `prize`,`new_phone` FROM `ecs_member_prize` ORDER BY `id` DESC LIMIT 4";
    $numCj = $GLOBALS['db1']->getAll($sql);
    $resData = array();
    foreach ($numCj as $k => $v) {
        $mobile = substr($v['new_phone'],-4);
        $resData[$k]['mobile'] = $mobile;
        $resData[$k]['prize'] = $prizeDtata[$v['prize']];
    }
    $resData[4] = array('mobile'=>random(4,'1234567890'),'prize'=>$prizeDtata[random(1,'23')]);
    $r['content'] = $resData;
    echo json_encode($r);die;
}
/**
 * 电话加密、解密
 * @param string $type : en=> 加密， de=> 解密
 */
function data_en_de_fun($mobile, $type){
    if($type == 'en'){
        return base64_encode($mobile);
    }elseif($type == 'de'){
        return base64_decode($mobile);
    }else{
        return $mobile;
    }
}

/**
* 产生随机字符串
*
* @param    int        $length  输出长度
* @param    string     $chars   可选的 ，默认为 0123456789
* @return   string     字符串
*/
function random($length, $chars) {
    $hash = '';
    $max = strlen($chars) - 1;
    for($i = 0; $i < $length; $i++) {
        $hash .= $chars[mt_rand(0, $max)];
    }
    return $hash;
}
/**
*检查随机码是否会重复
*/
function diff_code($code){
    $sql = "SELECT `order_sn` FROM `ecs_member_vip` WHERE `code` = '{$code}'";
    $res = $GLOBALS['db1']->getAll($sql);
    return $res;
}
/**
*随机抽奖概率
*/
function getCjList($ct){

    $prize_arr_list = $prize_arr = array(
        '0' => array('id'=>'1','prize'=>'4','v'=>16.5),
        '1' => array('id'=>'2','prize'=>'1','v'=>1),
        '2' => array('id'=>'3','prize'=>'4','v'=>16.5),
        '3' => array('id'=>'4','prize'=>'2','v'=>0),
        '4' => array('id'=>'5','prize'=>'4','v'=>16.5),
        '5' => array('id'=>'6','prize'=>'3','v'=>0),
        '6' => array('id'=>'7','prize'=>'4','v'=>16.5),
        '7' => array('id'=>'8','prize'=>'4','v'=>16.5),
        '8' => array('id'=>'9','prize'=>'3','v'=>0),
        '9' => array('id'=>'10','prize'=>'4','v'=>16.5),
        '10' => array('id'=>'11','prize'=>'2','v'=>0)
    );
    if(!isset($ct) && empty($ct)){
        $prize_arr_list;
    }
    if($ct['one'] >= 1){
        unset($prize_arr_list[1]);
    }
    if($ct['two'] >= 5){
        unset($prize_arr_list[3],$prize_arr_list[10]);
    }
    if($ct['three'] >= 10){
        unset($prize_arr_list[5],$prize_arr_list[8]);
    }
    foreach ($prize_arr_list as $key => $val) {
        $arr[$val['id']] = $val['v'];
    }
    $rid = getRand($arr);
    $res = $prize_arr[$rid-1];
    $result['id'] =  $res['id']-1;
    $result['prize'] = $res['prize'];
    return $result;
}

function getRand($proArr) {
    $result = '';
    $proSum = array_sum($proArr);
    foreach ($proArr as $key => $proCur) {
        $randNum = mt_rand(1, $proSum);
        if ($randNum <= $proCur) {
            $result = $key;
            break;
        } else {
            $proSum -= $proCur;
        }
    }
    unset ($proArr);
    return $result;
}
/**
*发送短信
*/
function msm($msl){
    $ch = curl_init();
    $str ="http://h.1069106.com:1210/services/msgsend.asmx/SendMsg?userCode=klsm&userPass=klsm1818&DesNo={$msl['DesNo']}&Msg=恭喜兑换成功，您的礼品码：{$msl['Code']}，凭礼品码可于5月18日后到店领取礼品。退订回复TD【珂兰钻石】&Channel=1";
    curl_setopt($ch, CURLOPT_URL, $str);
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    $output = curl_exec($ch);
}