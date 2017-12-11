<?php
header("Content-type:text/html;charset=utf8;");
set_time_limit(0);
error_reporting(E_ALL);

$localhost = 'localhost';
$db_user   = 'root';
$db_pass   = '';

//创建对象并打开连接，最后一个参数是选择的数据库名称 
$mysqli = new mysqli($localhost, $db_user ,$db_pass, 'kela_order_part'); 
//检查连接是否成功 
if (mysqli_connect_errno()){ 
    //注意mysqli_connect_error()新特性 
    die('Unable to connect!'). mysqli_connect_error(); 
}

function con($arr)
{
    $data_info = array();
    while($row = $arr->fetch_array(MYSQLI_ASSOC)){

        $data_info[] = $row;
    }

    return $data_info;
}

$sql = "select `order_sn`,
`consignee`,
`mobile`,
`goods_type`,
`make_order`,`total_fee` from `oi`";
$result = $mysqli->query($sql);
$data = array();
$data = con($result);

$info = array();

$a = array(
    'cp'=>'成品',
    'dz'=>'定制',
    'js'=>'素金饰品',
    'lz'=>'裸钻',
    'sp'=>'特殊类型',
    'xg'=>'黄金千足金',
    'xp'=>'新品',
    'zp'=>'成品',
    'cp'=>'赠品'
);

foreach ($data as $key => $value) {
    # code...
    if(!preg_match('/^\d*$/',trim($value['mobile']))){
        $data[$key]['mobile'] = base64_decode($value['mobile']);
    }else{
        $data[$key]['mobile'] = $value['mobile'];
    }

    $data[$key]['goods_type'] = $a[$value['goods_type']];
    $data[$key]['total_fee'] = number_format($value['total_fee'],2);
}


foreach ($data as $key => $value) {
    # code...
    if($value['order_sn'] == ''){
        $data[$key]['order_sn'] = '——';
    }
    if($value['mobile'] == ''){
        $data[$key]['mobile'] = '——';
    }
    if($value['goods_type'] == ''){
        $data[$key]['goods_type'] = '——';
    }
    if($value['consignee'] == ''){
        $data[$key]['consignee'] = '——';
    }
    if($value['make_order'] == ''){
        $data[$key]['make_order'] = '——';
    }
    if($value['total_fee'] === ''){
        $data[$key]['total_fee'] = '——';
    }
}

foreach ($data as $key => $value) {
    # code...
    echo $value['total_fee']."<br/>";
}

die;

$str = '';
foreach ($data as $key => $value) {
    # code...
    $a = iconv('utf-8','gb2312',htmlspecialchars($value['order_sn']));
    $b = $value['consignee'];
    $c = iconv('utf-8','gb2312',htmlspecialchars($value['mobile']));
    //$d = mb_convert_encoding($value['goods_type'],"utf-8","ASCII,JIS,EUC-JP,SJIS,UTF-8");
    $d = iconv('utf-8','gb2312',htmlspecialchars($value['goods_type']));
    if($value['make_order']){
        $e = iconv('utf-8','gbk',htmlspecialchars($value['make_order']));
    }else{
        $e = '';
    }
    $f = iconv('utf-8','gb2312',htmlspecialchars($value['total_fee']));
    $str .= $a.",".$b.",".$c.",".$d.",".$e.",".$f."\n";
}

$filename = date('Ymd His').'.csv'; //设置文件名   
export_csv($filename,$str); //导出 

function export_csv($filename,$data)   
{   
    header("Content-type:text/csv");
    header("Content-Disposition:attachment;filename=".$filename);
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    header('Expires:0');
    header('Pragma:public');
    echo $data;
}