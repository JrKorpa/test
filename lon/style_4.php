<?php
header("Content-type: text/html;charset=utf-8");
set_time_limit(0);
error_reporting();

$localhost = '192.168.0.91';
$db_user   = 'root';
$db_pass   = '123456';

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


$sql = "select `l`.`style_sn`,`l`.`xiangkou` from `base_style_info` `s`  
inner join `list_style_goods` `l` on `s`.`style_id` = `l`.`style_id` 
where `s`.`check_status` <> 5";
$result = $mysqli->query($sql);
$goods_info = con($result);
$goods1 = array();
foreach ($goods_info as $key => $value) {
    # code...
    $goods1[$value['style_sn']."_".number_format($value['xiangkou'], 2, '.', '')] = $value['style_sn']."_".number_format($value['xiangkou'], 2, '.', '');
}

//$array_1 = array_diff($info_3, $goods1);
foreach ($goods1 as $key) {
    # code...
    # 
    $boolean = array_key_exists($key,$info_3);
    if(!$boolean){

        $array1[] = $key;
    }
}

/*foreach ($array1 as $key => $value) {
    # code...
    $arr = explode("_", $value);
}*/

foreach ($array1 as $key => $value) {
    # code...
    echo "'".$value."',<br/>";
}
