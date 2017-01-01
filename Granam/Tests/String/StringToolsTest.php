<?php
namespace Granam\Tests\String;

use Granam\String\StringTools;

class StringToolsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider provideValuesToRemoveDiacritics
     * @param string $value
     * @param string $expectedResult
     */
    public function I_can_remove_diacritics_from_any_string($value, $expectedResult)
    {
        self::assertSame($expectedResult, StringTools::removeDiacritics($value));
    }

    public function provideValuesToRemoveDiacritics()
    {
        /** For list of all pangrams see great @link http://clagnut.com/blog/2380/ */
        return [
            [ // Arabic
                'naṣun ḥakymun lahu syrun qāṭiʿun wa ḏu šānin ʿẓymin maktubun ʿala ṯubin aẖḍra wa muġalafun biǧildin azraq',
                'nasun hakymun lahu syrun qati’un wa du sanin ’zymin maktubun ’ala tubin ahdra wa mugalafun bigildin azraq',
            ],
            [ // Azeri
                'Zəfər, jaketini də papağını da götür, bu axşam hava çox soyuq olacaq.',
                'Zefer, jaketini de papagini da gotur, bu axsam hava cox soyuq olacaq.',
            ],
            [ // Breton
                'Yec’hed mat Jakez ! Skarzhit ar gwerennoù-mañ, kavet e vo gwin betek fin ho puhez.',
                'Yec’hed mat Jakez ! Skarzhit ar gwerennou-man, kavet e vo gwin betek fin ho puhez.',
            ],
            [ // Catalan
                'Jove xef, porti whisky amb quinze glaçons d’hidrogen, coi!',
                'Jove xef, porti whisky amb quinze glacons d’hidrogen, coi!',
            ],
            [ // Croatian
                'Gojazni đačić s biciklom drži hmelj i finu vatu u džepu nošnje.',
                'Gojazni dacic s biciklom drzi hmelj i finu vatu u dzepu nosnje.',
            ],
            ['Høj bly gom vandt fræk sexquiz på wc', 'Hoj bly gom vandt fraek sexquiz pa wc'], // Danish
            ['Eble ĉiu kvazaŭ-deca fuŝĥoraĵo ĝojigos homtipon.', 'Eble ciu kvazau-deca fushorajo gojigos homtipon.'], // Esperanto
            [ // Estonian
                'Põdur Zagrebi tšellomängija-följetonist Ciqo külmetas kehvas garaažis',
                'Podur Zagrebi tsellomangija-foljetonist Ciqo kulmetas kehvas garaazis',
            ],
            [/** well, this is english, @link http://clagnut.com/blog/2380/#Perfect_pangrams_in_English_.2826_letters.29 */
                'Zing, dwarf jocks vex lymph, Qutb.',
                'Zing, dwarf jocks vex lymph, Qutb.',
            ],

            ['Příliš žluťoučký kůň úpěl ďábelské ódy', 'Prilis zlutoucky kun upel dabelske ody'], // Czech
            [ // Finnish
                'Fahrenheit ja Celsius yrjösivät Åsan backgammon-peliin, Volkswagenissa, daiquirin ja ZX81:n yhteisvaikutuksesta',
                'Fahrenheit ja Celsius yrjosivat Asan backgammon-peliin, Volkswagenissa, daiquirin ja ZX81:n yhteisvaikutuksesta',
            ],
            [ // Finnish
                'Törkylempijävongahdus',
                'Torkylempijavongahdus',
            ],
            [ // French
                'Voix ambiguë d’un cœur qui au zéphyr préfère les jattes de kiwi',
                'Voix ambigue d’un cceur qui au zephyr prefere les jattes de kiwi',
            ],
            [ // German
                'Falsches Üben von Xylophonmusik quält jeden größeren Zwerg',
                'Falsches Uben von Xylophonmusik qualt jeden grosseren Zwerg',
            ],
            [ // Hungarian
                'Jó foxim és don Quijote húszwattos lámpánál ülve egy paár bűvös cipőt készít.',
                'Jo foxim es don Quijote huszwattos lampanal ulve egy paar buvos cipot keszit.',
            ],
            [ // Icelandic
                'Kæmi ný öxi hér, ykist þjófum nú bæði víl og ádrepa.',
                'Kaemi ny oxi her, ykist bjofum nu baedi vil og adrepa.',
            ],
            [ // Igbo
                'Nne, nna, wepụ he’l’ụjọ dum n’ime ọzụzụ ụmụ, vufesi obi nye Chukwu, ṅụrịanụ, gbakọọnụ kpaa, kwee ya ka o guzoshie ike; ọ ghaghị ito, nwapụta ezi agwa.',
                'Nne, nna, wepu he’l’ujo dum n’ime ozuzu umu, vufesi obi nye Chukwu, nurianu, gbakoonu kpaa, kwee ya ka o guzoshie ike; o ghaghi ito, nwaputa ezi agwa.',
            ],
            [ // Irish
                'Ċuaiġ bé ṁórṡáċ le dlúṫspád fíorḟinn trí hata mo ḋea-ṗorcáin ḃig',
                'Cuaig be morsac le dlutspad fiorfinn tri hata mo dea-porcain big',
            ],
            [ // Latvian
                'Muļķa hipiji mēģina brīvi nogaršot celofāna žņaudzējčūsku.',
                'Mulka hipiji megina brivi nogarsot celofana znaudzejcusku.',
            ],
            [ // Lithuanian
                'Įlinkdama fechtuotojo špaga sublykčiojusi pragręžė apvalų arbūzą',
                'Ilinkdama fechtuotojo spaga sublykciojusi pragreze apvalu arbuza',
            ],
            [ // Lojban
                '.o’i mu xagji sofybakni cu zvati le purdi',
                '.o’i mu xagji sofybakni cu zvati le purdi',
            ],
            [ // Mapudungun
                'Ngütram minchetu apochiküyeṉ: ñidol che mamüll ka rag kushe ḻafkeṉ mew.',
                'Ngutram minchetu apochikuyen: nidol che mamull ka rag kushe lafken mew.',
            ],
            [ // Norwegian
                'Vår sære Zulu fra badeøya spilte jo whist og quickstep i min taxi.',
                'Var saere Zulu fra badeoya spilte jo whist og quickstep i min taxi.',
            ],
            [ // Polish
                'Jeżu klątw, spłódź Finom część gry hańb!',
                'Jezu klatw, splodz Finom czesc gry hanb!',
            ],
            [ // Portuguese
                'Luís argüia à Júlia que «brações, fé, chá, óxido, pôr, zângão» eram palavras do português.',
                'Luis arguia a Julia que «bracoes, fe, cha, oxido, por, zangao» eram palavras do portugues.',
            ],
            [ // Romanian
                'Muzicologă în bej vând whisky și tequila, preț fix.',
                'Muzicologa in bej vand whisky si tequila, pret fix.',
            ],
            [ // Scottish Galeic
                'Mus d’fhàg Cèit-Ùna ròp Ì le ob.',
                'Mus d’fhag Ceit-Una rop I le ob.',
            ],
            [ // Serbian
                'Ljubazni fenjerdžija čađavog lica hoće da mi pokaže štos.',
                'Ljubazni fenjerdzija cadavog lica hoce da mi pokaze stos.',
            ],
            [ // Slovak
                'Kŕdeľ šťastných ďatľov učí pri ústí Váhu mĺkveho koňa obhrýzať kôru a žrať čerstvé mäso.',
                'Krdel stastnych datlov uci pri usti Vahu mlkveho kona obhryzat koru a zrat cerstve maso.',
            ],
            [ // Slovenian
                'Hišničin bratec vzgaja polže pod fikusom.',
                'Hisnicin bratec vzgaja polze pod fikusom.',
            ],
            [ // Spanish
                'Benjamín pidió una bebida de kiwi y fresa; Noé, sin vergüenza, la más exquisita champaña del menú.',
                'Benjamin pidio una bebida de kiwi y fresa; Noe, sin verguenza, la mas exquisita champana del menu.',
            ],
            [ // Swedish
                'Yxskaftbud, ge vår WC-zonmö IQ-hjälp.',
                'Yxskaftbud, ge var WC-zonmo IQ-hjalp.',
            ],
            [ // Turkish
                'Pijamalı hasta yağız şoföre çabucak güvendi.',
                'Pijamali hasta yagiz sofore cabucak guvendi.',
            ],
            [ // Urdu
                'Ṭhanḍ meṉ, ek qaḥat̤-zadah gāʾoṉ se guẕarte waqt ek ciṛciṛe, bā-ʾas̱ar o-fārig̱ẖ s̱ẖaḵẖṣ ko baʿẓ jal-parī numā aẕẖdahe naz̤ar āʾe.',
                'Thand men, ek qahat-zadah ga’on se guzarte waqt ek circire, ba-’asar o-farigh shakhs ko ba’z jal-pari numa azhdahe nazar a’e.',
            ],
            [ // Uyghur
                'Awu bir jüp xoraz Fransiyening Parizh shehrige yëqin taghqa köchelmidi.',
                'Awu bir jup xoraz Fransiyening Parizh shehrige yeqin taghqa kochelmidi.',
            ],
            [ // Yoruba
                'Ìwò̩fà ń yò̩ séji tó gbojúmó̩, ó hàn pákànpò̩ gan-an nis̩é̩ rè̩ bó dò̩la.',
                'Iwofa n yo seji to gbojumo, o han pakanpo gan-an nise re bo dola.',
            ],
            [ // Welsh
                'Parciais fy jac codi baw hud llawn dŵr ger tŷ Mabon.',
                'Parciais fy jac codi baw hud llawn dwr ger ty Mabon.',
            ],
            [/** @link http://getemoji.com/ */
                '😀 😃 😄 😁 😆 😅 😂',
                '😀 😃 😄 😁 😆 😅 😂',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider provideValuesToMakeConstant
     * @param string $toConstant
     * @param string $asConstant
     */
    public function I_can_convert_any_string_to_constant_like_value($toConstant, $asConstant)
    {
        self::assertSame($asConstant, StringTools::toConstant($toConstant));
    }

    public function provideValuesToMakeConstant()
    {
        /** For list of all pangrams see great @link http://clagnut.com/blog/2380/ */
        return [
            ['¿Quién es?', 'quien_es'], // surrounding non-characters are just removed, not translated to underscores (spanish)
            ['Zing, dwarf jocks vex lymph, Qutb.', 'zing_dwarf_jocks_vex_lymph_qutb'], /** well, this is english, @link http://clagnut.com/blog/2380/#Perfect_pangrams_in_English_.2826_letters.29 */
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
        self::assertSame($expectedResult, StringTools::camelCaseToSnakeCasedBasename($toConvert));
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
            ['small-ukulele', 'isSmallUkulele', 'is'],
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
            ['small-ukulele', 'reserveSmallUkulele', 'reserve'],
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
        $utf8String = \mb_convert_encoding('Příliš', 'UTF-8');
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
