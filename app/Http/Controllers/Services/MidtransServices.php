<?php
namespace App\Http\Services;

use App\Helpers\BotHelper;
use GuzzleHttp\Client;

class MidtransServices 
{
   public function initServices()
   {  
       // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = '<your server key>';
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;
       $client = new Client();
       try {
         
        //    $res = $client->request('GET','http://localhost:8001/coba', []);
        //     $data = json_decode($res->getBody()->getContents());
        //     return $data;
       } catch (\Throwable $th) {
            BotHelper::errorBot('Init Services Midtrans', $th);
           return $th;
       }
   }
}


?>