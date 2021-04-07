<?php
const TICKET_COLUMNS = array("id", "ticketType", "eventId", "eventType", "price", "inStock");
const INVOICE_COLUMNS = array("id", "userId", "userAddress", "userPhone", "tax", "date", "dueDate");

if(!isset($_SESSION)) session_start();

?>
<!--Page to view orders-->
<html lang="en">
<head>
    <title>Export data</title>
    <link type="text/css" rel="stylesheet" href="css/style.css" />
</head>
<body>
<?php
//Load menubar
require_once ("validate.php");
require_once ("menubar.php");
?>
<div class="content">
    <!--Form to export users to external API-->
    <h1>Export Data</h1>
    <form class="" name="exportActs" action="exports/exportToAPI.php" method="post">
        <input type="submit" name="export" value="Export to API" class="customButton"/>

        <div class="formField red">
            <p><?php if(isset($_SESSION["exportAPIError"])){echo $_SESSION["exportAPIError"];}?></p>
        </div>
    </form>

    <!--Form to export specific columns from all acts-->
    <form class="exportForm" name="exportActs" action="exports/exportActs.php" method="post">
        <h2>Export Acts</h2>
        <div class="formBody">
            <div class="group">
                <p>Columns to export</p>
                <input type="checkbox" id="id" name="id" value="id">
                <label for="id">id</label><br>
                <input type="checkbox" id="eventId" name="eventId" value="eventId">
                <label for="eventId">eventId</label><br>
                <input type="checkbox" id="date" name="date" value="date">
                <label for="date">date</label><br>
                <input type="checkbox" id="startTime" name="startTime" value="startTime">
                <label for="startTime">startTime</label><br>
                <input type="checkbox" id="endTime" name="endTime" value="endTime">
                <label for="endTime">endTime</label><br>
                <input type="checkbox" id="location" name="location" value="location">
                <label for="location">location</label><br>
                <input type="checkbox" id="imagePath" name="imagePath" value="imagePath">
                <label for="imagePath">imagePath</label><br>
                <br>
            </div>

            <div class="group">
                <p>Format</p>
                <input type="radio" id="csv" name="format" value="csv" checked>
                <label for="csv">CSV</label><br>
                <input type="radio" id="excel" name="format" value="excel">
                <label for="excel">Excel</label><br>
            </div>
            <div>
                <input type="submit" name ="exportOrders" value="Export" class="customButton"/>
            </div>

            <div class="formField red">
                <p><?php if(isset($_SESSION["exportActsError"])){echo $_SESSION["exportActsError"];}?></p>
            </div>
        </div>
    </form>

    <!--Form to export specific columns from all users-->
    <form class="exportForm" name="exportUsers" action="exports/exportUsers.php" method="post">
        <h2>Export Users</h2>
        <div class="formBody">
            <div class="group">
                <p>Columns to export</p>
                <input type="checkbox" id="id" name="id" value="id">
                <label for="id">id</label><br>
                <input type="checkbox" id="firstname" name="firstname" value="firstname">
                <label for="firstname">firstname</label><br>
                <input type="checkbox" id="lastname" name="lastname" value="lastname">
                <label for="lastname">lastname</label><br>
                <input type="checkbox" id="password" name="password" value="password">
                <label for="password">password</label><br>
                <input type="checkbox" id="salt" name="salt" value="salt">
                <label for="salt">salt</label><br>
                <input type="checkbox" id="email" name="email" value="email">
                <label for="email">email</label><br>
                <input type="checkbox" id="register_date" name="register_date" value="register_date">
                <label for="register_date">register date</label><br>
                <input type="checkbox" id="usertype" name="usertype" value="usertype">
                <label for="usertype">usertype</label><br>
            </div>

            <div class="group">
                <p>Format</p>
                <input type="radio" id="csv" name="format" value="csv" checked>
                <label for="csv">CSV</label><br>
                <input type="radio" id="excel" name="format" value="excel">
                <label for="excel">Excel</label><br>
            </div>
            <div>
                <input type="submit" value="Export" name="exportUsers" class="customButton"/>
            </div>

            <div class="formField red">
                <p><?php if(isset($_SESSION["exportUsersError"])){echo $_SESSION["exportUsersError"];}?></p>
            </div>
        </div>
    </form>

    <!--Form to export specific columns from all tickets-->
    <form class="exportForm" name="exportTickets" action="exports/exportTickets.php" method="post">
        <h2>Export Tickets</h2>
        <div class="formBody">
            <div class="group">
                <p>Columns to export</p>
                <input type="checkbox" id="id" name="id" value="id">
                <label for="id">id</label><br>
                <input type="checkbox" id="ticketType" name="ticketType" value="ticketType">
                <label for="ticketType">ticketType</label><br>
                <input type="checkbox" id="eventId" name="eventId" value="eventId">
                <label for="eventId">eventId</label><br>
                <input type="checkbox" id="eventType" name="eventType" value="eventType">
                <label for="eventType">eventType</label><br>
                <input type="checkbox" id="price" name="price" value="price">
                <label for="price">price</label><br>
                <input type="checkbox" id="inStock" name="inStock" value="inStock">
                <label for="inStock">inStock</label><br>
                <br><br>
            </div>

            <div class="group">
                <p>Format</p>
                <input type="radio" id="csv" name="format" value="csv" checked>
                <label for="csv">CSV</label><br>
                <input type="radio" id="excel" name="format" value="excel">
                <label for="excel">Excel</label><br>
            </div>
            <div>
                <input type="submit" value="Export" name="exportTickets" class="customButton"/>
            </div>

            <div class="formField red">
                <p><?php if(isset($_SESSION["exportTicketsError"])){echo $_SESSION["exportTicketsError"];}?></p>
            </div>
        </div>
    </form>

    <!--Form to export specific columns from all invoices-->
    <form class="exportForm" name="exportInvoices" action="exports/exportInvoices.php" method="post">
        <h2>Export Invoices</h2>
        <div class="formBody">
            <div class="group">
                <p>Columns to export</p>
                <input type="checkbox" id="id" name="id" value="id">
                <label for="id">id</label><br>
                <input type="checkbox" id="userId" name="userId" value="userId">
                <label for="userId">userId</label><br>
                <input type="checkbox" id="userAddress" name="userAddress" value="userAddress">
                <label for="userAddress">userAddress</label><br>
                <input type="checkbox" id="userPhone" name="userPhone" value="userPhone">
                <label for="userPhone">userPhone</label><br>
                <input type="checkbox" id="tax" name="tax" value="tax">
                <label for="tax">tax</label><br>
                <input type="checkbox" id="date" name="date" value="date">
                <label for="date">date</label><br>
                <input type="checkbox" id="dueDate" name="dueDate" value="dueDate">
                <label for="dueDate">dueDate</label><br>
                <br>
            </div>

            <div class="group">
                <p>Format</p>
                <input type="radio" id="csv" name="format" value="csv" checked>
                <label for="csv">CSV</label><br>
                <input type="radio" id="excel" name="format" value="excel">
                <label for="excel">Excel</label><br>
            </div>
            <div>
                <input type="submit" value="Export" name="exportInvoices" class="customButton"/>
            </div>

            <div class="formField red">
                <p><?php if(isset($_SESSION["exportInvoicesError"])){echo $_SESSION["exportInvoicesError"];}?></p>
            </div>
        </div>
    </form>
</div>
</body>
</html>