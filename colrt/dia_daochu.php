<?php
/**
 * @Author: anchen
 * @Date:   2015-08-22 12:31:37
 * @Last Modified by:   anchen
 * @Last Modified time: 2015-08-24 09:31:47
 */借入   4       
1062    代销  3       
1061    委托加工    2       
1060    购买  1


CASE category
    WHEN "Holiday" THEN "Seasonal"
    WHEN "Profession" THEN "Bi_annual"
    WHEN "Literary" THEN "Random" END AS "Pattern"


$sql = "SELECT `o`.`order_sn` AS  '订单号',
SUBSTRING( `o`.`check_time`, 1, 4 ) AS  '年',
SUBSTRING( `o`.`check_time`, 6, 2 ) AS  '月',
SUBSTRING( `o`.`check_time`, 9, 2 ) AS  '日',
`ad`.`channel_name` AS  '部门',
`source`.`ad_name` AS  '订单来源',
`og`.`goods_sn` AS  '产品款号',
`og`.`goods_id` AS  '货号',
`og`.`goods_count` AS  '数量',
`og`.`cart` AS  '石重', 
`og`.`color` AS  '颜色', 
`og`.`clarity` AS  '净度', 
`og`.`cut` AS  '切工', 
`og`.`goods_price` AS  '商品价格', 
IF(`o`.`is_xianhuo`=1,'现货','期货') AS '订单类型', 
`og`.`zhengshuhao` AS '证书号'
FROM 
`app_order`.`base_order_info` AS `o`,
`cuteframe`.`ecs_ad` AS `source`, 
`cuteframe`.`sales_channels` AS `ad`, 
`app_order`.`app_order_details` AS `og`
WHERE `o`.`customer_source_id` = `source`.`ad_id`
AND `o`.`department_id` = `ad`.`id`
AND `o`.`id` = `og`.`order_id`
AND `o`.`order_status` = 2 
AND `o`.`order_pay_status`>1
AND `og`.`goods_type` =  'lz'
AND `o`.`check_time` >=  '2015-1-01 00:00:00'
AND `o`.`check_time` <=  '2015-8-22 23:59:59'";