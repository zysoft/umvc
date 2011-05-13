<?
error_reporting(E_ALL);

// http://www.php.net/manual/en/security.magicquotes.disabling.php
if (get_magic_quotes_gpc()) {
    $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    while (list($key, $val) = each($process)) {
        foreach ($val as $k => $v) {
            unset($process[$key][$k]);
            if (is_array($v)) {
                $process[$key][stripslashes($k)] = $v;
                $process[] = &$process[$key][stripslashes($k)];
            } else {
                $process[$key][stripslashes($k)] = stripslashes($v);
            }
        }
    }
    unset($process);
}

include_once(UF_BASE.'/core/debug.php');
require_once(UF_BASE.'/core/application.php');
require_once(UF_BASE.'/core/baker.php');
require_once(UF_BASE.'/core/session.php');
require_once(UF_BASE.'/core/response.php');
require_once(UF_BASE.'/core/httprequest.php');
require_once(UF_BASE.'/core/controller.php');
require_once(UF_BASE.'/core/validator.php');

/* EOF */