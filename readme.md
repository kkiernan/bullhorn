# Bullhorn

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

- [Find](#find)
- [Find Multiple](#find-multiple)
- [Query](#query)

### Find
The `find` method retrieves a single entity with the ID you specify. See the [Bullhorn documentation](http://developer.bullhorn.com/doc/version_2-0/#Operations/operation-find.htm%3FTocPath%3DReference%7CCore%20Operations%7C_____14) for detailed information.

```
// Find a candidate by their ID.
$candidate = $bullhorn->find('Candidate', 98261);
```

**Tip:** The `find` method first checks to see if the id you are passing in is an array. If an array is detected, `find` will pass the request on to the `findMultiple` method for you. This is helpful when passing the id(s) in from a query result and you aren't sure if one or many results were returned.

### Find Multiple
The `findMultiple` method retrieves an array of entities matching the IDs you specify. See the [Bullhorn documenation](http://developer.bullhorn.com/doc/version_2-0/#Operations/operation-findMultiple.htm%3FTocPath%3DReference%7CCore%20Operations%7C_____15) for detailed information.

```
// Find multiple jobs.
$jobs = $bullhorn->findMultiple('JobOrder', [100, 101]);
```

### Query
The `query` method retrieves IDs of entities that match a query you specify. After retrieving the IDs, you can pass them to the `find` or `findMultiple` methods to get the entity details. See the [Bullhorn documentation](http://developer.bullhorn.com/doc/version_2-0/#Operations/operation-query.htm%3FTocPath%3DReference%7CCore%20Operations%7C_____45) for detailed information.

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

// Now you can get the details for the candidate.
$candidate = $bullhorn->find('Candidate', $candidateId);
```

## Available Entities

There are a ton of entities to work with in Bullhorn. Some examples are [Appointment](http://developer.bullhorn.com/doc/version_2-0/index.htm#Entities/Entity-Appointment.htm%3FTocPath%3DReference%7CEntities%7C_____1), [Candidate](http://developer.bullhorn.com/doc/version_2-0/index.htm#Entities/Entity-Candidate.htm%3FTocPath%3DReference%7CEntities%7C_____4), [Note](http://developer.bullhorn.com/doc/version_2-0/index.htm#Entities/Entity-Note.htm%3FTocPath%3DReference%7CEntities%7C_____34), and [JobSubmission](http://developer.bullhorn.com/doc/version_2-0/index.htm#Entities/Entity-JobSubmission.htm%3FTocPath%3DReference%7CEntities%7C_____33). Sea a [full list of the available entities](http://developer.bullhorn.com/doc/version_2-0/index.htm).

## Coding Style

I use phpcs and phpcbf to clean up the files and conform as closely as possible to PSR-2.