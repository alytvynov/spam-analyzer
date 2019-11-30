<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Ltv\Service\SpamAnalyzer;

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
}
