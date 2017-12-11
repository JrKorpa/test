<?php

header("Content-type:text/html;charset=utf8;");
set_time_limit(0);
error_reporting(E_ALL);
$oldData = getOld();
$newData = getNew();

$oldStyle = $oldData['base'];
$newStyle = $newData['base'];

$diff1 = array_diff($oldStyle,$newStyle);
$diff2 = array_diff($newStyle,$oldStyle);

$oldStyle_count = count($oldStyle);
$newStyle_count = count($newStyle);


//fac

//fac
$oldStyleF = $oldData['fac'];
$newStyleF = $newData['fac'];

$oldStyleF_style_id = array_keys($oldData['fac']);
$newStyleF_style_id = array_keys($newData['fac']);


$diffF1 = array_diff($oldStyleF_style_id,$newStyleF_style_id);
$diffF1_style =  array();
foreach($diffF1 as $style_id){
	$diffF1_style[] = $oldStyle[$style_id];
}

$diffF2 = array_diff($newStyleF_style_id,$oldStyleF_style_id);
$diffF2_style =  array();
foreach($diffF2 as $style_id){
	$diffF2_style[] = $newStyle[$style_id];
}




echo "统计：老款有款 $oldStyle_count 个,新款有款 $newStyle_count 个 <br/>";

echo "<div style='width:100%'>";
echo "统计：老款确失款 ".implode(',',$diff1).",新款确失款 ".implode(',',$diff2)."<br/>";
echo "</div>";

echo "统计：老款有工厂信息 $oldStyleF_count 个,有工厂信息 $newStyleF_count 个<br/>";

echo "统计：新老款信息问题：<br/>";
echo "<table border=1 cellspacing=1 cellpadding=1 width=100%>";
echo "<tr><td></td><td>老款</td><td>新款</td></tr>";


foreach($oldStyleF as $key => $style){
	if(array_key_exists($key,$newStyleF)){
		$style_sn = $newStyle[$key];
		if($style !== $newStyleF[$key]){
			echo "<tr><td>款号：".$style_sn."</td><td>";
			//echo "<br>款号：".$key;
			echo implode(',',$style);
			echo "</td><td>";
			echo implode(',',$newStyleF[$key]);
			echo "</td></tr>";
		}
	}
}

echo "</table>";


function getNew(){
	//$db1 = mysqli_connect('192.168.1.93', 'cuteman', 'QW@W#RSS33#E#', 'app_order');
	$db1 = mysqli_connect('127.0.0.1', 'root', '', 'test');
	$sql = "set names utf8;";
	$arr = mysqli_query($db1, $sql);
	//style
	$sql = "select style_id,style_sn from `front`.`base_style_info`;";
	$arr = mysqli_query($db1, $sql);
	$data= array();
	while($w=mysqli_fetch_assoc($arr)){
		$data[$w['style_id']] = $w['style_sn'];
	}

	//style_factory
	$sql = "select style_id,factory_sn from `front`.`rel_style_factory`;";
	$arr = mysqli_query($db1, $sql);
	$fac_data= array();
	while($w=mysqli_fetch_assoc($arr)){
		$fac_data[$w['style_id']][] = $w['factory_sn'];
	}
	foreach($fac_data as $k => $v){
                sort($v);
		$fac_data[$k] = $v;
	}

	$ret = array('base'=>$data,'fac'=>$fac_data);
	return $ret;
}



function getOld(){
	//$db1 = mysqli_connect('192.168.1.55', 'style_zyy', 'KELAzhangyuanyuan123', 'kela_style');
	$db1 = mysqli_connect('127.0.0.1', 'root', '', 'kela_style');
	$sql = "set names utf8;";
	$arr = mysqli_query($db1, $sql);

	//style
	$sql = "select style_id,style_sn from `kela_style`.`style_style`;";
	$arr = mysqli_query($db1, $sql);
	$data= array();
	while($w=mysqli_fetch_assoc($arr)){
		$data[$w['style_id']] = $w['style_sn'];
	}


	//style_factory
	$sql = "select style_id,factory_sn from `kela_style`.`style_factory`;";
	$arr = mysqli_query($db1, $sql);
	$fac_data= array();
	while($w=mysqli_fetch_assoc($arr)){
		$fac_data[$w['style_id']][] = $w['factory_sn'];
	}
	foreach($fac_data as $k => $v){
                sort($v);
                $fac_data[$k] = $v;
        }

	$ret = array('base'=>$data,'fac'=>$fac_data);
	return $ret;
}



















