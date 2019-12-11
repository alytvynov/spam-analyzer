<?php

namespace Ltv\Service;

/**
 * Class AnalyseText
 *
 *
 *  example call
 *  $ant = new AnalyseText();
 *
 *   for each
 *         $lang_utili = $ant->langDectect($ligne['langue_id'], $ligne['langue_locale']) *
 *         $ant->getAllListWords($lang_utili);
 *         $ant->process($ligne['commentaire'], $lang_utili, $place);
 */
class AnalyseText
{

    const LOCALE_FR = 'fr';
    const LOCALE_DE = 'de';
    const LOCALE_ES = 'es';
    const LOCALE_IT = 'it';
    const LOCALE_NL = 'nl';

    const HTML_FORMAT_INSULT_WORDS     = '<tred>%s</tred>';
    const HTML_FORMAT_RACIST_WORDS     = '<tred>%s</tred>';
    const HTML_FORMAT_ALERT_WORDS      = '<torange>%s</torange>';
    const HTML_FORMAT_SERVICES_WORDS   = '<tblue>%s</tblue>';
    const HTML_FORMAT_ACTIVITIES_WORDS = '<tgreen>%s</tgreen>';
    const HTML_FORMAT_PHONE            = '<torange>%s</torange>';
    const HTML_FORMAT_EMAIL            = '<torange>%s</torange>';
    const HTML_FORMAT_SITE             = '<torange>%s</torange>';

    const KEY_INSULT   = 'insult';
    const KEY_RACIST   = 'racist';
    const KEY_ALERT    = 'alert';
    const KEY_SERVICE  = 'service';
    const KEY_ACTIVITE = 'activity';
    const KEY_PHONE    = 'phone';
    const KEY_EMAIL    = 'email';
    const KEY_SITE     = 'site';
    const ALL          = 'all';

    const KEY_INSULT_WORDS     = 'insults_words';
    const KEY_RACIST_WORDS     = 'racist_words';
    const KEY_ALERT_WORDS      = 'alert_words';
    const KEY_SERVICES_WORDS   = 'services_words';
    const KEY_ACTIVITIES_WORDS = 'activities_words';
    const KEY_PHONES           = 'phones';
    const KEY_EMAILS           = 'emails';
    const KEY_SITES            = 'sites';

