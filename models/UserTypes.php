<?php

// Used as an alternative for enums to improve readability
abstract class UserTypes {
    const USER = 0;
    const ADMIN = 1;
    const SUPERADMIN = 2;

    // Access levels of different pages
    const ACCESSLEVELS = ["home.php" => 0, "create.php" => 2, "edit.php" => 0, "viewUsers.php" => 1, "changeAvatar.php" => 0];

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
