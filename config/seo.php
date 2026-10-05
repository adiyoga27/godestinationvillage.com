<?php

return [

    /*
    | Host kanonik situs. Request ke host lain (staging, salinan lama)
    | diberi header X-Robots-Tag: noindex.
    */
    'primary_host' => env('SEO_PRIMARY_HOST', 'godevi.org'),

];
