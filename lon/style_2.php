<?php
header("Content-type: text/html;charset=utf-8");
set_time_limit(0);
error_reporting();

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

$sql = "select `style_sn`,`stone` from `app_xiangkou`";
$result = $mysqli->query($sql);
$info_1 = con($result);
foreach ($info_1 as $key => $value) {
    # code...
    $info_2[$value['style_sn'].'_'.$value['stone']] = $value['style_sn'].'_'.$value['stone'];
}

$sql = "select `s`.`style_sn`,
`r`.`attribute_value` 
from `base_style_info` `s` 
left join `rel_style_attribute` `r` on `s`.`style_sn` = `r`.`style_sn` 
where `s`.`check_status` <> 5 
and `r`.`attribute_id` = 1";
$result = $mysqli->query($sql);
$data = con($result);

$data1 = array();
foreach ($data as $key => $value) {
    # code...
    $data1[$value['style_sn']] = $value['attribute_value'];
}

$xk_attr = array();
$sql = "select `att_value_id`,`att_value_name` from `app_attribute_value` where `attribute_id` = 1";
$result = $mysqli->query($sql);
$xk_attr = con($result);

foreach ($xk_attr as $key => $value) {
    # code...
    $xk_attr1[$value['att_value_id']] = $value['att_value_name'];
}

foreach ($data1 as $key => $value) {
    # code...
    if($value){
        $xiangkou = array_filter(explode(",", $value));
        foreach ($xiangkou as $k => $v) {
            # code...
            $info_3[$key.'_'.$xk_attr1[$v]] = $key.'_'.$xk_attr1[$v];
        }
    }
}

$array_1 = array_diff($info_2, $info_3);

echo '<pre>';
print_r($array_1);die;
