<?php
require __DIR__.'/../../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
require __DIR__.'/../../Vkim.class.php';
class RoutingTest extends TestCase
{
    private $routing;

    protected function setUp()
    {
        $this->routing = new Routing();
    }

    protected function tearDown()
    {
        $this->routing = NULL;
    }

    public function testIsSuccessResponse()
    {
        // $response1 = new Routing();
        // $this->assertEquals(false, $this->vkim->isSuccessResponse($response1));
        // $response2 = new stdClass();
        // $response2->error = 'Hello';
        // $this->assertEquals(false, $this->vkim->isSuccessResponse($response2));
    }
}