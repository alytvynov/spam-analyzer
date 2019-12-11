<?php
declare(strict_types=1);

/**
 * @author Anton LYTVYNOV <lytvynov.anton@gmail.com>
 * @link   https://lytvynov-anton.com
 */

namespace Ltv\Service;

class SpamAnalyzer
{

    const HTML_FORMAT_INSULT_WORDS     = '<red>%s</red>';
    const HTML_FORMAT_RACIST_WORDS     = '<red>%s</red>';
    const HTML_FORMAT_ALERT_WORDS      = '<orange>%s</orange>';
    const HTML_FORMAT_SERVICES_WORDS   = '<blue>%s</blue>';
    const HTML_FORMAT_ACTIVITIES_WORDS = '<green>%s</green>';
    const HTML_FORMAT_DEFAULT          = '<xxx>%s</xxx>';
    const HTML_FORMAT_PHONE            = '<phone>%s</phone>';
    const HTML_FORMAT_EMAIL            = '<email>%s</email>';
    const HTML_FORMAT_SITE             = '<site>%s</site>';

    const KEY_INSULT_WORDS     = 'insults_words';
    const KEY_RACIST_WORDS     = 'racist_words';
    const KEY_ALERT_WORDS      = 'alert_words';
    const KEY_SERVICES_WORDS   = 'services_words';
    const KEY_ACTIVITIES_WORDS = 'activities_words';

    const KEY_PHONES = 'phones';
    const KEY_EMAILS = 'emails';
    const KEY_SITES  = 'sites';

    const MAPPING = [
        self::KEY_INSULT_WORDS     => self::HTML_FORMAT_INSULT_WORDS,
        self::KEY_RACIST_WORDS     => self::HTML_FORMAT_RACIST_WORDS,
        self::KEY_ALERT_WORDS      => self::HTML_FORMAT_ALERT_WORDS,
        self::KEY_SERVICES_WORDS   => self::HTML_FORMAT_SERVICES_WORDS,
        self::KEY_ACTIVITIES_WORDS => self::HTML_FORMAT_ACTIVITIES_WORDS,

        self::KEY_PHONES => self::HTML_FORMAT_PHONE,
        self::KEY_EMAILS => self::HTML_FORMAT_EMAIL,
        self::KEY_SITES  => self::HTML_FORMAT_SITE,
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
    private $emails;

    /**
     * @var array
     */
    private $sites;

    /**
     * @var array
     */
    private $phones;

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
        $this->input = $input;
        $this->clear();
    }

    /**
     * @param string $input
     *
     * @return $this
     */
    public function setInput(string $input): SpamAnalyzer
    {
        $this->input = $input;
        $this->clear();

        return $this;
    }

    /**
     * @param array $badWords
     *
     * @return SpamAnalyzer
     */
    public function setBadWords(array $badWords): SpamAnalyzer
    {
        $this->badWords = $badWords;

        return $this;
    }

