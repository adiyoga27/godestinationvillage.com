<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class BotHelper  
{
    public static function sendTelegram($message)
    {
        $token = config('telegram.token');
        $chatid = config('telegram.chat_id');
        Http::post("https://api.telegram.org/bot$token/sendmessage?chat_id=$chatid&text=$message");
        return true;
    }
    public static function errorBot($activity, $message)
    {
        $name = Auth::user()->name ?? "";
        $role = Auth::user()->role_id ?? "";

        $date = date('d M Y H:i')." wita";
        $messages = "Godevi - Error Website \n\nDate: $date \nActivity : $activity \nUser : $name \nRole : $role \n\nMessage:$message";
        $token = config('telegram.token');
        $chatid = config('telegram.chat_id');
        Http::post("https://api.telegram.org/bot$token/sendmessage?chat_id=$chatid&text=$messages");
        return true;
    }
}
