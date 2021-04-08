<?php

// Used as an alternative for enums to improve readability
abstract class UserTypes {
    const USER = 0;
    const ADMIN = 1;
    const SUPERADMIN = 2;

    // Access levels of different pages
    const ACCESSLEVELS = [
        "home.php" => self::USER,
        "create.php" => self::SUPERADMIN,
        "createInvoice.php" => self::SUPERADMIN,
        "edit.php" => self::USER,
        "viewUsers.php" => self::ADMIN,
        "changeAvatar.php" => self::USER,
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
                return "User";
            case 1:
                return "Admin";
            case 2:
                return "Superadmin";
        }

        return "Couldn't get type";
    }
}
?>
