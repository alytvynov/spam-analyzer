<?php
declare(strict_types=1);

namespace Ltv\Service;

/**
 * Class processResultr
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

    const KEY_INSULT_WORDS     = 'insults';
    const KEY_RACIST_WORDS     = 'racists';
    const KEY_ALERT_WORDS      = 'alerts';
    const KEY_SERVICES_WORDS   = 'services';
    const KEY_ACTIVITIES_WORDS = 'activities';

    const MAPPING = [
        self::KEY_INSULT_WORDS     => self::HTML_FORMAT_INSULT_WORDS,
        self::KEY_RACIST_WORDS     => self::HTML_FORMAT_RACIST_WORDS,
        self::KEY_ALERT_WORDS      => self::HTML_FORMAT_ALERT_WORDS,
        self::KEY_SERVICES_WORDS   => self::HTML_FORMAT_SERVICES_WORDS,
        self::KEY_ACTIVITIES_WORDS => self::HTML_FORMAT_ACTIVITIES_WORDS,
    ];

    /**
     * @var string
     */
    private $input;

    /**
     * @var string
     */
    private $output;

    /**
     * @var array
     */
    private $processResult;

    /**
     * @var array
     */
    private $badWords = [
        self::KEY_INSULT_WORDS     => ['con', 'cons', 'connard', 'connards', 'salope', 'salopes', 'enculé', 'enculés', 'pd', 'pds',],
        self::KEY_RACIST_WORDS     => ['gitan', 'arabe', 'rital',],
        self::KEY_ALERT_WORDS      => ['tente', 'tentes', 'caravane', 'caravanes', 'interdit', 'illégal',],
        self::KEY_SERVICES_WORDS   => ['wifi', '4g', 'eau', 'services',],
        self::KEY_ACTIVITIES_WORDS => ['escalade', 'moto', 'rando',],
    ];

    /**
     * @param string $input
     */
    public function __construct(string $input = null)
    {
        $this->clear();
        $this->input = $input;
    }

    /**
     * @param string $input
     *
     * @return $this
     */
    public function setInput(string $input)
    {
        $this->clear();
        $this->input = $input;

        return $this;
    }

    private function clear(): void
    {
        $this->output        = null;
        $this->processResult = [];
    }

    /**
     * @return string
     */
    public function getInput(): string
    {
        return $this->input;
    }

    /**
     * @return string
     */
    public function getOutput(): string
    {
        return $this->output;
    }

    /**
     * @return array
     */
    public function getProcessResult(): array
    {
        return $this->processResult;
    }

    /**
     * @param string $key
     *
     * @return array
     */
    public function getHtmlFormatByKey(string $key): string
    {
        return self::MAPPING[$key];
    }

    public function process()
    {
        $this->output = $this->input;

        foreach ($this->badWords as $key => $words) {
            $this->processResult[$key] = $this->findWords($words, $this->getHtmlFormatByKey($key));
        }

        $this->processResult = array_filter($this->processResult);

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
            if (preg_match($regular, $this->output)) {
                $words[]      = $word;
                $this->output = preg_replace($regular, $html, $this->output);
            }
        }

        return $words;
    }
}
