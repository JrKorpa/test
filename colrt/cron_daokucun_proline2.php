<?php
/**
脚本要求：
名词解释：

1.每天晚上定时跑脚本，备份库存数据。
*/

	header("Content-type:text/html;charset=utf-8");
	date_default_timezone_set('Asia/Shanghai');
	require_once('MysqlDB.class.php');
	set_time_limit(0);
	ini_set('memory_limit','2000M');

//$new_mysqli=new mysqli('192.168.1.93','cuteman','QW@W#RSS33#E#','warehouse_shipping') or die("数据库连接失败！") ;
$new_conf = [
	'dsn'=>"mysql:host=localhost;dbname=warehouse_shipping",
	'user'=>"root",
	'password'=>"",
		'charset' => 'utf8'
];
	$new_conf1 = [
'dsn'=>"mysql:host=localhost;dbname=app_order",
'user'=>"root",
'password'=>"",
	'charset' => 'utf8'
];
$db = new MysqlDB($new_conf);
$dbR = new MysqlDB($new_conf1);
$goods_status = array(
	'' => '无',
	'100' => '锁定',
	'1'	=> '收货中',
	'2'	=> '库存',
	'3' => '已销售',
	'4' => '盘点中',
	'5' => '调拨中',
	'6' => '损益中',
	'7' => '已报损',
	'8' => '返厂中',
	'9' => '已返厂',
	'10' => '销售中',
	'11' => '退货中',
	'12' => '作废'
);

//入库方式。0=购买 1=委托加工 2=供销 3=借入
$storage_mode_array = array(
		'1'=>'购买',
		'2'=>'加工',
		'3'=>'代销',
		'4'=>'借入',
);
$warehouse_type = array(
			'1'=>'柜面',
			'2'=>'后库',
			'3'=>'待取',
			'4'=>'冻结',
			'5'=>'赠品',
			'6'=>'活动',
			'7'=>'裸钻',
			'8'=>'拆货',
			'9'=>'退货',
			'10'=>'借货',
			'11'=>'其它'
			);

	$dd = date("Ymd");
	$content = "货号,入库方式,款号,模号,名称,张萌类型,款式类型,产品线,系列,状态,主成色,主成色重,主石,主石重,主石颜色,主石净度,切工,抛光,对称,荧光,主石粒数,副石,指圈,金托类型,供应商,公司,仓库,仓库类型,本库库龄,总库龄,成本,证书类型,证书号,珂兰订单号,订单付款状态,品牌,裸钻证书类型,系列及款式归属,副石1,副石1粒数,副石1重,副石2,副石2粒数,副石2重,新产品线,新款式分类,供应商货品条码\n";
	$content = iconv("utf-8","gbk",$content);
	file_put_contents(__DIR__."/kucun/xin_kucunproline" . $dd . ".csv",$content,FILE_APPEND);

$page = 1;
$limit = 1000;

