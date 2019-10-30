# Text Analyser
       
To use : `SpamAnalyser.php`     

Example : 
```php
use Ltv\Service\SpamAnalyzer;

$text = 'Super camping mais le patron est un vrai con. Dommage que la Wifi est constante et d'un bon débit';

$spamAnalyzer = new SpamAnalyzer();
$spamAnalyzer->setTextInitial($text1)->process();

var_dump($ta->getTextInitial());
var_dump($ta->getTextFormatted());
var_dump($ta->getTextAnalyse());
```

### Colors html
To change the html replacement you can find constants : 
```php
const HTML_FORMAT_INSULT_WORDS     = '<red>%s</red>';
const HTML_FORMAT_RACIST_WORDS     = '<red>%s</red>';
const HTML_FORMAT_ALERT_WORDS      = '<orange>%s</orange>';
const HTML_FORMAT_SERVICES_WORDS   = '<blue>%s</blue>';
const HTML_FORMAT_ACTIVITIES_WORDS = '<green>%s</green>';
```
You can just edit this constants to change html replacement but don't miss `%s`. It's important.

### Regards
Feel free to contact me for any questions of for further work
* Anton LYTVYNOV
* [LinkedIn](https://www.linkedin.com/in/anton-lytvynov/)
* lytvynov.anton@gmail.com

### Licence MIT
