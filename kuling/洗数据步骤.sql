货品入库：（未审核之前单据保存时的货品状态（1，11））（审核之后的状态（2））
        1、收货单 L （入库库龄都为1天） 1->2
        2、其他收货单 T（入库库龄都为1天）1->2
        3、销售退货单（重新入库结束时间清空为0）D  11->2
        4、批发退货单（重新入库结束时间清空为0）H  11->2
        5、老系统货号转新系统（写入库龄表，统计库龄）

        写入货号至库龄表，并记录本库库龄和总库库龄为0天
        记录货品添加时间addtime（入库时间）

货品转仓：（未审核之前单据保存时的货品状态（5）（审核之后的状态（2））
        1、调拨单 M 库存 2  5->2
        
        记录最后一次转仓时间change_time（最后一次转仓时间）
        #总库龄 = 当前时间 - addtime（入库时间）（不需要记录，改变状态后，每天洗库龄）
        #本库库龄 = 当前时间 - change_time（最后一次转仓时间）（库龄清1）
        本库库龄 = 重置为1天 记录最后一次转仓时间change_time

货品出库：（未审核之前单据保存时的货品状态（10，6，8））（审核之后的状态（3，7，9））
        1、销售单 S （自动审核warehouseModel.php）已销售 3  10->3
        2、退货返厂单 B 已返厂 9  8->9
        3、损益单 E 已报损 7  6->7
        4、其他出库单 C  已返厂 9  8->9
        5、批发销售单 P 已销售 3  10->3
        
        记录出库时间endtime（出库时间）
        #总库龄 = endtime（出库时间） - addtime（入库时间）
        #本库库龄 = 不变（停止计算）;

收货单、其他收货单、销售退货单、批发销售单、老系统货号转新系统、调拨单、销售单、退货返厂单、损益单、其他出库单、批发销售单

在库里的货品状态：（2，4，5，6，8，10）2,4,5,6,8,10
不再库里的货品状态：（1，3，7，9，11，12）1,3,7,9,11,12

盘点单（无需操作）W
维修退货单（无需操作）O
维修发货单（无需操作）R
维修调拨单（无需操作）WF

货品状态字典
100
作废  12      
退货中 11      
销售中 10      
已返厂 9       
返厂中 8       
已报损 7       
损益中 6       
调拨中 5       
盘点中 4       
已销售 3
库存  2       
收货中 1 

单据类型：array(14) { 
["S"]=> string(9) "销售单" 
["B"]=> string(15) "退货返厂单" 
["M"]=> string(9) "调拨单" 
["E"]=> string(9) "损益单" 
["C"]=> string(15) "其他出库单" 
["W"]=> string(9) "盘点单" 
["T"]=> string(15) "其他收货单" 
["L"]=> string(9) "收货单" 
["D"]=> string(15) "销售退货单" 
["O"]=> string(15) "维修退货单" 
["P"]=> string(15) "批发销售单" 
["R"]=> string(15) "维修发货单" 
["WF"]=> string(15) "维修调拨单" 
["H"]=> string(15) "批发退货单" }


所以出库单据审核后的时间 = 货品出库时间（endtime）

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
（1）跑脚本（将已经不在库存中的货品算出总库龄）；
（2）跑脚本1\2\3\4；
（3）执行定时任务，对于库存状态的库存状态的货品每天加1；

总库龄 ：$sql = "UPDATE `warehouse_goods_age` SET `total_age` = `total_age` + 1 WHERE `goods_id` IN(SELECT `goods_id` FROM `warehouse_goods` WHERE `is_on_sale` IN(2,4,5,6,8,10))";

本库库龄 ：$sql = "UPDATE `warehouse_goods_age` SET `self_age` = `self_age` + 1 WHERE `goods_id` IN(SELECT `goods_id` FROM `warehouse_goods` WHERE `is_on_sale` IN(2,4,5,6,8,10))";

$sql = "insert into `warehouse_goods_age` (`warehouse_id`,`goods_id`) select `id`,`goods_id` from `warehouse_goods`";

$sql = "update `warehouse_goods_age` as `t` set `t`.`total_age` = (1)";


select `g`.`goods_id`,`g`.`addtime`,`a`.`endtime`,`a`.`total_age` from `warehouse_goods` `g`
inner join `warehouse_goods_age` `a` on `g`.`goods_id` = `a`.`goods_id`
where `a`.`endtime` <> ''
and `g`.`is_on_sale` <> 2

$sql = "update `warehouse_goods_age` `a`,`warehouse_goods` `g` set `a`.`total_age` = floor((UNIX_TIMESTAMP(`a`.`endtime`) - UNIX_TIMESTAMP(`g`.`addtime`))/86400) where `a`.`endtime` <> ''";

$sql = "update `warehouse_goods_age` as `a`,`warehouse_goods` as `g` set `a`.`total_age` = floor((UNIX_TIMESTAMP(`a`.`endtime`) - UNIX_TIMESTAMP(`g`.`addtime`))/86400) where `a`.`endtime` <> ''";


select `a`.`goods_id`,`a`.`addtime`,`a`.`change_time`,`b`.`endtime`,`b`.`total_age`,`b`.`self_age` from `warehouse_goods` `a` inner join `warehouse_goods_age` `b` on `a`.`goods_id` = `b`.`goods_id`;

select `a`.`goods_id`,`a`.`addtime`,`a`.`change_time`,`b`.`endtime`,`b`.`total_age`,`b`.`self_age` from `warehouse_goods` `a` inner join `warehouse_goods_age` `b` on `a`.`goods_id` = `b`.`goods_id` where `a`.`goods_id` = 150327517112;

update `warehouse_goods_age` set `endtime` = '0000-00-00 00:00:00',`total_age` = 0,`self_age` = 0;
