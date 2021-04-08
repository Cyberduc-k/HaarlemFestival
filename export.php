<?php if(!isset($_SESSION)) session_start();?>
<!--Page to view orders-->
<html lang="en">
<head>
    <title>Export data</title>
    <link type="text/css" rel="stylesheet" href="css/style.css" />
    <link type="text/css" rel="stylesheet" href="css/exportData.css" />
</head>
<body>
<?php
//Load menubar
require_once ("validate.php");
require_once ("menubar.php");
?>
<section class="content">
    <!--Form to export users to external API-->
    <section class="header">
        <h1>Export Data</h1>
        <form id="exportToAPI" name="exportActs" action="exports/exportToAPI.php" method="post">
            <input type="submit" name="export" value="Export to API"/>

            <p><?php if(isset($_SESSION["exportAPIError"])){echo $_SESSION["exportAPIError"];}?></p>
        </form>
    </section>

    <!--Form to export specific columns from all acts-->
    <form class="exportForm" name="exportActs" action="exports/exportActs.php" method="post">
        <h2>Export Acts</h2>
        <p>Columns to export</p>
        <article class="inputs">
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
            <label for="imagePath">imagePath</label>
        </article>
        <p>Format</p>
        <input type="radio" id="csv" name="format" value="csv" checked>
        <label for="csv">CSV</label><br>
        <input type="radio" id="excel" name="format" value="excel">
        <label for="excel">Excel</label><br>
        <input type="submit" name ="exportOrders" value="Export" class="exportBtn"/>

        <p><?php if(isset($_SESSION["exportActsError"])){echo $_SESSION["exportActsError"];}?></p>
    </form>

    <!--Form to export specific columns from all users-->
    <form class="exportForm" name="exportUsers" action="exports/exportUsers.php" method="post">
        <h2>Export Users</h2>
        <p>Columns to export</p>
        <article class="inputs">
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
            <label for="usertype">usertype</label>
        </article>


        <p>Format</p>
        <input type="radio" id="csv" name="format" value="csv" checked>
        <label for="csv">CSV</label><br>
        <input type="radio" id="excel" name="format" value="excel">
        <label for="excel">Excel</label><br>
        <input type="submit" value="Export" name="exportUsers" class="exportBtn"/>

        <p><?php if(isset($_SESSION["exportUsersError"])){echo $_SESSION["exportUsersError"];}?></p>
    </form>

    <!--Form to export specific columns from all tickets-->
    <form class="exportForm" name="exportTickets" action="exports/exportTickets.php" method="post">
        <h2>Export Tickets</h2>
        <p>Columns to export</p>
        <article class="inputs">
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
            <label for="inStock">inStock</label>
        </article>

        <p>Format</p>
        <input type="radio" id="csv" name="format" value="csv" checked>
        <label for="csv">CSV</label><br>
        <input type="radio" id="excel" name="format" value="excel">
        <label for="excel">Excel</label><br>
        <input type="submit" value="Export" name="exportTickets" class="exportBtn"/>

        <p><?php if(isset($_SESSION["exportTicketsError"])){echo $_SESSION["exportTicketsError"];}?></p>
    </form>

    <!--Form to export specific columns from all invoices-->
    <form class="exportForm" name="exportInvoices" action="exports/exportInvoices.php" method="post">
        <h2>Export Invoices</h2>
        <p>Columns to export</p>
        <article class="inputs">
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
            <label for="dueDate">dueDate</label>
        </article>

        <p>Format</p>
        <input type="radio" id="csv" name="format" value="csv" checked>
        <label for="csv">CSV</label><br>
        <input type="radio" id="excel" name="format" value="excel">
        <label for="excel">Excel</label><br>
        <input type="submit" value="Export" name="exportInvoices" class="exportBtn"/>

        <p><?php if(isset($_SESSION["exportInvoicesError"])){echo $_SESSION["exportInvoicesError"];}?></p>
    </form>
</section>
<?php
require_once ("footer.php");
?>
</body>
</html>