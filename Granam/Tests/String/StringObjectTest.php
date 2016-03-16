<?php
namespace Granam\Tests\String;

use Granam\Scalar\Scalar;
use Granam\String\StringObject;

class StringObjectTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function can_create_instance()
    {
        $instance = new StringObject('foo');
        self::assertNotNull($instance);
    }

    /** @test */
    public function is_a_scalar()
    {
        $instance = new StringObject('foo');
        self::assertInstanceOf(Scalar::getClass(), $instance);
    }

    /**
     * @test
     */
    public function I_can_create_string_object_from_most_of_types()
    {
        $withInteger = new StringObject($integer = 1);
        self::assertSame((string)$integer, $withInteger->getValue());
        self::assertSame((string)$integer, (string)$withInteger);

        $withFloat = new StringObject($float = 1.1);
        self::assertSame((string)$float, $withFloat->getValue());
        self::assertSame((string)$float, (string)$withFloat);

        $withFalse = new StringObject($false = false);
        self::assertSame((string)$false, $withFalse->getValue());
        self::assertSame((string)$false, (string)$withFalse);
        self::assertSame('', (string)$withFalse);

        $withTrue = new StringObject($true = true);
        self::assertSame((string)$true, $withTrue->getValue());
        self::assertSame((string)$true, (string)$withTrue);
        self::assertSame('1', (string)$withTrue);

        $withNull = new StringObject($null = null);
        self::assertSame((string)$null, $withNull->getValue());
        self::assertSame((string)$null, (string)$withNull);
        self::assertSame('', (string)$withNull);

        $strictString = new StringObject(new WithToString($string = 'foo'));
        self::assertSame($string, $strictString->getValue());
        self::assertSame($string, (string)$strictString);
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
        self::assertTrue($emptyString->isEmpty());

        $filledString = new StringObject('some string');
        self::assertFalse($filledString->isEmpty());
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
