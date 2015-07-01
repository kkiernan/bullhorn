<?php

namespace Kiernan;

use SoapClient;
use stdClass;

class SoapSession implements Session
{
    /**
     * @var SoapClient
     */
    protected $client;

    /**
     * @var string
     */
    protected $session;

    /**
     * @var integer
     */
    protected $corporationId;

    /**
     * @var integer
     */
    protected $userId;

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

        $result = $this->client->startSession($config);

        $this->session = $result->return->session;
        $this->corporationId = $result->return->corporationId;
        $this->userId = $result->return->userId;
    }

    /**
     * Getter for the client property.
     * 
     * @return SoapClient
     */
    public function client()
    {
        return $this->client;
    }

    /**
     * Getter for the session property.
     * 
     * @return string
     */
    public function session()
    {
        return $this->session;
    }
}
