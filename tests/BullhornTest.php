<?php

use Kiernan\Bullhorn;

class BullhornTest extends PHPUnit_Framework_TestCase
{
    protected $bullhorn;

    protected function setup()
    {
        $this->bullhorn = new Bullhorn(
            'https://api.bullhornstaffing.com/webservices-2.5/?wsdl',
            ['soap_version' => SOAP_1_1, 'trace' => 1],
            getenv('BULLHORN_USERNAME'),
            getenv('BULLHORN_PASSWORD'),
            getenv('BULLHORN_API_KEY')
        );
    }

    public function testCanCreateSoapVarInteger()
    {
        // $this->markTestSkipped();

        $integer = 100;

        $soapVar = $this->bullhorn->soapInt($integer);

        $this->assertInstanceOf('SoapVar', $soapVar);
        $this->assertEquals('int', $soapVar->enc_stype);
        $this->assertEquals($integer, $soapVar->enc_value);
        $this->assertEquals('http://www.w3.org/2001/XMLSchema', $soapVar->enc_ns);
    }

    public function testCanCreateSoapVarObject()
    {
        // $this->markTestSkipped();

        $data = [
            'foo' => 'MyFoo',
            'bar' => [],
            'baz' => 1234
        ];

        $type = 'someType';

        $namespace = 'http://some.namespace.com';

        $soapVar = $this->bullhorn->soapObject($data, $type, $namespace);

        $this->assertInstanceOf('SoapVar', $soapVar);
        $this->assertEquals($type, $soapVar->enc_stype);
        $this->assertEquals($data, $soapVar->enc_value);
        $this->assertEquals($namespace, $soapVar->enc_ns);
    }

    public function testCanFindSingleCandidate()
    {
        // $this->markTestSkipped('Replace the candidate id and assertion values with a known candidate to run this test.');

        $kelly = $this->bullhorn->find('Candidate', 294415);

        $this->assertInstanceOf('stdClass', $kelly);
        $this->assertEquals('Kelly Kiernan', $kelly->name);
        $this->assertEquals('kelly@kellykiernan.com', $kelly->email);
    }

    public function testCanFindMultipleCandidates()
    {
        // $this->markTestSkipped('Replace the candidate id and assertion values with a known candidate to run this test.');

        $candidates = $this->bullhorn->findMultiple('Candidate', [294415, 294415]);

        $this->assertInstanceOf('stdClass', $candidates[0]);
        $this->assertInstanceOf('stdClass', $candidates[1]);
        $this->assertEquals('Kelly Kiernan', $candidates[0]->name);
        $this->assertEquals('Kelly Kiernan', $candidates[1]->name);
        $this->assertEquals('kelly@kellykiernan.com', $candidates[0]->email);
        $this->assertEquals('kelly@kellykiernan.com', $candidates[1]->email);
    }
}
