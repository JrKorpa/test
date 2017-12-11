<?php
header("Content-type:text/html;charset=utf-8;");
$conn=mysqli_connect('192.168.10.23','root','1308b8dac1e577','front') or die("数据库链接失败");
//$conn=mysqli_connect('192.168.1.59','cuteman','QW@W#RSS33#E#','front') or die("数据库链接失败");
mysqli_query($conn,'set names utf-8');

if(!$_REQUEST['s'])
{
    exit();
}
$style_sn = $_REQUEST['s'];
$where = " AND style_sn = '$style_sn' ";
$sql = "select style_id,style_sn,stone,count(*) from front.app_xiangkou where 1
$where  
group by style_id,style_sn,stone";
$goodsdata  = mysqli_query($conn,$sql);
$goodsarr = combinedata($goodsdata);
//var_dump($goodsarr);
//die;

$list = array();
$list[41] = '6-8';
$list[43] = '9-10';
$list[45] = '11-13';
$list[47] = '14-15';
$list[49] = '16-22';
$list[394] = '23-25';

$sql2 = "select attribute_value from rel_style_attribute where attribute_id = 5 $where ;";
$goodsdata2  = mysqli_query($conn,$sql2);
$goodsarr2 = combinedata($goodsdata2);
//echo $sql2;
//var_dump($goodsarr2);

$allid = array();
foreach($goodsarr2 as $v)
{
	$l = $v[0];
}
if(!empty($l)){
    $list_arr = array_filter(array_unique(explode(',',$l)));
    var_dump($list_arr);
    foreach($list_arr as $vv){
        $n_list_arr[] = $list[$vv];
    }    
}else{
    exit('empty l');
}

//var_dump($n_list_arr);die;

$where = " AND style_sn = '$style_sn' ";
$sql3 = "select * from front.app_xiangkou where 1
$where;";
$goodsdata3  = mysqli_query($conn,$sql3);
$goodsarr3 = combinedata($goodsdata3);

foreach($goodsarr3 as $s_3){
    $gList[$s_3['3']][$s_3['4']] = $s_3; 
}

//var_dump($n_list_arr);


foreach($gList as $xk => $data){
    $indexA = array_keys($n_list_arr);
    foreach($n_list_arr as $sc => $s_data){
        //记录不存在
        if(!isset($data[$s_data])){
            toBuildA($s_data,$data);    
        }
    }
}


//var_dump($gList);die;

function getIndex($a,$b){
    foreach($b as $k => $bb){
        if($bb == $a){
            return $k;
        }
    }
    return 999;
    //var_dump($a,$b);die;
}

function toBuildA($sc,$data)
{
    global $conn;
    //var_dump($sc);
    global $n_list_arr;
    $index = getIndex($sc,$n_list_arr);
    do{
        $index--;
        if($index<0){
            break;
        }
        //var_dump('-',$index);
        if(isset($n_list_arr[$index]) && isset($data[$n_list_arr[$index]]) && isset($data[$n_list_arr[$index]][13])){
            $row = $data[$n_list_arr[$index]]; 
            $new = array();
            $new['style_id'] = $row[1];
            $new['style_sn'] = $row[2];
            $new['stone'] = $row[3];
            $new['finger'] = $sc;
            $new['main_stone_weight'] = $row[5];
            $new['main_stone_num'] = $row[6];
            $new['sec_stone_weight'] = $row[7];
            $new['sec_stone_num'] = $row[8];
            $new['sec_stone_weight3'] = $row[9];
            $new['sec_stone_num3'] = $row[10];            
            $new['sec_stone_weight_other'] = $row[11];
            $new['sec_stone_num_other'] = $row[12];
            $new['g18_weight'] = $row[13];
            $new['g18_weight_more'] = $row[14];
            $new['g18_weight_more2'] = $row[15];
            $new['gpt_weight'] = $row[16];
            $new['gpt_weight_more'] = $row[17];
            $new['gpt_weight_more2'] = $row[18];
            $new['sec_stone_price_other'] = $row[19];

            $sql = "insert into app_xiangkou(`".implode('`,`',array_keys($new))."`) values ('".implode("','",array_values($new))."')";
            mysqli_query($conn,$sql);
            break;
        }
    }while(1);

    $index = getIndex($sc,$n_list_arr);
    do{
        $index++;
        if($index>6){
            break;
        }
        //var_dump('+',$index);
        if(isset($n_list_arr[$index]) && isset($data[$n_list_arr[$index]]) && isset($data[$n_list_arr[$index]][13])){
            $row = $data[$n_list_arr[$index]]; 
            $new = array();
            $new['style_id'] = $row[1];
            $new['style_sn'] = $row[2];
            $new['stone'] = $row[3];
            $new['finger'] = $sc;
            $new['main_stone_weight'] = $row[5];
            $new['main_stone_num'] = $row[6];
            $new['sec_stone_weight'] = $row[7];
            $new['sec_stone_num'] = $row[8];
            $new['sec_stone_weight3'] = $row[9];
            $new['sec_stone_num3'] = $row[10];            
            $new['sec_stone_weight_other'] = $row[11];
            $new['sec_stone_num_other'] = $row[12];
            $new['g18_weight'] = $row[13];
            $new['g18_weight_more'] = $row[14];
            $new['g18_weight_more2'] = $row[15];
            $new['gpt_weight'] = $row[16];
            $new['gpt_weight_more'] = $row[17];
            $new['gpt_weight_more2'] = $row[18];
            $new['sec_stone_price_other'] = $row[19];

            $sql = "insert into app_xiangkou(`".implode('`,`',array_keys($new))."`) values ('".implode("','",array_values($new))."')";
            mysqli_query($conn,$sql);
            break;
        }
    }while(1);
}

function combinedata($result)
{
	$goods_ids = array();
	if(!$result)
	{
		return $goods_ids;
	}
	
	while($row = mysqli_fetch_row($result))
	{
		array_push($goods_ids,$row);
	}
	return $goods_ids;
}