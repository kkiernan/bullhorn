<?php

use Kiernan\Bullhorn;

require 'vendor/autoload.php';
$config = include 'config/bullhorn.php';

// Start your Bullhorn API session.
$bullhorn = new Bullhorn(
    $config['WSDL'],
    $config['PARAMS'],
    $config['USERNAME'],
    $config['PASSWORD'],
    $config['API_KEY']
);

// Find a candidate by their ID.
$candidate = $bullhorn->find('Candidate', 98261);
print_r($candidate);

// Find a candidate education record by its ID.
$candidateEducation = $bullhorn->find('CandidateEducation', 360);
print_r($candidateEducation);

// Get a candidate ID by email.
$candidateId = $bullhorn->query(
    [
        'entityName' => 'Candidate',
        'maxResults' => 1,
        'where' => 'isDeleted = 0 AND email = \'kkiernan@orbissolutions.com\'',
        'distinct' => 0,
        'parameters' => []
    ]
);

// Get details for the retrieved candidate ID.
$candidate = $bullhorn->find('Candidate', $candidateId);
print_r($candidate);
