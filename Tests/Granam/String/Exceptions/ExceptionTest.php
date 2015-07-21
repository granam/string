<?php
namespace Granam\Tests\String\Exceptions;

class ExceptionTest extends \PHPUnit_Framework_TestCase
{

    /** @test */
    public function exception_interface_exists()
    {
        $this->assertTrue(interface_exists('Granam\String\Exceptions\Exception'));
    }

    /** @test */
    public function origins_in_scalar_exception()
    {
        $reflection = new \ReflectionClass('Granam\String\Exceptions\Exception');
        $this->assertTrue($reflection->isSubclassOf('Granam\Scalar\Exceptions\Exception'));
    }
}
