<?php

namespace App\Services;

class Translation
{
    private static $language = 'en';
    private static $translations = [];
    private static $supportedLanguages = [
        'en', 'es', 'fr', 'de', 'it', 'pt', 'nl', 'pl', 'ru', 'uk', 'tr', 'ar',
        'zh', 'ja', 'ko', 'th', 'vi', 'id', 'hi', 'bn', 'sv', 'da', 'no', 'fi',
        'el', 'he', 'cs', 'hu', 'ro', 'sk', 'am'
    ];

    public static function setLanguage($lang)
    {
        $lang = strtolower(trim($lang));
        if (!in_array($lang, self::$supportedLanguages)) {
            $lang = 'en';
        }
        self::$language = $lang;
        self::loadTranslations();
    }

    public static function getLanguage()
    {
        return self::$language;
    }

    private static function loadTranslations()
    {
        $file = __DIR__ . '/../../storage/translations/' . self::$language . '.json';
        
        if (file_exists($file)) {
            $content = file_get_contents($file);
            self::$translations = json_decode($content, true) ?? [];
        } else {
            self::$translations = [];
        }
    }

    public static function translate($key, $default = '')
    {
        if (empty(self::$translations)) {
            self::loadTranslations();
        }

        $keys = explode('.', $key);
        $value = self::$translations;

        foreach ($keys as $k) {
            if (is_array($value) && isset($value[$k])) {
                $value = $value[$k];
            } else {
                return $default ?: $key;
            }
        }

        return $value ?? ($default ?: $key);
    }

    public static function t($key, $default = '')
    {
        return self::translate($key, $default);
    }
}
