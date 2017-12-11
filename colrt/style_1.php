<?php
header("Content-type: text/html;charset=utf-8");
set_time_limit(0);
error_reporting(1);

$localhost = '192.168.0.95';
$db_user   = 'cuteman';
$db_pass   = 'QW@W#RSS33#E#';

//创建对象并打开连接，最后一个参数是选择的数据库名称 
$mysqli = new mysqli($localhost, $db_user ,$db_pass, 'front');
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

$sql = "select * from test.cfg";
$result = $mysqli->query($sql);
$data = con($result);

foreach ($data as $key => $value) {
    $aaa[$value['account']] = $value;
}

$sql = "select account from cuteframe.user";
$result = $mysqli->query($sql);
$aad = con($result);
$sql = '';
//var_dump($data);die;
foreach ($aaa as $key => $value) {
    if(in_array($value['account'], $aad)){
        continue;
    }
     //$sql.= "INSERT INTO `cuteframe`.`user` (`id`, `icd`, `account`, `password`, `code`, `real_name`, `is_on_work`, `is_enabled`, `gender`, `birthday`, `mobile`, `phone`, `qq`, `email`, `address`, `join_date`, `user_type`, `up_pwd_date`, `uin`, `is_system`, `is_deleted`, `is_warehouse_keeper`, `is_channel_keeper`, `company_id`, `role_id`) VALUES (NULL, NULL, '".$value['account']."', '".$value['password']."', '', '".$value['real_name']."', '1', '1', '0', '0000-00-00', '".trim($value['tel'])."', '', '', '".$value['email']."', '', '0000-00-00', '3', '2030778433', NULL, '0', '0', '".$value['is_warehouse_keeper']."', '".$value['is_channel_keeper']."','".$value['company_id']."','".$value['role_id']."');<br/>";
     $sql.="INSERT INTO `cuteframe`.`user_extend_company` (`id`, `user_id`, `company_id`) VALUES (null, '".$value['user_id']."', '".$value['company_id']."');<br/>";
}
echo $sql;die;


//update cuteframe.company a, test.cfg b set b.company_id = a.id where a.company_name = b.company;

//update cuteframe.`user` a, test.cfg b set b.user_id = a.id where a.account = b.account;