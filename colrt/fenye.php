<?php
//包含连接MySQL的文件
include "conn.php";

//分页的相关变量
$pagesize = 5; //每页显示条数
//获取地址栏中传递的page参数
if(empty($_GET["page"]))
{
    $page = 1;
    $startrow = 0;
}else
{
    $page = (int)$_GET["page"];
    $startrow = ($page-1)*$pagesize;
}
//构建查询的SQL语句
$sql = "SELECT * FROM 007_news";
//执行SQL语句
$result = mysql_query($sql);
//总记录数和总页数
$records = mysql_num_rows($result); //总记录数
$pages = ceil($records/$pagesize); //总页数

//构建分页的SQL语句
$sql = "SELECT * FROM 007_news ORDER BY orderby ASC,id DESC LIMIT $startrow,$pagesize";
//执行SQL语句
$result = mysql_query($sql);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>新闻管理列表页</title>
<script type="text/javascript">
function confirmDel(id)
{
    //询问是否要删除
    if(window.confirm("你确定要删除吗？"))
    {
        //如果单击确定按钮，则跳转到del.php页面
        location.href = "del.php?id="+id;
    }
}
</script>
<style type="text/css">
.pagelist{
    height:40px;
    line-height:40px;
}
.pagelist a{
    border:1px solid #ccc;
    background-color:#f0f0f0;
    padding:3px 10px;
    margin:0px 3px;
}
.pagelist span{padding:3px 10px;}
</style>
</head>

<body>
<div style="padding:5px;"><input type="button" value="添加新闻" onclick="javascript:location.href='add.php'"></div>
<table width="100%" border="1" bordercolor="#ccc" rules="all" cellpadding="5" align="center">
    <tr bgColor="#e0e0e0">
        <th>编号</th>
        <th>新闻标题</th>
        <th>作者</th>
        <th>来源</th>
        <th>排序</th>
        <th>点击率</th>
        <th>发布日期</th>
        <th>操作选项</th>
    </tr>
    <?php
    while($arr = mysql_fetch_assoc($result)){
    ?>
    <tr align="center">
        <td><?php echo $arr['id']?></td>
        <td align="left"><a target="_blank" href="content.php?id=<?php echo $arr['id']?>"><?php echo $arr['title']?></a></td>
        <td><?php echo $arr['author']?></td>
        <td><?php echo $arr['source']?></td>
        <td><?php echo $arr['orderby']?></td>
        <td><?php echo $arr['hits']?></td>
        <td><?php echo date("Y-m-d H:i",$arr['addate'])?></td>
        <td>
            <a href="edit.php?id=<?php echo $arr['id']?>">修改</a> | 
            <a href="javascript:void(0)" onClick="confirmDel(<?php echo $arr['id']?>)">删除</a>
        </td>
    </tr>
    <?php }?>
    <tr>
        <td colspan="8" align="center" class="pagelist">
            <?php
                $prev = $page-3; //$prev当前页-3
                $next = $page+3;
                if($prev<1){
                        $prev = 1;
                    }
                if($next>$pages){
                        $next=$pages;
                    }
                for($i=$prev;$i<=$next;$i++)
                {
                    //如果是当前页，则不加链接
                    if($i==$page){
                        echo "<span>$i</span>";
                    }else{
                        echo "<a href='manage.php?page=$i'>$i</a>";
                    }
                }    
            ?>
        </td>
    </tr>
</table>
</body>
</html>