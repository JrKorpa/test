<?php
/**
* 导出款式材质与金重的差异脚本；
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

/*$new_conf = [
    'dsn'=>"mysql:host=192.168.0.131;dbname=front",
    'user'=>"root",
    'password'=>"123456",
    'charset' => 'utf8'
];*/

$db = new MysqlDB($new_conf);

$sql = "select `style_sn`,`g18_weight`,`gpt_weight` from `app_xiangkou`;";
$snXk = $db->getAll($sql);

$sql = "select `style_sn`,`attribute_value` from `rel_style_attribute` where `attribute_id` = 3";
$snSa = array_combine(array_column($db->getAll($sql), 'style_sn'), array_column($db->getAll($sql), 'attribute_value'));

$sql = "select `att_value_id`,`att_value_name` from `app_attribute_value` where `attribute_id` = 3";
$ks = array_combine(array_column($db->getAll($sql), 'att_value_id'), array_column($db->getAll($sql), 'att_value_name'));

if($snSa){
    $cot = array_filter($snSa);

    foreach ($cot as $key => $value) {
        $t = explode(",", rtrim($value, ","));
        $stt = '';
        foreach ($t as $k => $v) {

            $stt.= $ks[$v]."|";
        }
        $relCz[$key] = rtrim($stt, '|');
    }
}

$cyInfo = array();
foreach ($snXk as $snInfo) {
    if($snInfo['g18_weight'] > 0){
        if(!isset($snSa[$snInfo['style_sn']]) || empty($snSa[$snInfo['style_sn']]) || !in_array('37', array_filter(explode(",", rtrim($snSa[$snInfo['style_sn']], ","))))){
            $cyInfo[$snInfo['style_sn']][] = '18K';
        }
    }
    if($snInfo['gpt_weight'] > 0){
        if(!isset($snSa[$snInfo['style_sn']]) || empty($snSa[$snInfo['style_sn']]) || !in_array('39', array_filter(explode(",", rtrim($snSa[$snInfo['style_sn']], ","))))){
            $cyInfo[$snInfo['style_sn']][] = 'PT950';
        }
    }
}

if(!empty($cyInfo)){
    foreach ($cyInfo as $k_sn => $cZ) {
        $info[$k_sn] = implode("|", array_unique($cZ));
    }
}else{
    echo '款式材质与金重信息没有差异';die;
}

//：款号、产品线、款式分类、款式状态、材质、缺失的材质（PT950/18K）
$str = '';
foreach ($info as $k_sn => $qScz) {
    $style_sn = iconv('utf-8','gb2312',$k_sn);
    $sql = "select `cat_type_name`,`product_type_name`,(case `check_status` when 1 then '保存' when 2 then '申请审核' when 3 then '已审核' when 4 then '无效' when 5 then '作废中' when 6 then '作废已驳回' when 7 then '已作废' end) `check_status` from `base_style_info` `s` inner join `app_cat_type` `c` on `s`.`style_type` = `c`.`cat_type_id` inner join `app_product_type` `p` on `s`.`product_type` = `p`.`product_type_id` where `s`.`style_sn` = '$k_sn'";
    $r = $db->getRow($sql);
    $a = iconv('utf-8','gb2312',$k_sn);
    $b = iconv('utf-8','gb2312',$r['cat_type_name']);
    $c = iconv('utf-8','gb2312',$r['product_type_name']);
    $d = iconv('utf-8','gb2312',$r['check_status']);
    $e = iconv('utf-8','gb2312',isset($relCz[$k_sn])?$relCz[$k_sn]:'');
    $f = iconv('utf-8','gb2312',$qScz);
    $str .= $a.",".$b.",".$c.",".$d.",".$e.",".$f."\n";
}
$filename = date('Ymd His').'.csv'; //设置文件名   
export_csv($filename,$str); //导出 

//导出
function export_csv($filename,$data)   
{   
    header("Content-type:text/csv");
    header("Content-Disposition:attachment;filename=".$filename);
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    header('Expires:0');
    header('Pragma:public');
    echo $data;
}

