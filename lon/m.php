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

$name = "data2.csv";

$file = fopen($name,"r");

$res = array();

while(! feof($file))
{
    $res[] = fgetcsv($file);
}

fclose($file);

$data = array();

foreach ($res as $key => $value) {
    # code...
    $data[$key]['goods_id'] = iconv("GBK", "UTF-8", $value[0]);
    $data[$key]['product'] = iconv("GBK", "utf-8", $value[6]);
    $data[$key]['type'] = iconv("GBK","utf-8", $value[7]);
}

array_pop($data);

foreach ($data as $key => $value) {
    # code...
    $sql = "update `warehouse_shipping`.`warehouse_goods` 
    set `product_type1` = '".$value['product']."',
    `cat_type1` = '".$value['type']."' 
    where `goods_id` = ".$value['goods_id'].";";
    //$result = $mysqli->query($sql);
    echo $sql."<br>";
}
