<?php

namespace Ltv\Service;

class DataProvider
{
    const LOCALE_EN = 'en';
    const LOCALE_FR = 'fr';
    const LOCALE_DE = 'de';
    const LOCALE_IT = 'it';
    const LOCALE_ES = 'es';
    const LOCALE_NL = 'nl';

    /**
     * @var string
     */
    private $data;

    public function __construct()
    {
        $json       = '{"de":{"activity":[{"keyword":"kinderspielm\u00f6glichkeiten"},{"link":"jeux_enfants"},{"keyword":"spazierm\u04e7glichkeiten"},{"link":"rando"},{"keyword":"kindern spielplatz"},{"link":"jeux_enfants"},{"keyword":"kinder spielplatz"},{"link":"jeux_enfants"},{"keyword":"wandergebiet"},{"link":"rando"},{"keyword":"Schwimmbad"},{"link":"baignade"},{"keyword":"wanderung"},{"link":"rando"},{"keyword":"wanderweg"},{"link":"rando"},{"keyword":"klettern"},{"link":"escalade"},{"keyword":"wandern"},{"link":"rando"},{"keyword":"angeln"},{"link":"peche"}],"alert":[{"keyword":"H\u00f6henbegrenzung"},{"link":""},{"keyword":"Strafzettel"},{"link":""},{"keyword":"geschlossen"},{"link":""},{"keyword":"wohnwagen"},{"link":"0"},{"keyword":"verboten"},{"link":""},{"keyword":"dachzelt"},{"link":"0"},{"keyword":"polizei"},{"link":""},{"keyword":"privat"},{"link":""},{"keyword":"Strafe"},{"link":""},{"keyword":"zelt"},{"link":"0"}],"service":[{"keyword":"entsorgungsm\u00f6glichkeiten"},{"link":"eau_usee"},{"keyword":"schwarzwasserentsorgung"},{"link":"eau_noire"},{"keyword":"entsorgungsm\u00f6glichkeit"},{"link":"eau_usee"},{"keyword":"frischwasserversorgung"},{"link":"point_eau"},{"keyword":"grauwasserentsorgung"},{"link":"eau_usee"},{"keyword":"chemieklo entleerung"},{"link":"eau_noire"},{"keyword":"grauwasserentleerung"},{"link":"eau_usee"},{"keyword":"toilettenentsorgung"},{"link":"eau_noire"},{"keyword":"entsorgunganlagen"},{"link":"eau_usee"},{"keyword":"br\u04e7tchenservice"},{"link":"boulangerie"},{"keyword":"br\u00f6tchenservice"},{"link":"boulangerie"},{"keyword":"entsorgunganlage"},{"link":"eau_usee"},{"keyword":"chemie toiletten"},{"link":"eau_noire"},{"keyword":"chemietoiletten"},{"link":"eau_noire"},{"keyword":"chemie toilette"},{"link":"eau_noire"},{"keyword":"mullentsorgung"},{"link":"poubelle"},{"keyword":"stromanschluss"},{"link":"electricite"},{"keyword":"waschmaschinen"},{"link":"laverie"},{"keyword":"waschmaschine"},{"link":"laverie"},{"keyword":"frischwasser"},{"link":"point_eau"},{"keyword":"trinkwasser"},{"link":"point_eau"},{"keyword":"mulltonnen"},{"link":"poubelle"},{"keyword":"wasserhahn"},{"link":"point_eau"},{"keyword":"toiletten"},{"link":"wc_public"},{"keyword":"br\u00f6tchen"},{"link":"boulangerie"},{"keyword":"mulleimer"},{"link":"poubelle"},{"keyword":"chemie wc"},{"link":"eau_noire"},{"keyword":"abwasser"},{"link":"eau_usee"},{"keyword":"trockner"},{"link":"laverie"},{"keyword":"toilette"},{"link":"wc_public"},{"keyword":"plumsklo"},{"link":"wc_public"},{"keyword":"duschen"},{"link":"douche"},{"keyword":"dixiklo"},{"link":"wc_public"},{"keyword":"Wasser"},{"link":"point_eau"},{"keyword":"dusche"},{"link":"douche"},{"keyword":"backer"},{"link":"boulangerie"},{"keyword":"hunde"},{"link":"animaux"},{"keyword":" gpl "},{"link":"gpl"},{"keyword":"strom"},{"link":"electricite"},{"keyword":"wlan "},{"link":"wifi"},{"keyword":"brot"},{"link":"boulangerie"},{"keyword":"wifi"},{"link":"wifi"},{"keyword":"mull"},{"link":"poubelle"},{"keyword":"hund"},{"link":"animaux"},{"keyword":"pool"},{"link":"piscine"},{"keyword":"klo"},{"link":"wc_public"},{"keyword":"4G"},{"link":"wifi"},{"keyword":"3G"},{"link":"wifi"},{"keyword":"wc"},{"link":"wc_public"}]},"en":{"alert":[{"keyword":"dumpster diving"},{"link":""},{"keyword":"no overnight"},{"link":""},{"keyword":"no overnight"},{"link":""},{"keyword":"bin diving"},{"link":""},{"keyword":"Policemen"},{"link":""},{"keyword":"Policeman"},{"link":""},{"keyword":"forbidden"},{"link":""},{"keyword":"forbidden"},{"link":""},{"keyword":"caravans"},{"link":"0"},{"keyword":"robbery"},{"link":""},{"keyword":"caravan"},{"link":""},{"keyword":"private"},{"link":""},{"keyword":"police"},{"link":""},{"keyword":"closed"},{"link":""},{"keyword":"tents"},{"link":"0"},{"keyword":"tent"},{"link":""}],"service":[{"keyword":"black water"},{"link":"eau_noire"},{"keyword":"electricity"},{"link":"electricite"},{"keyword":"grey water"},{"link":"eau_usee"},{"keyword":"toilets"},{"link":"wc_public"},{"keyword":"showers"},{"link":"douche"},{"keyword":"shower"},{"link":"douche"},{"keyword":"water"},{"link":"point_eau"},{"keyword":"pool "},{"link":"piscine"},{"keyword":"wifi"},{"link":"wifi"},{"keyword":"dog"},{"link":"animaux"}]},"es":{"alert":[{"keyword":"prohibido"},{"link":""},{"keyword":"caravanas"},{"link":""},{"keyword":"caravana"},{"link":""},{"keyword":"polic\u00eda"},{"link":""},{"keyword":"cerrada"},{"link":""},{"keyword":"cerrado"},{"link":""},{"keyword":"policia"},{"link":""}],"service":[{"keyword":"agua"},{"link":"point_eau"},{"keyword":"wifi"},{"link":"wifi"}]},"fr":{"activity":[{"keyword":"aire de jeux"},{"link":"jeux_enfants"},{"keyword":"randonn\u00e9es"},{"link":"rando"},{"keyword":"escalade"},{"link":"escalade"},{"keyword":"peche"},{"link":"peche"},{"keyword":"rando"},{"link":"rando"}],"alert":[{"keyword":"n existe plus"},{"link":""},{"keyword":"inaccessible"},{"link":"0"},{"keyword":"introuvable"},{"link":""},{"keyword":"supprimer"},{"link":""},{"keyword":"barri\u00e8re"},{"link":""},{"keyword":"caravanes"},{"link":""},{"keyword":"interdit"},{"link":""},{"keyword":"caravane"},{"link":""},{"keyword":"ill\u00e9gal"},{"link":""},{"keyword":"tentes"},{"link":""},{"keyword":"ferm\u00e9"},{"link":""},{"keyword":"Ferm\u00e9"},{"link":""},{"keyword":"police"},{"link":"0"},{"keyword":"tente"},{"link":""},{"keyword":"tente"},{"link":""}],"insult":[{"keyword":"connasse"},{"link":""},{"keyword":"connards"},{"link":""},{"keyword":"connard"},{"link":""},{"keyword":"encul\u00e9"},{"link":""},{"keyword":"salope"},{"link":""},{"keyword":"cons"},{"link":""},{"keyword":"con"},{"link":""}],"racist":[{"keyword":"gitan"},{"link":""}],"service":[{"keyword":"\u00e9lectricit\u00e9"},{"link":"electricite"},{"keyword":"toilettes"},{"link":"wc_public"},{"keyword":"poubelle"},{"link":"poubelle"},{"keyword":"vidanger"},{"link":"eau_usee"},{"keyword":"piscine"},{"link":"piscine"},{"keyword":"douches"},{"link":"douche"},{"keyword":"laverie"},{"link":"laverie"},{"keyword":"douche"},{"link":"douche"},{"keyword":"chien"},{"link":"animaux"},{"keyword":"wifi"},{"link":"wifi"},{"keyword":"eau"},{"link":"point_eau"},{"keyword":"3G"},{"link":"donnees_mobile"},{"keyword":"4G"},{"link":"donnees_mobile"},{"keyword":"WC"},{"link":"wc_public"}]},"it":{"alert":[{"keyword":"roulotte"},{"link":"0"},{"keyword":"caravani"},{"link":"0"},{"keyword":"caravan"},{"link":"0"},{"keyword":"police"},{"link":"0"},{"keyword":"chiuso"},{"link":""},{"keyword":"tenda"},{"link":"0"},{"keyword":"tendi"},{"link":"0"}],"service":[{"keyword":"scarico acqua"},{"link":"eau_usee"},{"keyword":"acqua grigia"},{"link":"eau_usee"},{"keyword":"elettricit\u00e0"},{"link":"electricite"},{"keyword":"acque grigie"},{"link":"eau_usee"},{"keyword":"carico acqua"},{"link":"point_eau"},{"keyword":"elettricita"},{"link":"electricite"},{"keyword":"acque nere"},{"link":"eau_noire"},{"keyword":"acqua nera"},{"link":"eau_noire"},{"keyword":"lavanderia"},{"link":"laverie"},{"keyword":"gabinetto"},{"link":"wc_public"},{"keyword":"cassetta"},{"link":"wc_public"},{"keyword":" cane "},{"link":"animaux"},{"keyword":"doccia"},{"link":"douche"},{"keyword":" cani "},{"link":"animaux"},{"keyword":"docce"},{"link":"douche"},{"keyword":"acqua"},{"link":"point_eau"},{"keyword":"wifi"},{"link":"wifi"},{"keyword":"wc"},{"link":"wc_public"}]},"nl":{"service":[{"keyword":"water"},{"link":"point_eau"},{"keyword":"wifi"},{"link":"wifi"}]}}';
        $this->data = json_decode($json, true);
    }

    /**
     * @return array
     */
    public function getDataAll(): array
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getLocales(): array
    {
        return array_keys($this->data);
    }

    /**
     * @param string $locale
     *
     * @return array
     */
    public function getDataByLocale(string $locale): array
    {
        return $this->data[$locale] ?? [];
    }
}
