<?php

abstract class TicketType {
    public const Normal    = 0;
    public const SingleDay = 1;
    public const ThreeDay  = 2;
    public const Family    = 3;

    // Int to type string
    public static function getType(int $type): String {
        switch ($type) {
            case 0:
                return "Single Access Pass";
            case 1:
                return "Single Day Pass";
            case 2:
                return "Three Day Pass";
            case 3:
                return "Family Ticket";
        }

        return "Couldn't get type";
    }
}

?>
