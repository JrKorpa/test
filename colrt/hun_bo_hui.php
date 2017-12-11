<?php
header("Content-type:text/html;charset=utf8;");
set_time_limit(0);
$db1 = mysqli_connect('192.168.1.59', 'cuteman', 'QW@W#RSS33#E#', 'cuteframe');
$db2 = mysqli_connect('192.168.1.59', 'cuteman', 'QW@W#RSS33#E#', 'app_order');
$db3 = mysqli_connect('192.168.1.59', 'cuteman', 'QW@W#RSS33#E#', 'warehouse_shipping');

$db1 = mysqli_connect('192.168.0.95', 'cuteman', 'QW@W#RSS33#E#', 'cuteframe');
$db2 = mysqli_connect('192.168.0.95', 'cuteman', 'QW@W#RSS33#E#', 'app_order');
$db3 = mysqli_connect('192.168.0.95', 'cuteman', 'QW@W#RSS33#E#', 'warehouse_shipping');

$act = isset($_REQUEST['act']) ? $_REQUEST['act'] : '';

switch ($act) {
    case 'upload':
        upload();
        break;
    case 'uploadnew':
        uploadnew();
        break;
    case 'main';
        main();
    default:
        def();
        break;
}
//====================Search Function==================================
function def()
{
    global $db3, $db1, $db2;
//	//@unlink('csv/get_hbh_goods_id_done.csv');
//	//$warehouse = $db3->getAll("select wh_id, wh_sn, wh_name from jxc_warehouse where status = 1 and (type = '6' or p_id = '58')");
//	////var_dump($warehouse);
    $time = date("Y-m-d");
//	//print_r($db3);exit;
//	//$huxnbohui = $db3->getAll("SELECT * FROM `ecs_hunbohui` WHERE `start_time` <= '$time' AND `end_time` >='$time' AND is_delete = 0 ");
//	$hunbohui = $db3->getAll("SELECT * FROM `ecs_hunbohui` WHERE `start_time` <= '$time' AND `end_time` >='$time' AND is_delete = 0 ");
//	
//	foreach($hunbohui as $w){
//		$str .= "<input name='id[]' type='checkbox' value='$w[id]' />({$w['active_start_time']}&nbsp;到&nbsp;&nbsp;{$w['active_end_time']})&nbsp; | &nbsp;{$w['name']}"."<br>";
//	}

    $sql = "SELECT * FROM `base_hunbohui_info` WHERE `start_time` <= '$time' AND `end_time` >='$time' AND is_delete = 0 ";
    $arr = mysqli_query($db1, $sql);
    $strhbh = '';
    while ($w = mysqli_fetch_array($arr)) {
        $wh_id = $w['id'];
        $wh_name = $w['name'];
        $wh_sn = $w['from_ad'];

        $active_start_time = $w['active_start_time'];
        $active_end_time = $w['active_end_time'];
        $strhbh .= "<label><input type='radio' name='id' value='$wh_id'>($active_start_time 到 $active_end_time) | $wh_name</label><br/>";
    }

    /**取仓库**/
    //$warehouse = $db3->getAll("select wh_id, wh_sn, wh_name from jxc_warehouse where status = 1 and type = '6' ");
    $sql = "SELECT `id`,`name`,`code` FROM `warehouse` WHERE `is_delete`=1";
    $arr = mysqli_query($db3, $sql);
    $strwh = '';
    while ($w = mysqli_fetch_array($arr)) {
        $wh_id = $w['id'];
        $wh_name = $w['name'];
        $wh_sn = $w['code'];
        //$strwh .= "<option value='$wh_id'>$wh_sn | $wh_name</option>";
        $strwh .= "<label><input type='radio' name='warehouse_id' id='$wh_id' value='$wh_id'>$wh_sn | $wh_name</label><br/>";
    }

    echo <<<HTML
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<!--<b>系统匹配</b><a href="http://report.kela.cn/hbh_mapping_goods.php" target="_blank">新版婚博会匹配货品</a>-->
<br/>
查询匹配条件：
1,婚博会订单
2,对戒订单
3,布产订单
4,非刻字订单(2014-02-18号更改为刻字订单也匹配)<br/>
<span style="font-size:12px;">(系统匹配到货将绑定到订单里 可根据货号查到对应的订单)</span>
<form action="" method="post">
{$strhbh}
<!--</select>-->
<br/>
匹配到<br/>
$strwh

<input type="hidden" name="act" value="uploadnew">
<input type="submit" value="提交查询">
</form>
<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
</body>
</html>
HTML;
    //main();
    exit;
}

