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
        $specialsReplaced = static::replaceSpecials($value);
        preg_match_all('~(?<words>\w*)(?<nonWords>\W*)~u', $specialsReplaced, $matches);
        $originalLocale = setlocale(LC_CTYPE, 0);
        $withoutDiacritics = '';
        setlocale(LC_CTYPE, 'C.UTF-8');
        $originalErrorReporting = ini_get('error_reporting');
        ini_set('error_reporting', $originalErrorReporting | E_NOTICE);
        /** @noinspection ForeachSourceInspection */
        foreach ($matches['words'] as $index => $word) {
            if (class_exists('Normalizer')) {
                // maps special characters (characters with diacritics) on their base-character followed by the diacritical mark
                // example:  Ú => U´,  á => a`
                $withMovedMark = \Normalizer::normalize($word, \Normalizer::FORM_KD);
                $wordWithoutDiacritics = preg_replace('~\pM~u', '', $withMovedMark); // removes diacritics (moved marks)
            } else {
                $wordWithoutDiacritics = static::removeDiacriticsFallback($word);
            }
            $withoutDiacritics .= $wordWithoutDiacritics . $matches['nonWords'][$index];
        }
        ini_set('error_reporting', $originalErrorReporting);
        setlocale(LC_CTYPE, $originalLocale);

        return $withoutDiacritics;
    }

    /**
     * @param $string
     * @return string
     */
    protected static function replaceSpecials($string)
    {
        return str_replace(
            ['Ð', 'ð', 'Ŀ', 'ŀ', 'Ł', 'ł', 'S̱', 's̱', 'Đ', 'đ', 'ß', 'Ħ', 'ħ', '̱', '̤', '̩', 'Ä', 'ä', 'Æ', 'æ', 'Œ', 'œ',
                'Þ', 'þ', 'Ŧ', 'ŧ', 'ĸ', 'Ə', 'ə', 'I', 'ı', 'Ö', 'ö', 'Ø', 'ø', 'Ñ', 'ñ', 'Ŋ', 'ŋ',
                'Ÿ', 'ÿ', 'Ü', 'ü', 'Ĳ', 'ĳ', 'ʿ', 'ʾ',
            ],
            ['D', 'd', 'L', 'l', 'L', 'l', 'S', 's', 'D', 'd', 'ss', 'H', 'h', '', '', '', 'Ae', 'ae', 'Ae', 'ae', 'Oe', 'oe',
                'T', 't', 'T', 't', 'k', 'E', 'e', 'I', 'i', 'Oe', 'oe', 'O', 'o', 'Ny', 'ny', 'N', 'n',
                'Yu', 'yu', 'Ue', 'ue', 'IJ', 'ij', '’', '’',
            ],
            $string
        );
    }

    /**
     * @param string $word
     * @return string
     */
    protected static function removeDiacriticsFallback($word)
    {
        $wordWithoutDiacritics = @iconv('UTF - 8', 'ASCII//TRANSLIT', $word);
        $lastError = error_get_last();
        if ($lastError && $lastError['file'] === __FILE__
            && $lastError['message'] === 'iconv(): Detected an illegal character in input string'
        ) {
            $wordWithoutDiacritics = '';
            preg_match_all('~\w~u', $word, $letters);
            foreach ($letters[0] as $letter) {
                $convertedLetter = @iconv('UTF-8', 'ASCII//TRANSLIT', $letter);
                if ($convertedLetter === false) {
                    // this error also overwrites previous with iconv(), which is important for previous condition
                    trigger_error(
                        "Could not convert character '{$letter}', using '🤣' instead",
                        E_USER_WARNING // warning level, therefore original error reporting can control it
                    );
                    $convertedLetter = '🤣';
                }
                $wordWithoutDiacritics .= $convertedLetter;
            }
        }

        return $wordWithoutDiacritics;
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