<?php
if (is_file("basefile.php")) {
    $basePath = "./";
} else if (is_file("./../basefile.php")) {
    $basePath = "./../";
} else if (is_file("./../../basefile.php")) {
    $basePath = "./../../";
}
include $basePath . "include/page_req_prop.php";

$txnId = "N/A";
$paymentType = "N/A";
$paymentAmount = 10;
$initiator = "N/A";
$receiver = "N/A";
$paymentDate = "N/A";
$status = "N/A";
$dataCount = 0;

$statusFaIcon = "fa-multiply";
$statusIconBgColor = "red";
$statusText = "Failed";
$statusWiseMsg = MSG::$PAYMENT_FAILED;

if (isset($_GET['txnId'])) {
    $txnId = $_GET['txnId'];
    $sql = "SELECT *, payments.id, card_info.id AS LINKID FROM card_info JOIN payments ON payments.card_id = card_info.id  WHERE payments.transaction_id = '$txnId'";
    $statement = $connect->prepare($sql);
    $statement->execute();
    $dataCount = $statement->rowCount();
    if ($dataCount > 0) {
        $result = $statement->fetch();
        $paymentType = $result['card_type'];
        $paymentAmount = $result['payment_amount'];
        $initiator = $result['card_holder'];
        $receiver = $result['payment_receiver'];
        $paymentDate = $result['payment_date'];
        $status = $result['payment_status'];

        // UI
        $statusFaIcon = "fa-check";
        $statusIconBgColor = "green";
        $statusText = "Success";
        $statusWiseMsg = MSG::$PAYMENT_SUCCESS;
    }
} else {
    header("location: ".$basePath . Route::$PAYMENT_SUCCESS_REDIRECT_URL);
}

$sql = "SELECT * FROM app_config";
$redirectUrl = "";
$statement = $connect->prepare($sql);
if ($statement->execute()) {
    $result = $statement->fetch();
    $redirectUrl = $result["redirection_url"];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SrvPay - Payment Success.</title>
    <?php include $basePath . "assets/stylesheet.php"; ?>
    <style>
        body {
            text-align: center;
            padding: 40px 0;
            background: #EBF0F5;
        }

        h1 {
            color: green;
            font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
            font-weight: 900;
            font-size: 40px;
            margin-bottom: 10px;
        }

        p {
            color: green;
            font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
            font-size: 20px;
            margin: 0;
        }

        i {
            color: white;
            font-size: 50px;
            line-height: 100px;
            margin-left: -15px;
        }

        .card {
            background: white;
            padding: 60px;
            border-radius: 4px;
            box-shadow: 0 2px 3px #C8D0D8;
            display: inline-block;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="card col-6 print-col-width">
        <div style="border-radius:100px; height:100px; width:100px; background: <?php echo $statusIconBgColor; ?>; margin:0 auto;">
            <i class="fa <?php echo $statusFaIcon; ?>" style="height: 80px; padding: 10px; color: white;"></i>
        </div>
        <h1 style="color: <?php echo $statusIconBgColor; ?>;"><?php echo $statusText; ?></h1>
        <?php
        if ($dataCount > 0) { ?>
            <div class="row my-4 py-2 border">
                <div class="col-md-3" style="text-align: left;">
                    <lable style="text-align: left; color: gray;">Transaction Id</lable>
                </div>
                <div class="col-md-9" style="text-align: right;">
                    <label style="text-align: right;"><?php echo $txnId; ?></label>
                </div>

                <div class="col-md-3 pt-2" style="text-align: left;">
                    <lable style="text-align: left; color: gray;">Payment Type</lable>
                </div>
                <div class="col-md-9 pt-2" style="text-align: right;">
                    <label style="text-align: right;"><?php echo $paymentType; ?></label>
                </div>

                <div class="col-md-4 pt-2" style="text-align: left;">
                    <lable style="text-align: left; color: gray;">Payment Amount</lable>
                </div>
                <div class="col-md-8 pt-2" style="text-align: right;">
                    <label style="text-align: right;"><?php echo $paymentAmount; ?></label>
                </div>

                <div class="col-md-3 pt-2" style="text-align: left;">
                    <lable style="text-align: left; color: gray;">Initiator</lable>
                </div>
                <div class="col-md-9 pt-2" style="text-align: right;">
                    <label style="text-align: right;"><?php echo $initiator; ?></label>
                </div>

                <div class="col-md-3 pt-2" style="text-align: left;">
                    <lable style="text-align: left; color: gray;">Receiver</lable>
                </div>
                <div class="col-md-9 pt-2" style="text-align: right;">
                    <label style="text-align: right;"><?php echo $receiver; ?></label>
                </div>

                <div class="col-md-3 pt-2" style="text-align: left;">
                    <lable style="text-align: left; color: gray;">Payment Date</lable>
                </div>
                <div class="col-md-9 pt-2" style="text-align: right;">
                    <label style="text-align: right;"><?php echo $paymentDate; ?></label>
                </div>

                <div class="col-md-3 pt-2" style="text-align: left;">
                    <lable style="text-align: left; color: gray;">Payment Status</lable>
                </div>
                <div class="col-md-9 pt-2" style="text-align: right;">
                    <label style="text-align: right;"><?php echo $status; ?></label>
                </div>
            </div>
        <?php }
        ?>
        <p><?php echo $statusWiseMsg; ?></p>
        <div class="mt-2 noPrint">
            <button class="btn btn-primary" onclick="window.print();"><i class="fa fa-print"></i> Print</button>
            <button class="btn btn-danger" onclick="window.location.href = '//localhost/Sarvanam/BookingEngine/';">Close</button>
        </div>
    </div>
    <?php include $basePath . "assets/scripts.php"; ?>
</body>

</html>