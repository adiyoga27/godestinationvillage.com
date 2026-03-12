<?php
namespace App\Helpers;

use DateTime;
use DateTimeZone;

class HelperMidtrans {
  
     //Helper for MidTrans Payment Gateway
     public static function getAuthMidTrans($serverKey){
        $password = '';
        $authKey = base64_encode($serverKey.':'.$password);
        return $authKey;
    }

    public static function getTimestampMidtrans()
    {
        $timestamp = new DateTime();
        $timestamp->setTimezone(new DateTimeZone('Asia/Jakarta'));
        return $timestamp->format('Y-m-d H:i:s +0700');
    }

    public static function generateSignatureKeyMidtrans($orderId,$statusCode,$amount,$serverKey){
        $bodyRequest = $orderId."".$statusCode."".$amount."".$serverKey;
        $sha512 = hash("sha512", $bodyRequest);

        return $sha512;
    }
}