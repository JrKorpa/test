<?php
/**
 * @Author: anchen
 * @Date:   2015-06-30 11:39:49
 * @Last Modified by:   anchen
 * @Last Modified time: 2015-09-15 18:51:31
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
$sql = "select  
 `style_sn`, 
 `goods_sn`, 
 `zhushizhong` as 主石重, 
 `zhushi_num` as 主石数, 
 `fushizhong1` as 副石1重, 
 `fushi_num1` as 副石1数, 
 `fushizhong2` as 副石2重, 
 `fushi_num2` as 副石2数, 
 `fushi_chengbenjia_other` as 其他副石成本价, 
 (case `caizhi` when 1 then '18K' when 2 then 'PT950' end ) as 材质,
 `yanse` as 材质颜色, 
 `weight` as 材质金重, 
 `jincha_shang` as 金重上公差,
 `jincha_xia` as 金重下公差, 
 `xiangkou` as 镶口,  
 `shoucun` as 手寸, 
 `dingzhichengben` as 定制成本, 
 (case `is_ok` when 1 then '上架' when 0 then '下架' end ) as 上架状态 
 from `list_style_goods` 
 where 
 `style_sn` in('KLRW006275','KLRW006426','KLRW013823','KLRW021064','KLRW021066','KLRW021656','KLRW022271','KLRW022614','W3192','W4451','W5576','W5184','W4594','W6568','W5966','W6583','W8058','W2000','W6800','W2115','W7821','W9846','W1985','W5564','W5087','W1449','W1273','W2068','W6383','W2815','W4950','W4037','W4421','W4578','W1999','W9489','W8019','W9148','W6310','W6225','W2770','W4329','W7550','W1512','W7258','W5177','W3491','W1978','W3663','W4767','W6767','W3081','W5459','W5154','W9253','W6022','W2635','W6439','W6212','W9793','W7170','W8259','W2553','W7193','W9889','W8614','W4633','W4616','W9258','W5204','W5372','W2712','W8488','W7275','W4356','W5162','W5561','W6281','W8578','W2059','W8722','W2118','W7456','W1186','W4947','W1374','W3155','W4345','W1971','W8138','W2907','W7762','W9194','W8089','W9109','W3669','W6578','W8792','W4685','W4090','W8175','W113_002','W111_001','W111_002','W113_005','W111_003','W111_004','W111_005','W111_006','W111_007','W111_008','W111_009','W111_010','W111_011','W111_012','W111_013','W111_014','W112_001','W112_002','W112_003','W112_004','W121_002','W113_007','W121_003','W113_008','W113_009','W121_004','W113_010','W113_011','W113_012','W121_005','W113_013','W121_006','W121_007','W121_008','W113_014','W121_009','W121_010','W140_001','W140_002','W150_001','W150_002','W150_003','W150_004','W150_005','W150_006','W113_015','W113_016','W113_018','W113_019','W113_020','W113_021','W113_022','W113_023','W113_024','W133_001','W122_001','W133_002','W134_001','W133_003','W160_001','W122_002','W160_002','W230_001','W230_002','W230_004','W230_005','W230_006','W230_007','W230_009','W230_010','W700_006','W210_001','W210_002','W210_003','W210_004','W210_005','W210_006','W210_007','W210_008','W210_009','W210_010','W210_011','W122_003','W122_004','W210_012','W220_001','W220_002','W122_005','W220_003','W220_004','W220_005','W122_006','W220_006','W122_007','W122_008','W122_009','W240_001','W122_010','W122_011','W240_002','W122_012','W240_003','W122_013','W240_004','W122_014','W240_005','W240_006','W240_007','W240_008','W240_009','W240_010','W240_011','W240_012','W300_001','W300_002','W300_003','W300_004','W300_005','W300_006','W300_007','W300_008','W400_001','W400_002','W500_001','W500_002','W500_003','W500_004','W170_001','W170_002','W170_003','W170_004');";
$result = $mysqli->query($sql);
$style_data = array();
$style_data = con($result);
//echo '<pre>';
//print_r($style_data);die;
$color_arr = array('W' => "白", 'Y' => "黄", 'R' => "玫瑰金", 'C' => "分色");
$patt = '/-([A-Z])-/';
foreach($style_data as $k=>$v){
    preg_match($patt,$v['goods_sn'],$m);
    if(empty($m)){
        $style_data[$k]['pcolor']='--';
        continue;
    }
    if(array_key_exists($m[1],$color_arr)){
        $style_data[$k]['pcolor']=$color_arr[$m[1]];
    }else{
        $style_data[$k]['pcolor']='--';
    }
}

$sql = "SELECT `style_sn`,`fee_type`,`price` FROM `app_style_fee` WHERE `style_sn` IN ('KLRW006275','KLRW006426','KLRW013823','KLRW021064','KLRW021066','KLRW021656','KLRW022271','KLRW022614','W3192','W4451','W5576','W5184','W4594','W6568','W5966','W6583','W8058','W2000','W6800','W2115','W7821','W9846','W1985','W5564','W5087','W1449','W1273','W2068','W6383','W2815','W4950','W4037','W4421','W4578','W1999','W9489','W8019','W9148','W6310','W6225','W2770','W4329','W7550','W1512','W7258','W5177','W3491','W1978','W3663','W4767','W6767','W3081','W5459','W5154','W9253','W6022','W2635','W6439','W6212','W9793','W7170','W8259','W2553','W7193','W9889','W8614','W4633','W4616','W9258','W5204','W5372','W2712','W8488','W7275','W4356','W5162','W5561','W6281','W8578','W2059','W8722','W2118','W7456','W1186','W4947','W1374','W3155','W4345','W1971','W8138','W2907','W7762','W9194','W8089','W9109','W3669','W6578','W8792','W4685','W4090','W8175','W113_002','W111_001','W111_002','W113_005','W111_003','W111_004','W111_005','W111_006','W111_007','W111_008','W111_009','W111_010','W111_011','W111_012','W111_013','W111_014','W112_001','W112_002','W112_003','W112_004','W121_002','W113_007','W121_003','W113_008','W113_009','W121_004','W113_010','W113_011','W113_012','W121_005','W113_013','W121_006','W121_007','W121_008','W113_014','W121_009','W121_010','W140_001','W140_002','W150_001','W150_002','W150_003','W150_004','W150_005','W150_006','W113_015','W113_016','W113_018','W113_019','W113_020','W113_021','W113_022','W113_023','W113_024','W133_001','W122_001','W133_002','W134_001','W133_003','W160_001','W122_002','W160_002','W230_001','W230_002','W230_004','W230_005','W230_006','W230_007','W230_009','W230_010','W700_006','W210_001','W210_002','W210_003','W210_004','W210_005','W210_006','W210_007','W210_008','W210_009','W210_010','W210_011','W122_003','W122_004','W210_012','W220_001','W220_002','W122_005','W220_003','W220_004','W220_005','W122_006','W220_006','W122_007','W122_008','W122_009','W240_001','W122_010','W122_011','W240_002','W122_012','W240_003','W122_013','W240_004','W122_014','W240_005','W240_006','W240_007','W240_008','W240_009','W240_010','W240_011','W240_012','W300_001','W300_002','W300_003','W300_004','W300_005','W300_006','W300_007','W300_008','W400_001','W400_002','W500_001','W500_002','W500_003','W500_004','W170_001','W170_002','W170_003','W170_004')";
$result = $mysqli->query($sql);
$style_fee_data = array();
$style_fee_data = con($result);
foreach($style_data as $ke=>$va){
    if(!empty($style_fee_data)){
        foreach($style_fee_data as $key=>$val){
            if(strstr($val['style_sn'],$va['style_sn'])){
                if($val['fee_type']==1){
                    $style_data[$ke]['caizhifee']=$val['price'];
                }
                if($val['fee_type']==2){
                    $style_data[$ke]['chaoshifee']=$val['price'];
                }
                if($val['fee_type']==3){
                    $style_data[$ke]['biaomiangongyifee']=$val['price'];
                }
            }
        }
    }
}
function con($arr)
{
    $data_info = array();
    while($row = $arr->fetch_array(MYSQLI_ASSOC)){

        $data_info[] = $row;
    }

    return $data_info;
}
$str = iconv('utf-8','gb2312',"款式编号,版式编码,主石重,主石数,副石1重,副石1数,副石2重,副石2数,其他副石成本价,材质,材质颜色,材质金重,金重上公差,金重下公差,镶口,手寸,金托工费,表面工艺处理费,副石镶石费,定制成本,上架状态\n");
foreach ($style_data as $key => $value) {
    # code...
    $caizhifee = isset($value['caizhifee'])?$value['caizhifee']:0.00;
    $chaoshifee = isset($value['chaoshifee'])?$value['chaoshifee']:0.00;
    $biaomiangongyifee = isset($value['biaomiangongyifee'])?$value['biaomiangongyifee']:0.00;
    $style_sn = iconv('utf-8','gb2312',$value['style_sn']);
    $a = iconv('utf-8','gb2312',$value['goods_sn']);
    $b = iconv('utf-8','gb2312',$value['主石重']);
    $c = iconv('utf-8','gb2312',$value['主石数']);
    $d = iconv('utf-8','gb2312',$value['副石1重']);
    $e = iconv('utf-8','gb2312',$value['副石1数']);
    $f = iconv('utf-8','gb2312',$value['副石2重']);
    $g = iconv('utf-8','gb2312',$value['副石2数']);
    $h = iconv('utf-8','gb2312',$value['其他副石成本价']);
    $i = iconv('utf-8','gb2312',$value['材质']);
    $j = iconv('utf-8','gb2312',$value['pcolor']);
    $k = iconv('utf-8','gb2312',$value['材质金重']);
    $l = iconv('utf-8','gb2312',$value['金重上公差']);
    $x = iconv('utf-8','gb2312',$value['金重下公差']);
    $m = iconv('utf-8','gb2312',$value['镶口']);
    $n = iconv('utf-8','gb2312',$value['手寸']);
    $o = iconv('utf-8','gb2312',$caizhifee);
    $p = iconv('utf-8','gb2312',$chaoshifee);
    $q = iconv('utf-8','gb2312',$biaomiangongyifee);
    $r = iconv('utf-8','gb2312',$value['定制成本']);
    $s = iconv('utf-8','gb2312',$value['上架状态']);
    $str .= $style_sn.",".$a.",".$b.",".$c.",".$d.",".$e.",".$f.",".$g.",".$h.",".$i.",".$j.",".$k.",".$l.",".$x.",".$m.",".$n.",".$o.",".$p.",".$q.",".$r.",".$s."\n";
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