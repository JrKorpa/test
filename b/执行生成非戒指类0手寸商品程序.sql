1、第一步
（1）修改非戒指类手寸9-10的金重信息的手寸改为0；（会产生重复数据）3328//3514
select `s`.`style_id`,`s`.`style_sn`,`x`.`x_id`,`x`.`finger`,`x`.`stone` from `base_style_info` `s` inner join `app_xiangkou` `x` on `s`.`style_sn` = `x`.`style_sn` where `s`.`style_type` not in(2,10,11) and `x`.`finger` = '9-10';

update `base_style_info` `s`,`app_xiangkou` `a` set `a`.`finger` = '0' where `a`.`finger` = '9-10' and `a`.`style_sn` = `s`.`style_sn` and `s`.`style_type` not in(2,10,11);

select `x`.* from `base_style_info` `s` inner join `app_xiangkou` `x` on `s`.`style_sn` = `x`.`style_sn` where `s`.`style_type` not in(2,10,11) and `x`.`finger` = '0';

delete from `app_xiangkou` where `x_id` in(85644,85577,86443,85450,85428,137590,85447,85448,85449,85429,85645,86334);

select `a`.`style_sn`,`a`.`stone`,`a`.`finger`,count(1) `h` from `base_style_info` `s` inner join `app_xiangkou` `a` where `a`.`finger` = '0' and `a`.`style_sn` = `s`.`style_sn` and `s`.`style_type` not in(2,10,11) group by `a`.`style_sn`,`a`.`stone`,`a`.`finger` having `h` > 1;

delete from `app_xiangkou` where `style_id` = 28046 and `style_sn` <> 'KLNW028046';
delete from `app_xiangkou` where `style_id` = 29399 and `style_sn` <> 'KLNW029399';
delete from `app_xiangkou` where `style_id` = 29159 and `style_sn` <> 'KLPW029159';
delete from `app_xiangkou` where `style_id` = 27057 and `style_sn` <> 'KLSW027057';

（2）删除非戒指类的非0手寸的金重信息；13439//KLNW028046//KLNW029399//KLPW029159
select `s`.`style_id`,`s`.`style_sn`,`x`.`x_id`,`x`.`finger`,`x`.`stone` from `base_style_info` `s` inner join `app_xiangkou` `x` on `s`.`style_sn` = `x`.`style_sn` where `s`.`style_type` not in(2,10,11) and `x`.`finger` <> '0';

delete `a`.* from `app_xiangkou` `a`,`base_style_info` `s` where `a`.`style_sn` = `s`.`style_sn` and `a`.`finger` <> '0' and `s`.`style_type` not in(2,10,11);

（3）删除商品列表非戒指类手寸非0的商品信息；//100028
select `s`.`style_id`,`s`.`style_sn`,`x`.`shoucun`,`x`.`xiangkou` from `base_style_info` `s` inner join `list_style_goods` `x` on `s`.`style_sn` = `x`.`style_sn` where `s`.`style_type` not in(2,10,11) and `x`.`shoucun` <> '0';

delete `l`.* from `base_style_info` `s`,`list_style_goods` `l` where `l`.`style_sn` = `s`.`style_sn` and `l`.`shoucun` <> '0' and `s`.`style_type` not in(2,10,11);
（4）下架可销售商品信息里面非戒指类手寸非0的商品；（因为商品没有手寸信息所以只能脚本执行下架）
2、第二步
（1）获取金重信息为非戒指类0手寸的金重用脚本生成商品手寸只有0的商品；（脚本执行生成商品，生成商品全部是为0手寸的商品）
select `x`.* from `base_style_info` `s` inner join `list_style_goods` `x` on `s`.`style_sn` = `x`.`style_sn` where `s`.`style_type` not in(2,10,11) and `x`.`shoucun` = '0';
（2）将新生成的商品重新生成成本价（脚本执行生成成本价）
select `x`.* from `base_style_info` `s` inner join `list_style_goods` `x` on `s`.`style_sn` = `x`.`style_sn` where `s`.`style_type` not in(2,10,11) and `x`.`shoucun` = '0';

（2）获取商品列表非戒指类0手寸的商品信息推送到可销售商品列表//6455//1207349//6348