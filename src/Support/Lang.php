<?php

namespace Hani221b\Grace\Support;
use Hani221b\Grace\Models\Language;

class Lang {
    public static function GetDefaultLanguage()
    {
        $default_language = Language::where('default', 1)->select('abbr')->first();
        return $default_language->abbr;
    }

    public static function GetActivatedLanguage()
    {
        return Language::Selection()->where('status', 1)->get();
    }
}
