<?php

use JetBrains\PhpStorm\Pure;
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

    function createPayment(int $userId, float $amount): bool
    {
        $mollie = new MollieApiClient();
        $mollie->setApiKey("test_Ds3fz4U9vNKxzCfVvVHJT2sgW5ECD8");

        try {
            // create random and unique order ID
            $orderId = $this->createUniqueOrderId();
            $amountString = number_format($amount, 2, '.', null);

            // determine the URL parts
            $protocol = isset($_SERVER['HTTPS']) && strcasecmp('off', $_SERVER['HTTPS']) !== 0 ? "https" : "http";
            $hostname = $_SERVER['HTTP_HOST'];

            // create new payment
            $payment = $mollie->payments->create([
                "amount" => [
                    "currency" => "EUR",
                    "value" => "{$amountString}", // You must send the correct number of decimals, thus we enforce the use of strings
                ],
                "description" => "Order {$orderId}",
                "redirectUrl" => "{$protocol}://{$hostname}/payment?order_id={$orderId}",
                "webhookUrl" => "{$protocol}://{$hostname}/payment",
                "metadata" => [
                    "order_id" => $orderId,
                ],
            ]);

            // write payment to database
            database_write($orderId, $payment->id, $payment->status);

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

    function getPaymentId(string $orderId): string
    {
        try {
            $stmt = $this->dao->getPaymentId($orderId);
            $num = $stmt->rowCount();

            if ($num > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                return (string)$row["paymentId"];

            }
        }
        catch
            (Exception $e) {
                $error = new ErrorLog();
                $error->setMessage($e->getMessage());
                $error->setStackTrace($e->getTraceAsString());

                ErrorService::getInstance()->create($error);
            }
        return "";
    }

    function updatePaymentStatus(string $orderId, string $paymentId, string $status, string $userId): bool
    {
        try {
            return ($this->dao->updateStatus($orderId, $paymentId, $status, $userId));

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



}
