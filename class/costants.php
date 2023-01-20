<?php
if(!defined('SPOKI_AUTHORIZATION_BEARER')) {
    define("SPOKI_AUTHORIZATION_BEARER", SpokiBearerToken::getToken('l.parisi@boostar.it', 'nupnyr-webKa3-vugrox'));
}

if(!defined('SPOKI_TEMPLATE_PRESENTAZIONE')) {
    define("SPOKI_TEMPLATE_PRESENTAZIONE", "33133");
}