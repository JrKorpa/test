<?php
/**
 * @Author: anchen
 * @Date:   2015-06-30 11:39:49
 * @Last Modified by:   anchen
 * @Last Modified time: 2015-09-23 17:45:02
 */
header("Content-type:text/html;charset=utf8;");
set_time_limit(0);
error_reporting(0);

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
$xilie = array(
    1=>'天鹅湖',
    2=>'天使之吻',
    3=>'怦然心动',
    4=>'UNO',
    5=>'天使之翼',
    6=>'星耀',
    7=>'小黄鸭',
    8=>'天生一对',
    9=>'基本款',
    10=>'O2O爆款',
    11=>'轻奢',
    12=>'PINK EMILY',
    13=>'Attractive',
    14=>'缤纷',
    15=>'挚爱',
    16=>'城堡'
);
$sql = "select 
`bsi`.`style_id`,
`bsi`.`style_sn`,
`bsi`.`style_name`,
`apt`.`product_type_name`,
`act`.`cat_type_name`,
`bsi`.`create_time`,
`bsi`.`modify_time`,
`bsi`.`xilie`,
(case `bsi`.`is_made` when 1 then '是' when 0 then '否' end ) as is_made,
(case `bsi`.`is_zp` when 1 then '否' when 2 then '是' end ) as is_zp,
(case `bsi`.`is_xz` when 1 then '否' when 2 then '是' end ) as is_xz,
`bsi`.`dapei_goods_sn`,
(case `bsi`.`changbei_sn` when 1 then '是' when 2 then '否' end ) as changbei_sn,
`bsi`.`market_xifen`,
(case `bsi`.`sell_type` when 1 then '新款' when 2 then '滞销款' when 3 then '畅销款' when 4 then '平常款' end ) as sell_type 
from `base_style_info` as `bsi` 
 inner join `app_product_type` as `apt` on `bsi`.`product_type` = `apt`.`product_type_id`
 inner join `app_cat_type` as `act` on `bsi`.`style_type` = `act`.`cat_type_id` 
 where `bsi`.`check_status` not in(4,7)   
 order by `bsi`.`style_id` desc;";
$result = $mysqli->query($sql);
$style_data = array();
$style_data = con($result);
//echo '<pre>';
//print_r($style_data);die;
function con($arr)
{
    $data_info = array();
    while($row = $arr->fetch_array(MYSQLI_ASSOC)){

        $data_info[] = $row;
    }

    return $data_info;
}

$sql = "SELECT `style_id`,`thumb_img` FROM `app_style_gallery` where `image_place` = 1";
$result = $mysqli->query($sql);
$gallery_data = con($result);
foreach ($gallery_data as $value) {
    # code...
    $gallery_data_s[$value['style_id']] = $value['thumb_img'];
}
//echo '<pre>';
//print_r($gallery_data_s);die;

foreach($style_data as $key => $val){
    if($val['xilie']){
        $xilie_ssss = trim($val['xilie'],",");
        $xilie_arr = explode(",",$xilie_ssss);
        $xilie_str = '';
        foreach ($xilie_arr as $k => $v) {
            # code...
            $xilie_str.=$xilie[$v]."|";
        }
        $style_data[$key]['xilie'] = rtrim($xilie_str,"|");
    }
    $style_data[$key]['thumb_img'] = isset($gallery_data_s[$val['style_id']])?$gallery_data_s[$val['style_id']]:'';

}
//echo '<pre>';
//print_r($style_data);die;

$sql = "SELECT `rsf`.`style_id`,`api`.`name`,`rsf`.`factory_id`,`rsf`.`factory_sn`,`rsf`.`xiangkou`,`rsf`.`factory_fee`,`rsf`.`is_def`,`rsf`.`is_def`,`rsf`.`is_factory` FROM `front`.`rel_style_factory` as `rsf`,`kela_supplier`.`app_processor_info` as `api` where `rsf`.`factory_id` = `api`.`id` and `rsf`.`is_cancel` = 1";
$result = $mysqli->query($sql);
$factory_arr = con($result);

foreach ($factory_arr as $key => $value) {
    # code...
    $factory_arr_s[$value['style_id']][$value['style_id'].$value['factory_id'].$value['xiangkou']] = $value;
}

$adsf = 0;
foreach ($style_data as $key => $value) {
    # code...
    if(isset($factory_arr_s[$value['style_id']]) && !empty($factory_arr_s[$value['style_id']])){
        foreach ($factory_arr_s[$value['style_id']] as $v) {
            # code...
            if($v['is_factory'] == 1 && $v['is_def'] == 1){
                $style_data[$key]['a'] = $v['name'].":".$v['factory_sn'].":".$v['xiangkou'].":".$v['factory_fee'];
            }
            if($v['is_factory'] == 1 && $v['is_def'] == 0){
                $style_data[$key]['b'] .= $v['name'].":".$v['factory_sn'].":".$v['xiangkou'].":".$v['factory_fee']."|";
            }
            if($v['is_factory'] == 0 && $v['is_def'] == 0){
                $style_data[$key]['c'] .= $v['name'].":".$v['factory_sn'].":".$v['xiangkou'].":".$v['factory_fee']."|";
            }
        }
    }
}
$str = iconv('utf-8','gb2312',"45度图片,款式编号,产品名称,产品线,款式分类,添加时间,更新时间,系列,是否定制,是否赠品,是否允许绑定销账,搭配套系名称,是否常备款,市场细分,畅销度,默认工厂/镶口,默认工厂/非默认镶口,非默认工厂/非默认镶口\n");
foreach ($style_data as $key => $value) {
    # code...
    $value['a'] = isset($value['a'])?$value['a']:'';
    $value['b'] = isset($value['b'])?$value['b']:'';
    $value['c'] = isset($value['c'])?$value['c']:'';
    $gallery = iconv('utf-8','gb2312',"<img src="".$value['thumb_img']."">");
    $style_sn = iconv('utf-8','gb2312',$value['style_sn']);
    $a = iconv('utf-8','gb2312',$value['style_name']);
    $b = iconv('utf-8','gb2312',$value['product_type_name']);
    $c = iconv('utf-8','gb2312',$value['cat_type_name']);
    $d = iconv('utf-8','gb2312',$value['create_time']);
    $e = iconv('utf-8','gb2312',$value['modify_time']);
    $f = iconv('utf-8','gb2312',$value['xilie']);
    $g = iconv('utf-8','gb2312',$value['is_made']);
    $h = iconv('utf-8','gb2312',$value['is_zp']);
    $i = iconv('utf-8','gb2312',$value['is_xz']);
    $j = iconv('utf-8','gb2312',$value['dapei_goods_sn']);
    $k = iconv('utf-8','gb2312',$value['changbei_sn']);
    $l = iconv('utf-8','gb2312',$value['market_xifen']);
    $x = iconv('utf-8','gb2312',$value['sell_type']);
    $m = iconv('utf-8','gb2312',$value['a']);
    $n = iconv('utf-8','gb2312',$value['b']);
    $o = iconv('utf-8','gb2312',$value['c']);
    $str .= $gallery.",".$style_sn.",".$a.",".$b.",".$c.",".$d.",".$e.",".$f.",".$g.",".$h.",".$i.",".$j.",".$k.",".$l.",".$x.",".$m.",".$n.",".$o."\n";
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