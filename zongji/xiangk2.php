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
    //var_dump($list_arr);
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
        $need = checkData($data);
        if($need['k']){
            toBuildA($s_data,$data);
        }
        if($need['pt']){
            toBuildB($s_data,$data);    
        }
    }
}

function checkData($data)
{
    $k = false;
    $pt = false;
    foreach($data as $key => $val){
        if(isset($val[13]) && $val[13]>0){
            $k = true;
        }
        if(isset($val[16]) && $val[16]>0){
            $pt = true;
        }
    }
    $ret['k'] = $k;
    $ret['pt'] = $pt;
    //var_dump($ret);die;
    return $ret;
    var_dump($data);die;
}

//var_dump($gList);die;
function toBuildB()
{
    global $conn;
    global $data;
    //var_dump($sc);
    global $n_list_arr;
    //var_dump($sc,$n_list_arr);
    $haveDone = false;

    $index = getIndex($sc,$n_list_arr);
    $index_bak = $index;
    
    //
    if(empty($data[$n_list_arr[$index]])){
        return false;
    }

    if($data[$n_list_arr[$index]][16] > 0 ){
        return false;
    }
    $x_id = $data[$n_list_arr[$index]][0];
    //var_dump($data[$n_list_arr[$index]],$x_id);
    //var_dump($index,$x_id);

    if(!$haveDone)
    {
        do{
            $index--;
            if($index<0){
                break;
            }
            //var_dump('-',$index);
            if(isset($n_list_arr[$index]) && isset($data[$n_list_arr[$index]]) && isset($data[$n_list_arr[$index]][16])){
                //var_dump($index);
                $haveDone = true;
                $row = $data[$n_list_arr[$index]]; 
                $new = array();
                /*
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
                */
                $new['gpt_weight'] = $row[16];
                $new['gpt_weight_more'] = $row[17];
                $new['gpt_weight_more2'] = $row[18];
                /*
                $new['g18_weight'] = $row[13];
                $new['g18_weight_more'] = $row[14];
                $new['g18_weight_more2'] = $row[15];
                $new['sec_stone_price_other'] = $row[19];
                */
                //var_dump($x_id,'=>',$row[0]);

                $sql = "update app_xiangkou set gpt_weight = ".$row[16].",gpt_weight_more  = ".$row[17].",gpt_weight_more2  = ".$row[18]." where x_id = ".$x_id."; ";
                //mysqli_query($conn,$sql);
                file_put_contents("xk2.log",$row[2]."||".$x_id.'=>'.$row[0]."||".$sql."\r\n",FILE_APPEND);

                $data[$n_list_arr[$index_bak]][16] = $row[16];
                $data[$n_list_arr[$index_bak]][17] = $row[17];
                $data[$n_list_arr[$index_bak]][18] = $row[18];

                

                echo $sql;
                echo "<br>";
                break;
            }
        }while(1);
    }

    $index = getIndex($sc,$n_list_arr);
    if(!$haveDone)
    {
    
        do{
            $index++;
            if($index>6){
                break;
            }
            //var_dump('+',$index);
            if(isset($n_list_arr[$index]) && isset($data[$n_list_arr[$index]]) && isset($data[$n_list_arr[$index]][13])){
                $row = $data[$n_list_arr[$index]]; 
                $new = array();
                $haveDone = true;
                /*
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
                */
                $new['gpt_weight'] = $row[16];
                $new['gpt_weight_more'] = $row[17];
                $new['gpt_weight_more2'] = $row[18];

                $sql = "update app_xiangkou set gpt_weight = ".$row[16].",gpt_weight_more  = ".$row[17].",gpt_weight_more2  = ".$row[18]." where x_id = ".$x_id."; ";
                //mysqli_query($conn,$sql);
                file_put_contents("xk2.log",$row[2]."||".$x_id.'=>'.$row[0]."||".$sql."\r\n",FILE_APPEND);

                $data[$n_list_arr[$index_bak]][16] = $row[16];
                $data[$n_list_arr[$index_bak]][17] = $row[17];
                $data[$n_list_arr[$index_bak]][18] = $row[18];

                echo $sql;
                echo "<br>";
                break;
            }
        }while(1);
    }
    echo "\r\n";
    echo "\r\n";
    echo "\r\n";
    echo "\r\n";}

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
    global $data;
    //var_dump($sc);
    global $n_list_arr;
    //var_dump($sc,$n_list_arr);
    $haveDone = false;

    $index = getIndex($sc,$n_list_arr);
    $index_bak = $index;
    
    //
    if(empty($data[$n_list_arr[$index]])){
        return false;
    }

    if($data[$n_list_arr[$index]][13] > 0 ){
        return false;
    }
    $x_id = $data[$n_list_arr[$index]][0];
    //var_dump($data[$n_list_arr[$index]],$x_id);
    //var_dump($index,$x_id);

    if(!$haveDone)
    {
        do{
            $index--;
            if($index<0){
                break;
            }
            //var_dump('-',$index);
            if(isset($n_list_arr[$index]) && isset($data[$n_list_arr[$index]]) && isset($data[$n_list_arr[$index]][13])){
                //var_dump($index);
                $haveDone = true;
                $row = $data[$n_list_arr[$index]]; 
                $new = array();
                /*
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
                */
                $new['g18_weight'] = $row[13];
                $new['g18_weight_more'] = $row[14];
                $new['g18_weight_more2'] = $row[15];
                /*
                $new['gpt_weight'] = $row[16];
                $new['gpt_weight_more'] = $row[17];
                $new['gpt_weight_more2'] = $row[18];
                $new['sec_stone_price_other'] = $row[19];
                */
                //var_dump($x_id,'=>',$row[0]);

                $sql = "update app_xiangkou set g18_weight = ".$row[13].",g18_weight_more  = ".$row[14].",g18_weight_more2  = ".$row[15]." where x_id = ".$x_id."; ";
                //mysqli_query($conn,$sql);
                file_put_contents("xk2.log",$row[2]."||".$x_id.'=>'.$row[0]."||".$sql."\r\n",FILE_APPEND);

                $data[$n_list_arr[$index_bak]][13] = $row[13];
                $data[$n_list_arr[$index_bak]][14] = $row[14];
                $data[$n_list_arr[$index_bak]][15] = $row[15];

                

                echo $sql;
                echo "<br>";
                break;
            }
        }while(1);
    }

    $index = getIndex($sc,$n_list_arr);
    if(!$haveDone)
    {
    
        do{
            $index++;
            if($index>6){
                break;
            }
            //var_dump('+',$index);
            if(isset($n_list_arr[$index]) && isset($data[$n_list_arr[$index]]) && isset($data[$n_list_arr[$index]][13])){
                $row = $data[$n_list_arr[$index]]; 
                $new = array();
                $haveDone = true;
                /*
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
                */
                $new['g18_weight'] = $row[13];
                $new['g18_weight_more'] = $row[14];
                $new['g18_weight_more2'] = $row[15];
                /*
                $new['gpt_weight'] = $row[16];
                $new['gpt_weight_more'] = $row[17];
                $new['gpt_weight_more2'] = $row[18];
                $new['sec_stone_price_other'] = $row[19];
                */

                $sql = "update app_xiangkou set g18_weight = ".$row[13].",g18_weight_more  = ".$row[14].",g18_weight_more2  = ".$row[15]." where x_id = ".$x_id."; ";
                //mysqli_query($conn,$sql);
                file_put_contents("xk2.log",$row[2]."||".$x_id.'=>'.$row[0]."||".$sql."\r\n",FILE_APPEND);

                $data[$n_list_arr[$index_bak]][13] = $row[13];
                $data[$n_list_arr[$index_bak]][14] = $row[14];
                $data[$n_list_arr[$index_bak]][15] = $row[15];


                echo $sql;
                echo "<br>";
                break;
            }
        }while(1);
    }
    echo "\r\n";
    echo "\r\n";
    echo "\r\n";
    echo "\r\n";
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