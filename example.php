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

// Test that findMultiple can handle a single ID.
$job = $bullhorn->findMultiple('JobOrder', [15616165165161]);

print_r($job);

exit;



// Basic findMultiple exapmle.
$jobs = $bullhorn->findMultiple('JobOrder', [100, 101]);

// Get job IDs that match a search.
$ids = $bullhorn->query(
    [
        'entityName' => 'JobOrder',
        'where' => "title LIKE '%manager%' AND isDeleted = 0 AND isOpen = 1 AND isPublic = 1 AND status = 'Accepting Candidates'",
        'distinct' => 0,
        'parameters' => []
    ]
);

// Get details for each job that matches our search.
$jobOrders = $bullhorn->find('JobOrder', $ids);

// Print the job titles to the screen.
foreach ($jobOrders as $jobOrder) {
    printf("%s \n", $jobOrder->title);
}

// Find a candidate by their ID.
$candidate = $bullhorn->find('Candidate', 98261);

// Find a candidate education record by its ID.
$candidateEducation = $bullhorn->find('CandidateEducation', 360);

// Get a candidate ID by email.
$candidateId = $bullhorn->query(
    [
        'entityName' => 'Candidate',
        'maxResults' => 1,
        'where' => 'isDeleted = 0 AND email = \'john@example.com\'',
        'distinct' => 0,
        'parameters' => []
    ]
);

// Get details for the retrieved candidate ID.
$candidate = $bullhorn->find('Candidate', $candidateId);