while(1){
	$start = ($page - 1) * $limit;
	echo $start . "\n";
	$sql = "SELECT
	           g.goods_id,
	           g.put_in_type,
	           g.goods_sn,
	           g.goods_name,
	           g.tuo_type,
	           g.cat_type,
	           g.zhushimairuchengben,
	           g.product_type,g.is_on_sale,s.xilie,
            	g.caizhi,
            	g.jinzhong,
            	g.zhushi,
            	g.zuanshidaxiao,
            	g.zhushiyanse,
            	g.zhushijingdu,
            	g.qiegong,
            	g.paoguang,
            	g.duichen,
            	g.yingguang,
            	g.zhengshuhao,
            	g.zhengshuleibie,
            	g.zhushilishu,
            	g.fushizhong,
            	g.shoucun,
            	g.prc_name,
            	g.mo_sn,
            	g.company, 
			    g.pinpai,
				g.luozuanzhengshu,
				g.fushilishu,
				g.fushizhong,
				g.fushi,
				g.shi2,
				g.shi2lishu,
				g.shi2zhong,
            	w.name,
            	w.type,
            	if(g.`change_time` = '0000-00-00 00:00:00', 0, (UNIX_TIMESTAMP( NOW( ) ) - UNIX_TIMESTAMP( g.`change_time` ) ) / ( 24 *3600 ) ) AS thisage ,
            	if(g.`addtime` = '0000-00-00 00:00:00', 0, (UNIX_TIMESTAMP( NOW( ) ) - UNIX_TIMESTAMP( g.`addtime` ) ) / ( 24 *3600 )) AS companyage ,
            	g.yuanshichengbenjia,
            	g.product_type1,
            	g.cat_type1,
                g.supplier_code
	FROM warehouse_shipping.`warehouse_goods` AS g left join front.base_style_info as s ON(s.style_sn = g.goods_sn),
    	warehouse_shipping.warehouse AS w
	WHERE g.warehouse_id = w.id AND g.is_on_sale NOT IN ( 1, 3, 7, 9,11,12,100 ) order by g.goods_id asc limit $start, $limit";
	$ret = $db->getAll($sql);
	if ($ret == null){
		break;
	}
	foreach($ret as $r){
	    //$sql = "select * from jxc_order where goods_id = '".$r['goods_id']."' ";
	    $kelaorderinfo=array('order_sn'=>'','order_amount'=>'');
	    if($r['type'] == 3){//待取状态 去获取珂兰订单号和支付状态
    	    $sql = "select bill_id from warehouse_bill_goods where goods_id = '".$r['goods_id']."' AND bill_type = 'M' order by id desc limit 1 ";
    	    $order_id = $db->getOne($sql);
    	    if($order_id){
    	        $sql = "select order_sn from warehouse_bill where id = $order_id  ";
    	        $kela_order_sn = $db->getOne($sql);
    	        if($kela_order_sn){
    	            $sql = "select order_amount,order_sn from app_order_account as oa,base_order_info as oi where oi.id = oa.order_id and oa.order_id = $order_id  ";
    	            $tmp = $dbR->getRow($sql);
    	            $kelaorderinfo['order_sn'] = $tmp['order_sn'];
    	            $kelaorderinfo['order_amount'] = $tmp['order_amount'] > 0 ? '定金' : '全款';
    	        }
    	    }
	    }
         $sql="select xilie from front.base_style_info where style_sn='{$r['goods_sn']}' limit 0,1";
                    
         $xilie = $db->getOne($sql);
         if (!empty($xilie)) $xilie = trim(trim($xilie), ',');
         if (!empty($xilie)) {
             $sql = "select name from front.app_style_xilie where id in ({$xilie})";
             $xilie_name = $db->getAll($sql);
         } else {
             $xilie_name = '';
         }                 
                    
         $name = '';
         if(!empty($xilie_name))
        {
        foreach ($xilie_name as $kk => $v){
             $name .= $v['name'].' ';
           }
         }
		$pinpai = $r['pinpai'];
		$luozuanzhengshu = $r['luozuanzhengshu'];
		$goods_id = $r['goods_id'];
		$goods_sn = trim($r['goods_sn']);
		$goods_name = $r['goods_name'];
		$storage_mode = $r['put_in_type'];

		$cat_type = $r['cat_type'];
		$product_type = $r['product_type'];

		$xilie2 = @$xilie[$r['xilie']];
		$is_on_sale = @$goods_status[$r['is_on_sale']];
		$caizhi = $r['caizhi'];
		$caizhizhong = $r['jinzhong'];
		$zhushi = $r['zhushi'];
		$zhushizhong = $r['zuanshidaxiao'];
		$zhushiyanse = $r['zhushiyanse'];
		$zhushijingdu = $r['zhushijingdu'];
		$qiegong = $r['qiegong'];
		$paoguang = $r['paoguang'];
		$duichen = $r['duichen'];
		$yingguang = $r['yingguang'];
		$zhushilishu = $r['zhushilishu'];
		$fushizhong = $r['fushizhong'] > 0 ? 'yes' : 'no';
		$shoucun = $r['shoucun'];
		$p_name = $r['company'];
		$wh_name = $r['name'];
		$wh_type = @$warehouse_type[$r['type']];
		//$age = ceil($r['age']);
		$thisage = ceil($r['thisage']);
		$companyage = ceil($r['companyage']);
		$chengbenjia = $r['yuanshichengbenjia'];
		$zhengshuhao = $r['zhengshuhao'];
		$zhengshuleibie = $r['zhengshuleibie'];
		$mo_sn = $r['mo_sn'];
		$zhangmeng_type = getZhangmengType($r);
		$prc_name = $r['prc_name'];
		$tuo = getTuoType($r['tuo_type']);
        $fushi = $r['fushi'];
		$fushilishu = $r['fushilishu'];
		$fushizhong = $r['fushizhong'];
		$shi2 = $r['shi2'];
		$shi2lishu = $r['shi2lishu'];
		$shi2zhong = $r['shi2zhong'];
		$product_type_1 = $r['product_type1'];
		$cat_type_1 = $r['cat_type1'];
        $supplier_code = $r['supplier_code'];
		$str = $goods_id . "," .
			iconv("utf-8","gbk",$storage_mode_array[$storage_mode]) . "," .
			iconv("utf-8","gbk",$goods_sn) . "," .
			iconv("utf-8","gbk",$mo_sn) . "," .
			iconv("utf-8","gbk",$goods_name) . "," .
			iconv("utf-8","gbk",$zhangmeng_type) . "," .
			iconv("utf-8","gbk",$cat_type) . "," .
			iconv("utf-8","gbk",$product_type) . "," .
			iconv("utf-8","gbk",$xilie2) . "," .
			iconv("utf-8","gbk",$is_on_sale) . "," .
			iconv("utf-8","gbk",$caizhi) . "," .

			$caizhizhong . "," .
			iconv("utf-8","gbk",$zhushi) . "," .
			$zhushizhong . "," .
			iconv("utf-8","gbk",$zhushiyanse) . "," .
			iconv("utf-8","gbk",$zhushijingdu) . "," .
			iconv("utf-8","gbk",$qiegong) . "," .
			$paoguang . "," .
			$duichen . "," .
			$yingguang . "," .
			$zhushilishu . "," .
			$fushizhong . "," .
			$shoucun . "," .
			iconv("utf-8","gbk",$tuo) . "," .
			iconv("utf-8","gbk",$prc_name) . "," .
			iconv("utf-8","gbk",$p_name) . "," .
			iconv("utf-8","gbk",$wh_name) . "," .
			iconv("utf-8","gbk",$wh_type) . "," .
			//$age . "," .
			$thisage . "," .
			$companyage . ",".
			$chengbenjia . "," .
			iconv("utf-8","gbk",$zhengshuleibie) . "," .
			iconv("utf-8","gbk",$zhengshuhao) . "," .
			$kelaorderinfo['order_sn'] . "," .
		    iconv("utf-8","gbk",$kelaorderinfo['order_amount']). ",".
			 
			iconv("utf-8","gbk",$pinpai) . "," .
			iconv("utf-8","gbk",$luozuanzhengshu) . "," .
			
			iconv("utf-8","gbk",$name). ",".
			iconv("utf-8","gbk",$fushi) . "," .
			$fushilishu . "," .
			$shi2zhong . "," .
			$shi2 . "," .
			$shi2lishu . "," .
			$shi2zhong . "," .
			iconv("utf-8","gbk",$product_type_1). ",".
			iconv("utf-8","gbk",$cat_type_1). ",".
            iconv("utf-8","gbk",$supplier_code)."\n";
			 

		file_put_contents(__DIR__."/kucun/xin_kucunproline" . $dd . ".csv",$str,FILE_APPEND);
	}
	$page++;
}

