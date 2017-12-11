<?php
header("Content-type:text/html;charset=utf-8;");
$conn=mysqli_connect('192.168.10.23','root','1308b8dac1e577','front') or die("数据库链接失败");
//$conn=mysqli_connect('192.168.1.59','cuteman','QW@W#RSS33#E#','front') or die("数据库链接失败");
mysqli_query($conn,'set names utf-8');
$sql = "select style_sn from front.app_xiangkou where 1
group by style_sn limit 100;";
$goodsdata  = mysqli_query($conn,$sql);
$goodsarr = combinedata($goodsdata);

//'W9971','W7443','W5731','KLRX010562','KLRW029026','B9446','KLRM028916','A1023','KLRX014759';


foreach($goodsarr as $style_snA)
{
    $t1 = microtime(true);
    $style_sn = $style_snA[0];
    file_get_contents("http://u/kela/xiangk.php?s=".$style_sn);
    file_get_contents("http://u/kela/xiangk2.php?s=".$style_sn);
    // ... 执行代码 ...
    $t2 = microtime(true);
    echo $style_sn.'耗时'.round($t2-$t1,3).'秒';
    echo "<br>";
}

function combinedata($result)
{
	$goods_ids = array();
	if(!$result)
	{
		return $goods_ids;
	}
	
	while($row = mysqli_fetch_row($result))
	{
		array_push($goods_ids,$row);
	}
	return $goods_ids;
}