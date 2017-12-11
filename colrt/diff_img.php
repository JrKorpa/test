<?php
/**
 * @Author: anchen
 * @Date:   2015-07-22 20:10:08
 * @Last Modified by:   anchen
 * @Last Modified time: 2015-08-04 11:42:42
 */
header("Content-type:text/html;charset=utf8;");
set_time_limit(0);
error_reporting(0);
//error_reporting( E_ALL&~E_NOTICE );
$db = mysqli_connect('127.0.0.1', 'root', '', '');
$sql = "set names utf8;";

function getnewimg(){
    //$db1 = mysqli_connect('203.130.44.199', 'cuteman', '.P$qk5tvzW!,', 'front');
    $db1 = mysqli_connect('127.0.0.1', 'root', '', 'test');
    $sql = "set names utf8;";
    $arr = mysqli_query($db1, $sql);
    $sql = "select style_id,style_sn from `test`.`base_style_info`;";
    $arr = mysqli_query($db1, $sql);
    $data= array();
    while($w=mysqli_fetch_assoc($arr)){
        $data[$w['style_id']] = $w['style_sn'];
    }
    $sql = "select * from `test`.`app_style_gallery` where `image_place` > 0";
    $arr = mysqli_query($db1, $sql);
    $fac_data= array();
    while($w=mysqli_fetch_assoc($arr)){
        $fac_data[$w['style_id']][$w['image_place']] = $w['thumb_img'];
        $big_data[$w['style_id'].".".$w['image_place']] = $w;
    }
    $sql = "select style_id,image_place,thumb_img from `test`.`app_style_gallery` where `image_place` = 0";
    //$arr = mysqli_query($db1, $sql);
    $fac_data_x= array();
    while($w=mysqli_fetch_assoc($arr)){
        $fac_data_x[$w['style_id']][] = $w;
    }
    $ret = array('base'=>$data,'fac'=>$fac_data,'big_data'=>$big_data);
    return $ret;
}

function getoldimg(){
    //$db1 = mysqli_connect('192.168.1.55', 'style_zyy', 'KELAzhangyuanyuan123', 'kela_style');
    $db1 = mysqli_connect('127.0.0.1', 'root', '', 'kela_style');
    $sql = "set names utf8;";
    $arr = mysqli_query($db1, $sql);
    //$sql = "select style_id,style_sn from `kela_style`.`style_style`;";
    //$arr = mysqli_query($db1, $sql);
    $data= array();
    //while($w=mysqli_fetch_assoc($arr)){
        //$data[$w['style_id']] = $w['style_sn'];
    //}
    $sql = "select style_id,image_place,thumb_img from `kela_style`.`style_gallery` where `image_place` > 0";
    $arr = mysqli_query($db1, $sql);
    $fac_data= array();
    while($w=mysqli_fetch_assoc($arr)){
        $fac_data[$w['style_id']][$w['image_place']] = $w['thumb_img'];
    }
    $sql = "select style_id,image_place,thumb_img from `kela_style`.`style_gallery` where `image_place` = 0";
    //$arr = mysqli_query($db1, $sql);
    $fac_data_x= array();
    while($w=mysqli_fetch_assoc($arr)){
        $fac_data_x[$w['style_id']][] = $w;
    }
    $ret = array('base'=>$data,'fac'=>$fac_data,'fac_x'=>$fac_data_x);
    return $ret;
}


$newimgData = getnewimg();
$oldimgData = getoldimg();
//$oldStyle_count = count($oldimgData['base']);
//$newStyle_count = count($newimgData['base']);

$new_style = array_keys($oldimgData['fac']);
$old_style = array_keys($newimgData['fac']);

//echo '<pre>';
//print_r($newimgData['big_data']);die;
$big_data = $newimgData['big_data'];
//echo '<pre>';
//print_r($big_data);die;
//$style_sn_arr = array_unique($oldimgData['base'] + $newimgData['base']);
//echo '<pre>';
//print_r($old_style);die;
//echo '<pre>';
//print_r($oldimgData['base']);die;
$all_style = array_unique($old_style + $new_style);
//echo '<pre>';
//print_r($all_style);die;
$all_style_list = array();
foreach($all_style as $key => $style_id){

    if(array_key_exists($style_id,$oldimgData['fac'])){


        $all_style_list[$style_id]['old'] = array('style_id'=>$style_id,'imgempty'=>1,'img'=>$oldimgData['fac'][$style_id]);

    
        
    }else{
        $all_style_list[$style_id]['old'] = array('style_id'=>$style_id,'imgempty'=>0);
    }


    if(array_key_exists($style_id,$newimgData['fac'])){

        $all_style_list[$style_id]['new'] = array('style_id'=>$style_id,'imgempty'=>1,'img'=>$newimgData['fac'][$style_id]);
    }else{
        $all_style_list[$style_id]['new'] = array('style_id'=>$style_id,'imgempty'=>0);
    }
}
//echo '<pre>';
//var_dump($all_style_list);die;


//echo '<pre>';
//print_r($all_style_list);die;

foreach($all_style_list as $key => $style_info){
    //echo '<pre>';
    //var_dump($style_info);die;
    //print_r($style_info);die;
    $all_style_list[$key] = getDiff($style_info);
    //echo '<pre>';
//print_r($all_style_list[$key]);die;
}

