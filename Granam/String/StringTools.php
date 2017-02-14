<?php
namespace Granam\String;

use Granam\Scalar\Tools\ToString;

class StringTools
{

    /**
     * @param string|StringInterface $value
     * @return string $withoutDiacritics
     */
    public static function removeDiacritics($value)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $value = ToString::toString($value);
        $withoutDiacritics = '';
        if (function_exists('transliterator_transliterate')) {
            $specialsReplaced = static::replaceSpecials($value);
            preg_match_all('~(?<words>\w*)(?<nonWords>\W*)~u', $specialsReplaced, $matches);
            /** @noinspection ForeachSourceInspection */
            foreach ($matches['words'] as $index => $word) {
                $wordWithoutDiacritics = \transliterator_transliterate('Any-Latin; Latin-ASCII', $word);
                $withoutDiacritics .= $wordWithoutDiacritics . $matches['nonWords'][$index];
            }
        } else {
            $specialsReplaced = static::replaceSpecials($value);
            $withoutDiacritics = static::removeDiacriticsFallback($specialsReplaced);
        }

        return $withoutDiacritics;
    }

    /**
     * @param $string
     * @return string
     */
    protected static function replaceSpecials($string)
    {
        return str_replace(
            ['̱', '̤', '̩', 'Ə', 'ə', 'ʿ', 'ʾ', 'ʼ',],
            ['', '', '', 'E', 'e', "'", "'", "'",],
            $string
        );
    }

    /**
     * @param string $word
     * @return string
     */
    protected static function removeDiacriticsFallback($word)
    {
        $originalErrorReporting = ini_get('error_reporting');
        ini_set('error_reporting', $originalErrorReporting | E_NOTICE);
        $originalLocale = setlocale(LC_CTYPE, 0);
        setlocale(LC_CTYPE, 'C.UTF-8');
        $wordWithoutDiacritics = @iconv('UTF - 8', 'ASCII//TRANSLIT', $word); // cause a notice if a problem occurs
        $lastError = error_get_last();
        if ($lastError && $lastError['file'] === __FILE__
            && $lastError['message'] === 'iconv(): Detected an illegal character in input string'
        ) {
            $wordWithoutDiacritics = '';
            preg_match_all('~\w~u', $word, $letters);
            /** @noinspection ForeachSourceInspection */
            foreach ($letters[0] as $letter) {
                $convertedLetter = @iconv('UTF-8', 'ASCII//TRANSLIT', $letter); // cause a notice if a problem occurs
                if ($convertedLetter === false) {
                    // this error also overwrites previous with iconv(), which is important for previous condition
                    trigger_error(
                        "Could not convert character '{$letter}', using '?' instead",
                        E_USER_WARNING // warning level, therefore original error reporting can control it
                    );
                    $convertedLetter = '?';
                }
                $wordWithoutDiacritics .= $convertedLetter;
            }
        }
        setlocale(LC_CTYPE, $originalLocale);
        ini_set('error_reporting', $originalErrorReporting);

        return $wordWithoutDiacritics;
    }

    /**
     * @param $string
     * @return string
     */
    protected static function replaceSpecialsFallback($string)
    {
        return preg_replace(
            ['Æ', 'æ', 'Œ', 'œ', 'Ð', 'ð', 'Ŀ', 'ŀ', 'Ł', 'ł', 'S̱', 's̱', 'Đ', 'đ', 'ß', 'Ħ', 'ħ', 'Ä', 'ä',
                'Þ', 'þ', 'Ŧ', 'ŧ', 'ĸ', 'I', 'ı', 'Ö', 'ö', 'Ø', 'ø', 'Ñ', 'ñ', 'Ŋ', 'ŋ',
                'Ÿ', 'ÿ', 'Ü', 'ü', 'Ĳ', 'ĳ',
            ],
            ['Ae', 'ae', 'Oe', 'oe', 'D', 'd', 'L', 'l', 'L', 'l', 'S', 's', 'D', 'd', 'ss', 'H', 'h', 'A', 'a',
                'TH', 'th', 'T', 't', 'q', 'I', 'i', 'O', 'o', 'O', 'o', 'N', 'n', 'N', 'n',
                'Yu', 'yu', 'U', 'u', 'IJ', 'ij',
            ],
            self::replaceSpecials(self::replaceSpecials($string))
        );
    }

    /**
     * @param string|StringInterface $value
     * @return string
     */
    public static function toConstant($value)
    {
        $withoutDiacritics = self::removeDiacritics($value);
        $underscored = preg_replace('~[^a-zA-Z0-9]+~', '_', trim($withoutDiacritics));

        return trim(strtolower($underscored), '_');
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