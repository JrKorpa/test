1、第一步
$sql = "insert into `warehouse_goods_age` (`warehouse_id`,`goods_id`) select `id`,`goods_id` from `warehouse_goods` where `is_on_sale` <> 1";

2、第二步
$sql = "update `warehouse_goods_age` as `a`,
(select `g`.`goods_id`,
    `t`.`bill_type`,
    `t`.`endtime` 
from `warehouse_goods` as `g`
inner join (
    select * from (select `bg`.`goods_id`,
    `b`.`bill_type`,
    `b`.`check_time` as `endtime` 
from `warehouse_bill` as `b` 
inner join `warehouse_bill_goods` `bg` on `b`.`id` = `bg`.`bill_id` 
where `b`.`bill_status` = 2 
order by `b`.`check_time` desc) as `r` GROUP BY `r`.`goods_id`
) as `t` on `g`.`goods_id` = `t`.`goods_id` 
where `g`.`is_on_sale` <> 2 
and `t`.`bill_type` in('S','B','E','C','P') 
) as `age` set `a`.`endtime` = `age`.`endtime` 
where `a`.`goods_id` = `age`.`goods_id`;
";

3、第三步
第一次跑一遍
之后每天定时任务跑脚本（ ku_age.php）

#数据表 warehouse_shipping
CREATE TABLE `warehouse_goods_age` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `warehouse_id` int(10) NOT NULL COMMENT '货品ID',
  `goods_id` bigint(30) NOT NULL COMMENT '货号',
  `endtime` datetime DEFAULT '0000-00-00 00:00:00' COMMENT '结束时间',
  `total_age` int(6) NOT NULL DEFAULT '0' COMMENT '总库龄',
  `self_age` int(6) NOT NULL DEFAULT '0' COMMENT '本库库龄',
  `last_onshelf_dt` datetime DEFAULT '0000-00-00 00:00:00' COMMENT '最后上架时间',
  `is_kuanprice` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否按款定价,0不是1是',
  `kuanprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '按款售价',
  `style_kuanprice_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '按款规则',
  PRIMARY KEY (`id`),
  UNIQUE KEY `goods_id` (`goods_id`) USING BTREE,
  KEY `warehouse_id` (`warehouse_id`) USING BTREE,
  KEY `is_kuanprice` (`is_kuanprice`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=262228 DEFAULT CHARSET=utf8;

