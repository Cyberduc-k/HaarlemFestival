<?php

require_once(__DIR__ . "/../models/User.php");
require_once(__DIR__ . "/../models/ResetKey.php");
require_once(__DIR__ . "/../models/Content.php");
require_once(__DIR__ . "/../models/Ticket.php");
require_once(__DIR__ . "/../models/TicketWithCount.php");
require_once(__DIR__ . "/../models/ACt.php");

// Functions often used throughout the service layer
class ServiceUtils {
    // Used to convert the raw data from the query to a User object
    protected function rowToUser(array $row): User {
        $user = new User();

        $registerDate = DateTime::createFromFormat('Y-m-d H:i:s', (string)$row["register_date"]);

        $user->setId((int)$row["id"]);
        $user->setFirstname((string)$row["firstname"]);
        $user->setLastname((string)$row["lastname"]);
        $user->setPassword((string)$row["password"]);
        $user->setSalt((string)$row["salt"]);
        $user->setEmail((string)$row["email"]);
        $user->setRegisterDate($registerDate);
        $user->setUsertype((int)$row["usertype"]);

        return $user;
    }

    // Used to convert the raw data from the query to a EditStash object
    protected function rowToEditStash(array $row): EditStash {
        $stash = new EditStash();

        $expDate = DateTime::createFromFormat('Y-m-d H:i:s', (string)$row["expDate"]);

        $stash->setId((int)$row["userId"]);
        $stash->setStashId((int)$row["stashId"]);
        $stash->setLastname((string)$row["lastname"]);
        $stash->setPassword((string)$row["password"]);
        $stash->setSalt((string)$row["salt"]);
        $stash->setFirstname((string)$row["firstname"]);
        $stash->setEmail((string)$row["email"]);
        $stash->setUsertype((int)$row["usertype"]);
        $stash->setExpDate($expDate);

        return $stash;
    }

    // Used to convert the raw data from the query to a ResetKey object
    protected function rowToResetKey(array $row): ResetKey {
        $resetKey = new ResetKey();

        $expDate = DateTime::createFromFormat('Y-m-d H:i:s', (string)$row["expDate"]);

        $resetKey->setId((int)$row["id"]);
        $resetKey->setUserId((int)$row["userId"]);
        $resetKey->setKey((string)$row["key"]);
        $resetKey->setExpDate($expDate);

        return $resetKey;
    }

    // Used to convert the raw data from the query to a EditKey object
    protected function rowToEditKey(array $row): EditKey {
        $editKey = new EditKey();

        $expDate = DateTime::createFromFormat('Y-m-d H:i:s', (string)$row["expDate"]);

        $editKey->setId((int)$row["stashId"]);
        $editKey->setKey((string)$row["key"]);
        $editKey->setExpDate($expDate);

        return $editKey;
    }

    // Used to convert the raw data from the query to a EditEmailKey object
    protected function rowToEditEmailKey(array $row): EditEmailKey {
        $editEmailKey = new EditEmailKey();

        $expDate = DateTime::createFromFormat('Y-m-d H:i:s', (string)$row["expDate"]);

        $editEmailKey->setId((int)$row["stashId"]);
        $editEmailKey->setOldEmail((string)$row["oldEmail"]);
        $editEmailKey->setOldKey((string)$row["oldKey"]);
        $editEmailKey->setOldStatus((bool)$row["oldStatus"]);
        $editEmailKey->setNewEmail((string)$row["newEmail"]);
        $editEmailKey->setNewKey((string)$row["newKey"]);
        $editEmailKey->setNewStatus((bool)$row["newStatus"]);
        $editEmailKey->setExpDate($expDate);

        return $editEmailKey;
    }

    // Prepare file name for storage
    protected function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }

    protected function rowToContent(array $row): Content {
        $content = new Content();

        $content->setId((int)$row["id"]);
        $content->setEventId((int)$row["eventId"]);
        $content->setHeader((string)$row["header"]);
        $content->setText((string)$row["text"]);
        $content->setImagePath((string)$row["imagePath"]);

        return $content;
    }

    protected function rowToTicket(array $row): Ticket {
        $ticket = new Ticket();

        $ticket->setId((int)$row["ticket"]);
        $ticket->setType((int)$row["ticketType"]);
        $ticket->setEventId((int)$row["eventId"]);
        $ticket->setEventType((int)$row["eventtype"]);
        $ticket->setPrice((float)$row["price"]);
        $ticket->setInStock((int)$row["inStock"]);

        return $ticket;
    }

    protected function rowToTicketWithCount(array $row): TicketWithCount {
        $twc = new TicketWithCount();

        $twc->ticket = $this->rowToTicket($row);
        $twc->count = (int)$row["nTickets"];

        return $twc;
    }

    protected function rowToAct(array $row): Act {
        $act = new Act();

        $act->setId((int)$row["id"]);
        $act->setEventId((int)$row["eventId"]);
        $act->setDate($row["date"]);
        $act->setStartTime($row["startTime"]);
        $act->setEndTime($row["endTime"]);
        $act->setLocation((string)$row["location"]);
        $act->setImagePath((string)$row["imagePath"]);

        return $act;
    }
}

?>
