<?php
header("Content-type:text/html;charset=utf8;");
$style_sn = array(
    'KLRW029903',
'KLMM029904',
'KLRW029905',
'KLMM029906',
'KLRW029907',
'KLMM029908',
'KLRW029909',
'KLMM029910',
'KLRW029911',
'KLMM029912',
'KLMM029913',
'KLRW029914',
'KLRW029915',
'KLMM029916',
'KLRW029917',
'KLMM029918',
'KLMM029920',
'KLRW029921',
'KLMM029922'
    );
$sql = "";
foreach ($style_sn as $key => $value) {
    # code...
    $sql .= "update `front`.`base_style_info` set `style_type` = 11 where `style_sn` = '{$value}';<br/>";
    $sql .= "update `front`.`rel_style_attribute` set `cat_type_id` = 11 where `style_sn` = '{$value}';<br/>";
}

echo $sql;
?>