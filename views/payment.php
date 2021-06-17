<html lang="en">
<head>
    <title>Thank you</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <link rel="stylesheet" type="text/css" href="/css/payment.css">
    <link rel="stylesheet" type="text/css" href="/css/innerNav.css">
</head>
<body>
<?php require __DIR__ . '/menubar.php'; ?>
<main>
    <article id="tickets">
        <h1>
            Thank you for your order.<br>
        </h1>
        <h2>
            Your order has been completed successfully.
        </h2>

        An email with the details of your order has been sent to your email address.
        
        <div id="buttonContainer">
            <button onclick="window.location.href='/account'" id="returnButton" name="returnButton">Return to my account
            </button>
        </div>
    </article>
</main>

<?php require __DIR__ . '/footer.php'; ?>
</body>
</html>




