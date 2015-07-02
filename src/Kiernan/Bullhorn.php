<?php

namespace Kiernan;

use SoapClient;
use stdClass;

class Bullhorn
{
    use \Kiernan\Traits\CreatesSoapVars;

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
     * @param string $wsdl     The Bullhorn WSDL URI you would like to use.
     * @param array  $params   An array of options.
     * @param string $username Username for the Bullhorn API.
     * @param string $password Password for the Bullhorn API.
     * @param string $apiKey   API key for the Bullhorn API.
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
     * @param string        $entityName The name of the entity.
     * @param integer|array $id         The id or ids for which to search.
     * 
     * @return \stdClass
     */
    public function find($entityName, $id)
    {
        // Without checking, you don't know if you get one id or an array of ids
        // from a query. This quick check makes the public api a bit cleaner.
        if (is_array($id)) {
            return $this->findMultiple($entityName, $id);
        }

        $request = [
            'session' => $this->session->session,
            'entityName' => $entityName,
            'id' => $this->soapInt($id)
        ];

        $entity = $this->client->find($request);

        if (! isset($entity->return->dto)) {
            return new stdClass;
        }

        return $entity->return->dto;
    }

    public function findMultiple($entityName, array $ids)
    {
        // Placeholder arrays for our results.
        $results = [];
        $flattenedResults = [];

        // Convert each ID to a SoapVar object.
        $ids = array_map(function($id) {
            return $this->soapInt($id);
        }, $ids);

        // The API only supports 20 IDs at a time.
        $chunks = array_chunk($ids, 20);

        // Send a request for each group of 20 IDs.
        foreach ($chunks as $ids) {
            $request = [
                'session' => $this->session->session,
                'entityName' => $entityName,
                'ids' => $ids
            ];

            $results[] = $this->client->findMultiple($request);
        }

        // Convert the results to a simple nested array.
        $results = array_map(function($chunk) {
            return $chunk->return->dtos;
        }, $results);

        // Flatten the nested array.
        array_walk_recursive($results, function(&$item, $key) use (&$flattenedResults) {
            $flattenedResults[] = $item;
        });

        return $flattenedResults;
    }

    /**
     * Send a query request to the api.
     *
     * @param array $data The query to run.
     * 
     * @return array
     */
    public function query(array $data)
    {
        $request = $this->soapObject(
            [
                'session' => $this->session->session,
                'query' => $this->soapObject(
                    $data,
                    'dtoQuery',
                    'http://query.apiservice.bullhorn.com/'
                )
            ],
            'query',
            'http://query.apiservice.bullhorn.com/'
        );

        $ids = $this->client->query($request);

        if (! $ids->return->ids) {
            return [];
        }

        return $ids->return->ids;
    }
}
