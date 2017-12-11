<?php
header("Content-type:text/html;charset=utf8;");
set_time_limit(0);
error_reporting(E_ALL);

//$localhost = '203.130.44.199';
$localhost = '192.168.10.23';
$db_user   = 'root';
$db_pass   = '1308b8dac1e577';

//创建对象并打开连接，最后一个参数是选择的数据库名称 
$mysqli = new mysqli($localhost, $db_user ,$db_pass, 'front'); 
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
$sql = "select `x`.* from `base_style_info` `s` inner join `app_xiangkou` `x` on `s`.`style_sn` = `x`.`style_sn` where `s`.`style_type` not in(2,10,11) and `x`.`finger` = '0';";
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
$sql = "select `style_id`,`attribute_id`,`attribute_value` from `rel_style_attribute`;";
$result = $mysqli->query($sql);
$s_styleattrval = array();
$s_styleattrval = con($result);

$style_attrval = array();
foreach ($s_styleattrval as $key => $value) {
    # code...
    $style_attrval[$value['style_id']][$value['attribute_id']] = $value['attribute_value'];
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
//print_r($style_attrval);die;

$is_flag = false;
$num1 = 0;
$num2 = 0;
$sxl = '';
//遍历金重信息，判断是否可以生成商品
foreach ($s_xiangkou as $key => $val) {
    # code...
    if(!isset($style_data[$val['style_id']])){//不存在
        $sxl = $val['style_sn'].'不存在款';
        file_put_contents("xl.log",$sxl."\r\n",FILE_APPEND);
        continue;
    }

    $styleNew = $style_data[$val['style_id']];
    if($styleNew['is_made'] == 0){//不是定制商品
        $sxl = $val['style_sn'].'不是定制商品';
        file_put_contents("xl.log",$sxl."\r\n",FILE_APPEND);
        continue;
    }

    if($styleNew['check_status'] != 3){//未审核
        $sxl = $val['style_sn'].'未审核';
        file_put_contents("xl.log",$sxl."\r\n",FILE_APPEND);
        continue;
    }

    $style_caizhi = array();
    $style_yanse = array();

    $cz_id = $style_attr['材质'];
    $ys_id = $style_attr['材质颜色'];

    if(isset($style_attrval[$val['style_id']][$cz_id])){

        $cz_str = '';
        $cz_str = $style_attrval[$val['style_id']][$cz_id];
        if($cz_str != ''){
            $cz_info = array();
            $cz_info = explode(",",rtrim($cz_str,","));

            //材质
            foreach ($cz_info as $v){

                $style_caizhi[]=isset($_attrval[$v])?$_attrval[$v]:array();
            }
        }else{
            $sxl = $val['style_sn'].'材质为空';
        file_put_contents("xl.log",$sxl."\r\n",FILE_APPEND);
            continue;
        }
    }else{
        $sxl = $val['style_sn'].'无材质';
        file_put_contents("xl.log",$sxl."\r\n",FILE_APPEND);
        continue;
    }

    if(isset($style_attrval[$val['style_id']][$ys_id])){

//var_dump($val['style_id'],$ys_id,$style_attrval[$val['style_id']][$ys_id]);die;

        $ys_str = '';
        $ys_str = $style_attrval[$val['style_id']][$ys_id];


        if($ys_str != ''){
            $ys_info = array();
            $ys_info = explode(",",rtrim($ys_str,","));

            foreach ($ys_info as $v){
                $style_yanse[]=isset($_attrval[$v])?$_attrval[$v]:array();
            }
        }else{
            $sxl = $val['style_sn'].'颜色为空';
        file_put_contents("xl.log",$sxl."\r\n",FILE_APPEND);
            continue;
        }
    }else{
        $sxl = $val['style_sn'].'无颜色';
        file_put_contents("xl.log",$sxl."\r\n",FILE_APPEND);
        continue;
    }
//print_r($style_caizhi);die;
    array_filter($style_yanse);
    if(empty($style_caizhi) || empty($style_yanse)){

$sxl = $val['style_sn'].'材质颜色为空';
        file_put_contents("xl.log",$sxl."\r\n",FILE_APPEND);
        continue;
    }
    if( in_array("18K", $style_caizhi)){
        $is_flag = true;
        $yanse_data = array();
        //print_r($style_yanse);die;
//        var_dump($style_yanse);
        foreach ($style_yanse as $vs){

            if(array_key_exists($vs, $color_arr)){

                $yanse_data[$color_value_arr[$vs]] = $color_arr[$vs];
            }
        }


//        var_dump('.............',$yanse_data,'.............');die;


        $caizhi = array('id'=>1,'name'=>"18K");
        $num1 += create_goods_insert($styleNew, $val, $caizhi, $yanse_data);
    }
    
    //PT950
    if( in_array("PT950", $style_caizhi)){
        $is_flag = true;

        //只有一个颜色那就是白色
        $yanse_data_pt = array();
        $yanse_data_pt[$color_value_arr["白"]] = $color_arr["白"];

        $caizhi = array('id'=>2,'name'=>"PT950");
        $num2 += create_goods_insert($styleNew, $val, $caizhi, $yanse_data_pt);
    }
}

if($is_flag){
    $num = 0;
    $num = $num1 + $num2;
    echo '操作成功,一共生成'.$num.'条SKU。';die;
}else{
    echo '生成商品出错！';die;
}

function create_goods_insert($styleNew, $jinZongInfo, $caizhiInfo, $yanse_data)
{
    //global $listStyleGoods;
    # code...
    # 
    $num = 0;
    $style_sn = '';
    $style_name = '';
    $product_type_id = '';
    $caizhi_id = $caizhiInfo['id'];
    $caizhi_name = $caizhiInfo['name'];
    $stone = $jinZongInfo['stone'];
    $finger = $jinZongInfo['finger'];
    $caizhi = $caizhiInfo['id'];
    $style_id = $styleNew['style_id'];
    $style_sn = $styleNew['style_sn'];
    $style_name = $styleNew['style_name'];
    $product_type_id = $styleNew['product_type'];
    $cat_type_id = $styleNew['style_type'];
    //切分手寸
    //$cut_finger = array();
    //$cut_finger = cutFingerInfo($finger);
//echo '<pre>';
//print_r($yanse_data);die;
    //循环颜色
    $newGoodsInfo = array();
    foreach ($yanse_data as $ys_key => $ys_val) {
        # code...
        if(trim($jinZongInfo['sec_stone_weight']) === ''){

$sxl = $val['style_sn'].'副石1重为空';
        file_put_contents("xl.log",$sxl."\r\n",FILE_APPEND);
            continue;
        }

        $color_name = $ys_val;
        $newGoodsInfo['style_id']=$style_id; //款式id
        $newGoodsInfo['style_sn']=$style_sn; //款式编码
        $newGoodsInfo['product_type_id']=$product_type_id; //产品线id
        $newGoodsInfo['cat_type_id']=$cat_type_id; //分类id
        $newGoodsInfo['style_name'] = $style_name; //款式名称
        $newGoodsInfo['caizhi']=$caizhi; //材质
        $newGoodsInfo['yanse']=$ys_key; //颜色编号
        $newGoodsInfo['xiangkou'] = $stone; //镶口

        $newGoodsInfo['zhushizhong']=$jinZongInfo['main_stone_weight']; //主石重 
        $newGoodsInfo['zhushi_num']=$jinZongInfo['main_stone_num']; //主石数 
        $newGoodsInfo['fushizhong1']=$jinZongInfo['sec_stone_weight']; //副石1重 
        $newGoodsInfo['fushi_num1']=$jinZongInfo['sec_stone_num']; //副石1数量
        $newGoodsInfo['fushizhong2']=$jinZongInfo['sec_stone_weight_other']; //副石2重
        $newGoodsInfo['fushi_num2']=$jinZongInfo['sec_stone_num_other']; // 副石2数量
        $newGoodsInfo['fushizhong3']=$jinZongInfo['sec_stone_weight3']; //副石2重
        $newGoodsInfo['fushi_num3']=$jinZongInfo['sec_stone_num3']; // 副石2数量
        //$newGoodsInfo['fushi_chengbenjia_other']=$xiangkou['sec_stone_price_other']; // 其他副石成本价
        $newGoodsInfo['dingzhichengben'] = 0; //定制成本 暂时为0，后面同一洗钱
        if($caizhi == 1){
            $newGoodsInfo['weight']=$jinZongInfo['g18_weight']; //18K标准金重
            $newGoodsInfo['jincha_shang']=$jinZongInfo['g18_weight_more']; //18K金重上公差 
            $newGoodsInfo['jincha_xia']=$jinZongInfo['g18_weight_more2']; // 18K金重下公差 
        }else{
            $newGoodsInfo['weight']=$jinZongInfo['gpt_weight']; //PT950标准金重 
            $newGoodsInfo['jincha_shang']=$jinZongInfo['gpt_weight_more']; //PT950金重上公差 
            $newGoodsInfo['jincha_xia']=$jinZongInfo['gpt_weight_more2']; //PT950金重下公差
        }

        $newGoodsInfo['last_update']=date("Y-m-d H:i:s"); //最后更新时间

        //如果为空则写入0；
        if($jinZongInfo['sec_stone_weight_other']==""){

            $newGoodsInfo['fushizhong2']=0; //副石2重
        }
        if($jinZongInfo['sec_stone_num_other']==""){

            $newGoodsInfo['fushi_num2']=0;// 副石2数量
        }
        if($jinZongInfo['sec_stone_weight3']==""){

            $newGoodsInfo['fushizhong3']=0; //副石2重
        }
        if($jinZongInfo['sec_stone_num3']==""){

            $newGoodsInfo['fushi_num3']=0;// 副石2数量
        }
        if($caizhi == 1){
            if($jinZongInfo['g18_weight']==""){
                $newGoodsInfo['weight'] =0;// 18K标准金重
            }
            if($jinZongInfo['g18_weight_more']==""){
                $newGoodsInfo['jincha_shang'] =0;// 18K金重上公差 
            }
            if($jinZongInfo['g18_weight_more2']==""){
                $newGoodsInfo['jincha_xia'] =0;// 18K金重下公差
            }
        }else{
            if($jinZongInfo['gpt_weight']==""){
                $newGoodsInfo['weight'] =0;// PT950标准金重 
            }
            if($jinZongInfo['gpt_weight_more']==""){
                $newGoodsInfo['jincha_shang'] =0;// PT950金重上公差
            }
            if($jinZongInfo['gpt_weight_more2']==""){
                $newGoodsInfo['jincha_xia'] =0;// PT950金重下公差
            }  
        }
        $newGoodsInfo['fushi_chengbenjia_other'] =0;// 
        //循环指圈
        # code...
        $shoucun = 0;//手寸
        $newGoodsInfo['shoucun'] = $shoucun;
        $stone_name = $stone * 100;
        //删除同一款、同一镶口、同一手寸、同一颜色的商品;
        $goods_sn = $style_sn."-".$caizhi_name."-".$color_name."-".$stone_name."-".$shoucun;//生成虚拟货号
        deleteListstylegoods($goods_sn); 
        $newGoodsInfo['goods_sn'] = $goods_sn;
        echo $goods_sn."\r\n";
        $r = insert_goods_info($newGoodsInfo);
        if($r){
            $num++;
        }

        
    }
    return $num;
}

function deleteListstylegoods($sn){
    global $mysqli;
    $sql = "delete from front.`list_style_goods` where goods_sn = '{$sn}';";
    $arr = $mysqli->query($sql);
}

function getListStyleGoods(){
    global $mysqli;
    $sql = "select * from front.`list_style_goods`;";
    $arr = $mysqli->query($sql);
    $listStyleGoods = array();
    while($w = $arr->fetch_array(MYSQLI_ASSOC)){

        $listStyleGoods[$w['goods_sn']] = $w['goods_id'];
    }
    return $listStyleGoods;
}


/*
 * 切割手寸
 * 转换数据 6-8 其实要变成 6,7,8
 */
function cutFingerInfo($data){

    if(empty($data)){

        continue;
    }    

    $is_search = checkString('-', $data);

    $new_arr = array();
    if($is_search){

        $tmp = explode('-', $data);

        $min = intval($tmp[0]);
        $max = intval($tmp[1]);

        if($min == $max) {

             $new_arr[] = $min;
        }else{

            for($i=$min;$i<=$max;$i++){

                $new_arr[] = $i;
            }
        }
    }else{

         $new_arr[] = $data;
    }
    $data=$new_arr;
    return $data;
}

//检查字符串是否存在
function checkString($search,$string){

    $pos = strpos($string, $search);

    if($pos == false){

        return false;
    }else{

        return true;
    }
}

//插入商品信息
function insert_goods_info($data){
    global $mysqli;
    $result = '';
    $keys = array_keys($data);
    $values = array_values($data);
    $sql = "insert into `list_style_goods` (".implode(',',$keys).") values('".implode("','",$values)."');";
    $result = $mysqli->query($sql);
    var_dump($result);
    echo "<br>";
    file_put_contents("s.log",$sql."\r\n",FILE_APPEND);
    echo "\r\n";
    return $result;
}