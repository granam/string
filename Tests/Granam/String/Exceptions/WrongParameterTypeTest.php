<?php
namespace Granam\Tests\String\Exceptions;

use Granam\String\Exceptions\WrongParameterType;

class WrongParameterTypeTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     * @expectedException \Granam\String\Exceptions\WrongParameterType
     */
    public function Can_be_thrown()
    {
        throw new WrongParameterType;
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function Origins_in_standard_runtime_exception()
    {
        throw new WrongParameterType;
    }

    /**
     * @test
     * @expectedException \Granam\String\Exceptions\Runtime
     */
    public function Is_marked_by_local_interface()
    {
        throw new WrongParameterType;
    }

    /**
     * @test
     * @expectedException \Granam\Scalar\Exceptions\Runtime
     */
    public function origins_in_scalar_runtime_exception()
    {
        throw new WrongParameterType;
    }

}
