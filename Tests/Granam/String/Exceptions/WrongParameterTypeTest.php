<?php
namespace Granam\Tests\String\Exceptions;

use Granam\String\Exceptions\WrongParameterType;

class WrongParameterTypeTest extends RuntimeTest {

    /**
     * @test
     * @expectedException \Granam\String\Exceptions\WrongParameterType
     */
    public function has_name_as_expected()
    {
        throw new WrongParameterType;
    }

    /**
     * @test
     * @expectedException \Granam\String\Exceptions\Runtime
     */
    public function is_local_runtime_exception()
    {
        throw new WrongParameterType;
    }
}
