<?php
/**
 * @Author: anchen
 * @Date:   2017-10-27 15:47:24
 * @Last Modified by:   anchen
 * @Last Modified time: 2017-10-27 16:20:19
 */
function getendday2( $start='now', $offset=0){
    $starttime = strtotime($start);
    $tmptime = $starttime + 24*3600;
    while( $offset > 0 ){
        //echo $tmptime."<br/>";
        $weekday = date('w', $tmptime);
        //var_dump($weekday);
        $tmpday = date('y-m-d', $tmptime);
        if($weekday != 0){//不是周末
            $offset--;
        }
        //echo $offset."<br/>";
        $tmptime += 24*3600;
        //echo $tmptime;
    }
    return $tmpday;
}
//var_dump(date('w',1509228000));die;
echo getendday2('2017-10-28',3);

function getendday( $start='now', $offset=0){
    //先计算不排除周六周日及节假日的结果
    $starttime = strtotime($start);
    $endtime = $starttime + $offset * 24 * 3600;
    $end = date('y-m-d', $endtime);
    //var_dump($end);die;
    //然后计算周六周日引起的偏移
    //var_dump($starttime);die;
    $weekday = date('w', $starttime);//得到星期值：1-7
    //var_dump($weekday);die;
    $remain = $offset % 7;
    $newoffset = 2 * ($offset - $remain) / 7;//每一周需重新计算两天
    if( $remain > 0 ){//周余凑整
        $tmp = $weekday + $remain;
        if( $tmp >= 7 ){
            $newoffset += 2;
        }else if( $tmp == 6 ){
            $newoffset += 1;
        }
        //考虑当前为周六周日的情况
        if( $weekday == 6 ){
            $newoffset -= 1;
        }else if( $weekday == 7 ){
            $newoffset -= 2;
        }
    }
    //根据偏移天数，递归做等价运算111cn.net
    if($newoffset > 0){
        #echo "[{$start} -> {$offset}] = [{$end} -> {$newoffset}]"."<br />n";
        return getendday($end,$newoffset);
    }else{
        return $end;
    }
}