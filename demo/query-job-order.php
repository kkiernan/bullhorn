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
| Fetch Job IDs
|---------------------------------------------------------------------
|
| Use the query method to retrieve entity ids that match specific
| search parameters. Note the use of single and double quotes
| in the where array element.
|
| You can pass the query result to the find or findMultiple method
| to retrieve details for each of the ids.
|
*/

$jobOrderIds = $bullhorn->query([
    'entityName' => 'JobOrder',
    'where'      => "title LIKE '%manager%' AND status = 'Accepting Candidates'",
    'distinct'   => 0,
    'parameters' => [],
]);

/*
|---------------------------------------------------------------------
| Print Candidate
|---------------------------------------------------------------------
*/

print_r($jobOrderIds);
