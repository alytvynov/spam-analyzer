<?php

namespace Ltv\Service;

/**
 * Class TextAnalyser
 *
 * @author Anton LYTVYNOV <lytvynov.anton@gmail.com>
 * @link   https://www.linkedin.com/in/anton-lytvynov/
 */
class SpamAnalyzer
{

    const HTML_FORMAT_INSULT_WORDS     = '<red>%s</red>';
    const HTML_FORMAT_RACIST_WORDS     = '<red>%s</red>';
    const HTML_FORMAT_ALERT_WORDS      = '<orange>%s</orange>';
    const HTML_FORMAT_SERVICES_WORDS   = '<blue>%s</blue>';
    const HTML_FORMAT_ACTIVITIES_WORDS = '<green>%s</green>';

    /**
     * @var string
     */
    public $textInitial;

    /**
     * @var string
     */
    public $textFormatted;

    /**
     * @var array
     */
    public $textAnalyse = [];

    /**
     * @var array
     */
    public $insultsWords = ['con', 'cons', 'connard', 'connards', 'salope', 'salopes', 'enculé', 'enculés', 'pd', 'pds'];

    /**
     * @var array
     */
    public $racistWords = ['gitan', 'arabe', 'rital'];

    /**
     * @var array
     */
    public $alertWords = ['tente', 'tentes', 'caravane', 'caravanes', 'interdit', 'illégal'];

    /**
     * @var array
     */
    public $servicesWords = ['wifi', '4g', 'eau', 'services'];

    /**
     * @var array
     */
    public $activitiesWords = ['escalade', 'moto', 'rando'];

    /**
     * @param string $textInitial
     */
    public function __construct(string $textInitial = '')
    {
        $this->clear();
        $this->textInitial = $textInitial;
    }

    /**
     * @param string $textInitial
     *
     * @return $this
     */
    public function setTextInitial(string $textInitial)
    {
        $this->clear();
        $this->textInitial = $textInitial;

        return $this;
    }

    private function clear(): void
    {
        $this->textFormatted = '';
        $this->textAnalyse   = [];
    }

    /**
     * @return string
     */
    public function getTextInitial(): string
    {
        return $this->textInitial;
    }

    /**
     * @return string
     */
    public function getTextFormatted(): string
    {
        return $this->textFormatted;
    }

    /**
     * @return array
     */
    public function getTextAnalyse(): array
    {
        return $this->textAnalyse;
    }

    public function process()
    {
        $this->textFormatted = $this->textInitial;

        $this->textAnalyse['insultsWords']    = $this->findWords($this->insultsWords, self::HTML_FORMAT_INSULT_WORDS);
        $this->textAnalyse['racistWords']     = $this->findWords($this->racistWords, self::HTML_FORMAT_RACIST_WORDS);
        $this->textAnalyse['alertWords']      = $this->findWords($this->alertWords, self::HTML_FORMAT_ALERT_WORDS);
        $this->textAnalyse['servicesWords']   = $this->findWords($this->servicesWords, self::HTML_FORMAT_SERVICES_WORDS);
        $this->textAnalyse['activitiesWords'] = $this->findWords($this->activitiesWords, self::HTML_FORMAT_ACTIVITIES_WORDS);
        $this->textAnalyse                    = array_filter($this->textAnalyse);

        return $this;
    }

    /**
     * @param array $keyWords
     * @param string $replaceHtmlFormat
     *
     * @return array
     */
    private function findWords(array $keyWords, string $replaceHtmlFormat = '<b>%s</b>'): array
    {
        $words = [];
        foreach ($keyWords as $word) {
            $regular = sprintf("/\b%s\b/i", $word);
            $html    = sprintf($replaceHtmlFormat, $word);
            if (preg_match($regular, $this->textFormatted)) {
                $words[]             = $word;
                $this->textFormatted = preg_replace($regular, $html, $this->textFormatted);
            }
        }

        return $words;
    }
}
