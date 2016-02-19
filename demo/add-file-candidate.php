<?php

use Kiernan\Bullhorn;

/*
|---------------------------------------------------------------------
| Require Files
|---------------------------------------------------------------------
*/

require '../vendor/autoload.php';

$config = require 'config.php';

/*
|---------------------------------------------------------------------
| Create Bullhorn Instnace
|---------------------------------------------------------------------
*/

$bullhorn = new Bullhorn(
    $config['bullhorn']['wsdl'],
    $config['bullhorn']['params'],
    $config['bullhorn']['username'],
    $config['bullhorn']['password'],
    $config['bullhorn']['apiKey']
);

/*
|---------------------------------------------------------------------
| Add File
|---------------------------------------------------------------------
|
| The addFile method sends a document from your filesystem
| to Bullhorn and links it with a specific entity.
|
*/

$entityId = 294415;
$entityName = 'Candidate';
$filename = 'test-resume.pdf';
$type = 'File';
$comments = 'Test API Upload!';

$result = $bullhorn->addFile($entityId, $entityName, $filename, $type, $comments);

/*
|---------------------------------------------------------------------
| Print Result
|---------------------------------------------------------------------
*/

print_r($result);
