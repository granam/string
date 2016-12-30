<?php
namespace Granam\String;

use Granam\Scalar\Tools\ToString;
use Granam\Strict\Object\StrictObject;

class StringTools extends StrictObject
{

    /**
     * @param string|StringInterface $value
     * @return string $withoutDiacritics
     */
    public static function removeDiacritics($value)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $value = ToString::toString($value);
        $specialsReplaced = self::flattenSpecials($value);
        preg_match_all('~(?<words>\w*)(?<nonWords>\W*)~u', $specialsReplaced, $matches);
        $originalLocale = setlocale(LC_CTYPE, 0);
        $withoutDiacritics = '';
        setlocale(LC_CTYPE, 'C.UTF-8');
        /** @noinspection ForeachSourceInspection */
        foreach ($matches['words'] as $index => $word) {
            $withoutDiacritics .= iconv('UTF-8', 'ASCII//TRANSLIT', $word) . $matches['nonWords'][$index];
        }
        setlocale(LC_CTYPE, $originalLocale);

        return $withoutDiacritics;
    }

    /**
     * @param string|StringInterface $value
     * @return string
     */
    public static function toConstant($value)
    {
        $withoutDiacritics = self::removeDiacritics($value);
        $trimmed = trim($withoutDiacritics);
        $nonCharactersReplaced = preg_replace('~\W+~u', '_', $trimmed);
        $underscored = preg_replace('~[^a-zA-Z0-9]+~', '_', $nonCharactersReplaced);

        return strtolower($underscored);
    }

    /**
     * @param $string
     * @return string
     */
    protected static function flattenSpecials($string)
    {
        return str_replace(['ø', 'æ', 'œ'], ['o', 'ae', 'ce'], $string);
    }

    /**
     * @param string|StringInterface $className
     * @return string
     */
    public static function camelCaseToSnakeCasedBasename($className)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $className = ToString::toString($className);
        $baseName = $className;
        if (preg_match('~(?<basename>[^\\\]+)$~u', $className, $matches) > 0) {
            $baseName = $matches['basename'];
        }
        $parts = preg_split('~([A-Z][a-z_]*)~', $baseName, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        $underscored = preg_replace('~_{2,}~', '_', implode('_', $parts));

        return strtolower($underscored);
    }

    /**
     * @param string|StringInterface $valueName
     * @param string|StringInterface $prefix
     * @return string
     */
    public static function assembleGetterForName($valueName, $prefix = 'get')
    {
        return self::assembleMethodName($valueName, $prefix);
    }

    /**
     * @param string|StringInterface $valueName
     * @param string|StringInterface $prefix
     * @return string
     */
    public static function assembleSetterForName($valueName, $prefix = 'set')
    {
        return self::assembleMethodName($valueName, $prefix);
    }

    /**
     * @param string|StringInterface $fromValue
     * @param string|StringInterface $prefix
     * @return string
     */
    public static function assembleMethodName($fromValue, $prefix = '')
    {
        $methodName = implode(
            array_map(
                function ($namePart) {
                    return ucfirst($namePart);
                },
                explode('_', self::toConstant(self::camelCaseToSnakeCasedBasename($fromValue)))
            )
        );
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $prefix = ToString::toString($prefix);
        if ($prefix === '') {
            return lcfirst($methodName);
        }

        return $prefix . $methodName;
    }

    /**
     * This method originates from Dropbox,
     *
     * @link http://dropbox.github.io/dropbox-sdk-php/api-docs/v1.1.x/source-class-Dropbox.Util.html#14-32
     *
     * If the given string begins with the UTF-8 BOM (byte order mark), remove it and
     * return whatever is left. Otherwise, return the original string untouched.
     *
     * Though it's not recommended for UTF-8 to have a BOM, the standard allows it to
     * support software that isn't Unicode-aware.
     *
     * @param string|StringInterface $string an UTF-8 encoded string
     * @return string
     */
    public static function stripUtf8Bom($string)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $string = ToString::toString($string);
        if (\substr_compare($string, "\xEF\xBB\xBF", 0, 3) === 0) {
            $string = \substr($string, 3);
        }

        return $string;
    }
}