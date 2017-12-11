<?php

header("Content-type:text/html;charset=utf8;");
set_time_limit(0);
error_reporting(0);
//error_reporting( E_ALL&~E_NOTICE );
$db = mysqli_connect('127.0.0.1', 'root', '', 'test');
$sql = "set names utf8;";

function getnewimg(){
	//$db1 = mysqli_connect('203.130.44.199', 'cuteman', '.P$qk5tvzW!,', 'front');
	$db1 = mysqli_connect('127.0.0.1', 'root', '', 'test');
	$sql = "set names utf8;";
	$arr = mysqli_query($db1, $sql);


	$sql = "select * from `test`.`app_style_gallery`";
	$arr = mysqli_query($db1, $sql);
	$fac_data= array();
	while($w=mysqli_fetch_assoc($arr)){
		$fac_data[$w['g_id']] = $w['thumb_img'];
	}

    return $fac_data;
}

function getoldimg(){
	//$db1 = mysqli_connect('192.168.1.55', 'style_zyy', 'KELAzhangyuanyuan123', 'kela_style');
	$db1 = mysqli_connect('127.0.0.1', 'root', '', 'kela_style');
	$sql = "set names utf8;";
	$arr = mysqli_query($db1, $sql);

    $sql = "select * from `kela_style`.`app_style_gallery`";
    $arr = mysqli_query($db1, $sql);
    $fac_data= array();
    while($w=mysqli_fetch_assoc($arr)){
        $fac_data[$w['g_id']] = $w['thumb_img'];
    }
    return $fac_data;
}

$newimgData = getnewimg();
$oldimgData = getoldimg();

//echo '<pre>';
//print_r($newimgData);die;

foreach ($newimgData as $key => $value) {
    # code...
    if($value != $oldimgData[$key]){
        $data[$key]['boss'] = $value;
        $data[$key]['bossimg'] = $oldimgData[$key];
    }
}
echo '<pre>';
print_r($data);die;

$oldStyle_count = count($oldimgData['base']);
$newStyle_count = count($newimgData['base']);

$new_style = array_keys($oldimgData['fac']);
$old_style = array_keys($newimgData['fac']);


$style_sn_arr = array_unique($oldimgData['base'] + $newimgData['base']);
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
  //  echo '<pre>';
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
$newStyle = array();
foreach ($all_style_list as $key => $value) {
    # code...
    //echo '<pre>';
    //print_r($value);die;
    if($value['emptyType'] == 'new'){
        $newStyle[$key] = $value['new']['img'];
    }
}

echo '<pre>';
print_r($newStyle);die;


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
        if(substr($v,35,6) > 201500){
            
            unset($newStyle[$key][$k]);
        }
    }
}
//echo '<pre>';
//print_r($newStyle);die;
$style_array = array();
$style_array = array_filter($newStyle);

foreach ($style_array as $key => $value) {
    # code...
    foreach ($value as $k => $v) {
        # code...
        $v = str_replace("http://style.kela.cn/","",$v);
        //print_r($v);die;
        //$sql = "insert into `kela_style`.`style_gallery` set `image_place` = {$k},`style_id` = {$key},`thumb_img` = '".$v."'";
        $sql = "update `kela_style`.`style_gallery` set `thumb_img` = '{$v}' where `style_id` = {$key} and `image_place` = {$k}";
        echo $sql;die;
        $r = mysqli_query($db, $sql);
    }
}
//echo '<pre>';
//print_r($style_array);die;
$placeExits = array(1,2,3,4,5,6,7,8,100);
echo "<table border=1>";
        echo "<tr>";
        echo "<td width='10%'>";
        echo "</td>";
        echo "<td width='45%'>";
        echo "</td>";
        echo "<td width='45%'>";
        echo "</td>";
         echo "</tr>";
foreach($all_style_list as $key => $style_info){
       /* echo "<tr>";
        echo "<td>";
        echo $style_info['emptyType'];
        echo "</td>";
         echo "</tr>";
         */
    if(in_array($style_info['emptyType'],array('same','all_empty'))){
        continue;
    }elseif($style_info['emptyType'] == 'diff'){
        echo "<tr>";
        echo "<td>";
        echo $style_sn_arr[$key];
        echo "</td>";
        echo "<td>";       
        echo "<table border=1>";
        foreach($placeExits as $val){
            if(array_key_exists($val,$style_info['old']['img'])){
                echo "<tr"; 
                if($style_info['diff'][$val] == 1){
                    echo " style='color:red'";
                }
                echo ">";
                echo "<td>";
                echo $val;
                echo "</td>";
                echo "<td>";
                echo $style_info['old']['img'][$val];
                echo "</td>";
                echo "</tr>";
            }else{
                echo "<tr>";
                echo "<td>";
                echo $val;
                echo "</td>";
                echo "<td>";
                echo "</td>";
                echo "</tr>";            
             }
        }
        echo "</table>";



        echo "</td>";
        echo "<td>";
        echo "<table border=1>";
        foreach($placeExits as $val){
            if(array_key_exists($val,$style_info['new']['img'])){
                echo "<tr"; 
                if($style_info['diff'][$val] == 1){
                    echo " style='color:red'";
                }
                echo ">";
                echo "<td>";
                echo $val;
                echo "</td>";
                echo "<td>";
                echo $style_info['new']['img'][$val];
                echo "</td>";
                echo "</tr>";
            }else{
                echo "<tr>";
                echo "<td>";
                echo $val;
                echo "</td>";
                echo "<td>";
                echo "</td>";
                echo "</tr>";            
             }
        }
        echo "</table>";
        echo "</td>";
        echo "</tr>";
    }elseif($style_info['emptyType'] == 'new'){
        echo "<tr>";
        echo "<td>";
        echo $style_sn_arr[$key];
        echo "</td>";
        echo "<td>";
        echo "</td>";
        echo "<td>";
       
        echo "<table border=1>";
        foreach($style_info['new']['img'] as $ke => $val){
            echo "<tr"; 
                echo " style='color:red'";
            echo ">";            
            echo "<td>";
            echo $ke;
            echo "</td>";
            echo "<td>";
            echo $val;
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</td>";
        echo "</tr>";
    }elseif($style_info['emptyType'] == 'old'){
        echo "<tr>";
        echo "<td>";
        echo $style_sn_arr[$key];
        echo "</td>";
        echo "<td>";

        echo "<table border=1>";
        foreach($style_info['old']['img'] as $val){
           echo "<tr"; 
                echo " style='color:red'";
            echo ">";            
            echo "<td>";
            echo "<td>";
            echo $val['place'];
            echo "</td>";
            echo "<td>";
            echo $val['thumb_img'];
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";

        echo "</td>";
        echo "<td>";
        echo "</td>";
        echo "</tr>";
    }
}
echo "</table>";


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