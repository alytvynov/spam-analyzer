<?php
declare(strict_types=1);

/**
 * @author Anton LYTVYNOV <lytvynov.anton@gmail.com>
 * @link   https://lytvynov-anton.com
 */

use Ltv\Service\SpamAnalyzer;
use PHPUnit\Framework\TestCase;

final class SpamAnalyzerTest extends TestCase
{

    public function testSetInput(): void
    {
        $spamAnalyzer = new SpamAnalyzer('input');

        $this->assertInstanceOf(
            SpamAnalyzer::class,
            $spamAnalyzer
        );

        $this->assertEquals(
            'input',
            $spamAnalyzer->getInput()
        );
    }

    public function testProcessText1()
    {
        $text         = 'Super camping mais le patron est un vrai con. Dommage que la Wifi est constante et d\'un bon débit';
        $spamAnalyzer = (new SpamAnalyzer($text))->process();

        $this->assertEquals(
            $text,
            $spamAnalyzer->getInput()
        );

        $this->assertEquals(
            'Super camping mais le patron est un vrai <red>con</red>. Dommage que la <blue>wifi</blue> est constante et d\'un bon débit',
            $spamAnalyzer->getOutput()
        );

        $this->assertEquals(
            ['insults' => ['con'], 'services' => ['wifi']],
            $spamAnalyzer->getProcessResult()
        );
    }

    public function testProcessText2()
    {
        $text         = 'Bel endroit avec départ de rando, mais attention car un rital à l\'entrée nous regarde mal';
        $spamAnalyzer = (new SpamAnalyzer($text))->process();

        $this->assertEquals(
            $text,
            $spamAnalyzer->getInput()
        );

        $this->assertEquals(
            'Bel endroit avec départ de <green>rando</green>, mais attention car un <red>rital</red> à l\'entrée nous regarde mal',
            $spamAnalyzer->getOutput()
        );

        $this->assertEquals(
            [
                'racists'    => ['rital',],
                'activities' => ['rando'],

            ],
            $spamAnalyzer->getProcessResult()
        );
    }

    public function testProcessText3()
    {
        $text         = 'Super camping, j\'adore';
        $spamAnalyzer = (new SpamAnalyzer($text))->process();

        $this->assertEquals(
            $text,
            $spamAnalyzer->getInput()
        );

        $this->assertEquals(
            'Super camping, j\'adore',
            $spamAnalyzer->getOutput()
        );

        $this->assertEquals(
            [],
            $spamAnalyzer->getProcessResult()
        );
    }

    public function testProcessText4()
    {
        $text         = 'Super lieu interdit, mais idéal pour planter sa tente et capter la 4G';
        $spamAnalyzer = (new SpamAnalyzer($text))->process();

        $this->assertEquals(
            $text,
            $spamAnalyzer->getInput()
        );

        $this->assertEquals(
            'Super lieu <orange>interdit</orange>, mais idéal pour planter sa <orange>tente</orange> et capter la <blue>4g</blue>',
            $spamAnalyzer->getOutput()
        );

        $this->assertEquals(
            [
                'alerts'   => [
                    'tente',
                    'interdit'
                ],
                'services' => [
                    '4g'
                ]
            ],
            $spamAnalyzer->getProcessResult()
        );
    }

    public function testProcessText5()
    {
        $text         = 'con CON cOn Con CoN';
        $spamAnalyzer = (new SpamAnalyzer($text))->process();

        $this->assertEquals(
            $text,
            $spamAnalyzer->getInput()
        );

        $this->assertEquals(
            '<red>con</red> <red>con</red> <red>con</red> <red>con</red> <red>con</red>',
            $spamAnalyzer->getOutput()
        );

        $this->assertEquals(
            [
                'insults' => [
                    'con'
                ],
            ],
            $spamAnalyzer->getProcessResult()
        );
    }

    public function testProcessText6()
    {
        $text         = '... grey water ...... dogs ... dog ....... showers ....';
        $spamAnalyzer = (new SpamAnalyzer($text))
            ->setBadWords(
                [
                    'words' => ['grey water', 'showers', 'water', 'dog'],
                ]
            )
            ->process();

        $this->assertEquals(
            $text,
            $spamAnalyzer->getInput()
        );

        $this->assertEquals(
            '... <xxx>grey water</xxx> ...... dogs ... <xxx>dog</xxx> ....... <xxx>showers</xxx> ....',
            $spamAnalyzer->getOutput()
        );

        $this->assertEquals(
            [
                'words' => ['grey water', 'showers', 'dog',]
            ],
            $spamAnalyzer->getProcessResult()
        );
    }

