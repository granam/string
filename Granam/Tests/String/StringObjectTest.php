<?php
declare(strict_types=1);

namespace Granam\Tests\String;

use Granam\Scalar\Scalar;
use Granam\String\StringObject;
use PHPUnit\Framework\TestCase;

class StringObjectTest extends TestCase
{

    /**
     * @test
     */
    public function I_can_use_it_as_scalar_object(): void
    {
        $stringObject = new StringObject('foo');
        self::assertInstanceOf(Scalar::class, $stringObject);
    }

    /**
     * @test
     */
    public function I_can_create_string_object_from_most_of_types(): void
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

        $strictString = new StringObject(new WithToString($string = 'foo'));
        self::assertSame($string, $strictString->getValue());
        self::assertSame($string, (string)$strictString);
    }

    /**
     * @test
     */
    public function I_get_empty_string_from_null_if_not_strict(): void
    {
        $withNull = new StringObject(null, false /* not strict */);
        self::assertSame((string)null, $withNull->getValue());
        self::assertSame((string)null, (string)$withNull);
        self::assertSame('', (string)$withNull);
    }

    /**
     * @test
     * @expectedException \Granam\String\Exceptions\WrongParameterType
     * @expectedExceptionMessageRegExp ~got NULL$~
     */
    public function I_can_not_use_null_by_default(): void
    {
        new StringObject(null);
    }

    /**
     * @test
     * @expectedException \Granam\String\Exceptions\WrongParameterType
     * @expectedExceptionMessageRegExp ~got NULL$~
     */
    public function I_can_not_use_null_if_strict(): void
    {
        new StringObject(null, true /* strict*/);
    }

    /**
     * @test
     * @expectedException \Granam\String\Exceptions\WrongParameterType
     */
    public function I_can_not_create_string_object_from_array(): void
    {
        /** @noinspection PhpParamsInspection */
        new StringObject([]);
    }

    /**
     * @test
     * @expectedException \Granam\String\Exceptions\WrongParameterType
     */
    public function I_can_not_create_string_object_from_resource(): void
    {
        new StringObject(tmpfile());
    }

    /**
     * @test
     * @expectedException \Granam\String\Exceptions\WrongParameterType
     */
    public function I_can_not_create_string_object_from_object(): void
    {
        new StringObject(new \stdClass());
    }

    /**
     * @test
     */
    public function I_can_ask_it_if_it_is_empty(): void
    {
        $emptyString = new StringObject('');
        self::assertTrue($emptyString->isEmpty());

        $filledString = new StringObject('some string');
        self::assertFalse($filledString->isEmpty());

        $withWhiteCharacters = new StringObject("\t\n\r\r\t  \t");
        self::assertFalse($withWhiteCharacters->isEmpty());
        self::assertTrue($withWhiteCharacters->isEmpty(true /* trim */));
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