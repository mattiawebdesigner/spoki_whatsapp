<?php
include_once '../class/autoload.php';

$contatto = new SpokiContatto("3348768832", "Nome test1", "Cognome Test1");

$spoki = new Spoki("your uuid");
$spoki->sendSingleMessage($contatto, "Messaggio di prova API", "https://app.spoki.it/api/messages/");