function main()
{
    global $db3;
    @unlink('csv/get_hbh_goods_id_done.csv');
    $warehouse = $db3->getAll("select wh_id, wh_sn, wh_name from jxc_warehouse where status = 1 and (type = '6' or p_id = '58')");
    foreach ($warehouse as $w) {
        $wh_id = $w['wh_id'];
        $wh_name = $w['wh_name'];
        $wh_sn = $w['wh_sn'];
        $str .= "<option value='$wh_id'>$wh_sn | $wh_name</option>";
    }

    echo <<<HTML
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<b>手动上传文件匹配</b>
<br/>
<form action="?act=upload" method="post" enctype="multipart/form-data">
<select name = 'warehouse'>
$str
</select>
上传文件<input type="file" name="file"><a href="csv/get_hbh_goods_id.csv">下载模版</a>
<br/>
<input type="hidden" name="act" value="upload">
<input type="submit" value="上传并查询">
</form>
</body>
</html>
HTML;
    exit;
}

function get_goods_id($goods_sn, $zhuchengse, $shoucun, $warehouse, &$goods_ids)
{
    global $db3;

    if ($shoucun != '') {
        $where .= " and shoucun = '$shoucun' ";
    }
    if ($zhuchengse != '') {
        $where .= " and zhuchengse = '$zhuchengse' ";
    }
    if ($goods_ids != null) {
        $goods_id_string = implode(',', $goods_ids);
        $where .= " and g.goods_id not in ($goods_id_string) ";
    }
    //$sql = "select goods_id from jxc_goods where goods_sn = '$goods_sn' and  warehouse = '$warehouse' and is_on_sale = 1 $where limit 1";
    echo $sql = "select g.goods_id from warehouse_goods as g where g.warehouse_id in ({$warehouse}) and is_on_sale = 1 and g.goods_sn = '$goods_sn' $where limit 1";

    $goods_id = $db3->getOne($sql);

    if ($goods_id != null) {
        $goods_ids[] = $goods_id;
    }
    return $goods_id;
}

function upload()
{
    global $db3, $smarty;
    $warehouse = $_POST['warehouse'];

    if ($_FILES['file']['error'] > 0) {
        notice('请上传附件', 0);
    }
    $file_array = explode(".", $_FILES['file']['name']);
    $file_extension = strtolower(array_pop($file_array));
    //判断格式是否正确
    if ($file_extension != 'csv') {
        notice("上传的附件格式不正确！必须为csv格式！", 0);
    }
    $f = $_FILES['file']['tmp_name'];
    $handle = @fopen($f, "r");
    $goods_ids = array();
    $n = 0;
    if ($handle) {
        while (!feof($handle)) {
            if ($n == 0) {
                $n++;
                continue;
            }
            $n++;
            $buffer = fgets($handle, 4096);
            $a = explode(',', $buffer);
            $order_sn = trim($a[0]);
            $rec_id = trim($a[1]);
            $goods_sn = trim($a[2]);

            $zhuchengse = trim($a[3]);
            $shoucun = trim($a[4]);
            if ($goods_sn != "") {
                $goods_id = get_goods_id($goods_sn, $zhuchengse, $shoucun, $warehouse, $goods_ids);

                $str = "`" . $order_sn . "," . $rec_id . "," . $goods_sn . "," . $zhuchengse . "," . $shoucun . "," . $goods_id . "\n";
                file_put_contents('csv/get_hbh_goods_id_done.csv', $str, FILE_APPEND);
            }
        }

        fclose($handle);
        if ($n <= 3) {
            notice("上传的附件数据不能为空！", 0);
        }
    }
    $rnd = rand();
    echo <<<HTML
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<div>上传完毕！
<a href="csv/get_hbh_goods_id_done.csv?$rnd">下载文件</a><br/><br/>
<a href='hun_bo_hui.php'>点击返回</a></div>

</body>
</html>
HTML;
    exit;

}

