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

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to SrvPay - Demo</title>
    <?php include $basePath . Route::$CSS_FILES; ?>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand">SrvPay</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="http://sarvanam.com/about-us.html">About Us</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="center p-4">
        <div class="col-75">
            <div class="container">
                <h3>Payment</h3>
                <div class="row">
                    <div class="col-md-6">
                        <label for="acceptedCards">Accepted Cards</label>
                        <input type="hidden" name="payment_amount" id="payment_amount" value="<?php echo $paymentAmount; ?>">
                        <input type="hidden" name="payment_reveiver" id="payment_receiver" value="<?php echo $paymentReceiver; ?>">
                        <div class="icon-container">
                            <img class="" src="<?php echo $basePath; ?>assets/image/visa_card.svg" title="Visa Card" style="height: 40px; width:fit-content;">
                            <img class="" src="<?php echo $basePath; ?>assets/image/debit_card.svg" title="Credit/Debit Card" style="height: 40px; width:fit-content;">
                            <img class="" src="<?php echo $basePath; ?>assets/image/amex_card.svg" title="AMEX Card" style="height: 40px; width:fit-content;">
                            <img class="" src="<?php echo $basePath; ?>assets/image/master_card.svg" title="Master Card" style="height: 40px; width:fit-content;">
                            <img class="" src="<?php echo $basePath; ?>assets/image/discover_card.svg" title="Discover Card" style="height: 40px; width:fit-content;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <label for="payToInfo">Payment Info</label>
                            <h3>Amount: <?php echo $paymentAmount; ?></h3>
                            <label><strong>Payment For: <?php echo $paymentReceiver; ?></strong></label>
                        </div>
                    </div>
                </div>
                <label for="card_holder_name">Name on Card</label>
                <input class="form-control" type="text" id="card_holder_name" name="cardname" require>
                <div id="card_holder_msg"></div>

                <label for="card_number">Credit card number</label>
                <input class="form-control" type="text" id="card_number" minlength="16" maxlength="16" name="cardnumber" onkeyup="GetCardType('card_number', 'card_num_msg');" require>
                <div id="card_num_msg"></div>

                <div class="row mt-2">
                    <div class="col-4">
                        <label for="card_exp_month">Exp Month</label>
                        <select class="form-control" name="card_exp_month" id="card_exp_month" require>
                            <option value="01">January</option>
                            <option value="02">February</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </div>
                    <div class="col-4">
                        <label for="card_exp_year">Exp Year</label>
                        <select class="form-control" name="card_exp_year" id="card_exp_year" require>
                            <?php
                            $yearToday = date('Y');
                            $yearFromTodayToTenYear = date("Y", strtotime("+ 10 year"));
                            for ($i = $yearToday; $i < $yearFromTodayToTenYear; $i++) {
                                echo '<option value="' . $i . '">' . $i . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-4">
                        <label for="card_csv">CVV</label>
                        <input class="form-control" type="text" id="card_csv" name="card_csv" placeholder="" require>
                        <div id="card_csv_msg"></div>
                    </div>
                </div>

                <input class="btn btn-success mt-4" type="button" value="Pay" onclick="makePayment();">
            </div>
        </div>
    </div>
    <?php include $basePath . Route::$JS_FILES; ?>
</body>

</html>