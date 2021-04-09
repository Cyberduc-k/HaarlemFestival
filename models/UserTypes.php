<?php

// Used as an alternative for enums to improve readability
abstract class UserTypes {
    const CLIENT = 0;
    const VOLUNTEER = 1;
    const SUPERADMIN = 2;

    // Access levels of different pages
    const ACCESSLEVELS = [
        "home.php" => self::CLIENT,
        "create.php" => self::SUPERADMIN,
        "createInvoice.php" => self::VOLUNTEER,
        "edit.php" => self::CLIENT,
        "viewUsers.php" => self::VOLUNTEER,
        "changeAvatar.php" => self::CLIENT,
        "exportToAPI.php" => self::SUPERADMIN,
        "exportActs.php" => self::SUPERADMIN,
        "exportInvoices.php" => self::SUPERADMIN,
        "exportTickets.php" => self::SUPERADMIN,
        "exportUsers.php" => self::SUPERADMIN,
        "viewApiKeys.php" => self::SUPERADMIN,
        "export.php" => self::SUPERADMIN
    ];

    // Int to type string
    public static function getType(int $type): String {
        switch ($type) {
            case 0:
                return "Client";
            case 1:
                return "Volunteer";
            case 2:
                return "Superadmin";
        }

        return "Couldn't get type";
    }
}
?>
