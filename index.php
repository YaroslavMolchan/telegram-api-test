<?php

require __DIR__ . '/vendor/autoload.php';

$bot = new \TelegramBot\Api\BotApi(getenv('BOT_TOKEN'));

//$bot->sendInvoice(
//    67852056,
//    'title',
//    'description',
//    'tes',
//    getenv('PAYMENT_PROVIDER_TOKEN'),
//    'test',
//    'USD',
//    [['label' => 'price1', 'amount' => 999999]]
//);

//$bot->sendInvoice(
//    67852056,
//    'title2',
//    'description',
//    'tes',
//    getenv('PAYMENT_PROVIDER_TOKEN'),
//    'test',
//    'USD',
//    [['label' => 'price1', 'amount' => 999999]],
//    null,
//    null,
//    null,
//    null,
//    null,
//    null,
//    null,
//    null,
//   true
//);

//$data = '
//{"update_id":63537796,
//"pre_checkout_query":{"id":"291422362830154760","from":{"id":67852056,"first_name":"Yaroslav","last_name":"Molchan","username":"YaroslavMolchan","language_code":"ru-RU"},"currency":"USD","total_amount":999999,"invoice_payload":"tes"}}
//';
//var_export(json_decode($data, true));
//echo '<br>';
//$data2 = '
//{"update_id":63537798,
//"shipping_query":{"id":"291422362776360151","from":{"id":67852056,"first_name":"Yaroslav","last_name":"Molchan","username":"YaroslavMolchan","language_code":"ru-RU"},"invoice_payload":"tes","shipping_address":{"country_code":"DZ","state":"Ndjd","city":"Ndjd","street_line1":"Dsfnfnf","street_line2":"Dbdnd","post_code":"23233"}}}
//';
//
//var_export(json_decode($data2, true));
