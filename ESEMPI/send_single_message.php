<?php
include_once '../class/autoload.php';

$contatto = new SpokiContatto("3348768832");

// $spoki = new Spoki("cd81527c-97dd-11ed-a8fc-0242ac120002");
$spoki = new Spoki($contatto->getUid());
$spoki->sendSingleMessage($contatto, "Messaggio di prova API", [], "https://app.spoki.it/api/messages/");
