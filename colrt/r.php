<?php
/**
 * @Author: anchen
 * @Date:   2015-07-27 17:24:11
 * @Last Modified by:   anchen
 * @Last Modified time: 2015-09-24 23:38:17
 */
$a = array(150702645239,150521568795,150702664544,150702664537,150702664545,150702664546,150702664547,150702615992,150702664470,150702664552,150702664551,150702664548,150702664550,150702659067,150702664536,150702635910,150702664535,150702658635,150702664539,150702664540,150702633782,150702631053,150606588592,150409528748,150702664561,150702664562,150702668648,150702663669,150702664565,150702664564,150702664566,150702659846,150702664559,150702616251,1311044248,1311044208,150702613494,150702664961,150702669711,150702665323,150702633766,150702664554,150702664555,150702664556,150414535853,150414535852,150702660238,150411533987,150411533989,150702664946,150702669603,150702654957,150702635871,150411533988,150702669962,150702663483);

$c = array(150521568795,150521568795,150409528748,150702616251,150702654957,150702659067,150702635910,150702659067,150702613494,150702659067,150702663483,150702645239,150702658635,150702664566,150702664565,150702664564,150702664470,150702615992,150702664562,150702664559,150702664561,150702659067,150702664566,150702664565,150702664564,150702664565,150702664566,150702664562,150702664561,150409528748,150606588592,150702664559,150702659846,150702613494,1311044208,1311044248,150702663483,150702664946,150702664556,150702664555,150702664554,150702664961,150702664946,150702660238,150702654957,150411533987,150411533988,150702663483,150702664946,150702663483);

$b = array(309,259,84.86,82.05,86,86,85,327.02,920.98,79,74.57,84,84,19.72,118.28,20.13,87.87,265.92,79,79,229,35.18,269,269,79.8,79,119,139,82,81,81,64,81.29,146,89,89,189,89,89,89,214.43,86,86,85,79,79,88.09,79,79,81.59,89,119,23.33,87.34,109,189);
$arr = array_combine($a,$b);
if(in_array(, haystack))
$sql = '';
foreach ($a as $key => $value) {
    # code...
    foreach ($b as $k => $v) {
        # code...
        # 
        if($key == $k ){
            $sql .= "update `app_order_account` set `order_amount` = {$v},`money_unpaid` = {$v},`goods_amount` = {$v} where `order_id` = {$value};<br/>";
        }
    }
}
echo $sql;die;