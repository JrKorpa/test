<?php
//error_reporting(E_ALLL);
$act = isset($_REQUEST['act'])?$_REQUEST['act']:'';

$sql = "select `x`.goods_sn,`x`.style_sn,`x`.style_name,`x`.dingzhichengben,
`x`.cat_type_id,`x`.product_type_id,`x`.xiangkou,`x`.shoucun,`x`.caizhi,`x`.yanse from `base_style_info` `s` inner join `list_style_goods` `x` on `s`.`style_sn` = `x`.`style_sn` where `s`.`style_type` not in(2,10,11) and `x`.`shoucun` = '0';";


//$db1 = mysqli_connect('192.168.10.23', 'root', '1308b8dac1e577', 'front');
$db1 = mysqli_connect('192.168.0.95', 'cuteman', 'QW@W#RSS33#E#', 'front_test');
$arr = mysqli_query($db1, $sql);
$data= array();
while($w=mysqli_fetch_assoc($arr)){
    $data[$w['goods_sn']] = $w;
}

$data_keys = array_keys($data);

$sql = "SELECT goods_id FROM `front`.`base_salepolicy_goods` WHERE isXianhuo=0;";
$arr2 = mysqli_query($db1, $sql);
$data2= array();
while($w2=mysqli_fetch_assoc($arr2)){
    $data2[] = $w2['goods_id'];
}

$data_diff = array_diff($data_keys,$data2);

if($act == ''){
    echo "Have ".count($data_diff)." ";
    exit;
}

$header = "insert into `front`.`base_salepolicy_goods`
(`goods_id`,`goods_sn`,`goods_name`,`chengbenjia`,
`category`,`product_type`,`xiangkou`,`finger`,`caizhi`,`yanse`,
`isXianhuo`,`is_sale`,`add_time`,`type`,`is_base_style`,`is_valid`,`company`,`warehouse`,`company_id`,`warehouse_id`,
`stone`,`cate_g`,`is_policy`
) values ";


$part = getSelect($data_diff,100);



foreach($part as $p)
{	
	$sql = insertA($p,$data);
	$q_sql = $header.$sql;
	mysqli_query($db1,$q_sql);
}

//error_reporting(E_ALLL);
function insertA($p,$data){
    $pData = array();
    $insertSql = array();
    foreach($p as $sn){
            $pData = $data[$sn];
            $pData['yanse'] = $pData['yanse'];
            $pData['isXianhuo'] = 0;
            $pData['is_sale'] = 1;
            $pData['add_time'] = date("Y-m-d H:i:s",time());
            $pData['type'] = 1;
            $pData['is_base_style'] = 0;
            $pData['is_valid'] = 1;
            $pData['company'] = '';
            $pData['warehouse'] = '';
            $pData['company_id'] = 0;
            $pData['warehouse_id'] = 0;
            $pData['stone'] = $pData['xiangkou'];
            $pData['cate_g'] = 0;
            $pData['is_policy'] = 1;
        $insertSql[] = "('".implode("','",$pData)."')";

    }
return implode(',',$insertSql);
}


//分批导出
    function getSelect($arr,$drop=5){
        
        $d=array();
        $i=0;
        foreach($arr as $k=>$v){
            $a=$i/$drop;
            $b=$i%$drop;
            $d[$a][$b]=$v;
            $i++;
        }
        return $d;
    }

