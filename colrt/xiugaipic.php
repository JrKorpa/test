<?php
/**
 * @Author: anchen
 * @Date:   2015-07-10 22:57:59
 * @Last Modified by:   anchen
 * @Last Modified time: 2015-08-12 10:39:47
 */

header("Content-type:text/html;charset=utf-8;");
set_time_limit(0);
error_reporting(0);
setlocale(LC_ALL, 'zh_CN');
//error_reporting( E_ALL&~E_NOTICE );
$db = mysqli_connect('127.0.0.1', 'root', '', 'test');
$sql = "set names utf8;";
$arr = mysqli_query($db, $sql);

$sql = "select `g_id`,`img_ori`,`thumb_img`,`middle_img`,`big_img` from `app_style_gallery`";

$arr = mysqli_query($db, $sql);

while ($t = mysqli_fetch_assoc($arr)) {
    # code...
    $data[$t['g_id']] = $t;
}

foreach ($data as $key => $value) {
    # code...
    if($value['img_ori'] != ''){

        $img_ori = str_replace('style.kela.cn','stylebimg.kela.cn',$value['img_ori']);
    }

    if($value['thumb_img'] != ''){

        $thumb_img = str_replace('style.kela.cn','stylebimg.kela.cn',$value['thumb_img']);
    }

    if($value['middle_img'] != ''){

        $middle_img = str_replace('style.kela.cn','stylebimg.kela.cn',$value['middle_img']);
    }

    if($value['big_img'] != ''){

        $big_img = str_replace('style.kela.cn','stylebimg.kela.cn',$value['big_img']);
    }

    $sql = "update `app_style_gallery` set `img_ori` = replace(img_ori,'style.kela.cn','stylebimg.kela.cn'),`thumb_img` = replace(thumb_img,'style.kela.cn','stylebimg.kela.cn'),`middle_img` = replace(middle_img,'style.kela.cn','stylebimg.kela.cn'),`big_img` = replace(big_img,'style.kela.cn','stylebimg.kela.cn')";

    mysqli_query($db,$sql);
}

die;