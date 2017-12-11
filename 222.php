<?php
/**
 * @Author: anchen
 * @Date:   2017-07-26 16:27:47
 * @Last Modified by:   anchen
 * @Last Modified time: 2017-09-07 18:19:09
 */
$localhost = '192.168.0.95';
$db_user   = 'cuteman';
$db_pass   = 'QW@W#RSS33#E#';

//创建对象并打开连接，最后一个参数是选择的数据库名称 
$mysqli = new mysqli($localhost, $db_user ,$db_pass, 'test'); 
//检查连接是否成功 
if (mysqli_connect_errno()){ 
    //注意mysqli_connect_error()新特性 
    die('Unable to connect!'). mysqli_connect_error(); 
}
function con($arr)
{
    $data_info = array();
    while($row = $arr->fetch_array(MYSQLI_ASSOC)){

        $data_info[] = $row;
    }

    return $data_info;
}
$sys_self = "zhanting";
$sql = "select * from test.quanxian_data1 where yonghu = '谭笑芳'";//and user_id = 14806
//$sql = "select * from test.quanxian_data";
$result = $mysqli->query($sql);
$data = array();
$data = con($result);
foreach ($data as $key => $value) {
    //$source_id = $toInfo[$value['warehouse']];
    $source_id = $value['warehouse_id'];
    /*if($value['quanxian'] == '收货单-审核'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '806', '1563')";
        echo $sql.";<br/>";
    }
    if($value['quanxian'] == '其他收货单-审核'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '728', '1562')";
        echo $sql.";<br/>";
    }
    if($value['quanxian'] == '其他出库单-审核'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '725', '1557')";
        echo $sql.";<br/>";
    }*/
    if($value['quanxian'] == '调拨单-审核'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '630', '1543')";
        echo $sql.";<br/>";
    }
    /*if($value['quanxian'] == '损益单-审核'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '707', '1548')";
        echo $sql.";<br/>";
    }
    if($value['quanxian'] == '退货返厂单-审核' || $value['quanxian'] == '退货单-审核'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '722', '1556')";
        echo $sql.";<br/>";
    }
    if($value['quanxian'] == '维修调拨-审核'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '2112', '2114')";
        echo $sql.";<br/>";
    }
    if($value['quanxian'] == '维修退货单-审核'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '1838', '1840')";
        echo $sql.";<br/>";
    }
    if($value['quanxian'] == '批发退货单-审核'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '2061', '2068')";
        echo $sql.";<br/>";
    }
    if($value['quanxian'] == '批量销售单-审核'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '2050', '2055')";
        echo $sql.";<br/>";
    }
    if($value['quanxian'] == '维修发货单-审核'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '1815', '1789')";
        echo $sql.";<br/>";
    }
    if($value['quanxian'] == '销售退货单-审核销售退货单'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '2222', '2225')";
        echo $sql.";<br/>";
    }
    if($sys_self == "boss" && $value['quanxian'] == '无订单退货单-审核'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '2854', '2853')";
        echo $sql.";<br/>";
    }*/
    /*$sql = "select * from test.user_warehouse where user_id = '{$value['user_id']}' and house_id = '{$source_id}'";
    $result = $mysqli->query($sql);
    $check = con($result);
    if(empty($check)){
        $sql = "INSERT INTO `cuteframe`.`user_warehouse` (`id`, `user_id`, `house_id`) VALUES (null, '".$value['user_id']."', '".$source_id."')";
        echo $sql.";<br/>";
    }*/
    /*if($value['quanxian'] == '收货单-制单'){
        //$sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '873', '1563')";
        //echo $sql.";<br/>";
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '874', '1563')";
        echo $sql.";<br/>";
    }
    if($sys_self == "zhanting" && $value['quanxian'] == '其他收货单-制单'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3079', '1562')";
        echo $sql.";<br/>";
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3080', '1562')";
        echo $sql.";<br/>";
    }
    if($sys_self == "boss" && $value['quanxian'] == '其他收货单-制单'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3168', '1562')";
        echo $sql.";<br/>";
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3169', '1562')";
        echo $sql.";<br/>";
    }
    if($sys_self == "zhanting" && $value['quanxian'] == '其他出库单-制单'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3081', '1557')";
        echo $sql.";<br/>";
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3082', '1557')";
        echo $sql.";<br/>";
    }
    if($sys_self == "boss" && $value['quanxian'] == '其他出库单-制单'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3170', '1557')";
        echo $sql.";<br/>";
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3171', '1557')";
        echo $sql.";<br/>";
    }*/
    if($sys_self == "zhanting" && $value['quanxian'] == '调拨单-制单'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3083', '1543')";
        echo $sql.";<br/>";
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3084', '1543')";
        echo $sql.";<br/>";
    }
    /*if($sys_self == "boss" && $value['quanxian'] == '调拨单-制单'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3172', '1543')";
        echo $sql.";<br/>";
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3173', '1543')";
        echo $sql.";<br/>";
    }*/
    /*if($sys_self == "zhanting" && $value['quanxian'] == '损益单-制单'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3085', '1548')";
        echo $sql.";<br/>";
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3086', '1548')";
        echo $sql.";<br/>";
    }
    if($sys_self == "boss" && $value['quanxian'] == '损益单-制单'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3174', '1548')";
        echo $sql.";<br/>";
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3175', '1548')";
        echo $sql.";<br/>";
    }
    if($sys_self == "zhanting" && ($value['quanxian'] == '退货返厂单-制单' || $value['quanxian'] == '退货单-制单')){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3087', '1556')";
        echo $sql.";<br/>";
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3088', '1556')";
        echo $sql.";<br/>";
    }
    if($sys_self == "boss" && ($value['quanxian'] == '退货返厂单-制单' || $value['quanxian'] == '退货单-制单')){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3188', '1556')";
        echo $sql.";<br/>";
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3189', '1556')";
        echo $sql.";<br/>";
    }
    if($sys_self == "zhanting" && $value['quanxian'] == '维修调拨-制单'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3089', '2114')";
        echo $sql.";<br/>";
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3090', '2114')";
        echo $sql.";<br/>";
    }
    if($sys_self == "boss" && $value['quanxian'] == '维修调拨-制单'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3176', '2114')";
        echo $sql.";<br/>";
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3177', '2114')";
        echo $sql.";<br/>";
    }
    if($sys_self == "zhanting" && $value['quanxian'] == '维修退货单-制单'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3091', '1840')";
        echo $sql.";<br/>";
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3092', '1840')";
        echo $sql.";<br/>";
    }
    if($sys_self == "boss" && $value['quanxian'] == '维修退货单-制单'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3178', '1840')";
        echo $sql.";<br/>";
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3179', '1840')";
        echo $sql.";<br/>";
    }
    if($sys_self == "zhanting" && $value['quanxian'] == '批发退货单-制单'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3093', '2068')";
        echo $sql.";<br/>";
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3094', '2068')";
        echo $sql.";<br/>";
    }
    if($sys_self == "boss" && $value['quanxian'] == '批发退货单-制单'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3180', '2068')";
        echo $sql.";<br/>";
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3181', '2068')";
        echo $sql.";<br/>";
    }
    if($sys_self == "zhanting" && $value['quanxian'] == '批量销售单-制单'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3095', '2055')";
        echo $sql.";<br/>";
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3096', '2055')";
        echo $sql.";<br/>";
    }
    if($sys_self == "boss" && $value['quanxian'] == '批量销售单-制单'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3182', '2055')";
        echo $sql.";<br/>";
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3183', '2055')";
        echo $sql.";<br/>";
    }
    if($sys_self == "zhanting" && $value['quanxian'] == '维修发货单-制单'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3097', '1789')";
        echo $sql.";<br/>";
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3098', '1789')";
        echo $sql.";<br/>";
    }
    if($sys_self == "boss" && $value['quanxian'] == '维修发货单-制单'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3184', '1789')";
        echo $sql.";<br/>";
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3185', '1789')";
        echo $sql.";<br/>";
    }
    /*if($sys_self == "zhanting" && $value['quanxian'] == '销售退货单-制单'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3099', '2225')";
        echo $sql.";<br/>";
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3100', '2225')";
        echo $sql.";<br/>";
    }
    if($sys_self == "boss" && $value['quanxian'] == '销售退货单-制单'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3099', '2225')";
        echo $sql.";<br/>";
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3100', '2225')";
        echo $sql.";<br/>";
    }*/
    /*if($sys_self == "boss" && $value['quanxian'] == '无订单退货单-制单'){
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3142', '2853')";
        echo $sql.";<br/>";
        $sql = "INSERT INTO `cuteframe`.`user_extend_operation` (`id`, `user_id`, `type`, `source_id`, `permission_id`, `parent_id`) VALUES (null, '".$value['user_id']."', '2', '".$source_id."', '3143', '2853')";
        echo $sql.";<br/>";
    }*/
}
die;