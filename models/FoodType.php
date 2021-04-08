<?php

abstract class FoodType {
    public const French = 0;
    public const Italian = 1;
    public const Dutch = 2;
    public const Mexican = 3;
    public const Asian = 4;
    public const NA = 5;

    // Int to type string
    public static function getType(int $type): String {
        switch ($type) {
            case 0:
                return "French";
            case 1:
                return "Italian";
            case 2:
                return "Dutch";
            case 3:
                return "Mexican";
            case 4:
                return "Asian";
            case 5:
                return "NA";
        }

        return "Couldn't get type";
    }
}


?>
