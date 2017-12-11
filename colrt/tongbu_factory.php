<?php
/**
 * @Author: anchen
 * @Date:   2015-07-10 22:57:59
 * @Last Modified by:   anchen
 * @Last Modified time: 2015-08-16 17:10:57
 */

header("Content-type:text/html;charset=utf-8;");
set_time_limit(0);
error_reporting(0);
setlocale(LC_ALL, 'zh_CN');
//error_reporting( E_ALL&~E_NOTICE );
$db = mysqli_connect('127.0.0.1', 'root', '', 'kela_style');
$sql = "set names utf8;";
$arr = mysqli_query($db, $sql);

$act = isset($_REQUEST['act']) ? $_REQUEST['act'] : '';

switch ($act){
    case 'tongbu':
        tongbu();
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
<form action="?act=tongbu" method="post">
    <div>
        <input type="text" name="style_id" value="">
        <input type="submit" name="" value="提交">
    </div>
</form>
</body>
</html>
HTML;
die;

function tongbu()
{
    global $db;

    $style_id = $_REQUEST['style_id'];

    //print_r($style_sn);die;

    $sql = "select * from `style_factory` where `style_id` in({$style_id})";


    $arr = mysqli_query($db, $sql);

    while ($t = mysqli_fetch_assoc($arr)) {
        # code...
        $data[$t['f_id']] = $t;
    }


$data_old = array();
$sql = "select `style_sn`,`style_id` from `style_style`";
$arr = mysqli_query($db,$sql);
while($w=mysqli_fetch_assoc($arr)){

    $data_old[$w['style_id']] = $w['style_sn'];
}

    //echo '<pre>';
    //var_dump($data);die;
    //print_r($data);die;
$sql = '';
foreach ($data as $key => $value) {
    # code...
    $sql .= "INSERT INTO `rel_style_factory` (`style_id`, `style_sn`, `factory_id`, `factory_sn`, `factory_fee`, `xiangkou`, `is_def`, `is_factory`, `is_cancel`) VALUES (".$value['style_id'].", '".$data_old[$value['style_id']]."', ".$value['factory_id'].", '".$value['factory_sn']."', ".$value['factory_fee'].", ".$value['xiangkou'].", ".$value['is_def'].", ".$value['is_factory'].", 1);<br>";
}
    

    echo $sql;die;

    $sql = "select * from `style_gallery` where `style_id` = ".$data['style_id']."";

    $arr = mysqli_query($db, $sql);

    while ($t = mysqli_fetch_assoc($arr)) {
        # code...
        $datas[] = $t;
    }
    //echo '<pre>';
    //print_r($datas);die;
    $str = '';
    foreach ($datas as $key => $value) {
        # code...
        $str .= "insert into `app_style_gallery` (`style_id`,`style_sn`,`image_place`,`img_sort`,`img_ori`,`thumb_img`,`middle_img`,`big_img`) value(".$value['style_id'].",'".$style_sn."',0,0,'http://stylebimg.kela.cn/".$value['img_ori']."','http://stylebimg.kela.cn/".$value['thumb_img']."','http://stylebimg.kela.cn/".$value['middle_img']."','http://stylebimg.kela.cn/".$value['big_img']."');<br>";
    }

    echo $str;die;
}