    const KEYS_WORDS = [self::KEY_INSULT_WORDS, self::KEY_RACIST_WORDS, self::KEY_ALERT_WORDS, self::KEY_SERVICES_WORDS, self::KEY_ACTIVITIES_WORDS,];

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
     * @var integer
     */
    public $prod = 0; // prod=1 keywords from database  / prod=0 = keywords sample
    //private $sample_all_json = '{"de":{"activity":[{"keyword":"kinderspielm\u00f6glichkeiten"},{"link":"jeux_enfants"},{"keyword":"spazierm\u04e7glichkeiten"},{"link":"rando"},{"keyword":"kindern spielplatz"},{"link":"jeux_enfants"},{"keyword":"kinder spielplatz"},{"link":"jeux_enfants"},{"keyword":"wandergebiet"},{"link":"rando"},{"keyword":"Schwimmbad"},{"link":"baignade"},{"keyword":"wanderung"},{"link":"rando"},{"keyword":"wanderweg"},{"link":"rando"},{"keyword":"klettern"},{"link":"escalade"},{"keyword":"wandern"},{"link":"rando"},{"keyword":"angeln"},{"link":"peche"}],"alert":[{"keyword":"H\u00f6henbegrenzung"},{"link":""},{"keyword":"Strafzettel"},{"link":""},{"keyword":"geschlossen"},{"link":""},{"keyword":"wohnwagen"},{"link":"0"},{"keyword":"verboten"},{"link":""},{"keyword":"dachzelt"},{"link":"0"},{"keyword":"polizei"},{"link":""},{"keyword":"privat"},{"link":""},{"keyword":"Strafe"},{"link":""},{"keyword":"zelt"},{"link":"0"}],"service":[{"keyword":"entsorgungsm\u00f6glichkeiten"},{"link":"eau_usee"},{"keyword":"schwarzwasserentsorgung"},{"link":"eau_noire"},{"keyword":"entsorgungsm\u00f6glichkeit"},{"link":"eau_usee"},{"keyword":"frischwasserversorgung"},{"link":"point_eau"},{"keyword":"grauwasserentsorgung"},{"link":"eau_usee"},{"keyword":"chemieklo entleerung"},{"link":"eau_noire"},{"keyword":"grauwasserentleerung"},{"link":"eau_usee"},{"keyword":"toilettenentsorgung"},{"link":"eau_noire"},{"keyword":"entsorgunganlagen"},{"link":"eau_usee"},{"keyword":"br\u04e7tchenservice"},{"link":"boulangerie"},{"keyword":"br\u00f6tchenservice"},{"link":"boulangerie"},{"keyword":"entsorgunganlage"},{"link":"eau_usee"},{"keyword":"chemie toiletten"},{"link":"eau_noire"},{"keyword":"chemietoiletten"},{"link":"eau_noire"},{"keyword":"chemie toilette"},{"link":"eau_noire"},{"keyword":"mullentsorgung"},{"link":"poubelle"},{"keyword":"stromanschluss"},{"link":"electricite"},{"keyword":"waschmaschinen"},{"link":"laverie"},{"keyword":"waschmaschine"},{"link":"laverie"},{"keyword":"frischwasser"},{"link":"point_eau"},{"keyword":"trinkwasser"},{"link":"point_eau"},{"keyword":"mulltonnen"},{"link":"poubelle"},{"keyword":"wasserhahn"},{"link":"point_eau"},{"keyword":"toiletten"},{"link":"wc_public"},{"keyword":"br\u00f6tchen"},{"link":"boulangerie"},{"keyword":"mulleimer"},{"link":"poubelle"},{"keyword":"chemie wc"},{"link":"eau_noire"},{"keyword":"abwasser"},{"link":"eau_usee"},{"keyword":"trockner"},{"link":"laverie"},{"keyword":"toilette"},{"link":"wc_public"},{"keyword":"plumsklo"},{"link":"wc_public"},{"keyword":"duschen"},{"link":"douche"},{"keyword":"dixiklo"},{"link":"wc_public"},{"keyword":"Wasser"},{"link":"point_eau"},{"keyword":"dusche"},{"link":"douche"},{"keyword":"backer"},{"link":"boulangerie"},{"keyword":"hunde"},{"link":"animaux"},{"keyword":" gpl "},{"link":"gpl"},{"keyword":"strom"},{"link":"electricite"},{"keyword":"wlan "},{"link":"wifi"},{"keyword":"brot"},{"link":"boulangerie"},{"keyword":"wifi"},{"link":"wifi"},{"keyword":"mull"},{"link":"poubelle"},{"keyword":"hund"},{"link":"animaux"},{"keyword":"pool"},{"link":"piscine"},{"keyword":"klo"},{"link":"wc_public"},{"keyword":"4G"},{"link":"wifi"},{"keyword":"3G"},{"link":"wifi"},{"keyword":"wc"},{"link":"wc_public"}]},"en":{"alert":[{"keyword":"dumpster diving"},{"link":""},{"keyword":"no overnight"},{"link":""},{"keyword":"no overnight"},{"link":""},{"keyword":"bin diving"},{"link":""},{"keyword":"Policemen"},{"link":""},{"keyword":"Policeman"},{"link":""},{"keyword":"forbidden"},{"link":""},{"keyword":"forbidden"},{"link":""},{"keyword":"caravans"},{"link":"0"},{"keyword":"robbery"},{"link":""},{"keyword":"caravan"},{"link":""},{"keyword":"private"},{"link":""},{"keyword":"police"},{"link":""},{"keyword":"closed"},{"link":""},{"keyword":"tents"},{"link":"0"},{"keyword":"tent"},{"link":""}],"service":[{"keyword":"black water"},{"link":"eau_noire"},{"keyword":"electricity"},{"link":"electricite"},{"keyword":"grey water"},{"link":"eau_usee"},{"keyword":"toilets"},{"link":"wc_public"},{"keyword":"showers"},{"link":"douche"},{"keyword":"shower"},{"link":"douche"},{"keyword":"water"},{"link":"point_eau"},{"keyword":"pool "},{"link":"piscine"},{"keyword":"wifi"},{"link":"wifi"},{"keyword":"dog"},{"link":"animaux"}]},"es":{"alert":[{"keyword":"prohibido"},{"link":""},{"keyword":"caravanas"},{"link":""},{"keyword":"caravana"},{"link":""},{"keyword":"polic\u00eda"},{"link":""},{"keyword":"cerrada"},{"link":""},{"keyword":"cerrado"},{"link":""},{"keyword":"policia"},{"link":""}],"service":[{"keyword":"agua"},{"link":"point_eau"},{"keyword":"wifi"},{"link":"wifi"}]},"fr":{"activity":[{"keyword":"aire de jeux"},{"link":"jeux_enfants"},{"keyword":"randonn\u00e9es"},{"link":"rando"},{"keyword":"escalade"},{"link":"escalade"},{"keyword":"peche"},{"link":"peche"},{"keyword":"rando"},{"link":"rando"}],"alert":[{"keyword":"n existe plus"},{"link":""},{"keyword":"inaccessible"},{"link":"0"},{"keyword":"introuvable"},{"link":""},{"keyword":"supprimer"},{"link":""},{"keyword":"barri\u00e8re"},{"link":""},{"keyword":"caravanes"},{"link":""},{"keyword":"interdit"},{"link":""},{"keyword":"caravane"},{"link":""},{"keyword":"ill\u00e9gal"},{"link":""},{"keyword":"tentes"},{"link":""},{"keyword":"ferm\u00e9"},{"link":""},{"keyword":"Ferm\u00e9"},{"link":""},{"keyword":"police"},{"link":"0"},{"keyword":"tente"},{"link":""},{"keyword":"tente"},{"link":""}],"insult":[{"keyword":"connasse"},{"link":""},{"keyword":"connards"},{"link":""},{"keyword":"connard"},{"link":""},{"keyword":"encul\u00e9"},{"link":""},{"keyword":"salope"},{"link":""},{"keyword":"cons"},{"link":""},{"keyword":"con"},{"link":""}],"racist":[{"keyword":"gitan"},{"link":""}],"service":[{"keyword":"\u00e9lectricit\u00e9"},{"link":"electricite"},{"keyword":"toilettes"},{"link":"wc_public"},{"keyword":"poubelle"},{"link":"poubelle"},{"keyword":"vidanger"},{"link":"eau_usee"},{"keyword":"piscine"},{"link":"piscine"},{"keyword":"douches"},{"link":"douche"},{"keyword":"laverie"},{"link":"laverie"},{"keyword":"douche"},{"link":"douche"},{"keyword":"chien"},{"link":"animaux"},{"keyword":"wifi"},{"link":"wifi"},{"keyword":"eau"},{"link":"point_eau"},{"keyword":"3G"},{"link":"donnees_mobile"},{"keyword":"4G"},{"link":"donnees_mobile"},{"keyword":"WC"},{"link":"wc_public"}]},"it":{"alert":[{"keyword":"roulotte"},{"link":"0"},{"keyword":"caravani"},{"link":"0"},{"keyword":"caravan"},{"link":"0"},{"keyword":"police"},{"link":"0"},{"keyword":"chiuso"},{"link":""},{"keyword":"tenda"},{"link":"0"},{"keyword":"tendi"},{"link":"0"}],"service":[{"keyword":"scarico acqua"},{"link":"eau_usee"},{"keyword":"acqua grigia"},{"link":"eau_usee"},{"keyword":"elettricit\u00e0"},{"link":"electricite"},{"keyword":"acque grigie"},{"link":"eau_usee"},{"keyword":"carico acqua"},{"link":"point_eau"},{"keyword":"elettricita"},{"link":"electricite"},{"keyword":"acque nere"},{"link":"eau_noire"},{"keyword":"acqua nera"},{"link":"eau_noire"},{"keyword":"lavanderia"},{"link":"laverie"},{"keyword":"gabinetto"},{"link":"wc_public"},{"keyword":"cassetta"},{"link":"wc_public"},{"keyword":" cane "},{"link":"animaux"},{"keyword":"doccia"},{"link":"douche"},{"keyword":" cani "},{"link":"animaux"},{"keyword":"docce"},{"link":"douche"},{"keyword":"acqua"},{"link":"point_eau"},{"keyword":"wifi"},{"link":"wifi"},{"keyword":"wc"},{"link":"wc_public"}]},"nl":{"service":[{"keyword":"water"},{"link":"point_eau"},{"keyword":"wifi"},{"link":"wifi"}]}}';
    //private $sample_all_array = null;

