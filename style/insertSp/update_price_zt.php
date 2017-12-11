<?php
/**
 * @Author: anchen
 * @Date:   2015-06-30 11:39:49
 * @Last Modified by:   anchen
 * @Last Modified time: 2017-03-13 19:58:24
 */
header("Content-type:text/html;charset=utf8;");
set_time_limit(0);
error_reporting(E_ALL);

//$localhost = '203.130.44.199';
$localhost = '192.168.10.23';
$db_user   = 'root';
$db_pass   = '1308b8dac1e577';

$localhost = '192.168.0.95';
$db_user   = 'cuteman';
$db_pass   = 'QW@W#RSS33#E#';

//创建对象并打开连接，最后一个参数是选择的数据库名称 
$mysqli = new mysqli($localhost, $db_user ,$db_pass, 'front_zt'); 
//检查连接是否成功 
if (mysqli_connect_errno()){ 
    //注意mysqli_connect_error()新特性 
    die('Unable to connect!'). mysqli_connect_error(); 
}

$sql = "select * from `base_style_info` `s` inner join `list_style_goods` `x` on `s`.`style_sn` = `x`.`style_sn` where `x`.goods_sn in('KLRW030288-18K-W-100-17')";
$result = $mysqli->query($sql);
$style_data = array();
$style_data = con($result);
function con($arr)
{
    $data_info = array();
    while($row = $arr->fetch_array(MYSQLI_ASSOC)){

        $data_info[] = $row;
    }

    return $data_info;
}

//所有款式信息
$sql = "select `style_id`,`style_sex`,`style_type` from `base_style_info`";
$result = $mysqli->query($sql);
$s_styleinfo = array();
$s_styleinfo = con($result);
$style_datatt = array();
foreach ($s_styleinfo as $key => $value) {
    # code...
    $style_datatt[$value['style_id']] = $value;
}

$sql = "SELECT `style_id`,`fee_type`,`price` FROM `app_style_fee`";
$result = $mysqli->query($sql);
$gongfei_data = con($result);

$sql = "SELECT `parent_id`,`product_type_id` FROM `app_product_type`";
$result = $mysqli->query($sql);
$parent_arr = con($result);

$sql = "SELECT `price`,`min`,`max` FROM `app_style_baoxianfee` WHERE `status` = 1";
$result = $mysqli->query($sql);
$baoxianfeidata = con($result);

$sql = "SELECT `lv`,`material_id`,price_type FROM `app_jinsun`";
$result = $mysqli->query($sql);
$jinsundata = con($result);

$sql = "SELECT `price`,`guige_a`,`guige_b` FROM `app_diamond_price`";
$result = $mysqli->query($sql);
$diamondprice = con($result);

$sql = "SELECT `price`,`tax_point`,`material_name` FROM `app_material_info`  WHERE material_status = 1";
$result = $mysqli->query($sql);
$caizhidata = con($result);

foreach ($gongfei_data as $k => $v) {
    # code...
    $gongfei_data_arr[$v['style_id']][$v['fee_type']] = $v['price'];
};
echo '<pre>';
//var_dump($baoxianfeidata);die;
print_r($gongfei_data_arr);die;