//echo '<pre>';
//print_r($all_style_list);die;
$newStyle = array();
foreach ($all_style_list as $key => $value) {
    # code...
    //echo '<pre>';
    //print_r($value);die;
    if($value['emptyType'] == 'new'){
        $newStyle[$key] = $value['new']['img'];
    }
}

//echo '<pre>';
//print_r($newStyle);die;

foreach ($newStyle as $key => $value) {
    # code...
    foreach ($value as $k => $v) {
        if('http://style.kela.cn/images/styles/' != substr($v,0,35)){
            
            unset($newStyle[$key][$k]);
        }
    }
}


//echo '<pre>';
//print_r($newStyle);die;

foreach (array_filter($newStyle) as $key => $value) {
    # code...
    foreach ($value as $k => $v) {
        //var_dump(substr($v,35,6));die;
        if(substr($v,35,6) < 201500){
            
            unset($newStyle[$key][$k]);
        }
    }
}
$style_array = array();
$style_array = array_filter($newStyle);

//echo '<pre>';
//print_r($style_array);die;
$db2 = mysqli_connect('127.0.0.1', 'root', '', 'test');
//echo 111;die;
$tmp = array();
foreach ($style_array as $key => $value) {
    # code...
    //print_r($value);die;
    
    foreach ($value as $k => $v) {
        $clt = $key.".".$k;
        //var_dump($big_data[$clt]);die;
        //print($big_data[$clt]);die;
        //var_dump(isset($big_data[$clt]));die;
        if(isset($big_data[$clt])){
            //print($big_data[$clt]);die;
            $tmp[$clt] = $big_data[$clt];
        };
        //echo '<pre>';
        //var_dump($tmp);die;
        //print($tmp);die;
        //echo $clt;die;
        //var_dump($key,$k);die;
        # code...
        //$v = str_replace("http://style.kela.cn/","",$v);
        //print_r($v);die;
        //$sql = "select style_id,style_sn,image_place,img_sort,img_ori,thumb_img,middle_img,big_img from test.app_style_gallery where `style_id` = ".$key." and `image_place` = ".$k."";
        
        //$arr = mysqli_query($db2, $sql);
        //print_r($arr);die;
        //while($w=mysqli_fetch_assoc($arr)){
            //$tmp[]= $w;
        //}
        //print_r($tmp);die;
        //$sql = "insert into `kela_style`.`style_gallery` set `image_place` = {$k},`style_id` = {$key},`thumb_img` = '".$v."'";
        
    }
}
//echo '<pre>';
//var_dump($tmp);die;
//print_r($tmp);die;
//echo $sql;die;
$sql = '';
foreach ($tmp as $key => $v) {
    $v['img_ori'] = str_replace("http://style.kela.cn/","",$v['img_ori']);
    $v['thumb_img'] = str_replace("http://style.kela.cn/","",$v['thumb_img']);
    $v['middle_img'] = str_replace("http://style.kela.cn/","",$v['middle_img']);
    $v['big_img'] = str_replace("http://style.kela.cn/","",$v['big_img']);
    $sql .= "insert into style_gallery set `style_id` = ".$v['style_id'].",`stone_color` = 0,`gold_color` = 0,`face_work` = 0,`image_place` = ".$v['image_place'].",`img_sort` = ".$v['img_sort'].",`img_ori` = '".$v['img_ori']."',`thumb_img` = '".$v['thumb_img']."',`middle_img` = '".$v['middle_img']."',`big_img` = '".$v['big_img']."';<br>";
    //$r = mysqli_query($db, $sql);
}
echo $sql;die;
if($r){
    echo 'ok';die;
}
echo 'no';die;
//$sql = "insert into front.app_style_gallery(style_id,style_sn,image_place,img_sort,img_ori,thumb_img,middle_img,big_img)";
        //echo $sql;die;
        //$r = mysqli_query($db, $sql);
//echo '<pre>';
//print_r($style_array);die;
function getDiff($style_info){
    if($style_info['old']['imgempty']==0 && $style_info['new']['imgempty']==0){
        $style_info['allemptyType'] = 'all_empty';
        return $style_info;
    }
    if($style_info['old']['imgempty']==0 || $style_info['new']['imgempty']==0){
        if($style_info['new']['imgempty'] == 1){
            $style_info['emptyType'] = 'new';
           
            return $style_info;
        }else{
            $style_info['emptyType'] = 'old';
            return $style_info;
        }
    }
    $old = $style_info['old']['img'];
    $new = $style_info['new']['img'];
    if($old == null){
        $old = array();
    }
    if($new == null){
        $new = array();
    }
//print_r($old);die;
    $placeExits = array(1,2,3,4,5,6,7,8,100);
    
    foreach($placeExits as $val){
        if(!array_key_exists($val,$old) && !array_key_exists($val,$new)){
            $style_info['diff'][$val] = 0;
        }elseif(array_key_exists($val,$old) && array_key_exists($val,$new)){
            $style_info['diff'][$val] = str_replace("http://style.kela.cn/","",$new[$val]) == $old[$val]?0:1;
        }else{
            $style_info['diff'][$val] = 1;
        }
    }
    $style_info['emptyType'] = array_sum($style_info['diff']) > 0 ?'diff': 'same';
    return $style_info;
}