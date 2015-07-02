# Bullhorn PHP Client

This is a basic PHP wrapper around the Bullhorn SOAP API. A REST API is offered by Bullhorn, but I currently only have the means to access and test the SOAP API. Support for the REST API can be added if there is any interest.

## Authenticating

Before you can issue any requests, you must create a new Bullhorn API session. It's really easy with the library!

```
// Start your Bullhorn API session. Keep your config options in a separate file
// that is not included in source control.
$bullhorn = new Bullhorn(
    $config['WSDL'],
    $config['PARAMS'],
    $config['USERNAME'],
    $config['PASSWORD'],
    $config['API_KEY']
);

// Or just do it inline...
$bullhorn = new Bullhorn(
    'https://api.bullhornstaffing.com/webservices-2.5/?wsdl',
    [
        'trace' => 1,
        'soap_version' => SOAP_1_1
    ],
    'your_username',
    'your_super_secret_password',
    'your_super_secret_api_key',
);
```

## Supported Operations

### Find
The find method retrieves a single entity with the ID you specify. See the [Bullhorn documentation](http://developer.bullhorn.com/doc/version_2-0/#Operations/operation-find.htm%3FTocPath%3DReference%7CCore%20Operations%7C_____14) for detailed information.

```
// Find a candidate by their ID.
$candidate = $bullhorn->find('Candidate', 98261);
print_r($candidate);
```

### Query
The query method retrieves IDs of entities that match a query you specify. See the [Bullhorn documentation](http://developer.bullhorn.com/doc/version_2-0/#Operations/operation-query.htm%3FTocPath%3DReference%7CCore%20Operations%7C_____45) for detailed information.

```
// Get a candidate ID by email.
$candidateId = $bullhorn->query(
    [
        'entityName' => 'Candidate',
        'maxResults' => 1,
        'where' => 'isDeleted = 0 AND email = \'john@smith.com\'',
        'distinct' => 0,
        'parameters' => []
    ]
);
```
