<?php
$data = array(
    'KLQW029393'
    );
$str = '';
foreach ($data as $key => $value) {
    # code...
    $spar = str_replace('Q','R',$value);
    $str .= "UPDATE `base_style_info` SET `style_sn` = '{$spar}' WHERE `style_sn` = '{$value}' LIMIT 1;<br/>
    UPDATE `rel_style_attribute` SET `style_sn` =  '{$spar}' WHERE `style_sn` = '{$value}';<br/>
    UPDATE `app_xiangkou` SET `style_sn` = '{$spar}' WHERE `style_sn` = '{$value}';<br/>
    UPDATE `rel_style_factory` SET `style_sn` = '{$spar}' WHERE `style_sn` = '{$value}';<br/>
    UPDATE `app_style_gallery` SET `style_sn` = '{$spar}' WHERE `style_sn` = '{$value}';<br/>
    UPDATE `app_style_fee` SET `style_sn` = '{$spar}' WHERE `style_sn` = '{$value}';<br/>
    UPDATE `app_factory_apply` SET `style_sn` = '{$spar}' WHERE `style_sn` = '{$value}';<br/>";
}

echo $str;