    private function clear(): void
    {
        $this->output        = $this->input;
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
     * @return string
     */
    public function getHtmlFormatByKey(string $key): string
    {
        return self::MAPPING[$key] ?? self::HTML_FORMAT_DEFAULT;
    }

    public function process(): SpamAnalyzer
    {
        $this->processSpam();
        $this->processData();

        if (count($this->emails) > 0) {
            $this->processResult[self::KEY_EMAILS] = $this->emails;
        }

        if (count($this->phones) > 0) {
            $this->processResult[self::KEY_PHONES] = $this->phones;
        }

        if (count($this->sites) > 0) {
            $this->processResult[self::KEY_SITES] = $this->sites;
        }

        return $this;
    }

    public function processSpam(): SpamAnalyzer
    {
        foreach ($this->badWords as $key => $words) {
            $this->processResult[$key] = $this->findWords($words, $this->getHtmlFormatByKey($key));
        }

        $this->processResult = array_filter($this->processResult);

        return $this;
    }

    public function processData(): SpamAnalyzer
    {
        $this->emails = $this->getEmailMatches();

        foreach ($this->emails as $email) {
            $replaceHtmlFormat = sprintf($this->getHtmlFormatByKey(self::KEY_EMAILS), $email);
            $this->output      = str_replace($email, $replaceHtmlFormat, $this->output);
        }

        $this->sites = $this->getSiteMatches();

        foreach ($this->sites as $site) {
            $replaceHtmlFormat = sprintf($this->getHtmlFormatByKey(self::KEY_SITES), $site);
            $this->output      = str_replace($site, $replaceHtmlFormat, $this->output);
        }

        $this->phones = $this->getPhoneMatches();

        foreach ($this->phones as $phone) {
            $replaceHtmlFormat = sprintf($this->getHtmlFormatByKey(self::KEY_PHONES), $phone);
            $this->output      = str_replace($phone, $replaceHtmlFormat, $this->output);
        }

        return $this;
    }

    /**
     * @param array  $keyWords
     * @param string $replaceHtmlFormat
     *
     * @return array
     */
    public function findWords(array $keyWords, string $replaceHtmlFormat = '<b>%s</b>'): array
    {
        $words = [];
        foreach ($keyWords as $word) {
            $matches = $this->getMatchesForWord($this->output, $word);

            $wordHtml = sprintf($replaceHtmlFormat, $word);
            $index    = count($matches) - 1;
            while ($index >= 0) {
                $match       = $matches[$index];
                $matchWord   = $match[0];
                $matchOffset = $match[1];

                if (!$this->isWordAlreadyReplaced($this->output, $matchOffset)) {
                    $this->output = $this->replaceMatch($wordHtml, $matchWord, $matchOffset);
                    $words[]      = $word;
                }
                $index--;
            }
        }

        return array_unique($words);
    }

    /**
     * @param string $wordHtml
     * @param string $matchWord
     * @param int    $matchOffset
     *
     * @return string
     */
    public function replaceMatch(string $wordHtml, string $matchWord, int $matchOffset)
    {
        $output       = $this->output;
        $beginning    = substr($output, 0, $matchOffset);
        $lengthForEnd = strlen($output) - $matchOffset - strlen($matchWord);
        $output       = $this->output;
        $end          = substr($output, $matchOffset + strlen($matchWord), $lengthForEnd);

        return $beginning . $wordHtml . $end;
    }

    public function getMatchesForWord(string $text, string $word): array
    {
        preg_match_all($this->getRegularForWord($word), $text, $matches, PREG_OFFSET_CAPTURE);
        $data = $matches[0] ?? [];

        return $data;
    }

    public function isWordAlreadyReplaced(string $text, int $offset)
    {
        return $this->isHtmlOnTheLeftSide($text, $offset) &&
            $this->isHtmlOnTheRightSide($text, $offset);
    }

    public function isHtmlOnTheLeftSide(string $text, int $offset): bool
    {
        $posOpen = strrpos($text, '>', $offset);

        if ($posOpen === false) {
            /* no opened tag on the left side */
            return false;
        } else {
            /* opened tag on the left side */

            return true;
        }
    }

    public function isHtmlOnTheRightSide(string $text, int $offset): bool
    {
        $posClosed = strpos($text, '</', $offset);

        if ($posClosed === false) {
            /* no close tag */
            return false;
        } else {
            $posOpen = strpos($text, '>', $offset);

            if ($posOpen === false) {
                /* no open tag, but close tag */
                return true;
            } else {
                /* open tag and close tag */
                return $posOpen > $posClosed;
            }
        }
    }

    public function getRegularForWord(string $word): string
    {
        return sprintf("/\b%s\b/ui", $word);
    }

    public function getEmailMatches()
    {
        $pattern = '/[a-z0-9_\-\+\.]+@[a-z0-9\-]+\.([a-z]{2,4})(?:\.[a-z]{2})?/i';
        preg_match_all($pattern, $this->output, $matches);
        $emailMatches = $matches[0];

        return $emailMatches;
    }

    public function getPhoneMatches()
    {
        $pattern = '/(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{2}){4}|\d{2}(?:[\s.-]?\d{3}){2})/';

        preg_match_all($pattern, $this->output, $matches);
        $phoneMatches = $matches[0];

        return $phoneMatches;
    }

    public function getSiteMatches()
    {
        $sites = [];

        $pattern = '/(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/ AND !(\@))?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?/';
        preg_match_all($pattern, $this->output, $matches, PREG_OFFSET_CAPTURE);
        $siteMatches = $matches[0];

        foreach ($siteMatches as $key => $match) {
            $word   = $match[0];
            $offset = $match[1];

            if (
                (!empty($this->output[$offset - 1]) && $this->output[$offset - 1] == '@') ||
                (!empty($this->output[$offset + strlen($word)]) && $this->output[$offset + strlen($word)] == '@')
            ) {
                unset($siteMatches[$key]);
            } else {
                $sites[] = $word;
            }
        }

        return $sites;
    }
}
