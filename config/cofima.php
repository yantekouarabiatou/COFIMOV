<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Destinataires des notifications de demandes de transport
    |--------------------------------------------------------------------------
    |
    | Adresses (séparées par des virgules) qui reçoivent les notifications
    | par email liées aux demandes de frais de transport. Configurables via
    | .env pour pouvoir basculer facilement entre adresses de test et
    | adresses réelles sans toucher au code.
    |
    */

    'email_dg' => array_filter(explode(',', env('COFIMA_EMAIL_DG', ''))),

    'email_secretariat' => array_filter(explode(',', env('COFIMA_EMAIL_SECRETAIRE', ''))),

    'email_rh' => array_filter(explode(',', env('COFIMA_EMAIL_RH', ''))),

];
