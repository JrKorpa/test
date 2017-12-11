<?php
//新旧款式库主石信息对比
header("Content-type:text/html;charset=utf8;");
set_time_limit(0);
error_reporting(E_ALL);
include('stone_config.php');
$oldData = getOld();
$newData = getNew();

//echo '<pre>';
//print_r($newData);
//print_r($oldData);die;

$diff = array();
foreach($oldData as $key=>$val){
	if(isset($newData[$key])){
		$tmp = $val['main_stone_attr'];
		$stone_cat = $val['main_stone_cat'];
		$newMain_stone_attr = $newData[$key]['main_stone_attr'];
		
		$temp = array();
		if(!empty($tmp)){
			foreach($tmp as $v){
				if(is_array($v)){
					foreach($v as $vv){
						$temp[] = $vv;
					}
				}else{
					$temp[] = $v;
				}
			}
            //echo '<pre>';
            //print_r($temp);die;
            //var_dump($key,$temp,$newMain_stone_attr);die;
			$flag = @array_diff($temp,$newMain_stone_attr);
			if(!empty($flag)){
				$diff[$key] = $val;
			}
		}
	}else{
		$diff[$key] = $val;
	}
    
}


$db1 = mysqli_connect('127.0.0.1', 'root', '', 'test');
$sql = "set names utf8;";
$arr = mysqli_query($db1, $sql);

$sql = "select style_id,style_sn from `base_style_info`";
$arr = mysqli_query($db1, $sql);
$t = array();
while($row=mysqli_fetch_assoc($arr)){
    $t[$row['style_sn']] = $row['style_id'];
}

//echo '<pre>';
//print_r($t);die;

if(count($diff) > 0){
    $i = 0;
    foreach ($diff as $k => $v) {
        # code...
        $s = array();
        $s = $v['main_stone_attr'];
        //print_r($v);die;
        $tmp = array();

        $str_attr = '';
        if(!empty($s)){

            if(is_array($s[2])){

                continue;
            }

            $tmp['weight'] = $s[1];
            $tmp['number'] = $s[2];
            $tmp['xiangkou_start'] = $s[3]['min'];
            $tmp['xiangkou_end'] = $s[3]['max'];

            $str_attr = serialize($tmp);
        }
        
        $time = date('Y-m-d H:i:s',time());
//echo $str_attr;die;
        //echo '<pre>';
        //print_r($tmp);die;
        #
        @$sql = "select count(`id`) from `rel_style_stone` where `style_id` = '".$t[$v['style_sn']]."' and `ston_position` = 1";
        
        $arr = mysqli_query($db1, $sql);
        if($arr == false){

            @$sql_s .= "insert into `rel_style_stone` `style_id` = '".$t[$v['style_sn']]."',`ston_position` = 1,`stone_cat` = ".$v['main_stone_cat'].",`stone_attr` = '".$str_attr."',`add_time` = '{$time}'<br>";
        }else{

            @$sql_s .= "update `rel_style_stone` set `stone_cat` = ".$v['main_stone_cat']." ,`stone_attr` = '".$str_attr."',`add_time` = '{$time}' where `style_id` = ".$t[$v['style_sn']]." and `stone_position` = 1<br>";
        }
        //$r = mysqli_query($db1, $sql);
        $i = $i + 1;

    }
    
}
echo $sql_s;
echo $i;die; 

function getNew(){
	//$db_new = mysqli_connect('192.168.1.93', 'cuteman', 'QW@W#RSS33#E#', 'app_order');
	$db_new = mysqli_connect('127.0.0.1', 'root', '', 'test');
	$sql = "set names utf8;";
	$arr = mysqli_query($db_new, $sql);
	
	$sql = "select ss.style_id,si.style_sn,stone_cat as main_stone_cat,ss.stone_attr as main_stone_attr from `test`.`rel_style_stone` as ss,`test`.`base_style_info` as si where ss.style_id=si.style_id and stone_position=1 and stone_cat<>0 and is_new=0;";
	$arr = mysqli_query($db_new, $sql);
	$data= array();
	while($row=mysqli_fetch_assoc($arr)){
		$row['main_stone_attr'] = unserialize($row['main_stone_attr']);
		$data[$row['style_id']] = $row;
	}

	return $data;
}



function getOld(){
	//$db_old = mysqli_connect('192.168.1.55', 'style_zyy', 'KELAzhangyuanyuan123', 'kela_style');
	$db_old = mysqli_connect('127.0.0.1', 'root', '', 'kela_style');
	$sql = "set names utf8;";
	$arr = mysqli_query($db_old, $sql);

	//style
	$sql = "select style_id,style_sn,main_stone_cat,main_stone_attr from `kela_style`.`style_style` where main_stone_cat<>0 and is_new=0;";
	$arr = mysqli_query($db_old, $sql);
	$data= array();
	while($row=mysqli_fetch_assoc($arr)){
		$row['main_stone_attr'] = unserialize($row['main_stone_attr']);
		$data[$row['style_id']] = $row;
	}
	return $data;
}



