function uploadnew()
{
    global $db2;
    global $db3, $db1;
    $warehouse_id = isset($_REQUEST['warehouse_id']) && $_REQUEST['warehouse_id'] ? $_REQUEST['warehouse_id'] : die('参数错误');
    $path = str_replace('\\', "/", __DIR__);


    if (!isset($_REQUEST['id'])) {
        exit('未选择婚博会');
    }
    $id = intval($_REQUEST['id']);

    $sql = "SELECT * FROM `base_hunbohui_info` WHERE id= $id;";
    $arr = mysqli_query($db1, $sql);
    $row = mysqli_fetch_array($arr);


    $active_start_time = $row['active_start_time'];
    $active_end_time = $row['active_end_time'];
    $from_ad = $row['from_ad'];

    $sql = "SELECT id FROM `customer_sources` WHERE source_code='$from_ad';";
    $arr = mysqli_query($db1, $sql);
    $row = mysqli_fetch_array($arr);
    $from_ad_num = $row['id'];


    unlink($path . '/get_hbh_goods_id_done.csv');
    //查询婚博会订单的明细款号goods_sn和订单id、订单号
    //$sql = "SELECT `id` FROM `base_order_info` WHERE `referer`='婚博会' AND `create_time`>='2015-05-23 00:00:00' AND `create_time`<='2015-05-24 23:59:59'";
    $sql = "SELECT `oi`.`order_sn`,`od`.* FROM `base_order_info` as `oi`,`app_order_details` as `od` WHERE `oi`.`id`=`od`.`order_id` AND `oi`.`referer`='婚博会'";

    $sql .= " AND is_xianhuo=0 AND od.goods_type!='lz' ";
    $sql .= " AND `oi`.`create_time`>='$active_start_time 00:00:00' AND `oi`.`create_time`<='$active_end_time 23:59:59'  ";
    //$sql .= " AND `oi`.`customer_source_id` = $from_ad_num ";

//    $sql .= " AND oi.order_sn in ('201512051654053') ";
    //$sql .="Limit 0,1000";

    //echo $sql;
    //die;
    $arr = mysqli_query($db2, $sql);
    $order_info = array();
    while ($row = mysqli_fetch_array($arr)) {
        $order_info[$row['id']]['order_id'] = $row['id'];
        $order_info[$row['id']]['goods_sn'] = $row['goods_sn'];
        $order_info[$row['id']]['order_sn'] = $row['order_sn'];
        $order_info[$row['id']]['goods_id'] = $row['goods_id'];
        $order_info[$row['id']]['kezi'] = $row['kezi'];
        $order_info[$row['id']]['zhiquan'] = $row['zhiquan'];
        $order_info[$row['id']]['caizhi'] = strtoupper($row['caizhi']);
        $order_info[$row['id']]['jinse'] = $row['jinse'];
    }
    $goods_ids = array();
    file_put_contents($path . '/get_hbh_goods_id_done.csv', iconv("UTF-8", "GBK", "订单号,布产号,商品款号,主成色,指圈号,货品ID,进销存信息主成色,订单信息材质,订单信息金色,刻字信息\n"));

    foreach ($order_info as $order_item) {

        $where = "`goods_sn`='" . $order_item['goods_sn'] . "' AND `cat_type`='情侣戒' AND `is_on_sale`=2 AND `warehouse_id`={$warehouse_id} and caizhi like '" . $order_item["caizhi"] . "%'";
        if ($order_item['zhiquan'] != '') {
            $start = $order_item['zhiquan'] - 1;
            $end = $order_item['zhiquan'] + 1;
            $where .= " AND `shoucun` >= $start and `shoucun` <= $end";
        }
//        
//        if($order_item['caizhi']!=''){
//            $where .= " AND `caizhi` = '".$order_item['caizhi']."'";
//        }

        if (count($goods_ids) > 0) {
            $goods_id_string = implode("','", $goods_ids);
            $where .= " AND `goods_id` not in ('$goods_id_string') ";
        }

        $sql = "SELECT `goods_id`,`goods_sn`,`order_goods_id`,`caizhi`,`shoucun` FROM `warehouse_goods` WHERE $where ";
//        echo $sql;
//        echo "\r\n";

        $arr = mysqli_query($db3, $sql);
        $orderGoodsInfo = array();
        while ($row = mysqli_fetch_array($arr)) {
            $orderGoodsInfo[] = $row;
        }

        //var_dump($orderGoodsInfo);

        $real = false;
        foreach ($orderGoodsInfo as $order_goods_item) {
            $goods_id = $order_goods_item['goods_id'];
            $goods_ids[] = $goods_id;
            list($gold, $gold_color) = get_gold_by_zhuchengse($order_goods_item['caizhi']);
            $gold = strtoupper($gold);
            $order_goods_item['caizhi'] = strtoupper($order_goods_item['caizhi']);
            //var_dump($gold, $gold_color);
            //var_dump($order_item);

            if ($gold == 'PT950' && $order_item['caizhi'] == 'PT950') {
                ////var_dump('continue1 --- '.$gold.'--'.$order_item['caizhi']."\r\n");
                $real = true;
            } elseif ($gold == '18K') {
                if (in_array($gold_color, array('分色', '彩金'))) {
                    if (in_array($order_item['jinse'], array('分色', '彩金'))) {
                        ////var_dump('continue2 --- '.$gold_color.'--'.$order_item['jinse']."\r\n");
                        $real = true;
                    }
                } elseif ($gold_color == $order_item['jinse']) {
                    //var_dump('continue3 --- '.$gold_color.'--'.$order_item['jinse']."\r\n");
                    $real = true;
                }
            }

            if ($real) {
                $str = "`" . replacestr($order_item['order_sn']) . ",''," . replacestr($order_item['goods_sn']) . "," . replacestr($order_goods_item['caizhi']) . "," . replacestr($order_goods_item['shoucun']) . ","
                    . replacestr($order_goods_item['goods_id']) . ',' . $order_goods_item['caizhi'] . ',' . replacestr($order_item['caizhi']) . ',' . replacestr($order_item['jinse']) . ",\"" . replacestr($order_item['kezi']) . "\"\n";
                $str = iconv("UTF-8", "GBK", $str);
//                echo $str;
                file_put_contents($path . '/get_hbh_goods_id_done.csv', $str, FILE_APPEND);
                break;
            }

        }
    }
    //file_put_contents($path.'/get_hbh_goods_id_done.csv', $newstr);
    $rnd = rand();
    echo <<<HTML
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<div>上传完毕！
<a href="get_hbh_goods_id_done.csv?$rnd">下载文件</a><br/><br/>
<a href='hun_bo_hui.php'>点击返回</a></div>

</body>
</html>
HTML;
    //exit;
    //取出婚博会订单
    ////var_dump($list);exit;


}