    public function testProcessText7()
    {
        $text         = 'Test this the text that we need';
        $spamAnalyzer = (new SpamAnalyzer($text))->setBadWords(['words' => ['this']])->process();

        $this->assertEquals(
            $text,
            $spamAnalyzer->getInput()
        );

        $this->assertEquals(
            'Test <xxx>this</xxx> the text that we need',
            $spamAnalyzer->getOutput()
        );

        $this->assertEquals(
            ['words' => ['this'],],
            $spamAnalyzer->getProcessResult()
        );
    }

    public function testProcessText8()
    {
        $text         = '... grey water ...... dogs ... dog ....... showers ....';
        $spamAnalyzer = (new SpamAnalyzer($text))
            ->setBadWords(
                [
                    'words' => ['showers', 'water', 'dog', 'grey water'],
                ]
            )
            ->process();

        $this->assertEquals(
            $text,
            $spamAnalyzer->getInput()
        );

        $this->assertEquals(
            '... grey <xxx>water</xxx> ...... dogs ... <xxx>dog</xxx> ....... <xxx>showers</xxx> ....',
            $spamAnalyzer->getOutput()
        );

        $this->assertEquals(
            [
                'words' => ['showers', 'water', 'dog',]
            ],
            $spamAnalyzer->getProcessResult()
        );
    }

    public function testProcessText9()
    {
        $text = 'Aire très bien conçue..Accueil chaleureux.....joli coin détente...beaucoup de villes devraient prendre modèle... Le tarif e justifié... Merci... nous y reviendrons';

        $spamAnalyzer = (new SpamAnalyzer($text))
            ->setBadWords(
                [
                    'words' => ['con', 'tente',],
                ]
            )
            ->process();

        $this->assertEquals(
            $text,
            $spamAnalyzer->getInput()
        );

        $this->assertEquals(
            'Aire très bien conçue..Accueil chaleureux.....joli coin détente...beaucoup de villes devraient prendre modèle... Le tarif e justifié... Merci... nous y reviendrons',
            $spamAnalyzer->getOutput()
        );

        $this->assertEquals(
            [],
            $spamAnalyzer->getProcessResult()
        );
    }

    public function testFindWords()
    {
        $text     = 'test test TT test';
        $keyWord  = 'TT';
        $badWords = ['words' => [$keyWord]];

        $spamAnalyzer = (new SpamAnalyzer())
            ->setInput($text)
            ->setBadWords($badWords);

        $this->assertEquals(
            [$keyWord],
            $spamAnalyzer->findWords([$keyWord], SpamAnalyzer::HTML_FORMAT_DEFAULT)
        );
    }

    /**
     * @findWords
     */
    public function testFindWordsASC()
    {
        $text = 'Here we can find gey water, is not it?';

        $keyWord  = 'gey water';
        $keyWord2 = 'water';
        $badWords = [
            'words' => [
                $keyWord,
                $keyWord2,
            ]
        ];

        $spamAnalyzer = (new SpamAnalyzer())
            ->setInput($text)
            ->setBadWords($badWords);

        $result = $spamAnalyzer->findWords($badWords['words'], SpamAnalyzer::HTML_FORMAT_DEFAULT);

        $this->assertEquals(
            [
                $keyWord
            ],
            $result
        );
    }

    /**
     * @findWords
     */
    public function testFindWordsDESC()
    {
        $text = 'Here we can find gey water, is not it?';

        $keyWord  = 'gey water';
        $keyWord2 = 'water';
        $badWords = [
            'words' => [
                $keyWord2,
                $keyWord,
            ]
        ];

        $spamAnalyzer = (new SpamAnalyzer())
            ->setInput($text)
            ->setBadWords($badWords);

        $result = $spamAnalyzer->findWords($badWords['words'], SpamAnalyzer::HTML_FORMAT_DEFAULT);

        $this->assertEquals(
            [
                $keyWord2
            ],
            $result
        );
    }

