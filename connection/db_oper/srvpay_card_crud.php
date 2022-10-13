<?php
if (is_file("basefile.php")) {
    $basePath = "./";
} else if (is_file("./../basefile.php")) {
    $basePath = "./../";
} else if (is_file("./../../basefile.php")) {
    $basePath = "./../../";
}
include $basePath . "include/page_req_prop.php";

$code = 0;
$msg = "Success";
$data = null;

if (isset($_POST)) {
    $operation = $_POST['Operation'];
    switch ($operation) {
        case 'Insert':
        case 'Update':
            $cardType = $_POST['Type'];
            $cardholder = $_POST['Holder'];
            $cardNum = $_POST['CardNumber'];
            $cardExpYear = $_POST['ExpYear'];
            $cardExpMonth = $_POST['ExpMonth'];
            $cardCsv = $_POST['CSV'];
            if ($operation == 'Insert') {
                $sql = "INSERT INTO card_info (card_type, card_holder, card_number, card_exp_year, card_exp_month, card_csv) VALUES ($cardType, $cardholder, $cardNum, $cardExpYear, $cardExpMonth, $cardCsv)";
            } else {
                $sql = "UPDATE card_info SET card_type = $cardType, card_holder = $cardholder, card_number = $cardNum, card_exp_year = $cardExpYear, card_exp_month = $cardExpMonth, card_csv = $cardCsv";
            }
            $statement = $connect->prepare($sql);
            if ($statement->execute()) {
                $code = 0;
                $msg = "Data " . $operation . " successfull.";
            } else {
                $code = 1;
                $msg = "Data " . $operation . " unsuccessfull.";
            }
            break;

        case 'ReadAll':
            $sql = "SELECT * from card_info";
            $statement = $connect->prepare($sql);
            if ($statement->execute()) {
                $result = $statement->fetchAll();
                $code = 0;
                $msg = "All Card data fetch successfull.";
                $data = $result;
            } else {
                $code = 1;
                $msg = "All card data fetch unsuccessfull.";
            }
            break;

        case 'ReadThisCard':
            $sql = "SELECT * from card_info WHERE card_holder = $cardholder AND card_number = $cardNum AND card_exp_year = $cardExpYear AND card_exp_month = $cardExpMonth AND card_csv = $cardCsv";
            break;

        case 'MakePayment':
            $cardholder = $_POST['Holder'];
            $cardNum = $_POST['CardNumber'];
            $cardExpYear = $_POST['ExpYear'];
            $cardExpMonth = $_POST['ExpMonth'];
            $cardCsv = $_POST['CSV'];
            $currentYear = date('Y');
            $currentMonth = date('m');

            if ($cardExpYear < $currentYear || ($cardExpYear == $currentYear && $cardExpMonth > $currentMonth)) {
                $code = 1;
                $msg = "Sorry! your card has already expired.";
            } else {
                // $data = array(
                //     "Card Holder" => $cardholder,
                //     "Card Number" => $cardNum,
                //     "Card Exp Year" => $cardExpYear,
                //     "Card Exp Month" => $cardExpMonth,
                //     "Card CSV" => $cardCsv,
                // );

                $sql = "SELECT * from card_info WHERE card_holder = '$cardholder' AND card_number = '$cardNum' AND card_exp_year = '$cardExpYear' AND card_exp_month = '$cardExpMonth' AND card_csv = '$cardCsv'";
                $statement = $connect->prepare($sql);
                if ($statement->execute()) {
                    $dataCount = $statement->rowCount();
                    $result = $statement->fetch();
                    if ($dataCount > 0) {
                        $cardId = $result['id'];
                        $paymentAmt = $_POST['PayAmount'];
                        $paymentReceiver = $_POST['PaymentReceiver'];
                        $pamentDate = date('Y-m-d H:i:s');
                        $txnId = md5($cardId . $paymentAmt . $pamentDate);

                        $sql = "INSERT INTO payments (card_id, transaction_id , payment_amount, payment_receiver, payment_date) VALUES ('$cardId', '$txnId', '$paymentAmt', '$paymentReceiver', '$pamentDate')";
                        $statement = $connect->prepare($sql);
                        if ($statement->execute()) {
                            $code = 0;
                            $msg = "Thank you. Payment successful.";
                            $data = array("TransactionId" => $txnId);
                        } else {
                            $code = 1;
                            $msg = "Something went wrong while making payment. Please try again after some time.";
                        }
                    } else {
                        $code = 1;
                        $msg = "Sorry! something went wrong. Please try again";
                    }
                } else {
                    $code = 1;
                    $msg = "Sorry! the card detail do not match. Please try again.";
                }
            }
            break;

        default:
            # code...
            break;
    }
}


$resObj = new \stdClass();
$resObj->code = $code;
$resObj->msg = $msg;
$resObj->data = $data;

$jsonResponse = json_encode($resObj);
echo $jsonResponse;
