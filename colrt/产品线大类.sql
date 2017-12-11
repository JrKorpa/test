/* 
* @Author: anchen
* @Date:   2015-08-24 14:11:40
* @Last Modified by:   anchen
* @Last Modified time: 2015-08-24 14:11:42
*/
SELECT `a`.`style_sn`,`a`.`style_name`,CASE `a`.`style_sex` WHEN 1 THEN '男' WHEN 2 THEN '女' WHEN 3 THEN '中性' END,`b`.`product_type_name` FROM `base_style_info` AS `a` LEFT JOIN `app_product_type` AS `b` ON `a`.`product_type` = `b`.`product_type_id` WHERE `a`.`product_type` in(2,3,4,5) AND `a`.`check_status` = 3