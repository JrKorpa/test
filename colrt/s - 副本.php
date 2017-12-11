<?php
/**
 * @Author: anchen
 * @Date:   2015-07-13 22:07:20
 * @Last Modified by:   anchen
 * @Last Modified time: 2015-07-14 19:41:55
 */
header("Content-type:text/html;charset=utf8;");
set_time_limit(0);
error_reporting(0);
//error_reporting( E_ALL&~E_NOTICE );
$db = mysqli_connect('127.0.0.1', 'root', '', 'test');
$sql = "set names utf8;";
$arr = mysqli_query($db, $sql);
$sql = "select * from `jxc_goods`";
$arr = mysqli_query($db, $sql);
$app_data= array();
while($w=mysqli_fetch_assoc($arr)){
    $app_data[] = $w;
}
$sql = "select `style_name`,`style_sn` from `base_style_info`";
$arr = mysqli_query($db, $sql);
$base_data = array();
while($w=mysqli_fetch_assoc($arr)){
    $base_data[$w['style_sn']] = $w['style_name'];
}
//echo '<pre>';
//print_r($base_data);die;
$style_sn = array();
foreach ($app_data as $key => $value) {
    # code...
    $style_sn[$value['goods_sn']] = [];
}
$data = array();
$prc_name = array();
$mo_sn = array();
$zhushizhong = array();
$zhushilishu = array();
$fushizhong = array();
$fushilishu = array();
$zhuchengse = array();
$zhushizhongjijia = array();
$fushizhongjijia = array();

foreach ($app_data as $key => $value) {
    # code...
    $prc_name[$value['goods_sn']] = $value['prc_name'];
    $mo_sn[$value['goods_sn']][] = $value['mo_sn'];
    $zhuchengse[$value['goods_sn']][] = $value['zhuchengse'];
    $xiangkou[$value['goods_sn']][$value['mo_sn']][] = $value['jietuoxiangkou'];
    $zhushizhong[$value['goods_sn']][$value['jietuoxiangkou']][] = $value['zhushizhong'];
    $zhushilishu[$value['goods_sn']][$value['zhushizhong']] = $value['zhushilishu'];
    $fushizhong[$value['goods_sn']][$value['fushilishu']] = $value['fushizhong'];
    $zhushizhongjijia[$value['goods_sn']] = $value['zhushizhongjijia'];
    $fushizhongjijia[$value['goods_sn']] = $value['fushizhongjijia'];
}
//echo '<pre>';
$zhushizhong_arr = array();
$zhuchengse_arr = array();
$zhushizhongjijia_arr = array();
$fushizhongjijia_arr = array();
//print_r($fushizhongjijia);die;
//
foreach ($zhuchengse as $key => $value) {
    # code...
    $zhuchengse_arr[$key] = array_flip($value);
}

foreach ($zhushizhongjijia as $key => $value) {
    # code...
     $zhushizhongjijia_arr[$key] = array_flip($value);
}

foreach ($zhushizhongjijia as $key => $value) {
    # code...
     $zhushizhongjijia_arr[$key] = array_flip($value);
}

foreach ($fushizhongjijia as $key => $value) {
    # code...
     $fushizhongjijia_arr[$key] = array_flip($value);
}

//echo '<pre>';
//print_r($zhushizhongjijia_arr);die;
if(is_array($zhushizhong)){
    foreach ($zhushizhong as $k => $v) {
        # code...
        foreach ($v as $x => $va) {
            # code...
            $zhushizhong_arr[$k][$x] = array_flip($va);
        }
    }
}

$xiangkou_mo = array();
$xiangkou_mo = filp($xiangkou);
//print_r($zhushizhong_arr);die;
//$zhushizhong_arr = filp($zhushizhong);
$mo_sn = filp($mo_sn);
//var_dump($xiangkou_mo);die;
function filp($arr){
    $content = array();
    if(is_array($arr)){
        foreach ($arr as $key => $value) {
            # code...
            $content[$key] = array_flip($value);
        }
    }
    return $content;
}
$prc_name = array_flip($prc_name);
//echo '<pre>';
//print_r($mo_sn);die;
//$data[$value['goods_sn']] = 1;

