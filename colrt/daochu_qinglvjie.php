<?php
/**
 * @Author: anchen
 * @Date:   2015-08-22 15:04:29
 * @Last Modified by:   anchen
 * @Last Modified time: 2015-08-24 15:04:18
 */
$db = mysqli_connect('127.0.0.1', 'root', '', 'test');
$sql = "set names utf8;";
$arr = mysqli_query($db, $sql);
$db1 = mysqli_connect('127.0.0.1', 'root', '', 'kela_style');
$sql = "set names utf8;";
$arr = mysqli_query($db1, $sql);
$str_sql = "'KLRM006836','KLRW006835','KLRM012552','KLRW012553','KLRM020411','KLRW020412','KLRM022724','KLRW022725','KLRM026005','KLRW026006','KLRM002675','KLRW006206','KLRM008616','KLRW006833','KLRM026627','KLRW026628','KLRW008614','KLRM008613','KLRM003900','KLRW003901','KLRM002713','KLRW002714','KLRM022722','KLRW022723','KLRM000368','KLRW000365','KLRW008681','KLRM008682','KLRM003482','KLRW003483','KLRM022720','KLRW022721','KLRW002961','KLRM002960','KLRM008676','KLRW008675','KLRM026374','KLRW026375','KLRW026826','KLRM026825','KLRM026003','KLRW026004','KLRM000282','KLRW000274','KLRW008065','KLRM008066','KLRM006878','KLRW006879','KLRW027914','KLRM027913','KLRW026373','KLRM026372','KLRM008489','KLRW008488','KLRM026831','KLRW026830','KLRM015221','KLRW015222','KLRW024151','KLRM024150','KLRW008660','KLRM008659','KLRM007650','KLRW007649','KLRM007289','KLRW007290','KLRM008607','KLRW008608','KLRM006213','KLRW000235','KLRM020728','KLRW020729','KLRM022316','KLRW022315','KLRM007193','KLRW007194','KLRM006914','KLRW006915','KLRM002883','KLRW002884','KLRM006285','KLRW006286','KLRM027911','KLRW027912','KLRM000324','KLRW000323','KLRM000294','KLRW000293','KLRM011134','KLRW007347','KLRM024145','KLRW024147','KLRW007647','KLRM007648','KLRM002383','KLRW002384','KLRM027584','KLRW027585','KLRM026009','KLRW026010','KLRW007778','KLRM007779','KLRM000306','KLRW000305','KLRM000403','KLRW000401','KLRM026388','KLRW026389','KLRW000296','KLRM000297','KLRM027488','KLRW027489','KLRM006052','KLRW006051','KLRM002316','KLRW002317','KLRM024141','KLRW024143','KLRM002389','KLRW002390','KLRM002623','KLRW002624','KLRM008623','KLRW008624','KLRM003891','KLRW007958','KLRM007939','KLRW007938','KLRM003871','KLRW003872','KLRM005157','KLRW007949','KLRM008059','KLRW008058','KLRM007640','KLRW007639','KLRW008068','KLRM005217','KLRM002406','KLRW002407','KLRM005990','KLRW005989','KLRM007788','KLRW007777','KLRW027146','KLRM027147','KLRW027148','KLRM027149','KLRW027150','KLRM027151','KLRW027152','KLRM027153','KLRW027154','KLRM027155','KLRW027156','KLRM027157','KLRM027517','KLRW027518','KLRM027692','KLRW027693','KLRM027688','KLRW027689','KLRM027690','KLRW027691','KLRW027951','KLRM027952'";
$sql = "select `style_id`,`style_sn` from `base_style_info` where style_sn in($str_sql)";
$arr = mysqli_query($db,$sql);
while($w=mysqli_fetch_assoc($arr)){

    $style_data[$w['style_id']] = $w['style_sn'];
}


$sql = "select `att_value_id`,`att_value_name` from `app_attribute_value`";
$arr = mysqli_query($db, $sql);
while($w=mysqli_fetch_assoc($arr)){

    $attribute_values[$w['att_value_id']] = $w['att_value_name'];
}

