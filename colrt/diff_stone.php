<?php
/**
 * @Author: anchen
 * @Date:   2015-07-13 16:24:56
 * @Last Modified by:   anchen
 * @Last Modified time: 2015-08-10 20:29:06
 */
$db_new = mysqli_connect('127.0.0.1', 'root', '', 'test');

$sql = "set names utf8;";

$arr = mysqli_query($db_new, $sql);

$sql = "select `style_id` from `rel_style_stone` group by `style_id`";

$arr = mysqli_query($db_new, $sql);
//print_r($arr);die;
while($row=mysqli_fetch_assoc($arr)){
        
        $data[] = $row['style_id'];
}


$db_old = mysqli_connect('127.0.0.1', 'root', '', 'kela_style');

$sql = "set names utf8;";

$arr = mysqli_query($db_old, $sql);

$sql = "select `style_id` from `style_style` group by `style_id`";

$arr = mysqli_query($db_old, $sql);

while($row=mysqli_fetch_assoc($arr)){

        $data_old[] = $row['style_id'];
}


$diff_data = array_diff($data_old,$data);


foreach ($diff_data as $key => $value) {
    # code...
    $sql = "select `style_sn`,`main_stone_cat`,`main_stone_attr`,`sec_stone_cat`,`sec_stone_attr` from `style_style` where `style_id` = {$value}";
    $arr = mysqli_query($db_old, $sql);
    while ($w =mysqli_fetch_assoc($arr)) {
        # code...  
        $datas[] = $w;
    }
}


foreach ($datas as $key => $value) {
    # code...
    if($value['main_stone_attr'] == 'N;' && $value['sec_stone_attr'] == 'N;'){

        unset($datas[$key]);
    }
}


foreach ($datas as $key => $value) {
    # code...
    # 
    if($value['main_stone_attr'] != 'N;'){
        $datas[$key]['main_stone_attr'] = unserialize($value['main_stone_attr']);
    }
    if($value['sec_stone_attr'] != 'N;'){
        $datas[$key]['sec_stone_attr'] = unserialize($value['sec_stone_attr']);
    }
}



echo '<pre>';
print_r($datas);die;

/*foreach ($data as $key => $value) {
    # code...
    # 
    $sql = "select * from `rel_style_stone` where style_id = {$value}";
    $arr = mysqli_query($db_new, $sql);

    while($row=mysqli_fetch_assoc($arr)){
            
            $datas[$key][] = $row;
    }
}


foreach ($datas as $key => $value) {
    if(count($value) < 2){

        unset($datas[$key]);
    }
}


foreach ($datas as $key => $value) {
    # code...
    //var_dump($value[0]['stone_cat'],$value[1]['stone_cat']);die;
    if($value[0]['stone_position'] != '' || $value[1]['stone_position'] != ''){

        unset($datas[$key]);
    }
}

echo '<pre>';
print_r($datas);die;*/

