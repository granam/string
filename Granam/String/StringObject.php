<?php
namespace Granam\String;

use Granam\Scalar\Scalar;
use Granam\Scalar\Tools\ToString;

class StringObject extends Scalar implements StringInterface
{

    /**
     * @param bool|float|int|null|object|string $value
     */
    public function __construct($value)
    {
        try {
            parent::__construct($value);
        } catch (\Granam\Scalar\Tools\Exceptions\WrongParameterType $exception) {
            // wrapping by a local one
            throw new Exceptions\WrongParameterType($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * @param mixed $value
     *
     * @return string
     */
    protected function castValue($value)
    {
        if (is_string($value)) {
            return $value;
        }

        try {
            return ToString::toString($value);
        } catch (\Granam\Scalar\Tools\Exceptions\WrongParameterType $exception) {
            // wrapping exception by local one
            throw new Exceptions\WrongParameterType($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

}
