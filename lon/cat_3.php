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

$L_arr = array();

$L_arr = array_combine($style_sn,$cat);
//echo '<pre>';
//print_r($L_arr);die;

$sql = "select `cat_type_id`,`cat_type_name` from `app_cat_type`";
$result = $mysqli->query($sql);
$product_A = array();
$product_A = con($result);

$product_B = array();
foreach ($product_A as $key => $value) {
    # code...
    $product_B[$value['cat_type_name']] = $value['cat_type_id'];
}

$sql = '';
foreach ($L_arr as $style_sn => $p_name) {
    # code...
    //$p_id = $product_B[$p_name];
    $sql .= "update `warehouse_goods` set `cat_type` = '".$p_name."' where `goods_sn` = '".$style_sn."'; <br/>";
    //$sql .= "update `rel_style_attribute` set `cat_type_id` = ".$p_id." where `style_sn` = '".$style_sn."'; <br/>";
}

echo $sql;die;

