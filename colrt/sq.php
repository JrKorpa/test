<?php

	header("Content-type:text/html;charset=utf-8");
	date_default_timezone_set('Asia/Shanghai');
	require_once('MysqlDB.class.php');
	set_time_limit(0);
	ini_set('memory_limit','2000M');

$new_conf = [
	'dsn'=>"mysql:host=192.168.0.95;dbname=cuteframe",
	'user'=>"cuteman",
	'password'=>"QW@W#RSS33#E#",
		'charset' => 'utf8'
];

$db = new MysqlDB($new_conf);

	$sql = "SELECT * from sales_channels_person";
	$ret = $db->getAll($sql);
	if ($ret == null){
		break;
	}

	foreach($ret as $r){
	    $names_1 = preg_split('/,/', $r['dp_leader_name'], -1, PREG_SPLIT_NO_EMPTY);
		$names_2 = preg_split('/,/', $r['dp_people_name'], -1, PREG_SPLIT_NO_EMPTY);
		
		$users = array_unique(array_merge($names_1, $names_2));
		foreach($users as $u) {
			$u = trim($u);
			$usql = "select * from `user` where account = '{$u}' and  is_enabled = 1 and is_on_work = 1 ";
			$model = $db->getRow($usql);
			if ($model == null) continue;
			
			$id = $model['id'];
			$sql = "
INSERT INTO user_menu_permission(user_id, permission_id) values({$id}, 2533);
INSERT INTO user_button_permission(user_id, parent_id, permission_id) values({$id}, 2533, 998);
INSERT INTO user_button_permission(user_id, parent_id, permission_id) values({$id}, 2533, 999);
INSERT INTO user_button_permission(user_id, parent_id, permission_id) values({$id}, 2533, 1000);
INSERT INTO user_operation_permission(user_id, parent_id, permission_id) values({$id}, 2533, 2532);
			";
			
			$db->exec($sql);
		}
	}

/*
INSERT INTO user_menu_permission(user_id, permission_id) values(13304, 2533);
INSERT INTO user_button_permission(user_id, parent_id, permission_id) values(13304, 2533, 998);
INSERT INTO user_button_permission(user_id, parent_id, permission_id) values(13304, 2533, 999);
INSERT INTO user_button_permission(user_id, parent_id, permission_id) values(13304, 2533, 1000);
INSERT INTO user_operation_permission(user_id, parent_id, permission_id) values(13304, 2533, 2532);
 */


/*INSERT INTO user_menu_permission(user_id, permission_id) values(13304, 2533);
INSERT INTO user_button_permission(user_id, parent_id, permission_id) values(13304, 2533, 2668);
INSERT INTO user_button_permission(user_id, parent_id, permission_id) values(13304, 2533, 2669);
INSERT INTO user_button_permission(user_id, parent_id, permission_id) values(13304, 2533, 998);
INSERT INTO user_button_permission(user_id, parent_id, permission_id) values(13304, 2533, 999);
INSERT INTO user_button_permission(user_id, parent_id, permission_id) values(13304, 2533, 1000);
INSERT INTO user_operation_permission(user_id, parent_id, permission_id) values(13304, 2533, 2664);
INSERT INTO user_operation_permission(user_id, parent_id, permission_id) values(13304, 2533, 2665);
INSERT INTO user_operation_permission(user_id, parent_id, permission_id) values(13304, 2533, 2666);*/

/*
INSERT INTO user_menu_permission(user_id, permission_id) values({$id}, 2667);
INSERT INTO user_button_permission(user_id, parent_id, permission_id) values({$id}, 2667, 2668);
INSERT INTO user_button_permission(user_id, parent_id, permission_id) values({$id}, 2667, 2669);
INSERT INTO user_button_permission(user_id, parent_id, permission_id) values({$id}, 2667, 998);
INSERT INTO user_button_permission(user_id, parent_id, permission_id) values({$id}, 2667, 999);
INSERT INTO user_button_permission(user_id, parent_id, permission_id) values({$id}, 2667, 1000);
INSERT INTO user_operation_permission(user_id, parent_id, permission_id) values({$id}, 2667, 2664);
INSERT INTO user_operation_permission(user_id, parent_id, permission_id) values({$id}, 2667, 2665);
INSERT INTO user_operation_permission(user_id, parent_id, permission_id) values({$id}, 2667, 2666);*/