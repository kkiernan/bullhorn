# Bullhorn

This is a basic PHP wrapper around the Bullhorn SOAP API. A REST API is offered by Bullhorn, but I currently only have the means to access and test the SOAP API.

## Authenticating

Before you can issue any requests to the Bullhorn API, you must create a new session using your Bullhorn credentials. See the demo folder for examples.

**Inline Example**
```
$bullhorn = new Bullhorn(
    'https://api.bullhornstaffing.com/webservices-2.5/?wsdl',
    ['trace' => 1, 'soap_version' => SOAP_1_1],
    'your_username',
    'your_super_secret_password',
    'your_super_secret_api_key',
);
```

**Separate Config File**
```
$config = require 'config.php';

$bullhorn = new Bullhorn(
    $config['bullhorn']['wsdl'],
    $config['bullhorn']['params'],
    $config['bullhorn']['username'],
    $config['bullhorn']['password'],
    $config['bullhorn']['apiKey']
);
```

## Supported Operations

- [find](#find)
- [findMultiple](#find-multiple)
- [query](#query)
- [addFile](#add-file)

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
// Get a candidate id for a specific query
$id = $bullhorn->query([
    'entityName' => 'Candidate',
    'maxResults' => 1,
    'where'      => "isDeleted = 0 AND email = 'john@smith.com'",
    'distinct'   => 0,
    'parameters' => []
]);

// Get the details for the candidate id that was returned
$candidate = $bullhorn->find('Candidate', $id);
```

## Add File

The `addFile` method links a file with a Bullhorn entity. Note that the file must be on the filesystem already. If the user is uploading a file from an HTML form, you must upload that file to your server first. This method does not upload the file for you, it only sends the file to Bullhorn and links it with the entity you specify.

```
$entityId = 294415;
$entityName = 'Candidate';
$filename = 'test-resume.pdf';
$type = 'File';
$comments = 'Test API Upload!';

$result = $bullhorn->addFile($entityId, $entityName, $filename, $type, $comments);
```

## Available Entities

There are a ton of entities to work with in Bullhorn. Some examples are [Appointment](http://developer.bullhorn.com/doc/version_2-0/index.htm#Entities/Entity-Appointment.htm%3FTocPath%3DReference%7CEntities%7C_____1), [Candidate](http://developer.bullhorn.com/doc/version_2-0/index.htm#Entities/Entity-Candidate.htm%3FTocPath%3DReference%7CEntities%7C_____4), [Note](http://developer.bullhorn.com/doc/version_2-0/index.htm#Entities/Entity-Note.htm%3FTocPath%3DReference%7CEntities%7C_____34), and [JobSubmission](http://developer.bullhorn.com/doc/version_2-0/index.htm#Entities/Entity-JobSubmission.htm%3FTocPath%3DReference%7CEntities%7C_____33). Sea a [full list of the available entities](http://developer.bullhorn.com/doc/version_2-0/index.htm).

## Coding Style

I use phpcs and phpcbf to clean up the files and conform as closely as possible to PSR-2.
