### Tinymce plugin builder
Dieses Plugin dient als Grundlage um TinyMCE mit wenig Aufwand mit zusätzlichen selber geschriebenen Plugins nachzuruesten. 
Als Vorlage kann https://github.com/markocupic/tinymce_newslink dienen. 

## Plugin in der config.php konfigurieren

```php
<?php
// system/modules/tinymce_mysuperplugin/config/config.php


// Add stylesheet
$GLOBALS['TL_CSS'][] = 'system/modules/tinymce_newslink/assets/css/newslink.css';

// Add a plugin to the tinymce editor
$GLOBALS['TINYMCE']['SETTINGS']['PLUGINS'][] = 'newslink';

// Add a button to the toolbar in tinymce editor
$GLOBALS['TINYMCE']['SETTINGS']['TOOLBAR'][] = 'newslink';

// Add a new config row to the tinymce.init method (string)
$GLOBALS['TINYMCE']['SETTINGS']['CONFIG_ROW']['test_string'] = "'This is a test string, and you have to quote it with a single quote.'";

// Add a new config row to the tinymce.init method (json_encoded array from a PHP class)
$GLOBALS['TINYMCE']['SETTINGS']['CONFIG_ROW']['newslink_news_data'] = json_encode(TinymceNewslink\TinymceNewslink::getContaoNewsArchivesAsJSON());

// Add a new config row to the tinymce.init method (json_encoded array from a language file)
$GLOBALS['TINYMCE']['SETTINGS']['CONFIG_ROW']['newslink_language_data'] = json_encode($GLOBALS['TL_LANG']['TINYMCE']['NEWSLINK']);

```



