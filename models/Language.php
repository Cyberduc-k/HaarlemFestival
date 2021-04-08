<?php

abstract class Language {
    public const Dutch   = 0;
    public const English = 1;
    public const Chinese = 2;

    public static function getLanguage(int $lang): String {
        switch ($lang) {
            case 0:
                return "Dutch";
            case 1:
                return "English";
            case 2:
                return "Chinese";
        }

        return "Couldn't get language";
    }
}

?>
