<script src="<?php echo $basePath; ?>assets/js/jquery.min.js"></script>
<script src="<?php echo $basePath; ?>assets/lib/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo $basePath; ?>assets/lib/fontawesome/js/all.min.js"></script>
<script src="<?php echo $basePath; ?>assets/lib/toastr/toastr.min.js"></script>
<script src="<?php echo $basePath; ?>assets/js/script.js"></script>

<script>
    function GetCardType(fieldId, msgFieldId) {
        var cardType = "";
        var number = document.getElementById(fieldId).value;

        var visaReg = new RegExp("^4");
        var amexReg = new RegExp("^3[47]");
        var discoverReg = new RegExp("^(6011|622(12[6-9]|1[3-9][0-9]|[2-8][0-9]{2}|9[0-1][0-9]|92[0-5]|64[4-9])|65)");
        var dinersReg = new RegExp("^36");
        var electronReg = new RegExp("^(4026|417500|4508|4844|491(3|7))");

        var cardImg = "";

        if (number.match(visaReg) != null) {
            cardType = "Visa";
            cardImg = 'visa_card.svg';
        } else if (/^(5[1-5][0-9]{14}|2(22[1-9][0-9]{12}|2[3-9][0-9]{13}|[3-6][0-9]{14}|7[0-1][0-9]{13}|720[0-9]{12}))$/.test(number)) {
            cardType = "Mastercard";
            cardImg = 'master_card.svg';
        } else if (number.match(amexReg) != null) {
            cardType = "AMEX";
            cardImg = 'amex_card.svg';
        } else if (number.match(discoverReg) != null) {
            cardType = "Discover";
            cardImg = 'discover_card.svg';
        } else if (number.match(dinersReg) != null) {
            cardType = "Diners";
            cardImg = 'visa_card.svg';
        } else if (number.match(electronReg) != null) {
            cardType = "Visa Electron";
            cardImg = 'visa_card.svg';
        }

        if (cardImg != "") {
            cardImg = '<img class="" src="<?php echo $basePath; ?>assets/image/' + cardImg + '" title="' + cardType + '" style="height: 40px; width:fit-content;">';
        }
        document.getElementById(msgFieldId).innerHTML = cardImg + ' <span class="text-success">' + cardType + '</span>';
    }
</script>

<script>
    $('#card_holder_name').keypress(function() {
        showInpFieldMsg('card_holder_msg', '');
    });
    $('#card_number').keypress(function() {
        showInpFieldMsg('card_num_msg', '');
    });
    $('#card_csv').keypress(function() {
        showInpFieldMsg('card_csv_msg', '');
    });

    function makePayment() {
        toastr.clear();
        var cardHolder = $('#card_holder_name').val();
        var cardNumber = $('#card_number').val();
        var cardExpYear = $('#card_exp_year').val();
        var cardExpMonth = $('#card_exp_month').val();
        var cardCSV = $('#card_csv').val();
        var paymentAmount = $('#payment_amount').val();
        var paymentReceiver = $('#payment_receiver').val();

        var params = {
            "Operation": "MakePayment",
            "Holder": cardHolder,
            "CardNumber": cardNumber,
            "ExpYear": cardExpYear,
            "ExpMonth": cardExpMonth,
            "CSV": cardCSV,
            "PayAmount": paymentAmount,
            "PaymentReceiver": paymentReceiver,
        }

        if (cardHolder == "") {
            showInpFieldMsg('card_holder_msg', '* Card holder name is empty');
            return;
        } else if (cardNumber == "") {
            showInpFieldMsg('card_num_msg', '* Card number is empty');
            return;
        } else if (cardCSV == "") {
            showInpFieldMsg('card_csv_msg', '* Card csv is empty');
            return;
        }
        $('#card_csv_msg').html('');

        $.ajax({
            url: '<?php echo $basePath . "connection/db_oper/srvpay_card_crud.php"; ?>',
            method: "POST",
            data: params,
            success: function(data) {
                console.log(data);
                var jsonData = JSON.parse(data);
                var code = jsonData.code;
                var msg = jsonData.msg;
                if (code == 1) {
                    toastr.error(msg, "Error!", {
                        timeOut: 5000
                    });
                } else {
                    toastr.success(msg, "Success", {
                        timeOut: 4000
                    });
                    var txnId = jsonData.data.TransactionId;
                    window.location.href = '<?php echo $basePath . Route::$PAYMENT_SUCCESS; ?>?txnId=' + txnId;
                }
            }
        });
    }



    $('#redirectUrl').keypress(function() {
        showInpFieldMsg('redirect_url_holder_msg', '');
    });

    // operation types are Insert, Update, ReadAll, ReadThis.
    function insertUpdateConfig(operation) {
        toastr.clear();
        var urlId = $('#urlId').val();
        var redirectUrl = $('#redirectUrl').val();

        if (redirectUrl == "") {
            showInpFieldMsg('redirect_url_holder_msg', '* Redirection url is empty.');
            return;
        }
        if (!isValidHttpUrl(redirectUrl)){
            showInpFieldMsg('redirect_url_holder_msg', '* Please enter a valid redirection url address.');
            return;
        }
        $('#redirect_url_holder_msg').html('');

        var params = {
            "Operation": operation,
            "id": urlId,
            "url": redirectUrl
        }
        // console.log(params);

        $.ajax({
            url: '<?php echo $basePath . "connection/db_oper/srvpay_settings_crud.php"; ?>',
            method: "POST",
            data: params,
            success: function(data) {
                // console.log(data);
                var jsonData = JSON.parse(data);
                var code = jsonData.code;
                var msg = jsonData.msg;
                if (code == 1) {
                    toastr.error(msg, "Error!", {
                        timeOut: 5000
                    });
                } else {
                    toastr.success(msg, "Success", {
                        timeOut: 4000
                    });
                    location.reload();
                }
            }
        });
    }


    var prevInpField;

    function showInpFieldMsg(fieldId, msg) {
        $('#' + prevInpField).html('');
        if (fieldId != prevInpField) {
            $('#' + fieldId).html('<span class="text-danger">' + msg + '</span>');
            prevInpField = fieldId;
        }
    }

    function isValidHttpUrl(string) {
        let url;
        try {
            url = new URL(string);
        } catch (_) {
            return false;
        }

        return url.protocol === "http:" || url.protocol === "https:";
    }
</script>