foreach ($style_data as $key => $value) {
    # code...
    $sql = "select `style_sn`,`attribute_id`,`attribute_value` from `test`.`rel_style_attribute` where `style_sn` = '".$value."'";
    //echo $sql;die;
    $arr = mysqli_query($db, $sql);
    while ($w=mysqli_fetch_assoc($arr)) {
        # code...
        $style_sn_arrt[$w['style_sn'].$w['attribute_id']] = $w['attribute_value']; 
    }

    $caizhi = isset($style_sn_arrt[$value.'3']) && !empty($style_sn_arrt[$value.'3']) ? $style_sn_arrt[$value.'3'] : '';
    $caizhi_yanse = isset($style_sn_arrt[$value.'33']) && !empty($style_sn_arrt[$value.'33']) ? $style_sn_arrt[$value.'33'] : '';
    $zhiquanfanwei = isset($style_sn_arrt[$value.'5']) && !empty($style_sn_arrt[$value.'5']) ? $style_sn_arrt[$value.'5'] : '';
    $gaiquan = isset($style_sn_arrt[$value.'31']) && !empty($style_sn_arrt[$value.'31']) ? $style_sn_arrt[$value.'31'] : '';
    $kezi = isset($style_sn_arrt[$value.'7']) && !empty($style_sn_arrt[$value.'7']) ? $style_sn_arrt[$value.'7'] : '';
    $boss[$value]['可做材质'] = $caizhi;
    $boss[$value]['材质颜色'] = $caizhi_yanse;
    $boss[$value]['指圈范围'] = $zhiquanfanwei;
    $boss[$value]['是否支持改圈'] = $gaiquan;
    $boss[$value]['是否支持刻字'] = $kezi;
}

foreach ($boss as $key => $value) {
    # code...
    foreach ($value as $k => $v) {
        # code...
        $tmp = array();
        $str = '';
        if($v){
            $v = trim($v,",");
            $tmp = explode(",", $v);
            foreach ($tmp as $va) {
                # code...
                $str.= $attribute_values[$va]."、";
            }
            $str = trim($str,"、");
        }
        $boss_y[$key][$k] = $str;
    }
}

$sql = "select `style_sn`,`metal_info` from `style_style` where style_sn in($str_sql)";
$arr = mysqli_query($db1,$sql);
while ($w=mysqli_fetch_assoc($arr)) {
    # code...
    $style_fake[$w['style_sn']] = unserialize($w['metal_info']);
}

$sql = "select `style_sn`,`fee_type`,`price` from `app_style_fee` where style_sn in({$str_sql})";
$arr = mysqli_query($db,$sql);
while ($w=mysqli_fetch_assoc($arr)) {
    # code...
    $style_fee[$w['style_sn']][$w['fee_type']] = $w['price'];
}

foreach ($boss_y as $key => $value) {
    # code...
    $boss_y[$key]['18k金重'] = $style_fake[$key][2]['gold_weigth'];
    $boss_y[$key]['PT950金重'] = $style_fake[$key][4]['gold_weigth'];
    $boss_y[$key]['18k工费'] = isset($style_fee[$key][1]) ? $style_fee[$key][1] : '';
    $boss_y[$key]['PT950工费'] = isset($style_fee[$key][4]) ? $style_fee[$key][4] : '';
}

foreach ($boss_y as $key => $value) {
    # code...
    $style_sn = iconv('utf-8','gb2312',$key);
    $a = iconv('utf-8','gb2312',$value['可做材质']);
    $b = iconv('utf-8','gb2312',$value['材质颜色']);
    $c = iconv('utf-8','gb2312',$value['指圈范围']);
    $d = iconv('utf-8','gb2312',$value['是否支持改圈']);
    $e = iconv('utf-8','gb2312',$value['是否支持刻字']);
    $f = iconv('utf-8','gb2312',$value['18k金重']);
    $g = iconv('utf-8','gb2312',$value['PT950金重']);
    $h = iconv('utf-8','gb2312',$value['18k工费']);
    $i = iconv('utf-8','gb2312',$value['PT950工费']);
    $str .= $style_sn.",".$a.",".$b.",".$c.",".$d.",".$e.",".$f.",".$g.",".$h.",".$i."\n";
}

$filename = date('Ymd His').'.csv'; //设置文件名   
export_csv($filename,$str); //导出 

function export_csv($filename,$data)   
{   
    header("Content-type:text/csv");   
    header("Content-Disposition:attachment;filename=".$filename);   
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');   
    header('Expires:0');   
    header('Pragma:public');   
    echo $data;   
}  
