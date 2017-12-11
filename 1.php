<?php
//$b = (3000*3) + (7*4500)+ (4*7000)+ (15*5500);//150000
//$b = (30*30*12*3);//32400 生活
//$b = (3300*4);//13200 房租+网费+水电 600，，租房 省 24000
//5000  //出行
//10000 //电子产品
//11000 //驾照
$b = (150000-32400-13800-5000-10000-11000);
//$b = (800*12);//13000
//$b = (18*4800)+(3600*7)+(3000*3);
//$b = (1.1*25*365);
var_dump($b);die;
//
//
//2010-9 2012-6 wuhan
//2012-7 2014-4 shenzhen
//2014-5 2014-9 beijing
//2014-10 2015-7 beijing
//2015-8 2017-9 shenzhen
//
//2017 25 sz 打工
//2016 24 sz 打工
//2015 23 bj 打工
//2014 22 sz 打工
//2013 21 sz 打工
//
//2012 20 
//2011 19
//2010 18大学
//
//2009 17
//2008 16
//2007 15高中
//
//2006 14
//2005 13
//2004 12初中
//
//2003 11
//2001 10
//2000 9
//1999 8
//1998 7
//1997 6
//1996 5
//1995 4
//
//1994 3
//1993 2
//1992 1


#------------------
#iphone 5s 16g 1535 
#
#iphone se 16g 2199 
#
#iphone 6  32g 2599
#
#iphone 6s plus 32g 3999
#
#iphone 6s plus 128g 4899
#
#iphone 7 plus 128g 6388
#
#iphone 8 plus 64g 6399
#
#iphone x  64 8688
#
#var_dump(((8220-(246.56+95.48+360)-3500)*0.2)-555);die;

#1DVF8vSolQoroElq
//权限
$a = array(15071);
$sql = '';
foreach ($a as $key => $value) {
    $sql .= "INSERT INTO `cuteframe`.`user_menu_permission` (`id`, `user_id`, `permission_id`) VALUES (null, '{$value}', '2544');";
    $sql .= "INSERT INTO `cuteframe`.`user_operation_permission` (`id`, `user_id`, `parent_id`, `permission_id`) VALUES (null, '{$value}', '2544', '2543');";
    //$sql .= "INSERT INTO `cuteframe`.`user_button_permission` (`id`, `user_id`, `parent_id`, `permission_id`) VALUES (null, '{$value}', '2544', '3138');";
    $sql .= "INSERT INTO `cuteframe`.`user_button_permission` (`id`, `user_id`, `parent_id`, `permission_id`) VALUES (null, '{$value}', '2544', '998');";
    $sql .= "INSERT INTO `cuteframe`.`user_button_permission` (`id`, `user_id`, `parent_id`, `permission_id`) VALUES (null, '{$value}', '2544', '999');";
    $sql .= "INSERT INTO `cuteframe`.`user_button_permission` (`id`, `user_id`, `parent_id`, `permission_id`) VALUES (null, '{$value}', '2544', '1000');";
}
echo $sql;die;

#INSERT INTO `shipping`.`ship_freight` (`id`, `order_no`, `freight_no`, `express_id`, `consignee`, `cons_address`, `cons_mobile`, `cons_tel`, `order_mount`, `remark`, `is_print`, `print_date`, `sender`, `department`, `note`, `create_id`, `create_name`, `create_time`, `is_deleted`, `channel_id`, `out_order_id`, `is_tsyd`) VALUES (null, '20171021526340', '617143100949', '18', '瞿正雄', '中国北京市通州区张家湾皇木厂工业区九号院', '13681103922', NULL, '84000.00', '订单发货', '2', '0000-00-00 00:00:00', '郭伟', '物流部', '', '14032', '郭伟', '1508569222', '0', '2', 'CG2017102105', '0');
//收货单顺序
//29 64 93
//30 65 94
//31 66 95 
//34 67 96
//35 68 97
//36 69 98