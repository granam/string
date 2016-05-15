<?php
namespace Granam\Tests\String;

use Granam\String\StringTools;

class StringToolsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @param string $toConstant
     * @param string $asConstant
     * @dataProvider provideValuesForAndLikeConstant
     */
    public function I_can_convert_any_string_to_constant_like_value($toConstant, $asConstant)
    {
        self::assertSame($asConstant, StringTools::toConstant($toConstant));
    }

    public function provideValuesForAndLikeConstant()
    {
        /** For list of all pangrams see great @link http://clagnut.com/blog/2380/ */
        return [
            ['Příliš žluťoučký kůň úpěl ďábelské ódy', 'prilis_zlutoucky_kun_upel_dabelske_ody'], // Czech
            ['Høj bly gom vandt fræk sexquiz på wc', 'hoj_bly_gom_vandt_fraek_sexquiz_pa_wc'], // Danish
            ['Fahrenheit ja Celsius yrjösivät Åsan backgammon-peliin, Volkswagenissa, daiquirin ja ZX81:n yhteisvaikutuksesta', 'fahrenheit_ja_celsius_yrjosivat_asan_backgammon_peliin_volkswagenissa_daiquirin_ja_zx81_n_yhteisvaikutuksesta'], // Finnish
            ['Voix ambiguë d’un cœur qui au zéphyr préfère les jattes de kiwi', 'voix_ambigue_d_un_cceur_qui_au_zephyr_prefere_les_jattes_de_kiwi'], // French
        ];
    }

    /**
     * @test
     * @dataProvider provideValueToSnakeCase
     * @param string $toConvert
     * @param string $expectedResult
     */
    public function I_can_turn_to_snake_case_anything($toConvert, $expectedResult)
    {
        self::assertSame($expectedResult, StringTools::camelToSnakeCaseBasename($toConvert));
    }

    public function provideValueToSnakeCase()
    {
        return [
            [__CLASS__, 'string_tools_test'],
            [__FUNCTION__, 'provide_value_to_snake_case'],
            ['IHave_CombinationsFOO', 'i_have_combinations_f_o_o'],
            ['.,*#@azAZ  O_K...  & K.O.', '.,*#@az_a_z_  _o_k_...  & _k_._o_.'], // the function is not for a constant name
            ['.,*#@ ...  &', '.,*#@ ...  &'],
        ];
    }

    /**
     * @test
     * @dataProvider provideValueNameAndGetter
     * @param string $valueName
     * @param string $expectedGetter
     * @param string|null $prefix
     */
    public function I_can_get_getter_for_any_name($valueName, $expectedGetter, $prefix = null)
    {
        if ($prefix === null) {
            self::assertSame($expectedGetter, StringTools::assembleGetterForName($valueName));
        } else {
            self::assertSame($expectedGetter, StringTools::assembleGetterForName($valueName, $prefix));
        }
    }

    public function provideValueNameAndGetter()
    {
        return [
            [__CLASS__, 'getStringToolsTest'],
            ["\n\t Dřípatka\\horská ?", 'getHorska'],
            ['small-ukulele', 'isSmallUkulele', 'is']
        ];
    }

    /**
     * @test
     * @dataProvider provideValueNameAndSetter
     * @param string $valueName
     * @param string $expectedSetter
     * @param string|null $prefix
     */
    public function I_can_get_setter_for_any_name($valueName, $expectedSetter, $prefix = null)
    {
        if ($prefix === null) {
            self::assertSame($expectedSetter, StringTools::assembleSetterForName($valueName));
        } else {
            self::assertSame($expectedSetter, StringTools::assembleSetterForName($valueName, $prefix));
        }
    }

    public function provideValueNameAndSetter()
    {
        return [
            [__CLASS__, 'setStringToolsTest'],
            ["\n\t Dřípatka\\horská ?", 'setHorska'],
            ['small-ukulele', 'reserveSmallUkulele', 'reserve']
        ];
    }

    /**
     * @test
     */
    public function I_can_create_any_method_name()
    {
        self::assertSame('stringToolsTest', StringTools::assembleMethodName(__CLASS__));
        self::assertSame('fooStringToolsTest', StringTools::assembleMethodName(__CLASS__, 'foo'));
    }

    /**
     * @test
     */
    public function I_can_strip_BOM_from_utf8_string()
    {
        $utf8String = mb_convert_encoding('Příliš', 'UTF-8');
        self::assertSame(
            $utf8String,
            StringTools::stripUtf8Bom("\xEF\xBB\xBF{$utf8String}")
        );
        self::assertSame(
            $utf8String,
            StringTools::stripUtf8Bom($utf8String)
        );
    }
}
