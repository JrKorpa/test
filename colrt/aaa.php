<?php
header("Content-type:text/html;charset=utf-8");
date_default_timezone_set('Asia/Shanghai');
define('IN_ECS', true);
define('ROOT_PATHS', str_replace('aaa.php', '', str_replace('\\', '/', __FILE__)));
require_once('includes/MysqlDB.class.php');
error_reporting(E_ALL);

/*$new_conf = [
    'dsn'=>"mysql:host=192.168.1.59;dbname=front",
    'user'=>"cuteman",
    'password'=>"QW@W#RSS33#E#",
    'charset' => 'utf8'
];*/

$new_conf = [
    'dsn'=>"mysql:host=192.168.0.95;dbname=front",
    'user'=>"cuteman",
    'password'=>"QW@W#RSS33#E#",
    'charset' => 'utf8'
];

$db = new MysqlDB($new_conf);
$sql = "SELECT `g_id`,`style_id`,`img_ori` FROM `app_style_gallery` WHERE LENGTH(`img_ori`)<>CHARACTER_LENGTH(`img_ori`)
and `style_id` = 29005";
$galldate = $db->getAll($sql);
foreach ($galldate as $key => $v) {
    $g_id = $v['g_id'];
    $oldname = $v['img_ori'];
    if($oldname){
        $t1 = $path =  explode('/', $oldname);
        unset($t1[0], $t1[1], $t1[2]);
        $oldname = implode('/', $t1);
        $t2 = explode('.', array_pop($t1));
        $newname = random_filename().".".array_pop($t2);
        $newname_path = implode("/", $t1)."/".$newname;
        $r = rename(iconv('UTF-8','GBK','./'.$oldname), './'.$newname_path);
        if($r){
            array_pop($path);
            $new_path = implode('/', $path)."/".$newname;
            $sql = "update `app_style_gallery` set `img_ori` = '$new_path' where `g_id` = $g_id";
            $res = $db->query($sql);
            if($res){
                echo '重命名成功！<br/>';
            }else{
                echo '重命名失败2！<br/>';
            }
        }else{
            echo '重命名失败1！<br/>';
        }
    }
}

echo '----------';die;

//$data = explode('/', $galldate);
$path = '/home/ictspace/www/img/avatar';
function getfiles($path){
    if(!is_dir($path)) return;
    $handle  = opendir($path);
    $files = array();
    while(false !== ($file = readdir($handle))){
        if($file != '.' && $file!='..'){
            $path2= $path.'/'.$file;
            if(is_dir($path2)){
                getfiles($path2);         
            }else{
                if(preg_match("/\.(gif|jpeg|jpg|png|bmp)$/i", $file)){
                    $files[] = $path.'/'.$file;
                }
            }         
        }
    }
    return $files;
}


$date=date('Ymdhis');//得到当前时间,如;20070705163148
$fileName=$_FIFLES['file']['name'];//得到上传文件的名字
$name=explode('.',$fileName);//将文件名以'.'分割得到后缀名,得到一个数组
$newPath=$date.'.'.$name[1];//得到一个新的文件为'20070705163148.jpg',即新的路径
$oldPath=$_FILES['file']['tmp_name'];//临时文件夹,即以前的路径
rename(iconv('UTF-8','GBK',$dir.$filename), iconv('UTF-8','GBK',$dir.$newFileName));

rename($oldPath,$newPath); //就可以重命名了!

//SELECT * FROM `app_style_gallery` where (`img_ori` like '%拷贝%' or `img_ori` like '%副本%');

//SELECT * FROM `app_style_gallery` WHERE LENGTH(`img_ori`)<>CHARACTER_LENGTH(`img_ori`);


//利用PHP目录和文件函数遍历用户给出目录的所有的文件和文件夹，修改文件名称
function fRename($dirname){
    if(!is_dir($dirname)){
        echo "{$dirname}不是一个有效的目录！";
        exit();
    }
    $handle = opendir($dirname);
    while(($fn = readdir($handle))!==false){
        if($fn!='.'&&$fn!='..'){
            $curDir = $dirname.'/'.$fn;
            if(is_dir($curDir)){
                fRename($curDir);
            }else{
                $path = pathinfo($curDir);
                $newname = $path['dirname'].'/'.rand(0,100).'.'.$path['extension'];
                rename($curDir,$newname);   
                echo $curDir.'---'.$newname."<br>";   
            }
        }
    }
}

/**
 * 生成随机的数字串
 *
 * @author: weber liu
 * @return string
 */
function random_filename()
{
    $str = '';
    for($i = 0; $i < 9; $i++)
    {
        $str .= mt_rand(0, 9);
    }

    return time() . $str;
}