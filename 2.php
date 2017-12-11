<?php
/**
 * @Author: anchen
 * @Date:   2017-06-09 10:29:51
 * @Last Modified by:   anchen
 * @Last Modified time: 2017-07-14 19:22:43
 */
//权限
$a = array(15419,
15374,
15181,
15425,
14967,
14964,
15854,
15497,
15188,
15075,
15046,
15084,
15234,
13403,
15418,
14979,
14402,
15099,
15424,
15785,
15074,
15155,
15527,
15863,
15423,
15480,
15857,
15065,
15258,
15420,
13490,
15864,
15855,
15539,
15422,
14950,
15862,
15165,
15860,
15815,
14945,
15056,
15429,
15670,
13758,
14486,
15150,
15825,
15702,
15259,
15100,
15125,
15658,
15107,
15128,
15858,
15778,
15856,
14914,
15731,
15090,
15326,
15025,
14978,
14956,
14898,
15113,
15086,
15659,
15849,
15859,
14161);
foreach ($a as $key => $value) {
    $sql = "INSERT INTO `cuteframe`.`user_menu_permission` (`id`, `user_id`, `permission_id`) VALUES (null, '{$value}', '2533');";
    echo $sql;
}
foreach ($a as $key => $value) {
    $sql = "INSERT INTO `cuteframe`.`user_menu_permission` (`id`, `user_id`, `permission_id`) VALUES (null, '{$value}', '3023');";
    echo $sql;
}
foreach ($a as $key => $value) {
    $sql = "INSERT INTO `cuteframe`.`user_menu_permission` (`id`, `user_id`, `permission_id`) VALUES (null, '{$value}', '3015');";
    echo $sql;
}
foreach ($a as $key => $value) {
   $sql = "INSERT INTO `cuteframe`.`user_operation_permission` (`id`, `user_id`, `parent_id`, `permission_id`) VALUES (null, '{$value}', '2533', '2532');";
   echo $sql;
}
foreach ($a as $key => $value) {
    $sql = "INSERT INTO `cuteframe`.`user_operation_permission` (`id`, `user_id`, `parent_id`, `permission_id`) VALUES (null, '{$value}', '3023', '3017');";
    echo $sql;
}
foreach ($a as $key => $value) {
    $sql = "INSERT INTO `cuteframe`.`user_operation_permission` (`id`, `user_id`, `parent_id`, `permission_id`) VALUES (null, '{$value}', '3023', '3018');";
    echo $sql;
}
foreach ($a as $key => $value) {
    $sql = "INSERT INTO `cuteframe`.`user_operation_permission` (`id`, `user_id`, `parent_id`, `permission_id`) VALUES (null, '{$value}', '3023', '3019');";
    echo $sql;
}
foreach ($a as $key => $value) {
    $sql = "INSERT INTO `cuteframe`.`user_operation_permission` (`id`, `user_id`, `parent_id`, `permission_id`) VALUES (null, '{$value}', '3023', '3020');";
    echo $sql;
}
foreach ($a as $key => $value) {
    $sql = "INSERT INTO `cuteframe`.`user_operation_permission` (`id`, `user_id`, `parent_id`, `permission_id`) VALUES (null, '{$value}', '3023', '3021');";
    echo $sql;
}
foreach ($a as $key => $value) {
    $sql = "INSERT INTO `cuteframe`.`user_operation_permission` (`id`, `user_id`, `parent_id`, `permission_id`) VALUES (null, '{$value}', '3023', '3022');";
    echo $sql;
}
foreach ($a as $key => $value) {
    $sql = "INSERT INTO `cuteframe`.`user_operation_permission` (`id`, `user_id`, `parent_id`, `permission_id`) VALUES (null, '{$value}', '3015', '3013');";
    echo $sql;
}
foreach ($a as $key => $value) {
    $sql = "INSERT INTO `cuteframe`.`user_operation_permission` (`id`, `user_id`, `parent_id`, `permission_id`) VALUES (null, '{$value}', '3015', '3014');";
    echo $sql;
}
foreach ($a as $key => $value) {
    $sql = "INSERT INTO `cuteframe`.`user_button_permission` (`id`, `user_id`, `parent_id`, `permission_id`) VALUES (null, '{$value}', '3023', '3024');";
    echo $sql;
}
foreach ($a as $key => $value) {
    $sql = "INSERT INTO `cuteframe`.`user_button_permission` (`id`, `user_id`, `parent_id`, `permission_id`) VALUES (null, '{$value}', '3023', '3025');";
    echo $sql;
}
foreach ($a as $key => $value) {
    $sql = "INSERT INTO `cuteframe`.`user_button_permission` (`id`, `user_id`, `parent_id`, `permission_id`) VALUES (null, '{$value}', '3023', '3026');";
    echo $sql;
}
foreach ($a as $key => $value) {
    $sql = "INSERT INTO `cuteframe`.`user_button_permission` (`id`, `user_id`, `parent_id`, `permission_id`) VALUES (null, '{$value}', '3023', '3027');";
    echo $sql;
}


