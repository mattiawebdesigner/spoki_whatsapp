<?php
include_once '../class/autoload.php';

//error_reporting(E_ALL);

// $contatto = new SpokiContatto("3348768832", "Nome test1", "Cognome Test1");

// $spoki = new Spoki("cd81527c-97dd-11ed-a8fc-0242ac120002");
// $spoki->sendSingleMessage($contatto, "Messaggio di prova API", "https://app.spoki.it/api/messages/");

$contact = new SpokiContatto("3348768832");
Spoki::sendTemplateToClient($contact, SPOKI_TEMPLATE_PRESENTAZIONE, [
    'SENTENCE' => $contact->getFirstName(),
]);