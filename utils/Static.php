<?php

class Route{
    public static $DB_LINK = "connection/database.php";
    public static $CSS_FILES = "assets/stylesheet.php";
    public static $JS_FILES = "assets/scripts.php";

    public static $CARD_PAYMENT = "index.php";
    public static $PAYMENT_SUCCESS = "payment_success.php";
    public static $PAYMENT_SUCCESS_REDIRECT_URL = "http://localhost/Sarvanam/BookingEngine/payment_complete.php";
}

class STR{
    public static $SITE_NAME = "SrvPay";
}

class MSG{
    public static $PAYMENT_SUCCESS = "Your payment is successful<br>Thank you for using SrvPay.";
    public static $PAYMENT_FAILED = "Sorry! Your payment was interrupted<br/>Please try again later.";
}

?>