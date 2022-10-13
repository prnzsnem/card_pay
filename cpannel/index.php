<?php
if (is_file("basefile.php")) {
    $basePath = "./";
} else if (is_file("./../basefile.php")) {
    $basePath = "./../";
} else if (is_file("./../../basefile.php")) {
    $basePath = "./../../";
}
include $basePath . "include/page_req_prop.php";

$paymentAmount = 10;
$paymentReceiver = "Hotel Pauwa";
if (isset($_GET['Amount'])) {
    $paymentAmount = $_GET['Amount'];
    $paymentReceiver = $_GET['PaymentReceiver'];
}

$sql = "SELECT * FROM payments";
$statement = $connect->prepare($sql);
$payments = [];
if ($statement->execute()) {
    $payments = $statement->fetchAll(PDO::FETCH_OBJ);
}
$statement->closeCursor();

$sql = "SELECT * FROM card_info";
$cardQuery = $connect->prepare($sql);
$cards = [];
if ($cardQuery->execute()) {
    $cards = $cardQuery->fetchAll(PDO::FETCH_OBJ);
}
$cardQuery->closeCursor();

// echo json_encode($payments);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SrvPay - CPannel</title>
    <?php include $basePath . Route::$CSS_FILES; ?>

    <link href="dashboard.css" rel="stylesheet">
</head>

<body>

    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
        <a class="navbar-brand col-sm-3 col-md-2 mr-0">SrvPay CPannel</a>
        <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="#">Sign out</a>
            </li>
        </ul>
    </nav>

    <div class="container-fluid">
        <div class="row" style="display: flex; flex-direction: row-reverse;">
            <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active">
                                <span data-feather="home"></span>
                                Dashboard <span class="sr-only">(current)</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./settings.php">
                                <span data-feather="settings"></span>
                                Settings
                            </a>
                        </li>
                        <li class="nav-item d-none">
                            <a class="nav-link" href="#">
                                <span data-feather="shopping-cart"></span>
                                Products
                            </a>
                        </li>
                        <li class="nav-item d-none">
                            <a class="nav-link" href="#">
                                <span data-feather="users"></span>
                                Customers
                            </a>
                        </li>
                        <li class="nav-item d-none">
                            <a class="nav-link" href="#">
                                <span data-feather="bar-chart-2"></span>
                                Reports
                            </a>
                        </li>
                        <li class="nav-item d-none">
                            <a class="nav-link" href="#">
                                <span data-feather="layers"></span>
                                Integrations
                            </a>
                        </li>
                    </ul>

                    <h6 class="sidebar-heading d-flex d-none justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>Saved reports</span>
                        <a class="d-flex align-items-center text-muted" href="#">
                            <span data-feather="plus-circle"></span>
                        </a>
                    </h6>
                    <ul class="nav flex-column mb-2">
                        <li class="nav-item d-none">
                            <a class="nav-link" href="#">
                                <span data-feather="file-text"></span>
                                Current month
                            </a>
                        </li>
                        <li class="nav-item d-none">
                            <a class="nav-link" href="#">
                                <span data-feather="file-text"></span>
                                Last quarter
                            </a>
                        </li>
                        <li class="nav-item d-none">
                            <a class="nav-link" href="#">
                                <span data-feather="file-text"></span>
                                Social engagement
                            </a>
                        </li>
                        <li class="nav-item d-none">
                            <a class="nav-link" href="#">
                                <span data-feather="file-text"></span>
                                Year-end sale
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card-counter danger">
                            <i class="fa fa-ticket"></i>
                            <span class="count-numbers"><?php echo count($cards); ?></span>
                            <span class="count-name">Cards</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card-counter success">
                            <i class="fa fa-database"></i>
                            <span class="count-numbers"><?php echo count($payments); ?></span>
                            <span class="count-name">Payments</span>
                        </div>
                    </div>
                </div>



                <h2>Cards List</h2>
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th class="text-center">S.N.</th>
                                <th class="text-center">Id</th>
                                <th>Card Type</th>
                                <th>Card Holder</th>
                                <th>Card Number</th>
                                <th>Card CVS</th>
                                <th>Card Exp Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $count = 0;
                            if ($cardQuery->rowCount() > 0) {
                                foreach ($cards as $card) {
                                    $count++;
                                    $cardId = htmlentities($card->id);
                                    $cardType = htmlentities($card->card_type);
                                    $cardHolder = htmlentities($card->card_holder);
                                    $cardNumber = htmlentities($card->card_number);
                                    $cardExpYear = htmlentities($card->card_exp_year);
                                    $cardExpMonth = htmlentities($card->card_exp_month);
                                    $cardCSV = htmlentities($card->card_csv);
                                    $cardStatus = htmlentities($card->card_status);
                                    $cardExpDate = $cardExpMonth . ", " . $cardExpYear;
                            ?>

                                    <tr>
                                        <td class="text-center"><?php echo $count; ?></td>
                                        <td class="text-center"><?php echo $cardId; ?></td>
                                        <td><?php echo $cardType; ?></td>
                                        <td><?php echo $cardHolder; ?></td>
                                        <td><?php echo $cardNumber; ?></td>
                                        <td><?php echo $cardCSV; ?></td>
                                        <td><?php echo $cardExpDate; ?></td>
                                        <td><?php echo $cardStatus; ?></td>
                                    </tr>

                            <?php }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <h2>Payments List</h2>
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th class="text-center">S.N.</th>
                                <th class="text-center">Card Id</th>
                                <th>Txn Id</th>
                                <th>Payment Amount</th>
                                <th>Payment Receiver</th>
                                <th>Payment Status</th>
                                <th>Payment Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $count = 0;
                            if ($statement->rowCount() > 0) {
                                foreach ($payments as $payment) {
                                    $count++;
                                    $cardId = htmlentities($payment->card_id);
                                    $txnId = htmlentities($payment->transaction_id);
                                    $pmtAmt = htmlentities($payment->payment_amount);
                                    $pmtRec = htmlentities($payment->payment_receiver);
                                    $pmtStat = htmlentities($payment->payment_status);
                                    $pmtDate = htmlentities($payment->payment_date); ?>

                                    <tr>
                                        <td class="text-center"><?php echo $count; ?></td>
                                        <td class="text-center"><?php echo $cardId; ?></td>
                                        <td><?php echo $txnId; ?></td>
                                        <td><?php echo $pmtAmt; ?></td>
                                        <td><?php echo $pmtRec; ?></td>
                                        <td><?php echo $pmtStat; ?></td>
                                        <td><?php echo $pmtDate; ?></td>
                                    </tr>

                            <?php }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <?php include $basePath . Route::$JS_FILES; ?>

    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
        feather.replace()
    </script>
</body>

</html>