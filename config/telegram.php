<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Telegram Bot
    |--------------------------------------------------------------------------
    |
    | Used by App\Helpers\BotHelper to send order & error notifications.
    |
    */

    'token' => env('TELEGRAM_TOKEN'),

    'chat_id' => env('TELEGRAM_CHATID', env('TELEGRAM_CHAT_ID')),

];