foreach($style_data as $key => $val){
    //echo '<pre>';
    //print_r($val);die;
    //2,每次获取一条基本数据
    $goods_id = $val['goods_id'];
    $style_id = $val['style_id'];
    $style_sn = $val['style_sn'];
    $yanse = $val['yanse'];
    $fushi_1 = $val['fushizhong1'];
    $fushi_num_1 = $val['fushi_num1'];
    $fushi_2 = $val['fushizhong2'];
    $fushi_num_2 = $val['fushi_num2'];
    $fushi_3 = $val['fushizhong3'];
    $fushi_num_3 = $val['fushi_num3'];
    $caizhi = $val['caizhi'];
    $weight = $val['weight'];
    $xiangkou = $val['xiangkou'];
    $jincha_shang = $val['jincha_shang'];
    $product_type_id = $val['product_type_id'];
    $goods_sn = $val['goods_sn'];
    $dingzhichengben_old = $val['dingzhichengben'];
    //$goods_sn[]= $val['goods_sn'];
    //3,工费信息 ：基础工费 表面工艺费 超石费 保险费
    echo '商品'.$val['goods_sn']."<br/>";
    if(!empty($style_id)){
        //获取四种工费
        $gongfei='';
        $baoxianfei = '';
        $baomiangongyi_gongfei='';
        $fushixiangshifei='';
        if($gongfei_data_arr){
            echo '<pre>';
            print_r($gongfei_data_arr[$style_id]);
            if(!empty($gongfei_data_arr[$style_id])){
                foreach ($gongfei_data_arr[$style_id] as $k => $v) {
                # code...
                if($k==1 && $caizhi==1){
                    $gongfei = empty($v)?'0':$v;
                }elseif($k==2){
                    $fushixiangshifei = empty($v)?'0':$v;
                    $fushixiangshifei = $fushixiangshifei * ($fushi_num_1 + $fushi_num_2 + $fushi_num_3);
                }elseif($k==3){
                    $baomiangongyi_gongfei = empty($v)?'0':$v;
                }elseif($k==4 && $caizhi==2){
                    $gongfei = empty($v)?'0':$v;
                }
            }

            }
            
        }

        $parent = '';
        if($parent_arr){
            foreach ($parent_arr as $v) {
                if($v['product_type_id'] == $product_type_id){
                    $parent = $v['parent_id'];
                    break;
                }
            }
        }
//print_r($xiangkou);die;
        if($parent == 3 && $xiangkou != ''){
            if($baoxianfeidata){
               foreach ($baoxianfeidata as $v) {
                    if($xiangkou >= $v['min'] && $xiangkou <= $v['max']){
                        $baoxianfei = $v['price'];
                        break;
                    }
               } 
            }
        }
        
    }
            
    //4,计算各种工费数据
    //var_dump($gongfei,$baomiangongyi_gongfei,$fushixiangshifei,$baoxianfei);die;
    $tal_gongfei = $gongfei+$baomiangongyi_gongfei+$fushixiangshifei+$baoxianfei;
    echo '工费:'.$gongfei;
    echo '表面工艺费:'.$baomiangongyi_gongfei;
    echo '镶石费:'.$fushixiangshifei;
    echo '保险费:'.$baoxianfei."<br/>";

    $is_leibie = 0;
    $style_sex = $style_datatt[$style_id]['style_sex'];
    $style_cat = $style_datatt[$style_id]['style_type'];
    if($style_sex == 2 && $style_cat == 2){
        $is_leibie = 2;
    }
    if($style_sex == 2 && $style_cat == 11){
        $is_leibie = 4;
    }
    if($style_sex == 1 && $style_cat == 10){
        $is_leibie = 1;
    }
    if($style_sex == 1 && $style_cat == 11){
        $is_leibie = 3;
    }
    //金损率:price_type:1男戒2女戒3情侣男戒4情侣女戒;
    //3,判断款号是什么什么戒指，来获取对应的金损
    $jinsunlv = 0;
    if(!empty($caizhi) && !empty($jinsundata)){
        foreach ($jinsundata as $v) {
            # code...
            if($v['material_id'] == $caizhi && $v['price_type'] == $is_leibie){
                $jinsunlv = $v['lv'];
                break;
            }
        }
    }
    if($jinsunlv == 0 && $caizhi == 1){
        $jinsunlv = 0.15;
    }
    if($jinsunlv == 0 && $caizhi == 2){
        $jinsunlv = 0.16;
    }
    
    //5,获取所有钻石规格单价数据
    //(副石1重/副石1数量)的对应单价*副石1重+（副石2重/副石2数量）的对应单价*副石2重+（副石3重/副石3数量）的对应单价*副石3重
    
    $guige = 0;
    $diamond_price = 0;
    if($fushi_num_1){
        $guige = 100*$fushi_1 / $fushi_num_1;
        $diamond_price = 0;
        //获取副石1价格
        foreach ($diamondprice as $k => $v) {
            if($guige > $v['guige_a'] && $guige <= $v['guige_b']){
                $diamond_price = $v['price'];
                break;
            }
        }
        $fushi_price_1 = $diamond_price*$fushi_1;
    }else{
        $fushi_price_1=0;
    }
    echo '副石1：';
    var_dump($guige,$diamond_price,$fushi_price_1);
    $guige = 0;
    $diamond_price = 0;
    if($fushi_num_2){
        $guige = 100*$fushi_2 / $fushi_num_2;
        //获取副石2价格
        $diamond_price = 0;
        foreach ($diamondprice as $k => $v) {
            if($guige > $v['guige_a'] && $guige <= $v['guige_b']){
                $diamond_price = $v['price'];
                break;
            }
        }
        $fushi_price_2 = $diamond_price*$fushi_2;
    }else{
        $fushi_price_2=0;
    }
    echo '副石2：';
    var_dump($guige,$diamond_price,$fushi_price_2);
    $guige = 0;
    $diamond_price = 0;
    if($fushi_num_3){
        $guige = 100*$fushi_3 / $fushi_num_3;
        //获取副石3价格
        $diamond_price = 0;
        foreach ($diamondprice as $k => $v) {
            if($guige > $v['guige_a'] && $guige <= $v['guige_b']){
                $diamond_price = $v['price'];
                break;
            }
        }
        $fushi_price_3 = $diamond_price*$fushi_3;
    }else{
        $fushi_price_3=0;
    }
    echo '副石3：';
    var_dump($guige,$diamond_price,$fushi_price_3);
    //var_dump($fushi_price_1,$fushi_price_2,$fushi_price_3);die;
    //var_dump($fushi_price_1+$fushi_price_2+$fushi_price_3);die;
    //6,(材质金重+向上公差）*金损率* 对应材质单价
    //材质单价:price_type :1=>18K；2=>PT950; price:价格; type = 2
    $caizhi_price = '';
    $shuidian = '';
    if(!empty($caizhi) && !empty($caizhidata)){
        if($caizhi ==1){
            $material_name ='18K';
        }elseif($caizhi ==2){
            $material_name ='PT950';
        }
        //获取对应的材质单价
        foreach ($caizhidata as $v) {
            # code...
            if($material_name == $v['material_name']){

                $caizhi_price = $v['price'];
                $shuidian     = $v['tax_point'];
                break;
            }
        }
    }
    //var_dump($caizhi_price,$shuidian);die;
    //7,金损率 等于1+金损率
    $jinsun_price = $jinsunlv+1;
    //8,计算金损价格
    //var_dump($weight,$jincha_shang,$jinsun_price,$caizhi_price);die;
    //var_dump($weight , $jincha_shang, $jinsun_price, $caizhi_price);
    $tal_jinsun = ($weight + $jincha_shang) * $jinsun_price * $caizhi_price;
    //9,计算定制成本价格
    //var_dump($fushi_price_1 , $fushi_price_2 , $fushi_price_3 , $tal_jinsun , $tal_gongfei);//die;
    echo '副石1：'.$fushi_price_1."<br/>";
    echo '副石2：'.$fushi_price_2."<br/>";
    echo '副石3：'.$fushi_price_3."<br/>";
    echo '金损：'.$tal_jinsun."<br/>";
    echo '工费：'.$tal_gongfei."<br/>";
    echo '税点：'.$shuidian."<br/>";
    $dingzhichengben = ($fushi_price_1 + $fushi_price_2 + $fushi_price_3 + $tal_jinsun + $tal_gongfei) * (1 + $shuidian);
    //var_dump($dingzhichengben,88);die;
    $chengbenjia = round($dingzhichengben,2);
    //var_dump($chengbenjia,99);
    echo '定制成本：'.$chengbenjia;die;
    if($chengbenjia != $dingzhichengben_old){
        $sql = "UPDATE  `list_style_goods` SET `dingzhichengben` = {$chengbenjia} WHERE `goods_id` = {$goods_id}";
        echo $sql;
        echo '<br/>';
        $result = $mysqli->query($sql);
        if($result){
            $str = date('Y-m-d H:i:s',time())." 货号:".$goods_sn." 之前价格: ￥".$dingzhichengben_old." 目前价格: ￥".$chengbenjia."\r\n";
        }else{
            $str = date('Y-m-d H:i:s',time())."货号:".$goods_sn." 之前价格: ￥".$dingzhichengben_old."  更新价格失败！！！\r\n";
        }
        $k = fopen("./log.txt","a+");//此处用a+，读写方式打开，将文件指针指向文件末尾。如果文件不存在则尝试创建之
        fwrite($k,$str);
    }
    //$chenbenjia[] =$where['chengbenjia'];
}
//$res = $model->UpdateSalepolicyChengben($goods_sn,$chenbenjia);
