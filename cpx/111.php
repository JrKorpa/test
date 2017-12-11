<?php
header("Content-type:text/html;charset=utf8;");
$style_sn1 = array(
    29903=>'KLRW029903',
29904=>'KLMM029904',
29905=>'KLRW029905',
29906=>'KLMM029906',
29907=>'KLRW029907',
29908=>'KLMM029908',
29909=>'KLRW029909',
29910=>'KLMM029910',
29911=>'KLRW029911',
29912=>'KLMM029912',
29913=>'KLMM029913',
29914=>'KLRW029914',
29915=>'KLRW029915',
29916=>'KLMM029916',
29917=>'KLRW029917',
29918=>'KLMM029918',
29920=>'KLMM029920',
29921=>'KLRW029921',
29922=>'KLMM029922'
    );
$sql = "";
foreach ($style_sn1 as $key => $value) {
    # code...
    $new_style_sn = 'KLL'.substr($value,-7);
    $sql.= "UPDATE `base_style_info` SET `style_sn` = '{$new_style_sn}' WHERE `style_id` = {$key} LIMIT 1;<br/>";
    $sql.= "UPDATE `rel_style_attribute` SET `style_sn` =  '{$new_style_sn}' WHERE `style_id` = {$key};<br/>";
    $sql.= "UPDATE `app_xiangkou` SET `style_sn` = '{$new_style_sn}' WHERE `style_id` = {$key};<br/>";
    $sql.= "UPDATE `rel_style_factory` SET `style_sn` = '{$new_style_sn}' WHERE `style_id` = {$key};<br/>";
    $sql.= "UPDATE `app_style_gallery` SET `style_sn` = '{$new_style_sn}' WHERE `style_id` = {$key};<br/>";
    $sql.= "UPDATE `app_style_fee` SET `style_sn` = '{$new_style_sn}' WHERE `style_id` = {$key};<br/>";
    $sql.= "UPDATE `app_factory_apply` SET `style_sn` = '{$new_style_sn}' WHERE `style_id` = {$key};<br/>";
}

echo $sql;
?>