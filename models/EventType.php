<?php

abstract class EventType {
    public const Dance    = 0;
    public const Food     = 1;
    public const Historic = 2;
    public const Jazz     = 3;

    // Int to type string
    public static function getType(int $type): String {
        switch ($type) {
            case 0:
                return "Dance";
            case 1:
                return "Food";
            case 2:
                return "Historic";
            case 3:
                return "Jazz";
        }

        return "Couldn't get type";
    }
}

?>
