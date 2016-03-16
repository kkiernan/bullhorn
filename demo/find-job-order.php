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
| Find Job Order by ID
|---------------------------------------------------------------------
|
| Once you have an id (often as a result of the query method),
| you can retrieve details using the find method.
|
| The first parameter must be a valid entity as defined by the
| Bullhorn API. Check the appropriate documentation for the
| API version you are using.
|
| http://developer.bullhorn.com/documentation
|
*/

$candidate = $bullhorn->find('JobOrder', 6046);

/*
|---------------------------------------------------------------------
| Print Candidate
|---------------------------------------------------------------------
*/

print_r($candidate);
