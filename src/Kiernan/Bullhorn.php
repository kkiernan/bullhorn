<?php

namespace Kiernan;

use SoapClient;
use stdClass;

class Bullhorn
{
    use CreatesSoapVars;

    /**
     * @var SoapClient
     */
    protected $client;

    /**
     * The session object contains the key, corporation id, and user id.
     * 
     * @var stdClass
     */
    protected $session;

    /**
     * Create a new soap session instance.
     * 
     * @param string $wsdl
     * @param array  $params
     * @param string $username
     * @param string $password
     * @param string $apiKey
     */
    public function __construct($wsdl, array $params, $username, $password, $apiKey)
    {
        $this->client = new SoapClient($wsdl, $params);

        $config = new stdClass();
        $config->username = $username;
        $config->password = $password;
        $config->apiKey = $apiKey;

        $this->session = $this->client->startSession($config)->return;
    }

    /**
     * Perform a find operation.
     * 
     * @param string    $entityName
     * @param int|array $id
     * 
     * @return stdClass
     */
    public function find($entityName, $id)
    {
        // Without checking, you don't know if you get one id or an array of ids
        // from a query. This quick check makes the public api a bit cleaner.
        if (is_array($id)) {
            return $this->findMultiple($entityName, $id);
        }

        $request = [
            'session'    => $this->session->session,
            'entityName' => $entityName,
            'id'         => $this->soapInt($id),
        ];

        $entity = $this->client->find($request);

        return isset($entity->return->dto) ? $entity->return->dto : new stdClass();
    }

    /**
     * Perform a findMultiple operation.
     * 
     * @param string $entityName
     * @param array  $ids
     * 
     * @return array
     */
    public function findMultiple($entityName, array $ids)
    {
        // Placeholder arrays for our results.
        $results = [];
        $flattenedResults = [];

        // Convert each ID to a SoapVar object.
        $ids = array_map(function ($id) {
            return $this->soapInt($id);
        }, $ids);

        // The API only supports 20 IDs per request.
        $chunks = array_chunk($ids, 20);

        // Send a request for each group of 20 IDs.
        foreach ($chunks as $ids) {
            $request = [
                'session'    => $this->session->session,
                'entityName' => $entityName,
                'ids'        => $ids,
            ];

            $results[] = $this->client->findMultiple($request);
        }

        // Keep only the dtos array in each result group.
        $results = array_map(function ($chunk) {
            return $chunk->return->dtos;
        }, $results);

        // If there are no entities, return an empty array. Without this, the
        // array_map called above results in this method returning an array
        // with a single empty element. Not a huge deal, but, I don't
        // know, I don't like that.
        if (empty(array_filter($results))) {
            return [];
        }

        // Flatten the nested array.
        array_walk_recursive($results, function (&$item, $key) use (&$flattenedResults) {
            $flattenedResults[] = $item;
        });

        return $flattenedResults;
    }

    /**
     * Send a query request to the api.
     *
     * @param array $data
     * 
     * @return array
     */
    public function query(array $data)
    {
        $request = $this->soapObject(
            [
                'session' => $this->session->session,
                'query'   => $this->soapObject(
                    $data,
                    'dtoQuery',
                    'http://query.apiservice.bullhorn.com/'
                ),
            ],
            'query',
            'http://query.apiservice.bullhorn.com/'
        );

        $ids = $this->client->query($request);

        return !$ids->return->ids ? [] : $ids->return->ids;
    }

    /**
     * Creates a link between an instance of an entity and a file.
     *
     * @param int    $entityId
     * @param string $entityName
     * @param string $filename
     * @param string $type
     * @param string $comments
     */
    public function addFile($entityId, $entityName, $filename, $type, $comments = '')
    {
        $entityId = $this->soapInt($entityId);

        $mimeType = mime_content_type($filename);

        $mimeTypeParts = explode('/', $mimeType);

        $fileMetaData = [
            'comments'       => $comments,
            'contentSubType' => $mimeTypeParts[1],
            'contentType'    => $mimeTypeParts[0],
            'name'           => $filename,
            'type'           => $type,
        ];

        $fileHandle = fopen($filename, 'r');
        $fileContent = fread($fileHandle, filesize($filename));
        fclose($fileHandle);

        $request = [
            'session'      => $this->session->session,
            'entityName'   => $entityName,
            'entityId'     => $entityId,
            'fileMetaData' => $fileMetaData,
            'fileContent'  => $fileContent,
        ];

        return $this->client->addFile($request);
    }
}
