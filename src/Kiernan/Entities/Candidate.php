<?php

namespace Kiernan\Entities;

use SoapVar;
use stdClass;

class Candidate
{
    use \Kiernan\Traits\CreatesSoapVars;

    /**
     * Find a candidate by their id.
     * 
     * @param Kiernan\SoapSession $session An encrypted authorization key.
     * @param integer             $id      The candidate's id.
     * 
     * @return stdClass
     */
    public static function findById($session, $id)
    {
        $id = self::soapInt($id);

        $request = [
            'session' => $session->session(),
            'entityName' => 'Candidate',
            'id' => $id,
        ];

        $candidate = $session->client()->find($request);

        if (! isset($candidate->return->dto)) {
            return new stdClass;
        }

        return $candidate->return->dto;
    }
}
