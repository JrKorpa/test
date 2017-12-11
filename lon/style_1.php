<?php
header("Content-type: text/html;charset=utf-8");
set_time_limit(0);
error_reporting();

$localhost = '192.168.0.95';
$db_user   = 'cuteman';
$db_pass   = 'QW@W#RSS33#E#';

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

$sql = "select `s`.`style_sn`,
(select `attribute_name` from `app_attribute` where `attribute_id` = `r`.`attribute_id`) as `attribute`,
`r`.`attribute_value` 
from `base_style_info` `s` 
left join `rel_style_attribute` `r` on `s`.`style_sn` = `r`.`style_sn` 
where `s`.`check_status` <> 5 
and `r`.`attribute_id` in(1,5)";
$result = $mysqli->query($sql);
$data = con($result);

$data1 = array();
foreach ($data as $key => $value) {
    # code...
    $data1[$value['style_sn']][$value['attribute']] = $value['attribute_value'];
}

$xk_attr = array();
$sql = "select `att_value_id`,`att_value_name` from `app_attribute_value` where `attribute_id` = 1";
$result = $mysqli->query($sql);
$xk_attr = con($result);

$sc_attr = array();
$sql = "select `att_value_id`,`att_value_name` from `app_attribute_value` where `attribute_id` = 5";
$result = $mysqli->query($sql);
$sc_attr = con($result);

foreach ($xk_attr as $key => $value) {
    # code...
    $xk_attr1[$value['att_value_id']] = $value['att_value_name'];
}

foreach ($sc_attr as $key => $value) {
    # code...
    $sc_attr1[$value['att_value_id']] = $value['att_value_name'];
}

$sc_attr = array();
$sql = "select `style_sn`,`stone`,`finger` from `front`.`app_xiangkou`";
$result = $mysqli->query($sql);
$info = con($result);

foreach ($info as $key => $value) {
    # code...
    $info1[] = $value['style_sn']."-".$value['stone']."-".$value['finger'];
}

$str1 = '';
$str2 = '';
$str3 = '';

$data2 = array();
foreach ($data1 as $style_sn => $attr) {
    # code...
    if(isset($attr['镶口']) && $attr['镶口'] != ''){

        $xiangkou = array_filter(explode(",", $attr['镶口']));
        if(isset($attr['指圈']) && $attr['指圈'] != ''){

            $stone = array_filter(explode(",", $attr['指圈']));
            foreach ($xiangkou as $xk) {
                # code...
                foreach ($stone as $sc) {
                    # code...
                    $str = $style_sn."-".$xk_attr1[$xk]."-".$sc_attr1[$sc];
                    if(!in_array($str, $info1)){

                        $str1.= $style_sn.",<br>";
                    }
                }
            }
        }else{

            $str2.= $style_sn.",<br>";
            continue;
        }
    }else{

        $str3.= $style_sn.",<br>";
        continue;
    }
}
echo $str1;
echo '+++++<br>';
echo $str2;
echo '+++++<br>';
echo $str3;