    /**
     * @var string
     */
    public $lang; // lang iso2 fr/en/de/it/es/nl

    /**
     * @var string
     */
    public $text; // text original

    /**
     * @var string
     */
    public $text_formatted = ''; // text with highlighted specific keywords

    /**
     * @var array
     */
    public $text_analyse; // associative array of founds keywords in one specific text      ex: $text_analyse['racist_words']='con';

    /**
     * @var string
     */
    public $texte_analyse_formatted; // associative array of founds keywords in one specific text      ex: $text_analyse['racist_words']='con';

    /**
     * @var array
     */
    //public $list =
    public $list = null;

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

    private function clear(): void
    {
        $this->text                    = '';
        $this->text_formatted          = '';
        $this->text_analyse            = [];
        $this->texte_analyse_formatted = '';
    }

    /**
     * @param string $text
     * @param string $lang_locale
     * @param string $place (associative array() of a place with for example $place['electricity']=1
     */
    public function process(string $text, string $lang_locale, array $place = null): void
    {
        $lang = $lang_locale;
        $this->clear();

        $this->text           = $text;
        $this->text_formatted = $this->text;

        $this->processSpam($lang);
        $this->text_analyse = array_filter($this->text_analyse);
        $this->processData();

        if (!empty($this->text_analyse[self::KEY_SERVICES_WORDS])) {
            foreach ($this->text_analyse[self::KEY_SERVICES_WORDS] as $ligne) { //ex electricity
                $this->htmlAnalyseServiceActivite($ligne['link'], $place, 'service');
            }
        }

        if (!empty($this->text_analyse[self::KEY_ACTIVITIES_WORDS])) {
            foreach ($this->text_analyse[self::KEY_ACTIVITIES_WORDS] as $ligne) { //ex swimming
                $this->htmlAnalyseServiceActivite($ligne['link'], $place, 'activite');
            }
        }
    }