foreach ($a as $key => $value) {
    $sql = "INSERT INTO `cuteframe`.`user_button_permission` (`id`, `user_id`, `parent_id`, `permission_id`) VALUES (null, '{$value}', '2533', '998');";
    echo $sql;
}
foreach ($a as $key => $value) {
    $sql = "INSERT INTO `cuteframe`.`user_button_permission` (`id`, `user_id`, `parent_id`, `permission_id`) VALUES (null, '{$value}', '2533', '999');";
    echo $sql;
}
foreach ($a as $key => $value) {
    $sql = "INSERT INTO `cuteframe`.`user_button_permission` (`id`, `user_id`, `parent_id`, `permission_id`) VALUES (null, '{$value}', '2533', '1000');";
    echo $sql;
}
foreach ($a as $key => $value) {
    $sql = "INSERT INTO `cuteframe`.`user_button_permission` (`id`, `user_id`, `parent_id`, `permission_id`) VALUES (null, '{$value}', '2533', '1001');";
    echo $sql;
}
foreach ($a as $key => $value) {
    $sql = "INSERT INTO `cuteframe`.`user_button_permission` (`id`, `user_id`, `parent_id`, `permission_id`) VALUES (null, '{$value}', '3023', '998');";
    echo $sql;
}
foreach ($a as $key => $value) {
    $sql = "INSERT INTO `cuteframe`.`user_button_permission` (`id`, `user_id`, `parent_id`, `permission_id`) VALUES (null, '{$value}', '3023', '999');";
    echo $sql;
}
foreach ($a as $key => $value) {
    $sql = "INSERT INTO `cuteframe`.`user_button_permission` (`id`, `user_id`, `parent_id`, `permission_id`) VALUES (null, '{$value}', '3023', '1000');";
    echo $sql;
}
foreach ($a as $key => $value) {
    $sql = "INSERT INTO `cuteframe`.`user_button_permission` (`id`, `user_id`, `parent_id`, `permission_id`) VALUES (null, '{$value}', '3023', '1001');";
    echo $sql;
}
foreach ($a as $key => $value) {
    $sql = "INSERT INTO `cuteframe`.`user_button_permission` (`id`, `user_id`, `parent_id`, `permission_id`) VALUES (null, '{$value}', '3015', '998');";
    echo $sql;
}
foreach ($a as $key => $value) {
    $sql = "INSERT INTO `cuteframe`.`user_button_permission` (`id`, `user_id`, `parent_id`, `permission_id`) VALUES (null, '{$value}', '3015', '999');";
    echo $sql;
}
foreach ($a as $key => $value) {
    $sql = "INSERT INTO `cuteframe`.`user_button_permission` (`id`, `user_id`, `parent_id`, `permission_id`) VALUES (null, '{$value}', '3015', '1000');";
    echo $sql;
}
foreach ($a as $key => $value) {
    $sql = "INSERT INTO `cuteframe`.`user_button_permission` (`id`, `user_id`, `parent_id`, `permission_id`) VALUES (null, '{$value}', '3015', '1001');";
    echo $sql;
}
exit();