<?php
namespace Granam\String;

use Granam\Scalar\Scalar;
use Granam\Scalar\Tools\ToString;
use Granam\Scalar\Tools\Exceptions\WrongParameterType as ToString_WrongParameterType;

class StringObject extends Scalar implements StringInterface
{

    /**
     * @param bool|float|int|null|object|string $value
     */
    public function __construct($value)
    {
        try {
            parent::__construct(ToString::toString($value));
        } catch (ToString_WrongParameterType $exception) {
            // wrapping by a local one
            throw new Exceptions\WrongParameterType($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