    /**
     *
     * @param string $link
     * @param array  $place
     * @param string $service_activite
     *
     * @return void
     */
    private function htmlAnalyseServiceActivite(string $link, array $place, string $service_activite): void
    { //show if icons of services or activities are checked or not
        if (array_key_exists($link, $place)) {
            $res = $place[$link];
            if ($res == 1) {
                $img                           = "<img src='images/icones/" . $service_activite . "_" . $link . ".png' alt='' width='20px' title='" . $link . "'/>";
                $this->texte_analyse_formatted .= $img . " est coché<br />";
            } else {
                $img                           = "<img src='images/icones/" . $service_activite . "_" . $link . "_off.png' alt='' width='20px' title='" . $link . "'/>";
                $this->texte_analyse_formatted .= $img . " n'est pas coché<br />";
            }
        }
    }

    /**
     *
     * @param string $listeType
     * @param string $lang (ex: fr/en/de...)
     *
     * @return array
     */
    public function getListWords(string $listeType = '', string $lang = ''): array
    { //get array of keywords depending the language (ALL/FR/DE/EN/ES/IT/NL)
        if ($this->prod) {

            $where = "";
            if ($lang <> '' && $lang <> self::ALL) { //if language is not set we take all the list
                $where                  = " AND mk.locale_langue = :locale_langue";
                $bind[':locale_langue'] = $lang;
            }
            $sql           = "SELECT mk.keyword, mk.link 
                FROM moderating_keyword mk 
                LEFT JOIN moderating_alert_type mat ON (mk.moderating_alert_type_id = mat.id)
                WHERE mat.code = :code
                $where
                ORDER BY length(keyword) DESC"; //for expression ex: 'dump water'
            $bind[':code'] = $listeType;

            $key = "list_keywords_" . $listeType . "__" . $lang;
            $res = cache_memoire::getAll_cache($sql, $bind, $key, 3600, 1, 0); //get list from database if not present in memcached
        } else {

            if ($lang == '') {
                $lang = 'en'; //I dont handle here the full list
            }

            //for test /!\ I only send in French but I can send in 7 differents languages
            switch ($listeType) {
                case self::KEY_INSULT :
                    $json_sample = '[{"keyword":"connards","link":""},{"keyword":"connasse","link":""},{"keyword":"encul\u00e9","link":""},{"keyword":"connard","link":""},{"keyword":"salope","link":""},{"keyword":"cons","link":""},{"keyword":"con","link":""}]';
                    break;
                case self::KEY_RACIST :
                    $json_sample = '[{"keyword":"gitan","link":""}]';
                    break;
                case self::KEY_ALERT :
                    $json_sample = '[{"keyword":"caravanas","link":""},{"keyword":"prohibido","link":""},{"keyword":"polic\u00eda","link":""},{"keyword":"caravana","link":""},{"keyword":"cerrada","link":""},{"keyword":"cerrado","link":""},{"keyword":"policia","link":""}]';
                    break;
                case self::KEY_ACTIVITE:
                    $json_sample = '[{"keyword":"aire de jeux","link":"jeux_enfants"},{"keyword":"randonn\u00e9es","link":"rando"},{"keyword":"escalade","link":"escalade"},{"keyword":"peche","link":"peche"},{"keyword":"rando","link":"rando"}]';
                    break;
                case self::KEY_SERVICE :
                    $json_sample = '[{"keyword":"\u00e9lectricit\u00e9","link":"electricite"},{"keyword":"toilettes","link":"wc_public"},{"keyword":"poubelle","link":"poubelle"},{"keyword":"vidanger","link":"eau_usee"},{"keyword":"laverie","link":"laverie"},{"keyword":"douches","link":"douche"},{"keyword":"piscine","link":"piscine"},{"keyword":"douche","link":"douche"},{"keyword":"chien","link":"animaux"},{"keyword":"wifi","link":"wifi"},{"keyword":"eau","link":"point_eau"},{"keyword":"4G","link":"donnees_mobile"},{"keyword":"WC","link":"wc_public"},{"keyword":"3G","link":"donnees_mobile"}]';
                    break;
            }
            $res = json_decode($json_sample, true);
        }

        return $res;
    }