function replacestr($str)
{
    return str_replace(array("\n", "\r\n", "\r", "❤", "♡"), array("", "", "", "桃心", "桃心"), $str);
}

function newgoods($goods_sn, $zhuchengse, $shoucun, $warehouse, &$goods_ids)
{
    global $db3, $db2;
    $where = '';
    if ($shoucun != '') {
        $where .= " and shoucun = '$shoucun' ";
    }
    if ($zhuchengse != '') {
        $where .= " and zhuchengse = '$zhuchengse' ";
    }
    if ($goods_ids != null) {
        $goods_id_string = implode(',', $goods_ids);
        $where .= " and goods_id not in ($goods_id_string) ";
    }
    //$sql = "select goods_id from jxc_goods where goods_sn = '$goods_sn' and  warehouse = '$warehouse' and is_on_sale = 1 $where limit 1";
    $sql = "select goods_id,order_goods_id from jxc_goods where warehouse in (" . implode(",", $warehouse) . ") and is_on_sale = 1 and goods_sn = '$goods_sn' $where limit 1";
    $goods_id = $db2->getRow($sql);
    if ($goods_id) {
        $goods_ids[] = $goods_id['goods_id'];
    }
    return $goods_id;
}

function get_gold_by_zhuchengse($se)
{
    $se = strtoupper($se);
    if ($se == 'PT950') {
        return array(
            $se,
            '白');
    }
    $gold_arr = explode('K', $se);
    $gold = $gold_arr[0] . "k";
    if ($gold_arr[1] != "玫瑰金" && $gold_arr[1] != "彩金") {
        $s_color = explode('金', $gold_arr[1]);
        $gold_color = $s_color[0];
    } else {
        $gold_color = $gold_arr[1];
    }
    return array(
        $gold,
        $gold_color);
}
