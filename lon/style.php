<?php
header("Content-type: text/html;charset=utf-8");
set_time_limit(0);
error_reporting(E_ALL);

$localhost = '192.168.10.23';
$db_user   = 'root';
$db_pass   = '1308b8dac1e577';

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

$name = "data.csv";

$file = fopen($name,"r");

$res = array();

while(! feof($file))
{
    $res[] = fgetcsv($file);
}

fclose($file);

array_shift($res);
array_pop($res);

$style_sn_lsit = '';

$data = array();
foreach ($res as $key => $value) {
    # code...
    $data[$value[0]]['xiangkou'] = $value[7];
    $data[$value[0]]['caizhi'] = $value[8];
    $data[$value[0]]['caizhiyanse'] = $value[9];

    $style_sn_lsit .= "'".$value[0]."',";
}

//sprintf("%.2f", $value[7]);

$sql = "select `style_id`,`style_sn`,`style_type`,`product_type` from `base_style_info` where `style_sn` in(".trim($style_sn_lsit,",").")";
$result = $mysqli->query($sql);
$styleInfo = array();
$styleInfo = con($result);

$styleInfoA = array();
foreach ($styleInfo as $key => $value) {
    # code...
    $styleInfoA[$value['style_sn']] = $value;
}

$sql = "select `rel_id`,`style_sn`,`attribute_id` from `rel_style_attribute` where `attribute_id` in(1,3,33) and `style_sn` in(".trim($style_sn_lsit,",").")";
//echo $sql;die;
//1、镶口，3、材质，33、材质颜色；
$result = $mysqli->query($sql);
$attrA = array();
$attrA = con($result);

$attrB = array();
foreach ($attrA as $key => $value) {
    # code...
    $attrB[$value['style_sn']][$value['attribute_id']] = $value['rel_id'];
}

$sql = "select `att_value_id`,`attribute_id`,`att_value_name` from `app_attribute_value` where `attribute_id` in(1,3,33)";
$result = $mysqli->query($sql);
$attrvalueA = array();
$attrvalueA = con($result);

$attrvalueB = array();
foreach ($attrvalueA as $key => $value) {
    # code...
    $attrvalueB[$value['attribute_id']][$value['att_value_name']] = $value['att_value_id'];
}

//echo '<pre>';
//print_r($attrB);die;

$xiangkou = '';
$caizhi = '';
$caizhiyanse = '';
$sInfo = array();
$i = 0;

foreach ($data as $style_sn => $value) {
$i++;
    foreach ($value as $k => $v) {

        $info = array();
        $sInfo = $styleInfoA[$style_sn];
        $info['cat_type_id'] = $sInfo['style_type'];
        $info['product_type_id'] = $sInfo['product_type'];
        $info['style_sn'] = $style_sn;
        $info['create_time'] = date('Y-m-d H:i:s',time());
        $info['create_user'] = '黄文銮';
        $info['style_id'] = $sInfo['style_id'];

        if($k == 'xiangkou'){

            $xiangkou = checkXiangKouStr($v);

            if(isset($attrB[$style_sn][1])){

                $rel_id = $attrB[$style_sn][1];
                update($rel_id,$xiangkou);
            }else{
                $info['show_type'] = 3;
                $info['attribute_id'] = 1;
                $info['attribute_value'] = $xiangkou;
                insert($info);
            }

        }elseif($k == 'caizhiyanse'){

            $caizhiyanse = checkYanSeStr($v);

            if(isset($attrB[$style_sn][33])){

                $rel_id = $attrB[$style_sn][33];
                update($rel_id,$caizhiyanse);
            }else{
                $info['show_type'] = 3;
                $info['attribute_id'] = 33;
                $info['attribute_value'] = $caizhiyanse;
                insert($info);
            }

        }elseif($k == 'caizhi'){

            $caizhi = checkCaiZhiStr($v);

            if(isset($attrB[$style_sn][3])){

                $rel_id = $attrB[$style_sn][3];
                update($rel_id,$caizhi);
            }else{
                $info['show_type'] = 3;
                $info['attribute_id'] = 3;
                $info['attribute_value'] = $caizhi;
                insert($info);
            }

        }
    }
}

echo $i;


function update($rel_id,$xiangkou)
{
    global $mysqli;

    $sql = "update `rel_style_attribute` set `attribute_value` = '{$xiangkou}' where `rel_id` = {$rel_id};";
    $result = $mysqli->query($sql);
    echo $sql;var_dump($result);
    echo '<br/>';
}

function insert($info)
{
    global $mysqli;

    $tmp = '';
    foreach ( $info as $k => $v ){

        $tmp .= '`' . $k . '` = \'' . $v . '\',';
    }
    $tmp = rtrim($tmp,',');
    $sql = "INSERT INTO `rel_style_attribute` SET {$tmp};";
    $result = $mysqli->query($sql);
    echo $sql;var_dump($result);
    echo '<br/>';
}

function checkCaiZhiStr($str)
{

    global $attrvalueB;

    $rel_str = '';

    $arr = array();
    $arr = explode("|", $str);

    foreach ($arr as $k => $v) {

        $tisss = isset($attrvalueB[3][$v])?$attrvalueB[3][$v]:'';
        $rel_str .= $tisss.",";
    }
    return trim($rel_str,",");
}

function checkYanSeStr($str)
{

    global $attrvalueB;

    $rel_str = '';

    $str = iconv("GBK", "UTF-8", $str);

    $arr = array();
    $arr = explode("|", $str);

    foreach ($arr as $k => $v) {

        $tisss = isset($attrvalueB[33][$v])?$attrvalueB[33][$v]:'';
        $rel_str .= $tisss.",";
    }
    return trim($rel_str,",");
}

function checkXiangKouStr($str)
{

    global $attrvalueB;
    
    $rel_str = '';

    $arr = array();
    $arr = explode("|", $str);

    foreach ($arr as $k => $v) {

        $stone = sprintf("%.2f", $v);
        $stone = isset($attrvalueB[1][$stone])?$attrvalueB[1][$stone]:'';
        $rel_str .= $stone.",";
    }

    return trim($rel_str,",");
}
