<?php
/**
* 通过读取csv文档，根据款号更新款的某一项属性；
*/
header("Content-type:text/html;charset=utf-8");
date_default_timezone_set('Asia/Shanghai');
require_once('MysqlDB.class.php');
set_time_limit(0);
ini_set('memory_limit','2000M');

$new_conf = [
	'dsn'=>"mysql:host=192.168.0.95;dbname=front",
	'user'=>"cuteman",
	'password'=>"QW@W#RSS33#E#",
	'charset' => 'utf8'
];

$new_conf = [
    'dsn'=>"mysql:host=192.168.0.131;dbname=front",
    'user'=>"root",
    'password'=>"123456",
    'charset' => 'utf8'
];

$db = new MysqlDB($new_conf);

$name = "insertCz.csv";

$file = fopen($name,"r");
while(! feof($file))
{
    $data[] = fgetcsv($file);
}
fclose($file);

$data = array_filter(eval('return '.iconv('gbk','utf-8',var_export($data,true)).';'));

$snKey = array_column($data, 0);

$data = array_combine($snKey, array_column($data, 1));

$sql = "select `attribute_id`,`attribute_name` from `app_attribute` where `attribute_status` = 1";
$fkSt = array_combine(array_column($db->getAll($sql), 'attribute_name'), array_column($db->getAll($sql), 'attribute_id'));//显示方式

$nameSave = "材质颜色";//需要更新的属性名称
$upAttr = $fkSt[$nameSave];

$sql = "select `style_id`,`style_sn`,`product_type`,`style_type` from `base_style_info` where `style_sn` in('".implode("','", $snKey)."')";
$daSn = $db->getAll($sql);
$snId = array_combine(array_column($daSn, 'style_sn'), array_column($daSn, 'style_id'));//ID
$snTp = array_combine(array_column($daSn, 'style_sn'), array_column($daSn, 'style_type'));//款式分类
$snPt = array_combine(array_column($daSn, 'style_sn'), array_column($daSn, 'product_type'));//产品线

$sql = "select `attribute_id`,`show_type` from `app_attribute` where `attribute_status` = 1";
$snSt = array_combine(array_column($db->getAll($sql), 'attribute_id'), array_column($db->getAll($sql), 'show_type'));//显示方式

$sql = "select `style_sn`,`attribute_value` from `rel_style_attribute` where `attribute_id` = $upAttr and `style_sn` in('".implode("','", $snKey)."')";
$relSn = array_combine(array_column($db->getAll($sql), 'style_sn'), array_column($db->getAll($sql), 'attribute_value'));//款式属性

$sql = "select `att_value_id`,`att_value_name` from `app_attribute_value` where attribute_id = $upAttr";
$attSn = array_combine(array_column($db->getAll($sql), 'att_value_name'), array_column($db->getAll($sql), 'att_value_id'));
//echo '<pre>';
//print_r($relSn);die;
$save_sql = '';
foreach ($data as $k_sn => $attrval) {
    if($attrval){
        $shuX = explode("|", $attrval);
        if(!empty($shuX)){
            $ins_attr = '';
            foreach ($shuX as $ys) {
                if(!in_array($attSn[$ys], array_filter(explode(",", rtrim($relSn[$k_sn], ","))))){
                    $ins_attr.= $attSn[$ys].",";
                }
            }
            if(isset($relSn[$k_sn]) && $ins_attr){//有则update
                $rel_insattr = rtrim($relSn[$k_sn], ",").",".rtrim($ins_attr, ",");
                $save_sql.= "update `rel_style_attribute` set `attribute_value` = '$rel_insattr' where `style_sn` = '$k_sn' and `attribute_id` = $upAttr;";
            }elseif(!isset($relSn[$k_sn]) && $ins_attr){//无则insert
                $save_sql.= "insert into `rel_style_attribute` values
(null, ".$snTp[$k_sn].", ".$snPt[$k_sn].", '$k_sn', ".$upAttr.", '$ins_attr', ".$snSt[$upAttr].", '".date("Y-m-d H:i:s")."', '黄文銮', '', ".$snId[$k_sn].", 0, '".date("Y-m-d H:i:s")."');";
            }else{
                //属性齐全无需更改
            }
        }
    }
}
//echo $save_sql;die;
$db->exec($save_sql);

//W230_002
