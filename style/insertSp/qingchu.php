<?php
header("Content-type: text/html; charset=utf-8");
set_time_limit(0);
error_reporting(0);

//$localhost = '203.130.44.199';
$localhost = 'localhost';
$db_user   = 'root';
$db_pass   = '';

$localhost = '192.168.0.95';
$db_user   = 'cuteman';
$db_pass   = 'QW@W#RSS33#E#';

//创建对象并打开连接，最后一个参数是选择的数据库名称 
$mysqli = new mysqli($localhost, $db_user ,$db_pass, 'front_test'); 
//print_r($mysqli);die;
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

$color_arr = array("白"=>"W","黄"=>"Y","玫瑰金"=>"R","分色"=>"C","彩金"=>"H","玫瑰黄"=>"RY","玫瑰白"=>"RW","黄白"=>"YW");
$color_value_arr = array("白"=>1,"黄"=>2,"玫瑰金"=>3,"分色"=>4,"彩金"=>5,"玫瑰黄"=>6,"玫瑰白"=>7,"黄白"=>8);

//读取镶口、金重信息
//$sql = "select `x`.* from `base_style_info` `s` inner join `app_xiangkou` `x` on `s`.`style_sn` = `x`.`style_sn` where `s`.`style_type` not in(2,10,11) and `x`.`finger` = '0';";
$sql = "select `x`.* from `base_style_info` `s` inner join `app_xiangkou` `x` on `s`.`style_sn` = `x`.`style_sn` where s.check_status = 3";
//
//$sql = "select * from base_style_info where style_sn in(select goods_sn from style_sn_now)";
$result = $mysqli->query($sql);
//print_r($result);die;
$s_xiangkou = array();
$s_xiangkou = con($result);
//所有款式信息
$sql = "select `style_id`,`style_sn`,`style_name`,`product_type`,`style_type`,`is_made`,`check_status` from `base_style_info`";
$result = $mysqli->query($sql);
$s_styleinfo = array();
$s_styleinfo = con($result);

$style_data = array();
foreach ($s_styleinfo as $key => $value) {
    # code...
    $style_data[$value['style_id']] = $value;
}
//echo '<pre>';
//print_r($s_xiangkou);die;


//$listStyleGoods = getListStyleGoods();

//获取款式属性信息
$sql = "select `attribute_id`,`attribute_name` from `app_attribute`;";
$result = $mysqli->query($sql);
$s_styleattr = array();
$s_styleattr = con($result);
$style_attr = array();
foreach ($s_styleattr as $key => $value) {
    # code...
    $style_attr[$value['attribute_name']] = $value['attribute_id'];
}

//获取款式属性值信息
$sql = "select `style_sn`,`attribute_id`,`attribute_value` from `rel_style_attribute`;";
$result = $mysqli->query($sql);
$s_styleattrval = array();
$s_styleattrval = con($result);

$style_attrval = array();
foreach ($s_styleattrval as $key => $value) {
    # code...
    $style_attrval[$value['style_sn']][$value['attribute_id']] = $value['attribute_value'];
}

//属性值配置表取名称
$sql = "select `att_value_id`,`att_value_name` from `app_attribute_value`;";
$result = $mysqli->query($sql);
$s_attrval = array();
$s_attrval = con($result);

$_attrval = array();
foreach ($s_attrval as $key => $value) {
    # code...
    $_attrval[$value['att_value_id']] = $value['att_value_name'];
}

//echo '<pre>';
//print_r($style_attr);die;

$is_flag = false;
$num1 = 0;
$num2 = 0;
$sxl = '';
//遍历金重信息，判断是否可以生成商品
foreach ($s_xiangkou as $key => $val) {

    $style_caizhi = array();
    $style_yanse = array();

    $styleNew = $style_data[$val['style_id']];
    $cz_id = $style_attr['材质'];
    $ys_id = $style_attr['材质颜色'];
    $xk_id = $style_attr['镶口'];
    $zq_id = $style_attr['指圈'];
    $style_xk = array();
    //镶口
    if(isset($style_attrval[$val['style_sn']][$xk_id])){

        $xk_str = '';
        $xk_str = $style_attrval[$val['style_sn']][$xk_id];
        if($xk_str != ''){
            $xk_info = array();
            $xk_info = explode(",",rtrim($xk_str,","));

            //材质
            foreach ($xk_info as $v){

                $style_xk[]=isset($_attrval[$v])?$_attrval[$v]:array();
            }
        }else{
            $sxl = $val['style_sn'].'镶口为空';
        file_put_contents("xl.log",$sxl."\r\n",FILE_APPEND);
        }
    }else{
        $sxl = $val['style_sn'].'无镶口';
        file_put_contents("xl.log",$sxl."\r\n",FILE_APPEND);
    }
    $style_zq = array();
    //手寸
    if(in_array($styleNew['style_type'],array(2,10,11))){
        if(isset($style_attrval[$val['style_sn']][$zq_id])){
            $zq_str = '';
            $zq_str = $style_attrval[$val['style_sn']][$zq_id];
            if($zq_str != ''){
                $zq_info = array();
                $zq_info = explode(",",rtrim($zq_str,","));

                //材质
                foreach ($zq_info as $v){

                    $style_zq[]=isset($_attrval[$v])?$_attrval[$v]:array();
                }
            }else{
                $sxl = $val['style_sn'].'手寸为空';
            file_put_contents("xl.log",$sxl."\r\n",FILE_APPEND);
            }
        }else{
            $sxl = $val['style_sn'].'无手寸';
            file_put_contents("xl.log",$sxl."\r\n",FILE_APPEND);
        }
    }else{
        $style_zq = array(0=>0);
    }
    //判断巷口和职权是否在属性里
    if(!in_array($val['stone'], $style_xk)){
        $tss = $val['x_id'];
        file_put_contents("ztss.log",$tss.",\r\n",FILE_APPEND);
    }
    if(!in_array($val['finger'],$style_zq)){
        $tss = $val['x_id'];
        file_put_contents("ztss.log",$tss.",\r\n",FILE_APPEND);
    }
}
exit('完成');