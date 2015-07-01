<?php

namespace Kiernan\Operations;

use Exception;

class Query
{
    use \Kiernan\Traits\CreatesSoapVars;

    /**
     * @var string
     */
    protected static $namespace = 'http://query.apiservice.bullhorn.com/';

    /**
     * Send a query request to the api.
     *
     * @param string $session The api session key.
     * @param array  $data    The query to run.
     * 
     * @return array
     */
    public static function send($session, $data)
    {
        $query = self::soapObject($data, 'dtoQuery', self::$namespace);

        $request = self::soapObject(
            [
            'session' => $session->session(),
            'query' => $query
            ],
            'query',
            self::$namespace
        );

        $results = $session->client()->query($request);

        if (! $results->return->ids) {
            return [];
        }

        return $results->return->ids;
    }
}
