<?php
header("Content-type:text/html;charset=utf-8");
date_default_timezone_set('Asia/Shanghai');

$a = array(9,
140,
153,
154,
4,
56,
60,
31,
47,
30,
42,
48,
41,
40,
43,
152,
68,
45,
123,
161,
158,
157,
156,
155,
138,
102,
105,
100,
104,
5,
109,
111,
110,
106,
99,
116,
117,
121,
113,
114,
118,
127,
124,
125,
126,
137,
135,
131,
128,
136,
133,
130,
132,
129,
134,
143,
144,
145,
146,
148,
147,
149,
141);


$b = array(6001,
6002,
6003,
6004,
6005,
6006,
6007,
6008,
6009,
6010,
6011,
6012,
6013,
6014,
6015,
6016,
6017,
6018,
6019,
6020,
6021,
6022,
6023,
6024,
6025,
6026,
6027,
6028,
6029,
6030,
6031,
6032,
6033,
6034,
6035,
6036,
6037,
6038,
6039,
6040,
6042,
6044,
6045,
6046,
6047,
6048,
6049,
6050,
6051,
6052,
6053,
6054,
6055,
6056,
6057,
6059,
6060,
6061,
6062,
6063,
6064,
6065,
6073);

$sql = '';
foreach ($a as $k => $v) {
    # code...
    $sql .= "update `cuteframe`.`sales_channels` set `dj_no` = ".$b[$k]." where `id` = $v;<br/>";
}

echo $sql;

//ALTER TABLE `cuteframe`.`sales_channels` ADD `dj_no` int(4) DEFAULT NULL;

/*
update `cuteframe`.`sales_channels` set `dj_no` = 6001 where `id` = 9;
update `cuteframe`.`sales_channels` set `dj_no` = 6002 where `id` = 140;
update `cuteframe`.`sales_channels` set `dj_no` = 6003 where `id` = 153;
update `cuteframe`.`sales_channels` set `dj_no` = 6004 where `id` = 154;
update `cuteframe`.`sales_channels` set `dj_no` = 6005 where `id` = 4;
update `cuteframe`.`sales_channels` set `dj_no` = 6006 where `id` = 56;
update `cuteframe`.`sales_channels` set `dj_no` = 6007 where `id` = 60;
update `cuteframe`.`sales_channels` set `dj_no` = 6008 where `id` = 31;
update `cuteframe`.`sales_channels` set `dj_no` = 6009 where `id` = 47;
update `cuteframe`.`sales_channels` set `dj_no` = 6010 where `id` = 30;
update `cuteframe`.`sales_channels` set `dj_no` = 6011 where `id` = 42;
update `cuteframe`.`sales_channels` set `dj_no` = 6012 where `id` = 48;
update `cuteframe`.`sales_channels` set `dj_no` = 6013 where `id` = 41;
update `cuteframe`.`sales_channels` set `dj_no` = 6014 where `id` = 40;
update `cuteframe`.`sales_channels` set `dj_no` = 6015 where `id` = 43;
update `cuteframe`.`sales_channels` set `dj_no` = 6016 where `id` = 152;
update `cuteframe`.`sales_channels` set `dj_no` = 6017 where `id` = 68;
update `cuteframe`.`sales_channels` set `dj_no` = 6018 where `id` = 45;
update `cuteframe`.`sales_channels` set `dj_no` = 6019 where `id` = 123;
update `cuteframe`.`sales_channels` set `dj_no` = 6020 where `id` = 161;
update `cuteframe`.`sales_channels` set `dj_no` = 6021 where `id` = 158;
update `cuteframe`.`sales_channels` set `dj_no` = 6022 where `id` = 157;
update `cuteframe`.`sales_channels` set `dj_no` = 6023 where `id` = 156;
update `cuteframe`.`sales_channels` set `dj_no` = 6024 where `id` = 155;
update `cuteframe`.`sales_channels` set `dj_no` = 6025 where `id` = 138;
update `cuteframe`.`sales_channels` set `dj_no` = 6026 where `id` = 102;
update `cuteframe`.`sales_channels` set `dj_no` = 6027 where `id` = 105;
update `cuteframe`.`sales_channels` set `dj_no` = 6028 where `id` = 100;
update `cuteframe`.`sales_channels` set `dj_no` = 6029 where `id` = 104;
update `cuteframe`.`sales_channels` set `dj_no` = 6030 where `id` = 5;
update `cuteframe`.`sales_channels` set `dj_no` = 6031 where `id` = 109;
update `cuteframe`.`sales_channels` set `dj_no` = 6032 where `id` = 111;
update `cuteframe`.`sales_channels` set `dj_no` = 6033 where `id` = 110;
update `cuteframe`.`sales_channels` set `dj_no` = 6034 where `id` = 106;
update `cuteframe`.`sales_channels` set `dj_no` = 6035 where `id` = 99;
update `cuteframe`.`sales_channels` set `dj_no` = 6036 where `id` = 116;
update `cuteframe`.`sales_channels` set `dj_no` = 6037 where `id` = 117;
update `cuteframe`.`sales_channels` set `dj_no` = 6038 where `id` = 121;
update `cuteframe`.`sales_channels` set `dj_no` = 6039 where `id` = 113;
update `cuteframe`.`sales_channels` set `dj_no` = 6040 where `id` = 114;
update `cuteframe`.`sales_channels` set `dj_no` = 6042 where `id` = 118;
update `cuteframe`.`sales_channels` set `dj_no` = 6044 where `id` = 127;
update `cuteframe`.`sales_channels` set `dj_no` = 6045 where `id` = 124;
update `cuteframe`.`sales_channels` set `dj_no` = 6046 where `id` = 125;
update `cuteframe`.`sales_channels` set `dj_no` = 6047 where `id` = 126;
update `cuteframe`.`sales_channels` set `dj_no` = 6048 where `id` = 137;
update `cuteframe`.`sales_channels` set `dj_no` = 6049 where `id` = 135;
update `cuteframe`.`sales_channels` set `dj_no` = 6050 where `id` = 131;
update `cuteframe`.`sales_channels` set `dj_no` = 6051 where `id` = 128;
update `cuteframe`.`sales_channels` set `dj_no` = 6052 where `id` = 136;
update `cuteframe`.`sales_channels` set `dj_no` = 6053 where `id` = 133;
update `cuteframe`.`sales_channels` set `dj_no` = 6054 where `id` = 130;
update `cuteframe`.`sales_channels` set `dj_no` = 6055 where `id` = 132;
update `cuteframe`.`sales_channels` set `dj_no` = 6056 where `id` = 129;
update `cuteframe`.`sales_channels` set `dj_no` = 6057 where `id` = 134;
update `cuteframe`.`sales_channels` set `dj_no` = 6059 where `id` = 143;
update `cuteframe`.`sales_channels` set `dj_no` = 6060 where `id` = 144;
update `cuteframe`.`sales_channels` set `dj_no` = 6061 where `id` = 145;
update `cuteframe`.`sales_channels` set `dj_no` = 6062 where `id` = 146;
update `cuteframe`.`sales_channels` set `dj_no` = 6063 where `id` = 148;
update `cuteframe`.`sales_channels` set `dj_no` = 6064 where `id` = 147;
update `cuteframe`.`sales_channels` set `dj_no` = 6065 where `id` = 149;
update `cuteframe`.`sales_channels` set `dj_no` = 6073 where `id` = 141;*/