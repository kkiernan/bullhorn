<?php

use Kiernan\Entities\Candidate;
use Kiernan\Operations\Query;
use Kiernan\SoapSession;

require 'vendor/autoload.php';
$config = include 'config/bullhorn.php';

// Start your Bullhorn API soap session.
$session = new SoapSession(
    $config['WSDL'],
    $config['PARAMS'],
    $config['USERNAME'],
    $config['PASSWORD'],
    $config['API_KEY']
);

// Get a candidate by their ID.
$candidate = Candidate::findById($session, 360);

print_r($candidate);

// Get the first 10 candidate IDs where the candidate has not been deleted.
$ids = Query::send(
    $session,
    [
    'entityName' => 'Candidate',
    'maxResults' => 10,
    'where' => 'isDeleted = 0',
    'distinct' => 0,
    'parameters' => []
    ]
);

// Get details for all candidates found in the previous query.
$candidates = [];

foreach ($ids as $id) {
    $candidates[] = Candidate::findById($session, $id);
}

print_r($candidates);
