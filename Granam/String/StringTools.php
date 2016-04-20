<?php
namespace Granam\String;

use Granam\Scalar\Tools\ToString;
use Granam\Strict\Object\StrictObject;

class StringTools extends StrictObject
{
    public static function toConstant($value)
    {
        $value = ToString::toString($value);
        $trimmed = trim($value);
        $specialsReplaced = self::replaceSpecials($trimmed);
        $nonCharactersReplaced = preg_replace('~\W+~u', '_', $specialsReplaced);
        $originalLocale = setlocale(LC_CTYPE, 0);
        setlocale(LC_CTYPE, 'C.UTF-8');
        $withoutDiacritics = iconv('UTF-8', 'ASCII//TRANSLIT', $nonCharactersReplaced);
        setlocale(LC_CTYPE, $originalLocale);
        $underscored = preg_replace('~[^a-zA-Z0-9]+~', '_', $withoutDiacritics);

        return strtolower($underscored);
    }

    protected static function replaceSpecials($string)
    {
        return str_replace(['ø', 'æ', 'œ'], ['o', 'ae', 'ce'], $string);
    }

    /**
     * @param string $className
     * @return string
     */
    public static function camelToSnakeCaseBasename($className)
    {
        if (preg_match('~[\\\]?(?<basename>\w+)[^\w_-]*$~u', $className, $matches) === 0) {
            return $className;
        }
        $baseName = $matches['basename'];
        $parts = preg_split('~([A-Z][a-z_]*)~', $baseName, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        $underscored = preg_replace('~_{2,}~', '_', implode('_', $parts));

        return strtolower($underscored);
    }

    public static function assembleGetterForName($valueName)
    {
        return 'get' . implode(
            array_map(
                function ($namePart) {
                    return ucfirst($namePart);
                },
                explode('_', self::toConstant(self::camelToSnakeCaseBasename($valueName)))
            )
        );
    }

    /**
     * This method originates from Dropbox,
     * @link http://dropbox.github.io/dropbox-sdk-php/api-docs/v1.1.x/source-class-Dropbox.Util.html#14-32
     *
     * If the given string begins with the UTF-8 BOM (byte order mark), remove it and
     * return whatever is left. Otherwise, return the original string untouched.
     *
     * Though it's not recommended for UTF-8 to have a BOM, the standard allows it to
     * support software that isn't Unicode-aware.
     *
     * @param string $string
     *    A UTF-8 encoded string.
     *
     * @return string
     */
    public static function stripUtf8Bom($string)
    {
        if (\substr_compare($string, "\xEF\xBB\xBF", 0, 3) === 0) {
            $string = \substr($string, 3);
        }

        return $string;
    }
}