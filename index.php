<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

require __DIR__ . '/vendor/autoload.php';

$botApi = new \TelegramBot\Api\BotApi(getenv('BOT_TOKEN'));


function redirect()
{
    $title = $_SERVER['REQUEST_METHOD'];
    $content = file_get_contents('php://input');
    $body = json_decode($content, true);
    if (is_null($body)) {
        $body = $content;
    }
    $url = "https://api.pushbullet.com/v2/pushes";
    $data = [
        'type' => 'note',
        'title' => $title,
        'body' => $body,
    ];
    $data = json_encode($data);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer o.AUOG5PIRZsy8ZsXOzAWjNnsYTpnseCn4",
        "Content-Type: application/json",
        'Content-Length: ' . strlen($data)
    ]);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
}

switch ($_GET['action']) {
    case 'invoice':
        $botApi->sendInvoice(
            67852056,
            'Normal invoice',
            'Description',
            'ni-1',
             getenv('PAYMENT_PROVIDER_TOKEN'),
            'ni-1-sp',
            'USD',
             [
                 ['label' => 'price1', 'amount' => 99999]
             ]
        );
        break;
    case 'invoice2':
        $botApi->sendInvoice(
            67852056,
            'title2',
            'description',
            'tes',
             getenv('PAYMENT_PROVIDER_TOKEN'),
            'test',
            'USD',
             [
                 ['label' => 'price1', 'amount' => 999999]
             ],
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            true
        );
        break;
    default:
        redirect();
        try {
            $bot = new \TelegramBot\Api\Client(getenv('BOT_TOKEN'));

            $bot->shippingQuery(function ($update) use ($bot, $botApi) {
                $callback = $update->getShippingQuery();
                $botApi->sendMessage($message->getChat()->getId(), 'pong!');
            });

            $bot->run();

        } catch (\TelegramBot\Api\Exception $e) {
            $e->getMessage();
        }
        break;
}
header("HTTP/1.0 200 OK");



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
