<?php
header("Pragma: nocache\n");
header("cache-control: no-cache, must-revalidate, no-store\n\n");
header("Expires: Mon, 01 Jan 1990 01:01:01 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s",time())."GMT");
header('Content-Type: text/html; charset=UTF-8');
header('P3P: CP="CAO PSA OUR"');
//header('P3P: CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
error_reporting(E_ERROR | E_PARSE);

define('DIR_CLASSES', DIR_ROOT.'/classes');
define('WBS_ROOT_PATH', realpath( DIR_ROOT."/../../../../" ));
//define('WBS_ROOT_PATH', realpath( dirname(__FILE__)."/../../../../../" ));
define('WBS_DIR', WBS_ROOT_PATH . '/');
if (!defined("SYSTEM_PATH")) {
	define("SYSTEM_PATH", WBS_DIR."system");
}
if (!defined("WBS_PUBLISHED_DIR")){define("WBS_PUBLISHED_DIR", WBS_DIR."published");}

define('__USE_OLD_UPDATE',false);