    public function testIsAlreadyReplaced()
    {
        $text         = '... grey water ...... dogs ... dog ....... showers ....';
        $spamAnalyzer = (new SpamAnalyzer($text))->process();

        $this->assertFalse($spamAnalyzer->isWordAlreadyReplaced($text, 22));
        $this->assertFalse($spamAnalyzer->isWordAlreadyReplaced($text, 31));
        /* grey */
        $this->assertFalse($spamAnalyzer->isWordAlreadyReplaced($text, 4));

        $text         = '... <xxx>grey water</xxx> ...... dogs ... dog ....... showers ....';
        $spamAnalyzer = (new SpamAnalyzer($text))->process();

        /* water */
        $this->assertTrue($spamAnalyzer->isWordAlreadyReplaced($text, 14));

        /* grey water */
        $this->assertTrue($spamAnalyzer->isWordAlreadyReplaced($text, 9));

        /* grey */
        $this->assertTrue($spamAnalyzer->isWordAlreadyReplaced($text, 9));

        $text         = 'water... water ...';
        $spamAnalyzer = (new SpamAnalyzer($text))->process();

        /* 1st water */
        $this->assertFalse($spamAnalyzer->isWordAlreadyReplaced($text, 0));
        $this->assertFalse($spamAnalyzer->isWordAlreadyReplaced($text, 8));

        $text         = '<dd>water... water ...</dd>';
        $spamAnalyzer = (new SpamAnalyzer($text))->process();

        $this->assertTrue($spamAnalyzer->isWordAlreadyReplaced($text, 4));
        $this->assertTrue($spamAnalyzer->isWordAlreadyReplaced($text, 13));

        $text         = '<dd>dqdq</dd> water... <dd>dd</dd>';
        $spamAnalyzer = (new SpamAnalyzer($text))->process();

        $this->assertFalse($spamAnalyzer->isWordAlreadyReplaced($text, 15));

    }

    public function testGetMatchesForWord()
    {
        $text         = '... grey water ...... dogs ... dog ....... showers ... ..... dog .';
        $spamAnalyzer = new SpamAnalyzer($text);
        $matches      = $spamAnalyzer->getMatchesForWord($text, 'dog');

        $this->assertEquals(
            [
                ['dog', 31,],
                ['dog', 61,],
            ],
            $matches
        );

        $text         = '... grey water ...... dogs ... dog ....... showers ... ..... dog .';
        $spamAnalyzer = new SpamAnalyzer($text);
        $matches      = $spamAnalyzer->getMatchesForWord($text, 'fff');

        $this->assertEquals(
            [],
            $matches
        );
    }

    public function testIsHtmlOnTheRightSide()
    {
        $spamAnalyzer = new SpamAnalyzer();

        $this->assertFalse($spamAnalyzer->isHtmlOnTheRightSide('... water<', 4));
        $this->assertFalse($spamAnalyzer->isHtmlOnTheRightSide('this is my text', 6));
        $this->assertTrue($spamAnalyzer->isHtmlOnTheRightSide('this is</ my text', 6));
        $this->assertFalse($spamAnalyzer->isHtmlOnTheRightSide('this is</ my text', 12));

        /** search close tag near XX before open tag */
        $this->assertFalse($spamAnalyzer->isHtmlOnTheRightSide('this XX my <xxx>text</xxx>', 4));
        $this->assertTrue($spamAnalyzer->isHtmlOnTheRightSide('this XX my text</xxx> here is the other <xxx>ddd</xxx>', 4));
    }


    public function testIsHtmlOnTheLeftSide()
    {
        $spamAnalyzer = new SpamAnalyzer();

        $this->assertFalse($spamAnalyzer->isHtmlOnTheLeftSide('b is here', 0));
        $this->assertTrue($spamAnalyzer->isHtmlOnTheLeftSide('>b is here', 0));
        $this->assertTrue($spamAnalyzer->isHtmlOnTheLeftSide('.. >b is here', 3));
        $this->assertTrue($spamAnalyzer->isHtmlOnTheLeftSide('.. />b is here', 3));
    }

    public function testIsWordAlreadyReplaced()
    {
        $spamAnalyzer = new SpamAnalyzer();

        /* is */
        $this->assertFalse($spamAnalyzer->isWordAlreadyReplaced('b is here', 2));

        $text   = 'b <x>is</x> here';
        $offset = 5;

        $this->assertTrue($spamAnalyzer->isHtmlOnTheLeftSide($text, $offset));
        $this->assertTrue($spamAnalyzer->isHtmlOnTheRightSide($text, $offset));
        /* is */
        $this->assertTrue($spamAnalyzer->isWordAlreadyReplaced($text, $offset));
        /* here */
        $this->assertFalse($spamAnalyzer->isWordAlreadyReplaced($text, 12));
    }

    public function testGetEmailMatches()
    {
        $text = 'dd dd.aa@df.df Lorem ipsum email@email.com dolor sit amet, consectetur adipiscing elit. Fusce sed volutpat sem. anton@gmail.com Sed convallis purus sit tt@tt.eu amet lorem placerat condimentum.';

        $spamAnalyzer = (new SpamAnalyzer($text))->setBadWords([]);
        $matches      = $spamAnalyzer->getEmailMatches();

        $this->assertEquals(
            [
                'dd.aa@df.df',
                'email@email.com',
                'anton@gmail.com',
                'tt@tt.eu',
            ],
            $matches
        );

        $spamAnalyzer->process();

        $this->assertEquals(
            $text,
            $spamAnalyzer->getInput()
        );

        $this->assertEquals(
            'dd <email>dd.aa@df.df</email> Lorem ipsum <email>email@email.com</email> dolor sit amet, consectetur adipiscing elit. Fusce sed volutpat sem. <email>anton@gmail.com</email> Sed convallis purus sit <email>tt@tt.eu</email> amet lorem placerat condimentum.',
            $spamAnalyzer->getOutput()
        );
    }


