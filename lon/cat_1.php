<?php
header("Content-type:text/html;charset=utf8;");
set_time_limit(0);
error_reporting(E_ALL);

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

include_once("./cat_2.php");

//$lala = FetchRepeatMemberInArray($style_sn);

$L_arr = array();

$L_arr = array_combine($style_sn,$product);
//echo '<pre>';
//print_r($lala);die;

$sql = "select `product_type_id`,`product_type_name` from `app_product_type`";
$result = $mysqli->query($sql);
$product_A = array();
$product_A = con($result);

$product_B = array();
foreach ($product_A as $key => $value) {
    # code...
    $product_B[$value['product_type_name']] = $value['product_type_id'];
}

$sql = '';
foreach ($L_arr as $style_sn => $p_name) {
    # code...
    $p_id = $product_B[$p_name];
    $sql .= "update `base_salepolicy_goods` set `product_type` = ".$p_id." where `goods_sn` = '".$style_sn."'; <br/>";
    //$sql .= "update `rel_style_attribute` set `product_type_id` = ".$p_id." where `style_sn` = '".$style_sn."'; <br/>";
}

echo $sql;die;

function FetchRepeatMemberInArray($array) { 
// 获取去掉重复数据的数组 
$unique_arr = array_unique ( $array ); 
// 获取重复数据的数组 
$repeat_arr = array_diff_assoc ( $array, $unique_arr ); 
return $repeat_arr; 
} 