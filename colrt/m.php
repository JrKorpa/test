<?php
/**
 * @Author: anchen
 * @Date:   2015-08-19 18:25:55
 * @Last Modified by:   anchen
 * @Last Modified time: 2015-08-20 17:07:52
 */

$db1 = mysqli_connect('127.0.0.1', 'root', '', 'test');
$sql = "set names utf8;";
$arr = mysqli_query($db1, $sql);


$list_arr = array(
    'KLPX028616' => 'KLPX029059',
    'KLSX028598' => 'KLSX029060',
    'KLSX028599' => 'KLSX029061',
    'KLSX028600' => 'KLSX029062',
    'KLSX028601' => 'KLSX029063',
    'KLPX028617' => 'KLPX029064',
    'KLPX028618' => 'KLPX029065',
    'KLPW029057' => 'KLPW029066',
    'KLPW029058' => 'KLPW029067',
    'KLPW029059' => 'KLPW029068',
    'KLPW029060' => 'KLPW029069',
    'KLPW029061' => 'KLPW029070',
    'KLPW029062' => 'KLPW029071',
    'KLPW029063' => 'KLPW029072',
    'KLPW029064' => 'KLPW029073',
    'KLPW029065' => 'KLPW029074',
    'KLPW029066' => 'KLPW029075',
    'KLPW029067' => 'KLPW029076',
    'KLPW029068' => 'KLPW029077',
    'KLPW029069' => 'KLPW029078',
    'KLPW029070' => 'KLPW029079',
    'KLPM029071' => 'KLPM029080',
    'KLPM027041' => 'KLPM029081',
    'KLPM029072' => 'KLPM029082',
    'KLPM029073' => 'KLPM029083',
    'KLPM029074' => 'KLPM029084',
    'KLPM029075' => 'KLPM029085',
    'KLPM029076' => 'KLPM029086',
    'KLPM029077' => 'KLPM029087',
    'KLPM029078' => 'KLPM029088',
    'KLPM029079' => 'KLPM029089',
    'KLPM029080' => 'KLPM029090',
    'KLPM029081' => 'KLPM029091',
    'KLNW029045' => 'KLNW029092',
    'KLNW029046' => 'KLNW029093',
    'KLNW029047' => 'KLNW029094',
    'KLNW029048' => 'KLNW029095',
    'KLNW029049' => 'KLNW029096',
    'KLNW029050' => 'KLNW029097',
    'KLNW029051' => 'KLNW029098',
    'KLNW029052' => 'KLNW029099',
    'KLNW029053' => 'KLNW029100',
    'KLNW029054' => 'KLNW029101',
    'KLNW029055' => 'KLNW029102',
    'KLNW029056' => 'KLNW029103',
    'KLNW029058' => 'KLNW029105',
    'KLNW029059' => 'KLNW029106',
    'KLNW029060' => 'KLNW029107',
    'KLNW029061' => 'KLNW029108',
    'KLSW029062' => 'KLSW029109',
    'KLSW029063' => 'KLSW029110',
    'KLSW029064' => 'KLSW029111',
    'KLPW029071' => 'KLPW029112',
    'KLRW029059' => 'KLPW029113',
    'KLRW029060' => 'KLPW029114' 
    );

foreach ($list_arr as $key => $value) {
    # code...
    $sql = "select `id`,`goods_sn` from `warehouse_bill_goods` where `goods_sn` = '{$key}'";
    $arr = mysqli_query($db1, $sql);
    while ($w=mysqli_fetch_assoc($arr)) {
        # code...
        $data[$w['id']] = $w['goods_sn'];
    }
}

echo '<pre>';
print_r($data);die;

//160783 => KLPX029059;
//160738 => KLSX029063;



//[22280] => KLPX028616
//[22274] => KLSX028601

