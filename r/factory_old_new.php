<?php
/**
 * @Author: anchen
 * @Date:   2015-08-06 16:09:48
 * @Last Modified by:   anchen
 * @Last Modified time: 2015-08-06 16:14:31
 */

//$db1 = mysqli_connect('192.168.1.55', 'style_zyy', 'KELAzhangyuanyuan123', 'kela_style');
$db1 = mysqli_connect('127.0.0.1', 'root', '', 'kela_style');
$sql = "set names utf8;";
$arr = mysqli_query($db1, $sql);
$sql = "select * from `kela_style`.`style_style` where `style_sn` in('KLSW028589','KLSM028590','KLSW028591','KLSW028592','KLSW028593','KLSW028594','KLSW028595','KLSX028597','KLSM028598','KLSM028599','KLSM028600','KLSM028601','KLSW028602','KLSW028603','KLSM028604','KLPX028605','KLPX028606','KLPX028607','KLPX028608','KLPX028609','KLPX028610','KLPX028611','KLPX028613','KLPX028614','KLPX028615','KLQX028616','KLQX028617','KLQX028618','KLQX028619');";

$db2 = mysqli_connect('127.0.0.1', 'root', '', 'test');
$sql = "set names utf8;";
$arr = mysqli_query($db2, $sql);
$sql = "select style_id,style_sn from `test`.`base_style_info`;";
$arr = mysqli_query($db2, $sql);
$data_v= array();
while($w=mysqli_fetch_assoc($arr)){
    $data_v[$w['style_sn']] = $w['style_id'];
}


$arr = mysqli_query($db1, $sql);
$data= array();
while($w=mysqli_fetch_assoc($arr)){
    $data[$w['style_id']] = $w['style_sn'];
}
//echo '<pre>';
//print_r($data);die;
foreach ($data as $key => $value) {
    # code...
    $sql = "select * from `kela_style`.`style_factory` where `style_id` = {$key}";
    //echo $sql;die;
    $arr = mysqli_query($db1, $sql);
    while($w = mysqli_fetch_assoc($arr)){
        $data_a[$value][] = $w;
    }
}
$str = '';
foreach ($data_a as $key => $value) {
    # code...
    foreach ($value as $k => $v) {
        # code...
        $sql = "select * from `rel_style_factory` where `style_sn` = '{$key}' and `factory_id` = ".$v['factory_id']." and `factory_sn` = '".$v['factory_sn']."' and `xiangkou` = ".$v['xiangkou']."";
        $arr = mysqli_query($db2, $sql);
        while($w = mysqli_fetch_assoc($arr)){
            $r[] = $w;
        }
        if($r){
            continue;
        }
        //echo $sql;die;
        $str .= "(".$data_v[$key].",'{$key}',".$v['factory_id'].",'".$v['factory_sn']."',".$v['factory_fee'].",".$v['xiangkou'].",".$v['is_def'].",".$v['is_factory'].",1),";
    }
}

$sql = "insert into `rel_style_factory` (`style_id`,`style_sn`,`factory_id`,`factory_sn`,`factory_fee`,`xiangkou`,`is_def`,`is_factory`,`is_cancel`) values{$str}";

echo $sql;die;
$arr = mysqli_query($db2,$sql);
if($arr){
    echo '1';die;
}
echo '0';die;