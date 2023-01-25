<?php

if(!defined('SPOKI_USERNAME')){
    define("SPOKI_USERNAME", 'l.parisi@boostar.it');
}
if(!defined('SPOKI_PASSWORD')){
    define('SPOKI_PASSWORD', 'nupnyr-webKa3-vugrox');
}
$tmp = SpokiBearerToken::getToken(SPOKI_USERNAME, SPOKI_PASSWORD);
if(!defined('SPOKI_AUTHORIZATION_BEARER')) {
    define("SPOKI_AUTHORIZATION_BEARER", $tmp['access_token']);
}
if(!defined('SPOKI_USER_ACCOUNT')) {
    define("SPOKI_USER_ACCOUNT", $tmp['account']);
}

if(!defined('SPOKI_TEMPLATE_PRESENTAZIONE')) {
    define("SPOKI_TEMPLATE_PRESENTAZIONE", "33133");
}