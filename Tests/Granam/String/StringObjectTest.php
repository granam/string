<?php
namespace Granam\Tests\Strict\String;

use Granam\Scalar\Scalar;
use Granam\String\StringObject;

class StringObjectTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function can_create_instance()
    {
        $instance = new StringObject('foo');
        $this->assertNotNull($instance);
    }

    /** @test */
    public function is_a_scalar()
    {
        $instance = new StringObject('foo');
        $this->assertInstanceOf(Scalar::getClass(), $instance);
    }

    /**
     * @test
     */
    public function I_can_create_string_object_from_most_of_types()
    {
        $withInteger = new StringObject($integer = 1);
        $this->assertSame((string)$integer, $withInteger->getValue());
        $this->assertSame((string)$integer, (string)$withInteger);

        $withFloat = new StringObject($float = 1.1);
        $this->assertSame((string)$float, $withFloat->getValue());
        $this->assertSame((string)$float, (string)$withFloat);

        $withFalse = new StringObject($false = false);
        $this->assertSame((string)$false, $withFalse->getValue());
        $this->assertSame((string)$false, (string)$withFalse);
        $this->assertSame('', (string)$withFalse);

        $withTrue = new StringObject($true = true);
        $this->assertSame((string)$true, $withTrue->getValue());
        $this->assertSame((string)$true, (string)$withTrue);
        $this->assertSame('1', (string)$withTrue);

        $withNull = new StringObject($null = null);
        $this->assertSame((string)$null, $withNull->getValue());
        $this->assertSame((string)$null, (string)$withNull);
        $this->assertSame('', (string)$withNull);

        $strictString = new StringObject(new WithToString($string = 'foo'));
        $this->assertSame($string, $strictString->getValue());
        $this->assertSame($string, (string)$strictString);
    }

    /**
     * @test
     * @expectedException \Granam\String\Exceptions\WrongParameterType
     */
    public function I_can_not_create_string_object_from_array()
    {
        new StringObject([]);
    }

    /**
     * @test
     * @expectedException \Granam\String\Exceptions\WrongParameterType
     */
    public function I_can_not_create_string_object_from_resource()
    {
        new StringObject(tmpfile());
    }

    /**
     * @test
     * @expectedException \Granam\String\Exceptions\WrongParameterType
     */
    public function I_can_not_create_string_object_from_object()
    {
        new StringObject(new \stdClass());
    }

    /**
     * @test
     */
    public function I_can_ask_it_if_it_is_empty()
    {
        $emptyString = new StringObject('');
        $this->assertTrue($emptyString->isEmpty());

        $filledString = new StringObject('some string');
        $this->assertFalse($filledString->isEmpty());
    }
}

/** inner */
class WithToString
{
    private $value;

    public function __construct($value)
    {
        $this->value = (string)$value;
    }

    public function __toString()
    {
        return $this->value;
    }
}
