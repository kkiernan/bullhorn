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

The `find` method retrieves a single entity with the id you specify.

```
// Find a candidate entity by id
$candidate = $bullhorn->find('Candidate', 98261);
```

**Tip**: The `find` method first checks to see if the id you are passing in is an array. If an array is detected, `find` will pass the request on to the `findMultiple` method for you. This is helpful when passing the id(s) in from a query result and you aren't sure if one or many results were returned.

### Find Multiple

The `findMultiple` method retrieves an array of entities matching the ids you specify.

```
// Find multiple job orders
$jobs = $bullhorn->findMultiple('JobOrder', [100, 101]);
```

### Query

The `query` method retrieves ids of entities that match a query you specify. After retrieving the ids, you can pass them to the `find` or `findMultiple` methods to get the entity details.

```
// Get a candidate id for a specific query
$id = $bullhorn->query([
    'entityName' => 'Candidate',
    'maxResults' => 1,
    'where'      => "isDeleted = 0 AND email = 'john@smith.com'",
    'distinct'   => 0,
    'parameters' => []
]);

// Get details for the candidate id
$candidate = $bullhorn->find('Candidate', $id);
```

## Add File

The `addFile` method links a file with a Bullhorn entity.

**Note**: The file must be on the filesystem already. For example, if the user is uploading a file from an HTML form, you must upload that file to your server. You can then pass that filename along to the `addFile` method.

```
$entityId = 294415;
$entityName = 'Candidate';
$filename = 'test-resume.pdf';
$type = 'File';
$comments = 'Test API Upload!';

$bullhorn->addFile($entityId, $entityName, $filename, $type, $comments);
```

## Available Entities

There are a ton of entities to work with in Bullhorn. Some examples are Appointment, Candidate, Note and JobSubmission. See a [full list of the available entities](http://developer.bullhorn.com/documentation) in the documentation.
