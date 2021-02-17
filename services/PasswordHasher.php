<?php
require_once(__DIR__ . "/../models/User.php");

class PasswordHasher
{
    private const SALT_LENGTH = 32;

    //Hash the plaintext password of a user and store the used salt
    public function hash(&$user): bool{
        try {
            $salt = bin2hex(random_bytes(self::SALT_LENGTH));
            $concat = $salt.$user->getPassword();
            $hash = password_hash($concat, PASSWORD_DEFAULT);

            $user->setPassword($hash);
            $user->setSalt($salt);

            return true;
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            return false;
        }
    }
}
?>