    public function getArrayServices(): array
    { //to link icons list
        $services                   = array();
        $services['point_eau']      = 'point_eau';
        $services['eau_noire']      = 'eau_noire';
        $services['eau_usee']       = 'eau_usee';
        $services['poubelle']       = 'poubelle';
        $services['wc_public']      = 'wc_public';
        $services['douche']         = 'douche';
        $services['boulangerie']    = 'boulangerie';
        $services['electricite']    = 'electricite';
        $services['wifi']           = 'wifi';
        $services['piscine']        = 'piscine';
        $services['laverie']        = 'laverie';
        $services['gaz']            = 'gaz';
        $services['lavage']         = 'lavage';
        $services['gpl']            = 'gpl';
        $services['donnees_mobile'] = 'donnees_mobile';
        $services['animaux']        = 'animaux';
        return $services;
    }

    public function getArrayActivites(): array
    { //to link icons list
        $activites                 = array();
        $activites['visites']      = 'visites';
        $activites['windsurf']     = 'windsurf';
        $activites['vtt']          = 'vtt';
        $activites['rando']        = 'rando';
        $activites['escalade']     = 'escalade';
        $activites['eaux_vives']   = 'eaux_vives';
        $activites['peche']        = 'peche';
        $activites['peche_pied']   = 'peche_pied';
        $activites['baignade']     = 'baignade';
        $activites['moto']         = 'moto';
        $activites['point_de_vue'] = 'point_de_vue';
        $activites['jeux_enfants'] = 'jeux_enfants';
        return $activites;
    }

    public function loadListIfDontExist(string $lang_locale = ''): void
    {
        $lang = $lang_locale;
        if (strlen($lang) < 2) {
            $lang = self::ALL;
        }
        if (empty($this->list[$lang])) {
            $this->getAllListWords($lang); //avoid to load several time the array when I'm on a list of 30 commentaries with different languages
        }
    }

    public function getAllListWords(string $lang = ''): void
    {
        $this->list = null;

        $this->list[$lang][self::KEY_INSULT_WORDS]     = $this->getListWords(self::KEY_INSULT, $lang);
        $this->list[$lang][self::KEY_RACIST_WORDS]     = $this->getListWords(self::KEY_RACIST, $lang);
        $this->list[$lang][self::KEY_ALERT_WORDS]      = $this->getListWords(self::KEY_ALERT, $lang);
        $this->list[$lang][self::KEY_SERVICES_WORDS]   = $this->getListWords(self::KEY_SERVICE, $lang);
        $this->list[$lang][self::KEY_ACTIVITIES_WORDS] = $this->getListWords(self::KEY_ACTIVITE, $lang);
    }

    //Because $lang_locale is not always present to dect the lang
    public function langDectect(int $lang_id = 0, $lang_locale = null): string
    {
        $lang = '';
        if (is_null($lang_locale)) {
            if ($lang_id > 0) {
                switch ($lang_id) {
                    case 1:
                        $lang = self::LOCALE_FR;
                        break;
                    case 2: //'en'
                        $lang = self::ALL; //'en' is by default langage so it can be 'pl' or 'pt'
                        break;
                    case 3:
                        $lang = self::LOCALE_DE;
                        break;
                    case 4:
                        $lang = self::LOCALE_ES;
                        break;
                    case 5:
                        $lang = self::LOCALE_IT;
                        break;
                    case 6:
                        $lang = self::LOCALE_NL;
                        break;
                }
            } else {
                $lang = self::ALL;
            }
        } else {
            $lang = $lang_locale;
        }
        return $lang;
    }

