<?php
header("Content-type:text/html;charset=utf8;");
set_time_limit(0);
error_reporting(E_ALL);

$localhost = '127.0.0.1';
$db_user   = 'root';
$db_pass   = '';

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

$sql = "select * from `sales_channels_person`;";
$result = $mysqli->query($sql);
$person_data = array();
$person_data = con($result);

$sql = "SELECT `m`.*,`u`.`account`,`u`.`real_name` FROM `organization` AS `m` LEFT JOIN `user` AS `u` ON `m`.`user_id`=`u`.`id` WHERE `u`.`is_enabled`='1' AND `u`.`is_deleted`='0' AND `m`.`dept_id` IN(48)";
$result = $mysqli->query($sql);
$pa_data = array();
$pa_data = con($result);
$tu_data = array();
foreach ($pa_data as $key => $value) {
    # code...
    $tu_data[] = $value['user_id'];
}

$dp_leader_name =array();
foreach ($person_data as $key => $value) {
    # code...
    if($value['dp_people_name'] != ''){

        $dp_leader_name[$key] = explode(',', $value['dp_people_name']);
    }
}


$userData = array();

foreach ($dp_leader_name as $key => $value) {
    # code...
    foreach ($value as $k => $v) {
        # code...
        # 
        if($v){
            $userData[$v] = $v;
        }
    }
}
$rel_data = array();
foreach ($userData as $key => $value) {
    # code...
    $sql = "select `id` from `user` where `account` = '{$value}'";
    $result = $mysqli->query($sql);
    $user_name = con($result);
    if($user_name[0]['id']){
        $user_id = $user_name[0]['id'];

        if(!in_array($user_id, $tu_data)){
            $rel_data[$user_id] = $value;
        }
    }
}
echo '<pre>';
print_r($rel_data);die;

$str = '';
foreach ($rel_data as $key => $value) {
    # code...
    $str.= "(48,12,5,{$key}),";
    
}
$str = rtrim($str,",");

$sql = "insert into `organization` (`dept_id`,`position`,`level`,`user_id`) values ".$str;

echo '<pre>';
print_r($sql);die;