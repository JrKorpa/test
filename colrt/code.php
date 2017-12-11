<?php
define('ROOT_PATH', str_replace('code.php', '', str_replace('\\', '/', __FILE__)));
require './includes/ValidateCode.class.php';
$_vc = new ValidateCode();
$_vc->doimg();
$_SESSION['code'] = $_vc->getCode();
print_r($_SESSION['code']);