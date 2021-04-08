<?php

abstract class FoodType {
    public const French = 0;
    public const Italian = 1;
    public const Dutch = 2;
    public const Mexican = 3;
    public const Asian = 4;
    public const NA = 5;

    public static function getType(String $foodTypeString): int {
        switch ($foodTypeString) {
            case "French":
                return 0;
            case "Italian":
                return 1;
            case "Dutch":
                return 2;
            case "Mexican":
                return 3;
            case "Asian":
                return 4;
        }

        return 5;
    }
}

?>
