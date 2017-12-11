<?php
/**
 * @Author: anchen
 * @Date:   2015-08-19 19:15:25
 * @Last Modified by:   anchen
 * @Last Modified time: 2015-08-20 01:17:56
 */
$db1 = mysqli_connect('127.0.0.1', 'root', '', 'kela_style');
$sql = "set names utf8;";
$arr = mysqli_query($db1, $sql);

$sql = "select `style_sn`,`main_stone_cat`,`main_stone_attr`,`sec_stone_cat`,`sec_stone_attr` from `kela_style`.`style_style` where `style_sn` in('KLRM006836','KLRW006835','KLRM012552','KLRW012553','KLRM020411','KLRW020412','KLRM022724','KLRW022725','KLRM026005','KLRW026006','KLRM002675','KLRW006206','KLRM008616','KLRW006833','KLRM026627','KLRW026628','KLRW008614','KLRM008613','KLRM003900','KLRW003901','KLRM002713','KLRW002714','KLRM022722','KLRW022723','KLRM000368','KLRW000365','KLRW008681','KLRM008682','KLRM003482','KLRW003483','KLRM022720','KLRW022721','KLRW002961','KLRM002960','KLRM008676','KLRW008675','KLRM026374','KLRW026375','KLRW026826','KLRM026825','KLRM026003','KLRW026004','KLRM000282','KLRW000274','KLRW008065','KLRM008066','KLRM006878','KLRW006879','KLRW027914','KLRM027913','KLRW026373','KLRM026372','KLRM008489','KLRW008488','KLRM026831','KLRW026830','KLRM015221','KLRW015222','KLRW024151','KLRM024150','KLRW008660','KLRM008659','KLRM007650','KLRW007649','KLRM007289','KLRW007290','KLRM008607','KLRW008608','KLRM006213','KLRW000235','KLRM020728','KLRW020729','KLRM022316','KLRW022315','KLRM007193','KLRW007194','KLRM006914','KLRW006915','KLRM002883','KLRW002884','KLRM006285','KLRW006286','KLRM027911','KLRW027912','KLRM000324','KLRW000323','KLRM000294','KLRW000293','KLRM011134','KLRW007347','KLRM024145','KLRW024147','KLRW007647','KLRM007648','KLRM002383','KLRW002384','KLRM027584','KLRW027585','KLRM026009','KLRW026010','KLRW007778','KLRM007779','KLRM000306','KLRW000305','KLRM000403','KLRW000401','KLRM026388','KLRW026389','KLRW000296','KLRM000297','KLRM027488','KLRW027489','KLRM006052','KLRW006051','KLRM002316','KLRW002317','KLRM024141','KLRW024143','KLRM002389','KLRW002390','KLRM002623','KLRW002624','KLRM008623','KLRW008624','KLRM003891','KLRW007958','KLRM007939','KLRW007938','KLRM003871','KLRW003872','KLRM005157','KLRW007949','KLRM008059','KLRW008058','KLRM007640','KLRW007639','KLRW008068','KLRM005217','KLRM002406','KLRW002407','KLRM005990','KLRW005989','KLRM007788','KLRW007777','KLRW027146','KLRM027147','KLRW027148', 'KLRM027149', 'KLRW027150', 'KLRM027151', 'KLRW027152', 'KLRM027153', 'KLRW027154', 'KLRM027155', 'KLRW027156','KLRM027157','KLRM027517', 'KLRW027518', 'KLRM027692', 'KLRW027693', 'KLRM027688','KLRW027689', 'KLRM027690', 'KLRW027691', 'KLRW027951','KLRM027952') ";
$arr = mysqli_query($db1,$sql);
//$face = array(1=>'磨砂',2=>'光面',3=>'特殊',4=>'拉沙',5=>'钉沙');
while($w=mysqli_fetch_assoc($arr)){

    $w['main_stone_attr'] = unserialize($w['main_stone_attr']);
    $w['sec_stone_attr'] = unserialize($w['sec_stone_attr']);
    $data[$w['style_sn']] = $w;
}

//echo '<pre>';
//print_r($data);die;
$f = array(0=>'无',1=>'圆钻',2=>'异形钻',3=>'珍珠',4=>'翡翠',5=>'红宝石',6=>'蓝宝石',7=>'和田玉',8=>'水晶',9=>'珍珠贝',10=>'碧玺',11=>'玛瑙',12=>'月光石',13=>'托帕石');
foreach ($data as $key => $value) {
    # code...
    //$a[$key] = unserialize($value);
    //$lsi[$key] = $

    /*$sql = "select `factory_fee` from `style_style` where `style_id` = {$value}";
    $arr = mysqli_query($db1, $sql);
    while($w=mysqli_fetch_assoc($arr)){
        $ad[$key] = $w['factory_fee'];
    }*/
    $ka[$key]['zhushi'] = $f[$value['main_stone_cat']];
    if($value['main_stone_attr'] == ''){
        $ka[$key]['zhushikeshu'] = '无';
    }else{
        $ka[$key]['zhushikeshu'] = $value['main_stone_attr'][2];
    }
    if($value['main_stone_attr'] == ''){
        $ka[$key]['xiangkou_s'] = '无';
    }else{
        $ka[$key]['xiangkou_s'] = $value['main_stone_attr'][3]['min'].'-'.$value['main_stone_attr'][3]['max'];
    }
    $ka[$key]['fushi'] = $f[$value['sec_stone_cat']];
    if($value['sec_stone_attr'] == ''){
        $ka[$key]['fushizhongliang'] = '无';
    }else{
        $ka[$key]['fushizhongliang'] = $value['sec_stone_attr'][1];
    }
    if($value['sec_stone_attr'] == ''){
        $ka[$key]['fushishuliang'] = '无';
    }else{
        $ka[$key]['fushishuliang'] = $value['sec_stone_attr'][2];
    }


}

//echo '<pre>';
//print_r($ka);die;

foreach ($ka as $key => $value) {
    # code...
    $style_sn = iconv('utf-8','gb2312',$key);
    $zhushi = iconv('utf-8','gb2312',$value['zhushi']);
    $zhushikeshu = iconv('utf-8','gb2312',$value['zhushikeshu']);
    $xiangkou_s = iconv('utf-8','gb2312',$value['xiangkou_s']);
    $fushi = iconv('utf-8','gb2312',$value['fushi']);
    $fushizhongliang = iconv('utf-8','gb2312',$value['fushizhongliang']);
    $fushishuliang = iconv('utf-8','gb2312',$value['fushishuliang']);
     $str .= $style_sn.",".$zhushi.",".$zhushikeshu.",".$xiangkou_s.",".$fushi.",".$fushizhongliang.",".$fushishuliang."\n"; 
}

 $filename = date('Ymd').'.csv'; //设置文件名   
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

//echo '<pre>';
//print_r($ad);die;