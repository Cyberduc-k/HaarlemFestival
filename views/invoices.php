<html lang="en">
<head>
    <title>Invoices</title>
    <link type="text/css" rel="stylesheet" href="/css/style.css" />
    <link type="text/css" rel="stylesheet" href="/css/invoices.css" />
</head>
<body>
    <?php require __DIR__.'/menubar.php'; ?>

    <section class="content">
        <h1>Invoices</h1>

        <a class="create" href="/invoice/create">Create Invoice</a>

        <?php foreach ($invoices as $invoice) {
            $date = $invoice->getDate();
            $user = $us->getById($invoice->getUserId());
        ?>
            <div class="invoice">
                <span class="date"><?php echo $date->format('d-m-Y H:i'); ?></span>
                <span class="name"><?php echo $user->getFullName(); ?></span>
                <a class="viewPdf" href="/invoice/<?php echo $invoice->getId(); ?>">View Pdf</a>
            </div>
        <?php } ?>
    </section>

    <?php require __DIR__.'/footer.php'; ?>
</body>
</html
