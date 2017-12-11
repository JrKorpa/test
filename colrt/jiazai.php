<?php
require './includes/DB.class.php';
$db = new DB();
@$n = _get(n,1);
@$m = _get(m,5);
$fc = ($n - 1) * $m;
$sql = 'select * from `with` limit '.$fc.','.$m.'';
//print_r($sql);die;
$res = $db->get_all($sql);
echo json_encode($res);

function _get($str,$num){
    $val = !empty($_POST[$str]) ? $_POST[$str] : $num;
    return $val;
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content='text/html; charset=utf-8'/>
	<title>加载更多</title>
</head>
<body>
	<?php foreach ($res as $key => $value): ?>
		<span id = 's'><?php echo $value['content']; ?></span><br/>
	<?php endforeach ?>
	<input id='n' type ='hidden' value='<?php echo $n; ?>'>
	<input type='submit' value='加载更多..<?php echo $n; ?>' onclick='jz()'/>
</body>
<script type="text/javascript" src="../public/js/jquery-2.1.1.min.js"></script>
<script type="text/javascript">
	function jz(){
		var c = $('#n').val();
		var t = 1;
		var n = parseInt(c)+parseInt(t);
		$.ajax({
			url:'jiazai.php', 
			type:'post', 
	    	data:{'n':n}, 
			dataType:'json', 
			success:function(msg){
				$.each(msg,function(i,val){
					$("#s").append('<span>'+val.content+'</span><br/>'); 
				})
			} 
		}); 
	}
</script>
</html>