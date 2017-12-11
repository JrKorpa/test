<?php
//error_reporting(E_ALLL);
$act = isset($_REQUEST['act'])?$_REQUEST['act']:'';

$sql = "SELECT 
goods_sn,style_sn,style_name,dingzhichengben,
cat_type_id,product_type_id,xiangkou,shoucun,caizhi,yanse
FROM `front`.`list_style_goods` WHERE style_sn in ('W9971','W7443','W5731','KLRX010562','KLRW029026','B9446','KLRM028916','A1023','KLRX014759')";


$db1 = mysqli_connect('192.168.10.23', 'root', '1308b8dac1e577', 'front');
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
    //对应关系
    $color = array(1=>1,2=>2,3=>3,4=>4,5=>8,6=>7,7=>6,8=>5);
    foreach($p as $sn){
            $pData = $data[$sn];
            $pData['yanse'] = isset($color[$pData['yanse']])?$color[$pData['yanse']]:'0';
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
            echo '<pre>';
            print_r($pData);die;
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

