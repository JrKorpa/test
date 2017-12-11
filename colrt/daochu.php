<?php
/**
 * @Author: anchen
 * @Date:   2015-08-25 19:20:37
 * @Last Modified by:   anchen
 * @Last Modified time: 2015-09-12 18:16:24
 */
header("Content-type:text/html;charset=utf8;");
set_time_limit(0);
//error_reporting(0);
$db = mysqli_connect('127.0.0.1', 'root', '', 'test');
$sql = "set names utf8;";
$arr = mysqli_query($db, $sql);

$sql = "select 
`asg`.`thumb_img` as 45度图片,
`bsi`.`style_id` as 款式ID,
`bsi`.`style_sn` as 款式编号,
`bsi`.`style_name` as 产品名称,
`apt`.`product_type_name` as 产品线,
`act`.`cat_type_name` as 款式分类,
`bsi`.`create_time` as 添加时间,
`bsi`.`modify_time` as 更新时间,
`bsi`.`xilie` as 系列,
(case `bsi`.`is_made` when 1 then '是' when 0 then '否' end ) as 是否定制,
(case `bsi`.`is_zp` when 1 then '否' when 2 then '是' end ) as 是否赠品,
(case `bsi`.`is_xz` when 1 then '否' when 2 then '是' end ) as 是否允许绑定销账,
`bsi`.`dapei_goods_sn` as 搭配套系名称,
(case `bsi`.`changbei_sn` when 1 then '是' when 2 then '否' end ) as 是否常备款,
`bsi`.`market_xifen` as 市场细分,
`bsi`.`sell_type` as 畅销度
 from `base_style_info` as `bsi` 
 left join `app_style_gallery` as `asg` on `bsi`.`style_id` = `asg`.`style_id` and `asg`.`image_place` = 1
 inner join `app_product_type` as `apt` on `bsi`.`product_type` = `apt`.`product_type_id`
 inner join `app_cat_type` as `act` on `bsi`.`style_type` = `act`.`cat_type_id` 
 where `bsi`.`check_status` in(1,2,3)";
$arr = mysqli_query($db,$sql);
while ($w=mysqli_fetch_assoc($arr)) {
    
}
$sql = "";
$arr = mysqli_query($db, $sql);

while($w=mysqli_fetch_assoc($arr)){

    $data[$w['style_sn']] = $w;
}

foreach ($data as $key => $value) {
    # code...
    $sql = "select `relation_sn` from `style_style` where `style_sn` = '{$key}'";
    $arr = mysqli_query($db1,$sql);
    while ($w=mysqli_fetch_assoc($arr)) {
        # code...
        $data[$key]['relation_sn'] = isset($w['relation_sn']) ? $w['relation_sn'] : '';
        if($data[$key]['relation_sn'] == ''){

            $data[$key]['relation_sn'] = isset($lovers[$key]) ? $lovers[$key] : '';
        }
    }
}

foreach ($data as $key => $value) {
    # code...
    $sql = "select `b`.`name`,`a`.`factory_sn` from `rel_style_factory` as `a`,`app_processor_info` as `b` where `a`.`factory_id` = `b`.`id` and `a`.`style_sn` = '{$key}' and `a`.`is_factory` = 1";
    $arr = mysqli_query($db,$sql);
    while ($w=mysqli_fetch_assoc($arr)) {
        # code...
        $data[$key]['factory_name'] = $w['name'];
        $data[$key]['factory_sn'] = $w['factory_sn'];
    }
}


foreach ($data as $key => $value) {
    # code...
    $style_sn = iconv('utf-8','gb2312',$key);
    $a = iconv('utf-8','gb2312',$value['style_name']);
    $b = iconv('utf-8','gb2312',$value['create_time']);
    $c = iconv('utf-8','gb2312',$value['cat_type_name']);
    $d = iconv('utf-8','gb2312',$value['product_type_name']);
    $e = iconv('utf-8','gb2312',$value['relation_sn']);
    $f = iconv('utf-8','gb2312',$value['factory_name']);
    $g = iconv('utf-8','gb2312',$value['factory_sn']);
    $str .= $style_sn.",".$a.",".$b.",".$c.",".$d.",".$e.",".$f.",".$g."\n";
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