# Spam Analyser
       
Use : `SpamAnalyser.php`     

### How to use

```php
use Ltv\Service\SpamAnalyzer;

$text = 'Il est con, non ? détenté +33 6 60 58 74 74 conçue con some@gmail.com first.com second.org Con lorem ipsum';
$spamAnalyzer = (new SpamAnalyzer($text))->process();
```

##### Output
```php
$ta->getOutput();
```
```
'Il est <red>con</red>, non ? détenté <phone>+33 6 60 58 74 74</phone> conçue <red>con</red> <email>some@gmail.com</email> <site>first.com</site> <site>second.org</site> <red>con</red> lorem ipsum',
```

##### Process result
```php
$spamAnalyzer->getProcessResult()
```
```
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
];
```

### Colors html
##### Default html
To change the html replacement you can find constants 
```php
const HTML_FORMAT_INSULT_WORDS     = '<red>%s</red>';
const HTML_FORMAT_RACIST_WORDS     = '<red>%s</red>';
const HTML_FORMAT_ALERT_WORDS      = '<orange>%s</orange>';
const HTML_FORMAT_SERVICES_WORDS   = '<blue>%s</blue>';
const HTML_FORMAT_ACTIVITIES_WORDS = '<green>%s</green>';
```
And the mapping :
```php
const MAPPING = [
    self::KEY_INSULT_WORDS     => self::HTML_FORMAT_INSULT_WORDS,
    ...
```

##### Bad words can be set by
```
$spamAnalyzer = (new SpamAnalyzer($text))
    ->setBadWords(
        [
            'words' => [
                'grey water', 
                'showers', 
                'water', 
                'dog',
            ],
        ]
    )->process();
```

### Tests 
```
composer install #install php unit dependencies
./vendor/bin/phpunit  --verbose --bootstrap  vendor/autoload.php tests;
```

### Contacts
* Anton LYTVYNOV
* [LinkedIn](https://www.linkedin.com/in/anton-lytvynov/)
* [Site ](http://lytvynov-anton.com/)
* lytvynov.anton@gmail.com
