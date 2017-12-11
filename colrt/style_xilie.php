<?php
/**
 * @Author: anchen
 * @Date:   2015-07-10 22:57:59
 * @Last Modified by:   anchen
 * @Last Modified time: 2015-08-08 16:38:14
 */

header("Content-type:text/html;charset=utf-8;");
set_time_limit(0);
error_reporting(0);
setlocale(LC_ALL, 'zh_CN');
//error_reporting( E_ALL&~E_NOTICE );
$db = mysqli_connect('127.0.0.1', 'root', '', 'test');
$sql = "set names utf8;";
$arr = mysqli_query($db, $sql);

$act = isset($_REQUEST['act']) ? $_REQUEST['act'] : '';

switch ($act){
    case 'upfile':
        upfile();
        break;
    default:
        break;
}


echo <<<HTML
<html
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>
<body>
<form action="?act=upfile" method="post" enctype="multipart/form-data">
    <div>
        <input type="file" name="up_name" value="">
        <input type="submit" name="" value="提交">
    </div>
</form>
</body>
</html>
HTML;
die;

function upfile()
{
    global $db;
    $xilie_arr = array(
        1=>'天鹅湖',
        2=>'天使之吻',
        3=>'怦然心动',
        4=>'UNO',
        5=>'天使之翼',
        6=>'星耀',
        7=>'小黄鸭',
        8=>'天生一对',
        9=>'基本款',
        10=>'O2O爆款',
        11=>'轻奢',
        12=>'PINK EMILY',
        13=>'Attractive',
        14=>'缤纷',
        15=>'挚爱',
        16=>'城堡'
    );
    $file = $_FILES['up_name'];
    $file_name = $file['tmp_name'];
    if(!$file_name){
        echo '文件为空！';
    }

    $file = fopen($file_name,'r');

    $style_data = array();
    while ($data = fgetcsv($file)) {
    
        $style_data[] = $data;
    }

    foreach ($style_data as $key => $value) {
        # code...
        foreach ($value as $k => $v) {
            # code...
           $style_data[$key][$k] = trim(iconv('gbk','utf-8',$v));
        }
    }

    $xilie_arr = array_flip($xilie_arr);
    ///echo '<pre>';
    //print_r($style_data);die;
    $style_info = array();

    foreach ($style_data as $key => $value) {
        # code...
        $style_info[$key]['xilie'] = $xilie_arr[$value[1]];
        $style_info[$key]['style_sn'] = $value[0];
    }

    foreach ($style_info as $key => $value) {
        # code...
        $sql = "select `xilie` from `test`.`base_style_info` where `style_sn` = '".$value['style_sn']."'";
        $arr = mysqli_query($db,$sql);
        $xilie  = '';
        while($w = mysqli_fetch_assoc($arr)){
            $xilie = $w;
        }
        $style_info[$key][3] = $xilie['xilie'];
    }

    //$diff = array();
    //echo '<pre>';
    //print_r($style_info);die;

    foreach ($style_info as $key => $value) {
        # code...
        $xilie1 = array();
        $xilie2 = array();
        if($value['xilie'] != ''){

            $xilie1 = explode(',',$value['xilie']);
        }

        if($value[3] != ''){
            $xilie2 = explode(',',$value[3]);
        }

        if(empty($xilie2)){
            continue;
        }

        foreach ($xilie1 as $k => $v) {
                # code...
            if(in_array($v, $xilie2)){
                unset($style_info[$key]);
            }
        }
    }

    //echo '<pre>';
    //print_r($style_info);die;
    $sql = '';
    //$style_sn_arr = array();
    foreach ($style_info as $key => $value) {
        # code...
        $str = $value['xilie'];
        $style_sn = $value['style_sn'];
        if($value['xilie'] != '' && $value[3] != ''){
            $str = $value['xilie'].",".$value[3];
        }
        $style_sn_arr[] = $style_sn;
        $sql .= "update `base_style_info` set `xilie` = '{$str}' where `style_sn` = '{$style_sn}' limit 1;<br>";
        
        //$arr = mysqli_query($db, $sql);
    }
    echo $sql;die;
    //echo '<pre>';
    //print_r($style_sn_arr);die;
    /*$a = array();
    foreach ($style_sn_arr as $key => $value) {
        # code...
        $sql = "select `xilie` from `base_style_info` where `style_sn` = '".$value."'";
        $arr = mysqli_query($db,$sql);
        while($w = mysqli_fetch_assoc($arr)){
            //print_r($w);die;
            $a[$value] = $w['xilie'];
        }
    }
    //echo 'ok!';die;
    //echo $sql;die;
    echo '<pre>';
    print_r($a);die;*/

}