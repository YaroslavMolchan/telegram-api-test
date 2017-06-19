<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

function redirectRequest()
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

$botApi = new \TelegramBot\Api\BotApi(env('BOT_TOKEN'));

$app->get('/1', function () use ($app, $botApi) {
    $botApi->sendInvoice(
        67852056,
        'Normal invoice',
        'Description',
        'ni-1',
         env('PAYMENT_PROVIDER_TOKEN'),
        'ni-1-sp',
        'USD',
        [
            ['label' => 'price1', 'amount' => 99999]
        ]
    );
});

$app->get('/2', function () use ($app, $botApi) {
    $botApi->sendInvoice(
        67852056,
        'title2',
        'description',
        'tes',
         env('PAYMENT_PROVIDER_TOKEN'),
        'test',
        'USD',
        [
            ['label' => 'price1', 'amount' => 999999]
        ],
        true
    );
});

$app->post('/', function () use ($app, $botApi) {
    redirectRequest();

    try {
        $bot = new \TelegramBot\Api\Client(env('BOT_TOKEN'));

        $bot->shippingQuery(function ($query) use ($bot, $botApi) {
            // $botApi->answerShippingQuery($query->getId(), true, [
            //     [
            //         'id' => 'np',
            //         'title' => 'Nova Poshta',
            //         [
            //             [
            //                 'label' => 'Main',
            //                 'amount' => 4444
            //             ]
            //         ]
            //     ]
            // ]);
            $botApi->answerShippingQuery($query->getId(), false, [], 'Error message');
        });

        $bot->preCheckoutQuery(function ($query) use ($bot, $botApi) {
            $botApi->answerPreCheckoutQuery($query->getId(), true);
        });

        $bot->run();

    } catch (\TelegramBot\Api\Exception $e) {
        $e->getMessage();
    }

    return 'post';
});

$app->get('/', function () use ($app) {
    redirectRequest();

    return 'get';
});
