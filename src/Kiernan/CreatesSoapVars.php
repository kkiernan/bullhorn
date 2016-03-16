<?php

namespace Kiernan;

use SoapVar;

trait CreatesSoapVars
{
    /**
     * A basic wrapper around PHP's SoapVar function that creates a SoapVar object
     * with the SOAP_ENC_OBJECT encoding, int and namespaces pre-set.
     *
     * @param int $int
     *
     * @return SoapVar
     */
    public function soapInt($int)
    {
        return new SoapVar(
            $int,
            XSD_INTEGER,
            'int',
            'http://www.w3.org/2001/XMLSchema'
        );
    }

    /**
     * A basic wrapper around PHP's SoapVar function that creates a SoapVar object
     * with the SOAP_ENC_OBJECT encoding set.
     *
     * @param array  $data
     * @param string $type
     * @param string $namespace
     *
     * @return SoapVar
     */
    public function soapObject(array $data, $type, $namespace)
    {
        return new SoapVar(
            $data,
            SOAP_ENC_OBJECT,
            $type,
            $namespace
        );
    }
}
