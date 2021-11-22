<?php

use PHPUnit\Framework\TestCase;
use Ltv\Service\AnalyseText;

final class AnalyzeTextTest extends TestCase
{
    public function test1(): void
    {
        $locales = ['fr', 'de', 'nl', 'en', 'it', 'es'];

        $text = 'Super camping mais le patron est un vrai con. Dommage que la Wifi est constante et d\'un bon débit';

        $analyseText       = new AnalyseText();
        $analyseText->prod = 0;

        foreach ($locales as $locale) {
            $locale = $analyseText->langDectect(1, $locale);
            $analyseText->getAllListWords($locale);
            $this->assertTrue(is_array($analyseText->list[$locale]));
            $analyseText->process($text, $locale, []);
        }

        $this->assertEquals(
            'Super camping mais le patron est un vrai <tred>con</tred>. Dommage que la <tblue>wifi</tblue> est constante et d\'un bon débit',
            $analyseText->text_formatted
        );
    }

    public function test2()
    {
        $locales = ['fr', 'de', 'nl', 'en', 'it', 'es'];

        $text         = 'Il est con, non ? détenté +33 6 60 58 74 74 conçue con some@gmail.com first.com second.org Con lorem ipsum';
        $analyseText       = new AnalyseText();
        $analyseText->prod = 0;

        foreach ($locales as $locale) {
            $locale = $analyseText->langDectect(1, $locale);
            $analyseText->getAllListWords($locale);
            $this->assertTrue(is_array($analyseText->list[$locale]));
            $analyseText->process($text, $locale, []);
        }

        $this->assertEquals(
            'Il est <tred>con</tred>, non ? détenté <torange>+33 6 60 58 74 74</torange> conçue <tred>con</tred> <torange>some@gmail.com</torange> <torange>first.com</torange> <torange>second.org</torange> <tred>con</tred> lorem ipsum',
            $analyseText->text_formatted
        );
    }
}
