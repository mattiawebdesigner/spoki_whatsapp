<?php
include_once '../class/autoload.php';

$contact = new SpokiContatto("1234567890");
Spoki::sendTemplateToClient($contact, SPOKI_TEMPLATE_PRESENTAZIONE, [
    'SENTENCE' => $contact->getFirstName(),
]);