    public function processSpam(string $lang): void
    {
        foreach (self::KEYS_WORDS as $key) {
            $this->text_analyse[$key] = $this->findWords($this->list[$lang][$key], $this->getHtmlFormatByKey($key));
        }
        /*
        $this->text_analyse['insults_words']    = $this->findWords($this->list[$lang]['insults_words'], self::HTML_FORMAT_INSULT_WORDS);
        $this->text_analyse['racist_words']     = $this->findWords($this->list[$lang]['racist_words'], self::HTML_FORMAT_ALERT_WORDS);
        $this->text_analyse['alert_words']      = $this->findWords($this->list[$lang]['alert_words'], self::HTML_FORMAT_INSULT_WORDS);
        $this->text_analyse['activities_words'] = $this->findWords($this->list[$lang]['activities_words'], self::HTML_FORMAT_ACTIVITIES_WORDS);
        $this->text_analyse['services_words']   = $this->findWords($this->list[$lang]['services_words'], self::HTML_FORMAT_ACTIVITIES_WORDS);
        */
    }

    public function processData(): void
    {
        $this->emails = $this->getEmailMatches();

        foreach ($this->emails as $email) {
            $replaceHtmlFormat    = sprintf($this->getHtmlFormatByKey(self::KEY_EMAILS), $email);
            $this->text_formatted = str_replace($email, $replaceHtmlFormat, $this->text_formatted);
        }

        $this->sites = $this->getSiteMatches();

        foreach ($this->sites as $site) {
            $replaceHtmlFormat    = sprintf($this->getHtmlFormatByKey(self::KEY_SITES), $site);
            $this->text_formatted = str_replace($site, $replaceHtmlFormat, $this->text_formatted);
        }

        $this->phones = $this->getPhoneMatches();

        foreach ($this->phones as $phone) {
            $replaceHtmlFormat    = sprintf($this->getHtmlFormatByKey(self::KEY_PHONES), $phone);
            $this->text_formatted = str_replace($phone, $replaceHtmlFormat, $this->text_formatted);
        }
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
        foreach ($keyWords as $every) {
            if ($every['keyword']) {
                $word    = $every['keyword'];
                $matches = $this->getMatchesForWord($this->text_formatted, $word);

                $wordHtml = sprintf($replaceHtmlFormat, $word);
                $index    = count($matches) - 1;
                while ($index >= 0) {
                    $match       = $matches[$index];
                    $matchWord   = $match[0];
                    $matchOffset = $match[1];

                    if (!$this->isWordAlreadyReplaced($this->text_formatted, $matchOffset)) {
                        $this->text_formatted = $this->replaceMatch($wordHtml, $matchWord, $matchOffset);
                        $words[]              = $every;
                    }
                    $index--;
                }
            }
        }

        return array_intersect_key($words, array_unique(array_map('serialize', $words)));
        //return array_unique($words);
    }

    public function replaceMatch(string $wordHtml, string $matchWord, int $matchOffset)
    {
        $output       = $this->text_formatted;
        $beginning    = substr($output, 0, $matchOffset);
        $lengthForEnd = strlen($output) - $matchOffset - strlen($matchWord);
        $output       = $this->text_formatted;
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
        preg_match_all($pattern, $this->text_formatted, $matches);
        $emailMatches = $matches[0];

        return $emailMatches;
    }

    public function getPhoneMatches()
    {
        $pattern = '/(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{2}){4}|\d{2}(?:[\s.-]?\d{3}){2})/';

        preg_match_all($pattern, $this->text_formatted, $matches);
        $phoneMatches = $matches[0];

        return $phoneMatches;
    }

    public function getSiteMatches()
    {
        $sites = [];

        $pattern = '/(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/ AND !(\@))?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?/';
        preg_match_all($pattern, $this->text_formatted, $matches, PREG_OFFSET_CAPTURE);
        $siteMatches = $matches[0];

        foreach ($siteMatches as $key => $match) {
            $word   = $match[0];
            $offset = $match[1];

            if (
                (!empty($this->text_formatted[$offset - 1]) && $this->text_formatted[$offset - 1] == '@') ||
                (!empty($this->text_formatted[$offset + strlen($word)]) && $this->text_formatted[$offset + strlen($word)] == '@')
            ) {
                unset($siteMatches[$key]);
            } else {
                $sites[] = $word;
            }
        }

        return $sites;
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

}

?>
