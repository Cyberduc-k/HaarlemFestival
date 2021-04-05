<?php

require_once(__DIR__ . "/../models/Invoice.php");
require_once(__DIR__ . "/../DAL/InvoiceDAO.php");
require_once("ServiceUtils.php");

class InvoiceService extends ServiceUtils {
    private InvoiceDAO $dao;

    public function __construct() {
        $this->dao = new InvoiceDAO();
    }

    public function getAll(): ?array {
        try {
            $stmt = $this->dao->getAll();
            $num = $stmt->rowCount();

            if ($num > 0) {
                $invoices = [];

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($invoices, $this->rowToInvoice($row));
                }

                return $invoices;
            }

            return null;
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            return null;
        }
    }

    public function getById(int $id): ?Invoice {
        try {
            $stmt = $this->dao->getById($id);
            $num = $stmt->rowCount();

            if ($num > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                return $this->rowToInvoice($row);
            }

            return null;
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            return null;
        }
    }

    // Create a new invoice
    public function create(Invoice $invoice): bool {
        try {
            return $this->dao->create($invoice);
        } catch (Exception $e) {
            $error = new ErrorLog();
            $error->setMessage($e->getMessage());
            $error->setStackTrace($e->getTraceAsString());

            ErrorService::getInstance()->create($error);

            // Return an empty stmt
            return false;
        }
    }

    // add a ticket to an invoice
    public function addTicket(int $invoiceId, int $ticketId, int $count): bool {
        try {
            return $this->dao->addTicket($invoiceId, $ticketId, $count);
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
