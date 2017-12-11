<?php
error_reporting(0);

$db1 = mysqli_connect('192.168.10.23', 'root', '1308b8dac1e577', 'front');

$name = "xk.data.csv";
//356
$file = fopen($name,"r");

while(! feof($file))
{
    $res[] = fgetcsv($file);
}

fclose($file);

$styleList = getStyleList();
$stylexiangkou = getStyleXaingkouList();
foreach ($res as $value) {
    # code...
    $style_sn=$value[0];
    
    $style_info = $styleList[$style_sn];
    if(in_array($style_info['style_type'], array(2,10,11))){
        $g_style[$value[0]][$value[1]] = $value;
    }
}
//删除0镶口和其他镶口同时存在款式的0镶口信息
foreach ($g_style as $key => $value) {
    # code...
    $stone_y = false;
    $stone_n = false;
    foreach ($value as $k => $v) {
        # code...
        if($k == 0){
            $stone_y = true;
        }
        if($k != 0){
            $stone_n = true;
        }
    }
    if($stone_y == true && $stone_n == true){
        unset($g_style[$key][0]);
    }
}

array_pop($g_style);

foreach($g_style as $key => $value){
    
    foreach ($value as $k => $val) {
        # code...
        $style_info = $styleList[$key];

        if($style_info['style_sex'] == 2){//女/

            $s_list = array('6-8','9-10','11-13','14-15','16-22');
        }elseif($style_info['style_sex'] == 1){//男

            $s_list = array('23-25','9-10','11-13','14-15','16-22');
        }elseif($style_info['style_sex'] == 3){//中性

            $s_list = array('6-8','9-10','11-13','14-15','16-22','23-25');
        }

        foreach($s_list as $p){

            $val[1] = floatval($val[1]);
            $stone=number_format($val[1], 2, '.', '');
            $finger=$p;
            $main_stone_weight=0;
            $main_stone_num=0;
            $sec_stone_weight=number_format($val[3], 2, '.', '');
            $sec_stone_num=$val[4];
            $sec_stone_weight_other=$val[5];
            $sec_stone_num_other=$val[6];
            $sec_stone_weight3=$val[7];
            $sec_stone_num3=$val[8];
            $sec_stone_price_other = $val[9];
            $g18_weight = number_format($val[10], 2, '.', '');
            $g18_weight_more = $val[11];
            $g18_weight_more2 = $val[12];
            $gpt_weight = number_format($val[13], 2, '.', '');
            $gpt_weight_more = $val[14];
            $gpt_weight_more2 = $val[15];        

            $data=array();
            $data['style_id']=$style_info['style_id'];
            $data['style_sn']=$key;
            $data['stone']=$stone;
            $data['finger']=$finger;
            $data['main_stone_weight']=$main_stone_weight;
            $data['main_stone_num']=$main_stone_num;
            $data['sec_stone_weight'] = $sec_stone_weight;
            $data['sec_stone_num'] = $sec_stone_num;
            $data['sec_stone_weight_other'] = $sec_stone_weight_other;
            $data['sec_stone_num_other'] = $sec_stone_num_other;
            $data['sec_stone_weight3'] = $sec_stone_weight3;
            $data['sec_stone_num3'] = $sec_stone_num3;
            $data['g18_weight'] = empty($g18_weight)?'0':$g18_weight;
            $data['g18_weight_more'] = empty($g18_weight_more)?'0':$g18_weight_more;
            $data['g18_weight_more2'] = empty($g18_weight_more2)?'0':$g18_weight_more2;
            $data['gpt_weight'] = empty($gpt_weight)?'0':$gpt_weight;
            $data['gpt_weight_more'] = empty($gpt_weight_more)?'0':$gpt_weight_more;
            $data['gpt_weight_more2'] = empty($gpt_weight_more2)?'0':$gpt_weight_more2;
            $data['sec_stone_price_other'] = empty($sec_stone_price_other)?'0':$sec_stone_price_other;

            if(isset($stylexiangkou[$key][$stone][$finger])){
                $info = $stylexiangkou[$key][$stone][$finger];
                //print_r($info);die;
                $x_id = $info['x_id'];
                $style_ot['main_stone_weight'] = $main_stone_weight;
                $style_ot['main_stone_num'] = $main_stone_num;
                $style_ot['sec_stone_weight'] = $sec_stone_weight;
                $style_ot['sec_stone_num'] = $sec_stone_num;
                $style_ot['sec_stone_weight_other'] = $sec_stone_weight_other;
                $style_ot['sec_stone_num_other'] = $sec_stone_num_other;
                $style_ot['sec_stone_weight3'] = $sec_stone_weight3;
                $style_ot['sec_stone_num3'] = $sec_stone_num3;
                $style_ot['sec_stone_price_other'] = empty($sec_stone_price_other)?'0':$sec_stone_price_other;
                update($style_ot,$x_id);
                if(!empty($data['g18_weight'])){

                    $g18_info = array();
                    $g18_info['g18_weight'] = empty($g18_weight)?'0':$g18_weight;
                    $g18_info['g18_weight_more'] = empty($g18_weight_more)?'0':$g18_weight_more;
                    $g18_info['g18_weight_more2'] = empty($g18_weight_more2)?'0':$g18_weight_more2;
                    //print_r($g18_info);die;
                    update($g18_info,$x_id);
                }

                if(!empty($data['gpt_weight'])){
                    $gpt_info = array();
                    $gpt_info['gpt_weight'] = empty($gpt_weight)?'0':$gpt_weight;
                    $gpt_info['gpt_weight_more'] = empty($gpt_weight_more)?'0':$gpt_weight_more;
                    $gpt_info['gpt_weight_more2'] = empty($gpt_weight_more2)?'0':$gpt_weight_more2;

                    update($gpt_info,$x_id);
                }
            }else{
                insert($data);
            }
        }
    }
}

function insert($data){

    global $db1;
    $keys = array_keys($data);
    $values = array_values($data);
    echo $sql = "insert into front.app_xiangkou (".implode(',',$keys).") values('".implode("','",$values)."');";
    $arr = mysqli_query($db1, $sql);
    //print_r($arr);die;
    echo "<br>";
}

function update($data,$id){

    global $db1;
    if(!$id){
        return false;
    }

    foreach ( $data as $k => $v ){
        $tmp .= '`' . $k . '` = \'' . $v . '\',';
    }
    $str_s = '';
    $str_s = $id."\r\n";
    $set = rtrim($tmp,',');
    echo $sql = "update front.app_xiangkou set {$set} where `x_id` = {$id};";
    $arr = mysqli_query($db1, $sql);
    echo "<br>";
    $k = fopen("./log_jz.txt","a+");
    fwrite($k,$str_s);
}

function getStyleList(){

    global $db1;
    $sql = "select * from front.base_style_info;";
	
    $arr = mysqli_query($db1, $sql);
    $styleList = array();
    while($style=mysqli_fetch_assoc($arr)){
        $styleList[$style['style_sn']] = $style;
    }
    return $styleList;
}

function getStyleXaingkouList(){

    global $db1;
    $sql = "select * from front.app_xiangkou;";
    $arr = mysqli_query($db1, $sql);
    $stylexiangkou = array();
    while($w=mysqli_fetch_assoc($arr)){
        $stylexiangkou[$w['style_sn']][$w['stone']][$w['finger']] = $w;
    }
    return $stylexiangkou;
}
        