    public function testGetSiteMatches()
    {
        $text = 'lorem http://www.lesite.fr imsum www.autresite.org dolor toto.com sit amet https://www.google.com test';

        $spamAnalyzer = (new SpamAnalyzer($text))->setBadWords([]);
        $matches      = $spamAnalyzer->getSiteMatches();

        $this->assertEquals(
            [
                'http://www.lesite.fr',
                'www.autresite.org',
                'toto.com',
                'https://www.google.com',
            ],
            $matches
        );

        $this->assertEquals(
            $text,
            $spamAnalyzer->getInput()
        );

        $spamAnalyzer->process();

        $this->assertEquals(
            'lorem <site>http://www.lesite.fr</site> imsum <site>www.autresite.org</site> dolor <site>toto.com</site> sit amet <site>https://www.google.com</site> test',
            $spamAnalyzer->getOutput()
        );
    }

    public function testGetSiteMatches2()
    {
        $text = 'lorem google.com test';

        $spamAnalyzer = (new SpamAnalyzer($text))->setBadWords([]);
        $siteMatches  = $spamAnalyzer->getSiteMatches();

        $this->assertEquals(
            ['google.com'],
            $siteMatches
        );

        $this->assertEquals(
            $text,
            $spamAnalyzer->getInput()
        );

        $spamAnalyzer->process();

        $this->assertEquals(
            'lorem <site>google.com</site> test',
            $spamAnalyzer->getOutput()
        );
    }

    public function testGetPhoneMatches()
    {
        $text = '... +33 6 60 58 74 74 ... 06.60.58.74.79 ... 0123456789 ... 01 23 45 67 89 ... 01.23.45.67.89 .... 0123 45.67.89 ... 0033 123-456-789 ... +33-1.23.45.67.89 ... +33 - 123 456 789 ... +33(0) 123 456 789 ... +33 (0)123 45 67 89 ... +33 (0)1 2345-6789 ...... +33(0) - 123456789 ...';

        $spamAnalyzer = (new SpamAnalyzer($text))->setBadWords([])->process();
        $matches      = $spamAnalyzer->getPhoneMatches();

        $this->assertEquals(
            [
                '+33 6 60 58 74 74',
                '06.60.58.74.79',
                '0123456789',
                '01 23 45 67 89',
                '01.23.45.67.89',
                '0123 45.67.89',
                '0033 123-456-789',
                '+33-1.23.45.67.89',
                '+33 - 123 456 789',
                '+33(0) 123 456 789',
                '+33 (0)123 45 67 89',
                '+33 (0)1 2345-6789',
                '+33(0) - 123456789',
            ],
            $matches
        );

        $this->assertEquals(
            $text,
            $spamAnalyzer->getInput()
        );

        $this->assertEquals(
            '... <phone>+33 6 60 58 74 74</phone> ... <phone>06.60.58.74.79</phone> ... <phone>0123456789</phone> ... <phone>01 23 45 67 89</phone> ... <phone>01.23.45.67.89</phone> .... <phone>0123 45.67.89</phone> ... <phone>0033 123-456-789</phone> ... <phone>+33-1.23.45.67.89</phone> ... <phone>+33 - 123 456 789</phone> ... <phone>+33(0) 123 456 789</phone> ... <phone>+33 (0)123 45 67 89</phone> ... <phone>+33 (0)1 2345-6789</phone> ...... <phone>+33(0) - 123456789</phone> ...',
            $spamAnalyzer->getOutput()
        );
    }

    public function testLargeProcessText()
    {
        $text         = 'Il est con, non ? détenté +33 6 60 58 74 74 conçue con some@gmail.com first.com second.org Con lorem ipsum';
        $spamAnalyzer = (new SpamAnalyzer($text))
            ->process();

        $this->assertEquals(
            [
                "insults" => [
                    0 => "con"
                ],
                "emails"  => [
                    0 => "some@gmail.com"
                ],
                "phones"  => [
                    0 => "+33 6 60 58 74 74"
                ],
                "sites"   => [
                    0 => "first.com",
                    1 => "second.org"
                ]
            ],
            $spamAnalyzer->getProcessResult()
        );

        $this->assertEquals(
            'Il est <red>con</red>, non ? détenté <phone>+33 6 60 58 74 74</phone> conçue <red>con</red> <email>some@gmail.com</email> <site>first.com</site> <site>second.org</site> <red>con</red> lorem ipsum',
            $spamAnalyzer->getOutput()
        );
    }
}
