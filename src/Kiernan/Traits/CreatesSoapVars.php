<?php

namespace Kiernan\Traits;

use SoapVar;

trait CreatesSoapVars
{
    /**
     * A basic wrapper around PHP's SoapVar function that creates a SoapVar object
     * with the SOAP_ENC_OBJECT encoding, int and namespaces pre-set.
     * 
     * @param int $int The integer to pass into the SoapVar object.
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
     * @param array  $data      The data to pass into the SoapVar object.
     * @param string $type      The type.
     * @param string $namespace The type namespace.
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
