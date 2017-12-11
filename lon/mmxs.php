<?php
header("Content-type: text/html;charset=utf-8");
set_time_limit(0);
error_reporting(1);

$localhost = '192.168.0.91';
$db_user   = 'root';
$db_pass   = '123456';

//创建对象并打开连接，最后一个参数是选择的数据库名称 
$mysqli = new mysqli($localhost, $db_user ,$db_pass, 'front');
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

$name = "11.csv";

$file = fopen($name,"r");

$res = array();

while(! feof($file))
{
    $res[] = fgetcsv($file);
}

fclose($file);

//echo '<pre>';
//print_r($res);die;
$data = array();

$a = array(
    'cp'=>'成品',
    'dz'=>'定制',
    'js'=>'素金饰品',
    'lz'=>'裸钻',
    'sp'=>'特殊类型',
    'xg'=>'黄金千足金',
    'xp'=>'新品',
    'zp'=>'赠品',
    'cp'=>'成品',
    'zengpin' =>'赠品',
    'chengpin' => '成品',
    'luozuan' => '裸钻',
    'tuangou'=>'团购' 
);

foreach ($res as $key => $value) {
    # code...
    $str = $value[0];
    $res[$key] = explode(",", $str);
    $res[$key][0] = str_replace('"','',iconv("GBK", "UTF-8", $res[$key][0]));
    $res[$key][1] = str_replace('"','',iconv("GBK", "UTF-8", $res[$key][1]));
    $res[$key][2] = base64_decode(str_replace('"','',$res[$key][2]));
    $res[$key][3] = $a[str_replace('"','',$res[$key][3])];
    $res[$key][4] = str_replace('"','',iconv("GBK", "UTF-8", $res[$key][4]));
    $res[$key][5] = str_replace('"','',iconv("GBK", "UTF-8", $res[$key][5]));
    $res[$key][6] = str_replace('"','',iconv("GBK", "UTF-8", $res[$key][6]));
}

array_pop($res);
//echo '<pre>';
//print_r($res);
$mxxx =array();

//echo '<pre>';
//print_r($mxxx);die;

foreach ($res as $key => $value) {
    # code...
    $a = iconv('utf-8','gb2312',htmlspecialchars($value[0]));
    $b = iconv('utf-8','gb2312',htmlspecialchars($value[1]));
    $c = iconv('utf-8','gb2312',htmlspecialchars($value[2]));
    $d = iconv('utf-8','gb2312',htmlspecialchars($value[3]));
    $d = iconv('utf-8','gb2312',htmlspecialchars($value[3]));
    $e = iconv('utf-8','gb2312',htmlspecialchars($value[4]));
    $e = iconv('utf-8','gb2312',htmlspecialchars($value[5]));
    $f = iconv('utf-8','gb2312',sprintf("%.2f", $value[6]));
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