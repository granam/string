<?php
namespace Granam\Tests\String\Exceptions;

use Granam\String\Exceptions\Runtime;

class RuntimeTest extends \PHPUnit_Framework_TestCase {

    /**
     * @test
     * @expectedException \Granam\String\Exceptions\Runtime
     */
    public function can_throw()
    {
        throw new Runtime;
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function origins_at_standard_runtime_exception()
    {
        throw new Runtime;
    }

    /**
     * @test
     * @expectedException \Granam\String\Exceptions\Exception
     */
    public function is_marked_by_local_interface()
    {
        throw new Runtime;
    }

    /**
     * @test
     * @expectedException \Granam\Scalar\Exceptions\Runtime
     */
    public function origins_in_scalar_runtime_exception()
    {
        throw new Runtime;
    }
}