function getZhangmengType($r){
$r['tuo_type'] = iconv("utf-8","gbk",$r['tuo_type']) ;
$r['product_type'] = iconv("utf-8","gbk",$r['product_type']) ;
$r['zhushimairuchengben'] = iconv("utf-8","gbk",$r['zhushimairuchengben']) ;
$r['zhushi'] = iconv("utf-8","gbk",$r['zhushi']) ;
$r['cat_type'] = iconv("utf-8","gbk",$r['cat_type']) ;
$r['caizhi'] = iconv("utf-8","gbk",$r['caizhi']) ;
	if ($r['tuo_type'] > 1){
		$t = "戒托";
	}elseif ($r['product_type'] == '珍珠饰品') {
		$t = "珍珠";
	}elseif ($r['product_type'] == '彩宝及翡翠饰品'){
		$t = "彩宝及翡翠";
	}elseif ($r['zhushimairuchengben'] > 0 && $r['zhushi'] == '钻石'){//主石是钻石
		if ($r['cat_type'] == '女戒'){
			$t = "钻石女戒";
		}elseif ($r['cat_type'] == '男戒'){
			$t = "钻石男戒";
		}elseif ($r['cat_type'] == '情侣戒'){
			$t = "钻石对戒";
		}elseif ($r['cat_type'] == '吊坠'){
			$t = "钻石吊坠";
		}elseif ($r['cat_type'] == '手链'){
			$t = "钻石手链";
		}else{
			$t = "其他";
		}
	}else{
		if ($r['caizhi'] == 'PT950'){
			$t = "素铂金";
		}elseif (substr($r['caizhi'],0,3) == '18k'){
			$t = "18k素金饰品";
		}elseif ($r['caizhi'] == '千足金'){
			if ($r['cat_type'] == '金条'){
				$t = "黄金金条";
			}else{
				$t = "千足金饰品";
			}
		}else{
			$t = "其他";
		}
	}
	return $t;
}

function getTuoType($tuo_type) {
    switch ($tuo_type) {
        case '1' : return '成品';
        case '2' : return '空托女戒';
        case '3' : return '空托';
        default  : return  '';
    }
}



