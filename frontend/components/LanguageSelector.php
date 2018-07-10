<?php
namespace frontend\components;

use yii\base\BootstrapInterface;

class LanguageSelector implements BootstrapInterface
{
    public static $supportedLanguages = ['en-US', 'ru-RU', 'uk-UA'];

    public function bootstrap($app)
    {
        $cookieLanguage = $app->request->cookies['language'];

        if (($cookieLanguage) && in_array($cookieLanguage, self::$supportedLanguages)) {
            $app->language = $cookieLanguage;
        }
    }
}
