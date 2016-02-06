<?php

require_once "lib/TurboApiClient.php";


$email = new Email();
$email->setFrom("aa@domain.com");
$email->setToList("bb.com, cc@domain.com");
$email->setCcList("dd@domain.com,ee@domain.com");
$email->setBccList("ffi@domain.com,rr@domain.com");	
$email->setSubject("subject");
$email->setContent("content");
$email->setHtmlContent("html content");
$email->addCustomHeader('X-FirstHeader', "value");
$email->addCustomHeader('X-SecondHeader', "value");
$email->addCustomHeader('X-Header-da-rimuovere', 'value');
$email->removeCustomHeader('X-Header-da-rimuovere');



$turboApiClient = new TurboApiClient("_username", "_password");


$response = $turboApiClient->sendEmail($email);

var_dump($response);