echo "<table border=1>";
        echo "<tr>";
        echo "<td width='10%'>";
        echo "45°图";
        echo "</td>";
        echo "<td width='10%'>";
        echo "款号";
        echo "</td>";
        echo "<td width='10%'>";
        echo "工厂名称";
        echo "</td>";
        echo "<td width='10%'>";
        echo "工厂模号";
        echo "</td>";
        echo "<td width='10%'>";
        echo "主成色";
        echo "</td>";
        echo "<td width='10%'>";
        echo "镶口";
        echo "</td>";
        echo "<td width='10%'>";
        echo "主石重";
        echo "</td>";
        echo "<td width='10%'>";
        echo "主石数";
        echo "</td>";
        echo "<td width='10%'>";
        echo "副石1重";
        echo "</td>";
        echo "<td width='10%'>";
        echo "副石1数";
        echo "</td>";
        echo "<td width='10%'>";
        echo "向上公差";
        echo "</td>";
        echo "<td width='10%'>";
        echo "向下公差";
        echo "</td>";
        echo "</tr>";
        foreach ($style_sn as $key => $value) {
            # code...
            echo "<tr>";
            echo "<td>";
            echo "</td>";
            echo "<td>";
            echo $key;
            echo "</td>";
            echo "<td>";
                echo "<table border=1>";
                foreach ($prc_name as $k => $v) {
                    # code...
                    echo "<tr>";
                    echo "$k";
                    echo "</tr>";
                }
                echo "</table>";
            echo "</td>";
            echo "<td>";
                echo "<table border='1'>";
                foreach ($mo_sn[$key] as $k => $v) {
                    # code...
                    echo "<tr>";
                    echo "<td>";
                    echo "$k";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            echo "</td>";
            echo "<td>";
                echo "<table border='1'>";
                foreach ($zhuchengse_arr[$key] as $k => $v) {
                    # code...
                    echo "<tr>";
                    echo "<td>";
                    echo "$k";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            echo "</td>";
            echo "<td>";
                echo "<table border='1'>";
                foreach ($zhushizhong_arr[$key] as $k => $v) {
                    foreach ($v as $x => $y) {
                        # code...
                        echo "<tr>";
                        echo "<td>";
                        echo "$x";
                        echo "</td>";
                        echo "</tr>";
                    }
                    # code...
                    
                }
                echo "</table>";
            echo "</td>";
            echo "<td>";
                echo "<table border='1'>";
                foreach ($fushizhong[$key] as $k => $v) {
                    # code...
                    echo "<tr>";
                    echo "<td>";
                    echo "$v";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            echo "</td>";
            echo "<td>";
                echo "<table border='1'>";
                foreach ($zhushizhongjijia_arr[$key] as $k => $v) {
                    # code...
                    echo "<tr>";
                    echo "<td>";
                    echo "$v";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            echo "</td>";
            echo "<td>";
                echo "<table border='1'>";
                foreach ($fushizhongjijia_arr[$key] as $k => $v) {
                    # code...
                    echo "<tr>";
                    echo "<td>";
                    echo "$k";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            echo "</td>";
            echo "<td>";
                echo "<table border='1'>";
                foreach ($mo_sn[$key] as $k => $v) {
                    # code...
                    echo "<tr>";
                    echo "<td>";
                    echo "$k";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            echo "</td>";
            echo "<td>";
                echo "<table border='1'>";
                foreach ($mo_sn[$key] as $k => $v) {
                    # code...
                    echo "<tr>";
                    echo "<td>";
                    echo "$k";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            echo "</td>";
            echo "<td>";
                echo "<table border='1'>";
                foreach ($mo_sn[$key] as $k => $v) {
                    # code...
                    echo "<tr>";
                    echo "<td>";
                    echo "$k";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            echo "</td>";
        }
        echo "<tr>";
echo "</table>";