<?php

require_once(__DIR__ . "/../models/User.php");
require_once(__DIR__ . "/../models/EditKey.php");
require_once(__DIR__ . "/../models/EditEmailKey.php");
require_once(__DIR__ . "/../models/EditStash.php");
require_once(__DIR__ . "/../DAL/UserDAO.php");
require_once(__DIR__ . "/../models/UserTypes.php");
require_once("PasswordHasher.php");
require_once("ServiceUtils.php");
require_once ("UserEditsService.php");
require_once ("EditKeyService.php");
require_once ("UserService.php");
require_once ("MailService.php");

// Only start a session when necessary
if(!isset($_SESSION)) session_start();

const AVATAR_DIR = "uploads/avatars/";
const DEFAULT_AVATAR = AVATAR_DIR."default.png";

class UserService extends ServiceUtils {
    private UserDAO $dao;

    public function __construct() {
        $this->dao = new UserDAO();
    }

    // Get all users
    public function getAll(): ?array {
        try {
            $stmt = $this->dao->getAll();
            $num = $stmt->rowCount();

            if ($num > 0) {
                $users = array();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($users, $this->rowToUser($row));
                }

                return $users;
            }

            return null;
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            // Return an empty stmt
            return null;
        }
    }

    // Get all users as array
    public function getAllAsArray(): ?array {
        try {
            $stmt = $this->dao->getAll();
            $num = $stmt->rowCount();

            if ($num > 0) {
                $users = array();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($users, $row);
                }

                return $users;
            }

            return null;
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            // Return an empty stmt
            return null;
        }
    }

    // Try to get a user by id
    public function getById(int $id): ?User {
        try {
            $stmt = $this->dao->getById($id);
            $num = $stmt->rowCount();

            if ($num > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                return $this->rowToUser($row);
            }

            return null;
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            // Return an empty user
            return null;
        }
    }

    //Try to get a users type by id
    public function getTypeById(int $id): ?int {
        try {
            $stmt = $this->dao->getTypeById($id);
            $num = $stmt->rowCount();

            if ($num > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                return (int)$row["usertype"];
            }

            return null;
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            // Return an empty user
            return null;
        }
    }

    // Try to get a user by email
    public function getByEmail(String $email): ?User {
        try {
            $stmt = $this->dao->getByEmail($email);
            $num = $stmt->rowCount();

            if ($num > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                return $this->rowToUser($row);
            }

            return null;
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            // Return an empty stmt
            return null;
        }
    }

    // Get all users that match these arguments
    public function getWithArgs(array $args): ?array {
        try {
            $stmt = $this->dao->getWithArgs($args);
            $num = $stmt->rowCount();

            if ($num > 0) {
                $users = array();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($users, $this->rowToUser($row));
                }

                return $users;
            }

            return null;
        } catch (Exception $e){
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            // Return an empty stmt
            return null;
        }
    }

    // Create a new user
    public function create(User $user): bool{
        try {
            // Check if there already is a user with this mail address
            if ($this->mailExists($user->getEmail())){
                $_SESSION["createError"] = "An user is already registered with this email address";
                return false;
            }

            // Hash the password of the user and store the salt
            $hasher = new PasswordHasher();
            $hasher->hash($user);

            if ($this->dao->create($user))
                return true;

            return false;
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            // Return an empty stmt
            return false;
        }
    }

    // Change the password of an user
    public function changePassword(int $id, String $newPassword, String $validatePassword): bool{
        try {
            // Make sure passwords are the same
            if ($newPassword == $validatePassword) {
                $user = $this->getById($id);

                // Make sure the user exists
                if (!is_null($user)) {
                    $user->setPassword($newPassword);

                    // Hash the password of the user and store the salt
                    $hasher = new PasswordHasher();
                    $hasher->hash($user);

                    if ($this->update($user))
                        return true;
                } else {
                    $_SESSION["createPasswordError"] = "Cannot find this user, try again";
                    return false;
                }
            } else {
                $_SESSION["createPasswordError"] = "passwords do not match";
                return false;
            }

            $_SESSION["createPasswordError"] = "Something went wrong while updating password";
            return false;
        } catch (Exception $e) {
            $_SESSION["createPasswordError"] = "Something went wrong while updating password".$e;
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            // Return an empty stmt
            return false;
        }
    }

    // Search if a mail address already exists
    // I could search and fill a user variable for this email but this is more resource efficient
    public function mailExists(String $email): bool {
        try {
            $stmt = $this->dao->mailExists($email);
            $num = $stmt->rowCount();

            // Rowcount is 1 when a record with that mail has been found
            return ($num == 1);
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            // Return an empty stmt
            return false;
        }
    }

    // Search if an id already exists
    // I could search and fill a user variable for this id but this is more resource efficient
    public function idExists(int $id): bool {
        try {
            $stmt = $this->dao->idExists($id);
            $num = $stmt->rowCount();

            // Rowcount is 1 when a record with that mail has been found
            return ($num == 1);
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            // Return an empty stmt
            return false;
        }
    }

    // Validate credentials
    public function validateLogin(String $email, String $password): bool {
        try {
            // Make sure the mail is valid
            if ($this->mailExists($email)) {
                $user = $this->getByEmail($email);

                // Make sure the mail is valid and complete
                if (!is_null($user)) {
                    $hash = $user->getPassword();
                    $concat = $user->getSalt().$password;

                    // Verify password hash
                    if (password_verify($concat, $hash)) {
                        return true;
                    } else {
                        $_SESSION["loginError"] = "Wrong password";
                        return false;
                    }
                } else {
                    $_SESSION["loginError"] = "Something went wrong on our side";
                    return false;
                }
            } else {
                $_SESSION["loginError"] = "No user found with this email";
                return false;
            }
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            return false;
        }
    }

    // Create a stash to edit this user, should still be confirmed whereas Update() is immediate
    public function editUser(User $updatedUser, bool $isImmediate): bool {
        try {
            $userEditsService = new UserEditsService();

            //Get current user to fill empty fields of the updated user
            $currentUser = $this->getById($updatedUser->getId());

            if (is_null($currentUser)) {
                $_SESSION["editError"] = "User not found";
                return false;
            }

            // check if password is changed
            if ($updatedUser->isSet("password")) {
                $hasher = new PasswordHasher();
                $hasher->hash($updatedUser);
            } else {
                $updatedUser->setPassword($currentUser->getPassword());
                $updatedUser->setSalt($currentUser->getSalt());
            }

            // check if usertype hasnt been changed
            if (!$updatedUser->isSet("usertype"))
                $updatedUser->setUsertype($currentUser->getUsertype());

            // No need to send mails when it is being changed by an admin
            if ($isImmediate) {
                $userService = new UserService();

                if ($userService->update($updatedUser)) {
                    return true;
                } else{
                    $_SESSION["editError"] = "Couldnt instantly edit user without confirmation";
                    return false;
                }
            }

            // check if mail address has changed, if it is 2 mails should be send
            if ($currentUser->getEmail() != $updatedUser->getEmail()) {
                if ($this->mailExists($updatedUser->getEmail())) {
                    $_SESSION["editError"] = "This mail is already in use";
                    return false;
                }

                $stash = new EditStash();
                $editEmailKey = new EditEmailKey();
                $nextStashId = $userEditsService->getNextId();

                if (is_null($nextStashId)) {
                    $_SESSION["editError"] = "Couldn't determine the next open spot, sorry";
                    return false;
                }

                // Generate a stash for this edit
                if (
                    $stash->generateFromUser($updatedUser, $nextStashId) &&
                    $editEmailKey->fillForId($nextStashId, $currentUser->getEmail(), $updatedUser->getEmail())
                ){
                    if ($userEditsService->createEditStashAndEditEmailKey($stash, $editEmailKey)) {
                        // Send mail with key to both emails
                        $subject = "Edit request";
                        $oldBody = "Copy the following link to confirm your edits: <br>".$editEmailKey->getOldLink();
                        $newBody = "Copy the following link to confirm your edits: <br>".$editEmailKey->getNewLink();

                        if (
                            MailService::getInstance()->sendMail($currentUser->getEmail(), $subject, $oldBody) &&
                            MailService::getInstance()->sendMail($updatedUser->getEmail(), $subject, $newBody)
                        ){
                            return true;
                        } else {
                            $_SESSION["editError"] = "Sending the mail failed, sorry";
                            return false;
                        }
                    } else {
                        $_SESSION["editError"] = "Couldn't store key and stash";
                        return false;
                    }
                } else {
                    $_SESSION["editError"] = "stash and key not generated";
                    return false;
                }
            }
            // Mail has not changed
            else {
                $stash = new EditStash();
                $editKey = new EditKey();
                echo "mail not changed";
                $nextStashId = $userEditsService->getNextId();

                if (is_null($nextStashId)) {
                    $_SESSION["editError"] = "Couldn't determine the next open spot, sorry";
                    return false;
                }

                // Generate a stash for this edit
                if ($stash->generateFromUser($updatedUser, $nextStashId) && $editKey->fillForId($nextStashId)) {
                    if ($userEditsService->createEditStashAndEditKey($stash, $editKey)) {
                        // Send mail with key
                        $subject = "Edit request";
                        $body = "Copy the following link to confirm your edits: <br>".$editKey->getLink();

                        if (MailService::getInstance()->sendMail($updatedUser->getEmail(), $subject, $body)) {
                            return true;
                        } else {
                            $_SESSION["editError"] = "Sending the mail failed, sorry";
                            return false;
                        }
                    } else {
                        $_SESSION["editError"] = "Couldn't store key and stash";
                        return false;
                    }
                } else {
                    $_SESSION["editError"] = "stash and key not generated";
                    return false;
                }
            }
        } catch (Exception $e) {
            $_SESSION["editError"] = "Something went wrong on our side, please contact the administrator".$e;
            return false;
        }
    }

    // Update all columns of a user
    public function update(User $user): bool {
        if ($this->dao->update($user))
            return true;

        return false;
    }

    // Delete a user
    public function delete(int $id): bool {
        if ($this->dao->delete($id))
            return true;

        return false;
    }

    // Get the avatar of a user, if it isn't found return default
    public function getAvatarByEmail(string $email): ?string {
        try {
            $stmt = $this->dao->getAvatarByEmail($email);
            $num = $stmt->rowCount();

            // Return avatar if set, else return default avatar
            if ($num > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return (string)$row["avatar"];
            } else {
                return DEFAULT_AVATAR;
            }
        } catch(Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            // Return an empty stmt
            return null;
        }
    }

    // Change the avatar of an user
    public function setAvatar(int $id, array $avatar): bool {
        try {
            // Check if there is an user with this id
            if (!$this->idExists($id)) {
                $_SESSION["avatarError"] = "No user found with this ID";
                return false;
            }

            // Check if the file is not too big
            if ($avatar['size'] > 200000) {
                $_SESSION["avatarError"] = "Please dont upload files bigger then 200kb";
                return false;
            }

            // Valid file mimes
            $validMimes = [
                "image/png"=> ".png",
                "image/x-png" => ".png",
                "image/jpeg" => ".jpg",
                "image/pjpeg" => ".jpg"
            ];

            // make sure mime is valid
            if (!array_key_exists($avatar["type"], $validMimes)) {
                $_SESSION["avatarError"] = "Please only upload png or jpeg images";
                return false;
            }

            // Create path for the image
            $path = AVATAR_DIR . time() . '-' . $this->clean($avatar["name"]);

            // Make sure path doesnt exist
            if (file_exists($path)) {
                $_SESSION["avatarError"] = "Please upload a file with a different name";
                return false;
            }

            // Try to upload the image
            if (move_uploaded_file($avatar["tmp_name"], $path)) {
                if($this->dao->setAvatar($id, $path)) {
                    return true;
                } else{
                    unlink($path);
                    $_SESSION["avatarError"] = "Couldn't upload file to db";
                    return false;
                }
            } else {
                $_SESSION["avatarError"] = "Couldn't store uploaded file".$path;
                return false;
            }
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            // Return an empty stmt
            return false;
        }
    }
}

?>
