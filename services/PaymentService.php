<?php

use Mollie\Api\Exceptions\ApiException;
use Mollie\Api\MollieApiClient;

require_once(__DIR__ . "/../libs/Mollie/vendor/autoload.php");
require_once(__DIR__ . "/../libs/Mollie/initialize.php");
require_once(__DIR__ . "/../services/UserService.php");
require_once(__DIR__ . "/../DAL/PaymentDAO.php");

require_once("ServiceUtils.php");
require_once("UserService.php");
require_once("MailService.php");

class PaymentService extends ServiceUtils
{
    private PaymentDAO $dao;
    private $userId;

    public function __construct()
    {
        $this->dao = new PaymentDAO();
    }

    function createPayment(int $userId, string $amount): bool
    {
        $us = new UserService();

        $mollie = new MollieApiClient();
        $mollie->setApiKey("test_Ds3fz4U9vNKxzCfVvVHJT2sgW5ECD8");
        $user = $us->getById($userId);

        try {
            // create random and unique order ID
            $orderId = $this->createUniqueOrderId();

            // determine the URL parts
            $protocol = isset($_SERVER['HTTPS']) && strcasecmp('off', $_SERVER['HTTPS']) !== 0 ? "https" : "http";
            $hostname = $_SERVER['HTTP_HOST'];

            // create new payment
            $payment = $mollie->payments->create([
                "amount" => [
                    "currency" => "EUR",
                    "value" => "{$amount}", // You must send the correct number of decimals, thus we enforce the use of strings
                ],
                "description" => "Order {$orderId}",
                "billingEmail" => $user->getEmail(),
                "redirectUrl" => "{$protocol}://{$hostname}/payment?order_id={$orderId}",
                "webhookUrl" => "{$protocol}://{$hostname}/webhook.php",
                "metadata" => [
                    "order_id" => $orderId,
                ],
            ]);

            // write payment to database
            $this->dao->newPayment($orderId, $payment->status, $_SESSION['userId']);


            // get checkout url to finish transaction
            Header("Location: " . $payment->getCheckoutUrl(), true, 303);


            return true;
        } catch (ApiException $e) {
            echo "API call failed: " . htmlspecialchars($e->getMessage());
        }
        return false;
    }


    function getStatusByOrderId(string $orderId): string
    {
        try {
            $stmt = $this->dao->getStatusByOrderId($orderId);
            $num = $stmt->rowCount();

            if ($num > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                return (string)$row["status"];
            }

        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);
        }
        return "";
    }

    function getUserId(string $orderId): int
    {
        try {
            $stmt = $this->dao->getUserId($orderId);
            $num = $stmt->rowCount();

            if($num > 0){
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                return (int)$row["userId"];
            }
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);
        }
        return 0;
    }

    function updatePaymentStatus(string $orderId, string $status): bool
    {
        try {
            return ($this->dao->updateStatus($orderId, $status));

        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);
            return false;
        }
    }

    function createUniqueOrderId(): ?string
    {
        try {
            do {
                $characters = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $randomId = "ord_";
                for ($i = 0; $i < 10; $i++) {
                    $randomId .= $characters[rand(0, $charactersLength - 1)];
                }
            } while ($this->orderIdExists($randomId));

            return $randomId;
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);
        }
        return "";
    }

    function orderIdExists(string $orderId): bool
    {
        try {
            $stmt = $this->dao->orderIdExists($orderId);
            $num = $stmt->rowCount();

            return ($num >= 1);
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);
        }
        return true;
    }

    function deleteOrder(string $orderId) : bool {
        try {
            return $this->dao->deleteOrder($orderId);
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);
        }
        return false;
    }


}
